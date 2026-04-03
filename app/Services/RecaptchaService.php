<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Validation\ValidationException;

class RecaptchaService
{
    public static function verify(string $token, ?string $action = null): bool
    {
        $secretKey = getWebConfig(name: 'recaptcha')['secret_key'];

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        $data = $response->json();
        if (!($data['success'] ?? false)) {
            ToastMagic::error(translate('ReCAPTCHA_Failed'));
            return false;
        }

        if (($data['score'] ?? 0) < 0.5) {
            ToastMagic::error(translate('ReCAPTCHA_Score_Too_Low_Please_Try_Again'));
            return false;
        }
        if ($action !== null && ($data['action'] ?? '') !== $action) {
            ToastMagic::error(translate('ReCAPTCHA_Action_Invalid'));
            return false;
        }

        return true;
    }

    public static function verificationStatus(object|array $request, string $session, ?string $action = 'default', ?bool $firebase = false): array
    {
        $firebaseOTPVerification = getWebConfig(name: 'firebase_otp_verification') ?? [];
        if ($firebase && $firebaseOTPVerification && $firebaseOTPVerification['status']) {
            if (empty($request['g-recaptcha-response'])) {
                return [
                    'status' => false,
                    'message' => translate('ReCAPTCHA_Failed'),
                ];
            } else {
                return [
                    'status' => true,
                    'message' => translate('ReCAPTCHA_verification_success.'),
                ];
            }
        }

        $recaptcha = getWebConfig(name: 'recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1 && !$request['default_captcha_value']) {
            try {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) use ($action) {
                            if (empty($value)) {
                                $fail(translate('ReCAPTCHA_verification_failed.'));
                                return;
                            }
                            if (!RecaptchaService::verify(token: $value, action: $action)) {
                                $fail(translate('ReCAPTCHA_verification_failed.'));
                                return;
                            }
                        },
                    ],
                ]);
            } catch (ValidationException $e) {
                return [
                    'status' => false,
                    'message' => $e->validator->errors()->first('g-recaptcha-response'),
                ];
            }
        } else if (strtolower(session($session)) != strtolower($request['default_captcha_value'])) {
            return [
                'status' => false,
                'message' => translate('ReCAPTCHA_failed.'),
            ];
        }

        if (isset($request['default_captcha_value']) && strtolower(session($session)) == strtolower($request['default_captcha_value'])) {
            session()->forget($session);
        }

        session()->forget($session);
        return [
            'status' => true,
            'message' => translate('ReCAPTCHA_verification_success.'),
        ];
    }
}


?>
