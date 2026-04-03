<?php

use App\Events\AddFundToWalletEvent;
use App\Models\AdminWallet;
use App\Models\Order;
use App\Models\OrderEditHistory;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Utils\Convert;
use App\Utils\CustomerManager;
use App\Utils\OrderManager;
use Illuminate\Support\Facades\DB;
use Modules\TaxModule\app\Models\SystemTaxSetup;
use Modules\TaxModule\app\Models\Tax;

if (!function_exists('digital_payment_success')) {
    function digital_payment_success($paymentData): void
    {
        if (isset($paymentData) && $paymentData['is_paid'] == 1) {
            $additionalData = json_decode($paymentData['additional_data'], true);

            $addCustomer = null;
            $newCustomerInfo = $additionalData['new_customer_info'] ?? null;

            if ($newCustomerInfo) {
                $checkCustomer = User::where(['email' => $newCustomerInfo['email']])->orWhere(['phone' => $newCustomerInfo['phone']])->first();
                if (!$checkCustomer) {
                    $addCustomer = User::create([
                        'name' => $newCustomerInfo['name'],
                        'f_name' => $newCustomerInfo['name'],
                        'l_name' => $newCustomerInfo['l_name'],
                        'email' => $newCustomerInfo['email'],
                        'phone' => $newCustomerInfo['phone'],
                        'is_active' => 1,
                        'password' => bcrypt($newCustomerInfo['password']),
                        'referral_code' => $newCustomerInfo['referral_code'],
                    ]);
                } else {
                    $addCustomer = $checkCustomer;
                }
                session()->put('newRegisterCustomerInfo', $addCustomer);

                if ($additionalData['is_guest']) {
                    $addressId = $additionalData['address_id'] ?? null;
                    $billingAddressId = $additionalData['billing_address_id'] ?? null;
                    ShippingAddress::where(['customer_id' => $additionalData['customer_id'], 'is_guest' => 1, 'id' => $addressId])
                        ->update(['customer_id' => $addCustomer['id'], 'is_guest' => 0]);
                    ShippingAddress::where(['customer_id' => $additionalData['customer_id'], 'is_guest' => 1, 'id' => $billingAddressId])
                        ->update(['customer_id' => $addCustomer['id'], 'is_guest' => 0]);
                }
            }

            session()->put('payment_mode', $additionalData['payment_mode'] ?? 'web');

            if (isset($additionalData['is_guest']) && $additionalData['is_guest'] == 0) {
                $user = User::where(['id' => $additionalData['customer_id']])->first();
                request()->merge(['user' => $user]);
            }

            $requestObj = [
                'customer_id' => $additionalData['customer_id'],
                'is_guest' => $additionalData['is_guest'] ?? 0,
                'guest_id' => ($additionalData['is_guest_in_order'] ?? 0) ? $additionalData['customer_id'] : null,
                'payment_request_from' => $additionalData['payment_mode'] ?? 'web',
            ];
            request()->merge($requestObj);

            $orderIds = OrderManager::generateOrder(data: [
                'is_guest' => $additionalData['is_guest_in_order'] ?? 0,
                'guest_id' => ($additionalData['is_guest_in_order'] ?? 0) ? $additionalData['customer_id'] : null,
                'customer_id' => $additionalData['customer_id'],
                'order_status' => 'confirmed',
                'payment_method' => $paymentData['payment_method'],
                'payment_status' => 'paid',
                'transaction_ref' => $paymentData['transaction_id'],
                'new_customer_id' => $addCustomer ? $addCustomer['id'] : ($additionalData['new_customer_id'] ?? null),
                'newCustomerRegister' => $addCustomer,

                'order_note' => $additionalData['order_note'],
                'coupon_code' => $additionalData['coupon_code'] ?? null,
                'address_id' => $additionalData['address_id'] ?? null,
                'billing_address_id' => $additionalData['billing_address_id'] ?? null,
                'requestObj' => $requestObj,
            ]);

            foreach ($orderIds as $orderId) {
                OrderManager::generateReferBonusForFirstOrder(orderId: $orderId);
            }
        }
    }
}

