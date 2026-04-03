<?php

namespace App\Http\Controllers\Vendor;

use App\Models\VendorWithdrawMethodInfo;
use App\Services\VendorPaymentInformationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\PaymentInfoRequest;
use App\Contracts\Repositories\VendorWithdrawMethodInfoRepositoryInterface;
use App\Contracts\Repositories\WithdrawalMethodRepositoryInterface;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class VendorPaymentInfoController extends Controller
{
    public function __construct(
        private readonly VendorWithdrawMethodInfoRepositoryInterface $vendorWithdrawMethodInfoRepo,
        private readonly WithdrawalMethodRepositoryInterface         $withdrawalMethodRepo,
        private readonly VendorPaymentInformationService             $vendorPaymentInformationService,
    )
    {
    }

    public function index(Request $request): View
    {
        $withdrawalMethods = $this->withdrawalMethodRepo->getListWhere(filters: ['is_active' => 1], dataLimit: 10);
        $vendorWithdrawMethods = $this->vendorWithdrawMethodInfoRepo->getListWhere(
            orderBy: ['created_at' => 'desc'],
            searchValue: $request['search'],
            filters: ['user_id' => auth('seller')->id()],
            relations: ['withdraw_method'],
            dataLimit: 10
        )->appends($request->all());
        return view('vendor-views.shop.payment-information', [
            'withdrawalMethods' => $withdrawalMethods,
            'vendorWithdrawMethods' => $vendorWithdrawMethods,
        ]);
    }

    public function add(PaymentInfoRequest $request): JsonResponse
    {
        $fields = $this->withdrawalMethodRepo->getFirstWhere(params: ['id' => $request['withdraw_method_id']]);
        $data = $this->vendorPaymentInformationService->getAddData(request: $request, fields: $fields['method_fields']);
        $this->vendorWithdrawMethodInfoRepo->add(data: $data);
        if ($this->vendorWithdrawMethodInfoRepo->getListWhere(
                filters: ['user_id' => auth('seller')->id()],
                dataLimit: 'all'
            )->count() == 1) {
            $this->vendorWithdrawMethodInfoRepo->updateWhere(params: ['user_id' => auth('seller')->id()], data: ['is_default' => 1, 'is_active' => 1]);
        }
        updateSetupGuideCacheKey(key: 'payment_information', panel: 'vendor');

        return response()->json([
            'status' => true,
            'message' => translate('payment_info_add_successfully')
        ]);
    }

    public function update(PaymentInfoRequest $request): JsonResponse
    {
        $fields = $this->withdrawalMethodRepo->getFirstWhere(params: ['id' => $request['withdraw_method_id']]);
        $data = $this->vendorPaymentInformationService->getAddData(request: $request, fields: $fields['method_fields']);
        $this->vendorWithdrawMethodInfoRepo->updateOrInsert(params: ['id' => $request['id']], data: $data);
        updateSetupGuideCacheKey(key: 'payment_information', panel: 'vendor');
        return response()->json([
            'status' => true,
            'message' => translate('payment_info_updated_successfully')
        ]);
    }

    public function getDynamicPaymentInformationView(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $data = [];

        if (!empty($id)) {
            $method = $this->withdrawalMethodRepo->getFirstWhere(params: ['is_active' => 1, 'id' => $id]);
            if ($method) {
                $data = $method['method_fields'];
                return response()->json([
                    'status' => true,
                    'message' => translate('Withdrawal_method_found'),
                    'htmlView' => view('vendor-views.shop.dynamic-payment-information', compact('data'))->render(),
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'message' => translate('Withdrawal_method_not_found'),
            'htmlView' => view('vendor-views.shop.dynamic-payment-information', compact('data'))->render(),
        ], 200);
    }

    public function getUpdateView($id): JsonResponse
    {
        $vendorWithdrawMethods = $this->vendorWithdrawMethodInfoRepo->getListWhere(filters: ['id' => $id], relations: ['withdraw_method']);
        return response()->json([
            'status' => count($vendorWithdrawMethods) > 0,
            'data' => $vendorWithdrawMethods
        ]);
    }

    public function delete($id): RedirectResponse
    {
        $this->vendorWithdrawMethodInfoRepo->delete(params: ['id' => $id]);
        ToastMagic::success(translate("Payment_method_has_been_deleted"));
        return redirect()->back();
    }

    public function updateDefault(Request $request): RedirectResponse
    {
        $this->vendorWithdrawMethodInfoRepo->updateWhere(params: ['user_id' => auth('seller')->id()], data: ['is_default' => 0]);
        $this->vendorWithdrawMethodInfoRepo->updateWhere(
            params: ['id' => $request['id']],
            data: ['is_default' => 1, 'is_active' => 1]
        );
        ToastMagic::success(translate("Payment_method_set_as_default"));
        return redirect()->back();
    }


    public function updateStatus(Request $request): RedirectResponse
    {
        $this->vendorWithdrawMethodInfoRepo->updateWhere(params: ['id' => $request['id']], data: ['is_active' => $request['status'] ?? 0]);
        ToastMagic::success(translate("status_updated_successfully"));
        return redirect()->back();
    }
}
