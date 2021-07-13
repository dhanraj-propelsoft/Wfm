@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
	 
	<style>
		.dt-buttons {
			display: none;
		}
		.dataTables_length {
			margin-bottom: -35px;
		}
		.dropdown-menu > a:hover {
		    background-color: #e74c3c;
		    color:white;
		}
		.dropdown-menu {
		    background-color: #e74c3c !important;
		   min-width: 3rem;
		   /* top : -20px !important;
		   left: -80px !important; */
		}
		.dropdown-submenu {
		  position: relative;
		 
		}

		.dropdown-submenu .dropdown-menu {
		 /*  top: 0; */
		 left: -200px; 
		   margin-top: -150px; 
		}
		.dropdown-menu > li:hover {
		    background-color: yellow;
		    color:white;
		}
		.dropdown-menu > a {
		 color: white;
		}
		.table td
		{
			padding: 2px;
		}
		body
		{
			font-size: 12px !important;
		}
		.btn
		{
			line-height: 1;
		}
		.list_options li:hover
		{
		    background-color: yellow;
			
		}

		
	</style>

@stop

@if($transaction_type->module == "inventory")
@include('includes.inventory')

@elseif($transaction_type->module == "trade")
@include('includes.trade')

@elseif($transaction_type->module == "trade_wms")
@include('includes.trade_wms')

@elseif($transaction_type->module == "fuel_station")
@include('includes.fuel_station')

@elseif($transaction_type->module == "mship")
@include('includes.mship')

@endif

@section('content')
@include('includes.add_user')
@include('includes.add_business')
@include('modals.invoice_modal')
@include('includes.views')


<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
<div class="alert alert-danger"></div>
@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif	


