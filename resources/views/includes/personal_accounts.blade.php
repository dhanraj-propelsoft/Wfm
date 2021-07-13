@section('sidebar')
@parent
	<li class="header"><span>My Accounts</span></li>
      <li><a data-link="dashboard" href="{{ route('user.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
      <li><a data-link="account" href="{{ route('account.index') }}"> <i class="fa icon-basic-todolist-pencil"></i><span>Cash and Bank</span></a></li>
      <!-- <li><a data-link="people" href="{{ route('personal_people.index') }}"> <i class="fa fa-user-o"></i><span>My People</span></a></li> -->
      <li><a data-link="category" href="{{ route('personal_category.index') }}"> <i class="fa fa-sitemap"></i><span>Categories</span></a></li>
      <li><a data-link="bills" href="{{ route('personal_bills.index') }}"><i class="fa icon-ecommerce-receipt"></i><span>Inward Vouchers</span></a></li>
      <li><a data-link="transaction" href="{{ route('personal_transaction.index') }}"><i class="fa icon-music-shuffle-button"></i><span>Transactions</span></a></li>
      <li><a data-link="transact/payment" href="{{ route('personal_cash_transaction.index', ['payment']) }}"><i class="fa icon-ecommerce-wallet"></i><span>Payables</span></a></li>
      <li><a data-link="transact/receipt" href="{{ route('personal_cash_transaction.index', ['receipt']) }}"><i class="fa icon-ecommerce-money"></i><span>Receivables</span></a></li>
@stop

@section('dom_links')
@parent
@stop