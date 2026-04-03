<?php

namespace App\Http\Controllers\RestAPI\v3\seller\auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Models\Shop;
use App\Utils\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)], 403);
        }

        $data = [
            'email' => $request['email'],
            'password' => $request['password']
        ];

        $seller = Seller::where(['email' => $request['email']])->first();

        if ($seller && isset($seller?->shop) && empty($seller?->shop?->setup_guide_app)) {
            Shop::where(['seller_id' => $seller['id']])->update([
                'setup_guide_app' => json_encode([
                    'shop_setup' => 0,
                    'add_new_product' => Product::where(['added_by' => 'seller', 'user_id' => $seller['id']])->count() > 0 ? 1 : 0,
                    'order_setup' => 0,
                    'withdraw_setup' => 0,
                    'payment_information' => 0,
                ]),
            ]);
        }

        if (isset($seller) && $seller['status'] == 'approved' && auth('seller')->attempt($data)) {
            $token = Str::random(50);
            Seller::where(['id' => auth('seller')->id()])->update(['auth_token' => $token]);
            if (SellerWallet::where('seller_id', $seller['id'])->first() == false) {
                DB::table('seller_wallets')->insert([
                    'seller_id' => $seller['id'],
                    'withdrawn' => 0,
                    'commission_given' => 0,
                    'total_earning' => 0,
                    'pending_withdraw' => 0,
                    'delivery_charge_earned' => 0,
                    'collected_cash' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            return response()->json(['token' => $token], 200);
        }elseif(isset($seller) && $seller['status'] == 'pending'){
            $errors = [];
            $errors[] = ['code' => 'auth-001', 'message' => translate('your_account_is_in_review_process').'. '.translate('please_wait_for_admin_approval')];
            return response()->json([
                'errors' => $errors,
                'loginStatus' => 'pending'
            ], 401);
        } else {
            $errors = [];
            $errors[] = ['code' => 'auth-001', 'message' => translate('invalid_credential')];
            return response()->json([
                'errors' => $errors,
                'loginStatus' => 'unauthorized'
            ], 401);
        }
    }
}