<div class="fill header" style="height:40px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
	<div class="row" style="padding-top: 5px;">

		<div style="float: left;margin-right: auto; padding-left: 20px;">
			<h5 class="float-left page-title"><b>@if($transaction_type->name == 'sales_cash') Invoice @else {{$transaction_type->display_name}} @endif</b></h5>
		</div>
		
		<div class="float-right form-inline">
			@if($transaction_type->module == "inventory" || $transaction_type->module == "trade")
				<div>
					{{ Form::hidden('type_name',$transaction_type->name) }}
					{{ Form::text('from_date',$firstDay_only,['class' => 'form-control date-picker', 'placeholder' => 'From Date', 'data-date-format' => 'dd-mm-yyyy','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}

					{{ Form::text('to_date',$today,['class' => 'form-control date-picker','placeholder' => 'To Date','data-date-format' => 'dd-mm-yyyy','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
					<button style=" height:25px;margin-right: 20px; border-radius: 3px 3px 3px 3px" type="submit" class="date btn btn-success search_all"><i class="fa fa-search" ></i></button>
					</div>
		
			@endif
			@if($transaction_type->name == 'job_card')
					
					<div>
					{{ Form::text('from_date',$from_date_trade_wms,['class' => 'form-control date-picker', 'placeholder' => 'From Date', 'data-date-format' => 'yyyy-mm-dd','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
					{{ Form::text('to_date',$to_date,['class' => 'form-control date-picker','placeholder' => 'To Date','data-date-format' => 'yyyy-mm-dd','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
					<button style=" height:25px;margin-right: 20px; border-radius: 3px 3px 3px 3px" type="submit" class="date btn btn-success search"><i class="fa fa-search" ></i></button>
					</div>
			@endif

			@if($transaction_type->name == 'job_request')
					<!-- {{ Form::label('from_date','From date') }} -->
					<div>
					{{ Form::text('from_date',$from_date_trade_wms,['class' => 'form-control date-picker', 'placeholder' => 'From Date', 'data-date-format' => 'yyyy-mm-dd','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
					{{ Form::text('to_date',$to_date,['class' => 'form-control date-picker','placeholder' => 'To Date','data-date-format' => 'yyyy-mm-dd','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
					<button style=" height:25px;margin-right: 20px; border-radius: 3px 3px 3px 3px" type="submit" class="date btn btn-success estimation_search"><i class="fa fa-search" ></i></button>
					</div>
			@endif

		  @if($transaction_type->name == 'job_invoice')
			 <div>
				{{ Form::text('from_date', $from_date_trade_wms, ['class' => 'form-control date-picker' , 'placeholder' => 'From Date', 'data-date-format' => 'yyyy-mm-dd','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
				{{ Form::text('to_date' , $to_date, ['class' => 'form-control date-picker','placeholder' => 'To Date', 'data-date-format' => 'yyyy-mm-dd','style' => 'border-radius:4px 4px 4px 4px;width:100px;height:25px;']) }}
				<button type="submit" class="btn btn-success invoice_search" style="height:25px;margin-right: 20px; border-radius:3px 3px 3px 3px"><i class="fa fa-search "></i></button>
			</div>

			@endif 
		</div>

	@if($transaction_type->name != 'job_status' ) 	

	<div class="btn-group btn-group-sm float-right" style="padding-right: 30px;height:25px;">


		@if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales') 
		
		<a class="btn btn-danger float-left add_cash_sale " style="color: #fff;padding-top: 1px;">Cash Sale</a>

		<a class="btn btn-danger float-left add " style="color: #fff;padding-top: 1px;">Credit Sale</a>
	
		@elseif($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash' ) 
	
		<!-- <a class="btn btn-danger float-left invoice_add_cash_sale" style="color: #fff">Cash Sale</a>
		
		<a class="btn btn-danger float-left invoice_add" style="color: #fff">Credit Sale</a> -->
		<div class="dropdown">
			<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:25px;">
					New Sale
						
			</button>
			<div class="dropdown-menu "  aria-labelledby="dropdownMenuButton">
				<a href="#" class="dropdown-item hover invoice_add_cash_sale "  data-name="cash">Cash Sale</a>
				
				<a href="#" class="dropdown-item hover invoice_add "  data-name="credit" >Credit Sale</a>
						
			</div>
		</div>
		@else

			<a class="btn btn-danger float-left add transaction_limit " style="color: #fff;padding-top: 1px;">New</a>

		@endif
			
			<a class="btn btn-danger float-left edit" style="color: #fff;padding-top: 1px; display: none;">Edit</a>
	

		<!-- @if($transaction_type->name == 'job_card' || $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash' || $transaction_type->name == 'job_request' || $transaction_type->name == 'purchases' || $transaction_type->name == 'purchase_order' || $transaction_type->name == 'sales' || $transaction_type->name == 'sales_cash' || $transaction_type->name == 'goods_receipt_note') 
			<a class="btn btn-danger float-left sms sms_limit" style="color: #fff;padding-top: 1px; display: none;">Send sms</a>

		@endif -->

		<a class="btn btn-danger float-left sms sms_limit" style="color: #fff;padding-top: 1px; display: none;">Send SMS</a>
	
		<a class="btn btn-danger float-left multidelete" id="{{$transaction_type->name}}" style="color: #fff;padding-top: 1px;">Delete</a>
	

		@if($transaction_type->name != 'job_invoice' )
		<a class="btn btn-danger float-left multiapprove" data-status="1" style="color: #fff;padding-top: 1px;">Approve</a>
		<!--<a class="btn btn-danger float-left un_approve" data-status="1" style="color: #fff;padding-top: 1px;">Unapprove</a>-->
		@endif
		<!-- <a class="btn btn-danger float-left multinotapprove" data-status="0" style="color: #fff">Not Approve</a> -->
		@if($transaction_type->name != 'job_card')
		<a class="btn btn-danger float-left print" style="color: #fff;padding-top: 1px;">Print</a>
		@endif

		<a class="btn btn-danger float-left excel_export" style="color: #fff;padding-top: 1px;">Export to Excel</a>
	</div>

	@endif

	</div>
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">



	@if($transaction_type->module == "inventory" || $transaction_type->module == "trade")

		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
				<th class="noExport">
					{{ Form::checkbox('checkbox_all', 'checkbox_all', null, ['id' => 'checkbox_all'] ) }} <label for="checkbox_all"><span></span></label></th>
				@if($type == 'delivery_note')
					<th> Delivery Note Number </th>
				@endif
				<th>
					@if($type == 'purchase_order') Purchase Order @elseif($type == 'goods_receipt_note') GRN Number @elseif($type == 'purchases') Purchase Number @elseif($type == 'debit_note') Invoice Number @elseif($type == 'estimation') Estimate Number  @elseif($type == 'sale_order') SO Number  @elseif($type == 'sales' || $type == 'sales_cash') Invoice Number @elseif($type == 'delivery_note') Invoice Number @elseif($type == 'credit_note') Invoice Number  @endif
				</th>

				@if($type == 'goods_receipt_note')
					<th> Purchase Number </th>
				@endif



				@if($type == 'purchases' || $type == 'estimation' || $type == 'sale_order' || $type == 'sales' || $type == 'sales_cash')
					<th> Reference Type </th>
				@endif

				@if($type == 'purchases' || $type == 'estimation' || $type == 'sale_order' || $type == 'sales' || $type == 'sales_cash'  )
					<th> Reference Number </th>

				@endif


				<th>
					@if($type == 'purchase_order' || $type == 'goods_receipt_note' || $type == 'purchases') Supplier @else Customer @endif
				</th>


				<th>
					@if($type == 'purchase_order' || $type == 'goods_receipt_note' || $type == 'purchases') Supplier Contact @else Customer Contact @endif
				</th>


				<th>
					@if($type == 'purchase_order') PO Amount @elseif($type == 'goods_receipt_note' || $type == 'purchases') Invoice Amount  @elseif($type == 'estimation') Estimate Amount @elseif($type == 'sale_order') SO Amount  @elseif($type == 'sales' || $type == 'sales_cash' || $type == 'delivery_note' || $type == 'credit_note' || $type == 'debit_note') Invoice Amount @endif
				</th>


				<th>
				@if($type == 'purchase_order') PO Date @elseif($type == 'goods_receipt_note') Invoice Date @elseif($type == 'purchases') Placed on Date @elseif($type == 'estimation') Estimate Date  @elseif($type == 'sale_order' ) SO Date @elseif($type == 'sales' || $type == 'sales_cash' || $type == 'delivery_note' || $type == 'credit_note' || $type == 'debit_note') Invoice Date @endif
				</th>


				<!-- @if($type == 'purchases' || $type == 'sales' || $type == 'sales_cash' || $type == 'delivery_note' || $type == 'credit_note' || $type == 'debit_note' )
				<th> Due Date </th>
				@endif -->

				@if($type == 'purchase_order' || $type == 'estimation' || $type == 'sale_order')
				<th> Shipping Date </th>
				@endif



				@if($type == 'purchases' || $type == 'sales' || $type == 'sales_cash')
				<th> Balance Due </th>
				<th> Payment Status </th>
				@endif



				<th> Status </th>

				<th> Action </th>
				</tr>
			</thead>
			<tbody>
				@foreach($transactions as $transaction)

			<tr>
				<td width="1">{{ Form::checkbox('transaction',$transaction->id, null, ['id' => $transaction->id, 'class' => 'item_checkbox','data-id' => $transaction->vehicle_id ,'data-approval_status' => $transaction->approval_status]) }}<label for="{{$transaction->id}}"><span></span></label></td>

				@if($type == 'delivery_note')
				<td>{{ $transaction->order_no }} </td>
				@endif



				<td> @if($type == 'debit_note' || $type == 'delivery_note' || $type == 'credit_note')
					<a class="po_edit" data-id="{{$transaction->id}}" data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->reference_no }}</a> @else
					<a class="po_edit" data-id="{{$transaction->id}}" data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->order_no }}</a>
					@endif
				</td>

				@if($type == 'goods_receipt_note')
				<td>{{ $transaction->reference_no }} </td>
				@endif

				@if($type == 'purchases' || $type == 'estimation' || $type == 'sale_order' || $type == 'sales' || $type == 'sales_cash')
				<td>{{ $transaction->reference_type }} </td>
				@endif

				@if($type == 'purchases' || $type == 'estimation' || $type == 'sale_order' || $type == 'sales' || $type == 'sales_cash')
				<td> {{ $transaction->reference_no }} </td>
				@endif


				<td>{{ $transaction->customer }}</td>
				<td> {{ $transaction->customer_contact }} </td>
				<td>{{ $transaction->total }}</td>
				<td>{{ $transaction->date }}</td>

				<!-- @if($type == 'purchases' || $type == 'sales' || $type == 'sales_cash' || $type == 'delivery_note' || $type == 'credit_note' || $type == 'debit_note'  )
				<td>{{ $transaction->due_date }}</td>
				@endif -->

				@if($type == 'purchase_order' || $type == 'estimation' || $type == 'sale_order')
				<td>{{ $transaction->shipping_date }}</td>
				@endif

				@if($type == 'purchases' || $type == 'sales' || $type == 'sales_cash' )
				<td>{{ $transaction->balance }}</td>
				<td>
					@if($transaction->status == 0)
						<label class="grid_label badge badge-warning">Pending</label>
					@elseif($transaction->status == 1)
						<label class="grid_label badge badge-success">Paid</label>
					@elseif($transaction->status == 2)
						<label class="grid_label badge badge-info">Partially Paid</label>
					@elseif($transaction->status == 3)
						<label class="grid_label badge badge-danger">Over due {{App\Custom::time_difference(Carbon\Carbon::now()->format('Y-m-d H:i:s'), Carbon\Carbon::parse($transaction->original_due_date)->format('Y-m-d'), 'd')}} days</label>
					@endif
				</td>
				@endif


				<td>
					@if($transaction->approval_status == 0)
						<label class="grid_label badge badge-warning status">Draft</label>
					@elseif($transaction->approval_status == 1)
						<label class="grid_label badge badge-success status">Approved</label>
					@endif
				</td>
				<td>
					<div class="action_options">
					</div>
					<button type="button" class="btn btn-info" id="actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button>

					<!-- <div class="dropup">
					    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Actions
					    <span class="caret"></span></button>
					    <ul class="dropdown-menu dropdown-menu-right" style="width: 120px;padding:12px;background-color:rgb(247, 236, 214) !important;" id="actions_list">

					    @if($type == "purchase_order")
					    						<li><a  class="approval_status po_edit" data-id="{{ $transaction->id}}" id="{{ $transaction->id}}" style="color: black;">View </a></li>
					    						@elseif($type == "purchases")
					    						<li><a class="po_edit"  data-id="{{ $transaction->id}}" id="{{ $transaction->id}}" style="color: black;"> View</a></li>
					    						@elseif($type == "goods_receipt_note")
					    						<li><a class="po_edit" data-id="{{ $transaction->id}}" id="{{ $transaction->id}}" style="color: black;">View</a></li>
					    						@elseif($type == "debit_note" || $type == "credit_note")
					    						<li><a class="po_edit" data-id="{{ $transaction->id}}" id="{{ $transaction->id}}" style="color: black;"> View</a></li>
					    						@elseif($type == "sale_order")
					    						<li><a class="po_edit" data-id="{{ $transaction->id}}" id="{{ $transaction->id}}" style="color: black;"> View</a></li>
					    						@elseif($type == "sales_cash" || $type == "sales")
					    						<li><a class="po_edit" data-id="{{ $transaction->id}}" id="{{ $transaction->id}}" style="color: black;"> View</a></li>
					    						@endif
					      <li><a  class="actions_edit" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Edit</a></li>
					      <li><a class="print" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">3. Print</a></li>

					      	@if($type == "purchase_order")
							<li><a  class="approval_status tab_print_btn"  id="{{ $transaction->id}}" style="color: black;">Print PO </a></li>
							@elseif($type == "purchases")
							<li><a class="tab_print_btn" id="{{ $transaction->id}}" style="color: black;">Print Purchase </a></li>
							@elseif($type == "goods_receipt_note")
							<li><a class="tab_print_btn" id="{{ $transaction->id}}" style="color: black;">Print GRN </a></li>
							@elseif($type == "debit_note" || $type == "credit_note")
							<li><a class="tab_print_btn" id="{{ $transaction->id}}" style="color: black;">Print Return </a></li>
							@elseif($type == "sale_order")
							<li><a class="tab_print_btn" id="{{ $transaction->id}}" style="color: black;">Print SO </a></li>
							@elseif($type == "sales_cash" || $type == "sales")
							<li><a class="tab_print_btn" id="{{ $transaction->id}}" style="color: black;">Print Invoice </a></li>
							@endif

					      <li><a class="actions_sms sms_limit" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;">SMS</a></li>
					      <li><a href="#" class="actions_multidelete" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;"> Delete</a></li>
					      <li><a href="#" class="actions_multiapprove" data-status="1" id="{{$transaction->id}}" style="color: black;" @if($transaction->approval_status == '1') disabled @endif>Approve</a></li>
					      @if($type != 'credit_note' || $type != 'debit_note')
					      <li><a href="#" class="actions_un_approve" data-status="1" id="{{$transaction->id}}" style="color: #d9d9d9;"> UnApprove</a></li>
					      @endif
					      @if($type == 'sales')
					      <li><a href="#" class="pay_for_invoice" data-identity_name="receipt" data-name="invoice_payment" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;">Payment</a></li>
					      <li><a href="#" class=" make_transaction approval_status" data-name="delivery_note" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;"> Create DN</a></li>
					      <li><a href="#" class="view_estimations" data-type_name="delivery_note" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">View DN</a></li>
					      <li><a href="#" class="make_transaction approval_status" data-name="credit_note" data-ref="credit_note" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;"> Create sales return</a></li>
					      @endif
					      @if($type == 'purchases')
					      <li><a href="#" class="pay_for_invoice" data-identity_name="payment" data-name="invoice_payment" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;">Payment</a></li>
					      <li><a href="#" class="make_transaction approval_status po_to_grn" data-name="goods_receipt_note" data-ref="po_to_grn" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style=" color: black;"> Create GRN</a></li>

					      <li><a href="#" class="view_estimations" data-type_name="goods_receipt_note" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">View GRN</a></li>
					      <li><a href="#" class="make_transaction approval_status " data-ref="debit_note" data-name="debit_note" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;"> Create purchase return</a></li>
					      @endif
					     @if($type == 'goods_receipt_note')

					     	<li><a href="#" class="tab_update_goods_btn update_goods" data-name="goods_receipt_note" data-id="{{$transaction->id}}" style="color: black;"> Update Inventory</a></li>

					     @endif




					    </ul>
									  	</div> -->

				</td>


			</tr>

				@endforeach
			</tbody>
		</table>

	@endif

	@if($transaction_type->name == "job_card")




		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>

				<th class="noExport">
					{{ Form::checkbox('checkbox_all', 'checkbox_all', null, ['id' => 'checkbox_all'] ) }} <label for="checkbox_all"><span></span></label>
				</th>
				<th>Jobcard Number</th>
				<th> Vehicle Details </th>
				<th style="width:125px"> Customer</th>
				<th> Assigned To </th>
				<th> Job Value </th>
				<th  style="width:100px">Advance amount</th>
				<th> Job Date</th>
				<!-- <th> Due Date</th> -->
				<th> Job Status </th>
				<th>Actions</th>
			</thead>

			<tbody>
			@foreach($transactions as $transaction)

				<tr>
					<td width="1" style="padding-left: 7px;">
						{{ Form::checkbox('transaction',$transaction->id, null, ['id' => $transaction->id, 'class' => 'item_checkbox','data-id' => $transaction->vehicle_id,'data-job_card_status_id' =>  $transaction->job_card_status_id  ]) }}
						<label for="{{$transaction->id}}"><span></span></label>
					</td>
					<td>
						<a style="color: #3366ff;" class="po_edit" data-id="{{$transaction->id}}" data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->order_no }}</a>
					</td>
					<td>{{ $transaction->registration_no }}</td>
					<td>{{ $transaction->customer }}</td>
					<td>{{ $transaction->assigned_to }}</td>
					<td>{{ $transaction->jobcard_total }}</td>
					<td>{{ $transaction->advance_amount}}</td>
					 <td>{{ $transaction->job_date }}</td>
					<!-- <td>{{ $transaction->job_due_date }}</td> -->
					<!-- <td>{{ $transaction->jobcard_status }}</td> -->
					<td>
						@if($transaction->job_card_status_id == '1')
					  	<label class="grid_label badge badge-default job_status">New</label>
						@elseif($transaction->job_card_status_id == '2')
						  	<label class="grid_label badge badge-success job_status">First Inspected</label>
						@elseif($transaction->job_card_status_id == '3')
						  	<label class="grid_label badge badge-warning job_status">Estimation Pending</label>
						@elseif($transaction->job_card_status_id == '4')
						  	<label class="grid_label badge badge-danger job_status">Estimation Approved</label>
						@elseif($transaction->job_card_status_id == '5')
						  	<label class="grid_label badge badge-default job_status">Work in Progress</label>
						@elseif($transaction->job_card_status_id == '6')
						  	<label class="grid_label badge badge-primary job_status">Final Inspected</label>
						@elseif($transaction->job_card_status_id == '7')
						  	<label class="grid_label badge badge-info job_status">Vehicle Ready</label>
						@elseif($transaction->job_card_status_id == '8')
						  	<label class="grid_label badge badge-warning job_status">Closed</label>
						@endif


						<select style="display:none" id="{{ $transaction->id }}" class="active_status form-control">
							<option @if($transaction->job_card_status_id == 1) selected="selected" @endif value="1">New</option>
							<option @if($transaction->job_card_status_id == 2) selected="selected" @endif value="2">First Inspected</option>
							<option @if($transaction->job_card_status_id == 3) selected="selected" @endif value="3">Estimation Pending</option>
							<option @if($transaction->job_card_status_id == 4) selected="selected" @endif value="4">Estimation Approved</option>
							<option @if($transaction->job_card_status_id == 5) selected="selected" @endif value="5">Work in Progress</option>
							<option @if($transaction->job_card_status_id == 6) selected="selected" @endif value="6">Final Inspected</option>
							<option @if($transaction->job_card_status_id == 7) selected="selected" @endif value="7">Vehicle Ready</option>
							<option @if($transaction->job_card_status_id == 8) selected="selected" @endif value="8">Closed</option>
						</select>
					</td>
					<td>
						<div class="action_options">
						</div>
						<button type="button" class="btn btn-info" id="job_card_actions"  ><span class="fa fa-caret-left"></span>&nbsp;Action</button>


						<!-- <div class="dropup">
						    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Actions
						    <span class="caret"></span></button>
						    <ul class="dropdown-menu dropdown-menu-right" style="width: 140px;padding:12px;background-color:rgb(247, 236, 214) !important;left: -80px !important;" id="actions_list">
						    <li><a style="color: black;" class="po_edit hover" data-id="{{$transaction->id}}" data-vehicle_id="{{$transaction->vehicle_id}}"  id="{{$transaction->id}}">View</a></li>
						      <li><a  class="actions_edit" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Edit</a></li>
						      <li><a class="tab_print_btn" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Print</a></li>
						      <li><a class="actions_sms" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">SMS</a></li>
						       <li><a href="#" class="actions_multidelete" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Delete</a></li>
						       <li><a href="#" class="pay_advance" data-name="jc_payment" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;"> Advance</a></li>
						       <li><a href="#" class="job_make_transaction approval_status  jobcard-estimation" data-name="job_request" data-ref ="jobcard-estimation" id="invoice_credit" data-id="{{$transaction->id}}" style="color: black;">Create Estimation</a></li>

						      <li><a href="#" class="view_estimations" data-type_name="job_request" data-identity_name="job_request" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">View Estimation</a></li>
						      <li><a href="#" class="job_make_transaction approval_status  hover jobcard-invoice_credit" data-ref ="jobcard-invoice_credit" data-name="job_invoice" id="invoice_credit" data-id="{{$transaction->id}}" style="color: black;">Create Invoice</a></li>

						      <li><a href="#" class="view_estimations" data-type_name="job_invoice" data-identity_name="invoice" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">View Invoice</a></li>
						      <li><a href="#"  class="view_estimations" data-type_name="job_invoice" data-identity_name="wms_receipt" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Pay Invoice bill</a></li>


						    </ul>
											  </div> -->
					</td>
				</tr>

				@endforeach

		</table>

	@endif

	@if($transaction_type->name == "job_request")

		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>

				<th class="noExport">
					{{ Form::checkbox('checkbox_all', 'checkbox_all', null, ['id' => 'checkbox_all'] ) }} <label for="checkbox_all"><span></span></label>
				</th>
				<th>Estimation Number</th>
				<th>Job Card Number</th>
				<th> Vehicle Details </th>
				<th> Service Type</th>
				<th> Customer </th>
				<th> Estimation Amount </th>
				<th> Estimation Date</th>
				<th> Estimation Status </th>
				<th>Actions</th>
			</thead>

			<tbody>
			@foreach($transactions as $transaction)

				<tr>
					<td width="1" style="padding-left: 7px;">{{ Form::checkbox('transaction',$transaction->id, null, ['id' => $transaction->id, 'class' => 'item_checkbox','data-id' =>$transaction->vehicle_id,'data-approval_status' =>  $transaction->approval_status]) }}<label for="{{$transaction->id}}"><span></span></label>
					</td>
					<td>
						<a style="color: #3366ff;" class="po_edit" data-id="{{$transaction->id}}" data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->order_no }}</a>
					</td>
					<td>
					<!-- removed reference class because of it calls another function too  ..we want to call go_to_jc class only-->
						<a style="color: #3366ff;" class="go_to_jc" data-id="{{$transaction->originated_from_id}}" data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->reference_no}}</a>
					</td>

					<td>{{ $transaction->registration_no }}</td>
					<td>{{ $transaction->service_type }}</td>
					<td>{{ $transaction->customer }}</td>
					<td>{{ $transaction->jobcard_total }}</td>
					<td>{{ $transaction->job_date }}</td>
					<td>
						@if($transaction->approval_status == 0)
							<label class="grid_label badge badge-warning status">Draft</label>
						@elseif($transaction->approval_status == 1)
							<label class="grid_label badge badge-success status">Approved</label>
						@endif
					</td>
					<td>
						<div class="action_options">
						</div>
						<button type="button" class="btn btn-info" id="job_request_actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button>

						<!-- <div class="dropup">
						    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Actions
						    <span class="caret"></span></button>
						    <ul class="dropdown-menu dropdown-menu-right" style="width: 120px;padding:12px;background-color:rgb(247, 236, 214) !important;" id="actions_list">
						      <li><a style="color: black;" class="po_edit hover" data-id="{{$transaction->id}}" data-vehicle_id="{{$transaction->vehicle_id}}"  id="{{$transaction->id}}" >View</a></li>
						      <li><a  class="actions_edit" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Edit</a></li>


						      <li><a class="actions_sms" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">SMS</a></li>

						      <li><a class="actions_multiapprove" data-status="1" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;"> Approve</a></li>
						      <li><a class="actions_un_approve" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: #b3acac;"> Unapprove</a></li>
						      <li><a href="#" class="actions_multidelete" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;"> Delete</a></li>
						       <li><a href="#" class="job_make_transaction approval_status  hover jobcard-invoice_credit" data-ref ="jobcard-invoice_credit" data-name="job_invoice" id="invoice_credit" data-id="{{$transaction->id}}" style="color: black;">Create Invoice</a></li>

						      <li><a href="#" class="view_estimations" data-type_name="job_invoice" data-identity_name="job_request" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">View Invoice</a></li>
						       <li><a class="view_prints" data-name="job_request" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Print</a></li>



						    </ul>
						</div> -->

					</td>
				</tr>

				@endforeach

		</table>

	@endif


	@if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash' )


		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>

				<th class="noExport">
					{{ Form::checkbox('checkbox_all', 'checkbox_all', null, ['id' => 'checkbox_all'] ) }} <label for="checkbox_all"><span></span></label>
				</th>
				<th>Invoice Number</th>
				<th style= "@if($transaction_type->module == 'fuel_station') display:none @endif"> Job Card Number</th>
				<th style= "@if($transaction_type->module == 'fuel_station') display:none @endif"> Job Estimate Number</th>
				<th> Vehicle Details </th>
				<th> Customer </th>
				<!-- <th> Assigned To</th> -->
				<th> Invoice Amount </th>
				<th style= "@if($transaction_type->module == 'fuel_station') display:none @endif" > Advance Amount </th>
				<th> Invoice Date </th>
				<!-- <th> Due Date </th> -->
				<th> Balance Due </th>
				<th> Payment Status </th>
				<th> Invoice Status </th>
				<th>Actions</th>
			</thead>

			<tbody>
			@foreach($transactions as $transaction)

				<tr>
					<td width="1" style="padding-left: 7px;">{{ Form::checkbox('transaction',$transaction->id, null, ['id' => $transaction->id, 'class' => 'item_checkbox','data-id'=>$transaction->vehicle_id,'data-approval_status' =>  $transaction->approval_status]) }}<label for="{{$transaction->id}}"><span></span></label>
					</td>
					<td>
						<a style="color: #3366ff;" class="po_edit" data-id="{{$transaction->id}}"  data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->order_no }}</a>
					</td>
					<td style= "@if($transaction_type->module == 'fuel_station') display:none @endif"><a style="color: #3366ff;" class="go_to_jc" data-id="{{$transaction->originated_from_id}}" data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->jc_order_no }}</a></td>
					<!--To show estimation refference number in invoice list page...-->
					<td><a style="color: #3366ff;" class="reference" data-id="{{$transaction->reference_id}}" data-vehicle_id="{{$transaction->vehicle_id}}">{{ $transaction->estimate_reference_no }}</a></td>
					<td>{{ $transaction->registration_no }}</td>
					<td>{{ $transaction->customer }}</td>
					<!-- <td>{{ $transaction->assigned_to }}</td> -->
					<td>{{ $transaction->jobcard_total }}</td>
					<td style= "@if($transaction_type->module == 'fuel_station') display:none @endif">{{ $transaction->advance_amount }}</td>
					<td>{{ $transaction->job_date }}</td>
					<!-- <td>{{ $transaction->job_due_date }}</td> -->
					<td>{{ $transaction->balance }}</td>
					<td>
						@if($transaction->status == 0)
							<label class="grid_label badge badge-warning">Pending</label>
						@elseif($transaction->status == 1)
							<label class="grid_label badge badge-success">Paid</label>
						@elseif($transaction->status == 2)
							<label class="grid_label badge badge-info">Partially Paid</label>
						@elseif($transaction->status == 3)
							<label class="grid_label badge badge-danger">Over due {{App\Custom::time_difference(Carbon\Carbon::now()->format('Y-m-d H:i:s'), Carbon\Carbon::parse($transaction->original_due_date)->format('Y-m-d'), 'd')}} days</label>
						@endif
					</td>
					<td>
						@if($transaction->approval_status == 0)
							<label class="grid_label badge badge-warning status">Draft</label>
						@elseif($transaction->approval_status == 1)
							<label class="grid_label badge badge-success status">Approved</label>
						@endif
					</td>
					<td>
						<div class="action_options">
						</div>
						<button type="button" class="btn btn-info" id="job_invoice_actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button>

						<!-- <div class="dropup">
						    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Actions
						    <span class="caret"></span></button>
						    <ul class="dropdown-menu dropdown-menu-right" style="width: 120px;padding:12px;left:-80px !important;background-color:rgb(247, 236, 214) !important;" id="actions_list">
						     <li><a style="color: black;" class="po_edit hover" data-id="{{$transaction->id}}" data-vehicle_id="{{$transaction->vehicle_id}}" id="{{$transaction->id}}" disabled="disabled">View</a></li>
						      <li><a  class="actions_edit" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Edit</a></li>
						     <li><a class="print" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Print</a></li>

						      <li><a class="actions_sms" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">SMS</a></li>

						      <li><a class="actions_multiapprove" data-status="1" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Approve</a></li>
						      <li><a class="actions_un_approve" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: #b3acac;">Unapprove</a></li>
						      <li><a href="#" class="actions_multidelete" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Delete</a></li>
						       @if($transaction_type->name != 'job_invoice_cash' )

						     <li><a href="#"  class="pay_for_invoice" data-identity_name="wms_receipt" data-name="invoice_payment" id="{{$transaction->id}}" data-id="{{$transaction->id}}" style="color: black;">Payment</a></li>
						     <li><a href="#"  class="view_estimations" data-type_name="job_invoice" data-identity_name="wms_receipt" data-name="{{$transaction_type->name}}" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Pay Invoice bill</a></li>
						      @endif
						       <li><a class="view_prints"  data-name="job_invoice" id="{{$transaction->id}}" data-id="{{$transaction->vehicle_id}}" style="color: black;">Print</a></li>




						    </ul>
						</div> -->
					</td>
				</tr>

				@endforeach

		</table>

	@endif


</div>
<div class="pdf_view_print" style="display: none;">


</div>
@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>  -->
@if(app()->environment() == "production")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.13/daterangepicker.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
@elseif(app()->environment() == "local")
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
@endif

<script type="text/javascript">

	var datatable = null;

	var isFirstIteration = true;

	var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [],

		dom: 'lBfrtip',
		buttons: [
			{
			extend: 'pdf',
				footer: true,
				exportOptions: {
					columns: [1,2]
				}
			},
			{
				extend: 'csv',
				footer: false,
				exportOptions: {
					columns: [1,2]
				}
			},
			{
				extend: 'excel',
				exportOptions: {
					columns: ":not(.noExport)"
				},
				footer: false
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ":not(.noExport)",
					stripHtml: false,
				},
				autoPrint: true
			}
		]


	};
	/*var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": []};*/

	function image_call_back(image, id) {
    	$('body').find('.edit[data-id="' + id + '"]').closest('tr').find('img').attr('src', image);
	}
	//to go new ui jobcard from estimation list page
	//start
	$('body').on('click','.go_to_jc',function()
	{
		var data = $(this).data('id');
		console.log("data"+data);
		redirectEditPage(data);

	});


	function redirectEditPage(data)
	{
		console.log("redirectEditPage"+data);

		var url = '{{ route("jobcard.edit", ":id") }}';
		url = url.replace(':id', data);
		console.log("redirectEditPage"+data);
		new imageLoader(cImageSrc, 'startAnimation()');
		window.location.href = url;
	}
	//end

	$('table').find('tbody tr').find('td:first :checkbox').prop('checked', false);
	$('table').find('thead tr th:first :checkbox').prop('indeterminate', false);

	$('.un_approve').on('click',function(){
		//alert();

		var id = $('input[name=transaction]:checked').val();
		$.ajax({
			url : '{{ route('transaction_un_approve') }}',
			type: 'get',
			data:
			{
				id : id
			},
			success:function(data)
			{
				location.reload();
			},
			error:function()
			{

			}
		});

	});


	//from this below end this command  for actions button related coding
	//print option in action button

	//this is options list for job card actions when click Action button in job card it will open
	$('body').on('click','#job_card_actions',function(e){
		e.preventDefault();
		//alert();
		var html ='';
		var obj = $(this);
		var id = $(this).closest('tr').find('input[name=transaction]').attr('id');

		var vehicle_id = $(this).closest('tr').find('input[name=transaction]').attr('data-id');
		var job_card_status_id = $(this).closest('tr').find('input[name=transaction]').attr('data-job_card_status_id');


		var action_length = $('.list_options');

		if(action_length.length > 0)
		{
			$('.list_options').remove();
			$('input[name=transaction]').prop('checked',false);
			$('tr').removeAttr('style');



		}
		html+=`<div style="background-color:rgb(247, 236, 214);height:200px;width:140px;position:absolute;right:100px;margin-top:-120px;border:2px solid #c4b4b4;border-radius:10px 2px 10px 10px;" class="list_options">
			<div>
			<span class="fa fa-times-circle close" style="float:right;padding-right:10px;font-size:20px;"></span>
			 <ul   id="actions_list" style="padding:10px;">

		      <li><a  class="actions_edit" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Edit</a></li>
		      <li><a class="tab_print_btn" id="`+id+`"  style="color: black;">Print</a></li>
		      <li><a class="actions_sms" id="`+id+`"  style="color: black;">SMS</a></li>
		       <li><a href="#" class="actions_multidelete" data-name="{{$transaction_type->name}}" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Delete</a></li>
		       <li><a href="#" class="pay_advance" data-name="jc_payment" id="`+id+`" data-id="`+id+`" style="color: black;"> Advance</a></li>

		       	  <li><a href="#" class="job_make_transaction approval_status  jobcard-estimation" data-name="job_request" data-ref ="jobcard-estimation" id="invoice_credit" data-id="`+id+`" style="color: black;">Create Estimation</a></li>`;





		      html+=`<li><a href="#" class="view_estimations" data-type_name="job_request" data-identity_name="job_request" data-name="{{$transaction_type->name}}" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">View Estimation</a></li>`;
		      if(job_card_status_id == 6 || job_card_status_id == 7)
		       {
		       	   html+=`<li><a href="#" class="job_make_transaction approval_status  hover jobcard-invoice_credit" data-ref ="jobcard-invoice_credit" data-name="job_invoice" id="invoice_credit" data-id="`+id+`" style="color: black;">Create Invoice</a></li>`;

		       }
		       else
		       {
		       	 html+=`<li><a href="#" class="" data-ref ="jobcard-invoice_credit" data-name="job_invoice" id="invoice_credit" data-id="`+id+`" style="color: #808080; data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status"">Create Invoice</a></li>`;
		       }


		     html+=`<li><a href="#" class="view_estimations" data-type_name="job_invoice" data-identity_name="invoice" data-name="{{$transaction_type->name}}" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">View Invoice</a></li>
		      <li><a href="#"  class="pay_bill" data-type_name="wms_receipt" data-identity_name="job_invoice_payment" data-name="{{$transaction_type->name}}" id="`+id+`" style="color: black;">Pay Invoice bill</a></li>						    </ul></div></div>`;


		var options = obj.prev('.action_options').append(html);
		$(this).closest('tr').find('input[name=transaction]').prop('checked',true);
		$(this).closest('tr').css('background-color','rgb(193, 197, 202)');

	});


	//this is options list for job request actions when click Action button in job card it will open
	$('body').on('click','#job_request_actions',function(e){
		e.preventDefault();
		//alert();
		var html='';
		var obj = $(this);
		var id = $(this).closest('tr').find('input[name=transaction]').attr('id');

		var vehicle_id = $(this).closest('tr').find('input[name=transaction]').attr('data-id');

		var approval_status = $(this).closest('tr').find('input[name=transaction]').attr('data-approval_status');

		var transaction_type = '{{ $transaction_type->name}}';

		var action_length = $('.list_options');

		if(action_length.length > 0)
		{
			$('.list_options').remove();
			$('input[name=transaction]').prop('checked',false);
			$('tr').removeAttr('style');

		}

		html+=`<div style="background-color:rgb(247, 236, 214);height:160px;width:140px;position:absolute;right:100px;margin-top:-120px;border:2px solid #c4b4b4;border-radius:10px 2px 10px 10px;" class="list_options">
			<div>
			<span class="fa fa-times-circle close" style="float:right;padding-right:10px;font-size:20px;"></span>
			 <ul   id="actions_list" style="padding:10px;">

		      <li><a  class="actions_edit" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Edit</a></li>


		      <li><a class="actions_sms" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">SMS</a></li>

		      <li><a class="actions_multiapprove" data-status="1" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;"> Approve</a></li>
		      <li><a class="actions_un_approve" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;" data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status"> Unapprove</a></li>
		      <li><a href="#" class="actions_multidelete" data-name="`+transaction_type+`" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;"> Delete</a></li>`;
		      if(approval_status == 1)
		      {
		      	 html+=`<li><a href="#" class="job_make_transaction approval_status  hover jobcard-invoice_credit" data-ref ="jobcard-invoice_credit" data-name="job_invoice" id="invoice_credit" data-id="`+id+`" style="color: black;">Create Invoice</a></li>`;
		      }
		      else
		      {
		      	html+=`<li><a href="#" class="" data-ref ="jobcard-invoice_credit" data-name="job_invoice" id="invoice_credit" data-id="`+id+`" style="color:  #808080;" data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status">Create Invoice</a></li>`;
		      }


		      html+=`<li><a href="#" class="view_estimations" data-type_name="job_invoice" data-identity_name="job_request" data-name="`+transaction_type+`" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">View Invoice</a></li>
		       <li><a class="view_prints" data-name="job_request" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Print</a></li>
		       </ul></div></div>`;


		var options = obj.prev('.action_options').append(html);
		$(this).closest('tr').find('input[name=transaction]').prop('checked',true);
		$(this).closest('tr').css('background-color','rgb(193, 197, 202)');

	});

	//this is options list for job invoice actions when click Action button in job card it will open
	$('body').on('click','#job_invoice_actions',function(e){
		e.preventDefault();
		//alert();
		var html='';
		var obj = $(this);
		var id = $(this).closest('tr').find('input[name=transaction]').attr('id');

		var approval_status = $(this).closest('tr').find('input[name=transaction]').attr('data-approval_status');



		var vehicle_id = $(this).closest('tr').find('input[name=transaction]').attr('data-id');

		var transaction_type = '{{ $transaction_type->name}}';

		var action_length = $('.list_options');

		if(action_length.length > 0)
		{
			$('.list_options').remove();
			$('input[name=transaction]').prop('checked',false);
			$('tr').removeAttr('style');

		}




			html+=`<div style="background-color:rgb(247, 236, 214);height:140px;width:140px;position:absolute;right:100px;border:2px solid #c4b4b4;border-radius:10px 2px 10px 10px;margin-top:-120px;" class="list_options">
			<div>
			<span class="fa fa-times-circle close" style="float:right;padding-right:10px;font-size:20px;"></span>
			 <ul   id="actions_list" style="padding:10px;">

		      <li><a  class="actions_edit" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Edit</a></li>

		      <li><a class="actions_sms" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">SMS</a></li>


		      <li><a href="#" class="actions_multidelete" data-name="`+transaction_type+`" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Delete</a></li>`;
		      
			       if(transaction_type != 'job_invoice_cash' ) 
			       {
						if(approval_status == 1)
						{
							console.log("Approved");
				     	html+=`<li><a href="#"  class="pay_bill"  data-type_name="wms_receipt" data-name="invoice_payment" data-identity_name="payment" id="`+id+`" style="color: black;">Payment</a></li>`;
				     		    
				     	}
				     	else
				     	{
				     		console.log("not approve");
				     		html+=`<li><a href="#"  class=""  data-type_name="wms_receipt" data-name="invoice_payment" data-identity_name="payment" id="`+id+`" style="color: #808080;"  data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status">Payment</a></li>`;

				     	}
			     	}
		       html+=`<li><a class="view_prints"  data-name="job_invoice" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Print</a></li>						    </ul></div></div>`;
		      // obj.prev('.action_options').css("position","absolute");
		var options = obj.prev('.action_options').append(html);
		$(this).closest('tr').find('input[name=transaction]').prop('checked',true);
		$(this).closest('tr').css('background-color','rgb(193, 197, 202)');


	});

	//this is options list for trade and inventory actions when click Action button in job card it will open
	$('body').on('click','#actions',function(e){
		e.preventDefault();
		//alert();
		var html='';
		console.log("actions");
		var obj = $(this);
		var id = $(this).closest('tr').find('input[name=transaction]').attr('id');
		console.log("id"+id);

		var approval_status = $(this).closest('tr').find('input[name=transaction]').attr('data-approval_status');
		console.log(approval_status);
		
		var vehicle_id = $(this).closest('tr').find('input[name=transaction]').attr('data-id');
		
		var transaction_type = '{{ $transaction_type->name}}';
		var type = "{{ $type }}";
		
		
		var action_length = $('.list_options');
		
		if(action_length.length > 0)
		{
			$('.list_options').remove();
			$('input[name=transaction]').prop('checked',false);
			$('tr').removeAttr('style');
			
		}
		

			html+=`<div style="background-color:rgb(247, 236, 214);height:200px;width:140px;position:absolute;right:100px;border:2px solid #c4b4b4;border-radius:10px 2px 10px 10px;margin-top:-120px;" class="list_options">
			<div>
			<span class="fa fa-times-circle close" style="float:right;padding-right:10px;font-size:20px;"></span>	
			 <ul   id="actions_list" style="padding:10px;">      
			 	 
		       <li><a  class="actions_edit" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">Edit</a></li>`;
			       	if(type == "purchase_order")
			       	{
					html+=`<li><a  class="approval_status tab_print_btn"  id="`+id+`" style="color: black;">Print PO </a></li>`;
					}
					else if(type == "purchases")
					{
					html+=`<li><a class="tab_print_btn" id="`+id+`" style="color: black;">Print Purchase </a></li>`;
					}
					else if(type == "goods_receipt_note")
					{
					html+=`<li><a class="tab_print_btn" id="`+id+`" style="color: black;">Print GRN </a></li>`;
					}
					else if(type == "debit_note" || type == "credit_note")
					{
					html+=`<li><a class="tab_print_btn" id="`+id+`" style="color: black;">Print Return </a></li>`;
					}
					else if(type == "sale_order")
					{
					html+=`<li><a class="tab_print_btn" id="`+id+`" style="color: black;">Print SO </a></li>`;
					}
					else if(type == "sales_cash" || type == "sales")
					{
					html+=`<li><a class="tab_print_btn" id="`+id+`" style="color: black;">Print Invoice </a></li>`;
					}
			
			      html+=`<li><a class="actions_sms sms_limit" id="`+id+`" data-id="`+id+`" style="color: black;">SMS</a></li>
			      <li><a href="#" class="actions_multidelete" data-name="`+transaction_type+`" id="`+id+`" data-id="`+id+`" style="color: black;"> Delete</a></li>
			      <li><a href="#" class="actions_multiapprove" data-status="1" id="`+id+`" style="color: black;" >Approve</a></li>`;
			      if(type != 'credit_note' || type != 'debit_note')
			      {
			      html+=`<li><a href="#" class="actions_un_approve" data-status="1" id="`+id+`" style="color: black;" data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status"> UnApprove</a></li>`;
			     }
			      if(type == 'sales')
			      {
			      	if(approval_status == 1)
			      	{
			      		 html+=`<li><a href="#" class="pay_bill" data-identity_name="payment" data-type_name="receipt"  id="`+id+`" style="color: black;">Payment</a></li>`;
			      	}
			      	else
			      	{
			      		 html+=`<li><a href="#" class="" data-identity_name="payment" data-type_name="receipt"  id="`+id+`" style="color: #808080;" data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status">Payment</a></li>`;

			      	}
			    
			     
			      	if(approval_status == 1)
			      	{
			      		html+=`<li><a href="#" class="make_transaction approval_status" data-name="delivery_note" id="`+id+`" data-id="`+id+`" style="color: black;"> Create DN</a></li>`;
			      	}
			      	else
			      	{
			      		html+=`<li><a href="#" class="" data-name="delivery_note" id="`+id+`" data-id="`+id+`" style="color: #808080;" data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status"> Create DN</a></li>`;
			      	}
			      
			      html+=`<li><a href="#" class="view_estimations" data-type_name="delivery_note" data-name="`+transaction_type+`" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">View DN</a></li>`;
			     /* if(approval_status == 1)
			      {*/
			      	html+=`<li><a href="#" class="make_transaction approval_status" data-name="credit_note" data-ref="credit_note" id="`+id+`" data-id="`+id+`" style="color: black;"> Create sales return</a></li>`;
			     /* }
			      else
			      {
			      	html+=`<li><a href="#" class="" data-name="credit_note" data-ref="credit_note" id="`+id+`" data-id="`+id+`" style="color: #808080;"> Create sales return</a></li>`;
			      }*/
			     
			      }
			      if(type == 'purchases')
			      {
			      	if(approval_status == 1)
			      	{
			      		html+=`<li><a href="#" class="pay_bill" data-identity_name="payment" data-type_name="payment" id="`+id+`"  style="color: black;">Payment</a></li>`;
			      	}
			      	else
			      	{
			      		html+=`<li><a href="#" class="" data-identity_name="payment" data-type_name="payment" id="`+id+`"  style="color: #808080;" data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status">Payment</a></li>`;
			      	}
			      	if(approval_status == 1)
			      	{
			      		  html+=`<li><a href="#" class="make_transaction approval_status po_to_grn" data-name="goods_receipt_note" data-ref="po_to_grn" id="`+id+`" data-id="`+id+`" style=" color: black;"> Create GRN</a></li>`;
			      	}
			      	else
			      	{
			      		 html+=`<li><a href="#" class="make_transaction approval_status po_to_grn" data-name="goods_receipt_note" data-ref="po_to_grn" id="`+id+`" data-id="`+id+`" style=" color: #808080;" data-toggle="tooltip" data-placement="top" title="Action is not permitted in this status"> Create GRN</a></li>`;
			      	}
			      
			    				   
			
			      html+=`<li><a href="#" class="view_estimations" data-type_name="goods_receipt_note" data-name="`+transaction_type+`" id="`+id+`" data-id="`+vehicle_id+`" style="color: black;">View GRN</a></li>
			      <li><a href="#" class="make_transaction approval_status " data-ref="debit_note" data-name="debit_note" id="`+id+`" data-id="`+id+`" style="color: black;"> Create purchase return</a></li>`;
			      }						  
			      html+=` </ul></div></div>`;
		var options = obj.prev('.action_options').append(html);
		$(this).closest('tr').find('input[name=transaction]').prop('checked',true);
		$(this).closest('tr').css('background-color','rgb(193, 197, 202)');


	});


	//to close option list 
	$('body').on('click','.close',function(){
		//alert();
		var action =$(this).closest('.list_options');
		$(this).closest('.list_options').remove();
		$('input[name=transaction]').prop('checked',false); 
		$('tr').removeAttr('style');


	

	});

	$(document).on( 'keydown', function ( e ) {
    if ( e.keyCode === 27 ) {      
        var action_length = $('.list_options');
	
		if(action_length.length > 0)
		{
			$('.list_options').hide();
			$('input[name=transaction]').prop('checked',false);
			$('tr').removeAttr('style');

			
			
		}
    }
	});



	$('body').on('click','.tab_print_btn', function(e) {
		var id = $(this).attr('id');

		print_transaction(id);

	});
	//to show small popup to view,estimation,invoice,grn,dn


	$('body').on('click','.view_estimations',function(e){
		
		e.preventDefault();
		$.get("{{ url('get_estimations_view') }}/"+$(this).attr('id')+"/"+$(this).attr('data-type_name'),function(data){
			//console.log(data);
			$('#centralModalSm .modal-body').html("");
			$('#centralModalSm .modal-body').html(data);


		});
		$('#centralModalSm').modal('show');

	});


	$('body').on('click','.pay_bill',function(e){
		
		e.preventDefault();
		$.get("{{ url('get_estimations_views') }}/"+$(this).attr('id')+"/"+$(this).attr('data-type_name')+"/"+$(this).attr('data-identity_name'),function(data){
			//console.log(data);
			$('#centralModalSm .modal-body').html("");
			$('#centralModalSm .modal-body').html(data);


		});
		$('#centralModalSm').modal('show');

	});



	//to show print in invoice list page
	$('body').on('click','.view_prints',function(e){
		e.preventDefault();
		//alert();
		$.get("{{ url('get_prints_view') }}/"+$(this).attr('id')+"/"+$(this).data('name'),function(data){
			//console.log(data);
			$('#centralModalSm .modal-body').html("");
			$('#centralModalSm .modal-body').html(data);


		});
		$('#centralModalSm').modal('show');

	});

	$('body').on('click', '.actions_smss', function(e) {
			
			e.preventDefault();
			isFirstIteration = true;
			var id = $(this).attr('id');			

		       	$.ajax({
					url: "{{ route('transaction.estimation_sms') }}" ,
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						type: '{{ $transaction_type->name }}',
						id: id
					},
					success: function(data, textStatus, jqXHR) {
                       alert_message(data.message, "success");


					}
				});
			
	});

	//sms poup
	$('body').on('click', '.actions_sms', function(e) {
			e.preventDefault();
			isFirstIteration = true;
			var id = $(this).attr('id');
			
		       	$.ajax({
					url: "{{ route('job_invoice_sms') }}" ,
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						type: '{{ $transaction_type->name }}',
						id: id
					},
					success: function(data, textStatus, jqXHR) {

					$('.crud_modal .modal-container').html("");
					$('.crud_modal .modal-container').html(data);
					$('.crud_modal').find('.modal-dialog').addClass('modal-md');
					$('.crud_modal').modal('show');
                       
					}
				});
			
	});

	//job card status change
	$('body').on('click', '.job_status', function(e) {
		e.preventDefault();
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

	/*copy transaction*/
		$('body').on('click','.make_transaction', function(e) {

			e.preventDefault();
			//$('.loader_wall_onspot').show();
			var obj = $(this);
			var id = obj.data('id');
			var transaction_name = obj.data('name');
			var transaction_type =  ('{{ $transaction_type->name }}');
			var transaction_module =  ('{{ $transaction_type->module }}');

			
			if(transaction_type == "job_card"){

				var obj = $(this);
				var id = obj.data('id');
				var transaction_name = obj.data('name');
				//e.preventDefault();	
				
				var job_status_id = $('select[name=jobcard_status_id]').val();
				

				if(transaction_name == "job_invoice" || transaction_name == "job_invoice_cash"){

					if(job_status_id == 1 || job_status_id == 2 || job_status_id == 3 || job_status_id == 4 || job_status_id == 5 || job_status_id == 8)
					{						
						alert_message("Copy Invoice is allowed only for jobcard status is Final Inspected or Vehicle Ready","error");
						return false;
					}
					else{

						$('<form>', {
					    "id": "dynamic_form",
					    "method": "POST",
					    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',
					    "action": '{{ route("add_to_account") }}'
						}).appendTo(document.body).submit();

						$('#dynamic_form').remove();

					}

				}

				else{

					$('<form>', {
				    "id": "dynamic_form",
				    "method": "POST",
				    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',
				    "action": '{{ route("add_to_account") }}'
					}).appendTo(document.body).submit();

				$('#dynamic_form').remove();

				}

			}

			if(transaction_type != "job_card"){

				$('<form>', {
				    "id": "dynamic_form",
				    "method": "POST",
				    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',
				    "action": '{{ route("add_to_account") }}'
					}).appendTo(document.body).submit();

				$('#dynamic_form').remove();
			}			

		});
	/*end*/

  	//job card status update
  	$('body').on('change', '.active_status', function(e) {

  		var status = $(this).val();
		var id = $(this).attr('id');
		var obj = $(this);
		
		$.ajax({
			 url: '{{ route('job_card_status.update') }}',
			 type: 'post',
			 data: {
			 	_token: "{{csrf_token()}}",
			 	id: id,
			 	status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					//console.log(data.status);
					if(status == 1) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						
						obj.parent().find('label').addClass('badge-default');
					}else if(status == 2) {

						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');

						obj.parent().find('label').addClass('badge-success');
					}
					else if(status == 3) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');

						obj.parent().find('label').addClass('badge-warning');
					}
					else if(status == 4) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-warning');

						obj.parent().find('label').addClass('badge-danger');
					}
					else if(status == 5) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-warning');


						obj.parent().find('label').addClass('badge-default');
					}
					else if(status == 6) {

						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-danger');
						obj.parent().find('label').removeClass('badge-warning');

						obj.parent().find('label').addClass('badge-primary');
					}
					else if(status == 7) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-warning');

						obj.parent().find('label').addClass('badge-info');

					}
					else if(status == 8) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').removeClass('badge-primary');
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').removeClass('badge-default');
						obj.parent().find('label').removeClass('badge-danger');

						obj.parent().find('label').addClass('badge-warning');
					}
					obj.hide();
					obj.parent().find('label').show();
					obj.parent().find('label').text(obj.find('option:selected').text());
				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
	
		
		
	});

	// to open print for trade_wms invoice in index page


	$('.invoice_print').on('click',function(){
        var transaction_id = $(this).attr('data-id');
        var data = $(this).attr('data-formate');
        $('.loader_wall_onspot').show();
       // $('#myModal_popup_show1').show();
			//$('body').css('overflow', 'hidden');
			/*$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {*/

				$.ajax({
					url: "{{ route('print_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: transaction_id,
						data:data

					},
					success:function(data, textStatus, jqXHR) {
					//console.log(data);
					//console.log(data.transaction_data);

					// I added new popup modal to print so hid this

                         
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();
						//$('.print_content').hide();


						var container = $('.print_content').find("#print");


						//new coding to show new popup 
						/*$('.print_popup_content').show();
						
						$('.print_popup_content').hide();


						var container = $('.print_popup_modal').find("#print_value");*/
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='voucher_type']").text(data.transaction_type);
							container.find("[data-value='po']").text(data.po_no);
							container.find("[data-value='purchase']").text(data.purchase_no);
							container.find("[data-value='grn']").text(data.grn_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='payment_mode']").text(data.payment_mode);
							container.find("[data-value='resource_person']").text(data.resource_person);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='shipping_address']").text(data.shipping_address);
							container.find("[data-value='billing_address']").text(data.billing_address);
	                        container.find("[data-value='customer_vendor']").text(data.customer_vendor);
	                        container.find("[data-value='vehicle_number']").text(data.vehicle_number);
	                        container.find("[data-value='make_model_variant']").text(data.make_model_variant);
	                        container.find("[data-value='company_name']").text(data.company_name);
	                        container.find("[data-value='company_phone']").text(data.company_phone);
	                        container.find("[data-value='company_address']").text(data.company_address);
	                        container.find("[data-value='email_id']").text(data.email_id);
	                        container.find("[data-value='amount']").text(data.amount);
	                        container.find("[data-value='payment_method']").text(data.payment_method);
	                        container.find("[data-value='km']").text(data.km);
	                        container.find("[data-value='assigned_to']").text(data.assigned_to);
	                        container.find("[data-value='company_gst']").text(data.company_gst);
	                        container.find("[data-value='customer_gst']").text(data.customer_gst);
                             container.find("[data-value='customer_mobile']").text(data.customer_mobile);


							var row = container.find('.invoice_item_table tbody tr').clone();

							var invoice_items = ``;
	                        var total_amount = 0;
	                        var total_discount = 0;
	                        var  total_length= 10;
	                        var length = total_length - (data.invoice_items).length;
							for (var i = 0; i < (data.invoice_items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();
								var discount = data.invoice_items[i].discount;
	                            var discount_value = $.parseJSON(discount);
	                            var amount= data.items[i].rate * data.items[i].quantity - discount_value.amount;
								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.invoice_items[i].name);
								new_row.find('.col_hsn').text(data.invoice_items[i].hsn);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_discount').text(discount_value.amount);
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
								new_row.find('.col_rate').text(parseFloat(data.items[i].rate).toFixed(2));
								new_row.find('.col_amount').text(parseFloat(data.items[i].amount).toFixed(2));
								new_row.find('.col_t_amount').text(parseFloat(amount).toFixed(2));
								
	                           var total_amount = parseFloat(amount)+parseFloat(total_amount);

	                           var total_discount = parseFloat(discount_value.amount)+parseFloat(total_discount);

								invoice_items += `<tr>`+new_row.html()+`</tr>`;
							}

							for(var i=1; i <= length;i++){

								var new_row = row.clone();
	                            
								invoice_items += `<tr>`+new_row.html()+`</tr>`;

							}

							container.find("[data-value='total_discount']").text(total_discount);
		                    container.find("[data-value='total_amount']").text(parseFloat(total_amount).toFixed(2));
							container.find('.invoice_item_table tbody').empty();
							container.find('.invoice_item_table tbody').append(invoice_items);

							var hsn_invoice_tax_values = Object.values(data.hsn_based_invoice_tax);
	                       
	                       //HSN based tax table
	                        var hsn_row = container.find('.hsnbasedTable tbody tr').clone();
	                        var hsn_tax = ``;
	                        var  totalhsn_length= 6;
	                        var hsn_length = totalhsn_length - hsn_invoice_tax_values.length;
	                        for(var i = 0; i < hsn_invoice_tax_values.length; i++){
	                        	var hsn_new_row = hsn_row.clone();
	                        	var taxable = parseFloat(hsn_invoice_tax_values[i].taxable).toFixed(2);
	                        	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                        	var gst = hsn_invoice_tax_values[i].name;
	                        	var sgst = hsn_invoice_tax_values[i].display_name;
	                        	if(sgst != null){
	                                var sgst_value = sgst.split('CGST');
	                        	}else{
	                                  sgst_value = '';
	                        	}
	                            
	                        	if(gst == null){
	                            	var exact_tax = '';
	                            }else{
	                            	var exact_tax = sgst_value[0];
	                            }
	                        	if(hsn_invoice_tax_values[i].tax_type == 1){
	                        	hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text("");
	                            hsn_new_row.find('.col_igst_amount').text("");
	                            hsn_new_row.find('.col_cgst').text(exact_tax);
	                            hsn_new_row.find('.col_cgst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_sgst').text(exact_tax);
	                            hsn_new_row.find('.col_sgst_amount').text(tax_amount);
	                            }else{
	                            	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                            	var igst = hsn_invoice_tax_values[i].display_name;
	                            	if(igst != null){
	                            		var exact_igst = igst.split('IGST');
	                            	}else{
	                            		exact_igst = '';
	                            	}
	                            	
	                            	if(gst == null){
	                            		var exact_tax = '';
	                            	}else
	                            	{
	                            		var exact_tax = exact_igst[0];
	                            	}
	                            hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text(exact_tax);
	                            hsn_new_row.find('.col_igst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_cgst').text("");
	                            hsn_new_row.find('.col_cgst_amount').text("");
	                            hsn_new_row.find('.col_sgst').text("");
	                            hsn_new_row.find('.col_sgst_amount').text("");
	                            }
	                        	hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;
	                        }

	                        for(var i=1; i <= hsn_length;i++){

								var hsn_new_row = hsn_row.clone();
	                            
								hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;


							}
	                        container.find('.hsnbasedTable tbody').empty();
							container.find('.hsnbasedTable tbody').append(hsn_tax);
							var invoice_tax_values = Object.values(data.invoice_tax);
							var tax_row = container.find('.floatedTable tbody tr').clone();
	                        var invoice_tax = ``;
	                        var  total_length= 6;
	                       	var gst_length = total_length - invoice_tax_values.length;
	                        var total_cgst = 0;
	                        var total_sgst = 0;
	                        var total_igst = 0;
	                        for (var i = 0; i < invoice_tax_values.length; i++) {
								var new_row = tax_row.clone();  
	                            var gst = invoice_tax_values[i].name;
	                            var sgst = invoice_tax_values[i].display_name;
	                            var tax_amount = parseFloat(invoice_tax_values[i].Tax_amount).toFixed(2);
	                            var taxable = parseFloat(invoice_tax_values[i].taxable).toFixed(2);
	                            if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';

	                            }else{
	                            	var exact_value = gst.split('GST');
	                            	var exact_sgst = sgst.split('SGST');
	                            }
	                        if(invoice_tax_values[i].tax_type == 1){
								new_row.find('.col_gst').html(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text("");
								new_row.find('.col_igst_amount').text("");
								new_row.find('.col_cgst').text(exact_sgst[0]);
								new_row.find('.col_cgst_amount').text(tax_amount);
								new_row.find('.col_sgst').text(exact_sgst[0]);
								new_row.find('.col_sgst_amount').text(tax_amount);
								var total_cgst = parseFloat(tax_amount)+parseInt(total_cgst);
								var total_sgst = parseFloat(tax_amount)+parseInt(total_sgst);			
								}
							else
							{
	                         	if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';
	                            	var taxable = '';
	                            	var tax_amount = '';
	                            }else{
	                            	var exact_value = gst.split('IGST');
	                            	var exact_sgst = sgst.split('IGST');
	                            }

	                            new_row.find('.col_gst').text(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text(exact_sgst[0]);
								new_row.find('.col_igst_amount').text(tax_amount);
								new_row.find('.col_cgst').text("");
								new_row.find('.col_cgst_amount').text("");
								new_row.find('.col_sgst').text("");
								new_row.find('.col_sgst_amount').text("");
	                    
	                            if (tax_amount == ''){
	                            	var tax_amount = 0;
	                            }else{
	                            	var tax_amount = tax_amount;
	                            }
	                          	var total_igst = parseFloat(tax_amount)+parseInt(total_igst);
							}		
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;							
						}


	             
	                    for(var i=1; i <= gst_length;i++)
	                    {

							var new_row = tax_row.clone();
	                        
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;


						}
			

	                    var  total_tax = total_cgst + total_sgst + total_igst;
	                    var round_of = Math.ceil(total_tax);
	                    var Rount_off_value = round_of - total_tax;
	                    var total = total_tax + total_amount;
	                    var total_amount= Rount_off_value + total;

	                  	var total_withtax = Math.ceil(total_amount);
	                  	var words = new Array();
	                    words[0] = '';
	                    words[1] = 'One';
	                    words[2] = 'Two';
					    words[3] = 'Three';
					    words[4] = 'Four';
					    words[5] = 'Five';
					    words[6] = 'Six';
					    words[7] = 'Seven';
					    words[8] = 'Eight';
					    words[9] = 'Nine';
					    words[10] = 'Ten';
					    words[11] = 'Eleven';
					    words[12] = 'Twelve';
					    words[13] = 'Thirteen';
					    words[14] = 'Fourteen';
					    words[15] = 'Fifteen';
					    words[16] = 'Sixteen';
					    words[17] = 'Seventeen';
					    words[18] = 'Eighteen';
					    words[19] = 'Nineteen';
					    words[20] = 'Twenty';
					    words[30] = 'Thirty';
					    words[40] = 'Forty';
					    words[50] = 'Fifty';
					    words[60] = 'Sixty';
					    words[70] = 'Seventy';
					    words[80] = 'Eighty';
					    words[90] = 'Ninety';
					    amount = total_withtax.toString();
					    var atemp = amount.split(".");
					    var number = atemp[0].split(",").join("");
					    var n_length = number.length;
					    var words_string = "";
					    if (n_length <= 9) {
					        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
					        var received_n_array = new Array();
					        for (var i = 0; i < n_length; i++) {
					            received_n_array[i] = number.substr(i, 1);
					        }
					        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
					            n_array[i] = received_n_array[j];
					        }
					        for (var i = 0, j = 1; i < 9; i++, j++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                if (n_array[i] == 1) {
					                    n_array[j] = 10 + parseInt(n_array[j]);
					                    n_array[i] = 0;
					                }
					            }
					        }
					        value = "";
					        for (var i = 0; i < 9; i++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                value = n_array[i] * 10;
					            } else {
					                value = n_array[i];
					            }
					            if (value != 0) {
					                words_string += words[value] + " ";
					            }
					            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Crores ";
					            }
					            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Lakhs ";
					            }
					            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Thousand ";
					            }
					            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
					                words_string += "Hundred and ";
					            } else if (i == 6 && value != 0) {
					                words_string += "Hundred ";
					            }
					        }
					        words_string = words_string.split("  ").join(" ");
	   					}

	                	container.find("[data-value='total_cgst']").text(total_cgst.toFixed(2));
	                	container.find("[data-value='total_sgst']").text(total_sgst.toFixed(2));
	                	container.find("[data-value='total_igst']").text(total_igst.toFixed(2));
	                	container.find("[data-value='round_off']").text(Rount_off_value.toFixed(2));
	                	container.find("[data-value='total_amountwithtax']").text(parseFloat(total_withtax).toFixed(2));
	                	container.find("[data-value='rupees']").text(words_string+"Only");
	                	container.find('.floatedTable tbody').empty();
	                	container.find('.floatedTable tbody').append(invoice_tax);                     

	                	var row = container.find('.no_tax_item_table tbody tr').clone();

						var no_tax_sale = ``;
	                    var total_tax_amount = 0;
	                    var sub_total_amount = 0;
			               /* for (var i = 0; i < (data.no_tax_sale).length; i++) {
								var j = i + 1;
								var new_row = row.clone();

								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.no_tax_sale[i].name);
								new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
								new_row.find('.col_rate').text(data.no_tax_sale[i].rate);
								new_row.find('.col_discount').text(data.no_tax_sale[i].discount);
								new_row.find('.col_amount').text(parseFloat(data.no_tax_sale[i].amount).toFixed(2));
			                    var tax_amount = data.no_tax_sale[i].tax_amount;
			                    var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);
			                    var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
								no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
							}*/
						var k =0;
							
						for (var i = 0; i < (data.no_tax_sale).length; i++) {
						
							var j = i + 1;
							var new_row = row.clone();
							var unit_rate = data.no_tax_sale[i].rate;
							var discount_amount = data.no_tax_sale[i].discount;
							
							var amount = data.no_tax_sale[i].amount;
							if(unit_rate == undefined){
								unit_rate = 0;
							}else{
								unit_rate = data.no_tax_sale[i].rate;
							}

							if(discount_amount == undefined){
								discount_amount = 0;
							}else{
								discount_amount = data.no_tax_sale[i].discount;
							}

							if(amount == undefined){
								amount = 0;
							}else{
								amount = data.no_tax_sale[i].amount;
							}
							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.no_tax_sale[i].name);
							new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
							new_row.find('.col_rate').text(parseFloat(unit_rate).toFixed(2));
							new_row.find('.col_discount').text(parseFloat(discount_amount).toFixed(2));
							new_row.find('.col_amount').text(parseFloat(amount).toFixed(2));
	                        var tax_amount = data.no_tax_sale[i].tax_amount;
	                        if(tax_amount == null){
	                        	tax_amount = 0;
	                        }else{
	                        	tax_amount = data.no_tax_sale[i].tax_amount;
	                        }
	                  		 var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);

	                        var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
							no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
				
							k = parseInt(k) + parseInt(data.no_tax_sale[i].discount_amount);
						

						}
						
						   
	                    var total_amount_withtax = parseFloat(total_tax_amount) + parseFloat(sub_total_amount);
	                       
						container.find('.total_table .invoice_sub_total').text(parseFloat(sub_total_amount).toFixed(2));
						container.find('.total_table .tax_value').text(parseFloat(total_tax_amount).toFixed(2));
						
						container.find('.total_table .sum_discount').text(k.toFixed(2));
						container.find('.total_table .invoice_total_amount').text(total_amount_withtax.toFixed(2));
						container.find('.no_tax_item_table tbody').empty();
						container.find('.no_tax_item_table tbody').append(no_tax_sale);

						

						var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

						var row = container.find('.item_table tbody tr').clone();

						var items = ``;

						for (var i = 0; i < (data.items).length; i++) {
							var j = i + 1;
							var new_row = row.clone();

							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.items[i].name);
							new_row.find('.col_hsn').text(data.items[i].hsn);
							new_row.find('.col_gst').text(data.items[i].gst);
							new_row.find('.col_discount').text(data.items[i].discount);
							new_row.find('.col_quantity').text(data.items[i].quantity);
							new_row.find('.col_rate').text(data.items[i].rate);
							new_row.find('.col_amount').text(data.items[i].amount);

							items += `<tr>`+new_row.html()+`</tr>`;
						}

						container.find('.item_table tbody').empty();

						container.find('.item_table tbody').append(items);

						container.find('.total_table .sub_total').text(data.sub_total);
						container.find('.total_table .total').text(data.total);

						var discount_row = container.find('.total_table .discounts').clone();
						var tax_row = container.find('.total_table .taxes').clone();

						var total = ``;

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.taxes).length; i++) {

							var new_row = tax_row.clone();

							new_row.find('.tax_name').text(data.taxes[i].key);
							new_row.find('.tax_value').text(data.taxes[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}
						container.find('.total_table .discounts, .total_table .taxes').remove();
						container.find(".total_table tr").first().after(total);
	                
						var divToPrint=document.getElementById('print');
						//console.log(divToPrint);
						//$('.crud_modal').find('.modal-container').html(divToPrint.innerHTML);
						//console.log($('.crud_modal').find('.modal-container').length);
						/*$.get("{{ route('print_popup.create') }}", function(data) {
							console.log(data);
							$('.print_popup_modal .modal-container').html("");
							$('.print_popup_modal .modal-container').html(divToPrint.innerHTML);
						});
						


						$('.print_popup_modal').modal('show');

						$('.type_print').on('click',function(){*/

     					//printDiv1();
						//console.log($('.print_popup_modal .modal-container'));
						//window.onload=function() { window.print(); }
						//window.print();
						//$('.print_popup_modal .modal-container').print();

					   /* });*/
						//$('.print_popup_modal .modal-container').append("<button class=							'btn btn-primary'>Print</button");

				
							var newWin=window.open("","Propel"/*,"width=690,height=900"*/);

							newWin.document.open();
							newWin.document.write(`<html>
								<style>


							  @page {
							        size: A4;
							        margin: 0;
							    }

							</style>
							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
								<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
								<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
							<script> 
							window.onload=function() { window.print(); }
						
							

							$(document).ready(function() {
		


								$('body').on('click', '.print', function() {
								//printDiv();
								});



							}); </scr`+`ipt>


							</body></html>`);
	
						
						newWin.document.close();

  						$('.print_content #print').removeAttr('style');
						$('.print_content #print').html("");
						$('.print_content').removeAttr('style');
						$('.print_content .modal-footer').hide();
						$('.print_content').animate({top: '0px'}); 
						$('body').css('overflow', '');

						}
						$('.loader_wall_onspot').hide();

					}
				});
		
			/*});*/
	});


	//to open print for estimation in index page


	$(".estimation_print").on('click', function(e) {
		
		var id = $(this).attr('data-id');
		 var data = $(this).attr('data-formate');
		print_transaction(id,data);

	});



	//edit 
	$('body').on('click', '.actions_edit', function(e) {
			e.preventDefault();
			isFirstIteration = true;
			var id = $(this).attr('id');

			
			var vehicle_id = $(this).attr('data-id');

			
			if(id != "" && typeof(id) != "undefined") {
				$('.loader_wall_onspot').show();
				$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

					$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					  $('.loader_wall_onspot').hide();
					  
					});
		
				});
			}
			
	});
	//sms
	// $('body').on('click', '.actions_sms', function(e) {
			
	// 		e.preventDefault();
	// 		isFirstIteration = true;
	// 		var id = $(this).attr('id');
			

	// 	       	$.ajax({
	// 				url: "{{ route('transaction.estimation_sms') }}" ,
	// 				type: 'post',
	// 				data: {
	// 					_token: '{{ csrf_token() }}',
	// 					type: '{{ $transaction_type->name }}',
	// 					id: id
	// 				},
	// 				success: function(data, textStatus, jqXHR) {
 //                       alert_message(data.message, "success");
	// 				}
	// 			});
			
	// });
	//delete
	$('body').on('click', '.actions_multidelete', function(e){
			
			var id = $(this).attr('id');
			var type = $(this).attr('data-name');
			$.ajax({
				url: '{{ route('transaction.delete_confirmation') }}',
				type: 'get',
				data: {
			    id: id,
			    type: type,
			    },
			success: function(data, textStatus, jqXHR) {
				console.log(data.type);
						
			if(data.type == 'job_card' || data.type == 'purchase_order' || data.type == 'estimation' )
			{
				var type = data.data.display_name;
				if(type == "Job Request"){
					type = 'Estimation';
				}else{
					type = data.data.display_name;
				}
				if(data.data == 'null'){
					
					$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
					$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
					$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
					$('.delete_modal_ajax').modal('show');

                    $('.delete_modal_ajax_btn').off().on('click', function() {
						$.ajax({
								url: "{{ route('transaction.destroy') }}",
								type: 'post',
								data: {
										_method: 'delete',
										_token : '{{ csrf_token() }}',
											id: id,
									},
								dataType: "json",
								beforeSend: function() {
									$('.loader_wall_onspot').hide();
								},
								success:function(data, textStatus, jqXHR) {
									call_back("", `edit`, data.message, id);
									$('.close_full_modal').trigger('click');
									$('.loader_wall_onspot').hide();
									$('.delete_modal_ajax').modal('hide');
									$('.alert-success').text("Transaction Deleted Successfully!");
									$('.alert-success').show();

									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
								},
								error:function(jqXHR, textStatus, errorThrown) {
								}
						});
					});
				}else{
					$('.delete_modal_ajax').find('.modal-title').text("Alert");
					$('.delete_modal_ajax').find('.modal-body').html("This can not be deleted, because It is referred in "+type+" <b>"+data.data.order_no+"</b>");
					$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
					$('.delete_modal_ajax').modal('show');
				}	
			}else if(data.type == 'job_request' || data.type == 'sale_order' || data.type == 'purchases')
			{
				
				var reference_no = data.reference_no;
				var status = data.status;
			
				var transaction_type = data.transaction_type;
				

				if(status == 'null'){
					$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
					$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
					$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
					$('.delete_modal_ajax').modal('show');
                	$('.delete_modal_ajax_btn').off().on('click', function() {
						$.ajax({
							url: "{{ route('transaction.destroy') }}",
							type: 'post',
							data: {
									_method: 'delete',
									_token : '{{ csrf_token() }}',
										id: id,
							},
							dataType: "json",
							beforeSend: function() {
								$('.loader_wall_onspot').hide();
							},
							success:function(data, textStatus, jqXHR) {
								call_back("", `edit`, data.message, id);
								$('.close_full_modal').trigger('click');
								$('.loader_wall_onspot').hide();
								$('.delete_modal_ajax').modal('hide');
								$('.alert-success').text("Transaction Deleted Successfully!");
								$('.alert-success').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							},
							error:function(jqXHR, textStatus, errorThrown) {
							}
						});
					});
				}else if(status == 1 && reference_no == null){
					$('.delete_modal_ajax').find('.modal-title').text("Alert");
							$('.delete_modal_ajax').find('.modal-body').text("Accounts, Journals and Inventory had been updated. This can not be deleted");
							$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
							$('.delete_modal_ajax').modal('show');
				}else if(status == 1 && data.type == 'job_invoice' || data.type  == 'sales' || data.type == 'purchase_order'){
					if(data.type == "job_invoice")
					{
						type ="Job Invoice";
					}
					else if(data.type == "sales")
					{
						type ="Invoice";
					}
					else
					{
						type="Purchase Order";
					}
					$('.delete_modal_ajax').find('.modal-title').text("Alert");
					$('.delete_modal_ajax').find('.modal-body').html("This can not be deleted, because It is referred in "+type+ "<b> "+reference_no+"</b>");
					$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
					$('.delete_modal_ajax').modal('show');
				}else if(status == 1 && data.type == 'job_card' || data.type == 'estimation' || data.type == 'goods_receipt_note'){
					if(data.type == "job_card")
					{
						type ="Job Card";
					}
					else if(data.type == "estimation")
					{
						type ="Estimation";
					}
					else
					{
						type="goods_receipt_note";
					}
					$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
					$('.delete_modal_ajax').find('.modal-body').html("It is referred from "+type+ "<b>" +reference_no+"</b>, Are you sure to delete?");
					$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
					$('.delete_modal_ajax').modal('show');
                    $('.delete_modal_ajax_btn').off().on('click', function() {
							$.ajax({
								url: "{{ route('transaction.destroy') }}",
								type: 'post',
								data: {
										_method: 'delete',
										_token : '{{ csrf_token() }}',
										id: id,
								},
								dataType: "json",
								beforeSend: function() {
									$('.loader_wall_onspot').hide();
								},
								success:function(data, textStatus, jqXHR) {
									call_back("", `edit`, data.message, id);
									$('.close_full_modal').trigger('click');
									$('.loader_wall_onspot').hide();
									$('.delete_modal_ajax').modal('hide');
									$('.alert-success').text("Transaction Deleted Successfully!");
									$('.alert-success').show();

									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
								},
								error:function(jqXHR, textStatus, errorThrown) {
								}
							});
						});
				}
			}else if(data.type == 'job_invoice' || data.type == 'sales' || data.type == 'goods_receipt_note' || data.type == 'debit_note' || data.type == 'credit_note' || data.type == 'delivery_note')
			{
				

					var action = data.action;
			
					if(action == 0){
						$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
					$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
					$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
					$('.delete_modal_ajax').modal('show');
                	$('.delete_modal_ajax_btn').off().on('click', function() {
						$.ajax({
							url: "{{ route('transaction.destroy') }}",
							type: 'post',
							data: {
									_method: 'delete',
									_token : '{{ csrf_token() }}',
										id: id,
							},
							dataType: "json",
							beforeSend: function() {
								$('.loader_wall_onspot').hide();
							},
							success:function(data, textStatus, jqXHR) {
								call_back("", `edit`, data.message, id);
								$('.close_full_modal').trigger('click');
								$('.loader_wall_onspot').hide();
								$('.delete_modal_ajax').modal('hide');
								$('.alert-success').text("Transaction Deleted Successfully!");
								$('.alert-success').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							},
							error:function(jqXHR, textStatus, errorThrown) {
							}
						});
					});
					}else if(action == 1){
						var reference_no = data.data.reference_no;
						var status = data.data.approval_status;
						if(status == 0){
							$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
							$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
							$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
							$('.delete_modal_ajax').modal('show');
                    		$('.delete_modal_ajax_btn').off().on('click', function() {
							$.ajax({
									url: "{{ route('transaction.destroy') }}",
									type: 'post',
									data: {
										_method: 'delete',
										_token : '{{ csrf_token() }}',
											id: id,
										},
									dataType: "json",
									beforeSend: function() {
									$('.loader_wall_onspot').hide();
									},
									success:function(data, textStatus, jqXHR) {
									call_back("", `edit`, data.message, id);
									$('.close_full_modal').trigger('click');
									$('.loader_wall_onspot').hide();
									$('.delete_modal_ajax').modal('hide');
									$('.alert-success').text("Transaction Deleted Successfully!");
									$('.alert-success').show();

									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
									},
									error:function(jqXHR, textStatus, errorThrown) {
								}
							});
						});
						}else if(status == 1){
							$('.delete_modal_ajax').find('.modal-title').text("Alert");
							$('.delete_modal_ajax').find('.modal-body').text("Accounts, Journals and Inventory had been updated. This can not be deleted");
							$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
							$('.delete_modal_ajax').modal('show');
						}
					}

			}
			else{
				
						var url = "{{ route('transaction.multidestroy') }}";
									multidelete(url);
				}					 		
			},
					error: function(jqXHR, textStatus, errorThrown) {}
			});
		
	});

	//function to delete 	
	/*function multidelete_action(obj, url, token, table = null) {
			var values = [];
			console.log("multidelete_action");

			var table_container;

			if(table == null) {
				table_container = $(".table_container");
			} else {
				table_container = $(table);
			}
			table_container.find('tbody tr').each(function() {
				var value = $(this).attr('id');
				if(value != undefined) {
					values.push(value);
				}
			});
			
			$('.delete_modal_ajax').modal('show');
			$('.delete_modal_ajax_btn').off().on('click', function() {
				$.ajax({
					url: url,
					type: 'post',
					data: {
						_method: 'delete',
						_token: token,
						id: values.join(",")
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						datatable.destroy();
						console.log(data);
						var list = data.data.list;
						for(var i in list) {
							$("input.item_check[value="+list[i]+"]").closest('tr').remove();
						}
						$(obj).closest('.batch_container').hide();
						$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
						$("input.item_check, input[name=check_all]").prop('checked', false);
						datatable = $('#datatable').DataTable(datatable_options);
						$('.delete_modal_ajax').modal('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});
		}*/
	//approve
	$('body').on('click', '.actions_multiapprove', function() {
		var url = "{{ route('transaction.multiapprove') }}";
		var status = $(this).data('status');
		var values = $(this).attr('id');
		

			$.ajax({
				url: url,
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: values,
					status: status
				},
				dataType: "json",
				success: function(data, textStatus, jqXHR) {
					datatable.destroy();
					var list = data.data.list;
					for(var i in list) {
						if(status == 1) {
							$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-warning');
							$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-success');
							$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').text("Approved");
						}else if(status == 0) {
							$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-success');
							$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-warning');
							$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').text("Draft");
						}
						

						//var active_text = $("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').closest('td').find('select').find('option[value="'+status+'"]').text();
						
					}
					$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
					$("input.item_checkbox, input[name=checkbox_all]").prop('checked', false);
					datatable = datatable = $('#datatable').DataTable(datatable_options);
					if(data.status == "1") {
								
						$('.alert-danger').text(data.message);
						$('.alert-danger').show();

						setTimeout(function() { $('.alert').fadeOut(); }, 3000);
					}
					else {
								
						$('.alert-success').text(data.message);
						$('.alert-success').show();

						setTimeout(function() { $('.alert').fadeOut(); }, 3000);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});
	});
	//end

	/*copy when no existing jc/ji/jic present*/
	 function make_transaction(name) {
			var obj = '';
			if(name == 'jobcard-estimation'){
				 obj = $('.jobcard-estimation');
			}else if(name == 'jobcard-invoice_cash'){
         		obj = $('.jobcard-invoice_cash');
			}else if(name == 'jobcard-invoice_credit'){
				obj = $('.jobcard-invoice_credit');
			}else if(name == 'po_to_grn'){
				obj = $('.po_to_grn');
			}
			var id = obj.data('id');
			var transaction_name = obj.data('name');
			var transaction_type =  ('{{ $transaction_type->name }}');
			var transaction_module =  ('{{ $transaction_type->module }}');
                
			if(transaction_type == "job_card"){

				
				var id = obj.data('id');
				var transaction_name = obj.data('name');
				

				//e.preventDefault();	
				
				var job_status_id = $('select[name=jobcard_status_id]').val();
				

				if(transaction_name == "job_invoice" || transaction_name == "job_invoice_cash"){

					if(job_status_id == 1 || job_status_id == 2 || job_status_id == 3 || job_status_id == 4 || job_status_id == 5 || job_status_id == 8)
					{						
						alert_message("Copy Invoice is allowed only for jobcard status is Final Inspected or Vehicle Ready","error");
						return false;
					}
					else{

						$('<form>', {
					    "id": "dynamic_form",
					    "method": "POST",
					    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',
					    "action": '{{ route("add_to_account") }}'
						}).appendTo(document.body).submit();

						$('#dynamic_form').remove();

					}

				}

				else{

					$('<form>', {
				    "id": "dynamic_form",
				    "method": "POST",
				    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',
				    "action": '{{ route("add_to_account") }}'
					}).appendTo(document.body).submit();

				$('#dynamic_form').remove();

				}

			}

			if(transaction_type != "job_card"){

				$('<form>', {
				    "id": "dynamic_form",
				    "method": "POST",
				    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',
				    "action": '{{ route("add_to_account") }}'
					}).appendTo(document.body).submit();

				$('#dynamic_form').remove();
			}			
    }
	/*end*/

	//update inventory
	$("body").on('click', '.tab_update_goods_btn', function(e) {
	
		e.preventDefault();

		var obj = $(this);	
		var id = $(this).attr('id');	


		$.ajax({
				url: "{{ route('transaction.update_inventory') }}",
				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',					
					@if(!empty($transactions))
					
					id: id,
					type: '{{ $transaction_type->name }}'
					@endif
				},
				success:function(data, textStatus, jqXHR) {
					
					$('.alert-success').text(data.message);
					$('.alert-success').show();

					obj.text("Inventory Updated");

					obj.removeClass('tab_update_goods_btn');

					obj.prop('disabled', true);

					setTimeout(function() { $('.alert').fadeOut(); }, 3000);
					
				},
				error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
				}

		});

	});

	$('body').on('click','.actions_un_approve',function(){
		//alert();
			
		//var id = $('input[name=transaction]:checked').val();
		var id = $(this).attr('id');
		//console.log("un Approve");
		//alert(id);

		$.ajax({
			url : '{{ route('transaction_un_approve') }}',
			type: 'get',
			data: 
			{
				id : id
			},
			success:function(data)
			{
				location.reload();
			},
			error:function()
			{

			}
		});
		
	});
	//copy to function using when click copy in action button
	$('body').on('click','.job_make_transaction', function(e) {
			e.preventDefault();
			var obj = $(this);
			var name = obj.attr('data-ref');
			if(name == 'jobcard-estimation'){
				var id = $('.jobcard-estimation').attr('data-id');
				var reference = $('.jobcard-estimation').attr('data-name');
				$.ajax({
				url: '{{ route('find_reference_id') }}',
				type: 'get',
				data: {
			    id: id,
			    reference:reference,
			    },
				success:function(data, textStatus, jqXHR) {
					if(data.data == null){
						make_transaction(name);
					}else{
						$('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Confirmation:</h4></div><div class="modal-body"><h7>'+data.data.display_name+'('+data.data.order_no+') For this Job card is already Exist..!<br>Click Continue to Create<br> <input type="checkbox" name="vehicle" class="pull-left" style = "display:block;width: 22px;height: 19px;"checked disabled><span class="pull-left">Delete the existing Estimation</span></h7></div><div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">No</button><button type="button" id='+data.data.id+' data-name='+data.data.order_no+' class="btn btn-success ok_btn" data-dismiss="modal">Continue</button></div>');
   			               $('.crud_modal').modal('show');
   			               $('.ok_btn').on('click',function(e){
                               e.preventDefault();
                               var id = $(this).attr('id');
                               if($('input[type="checkbox"]'). prop("checked") == true){
                                  $.ajax({
									url:'{{ route('transaction.destroy') }}',
									type: 'post',
									data: {
									_method: 'delete',
									_token : '{{ csrf_token() }}',
									id: id,
									},
									success:function(data)
									{
									  if(data.status == 1){
										 make_transaction(name);
									 }
									}
							});

                               }else{
                                /*    $.ajax({
									url:'{{ route('transaction.destroy') }}',
									type: 'post',
									data: {
									_method: 'delete',
									_token : '{{ csrf_token() }}',
									id: id,
									},
									success:function(data)
									{
									  if(data.status == 1){
									  	var gen_no = data.data.gen_no;
									  existing_transaction(name,gen_no);
									 }
									}
							}); */ 
                               }
                              
   			               });
					}
				},
			    error:function(jqXHR, textStatus, errorThrown) {
				}
		});
			}else if(name == 'jobcard-invoice_cash' || name == 'jobcard-invoice_credit'){
				var id = $(this).attr('data-id');
				var reference = $(this).attr('data-name');
					$.ajax({
				url: '{{ route('find_reference_id') }}',
				type: 'get',
				data: {
			    id: id,
			    reference:reference,
			    },
				success:function(data, textStatus, jqXHR) {
					if(data.data == null){
                        make_transaction(name);                  
					}else{
						$('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Confirmation:</h4></div><div class="modal-body"><h7>'+data.data.display_name+'('+data.data.order_no+') For this Transaction is already Exist..!<br>Click Continue to Create<br> <input type="checkbox" name="vehicle" class="pull-left" style = "display:block;width: 22px;height: 19px;"checked disabled><span class="pull-left">Delete the existing Estimation</span></h7></div><div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">No</button><button type="button" id='+data.data.id+' data-name='+data.data.order_no+' class="btn btn-success ok_btn" data-dismiss="modal">Continue</button></div>');
   			               $('.crud_modal').modal('show');
   			               $('.ok_btn').on('click',function(e){
                               e.preventDefault();
                               var id = $(this).attr('id');
                               var order_no = $(this).attr('data-name');
                               if($('input[type="checkbox"]'). prop("checked") == true){
                                  $.ajax({
									url: '{{ route('transaction.destroy') }}',
									type: 'post',
									data: 
									{
								    _method: 'delete',
									_token: '{{ csrf_token() }}',
									id :id,
									},
									success:function(data)
									{
									  if(data.status == 1){
										 make_transaction(name);
									 }
									}
							});

                               }else{
                               	//Hided by vishnu
                                    /*$.ajax({
									url:'{{ route('transaction.destroy') }}',
									type: 'post',
									data: {
									_method: 'delete',
									_token : '{{ csrf_token() }}',
									id: id,
									},
									success:function(data)
									{
									  if(data.status == 1){
									  	var gen_no = data.data.gen_no;
									  existing_transaction(name,gen_no);
									 }
									}
							});*/
                               }
                              
   			               });
					}
					
				},
			    error:function(jqXHR, textStatus, errorThrown) {
				}
		});
			}else if(name == 'po_to_grn'){
				var id = $(this).attr('data-id');
				var reference = $(this).attr('data-name');
					$.ajax({
				url: '{{ route('find_reference_id') }}',
				type: 'get',
				data: {
			    id: id,
			    reference:reference,
			    },
				success:function(data, textStatus, jqXHR) {
					if(data.data == null){
                        make_transaction(name);                  
					}else{
						$('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Confirmation:</h4></div><div class="modal-body"><h7>'+data.data.display_name+'('+data.data.order_no+') For this Transaction is already Exist..!<br></h7></div><div class="modal-footer"><button type="button" class="btn success" data-dismiss="modal">ok</button></div>');
   			               $('.crud_modal').modal('show');
					}
				},
			    error:function(jqXHR, textStatus, errorThrown) {
				}
		});
			}
	});

	$('.dropdown-submenu a.test').on("click", function(e){
	    $(this).next('ul').toggle();
	    e.stopPropagation();
	    e.preventDefault();
	 });

	

	//pay advance for job card
	$('body').on('click','.pay_advance, .pay_for_invoice',function(){
			
				var id = $(this).attr('data-id');
				var name= $(this).attr('data-name');

				$.ajax({
						url: '{{ route('home_page.pay_advance') }}',
						type: 'post',
						data: 
						{
							_token: '{{ csrf_token() }}',
							id :id,
							type: 'wms_receipt',
							name: name,
						},
						success:function(data)
						{
							//console.log(data);
							var people  = data.people;
							var business = data.business;
							var date = new Date();
							var payment = data.payment;
							var ledgers = data.ledgers;


							$('.person_row').find('select[name=job_card]').html(`<option value=`+data.selected_job_card.id+`>`+data.selected_job_card.order_no+`</option>`);

							$('.people').hide();

							if(data.name.user_type == 0)
							{

								$('.people').show();
								$('.business').hide();
								$('.people').find('select').prop('disabled', false);
								$('.business').find('select').prop('disabled', true);
								$('#people_type').prop('checked',true);
								$('select[name=people_id]').html("<option value='"+data.name.person_id+"'>"+data.name.display_name+"</option>")
								$('input[name=payment_amount]').val(data.name.total);
								$('input[name=invoice_payment_amount]').val(data.name.total);
								$('select[name=people_id]').val(data.name.person_id);
							}
							else if(data.name.user_type == 1)
							{
								$('.business').show();
								$('.people').hide();
								$('.business').find('select').prop('disabled', false);
								$('.people').find('select').prop('disabled', true);
								$('#business_type').prop('checked',true);
								$('select[name=people_id]').html("<option value='"+data.name.business_id+"'>"+data.name.display_name+"</option>")
								$('input[name=payment_amount]').val(data.name.total);
								$('input[name=invoice_payment_amount]').val(data.name.total);
								$('select[name=people_id]').val(data.name.business_id);

							}
							$('.person_row').find('input[name=customer]').on('change', function(){
								if($(this).val() == "people") {
									$('.people').find('select[name=people_id]').html('');
									$('.people').find('select[name=people_id]').append("<option value=''>Select People</option>");
									for (var i in people) {
										$('.people').find('select[name=people_id]').append("<option value='"+people[i].id+"'>"+people[i].name+"</option>");
									}
									$('.people').show();
									$('.business').hide();
									$('.people').find('select').prop('disabled', false);
									$('.business').find('select').prop('disabled', true);
									$('.business').find('select').val('');
								} else if($(this).val() == "business")  {

									$('.business').find('select[name=people_id]').html('');
									$('.business').find('select[name=people_id]').append("<option value=''>Select Business</option>");
									for (var i in business) {
										$('.business').find('select[name=people_id]').append("<option value='"+business[i].id+"'>"+business[i].name+"</option>");
									}
									$('.business').show();
									$('.people').hide();
									$('.business').find('select').prop('disabled', false);
									$('.people').find('select').prop('disabled', true);
									$('.people').find('select').val('');
								}
							});

							$('input[name=invoice_payment_amount]').keyup(function() {
								var payment = parseFloat($(this).val());
								var due_amount = parseFloat($('input[name=invoice_due_amount]').val());
								if( payment > due_amount ) {
									$(this).val(due_amount);
								}
							});
							if(data.type == "jc_payment")
							{
								$('.person_row').show();
								$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').hide();
								$('.invoice_modal').find('select[name=job_card]').closest('.form-group').show();

								$('.invoice_modal').find('input[name=payment_date]').val($.datepicker.formatDate('dd-mm-yy', new Date()));
								$('.invoice_modal').find('select[name=invoice_payment_method]').html('');
								$('.invoice_modal').find('select[name=invoice_payment_ledger]').html('');
								for (var i in payment) {
									$('.invoice_modal').find('select[name=invoice_payment_method]').append("<option value='"+payment[i].id+"'>"+payment[i].display_name+"</option>");
								}
								for (var i in ledgers) {
									$('.invoice_modal').find('select[name=invoice_payment_ledger]').append("<option value='"+ledgers[i].id+"'>"+ledgers[i].name+"</option>");
								}

								$('.invoice_modal').find('.modal-title').text('WMS Receipt: (This is for Advance)');
           						$('.invoice_modal').find('.payment_amount').show();
            					$('.invoice_modal').find('.reduction').css('display','none');
								$('.invoice_modal').find('.tab_print_btn').hide();
								$('.invoice_modal').find('.btn-success').text('Save');
								$('.invoice_modal').find('.btn-default').text('Close');
								$('.invoice_modal').find('.btn-success').on('click',function(){
								$('.invoice_modal').find('.tab_print_btn').show();
								});
								$('.invoice_modal').find('.tab_print_btn').on('click',function(){
                   					var id=$(this).val();
                           
 
                    					print_receipt_transaction(id);

                    
							});
								$('.invoice_modal').modal('show');
		     				}
		     				else if(data.type == "invoice_payment")
		     				{
		     					$('.person_row').hide();
		     					$('.invoice_modal').find('input[name=payment_date]').val($.datepicker.formatDate('dd-mm-yy', new Date()));
		     					$('.invoice_modal').find('select[name=invoice_payment_method]').html('');
								$('.invoice_modal').find('select[name=invoice_payment_ledger]').html('');
		     					for (var i in payment) {
									$('.invoice_modal').find('select[name=invoice_payment_method]').append("<option value='"+payment[i].id+"'>"+payment[i].display_name+"</option>");
								}
								for (var i in ledgers) {
									$('.invoice_modal').find('select[name=invoice_payment_ledger]').append("<option value='"+ledgers[i].id+"'>"+ledgers[i].name+"</option>");
								}
								$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').show();
								$('.invoice_modal').find('.payment_amount').hide();
								$('.invoice_modal').find('.reduction').css('display','block');
								$('.invoice_modal').find('select[name=job_card]').closest('.form-group').hide();
								//validator.resetForm();
								$('.invoice_modal').find('.modal-title').text("Wms Receipt:");
								$('.invoice_modal').find('input[name=invoice_due_amount]').val();
								$('.invoice_modal').find('input[name=invoice_payment_amount]').val();
            
								//user_type = $(this).data('user_type');
								//people_id = $(this).data('people_id');
								//type = $(this).data('type');
								//reference_id.push($(this).data('id'));
								//order_id.push($(this).data('reference_no'));
								//balance = $(this).data('balance');

			   					$('.invoice_modal').find('.btn-default').text('Close');
			  					$('invoice_modal').find('.tab_print_btn').hide();
			  					$('.invoice_modal').modal('show');
								
		     				}




						
						}
				});
	});
	//end the action button 

	function call_back(data, modal, message, id = null) {
		datatable.destroy();

		if($('.item_checkbox[value="' + id + '"]')) {
			$('.item_checkbox[value="' + id + '"]').closest('tr').remove();			
			$('table').find('tbody tr').find('td:first :checkbox').prop('checked', false);
			$('table').find('thead tr th:first :checkbox').prop('indeterminate', false);
		}
		$('.data_table tbody').prepend(data);
		datatable =  $('#datatable').DataTable(datatable_options);
		$('.crud_modal').modal('hide');	

		$('.alert-success').text(message);
		$('.alert-success').show();

		setTimeout(function() { $('.alert').fadeOut(); }, 3000);
	}
	
		function print_transaction(id) {
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('print_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id
					},
					success:function(data, textStatus, jqXHR) {

						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
						container.html("");

						 var specifications = data.specification;
						 var spec ='';
						 
						 if(specifications == null){
						 	spec = '';
						 }else{
						 	specifications = data.specification;
						 	spec = specifications.split(",",4).join('<br>');
						 }
						/*var spec ='';
						if(specifications == null){
                            specifications = '';
						 	spec = '';
						}else{
							specifications = data.specification.spec;
							spec = specifications.split(",",4).join('<br>');
						}*/


						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='voucher_type']").text(data.transaction_type);
							container.find("[data-value='po']").text(data.po_no);
							container.find("[data-value='purchase']").text(data.purchase_no);
							container.find("[data-value='grn']").text(data.grn_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='payment_mode']").text(data.payment_mode);
							container.find("[data-value='resource_person']").text(data.resource_person);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='shipping_address']").text(data.shipping_address);
							container.find("[data-value='billing_address']").text(data.billing_address);
                            container.find("[data-value='customer_vendor']").text(data.customer_vendor);
                            container.find("[data-value='vehicle_number']").text(data.vehicle_number);
                            container.find("[data-value='make_model_variant']").text(data.make_model_variant);
                            container.find("[data-value='company_name']").text(data.company_name);
                            container.find("[data-value='company_phone']").text(data.company_phone);
                            container.find("[data-value='company_address']").text(data.company_address);
                            container.find("[data-value='email_id']").text(data.email_id);
                            container.find("[data-value='amount']").text(data.amount);
                            container.find("[data-value='payment_method']").text(data.payment_method);
                            container.find("[data-value='km']").text(data.km);
                            container.find("[data-value='assigned_to']").text(data.assigned_to);
                             container.find("[data-value='company_gst']").text(data.company_gst);
                             container.find("[data-value='customer_gst']").text(data.customer_gst);
                              container.find("[data-value='driver']").text(data.driver);
                            container.find("[data-value='driver_mobile_no']").text(data.driver_mobile_no);
                            container.find("[data-value='warranty']").text(data.warranty);
                            container.find("[data-value='insurance']").text(data.insurance);
                            container.find("[data-value='mileage']").text(data.mileage);
                            container.find("[data-value='engine_no']").text(data.engine_no);
                            container.find("[data-value='chassis_no']").text(data.chassis_no);
                            container.find("[data-value='specification']").html(spec);
                            container.find("[data-value='job_due_on']").text(data.job_due_on);
                            container.find("[data-value='last_visit_on']").text(data.last_visit_on);
                            container.find("[data-value='next_visit_on']").text(data.next_visit_on);
                            container.find("[data-value='service_on']").text(data.service_on);
                            container.find("[data-value='last_visit_jc']").text(data.last_visit_jc);
                         container.find("[data-value='customer_mobile']").text(data.customer_mobile);



                            /*Job card print*/
                            

                            var row = container.find('.job_card_table tbody tr').clone();

                            var job_card_item = ``;

                            var total_job_items_length = 12;

                            var job_card_length = total_job_items_length - (data.job_card_items).length;
                          

                            	for (var i = 0; i < (data.job_card_items).length; i++) {

								var j = i + 1;

								var new_row = row.clone();



								new_row.find('.col_s_no').text(j);

								new_row.find('.col_items').text(data.job_card_items[i].item_name);

								new_row.find('.col_qty').text(data.job_card_items[i].qty);

								new_row.find('.col_total_price').text(data.job_card_items[i].amt);

								job_card_item += `<tr>`+new_row.html()+`</tr>`;

							}

                            for(var i=1; i <= job_card_length;i++){
								var job_new_row = row.clone();
								job_card_item += `<tr>`+job_new_row.html()+`</tr>`;

							}
                            
							container.find('.job_card_table tbody').empty();

							container.find('.job_card_table tbody').append(job_card_item);

							var complaints = data.complaints;
                             if(complaints != null){
                             	var vehicle_complaints = complaints.split('\n',8).join('<br>');
                             }else{
                             	var vehicle_complaints = '';
                             }

                            container.find("[data-value='complaints']").html(vehicle_complaints);

                             var checklist_details = Object.values(data.checklist_details);

							 var row = container.find('.checklist tbody tr').clone();

							 var checklist = ``;

							 var total_checklist = 12;
                             
                             var checklist_total = total_checklist - (checklist_details).length;

                            for (var i = 0; i < (checklist_details).length; i++) {
                                   var check_new_row = row.clone();
                                   
                                check_new_row.find('.col_checklist').text(checklist_details[i].checklist);
                                check_new_row.find('.col_notes').text(checklist_details[i].notes);

                                checklist += `<tr>`+check_new_row.html()+`</tr>`;
                            }
                            
                            for(var i=1; i <= checklist_total;i++){
								var check_new_row = row.clone();
								checklist += `<tr>`+check_new_row.html()+`</tr>`;

							}

                            
                            container.find('.checklist tbody').empty();
                            container.find('.checklist tbody').append(checklist);
                            var fuel_value = data.fuel_level;
                            var fuel = ``;
                            if(fuel_value != null){
                            	fuel = data.fuel_level[0].notes;
                            }else{
                            	fuel = '';
                            }
                            
                            container.find("[data-value='fuel_checklist']").text(fuel);
                            container.find("[data-value='top']").text(data.first_checklists[4].notes);
                            container.find("[data-value='right']").text(data.first_checklists[3].notes);
                            container.find("[data-value='left']").text(data.first_checklists[2].notes);
                            container.find("[data-value='front']").text(data.first_checklists[1].notes);
                            container.find("[data-value='back']").text(data.first_checklists[0].notes);

                            /*END*/
                           
                            
							var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

							var row = container.find('.invoice_item_table tbody tr').clone();

							var invoice_items = ``;
                            var total_amount = 0;
                            var total_discount = 0;
                            var  total_length= 10;
                            var length = total_length - (data.invoice_items).length;
							for (var i = 0; i < (data.invoice_items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();
								var discount = data.invoice_items[i].discount;
                                var discount_value = $.parseJSON(discount);
                                var amount= data.items[i].rate * data.items[i].quantity - discount_value.amount;
								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.invoice_items[i].name);
								new_row.find('.col_hsn').text(data.invoice_items[i].hsn);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_discount').text(discount_value.amount);
								new_row.find('.col_rate').text(parseFloat(data.items[i].rate).toFixed(2));
								new_row.find('.col_amount').text(parseFloat(data.items[i].amount).toFixed(2));
								new_row.find('.col_t_amount').text(parseFloat(amount).toFixed(2));
								
                               var total_amount = parseFloat(amount)+parseFloat(total_amount);

                               var total_discount = parseFloat(discount_value.amount)+parseFloat(total_discount);

								invoice_items += `<tr>`+new_row.html()+`</tr>`;
							}

							for(var i=1; i <= length;i++){

								var new_row = row.clone();
                                
								invoice_items += `<tr>`+new_row.html()+`</tr>`;

							}

							container.find("[data-value='total_discount']").text(total_discount);
		                    container.find("[data-value='total_amount']").text(parseFloat(total_amount).toFixed(2));
							container.find('.invoice_item_table tbody').empty();
							container.find('.invoice_item_table tbody').append(invoice_items);
                            
                            var hsn_invoice_tax_values = Object.values(data.hsn_based_invoice_tax);
	                       
	                       //HSN based tax table
	                        var hsn_row = container.find('.hsnbasedTable tbody tr').clone();
	                        var hsn_tax = ``;
	                        var  totalhsn_length= 6;
	                        var hsn_length = totalhsn_length - hsn_invoice_tax_values.length;
	                        for(var i = 0; i < hsn_invoice_tax_values.length; i++){
	                        	var hsn_new_row = hsn_row.clone();
	                        	var taxable = parseFloat(hsn_invoice_tax_values[i].taxable).toFixed(2);
	                        	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                        	var gst = hsn_invoice_tax_values[i].name;
	                        	var sgst = hsn_invoice_tax_values[i].display_name;
	                        	if(sgst != null){
	                                var sgst_value = sgst.split('CGST');
	                        	}else{
	                                  sgst_value = '';
	                        	}
	                            
	                        	if(gst == null){
	                            	var exact_tax = '';
	                            }else{
	                            	var exact_tax = sgst_value[0];
	                            }
	                        	if(hsn_invoice_tax_values[i].tax_type == 1){
	                        	hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text("");
	                            hsn_new_row.find('.col_igst_amount').text("");
	                            hsn_new_row.find('.col_cgst').text(exact_tax);
	                            hsn_new_row.find('.col_cgst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_sgst').text(exact_tax);
	                            hsn_new_row.find('.col_sgst_amount').text(tax_amount);
	                            }else{
	                            	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                            	var igst = hsn_invoice_tax_values[i].display_name;
	                            	if(igst != null){
	                            		var exact_igst = igst.split('IGST');
	                            	}else{
	                            		exact_igst = '';
	                            	}
	                            	
	                            	if(gst == null){
	                            		var exact_tax = '';
	                            	}else
	                            	{
	                            		var exact_tax = exact_igst[0];
	                            	}
	                            hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text(exact_tax);
	                            hsn_new_row.find('.col_igst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_cgst').text("");
	                            hsn_new_row.find('.col_cgst_amount').text("");
	                            hsn_new_row.find('.col_sgst').text("");
	                            hsn_new_row.find('.col_sgst_amount').text("");
	                            }
	                        	hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;
	                        }

	                        for(var i=1; i <= hsn_length;i++){

								var hsn_new_row = hsn_row.clone();
	                            
								hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;


							}
	                        container.find('.hsnbasedTable tbody').empty();
							container.find('.hsnbasedTable tbody').append(hsn_tax);
							var invoice_tax_values = Object.values(data.invoice_tax);
							var tax_row = container.find('.floatedTable tbody tr').clone();
                            var invoice_tax = ``;
                            var  total_length= 6;
                           	var gst_length = total_length - invoice_tax_values.length;
                            var total_cgst = 0;
                            var total_sgst = 0;
                            var total_igst = 0;
                            for (var i = 0; i < invoice_tax_values.length; i++) {
								var new_row = tax_row.clone();  
                                var gst = invoice_tax_values[i].name;
                                var sgst = invoice_tax_values[i].display_name;
                                var tax_amount = parseFloat(invoice_tax_values[i].Tax_amount).toFixed(2);
                                var taxable = parseFloat(invoice_tax_values[i].taxable).toFixed(2);
                                if(gst == null){
                                	var exact_value = '';
                                	var exact_sgst  = '';

                                }else{
                                	var exact_value = gst.split('GST');
                                	var exact_sgst = sgst.split('SGST');
                                }
                            if(invoice_tax_values[i].tax_type == 1){
								new_row.find('.col_gst').html(exact_value[0]);
                                new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text("");
								new_row.find('.col_igst_amount').text("");
								new_row.find('.col_cgst').text(exact_sgst[0]);
								new_row.find('.col_cgst_amount').text(tax_amount);
								new_row.find('.col_sgst').text(exact_sgst[0]);
								new_row.find('.col_sgst_amount').text(tax_amount);

								
								var total_cgst = parseFloat(tax_amount)+parseInt(total_cgst);
								 

								var total_sgst = parseFloat(tax_amount)+parseInt(total_sgst);


								
						}
						else{
                             if(gst == null){
                                	var exact_value = '';
                                	var exact_sgst  = '';
                                	var taxable = '';
                                	var tax_amount = '';
                                }else{
                                	var exact_value = gst.split('IGST');
                                	var exact_sgst = sgst.split('IGST');
                                }

	                            new_row.find('.col_gst').text(exact_value[0]);
                                new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text(exact_sgst[0]);
								new_row.find('.col_igst_amount').text(tax_amount);
								new_row.find('.col_cgst').text("");
								new_row.find('.col_cgst_amount').text("");
								new_row.find('.col_sgst').text("");
								new_row.find('.col_sgst_amount').text("");
                        
                                if (tax_amount == ''){
                                	var tax_amount = 0;
                                }else{
                                	var tax_amount = tax_amount;
                                }
                              var total_igst = parseFloat(tax_amount)+parseInt(total_igst);



  						}		

  								
								invoice_tax += `<tr>`+new_row.html()+`</tr>`;


							
					}

                    
                            for(var i=1; i <= gst_length;i++){

								var new_row = tax_row.clone();
                                
								invoice_tax += `<tr>`+new_row.html()+`</tr>`;


							}

							

                    var  total_tax = total_cgst + total_sgst + total_igst;
                    var round_of = Math.ceil(total_tax);
                    var Rount_off_value = round_of - total_tax;
                    var total = total_tax + total_amount;
                    var total_amount= Rount_off_value + total;

                  var total_withtax = Math.ceil(total_amount);
                            var words = new Array();
                                        words[0] = '';
                                        words[1] = 'One';
                                        words[2] = 'Two';
									    words[3] = 'Three';
									    words[4] = 'Four';
									    words[5] = 'Five';
									    words[6] = 'Six';
									    words[7] = 'Seven';
									    words[8] = 'Eight';
									    words[9] = 'Nine';
									    words[10] = 'Ten';
									    words[11] = 'Eleven';
									    words[12] = 'Twelve';
									    words[13] = 'Thirteen';
									    words[14] = 'Fourteen';
									    words[15] = 'Fifteen';
									    words[16] = 'Sixteen';
									    words[17] = 'Seventeen';
									    words[18] = 'Eighteen';
									    words[19] = 'Nineteen';
									    words[20] = 'Twenty';
									    words[30] = 'Thirty';
									    words[40] = 'Forty';
									    words[50] = 'Fifty';
									    words[60] = 'Sixty';
									    words[70] = 'Seventy';
									    words[80] = 'Eighty';
									    words[90] = 'Ninety';
									    amount = total_withtax.toString();
									    var atemp = amount.split(".");
									    var number = atemp[0].split(",").join("");
									    var n_length = number.length;
									    var words_string = "";
					    if (n_length <= 9) {
					        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
					        var received_n_array = new Array();
					        for (var i = 0; i < n_length; i++) {
					            received_n_array[i] = number.substr(i, 1);
					        }
					        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
					            n_array[i] = received_n_array[j];
					        }
					        for (var i = 0, j = 1; i < 9; i++, j++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                if (n_array[i] == 1) {
					                    n_array[j] = 10 + parseInt(n_array[j]);
					                    n_array[i] = 0;
					                }
					            }
					        }
					        value = "";
					        for (var i = 0; i < 9; i++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                value = n_array[i] * 10;
					            } else {
					                value = n_array[i];
					            }
					            if (value != 0) {
					                words_string += words[value] + " ";
					            }
					            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Crores ";
					            }
					            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Lakhs ";
					            }
					            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Thousand ";
					            }
					            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
					                words_string += "Hundred and ";
					            } else if (i == 6 && value != 0) {
					                words_string += "Hundred ";
					            }
					        }
					        words_string = words_string.split("  ").join(" ");
					    }

   
   
    

   
                        container.find("[data-value='total_cgst']").text(total_cgst.toFixed(2));
                        container.find("[data-value='total_sgst']").text(total_sgst.toFixed(2));
                        container.find("[data-value='total_igst']").text(total_igst.toFixed(2));
                        container.find("[data-value='round_off']").text(Rount_off_value.toFixed(2));
                        container.find("[data-value='total_amountwithtax']").text(parseFloat(total_withtax).toFixed(2));
                        container.find("[data-value='rupees']").text(words_string+"Only");
                        container.find('.floatedTable tbody').empty();
                        container.find('.floatedTable tbody').append(invoice_tax);                     
 
                        var row = container.find('.no_tax_item_table tbody tr').clone();

							var no_tax_sale = ``;
                            var total_tax_amount = 0;
                            var sub_total_amount = 0;
							for (var i = 0; i < (data.no_tax_sale).length; i++) {
								var j = i + 1;
								var new_row = row.clone();
								
								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.no_tax_sale[i].name);
								new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
								new_row.find('.col_rate').text(data.no_tax_sale[i].rate);
								new_row.find('.col_discount').text(data.no_tax_sale[i].discount);
								new_row.find('.col_amount').text(parseFloat(data.no_tax_sale[i].amount).toFixed(2));
                                var tax_amount = data.no_tax_sale[i].tax_amount;
                                var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);
                                var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
								no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
							}

							   
                               var total_amount_withtax = parseFloat(total_tax_amount) + parseFloat(sub_total_amount);
                              
							container.find('.total_table .invoice_sub_total').text(parseFloat(sub_total_amount).toFixed(2));
							container.find('.total_table .tax_value').text(parseFloat(total_tax_amount).toFixed(2));
							container.find('.total_table .invoice_total_amount').text(total_amount_withtax.toFixed(2));
							container.find('.no_tax_item_table tbody').empty();
							container.find('.no_tax_item_table tbody').append(no_tax_sale);


							var row = container.find('.item_table tbody tr').clone();

							var items = ``;

							for (var i = 0; i < (data.items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();

								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.items[i].name);
								new_row.find('.col_hsn').text(data.items[i].hsn);
								new_row.find('.col_gst').text(data.items[i].gst);
								new_row.find('.col_discount').text(data.items[i].discount);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_rate').text(data.items[i].rate);
								new_row.find('.col_amount').text(data.items[i].amount);

								items += `<tr>`+new_row.html()+`</tr>`;
							}

							container.find('.item_table tbody').empty();

							container.find('.item_table tbody').append(items);

							container.find('.total_table .sub_total').text(data.sub_total);
							container.find('.total_table .total').text(data.total);

							var discount_row = container.find('.total_table .discounts').clone();
							var tax_row = container.find('.total_table .taxes').clone();

							var total = ``;

							for (var i = 0; i < (data.discounts).length; i++) {

								var new_row = discount_row.clone();

								new_row.find('.discount_name').text(data.discounts[i].key);
								new_row.find('.discount_value').text(data.discounts[i].value);

								total += `<tr>`+new_row.html()+`</tr>`;
							}

							for (var i = 0; i < (data.discounts).length; i++) {

								var new_row = discount_row.clone();

								new_row.find('.discount_name').text(data.discounts[i].key);
								new_row.find('.discount_value').text(data.discounts[i].value);

								total += `<tr>`+new_row.html()+`</tr>`;
							}

							for (var i = 0; i < (data.taxes).length; i++) {

								var new_row = tax_row.clone();

								new_row.find('.tax_name').text(data.taxes[i].key);
								new_row.find('.tax_value').text(data.taxes[i].value);

								total += `<tr>`+new_row.html()+`</tr>`;
							}
							container.find('.total_table .discounts, .total_table .taxes').remove();
							container.find(".total_table tr").first().after(total);

							var divToPrint=document.getElementById('print');
	  						var newWin=window.open('','Propel');


	  						newWin.document.open();
	  						newWin.document.write(`<html>
	  							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	  							<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
	  							<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
								<script> 

								window.onload=function() { window.print(); }

								$(document).ready(function() {
			


									$('body').on('click', '.print', function() {
									//printDiv();
									});



							}); </scr`+`ipt>


							 </body></html>`);

	  						
	  						newWin.document.close();

	  						$('.print_content #print').removeAttr('style');
							$('.print_content #print').html("");
							$('.print_content').removeAttr('style');
							$('.print_content .modal-footer').hide();
							$('.print_content').animate({top: '0px'}); 
							$('body').css('overflow', '');

						}

						$('.loader_wall_onspot').hide();

					}
				});
		
			});
				
		}


	$(document).ready(function() {

	$('body').on('click', '.print', function(){
		$(".buttons-print")[0].click(); //trigger the click event
	});

	$('body').on('click', '.excel_export', function(){
		
		$(".buttons-excel")[0].click(); //trigger the click event
	});

	/*$('body').on('click', '.multidelete', function() {
		var url = "{{ route('transaction.multidestroy') }}";
		multidelete(url);
	});*/

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('transaction.multiapprove') }}";
		active_status($(this).data('status'), url);
	});

	$('body').on('click', '.multinotapprove', function() {
		var url = "{{ route('transaction.multiapprove') }}";
		active_status($(this).data('status'), url);
	});

	$('.action_dropdown').each(function() {
		if($(this).find('ul li:not(.hide):first').length > 0) {
			$(this).parent().find('.action_dropdown > a').remove();
			$(this).parent().find('.action_dropdown').prepend($(this).find('ul li:not(.hide):first').html());
		}
	});

	datatable =  $('#datatable').DataTable(datatable_options);

		$('.add, .add_cash_sale').on('click', function(e) {
			
			e.preventDefault();
			var that = $(this);

			$.ajax({

				url: "{{ route('transaction_limitation') }}",
				type: 'get',
				data:{
					//_token : '{{ csrf_token() }}',
					//type : '{{ $transaction_type->name }}'
				},
				success: function(data, textStatus, jqXHR)
				{
					var transaction_limit = data.transaction_limitation;
					var revenue_limit = data.transaction_revenue;
					var plan_limit = data.plan_limitation;
					

					if(transaction_limit == true && revenue_limit == true && plan_limit == true){

						$('.loader_wall_onspot').show();
						$('body').css('overflow', 'hidden');

						$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

							if(that.hasClass('add_cash_sale')) {

								$.get("{{ route('transaction.create', ['sales_cash']) }}", function(data) {
								  $('.full_modal_content').show();
								  $('.full_modal_content').html("");
								  $('.full_modal_content').html(data);
								  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
								  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
								  $('.loader_wall_onspot').hide();
								});
							} else {
								$.get("{{ route('transaction.create', [$type]) }}", function(data) {
								  $('.full_modal_content').show();
								  $('.full_modal_content').html("");
								  $('.full_modal_content').html(data);
								  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
								  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
								  $('.loader_wall_onspot').hide();
								});
							}
		
						});

					}
					else{
						//e.preventDefault();
						if(transaction_limit == false ){
							$('#error_dialog #title').text('Limit Exceeded!');
							$('#error_dialog #message').html('{{ config('constants.error.limit_exceed') }}'  + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
							$('#error_dialog').modal('show');
						}
						else if(plan_limit == false ){
							$('#error_dialog #title').text('Plan Expired!');
							$('#error_dialog #message').html('{{ config('constants.error.expire') }}'  + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
							$('#error_dialog').modal('show');
						}
						else{
							$('#error_dialog #title').text('Revenue Exceeded!');
							$('#error_dialog #message').html('{{ config('constants.error.revenue_limit') }}' + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
							$('#error_dialog').modal('show');
						}						

						return false;
					}
				}
			});	
		});	


		$('.invoice_add, .invoice_add_cash_sale').on('click', function(e)
		{
			e.preventDefault(); 
			var that = $(this);

			$.ajax({

				url: "{{ route('transaction_limitation') }}",
				type: 'get',
				data:{
					/*_token : '{{ csrf_token() }}',*/
					
				},
				success: function(data, textStatus, jqXHR)
				{
					var transaction_limit = data.transaction_limitation;
					var revenue_limit = data.transaction_revenue;
					var plan_limit = data.plan_limitation;


					if(transaction_limit == true && revenue_limit == true && plan_limit == true)
					{
						$('.loader_wall_onspot').show();
						$('body').css('overflow', 'hidden');

						$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

							if(that.hasClass('invoice_add_cash_sale')) {

								$.get("{{ route('transaction.create', ['job_invoice_cash']) }}", function(data) {
								  $('.full_modal_content').show();
								  $('.full_modal_content').html("");
								  $('.full_modal_content').html(data);
								  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
								  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
								  $('.loader_wall_onspot').hide();
								});
							} else {
								$.get("{{ route('transaction.create', [$type]) }}", function(data) {
								  $('.full_modal_content').show();
								  $('.full_modal_content').html("");
								  $('.full_modal_content').html(data);
								  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
								  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
								  $('.loader_wall_onspot').hide();
								});
							}
			
						});
					}
					else
					{
						//e.preventDefault();
						if(transaction_limit == false ){
							$('#error_dialog #title').text('Limit Exceeded!');
							$('#error_dialog #message').html('{{ config('constants.error.limit_exceed') }}'  + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
							$('#error_dialog').modal('show');
						}
						else if(plan_limit == false ){
							$('#error_dialog #title').text('Plan Expired!');
							$('#error_dialog #message').html('{{ config('constants.error.expire') }}'  + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
							$('#error_dialog').modal('show');
						}
						else{
							$('#error_dialog #title').text('Revenue Exceeded!');
							$('#error_dialog #message').html('{{ config('constants.error.revenue_limit') }}' + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
							$('#error_dialog').modal('show');
						}

						return false;
					}
				}
			});		
				
		});


		$('body').on('click', '.sms', function(e) {
			
			e.preventDefault();			

			$.ajax({

				url: "{{ route('sms_limitation') }}",
				type: 'get',
				data:{
					/*_token : '{{ csrf_token() }}',*/
					
				},
				success: function(data, textStatus, jqXHR)
				{
					var sms_limit = data.sms_limitation;

					if(sms_limit == true){

						isFirstIteration = true;

						var id = $(".item_checkbox:checked").val();

				       	$.ajax({
							url: "{{ route('transaction.estimation_sms') }}" ,
							type: 'post',
							data: {
								_token: '{{ csrf_token() }}',
								type: '{{ $transaction_type->name }}',
								id: id
							},
							success: function(data, textStatus, jqXHR) {
		                       alert_message(data.message, "success");
							}
						});
					}
					else{

						$('#error_dialog #title').text('Limit Exceeded!');
						$('#error_dialog #message').html('{{ config('constants.error.sms_no') }}' + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us." );

						
						$('#error_dialog').modal('show');

						return false;
					}
				}
			});		
							
		});

		//to view modal popup with edit and close button...
		$('#datatable tbody').on('click', '.po_edits', function ()
		{
			//alert();
			var transaction_id = $(this).attr('data-id');
	        var data = $(this).attr('data-formate');
			view_print(transaction_id,data);
	        //$('.loader_wall_onspot').show();
	       /* $.ajax({
					url: "{{ route('print_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: transaction_id,
						data:data

					},
					success:function(data, textStatus, jqXHR) {
					//console.log(data);
					//console.log(data.transaction_data);

					// I added new popup modal to print so hid this

                         
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();
						$('.print_content').hide();


						var container = $('.print_content').find("#print");


						//new coding to show new popup
						//hidden before create function //start
						/*$('.print_popup_content').show();
						
						$('.print_popup_content').hide();


						var container = $('.print_popup_modal').find("#print_value");*/
						//end
						/*container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='voucher_type']").text(data.transaction_type);
							container.find("[data-value='po']").text(data.po_no);
							container.find("[data-value='purchase']").text(data.purchase_no);
							container.find("[data-value='grn']").text(data.grn_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='payment_mode']").text(data.payment_mode);
							container.find("[data-value='resource_person']").text(data.resource_person);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='shipping_address']").text(data.shipping_address);
							container.find("[data-value='billing_address']").text(data.billing_address);
	                        container.find("[data-value='customer_vendor']").text(data.customer_vendor);
	                        container.find("[data-value='vehicle_number']").text(data.vehicle_number);
	                        container.find("[data-value='make_model_variant']").text(data.make_model_variant);
	                        container.find("[data-value='company_name']").text(data.company_name);
	                        container.find("[data-value='company_phone']").text(data.company_phone);
	                        container.find("[data-value='company_address']").text(data.company_address);
	                        container.find("[data-value='email_id']").text(data.email_id);
	                        container.find("[data-value='amount']").text(data.amount);
	                        container.find("[data-value='payment_method']").text(data.payment_method);
	                        container.find("[data-value='km']").text(data.km);
	                        container.find("[data-value='assigned_to']").text(data.assigned_to);
	                        container.find("[data-value='company_gst']").text(data.company_gst);
	                        container.find("[data-value='customer_gst']").text(data.customer_gst);

							var row = container.find('.invoice_item_table tbody tr').clone();

							var invoice_items = ``;
	                        var total_amount = 0;
	                        var total_discount = 0;
	                        var  total_length= 10;
	                        var length = total_length - (data.invoice_items).length;
							for (var i = 0; i < (data.invoice_items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();
								var discount = data.invoice_items[i].discount;
	                            var discount_value = $.parseJSON(discount);
	                            var amount= data.items[i].rate * data.items[i].quantity - discount_value.amount;
								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.invoice_items[i].name);
								new_row.find('.col_hsn').text(data.invoice_items[i].hsn);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_discount').text(discount_value.amount);
								new_row.find('.col_rate').text(parseFloat(data.items[i].rate).toFixed(2));
								new_row.find('.col_amount').text(parseFloat(data.items[i].amount).toFixed(2));
								new_row.find('.col_t_amount').text(parseFloat(amount).toFixed(2));
								
	                           var total_amount = parseFloat(amount)+parseFloat(total_amount);

	                           var total_discount = parseFloat(discount_value.amount)+parseFloat(total_discount);

								invoice_items += `<tr>`+new_row.html()+`</tr>`;
							}

							for(var i=1; i <= length;i++){

								var new_row = row.clone();
	                            
								invoice_items += `<tr>`+new_row.html()+`</tr>`;

							}

							container.find("[data-value='total_discount']").text(total_discount);
		                    container.find("[data-value='total_amount']").text(parseFloat(total_amount).toFixed(2));
							container.find('.invoice_item_table tbody').empty();
							container.find('.invoice_item_table tbody').append(invoice_items);

							var hsn_invoice_tax_values = Object.values(data.hsn_based_invoice_tax);
	                       
	                       //HSN based tax table
	                        var hsn_row = container.find('.hsnbasedTable tbody tr').clone();
	                        var hsn_tax = ``;
	                        var  totalhsn_length= 6;
	                        var hsn_length = totalhsn_length - hsn_invoice_tax_values.length;
	                        for(var i = 0; i < hsn_invoice_tax_values.length; i++){
	                        	var hsn_new_row = hsn_row.clone();
	                        	var taxable = parseFloat(hsn_invoice_tax_values[i].taxable).toFixed(2);
	                        	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                        	var gst = hsn_invoice_tax_values[i].name;
	                        	var sgst = hsn_invoice_tax_values[i].display_name;
	                        	if(sgst != null){
	                                var sgst_value = sgst.split('CGST');
	                        	}else{
	                                  sgst_value = '';
	                        	}
	                            
	                        	if(gst == null){
	                            	var exact_tax = '';
	                            }else{
	                            	var exact_tax = sgst_value[0];
	                            }
	                        	if(hsn_invoice_tax_values[i].tax_type == 1){
	                        	hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text("");
	                            hsn_new_row.find('.col_igst_amount').text("");
	                            hsn_new_row.find('.col_cgst').text(exact_tax);
	                            hsn_new_row.find('.col_cgst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_sgst').text(exact_tax);
	                            hsn_new_row.find('.col_sgst_amount').text(tax_amount);
	                            }else{
	                            	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                            	var igst = hsn_invoice_tax_values[i].display_name;
	                            	if(igst != null){
	                            		var exact_igst = igst.split('IGST');
	                            	}else{
	                            		exact_igst = '';
	                            	}
	                            	
	                            	if(gst == null){
	                            		var exact_tax = '';
	                            	}else
	                            	{
	                            		var exact_tax = exact_igst[0];
	                            	}
	                            hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text(exact_tax);
	                            hsn_new_row.find('.col_igst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_cgst').text("");
	                            hsn_new_row.find('.col_cgst_amount').text("");
	                            hsn_new_row.find('.col_sgst').text("");
	                            hsn_new_row.find('.col_sgst_amount').text("");
	                            }
	                        	hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;
	                        }

	                        for(var i=1; i <= hsn_length;i++){

								var hsn_new_row = hsn_row.clone();
	                            
								hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;


							}
	                        container.find('.hsnbasedTable tbody').empty();
							container.find('.hsnbasedTable tbody').append(hsn_tax);
							var invoice_tax_values = Object.values(data.invoice_tax);
							var tax_row = container.find('.floatedTable tbody tr').clone();
	                        var invoice_tax = ``;
	                        var  total_length= 6;
	                       	var gst_length = total_length - invoice_tax_values.length;
	                        var total_cgst = 0;
	                        var total_sgst = 0;
	                        var total_igst = 0;
	                        for (var i = 0; i < invoice_tax_values.length; i++) {
								var new_row = tax_row.clone();  
	                            var gst = invoice_tax_values[i].name;
	                            var sgst = invoice_tax_values[i].display_name;
	                            var tax_amount = parseFloat(invoice_tax_values[i].Tax_amount).toFixed(2);
	                            var taxable = parseFloat(invoice_tax_values[i].taxable).toFixed(2);
	                            if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';

	                            }else{
	                            	var exact_value = gst.split('GST');
	                            	var exact_sgst = sgst.split('SGST');
	                            }
	                        if(invoice_tax_values[i].tax_type == 1){
								new_row.find('.col_gst').html(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text("");
								new_row.find('.col_igst_amount').text("");
								new_row.find('.col_cgst').text(exact_sgst[0]);
								new_row.find('.col_cgst_amount').text(tax_amount);
								new_row.find('.col_sgst').text(exact_sgst[0]);
								new_row.find('.col_sgst_amount').text(tax_amount);
								var total_cgst = parseFloat(tax_amount)+parseInt(total_cgst);
								var total_sgst = parseFloat(tax_amount)+parseInt(total_sgst);			
								}
							else
							{
	                         	if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';
	                            	var taxable = '';
	                            	var tax_amount = '';
	                            }else{
	                            	var exact_value = gst.split('IGST');
	                            	var exact_sgst = sgst.split('IGST');
	                            }

	                            new_row.find('.col_gst').text(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text(exact_sgst[0]);
								new_row.find('.col_igst_amount').text(tax_amount);
								new_row.find('.col_cgst').text("");
								new_row.find('.col_cgst_amount').text("");
								new_row.find('.col_sgst').text("");
								new_row.find('.col_sgst_amount').text("");
	                    
	                            if (tax_amount == ''){
	                            	var tax_amount = 0;
	                            }else{
	                            	var tax_amount = tax_amount;
	                            }
	                          	var total_igst = parseFloat(tax_amount)+parseInt(total_igst);
							}		
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;							
						}


	             
	                    for(var i=1; i <= gst_length;i++)
	                    {

							var new_row = tax_row.clone();
	                        
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;


						}
			

	                    var  total_tax = total_cgst + total_sgst + total_igst;
	                    var round_of = Math.ceil(total_tax);
	                    var Rount_off_value = round_of - total_tax;
	                    var total = total_tax + total_amount;
	                    var total_amount= Rount_off_value + total;

	                  	var total_withtax = Math.ceil(total_amount);
	                  	var words = new Array();
	                    words[0] = '';
	                    words[1] = 'One';
	                    words[2] = 'Two';
					    words[3] = 'Three';
					    words[4] = 'Four';
					    words[5] = 'Five';
					    words[6] = 'Six';
					    words[7] = 'Seven';
					    words[8] = 'Eight';
					    words[9] = 'Nine';
					    words[10] = 'Ten';
					    words[11] = 'Eleven';
					    words[12] = 'Twelve';
					    words[13] = 'Thirteen';
					    words[14] = 'Fourteen';
					    words[15] = 'Fifteen';
					    words[16] = 'Sixteen';
					    words[17] = 'Seventeen';
					    words[18] = 'Eighteen';
					    words[19] = 'Nineteen';
					    words[20] = 'Twenty';
					    words[30] = 'Thirty';
					    words[40] = 'Forty';
					    words[50] = 'Fifty';
					    words[60] = 'Sixty';
					    words[70] = 'Seventy';
					    words[80] = 'Eighty';
					    words[90] = 'Ninety';
					    amount = total_withtax.toString();
					    var atemp = amount.split(".");
					    var number = atemp[0].split(",").join("");
					    var n_length = number.length;
					    var words_string = "";
					    if (n_length <= 9) {
					        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
					        var received_n_array = new Array();
					        for (var i = 0; i < n_length; i++) {
					            received_n_array[i] = number.substr(i, 1);
					        }
					        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
					            n_array[i] = received_n_array[j];
					        }
					        for (var i = 0, j = 1; i < 9; i++, j++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                if (n_array[i] == 1) {
					                    n_array[j] = 10 + parseInt(n_array[j]);
					                    n_array[i] = 0;
					                }
					            }
					        }
					        value = "";
					        for (var i = 0; i < 9; i++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                value = n_array[i] * 10;
					            } else {
					                value = n_array[i];
					            }
					            if (value != 0) {
					                words_string += words[value] + " ";
					            }
					            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Crores ";
					            }
					            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Lakhs ";
					            }
					            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Thousand ";
					            }
					            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
					                words_string += "Hundred and ";
					            } else if (i == 6 && value != 0) {
					                words_string += "Hundred ";
					            }
					        }
					        words_string = words_string.split("  ").join(" ");
	   					}

	                	container.find("[data-value='total_cgst']").text(total_cgst.toFixed(2));
	                	container.find("[data-value='total_sgst']").text(total_sgst.toFixed(2));
	                	container.find("[data-value='total_igst']").text(total_igst.toFixed(2));
	                	container.find("[data-value='round_off']").text(Rount_off_value.toFixed(2));
	                	container.find("[data-value='total_amountwithtax']").text(parseFloat(total_withtax).toFixed(2));
	                	container.find("[data-value='rupees']").text(words_string+"Only");
	                	container.find('.floatedTable tbody').empty();
	                	container.find('.floatedTable tbody').append(invoice_tax);                     

	                	var row = container.find('.no_tax_item_table tbody tr').clone();

						var no_tax_sale = ``;
	                    var total_tax_amount = 0;
	                    var sub_total_amount = 0;
						//hidden before create function //start

			               /* for (var i = 0; i < (data.no_tax_sale).length; i++) {
								var j = i + 1;
								var new_row = row.clone();

								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.no_tax_sale[i].name);
								new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
								new_row.find('.col_rate').text(data.no_tax_sale[i].rate);
								new_row.find('.col_discount').text(data.no_tax_sale[i].discount);
								new_row.find('.col_amount').text(parseFloat(data.no_tax_sale[i].amount).toFixed(2));
			                    var tax_amount = data.no_tax_sale[i].tax_amount;
			                    var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);
			                    var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
								no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
							}*/
							//end
						/*var k =0;
							
						for (var i = 0; i < (data.no_tax_sale).length; i++) {
						
							var j = i + 1;
							var new_row = row.clone();
							var unit_rate = data.no_tax_sale[i].rate;
							var discount_amount = data.no_tax_sale[i].discount;
							
							var amount = data.no_tax_sale[i].amount;
							if(unit_rate == undefined){
								unit_rate = 0;
							}else{
								unit_rate = data.no_tax_sale[i].rate;
							}

							if(discount_amount == undefined){
								discount_amount = 0;
							}else{
								discount_amount = data.no_tax_sale[i].discount;
							}

							if(amount == undefined){
								amount = 0;
							}else{
								amount = data.no_tax_sale[i].amount;
							}
							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.no_tax_sale[i].name);
							new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
							new_row.find('.col_rate').text(parseFloat(unit_rate).toFixed(2));
							new_row.find('.col_discount').text(parseFloat(discount_amount).toFixed(2));
							new_row.find('.col_amount').text(parseFloat(amount).toFixed(2));
	                        var tax_amount = data.no_tax_sale[i].tax_amount;
	                        if(tax_amount == null){
	                        	tax_amount = 0;
	                        }else{
	                        	tax_amount = data.no_tax_sale[i].tax_amount;
	                        }
	                  		 var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);

	                        var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
							no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
				
							k = parseInt(k) + parseInt(data.no_tax_sale[i].discount_amount);
						

						}
						
						   
	                    var total_amount_withtax = parseFloat(total_tax_amount) + parseFloat(sub_total_amount);
	                       
						container.find('.total_table .invoice_sub_total').text(parseFloat(sub_total_amount).toFixed(2));
						container.find('.total_table .tax_value').text(parseFloat(total_tax_amount).toFixed(2));
						
						container.find('.total_table .sum_discount').text(k.toFixed(2));
						container.find('.total_table .invoice_total_amount').text(total_amount_withtax.toFixed(2));
						container.find('.no_tax_item_table tbody').empty();
						container.find('.no_tax_item_table tbody').append(no_tax_sale);

						

						var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

						var row = container.find('.item_table tbody tr').clone();

						var items = ``;

						for (var i = 0; i < (data.items).length; i++) {
							var j = i + 1;
							var new_row = row.clone();

							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.items[i].name);
							new_row.find('.col_hsn').text(data.items[i].hsn);
							new_row.find('.col_gst').text(data.items[i].gst);
							new_row.find('.col_discount').text(data.items[i].discount);
							new_row.find('.col_quantity').text(data.items[i].quantity);
							new_row.find('.col_rate').text(data.items[i].rate);
							new_row.find('.col_amount').text(data.items[i].amount);

							items += `<tr>`+new_row.html()+`</tr>`;
						}

						container.find('.item_table tbody').empty();

						container.find('.item_table tbody').append(items);

						container.find('.total_table .sub_total').text(data.sub_total);
						container.find('.total_table .total').text(data.total);

						var discount_row = container.find('.total_table .discounts').clone();
						var tax_row = container.find('.total_table .taxes').clone();

						var total = ``;

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.taxes).length; i++) {

							var new_row = tax_row.clone();

							new_row.find('.tax_name').text(data.taxes[i].key);
							new_row.find('.tax_value').text(data.taxes[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}
						container.find('.total_table .discounts, .total_table .taxes').remove();
						container.find(".total_table tr").first().after(total);
	                
						var divToPrint=document.getElementById('print');
						//hidden before create function //start

						//console.log(divToPrint);
						//$('.crud_modal').find('.modal-container').html(divToPrint.innerHTML);
						//console.log($('.crud_modal').find('.modal-container').length);
						//end
						$.get("{{ route('print_popup.create') }}", function(data) {
							console.log(data);
							$('.print_popup_modal .modal-container').html("");
							$('.print_popup_modal .modal-container').html(divToPrint.innerHTML);
						});
						


						$('.print_popup_modal').modal('show');
						//console.log("print_div");


						$('#close_and_edit').on('click',function(){
							//alert();
							$('.print_popup_modal').modal('hide');

							isFirstIteration = true;
							var id = transaction_id;
	       					var vehicle_id = $('.po_edit').data('vehicle_id');       

							if(id != "" && typeof(id) != "undefined") {

								$('.loader_wall_onspot').show();
									$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

										$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
										  $('.full_modal_content').show();
										  $('.full_modal_content').html("");
										  $('.full_modal_content').html(data);
										  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
										  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
										  $('.loader_wall_onspot').hide();
										  
										});
							
									});
							}


						});

						$('#generate_pdf').on('click',function(){
							//alert();
							console.log($('.print_popup_modal').find('.modal-container'));
							
							var doc = new jsPDF();
							doc.addHTML($('.print_popup_modal').find('.modal-container'), 15, 15, {
							'background': '#fff',
							'border':'2px solid white',
							}, function() {
							doc.save('Propel.pdf');
							});
							

						});
						

						$('.type_print').on('click',function(){
							//printElement(document.getElementById('print'));
							window.print();

	     				

					    });
				

						}
						$('.loader_wall_onspot').hide();

					}
				});*/

		});


	function view_print(transaction_id,data)
	{
			var transaction_id = transaction_id;
	        var data = data;
	        console.log("view print function");
		 $.ajax({
					url: "{{ route('print_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: transaction_id,
						data:data

					},
					success:function(data, textStatus, jqXHR) {
					//console.log(data);
					//console.log(data.transaction_data);

					// I added new popup modal to print so hid this

                         
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();
						$('.print_content').hide();


						var container = $('.print_content').find("#print");


						//new coding to show new popup
						//hidden before create function //start
						/*$('.print_popup_content').show();
						
						$('.print_popup_content').hide();


						var container = $('.print_popup_modal').find("#print_value");*/
						//end
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='voucher_type']").text(data.transaction_type);
							container.find("[data-value='po']").text(data.po_no);
							container.find("[data-value='purchase']").text(data.purchase_no);
							container.find("[data-value='grn']").text(data.grn_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='payment_mode']").text(data.payment_mode);
							container.find("[data-value='resource_person']").text(data.resource_person);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='shipping_address']").text(data.shipping_address);
							container.find("[data-value='billing_address']").text(data.billing_address);
	                        container.find("[data-value='customer_vendor']").text(data.customer_vendor);
	                        container.find("[data-value='vehicle_number']").text(data.vehicle_number);
	                        container.find("[data-value='make_model_variant']").text(data.make_model_variant);
	                        container.find("[data-value='company_name']").text(data.company_name);
	                        container.find("[data-value='company_phone']").text(data.company_phone);
	                        container.find("[data-value='company_address']").text(data.company_address);
	                        container.find("[data-value='email_id']").text(data.email_id);
	                        container.find("[data-value='amount']").text(data.amount);
	                        container.find("[data-value='payment_method']").text(data.payment_method);
	                        container.find("[data-value='km']").text(data.km);
	                        container.find("[data-value='assigned_to']").text(data.assigned_to);
	                        container.find("[data-value='company_gst']").text(data.company_gst);
	                        container.find("[data-value='customer_gst']").text(data.customer_gst);
	                       container.find("[data-value='customer_mobile']").text(data.customer_mobile);


							var row = container.find('.invoice_item_table tbody tr').clone();

							var invoice_items = ``;
	                        var total_amount = 0;
	                        var total_discount = 0;
	                        var  total_length= 10;
	                        var length = total_length - (data.invoice_items).length;
							for (var i = 0; i < (data.invoice_items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();
								var discount = data.invoice_items[i].discount;
	                            var discount_value = $.parseJSON(discount);
	                            var amount= data.items[i].rate * data.items[i].quantity - discount_value.amount;
								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.invoice_items[i].name);
								new_row.find('.col_hsn').text(data.invoice_items[i].hsn);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_discount').text(discount_value.amount);
								new_row.find('.col_rate').text(parseFloat(data.items[i].rate).toFixed(2));
								new_row.find('.col_amount').text(parseFloat(data.items[i].amount).toFixed(2));
								new_row.find('.col_t_amount').text(parseFloat(amount).toFixed(2));
								
	                           var total_amount = parseFloat(amount)+parseFloat(total_amount);

	                           var total_discount = parseFloat(discount_value.amount)+parseFloat(total_discount);

								invoice_items += `<tr>`+new_row.html()+`</tr>`;
							}

							for(var i=1; i <= length;i++){

								var new_row = row.clone();
	                            
								invoice_items += `<tr>`+new_row.html()+`</tr>`;

							}

							container.find("[data-value='total_discount']").text(total_discount);
		                    container.find("[data-value='total_amount']").text(parseFloat(total_amount).toFixed(2));
							container.find('.invoice_item_table tbody').empty();
							container.find('.invoice_item_table tbody').append(invoice_items);

							var hsn_invoice_tax_values = Object.values(data.hsn_based_invoice_tax);
	                       
	                       //HSN based tax table
	                        var hsn_row = container.find('.hsnbasedTable tbody tr').clone();
	                        var hsn_tax = ``;
	                        var  totalhsn_length= 6;
	                        var hsn_length = totalhsn_length - hsn_invoice_tax_values.length;
	                        for(var i = 0; i < hsn_invoice_tax_values.length; i++){
	                        	var hsn_new_row = hsn_row.clone();
	                        	var taxable = parseFloat(hsn_invoice_tax_values[i].taxable).toFixed(2);
	                        	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                        	var gst = hsn_invoice_tax_values[i].name;
	                        	var sgst = hsn_invoice_tax_values[i].display_name;
	                        	if(sgst != null){
	                                var sgst_value = sgst.split('CGST');
	                        	}else{
	                                  sgst_value = '';
	                        	}
	                            
	                        	if(gst == null){
	                            	var exact_tax = '';
	                            }else{
	                            	var exact_tax = sgst_value[0];
	                            }
	                        	if(hsn_invoice_tax_values[i].tax_type == 1){
	                        	hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text("");
	                            hsn_new_row.find('.col_igst_amount').text("");
	                            hsn_new_row.find('.col_cgst').text(exact_tax);
	                            hsn_new_row.find('.col_cgst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_sgst').text(exact_tax);
	                            hsn_new_row.find('.col_sgst_amount').text(tax_amount);
	                            }else{
	                            	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                            	var igst = hsn_invoice_tax_values[i].display_name;
	                            	if(igst != null){
	                            		var exact_igst = igst.split('IGST');
	                            	}else{
	                            		exact_igst = '';
	                            	}
	                            	
	                            	if(gst == null){
	                            		var exact_tax = '';
	                            	}else
	                            	{
	                            		var exact_tax = exact_igst[0];
	                            	}
	                            hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text(exact_tax);
	                            hsn_new_row.find('.col_igst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_cgst').text("");
	                            hsn_new_row.find('.col_cgst_amount').text("");
	                            hsn_new_row.find('.col_sgst').text("");
	                            hsn_new_row.find('.col_sgst_amount').text("");
	                            }
	                        	hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;
	                        }

	                        for(var i=1; i <= hsn_length;i++){

								var hsn_new_row = hsn_row.clone();
	                            
								hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;


							}
	                        container.find('.hsnbasedTable tbody').empty();
							container.find('.hsnbasedTable tbody').append(hsn_tax);
							var invoice_tax_values = Object.values(data.invoice_tax);
							var tax_row = container.find('.floatedTable tbody tr').clone();
	                        var invoice_tax = ``;
	                        var  total_length= 6;
	                       	var gst_length = total_length - invoice_tax_values.length;
	                        var total_cgst = 0;
	                        var total_sgst = 0;
	                        var total_igst = 0;
	                        for (var i = 0; i < invoice_tax_values.length; i++) {
								var new_row = tax_row.clone();  
	                            var gst = invoice_tax_values[i].name;
	                            var sgst = invoice_tax_values[i].display_name;
	                            var tax_amount = parseFloat(invoice_tax_values[i].Tax_amount).toFixed(2);
	                            var taxable = parseFloat(invoice_tax_values[i].taxable).toFixed(2);
	                            if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';

	                            }else{
	                            	var exact_value = gst.split('GST');
	                            	var exact_sgst = sgst.split('SGST');
	                            }
	                        if(invoice_tax_values[i].tax_type == 1){
								new_row.find('.col_gst').html(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text("");
								new_row.find('.col_igst_amount').text("");
								new_row.find('.col_cgst').text(exact_sgst[0]);
								new_row.find('.col_cgst_amount').text(tax_amount);
								new_row.find('.col_sgst').text(exact_sgst[0]);
								new_row.find('.col_sgst_amount').text(tax_amount);
								var total_cgst = parseFloat(tax_amount)+parseInt(total_cgst);
								var total_sgst = parseFloat(tax_amount)+parseInt(total_sgst);			
								}
							else
							{
	                         	if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';
	                            	var taxable = '';
	                            	var tax_amount = '';
	                            }else{
	                            	var exact_value = gst.split('IGST');
	                            	var exact_sgst = sgst.split('IGST');
	                            }

	                            new_row.find('.col_gst').text(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text(exact_sgst[0]);
								new_row.find('.col_igst_amount').text(tax_amount);
								new_row.find('.col_cgst').text("");
								new_row.find('.col_cgst_amount').text("");
								new_row.find('.col_sgst').text("");
								new_row.find('.col_sgst_amount').text("");
	                    
	                            if (tax_amount == ''){
	                            	var tax_amount = 0;
	                            }else{
	                            	var tax_amount = tax_amount;
	                            }
	                          	var total_igst = parseFloat(tax_amount)+parseInt(total_igst);
							}		
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;							
						}


	             
	                    for(var i=1; i <= gst_length;i++)
	                    {

							var new_row = tax_row.clone();
	                        
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;


						}
			

	                    var  total_tax = total_cgst + total_sgst + total_igst;
	                    var round_of = Math.ceil(total_tax);
	                    var Rount_off_value = round_of - total_tax;
	                    var total = total_tax + total_amount;
	                    var total_amount= Rount_off_value + total;

	                  	var total_withtax = Math.ceil(total_amount);
	                  	var words = new Array();
	                    words[0] = '';
	                    words[1] = 'One';
	                    words[2] = 'Two';
					    words[3] = 'Three';
					    words[4] = 'Four';
					    words[5] = 'Five';
					    words[6] = 'Six';
					    words[7] = 'Seven';
					    words[8] = 'Eight';
					    words[9] = 'Nine';
					    words[10] = 'Ten';
					    words[11] = 'Eleven';
					    words[12] = 'Twelve';
					    words[13] = 'Thirteen';
					    words[14] = 'Fourteen';
					    words[15] = 'Fifteen';
					    words[16] = 'Sixteen';
					    words[17] = 'Seventeen';
					    words[18] = 'Eighteen';
					    words[19] = 'Nineteen';
					    words[20] = 'Twenty';
					    words[30] = 'Thirty';
					    words[40] = 'Forty';
					    words[50] = 'Fifty';
					    words[60] = 'Sixty';
					    words[70] = 'Seventy';
					    words[80] = 'Eighty';
					    words[90] = 'Ninety';
					    amount = total_withtax.toString();
					    var atemp = amount.split(".");
					    var number = atemp[0].split(",").join("");
					    var n_length = number.length;
					    var words_string = "";
					    if (n_length <= 9) {
					        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
					        var received_n_array = new Array();
					        for (var i = 0; i < n_length; i++) {
					            received_n_array[i] = number.substr(i, 1);
					        }
					        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
					            n_array[i] = received_n_array[j];
					        }
					        for (var i = 0, j = 1; i < 9; i++, j++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                if (n_array[i] == 1) {
					                    n_array[j] = 10 + parseInt(n_array[j]);
					                    n_array[i] = 0;
					                }
					            }
					        }
					        value = "";
					        for (var i = 0; i < 9; i++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                value = n_array[i] * 10;
					            } else {
					                value = n_array[i];
					            }
					            if (value != 0) {
					                words_string += words[value] + " ";
					            }
					            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Crores ";
					            }
					            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Lakhs ";
					            }
					            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Thousand ";
					            }
					            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
					                words_string += "Hundred and ";
					            } else if (i == 6 && value != 0) {
					                words_string += "Hundred ";
					            }
					        }
					        words_string = words_string.split("  ").join(" ");
	   					}

	                	container.find("[data-value='total_cgst']").text(total_cgst.toFixed(2));
	                	container.find("[data-value='total_sgst']").text(total_sgst.toFixed(2));
	                	container.find("[data-value='total_igst']").text(total_igst.toFixed(2));
	                	container.find("[data-value='round_off']").text(Rount_off_value.toFixed(2));
	                	container.find("[data-value='total_amountwithtax']").text(parseFloat(total_withtax).toFixed(2));
	                	container.find("[data-value='rupees']").text(words_string+"Only");
	                	container.find('.floatedTable tbody').empty();
	                	container.find('.floatedTable tbody').append(invoice_tax);                     

	                	var row = container.find('.no_tax_item_table tbody tr').clone();

						var no_tax_sale = ``;
	                    var total_tax_amount = 0;
	                    var sub_total_amount = 0;
						//hidden before create function //start

			               /* for (var i = 0; i < (data.no_tax_sale).length; i++) {
								var j = i + 1;
								var new_row = row.clone();

								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.no_tax_sale[i].name);
								new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
								new_row.find('.col_rate').text(data.no_tax_sale[i].rate);
								new_row.find('.col_discount').text(data.no_tax_sale[i].discount);
								new_row.find('.col_amount').text(parseFloat(data.no_tax_sale[i].amount).toFixed(2));
			                    var tax_amount = data.no_tax_sale[i].tax_amount;
			                    var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);
			                    var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
								no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
							}*/
							//end
						var k =0;
							
						for (var i = 0; i < (data.no_tax_sale).length; i++) {
						
							var j = i + 1;
							var new_row = row.clone();
							var unit_rate = data.no_tax_sale[i].rate;
							var discount_amount = data.no_tax_sale[i].discount;
							
							var amount = data.no_tax_sale[i].amount;
							if(unit_rate == undefined){
								unit_rate = 0;
							}else{
								unit_rate = data.no_tax_sale[i].rate;
							}

							if(discount_amount == undefined){
								discount_amount = 0;
							}else{
								discount_amount = data.no_tax_sale[i].discount;
							}

							if(amount == undefined){
								amount = 0;
							}else{
								amount = data.no_tax_sale[i].amount;
							}
							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.no_tax_sale[i].name);
							new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
							new_row.find('.col_rate').text(parseFloat(unit_rate).toFixed(2));
							new_row.find('.col_discount').text(parseFloat(discount_amount).toFixed(2));
							new_row.find('.col_amount').text(parseFloat(amount).toFixed(2));
	                        var tax_amount = data.no_tax_sale[i].tax_amount;
	                        if(tax_amount == null){
	                        	tax_amount = 0;
	                        }else{
	                        	tax_amount = data.no_tax_sale[i].tax_amount;
	                        }
	                  		 var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);

	                        var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
							no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
				
							k = parseInt(k) + parseInt(data.no_tax_sale[i].discount_amount);
						

						}
						
						   
	                    var total_amount_withtax = parseFloat(total_tax_amount) + parseFloat(sub_total_amount);
	                       
						container.find('.total_table .invoice_sub_total').text(parseFloat(sub_total_amount).toFixed(2));
						container.find('.total_table .tax_value').text(parseFloat(total_tax_amount).toFixed(2));
						
						container.find('.total_table .sum_discount').text(k.toFixed(2));
						container.find('.total_table .invoice_total_amount').text(total_amount_withtax.toFixed(2));
						container.find('.no_tax_item_table tbody').empty();
						container.find('.no_tax_item_table tbody').append(no_tax_sale);

						

						var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

						var row = container.find('.item_table tbody tr').clone();

						var items = ``;

						for (var i = 0; i < (data.items).length; i++) {
							var j = i + 1;
							var new_row = row.clone();

							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.items[i].name);
							new_row.find('.col_hsn').text(data.items[i].hsn);
							new_row.find('.col_gst').text(data.items[i].gst);
							new_row.find('.col_discount').text(data.items[i].discount);
							new_row.find('.col_quantity').text(data.items[i].quantity);
							new_row.find('.col_rate').text(data.items[i].rate);
							new_row.find('.col_amount').text(data.items[i].amount);

							items += `<tr>`+new_row.html()+`</tr>`;
						}

						container.find('.item_table tbody').empty();

						container.find('.item_table tbody').append(items);

						container.find('.total_table .sub_total').text(data.sub_total);
						container.find('.total_table .total').text(data.total);

						var discount_row = container.find('.total_table .discounts').clone();
						var tax_row = container.find('.total_table .taxes').clone();

						var total = ``;

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.taxes).length; i++) {

							var new_row = tax_row.clone();

							new_row.find('.tax_name').text(data.taxes[i].key);
							new_row.find('.tax_value').text(data.taxes[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}
						container.find('.total_table .discounts, .total_table .taxes').remove();
						container.find(".total_table tr").first().after(total);
	                
						var divToPrint=document.getElementById('print');
						//hidden before create function //start

						//console.log(divToPrint);
						//$('.crud_modal').find('.modal-container').html(divToPrint.innerHTML);
						//console.log($('.crud_modal').find('.modal-container').length);
						//end
						$.get("{{ route('print_popup.create') }}", function(data) {
							
							$('.print_popup_modal .modal-container').html("");
							var data=$('.print_popup_modal .modal-container').html(divToPrint.innerHTML);
							console.log(data);
						});
						


						$('.print_popup_modal').modal('show');
						//console.log("print_div");


						$('#close_and_edit').on('click',function(){
							//alert();
							$('.print_popup_modal').modal('hide');

							isFirstIteration = true;
							var id = transaction_id;
	       					var vehicle_id = $('.po_edit').data('vehicle_id');       

							if(id != "" && typeof(id) != "undefined") {

								$('.loader_wall_onspot').show();
									$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

										$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
										  $('.full_modal_content').show();
										  $('.full_modal_content').html("");
										  $('.full_modal_content').html(data);
										  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
										  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
										  $('.loader_wall_onspot').hide();
										  
										});
							
									});
							}


						});

						$('#generate_pdf').on('click',function(){
							//alert();
							console.log($('.print_popup_modal').find('.modal-container'));
							
							var doc = new jsPDF();
							doc.addHTML($('.print_popup_modal').find('.modal-container'), 15, 15, {
							'background': '#fff',
							'border':'2px solid white',
							}, function() {
							doc.save('Propel.pdf');
							});
							

						});
						

						$('.type_print').on('click',function(){
							//printElement(document.getElementById('print'));
							window.print();

	     				

					    });
				

						}
						$('.loader_wall_onspot').hide();

					}
				});
	}


		$('#datatable tbody').on('click', '.po_edit', function () {
			
			isFirstIteration = true;
			var id = $(this).data('id');
	        var vehicle_id = $(this).data('vehicle_id');       

			if(id != "" && typeof(id) != "undefined") {

				$('.loader_wall_onspot').show();
					$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

						$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
						  $('.full_modal_content').show();
						  $('.full_modal_content').html("");
						  $('.full_modal_content').html(data);
						  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
						  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
						  $('.loader_wall_onspot').hide();
						  
						});
			
					});
				}

			});


			$('#datatable tbody').on('click', '.reference', function () {
			
			isFirstIteration = true;
			var id = $(this).data('id');
	        var vehicle_id = $(this).data('vehicle_id');       

			if(id != "" && typeof(id) != "undefined") {

				$('.loader_wall_onspot').show();
					$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

						$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
						  $('.full_modal_content').show();
						  $('.full_modal_content').html("");
						  $('.full_modal_content').html(data);
						  $('.full_modal_content').find('.transactionform').find('.tab_delete_btn ').hide();
						  //console.log($('.full_modal_content').find('.transactionform').find('.form-body').find('.tab-content').length);
						 $('.full_modal_content').find('.transactionform').find('.form-body').find('.tab-content :input').prop( "disabled", true );
						  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
						  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
						  $('.loader_wall_onspot').hide();
						  
						});
			
					});
				}

			});


			$('body').on('click', '.make_transaction a', function(e) {
				e.preventDefault();
				$('.loader_wall').show();
				var obj = $(this);
				var id = obj.data('id');
				var transaction_name = obj.data('name');

				$.ajax({
					url: "{{ route('add_to_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id,
						transaction_name : transaction_name
					},
					success:function(data, textStatus, jqXHR) {
						$('.loader_wall').hide();
						$('.alert-success').html(data.message);
						$('.alert-success').show();

						setTimeout(function() { $('.alert').fadeOut(); }, 3000);
					}
				});

			});


			$('body').on('click', '.edit', function(e) {
				e.preventDefault();
				isFirstIteration = true;
				var id = $(".item_checkbox:checked").val();
				var vehicle_id = $(".item_checkbox:checked").attr('data-id');
				if(id != "" && typeof(id) != "undefined") {
					$('.loader_wall_onspot').show();
					$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

						$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
						  $('.full_modal_content').show();
						  $('.full_modal_content').html("");
						  $('.full_modal_content').html(data);
						  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
						  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
						  $('.loader_wall_onspot').hide();
						  
						});
			
					});
				}
				
			});


			// send Id for Acknowladgement page		


			$('body').on('change', 'input[name=checkbox_all]', function(e) {

				if ($(this).is(":checked")) {
					$(this).closest('table').find('tbody tr').find('td:first :checkbox').prop('checked', true);
				} else {
					$(this).closest('table').find('tbody tr').find('td:first :checkbox').prop('checked', false);
				}
			});

			$('body').on('change', '.item_checkbox', function(e) {
				$('.edit').hide();
				$('.sms').hide();
				$('.sms_limit').hide();
				if ($(".item_checkbox:checked").length > 0) {
					$(this).closest('table').find('thead tr th:first :checkbox').prop('indeterminate', true);
					if ($(".item_checkbox:checked").length == 1) {
						$('.edit').show();
						$('.sms').show();
						$('.sms_limit').show();
					}
				} else {
					$(this).closest('table').find('thead tr th:first :checkbox').prop('indeterminate', false);
				}
			});

		
			$('body').on('click', '.delete', function(){		
				var id = $(this).data('id');
				var parent = $(this).closest('tr');
				var delete_url = '{{ route('transaction.destroy') }}';
				delete_row(id, parent, delete_url);
			});


			$('body').on('click', '.multidelete', function(e) 
			{
				e.preventDefault();
				var id = $(".data_table").find('tbody tr').find("td:first").find("input:checked").val();
				var type = $(this).attr('id');
				$.ajax({
					url: '{{ route('transaction.delete_confirmation') }}',
					type: 'get',
					data: {
				    id: id,
				    type: type,
				    },
				success: function(data, textStatus, jqXHR) {
				
				if(data.type == 'job_card' || data.type == 'purchase_order' || data.type == 'estimation' )
				{
					var type = data.data.display_name;
					if(type == "Job Request"){
						type = 'Estimation';
					}else{
						type = data.data.display_name;
					}
					if(data.data == 'null'){
						$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
						$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
						$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
						$('.delete_modal_ajax').modal('show');
	                    $('.delete_modal_ajax_btn').off().on('click', function() {
							$.ajax({
									url: "{{ route('transaction.destroy') }}",
									type: 'post',
									data: {
											_method: 'delete',
											_token : '{{ csrf_token() }}',
												id: id,
										},
									dataType: "json",
									beforeSend: function() {
										$('.loader_wall_onspot').hide();
									},
									success:function(data, textStatus, jqXHR) {
										call_back("", `edit`, data.message, id);
										$('.close_full_modal').trigger('click');
										$('.loader_wall_onspot').hide();
										$('.delete_modal_ajax').modal('hide');
										$('.alert-success').text("Transaction Deleted Successfully!");
										$('.alert-success').show();

										setTimeout(function() { $('.alert').fadeOut(); }, 3000);
									},
									error:function(jqXHR, textStatus, errorThrown) {
									}
							});
						});
					}else{
						$('.delete_modal_ajax').find('.modal-title').text("Alert");
						$('.delete_modal_ajax').find('.modal-body').html("This can not be deleted, because It is referred in "+type+" <b>"+data.data.order_no+"</b>");
						$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
						$('.delete_modal_ajax').modal('show');
					}	
				}else if(data.type == 'job_request' || data.type == 'sale_order' || data.type == 'purchases')
				{
					var reference_no = data.reference_no;
					var status = data.status;
					var transaction_type = data.transaction_type;
					var data = data.data;

					if(status == 0 || data == null){
						$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
						$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
						$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
						$('.delete_modal_ajax').modal('show');
	                	$('.delete_modal_ajax_btn').off().on('click', function() {
							$.ajax({
								url: "{{ route('transaction.destroy') }}",
								type: 'post',
								data: {
										_method: 'delete',
										_token : '{{ csrf_token() }}',
											id: id,
								},
								dataType: "json",
								beforeSend: function() {
									$('.loader_wall_onspot').hide();
								},
								success:function(data, textStatus, jqXHR) {
									call_back("", `edit`, data.message, id);
									$('.close_full_modal').trigger('click');
									$('.loader_wall_onspot').hide();
									$('.delete_modal_ajax').modal('hide');
									$('.alert-success').text("Transaction Deleted Successfully!");
									$('.alert-success').show();

									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
								},
								error:function(jqXHR, textStatus, errorThrown) {
								}
							});
						});
					}else if(status == 1 && reference_no == null){
						$('.delete_modal_ajax').find('.modal-title').text("Alert");
								$('.delete_modal_ajax').find('.modal-body').text("Accounts, Journals and Inventory had been updated. This can not be deleted");
								$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
								$('.delete_modal_ajax').modal('show');
					}else if(status == 1 && transaction_type.display_name == 'Job Invoice' || transaction_type.display_name == 'Invoice' || transaction_type.display_name == 'Purchase Order'){
						$('.delete_modal_ajax').find('.modal-title').text("Alert");
						$('.delete_modal_ajax').find('.modal-body').html("This can not be deleted, because It is referred in "+transaction_type.display_name+ "<b> "+reference_no+"</b>");
						$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
						$('.delete_modal_ajax').modal('show');
					}else if(status == 1 && transaction_type.display_name == 'Job Card' || transaction_type.display_name == 'Estimation' || transaction_type.display_name == 'Goods Receipt Note'){
						$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
						$('.delete_modal_ajax').find('.modal-body').html("It is referred from "+transaction_type.display_name+ "<b>" +reference_no+"</b>, Are you sure to delete?");
						$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
						$('.delete_modal_ajax').modal('show');
	                    $('.delete_modal_ajax_btn').off().on('click', function() {
								$.ajax({
									url: "{{ route('transaction.destroy') }}",
									type: 'post',
									data: {
											_method: 'delete',
											_token : '{{ csrf_token() }}',
											id: id,
									},
									dataType: "json",
									beforeSend: function() {
										$('.loader_wall_onspot').hide();
									},
									success:function(data, textStatus, jqXHR) {
										call_back("", `edit`, data.message, id);
										$('.close_full_modal').trigger('click');
										$('.loader_wall_onspot').hide();
										$('.delete_modal_ajax').modal('hide');
										$('.alert-success').text("Transaction Deleted Successfully!");
										$('.alert-success').show();

										setTimeout(function() { $('.alert').fadeOut(); }, 3000);
									},
									error:function(jqXHR, textStatus, errorThrown) {
									}
								});
							});
					}
				}else if(data.type == 'job_invoice' || data.type == 'sales' || data.type == 'goods_receipt_note')
				{
						
						var action = data.action;
						if(action == 0){
							$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
						$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
						$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
						$('.delete_modal_ajax').modal('show');
	                	$('.delete_modal_ajax_btn').off().on('click', function() {
							$.ajax({
								url: "{{ route('transaction.destroy') }}",
								type: 'post',
								data: {
										_method: 'delete',
										_token : '{{ csrf_token() }}',
											id: id,
								},
								dataType: "json",
								beforeSend: function() {
									$('.loader_wall_onspot').hide();
								},
								success:function(data, textStatus, jqXHR) {
									call_back("", `edit`, data.message, id);
									$('.close_full_modal').trigger('click');
									$('.loader_wall_onspot').hide();
									$('.delete_modal_ajax').modal('hide');
									$('.alert-success').text("Transaction Deleted Successfully!");
									$('.alert-success').show();

									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
								},
								error:function(jqXHR, textStatus, errorThrown) {
								}
							});
						});
						}else if(action == 1){
							var reference_no = data.data.reference_no;
							var status = data.data.approval_status;
							if(status == 0){
								$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
								$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
								$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
								$('.delete_modal_ajax').modal('show');
	                    		$('.delete_modal_ajax_btn').off().on('click', function() {
								$.ajax({
										url: "{{ route('transaction.destroy') }}",
										type: 'post',
										data: {
											_method: 'delete',
											_token : '{{ csrf_token() }}',
												id: id,
											},
										dataType: "json",
										beforeSend: function() {
										$('.loader_wall_onspot').hide();
										},
										success:function(data, textStatus, jqXHR) {
										call_back("", `edit`, data.message, id);
										$('.close_full_modal').trigger('click');
										$('.loader_wall_onspot').hide();
										$('.delete_modal_ajax').modal('hide');
										$('.alert-success').text("Transaction Deleted Successfully!");
										$('.alert-success').show();

										setTimeout(function() { $('.alert').fadeOut(); }, 3000);
										},
										error:function(jqXHR, textStatus, errorThrown) {
									}
								});
							});
							}else if(status == 1){
								$('.delete_modal_ajax').find('.modal-title').text("Alert");
								$('.delete_modal_ajax').find('.modal-body').text("Accounts, Journals and Inventory had been updated. This can not be deleted");
								$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
								$('.delete_modal_ajax').modal('show');
							}
						}

				}else if(data.type == 'debit_note' || data.type == 'credit_note'){
					var action = data.action;
					if(action == 0){
						$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
						$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
						$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
						$('.delete_modal_ajax').modal('show');
	                	$('.delete_modal_ajax_btn').off().on('click', function() {
							$.ajax({
								url: "{{ route('transaction.destroy') }}",
								type: 'post',
								data: {
										_method: 'delete',
										_token : '{{ csrf_token() }}',
											id: id,
								},
								dataType: "json",
								beforeSend: function() {
									$('.loader_wall_onspot').hide();
								},
								success:function(data, textStatus, jqXHR) {
									call_back("", `edit`, data.message, id);
									$('.close_full_modal').trigger('click');
									$('.loader_wall_onspot').hide();
									$('.delete_modal_ajax').modal('hide');
									$('.alert-success').text("Transaction Deleted Successfully!");
									$('.alert-success').show();

									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
								},
								error:function(jqXHR, textStatus, errorThrown) {
								}
							});
						});
					}else{
						$('.delete_modal_ajax').find('.modal-title').text("Alert");
								$('.delete_modal_ajax').find('.modal-body').text("Accounts, Journals and Inventory had been updated. This can not be deleted");
								$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
								$('.delete_modal_ajax').modal('show');
					}

				}
				else{
							var url = "{{ route('transaction.multidestroy') }}";
										multidelete(url);
					}					 		
				},
						error: function(jqXHR, textStatus, errorThrown) {}
				});
			
			});


			function multidelete( url) {
				var values = [];
				$(".data_table").find('tbody tr').each(function() {
					var value = $(this).find("td:first").find("input:checked").val();
					if(value != undefined) {
						values.push(value);
					}
				});
				$('.delete_modal_ajax').modal('show');
				$('.delete_modal_ajax_btn').off().on('click', function() {
					$.ajax({
						url: url,
						type: 'post',
						data: {
							_method: 'delete',
							_token: '{{ csrf_token() }}',
							id: values.join(",")
						},
						dataType: "json",
						success: function(data, textStatus, jqXHR) {
							datatable.destroy();
							var list = data.data.list;
							for(var i in list) {
								$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').remove();
							}
							$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
							$("input.item_checkbox, input[name=checkbox_all]").prop('checked', false);
							datatable = $('#datatable').DataTable(datatable_options);
							$('.delete_modal_ajax').modal('hide');
						},
						error: function(jqXHR, textStatus, errorThrown) {}
					});
				});
			}

			function active_status(status, url) {
				var values = [];
					$(".data_table").find('tbody tr').each(function() {
						var value = $(this).find("td:first").find("input:checked").val();
						if(value != undefined) {
							values.push(value);
						}
					});
				$.ajax({
						url: url,
						type: 'post',
						data: {
							_token: '{{ csrf_token() }}',
							id: values.join(","),
							status: status
						},
						dataType: "json",
						success: function(data, textStatus, jqXHR) {
							datatable.destroy();
							var list = data.data.list;
							for(var i in list) {
								if(status == 1) {
									$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-warning');
									$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-success');
									$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').text("Approved");
								}else if(status == 0) {
									$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-success');
									$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-warning');
									$('body').find("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').text("Draft");
								}
								

								//var active_text = $("input.item_checkbox[value="+list[i]+"]").closest('tr').find('label.status').closest('td').find('select').find('option[value="'+status+'"]').text();
								
							}
							$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
							$("input.item_checkbox, input[name=checkbox_all]").prop('checked', false);
							datatable = $('#datatable').DataTable(datatable_options);

							if(data.status == "1") {
								
								$('.alert-danger').text(data.message);
								$('.alert-danger').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							}
							else {
								
								$('.alert-success').text(data.message);
								$('.alert-success').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							}
							
						},
						error: function(jqXHR, textStatus, errorThrown) {}
					});
			}
			

	



			function delete_row(id, parent, delete_url) {
				$('.delete_modal_ajax').modal('show');
					$('.delete_modal_ajax_btn').off().on('click', function() {
						$.ajax({
							 url: delete_url,
							 type: 'post',
							 data: {
								_method: 'delete',
								_token : '{{ csrf_token() }}',
								id: id,
								},
							 dataType: "json",
								success:function(data, textStatus, jqXHR) {
									datatable.destroy();
									parent.remove();
									datatable =  $('#datatable').DataTable(datatable_options);
									$('.delete_modal_ajax').modal('hide');
									alert_message(data.message, "success");
								},
							 error:function(jqXHR, textStatus, errorThrown) {
								}
							});
					});
			}

		});




	//job_card_search
	$('.search').on('click',function(e){
		e.preventDefault();
		//alert();
		var html='';
		var from_date = $('input[name=from_date]').val();
		//alert(from_date);
		var to_date = $('input[name=to_date]').val();
		var job = "job_card";
		//alert(to_date);
		$.ajax({
			type: 'post',
			url: '{{ route('get_job_card_data')}}',
			data: {
				_token: '{{ csrf_token() }}',
				from_date : from_date,
				to_date : to_date,
				type: job,


			},
			success:function(data,jqXHR,textStatus)
			{				
				var datas = data.data;

				if(data.status == 1)
				{
					$('#datatable tbody').empty();
					for(var i in datas)
					{
						var advance=datas[i].advance_amount;
						var advance_amount;
						if(advance==null){
							advance_amount="";
						}else{
							advance_amount=datas[i].advance_amount;
						}
						html+=`<tr>
						<td style="padding-left: 7px;"><input type="checkbox" id="`+datas[i].id+`" value="`+datas[i].id+`" name="transaction" class="item_checkbox"  data-id ="`+datas[i].id+`" data-job_card_status_id =  "`+datas[i].jobcard_status_id+`"><label for="`+datas[i].id+`"><span></span></label></input>
						</td>
						<td>`+datas[i].order_no+`</td>
						<td>`+datas[i].registration_no+`</td>
						<td>`+datas[i].customer+`</td>
						<td>`+datas[i].assigned_to+`</td>
						<td>`+datas[i].balance+`</td>
						<td>`+advance_amount+`</td>
						<td>`+datas[i].job_date+`</td>						
						
						<td>`;
						/*console.log(datas[i].jobcard_status_id);*/
						if(datas[i].jobcard_status_id == '1')
						{
					  	html+=`<label class="grid_label badge badge-default job_status">New</label>`;
					  	}
						else if(datas[i].jobcard_status_id == '2')
						{
						  	html+=`<label class="grid_label badge badge-success job_status">First Inspected</label>`;
						}
						else if(datas[i].jobcard_status_id == '3')
						{
						  	html+=`<label class="grid_label badge badge-warning job_status">Estimation Pending</label>`;
						}
						else if(datas[i].jobcard_status_id == '4')
						{
						  	html+=`<label class="grid_label badge badge-danger job_status">Estimation Approved</label>`;
						}
						else if(datas[i].jobcard_status_id == '5')
						  	html+=`<label class="grid_label badge badge-default job_status">Work in Progress</label>`;
						else if(datas[i].jobcard_status_id == '6')
						{
							html+=`<label class="grid_label badge badge-primary job_status">Final Inspected</label>`;
						}
						else if(datas[i].jobcard_status_id == '7')
						  	html+=`<label class="grid_label badge badge-info job_status">Vehicle Ready</label>`;
						else if(datas[i].jobcard_status_id == '8')
						{

						  	html+=`<label class="grid_label badge badge-warning job_status">Closed</label>`;
						}
						html+=`<select style="display:none" id="`+datas[i].id+`" class="active_status form-control">
							<option  value="1">New</option>
							<option value="2">First Inspected</option>
							<option value="3">Estimation Pending</option>
							<option value="4">Estimation Approved</option>
							<option value="5">Work in Progress</option>
							<option value="6">Final Inspected</option>
							<option value="7">Vehicle Ready</option>
							<option value="8">Closed</option>
						</select>`;
						html+=`</td>
						<td>
						<div class="action_options">
						</div>
						<button type="button" class="btn btn-info" id="job_card_actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button>
						
						
						</td>				


						</tr>`;
					}

					//$('tbody').html(html);
					call_back_optional(html,`add`,``);
					//$('#datatable tbody').append(html);

				}
				else
				{
					call_back_optional(``,`add`,``);
					alert_message(data.message,'error');
				}

			},
			error:function()
			{

			}


		});

	});

	$('.estimation_search').on('click',function(){
		//alert();
		var html = '';
		var  type_name = "job_request";
		var fromdate = $('input[name=from_date]').val();
		var to_date = $('input[name=to_date]').val();
		$.ajax({
			url : '{{  route('get_job_card_data') }}',
			type : 'POST',
			data: 
			{
				_token : '{{ csrf_token() }}',
				from_date : fromdate,
				to_date : to_date,
				type : type_name
			},
			success:function(data)
			{
				//alert();
				var datas = data.data;
				if(data.status == 1)
				{
					$('#datatable tbody').empty();
					for(var i in datas)
					{
						
						html+=`<tr>
						<td>
							<input type="checkbox" id="`+datas[i].id+`" value="`+datas[i].id+`" name="transaction" class="item_checkbox"  data-id = "`+datas[i].id+`" data-approval_status ="`+datas[i].approval_status+`"><label for="`+datas[i].id+`"><span></span></label></input>
						</td>
						<td><a style="color: #3366ff;" class="po_edit" data-id="`+datas[i].id+`">`+datas[i].order_no+`</a></td>
						<td><a style="color: #3366ff;" class="go_to_jc" data-id="`+datas[i].originated_from_id+`" >`+datas[i].reference_no+`</a></td>
						<td>`+datas[i].registration_no+`</td>
						<td>`+datas[i].service_type+`</td>
						<td>`+datas[i].customer+`</td>
						<td>`+datas[i].jobcard_total+`</td>
						<td>`+datas[i].job_date+`</td>`;

						html+=`<td>`;
						if(datas[i].approval_status ==0)
						{
							html+= `<label class="grid_label badge badge-warning status">Draft</label>`;
						}
						else
						{
							html+=`<label class="grid_label badge badge-success status">Approved</label>`;
						}
						html+=`</td>

						<td>
						<div class="action_options">
						</div>
						<button type="button" class="btn btn-info" id="job_request_actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button>
						
						</td>

						</tr>`;
					}
				call_back_optional(html,`add`,``);

				}
				else
				{
					call_back_optional(``,`add`,``);
					alert_message(data.message,'error');
				}

			
			},
			error:function()
			{

			}



		});

	});

	$('.search_all').on('click',function(){

		var html='';
		var type_name = $('input[name=type_name]').val();
		
		
		var from_date = $('input[name=from_date]').val();
		var to_date = $('input[name=to_date]').val();

		$.ajax({
			type: 'POST',
			url : '{{ route('get_job_card_data')}}',
			data :{
				_token : '{{ csrf_token() }}',
				from_date : from_date,
				to_date : to_date,
				type : type_name

			},
			success:function(data)
			{
				//alert();
				var datas = data.data;
				
				var type = data.type;
				var get_due_date = data.get_date;
				
				if(data.status == 1)
				{
					$('#datatable tbody').empty();
					for(var i in datas)
					{
						
						html+=`<tr>
						<td><input type="checkbox" name="transaction" id="`+datas[i].id+`" value="`+datas[i].id+`" class="item_checkbox" data-id = "`+datas[i].id+`" data-approval_status ="`+datas[i].approval_status+`"><label for="`+datas[i].id+`"><span></span></label></input></td>`;
						if(type == 'delivery_note')
						{
							html+=`<td>`+datas[i].order_no+`</td>`;
						}

						html+=`<td>`;
						if(type == 'debit_note' || type == 'delivery_note' || type == 'credit_note')
						{
								
								html+=`<a class="po_edit" data-id=`+datas[i].id+`" data-vehicle_id="`+datas[i].vehicle_id+`">`+datas[i].reference_no+`</a>`;
						} 
						else
						{
							html+=`<a class="po_edit" data-id="`+datas[i].id+`" data-vehicle_id"`+datas[i].vehicle_id+`>`+datas[i].order_no+`</a>`; 
							
						}
						html+= `</td>`;	
						
						
						
						if(type == 'goods_receipt_note')
						{
							html+=`<td>`+datas[i].reference_no+`</td>`;
						}
				
						if(type == "purchases" || type == 'estimation' || type == 'sale_order' || type == 'sales' || type == 'sales_cash')
						{
							
							html+=`<td>`+datas[i].reference_type+`</td>`;
						}
						if(type == 'purchases' || type == 'estimation' || type == 'sale_order' || type == 'sales' || type == 'sales_cash')
						{
							html+=`<td>`+datas[i].reference_no+`</td>`;
						}
		
						html+=`<td>`+datas[i].customer+`</td>
						<td>`+datas[i].customer_contact+`</td>
						<td>`+datas[i].total+`</td>
						<td>`+datas[i].date+`</td>`;
					/*	if(type == 'purchases' || type == 'sales' || type == 'sales_cash' || type == 'delivery_note' || type == 'credit_note' || type == 'debit_note'  )
						{
							html+=`<td>`+datas[i].due_date+`</td>`;
						}*/
			
			
						if(type == 'purchase_order' || type == 'estimation' || type == 'sale_order')
						{
							html+=`<td>`+datas[i].shipping_date+`</td>`;
						}

					
						if(type == 'purchases' || type == 'sales' || type == 'sales_cash' )
						{
							html+=`<td>`+datas[i].balance+`</td>
							<td>`;

							if(datas[i].status == 0)
							{
								html+=`<label class="grid_label badge badge-warning">Pending</label>`; 
							}
						
							else if(datas[i].status == 1)
							{
								html+=`<label class="grid_label badge badge-success">Paid</label> `;
							}
						
							else if(datas[i].status == 2)
							{
								html+=`<label class="grid_label badge badge-info">Partially Paid</label> `;
							}
						
							else
							{
								html+= `<label class="grid_label badge badge-danger">Over due `+datas[i].get_date+`days</label> `;;					
								
									
							}
						
							html+=`</td>`;
						}
													
						html+=`<td>`;
						if(datas[i].approval_status == 0)
						{
							html+=`<label class="grid_label badge badge-warning status">Draft</label> `;
						}
						
						else
						{
							html+=`<label class="grid_label badge badge-success status">Approved</label>`;
						}
						 
						html+=`</td>`;
						html+=`<td>
						<div class="action_options">
						</div>
						<button type="button" class="btn btn-info" id="actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button>
						
						
				  	</td>
				
						
						</tr>`;
					}
					call_back_optional(html,`add`,``);

				}
				else
				{
					call_back_optional(``,`add`,``);
					alert_message(data.message,'error');
				}

			},
			error:function()
			{

			}

		});

	});
	
	$('.invoice_search').on('click',function(e){
		e.preventDefault();
		var html='';
		var from_date = $('input[name=from_date]').val();
		var to_date = $('input[name=to_date]').val();
		var job = "job_invoice";
		$.ajax({
			type: 'post',
			url: '{{ route('get_job_card_data')}}',
			data: {
				_token: '{{ csrf_token() }}',
				from_date : from_date,
				to_date : to_date,
				type: job,
			},
			success:function(data,jqXHR,textStatus)
			{
				var datas=data.data;
				if( data.status== 1)
				{
					 datatable.destroy();
					$('#datatable tbody').empty();


					for(var i in datas)
					{

						if(datas[i].status == 0)
						{
							active_selected = "selected";
							selected_text = "Pending";
							selected_class = "badge-warning";
						}
						else if(datas[i].status == 1)
						{
							active_selected = "selected";
							selected_text = "Paid";
							selected_class = "badge-success";
						}
						else if(datas[i].status == 2)
						{
							active_selected = "selected";
							selected_text = "Partially Paid";
							selected_class = "badge-info";
						}
						else if(datas[i].status == 3)
						{
							active_selected = "selected";
							selected_text = "Over due days";
							selected_class = "badge-danger";
						}

						if(datas[i].approval_status == 0)
						{
							active_selected = "selected";
							text = "Draft";
							selected = "badge-warning";
						}
						else if(datas[i].approval_status == 1)
						{
							active_selected = "selected";
							text = "Approved";
							selected = "badge-success";
						}
						html+=`<tr>
						<td style="padding-left: 7px;"><input type="checkbox" name="transaction" id="`+datas[i].id+`" value="`+datas[i].id+`" class="item_checkbox" data-id= "`+datas[i].id+`" data-approval_status="`+datas[i].approval_status+`"> <label for="`+datas[i].id+`"><span></span></label></input></td>
						<td><a style="color: #3366ff;" class="po_edit" data-id="`+datas[i].id+`"  data-vehicle_id="`+datas[i].vehicle_id+`">`+datas[i].order_no+`</a></td>
						<td><a style="color: #3366ff;" class='go_to_jc' data-id="`+datas[i].originated_from_id+`">`+datas[i].jc_order_no+`</a></td>					
						<td><a style="color: #3366ff;" class='reference' data-id="`+datas[i].reference_id+`" data-vehicle_id="`+datas[i].vehicle_id+`">`+datas[i].reference_no+`</a></td>
						<td>`+datas[i].registration_no+`</td>
						<td>`+datas[i].customer+`</td>
						<td>`+datas[i].total+`</td>

						<td></td>
						<td>`+datas[i].job_date+`</td>
						
						<td>`+datas[i].balance+`</td>
						<td>
							<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						</td>
						<td>
							<label class="grid_label badge `+selected+` status">`+text+`</label> 
						</td>
						<td>
							<div class="action_options">
							</div>
							<button type="button" class="btn btn-info" id="job_invoice_actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button>
							
							
							
						</td>
						</tr>`;
					}
					
					//call_back_optional(html,`add`,``);
					$('#datatable tbody').append(html);
					datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [], 

		dom: 'lBfrtip',
		buttons: [
			{
			extend: 'pdf',
				footer: true,
				exportOptions: {
					columns: [1,2]
				}
			},
			{
				extend: 'csv',
				footer: false,
				exportOptions: {
					columns: [1,2]
				}
			},
			{
				extend: 'excel',
				exportOptions: {
					columns: ":not(.noExport)"
				},
				footer: false
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ":not(.noExport)",
					stripHtml: false,
				},
				autoPrint: true
			}
		]


	};

		datatable=$('#datatable').DataTable(datatable_options);

				}
				else
				{
					call_back_optional(``,`add`,``);
					alert_message(data.message,'error');
				}

			},
			error:function()
			{

			}


		});

	});


	</script>
@stop