if (!function_exists('digital_payment_fail')) {
    function digital_payment_fail($payment_data)
    {

    }
}
if (!function_exists('customer_order_edit_pay_due_amount_success')) {
    /**
     * @throws Throwable
     */
    function customer_order_edit_pay_due_amount_success($payment_data): void
    {
        if (!isset($payment_data) || ($payment_data['is_paid'] ?? 0) != 1) {
            return;
        }
        $additionalData = json_decode($payment_data['additional_data'] ?? '{}', true);
        if (empty($additionalData['order_id'])) {
            return;
        }
        $order = Order::where('id', $additionalData['order_id'])->first();
        DB::transaction(function () use ($additionalData, $payment_data, $order) {
            $order->update([
                'edit_due_amount' => 0,
                'order_amount' => $additionalData['order_amount'] ?? 0,
                'payment_status' => 'paid',
            ]);
            OrderEditHistory::where('order_id', $additionalData['order_id'])
                ->latest('id')
                ->limit(1)
                ->update([
                    'order_due_payment_status' => 'paid',
                    'order_due_payment_method' => $payment_data['payment_method'] ?? null,
                    'order_due_transaction_ref' => $payment_data['transaction_id'] ?? '',
                    'order_due_payment_note' => $payment_data['order_due_payment_note'] ?? '',
                ]);
        });
        OrderManager::sendPushNotificationAfterDuePayment(order: $order);
        AdminWallet::where(['admin_id' => 1])->increment('pending_amount', $additionalData['order_amount']);
    }
}
if (!function_exists('customer_order_edit_pay_due_amount_failed')) {
    function customer_order_edit_pay_due_amount_failed($payment_data): void
    {
        if (!isset($payment_data)) {
            return;
        }
        $additionalData = json_decode($payment_data['additional_data'] ?? '', true);
        if (empty($additionalData['order_id'])) {
            return;
        }
        OrderEditHistory::where('order_id', $additionalData['order_id'])
            ->latest('id')
            ->limit(1)
            ->update([
                'order_due_payment_status' => 'unpaid',
                'order_due_transaction_ref' => $payment_data['transaction_id'] ?? '',
            ]);
    }
}

// Add Fund To Wallet - Success
if (!function_exists('add_fund_to_wallet_success')) {
    function add_fund_to_wallet_success($payment_data): void
    {
        if (isset($payment_data) && $payment_data['is_paid'] == 1) {
            $additional_data = json_decode($payment_data['additional_data'], true);
            session()->put('payment_mode', ($additional_data['payment_mode'] ?? 'web'));

            $paymentAmount = Convert::usdPaymentModule(floatval($payment_data['payment_amount']), $payment_data['currency_code']);
            $paymentAmount = usdToDefaultCurrency(amount: $paymentAmount);
            $wallet_transaction = CustomerManager::create_wallet_transaction($payment_data['payer_id'], $paymentAmount, 'add_fund', 'add_funds_to_wallet', $payment_data);

            if ($wallet_transaction) {
                try {
                    $data = [
                        'walletTransaction' => $wallet_transaction,
                        'userName' => $wallet_transaction->user['f_name'],
                        'userType' => 'customer',
                        'templateName' => 'add-fund-to-wallet',
                        'subject' => translate('add_fund_to_wallet'),
                        'title' => translate('add_fund_to_wallet'),
                    ];
                    event(new AddFundToWalletEvent(email: $wallet_transaction->user['email'], data: $data));
                } catch (Exception $ex) {
                    info($ex);
                }
            }
        }
    }
}

// Add Fund To Wallet - Fail
if (!function_exists('add_fund_to_wallet_fail')) {
    function add_fund_to_wallet_fail($payment_data)
    {

    }
}

if (!function_exists('config_settings')) {
    function config_settings($key, $settings_type)
    {
        try {
            $config = DB::table('addon_settings')->where('key_name', $key)
                ->where('settings_type', $settings_type)->first();
        } catch (Exception $exception) {
            return null;
        }
        return (isset($config)) ? $config : null;
    }
}

