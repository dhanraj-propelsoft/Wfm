<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="{{ URL::asset('assets/layout/images/fav_icon.png') }}" type="image/x-icon">

<link rel="icon" href="{{ URL::asset('assets/layout/images/fav_icon.png') }}" type="image/x-icon">

<meta content="" name="description" />

<meta content="" name="author" />

<noscript>

<meta http-equiv="refresh" content="0; URL={{url('script')}}">

</noscript>



<!-- CSRF Token -->

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('head_links')

<title>{{ config('app.name', 'Laravel') }}</title>



<!-- Styles -->

@if(app()->environment() == "production")

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">



<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker.min.css"/>



<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css"/>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/css/bootstrap2/bootstrap-switch.min.css"/>

@elseif(app()->environment() == "local")

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"/>



<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"/>



@endif





<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}"/>



<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/theme.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/linecon.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/background.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/stylesheet.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

@show

<style>
	.active{
	  background-color: white;
	
	}
	.bgcolor{
		background-color:transparent !important;
	}
	#panelhead{
	margin-top: 2px;	
	width:450px;
	height:55px;
	border:1px solid #000; 
	background-color: #ecf07f;
	color: red;
	font-weight:bold;
	padding: 5px;
	display:none;
	}
	#position{
	position: absolute;
	margin-left: auto;
	margin-right:auto; 
	}

	.overlay {
    background: #e9e9e9;
    display: none;
    position: absolute;
    text-align: center;
    vertical-align: middle;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: 0.75;
	z-index:20;
	width: 100%; /* Full width (cover the whole page) */
  height: 100%; /* Full height (cover the whole page) */
  min-height: 1500px;
  overflow-y: scroll;
  margin-top:30px
}


</style>

</head>



<body>



@include('modals.crud_modal')

@include('modals.crud_full_modal')
@include('modals.crud_modal_small')
@include('modals.print_modal')

@include('modals.pdf_modal')

@include('modals.confirm_delete_modal')

@include('modals.error_modal')
@include('modals.search_vehicle_modal')

@include('modals.close_confirmation_modal')

	@include('includes.loader')

	@include('modals.wms_preview_modal')
	@include('modals.discount_new_popup')
	@include('modals.voucher_restart_modal')


<?php Log::info('Master-Blade:-Inside');?>
	

