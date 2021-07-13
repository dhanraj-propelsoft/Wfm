{{--
@extends('layouts.master')

@section('content')
@include('includes.add_user')
@include('includes.add_business')

--}}

<style>
#select2-payment_terms-container{
		background-color: yellow;
	}
#select2-payment_method_id-container{
	background-color: yellow;
	}
#select2-voucher_term_id-container{
	background-color: yellow;
}
.chevron {
 display: inline-block;
  position: relative;
  clear: both;
/*  padding: 8px;*/
  height:25px;
  width: 90px;
  margin-top:25px;
  text-align:center;
  margin-right: 3px;
}

.chevron:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  height: 50%;
  width: 100%;
  background: #adb6e1;
  color: white;
  -webkit-transform: skew(60deg, 0deg);
  -moz-transform: skew(60deg, 0deg);
  -ms-transform: skew(60deg, 0deg);
  -o-transform: skew(60deg, 0deg);
  transform: skew(60deg, 0deg);
 /* z-index:-1;*/
}

.chevron:after {
  content: '';
  position: absolute;
  top: 49%;
  right: 0;
  height: 50%;
  width: 100%;
  background: #adb6e1;
  -webkit-transform: skew(-60deg, 0deg);
  -moz-transform: skew(-60deg, 0deg);
  -ms-transform: skew(-60deg, 0deg);
  -o-transform: skew(-60deg, 0deg);
  transform: skew(-60deg, 0deg);
  /* z-index:-1;*/
}

.chevron_active {
 display: inline-block;
  position: relative;
  clear: both;
 /* padding: 8px;*/
  height:25px;
  width: 90px;
  margin-top:25px;
  text-align:center;
  margin-right: 3px;
}

.chevron_active:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  height: 50%;
  width: 100%;
  background: #243175;
  color: white;
  -webkit-transform: skew(60deg, 0deg);
  -moz-transform: skew(60deg, 0deg);
  -ms-transform: skew(60deg, 0deg);
  -o-transform: skew(60deg, 0deg);
  transform: skew(60deg, 0deg);
 /* z-index:-1;*/
}

.chevron_active:after {
  content: '';
  position: absolute;
  top: 49%;
  right: 0;
  height: 50%;
  width: 100%;
  background: #243175;
  -webkit-transform: skew(-60deg, 0deg);
  -moz-transform: skew(-60deg, 0deg);
  -ms-transform: skew(-60deg, 0deg);
  -o-transform: skew(-60deg, 0deg);
  transform: skew(-60deg, 0deg);
  /* z-index:-1;*/
}
</style>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/transaction.css') }}">

