<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\LoyaltyPointTransactionRepositoryInterface;
use App\Enums\WebConfigKey;
use App\Exports\CustomerTransactionsExport;
use App\Http\Controllers\BaseController;
use App\Traits\PaginatorTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomerLoyaltyController extends BaseController
{
    use PaginatorTrait;

    public function __construct(
        private readonly CustomerRepositoryInterface                    $customerRepo,
        private readonly LoyaltyPointTransactionRepositoryInterface     $loyaltyPointTransactionRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, ?string $type = null): View
    {
        $from = null;
        $to = null;

        if ($request->filled('date')) {
            $dates = explode(' - ', $request->date);
            $from = \Carbon\Carbon::createFromFormat('d M Y', $dates[0])->startOfDay();
            $to   = \Carbon\Carbon::createFromFormat('d M Y', $dates[1])->endOfDay();
        }
        $filters = [
            'from' => $from,
            'to' => $to,
            'transaction_type' => $request->transaction_type,
            'customer_id' => $request->customer_id,
        ];
        $data = $this->loyaltyPointTransactionRepo->getListWhereSelect(filters: $filters, dataLimit: 'all');
        $transactions = $this->loyaltyPointTransactionRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue: $request['searchValue'],
            filters: $filters,
            relations: ['user'],
            dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT)
        );
        $customer = "all";
        if (isset($request['customer_id']) && $request['customer_id'] != 'all' && !is_null($request['customer_id']) && $request->has('customer_id')) {
            $customer = $this->customerRepo->getFirstWhere(params: ['id' => $request['customer_id']]);
        }
        $customers = $this->customerRepo->getCustomerNameList(request: $request, dataLimit: 'all')->filter(function ($customer) {
                return $customer['id'] != 0;
            })->values()->toArray();
        array_unshift($customers, ['id' => 'all', 'text' => translate('All_Customer')]);
        return view('admin-views.customer.loyalty.report', compact('data', 'transactions', 'customer','customers'));
    }

    public function exportList(Request $request): BinaryFileResponse
    {
        $filters = [
            'to' => $request['to'],
            'from' => $request['from'],
            'transaction_type' => $request['transaction_type'],
            'customer_id' => $request['customer_id'],
        ];
        $summary = $this->loyaltyPointTransactionRepo->getListWhereSelect(filters:$filters, dataLimit:'all');
        $transactions = $this->loyaltyPointTransactionRepo->getListWhere(orderBy: ['id'=>'desc'], filters:$filters, dataLimit:'all');
        $data = [
            'type'=>'loyalty',
            'transactions'=> $transactions,
            'credit' => $summary[0]->total_credit,
            'debit' => $summary[0]->total_debit,
            'balance' => $summary[0]->total_credit - $summary[0]->total_debit,
            'transaction_type' =>$request['transaction_type'],
            'to' => $request['to'],
            'from' => $request['from'],
            'customer' => $request['customer_id'] ? $this->customerRepo->getFirstWhere(params:['id'=>$request['customer_id']]) : "all_customers",
        ];
        return Excel::download(new CustomerTransactionsExport($data), 'Loyalty-Transactions-Report.xlsx');
    }

}