<div id="page-wrapper">

	<div class="overlay" style="display: show;">
        <br>
        <br>
        <br>
        <br>
        <br>
        <h6 style="color: blue;"> We are processing your request, processing time depends on your internet speed... </h3>
	</div>
	<div id="loaderIndicator" style = "z-index:1000; position: absolute; top: 30%; left: 50%; margin-top: -50px; margin-left: -50px; width: 150px; height: 150px;"></div>

	<div id="page-header" class="{{ Session::get('theme_header') }}">

		<div id="header-logo" class="logo-bg"> 

		<a href="{{ route('dashboard') }}" class="logo-content"> 

		<span class="logo"></span> 

			@if(Session::get('business'))

				<span class="company_name">{{ Session::get('business') }}</span>

			@else

				<span class="company_name">PROPELSOFT</span>

			@endif

			

			@if(Session::get('bcrm_code'))

				<span class="company_slogan">Business ID: {{ Session::get('bcrm_code') }}</span>

			@else

				<span class="company_slogan">Accelerating Business Ahead</span>

			@endif

		</a>



		<a id="close-sidebar" class="sidebar-toggler" href="#" title="Close sidebar"><i class="fa fa-angle-left"></i></a>

	</div>





		<div id="header-nav-left">

			@if(Session::get('organization_id') && App\Custom::plan_renewal() != "")

			<span style="color: #ff2100; padding: 5px; font-weight: bold; margin-top: 15px; background: #ffff; border-radius: 5px; margin-left: 300px;position: absolute;" class="pull-left">

			{{ App\Custom::plan_renewal() }} <a style="color: #0e74b7;" href="{{route('plan')}}"> Subscribe </a>

			</span>

			@endif

		</div>
		<div class="row" id="position">
			<div class="col-md-10 offset-md-9 show_msg" id="panelhead"></div>
		</div>

		<div id="header-nav-right"  class="nav_list"> 

		@if(Session::get('organization_id'))

		<!-- <a href="#" title="Full Screen" id="full-screen" class="hdr-btn"><i class="fa fa-arrows-alt"></i></a>

		<a href="#" title="Chat" class="hdr-btn"><i class="fa fa-comments-o"></i></a> -->

		@endif

		<div style="float:left;">
		@if(Session::get('organization_id'))
	 	@if (App\Custom::check_module_list())
	 	<div style="/*margin-right: 100px;*/">
    	 	    <!-- Internet Speed Test -->
    	 	    <input type="text" id="internetSpeed" disabled style='height:36px;border-radius: 3px;border:2px solid white;width:130px' name="internetSpeed" value="Test Internet Speed"/> <button data-toggle="tooltip" title="Run Speed Test" style="height:36px;border-radius: 3px;border:2px solid white;" class="btn btn-success" id="internetSpeedButton"><i class="fa fa-cloud-download" ></i></button>

				@if(Session::get('organization_id'))
					@if (App\Organization::checkModuleExists('books', Session::get('organization_id')))
						@permission('books')
					<a href="{{ route('books.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="Books"> 
					<img src="{{ URL::to('/') }}/public/package/1b.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/accounts") class="active" @endif>
					<!--  Books  --></a> 
						@endpermission
					@endif
				@endif
				@if(Session::get('organization_id'))
					@if (App\Organization::checkModuleExists('hrm', Session::get('organization_id')))
						@permission('hrm')
					<a href="{{ route('hrm.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="HRM"> 
					<img src="{{ URL::to('/') }}/public/package/2.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;"  @if(request()->route()->getPrefix() == "/hrm") class="active"  @endif>
					<!-- HRM  --></a>
						@endpermission
					@endif
				@endif

				@if(Session::get('organization_id'))

					@if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))
					@permission('wfm')
						
					<a href="{{ route('wfm.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="WFM"> 
					<img src="{{ URL::to('/') }}/public/package/3.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/wfm") class="active"  @endif>
					<!--  WFM  --></a>
					 @endpermission
						
					@endif
				@endif

				@if(Session::get('organization_id'))
					@if (App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
						@permission('inventory')
					<a href="{{ route('inventory.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="Inventory"> 
					<img src="{{ URL::to('/') }}/public/package/4.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;"  @if(request()->route()->getPrefix() == "/inventory") class="active"  @endif>
					<!-- Inventory  --></a>
						@endpermission
					@endif
				@endif
				@if(Session::get('organization_id'))
					@if (App\Organization::checkModuleExists('trade', Session::get('organization_id')))
						@permission('trade')
					<a href="{{ route('trade.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="Trade"> 
					<img src="{{ URL::to('/') }}/public/package/5.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/trade") class="active"  @endif>
					<!--  Trade  --></a>
						@endpermission
					@endif
				@endif
				


				@if(Session::get('organization_id'))

					@if (App\Organization::checkModuleExists('trade_wms', Session::get('organization_id')))
						
						@permission('trade_wms')
						<a href="{{ route('trade_wms.job_board') }}" style="margin-right: 4px;" data-toggle="tooltip" data-placement="bottom" title="WMS"> 
						<img src="{{ URL::to('/') }}/public/package/6c.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/trade_wms") class="active"  @endif> 
						<!-- WMS  --></a>
						@endpermission
						
					@endif

				@endif
						
		</div>
		@else
			<div class="dropdown">
				<a  title="Home" href="{{ route('dashboard') }}"><i class="fa icon-basic-home"></i></a>
			</div>
		@endif
		@else
			<div >
			<!-- 	<a title="Menus" href="#"><i class="fa icon-arrows-squares"></i></a> -->
				<a href="{{ route('user.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="My Accounts"> 
				<img src="{{ URL::to('/') }}/public/package/1b.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/user") class="active"  @endif>
				<!-- My Accounts  --></a> 
				<a href="{{ route('personal_people.index') }}" data-toggle="tooltip" data-placement="bottom" title="My People">
				<img src="{{ URL::to('/') }}/public/package/2.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/trade_wms") class="active"  @endif>
				<!-- My People  --></a> 
				<a href="{{ route('vehicle_management.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="VMS"> 
				<img src="{{ URL::to('/') }}/public/package/vms.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/trade_wms") class="active"  @endif><!--  VMS  --></a>
				<a href="{{ route('books.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="Assets"> 
				<img src="{{ URL::to('/') }}/public/package/assets_locked1.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/trade_wms") class="active"  @endif><!--  Assets  --></a>
				<a href="{{ route('books.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="Prescriptions"> 
				<img src="{{ URL::to('/') }}/public/package/prescriptions_locked1.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/trade_wms") class="active"  @endif> <!-- Prescriptions  --></a>
				<a href="{{ route('books.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="Tasks" style="margin-right: 4px;"> 
				<img src="{{ URL::to('/') }}/public/package/tasks_locked1.png" width="41" height="36" style="border-radius: 3px;border:2px solid white;" @if(request()->route()->getPrefix() == "/trade_wms") class="active"  @endif>
				</a> 
			</div>
		@endif
		</div>

	
	
		@if (App\Organization::checkModuleExists('inventory', Session::get('organization_id')) || App\Organization::checkModuleExists('trade_wms', Session::get('organization_id')) ||
		App\Organization::checkModuleExists('hrm', Session::get('organization_id')) ||
		App\Organization::checkModuleExists('books', Session::get('organization_id')))
		<div class="dropdown" style="margin-right:3px;">
				<a  data-toggle="tooltip" data-placement="top" title="Quickly goes to the page"> 
				<img src="{{ URL::to('/') }}/public/package/link_icon.png" alt="Profile image" width="18" style="vertical-align:top ; width: 20px; margin: 5px;"></a>
				<div class="drop-menu right float-right profile target">
				<div class="ui-widget" style="margin-top:10px; ">
				{{ Form::text('order_no',null,['class' => 'form-control unique ','placeholder' => 'Please Enter Invoice no or Purchase no'])}}
				</div>
				<br>
				<center class="help_link" style="display:none;"><i class="fa fa-question-circle fa-lg" aria-hidden="true" style="color: blue;"></i><a class="help_page" href="#" target="_blank">Help for this Page</a></center>
				
					<div class="menu-box-sm">
						<div class="login-box clearfix">
							<div class="row head">
							<ul>
							@permission('trade_wms')
							<h6 style="color: #5C7EBE !important;margin-left: 10px;">WMS</h6>
							@endpermission

				 			@permission('wms-job-invoice-list')
							<li><a class="dropdown-item bgcolor" href="{{ route('transaction.index', ['job_invoice']) }}">Job invoice</a></li>
							@endpermission

							@permission('WMS-Receivables')
							<li><a class="dropdown-item bgcolor" href="{{ route('cash_transaction.index', ['wms_receipt']) }}">Receivables</a></li>
							@endpermission

							@permission('inventory') 
							<h6 style="color: #5C7EBE !important;margin-left: 10px;">Inventory</h6>
							@endpermission
							@permission('purchase-list')
							<li><a class="dropdown-item bgcolor" href="{{ route('transaction.index', ['purchases']) }}">Purchase</a></li>
							@endpermission
							@permission('payables-list')
						    <li><a class="dropdown-item bgcolor" href="{{ route('cash_transaction.index', ['payment']) }}">Payables</a></li>
						    @endpermission
						

						 	@permission('hrm') 
						    <h6 style="color: #5C7EBE !important;margin-left: 10px;">HRM</h6>
						    @endpermission
						    @permission('attendance-list')
						    <li><a class="dropdown-item bgcolor" href="{{ route('hrm_attendance.index') }}">Attendance</a></li>
						    @endpermission
						    @permission('permissions-list')
						    <li><a class="dropdown-item bgcolor" href="{{ route('payroll.index') }}">Salary</a></li>
						   	@endpermission
						
 			
							@permission('books') 
						    <h6 style="color: #5C7EBE !important;margin-left: 10px;">Books</h6>
						    @endpermission
						    <li><a class="dropdown-item bgcolor" href="{{ route('vouchers.index') }}">Transaction</a></li>
						    @permission('Petty-Expenses-Management')
						    <li><a class="dropdown-item bgcolor" href="{{ route('expenses.index') }}">Expenses</a></li>
						    @endpermission
						
						    </ul>
							</div>
						</div>
					</div>
				</div>
		</div>
		@endif
		<div style="float:right;">
		
			<div id="notification" class="dropdown">

				<a  title="Notifications" href="#" data-target=".notifi" class="smallbutton">

					<span style="display: none;" class="small-badge bg-yellow"></span>

					<i class="fa fa-bell-o"></i></a>

				<div class="drop-menu right float-right notifi target">

					<div class="box-sm main-menu">



					<h5 style="margin: 0; padding: 5px;">Notifications</h5>



					<hr style="margin: 0 0 5px;">



					<ul class="toolbar_notifications">

					</ul>

					<a style="background: #2991D8; color: #fff;display: block; overflow: hidden; height: 100%; content: ''; border-radius: 3px; position: relative; min-height: 1px; padding: 10px;" 

					

					@if(Session::get('account_type') == 'business')

						href="{{ route('notifications') }}" 

					@elseif(Session::get('account_type') == 'user')

						href="{{ route('user_notifications') }}" 

					@endif



					



					class="label label-sm label-success">View All</a>

					</div>

				</div>

			</div>



			 <!-- <div class="dropdown"><a title="Settings" href="{{ route('settings') }}">

			 	<i class="fa icon-basic-settings"></i></a>

			 </div> -->



			



			 <div class="dropdown"> 

				<a href="#" title="My Account" class="user-profile clearfix smallbutton" data-target=".profile"> 

					<img src="{{ URL::to('/') }}/public/users/images/{{ App\Person::user_image(Auth::user()->person_id) }}" alt="Profile image" width="18" style="vertical-align:top ; width: 20px; margin: 5px;"> <!-- <span>{{ Auth::user()->name }}</span> 

					<i class="fa fa-angle-down"></i> --> <!-- <i class="fa icon-basic-lock-open"></i> --></a>

				<div class="drop-menu right float-right profile target">

					<div class="box-sm ">

						<div class="login-box clearfix">

							<div class ="row">



								<div class="col-md-4">



									<div class="form-group col-md-12 user-img">

										<div class="row">							  	

										  	<div class="col-md-12">				

											<img src="{{ URL::to('/') }}/public/users/images/{{ App\Person::user_image(Auth::user()->person_id) }}" alt="">											

											</div>

										</div>

									</div>

									

								</div>





								<div class="col-md-8 user-info">



									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12" style="color: #333;">

											{{ Auth::user()->name }}

											</div>

										</div>

									</div>



									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12" style="color: #666;">

											Propel-ID: {{ Session::get('crm_code') }}

											</div>

										</div>

									</div>

									<hr>

									

									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12">				

											<a href="{{ route('person_profile.show', [Auth::user()->person_id]) }}">Edit profile</a>

											</div>

										</div>

									</div>

									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12">				

											<a href="{{ route('change_password') }}">Change Password</a>

											</div>

										</div>

									</div>

									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12">				

											<a href="{{ route('companies.index') }}">Change Account</a>

											</div>

										</div>

									</div>

									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12">				

											<a href="{{ route('settings') }}">Settings</a>

											</div>

										</div>

									</div>

									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12">				

											<a href="{{ route('settings') }}">Support</a>

											</div>

										</div>

									</div>

									<hr>

									<div class="form-group col-md-12">

										<div class="row">							  	

										  	<div class="col-md-12">				

											<a href="{{ url('/logout') }}" 

										onclick="event.preventDefault();

						 				document.getElementById('logout-form').submit();">Log Out</a>

											</div>

										</div>

									</div>



								</div>

							</div>

						</div>

						<!-- <div class="divider"></div> -->

						<!-- <div class="login-box clearfix">

							<p>Propel Soft is a Marketplace for all your Business Services supported by All-In-One Platform. Instant access to customer, vendor and employee information. </p>

							&copy; 2018,  <a href="http://www.propelsoft.in" target="_blank">PropelSoft</a>. All Rights Reserved.</div> -->

					</div>

				</div>

			</div>



			<!-- <a class="header-btn" title="Logout" id="logout-btn" href="#" 

			onclick="event.preventDefault();

				 document.getElementById('logout-form').submit();">

				 <i class="fa icon-basic-lock-open"></i>

			</a> -->

			<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">

				{{ csrf_field() }}

			</form>
		</div>
		</div>

	</div>

	<div id="page-sidebar" class="{{ Session::get('theme_sidebar') }}">

		<ul id="sidebar-menu" class="sf-js-enabled sf-arrows">

			@section('sidebar') @show

			

		</ul>

	</div>

	<div id="page-content-wrapper">

		<div id="page-content" style="margin-top: -22px;">

			<div id="container">
				<?php Log::info('Master-Blade:- content Rendering Start');?>

				@yield('content')
				<?php Log::info('Master-Blade:- content Rendering End');?>

			</div>

		</div>

	</div>