if (!function_exists('getCheckAddonPublishedStatus')) {
    function getCheckAddonPublishedStatus(string $moduleName): int
    {
        try {
            if (file_exists(base_path("Modules/{$moduleName}/Addon/info.php"))) {
                $full_data = include(base_path("Modules/{$moduleName}/Addon/info.php"));
                return $full_data['is_published'] == 1 ? 1 : 0;
            }
        } catch (Exception $exception) {
        }
        return 0;
    }
}

if (!function_exists('getTaxModuleSystemTypesConfig')) {
    function getTaxModuleSystemTypesConfig($getTaxVatList = true, $tax_payer = 'vendor'): array
    {
        $cacheKey = "tax_system_type_{$tax_payer}_" . ($getTaxVatList ? 'with_vat' : 'no_vat');

        $cacheKeys = Cache::get('cache_tax_system_types_and_config', []);
        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put('cache_tax_system_types_and_config', $cacheKeys, 60 * 60 * 24 * 7);
        }

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($getTaxVatList, $tax_payer) {
            if (getCheckAddonPublishedStatus('TaxModule')) {
                $systemTaxVat = SystemTaxSetup::where('is_active', 1)
                    ->with(['additionalData' => function ($query) {
                        return $query->where('is_active', 1);
                    }])
                    ->where('tax_payer', $tax_payer)
                    ->where('is_default', 1)
                    ->first();

                if (!$systemTaxVat) {
                    $systemTaxVat = SystemTaxSetup::create([
                        'tax_type' => 'order_wise',
                        'country_code' => null,
                        'tax_payer' => 'vendor',
                        'is_default' => true,
                        'is_active' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    return [
                        'SystemTaxVat' => $systemTaxVat ?? null,
                        'SystemTaxVatType' => $systemTaxVat?->tax_type ?? 'order_wise',
                        'is_included' => $systemTaxVat?->is_included ?? 0,
                        'productWiseTax' => false,
                        'categoryWiseTax' => false,
                        'taxVats' => []
                    ];
                }

                if ($getTaxVatList) {
                    $taxVats = Tax::where('is_active', 1)->where('is_default', 1)->get();
                }

                if ($systemTaxVat?->tax_type == 'product_wise') {
                    $productWiseTax = true;
                } elseif ($systemTaxVat?->tax_type == 'category_wise') {
                    $categoryWiseTax = true;
                }
            }

            return [
                'SystemTaxVat' => $systemTaxVat ?? null,
                'SystemTaxVatType' => $systemTaxVat?->tax_type ?? 'order_wise',
                'is_included' => $systemTaxVat?->is_included ?? 0,
                'productWiseTax' => $productWiseTax ?? false,
                'categoryWiseTax' => $categoryWiseTax ?? false,
                'taxVats' => $taxVats ?? []
            ];
        });
    }
}

if (!function_exists('getModuleDynamicAsset')) {
    function getModuleDynamicAsset(string $path): string
    {
        if (getModuleAssetsProcessingDirectory() == 'public') {
            $position = strpos($path, 'public/');
            $result = $path;
            if ($position === 0) {
                $result = preg_replace('/public/', '', $path, 1);
            }
        } else {
            $result = $path;
        }
        return asset($result);
    }
}

if (!function_exists('getModuleDynamicStorage')) {
    function getModuleDynamicStorage(string $path): string
    {
        if (getModuleAssetsProcessingDirectory() == 'public') {
            $result = str_replace('storage/app/public', 'storage', $path);
        } else {
            $result = $path;
        }
        return asset($result);
    }
}

if (!function_exists('getModuleAssetsProcessingDirectory')) {
    function getModuleAssetsProcessingDirectory(): string
    {
        $cacheKey = 'SYSTEM_DOMAIN_POINTED_DIRECTORY_' . md5($_SERVER['SCRIPT_FILENAME']);
        return Cache::rememberForever($cacheKey, function () {
            $scriptPath = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
            $basePath = realpath(base_path());
            $publicPath = realpath(public_path());

            if ($scriptPath === $publicPath) {
                return 'public';
            } elseif ($scriptPath === $basePath) {
                return 'root';
            }
            return 'unknown';
        });
    }
}
