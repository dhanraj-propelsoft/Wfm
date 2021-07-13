@section('sidebar')
@parent
	<li class="header"><span>Overview</span></li>
      <li><a data-link="dashboard" href="{{ route('user.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
      <li><a data-link="account" href="{{ route('account.index') }}"> <i class="fa icon-basic-todolist-pencil"></i><span>My Accounts</span></a></li>
      <li><a data-link="people" href="{{ route('personal_people.index') }}"> <i class="fa fa-user-o"></i><span>My Circle People</span></a></li>
      <li><a data-link="category" href="{{ route('personal_category.index') }}"> <i class="fa fa-sitemap"></i><span>Categories</span></a></li>
      <li><a data-link="bills" href="{{ route('personal_bills.index') }}"><i class="fa icon-ecommerce-receipt"></i><span>Bills</span></a></li>
      <li><a data-link="transaction" href="{{ route('personal_transaction.index') }}"><i class="fa icon-music-shuffle-button"></i><span>Transactions</span></a></li>
@stop

@section('dom_links')
@parent
@stop