</div>

@section('dom_links') 

<!-- Scripts --> 

<!--[if lt IE 9]>

		<script src="{{ URL::asset('assets/plugins/respond.min.js') }}"></script>

		<script src="{{ URL::asset('assets/plugins/excanvas.min.js') }}"></script> 

		[endif]--> 

@if(app()->environment() == "production")

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.bundle.js"></script>    

    
<!-- <script type="text/javascript" src="{{ URL::asset('assets/plugins/popper.min.js') }}"></script> --> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>  

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}" ></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script> 

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script> -->

<script type="text/javascript" src="{{ URL::asset('assets/plugins/select2/js/select2.full.min.js') }}"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.js"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/3.3.2/screenfull.min.js"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.2/jquery.slimscroll.min.js"></script>

<script type="text/javascript"  src="{{ URL::asset('assets/plugins/ckeditor.js') }}"></script>

<!-- <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script> -->

@elseif(app()->environment() == "local")



<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.js" type="text/javascript"

            charset="utf-8"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"

            charset="utf-8"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript"

            charset="utf-8"></script> -->

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/modernizr-custom.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/popper.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}" ></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/moment.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/select2/js/select2.full.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/screenfull.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script> 


@endif

{{-- START TOASTER JS --}}
<script src="{{ URL::asset('assets/layout/js/toastr.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/toastr.css')}}">
{{-- END TOASTER JS --}}
	

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.inputmask.bundle.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/row-sorter.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/layout/js/debug.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/layout/js/custom.js') }}"></script> 









