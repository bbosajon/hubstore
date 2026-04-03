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

class MtnPaymentController extends Controller
{
    use Processor;

    private PaymentRequest $payment;
    private User $user;
    private $config_values;
    private $subscription_key;
    private $api_user;
    private $api_key;
    private $api_url;
    private $callback_host;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('mtn', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values);
            $this->api_url = 'https://api.mtn.com';
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values);
            $this->api_url = 'https://sandbox-api.mtn.com';
        }

        if (isset($this->config_values)) {
            $this->subscription_key = $this->config_values->subscription_key ?? env('MTN_SUBSCRIPTION_KEY', '');
            $this->api_user = $this->config_values->api_user ?? env('MTN_API_USER', '');
            $this->api_key = $this->config_values->api_key ?? env('MTN_API_KEY', '');
            $this->callback_host = $this->config_values->callback_host ?? env('MTN_CALLBACK_HOST', '');
        }

        $this->payment = $payment;
        $this->user = $user;
    }

    /**
     * Initialize MTN payment
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
            $ref = 'MTN' . time() . rand(1000, 9999);
            
            $payload = [
                'amount' => $data['payment_amount'] ?? 0,
                'currency' => $data['currency'] ?? 'XOF',
                'externalId' => $data['id'],
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $payer['phone'] ?? ''
                ],
                'payerMessage' => 'Payment for Order',
                'payeeNote' => 'Payment received'
            ];

            $curl = curl_init();

            $base64Auth = base64_encode($this->api_user . ':' . $this->api_key);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->api_url . '/v1/RequestToPay',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic " . $base64Auth,
                    "X-Reference-Id: " . $ref,
                    "X-Target-Environment: production",
                    "Ocp-Apim-Subscription-Key: " . $this->subscription_key,
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpcode == 202) {
                // Store the reference for later verification
                $this->payment::where(['id' => $data['id']])->update([
                    'transaction_id' => $ref
                ]);

                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_200, ['reference' => $ref]), 200);
            }

            $responseData = json_decode($response, true);
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204, $responseData ?? $response), 200);
        } catch (\Exception $e) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, ['error' => $e->getMessage()]), 400);
        }
    }

    /**
     * Handle payment callback from MTN
     *
     * @param Request $request
     * @return Redirector|RedirectResponse
     */
    public function handleGatewayCallback(Request $request): Redirector|RedirectResponse
    {
        try {
            $paymentId = $request->query('payment_id');
            $referenceId = $request->query('reference_id');
            $status = $request->query('status');

            if ($status === 'SUCCESSFUL') {
                $this->payment::where(['id' => $paymentId])->update([
                    'payment_method' => 'mtn',
                    'is_paid' => 1,
                    'transaction_id' => $referenceId,
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
     * Check payment status
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkStatus(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reference_id' => 'required|string',
                'payment_id' => 'required|uuid'
            ]);

            if ($validator->fails()) {
                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
            }

            $referenceId = $request->input('reference_id');
            $base64Auth = base64_encode($this->api_user . ':' . $this->api_key);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->api_url . '/v1/RequestToPayTransactionStatus/' . $referenceId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic " . $base64Auth,
                    "X-Target-Environment: production",
                    "Ocp-Apim-Subscription-Key: " . $this->subscription_key
                ),
            ));

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            $responseData = json_decode($response, true);

            if ($httpcode == 200 && isset($responseData['status']) && $responseData['status'] === 'SUCCESSFUL') {
                $this->payment::where(['id' => $request->input('payment_id')])->update([
                    'payment_method' => 'mtn',
                    'is_paid' => 1,
                ]);

                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_200, $responseData), 200);
            }

            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_200, $responseData ?? ['message' => 'Payment pending or failed']), 200);
        } catch (\Exception $e) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, ['error' => $e->getMessage()]), 400);
        }
    }
}
