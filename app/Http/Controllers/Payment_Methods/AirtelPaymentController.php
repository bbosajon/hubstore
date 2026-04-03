<?php

namespace App\Http\Controllers\Payment_Methods;

use App\Models\PaymentRequest;
use App\Models\User;
use App\Traits\Processor;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AirtelPaymentController extends Controller
{
    use Processor;

    private PaymentRequest $payment;
    private User $user;
    private $config_values;
    private $client_id;
    private $client_secret;
    private $api_url;
    private $token_url;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('airtel', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values);
            $this->api_url = 'https://api.airtel.africa';
            $this->token_url = 'https://auth.airtel.africa/oauth2/token';
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values);
            $this->api_url = 'https://sandbox-api.airtel.africa';
            $this->token_url = 'https://sandbox-auth.airtel.africa/oauth2/token';
        }

        if (isset($this->config_values)) {
            $this->client_id = $this->config_values->client_id ?? env('AIRTEL_CLIENT_ID', '');
            $this->client_secret = $this->config_values->client_secret ?? env('AIRTEL_CLIENT_SECRET', '');
        }

        $this->payment = $payment;
        $this->user = $user;
    }

    /**
     * Get access token from Airtel API
     *
     * @return array
     */
    private function getAccessToken(): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->token_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'client_credentials'
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true) ?? [];
    }

    /**
     * Initialize payment
     *
     * @param Request $request
     * @return JsonResponse|Redirector|RedirectResponse
     */
    public function index(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $data = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }

        $payer = json_decode($data['payer_information'], true);

        try {
            $tokenResponse = $this->getAccessToken();

            if (!isset($tokenResponse['access_token'])) {
                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, ['error' => 'Failed to obtain access token']), 400);
            }

            $accessToken = $tokenResponse['access_token'];

            $payload = [
                'reference' => 'REF' . time() . rand(1000, 9999),
                'subscriber' => [
                    'email' => $payer['email'] ?? 'customer@example.com',
                    'msisdn' => $payer['phone'] ?? ''
                ],
                'transaction' => [
                    'amount' => $data['payment_amount'] ?? 0,
                    'currency' => $data['currency'] ?? 'XOF',
                    'id' => $data['id']
                ],
                'merchant' => [
                    'consumerId' => $this->config_values->merchant_id ?? '',
                    'serviceId' => $this->config_values->service_id ?? ''
                ]
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->api_url . '/standard/v1/payments',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $accessToken,
                    "Content-Type: application/json",
                    "Accept: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            $responseData = json_decode($response, true);

            if ($httpcode == 201 && isset($responseData['data']['transaction']['id'])) {
                // Store the Airtel transaction ID
                $this->payment::where(['id' => $data['id']])->update([
                    'transaction_id' => $responseData['data']['transaction']['id']
                ]);

                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_200, $responseData), 200);
            }

            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204, $responseData), 200);
        } catch (\Exception $e) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, ['error' => $e->getMessage()]), 400);
        }
    }

    /**
     * Handle payment callback from Airtel
     *
     * @param Request $request
     * @return Redirector|RedirectResponse
     */
    public function handleGatewayCallback(Request $request): Redirector|RedirectResponse
    {
        try {
            $paymentId = $request->query('payment_id');
            $transactionId = $request->query('transaction_id');
            $status = $request->query('status');

            if ($status === 'SUCCESS') {
                $this->payment::where(['id' => $paymentId])->update([
                    'payment_method' => 'airtel',
                    'is_paid' => 1,
                    'transaction_id' => $transactionId,
                ]);

                $data = $this->payment::where(['id' => $paymentId])->first();
                if (isset($data) && function_exists($data->success_hook)) {
                    call_user_func($data->success_hook, $data);
                }
                return $this->payment_response($data, 'success');
            }

            $payment_data = $this->payment::where(['id' => $paymentId])->first();
            if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
                call_user_func($payment_data->failure_hook, $payment_data);
            }
            return $this->payment_response($payment_data, 'fail');
        } catch (\Exception $e) {
            return redirect()->route('payment-fail', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Cancel payment
     *
     * @param Request $request
     * @return Application|JsonResponse|Redirector|RedirectResponse
     */
    public function cancel(Request $request): Application|JsonResponse|Redirector|RedirectResponse
    {
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'fail');
    }

    /**
     * Verify transaction status with Airtel
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyTransaction(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'transaction_id' => 'required|string',
                'payment_id' => 'required|uuid'
            ]);

            if ($validator->fails()) {
                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
            }

            $tokenResponse = $this->getAccessToken();
            if (!isset($tokenResponse['access_token'])) {
                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, ['error' => 'Failed to obtain access token']), 400);
            }

            $accessToken = $tokenResponse['access_token'];
            $transactionId = $request->input('transaction_id');

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->api_url . '/standard/v1/payments/' . $transactionId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $accessToken,
                    "Accept: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            $responseData = json_decode($response, true);

            if ($httpcode == 200) {
                $transactionStatus = $responseData['data']['transaction']['status'] ?? null;

                if ($transactionStatus === 'TS') {
                    $this->payment::where(['id' => $request->input('payment_id')])->update([
                        'payment_method' => 'airtel',
                        'is_paid' => 1,
                    ]);
                }

                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_200, $responseData), 200);
            }

            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204, $responseData), 200);
        } catch (\Exception $e) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, ['error' => $e->getMessage()]), 400);
        }
    }
}