<script type="text/javascript">



if(moment().month() > 3) {

	var fiscal_year = "01 04 "+moment().year();

} else {

	var fiscal_year = "01 04 "+moment().subtract(1, 'year').format('YYYY');

}





@if(Session::get('organization_id'))



	<?php

	 $financialyear = App\AccountFinancialYear::select(DB::raw('DATE_FORMAT(financial_start_year, "%d-%m-%Y") AS financial_start_year'), DB::raw('DATE_FORMAT(financial_end_year, "%d-%m-%Y") AS financial_end_year'))->where('organization_id', Session::get('organization_id'))->where('status', '1')->first(); 

		$organization_id = Session::get('organization_id');		
		
		$plan = ['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];

		
	?>



	/*@if(App\Custom::plan_expire($plan,$organization_id))

		$('.transaction_change').each(function() {
			$(this).removeClass('add');
			$(this).removeClass('edit');
			$(this).removeClass('delete');
		});

		$('body').on('click', '.transaction_change', function(e) {
			e.preventDefault();
			
			$('#error_dialog #title').text('Plan Expired!');
			$('#error_dialog #message').html('{{ config('constants.error.expire') }}' + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
			$('#error_dialog').modal('show');

			return false;
		});
	@endif*/

	@if(!App\Custom::plan_limitation())

		$('.transaction_change').each(function() {
			$(this).removeClass('add');
			$(this).removeClass('edit');
			$(this).removeClass('delete');
		});

		$('body').on('click', '.transaction_change', function(e) {
			e.preventDefault();
			
			$('#error_dialog #title').text('Plan Expired!');
			$('#error_dialog #message').html('{{ config('constants.error.expire') }}' + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
			$('#error_dialog').modal('show');

			return false;
		});
	@endif

@endif



setNotification();



	var financialyear_start = '{{$financialyear->financial_start_year or ''}}';

	var financialyear_end = '{{$financialyear->financial_end_year or ''}}';



	



	$(document).ready(function() {

        // Internet Speed Test - START
        $("#internetSpeedButton").click(function(){
            MeasureConnectionSpeed();
          });

        var imageAddr = "http://mypropelsoft.com/public/20574226_l.jpg";
        var downloadSize = 4995374; //bytes

        function ShowProgressMessage(msg) {
            if (console) {
                if (typeof msg == "string") {
                    console.log(msg);
                } else {
                    for (var i = 0; i < msg.length; i++) {
                        console.log(msg[i]);
                    }
                }
            }

            var oProgress = document.getElementById("progress");
            if (oProgress) {
                var actualHTML = (typeof msg == "string") ? msg : msg.join("<br />");
                oProgress.innerHTML = actualHTML;
            }
        }

        // function InitiateSpeedDetection() {
        //     ShowProgressMessage("Loading the image, please wait...");
        //     window.setTimeout(MeasureConnectionSpeed, 1);
        // };

        // if (window.addEventListener) {
        //     window.addEventListener('load', InitiateSpeedDetection, false);
        // } else if (window.attachEvent) {
        //     window.attachEvent('onload', InitiateSpeedDetection);
        // }

        function MeasureConnectionSpeed() {
            var startTime, endTime;
            var download = new Image();
            download.onload = function () {
                endTime = (new Date()).getTime();
                showResults();
            }

            download.onerror = function (err, msg) {
                $('#internetSpeed').val("Invalid image, or error downloading");
                ShowProgressMessage("Invalid image, or error downloading");
            }

            $('#internetSpeed').val("Checking speed...");
            startTime = (new Date()).getTime();
            var cacheBuster = "?nnn=" + startTime;
            download.src = imageAddr + cacheBuster;

            function showResults() {
                var duration = (endTime - startTime) / 1000;
                var bitsLoaded = downloadSize * 8;
                var speedBps = (bitsLoaded / duration).toFixed(2);
                var speedKbps = (speedBps / 1024).toFixed(2);
                var speedMbps = (speedKbps / 1024).toFixed(2);
                if(speedMbps > 1){
                    $('#internetSpeed').val(speedMbps + " Mbps / " + duration + " sec");
                }else if (speedKbps > 1){
                    $('#internetSpeed').val(speedKbps + " kbps / " + duration + " sec");
                }else{
                    $('#internetSpeed').val(speedBps + " bps / " + duration + " sec");
                }
                ShowProgressMessage([
                    "Your connection speed is:",
                    duration + " seconds",
                    speedBps + " bps",
                    speedKbps + " kbps",
                    speedMbps + " Mbps"
                ]);
            }
        }
        // Internet Speed Test - END

		$('.toolbar_notifications').slimScroll({

			height: '220'

		});



		$.ajax({

			url: "{{ route('user_log') }}",

			type: 'post',

			data: {

				_token :"{{csrf_token()}}",

				page: $.trim($('.page-title').clone().find('a').remove().end().text()),

				url: window.location.href,

				},

			dataType: "json"

		});


		$.ajax({
	     		type: "POST",
		        url: "{{ route('message_show') }}",
		        data: { 
		        	_token :"{{csrf_token()}}"
		        },
		        success: function(data, textStatus, jqXHR) {
		            var panel_msg=data.result;
		            if(panel_msg != null){
		            	$('.show_msg').show();
		            	 $(".show_msg").text(panel_msg.message);
		            }else{
		            	$('.show_msg').hide();
		            }
		          
		        },
		        error: function(result) {
		            //alert('error');
		        }
		    });
    
         $("input[name=order_no]").autocomplete({
		    source: "{{ route('order_no_search') }}",
				minLength:1,
		      	select: function( event, ui ) {	
		       	$('input[name=order_no]').val(ui.item.label);
		       	var id=ui.item.id;
		       	var module_name=ui.item.module;
		    	$.get("{{ url('transaction_link') }}/"+id+"/popup/"+module_name, function(data) {
			  	$('.crud_modal .modal-container').html("");
			  	$('.crud_modal .modal-container').html(data);
				});
				$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
				$('.crud_modal').modal('show');
				$('input[name=order_no]').val('').trigger().change();
		      }
		});




		/*$('#notification').click(function() {

	     	$.ajax({

	     		type: "POST",

		        url: "{{ route('notification_status') }}",

		        data: { 

		        	_token :"{{csrf_token()}}"            

		        },

		        success: function(result) {

		            //alert('ok');

		            $("#notification .bg-yellow").hide();

		        },

		        error: function(result) {

		            //alert('error');

		        }

		    });

		})*/;

	});



	function setNotification() {
		$.ajax({
			@if(Session::get('account_type') == 'business')
				url: "{{ route('get_notifications') }}",
			@elseif(Session::get('account_type') == 'user')
				url: "{{ route('get_user_notifications') }}",
			@endif
			
			type: 'post',
			data: {
				_token :"{{csrf_token()}}"
				},
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				var result = data.notifications;

				if(data.total > 0) {
					$("#notification .bg-yellow").show();
					if(!$("#notification > div").hasClass('drop-menu')) {
						$("#notification > div").addClass('drop-menu');
					}
				} else {
					$("#notification .bg-yellow").hide();
					if($("#notification > div").hasClass('drop-menu')) {
						$("#notification > div").hide();
						$("#notification > div").removeClass('drop-menu');
					}
					
				}
				var html = ``;
				for(var i in result) {
			
					html += `<li>
							<div class="col1">
								<div class="cont">
									<div class="cont-col1">
										<div style="background: #ead941;" class="label label-sm label-success">
											<i class="fa fa-bell-o"></i>
										</div>
									</div>
									<div class="cont-col2">
										<div class="desc"> `+ result[i].message +`<br>
											<span style="padding: 2px 0px; float: left; font-size: 11px;">`+ result[i].type +`</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col2">
								<div class="date" data-toggle="tooltip" data-placement="top" title="this time created at vendor Company"> `+ result[i].time +` </div>
							</div>
						</li>`;
				
				}
			
				$('.toolbar_notifications').empty();
				$('.toolbar_notifications').append(html);
			}
		});
		
		
	}



			$(document).ready(function () {

				    var $targets = $('.target');



				    $('.smallbutton').click(function () {



				        var $target = $($(this).data('target'));

				        $targets.not($target).hide();

				    });

				});

		$('.fsm_todayrate').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('change_itemrate') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  		});

	

