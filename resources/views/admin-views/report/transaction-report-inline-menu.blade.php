<div class="inline-page-menu my-4">
    <ul class="nav nav-pills nav--tab gap-3">
        <li class="nav-item"><a class="nav-link {{ Request::is('admin/transaction/order-transaction-list') ?'active':'' }}" href="{{route('admin.transaction.order-transaction-list')}}">{{translate('order_Transactions')}}</a></li>
        <li class="nav-item"><a class="nav-link {{ Request::is('admin/transaction/expense-transaction-list') ?'active':'' }}" href="{{route('admin.transaction.expense-transaction-list')}}">{{translate('expense_Transactions')}}</a></li>
        <li class="nav-item"><a class="nav-link {{ Request::is('admin/report/transaction/refund-transaction-list') ?'active':'' }}" href="{{ route('admin.report.transaction.refund-transaction-list') }}">{{translate('refund_Transactions')}}</a></li>
    </ul>
</div>