<div class="content">

	<!-- <div class="alert alert-success">
		{{ Session::get('flash_message') }}
	</div>
	<div class="alert alert-danger"></div> -->
		@if($errors->any())
			<div class="alert alert-danger">
				@foreach($errors->all() as $error)
					<p>{{ $error }}</p>
				@endforeach
			</div>
		@endif
  	<!-- <div class="modal-header"> -->
  	<div class="fill header">
  		
	    <h3 class="float-left voucher_name"> @if(!empty($transactions))
	      {{ $transaction_type->display_name }}# {{ $transactions->order_no }}
	      @else
	      {{$transaction_type->display_name}}# {{$voucher_no}}
	      @endif </h3>
	    <!-- <div style="cursor: pointer;" class="float-left voucher_code"><i style="font-size: 20px; color: #b73c3c; padding-top: 5px; padding-left: 5px;" class="fa icon-basic-gear"></i></div> -->
	    <div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i> </div>
	    <!-- <div class="float-right side_panel"><i style="font-size: 25px" class="fa icon-basic-gear"></i></div> --> 
  	</div>
	
  	<div class="clearfix"></div>
	  	{!! Form::open(['class' => 'form-horizontal transactionform']) !!}
	  	{{ csrf_field() }} 

  	<!--   <div class="modal-body"> --> 

	<div class="form-body" style="padding: 5px 20px 50px; margin-top: 2px; ">
	<div class="alert alert-success">
		{{ Session::get('flash_message') }}
	</div>
	<div class="alert alert-danger"></div>
  	<!-- </div> -->
		<ul class="nav nav-tabs">

			@if($transaction_type->module == 'fuel_station')
			   <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#invoice_details">Invoice Details</a> </li>
			  
	    
		    @elseif($transaction_type->module == 'trade_wms')
		    	<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#order_details">Job Details</a> </li>
		    	<!-- <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#vehicles">Vehicles</a> </li> -->
		    	<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#item_details">Job & Parts</a> </li>    	
		    	
		    	@if($transaction_type->name == 'job_card')
		    		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#attachments">Attachments</a> </li>
		    		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#readings">Readings</a> </li>
		    		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#checklist">Checklist</a> </li> 
		    		
		    	@endif
		    	@else
		    	 <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#order_details">Order Details</a> </li>
			    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#item_details">Item Details</a> </li>
		    @endif
	  	</ul>

	  	<div class="tab-content">

	  		{!! Form::hidden('id', $id) !!}

			@if($transaction_type->module == 'fuel_station')
				<div class="tab-pane active" id="invoice_details">	 <div class="row">		 			
						<div class="form-group col-md-3">
							<label class="control-label required" for="order_id">Sales Type</label>
							 
							<div class="custom-panel" >
								<input id="cash_type" type="radio" name="job_sale_type" value="cash" @if($transaction_type->name == "job_invoice_cash") checked="checked" @endif />

								<label for="cash_type" class="custom-panel-radio"><span></span>Cash</label>

								<input id="credit_type" type="radio" name="job_sale_type"  value="credit" @if($transaction_type->name == "job_invoice") checked="checked" @endif />

								<label for="credit_type"><span></span>Credit</label>
							</div>
						</div>		
		 				<div class="col-md-3">
							<div class="form-group">
								<div class="row">
									<div class="col-md-10">
										<label for="registration_number" class="control-label required">Registration Number</label>
										{{ Form::select('registration_number', $vehicles_register, $wms_transaction->registration_id, ['class' => 'form-control select_item', 'id' => 'registration_number']) }}
									</div>						
									<div class="col-md-1 col-md-offset-1" style="padding-top: 30px; ">
										<a href="javascript:;" id="" class="add_vehicle" ><i class="fa fa-car"></i></a>
									</div>
								</div>
							</div>
						</div>										
						<div class="col-md-3">
							<div class="form-group">
								<div class="row">
									<label for="vehicle_name" class="required">Make/ Modal / variant / 	Version</label>
									{{ Form::text('vehicle_name', $wms_transaction->vehicle_configuration, ['class' => 'form-control', 'id' => 'vehicle_name','disabled']) }}
								</div>
							</div>
						</div>
						<div class="" style="width: 180px;margin-left: 20px">	
							<div class=" customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
								{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
								<div class="custom-panel" >
									<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
									<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
									<input id="people_type" type="radio" name="customer" value="0" />
									<label for="people_type"><span></span>People</label>					
								</div>
							</div>
						</div>	
					</div>
					<div class="row">
				 		<div class="col-md-3 search_container people" style= "@if($customer_label == null) display:none @endif"> 
							{{ Form::label('people', $customer_label, array('class' => 'control-label required')) }}

							{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}

							{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
							{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
							<div class="content"></div>
						</div>

						<div class="col-md-3 search_container business" style= "@if($customer_label == null) display:none @endif"> 
							{{ Form::label('business', $customer_label, array('class' => 'control-label required')) }}

							{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}

							{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
							{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
							<div class="content"></div>
				    	</div>				
						<div class="col-md-3">
							<div class="form-group ">
								<label for="driver" class="control-label">Contact / Driver Name</label>
								{{ Form::text('driver', $wms_transaction->driver, ['class'=>'form-control']) }}
							
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group col-md-12">
								<div class="row ">
									<label for="driver_contact" class="control-label">Contact / Driver Number</label>
									{{ Form::text('driver_contact', $wms_transaction->driver_contact, ['class'=>'form-control','id' => 'driver_contact']) }}
								</div>
							</div>
						</div>						
						<div class="col-md-3">
							<div class="form-group ">
								<label for="vehicle_mileage" class="control-label required">Vehicle Odometer Mileage</label>
								{{ Form::text('vehicle_mileage', $wms_transaction->vehicle_mileage, ['class' => 'form-control numbers']) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-3">
								<label for="invoice_date" class="required">Invoice Date</label>
									{{ Form::text('invoice_date', $wms_transaction->job_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
							</div>	
							<div class="col-md-3">
								<label for="employee_id">Invoiced By</label>
								{{ Form::select('employee_id', $employees,null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}
							</div>				
							<div class="col-md-3">
								<label for="job_due_date">Payment Due Date</label>
									{{ Form::text('job_due_date', $wms_transaction->job_due_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
							</div>			
							<div >						
								{{ Form::hidden('job_completed_date', $wms_transaction->job_completed_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
							</div>		
							<div class="col-md-3">
								<label for="payment_terms">Payment Terms</label>
								{{ Form::select('payment_terms', $payment_terms, $wms_transaction->payment_terms, ['class' => 'form-control select_item', 'id' => 'payment_terms', 'placeholder' => 'Select Payment Terms']) }}
							</div>
						</div >
					</div>
					<div class="form-group ">
						<div class="row">
							<div class="col-md-3">
								<label for="payment_mode">Payment Method</label>
									{{ Form::select('payment_method_id', $payment, null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }} 
							</div>
							<div class="col-md-3">
			    				<label for="payment_mode">Shift Name</label>

									{{ Form::select('shift_id', $shift, $wms_transaction->shift_id, ['class' => 'form-control ', 'id' => 'shift_id','disabled']) }} 
			    			</div>
			    			<div class="col-md-3">
			    				<label for="payment_mode">Pump Name</label>

									{{ Form::select('pump_id', $pump_name, $wms_transaction->pump_id, ['class' => 'form-control select_item', 'id' => 'pump_id']) }} 

			    			</div>
			    			<div class="col-md-3">
								<label for="group_name_show">Customer Group :</label>
								&nbsp;&nbsp;
								<div style="float:right;">
										{{ Form::text('group_name_show',null,['class'=> 'form-control',
									'id' =>'group_name_show','disabled']) }}
								</div>
							</div>
									
							</div>		
						</div>	
					<div class="form-group" style="margin-left: 15px;margin-top: 10px">
						<table style="border-collapse: collapse;" class="table table-bordered crud_table">
							<thead>
								<tr>
									<th width="4%">#</th>
									<th width="25%">Product </th>	
									<th width="13%" style= "">Disc.Type</th>		
									<th width="10%">Unit Price</th>
									<th width="8%" style= " ">Disc %</th>
									<th width="6%">Stock</th>
									<th width="6%">Qty</th>
									<th width="10%">Rate</th>		
									<th width="10%" >Tax %</th>
									<th width="10%">Tax Amount</th>
									<th width="10%">Total</th>
									<th width="3%"></th>
								</tr>
							</thead>
							<tbody>
							<tr class="parent items">
								<td class="sorter"><span class="index_number" style="float: right; padding-left: 5px;">1</span></td>
								<td>
									<select name="item_id" class="form-control select_item" id="item_id">
									<option value="">Select Item</option>
									<?php $selected_item = null; ?>
															
									@foreach($items as $item)
									@if($selected_item != $item->category) 
									<optgroup label="{{$item->category}}"> @endif	
									<?php $selected_item = $item->category; ?>
									<option data-tax="{{$item->include_tax}}" data-purchase_tax="{{$item->include_purchase_tax}}" data-rate = "" value="{{$item->id}}">{{$item->name}}</option>									
									</optgroup>
											@endforeach	
										</select>
									<input type="hidden" name="parent_id">

									<input type="hidden" name="batch_id">

									<div class='item_container'></div>

								</td>
									<div class='description_container'></div>
										@if($discount_option)
									<td >
											<select name='discount_id' class='form-control select_item taxes' id = 'discount_id'>
											<option value="">Select Discount</option>
										@foreach($discounts as $discount) 
											<option value="{{$discount->id}}" data-value="{{$discount->value}}">{{$discount->display_name}}</option>
										@endforeach
											</select>
									</td>
										@endif
									<td>
										{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
										<div class='rate_container'></div>
									</td>
									
									<td >
										{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}
									 </td>
									
														
									<td>
										{{ Form::text('in_stock', null, ['class'=>'form-control numbers', 'disabled', 'id' => 'in_stock']) }}
									</td>
									<td>
										{{ Form::text('quantity', null, ['class'=>'form-control decimal']) }}
										<div class='quantity_container'></div>
									</td>
									<td>
										{{ Form::text('amount', null, ['class'=>'form-control numbers']) }}
									</td>
									<td style= "@if($transaction_type->name == 'job_card') display:none @endif">
										<select name='tax_id' class='form-control select_item taxes' id = 'tax_id'>
										<option value="">Select Tax</option>
										@foreach($taxes as $tax) 
											<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
										@endforeach
										</select>
										<div class='tax_container'></div> 
									</td>			
										
									<td>
										{{ Form::text('tax_amount', null, ['class'=>'form-control decimal']) }}
									</td>
										
									<td>
										{{ Form::text('tax_total', null, ['class'=>'form-control decimal']) }}
									</td>
										
									<td>
										<a class="grid_label action-btn delete-icon remove_row approval_status"><i class="fa fa-trash-o"></i></a> 
										<a  class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="row" style="margin-left: 250px;margin-top: 10px">
						<table id="new" class= "total_rows" align="right">
							<tr>
								<td id="new">
										<div id="design">
											<div id="left">
												<h6>Total Rate  : </h6>
											</div>
											<div id="right">
												<h6 class="sub_total">0.00</h6>
											</div>
										</div>
								</td>
								<td id="new">
									<div id="design">
								 			<div id="left">
								 				<h6> Tax Amount : </h6>
								 			</div>
								 			<div id="right">
												{{ Form::text('tax_amount', null, ['class'=>'form-control decimal','style'=>'color:blue;font-size:15px;background-color:transparent;border:0;width:90px;padding-top:1px;','disabled']) }}
											</div>
									</div>
								</td>
								<td class="advance" id="new">
										<div id="design">
											<div id="left">
												<h6>Advance Amount :</h6>
											</div>
											<div id="right">
												<h6 class="advance_value">0.00</h6>
												{{ Form::hidden('advance_text', null, ['class'=>'form-control decimal']) }}
											</div>
										</div>
								</td>
								<td id="new">
									<div id="design">
											<div id="left">
												<h6 >Total Amount</h6>
											</div>
											<div id="right">
												<h6 class= "total">0.00</h6>
												<input type="hidden" name="total">
											</div>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<br>
					<div class="row" style="margin-top: 20px">
						<div class="form-group custom-panel col-md-12">
							<div class="row custom-panel-address">
								<div class="col-md-12 ">
									<div class="row ">
										<div class="col-md-12">
											<label><b>{{$address_label}}</b></label>
										</div>
										<div class="col-md-3">
											<label for="date">Name</label>
												{{ Form::text('customer_name',($transactions->name != null) ? $transactions->name : null, ['class'=>'form-control display_name', 'autocomplete' => 'off']) }} 
										</div>
										<div class="col-md-3">
											<label for="date">Mobile</label>
											{{ Form::text('customer_mobile', ($transactions->mobile != null) ? $transactions->mobile : null, ['class'=>'form-control mobile', 'autocomplete' => 'off']) }} 
										</div>
										<div class="col-md-3">
											<label for="date">Email</label>
											{{ Form::text('customer_email',($transactions->email != null) ? $transactions->email : null, ['class'=>'form-control email', 'autocomplete' => 'off']) }} 
										</div>
										<div class="col-md-3">
											<label for="date">Address:</label>
											{{ Form::textarea('customer_address', ($transactions->address != null) ? $transactions->addrss : null, ['class'=>'form-control address', 'style'=>' height: 30px;']) }} 
										</div>
									</div>
								</div>
							</div>
							<div class="row custom-panel-address">
								<div class="col-md-12 ">
									<div class="row ">
										<div class="col-md-12">
											<label>
											<div class="row">
												<div style="@if($company_label) display: none; @endif" class="col-md-12">
													{{ Form::checkbox('billing_checkbox', '1', $company_label, array('id' => 'billing_checkbox')) }}
													<label for="billing_checkbox"><span></span>Billing address is different</label>
												</div>
											</div>
											</label>
										</div>
										<div class="col-md-3  billing">
											<label for="date">Billing Name</label>
												<input type="text" class="form-control " name="billing_name" value="{{$company_name}}" autocomplete="off" /> 
										</div>
										<div class="col-md-3  billing">
											<label for="date">Billing Mobile</label>			
											<input type="text" class="form-control  " name="billing_mobile" value="{{$company_mobile}}" autocomplete="off"  />
										</div>
										<div class="col-md-3  billing">
											<label for="date">Billing Email</label>
												<input type="text" class="form-control  " name="billing_email" value="{{$company_email}}" autocomplete="off"  /> 
										</div>
										<div class="col-md-3  billing">
											<label for="date">Billing Address</label>
											<textarea name="billing_address" class="form-control  "	style="height: 30px;" >{{$company_address}}</textarea>
										</div>	
									</div>
								</div>
							</div>
						</div>
					</div>	    	
				</div>	
			@endif

	  		@if($transaction_type->module == 'trade' ||  $transaction_type->module == 'inventory')

		    	<div class="tab-pane active" id="order_details">

					<div class="form-group">
						<div class="row">

							<div class="col-md-3" style= "@if(count($order_type_value) == 0) display:none @endif">
								<label for="order_id">Reference Type</label>

								{{ Form::select('order_type', $order_type_value, $reference_transaction_type, ['class' => 'form-control','readonly']) }}
							</div>

							<div class="col-md-3" style= "@if($order_label == null) display:none @endif">
								<label for="order_id">{{$order_label}}</label>

								<?php
									$reference_no = null;

									if(!empty($transactions)) {
										$reference_no = $transactions->reference_no;
									}
								?>
								 {{ Form::text('order_id', $reference_no, ['class'=>'form-control']) }}

								 {{ Form::hidden('reference_id', null, ['class'=>'form-control']) }}
							</div>
													
						</div>
					</div>

					<div class="form-group">
						{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
						<div class="row" style="padding-left: 30px;">
							

							<div class="row col-md-6 custom-panel">

								<div class="col-md-6 customer_type" style= "padding:2px;@if($customer_type_label == null) display:none @endif"> 
									<!-- {{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br> --> 
									<div class="" >
										<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
										<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
										<input id="people_type" type="radio" name="customer" value="0" />
										<label for="people_type"><span></span>People</label>
									</div>
								</div>
								<div class="col-md-6 search_container people" style= "padding:2px;@if($customer_label == null) display:none @endif"> 
									<!-- {{ Form::label('people', $customer_label, array('class' => 'control-label required')) }} -->								
									{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}

									{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
									{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
								<div class="content"></div>
								</div>

								<div class="col-md-6 search_container business" style= "padding:2px;@if($customer_label == null) display:none @endif"> 
									<!-- {{ Form::label('business', $customer_label, array('class' => 'control-label required')) }} -->
									
									{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}

									{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
									{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
								<div class="content"></div>
								</div>
							</div>
							
							
							

											
							
						</div>
					</div>

					<div class="form-group">
						<div class="row">
							@if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash")
							<div class="col-md-3">
								<label class="control-label required" for="order_id">Type</label> <br>
								<div class="custom-panel" >
								<input id="cash_type" type="radio" name="sale_type" value="cash" @if($transaction_type->name == "sales_cash") checked="checked" @endif />
								<label for="cash_type"><span></span>Cash</label>
								<input id="credit_type" type="radio" name="sale_type"  value="credit" @if($transaction_type->name == "sales") checked="checked" @endif />
								<label for="credit_type"><span></span>Credit</label>
								</div>
							</div>
							@endif


							<div class="col-md-3" style= "@if($sales_person_label == null) display:none @endif">
							<label for="employee_id">{{$sales_person_label}}</label>
							{{ Form::select('employee_id', $employees, null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }} </div>


							@if($transaction_type->name == "purchase_order")
							<div class="col-md-3">
							<label class="required" for="approval_status">Order Status</label>
								<select name='approval_status' class='form-control select_item' id = 'approval_status'>
									@if($transaction_type->name = "purchase_order")
									<option value="0">Pending</option>
									<option value="1">Approved</option>
									@endif
								</select>
							</div>
							@endif

						</div>
					</div>

					<div class="form-group">
						<div class="row">					

							<div class="col-md-3" style= "@if($payment_label == null) display:none @endif">
							<label for="payment_mode">{{$payment_label}}</label>
							{{ Form::select('payment_method_id', $payment,null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }}
							</div>					
						

							<div class="col-md-3">						

							@if($transaction_type->name == 'delivery_note')
								<label for="date">Delivery Mode</label>
								{{ Form::select('shipment_mode_id', $shipment_mode, '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}
							@else
								<label for="date">Shippment Mode</label>
								{{ Form::select('shipment_mode_id', $shipment_mode, '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}
							@endif
							</div>

						

						@if($transaction_type->name == 'delivery_note' )
							<div class="col-md-3">
								<label for="delivery_details">Delivery Details</label>
								{{ Form::text('delivery_details', null ,['class' => 'form-control number']) }} 
							</div>
						@endif

							

						</div>
					</div>

					<div class="form-group">
						<div class="row">

							<div class="col-md-3" style= "@if($date_label == null) display:none @endif">
								<label class="required" for="date">{{$date_label}}</label>
									{{ Form::text('invoice_date', ($transaction_type->date_setting == 0) ? date('d-m-Y') : null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) }} 
								</div>

								<div class="col-md-3">
									@if($transaction_type->name == 'delivery_note' )

										<label for="shipping_date">Delivery On</label>
										{{ Form::text('shipping_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}							

									@else
										<label for="shipping_date">Shipping Date</label>
										{{ Form::text('shipping_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
									@endif
							</div>

						</div>
					</div>

					<div class="form-group">
						<div class="row">

							@if($transaction_type->name != "sales_cash")
								<div class="col-md-3" style= "@if($term_label == null) display:none @endif">

								{{ Form::label('voucher_term_id', $term_label, array('class' => 'control-label required')) }}

								{{ Form::select('voucher_term_id', $terms, null, ['class' => 'form-control select_item ', 'id' => 'voucher_term_id']) }}

								</div>
							@endif	

									
							@if($transaction_type->name != "sales_cash")
							<div class="col-md-3"  style= "@if($due_date_label == null) display:none @endif">
								{{ Form::label('due_date', $due_date_label, array('class' => 'control-label required')) }}

								{{ Form::text('due_date', $due_date, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) }} </div>
							@endif

							

							@if($transaction_type->name == "estimation")
								<div class="col-md-3">	
									<label for="estimate_size">Estimate Size</label>					
									{{ Form::text('estimate_size', null, ['class'=>'form-control ']) }} 
								</div>
							@endif

							

						</div>
					</div>

					<div class="form-group">
						<div class="row">
							
							@if($transaction_type->name == 'delivery_note')
							<div class="col-md-3">
								<label for="delivery_by">Delivery By</label>
								{{ Form::text('delivery_by', null ,['class' => 'form-control']) }}
							</div>
							@endif
						</div>
					</div>

					<div class="form-group">

					<div class="row">

						@if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "purchases" || $transaction_type->name == "delivery_note" || $transaction_type->name == "goods_receipt_note")

						<div class="col-md-3" style= "@if($transaction_type->name == 'delivery_note' || $transaction_type->name == 'goods_receipt_note' ) display:none @endif">

							<label><b>Update Stock</b></label>

							<input name="stock_update" type="checkbox"  id ="stock_update" value="1" class="form-control"><label for="stock_update"><span></span></label>

						</div>

						@endif

					</div>
				</div>


					
				 	<br><br>
					

					<div class="form-group custom-panel">
						<div class="row custom-panel-address">

							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<label><b>{{$address_label}}</b></label>
										<input name="update_customer_info" type="checkbox" value="" id="update_customer_info" data-toggle="tooltip" data-placement="top" title="Check to update customer master"><label for="update_customer_info"><span></span></label></input>

										{{ Form::text('customer_name',null, ['class'=>'form-control display_name', 'autocomplete' => 'off']) }}
										{{ Form::text('customer_mobile', null, ['class'=>'form-control mobile', 'autocomplete' => 'off']) }} 
										{{ Form::text('customer_email', null, ['class'=>'form-control email', 'autocomplete' => 'off']) }}
										{{ Form::textarea('customer_address', null, ['class'=>'form-control address', 'size' => '30x2']) }} 
									</div>
									<div class="col-md-3">
										<label><b>Billing Communication</b></label>
										<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="billing_name" value="{{$company_name}}" autocomplete="off" /> 
										<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="billing_mobile" value="{{$company_mobile}}" autocomplete="off"  /> 
										<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  email @endif " name="billing_email" value="{{$company_email}}" autocomplete="off"  />
										<textarea name="billing_address" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  address @endif " cols="30" rows="2"> {{$company_address}}</textarea>
									</div>
									<div class="col-md-3">
										<label><b>Shipping Communication</b></label>
											<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="shipping_name" value="{{$company_name}}" autocomplete="off"  />
											<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="shipping_mobile" value="{{$company_mobile}}" autocomplete="off"  /> 
											<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   email @endif " name="shipping_email" value="{{$company_email}}" autocomplete="off"  />
											<textarea name="shipping_address" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   address @endif " cols="30" rows="2" > {{$company_address}}</textarea>
									</div>

									
								</div>
							</div>

						</div>
					</div>

		    	</div>

			    <div class="tab-pane" id="item_details">

					<div class="clearfix"></div>
						<div style="float:right; width: 130px; margin: 10px;display:none;"> 
						<select name="tax_types" class='form-control select_item' disabled>
							<option value="2">Exclude Tax</option>
							<option value="1">Include Tax</option>		
							<option value="0">Out Of Scope</option>
						</select>
					</div>

					<div class="clearfix"></div>
					<div class="form-group">

						@if($transaction_type->module == 'trade' )

						<div style="float:right;margin:5px;padding-top: 5px; @if($transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' ||  $transaction_type->name == 'credit_note') display: none; @endif">
							
							<div style="float:left;font-weight:bold;color: #4b5056;">
								<label for="new_discount_value">Discount % :</label>
							</div>

							<div style="float:right;">
								{{ Form::text('new_discount_value',null,['class' => 'form-control','style' =>'width:40px'])}}
							</div>
							
						</div>
						@endif


					
					<table style="border-collapse: collapse;" class="table table-bordered crud_table">

						<thead>
							<tr>
								<th width="2%">#</th>
								<th width="25%">Item</th>			
								<th width="10%">Description</th>
								
								@if($transaction_type->module == 'inventory')
								<th style= "" width="10%">Selling Price</th>								
								<!-- <th style= "" width="10%">Base Price</th> -->
								@endif	

								@if($transaction_type->module == 'inventory')
									@if($transaction_type->name == 'purchases' || $transaction_type->name == 'goods_receipt_note' )
									<th width="12%"> NewSellingPrice</th>	
									@endif
								@endif					
												
								@if($transaction_type->module == 'trade')
									@if($discount_option )
										<th width="13%">Disc.Type</th>
									@endif
								@endif
								@if($transaction_type->module == 'trade')
										<th width="12%">Unit Price</th>
								@endif
								@if($transaction_type->module == 'inventory')
										<th width="12%">Unit/Purchase Price</th>
								@endif


								<!-- <th width="10%">Unit Price</th> -->

								

								@if($transaction_type->module == 'trade')
								<th width="8%" style=" @if($transaction_type->name == 'sale_order' ||  $transaction_type->name == 'credit_note') display: none; @endif">
									Disc %
								</th>								

								@endif

								<th width="6%">Qty</th>
								<!-- <th width="10%">Unit Price</th> -->
								<th width="10%">Amount</th>					
								<th width="10%">Tax%</th>
								<th width="10%">TaxAmount</th>

								<!-- @if($transaction_type->module == 'trade')
									@if($discount_option )
										<th width="13%">Disc.Type</th>
										<th width="8%">Discount %</th>
									@endif
								@endif -->

								<th width="10%">Total</th>
								<th width="3%"></th>
							</tr>
						
						</thead>


						<tbody>
						<tr id="tr_1" data-row="1" class="parent items">

							<td class=""><span class="index_number" style="float: right; padding-left: 5px;">1</span>
							</td>

							@if($transaction_type->module == 'inventory')

							<td>
								<div style="width:175px;float: left;">

								<select name="item_id" class="form-control select_item" id="item_id">
								<option value="">Select Item</option>
								<?php $selected_item = null; ?>
								
								
								@foreach($items as $item)

									@if($selected_item != $item->category)
										<optgroup label="{{$item->category}}"> 
									@endif
										
								
									<?php $selected_item = $item->category; ?>
									<option data-tax="{{$item->include_tax}}" data-purchase_tax="{{$item->include_purchase_tax}}" data-rate = "" value="{{$item->id}}">{{$item->name}}</option>

								@endforeach
									</optgroup>
								</select>

								<input type="hidden" name="parent_id">
								<input type="hidden" name="batch_id">

								</div>

								@if($transactions->approval_status != 1)

								<div style="float:right;" id="jc_item_create">
									<a href="javascript:;" id="" data-toggle="tooltip" title="Add Item"  class="jc_item_create"><i class="fa fa-cube" style="padding: 2px;" aria-hidden="true"></i></a>
								</div>								

								@endif								

							</td>

							@endif

							@if($transaction_type->module == 'trade')

							<td>

								<div style="width:180px;float: left;">

								<select name="item_id" class="form-control select_item" id="item_id">
								<option value="">Select Item</option>
								<?php $selected_item = null; ?>
								
								
								@foreach($items as $item)

									@if($selected_item != $item->category)
										<optgroup label="{{$item->category}}"> 
									@endif
										
								
									<?php $selected_item = $item->category; ?>
									<option data-tax="{{$item->include_tax}}" data-purchase_tax="{{$item->include_purchase_tax}}" data-rate = "" value="{{$item->id}}">{{$item->name}}</option>

								@endforeach
									</optgroup>
								</select>

									<input type="hidden" name="parent_id">
									<input type="hidden" name="batch_id">

								</div>
								@if($transactions->approval_status != 1)

								<div style="float:right;" id="jc_item_create">
									<a href="javascript:;" id="" data-toggle="tooltip" title="Add Item"  class="jc_item_create"><i class="fa fa-cube" style="padding: 2px;" aria-hidden="true"></i></a>
								</div>

								<div style="float:right; display: none;" id="item_batch" class="item_batch">

									<a href="javascript:;"><i class="fa fa-cart-plus" style="padding: 5px;" aria-hidden="true"></i></a>

								</div>

								@endif
								

							</td>

							@endif

							<td>
								{{ Form::textarea('description', null, ['class'=>'form-control', 'style'=>' height: 26px;' , 'placeholder' => 'Description']) }}
							</td>
							@if($transaction_type->module == 'inventory')
								<td style= "">
									{{ Form::text('base_price', null, ['class'=>'form-control decimal','disabled']) }}
								</td>
								<!-- 
								<td style= "">
									{{ Form::text('base_price', null, ['class'=>'form-control decimal','disabled']) }}
								</td> -->
							@endif
							@if($transaction_type->module == 'inventory')
									@if($transaction_type->name == 'purchases' || $transaction_type->name == 'goods_receipt_note' )
									<td style= "">
										{{ Form::text('new_base_price', null, ['class'=>'form-control decimal']) }}
										<!-- <span class="fa fa-refresh" style="float:right;"></span> -->

											
									</td>
									@endif
							@endif




						
							@if($transaction_type->module == 'trade')	

								@if($discount_option)
										<td>
											<select name='discount_id' class='form-control select_item taxes' id = 'discount_id'>
											<option value="">Select Discount</option>
											@foreach($discounts as $discount) 
											<option value="{{$discount->id}}" data-value="{{$discount->value}}">{{$discount->display_name}}</option>
											@endforeach
											</select>
										</td>
										
								@endif
							@endif
							@if($transaction_type->module == 'trade')	

								<td>
									{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
									
								</td>
							@endif
							@if($transaction_type->module == 'inventory')	
								<td>
									{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
									
								</td>
							@endif

							<!-- <td>
								{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
								<div class='rate_container'></div>
							</td> -->

							

								@if($transaction_type->module == 'trade')	

									<td style=" @if($transaction_type->name == 'sale_order' ||  $transaction_type->name == 'credit_note') display: none; @endif">
										{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}

									</td>

								@endif

							<td>
								{{ Form::text('quantity', null, ['class'=>'form-control decimal']) }}
								
							</td>

							

							<!-- <td>
								{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
								<div class='rate_container'></div>
							</td> -->

							 <td>
								{{ Form::text('amount', null, ['class'=>'form-control numbers']) }}
							</td>							


							<td>
								<select name='tax_id' class='form-control select_item taxes' id = 'tax_id'>
									<option value="">Select Tax</option>
									@foreach($taxes as $tax) 
										<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
									@endforeach
								</select>
								
						
							 </td>

							<td>
								{{ Form::text('tax_amount', null, ['class'=>'form-control decimal']) }}
							</td>

							 

								
							<!-- @if($transaction_type->module == 'trade')	
							
								@if($discount_option)
										<td>
											<select name='discount_id' class='form-control select_item taxes' id = 'discount_id'>
											<option value="">Select Discount</option>
											@foreach($discounts as $discount) 
											<option value="{{$discount->id}}" data-value="{{$discount->value}}">{{$discount->display_name}}</option>
											@endforeach
											</select>
										</td>
										<td>
											{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}
										</td>
								@endif
							@endif -->

						 	<td>
								{{ Form::text('tax_total', null, ['class'=>'form-control decimal']) }}
							</td>




							<td style="">
								<a class="grid_label action-btn delete-icon remove_row approval_status"><i class="fa fa-trash-o"></i></a> 
								<a  class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>
							</td>

							
						</tr>
						</tbody>
					</table>

						<table id ="new" align="right">
								
							<tr>

								<td id="new" >
									<div id="design">
										<div id="left" ><h6>Sub Total :</h6></div>
								
										<div id="right"><h6 class="sub_total">0.00</h6>
										</div>
									</div>
								</td>

								@if($transaction_type->module == 'trade')
								@if($discount_option)	
								<td id="new" class="total_rows">
									<div id="design">
										<div id="left" ><h6>Total Discount:</h6></div>
										<div id="right">{{ Form::text('sum_discount', null, ['class'=>'form-control decimal','style'=>'color:blue;font-size:15px;background-color:transparent;border:0;padding-top:1px;width:90px;','disabled']) }}
										</div>
									</div>
								</td>
								@endif
								@endif
											
								
								<td id="new" class="total_rows">
									<div id="design">
										<div id="left" ><h6>Tax Amount :</h6></div>

										<div id="right"><h6 class="box_tax_amount">0.00</h6>

										</div>
							
										<!-- <div id="right">{{ Form::text('tax_amount', null, ['class'=>'form-control decimal','style'=>'color:blue;background-color:transparent;border:0;padding-top:1px;font-size:15px;width:90px;','disabled']) }}
										</div> -->
									</div>
								
								</td>
									
								<td id="new" >
								
									<div id="design">
										<div id="left"><h6>Total Amount :</h6></div>
										<div id="right">
										<h6 class= "total"  >0.00</h6>
										<input type="hidden" name="total">
										</div>
									</div>
									
								</td>
										
							</tr>
						</table>


					</div>
					

				</div>	


		    @endif

		    <!-- Doubt -->

				<div class="recurring" style="display: none;" >
					<hr>
					<div class="form-group">
						<div class="row">
						<div class="col-md-2"> {!! Form::label('interval', 'Interval', ['class' => 'control-label']) !!}			
							{!!	Form::select('interval', ['0'=>'Daily','1'=>'Weekly','2'=>'Monthly'], null, ['class' => 'form-control select_item']); !!} </div>
				
						<div class="col-md-2 month" style="display: none;">
							<label style="position: absolute; left: -5px; top: 30px;">On</label>
							<label class="control-label">&nbsp;</label>
							{!! Form::label('period', 'Period', ['class' => 'control-label']) !!}			
							{!!	Form::select('period', ['' => 'Day','1'=>'First','2'=>'Second','3'=>'Third','4'=>'Fourth','0'=>'Last'], null, ['class' => 'form-control select_item']); !!} </div>
				
						<div class="col-md-2 week" style="display: none;">
							<label class="control-label">&nbsp;</label>
							{!! Form::select('week_day_id', $weekdays, $weekday ,['class' => 'form-control select_item']) !!} </div>
				
						<div class="col-md-2 day" style="display: none;">
							<label class="control-label">&nbsp;</label>
							{{ Form::select('day',$days,null ,['class' => 'form-control select_item']) }} </div>
				
						<div class="col-md-3 every">
							<label class="control-label" style="width: 100%">&nbsp;</label>
							<label class="every_time" style="float: left; padding-right: 8px;"> every </label>
							{{ Form::text('frequency', null, ['class'=>'form-control numbers', 'style' => 'float:left; width: 50px']) }} 
							<label class="period" style="float: left; padding-left: 8px;"> day(s) </label> 
						</div>
				
							</div>
							</div>
							 <div class="form-group">
							<div class="row">
							 <div class="col-md-2">
							{!! Form::label('start_date', 'Start Date', ['class' => 'control-label']) !!}	
							{{ Form::text('start_date', null ,['class' => 'form-control date-picker datetype']) }} 
							</div>
				
							<div class="col-md-2">
							{!! Form::label('end', 'End', ['class' => 'control-label']) !!}	
							{!!	Form::select('end', ['0' => 'None','1'=>'By','2'=>'After'], 0, ['class' => 'form-control select_item']); !!}
							</div>
				
							<div class="col-md-2 end_date" style="display: none;">
							{!! Form::label('end_date', 'End Date', ['class' => 'control-label']) !!}	
							{{ Form::text('end_date', null ,['class' => 'form-control date-picker datetype']) }} 
							</div>
				
							<div class="col-md-1 occurrence" style="display: none;">
							<label class="control-label">&nbsp;</label>
							{{ Form::text('end_occurrence', null ,['class' => 'form-control number']) }} 
							<label class="period" style="position: absolute; right: -60px; top: 30px;"> Occurrences </label>
							</div>
						</div>
					</div>
					<hr>
				</div>	

				<div style="display: none;" class="form-group">
					<div class="row field_container">
					<?php $val = null; ?>
					@foreach($transaction_fields as $field)
						@if($field->sub_heading != $val)
						<?php $val = $field->sub_heading; ?>
						<div class="col-md-12"><span style="border-bottom: 1px solid #ccc; float: left; margin: 30px 0 20px; width: 100%; font-size: 18px; text-transform: capitalize; font-family: 'ProximaNovaLight', 'ProximaNovaRegular', 'Source Sans Pro', Arial, sans-serif; ">
								{{$field->sub_heading}} </span></div>
						@endif

						<div class="col-md-3 field_label">  				
						@if($field->field_type == 'textbox')
						<label class="fields control-label" style="text-transform:capitalize;width:100%;">{{$field->name}}</label>
						<input name="transaction_field" data-name="{{$field->id}}" data-format="{{$field->id}}" data-format_id="{{$field->id}}" class="form-control {{$field->field_format}}" />
						@elseif($field->field_type == 'checkbox')
						<label class="fields control-label" style="text-transform:capitalize;width:100%;">{{$field->name}}</label>
						<?php
							$group_name = explode('`', $field->group_name); 
							for($i=0; $i<count($group_name); $i++) {
								echo '<input id='.$group_name[$i].' type="checkbox" name="transaction_field" value="'.$group_name[$i].'" /><label for='.$group_name[$i].' style="text-transform:capitalize;"><span></span>'.$group_name[$i].'</label>';
							}					
						?>			
						@elseif($field->field_type == 'radio')
						<label class="fields control-label" style="text-transform:capitalize;width:100%;">{{$field->name}}</label>
						<?php
							$group_name = explode('`', $field->group_name); 
							for($i=0; $i<count($group_name); $i++) {
								echo '<input id='.$group_name[$i].' type="radio" name="transaction_field" value="'.$group_name[$i].'" /><label for='.$group_name[$i].' style="text-transform:capitalize;"><span></span>'.$group_name[$i].'</label>';
							}					
						?>
						@elseif($field->field_type == 'select')
						<label class="fields control-label" style="text-transform:capitalize;width:100%;">{{$field->name}}</label>
						<select name="transaction_field" data-name="{{$field->id}}" class="form-control">
						<option class="capitalize">Select {{$field->name}}</option>
						<?php
							$group_name = explode('`', $field->group_name); 
							for($i=0; $i<count($group_name); $i++) {
								echo "<option class='capitalize' value=".$group_name[$i].">".$group_name[$i]."</option>";
							}
							
						?>
			           </select>
						@endif
							
						</div>
					@endforeach
					</div>	
			 	</div>

			<!-- Doubt -->


			@if($transaction_type->module == 'trade_wms')

				@if($transaction_type->name == 'job_card' || $transaction_type->name == 'job_request' || $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')

			    	<div class="tab-pane active" id="order_details">	
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<div class="row">
									<div class="col-md-10">
										<label for="registration_number" class="control-label required">Registration Number</label>
										{{ Form::select('registration_number', $vehicles_register, $wms_transaction->registration_id, ['class' => 'form-control select_item', 'id' => 'registration_number']) }}
									</div>
									<div class="col-md-2">
										<div class="col-md-1" style="padding-top: 30px; ">
										<a href="javascript:;" id="" class="add_vehicle" ><i class="fa fa-car"></i></a>
							       		</div>
									</div>
									</div>
								</div>
							</div>
						@if($transaction_type->name == 'job_card')
						<div class="col-md-9">

									
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 1 ? 'chevron_active' : 'chevron' }}" data-id="1" data-toggle="tooltip" data-placement="top" title="New">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 10px;">New</span>
						 </div>
						 </a>
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 2 ? 'chevron_active' : 'chevron' }}" data-id="2" data-toggle="tooltip" data-placement="top" title="First Inspected">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 8px;">First Inspected</span>
						 </div>
						 </a>
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 3 ? 'chevron_active' : 'chevron' }}" data-id="3" data-toggle="tooltip" data-placement="top" title="Estimation Pending">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 7px;">Estimation Pending</span>
						 </div>
						 </a>
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 4 ? 'chevron_active' : 'chevron' }}" data-id="4" data-toggle="tooltip" data-placement="top" title="Estimation Approved">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 7px;">Estimation Approved</span>
						 </div>
						 </a>
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 5 ? 'chevron_active' : 'chevron' }}" data-id="5" data-toggle="tooltip" data-placement="top" title="Work in Progress">
						 <div class="" id="che">
						 
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 8px;">Work in Progress</span>
						 </div>
						 </a>
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 6 ? 'chevron_active' : 'chevron' }}" data-id="6" data-toggle="tooltip" data-placement="top" title="Final Inspected">
						 <div class="" id="che">
						 
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 8px;">Final Inspected</span>
						 </div>
						</a>
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 7 ? 'chevron_active' : 'chevron' }}" data-id="7" data-toggle="tooltip" data-placement="top" title="Vehicle Ready">
						 <div class="" id="che">
						 
						 	<span style="position: relative;top:3px;z-index: 100;color: white;font-weight: bold;font-size: 10px;">Vehicle Ready</span>
						 </div>
						</a>
						<a href="#" class="jobcard_status_update job_card_status_change {{ $wms_transaction->jobcard_status_id >= 8 ? 'chevron_active' : 'chevron' }}" data-id="8" data-toggle="tooltip" data-placement="top" title="Closed">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 10px;">Closed</span>
						 </div>
						</a>	
						
						 
						
							<input name="jobcard_status_id" class="jobcard_status_id" type="hidden" value="{{ $wms_transaction->jobcard_status_id }}"></input>
						</div>
						
						@endif
							<div class="col-md-3">
								<!--<div class="form-group col-md-12">
									@if( $transaction_type->name == 'job_card')
									<div class="row">

										<label for="date" class="required">Job Card Status</label>
										{{ Form::select('jobcard_status_id', $job_card_status, $wms_transaction->jobcard_status_id, ['class' => 'form-control select_item ', 'id' => 'jobcard_status_id']) }}
									</div>
									@endif
								</div>-->
								
								@if( $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
								<div class="form-group col-md-12">
										<label class="control-label required" for="order_id">Sales Type</label> <br>	

										<div class="custom-panel" >
											<input id="cash_type" type="radio" name="job_sale_type" value="cash" @if($transaction_type->name == "job_invoice_cash") checked="checked" @endif />
											<label for="cash_type" class="custom-panel-radio"><span></span>Cash</label>

											<input id="credit_type" type="radio" name="job_sale_type"  value="credit" @if($transaction_type->name == "job_invoice") checked="checked" @endif />
											<label for="credit_type"><span></span>Credit</label>
										</div>
								</div>	
								@endif	
							</div>
					<!--		<div class="col-md-3">
								<div class="form-group col-md-12">
									<div class="row">
									<label for="vehicle_name" class="required">Make/ Modal / variant / Version</label>
									{{ Form::text('vehicle_name', $wms_transaction->vehicle_configuration, ['class' => 'form-control', 'id' => 'vehicle_name','disabled']) }}
									</div>
								</div>
							</div>
							<div class="col-md-3">

								<div class="form-group col-md-12">

								<div class="row">
	
								<label for="show_customer_name" class="required">Customer Name</label>

								<!-- ($official != null) ? $official->email : null; -->

								<!--{{ Form::text('show_customer_name', $cus_name, ['class' => 'form-control', 'id' => 'cus_name']) }}

								</div>

								</div>

							</div>-->
						</div>
						<div class="row">
						    <div class="col-md-6">

								<div class="form-group">

									<div class="row">

										<div class="col-md-12">

											<label for="complaint">Complaints
												<span style="display:none;color:#007bff;cursor: pointer;" class="jobcard_complaint" id="jobcard_complaint_group">(Pre Define)</span>
												<span style="color:#007bff;cursor: pointer;display:none" class="applied_complaint">(Pre Define)</span>
											</label>

											 <textarea name="complaint" class="form-control complaint"  value="{{ $wms_transaction->vehicle_complaints }}" style="height: 75px;">{{$wms_transaction->vehicle_complaints}} </textarea> 

										</div>

									</div>

								</div>

							</div>

							<div class="col-md-3">
								<div class="form-group  col-md-12">
									<div class="row">
									<label for="vehicle_name" class="required">Make/ Modal / variant / Version</label>

									{{ Form::text('vehicle_name', $wms_transaction->vehicle_configuration, ['class' => 'form-control', 'id' => 'vehicle_name','disabled']) }}

									<label for="driver" class="control-label">Contact / Driver Name</label>

									{{ Form::text('driver', $wms_transaction->driver, ['class'=>'form-control','id' => 'driver']) }}
									</div>


								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group  col-md-12">
									<div class="row">
									<label for="show_customer_name" class="required">Customer Name</label>

									{{ Form::text('show_customer_name',$cus_name, ['class' => 'form-control', 'id' => 'cus_name']) }}
									<label for="driver" class="control-label">Contact / Driver Number</label>

									{{ Form::text('driver_contact', $wms_transaction->driver_contact, ['class'=>'form-control','id' => 'driver_contact']) }}
									</div>
								</div>
							</div>
						<!--	<div class="col-md-6">
								<div class="form-group col-md-12 ">
									<div class="row">
										<label for="complaint" class="" style="cursor:pointer">Complaints
											<!-- <a class="jobcard_complaint" id="jobcard_complaint_group" style="color:#007bff;">(Pre-define)
											</a>
											<a class="applied_complaint" id="applied_complaint" style="display: none;color:#007bff;">(Pre-define)</a> -->
										<!--</label>
										
										{{ Form::textarea('compliant',$wms_transaction->vehicle_complaints,['class' => 'form-control','style'=> 'height:60px;'])}}-->
										 <!-- <textarea name="compliant" class="form-control complaint"  value=""></textarea> -->
										
									<!--</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group col-md-12">
									<div class="row ">
									<label for="driver" class="control-label">Contact / Driver Name</label>
									{{ Form::text('driver', $wms_transaction->driver, ['class'=>'form-control','id' => 'driver']) }}
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group col-md-8">
									<div class="row ">
									<label for="driver_contact" class="control-label">Contact / Driver Number</label>
									{{ Form::text('driver_contact',$wms_transaction->driver_contact, ['class'=>'form-control','id' =>'driver_contact']) }}
									</div>
									@if( $transaction_type->name == 'job_card')
									<div class="col-md-4" id = "additional_contacts" style="padding-left: 210px;top: -22px;">
										<a style="color: #3366ff;" class="show_contact"><i class="fa fa-ellipsis-h" style="font-size: 24px;" aria-hidden="true"></i>
										</a>
									</div>
									@endif	

								</div>
							</div>-->
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group col-md-12">
									<div class="row">
										<label for="vehicle_mileage" class="control-label required">Vehicle Odometer Mileage</label>
										{{ Form::text('vehicle_mileage', $wms_transaction->vehicle_mileage, ['class' => 'form-control numbers']) }}
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group col-md-12">
									<div class="row">
										<label for="next_visit_reason" class="control-label required">Vehicle Next Visit Reason</label>
										{{ Form::text('next_visit_reason', $wms_transaction->vehicle_next_visit_reason, ['class'=>'form-control']) }}
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group col-md-12">
									<div class="row ">
										<label for="next_visit_mileage" class="control-label required">Vehicle Next Visit - Odometer Mileage</label>
										{{ Form::text('next_visit_mileage', $wms_transaction->next_visit_mileage, ['class'=>'form-control numbers']) }}
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group col-md-12">
									<div class="row">
										<label for="next_visit_date" class="control-label required">Vehicle Next Visit - Date</label>
										{{ Form::text('next_visit_date', $wms_transaction->vehicle_next_visit, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								
								<div class="form-group">
									<div class="row ">
										@if($transaction_type->name == 'job_request' || $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
										<div class="col-md-6">
							                <label for="order_id">Reference Type</label>
											{{ Form::select('order_type', $order_type_value, null, ['class' => 'form-control']) }}
					              		</div>
								        <div class="col-md-6" style= "@if($order_label == null) display:none @endif">
											<label for="order_id">Reference Number</label>
											<?php
												$reference_no = null;

												if(!empty($transactions)) {
													$reference_no = $transactions->reference_no;
												}
											?>
											{{ Form::text('order_id', $reference_no, ['class'=>'form-control']) }}

											{{ Form::hidden('reference_id', null, ['class'=>'form-control']) }}
										</div>
										@endif
										
									</div>
								</div>
							
								<!-- <div class="form-group">
									<div class="row">
										<div class="col-md-6 customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
											{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
											<div class="custom-panel" >
											<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
											<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
											<input id="people_type" type="radio" name="customer" value="0" />
											<label for="people_type"><span></span>People</label>
											</div>
										</div>
										 <div class="col-md-6 search_container people" style= "@if($customer_label == null) display:none @endif"> 
												{{ Form::label('people', $customer_label, array('class' => 'control-label required')) }}
								
												{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
								
												{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
												{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
												<div class="content"></div>
										</div>
								
										<div class="col-md-6 search_container business" style= "@if($customer_label == null) display:none @endif"> 
												{{ Form::label('business', $customer_label, array('class' => 'control-label required')) }}
								
												{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}
								
												{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
												{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
												<div class="content"></div>
										</div>
									</div>
								</div> -->
								<div class="form-group">
									<div class="row">
										@if( $transaction_type->name == 'job_card' || $transaction_type->name == 'job_request')
										<div class="col-md-6">
											<label class="required" for="date">Date</label>
											{{ Form::text('job_date',  $wms_transaction->job_date , ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 
										</div>
										@endif
										@if( $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
										<div class="col-md-6">
											<label class="required" for="date">Invoice Date</label>
											{{ Form::text('job_date',  $wms_transaction->job_date , ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 
										</div>
										@endif
										@if( $transaction_type->name == 'job_card')
										<div class="col-md-6">
											<label for="job_due_date">Job Due Date</label>
											{{ Form::text('job_due_date', $wms_transaction->job_due_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
										</div>
										@endif
										@if( $transaction_type->name == 'job_request')
										<div class="col-md-6">
											<label for="job_due_date">Expiry Date</label>
											{{ Form::text('job_due_date', $wms_transaction->job_due_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
										</div>
										@endif
										@if(  $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
										<div class="col-md-6">
											<label for="job_due_date">Payment Due Date</label>
											{{ Form::text('job_due_date', $wms_transaction->job_due_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
										</div>
										@endif
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label for="shipment_mode_id">Delivery Method</label>
											{{ Form::select('shipment_mode_id', $shipment_mode, '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }} 
										</div>
										<!-- <div class="col-md-6">
											<label for="job_completed_date">Delivery Date</label>
											{{ Form::text('job_completed_date', $wms_transaction->job_completed_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
										</div> -->
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										@if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
										<div class="col-md-6">
											<label for="payment_terms">Payment Terms</label>
											{{ Form::select('payment_terms', $payment_terms, $wms_transaction->payment_terms, ['class' => 'form-control select_item', 'id' => 'payment_terms', 'placeholder' => 'Select Payment Terms']) }}
										</div>
										<div class="col-md-6">
											<label for="payment_mode">Payment Method</label>
											{{ Form::select('payment_method_id', $payment, null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }} 
										</div>
										@endif
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										@if($transaction_type->name == 'job_card' || $transaction_type->name == 'job_request')
										<div class="col-md-6">
											<label for="employee_id">Assigned to</label>
											{{ Form::select('employee_id', $employees,null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}
										</div>
										@endif
										@if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
										<div class="col-md-6">
											<label for="employee_id">Invoiced By</label>
											{{ Form::select('employee_id', $employees,null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}
										</div>
										@endif
										<div class="col-md-6">
											<label for="sub_total" class="control-label required">Total</label>
											{{ Form::text('wms_total',$transactions->total, ['class' => 'form-control total', 'id' => 'sub_total','disabled']) }}
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-12" style="border: 1px solid #d7dbe0;padding:1px;margin-top: 15px;overflow-y:scroll;height: 60%;">
			    					<br/>
			    					<div style="padding: -5px;margin-top: -12px;">

			    						<table style="border-collapse: collapse;" class="table table-bordered" id="specification">
			    							<thead>
			    								<tr>
			    									<th width="15%">Specifications</th>
			    									<th width="12%"> Values</th>
			    								</tr>
			    							</thead>
			    							<tbody>

		                                       @foreach($spec_values as $spec_value)
												<tr>
													<td>					{{$spec_value->display_name}}
													</td>
													<td>
											        {{ Form::text('value',$spec_value->spec_value, ['class'=>'form-control','disabled']) }}
								              		</td>						
												</tr>
											 	 @endforeach
		                                         		
			    							</tbody>
			    						</table>
			    					</div>

			    				</div>
			    				@if( $transaction_type->name == 'job_card')
			    				<div class="form-group col-md-12" style="top:30px;padding-left: 40px;">

									<div class="row">
	                                   <?php
										$date=date_create($approved_date);
										?>
						
										<div class="row">
											@if($approvel_status == '0')
											<b><a  href="{{url('jc_acknowladge/')}}/{{$transactions->id}}/{{$org_id}}" target="_blank" >Job Card Acknowladgement</a></b>&nbsp;&nbsp;&nbsp;
											<b><a  href="#" class="vechile_history" data-id="{{$wms_transaction->registration_id}}" style="color: green;">Vechile History</a></b>
											 @elseif($approvel_status == '1') 
											<b><a  href="{{url('jc_acknowladge/')}}/{{$transactions->id}}/{{$org_id}}" target="_blank" >Approved on (<?php echo date_format($date,"l jS \of F Y h:i:s A"); ?>)</a></b>
											 @endif
									    </div>
									</div>
								</div>
								@endif
			    				@if( $transaction_type->name == 'job_request')
			    				<div class="form-group col-md-6" style="top:30px;padding-left: 40px;">
									<div class="row">
	                                   <?php
										$date=date_create($approved_date);
										?>
						
										<div class="row">
											@if($approvel_status == '0')
											<b><a  href="{{url('viewlist/')}}/{{$transactions->id}}/{{$org_id}}" target="_blank" >Approve Estimation</a></b>
											 @elseif($approvel_status == '1') 
											<b><a  href="{{url('viewlist/')}}/{{$transactions->id}}/{{$org_id}}" target="_blank" >Estimation Approved on (<?php echo date_format($date,"l jS \of F Y h:i:s A"); ?>)</a></b>
											 @endif
									    </div>
									</div>
								</div>
			    				@endif

		    				</div>

		    				
						</div>
						<br>
						
						<div class="form-group custom-panel col-md-12">
	    			
	    				<div class="row custom-panel-address">
	    					<div class="col-md-12 ">
	    					
	    						<div class="row">
	    								
		    							<div class="col-md-3" >
		    								<label><b>{{$address_label}}</b></label>
		    								<input name="update_customer_info" type="checkbox" value="" id="update_customer_info" data-toggle="tooltip" data-placement="top" title="Check to update customer master"><label for="update_customer_info"><span></span></label></input>
		    								{{ Form::text('customer_name',null, ['class'=>'form-control ', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Name']) }} 
		    								{{ Form::text('customer_mobile', null, ['class'=>'form-control ', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Mobile']) }} 
		    								{{ Form::text('customer_email', null, ['class'=>'form-control ', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Email' ]) }}
		    								{{ Form::text('customer_gst', $transactions->gst, ['class'=>'form-control ', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'GST' ]) }}
		    								{{ Form::textarea('customer_address', null, ['class'=>'form-control ','style'=>' height: 30px;','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Address']) }}
		    								
		    							</div>
		    							<div class="col-md-3" >
		    								<label><b>Billing Communication</b>
		    								<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Billing Name" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="billing_name" value="{{$company_name}}" autocomplete="off" /> 
		    									
		    								<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Billing Mobile" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="billing_mobile" value="{{$company_mobile}}" autocomplete="off" />

		    								<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Billing Email" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  email @endif " name="billing_email" value="{{$company_email}}" autocomplete="off"  />
		    								<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Billing GST" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  email @endif " name="billing_gst" value="{{$transactions->billing_gst}}" autocomplete="off"  />
		    								<textarea name="billing_address" style="width:280px;height: 30px;" data-toggle="tooltip" data-placement="top" title="Billing Address" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  address @endif " cols="30" rows="2"> {{$company_address}}</textarea>
		    									
		    							</div>
		    							<div class="col-md-3">

		    								<label><b>Shipping Communication</b>
		    								<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Shipping Name" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="shipping_name" value="{{$company_name}}" autocomplete="off"  />
		    								
		    								<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Shipping Mobile" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="shipping_mobile" value="{{$company_mobile}}" autocomplete="off"  />

		    								<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Shipping Email" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   email @endif " name="shipping_email" value="{{$company_email}}" autocomplete="off" />
		    								<textarea name="shipping_address" data-toggle="tooltip" data-placement="top" title="Shipping Address" style="width:280px;height: 30px;" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   address @endif " cols="30" rows="2" > {{$company_address}}</textarea>
		    								
		    							</div>
		    						
	    			
	    			
	    						</div>
	    			
	    					</div>
	    				</div>
	    			
	    											
	    			
	    				</div>

	    				<div class="row">
							<div id ="show_more_detail" >
								
								<label for="show_vehicle_detail" ><span></span><a  id="show_vehicle_detail" style="padding: 17px;color: #007bff;font-weight: bold;">Show More..</a></label></input>

								<div class="row row-full" style="border: 1px solid #d7dbe0;">
								</div>

							</div>
							<div style="display: none;" id ="show_less_detail">
								<label for="hide_vehicle_detail" ><span></span><a  id="hide_vehicle_detail" style="padding: 17px;color: #007bff;font-weight: bold;">Show Less..</a></label></input>

								<div class="row show-full" style="border: 1px solid #d7dbe0;">

								<div class="show_vehicle_details col-md-12" style="display:none;padding:10px;">

									<div class="row" style="padding-left: 30px;">

									<div class="row col-md-6 custom-panel">
										<div class="col-md-6">
										<div class="form-group">
											<div class="row ">
												<div class="col-md-12 customer_type" style= "padding: 2px;@if($customer_type_label == null) display:none @endif"> 
													<!-- {{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br> -->
												<div class="custom-panel" >

												<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />

												<label for="business_type" class="custom-panel-radio"><span></span>Business</label>

												<input id="people_type" type="radio" name="customer" value="0" />

												<label for="people_type"><span></span>People</label>

														</div>
													</div>
															
												</div>
											</div>
												
										</div> 

											<div class="col-md-6">
												<div class="form-group">
												<div class="row">
												<div class="col-md-12 search_container people" style= "padding: 2px;@if($customer_label == null) display:none @endif"> 
													<!-- {{ Form::label('people', $customer_label, array('class' => 'control-label required')) }} -->

												{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}

												{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}

												{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}

												<div class="content">
														
												</div>
											</div>

													<div class="col-md-12 search_container business" style= "padding:2px;@if($customer_label == null) display:none @endif"> 
														<!-- {{ Form::label('business', $customer_label, array('class' => 'control-label required')) }} -->

													{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}

													{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
													{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
													<div class="content"></div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<div class="row">
														<div class="col-md-12">
														<label for="job_completed_date">Delivery Date</label>
														{{ Form::text('job_completed_date', $wms_transaction->job_completed_date, ['class'=>'form-control date-picker datetype rearrangedate', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
														</div>
													</div>
												</div>
											</div>
										<div class="col-md-3">
												<div class="form-group">
													<div class="row">
														
													@if( $transaction_type->name == 'job_card')
													<div class=" col-md-12">

														<label for="date" class="required">Job Card Status</label>
														{{ Form::select('jobcard_status_id', $job_card_status, $wms_transaction->jobcard_status_id, ['class' => 'form-control select_item ', 'id' => 'jobcard_status_id']) }}
													</div>
													@endif
								
														
													</div>
												</div>
											</div>
									</div>

									<div class="row">
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row ">
													<label for="vehicle_category" class="control-label required">Vehicle Category</label>
													{{ Form::select('vehicle_category', $vehicle_category, null, ['class' => 'form-control select_item', 'id' => 'vehicle_category', 'disabled']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row ">
													<label for="permit_type" class="control-label">Vehicle Permit type</label>
													{{ Form::text('permit_type', null, ['class'=>'form-control','disabled','id' => 'permit_type']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="last_visit" class="control-label">Vehicle Last Visit</label>
													{{ Form::text('last_visit', null, ['class'=>'form-control','disabled','id' => 'last_update_date']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="vehicle_last_job" class="control-label">Vehicle Last Job Card<a style="color: #3366ff;float:right;margin-left: 70px;" class="po_edit" data-id="{{$last_job_card_id}}" data-vehicle_id="{{$wms_transaction->registration_id}}"><i class="fa icon-ecommerce-cart"></i><span>Go to JC</span></a></label>
													{{ Form::text('vehicle_last_job', $wms_transaction->vehicle_last_job, ['class'=>'form-control','disabled']) }}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label class="required" for="service_type">Service Type</label>
													{{ Form::select('service_type', $vehicle_sevice_type, $wms_transaction->service_type, ['class' => 'form-control select_item', 'id' => 'service_type']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="vehicle_insurance" class="control-label">Vehicle Insurance</label>
													{{ Form::text('vehicle_insurance', null, ['class'=>'form-control','disabled','id' => 'vehicle_insurance']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="warranty_km" class="control-label">Warranty KM</label>
													{{ Form::text('warranty_km', null, ['class'=>'form-control','disabled','id' => 'warranty_km']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="warrenty_yrs" class="control-label">Warranty Years</label>
													{{ Form::text('warrenty_yrs', null, ['class'=>'form-control','disabled','id' => 'warrenty_yrs']) }}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="fc_due" class="control-label">FC Due</label>
													{{ Form::text('fc_due', null, ['class'=>'form-control','disabled','id' => 'fc_due']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="permit_due" class="control-label">Permit Due</label>
													{{ Form::text('permit_due', null, ['class'=>'form-control','disabled','id' => 'permit_due']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="engine_number" class="control-label">Vehicle Engine Number</label>
													{{ Form::text('engine_number', null, ['class' => 'form-control', 'id' => 'engine_no','disabled']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="chassis_number" class="control-label">Vehicle Chasis Number</label>
													{{ Form::text('chassis_number', null, ['class' => 'form-control','id' => 'chassis_no','disabled']) }}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="tax_due" class="control-label">Tax Due</label>
													{{ Form::text('tax_due', null, ['class'=>'form-control','disabled','id' => 'tax_due']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="insurance_due" class="control-label">Insurance Due</label>
													{{ Form::text('insurance_due', null, ['class'=>'form-control','disabled','id' => 'insurance_due']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="bank_loan" class="control-label">Bank Loan</label>
													{{ Form::text('bank_loan', null, ['class'=>'form-control','disabled','id' => 'bank_loan']) }}
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group col-md-12">
												<div class="row">
													<label for="month_due_date" class="control-label">Month Due Date</label>
													{{ Form::text('month_due_date', null, ['class'=>'form-control','disabled','id' => 'month_due_date']) }}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="vehicle_note" class="control-label">Vehicle Note</label>
											{{ Form::textarea('vehicle_note', $wms_transaction->vehicle_note, ['class'=>'form-control', 'size' => '30x2']) }}
										</div>
									</div>
								</div>
								</div>
							</div>
						</div>


			    	</div>

		    	@endif		    	

			<div class="tab-pane" id="item_details">
				
				<div class="clearfix"></div>

				<div class="row" style="margin-top: 5px;">

					<div class="col-md-2">

						<div style="float:left;"> 
							<!-- <input type="checkbox" name="show_complaints" class="show_com" id="show_complaintss">
							<label for="show_complaints"><span></span>Show complaints</label></input> -->

							<label for="completed_complaint_list"><span></span>Complaints Completed <span class="completed_value" style="color:#0000FF;cursor: pointer;">{{$total_completed}}/{{$total_complaints}}</span><span class="applied_completed_value" style="color:#0000FF;cursor: pointer;display:none;">{{$total_completed}}/{{$total_complaints}}</span></label>

						</div>

						<div class="form-group col-md-12" style="float:left;">
							<div class="row">
								<div class="col-md-12 show_complaint" style="display:none;">					<label for="complaints" >Complaints</label>
									<textarea name="complaints" class="form-control job_complaint" style="width:40%;" disabled></textarea>
								</div>
							</div>
						</div>

					</div>

					<div class="col-md-10">						

						<table id="new" class= "total_rows" align="right">

							<tr>

								@if( $transaction_type->name != 'job_card')
								<td id="new">
									<div id="design">
									<div id="left" style="color: blue;">
									<a class="discount_popup">Disc%:</a>
									</div>

									<div id="right" style="padding: 1px;">
									@if($discount_option)

										{{ Form::text('new_discount_value',null,['class' => 'form-control new_discount_value','style' =>'width:40px'])}}

									@endif
									</div>
									</div>
								</td>
								@endif

								<td id="new">
									<div id="design">
										<div id="left">
											<h6>Total Rate:</h6>
										</div>
										<div id="right">
											<h6 class="sub_total">0.00</h6>
										</div>
									</div>
								</td>

								@if($transaction_type->name != "job_card")

									<td id="new">
										<div id="design">
											<div id="left">
												<h6>Total Discount:</h6>
											</div>

											<div id="right">
												<h6 class= "box_tax_discount">0.00</h6>
												<input type="hidden" name="sum_discount">
											</div>
										</div>
									</td>

								@endif								

								<td id="new">

									<div id="design">

										<div id="left">
											<h6> Tax:</h6>
										</div>

										<div id="right">
											<h6 class= "box_tax_amount">0.00</h6>

											<input type="hidden" name="tax_amount">

										</div>
									</div>

								</td>

								<td class="advance" id="new">
									<div id="design">
										<div id="left">
											<h6>Advance:</h6>
										</div>
										<div id="right">
											<h6 class="advance_value">0.00</h6>
											{{ Form::hidden('advance_text', null, ['class'=>'form-control decimal']) }}
										</div>
									</div>
								</td>

								<td id="new">
									<div id="design">
										<div id="left">
											<h6 >Total:</h6>
										</div>

										<div id="right">
											<h6 class= "total">0.00</h6>
											<input type="hidden" name="total">
										</div>
									</div>
								</td>

							</tr>

						</table>

						
						
						
											

						<div style="float:right; width: 130px; margin: 10px;display:none;"> 
							<select name="tax_types" class='form-control select_item' disabled>
							<option value="2">Exclude Tax</option>
							<option value="1">Include Tax</option>	
							<option value="0">Out Of Scope</option>
							</select>
						
						</div>

						<div style="float:right;margin:10px;padding-top: 5px; display: none;">
							<div style="float:left;font-weight: bold;color: #4b5056;">
								<label for="group_name_show">Customer Group :</label>
							</div>&nbsp;&nbsp;
							<div style="float:right;">
							{{ Form::text('group_name_show',null,['class'=> 'form-control','id' =>'group_name_show','disabled']) }}
							</div>							
						</div>



					</div>

				</div>

				<div class="clearfix"></div>

				<div class="form-group" style="margin-top: 5px; padding: 10px;" >
						
					<table id="crud_table" style="border-collapse: collapse; margin-bottom: 0px; box-shadow: 0 4px 8px 4px #A9A9A9, 0 6px 20px 0 #A9A9A9;" class="table table-bordered crud_table">
						<thead>

						<tr>
							<th width="2%">#</th>
							<th width="25%">Job & Parts </th>
							<th width="10%">Description</th>
							<th width="8%">Duration</th>
							
								@if($discount_option)
									<th width="12%" style= "@if($transaction_type->name == 'job_card') display:none @endif">Disc.Type</th>
								@endif
								@if($discount_option)
									<th width="6%" style= "@if($transaction_type->name == 'job_card') display:none @endif">Disc%</th>
								@endif
							

							<th width="11%">Price B.Tax</th>
							<th width="6%">Stock</th>
							<th width="6%">Qty</th>
							<th width="10%">Rate</th>
							<th width="10%" style= "">Tax%</th>							
							<th width="10%" style= "@if($transaction_type->name == 'job_card') display:none @endif">TaxAmount</th>						

							<th width="10%">Total</th>

							@if($transaction_type->name == "job_card")
								<th width="10%">AssignedTo</th>
							@endif

							@if($transaction_type->name == "job_card")
								<th width="10%">FromTime</th>
							@endif
							
							<th width="10%" style= "display:none">ToTime</th>							

							@if($transaction_type->name == "job_card")
								<th width="10%">Status</th>
							@endif						

							<th width="3%"></th>

						</tr>

						
						</thead>
						<tbody>

						<tr id="tr_1" data-row="1" class="parent items">

							<td class=""><span class="index_number" style="float: right; padding-left: 3px;">#</span></td>

							<td>
								<div style="width:165px;float: left;" data-toggle="tooltip" data-placement="top" title="Item from the Stock">

								<select name="item_id" class="form-control select_item" id="item_id">
								<option value="">Select Item</option>
								<?php $selected_item = null; ?>
								
								@foreach($items as $item)
									@if($selected_item != $item->category) 
								
									<optgroup label="{{$item->category}}"> @endif	
								
									<?php $selected_item = $item->category; ?>
									<option data-tax="{{$item->include_tax}}" data-purchase_tax="{{$item->include_purchase_tax}}" data-rate = "" value="{{$item->id}}">{{$item->name}}</option>

									</optgroup>
								
								@endforeach
										
								</select> 
								<input type="hidden" name="parent_id">
								<input type="hidden" name="batch_id">
								<input type="hidden" name="append_item">
								</div>

								@if($transactions->approval_status != 1)

								<div style="float:right;" id="jc_item_create">
									<a href="javascript:;" id="" data-toggle="tooltip" data-placement="top" title="Add new item" class="jc_item_create"><i class="fa fa-cube" style="padding: 2px;" aria-hidden="true"></i></a>
								</div>

								<div style="float:right; display: none;" id="item_batch" class="item_batch" data-toggle="tooltip" data-placement="top" title="Select Item Batch">

								<a href="javascript:;"><i class="fa fa-cart-plus" style="padding: 2px;" aria-hidden="true"></i></a>
								

								</div>

								@endif

							</td>

							<!-- @if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
							<td>
								{{ Form::text('base_price', null, ['class'=>'form-control decimal','disabled']) }}
							</td>
							@endif -->

							<td data-toggle="tooltip" data-placement="top" title="Desctiption- Appears in Print">
								{{ Form::textarea('description', null, ['class'=>'form-control', 'style'=>' height: 26px;' , 'placeholder' => 'Description']) }}
							</td>									
							

							<td data-toggle="tooltip" data-placement="top" title="Hr-Duration of this work" >
							{{ Form::text('duration', null, ['class'=>'form-control']) }} 
							<div class='duration'></div>

							</td>

							@if($discount_option)
								<td style= "@if($transaction_type->name == 'job_card') display:none @endif" data-toggle="tooltip" data-placement="top" title="Disount Type">
									<select name='discount_id' class='form-control select_item taxes' id = 'discount_id'>
									<option value="">Select Discount</option>
									@foreach($discounts as $discount) 
									<option value="{{$discount->id}}" data-value="{{$discount->value}}">{{$discount->display_name}}</option>
									@endforeach
									</select>
								</td>
						 	@endif
						 	

							@if($discount_option)
								<td style= "@if($transaction_type->name == 'job_card') display:none @endif" data-toggle="tooltip" data-placement="top" title="Disount %">
									{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}
								 </td>
						 	@endif

						 	<td data-toggle="tooltip" data-placement="top" title="Unit Price - before the tax">
								{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
								
							</td>
								
							<td data-toggle="tooltip" data-placement="top" title="Currently Available Stock">
							{{ Form::text('in_stock', null, ['class'=>'form-control numbers', 'disabled', 'id' => 'in_stock']) }}
							</td>

							<td data-toggle="tooltip" data-placement="top" title="Selling Quantity">
								{{ Form::text('quantity', null, ['class'=>'form-control decimal']) }}
								
							</td>							

							<td data-toggle="tooltip" data-placement="top" title="Price B.Tax * Qty (without tax)">
								{{ Form::text('amount', null, ['class'=>'form-control numbers']) }}
							</td>
							
							<td style= "" data-toggle="tooltip" data-placement="top" title="Item's GST tax">
								<select name='tax_id' class='form-control select_item taxes' id = 'tax_id'>
									<option value="">Select Tax</option>
									@foreach($taxes as $tax) 
									<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
										@endforeach
								</select>
						
							</td>

							<td style= "@if($transaction_type->name == 'job_card') display:none @endif" data-toggle="tooltip" data-placement="top" title="Tax Amount">
								{{ Form::text('tax_amount', null, ['class'=>'form-control decimal']) }}
							</td>

							<td style= "" data-toggle="tooltip" data-placement="top" title="Price * Qty with Tax">
								{{ Form::text('tax_total', null, ['class'=>'form-control decimal']) }}
							</td>					


							@if($transaction_type->name == "job_card")
								<td data-toggle="tooltip" data-placement="top" title="Who works for this item">
									{{ Form::select('assigned_employee_id', $employees, $selected_employee, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}
								</td>
							@endif

							@if($transaction_type->name == "job_card")
								<td data-toggle="tooltip" data-placement="top" title="When the work starts">
									{{ Form::text('start_time', $current_date, ['class'=>'form-control datetimepicker2','id' => 'start_time']) }}								
								</td>
							@endif
							
								<td style= "display:none; " data-toggle="tooltip" data-placement="top" title="When the work End">
									{{ Form::text('end_time', $add_date, ['class'=>'form-control datetimepicker2','id' => 'end_time']) }}
								</td>
							

							@if($transaction_type->name == "job_card")
								<td data-toggle="tooltip" data-placement="top" title="Status of this item/work">
									{!!	Form::select('job_item_status',$job_item_status, $item_status, ['class' => 'form-control select_item']); !!}
								</td>
							@endif						


							<td data-toggle="tooltip" data-placement="top" title="Click to add item into the list" >

							<a class="grid_label action-btn edit-icon add_row_append"><i class="fa fa-plus"></i></a>

							</td>
						</tr>

						</tbody>
					</table>							

				</div>

				<div class="form-group" style=" margin-top: 10px; overflow-y: scroll; height:330px;" >
					
					<table id="append_table" style="border-collapse: collapse;" class="table table-bordered append_table">

						<thead>

						<tr>

							<th width="2%">#</th>
							<th width="17%">Job & Parts </th>			
							<th width="5%">Description</th>
							<th width="2%" style="">Duration</th>
							
								@if($discount_option)
									<th width="7%" style= "@if($transaction_type->name == 'job_card') display:none @endif">Disc.Type</th>
								@endif

								@if($discount_option)
									<th width="2%" style= "@if($transaction_type->name == 'job_card') display:none @endif">Disc%</th>
								@endif
							

							<th width="9%">PriceB.Tax</th>
							<th width="6%">Stock</th>
							<th width="6%">Qty</th>
							<th width="10%">Rate</th>
							<th width="7%" style= "">Tax%</th>							
							<th width="8%" style= "@if($transaction_type->name == 'job_card') display:none @endif">TaxAmount</th>						

							<th width="12%">Total</th>

							@if($transaction_type->name == "job_card")
								<th width="10%">AssignedTo</th>
							@endif

							@if($transaction_type->name == "job_card")
								<th width="10%">FromTime</th>
							@endif
							
							<th width="10%" style="display: none;">ToTime</th>							

							@if($transaction_type->name == "job_card")
								<th width="10%">Status</th>
							@endif						

							<th width="3%"></th>

						</tr>
						

						</thead>

						<tbody>
							

						</tbody>

					</table>

				</div>

			</div>	


			@if($transaction_type->name === "job_card")
				<div class="tab-pane" id="attachments">
					<div class="clearfix"></div>
				<div class="form-group">

					<br /><br/>

					<div class="row">
						 
						<input type="hidden" name="transaction_id" value="{{$transactions->id}}"/>

						<!--Start Before Image -->	
								 
							<div class="col-lg-12 col-md-12 col-sm-12">
									<H5>Before</H5>
									 <div class="dropzone " id="before_image" >

									 @foreach($wms_attachments_before as $wms_attachment)
										
											<div class="img-wrap">
												<span class="close">&times;</span>
												<a target="_blank" href="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/temp/'.$wms_attachment->origional_file}}">
										
												<img alt="Select Image" data-id="{{$wms_attachment->id}}"  src="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/temp/'.$wms_attachment->origional_file}}" width="120" height="120" />
											</a>
											</div>
										
										
										<!-- <h5>{{$wms_attachment->transaction_id}}</h5>  -->
													 
									@endforeach
										<div class="fallback"></div>
															 
																	 
										</div>
													 
										<br>

										<div class="myProgress">
											<div id="BeforePBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="display:none;">Processing uploded file...</div>
											</div>
							</div>

							<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="col-md-12 pull-right" style="padding:5px 20px">
										<button type="button" class="btn btn-success Insert_files pull-right" id="SaveBeforeImg" style="float: right;">Upload Files
												 </button> 
									</div>
					 
							</div>
					 
					 	<!--End Before Image -->

						<!--Start Progress Image -->
								
									 
							<div class="col-lg-12 col-md-12 col-sm-12">
								<H5>Progress</H5>
									<div class="dropzone" id="progress_image" >
														 
									@foreach($wms_attachments_progress as $wms_attachment)
									<div class="img-wrap">
										<span class="close">&times;</span>
										<a target="_blank" href="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/temp/'.$wms_attachment->origional_file}}">
										 <img alt="Select Image"  data-id="{{$wms_attachment->id}}"  src="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/temp/'.$wms_attachment->origional_file}}" width="120" height="120" />
										 </a>
											 <!-- <h5>{{$wms_attachment->transaction_id}}</h5>  -->
									</div>									 
											 @endforeach
										 <div class="fallback"></div>
																 
																		 
											</div><br>

									<div class="myProgress">
										<div id="ProgressPBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="display:none;">Processing uploded file...</div>
									</div>
							</div>

							<div class="col-lg-12 col-md-12 col-sm-12">
								 <div class="col-md-12 pull-right" style="padding:5px 20px">
									 <button type="button" class="btn btn-success Insert_files pull-right" id="SaveProgressImg" style="float: right;">Upload Files
									</button> 
								</div>
						 
							</div>
						 
						 <!--End Progress Image -->

						 <!--Start After Image -->
								
									 
						<div class="col-lg-12 col-md-12 col-sm-12">
							<H5>After</H5>
							<div class="dropzone" id="after_image" >
														 
								@foreach($wms_attachments_after as $wms_attachment)
								<div class="img-wrap">
									<span class="close">&times;</span>
								 <a target="_blank" href="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/temp/'.$wms_attachment->origional_file}}">
								 <img alt="Select Image"  data-id="{{$wms_attachment->id}}"  src="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/temp/'.$wms_attachment->origional_file}}" width="120" height="120" />
								 </a>
								 <!-- <h5>{{$wms_attachment->transaction_id}}</h5>  -->
								</div>									 
								 @endforeach
								 <div class="fallback"></div>
																 
																		 
							</div><br>

							<div class="myProgress">
							<div id="AfterPBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="display:none;">Processing uploded file...</div>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="col-md-12 pull-right" style="padding:5px 20px">
								<button type="button" class="btn btn-success Insert_files pull-right" id="SaveAfterImg" style="float: right;">Upload Files
								</button> 
							</div>
						 
						</div>
						 
						 <!--End After Image -->
							 
					</div>
				 
						<!-- <h2><center>Under Development </center></h2> -->


						<!-- <div class="form-group"><br/>


							 <div class="row">
								 <div class="col-md-6">
									 <center><label class="control-label"><b>Before Job</b></label></center>
								 </div>
								 <div class="col-md-6">
									 <center><label class="control-label"><b>After Job</b></label></center>
								 </div>
							 </div> <br />


							 <div class="row">
								 <div class="col-md-6 image_container">
									 @foreach($wms_attachments_before as $wms_attachment)
									 <div class="col-md-12 pull-left">
										 <img alt="Select Image" src="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/jobcard_'.$wms_attachment->transaction_id.'/thumbnails/'.$wms_attachment->thumbnail_file}}" width="200" height="200" />
										 <h5>{{$wms_attachment->image_name}}</h5>

									 </div>
									 @endforeach
										 <div class="dropzone" id="uploadFile"  ></div>

										 {{ Form::hidden('category_id',1, ['class' => 'image_category','id' =>'category_id']) }}
												 </div>
								 <div class="col-md-6 image_container">
									 @foreach($wms_attachments_after as $wms_attachment)
									 <div class="col-md-12 pull-left">
										 <img alt="Select Image" src="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/jobcard_'.$wms_attachment->transaction_id.'/thumbnails/'.$wms_attachment->thumbnail_file}}" width="200" height="200" />
										 <h5>{{$wms_attachment->image_name}}</h5>
									 </div>
									 @endforeach						
										 <div class="dropzone" id="uploadFile2"  ></div>
										 
										 {{ Form::hidden('category_id',2, ['class' => 'image_category','id' =>'category_id']) }}
										 
								 </div>
								 <button class="attachments" type="button" id="Insert_files"> Upload</button>
							 </div>
		 
								
							 <div class="row">
								 <div class="col-md-6">
							 
								 </div>
								 <div class="col-md-3 pull-right">				
										
										 <button type="button" class="btn btn-success Insert_files ">Upload Files </button> 
								 </div> 
							 </div>
						 </div> -->


				</div> 

					<!-- <h2><center>Under Development </center></h2> -->


				 	<!-- <div class="form-group"><br/>


					 <div class="row">
						 <div class="col-md-6">
							 <center><label class="control-label"><b>Before Job</b></label></center>
						 </div>
						 <div class="col-md-6">
							 <center><label class="control-label"><b>After Job</b></label></center>
						 </div>
					 </div> <br />


					 <div class="row">
						 <div class="col-md-6 image_container">
							 @foreach($wms_attachments_before as $wms_attachment)
							 <div class="col-md-12 pull-left">
								 <img alt="Select Image" src="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/jobcard_'.$wms_attachment->transaction_id.'/thumbnails/'.$wms_attachment->thumbnail_file}}" width="200" height="200" />
								 <h5>{{$wms_attachment->image_name}}</h5>

							 </div>
							 @endforeach
								 <div class="dropzone" id="uploadFile"  ></div>

								 {{ Form::hidden('category_id',1, ['class' => 'image_category','id' =>'category_id']) }}
										 </div>
						 <div class="col-md-6 image_container">
							 @foreach($wms_attachments_after as $wms_attachment)
							 <div class="col-md-12 pull-left">
								 <img alt="Select Image" src="{{asset('public/wms_attachments/org_'.$wms_attachment->organization_id).'/jobcard_'.$wms_attachment->transaction_id.'/thumbnails/'.$wms_attachment->thumbnail_file}}" width="200" height="200" />
								 <h5>{{$wms_attachment->image_name}}</h5>
							 </div>
							 @endforeach						
								 <div class="dropzone" id="uploadFile2"  ></div>
								 
								 {{ Form::hidden('category_id',2, ['class' => 'image_category','id' =>'category_id']) }}
								 
						 </div>
						 <button class="attachments" type="button" id="Insert_files"> Upload</button>
					 </div>
 
						
					 <div class="row">
						 <div class="col-md-6">
					 
						 </div>
						 <div class="col-md-3 pull-right">				
								
								 <button type="button" class="btn btn-success Insert_files ">Upload Files </button> 
						 </div> 
					 </div>
				 	</div> -->


				</div>

			@endif

		    @if($transaction_type->name == 'job_card')
			  	<div class="tab-pane" id="readings">
		     		<div class="clearfix"></div>
					<div class="form-group"><br/>
						<table style="border-collapse: collapse;" class="table table-bordered">
							<thead>
							<tr>
								<th width="4%">#</th>
								<!-- <th width="15%">Type of Reading</th> -->
								<th width="15%">Reading Factors</th>
								<th width="12%">Reading Values</th>
								<!-- <th width="8%">Calculation</th> -->
								<th width="10%">Notes</th>
							</tr>
							<tr>
							</thead>
							<tbody>
							<?php $i = 1; ?>
							@foreach($wms_transaction_readings as $reading)
								<tr>
									<td>
										<span style="float: right; padding-left: 5px;">{{$i}}</span>
										{{ Form::hidden('wms_reading_id', $reading->id) }}
									</td>							
									<!-- <td>{{ $reading->division_name}}
									</td> -->
									<td>
										{{ $reading->reading_factor_name}}
										{{ Form::hidden('wms_reading_factor_id', $reading->reading_factor_id) }}
									</td>

									<td>
										{{ Form::text('reading_values', $reading->reading_values, ['class'=>'form-control']) }}
									</td>
									<!-- <td>{{ Form::text('reading_calculation', null, ['class'=>'form-control numbers']) }}</td> -->
									<td>
										{{ Form::text('reading_notes', $reading->reading_notes, ['class'=>'form-control']) }}
									</td>
								</tr>
								<?php $i++; ?>
							@endforeach					
							</tbody>
						</table>
					</div>
		    	</div>
    		@endif

			@if($transaction_type->name == 'job_card')			    
			    <div class="tab-pane" id="checklist">
		     		<div class="clearfix"></div>
					<div class="form-group"><br/>
						<table style="border-collapse: collapse;" class="table table-bordered">
							<thead>
							<tr>
								<th width="4%">#</th>
								<!-- <th width="15%">Type of Reading</th> -->
								<th width="15%">CheckList</th>
								<th width="12%">CheckList Status</th>
								<!-- <th width="8%">Calculation</th> -->
								<th width="10%">Notes</th>
							</tr>
							<tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
							@foreach($wms_checklist as $checklist)
								<tr>
									<td>
										<span style="float: right; padding-left: 5px;">{{$i}}</span>
										{{ Form::hidden('wms_checklist_id', $checklist->id) }}
									</td>					
								

									<td>{{ $checklist->name}}
										{{ Form::hidden('checklist_id', $checklist->checklist_id) }}
									</td>
									<?php $wfm_checklist="wms_checklist_status".$i; ?>
									<?php 
									$wfm_checklist_status=false; 		
									$wfm_checklist_value=0;
									if($checklist->checklist_status!=null)
									{
									$wfm_checklist_status=true; 
									$wfm_checklist_value=1;
										
									}?>
									<td>  {{ Form::checkbox('wms_checklist_status', $wfm_checklist_value, $wfm_checklist_status, array('id' =>$wfm_checklist )) }}	<label for="wms_checklist_status<?php echo $i?>"><span></span></label></td>
									<!-- <td>{{ Form::text('reading_calculation', null, ['class'=>'form-control numbers']) }}</td> -->
									<td>{{ Form::text('wms_checklist_notes', $checklist->checklist_notes, ['class'=>'form-control']) }}</td>
								</tr>
								<?php $i++; ?>
							@endforeach					
							</tbody>
						</table>
					</div>
		    	</div>
 			@endif

	   	@endif
	   		
	  	</div>

	</div>

		<div class="save_btn_container">
			<!-- <div class="dropdown" style="float:right;">
				<button class="btn btn-success dropdown-toggle" type="button" id="dropdownSaveButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				
				Save/Close
					
				</button>
			
				<div   class="dropdown-menu save_close" aria-labelledby="dropdownSaveButton">
					
				  	<a href="#" style="@if($transactions->approval_status == "1") display:none; @endif" class="dropdown-item hover tab_save_close_btn approval_status"  data-name="save_close" >Save and Close</a>
				    
				   <a href="#" style="@if($transactions->approval_status == "1") display:none; @endif" class="dropdown-item hover tab_save_btn approval_status"  data-name="save">Save</a>
					
					<a href="#" class="dropdown-item clear cancel_transaction" data-name="close">Close</a>
					
				</div>
			</div> -->
			<button type="reset" class="btn btn-default clear cancel_transaction">Close</button> 
			
			@if($transaction_type->name == "purchase_order")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" class="btn btn-success tab_send_btn approval_status">Send PO </button>
			@elseif($transaction_type->name == "sale_order")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" class="btn btn-success tab_send_btn approval_status">Send SO </button>
			@elseif($transaction_type->name == "sales_cash" || $transaction_type->name == "sales" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" class="btn btn-success tab_send_btn approval_status">Send Invoice </button>
			@elseif($transaction_type->name == "delivery_note")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" class="btn btn-success tab_send_btn approval_status">Send Delivery Note </button>
			@elseif($transaction_type->name == "estimation")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" class="btn btn-success tab_send_btn approval_status">Send Est.</button>
			@endif

			@if($transaction_type->name == "purchase_order" || $transaction_type->name == "purchases" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales_cash" || $transaction_type->name == "sales" || $transaction_type->name == "delivery_note" || $transaction_type->name == "estimation" || $transaction_type->name == "goods_receipt_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")

				@if($transactions->approval_status == "1")
					<button  type="submit"  class="btn btn-success tab_approve_btn ">Approved </button>
				@else
					<button  type="submit"  class="btn btn-success tab_approve_btn ">Approve </button>
				@endif
			@endif


			@if($transaction_type->name == "purchase_order")
			<button type="submit" class="btn btn-success tab_print_btn approval_status">Print PO </button>
			@elseif($transaction_type->name == "purchases")
			<button type="submit" class="btn btn-success tab_print_btn">Print Purchase </button>
			@elseif($transaction_type->name == "goods_receipt_note")
			<button type="submit" class="btn btn-success tab_print_btn">Print GRN </button>
			@elseif($transaction_type->name == "debit_note" || $transaction_type->name == "credit_note")
			<button type="submit" class="btn btn-success tab_print_btn">Print Return </button>
			@elseif($transaction_type->name == "sale_order")
			<button type="submit" class="btn btn-success tab_print_btn">Print SO </button>
			@elseif($transaction_type->name == "sales_cash" || $transaction_type->name == "sales")
			<button type="submit" class="btn btn-success tab_print_btn">Print Invoice </button>
			@elseif($transaction_type->name == "delivery_note")
			<button type="submit" class="btn btn-success tab_print_btn">Print Delivery Note </button>
			@elseif($transaction_type->name == "estimation")
			<button type="submit" class="btn btn-success tab_print_btn">Print Est.</button>
			 @elseif($transaction_type->name == "job_card")

  			 <!-- <button style="@if($wms_transaction->jobcard_status_id == "8") display:none; @endif" type="submit" class="btn btn-success tab_print_btn"> Print </button>  -->

  			@elseif($transaction_type->name == "job_request")

  			<!-- <button style="@if($wms_transaction->jobcard_status_id == "8") display:none; @endif" type="submit" class="btn btn-success tab_print_btn"> Print </button> -->
  			<div>
  			<button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="@if($wms_transaction->jobcard_status_id == "8") display:none; @endif">
			
			Print
				
			</button>
			  <div class="dropdown-menu"  aria-labelledby="dropdownMenuButton">
			  	@foreach($estimation_print_templates as $estimation_print_template) 
			  	<a href="#" class="dropdown-item hover estimation_print" id="estimation_print" data-id="{{$transactions->id}}" data-name="estimation_print" data-formate="{{$estimation_print_template->data}}">{{$estimation_print_template->display_name}}</a>
			  	@endforeach  
			  </div>
			</div>
  			
            @elseif($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
            <div >
			<button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			
			Print
				
			</button>
			  <div class="dropdown-menu"  aria-labelledby="dropdownMenuButton">
			  	@foreach($print_templates as $print_template) 
			  	<a href="#" class="dropdown-item hover invoice_print" id="invoice_print" data-id="{{$transactions->id}}" data-name="invoice_print" data-formate="{{$print_template->data}}">{{$print_template->display_name}}</a>
			  	@endforeach  
			  </div>
			</div>
            @endif

            <button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit" id="sms_btn">Send SMS</button>

			<!-- @if($transaction_type->name == "purchase_order")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit" id="sms_btn">SMS PO </button>

			@elseif($transaction_type->name == "purchases")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit" id="sms_btn">SMS Purchase </button>

			@elseif($transaction_type->name == "goods_receipt_note")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit" id="sms_btn">SMS GRN </button>

			@elseif($transaction_type->name == "debit_note")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit">SMS Return </button>

			@elseif($transaction_type->name == "sale_order")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit">SMS SO </button>

			@elseif($transaction_type->name == "sales_cash" || $transaction_type->name == "sales" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
			
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit">SMS Invoice </button>
			@elseif($transaction_type->name == "delivery_note")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit">SMS Delivery Note </button>
			@elseif($transaction_type->name == "estimation")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" class="btn btn-success tab_sms_btn approval_status sms_limit">SMS EST. </button>
			@elseif($transaction_type->name == "job_request")
			<button data-id="{{$transactions->id}}" type="submit" class="btn btn-success estimation_msg sms_limit">Send sms</button>

			@endif -->	
			
			@if($transaction_type->name == "purchase_order")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" data-id="{{$transactions->id}}" data-name="purchases" class="btn btn-success make_transaction approval_status">Copy to Purchase</button>
			@elseif($transaction_type->name == "purchases")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" data-id="{{$transactions->id}}" data-name="goods_receipt_note" data-ref="po_to_grn" class="btn btn-success job_make_transaction approval_status po_to_grn">Copy to GRN </button>

			@elseif($transaction_type->name == "sale_order")

			<div class="dropdown" style="float:right;">
				<button class="btn btn-success dropdown-toggle approval_status" style="@if($transactions->approval_status != "1" ) display:none; @endif"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				
				Copy to
					
				</button>
				  <div class="dropdown-menu"  aria-labelledby="dropdownMenuButton">

				  	<a href="#" class=" make_transaction approval_status dropdown-item hover"  id="sales_cash" data-id="{{$transactions->id}}" data-name="sales_cash">Cash Invoice</a>
				    
				    <a href="#" class="make_transaction approval_status dropdown-item "   id="sales" data-id="{{$transactions->id}}" data-name="sales">Credit Invoice</a>		   
				  </div>	
			</div>

			@elseif($transaction_type->name == "estimation")

			<div class="dropdown" style="float:right;">
				<button class="btn btn-success dropdown-toggle approval_status" style="@if($transactions->approval_status != "1" ) display:none; @endif"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				
				Copy to
					
				</button>
				  <div class="dropdown-menu"  aria-labelledby="dropdownMenuButton">

				  	<a href="#" class=" make_transaction approval_status dropdown-item "  id="sale_order" data-id="{{$transactions->id}}" data-name="sale_order">Sale Order</a>

				  	<a href="#" class=" make_transaction approval_status dropdown-item "  id="sales_cash" data-id="{{$transactions->id}}" data-name="sales_cash">Cash Invoice</a>
				    
				    <a href="#" class="make_transaction approval_status dropdown-item "   id="sales" data-id="{{$transactions->id}}" data-name="sales">Credit Invoice</a>		   
				  </div>	
			</div>
			
			@elseif($transaction_type->name == "sales_cash" || $transaction_type->name == "sales")
			<button style="@if($transactions->approval_status != "1") display:none; @endif" type="submit" data-id="{{$transactions->id}}" data-name="delivery_note" class="btn btn-success make_transaction approval_status">Copy to Delivery Note </button>


			@elseif($transaction_type->name == "job_card")
			<div class="dropdown" style="float:right;">
				<button class="btn btn-success dropdown-toggle" style="@if($wms_transaction->jobcard_status_id == "8" ) display:none; @endif"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				
				Create New
					
				</button>
				  <div class="dropdown-menu copy_invoice"  aria-labelledby="dropdownMenuButton">
				  	<a href="#" class="job_make_transaction approval_status dropdown-item jobcard-estimation" data-ref ="jobcard-estimation" id="invoice_credit" data-id="{{$transactions->id}}" data-name="job_request">Job Estimate</a>	

				  	<a href="#" class="job_make_transaction approval_status dropdown-item hover jobcard-invoice_cash" data-ref ="jobcard-invoice_cash" id="invoice_cash" data-id="{{$transactions->id}}" data-name="job_invoice_cash">Cash Invoice</a>
				    
				    <a href="#" class="job_make_transaction approval_status dropdown-item jobcard-invoice_credit"  data-ref ="jobcard-invoice_credit" id="invoice_credit" data-id="{{$transactions->id}}" data-name="job_invoice">Credit Invoice</a>		   
				  </div>	
			</div>

			<!-- <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			
			Copy to Job Invoice
				
			</button>
			  <div class="dropdown-menu copy_invoice"  aria-labelledby="dropdownMenuButton">
			  	<a href="#" class="make_transaction approval_status dropdown-item hover" id="invoice_cash" data-id="{{$transactions->id}}" data-name="job_invoice_cash" >Cash</a>
			    
			    <a href="#" class="make_transaction approval_status dropdown-item " id="invoice_credit" data-id="{{$transactions->id}}" data-name="job_invoice">Credit</a>
			
			   
			  </div>	
			
			
			
			<button style="" type="submit" data-id="{{$transactions->id}}" data-name="job_request" class="btn btn-success make_transaction approval_status">Copy to Job Estimate </button> -->

			@elseif($transaction_type->name == "job_request")
			<button style="" type="submit" data-id="{{$transactions->id}}" data-ref="jobcard-invoice_credit" data-name="job_invoice" class="btn btn-success job_make_transaction approval_status jobcard-invoice_credit">Copy to Job Invoice </button>
			
			@endif
			

			<!-- @if($transaction_type->name == "goods_receipt_note")
			@if($transactions->approval_status == "1")
			@if($transactions->item_update_status == "1")
			
			<a style="color:white; float: right; margin-right: 5px;" class="btn btn-success">Inventory Updated</a>
			@else
			<button type="submit" data-id="{{$transactions->id}}" data-name="goods_receipt_note" class="btn btn-success tab_update_goods_btn update_goods">Update Inventory</button>
			
			@endif
			@endif
			@endif -->


			<!-- @if($transaction_type->name == "goods_receipt_note")
			<button type="submit" data-id="{{$transactions->id}}" data-name="goods_receipt_note" class="btn btn-success">Goods Descrepancy</button>
			@endif -->
		

			<button style="@if($transactions->approval_status == "1") display:none; @endif" type="button" class="btn btn-success tab_delete_btn approval_status">Delete </button>

			@if($transaction_type->name == "job_card")
				<button style="@if($wms_transaction->jobcard_status_id == "8" ) display:none; @endif" type="submit" class="btn btn-success tab_save_close_btn approval_status">Save and Close </button>

				<button style="@if($wms_transaction->jobcard_status_id == "8" ) display:none; @endif" type="submit" class="btn btn-success tab_save_btn approval_status" id="save_attachment">Save </button>
			@endif
			
			@if($transaction_type->name != "job_card")
			
			<button style="@if($transactions->approval_status == "1" ) display:none; @endif" type="submit" class="btn btn-success tab_save_close_btn approval_status">Save and Close </button>

			<button style="@if($transactions->approval_status == "1" ) display:none; @endif" type="submit" class="btn btn-success tab_save_btn approval_status" id="save_attachment">Save </button>
			@endif


			<div style="margin:-25px auto 0px; width: 150px;">
			<div class="col-md-12"> @if($transaction_type->name != "sales_cash")
		      {{ Form::checkbox('make_recurring', '1', null, array('id' => 'make_recurring')) }}
		      <label for="make_recurring" style="display: none;"><span></span></label>
		      <a class="make_recurring">Make Recurring</a> @endif </div>
		  	</div>
	  	</div>

	{!! Form::close() !!} 

</div>


{{--

@stop

@section('dom_links')
@parent

--}}



<script type="text/javascript">

var current_select_item = null;
	
	$(document).ready(function() {

		@if(!empty($transactions) && $transaction_type != null )
		order('{{$transactions->id}}', '{{$transaction_type->name}}');
		@endif

		basic_functions();

		$('[data-toggle="tooltip"]').tooltip();

		$('#show_vehicle_detail').on('click',function(){
				$('.show_vehicle_details').show();
				$('#show_more_detail').hide();
				$('#show_less_detail').show();


		});
		$('#hide_vehicle_detail').on('click',function(){
				$('.show_vehicle_details').hide();
				$('#show_more_detail').show();
				$('#show_less_detail').hide();
		});
		
		
		$('.job_card_status_change').on('click',function(){
	//alert();
	
		var id = $(this).attr('data-id');
		//console.log($(this).closest('div').find('.chevron'));

		$('input[name=jobcard_status_id]').val(id);

	/*	if($('.chevron_active').length > 0)
		{
			$('.chevron_active').addClass('chevron');
			$('.chevron_active').removeClass('chevron_active');

		}
		$(this).removeClass('chevron');*/
		$(this).nextAll().addClass('chevron');
		
		$(this).nextAll().removeClass('chevron_active');
		$(this).prevAll().addClass('chevron_active');

		$(this).addClass('chevron_active');
			var jobcard_status_id=$('input[name=jobcard_status_id]').val();
			
			
			$.ajax({
				url: '{{ route('save_job_card_status') }}',
				type: 'post',
				data: 
				{
					_token: '{{ csrf_token() }}',
					jobcard_status_id : jobcard_status_id,
					id :'{{$id}}',
				},
				success:function(data)
				{
					//alert();
				},
				error:function()
				{

				}
			});

	});

		//To save job_card_status when changing status in edit
	$('select[name=jobcard_status_ids]').on('change',function(){
		var jobcard_status_id=$(this).val();
		
		$.ajax({
			url: '{{ route('save_job_card_status') }}',
			type: 'post',
			data: 
			{
				_token: '{{ csrf_token() }}',
				jobcard_status_id : jobcard_status_id,
				id :'{{$id}}',
			},
			success:function(data)
			{
				//alert();
			},
			error:function()
			{

			}

		});

	});

	//end	

	/*$('a#invoice_cash').on('click',function(e){
		e.preventDefault();
		
		//alert();
		var job_status_id = $('select[name=jobcard_status_id]').val();
		

		if(job_status_id != 6 || job_status_id !=7)
		{
			 return false;
			alert_message("Can not copy to Invoice","success");
			//$('.copy_invoice').show();
			
		}

	});*/

	/*$('a#invoice_credit').on('click',function(e){
		e.preventDefault();
		
		//alert();
		var job_status_id = $('select[name=jobcard_status_id]').val();
		
		if(job_status_id != 6 || job_status_id !=7)
		{
			alert_message("Can not copy to Invoice","success");
			//$('.copy_invoice').show();
		}

	});*/

 
	


	$('.add_vehicle').on('click', function(e) {
			e.preventDefault();
			$.get("{{ route('vehicle_registered.create') }}", function(data) {
				$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
			$('.loader_wall_onspot').hide();
		});
		
$('.discount_popup').on('click', function(e) {
			e.preventDefault();
			//var id = "{{ $id }}";
			var sub_total = $('.sub_total').text();
			var box_tax_amount = $('.box_tax_amount').text();

			$.get("{{ url('trade_wms/discount_popup.create') }}/"+sub_total+"/"+ box_tax_amount, function(data) {
				$('.discount_crud_modal .modal-container').html("");
				$('.discount_crud_modal .modal-container').html(data);
			});
			//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.discount_crud_modal').modal('show');
			$('.loader_wall_onspot').hide();
		});

	$('.purchase_year').datepicker({
        autoclose: true,
        viewMode: "years", 
    	minViewMode: "years",
        format: 'yyyy'
    });

    $('.jobcard_complaint, .completed_value').on('click',function(e){
		e.preventDefault();	
		var id = '{{$transactions->id}}';
        $.get("{{ url('inventory/jobcard_complaint_edit_view') }}/"+id, function(data) {
              	$('.group_item_modal .modal-container').html(data);
              	
        });
        $('.group_item_modal').find('.modal-dialog').addClass('modal-lg');
        $('.group_item_modal').modal('show');
	});

	$('.applied_complaint, .applied_completed_value').on('click',function(){
		$('.group_item_modal').modal('show');
	});


	$('textarea.complaint').keyup(function()
	{	

		var content = $('textarea.complaint').val();
		
		$('textarea.job_complaint').val(content);
		
	});
	
	$('#update_customer_info').on('click',function(){
		
		if($(this).prop('checked') == true)
		{
			$('input[name=update_customer_info]').val(1);

		}
		else
		{
			$('input[name=update_customer_info]').val(0);
		}
	});

	$('.show_com').on('change', function() {
		
		if($(this).is(":checked")) {
			//alert();
			$('.show_complaint').show();
			
		} 
		else 
		{
			$('.show_complaint').hide(); 
		}
	});


	$("table").rowSorter({
		handler: "td.sorter",
		onDrop: function() { 
			var i = 1;
			$('.crud_table').find('tbody tr').each(function() {
				$(this).find('.index_number').text(i++);
			}) 
		}
	});

	$('.make_recurring').on('click', function() {
		$('input[name=make_recurring]').prop('checked', true);
		$('input[name=make_recurring]').trigger('change');
		$('.voucher_name').text("Recurring {{$transaction_type->display_name}}");
		$('.recurring').show();
		$('.voucher_code').hide();
	});

	$('.cancel_transaction').on('click', function(e) {
		e.preventDefault();
		$('input[name=make_recurring]').prop('checked', false);
		$('input[name=make_recurring]').trigger('change');
		if($('.recurring').is(':hidden')) {
			$('.close_full_modal').trigger('click');
		} else {
			$('.voucher_name').text("{{$transaction_type->display_name}}# {{$voucher_no}}");
			$('.recurring').hide();
			$('.voucher_code').show();
		}
		
	});

	$("input[name=sale_type]").on('change', function(){
		$('.loader_wall_onspot').show();
		if($(this).val() == "cash") {
			$.get("{{ route('transaction.create', ['sales_cash']) }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.loader_wall_onspot').hide();
					});
		} else if($(this).val() == "credit") {
			$.get("{{ route('transaction.create', ['sales']) }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.loader_wall_onspot').hide();
					});
		}

	});

	$("select[name=interval]").on('change', function(){
		$('select[name=period]').val(''); 
		$('select[name=week_day_id]').val('{{$weekday}}');
		$('select[name=day]').val(1);
		$('select[name=period], select[name=week_day_id], select[name=day]').trigger('change');
		$('.every').show();
		$('.month').hide();
		$('.week').hide();
		$('.day').hide();

		if($(this).val() == 0)
		{
			$('.every .every_time').text(" every ");
			$('.every .period').text(" day(s) ");
		}		
		else if($(this).val() == 1)
		{
			$('.week').show();
			$('.every .every_time').text(" for every ");
			$('.every .period').text(" week(s) ");
		}
		else if($(this).val() == 2)
		{
			$('.month').show();
			$('.day').show();
			$('.every .every_time').text( " of every ");
			$('.every .period').text(" month(s) ");
		}
	});

	$('select[name=period]').on('change', function() {
		if($(this).val() != '')
		{			
			$('.week').show();
			$('.day').hide();			
		}
		else{
			$('.week').hide();
			$('.day').show();
		}
	});	

	$('select[name=end]').on('change', function() {		
		$('.end_date').hide();
		$('.occurrence').hide();

		if($(this).val() == '1') {			
			$('.end_date').show();
			$('.occurrence').hide();			
		}
		else if($(this).val() == '2') {		
			$('.end_date').hide();
			$('.occurrence').show();
		}
	});	

	

	$('body').on('click', '.discount_picker_container', function(e) {
		var discount_picker = $('ul.discount_picker');

		if (discount_picker.is(":visible")) {
			$('ul.discount_picker').hide();
		} else {
			$('ul.discount_picker').show();
		}

	});

	$('ul.discount_picker').find('.percent').on('click', function() {
		$(this).closest('td').find('input[name=discount_is_percent]').prop('checked', true);
		$(this).closest('td').find('input[name=discount_is_percent]').trigger('change');
		$(this).closest('.discount_picker_container').find('.discount_type').text('%');
	});

	$('ul.discount_picker').find('.rupee').on('click', function() {
		$(this).closest('td').find('input[name=discount_is_percent]').prop('checked', false);
		$(this).closest('td').find('input[name=discount_is_percent]').trigger('change');
		$(this).closest('.discount_picker_container').find('.discount_type').text('Rs');
	});


	$('input[name=invoice_date]').on('change', function(e) {		
		var date = $(this).val();
		if(date == null){
			$('input[name=due_date], input[name=shipping_date]').val("");
		}
		advanced_date(date);
	});

	advanced_date($('input[name=invoice_date]').val());

	function advanced_date(date) {
		if(date != "") {
			$('input[name=due_date], input[name=shipping_date]').datepicker('remove');
			$('input[name=due_date], input[name=shipping_date]').prop('disabled', false);
			$('input[name=due_date], input[name=shipping_date]').datepicker({
				startDate: date,
				todayHighlight: true,
				rtl: false,
				orientation: "left",
				autoclose: true
			});
		} else {
			$('input[name=due_date], input[name=shipping_date]').prop('disabled', true);
		}
	}

	$('.people').hide();

	$('#people_type').on('click', function(){
		$('.people').show();
		$('.business').hide();
		$('.people').find('select').prop('disabled', false);
		$('.business').find('select').prop('disabled', true);

	});

	$('#business_type').on('click', function(){	
		$('.business').show();
		$('.people').hide();
		$('.business').find('select').prop('disabled', false);
		$('.people').find('select').prop('disabled', true);

	});


	

	$('#person_id').each(function() {
		$(this).prepend('<option value="0"></option>');
		select_user($(this));
	});

	$('#business_id').each(function() {
		$(this).prepend('<option value="0"></option>');
		select_business($(this));
	});


	
	// to get credit limit
	/*$('select[name=people_id]').on('change', function(){
		
		var selected_people = $(this).val();
		
		var selected_type = $('input[name=customer]:checked').val();		
		
		$.ajax({
				 url: '{{ route('get_credit_limit') }}',
				 type: 'post',
				 data: {
					_token : '{{ csrf_token() }}',
					selected_people: selected_people,
					selected_type : selected_type
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						
						var result = data.max_credit_limit;			
						
						if(result != "")
						{
							$('input[name=credit_limit_text]').val(result);
							$('.credit_limit_value').text(result);
							$('.credit_limit').show();
						}
						if(result == null)
						{
							$('.credit_limit_value').text("0.00");
							$('.credit_limit').show();

						}
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});

	});*/


	$('input[name=billing_checkbox]').on('change', function() {
		if($(this).is(":checked")) {
			$(".billing").show();//.find('input, textarea').prop('disabled', false);
		} 
		else {
			$(".billing").hide();//.find('input, textarea').prop('disabled', true);      
		}
	});


	$('input[name=shipping_checkbox]').on('change', function() {
		if($(this).is(":checked")) {
			$(".shipping").show();//.find('input, textarea').prop('disabled', false);
		} 
		else {
			$(".shipping").hide();//.find('input, textarea').prop('disabled', true);      
		}
	});
	


	$('.side_panel').on('click', function() {
		$('.slide_panel_bg').fadeIn();
		$('.settings_panel').animate({ right: 0 });
	});

	$('.close_side_panel').on('click', function() {
		$('.slide_panel_bg').fadeOut();
		$('.settings_panel').animate({ right: "-25%" });
		load_data();
	});

	$( "select[name=make_id]" ).change(function () {

		var model =  $( "select[name=vehicle_model_id]" );

		var select_val = $(this).val();
		model.empty();
		model.append("<option value=''>Select Model</option>");
			$.ajax({
				 url: '{{ route('get_model') }}',
				 type: 'post',
				 data: {
					_token : '{{ csrf_token() }}',
					make_id: select_val
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						for(var i in result) {	
							model.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});

	});



	$('select[name=voucher_term_id]').on('change', function(){

		if($(this).val() != "") {

			//var term_days = $(this).find('option:selected').data('value');

			var term_text = $(this).find('option:selected').text();

			if(term_text== 'Due on receipt'){
				var term_days = 0;
			}
			else if(term_text== 'Net 15'){
				var term_days = 15;
			}else{
				var term_days = 30;
			}

			var due_date =  $('input[name=invoice_date]').datepicker('getDate');
			
			due_date.setDate(due_date.getDate()+term_days);

			$('input[name=due_date]').datepicker("setDate", due_date);

		}

	});

	$('.job_date').on('change', function(){		

			if($(this).val() != "") {
				//var job_date = $(this).val();
				var due_date =  $('input[name=job_date]').datepicker('getDate');
				due_date.setDate(due_date.getDate()+1);	
				$('input[name=job_due_date]').datepicker("setDate", due_date);			
			}
			
		});

	$('input[name=order_id]').on('input', function() {
		var id = $('input[name=order_id]').val();
		var type = $("select[name=order_type]").val();
		if(type == "" && $("select[name=reference_type]").val() == "direct") {
			$('.transactionform input:not(input[type=button]):not(input[type=submit]):not(input[type=reset]):not(input[name=_token]):not(input[name=order_id]):not(input[name=invoice_date]):not(input[type=radio]):not(input[type=checkbox])').val("");
			$('.transactionform select:not([name=reference_type]):not([name=tax_types])').val("");
			$('.transactionform select:not([name=reference_type]):not([name=tax_types]):not("#state")').trigger('change');
		} else if(type != "" && id != "") {
			order(id, type, 1);	
		}
	});

	/*$('select[name=order_type]').on('change', function() {
		var id = $('input[name=order_id]').val();
		var type = $("select[name=order_type] option:selected").val();
		if(type != "" && id != "") {
			order(id, type);	
		}
	});*/


	/*$('input[name=order_id]').on('input', function() {
		var obj = $(this);
		var id = obj.val();
		var type = "";
		if(id != "") {

						@if($type == 'purchases')
						 type= 'purchase_order'
						 @elseif($type == 'sales' || $type == 'sales_cash')
						 type= 'sale_order'
						 @elseif($type == 'credit_note' || $type == 'delivery_note' || $type == 'receipt')
						 type= 'sales'
						 @elseif($type == 'debit_note' || $type == 'goods_receipt_note' || $type == 'payment')
						 type= 'purchases'
						 @endif

			order(id, type);
			
		}
		
	});*/

	
	
/*if(isFirstIteration == false) {*/
	
	$('body').on('blur', 'input[name=quantity], input[name=rate], input[name=discount], select[name=tax_id], select[name=discount_id], input[name=discount_value]', function(){

		var obj = $(this);
		var parent = obj.closest('tr');
		var tax_type = $('select[name=tax_types]').val();
		var rate =  parent.find('input[name=rate]').val();
		var quantity = parent.find('input[name=quantity]').val();
		var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
		var discount_id = parent.find('input[name=discount_value]').val();
		var tax_value = isNaN(tax_id) ? 0 : tax_id/100;

		var discount_value = isNaN(discount_id) ? 0 : discount_id/100;

		var amount = (rate*quantity).toFixed(2);
		var tax_amount = (amount*tax_value).toFixed(2);				
		var discount_amount = (amount*discount_value).toFixed(2);

		if(rate > 0 || quantity > 0){
		
			parent.find('input[name=amount]').val(amount);
			table(); 
		}
		
		
	});

	


	/*$('body').on('change', 'input[name=quantity]', function(){

		var obj = $(this);
		var parent = obj.closest('tr');
		
		var in_stock =  parent.find('input[name=in_stock]').val();
		var quantity = parent.find('input[name=quantity]').val();
		var job_item_status = parent.find('select[name=job_item_status]').val();
		


		if(parseInt(quantity) > parseInt(in_stock))
		{
			obj.closest('tr').find('td > input[name=quantity]').css('color', '#FF0000');
			obj.closest('tr').find('td > select[name=job_item_status]').val(3);
			obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');
			
		}else{
			obj.closest('tr').find('td > input[name=quantity]').css('color', '#000000');
			obj.closest('tr').find('td > select[name=job_item_status]').val(1);
			obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');			
		}
		table();			

	});*/

	$('body').on('blur','input[name=new_discount_value]',function(){		
		
		var dis_val = $(this).val();

		var append_item = $('.append_table').find('input[name=append_item_id]').val();

		var crud_item = $('.crud_table').find('select[name=item_id]').val();

		/* For WMS and Trade */
		
		if(dis_val > 0 && append_item != ''){
			$('.append_table').find('input[name=discount_value]').val(dis_val);
			table();
		}

		if(dis_val == ''){
			$('.append_table').find('input[name=discount_value]').val('');
			table();
		}			

		if(dis_val > 0 && crud_item != ''){

			$('.crud_table').find('input[name=discount_value]').val(dis_val);
			table();
		}

		if(dis_val == ''){
			$('.crud_table').find('input[name=discount_value]').val('');
			table();
		}

		/* End*/		
		
	});

	/*$('body').on('keyup','input[name=new_discount_value]',function(){

		var crud_dis_val = $(this).val();

		var crud_item = $('.crud_table').find('select[name=item_id]').val();	

		if(crud_dis_val > 0 && crud_item != ''){

			$('.crud_table').find('input[name=discount_value]').val(crud_dis_val);
		}

		if(crud_dis_val == ''){
			$('.crud_table').find('input[name=discount_value]').val('');
		}
	});*/


	$('body').on('change', 'select[name=tax_id], select[name=discount_id]', function(){

		var obj = $(this);
		var parent = obj.closest('tr');

		if($(this).attr('name') == 'discount_id') {
			parent.find('input[name=discount_value]').val($(this).find('option:selected').data('value'));
		}

		
		var tax_type = $('select[name=tax_types]').val();
		var rate =  parent.find('input[name=rate]').val();
		var quantity = parent.find('input[name=quantity]').val();
		var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
		var discount_id = parent.find('input[name=discount_value]').val();
		var tax_value = isNaN(tax_id) ? 0 : tax_id/100;

		var discount_value = isNaN(discount_id) ? 0 : discount_id/100;

		var amount = (rate*quantity).toFixed(2);
		var tax_amount = (amount*tax_value).toFixed(2);
		var discount_amount = (amount*discount_value).toFixed(2);
		

		parent.find('input[name=amount]').val(amount);
		table();
 
		
	});


	$('select[name=tax_types]').on('change', function(){
		var obj = $(this);

		$('.crud_table tbody tr').each(function() {

			var parent = $(this);
			var rate =  parent.find('input[name=rate]').val();
			var quantity = parent.find('input[name=quantity]').val();
			var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
			var discount_id = parent.find('select[name=discount_id]').find('option:selected').data('value');
			var tax_value = isNaN(tax_id) ? 0 : tax_id/100;

			if(obj.val() == 1) {
				var include_tax = (rate*(tax_value+1)).toFixed(2);
				parent.find('input[name=rate]').val(include_tax);
			} else if(obj.val() == 2) {
				var exclude_tax = rate - (rate * (tax_value * 100)/(100 + (tax_value * 100) ));
				parent.find('input[name=rate]').val(exclude_tax);
			} else if(obj.val() == 0) {
				parent.find('select[name=tax_id]').val("").trigger('change');
				parent.find('input[name=rate]').val(rate);
			}

			var discount_value = isNaN(discount_id) ? 0 : discount_id/100;

			var amount = isNaN(parent.find('input[name=rate]').val()) ? 0 : parent.find('input[name=rate]').val()*quantity;

			var tax_amount = amount*tax_value;
			var discount_amount = amount*discount_value;
			
			parent.find('input[name=amount]').val(amount);
			table();
	 
			
		});

	});


	$('body').on('blur','input[name=tax_total]',function(){

		var obj = $(this);

		var parent = obj.closest('tr');
		var tax_total = parent.find('input[name=tax_total]').val();
		var qty = parent.find('input[name=quantity]').val();
		var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');

		if(tax_total > 0)	{	
			var tax_value = isNaN(tax_id) ? 0 : tax_id/100
			var amount = tax_total / (1 + tax_value);
			var unit_rate = amount / qty;
			var amount = unit_rate * qty;
			var tax_amount = amount*tax_value;


			parent.find('input[name=rate]').val(unit_rate.toFixed(2));
			parent.find('input[name=amount]').val(amount.toFixed(2));
			parent.find('input[name=tax_amount]').val(tax_amount.toFixed(2));

			table();
		}

	});


	$('body').on('blur', 'input[name=amount]', function(){

		var obj = $(this);
		var parent = obj.closest('tr');
		var quantity = parent.find('input[name=quantity]').val();
		var rate =  parent.find('input[name=rate]').val();
		var amount = parent.find('input[name=amount]').val();

		if(amount > 0){
			var new_rate = amount/quantity;
			parent.find('input[name=rate]').val(new_rate.toFixed(2));

			table();
		}

	});
	

	$('body').on('change', 'input[name=discount_is_percent]', function(){
		
		table();
		/*if(isFirstIteration == false) {
			table();
		}*/

	});

	/*}*/

	$('select.person_id, select.business_id').on('change', function() {
		if($("input[name=order_id]").val() == "") {
			var id = $(this).val();
			var type;

		if(id != "" && id != null) {

			if($(this).hasClass('person_id')) {
				type = '0';
			} else if($(this).hasClass('business_id')) {
				type = '1';
			}

			$.ajax({
				 url: "{{ route('get_customer_preference') }}",
				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',
					id: id,
					type: type
				 },
				 dataType: "json",
				 success:function(data, textStatus, jqXHR) {

					if(data.term_id != "" && data.term_id != null) {
						$('select[name=voucher_term_id]').val(data.term_id);
						$('select[name=voucher_term_id]').trigger('change');
					} else if(data.payment_mode_id != "" && data.term_id != null) {
						$('select[name=payment_method_id]').val(data.payment_mode_id);
						$('select[name=payment_method_id]').trigger('change');
					}
							
				 },
				 error:function(jqXHR, textStatus, errorThrown) {}
			});

		}

		}
		
	});

	$('.reference').on('click', function () {
		
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


	$('.invoice_print').on('click',function(){
        var transaction_id = $(this).attr('data-id');
        var data = $(this).attr('data-formate');
        $('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('print_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: transaction_id,
						data:data

					},
					success:function(data, textStatus, jqXHR) {
						console.log("invoice print success");
						console.log(data);
                         
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
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
							container.find("[data-value='billing_name']").text(data.billing_name);

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
                           //to show voucher number in invoice
							container.find("[data-value='estimate_no']").text(data.estimate_no);

                            container.find("[data-value='assigned_to']").text(data.assigned_to);
                             container.find("[data-value='company_gst']").text(data.company_gst);
                             container.find("[data-value='customer_communication_gst']").text(data.customer_communication_gst);
                             container.find("[data-value='billing_communication_gst']").text(data.billing_communication_gst);

                             container.find("[data-value='customer_gst']").text(data.customer_gst);
                             container.find("[data-value='customer_mobile']").text(data.customer_mobile);

							var row = container.find('.invoice_item_table tbody tr').clone();

							var invoice_items = ``;
                            var total_amount = 0;
                            var total_discount = 0;
                            var  total_length= 10;

                            // to show item in job invoice 
                            	/*var row = container.find('.item_table tbody tr').clone();
                            	console.log(data.items);

							var items = ``;

							for (var i = 0; i < (data.items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();

								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.items[i].name);
								new_row.find('.col_hsn').text(data.items[i].hsn);
								new_row.find('.col_gst').text(data.items[i].gst);
								new_row.find('.col_discount').text(data.items[i].discount);
								
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_rate').text(data.items[i].rate);
								new_row.find('.col_amount').text(data.items[i].amount);

								items += `<tr>`+new_row.html()+`</tr>`;
							}*/

                            //end
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

								
								var total_cgst = parseFloat(tax_amount)+parseFloat(total_cgst);
								 

								var total_sgst = parseFloat(tax_amount)+parseFloat(total_sgst);


								
						}else{
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

							

                    var  total_tax = total_cgst + total_sgst + total_igst + total_amount;
                    var round_of = Math.ceil(total_tax);
                    var Rount_off_value = round_of - total_tax;
                    var total = total_tax + total_amount;
                    var total_amount= Rount_off_value + total_tax;
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
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
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
								var unit_rate = parseFloat(data.no_tax_sale[i].rate);
								var discount_amount = data.no_tax_sale[i].discount;
								
								var amount = parseFloat(data.no_tax_sale[i].amount);
								if(unit_rate == undefined){
									unit_rate = 0;
								}else{
									unit_rate = parseFloat(data.no_tax_sale[i].rate);
								}

								if(discount_amount == undefined){
									discount_amount = 0;
								}else{
									discount_amount = data.no_tax_sale[i].discount;
								}

								if(amount == undefined){
									amount = 0;
								}else{
									amount = parseFloat(data.no_tax_sale[i].amount);
								}
								
								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.no_tax_sale[i].name);
								new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
								new_row.find('.col_rate').text(parseFloat(unit_rate).toFixed(2));
								new_row.find('.col_discount').text(parseFloat(discount_amount).toFixed(2));
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
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

							//to show items in b2c no tax job invoice..
							var row = container.find('.no_tax_sales_table tbody tr').clone();

							var no_tax_estimation = ``;
							var total_sale_amount = 0.00;
							for (var i = 0; i < (data.no_tax_estimation).length; i++) {
								var j = i + 1;
								var sales_new_row = row.clone();
								var tax_amount = data.no_tax_estimation[i].tax_amount;
								if(tax_amount == null){
									tax_amount = 0.00;
								}
								var tax_rate = data.no_tax_estimation[i].tax_rate;
								if(tax_rate == null){
									tax_rate = 0.00;
								}
								var unit_price = data.no_tax_estimation[i].rate;
								var quantity = data.no_tax_estimation[i].quantity;
								var price = parseFloat(tax_rate) + parseFloat(unit_price);
								var amount = parseFloat(quantity) * parseFloat(unit_price);
								var total_amount = parseFloat(amount) + parseFloat(tax_amount);
								
								
								sales_new_row.find('.col_id').text(j);
								sales_new_row.find('.col_desc').text(data.no_tax_estimation[i].name);
								sales_new_row.find('.col_quantity').text(data.no_tax_estimation[i].quantity);
								sales_new_row.find('.col_rate').text(parseFloat(price).toFixed(2));
								sales_new_row.find('.col_discount').text(data.no_tax_estimation[i].discount);
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
								sales_new_row.find('.col_amount').text(parseFloat(total_amount).toFixed(2));
								var total_sale_amount = parseFloat(total_amount) + parseFloat(total_sale_amount);
								no_tax_estimation += `<tr>`+sales_new_row.html()+`</tr>`;
							}

							container.find('.sales_total_amount').text(parseFloat(total_sale_amount).toFixed(2));

							container.find('.no_tax_sales_table tbody').empty();
							container.find('.no_tax_sales_table tbody').append(no_tax_estimation);

							

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
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
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
	});

	$(".estimation_print").on('click', function(e) {
		console.log("estimation print function");
		var id = $(this).attr('data-id');
		 var data = $(this).attr('data-formate');
		print_transaction(id,data);

	});

	/*load_data();

	function load_data() {
		$('#container').html(`<div id="container" class="container field" style="float:left;"><hr>
		<div class="form-group">
		 <div class="col-md-12">
		<label for="field_name"><b>Field Label</b></label>
		</div>
		</div>

		 <div class="form-group">
		 <div class="col-md-12">
		<label for="field_name">Field Name</label>
		{{ Form::text('field_name', null, ['class'=>'form-control']) }} 
		</div>
		</div>

		<div class="form-group">
		 <div class="col-md-12">
		<label for="field_type">Field Type</label>
		<select name='field_type' class='form-control field_type' id = 'field_type'>
		<option value="">Select Field Type</option>						
		@foreach($field_types as $field_type)					
		<option value="{{ $field_type->id }}" data-name="{{ $field_type->name }}" data-format="{{$field_type->format}}" data-format_id="{{$field_type->format_id}}">{{ $field_type->display_name }}</option>
		@endforeach					
		</select> 
		</div>
		</div>

		<div class="form-group col-md-12" style="display:none">
		  <table class="field_table">
			<thead>
			  <tr>
				<th class="field_item">Options</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td class="field_item">{!! Form::text('field_item', null, ['class'=>'form-control', 'id'=>'field_item']) !!}</td>
				<td><a class="grid_label action-btn edit-icon add_field"><i class="fa fa-plus"></i></a></td>
			  </tr>
			</tbody>
		  </table>
		</div>

		<div class="form-group">
				<div class="col-md-12"> {{ Form::checkbox('type', '1', null, array('id' => 'type')) }}
					<label for="type"><span></span>Enable on all <?php echo str_replace("_", " ", $type); ?> transactions.</label>
				</div>
		</div>
		<div class="form-group">
				<div class="col-md-12"> {{ Form::checkbox('required_status', '1', null, array('id' => 'require')) }}
					<label for="require"><span></span>It's Required</label>
				</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-md-5"> 
					<a href="#" class="under_group">Under Sub-Head</a>
				</div>
				<div class="col-md-7"> 
					<a href="#" class="create_new_group">Create New Sub-Head</a>
				</div>
				</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 group">
				
				</div>
		</div>
		 <div>
			<button style="float:right" type="submit" class="btn btn-success save_field">Save Field </button>
			</div>
		</div>`);
	}*/	
	
	$('body').on('click', '.under_group', function(){
		
		$('.group').html(`<select name='group' class='form-control group' id = 'group'>
			<option value="">Select Sub Heading</option>						
			@foreach($sub_heading as $field)					
			<option value="{{ $field->sub_heading }}">{{ $field->sub_heading }}</option>
			@endforeach					
			</select>`);		

	});

	$('body').on('click', '.create_new_group', function(){

		$('.group').html(`{{ Form::text('new_group', null, ['class'=>'form-control']) }}`);
		
	});
	

	$('body').on('change', 'select[name=field_type]', function(){
		$('.field_table').closest('.form-group').hide();
		var field_types = $('select[name=field_type] option:selected').data('name');

		if(field_types == 'select' || field_types == 'checkbox' || field_types == 'radio') {

			$('.field_table').closest('.form-group').show();

		}

	});

	$('select[name=reference_type]').on('change', function() {
		if($('select[name=order_type]').length > 0) {
			if($(this).val() != "direct") {
				$('select[name=order_type]').val($(this).val()).trigger('change');
			} else {
				$('select[name=order_type]').val("").trigger('change');
			}	
		}
	});

	$('body').on('click', '.add_field', function(){
		var obj = $(this);
		var clone = obj.closest('tr').clone(true, true);
		clone.find('input[name=field_item]').val("");

		
		obj.closest('td').append('<a class="grid_label action-btn delete-icon delete_field"><i class="fa fa-trash-o"></i></a>');
		obj.closest('tr').after(clone);
		obj.closest('td').find('a.edit-icon').hide();

	});

	$('body').on('click', '.delete_field', function(){
		var obj = $(this);
		obj.closest('tr').find('input[name=field_item]').val("");
		obj.closest('tr').hide();

	});

		$('body').on('click', '.save_field', function(){
			var field_item = $('input[name=field_item]').map(function() { 
						return this.value; 
					}).get();
			$.ajax({

				url: "{{ route('save_field') }}",
				type: "post",
				data: {
					_token: '{{ csrf_token() }}',
					type: '{{$type}}',
					field_name: $('input[name=field_name]').val(),
					field_type: $('select[name=field_type] option:selected').val(),
					field_format: $('select[name=field_type] option:selected').data('format'),
					check_type: $('input[name=type]:checked').val(),
					new_group: $('input[name=new_group]').val(),
					required_status: $('input[name=required_status]:checked').val(),
					field_item: field_item,
				},
				dataType: "json",

			success:function(data, textStatus, jqXHR) {

			var field_name = $('input[name=field_name]').val();
			var field_type = $('select[name=field_type] option:selected').data('name');
			var field_format = $('select[name=field_type] option:selected').data('format');
			var field_format_id = $('select[name=field_type] option:selected').data('format_id');
			var field_type_id = $('select[name=field_type]').val();
			var check_type = ($('input[name=type]:checked').val() != "") ? $('input[name=type]:checked').val() : 0;
			var html="";

			if(field_type != null) {

			html += `<div class="col-md-3 field_label" style="border:1px dashed #ccc"><a class="remove_field" style="position:absolute;top:-10px; right:5px"><i class="fa fa-trash-o" style="font-size:18px;color:#aaa;"></i></a>
				<label class="fields" style="text-transform:capitalize;width:100%;"> `+field_name+` </label>`;
				if(field_type == 'textbox') {
					html += `<input data-type="`+field_type_id+`" data-status="`+check_type+`" name="field" data-name="`+field_name+`" class="form-control `+field_format+`" data-format="`+field_format_id+`" />`;
				}
				else if(field_type == 'select') {
					html += `<select name="`+field_name+`" data-name="`+data.data.id+`" class="form-control">
								<option>Select `+field_name+`</option>`;
							for(var i in field_item) {
								html += `<option>`+field_item[i]+`</option>`;
							}
								
		           html += `</select>`;
				}
				
				else if(field_type == 'radio') {
					for(var i in field_item) {
						html += `<input type="radio" name="`+field_name+`" value="`+data.data.id+`" /><label for="`+data.data.id+`" style="text-transform:capitalize;"><span></span>`+field_item[i]+`</label>`;
						}

				}
				html += `</div>`;
				

			}

			$('.field_container').append(html);

			$('.close_side_panel').trigger('click');

			basic_functions();
		}
		});

		});
		});

	$('body').on('click', '.remove_field', function(){

		$(this).closest('.field_label').remove();

	});

	


	$(".tab_delete_btn").on('click', function(e) {
		e.preventDefault();
		$('.delete_modal_ajax').modal('show');
					$('.delete_modal_ajax_btn').off().on('click', function() {
						$.ajax({
							 url: "{{ route('transaction.destroy') }}",
							 type: 'post',
							 data: {
								_method: 'delete',
								_token : '{{ csrf_token() }}',
								id: '{{$id}}',
								},
							 dataType: "json",
							 beforeSend: function() {
							 	$('.loader_wall_onspot').hide();
							 },
							 success:function(data, textStatus, jqXHR) {
								call_back("", `edit`, data.message, '{{$id}}');
								$('.close_full_modal').trigger('click');
								$('.loader_wall_onspot').hide();
								$('.delete_modal_ajax').modal('hide');
								$('.alert-success').text(message);
								$('.alert-success').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							 },
							 error:function(jqXHR, textStatus, errorThrown) {
								}
							});
					});
	});

	$(".tab_send_btn").on('click', function(e) {
		e.preventDefault();			

			$.ajax({
				url: "{{ route('transaction.send_all') }}",
				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',					
					@if(!empty($transactions))
					
					id: '{{ $transactions->id }}',
					@endif
				},
				success:function(data, textStatus, jqXHR) {
					
					$('.alert-success').text(data.message);
					$('.alert-success').show();

					setTimeout(function() { $('.alert').fadeOut(); }, 3000);
					
				},
				error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
				}

			});
	});

	$(".estimation_msg").on('click', function(e) {
            e.preventDefault();		
           var id=$(this).data("id");
				$.ajax({
					url: "{{ route('transaction.estimation_sms') }}" ,
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						id: id
					},
					success: function(data, textStatus, jqXHR) {
                           alert_message(data.message, "success");
					}
				});
	});

	
		

		$('body').on('click', '.tab_sms_btn', function(e) {

			e.preventDefault();				

			$.ajax({

				url: "{{ route('sms_limitation') }}",
				type: 'get',
				data:{
					//_token : '{{ csrf_token() }}',
					//type: '{{ $transaction_type->name }}'
				},
				success: function(data, textStatus, jqXHR)
				{
					var sms_limit = data.sms_limitation;

					if(sms_limit == true){

						//isFirstIteration = true;

						$.ajax({
							//url: "{{ route('transaction.sms_send') }}",
							url: "{{ route('transaction.estimation_sms') }}",
							 type: 'post',
							 data: {
								_token: '{{ csrf_token() }}',					
								@if(!empty($transactions))
								
								id: '{{ $transactions->id }}',
								type: '{{ $transaction_type->name }}',
								@endif
							},
							success: function(data, textStatus, jqXHR) {
			                    alert_message(data.message, "success");
							}

						});
					}
					else{

						$('#error_dialog #title').text('Limit Exceeded!');
						$('#error_dialog #message').html('{{ config('constants.error.sms_no') }}'  + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us." );
						$('#error_dialog').modal('show');

						return false;
					}
				}
			});

		});


		

	$(".tab_print_btn").on('click', function(e) {

		print_transaction('{{$id}}',null);

	});
	
	$(".tab_pdf_btn").on('click', function(e) {
    	e.preventDefault();		
		
		pdf_transaction('{{$id}}');
	});


	$("body").on('click', '.tab_update_goods_btn', function(e) {
	
		e.preventDefault();

		var obj = $(this);		

		$.ajax({
				url: "{{ route('transaction.update_inventory') }}",
				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',					
					@if(!empty($transactions))
					
					id: '{{ $transactions->id }}',
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



		var sms = 0;
		var print = 0;
		var approve = 0;
		var send_po = 0;
		var tab_update_goods_btn = 0;
		var tab_save_close = false;
		var jc_save_close = false;
		var jc_store_only = false;

		$(".tab_approve_btn, .tab_save_close_btn, .tab_save_btn").off().on('click', function(e) {

			//alert(print);
		
			var that = $(this);
	
			e.preventDefault();
			var next_tab = $('.nav-tabs li a.active').parent().next('li:visible').find('a').attr('href');
			var next_other_tab = $('.nav-tabs li a.active').parent().next('li:visible').next('li:visible').find('a').attr('href');

			var validator = $('.transactionform').validate();

			if(that.hasClass('tab_save_close_btn')) {
			tab_save_close = true;
			} else {
			tab_save_close = false;
			}

			if(that.hasClass('jc_store_btn')) {
				jc_store_only = true;

			}else{
				jc_store_only = false;
			}


			if(that.hasClass('jc_store')) {					
				jc_save_close = true;
			}else{
				jc_save_close = false;
			}

			if($(this).hasClass('tab_approve_btn')) {
				send_po = 0;
				approve = 1;
				print = 0;
				sms = 0;
				tab_update_goods_btn = 0;
				tab_save_close = false;
			}
			 else if($(this).hasClass('tab_save_close_btn')) {
				send_po = 0;
				approve = 0;
				print = 0;
				sms = 0;
				tab_update_goods_btn = 1;
				tab_save_close = true;
			}

			if(validator.checkForm() == true) {
				$('.form-group').removeClass('has-error');
				$('.help-block').remove();

				/*if(next_tab) {

					$('a[href="'+next_tab+'"]')[0].click();
					
					if(next_other_tab == undefined) {
						$(this).text("Save");
					}

					if(next_other_tab == undefined) {
						if(that.hasClass('tab_save_close_btn')) {
							that.text("Save and Close");
						}else if(that.hasClass('tab_approve_btn')) {
							that.text("Approve");
						}  else {
							that.text("Save");
						}
					}
					return false;
				}*/

				if($(".transactionform").valid()) {
					$(".transactionform").submit();
				}
			} 
			else {
				$('.form-group').addClass('has-error');
				validator.showErrors();
			}
		});

		$('.job_make_transaction').off().on('click', function(e) {
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
						$('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Confirmation:</h4></div><div class="modal-body"><h7>'+data.data.display_name+'('+data.data.order_no+') For this Job card is already Exist..!<br>Click Continue to Create<br> <input type="checkbox" name="vehicle" class="pull-left" style = "display:block;width: 22px;height: 19px;"checked><span class="pull-left">Delete the existing Estimation</span></h7></div><div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">No</button><button type="button" id='+data.data.id+' data-name='+data.data.order_no+' class="btn btn-success ok_btn" data-dismiss="modal">Continue</button></div>');
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

                               		make_transaction(name);
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
						$('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Confirmation:</h4></div><div class="modal-body"><h7>'+data.data.display_name+'('+data.data.order_no+') For this Transaction is already Exist..!<br>Click Continue to Create<br> <input type="checkbox" name="vehicle" class="pull-left" style = "display:block;width: 22px;height: 19px;"checked><span class="pull-left">Delete the existing Estimation</span></h7></div><div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">No</button><button type="button" id='+data.data.id+' data-name='+data.data.order_no+' class="btn btn-success ok_btn" data-dismiss="modal">Continue</button></div>');
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

                               		make_transaction(name);
                               	//Hided by vishnu this function is for delete and create a new transaction with the same deleted gen no
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
						$('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Confirmation:</h4></div><div class="modal-body"><h7>'+data.data.display_name+'('+data.data.order_no+') For this Transaction is already Exist..!<br>Click Continue to Create<br> <input type="checkbox" name="vehicle" class="pull-left" style = "display:block;width: 22px;height: 19px;"checked><span class="pull-left">Delete the existing Estimation</span></h7></div><div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">No</button><button type="button" id='+data.data.id+' data-name='+data.data.order_no+' class="btn btn-success ok_btn" data-dismiss="modal">Continue</button></div>');
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

                               		make_transaction(name);
                               	
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
			}
		});
			
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
					
					var job_status_id = $('input[name=jobcard_status_id]').val();
					

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
	/*Generate Transaction with existing order_no*/

	    function existing_transaction(name,gen_no) {
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
					
					var job_status_id = $('input[name=jobcard_status_id]').val();
					

					if(transaction_name == "job_invoice" || transaction_name == "job_invoice_cash"){

							$('<form>', {
						    "id": "dynamic_form",
						    "method": "POST",
						    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy"> <input type ="text" name="gen_no" value="'+gen_no+'">',
						    "action": '{{ route("add_to_account") }}'
							}).appendTo(document.body).submit();

							$('#dynamic_form').remove();

					}

					else{

						$('<form>', {
					    "id": "dynamic_form",
					    "method": "POST",
					    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'" > <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy"> <input type ="text" name="gen_no" value="'+gen_no+'">',
					    "action": '{{ route("add_to_account") }}'
						}).appendTo(document.body).submit();

					$('#dynamic_form').remove();

					}

				}

				if(transaction_type != "job_card"){

					$('<form>', {
					    "id": "dynamic_form",
					    "method": "POST",
					    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy"> <input type ="text" name="gen_no" value="'+gen_no+'">',
					    "action": '{{ route("add_to_account") }}'
						}).appendTo(document.body).submit();

					$('#dynamic_form').remove();
				}	
	    }
	/*end*/


		/*copy transaction*/
		$('.make_transaction').off().on('click', function(e) {

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
				
				var job_status_id = $('input[name=jobcard_status_id]').val();
				

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

		//$('body').off('change', 'select[name=item_id]');

		//$('.jc_item_create').on('click', function(e) {

		$('body').on('click', '.jc_item_create', function() {

			//e.preventDefault();
			$.get("{{ route('jc_item.create') }}", function(data) {

				$('.crud_modal .modal-container').html("");			
				//$('.crud_modal .modal-container').attr("data-id",0);
				$('.crud_modal .modal-container').html(data);		

				});
				$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
				$('.crud_modal').modal('show');
				$('.loader_wall_onspot').hide();


		});


		$('body').on('click', '.item_batch', function() {

			//e.preventDefault();

			var id = $(this).attr('data-id');
			var tr_id = $(this).closest('tr').attr('id');	
	     
	       
			$.get("{{ url('inventory/item-batches') }}/"+id, function(data) {

				$('.crud_modal .modal-container').html("");
				//$('.crud_modal .modal-container').attr("data-id",0);
				$('.crud_modal .modal-container').html(data);

			});
			$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

			/* get (+ sign) current row id */
			$('.crud_modal').find('.modal-dialog').attr('data-tr',tr_id);
			/*end*/
			$('.crud_modal').modal('show');
			$('.loader_wall_onspot').hide();

		});


		$('body').off('click', '.add_row').on('click', '.add_row', function() {

			var obj = $(this);
			var quantity = obj.closest("tr").find('input[name="quantity"]');
			var item = obj.closest("tr").find('select[name="item_id"]');		
			var remaining_item = obj.closest("tr").find('select[name="item_id"] > optgroup > option');


			if(item.val() != "" && quantity.val() != "" && quantity.val() != 0 ) {          

				$('.select_item').each(function() { 
					var select = $(this);  
					if(select.data('select2')) { 
						select.select2("destroy"); 
					} 

				});

				var clone = $(this).closest('tr').clone();

				/* get tr length using for batch item */	
			
				var row_index = $('.crud_table tbody > tr').length;

				var data_row = $(this).closest('tr').last().attr('data-row');		
				
	            var New_data_row = row_index;
	            
	            if(data_row)
	            {
	            	 New_data_row = parseInt(data_row)+1;
	            }
	            
			    clone.closest('tr').attr("id","tr_"+New_data_row);
			    clone.closest('tr').attr("data-row",New_data_row);

			    /*end*/

				//clone.find('select, input[type=text]').val("");           

				var selected_item = item.find(':selected').val();

				/*clone.find('.date-picker').datepicker({

					rtl: false,
					orientation: "left",
					todayHighlight: true,
					autoclose: true

				});*/

				clone.find('.datetimepicker2').datetimepicker({
					rtl: false,
					orientation: "left",
					todayHighlight: true,
					autoclose: true
				});

				

				clone.find('.item_container, .rate_container, .quantity_container, .tax_container, .description_container').empty();            

				clone.find('select[name=item_id], select[name=tax_id], input[name=tax_amount], select[name=discount_id], input[name=quantity], input[name=new_base_price],input[name=rate], input[name=amount],input[name=tax_total],textarea,input[name=discount_value], input[name=batch_id]').prop("disabled", false).val("");

				clone.find('.item_batch').hide();
				clone.find('input[name=in_stock]').prop("disabled", true).val("");

				clone.find('input[name=base_price]').prop("disabled", true).val("");

				clone.find('input[name=quantity]').css('color', '#000000');             

				// If Need Repeated Item use this line

				//clone.find('select[name=item_id] > optgroup > option[value="' + selected_item + '"]').wrap('<span>');

				clone.find('.index_number').text(parseInt($('.index_number').last().text()) + 1);

				if(remaining_item.length > 1) {

					clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

					/*if(remaining_item.length == 2) {

						clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');

					}*/

					obj.closest('tbody').append(clone);

				}

				obj.parent().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');

				item.find('optgroup > option[value!="' + selected_item + '"]').wrap('<span>');

				$('.select_item').select2();
			}

		});

		$('body').on('click', '.remove_row', function() {

			var obj = $(this);
			var item = obj.closest("tr").find('select[name="item_id"]');
			var remaining_item = obj.closest("tr").find('select[name="item_id"] > optgroup > option');
			var last_row_item = obj.closest("table").find('tr').last().find('select[name="item_id"] > optgroup > option');
			var selected_item = item.find(':selected').val();
			var selected_item_array = [];


			last_row_item.each(function() {
				selected_item_array.push($(this).val());
			});

			selected_item_array.push(selected_item);
		
			
			obj.closest('tr').nextUntil( 'tr.parent' ).remove();

			obj.closest('tr').next().find('.index_number').text( $('.parent').prev().length + 1);
			obj.closest('tr').remove();

			var new_index = 1;

			$('.index_number').each(function() {
				$(this).text(new_index);
				new_index++;
			});

			/*last_row_item.each(function() {
				selected_item_array.push($(this).val());
			});

			selected_item_array.push(selected_item);
		
			obj.closest('tr').remove();*/

			for (var i in selected_item_array) {
				$('select[name=item_id]:last').find('optgroup > span > option[value="' + selected_item_array[i] + '"]').unwrap();
			}

			$('select[name="item_id"]:last > span > option').unwrap();

			var row_index = $('.crud_table tbody > tr').length;

			

			if(row_index > 1) {
				$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
			} else {
				$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
			}
			table();

			/*if(isFirstIteration == false) {
				table();
			}*/
			

		});


	$('body').off('click', '.add_row_append').on('click', '.add_row_append', function()
	{
		var obj = $(this);

		var quantity = obj.closest("tr").find('input[name="quantity"]').val();
		var item_id = obj.closest("tr").find('select[name="item_id"]').val();
		var parent_id = obj.closest("tr").find('input[name="parent_id"]').val();
		var batch_id = obj.closest("tr").find('input[name="batch_id"]').val();
		var item_text = obj.closest("tr").find('select[name="item_id"] option:selected').text();

		var tax_id = obj.closest("tr").find('select[name="tax_id"]').val();
		var discount_id = obj.closest("tr").find('select[name="discount_id"]').val();

		var assigned_to = obj.closest("tr").find('select[name="assigned_employee_id"]').val();

		var item_status_id = obj.closest("tr").find('select[name="job_item_status"]').val(); 
		

		if(item_id != "" && quantity != "" && quantity != 0 ) {	

			$('.select_item').each(function() { 
			var select = $(this);  
			if(select.data('select2')) { 
				select.select2("destroy"); 
			} 
			});


			obj.closest("tr").find('.item_batch').hide();

			var crud_clone = $(this).closest('tr').clone();
			
			crud_clone.find('td:last').html('<a class="grid_label action-btn delete-icon remove_row_append"><i class="fa fa-trash-o"></i></a>');

			crud_clone.find('.datetimepicker2').datetimepicker({
				rtl: false,
				orientation: "left",
				todayHighlight: true,
				autoclose: true
			});	
			

			crud_clone.find('select[name="item_id"]').remove();

			var row_index = $('.append_table tbody > tr').length;	
						
			var New_data_row = row_index+1;

			crud_clone.find( "td:eq(0) > span" ).text(New_data_row);
			crud_clone.find( "td:eq(0) > span" ).removeClass('index_number');
			crud_clone.find( "td:eq(0) > span" ).addClass('index_number_append');


			crud_clone.find( "td:eq(1)" ).html('<input type="hidden" name="append_item_id" value="'+ item_id+'"  class="form-control"><input type="hidden" name="parent_id" value="'+parent_id+'"><input type="hidden" name="batch_id" value="'+batch_id+'" ><input type="text" name="append_item" class ="form-control" disabled="ture" style="width:200px;float: left;" value="'+item_text+'">');

			$('.append_table > tbody').append(crud_clone);

			$('.append_table').find('tr').last().find('select[name="tax_id"]').val(tax_id);

			$('.append_table').find('tr').last().find('select[name="discount_id"]').val(discount_id);

			$('.append_table').find('tr').last().find('select[name="assigned_employee_id"]').val(assigned_to);

			$('.append_table').find('tr').last().find('select[name="job_item_status"]').val(item_status_id);		

		}

		$('.crud_table').find('select[name=item_id], select[name=tax_id], select[name=discount_id], input[name=quantity], input[name=new_base_price],input[name=rate], input[name=amount],input[name=tax_total],textarea,input[name=discount_value], input[name=batch_id],input[name=in_stock],input[name=tax_amount]').val("");		

			$('.select_item').select2();
		 
	});



	$('body').off('click', '.remove_row_append').on('click', '.remove_row_append', function() {		

		var obj = $(this);
		obj.closest('tr').remove();
		var new_index = 1;

		$('.index_number_append').each(function() {
			$(this).text(new_index);
			new_index++;
		});
		
		table();

	});


		$('body').on('change', 'select[name=item_id]', function(event, params) {

			var obj = $(this);
			var id = obj.val();

			var vehicle_id = $('select[name=registration_number]').val();

			var over_all_discount = $('input[name=new_discount_value]').val();

			var transaction_module =  ('{{ $transaction_type->module }}');
				
			obj.closest('tr').find('select[name=discount_id]').trigger('change');


			if(transaction_module == 'trade_wms'){

				if(id != "" && vehicle_id != "") 
				{
					$.ajax({
						url: "{{ route('get_item_rate') }}",
						type: 'post',
						data: {
							_token: '{{ csrf_token() }}',
							id: id,
							vehicle_id : vehicle_id,
							transaction_module : transaction_module,
							date: $('input[name=invoice_date]').val()
						 },
						success:function(data, textStatus, jqXHR) {

							//var group = data.group;
							//var is_group = data.is_group;
							var segment_price = data.segment_price;
							var modules = data.modules;

							var item_batch_id = data.item_batch_id;
							var service_batch_id = data.service_batch_id;
							//var overall_quantity = data.overall_quantity;

							obj.closest('tr').nextUntil( 'tr.parent' ).remove();

							obj.find('option:selected').attr('data-rate', data.price);

						if(item_batch_id != null && transaction_module != 'inventory' && service_batch_id == null)
						{							
							/*var select = obj.closest('tr').find('select[name=tax_id]');

							if(select.data('select2')) { 
								select.select2("destroy"); 
							}*/ 						

							obj.closest('tr').find('.item_batch').show();

							obj.closest('tr').find('.item_batch').attr("data-id",id);

							obj.closest('tr').find('select, input, textarea').prop('disabled', false);

							(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

							obj.prop('disabled', false);

							//obj.closest('tr').find('select[name=tax_id]').prop('disabled', true);							

							if(params != 1)
							{
								obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock], input[name=amount], input[name=base_price], input[name=new_base_price], input[name=tax_amount],input[name=tax_total], select[name=tax_id], select[name=discount_id], input[name=discount_value]').val("");

								obj.closest('tr').find('select, input, textarea').prop('disabled', true);

								(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

								obj.prop('disabled', false);

								var box_total = $('input[name=total]').val();

								if(box_total <= 0.00){
									$('.sub_total, .box_tax_amount, .total').text("0.00");
								}else{
									table();
								}
							}

							//$('.crud_table').find('.sub_total, .box_tax_amount, .total').val("");

							//table();						
						}

						else if(service_batch_id != null && transaction_module != 'inventory' && item_batch_id == null)
						{
							var select = obj.closest('tr').find('select[name=tax_id]');

							if(select.data('select2')) { 
								select.select2("destroy"); 
							} 						

							obj.closest('tr').find('.item_batch').show();

							obj.closest('tr').find('.item_batch').attr("data-id",id);

							obj.closest('tr').find('select, input, textarea').prop('disabled', false);

							(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

							obj.prop('disabled', false);

							obj.closest('tr').find('select[name=tax_id]').prop('disabled', true);

							

							if(params != 1)
							{
							obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock], input[name=amount], input[name=base_price], input[name=new_base_price], input[name=tax_amount],input[name=tax_total], select[name=tax_id], select[name=discount_id], input[name=discount_value]').val("");

								obj.closest('tr').find('select, input, textarea').prop('disabled', true);

								(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

								obj.prop('disabled', false);

								var box_total = $('input[name=total]').val();

								if(box_total <= 0.00){
									$('.sub_total, .box_tax_amount, .total').text("0.00");
								}else{
									table();
								}
							}						
						}

						else
						{
							obj.closest('tr').find('input[name=batch_id]').val(data.single_batch_id);

							obj.closest('tr').find('select, input, textarea').prop('disabled', false);

							(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

							obj.closest('tr').find('#item_batch').hide();

							obj.prop('disabled', false);	
							
							if(over_all_discount != null)
							{
							obj.closest('tr').find('td > input[name=discount_value]').val(over_all_discount);
							}
							
							@if($type == 'purchases' || $type == 'purchase_order')

								if(obj.closest('tr').find('input[name=quantity]').val() == "" || obj.closest('tr').find('input[name=quantity]').val() == 0) {

								obj.closest('tr').find('input[name=quantity]').val(data.moq);
							}else{
								obj.closest('tr').find('input[name=quantity]').val(1);

							}

							@else

							if(obj.closest('tr').find('input[name=quantity]').val() == "" || obj.closest('tr').find('input[name=quantity]').val() == 0) {

								obj.closest('tr').find('input[name=quantity]').val(1);								
							}
							else{
								obj.closest('tr').find('input[name=quantity]').val(1);
							}

							@endif	
							
							@if($type == 'purchases' || $type == 'purchase_order'  || $type == 'goods_receipt_note')
								obj.closest('tr').find('input[name=rate]').val(data.purchase_price);

							@else

							if(modules == 'trade_wms')
							{

								obj.closest('tr').find('input[name=duration]').val(data.duration);

								if(segment_price == null){
									obj.closest('tr').find('input[name=rate]').val(data.base_price);
								}
								else{
									obj.closest('tr').find('input[name=rate]').val(data.segment_price);
								}
								}
							
								else{

									obj.closest('tr').find('input[name=rate]').val(data.base_price);
								}	


							@endif

							obj.closest('tr').find('td > input[name=in_stock]').val(data.in_stock).prop('disabled', true);

							if(modules == 'inventory'){

								obj.closest('tr').find('input[name=base_price]').val(data.base_price);

								
							}else{
								obj.closest('tr').find('td > input[name=base_price]').val(data.price).prop('disabled', true);
							}							
							
							obj.closest('tr').find('td > select[name=tax_id]').val(data.tax_id).prop('disabled', false);

							obj.closest('tr').find('td > select[name=tax_id]').trigger('change');
							

							obj.closest('tr').find('td > select[name=job_item_status]').val();

							obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');

							
							/*if(parseInt(data.in_stock) <= 0 )
							{
								obj.closest('tr').find('td > select[name=job_item_status]').val(3);
								obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');
							}else{
								obj.closest('tr').find('td > select[name=job_item_status]').val(1);
								obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');
							}*/

							$('.select_item').select2();
						}
					},
						error:function(jqXHR, textStatus, errorThrown) {}
					});
					
				}				

				else {
					obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock]').val("");

					// Default select2 option

					//table();

					$('select[name=item_id]').val('').select2();		
				}


				
			}

			if(transaction_module != 'trade_wms'){

				if(id != "") 
				{
					$.ajax({
						url: "{{ route('get_item_rate') }}",
						type: 'post',
						data: {
							_token: '{{ csrf_token() }}',
							id: id,
							transaction_module : transaction_module,

							date: $('input[name=invoice_date]').val()
						 },
						success:function(data, textStatus, jqXHR) {

							//var group = data.group;
							//var is_group = data.is_group;
							var segment_price = data.segment_price;
							var modules = data.modules;

							var item_batch_id = data.item_batch_id;
							var service_batch_id = data.service_batch_id;
							//var overall_quantity = data.overall_quantity;

							obj.closest('tr').nextUntil( 'tr.parent' ).remove();

							obj.find('option:selected').attr('data-rate', data.price);

						if(item_batch_id != null && transaction_module != 'inventory' && service_batch_id == null)
						{
							/*var select = obj.closest('tr').find('select[name=tax_id]');

							if(select.data('select2')) { 
								select.select2("destroy"); 
							}*/ 						

							obj.closest('tr').find('.item_batch').show();

							obj.closest('tr').find('.item_batch').attr("data-id",id);

							obj.closest('tr').find('select, input, textarea').prop('disabled', false);

							(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

							obj.prop('disabled', false);

							//obj.closest('tr').find('select[name=tax_id]').prop('disabled', true);

							

							if(params != 1)
							{
								obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock], input[name=amount], input[name=base_price], input[name=new_base_price], input[name=tax_amount],input[name=tax_total], select[name=tax_id], select[name=discount_id], input[name=discount_value]').val("");

								obj.closest('tr').find('select, input, textarea').prop('disabled', true);

								(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

								obj.prop('disabled', false);

								var box_total = $('input[name=total]').val();

								if(box_total <= 0.00){
									$('.sub_total, .box_tax_amount, .total').text("0.00");
								}else{
									table();
								}
							}

							//$('.crud_table').find('.sub_total, .box_tax_amount, .total').val("");

							//table();
						
						}
						else if(service_batch_id != null && transaction_module != 'inventory' && item_batch_id == null)
						{
							/*var select = obj.closest('tr').find('select[name=tax_id]');

							if(select.data('select2')) { 
								select.select2("destroy"); 
							}*/ 						

							obj.closest('tr').find('.item_batch').show();

							obj.closest('tr').find('.item_batch').attr("data-id",id);

							obj.closest('tr').find('select, input, textarea').prop('disabled', false);

							(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

							obj.prop('disabled', false);

							//obj.closest('tr').find('select[name=tax_id]').prop('disabled', true);							

							if(params != 1)
							{
								obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock], input[name=amount], input[name=base_price], input[name=new_base_price], input[name=tax_amount],input[name=tax_total], select[name=tax_id], select[name=discount_id], input[name=discount_value]').val("");

								obj.closest('tr').find('select, input, textarea').prop('disabled', true);

								(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

								obj.prop('disabled', false);

								var box_total = $('input[name=total]').val();

								if(box_total <= 0.00){
									$('.sub_total, .box_tax_amount, .total').text("0.00");
								}else{
									table();
								}
							}

							//$('.crud_table').find('.sub_total, .box_tax_amount, .total').val("");

							//table();
						
						}
						else
						{
							obj.closest('tr').find('input[name=batch_id]').val(data.single_batch_id);

							obj.closest('tr').find('select, input, textarea').prop('disabled', false);

							(!obj.closest('tr').hasClass('items')) ? obj.closest('tr').addClass('items') : '';

							obj.closest('tr').find('#item_batch').hide();

							obj.prop('disabled', false);

							if(over_all_discount != null)
							{
								obj.closest('tr').find('td > input[name=discount_value]').val(over_all_discount);
							}
								

								//alert(data.in_stock);
							@if($type == 'purchases' || $type == 'purchase_order')
								if(obj.closest('tr').find('input[name=quantity]').val() == "") {
								obj.closest('tr').find('input[name=quantity]').val(data.moq);
							}
							@else
								if(obj.closest('tr').find('input[name=quantity]').val() == "") {
								obj.closest('tr').find('input[name=quantity]').val(1);
								
							}
							@endif	
							
							@if($type == 'purchases' || $type == 'purchase_order'  || $type == 'goods_receipt_note')
								obj.closest('tr').find('input[name=rate]').val(data.purchase_price);

							@else

							if(modules == 'trade_wms')
							{
								if(segment_price == null){
									obj.closest('tr').find('input[name=rate]').val(data.base_price);
								}
								else{
									obj.closest('tr').find('input[name=rate]').val(data.segment_price);
								}
							}
							
							else{

								obj.closest('tr').find('input[name=rate]').val(data.base_price);
							}	


							@endif

							obj.closest('tr').find('td > input[name=in_stock]').val(data.in_stock).prop('disabled', true);

							/*if(modules == 'inventory'){

								obj.closest('tr').find('td > input[name=base_price]').val(data.base_price).prop('disabled', true);
								obj.closest('tr').find('input[name=new_base_price]').val(data.base_price);

							}else{
								obj.closest('tr').find('td > input[name=base_price]').val(data.price).prop('disabled', true);
							}*/
							
							
							obj.closest('tr').find('td > select[name=tax_id]').val(data.tax_id).prop('disabled', false);

							obj.closest('tr').find('td > select[name=tax_id]').trigger('change');				

							
							/*if(parseInt(data.in_stock) <= 0 )
							{
								obj.closest('tr').find('td > select[name=job_item_status]').val(3);
								obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');
							}else{
								obj.closest('tr').find('td > select[name=job_item_status]').val(1);
								obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');
							}*/
								
						}
					},
						error:function(jqXHR, textStatus, errorThrown) {}
					});
				}
				else {
					obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock]').val("");				

					table();					
				}
				
			}

		});


	$('.transactionform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				people_id: {
					required: true
				},
				invoice_date: {
					required: true
				},
				ledger_id: {
					required: true
				},
				make_id: {
					required: true
				},
				vehicle_model_id: {
					required: true
				},
				voucher_term_id: {
					required: true
				},
				due_date: {
					required: true
				},
				jobcard_status_id: {
				required: true
				},
				

				/*
				order_id: {
					remote: {
						url: "{{ route('get_transaction_order') }}",
						type: "post",
						data: {
						 _token : '{{ csrf_token() }}',
						 type: $("select[name=order_type]").val()
						}
					}
				}*/
			},

			messages: {
				people_id: {
					required: "Customer is required"
				},
				invoice_date: {
					required: "Invoice date is required"
				},
				ledger_id: {
					required: "Sales account is required"
				},
				make_id: {
					required:  "Make is required"
				},
				vehicle_model_id: {
					required:  "Model is required"
				},

				voucher_term_id: {
					required: "Vocher Term is required"
				},
				due_date: {
					required: "Due Date is required"
				},
				jobcard_status_id: {
				required: "Jobcard Status is required"
				},			
				/*
				order_id: {
					remote: "Order ID does not exist"
				}*/
			},

			invalidHandler: function(event, validator) { //display error alert on form submit   
				$('.alert-danger', $('.login-form')).show();
			},

			highlight: function(element) { // hightlight error inputs
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function(label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},

			submitHandler: function(form) {

				$('.tab_save_btn').attr("disabled", true);
				$('.tab_save_close_btn').attr("disabled", true);
				$('.tab_approve_btn').attr("disabled", true);
				
			$.ajax({

				url: @if(!empty($transactions)) 
				 "{{ route('transaction.update') }}",
				 @else
				 "{{ route('transaction.store', $transaction_type->name) }}",
				 @endif

				 type: 'post',

				data: {
					
					_token: '{{ csrf_token() }}',
					@if(!empty($transactions))
					_method:  'PATCH',
					id: '{{ $transactions->id }}',
					@endif

					@if($transaction_type->module == 'trade_wms')

						/*Add Item or Job and Parts*/

						item_id: $('.append_table').find('input[name=append_item_id]').map(function() { 
							return this.value; 
						}).get(),

						batch_id: $('.append_table').find('input[name=batch_id]').map(function() { 
								return this.value;
							}).get(),

						parent_item_id: $('.append_table').find('input[name=parent_id]').map(function() { 
								return this.value; 
							}).get(),

						description: $('.append_table').find('textarea[name=description]').map(function() { 
							return this.value; 
						}).get(),

						duration : $('.append_table').find('input[name=duration]').map(function() { 
							return this.value; 
						}).get(),

						quantity: $('.append_table').find('input[name=quantity]').map(function() { 
							return this.value; 
						}).get(),

						rate: $('.append_table').find('input[name=rate]').map(function() { 
							return this.value; 
						}).get(),

						amount: $('.append_table').find('input[name=amount]').map(function() { 
							return this.value; 
						}).get(),

						new_selling_price: $('.append_table').find('input[name=new_base_price]').map(function() { 
								return this.value; 
							}).get(),

						tax_id: $('.append_table').find('select[name=tax_id]').map(function() { 
							return this.value; 
						}).get(),

						discount_id: $('.append_table').find('select[name=discount_id]').map(function() { 
							return this.value; 
						}).get(),

						discount_value: $('.append_table').find('input[name=discount_value]').map(function() { 
							return this.value; 
						}).get(),

						assigned_employee_id: $('.append_table').find('select[name=assigned_employee_id]').map(function() { 
								return this.value; 
							}).get(),

						start_time: $('.append_table').find('input[name=start_time]').map(function() { 
								return this.value; 
							}).get(),

						end_time: $('.append_table').find('input[name=end_time]').map(function() { 
								return this.value; 
							}).get(),

						job_item_status: $('.append_table').find('select[name=job_item_status]').map(function() { 
								return this.value; 
							}).get(),						

						/* End */
					@endif	


					@if($transaction_type->module != 'trade_wms')

						item_id: $('select[name=item_id]').map(function() { 
							return this.value; 
						}).get(),

						batch_id: $('input[name=batch_id]').map(function() { 
								return this.value;
							}).get(),

						parent_item_id: $('input[name=parent_id]').map(function() { 
								return this.value; 
							}).get(),
						description: $('textarea[name=description]').map(function() { 
							return this.value; 
						}).get(),
						quantity: $('input[name=quantity]').map(function() { 
							return this.value; 
						}).get(),
						rate: $('input[name=rate]').map(function() { 
							return this.value; 
						}).get(),
						amount: $('input[name=amount]').map(function() { 
							return this.value; 
						}).get(),

						new_selling_price: $('input[name=new_base_price]').map(function() { 
								return this.value; 
							}).get(),
						
						selling_price: $('input[name=base_price]').map(function() { 
							return this.value; 
						}).get(),

						tax_id: $('select[name=tax_id]').map(function() { 
							return this.value; 
						}).get(),
						discount_id: $('select[name=discount_id]').map(function() { 
							return this.value; 
						}).get(),
						discount_value: $('input[name=discount_value]').map(function() { 
							return this.value; 
						}).get(),


						assigned_employee_id: $('select[name=assigned_employee_id]').map(function() { 
								return this.value; 
							}).get(),

						start_time: $('input[name=start_time]').map(function() { 
								return this.value; 
							}).get(),

						end_time: $('input[name=end_time]').map(function() { 
								return this.value; 
							}).get(),

						job_item_status: $('select[name=job_item_status]').map(function() { 
								return this.value; 
							}).get(),


					@endif
					tax_type: $('select[name=tax_types]').val(),
					complaints: $('textarea[name=complaint]').val(),
					driver : $('input[name=driver]').val(),
					driver_contact : $('input[name=driver_contact]').val(),
					reference_id: $('input[name=reference_id]').val(),
					type: '{{ $transaction_type->name }}',
					order_id: $('input[name=order_id]').val(),
					approval_status: $('select[name=approval_status]').val(),
					people_type: $('input[name=customer]:checked').val(),
					people_id: $('select[name=people_id]:not([disabled])').val(),
					invoice_date: $('input[name=invoice_date]').val(),
					due_date: $('input[name=due_date]').val(),
					term_id: $('select[name=voucher_term_id]').val(),
					payment_method_id: $('select[name=payment_method_id]').val(),
					ledger_id: $('select[name=ledger_id]').val(),
					employee_id: $('select[name=employee_id]').val(),
					update_customer_info : $('input[name=update_customer_info]').val(),
					name: $('input[name=customer_name]').val(),
					mobile: $('input[name=customer_mobile]').val(),
					email: $('input[name=customer_email]').val(),
					gst: $('input[name=customer_gst]').val(),
					registration_no: $('select[name=registration_number]').val(),
					address: ($('textarea[name=customer_address]').val()).replace('\n', '<br>'),
					billing: $('input[name=billing_checkbox]:checked').val(),
					billing_name: $('input[name=billing_name]').val(),
					billing_mobile: $('input[name=billing_mobile]').val(),
					billing_email: $('input[name=billing_email]').val(),
					billing_gst: $('input[name=billing_gst]').val(),
					billing_address: ($('textarea[name=billing_address]').val()).replace('\n', '<br>'),
						job_date: $('input[name=job_date]').val(),
						job_due_date: $('input[name=job_due_date]').val(),
						job_completed_date: $('input[name=job_completed_date]').val(),	
						jobcard_status_id: $('input[name=jobcard_status_id]').val(),
						payment_terms: $('select[name=payment_terms]').val(),
						service_type: $('select[name=service_type]').val(),
					
						engine_no: $('input[name=engine_number]').val(),
						chasis_no: $('input[name=chassis_number]').val(),
					
						delivery_details: $('input[name=delivery_details]').val(),
						job_due_date: $('input[name=job_due_date]').val(),
						vehicle_last_visit: $('input[name=last_visit]').val(),
						vehicle_last_job: $('input[name=last_job_card]').val(),
						vehicle_next_visit: $('input[name=next_visit_date]').val(),
						vehicle_mileage: $('input[name=vehicle_mileage]').val(),
						next_visit_mileage: $('input[name=next_visit_mileage]').val(),
						vehicle_next_visit_reason: $('input[name=next_visit_reason]').val(),
						vehicle_note: $('textarea[name=vehicle_note]').val(),
					shipping: $('input[name=shipping_checkbox]:checked').val(),
					shipping_name: $('input[name=shipping_name]').val(),
					shipping_mobile: $('input[name=shipping_mobile]').val(),
					shipping_email: $('input[name=shipping_email]').val(),
					shipping_address: ($('textarea[name=shipping_address]').val()).replace('\n', '<br>'),
					shipment_mode_id: $('select[name=shipment_mode_id]').val(),
					shipping_date: $('input[name=shipping_date]').val(),
					discount: $('input[name=discount]').val(),
					discount_is_percent: $('input[name=discount_is_percent]:checked').val(),
					stock_update: $('input[name=stock_update]:checked').val(),
					over_all_discount : $('input[name=new_discount_value]').val(),
					advance_amount: $('input[name=advance_text]').val(),
					field_name: $('input[name=field]').map(function() { 
						return $(this).data('name'); 
					}).get(),
					field_type: $('input[name=field]').map(function() { 
						return $(this).data('type'); 
					}).get(),
					field_format: $('input[name=field]').map(function() { 
						return $(this).data('format'); 
					}).get(),
					check_type: $('input[name=field]').map(function() { 
						return $(this).data('status'); 
					}).get(),
					field_value: $('input[name=field]').map(function() { 
						return this.value; 
					}).get(),
				
					make_recurring: $('input[name=make_recurring]:checked').val(),
					approve: approve,
					// WMS
					
	                	wms_reading_factor_id: $('input[name=wms_reading_factor_id]').map(function() { 
							return this.value; 
						}).get(),
						reading_values: $('input[name=reading_values]').map(function() { 
							return this.value; 
						}).get(),
					
						reading_notes: $('input[name=reading_notes]').map(function() { 
							return this.value; 
						}).get(),
							wms_reading_id: $('input[name=wms_reading_id]').map(function() { 
							return this.value; 
						}).get(),

							wms_checklist_id:$('input[name=wms_checklist_id]').map(function() { 
							return this.value; 
						}).get(),
						checklist_id:$('input[name=checklist_id]').map(function() { 
							return this.value; 
						}).get(),
						checklist_status:$('input[name=wms_checklist_status]').map(function() { 
						//	return this.value; 
						
						return $(this).is(":checked") ? 1 : 0;
							//return $(this).attr("checked") ? 1 : 0;;
						}).get(),
						checklist_notes:$('input[name=wms_checklist_notes]').map(function() { 
							return this.value; 
						}).get(),

						// END WMS

					sms: sms,
					print: print,
					approve: approve,
					send_po: send_po,
					update_goods: tab_update_goods_btn
					},
					
					beforeSend:function() {
						$('.loader_wall_onspot').show();
						//$('.tab_save_btn').attr("disabled", true);
						//$('.tab_save_close_btn').attr("disabled", true);
					},
				 	dataType: "json",
					success:function(data, textStatus, jqXHR) {	

						if(transaction_id == null)
						{
							$('.tab_approve_btn').attr("disabled", true);
							$('.tab_save_btn').attr("disabled", true);
							$('.tab_save_close_btn').attr("disabled", true);
						}

						if(transaction_id == null)
						{
							$('.tab_approve_btn').attr("disabled", false);
							$('.tab_save_btn').attr("disabled", false);
							$('.tab_save_close_btn').attr("disabled", false);
						}	

						if(tab_save_close == true && jc_save_close == true)
						{
							$('.loader_wall_onspot').hide();
							location.assign("{{route('home_page.index')}}");

							$('.alert-success').text('Transaction Updated Successfully..!');
							$('.alert-success').show();

							setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							return false;
						}else if(jc_store_only == true){
							$('.loader_wall_onspot').hide();

							$('.alert-success').text('Transaction Updated Successfully..!');
								$('.alert-success').show();
								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
								return false;
						}

						if(data.status == "0") {							
							$('.loader_wall_onspot').hide();
							$('.alert-danger').text(data.message);
							$('.alert-danger').show();

							setTimeout(function() { $('.alert').fadeOut(); }, 3000);
						} 
						else if(data.status == "2") {

							$('.loader_wall_onspot').hide();

							$('#error_dialog #title').text('Alert..!');
							$('#error_dialog #message').html("<b>There is a problem in system action/response. Please do again the work. If problem continues, please contact Propelsoft Support</b>");
							$('#error_dialog').modal('show');
						}
						else {
						var customer = ($('select[name=people_id]:not([disabled]) option:selected').val() == "") ? '' : $('select[name=people_id]:not([disabled]) option:selected').text();

						var selected_text = "Pending";
						var selected_class = "badge-warning";

						var approval_text = "Draft";
						var approval_class = "badge-warning";

						if(data.data.status == 0) {
							selected_text = "Pending";
							selected_class = "badge-warning";
						} else if(data.data.status == 1) {
							selected_text = "Paid";
							selected_class = "badge-success";
						} else if(data.data.status == 2) {
							selected_text = "Partially Paid";
							selected_class = "badge-info";
						} else if(data.data.status == 3) {
							selected_text = "Over due " + data.data.due + " days";
							selected_class = "badge-danger";
						}

						if(data.data.approval_status == 0) {
							approval_text = "Draft";
							approval_class = "badge-warning";
						} else if(data.data.approval_status == 1) {
							approval_text = "Approved";
							approval_class = "badge-success";
						}
						var approve_selected = "";
						var draft_selected = "";


						var html = "";

							html +=`<tr>
								<td style="padding-left: 7px;">
									<input id="`+data.data.id+`" class="item_checkbox" name="transaction" value="`+data.data.id+`" type="checkbox">
									<label for="`+data.data.id+`"><span></span></label>
								</td>
								<td>
								<a class="po_edit" data-id="`+data.data.id+`">`+data.data.order_no+`</a>
								</td>

								

								@if($transaction_type->name == "purchases" || $transaction_type->name == "estimation" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || 
									$transaction_type->name == "sales_cash")
									<td>`+data.data.reference_type+`</td> @endif

								@if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
									<td>`+data.data.jc_order_no+`</td>
								@endif

								@if($transaction_type->name == "purchases" || $transaction_type->name == "estimation" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || 
									$transaction_type->name == "sales_cash" ||
									$transaction_type->name == "goods_receipt_note" ||
									$transaction_type->name == "delivery_note" ||
									$transaction_type->name == "job_invoice" || $transaction_type->name == "job_request" || 
									$transaction_type->name == "job_invoice_cash")
									<td>`+data.data.reference_no+`</td> @endif

									@if($transaction_type->name == "job_card" || $transaction_type->name == "job_request" || $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
									<td>`+data.data.registration_id+`</td> @endif

									@if($transaction_type->name == "job_request")
										<td>`+data.data.service_type+`</td> @endif	
									
									<td>`+data.data.people+`</td>
									
									@if($transaction_type->module == "trade" || $transaction_type->module == "inventory")
									<td>`+data.data.people_contact+`</td> @endif

									@if($transaction_type->name == "job_card")
									<td>`+data.data.assigned_to+`</td> @endif

									<td>`+data.data.total+`</td>

									@if($transaction_type->name == "job_card" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
							
								<td>`+data.data.advance_amount+`</td> 
								@endif


									@if($transaction_type->name == "job_card" || $transaction_type->name == "job_request" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
									<td>`+data.data.job_date+`</td> @endif

									@if($transaction_type->module == "trade" || $transaction_type->module == "inventory")
									<td>`+data.data.date+`</td> @endif `;


								if(/*data.data.transaction_type == "purchases" || 
									data.data.transaction_type == "sales" || 
									data.data.transaction_type == "sales_cash" || 
									data.data.transaction_type == "delivery_note" || 
									data.data.transaction_type == "credit_note" || 
									data.data.transaction_type == "debit_note" || */
									data.data.transaction_type == "sale_order" )
									{
										html +=`<td>`+data.data.due_date+`</td>`;
									}
/*
									if(data.data.transaction_type == "job_card" ||
									data.data.transaction_type == "job_invoice")
									{
										html +=`<td>`+data.data.job_due_date+`</td>`;
									}*/

									if(data.data.transaction_type == "estimation" || data.data.transaction_type == "purchase_order" )
									{
										html +=`<td>`+data.data.shipping_date+`</td>`;
									}

									

								if(data.data.transaction_type == "sales" || 
										data.data.transaction_type == "purchases" || 
										data.data.transaction_type == "sales_cash" || 
										data.data.transaction_type == "job_invoice" || data.data.transaction_type == "job_invoice_cash" ) 
									{

										if(data.data.transaction_type == "sales_cash" || data.data.transaction_type == "job_invoice"|| data.data.transaction_type == "job_invoice_cash" ) {
											html +=`<td> 0.00 </td>`;
										} else {
											html +=`<td>`+data.data.balance+`</td>`;	
										}
										
					 
										html += `<td>
											<label class="grid_label badge `+selected_class+`">`+selected_text+`</label>
											</td>`;
									}

								if(data.data.transaction_type == "job_card")
								{
									html +=`<td>`+data.data.jobcard_status+`</td>`;
								}
							
								if(data.data.transaction_type != "job_card")
								{
									if(data.data.approval_status == 1) {
										approve_selected = "selected";
										$('.tab_approve_btn').text("Approved");
										$('.approval_status').show();
										$('.tab_save_close_btn').hide();
										$('.tab_save_btn').hide();
										$('.tab_delete_btn').hide();
									} else if(data.data.approval_status == 0) {
										draft_selected = "selected";
										$('.approval_status').hide();
									}

									html +=`<td>
										<label class="grid_label badge `+approval_class+` status">`+approval_text+`</label>
										</td>`;
								}
								if(data.data.transaction_type == "job_card")
									{
										html+=`<td>
												<div class="action_options">
												</div>
												<button type="button" class="btn btn-info" id="job_card_actions"  ><span class="fa fa-caret-left"></span>&nbsp;Action</button>
												</td>`;
									}
									if(data.data.transaction_type == "job_request" )
									{
										html+=`<td><div class="action_options">
										</div>
										<button type="button" class="btn btn-info" id="job_request_actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button></td>`;
									}
									if(data.data.transaction_type == "job_invoice" || data.data.transaction_type == "job_invoice_cash")
									{
										html+=`<td><div class="action_options">
									</div>
									<button type="button" class="btn btn-info" id="job_invoice_actions"><span class="fa fa-caret-left"></span>&nbsp;Action</button></td>`;
									}
								if(data.data.transaction_type == "purchase_order"

										|| data.data.transaction_type == "purchases" || data.data.transaction_type == "goods_receipt_note"
										|| data.data.transaction_type == "debit_note" || data.data.transaction_type == "estimation"|| data.data.transaction_type == "sale_order"|| data.data.transaction_type == "sales" || data.data.transaction_type == "sales_cash"|| data.data.transaction_type == "delivery_note" || data.data.transaction_type == "credit_note")
								{
									html+=`<td>
										<div class="action_options">
										</div>
										<button type="button" class="btn btn-info" id="actions"><span class="fa fa-caret-down"></span>&nbsp;Action</button>
								</td>`;
								}
								
							html+=`</tr>`;	

						@if(!empty($transactions))
						call_back(html, `edit`, data.message, data.data.id);
						@else
						call_back(html, `add`, data.message);
						@endif

						

						//This code is used to close the page
						if(tab_save_close == true) {
							$('.close_full_modal').trigger('click');
						}
						
						$('.loader_wall_onspot').hide();
						$('.tab_save_btn').attr("disabled", false);
						$('.tab_save_close_btn').attr("disabled", false);
						$('.tab_approve_btn').attr("disabled", false);

						/*if(print == 1) {
							print_transaction('{{$id}}');
						}*/


						sms = 0;
						print = 0;
						approve = 0;
						send_po = 0;
						tab_update_goods_btn = 0;
					}
				},
				error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
				}
				});
			}
	});


	function print_transaction(id,data) {
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('print_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id,
						data: data,
					},
					success:function(data, textStatus, jqXHR){

						console.log("print transactions");
						console.log(data);
                    

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
							container.find("[data-value='estimate_no']").text(data.estimate_no);
							container.find("[data-value='shipping_address']").text(data.shipping_address);
							container.find("[data-value='billing_address']").text(data.billing_address);
							container.find("[data-value='billing_name']").text(data.billing_name);

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
                              container.find("[data-value='customer_communication_gst']").text(data.customer_communication_gst);
                             container.find("[data-value='billing_communication_gst']").text(data.billing_communication_gst);
                             container.find("[data-value='customer_gst']").text(data.customer_gst);
                             container.find("[data-value='customer_mobile']").text(data.customer_mobile);
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

								
								var total_cgst = parseFloat(tax_amount)+parseFloat(total_cgst);
								 

								var total_sgst = parseFloat(tax_amount)+parseFloat(total_sgst);


								
						}else{
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

							

                    var  total_tax = total_cgst + total_sgst + total_igst +total_amount;
                    var round_of = Math.ceil(total_tax);
                    var Rount_off_value = round_of - total_tax;
                    var total = total_tax + total_amount;
                    var total_amount= Rount_off_value + total_tax;

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
								new_row.find('.col_rate').text(parseFloat(data.no_tax_sale[i].rate).toFixed(2));
								new_row.find('.col_discount').text(data.no_tax_sale[i].discount);
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
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

							var row = container.find('.no_tax_sales_table tbody tr').clone();

							var no_tax_estimation = ``;
							var total_sale_amount = 0.00;
							for (var i = 0; i < (data.no_tax_estimation).length; i++) {
								var j = i + 1;
								var sales_new_row = row.clone();
								var tax_amount = data.no_tax_estimation[i].tax_amount;
								if(tax_amount == null){
									tax_amount = 0.00;
								}
								var unit_price = data.no_tax_estimation[i].rate;
								var quantity = data.no_tax_estimation[i].quantity;
								var price = parseFloat(tax_amount) + parseFloat(unit_price);
								var amount = parseFloat(quantity) * parseFloat(unit_price);
								var total_amount = parseFloat(amount) + parseFloat(tax_amount);
								
								
								sales_new_row.find('.col_id').text(j);
								sales_new_row.find('.col_desc').text(data.no_tax_estimation[i].name);
								sales_new_row.find('.col_quantity').text(data.no_tax_estimation[i].quantity);
								sales_new_row.find('.col_rate').text(parseFloat(price).toFixed(2));
								sales_new_row.find('.col_discount').text(data.no_tax_estimation[i].discount);
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
								sales_new_row.find('.col_amount').text(parseFloat(total_amount).toFixed(2));
								var total_sale_amount = parseFloat(total_amount) + parseFloat(total_sale_amount);
								no_tax_estimation += `<tr>`+sales_new_row.html()+`</tr>`;
							}

							container.find('.sales_total_amount').text(parseFloat(total_sale_amount).toFixed(2));

							container.find('.no_tax_sales_table tbody').empty();
							container.find('.no_tax_sales_table tbody').append(no_tax_estimation);


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
								new_row.find('.col_tax').text(data.invoice_items[i].tax);
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




	// ****  Getting datas of vehicles based on license number  *****

	$('select[name=registration_number]').on('change', function(event) {
		
		var id = $('select[name=registration_number]').val();
	
		$.ajax({
			url: "{{ route('get_vehicle_datas') }}",  // VehicleVariantController
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id: id,
			},
			dataType: "json",
			success:function(data, textStatus, jqXHR) {
                 
				if(data.data.user_type == 0) {
					$('#people_type').prop('checked', true);
					$('#business_type').prop('checked', false);
					$('.people').show();
					$('.business').hide();
					$('.business select[name=people_id]').prop('disabled', true);
					$('.people select[name=people_id]').prop('disabled', false);
					trigger_people = $('.people select[name=people_id]');
				}
				else if(data.data.user_type == 1) {
					$('#business_type').prop('checked', true);
					$('#people_type').prop('checked', false);
					$('.people').hide();
					$('.business').show();
					$('.business select[name=people_id]').prop('disabled', false);
					$('.people select[name=people_id]').prop('disabled', true);
					trigger_people = $('.business select[name=people_id]');
				}

							
						
				$("#registration").val(data.data.registration_no);
				$("#engine_no").val(data.data.engine_no);

				//$("#business_id").val(data.data.owner_id);
				//$("#person_id").val(data.data.owner_id);
				$('select[name=people_id]').val(data.data.owner_id);
				$("#chassis_no").val(data.data.chassis_no);
				$("#purchase_date").val(data.data.manufacturing_year);
				$("#drivetrain").val(data.data.vehicle_drivetrain_id);
				$("#fuel_type").val(data.data.fuel_type_id);
				$("#vehicle_category").val(data.data.vehicle_category_id);
				$("#no_of_wheels").val(data.data.vehicle_wheel_type_id);
				$("#vehicle_make").val(data.data.vehicle_make_id);
				$("#rim_wheel").val(data.data.vehicle_rim_type_id);
				$("#vehicle_model").val(data.data.vehicle_model_id);
				$("#tyre_size").val(data.data.vehicle_tyre_size_id);
				$("#vehicle_variant").val(data.data.vehicle_variant_id);
				$("#body_type").val(data.data.vehicle_body_type_id);
				$("#vehicle_usage").val(data.data.vehicle_usage_id);
				$("#vehicle_version").val(data.data.vehicle_version);
				$("#last_update_date").val(data.data.last_update_date);
				$("#last_update_jc").val(data.data.last_update_jc);
				$("#driver").val(data.data.driver);
				$("#driver_contact").val(data.data.driver_contact);
				$('#customer_gst').val(data.data.gst);
				$('#billing_gst').val(data.data.gst);
				if(data.data.additional_contacts == " "){
						$('#additional_contacts').css('display','none');
					}else{
						$('#additional_contacts').css('display','block');
						$('#additional_contacts').on('click',function(){

						 var contact_length = data.data.array_contact.length;
						 var contacts ='';
						 for (var i =0; i < contact_length; ++i) {
						 	contacts += '<div class="row"><div class="col-md-6"><div class="form-group  col-md-12"><div class="row "><label for="last_job_card" class="control-label">Contact Name</label><input type = "text" name="contact_name" value="'+data.data.array_contact[i]+'" class="form-control"></div></div></div><div class="col-md-6"><div class="form-group col-md-12"><div class="row"><label for="last_visit" class="control-label">contact Number</label><input type = "text" name="contact_number" value="'+data.data.array_mobile[i]+'" class="form-control"></div></div></div></div>';
						 }

						 $('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Contacts:</h4></div><div class="modal-body">'+contacts+'</div><div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">Close</button></div>');
						 	$('.crud_modal').find('.modal-dialog').removeClass('modal-lg');
						 	$('.crud_modal').modal('show');


						 });
					}
				if(data.data.vehicle_permit_type == "1"){
					$("#permit_type").val("Yes");
				}else{
					$("#permit_type").val("No");
				}
				
				$("#fc_due").val(data.data.fc_due);
				$("#permit_due").val(data.data.permit_due);
				$("#tax_due").val(data.data.tax_due);
				$("#vehicle_insurance").val(data.data.vehicle_insurance);
				$("#insurance_due").val(data.data.vehicle_insurance_due);
				if(data.data.bank_loan == "1"){
					$("#bank_loan").val("Yes");
				}else{
					$("#bank_loan").val("No");
				}
				if(data.data.group_name != null)
				{
					$("#group_name_show").val(data.data.group_name.name);
				}
				else
				{
					$("#group_name_show").val("");

				}
				$("#month_due_date").val(data.data.month_due_date);
				$("#warranty_km").val(data.data.warranty_km);
				$("#warrenty_yrs").val(data.data.warranty_yrs);
	 			
				$("#engine_no, #chassis_no, #purchase_date, #drivetrain, #fuel_type, #vehicle_category, #no_of_wheels, #vehicle_make, #rim_wheel, #vehicle_model, #tyre_size, #vehicle_variant, #body_type, #vehicle_usage, #vehicle_version,#last_update_date,#last_update_jc,#driver,#permit_type,#tax_due,#vehicle_insurance,#bank_loan,#month_due_date,#warranty_km,#warrenty_yrs,#group_name_show,#specification").trigger('change');
			

				$(trigger_people).trigger('change');

				

			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
	});

	// ****  End of getting datas of vehicles based on license number  *****

	/*Check object value exist or not*/

	/* Edit values */
	
	function order(id, type) {

		var obj = $('select[name=item_id]');
		var vehicle_id = $('select[name=registration_number]').val();
		var transaction_module =  ('{{ $transaction_type->module }}');			

			$.ajax({
				url: "{{ route('get_order_details') }}",
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					order_id: id,
					type: type					
				 },
				success:function(data, textStatus, jqXHR) {	


					var transactions = data.response;
					var transaction_items = data.data;
					var item_batch = data.item_batch;
					var service_batch = data.service_batch;

					var trigger_triggered = false;

					if(trigger_triggered == false)
	        		{
	            		$('select[name=people_id]').trigger('click');
	            		trigger_triggered = true; //set it executed here
	        		}
	        		else{
	        			return false;
	        		}				

				 	/*$('.transactionform input:not(input[type=button]):not(input[type=submit]):not(input[type=reset]):not(input[name=_token]):not(input[type=radio]):not(input[type=checkbox])').val("");*/

				 	/*$('.transactionform select:not([name=order_type]):not([name=tax_types]):not([name=employee_id])').val("");*/

				 	$('.transactionform select:not([name=order_type]):not([name=tax_types]):not("#state")').trigger('change');
				 	
				 	//$('select[name=order_type]').val(type);
					 $('select[name=tax_types]').val(data.response.tax_type);
					 
				 	$('select[name=approval_status]').val(data.response.approval_status);

					 $('select[name=tax_types], select[name=approval_status]').trigger('change');
					 
					 $('input[name=order_id]').val(data.response.reference_no);				 	
									

					if(data.response.user_type == 0) {
						$('#people_type').prop('checked', true);
						$('#business_type').prop('checked', false);
						$('.people').show();
						$('.business').hide();
						$('.business select[name=people_id]').prop('disabled', true);
						$('.people select[name=people_id]').prop('disabled', false);
					}
					else if(data.response.user_type == 1) {
						$('#business_type').prop('checked', true);
						$('#people_type').prop('checked', false);
						$('.people').hide();
						$('.business').show();
						$('.business select[name=people_id]').prop('disabled', false);
						$('.people select[name=people_id]').prop('disabled', true);
					}

					$('select[name=registration_number]').val(data.response.registration_id);
					//$('select[name=registration_number]').trigger('change');

					$('input[name=advance_text]').val(data.response.advance_amount);

					
					
					$('select[name=people_id]').val(data.response.people_id);
					$('select[name=people_id]').trigger('change');
					$('select[name=voucher_term_id]').val(data.response.term_id);
					$('input[name=reference_id]').val(data.response.reference_id);
					//$('select[name=voucher_term_id]').trigger('change'); 
					@if(!empty($transactions))
					$('input[name=invoice_date]').val(data.response.date);
					$('input[name=due_date]').val(data.response.due_date);
					$('textarea[name=compliant]').val(data.response.vehicle_complaints);
					$('textarea[name=complaints]').val(data.response.vehicle_complaints);
					
					@endif

					setTimeout(function() {

						/*@if($transactions->approval_status == "1") 

						$('input, select, textarea').each(function() {
							$(this).prop('disabled', 'true');
						});

						 @endif*/

						 $('select[name=registration_number]').val(data.response.registration_id);	
						
						$('input[name=customer_name]').val(data.response.name);
						$('input[name=customer_mobile]').val(data.response.mobile);
						$('input[name=customer_email]').val(data.response.email);
						$('textarea[name=customer_address]').val( (data.response.address != null) ? (data.response.address).replace("<br>", "\n") : "");


						$('input[name=billing_name]').val(data.response.billing_name);
						$('input[name=billing_mobile]').val(data.response.billing_mobile);
						$('input[name=billing_email]').val(data.response.billing_email);
						$('textarea[name=billing_address]').val( (data.response.billing_address != null) ? (data.response.billing_address).replace("<br>", "\n") : "");


						$('input[name=shipping_name]').val(data.response.shipping_name);
						$('input[name=shipping_mobile]').val(data.response.shipping_mobile);
						$('input[name=shipping_email]').val(data.response.shipping_email);
						$('textarea[name=shipping_address]').val( (data.response.shipping_address != null) ? (data.response.shipping_address).replace("<br>", "\n") : "");

						for(var i in transaction_items)
						{
							$('input[name=new_discount_value]').val(transaction_items[i].percentage);
						}


					}, 500, data);


					$('select[name=payment_method_id]').val(data.response.payment_mode_id);
					$('select[name=payment_method_id]').trigger('change');
					$('select[name=employee_id]').val(data.response.employee_id);
					$('select[name=employee_id]').trigger('change');

					$('textarea[name=billing_address]').val(data.response.billing_address);

					$(".billing, .shipping").hide();
					
					if(data.response.billing_address != null) {
						$('input[name=billing_checkbox]').prop('checked', true);
						$(".billing").show();
					}

					if(data.response.shipping_address != null) {
						$('input[name=shipping_checkbox]').prop('checked', true);
						$(".shipping").show();
					}

					$('textarea[name=shipping_address]').val(data.response.shipping_address);
					$('select[name=shipment_mode_id]').val(data.response.shipment_mode_id);
					$('select[name=shipment_mode_id]').trigger('change');
					$('input[name=shipping_date]').val(data.response.shipping_date);

					$('select[name=tax_types]').val(data.response.tax_type);

					$('.select_item').each(function() { 
						var select = $(this);  
						if(select.data('select2')) { 
							select.select2("destroy"); 
						} 
					});


				if(transaction_module != 'trade_wms'){

					var clone = $(".crud_table tbody > tr ");

					clone.find('select[name=item_id], select[name=tax_id], select[name=discount_id], input[name=quantity], input[name=rate], input[name=amount]').val("");

					clone.find('select > optgroup > span >  option').unwrap();

					$(".crud_table tbody tr").remove();

					var index_number = 1;
					var item_array = [];
					

					for(var i in transaction_items) {

						var transaction_item = clone.clone();

						if(transaction_items[i].stock_update == 1) {

							$('body').find('input[name=stock_update]').prop('checked', true);
						}

						/* get tr length using for batch item */	
		
						var row_index = $('.crud_table tbody > tr').length;					
					
		           	 	var New_data_row = row_index+1; 
            
		    			transaction_item.closest('tr').attr("id","tr_"+New_data_row);
		    			transaction_item.closest('tr').attr("data-row",New_data_row);

		    			/*end*/
                
                		/*batch sign ( service also )*/

						if(item_batch.filter(obj => obj.item_id == transaction_items[i].item_id).length>0)
						{
							transaction_item.find('.item_batch').show();

							transaction_item.find('.item_batch').attr("data-id",transaction_items[i].item_id);
						}

						if(service_batch.filter(obj => obj.inventory_item_id == transaction_items[i].item_id).length>0)
						{
							transaction_item.find('.item_batch').show();

							transaction_item.find('.item_batch').attr("data-id",transaction_items[i].item_id);
						}

						/*end*/						

						item_array.push(transaction_items[i].item_id);			

						// new line added

						transaction_item.find('.index_number').text(index_number + parseInt(i));

						//

						transaction_item.find('textarea[name=description]').val(transaction_items[i].description);

						transaction_item.find('input[name=in_stock]').val(transaction_items[i].batch_stock);

						transaction_item.find('input[name=batch_id]').val(transaction_items[i].batch_id);

						@if($type == 'purchases' || $type == 'purchase_order'  || $type == 'goods_receipt_note')

							transaction_item.find('input[name=base_price]').val(data.selling_price[i]);

							transaction_item.find('input[name=new_base_price]').val(data.new_selling_price[i]);
						@else

							transaction_item.find('input[name=base_price]').val(data.base_price[i]);
						@endif						

						transaction_item.find('select[name=item_id]').val(transaction_items[i].item_id);

						transaction_item.find('input[name=rate]').val(transaction_items[i].rate);
						
						transaction_item.find('input[name=amount]').val(transaction_items[i].amount);
						transaction_item.find('select[name=tax_id]').val(transaction_items[i].tax_id);
						transaction_item.find('select[name=discount_id]').val(transaction_items[i].discount_id);
						transaction_item.find('input[name=discount_value]').val(transaction_items[i].discount_value);

						transaction_item.find('select[name=assigned_employee_id]').val(transaction_items[i].assigned_employee_id);

						transaction_item.find('input[name=start_time]').val(transaction_items[i].start_time);

						transaction_item.find('input[name=end_time]').val(transaction_items[i].end_time);

						transaction_item.find('select[name=job_item_status]').val(transaction_items[i].job_item_status);



						if(parseInt(transaction_items[i].quantity) > parseInt(transaction_items[i].batch_stock))
						{
							transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity);
							/*transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#FF0000');*/
							//transaction_item.find('select[name=job_item_status]').val(3);
							transaction_item.find('select[name=job_item_status]').trigger('change');
							
						}else{
							transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#000000');
							//transaction_item.find('select[name=job_item_status]').val(1);
							transaction_item.find('select[name=job_item_status]').trigger('change');			
						}						

						transaction_item.find('select[name=item_id] > optgroup > span > option[value="' + transaction_items[i].item_id + '"]').unwrap();

						transaction_item.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');

						
						

						 if(transaction_items[i].parent_item_id == null){
							transaction_item.find('input[name=parent_id]').val(transaction_items[i].parent_item_id);
							//transaction_item.find('.index_number').remove();
							transaction_item.find('.remove_row').remove();
						}

						$(".crud_table tbody").append(transaction_item);


					}
						

					var row_index = $('.crud_table tbody > tr').length;

					if(row_index > 1) {

						$('.crud_table').find('tr').find('td:last').html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');

						$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

					} else {

						$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
					}

				}


				if(transaction_module == 'trade_wms'){

					var clone = $(".crud_table tbody > tr ");

					clone.find('td:last').html('<a class="grid_label action-btn delete-icon remove_row_append"><i class="fa fa-trash-o"></i></a>');

					clone.find('.datetimepicker2').datetimepicker({
						rtl: false,
						orientation: "left",
						todayHighlight: true,
						autoclose: true
					});

					clone.find('select[name=item_id], select[name=tax_id], select[name=discount_id], input[name=quantity], input[name=rate], input[name=amount]').val("");
					
					var index_number = 1;
					var item_array = [];				
					
					if(transaction_items[0] != ""){

						for(var i in transaction_items)
						{
							
							var transaction_item = clone.clone();

							transaction_item.find('.datetimepicker2').datetimepicker({
							rtl: false,
							orientation: "left",
							todayHighlight: true,
							autoclose: true
							});				

							/* get tr length using for batch item */
			
							var row_index = $('.append_table tbody > tr').length;	
							
							var New_data_row = row_index+1;

							transaction_item.find( "td:eq(0) > span" ).text(New_data_row);

							transaction_item.find( "td:eq(0) > span" ).removeClass('index_number');

							transaction_item.find( "td:eq(0) > span" ).addClass('index_number_append'); 


							transaction_item.find( "td:eq(1)" ).html('<input type="hidden" name="append_item_id" value="'+ transaction_items[i].item_id +'"  class="form-control"><input type="hidden" name="batch_id" value="'+transaction_items[i].batch_id+'" ><input type="text" name="append_item" class ="form-control" disabled="ture" style="width:200px;float: left;" value="'+transaction_items[i].item_name+'">');
	            
			    			
	                
							if(item_batch.filter(obj => obj.item_id == transaction_items[i].item_id).length>0)
							{
								//transaction_item.find('.item_batch').show();
								transaction_item.find('.item_batch').attr("data-id",transaction_items[i].item_id);
							}

							if(service_batch.filter(obj => obj.inventory_item_id == transaction_items[i].item_id).length>0)
							{
								//transaction_item.find('.item_batch').show();

								transaction_item.find('.item_batch').attr("data-id",transaction_items[i].item_id);
							}

							item_array.push(transaction_items[i].item_id);						

							transaction_item.find('textarea[name=description]').val(transaction_items[i].description);

							transaction_item.find('input[name=duration]').val(transaction_items[i].duration);

							transaction_item.find('input[name=in_stock]').val(transaction_items[i].batch_stock);

							transaction_item.find('input[name=new_discount_value]').val(transaction_items[i].percentage);


							@if($type == 'purchases' || $type == 'purchase_order'  || $type == 'goods_receipt_note')

								transaction_item.find('input[name=base_price]').val(data.selling_price[i]);

								transaction_item.find('input[name=new_base_price]').val(data.new_selling_price[i]);
							@else
								transaction_item.find('input[name=base_price]').val(data.base_price[i]);
							@endif


							transaction_item.find('input[name=rate]').val(transaction_items[i].rate);
							
							transaction_item.find('input[name=amount]').val(transaction_items[i].amount);

							transaction_item.find('select[name=tax_id]').val(transaction_items[i].tax_id);

							transaction_item.find('select[name=discount_id]').val(transaction_items[i].discount_id);

							transaction_item.find('input[name=discount_value]').val(transaction_items[i].discount_value);

							transaction_item.find('select[name=assigned_employee_id]').val(transaction_items[i].assigned_employee_id);

							transaction_item.find('input[name=start_time]').val(transaction_items[i].start_time);

							transaction_item.find('input[name=end_time]').val(transaction_items[i].end_time);

							transaction_item.find('select[name=job_item_status]').val(transaction_items[i].job_item_status);

							if(parseInt(transaction_items[i].quantity) > parseInt(transaction_items[i].batch_stock))
							{
								transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity);
								/*transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#FF0000');*/
								//transaction_item.find('select[name=job_item_status]').val(3);
								transaction_item.find('select[name=job_item_status]').trigger('change');
								
							}else{
								transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#000000');
								//transaction_item.find('select[name=job_item_status]').val(1);
								transaction_item.find('select[name=job_item_status]').trigger('change');			
							}

							$(".append_table tbody").append(transaction_item);

							//Temp code after JC refactoring. This will trigger to re-price the object
							transaction_item.find('input[name=rate]').blur();
						}
						
					}
						

					var row_index = $('.crud_table tbody > tr').length;

					$('.append_table').find('tr').find('td:last').html('<a class="grid_label action-btn delete-icon remove_row_append"><i class="fa fa-trash-o"></i></a>');

					$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row_append"><i class="fa fa-plus"></i></a>');


				}		
						
				$('.select_item').select2();

				table();

				@if($transactions->approval_status == "1") 

					$('.transactionform').find('input, select, textarea').each(function() {
						$(this).prop('disabled', 'true');
					});

				@endif	

				},
				error:function(jqXHR, textStatus, errorThrown) {}
			});

	}


	/* Get Edit Values - item details only*/

	function table() {
		var amount = 0.00;		
		var discount_amount = 0.00;
		var tax_amount = 0.00;
		var tax = 0.00;
		var sub_total = 0.00;
		var discount = $('input[name=discount]').val();
		var discount_html;
		var sum_discount = parseFloat(0.00);
		var sum_tax = parseFloat(0.00);
		var discount_transactions = parseFloat(0.00);

		$('body').find('.items').find('input[name=amount], select[name=tax_id], select[name=discount_id], input[name=discount_value]').each(function() {

			if($(this).attr('name') == 'amount') {
				
				amount += parseFloat(($(this).val()) ? $(this).val():0);				
			}

			else if($(this).attr('name') == 'tax_id') {
				var tax_value = $(this).find('option:selected').data('value');
				tax_amount += parseFloat( isNaN(tax_value) ? 0 : tax_value/100 ) * ($(this).closest('tr').find('input[name=amount]').val());
			}

			else if($(this).attr('name') == 'discount_id' || $(this).attr('name') == 'discount_value') {
				var discount_value = $(this).closest('tr').find('input[name=amount]').val();				

				var discount_name = ($(this).find('option:selected').val() != "") ?  $(this).find('option:selected').text() : "";

				discount_amount += parseFloat(( isNaN(discount_value) ? 0 : discount_value)/100)*($(this).closest('tr').find('input[name=amount]').val());

			}

			/*else if($(this).attr('name') == 'discount_value') {
				var discount_value = $(this).val();
				var discount_name = "null";
				discount_amount += parseFloat(( isNaN(discount_value) ? 0 : discount_value)/100)*($(this).closest('tr').find('input[name=amount]').val());	
			}*/

		});

		$('.total_rows').find('tr.discount_row').remove();
		sum_discount = parseFloat(0.00);
		var discount_name_array = [];
		var discount_value_array = [];
		var discount_amount_array = [];
		var discount_item_amount = [];

		$('body').find('.items').find('input[name=discount_value]').each(function() {
			if($(this).val() != "") {
				var discount_value = $(this).val();
				var discount_name = ($(this).closest('tr').find(' select[name=discount_id]').val() != "") ? $(this).closest('tr').find(' select[name=discount_id] option:selected').text() : discount_value;
				var item_amount = parseFloat($(this).closest('tr').find('input[name=amount]').val());
				var total_discount = parseFloat(( isNaN(discount_value) ? 0 : discount_value)/100)*(item_amount);

				if(!discount_name_array.includes(discount_name)) {
					discount_name_array.push(discount_name);
					discount_value_array.push(discount_value);
					discount_amount_array.push(total_discount);
					discount_item_amount.push(item_amount);
				} else {
					var index = discount_name_array.indexOf(discount_name);
					discount_amount_array[index] = parseFloat(discount_amount_array[index]) + parseFloat(total_discount);
					discount_item_amount[index] = parseFloat(discount_item_amount[index]) + parseFloat(item_amount);
				}
			}
		});

		for(var discount in discount_name_array) {
			sum_discount += parseFloat(discount_amount_array[discount]);
			discount_html += `<tr class="discount_row">
			<td><h6 style="float:right; text-align:right; font-size:14px; font-weight:bold; ">`+discount_name_array[discount]+` @`+discount_value_array[discount]+`% on `+discount_item_amount[discount]+`</h6></td>
			<td></td>
			<td><h6 style="float:right; text-align:right; width: 150px;"> -`+

			parseFloat(discount_amount_array[discount]).toFixed(2)+`</h6></td>
			</tr>`;
		}

		$('.total_rows').find('tr').last().prev().after(discount_html);
		



		$('.total_rows').find('tr.tax_row').remove();
		var tax_name_array = [];
		var tax_value_array = [];
		var tax_amount_array = [];
		var tax_item_amount = [];

		

		$('body').find('.items').find('select[name=tax_id]').each(function() {
			var obj = $(this);
			/* if tax is null for separate item */
			
			var amount_element2 = ((obj.closest('tr').find('input[name=amount]').val()).isNaN) ? 0 : obj.closest('tr').find('input[name=amount]').val();
			
		
			/* end */

			var data = obj.find('option:selected').data('tax');		
			var tax_value1 = $(this).find('option:selected').data('value');
			
			/* tax Include */
			
			//var	tax_amount1 = parseFloat(isNaN(tax_value1) ? 0 : tax_value1/100 ) * ($(this).closest('tr').find('input[name=amount]').val());
			
			var amount_element = ((obj.closest('tr').find('input[name=amount]').val()).isNaN) ? 0 : obj.closest('tr').find('input[name=amount]').val();
			
			

			var single_discount = parseFloat(obj.closest('tr').find('input[name=discount_value]').val());

			var single_item_discount = parseFloat(( isNaN(single_discount) ? 0 : single_discount)/100)*(amount_element);

			var single_item_amount = (parseFloat(amount_element) - parseFloat(single_item_discount).toFixed(2));

			var single_total_tax = parseFloat(( isNaN(tax_value1) ? 0 : tax_value1)/100)*(single_item_amount);

			

			if(obj.val() != "") {	

				/*$.ajax({
					url: "{{ route('get_tax') }}",
					type: 'post',
					data: {
					_token: '{{ csrf_token() }}',
					id: obj.val()
				 },
				success:function(data, textStatus, jqXHR) {*/		

			

			for(var i in data) {

				var tax_type = $('select[name=tax_types]').val();				
				
				@if($type == 'purchase_order' || $type == 'purchases' || $type == 'debit_note')
					var tax_included = obj.closest('tr').find('select[name=item_id] option:selected').data('purchase_tax');
				@elseif($type == 'sale_order' || $type == 'sales' || $type == 'sales_cash' || $type == 'delivery_note')
					var tax_included = obj.closest('tr').find('select[name=item_id] option:selected').data('tax');
				@endif

				var tax_value = data[i].value;
				var tax_name = data[i].name;

				var item_amount = 0;
				var total_tax = 0;

				

				if(tax_type == 1) { //include 					
					
					/*total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(item_amount);*/
					
					item_amount = (parseFloat(amount_element)).toFixed(2);

					base_price = (parseFloat(amount_element)/((obj.find('option:selected').data('value')/100)+1)).toFixed(2);

					total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(base_price);
					
				} else if(tax_type == 2) {  //exclude

					//item_amount = (parseFloat(amount_element)).toFixed(2);
					//total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(item_amount);

					item_amount = (parseFloat(amount_element) - parseFloat(single_item_discount).toFixed(2));

					total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(item_amount);

				} else if(tax_type == 0) { //no
					item_amount = parseFloat(amount_element);
					total_tax = 0;
				}

				if(!tax_name_array.includes(tax_name)) {
					tax_name_array.push(tax_name);
					tax_value_array.push(tax_value);
					tax_amount_array.push(total_tax);
					tax_item_amount.push(item_amount);
				} else {
					var index = tax_name_array.indexOf(tax_name);
					tax_amount_array[index] = parseFloat(tax_amount_array[index]) +  parseFloat(total_tax);
					tax_item_amount[index] = parseFloat(tax_item_amount[index]) + parseFloat(item_amount);
				}
			}

			var tax_html;
			sum_tax = parseFloat(0.00);
			$('.total_rows').find('tr.tax_row').remove();

			for(var tax in tax_name_array) {

				tax_html += `<tr class="tax_row"> <td><h6 style="float:right; text-align:right; font-size:14px; font-weight:bold; ">`;

				if(tax_type == 1) {
					tax_html += `Includes `;
				}
				tax_html += tax_name_array[tax]+` @`+tax_value_array[tax]+`% on `+tax_item_amount[tax]+`</h6></td> <td></td> <td><h6 style="float:right; text-align:right; width: 150px;">`+(tax_amount_array[tax]).toFixed(2)+`</h6></td> </tr>`;
				if(tax_type != 1) {	   
					sum_tax += parseFloat(tax_amount_array[tax]);
				}
			}

			$('.total_rows').find('tr').last().prev().after(tax_html);
			
               
				//$('.total').text( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2) );
				
				//$('input[name=wms_total]').val( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2) );


				/* },
				 error:function(jqXHR, textStatus, errorThrown) {

				 }
				});*/
			}
			else{  /* if tax is null for separate item */

				var taxtotal = obj.closest('tr').find('input[name=tax_total]');
				
				var single_item_tax_toal = (parseFloat(single_item_amount)  + parseFloat(single_total_tax));		

				taxtotal.val((parseFloat(isNaN(single_item_tax_toal) ? 0.00 :  parseFloat(single_item_tax_toal) )).toFixed(2));
			}

			//single item tax amount - row

				var taxamount = obj.closest('tr').find('input[name=tax_amount]');

				taxamount.val( (parseFloat(isNaN(single_total_tax) ?0.00 : parseFloat(single_total_tax) )).toFixed(2));

			//end

			//single item tax with total - row

				var taxtotal = obj.closest('tr').find('input[name=tax_total]');				

				var single_item_tax_toal = (parseFloat(single_item_amount)  + parseFloat(single_total_tax));

				taxtotal.val( (parseFloat(isNaN(single_item_tax_toal) ?0.00 : parseFloat(single_item_tax_toal) )).toFixed(2));

			//end
		});



		var advance_html;

		$('.total_rows').find('tr.advance_row').remove();

		var advance = $('input[name=advance_text]').val();
		

		// start to adavance amount
			var ad = parseFloat((advance == '') ? 0.00 : advance).toFixed(2);
			
			$('.total_rows').find('.advance_value').text(ad);
		//end

		/*if(advance != null && advance != 0){
			advance_val = $('.advance_value').text((parseFloat(advance)).toFixed(2));
			
		}else{
			advance_val = $('.advance_value').text(parseFloat(0.00));
		}*/

		advance_html += `<tr class="advance_row">
			<td><h6 style="float:right; text-align:right; font-size:14px; font-weight:bold; ">`+'Advance'+`</h6></td>
			<td></td>
			<td><h6 style="float:right; text-align:right; width: 150px;"> `+

			parseFloat((advance == '') ? 0.00 : advance).toFixed(2)+`</h6></td>
			</tr>`;

		$('.total_rows').find('tr').last().prev().after(advance_html);

		/* Box - discount */

		if(discount != null) {

			if($('input[name=discount_is_percent]').is(':checked')) {

			discount_transactions = parseFloat((discount/100)*sub_total);
			}
			else {
				discount_transactions = parseFloat(discount); 
			}
		}

		$('.discount').text((discount_transactions != "" && discount_transactions != 0) ? "- "+ parseFloat(discount_transactions).toFixed(2) : 0.00);

			//start to total discount for trade

			var total_discount = $('.total_rows').find('input[name=sum_discount]');	

			total_discount.val((parseFloat(isNaN(sum_discount) ? 0.00 :  parseFloat(sum_discount) )).toFixed(2));

			$('.box_tax_discount').text( (parseFloat(isNaN(sum_discount) ? 0.00 : parseFloat(sum_discount) )).toFixed(2) );

			//end

		/* End */

		/*start trade and trade-wms tax amount - box*/

			var tax_amount_box = $('.total_rows').find('input[name=tax_amount]');

			tax_amount_box.val( (parseFloat(isNaN(sum_tax) ? 0.00 : parseFloat(sum_tax) )).toFixed(2));

			$('.box_tax_amount').text( (parseFloat(isNaN(sum_tax) ? 0.00 : parseFloat(sum_tax) )).toFixed(2) );	

		/* End */				



		/* subtotal and total - Box*/
		
		sub_total = (amount).toFixed(2);

		$('.sub_total').text(sub_total);

		$('input[name=total]').val(sub_total - sum_discount);

		$('.total').text( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2) );

		$('input[name=wms_total]').val( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2) );

		/*End*/


		//$('.total').text( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) - (isNaN($('input[name=advance_text]').val()) ? 0.00 : $('input[name=advance_text]').val()) ).toFixed(2) );

		//$('input[name=wms_total]').val( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) - (isNaN($('input[name=advance_text]').val()) ? 0.00 : $('input[name=advance_text]').val()) ).toFixed(2) );

		//var advance_html;
		//var advance = $('input[name=advance_text]').val();		

		//$('.total_rows').find('tr.tax_row').remove();		

		//$('.advance_value').text((advance != "" && advance != 0) ? "- "+parseFloat(advance).toFixed(2) : 0.00);

		/*$('.advance_value').text( (parseFloat(isNaN($('input[name=advance_text]').val()) ? 0.00 : $('input[name=advance_text]').val())).toFixed(2) );*/		

		
		//start
		//to get credit limit-total						
		$tot = $('.sub_total').text();
		
		//$credit = $('.credit_limit_value').val();
		$credit = $('input[name=credit_limit_text]').val();
		
		$credit_limit_total = $credit-$tot;
		
		 $('.credit_limit_value').text(parseFloat($credit_limit_total).toFixed(2));

		//end

	}

	function creditLimit() {
		if(parseFloat($('.total').text()) > $('input[name=credit_limit_text]').val()) {
			$('.credit_limit h6').css('color', '#FF0000');	

					/*$('.alert').fadeIn('');
					$('.alert').html('Cross the Credit Limit!!!');

					setTimeout(function() {
						$('.alert').fadeOut('');
					}, 5000);*/

		}else{
			$('.credit_limit h6').css('color', '#000000');
		}
	}


		
		var transaction_id = $('input[name=transaction_id]').val();
			
		var BeforeAttachments = new Dropzone("div#before_image", {		
			
				method: 'POST',
				paramName: 'file',
				params:{
						_token:'{{ csrf_token() }}',
						attachment_uid:'',
						transaction_id : $('input[name=transaction_id]').val(),
						image_category:1

				},
				url: "{{ url('/transaction/attachments/wms_attachment') }}",
				dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",
				clickable: true,
				maxFilesize: 10, // MB
				parallelUploads: 10,
				// thumbnailWidth: null,
				//thumbnailHeight: null
				acceptedFiles: "image/*",
				maxFiles: 10,
				uploadMultiple: true,
				autoProcessQueue: false,
				addRemoveLinks: true,
				removedfile: function (file) {
					// 
					file.previewElement.remove();
					// $(this).remove();
				},
				queuecomplete: function () {
					//	BeforeAttachments.removeAllFiles();
				}, 
				uploadprogress: function(file, progress, bytesSent) {
				
					var elem = document.getElementById("BeforePBar");
					elem.style.width = progress + '%';			
					
					
					var value = progress;
					
					if(progress == 100)
					{
					$('#BeforePBar').hide();

					 }
			 	},
			 
		});
		
		
		var ProgressAttachments = new Dropzone("div#progress_image", {
			
			
			method: 'POST',
			paramName: 'file',
			params:{
						_token:'{{ csrf_token() }}',
						attachment_uid:'',
						transaction_id : $('input[name=transaction_id]').val(),
						image_category:2

					},
			url: "{{ url('/transaction/attachments/wms_attachment') }}",
			dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",
			clickable: true,
			maxFilesize: 10, // MB
			parallelUploads: 10,
			// thumbnailWidth: null,
			//thumbnailHeight: null
			acceptedFiles: "image/*",
			maxFiles: 10,
			uploadMultiple: true,
			autoProcessQueue: false,
			addRemoveLinks: true,
			removedfile: function (file) {
				// 
				file.previewElement.remove();
				// $(this).remove();
			},
			queuecomplete: function () {
				//ProgressAttachments.removeAllFiles();
			}, 
			uploadprogress: function(file, progress, bytesSent) {
			
			var elem = document.getElementById("ProgressPBar");
			elem.style.width = progress + '%';
			
			
			
			var value = progress;
			
			if(progress == 100)
			{
			$('#ProgressPBar').hide();

			 }
		 },
		 
		});

	
		var AfterAttachments = new Dropzone("div#after_image", {			
				
				method: 'POST',
				paramName: 'file',
				params:{
						_token:'{{ csrf_token() }}',
						attachment_uid:'',
						transaction_id : $('input[name=transaction_id]').val(),
						image_category:3

						},
				url: "{{ url('/transaction/attachments/wms_attachment') }}",
				dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",
				clickable: true,
				maxFilesize: 10, // MB
				parallelUploads: 10,
				// thumbnailWidth: null,
				//thumbnailHeight: null
				acceptedFiles: "image/*",
				maxFiles: 10,
				uploadMultiple: true,
				autoProcessQueue: false,
				addRemoveLinks: true,
				removedfile: function (file) {
					// 
					file.previewElement.remove();
					// $(this).remove();
				},
				queuecomplete: function () {
					//AfterAttachments.removeAllFiles();
				}, 
				uploadprogress: function(file, progress, bytesSent) {
				
				var elem = document.getElementById("AfterPBar");
				elem.style.width = progress + '%';
				
				
				
				var value = progress;
				
				if(progress == 100)
				{
				$('#AfterPBar').hide();

				 }
			 },
			 
		});


		$('#SaveBeforeImg').click(function(e){  
			e.preventDefault(); 
			$('#BeforePBar').show();

			BeforeAttachments.processQueue();
		});

		$('#SaveProgressImg').click(function(e){  
			e.preventDefault(); 
			$('#ProgressPBar').show();
			ProgressAttachments.processQueue();
		});

		$('#SaveAfterImg').click(function(e){  
			e.preventDefault(); 
			$('#AfterPBar').show();
			AfterAttachments.processQueue();
		});	

		$('body').on('click', '.img_del', function(){
					console.log((this));
			})

		$('body').on('click','.img-wrap .close', function(e) {
			var img_content= $(this).closest('.img-wrap');
    		var img_id = $(this).closest('.img-wrap').find('img').data('id');
				console.log(img_id);
					e.preventDefault();
					$('.delete_modal_ajax').modal('show');
					$('.delete_modal_ajax_btn').off().on('click', function() {
						$.ajax({
							 url: "{{ url('transaction/delete_attachment') }}",
							 type: 'post',
							 data: {
								_method: 'delete',
								_token : '{{ csrf_token() }}',
								id: img_id,
								},
							 dataType: "json",
							 beforeSend: function() {
							 	$('.loader_wall_onspot').hide();
							 },
							 success:function(data, textStatus, jqXHR) {

								//$('.close_full_modal').trigger('click');
								//$('.loader_wall_onspot').hide();
								$('.delete_modal_ajax').modal('hide');
								$('.alert-success').text(data.message);
								$('.alert-success').show();
								img_content.remove();
								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							 },
							 error:function(jqXHR, textStatus, errorThrown) {
								}
							});
					});
		});
		$('body').on('click', '.vechile_history', function(){
			$.get("{{  url('inventory/vechile_history') }}/"+$(this).data('id'), function(data) {
				$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');		
		});
		$('body').on('click', '.po_edit', function () {
			var id = $(this).data('id');
	        var vehicle_id = $(this).data('vehicle_id');   
	          $.get("{{ url('transaction_link') }}/"+id+"/popup", function(data) {
			  $('.crud_modal .modal-container').html("");
			  $('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
		});

</script> 

<script>
	
		ClassicEditor.create( document.querySelector( '#editor' ),{
		removePlugins: [ 'Heading', 'Link' ,'bold', 'italic','blockQuote','bulletedList' ],
			toolbar: [  ]
		}
				
		);
</script>


{{--
@stop
--}}