//Loader

	var cSpeed=4;
	var cWidth=100;
	var cHeight=100;
	var cTotalFrames=15;
	var cFrameWidth=100;
	var cImageSrc='{{ URL::to('/') }}/public/tier_spinner.gif';

	var cImageTimeout=false;
	var cIndex=0;
	var cXpos=0;
	var cPreloaderTimeout=false;
	var SECONDS_BETWEEN_FRAMES=0;
	var $overlaySelector = $(".overlay");
	function startAnimation(){

		document.getElementById('loaderIndicator').style.backgroundImage='url('+cImageSrc+')';
		document.getElementById('loaderIndicator').style.width=cWidth+'px';
		document.getElementById('loaderIndicator').style.height=cHeight+'px';
		$overlaySelector.show();

		//FPS = Math.round(100/(maxSpeed+2-speed));
		FPS = Math.round(100/cSpeed);
		SECONDS_BETWEEN_FRAMES = 1 / FPS;

		cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES/1000);
				$('#loaderIndicator').show();

	}

	function continueAnimation(){

		cXpos += cFrameWidth;
		//increase the index so we know which frame of our animation we are currently on
		cIndex += 1;

		//if our cIndex is higher than our total number of frames, we're at the end and should restart
		if (cIndex >= cTotalFrames) {
			cXpos =0;
			cIndex=0;
		}

		if(document.getElementById('loaderIndicator'))
			document.getElementById('loaderIndicator').style.backgroundPosition=(-cXpos)+'px 0';

		cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES*1000);
	}

	function stopAnimation(){//stops animation
		clearTimeout(cPreloaderTimeout);
		cPreloaderTimeout=false;
		$('#loaderIndicator').hide();
		$overlaySelector.hide();
	}

	function imageLoader(s, fun)//Pre-loads the sprites image
	{
		clearTimeout(cImageTimeout);
		cImageTimeout=0;
		genImage = new Image();
		genImage.onload=function (){cImageTimeout=setTimeout(fun, 0)};
		genImage.onerror=new Function('alert(\'Could not load the image\')');
		genImage.src=s;
	}

	//The following code starts the animation
	//new imageLoader(cImageSrc, 'startAnimation()');

	function showAlertMsg(msg,type){

		// toaster options
		toastr.options = {
		"debug": false,
		"positionClass": "toast-top-right",
		//	"onclick": null,
		//"fadeIn": 300,
		//"fadeOut": 1000,
		//"timeOut": 5000,
		//"extendedTimeOut": 1000
		"closeButton" : true
		};

		switch(type){
        case 'info':
            toastr.info(msg);
            break;
        
        case 'warning':
            toastr.warning(msg);
            break;

        case 'success':
            toastr.success(msg);
            break;

        case 'error':
            toastr.error(msg);
            break;
    }
		//toastr.success(msg);			
	}

	</script> 

@show

@section('foot_links')

@show
<?php Log::info('Master-Blade:-Return');?>
</body>

</html>

