{{--

@extends('layouts.master')
@section('content')
@include('includes.add_user')
@include('includes.add_business')

--}}

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/transaction.css') }}">
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
 /* padding: 4px;*/
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
  /*padding: 4px;*/
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



<div class="content"> 

  <!-- <div class="modal-header"> -->

  <div class="fill header">

	<h3 class="float-left voucher_name"> 

	@if(!empty($transactions))

	  {{ $transaction_type->display_name }}# {{ $transactions->order_no }}

	  @else

	  {{$transaction_type->display_name}}# {{$voucher_no}}

	@endif

	</h3>

	<!-- <div style="cursor: pointer;" class="float-left voucher_code"><i style="font-size: 20px; color: #b73c3c; padding-top: 5px; padding-left: 5px;" class="fa icon-basic-gear"></i></div> -->

	<div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i> </div>

	<!-- <div class="float-right side_panel"><i style="font-size: 25px" class="fa icon-basic-gear"></i></div> --> 

  </div>

  <!-- </div> -->

  <div class="clearfix"></div>

  {!! Form::open(['class' => 'form-horizontal transactionform']) !!}

  {{ csrf_field() }} 

  <!--   <div class="modal-body"> --> 

	<div class="alert alert-success">
		{{ Session::get('flash_message') }}
	</div>

	<div class="alert alert-danger">
		{{ Session::get('flash_message') }}
	</div>



	<!-- @if($errors->any())

		<div class="alert alert-danger">

			@foreach($errors->all() as $error)

				<p>{{ $error }}</p>

			@endforeach

		</div>

	@endif -->



	<div class="form-body" style="padding: 5px 20px 50px; margin-top: 2px; ">

		<ul class="nav nav-tabs">

			@if($transaction_type->module == 'fuel_station')
			  <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#invoice_details">Invoice Detail</a> </li>
			

			@elseif($transaction_type->module == 'trade_wms')

				<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#order_details">Job Details</a> </li>

				<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link item_details"data-toggle="tab" href="#item_details" >Job & Parts</a> </li>
				<!-- @if($transaction_type->name == 'job_card')

				<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link item_details"data-toggle="tab" href="#item_details_test" >Test</a> </li>
				
				@endif -->

				@if($transaction_type->name == 'job_card')

					<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#attachments">Attachments</a> </li>

					<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#readings">Readings</a> </li>    

					<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#checklist">Checklist</a> </li> 

				@endif
			@elseif($transaction_type->module != 'trade_wms')

					<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#order_details">Order Details</a> </li>

				 <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#item_details">Item Details</a> </li>

			@endif

		</ul>


		<div class="tab-content">

		@if($transaction_type->module == 'fuel_station')

			<div class="tab-pane active" id="invoice_details">              
				<div class="row" >
					<div class="form-group col-md-3" style="margin-left:4px">
						<label class="control-label required" for="order_id">Type</label> <br>
						<div class="custom-panel"  style="background-color: #e9ecef">&nbsp;
							<input id="cash_type" type="radio" name="job_sale_type" value="cash" @if($transaction_type->name == "job_invoice_cash" ) checked="checked" @endif />
							<label for="cash_type" class="custom-panel-radio"><span></span>Cash</label>&nbsp;&nbsp;&nbsp;

							<input id="credit_type" type="radio" name="job_sale_type"  value="credit"  @if($transaction_type->name == "job_invoice" ) checked="checked" @endif/>
							<label for="credit_type"><span></span>Credit</label>
						</div>
					</div>                      
					<div class="col-md-3">
						<div class="form-group">    
							<div class="row" >                  
								<div class="col-md-10">
									<label for="registration_number" class="control-label required">Registration Number</label>
									{{ Form::select('registration_number', $vehicles_register, null, ['class' => 'form-control select_item registration_number', 'id' => 'registration_number','style' => 'background-color:yellow']) }}
								</div>
								<div class="col-md-1 col-md-offset-1" style="padding-top: 30px">
									<a href="javascript:;" id="" class="add_vehicle " ><i class="fa fa-car"></i>
									</a>
								</div>
							</div>      
						</div>
					</div>

					<div class="col-md-3" >
						<div class="row">
							<label for="vehicle_name" class="required">Make/ Modal / variant / Version</label>

							{{ Form::text('vehicle_name', null, ['class' => 'form-control', 'id' => 'vehicle_name','disabled']) }}
						</div>
					</div>  
					<div class="" style="width: 180px;margin-left: 20px">                   
						<div class="customer_type" style= " @if($customer_type_label == null) display:none @endif"> 
							{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
							<div class="custom-panel" style="background-color: #e9ecef">
								<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />

								<label for="business_type" class="custom-panel-radio"><span></span>Business</label>

								<input id="people_type" type="radio" name="customer" value="0"  />

								<label for="people_type" ><span></span>People</label>
							</div>
						</div>
					</div>  
					<div class="row" style="margin-left: 3px">
						<div class="col-md-3 search_container people " style= "@if($customer_label == null) display:none @endif">
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
						<div class="col-md-3" style="margin-left:-5px">
							<div class="form-group col-md-12">                              
								<div class="row">
									<label for="driver" class="control-label required" >Contact / Driver Name</label>

									{{ Form::text('driver', null, ['class'=>'form-control','id' => 'driver']) }}

								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group col-md-12">
								<div class="row">

									<label for="driver_contact" class="control-label required">Contact / Driver Number</label>
									{{ Form::text('driver_contact', null, ['class'=>'form-control','id' => 'driver_contact']) }}
								</div>
							</div>
						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="vehicle_mileage" class="control-label required">Vehicle Odometer Mileage</label>

								{{ Form::text('vehicle_mileage', null, ['class' => 'form-control numbers']) }}
								</div>
							</div>
						</div>
					</div>                          
					<div class="form-group">
						<div class="row" style="margin-left: 3px">
							<div class="col-md-3" style="width: 230px">
								<label class="required" for="date">Invoice Date</label>
								{{ Form::text('job_date', date('d-m-Y') , ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 

							</div>          
									
							<div class="col-md-3" style="margin-left:-4px;margin-right: 10px">

								<label for=" employee_id" class="required" >Invoiced By</label>

								{{ Form::select('employee_id', $employees, $selected_employee, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}

							</div>          
							<div class="col-md-3" style="margin-left:-11px">
								<label for="job_due_date" class="required">Payment Due Date</label>
									{{ Form::text('job_due_date',date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
							</div>
							<div class="col-md-3">
								<label for="shipping_date" class="required">Delivery On</label>

								{{ Form::text('job_completed_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

							</div>                  
						</div>
					</div>
					<div class="form-group">
					<div class="row" style="margin-left: 3px"> 
					<div class="col-md-3" class="required" >

						<label for="payment_terms" class="required" >Payment Terms</label>

							{{ Form::select('payment_terms', $payment_terms, $payment_term, ['class' => 'form-control select_item', 'id' => 'payment_terms']) }}

					</div>
									
					<div class="col-md-2" style="margin-left:-5px">
						<label for="shift_id" class="required">Shift Name</label>
						{{ Form::select('shift_id', $shift, $shift_id, ['class' => 'form-control ', 'id' => 'shift_id']) }} 
					</div>
					<div class="col-md-2">
						<label for="pump_id" class="required" >Pump Name</label>

						{{ Form::select('pump_id', $pump_name, null, ['class' => 'form-control select_item', 'id' => 'pump_id']) }} 
					</div>
					<div class="col-md-2">
						<label for="payment_method_id" class="required" >Payment Method</label>

						{{ Form::select('payment_method_id', $payment, null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }} 
					</div>                          
					<div class="col-md-3">
						<div style="font-weight: bold;color: #4b5056;">
							<label for="group_name_show">Customer Group :</label>
						</div>
						<div>                                   {{ Form::text('group_name_show',null,['class'=> 'form-control','id' =>'group_name_show','disabled']) }}
						</div>                      
					</div>
					</div>
					</div>                          
				
					<div class="row " style="margin-top: 50px;margin-left: -8px">
						<div style="float:right; width: 130px; margin: 10px;display: none;">    
							<select name="tax_types" class='form-control select_item' disabled >

								<option value="2">Exclude Tax</option>
								<option value="1">Include Tax</option>
								<option value="0">Out Of Scope</option>

							</select>
						</div>
					 <div class="col-md-12"style="">    
						<table style="border-collapse: collapse;" class="table table-bordered crud_table">
						<thead>
							<tr>
								<th width="4%">#</th>
								<th width="25%">Products </th>                  
								<th width="13%" >Disc.Type</th>     
								<th width="10%">Unit Price</th>
								<th width="8%" >Disc %</th>
								<th width="16%">Stock</th>
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

									</select> <input type="hidden" name="parent_id">
									<input type="hidden" name="batch_id">

									<div class='item_container'></div>

								</td>
								<td>

									<select name='discount_id' class='form-control select_item taxes' id = 'discount_id'>

										 <option value="">Select Discount</option>

										 @foreach($discounts as $discount) 

										 <option value="{{$discount->id}}" data-value="{{$discount->value}}">{{$discount->display_name}}</option>

										 @endforeach
									</select>

								</td>

								<td>

									{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 

									<div class='rate_container'></div>

								</td>

								<td>

									{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}

								</td>

								<td>

									{{ Form::text('in_stock', null, ['class'=>'form-control numbers', 'disabled', 'id' => 'in_stock']) }}

								</td>
								<td>

									{{ Form::text('quantity', null, ['class'=>'form-control decimal quantity','id' => 'quantity']) }}

									<div class='quantity_container'></div>
		

								</td>
								<td>

									{{ Form::text('amount', null, ['class'=>'form-control numbers']) }}

								</td>
								<td>

									<select name='tax_id' class='form-control select_item taxes' id = 'tax_id' >

									<option value="">Select Tax</option>

									@foreach($taxes as $tax) 

									<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>

									@endforeach

									</select>
									<div class='tax_container'></div> 
								</td>       

								<td>

									{{ Form::text('tax_amount', null, ['class'=>'form-control decimal']) }}
									<div class='tax_amount'></div> 

								</td>               

								<td>

									{{ Form::text('tax_total', null, ['class'=>'form-control decimal']) }}

								</td>

								<td><a style="display: none;" class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a></td>

							</tr>

							</tbody>

						</table>
					</div>  
					</div>                          
					<div class="row form-group" style="margin-top: 30px;margin-left: 260px;max-height:10px">
						<table id="new" class= "total_rows" align="right">
							<tr>
								<td id="new" >
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
					<div class="form-group custom-panel col-md-12" style="margin-top:130px;">
						<div class="row custom-panel-address">
							<div class="col-md-12 ">
								<div class="row ">
									<div class="col-md-12">
										<label><b>{{$address_label}}</b></label>

									</div>
									<div class="col-md-3">
										<label for="date">Name</label>
											{{ Form::text('customer_name',null, ['class'=>'form-control display_name', 'autocomplete' => 'off']) }} 

									</div>
									<div class="col-md-3">
										<label for="date">Mobile</label>

											{{ Form::text('customer_mobile', null, ['class'=>'form-control mobile', 'autocomplete' => 'off']) }} 

									</div>
									<div class="col-md-3">

										<label for="date">Email</label>

											{{ Form::text('customer_email', null, ['class'=>'form-control email', 'autocomplete' => 'off']) }} 

									</div>
									<div class="col-md-3">

										<label for="date">Address:</label>

											{{ Form::textarea('customer_address', null, ['class'=>'form-control address', 'style'=>' height: 30px;']) }} 

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

												<div style="@if($company_label) display: none; @endif" class="col-md-12"> {{ Form::checkbox('billing_checkbox', '1', $company_label, array('id' => 'billing_checkbox')) }}

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

										<textarea name="billing_address" class="form-control  " style="height: 30px;" >{{$company_address}}</textarea> 

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

						<div class="col-md-3" style = "@if(count($order_type_value) == 0) display:none @endif">

								<label for="order_id">Reference Type</label>

								{{ Form::select('order_type', $order_type_value, null, ['class' => 'form-control']) }}
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
					<div style= "@if($customer_type_label == null) display:none @endif">
						 	{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
						</div>
					<div style="padding-left: 12px;">

						<div class="row col-md-6 custom-panel">

							<div class="col-md-6 customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
							                              
							       <!-- {{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br> -->
							                              
							        <div class="" >
							                              
							        <input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
							                              
							     	<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
							                              
							        <input id="people_type" type="radio" name="customer" value="0" />
							                              
							         <label for="people_type" ><span></span>People</label>
							                              
							        </div>
							                              
							</div>
							                              
							                              
							                              
							<div class="col-md-6 search_container people " style= "padding: 2px;@if($customer_label == null) display:none @endif">
							                              
							   <!--  {{ Form::label('people', $customer_label, array('class' => 'control-label required')) }} -->
							                              
							    {{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id', 'disabled']) }}
							                              
							    {{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
							                              
							    {{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
							                              
							    <div class="content"></div>
							                              
							</div>
							                              
							<div class="col-md-6 search_container business" style= "padding: 2px;@if($customer_label == null) display:none @endif">
							                              
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

								<input id="cash_type" type="radio" name="sale_type" value="cash" @if($transaction_type->name == "sales_cash" ) checked="checked" @endif />

								<label for="cash_type" class="custom-panel-radio"><span></span>Cash</label>

								<input id="credit_type" type="radio" name="sale_type"  value="credit" @if($transaction_type->name == "sales") checked="checked" @endif />

								<label for="credit_type"><span></span>Credit</label>
							</div>

						</div>

						@endif                



						<div class="col-md-3" style= "@if($sales_person_label == null) display:none @endif">

							<label for="employee_id">{{$sales_person_label}}</label>

							{{ Form::select('employee_id', $employees, $selected_employee, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}
						</div>
					</div>

				</div>

				<div class="form-group">

						<div class="row">                           

							<div class="col-md-3" style= "@if($payment_label == null) display:none @endif">

								<label for="payment_mode" class="required">{{$payment_label}}</label>

								{{ Form::select('payment_method_id', $payment, null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }} 

							</div>				



							<div class="col-md-3">

							@if(($transaction_type->name == 'delivery_note') )

								<label for="date">Delivery Mode</label>

								{{ Form::select('shipment_mode_id', $shipment_mode, '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}

							@else

								<label for="date">Shippment Mode</label>

								{{ Form::select('shipment_mode_id', $shipment_mode, '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}

							@endif

							</div>
							

						</div>

				</div>

				<div class="form-group">

					<div class="row">

						<div class="col-md-3" style= "@if($date_label == null) display:none @endif">

							<label class="required" for="date">{{$date_label}}</label>

								{{ Form::text('invoice_date', ($transaction_type->date_setting == 0) ? date('d-m-Y') : null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 

						</div>



						<div class="col-md-3">

							@if( $transaction_type->name == 'delivery_note')

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

							<label for="" class="control-label required">Terms</label>

								{{ Form::select('voucher_term_id', $terms, $selected_term->id, ['class' => 'form-control select_item ', 'id' => 'voucher_term_id']) }}

						</div>

						@endif  



						@if($transaction_type->name != "sales_cash")

							<div class="col-md-3"  style= "@if($due_date_label == null) display:none @endif">   

							{{ Form::label('due_date', $due_date_label, array('class' => 'control-label required')) }}

							{{ Form::text('due_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

							</div>

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

						@if( $transaction_type->name == 'delivery_note' )

							<div class="col-md-3">

								<label for="delivery_by">Delivery By</label>

								{{ Form::text('delivery_by', null ,['class' => 'form-control']) }}

							</div>

						@endif



							@if($transaction_type->name == 'delivery_note')

								<div class="col-md-3">

									<label for="delivery_details">Delivery Details</label>

									{{ Form::text('delivery_details', null ,['class' => 'form-control number']) }} 

								</div>

							@endif  
					</div>

				</div>

				<div class="form-group">

					<div class="row">

						@if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "purchases" )

						<div class="col-md-3">

							<label><b>Update Stock</b></label>

							<input name="stock_update" type="checkbox"  id ="stock_update" checked="checked" value="1" class="form-control"><label for="stock_update"><span></span></label>

						</div>	

						@endif

					</div>
				</div>


				<br><br>

				 <div class="form-group custom-panel">

					<div class="row custom-panel-address">

						<div class="col-md-12 ">

							<div class="row ">

								<div class="col-md-3">

									<label><b>{{$address_label}}</b></label>
									<input name="update_customer_info" type="checkbox" value="" id="update_customer_info" data-toggle="tooltip" data-placement="top" title="Check to update customer master"><label for="update_customer_info"><span></span></label></input>
									{{ Form::text('customer_name',null, ['class'=>'form-control display_name', 'autocomplete' => 'off']) }}
									{{ Form::text('customer_mobile', null, ['class'=>'form-control mobile', 'autocomplete' => 'off']) }}
									{{ Form::text('customer_email', null, ['class'=>'form-control email', 'autocomplete' => 'off']) }}
									{{ Form::textarea('customer_address', null, ['class'=>'form-control address', 'style'=>' height: 30px;']) }}  

								</div>
								<div class="col-md-3">
									<label><b>Billing Communication</b></label>
									<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="billing_name" value="{{$company_name}}" autocomplete="off" />
									<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="billing_mobile" value="{{$company_mobile}}" autocomplete="off"  />
									<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  email @endif " name="billing_email" value="{{$company_email}}" autocomplete="off"  />
									<textarea name="billing_address" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  address @endif "

									style="height: 30px;" >{{$company_address}}</textarea> 
								</div>
								<div class="col-md-3">
									<label><b>Shipping communication</b></label>
									<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="shipping_name" value="{{$company_name}}" autocomplete="off"  />
									<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="shipping_mobile" value="{{$company_mobile}}" autocomplete="off"  />
									<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   email @endif " name="shipping_email" value="{{$company_email}}" autocomplete="off"  /> 
									<textarea name="shipping_address" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   address @endif " style="height: 30px;" > {{$company_address}}</textarea>
								</div>

			

							</div>



						</div>

					</div>

				
				</div>
			</div>


			<div class="tab-pane" id="item_details">

				<div class="clearfix"></div>

					<div style="float:right; width: 130px; margin: 10px;display:none"> 

						<select name="tax_types" class='form-control select_item' disabled>

						<option value="2">Exclude Tax</option>
						<option value="1">Include Tax</option>
						<option value="0">Out Of Scope</option>

						</select>

					</div>


				<div class="clearfix"></div>

				<div class="form-group" style="">

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
								<!-- <th style= "" width="10%">Base Price</th>  -->

							@endif  

							@if($transaction_type->module == 'inventory')

								@if($transaction_type->name == 'purchases' || $transaction_type->name == 'goods_receipt_note' )
									<th style= "" width="12%">New Selling Price</th>

								@endif

							@endif

							@if($transaction_type->module == 'trade')

								@if($discount_option )

									<th width="13%">Disc.Type</th>

								@endif

							@endif

							<!-- <th width="10%">Unit Price</th> -->

							@if($transaction_type->module == 'trade')
								<th width="12%">Unit Price</th>
							@endif

							@if($transaction_type->module == 'inventory')
								<th width="12%">Unit /Purchase Price</th>
							@endif

							@if($transaction_type->module == 'trade')			

								<th width="8%" style=" @if($transaction_type->name == 'sale_order' ||  $transaction_type->name == 'credit_note') display: none; @endif"> 
									Disc %
								</th>								

							@endif

							<th width="6%">Qty</th>
							<!-- <th width="10%">Unit Price</th> -->
							<th width="10%">Amount</th>
							<th width="10%">Tax %</th>  
							<th width="10%">Tax Amount</th>

							<!-- @if($transaction_type->module == 'trade')
								@if($discount_option )
									<th width="13%">Disc.Type</th>
									<th width="8%">Discount %</th>
								@endif

							@endif -->

							<th width="10%">Total</th>
							<th width="3%"></th>

						</tr>

						<tr>

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
									
									<div style="float:right;" id="jc_item_create">
										<a href="javascript:;" id="" data-toggle="tooltip" title="Add Item"  class="jc_item_create "><i class="fa fa-cube" style="padding: 2px;" aria-hidden="true"></i></a>
									</div>

									<!-- <div style="float:right; display: none;" id="item_batch" class="item_batch">

									<a href="javascript:;"><i class="fa fa-cart-plus" style="padding: 5px;" aria-hidden="true"></i></a> -->

									</div>								

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

									<div style="float:right;" id="jc_item_create">
										<a href="javascript:;" id="" data-toggle="tooltip" title="Add Item"  class="jc_item_create "><i class="fa fa-cube" style="padding: 2px;" aria-hidden="true"></i></a>
									</div>

									<div style="float:right; display: none;" id="item_batch" class="item_batch ">

										<a href="javascript:;"><i class="fa fa-cart-plus" style="padding: 5px;" aria-hidden="true"></i></a>

									</div>								

								</td>

							@endif

								<td>
									{{ Form::textarea('description', null, ['class'=>'form-control', 'style'=>' height: 26px;' , 'placeholder' => 'Description']) }}
								</td>

								@if($transaction_type->module == 'inventory')

								<td style= "">

								 	{{ Form::text('base_price', null, ['class'=>'form-control decimal','disabled']) }}

								</td>

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

								<!-- <td>
								
									{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
								
									<div class='rate_container'></div>
								
								</td> -->
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

									<select name='tax_id' class='form-control select_item taxes' id = 'tax_id' >

										<option value="">Select Tax</option>

										@foreach($taxes as $tax) 

											<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>

										@endforeach

									</select>						

								</td>

								<td>

									{{ Form::text('tax_amount', null, ['class'=>'form-control decimal']) }}

								</td>

								<div class='tax_amount'></div> 

							

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

									<td><a style="display: none;" class="grid_label action-btn delete-icon remove_row"><i class="fa fa-minus"></i></a> <a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a></td>

							</tr>

						</tbody>

					</table>


					<table id="new" align="right">

						<tr>

							<td id="new" >

								<div id="design">

								<div id="left" ><h6>Sub Total :</h6></div>

								<div id="right"><h6 class="sub_total" >0.00</h6>

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

									<!-- <div id="left"><h6>Tax Amount :</h6></div>

									<div id="right">{{ Form::text('tax_amount', null, ['class'=>'form-control decimal box_tax_amount','style'=>'color:blue;font-size:15px;background-color:transparent;border:0;padding-top:1px;width:90px;','disabled']) }}

									</div> -->

								</div>

								

							</td>

						

							<td id="new" >

								<div id="design">

									<div id="left"><h6>Total Amount :</h6></div>

									<div id="right">
									<h6 class= "total">0.00</h6>
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

						{!! Form::select('interval', ['0'=>'Daily','1'=>'Weekly','2'=>'Monthly'], null, ['class' => 'form-control select_item']); !!} </div>

			

					<div class="col-md-2 month" style="display: none;">

						<label style="position: absolute; left: -5px; top: 30px;">On</label>

						<label class="control-label">&nbsp;</label>

						{!! Form::label('period', 'Period', ['class' => 'control-label']) !!}           

						{!! Form::select('period', ['' => 'Day','1'=>'First','2'=>'Second','3'=>'Third','4'=>'Fourth','0'=>'Last'], null, ['class' => 'form-control select_item']); !!} </div>

			

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

						{!! Form::select('end', ['0' => 'None','1'=>'By','2'=>'After'], 0, ['class' => 'form-control select_item']); !!}

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

			@if( $transaction_type->name == 'job_card'|| $transaction_type->name == 'job_request' || $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')         

				<div class="tab-pane active" id="order_details">

					<div class="clearfix"></div>

					<div class="row">

						<div class="col-md-3">

							<div class="form-group">    

								<div class="row">           

									<div class="col-md-10">

										<label for="registration_number" class="control-label required">Registration Number</label>

										{{ Form::select('registration_number', $vehicles_register, null, ['class' => 'form-control select_item', 'id' => 'registration_number','style' => 'background-color:yellow']) }}

									</div>

									<div class="col-md-2" style="padding-top: 26px; padding-left: 3px;">

										<a href="javascript:;" id="" data-toggle="tooltip" title="Add Vehicle " class="add_vehicle " ><i class="fa fa-car"></i></a>

									</div>

								</div>      

							</div>

						</div>
					@if($transaction_type->name == 'job_card')
						<div class="col-md-9">



						<a href="#" class="job_card_status_change chevron_active" data-id="1" data-toggle="tooltip" data-placement="top" title="New">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top:3px;z-index: 100;color: white;font-weight: bold;font-size: 10px;">New</span>
						 </div>
						 </a>
						<a href="#" class="job_card_status_change chevron" data-id="2" data-toggle="tooltip" data-placement="top" title="First Inspected">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 8px;">First Inspected</span>
						 </div>
						 </a>
						<a href="#" class="job_card_status_change chevron" data-id="3" data-toggle="tooltip" data-placement="top" title="Estimation Pending">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 7px;">Estimation Pending</span>
						 </div>
						 </a>
						<a href="#" class="job_card_status_change chevron" data-id="4" data-toggle="tooltip" data-placement="top" title="Estimation Approved">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 7px;">Estimation Approved</span>
						 </div>
						 </a>
						<a href="#" class="job_card_status_change chevron" data-id="5" data-toggle="tooltip" data-placement="top" title="Work in Progress">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 8px;">Work in Progress</span>
						 </div>
						 </a>
						<a href="#" class="job_card_status_change chevron" data-id="6" data-toggle="tooltip" data-placement="top" title="Final Inspected">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 8px;">Final Inspected</span>
						 </div>
						</a>
						<a href="#" class="job_card_status_change chevron" data-id="7" data-toggle="tooltip" data-placement="top" title="Vehicle Ready">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 10px;">Vehicle Ready</span>
						 </div>
						</a>
						<a href="#" class="job_card_status_change chevron" data-id="8" data-toggle="tooltip" data-placement="top" title="Closed">
						 <div class="" id="che">
						 	
						 	<span style="position: relative;top: 3px;z-index: 100;color: white;font-weight: bold;font-size: 10px;">Closed</span>
						 </div>
						</a>
						</div>

					
						
						 
						
							<input name="jobcard_status_id" class="jobcard_status_id" type="hidden" value="1"></input>
				
						
						@endif

						<div class="col-md-3">

					<!--	@if($transaction_type->name == 'job_card')

							<div class="form-group col-md-12">

								<div class="row">

								<label for="date" class="required">Job Card Status</label>

								{{ Form::select('jobcard_status_id', $job_card_status, $job_status, ['class' => 'form-control select_item ', 'id' => 'jobcard_status_id']) }}

								</div>

							</div>

						@endif-->

						@if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')

								<div class="form-group col-md-12">

										<label class="control-label required" for="order_id">Type</label> <br>

									<div class="custom-panel" >

										<input id="cash_type" type="radio" name="job_sale_type" value="cash" @if($transaction_type->name == "job_invoice_cash" ) checked="checked" @endif />

										<label for="cash_type" class="custom-panel-radio"><span></span>Cash</label>



										<input id="credit_type" type="radio" name="job_sale_type"  value="credit"  @if($transaction_type->name == "job_invoice" ) checked="checked" @endif/>

										<label for="credit_type"><span></span>Credit</label>

									</div>

								</div>

						@endif

						</div>

					<!--	<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="vehicle_name" class="required">Make/ Modal / variant / Version</label>

								{{ Form::text('vehicle_name', null, ['class' => 'form-control', 'id' => 'vehicle_name','disabled']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">
	
								<label for="show_customer_name" class="required">Customer Name</label>

								{{ Form::text('show_customer_name', null, ['class' => 'form-control', 'id' => 'cus_name']) }}

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

											 <textarea name="complaint" class="form-control complaint"  val="" style="height: 75px;"></textarea> 

										</div>

									</div>

								</div>

							</div>
							<div class="col-md-3">
								<div class="form-group  col-md-12">
									<div class="row">
									<label for="vehicle_name" class="required">Make/ Modal / variant / Version</label>

									{{ Form::text('vehicle_name', null, ['class' => 'form-control', 'id' => 'vehicle_name','disabled']) }}

									<label for="driver" class="control-label">Contact / Driver Name</label>

									{{ Form::text('driver', null, ['class'=>'form-control','id' => 'driver']) }}
									</div>


								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group  col-md-12">
									<div class="row">
									<label for="show_customer_name" class="required">Customer Name</label>

									{{ Form::text('show_customer_name', null, ['class' => 'form-control', 'id' => 'cus_name']) }}
									<label for="driver" class="control-label">Contact / Driver Number</label>

									{{ Form::text('driver_contact', null, ['class'=>'form-control','id' => 'driver_contact']) }}
									</div>
								</div>
							</div>

						<!--<div class="col-md-6">

							<div class="form-group">

								<div class="row">

									<div class="col-md-12">

										<label for="complaint">Complaints
											<!-- <span style="color:#007bff;cursor: pointer;" class="jobcard_complaint" id="jobcard_complaint_group">(Pre Define)</span>
											<span style="color:#007bff;cursor: pointer;display:none" class="applied_complaint">(Pre Define)</span> -->
										<!--</label>

										 <textarea name="complaint" class="form-control complaint"  val=""></textarea> 

									</div>

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

									<label for="driver" class="control-label">Contact / Driver Name</label>

									{{ Form::text('driver', null, ['class'=>'form-control','id' => 'driver']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-8">

	    						<div class="row">

		    					<label for="driver" class="control-label">Contact / Driver Number</label>

								{{ Form::text('driver_contact', null, ['class'=>'form-control','id' => 'driver']) }}

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

								{{ Form::text('vehicle_mileage', null, ['class' => 'form-control numbers']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="next_visit_reason" class="control-label required">Vehicle Next Visit Reason</label>

								{{ Form::text('next_visit_reason', null, ['class'=>'form-control']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="next_visit_mileage" class="control-label required">Vehicle Next Visit - Odometer Mileage</label>

								{{ Form::text('next_visit_mileage', null, ['class'=>'form-control numbers']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="next_visit_date" class="control-label required">Vehicle Next Visit - Date</label>

								{{ Form::text('next_visit_date', date('d-m-Y', strtotime('+90 day')), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

								</div>

							</div>

						</div>

					</div>

					<div class="row">
						<div class="col-md-6">
						<!--    
						<div class="form-group col-md-12">
						
						<div class="row">				
															 
						
						<label for="vehicle_note" class="control-label">Vehicle Note</label>
						
						{{ Form::textarea('vehicle_note', null, ['class'=>'form-control', 'size' => '30x2']) }}			
															
						
						</div>
						
						</div> -->

							<div class="form-group">

								<div class="row">

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

							

							<div class="form-group">

								<div class="row">

									@if( $transaction_type->name == 'job_card' || $transaction_type->name == 'job_request')

									<div class="col-md-6">

										<label class="required" for="date">Date</label>

										{{ Form::text('job_date',  date('d-m-Y') , ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 

									</div>

									@endif

									@if( $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')

									<div class="col-md-6">

										<label class="required" for="date">Invoice Date</label>

										{{ Form::text('job_date',  date('d-m-Y') , ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 

									</div>

									

									@endif

									@if( $transaction_type->name == 'job_card')

									<div class="col-md-6">

										<label for="job_due_date">Job Due Date</label>

										{{ Form::text('job_due_date',  date('d-m-Y', strtotime('+1 day')), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

									</div>

									@endif

									@if( $transaction_type->name == 'job_request')

									<div class="col-md-6">

										<label for="job_due_date">Expiry Date</label>

										{{ Form::text('job_due_date', $tomorrow_date, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

									</div>

									@endif

									@if( $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')

									<div class="col-md-6">

										<label for="job_due_date">Payment Due Date</label>

										{{ Form::text('job_due_date',date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

									</div>

									@endif

								</div>

							</div>

							<div class="form-group">

								<div class="row">

									<div class="col-md-6">

										<label for="date">Delivery Method</label>

										{{ Form::select('shipment_mode_id', $shipment_mode, $delivery_method, ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}

									</div>

									<!-- @if($transaction_type->name == 'job_card' || $transaction_type->name == 'job_request')
									
									<div class="col-md-6">
									
										<label for="shipping_date">Delivery Date</label>
									
										{{ Form::text('job_completed_date',  date('d-m-Y', strtotime('+1 day')), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
									
									</div>
									
									@endif
									
									@if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')
									
									<div class="col-md-6">
									
										<label for="shipping_date">Delivery On</label>
									
										{{ Form::text('job_completed_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
									
									</div>
									
									@endif -->

									

								</div>

							</div>

							<div class="form-group">

								<div class="row">

									@if( $transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')

									<div class="col-md-6">

										<label for="payment_terms">Payment Terms</label>

										{{ Form::select('payment_terms', $payment_terms, $payment_term, ['class' => 'form-control select_item', 'id' => 'payment_terms']) }}

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

										{{ Form::select('employee_id', $employees, $selected_employee, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}

									</div>

									@endif

									@if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')

									<div class="col-md-6">

										<label for="employee_id">Invoiced By</label>

										{{ Form::select('employee_id', $employees, $selected_employee, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}

									</div>

									@endif

									<div class="col-md-6">

										<label for="total" class="control-label required">Total</label>

										{{ Form::text('wms_total',null, ['class' => 'form-control total', 'id' => 'total','disabled']) }}

									</div>



								</div>

							</div>



						</div>

						<div class="col-md-6" style="border: 1px solid #d7dbe0;padding:1px;margin-top: 15px;overflow-y:scroll;">

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

									</tbody>

								</table>

							</div>

						</div>

					</div>

					<br/>

					

					<div class="form-group custom-panel col-md-12">
					
						<div class="row custom-panel-address">
							<div class="col-md-12 ">
													
								<div class="row">
						
									<div class="col-md-3">
										<label><b>{{$address_label}}</b></label>
										<input type="checkbox" name="update_customer_info" value="" id="update_customer_info" data-toggle="tooltip" data-placement="top" title="Check to update customer master">
										<label for="update_customer_info"><span></span></label>

										{{ Form::text('customer_name',null, ['class'=>'form-control display_name', 'autocomplete' => 'off' ,'style' => 'width:280px;','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Name','placeholder' => 'Name']) }} 

										{{ Form::text('customer_mobile', null, ['class'=>'form-control mobile', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Mobile Number','placeholder' => 'Mobile']) }} 

										{{ Form::text('customer_email', null, ['class'=>'form-control email', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Email','placeholder' => 'Email']) }}

										{{ Form::text('customer_gst', null, ['class'=>'form-control customer_gst', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'GST','id' => 'customer_gst','placeholder' => 'GST']) }}

										{{ Form::textarea('customer_address', null, ['class'=>'form-control address','style' =>'height:30px;','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Address','placeholder' => 'Address'] ) }}
											
									</div>

									<div class="col-md-3">
										<label><b>Billing Communication</b>
										<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Billing Name" class="form-control display_name @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="billing_name" value="{{$company_name}}" autocomplete="off" placeholder="Billing Name" />

										<input type="text" data-toggle="tooltip" data-placement="top" title="Billing Mobile" style="width:280px;" class="form-control mobile @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="billing_mobile" value="{{$company_mobile}}" autocomplete="off"  placeholder="Billing Mobile" />

										<input type="text" data-toggle="tooltip" data-placement="top" title="Billing Email" style="width:280px;" class="form-control email @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  email @endif " name="billing_email" value="{{$company_email}}" autocomplete="off"  placeholder="Billing Email" />

										<input type="text" data-toggle="tooltip" data-placement="top" title="Billing GST" style="width:280px;" id="billing_gst" class="form-control billing_gst @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  gst @endif " name="billing_gst" value="" autocomplete="off" placeholder="Billing GST" />

										<textarea name="billing_address" data-toggle="tooltip" data-placement="top" title="Billing Address" style="width:280px;height: 30px;" class="form-control address @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  address @endif " placeholder="Billing Address">{{$company_address}}</textarea>
												
									</div>

									<div class="col-md-3">
										<label><b>Shipping Communication</b>
										<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Shipping Name" class="form-control display_name @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="shipping_name" value="{{$company_name}}" autocomplete="off"  placeholder="Shipping Name" />

										<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Shipping Mobile" class="form-control mobile @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="shipping_mobile" value="{{$company_mobile}}" autocomplete="off" placeholder="Shipping Mobile" />

										<input type="text" style="width:280px;" data-toggle="tooltip" data-placement="top" title="Shipping Email" class="form-control email @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   email @endif " name="shipping_email" value="{{$company_email}}" autocomplete="off"  placeholder="Shipping Email" />

										<textarea name="shipping_address" data-toggle="tooltip" data-placement="top" title="Shipping Address" style="width:280px;height: 30px;" class="form-control address @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   address @endif " placeholder="Shipping Address"> {{$company_address}}</textarea>
											
									</div>
									
					
								
								</div>
					
							</div>
						</div>
					
													
					
					</div>  
					<div class="row">

						<div class="form-group">

							<div id ="show_more_detail" >

								<label for="show_vehicle_detail" ><span></span><a  id="show_vehicle_detail" style="padding: 17px;color: #007bff;font-weight: bold;">Show More..</a></label></input>

								<div class="row row-full" style="border: 1px solid #d7dbe0;">

								</div>

							</div>

							<div style="display: none;" id ="show_less_detail">

								<label for="hide_vehicle_detail" ><span></span><a  id="hide_vehicle_detail" style="padding: 17px;color: #007bff;font-weight: bold;">Show Less..</a></label></input>

								<div class="row show-full" style="border: 1px solid #d7dbe0;">

								<div class="show_vehicle_details col-md-12" style="display:none;padding:10px;">
					<div class="row">

					<div class="col-md-6">

						<div class="form-group">
							<div style= "@if($customer_type_label == null) display:none @endif">
									{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
							</div>
							<div style="padding-left: 20px;">

								<div class="row custom-panel">

									<div class="col-md-6 customer_type" style= "/*@if($customer_type_label == null) display:none @endif*/"> 
											
										<!-- 	{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br> -->
										
										<div class="" style="background-color: #e9ecef">

										<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
										
										<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
										
										<input id="people_type" type="radio" name="customer" value="0"  />
										
										<label for="people_type" ><span></span>People</label>
										
									</div>
										
									</div>
									<div class="col-md-6">
						
										<div class="form-group">
								
											<div class="row">

											<div class="col-md-12 search_container people " style= "@if($customer_label == null) display:none @endif" id="people_div">
									
											<!-- {{ Form::label('people', $customer_label, array('class' => 'control-label required')) }} -->
									
											{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
									
											{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
									
											{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
									
											<div class="content"></div>
									
											</div>
									
											<div class="col-md-12 search_container business" style= "@if($customer_label == null) display:none @endif" id="business_div">
									
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
								

							</div>
						</div>

					</div>

						<!-- <div class="col-md-3">
						
							<div class="form-group">
						
								<div class="row">
									<div class="col-md-12 search_container people " style= "@if($customer_label == null) display:none @endif">
						
						
						
										{{ Form::label('people', $customer_label, array('class' => 'control-label required')) }}
						
						
						
										{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
						
						
						
										{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
						
						
						
										{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
						
						
						
										<div class="content"></div>
						
									</div>
						
						
						
									<div class="col-md-12 search_container business" style= "@if($customer_label == null) display:none @endif">
						
										{{ Form::label('business', $customer_label, array('class' => 'control-label required')) }}
						
										{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}
						
										{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
						
										{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
						
										<div class="content"></div>
						
									</div>								
						
								</div>
						
							</div>
						
						</div>  -->

						<div class="col-md-3">

							<div class="form-group ">

								<div class="row">
									@if($transaction_type->name == 'job_card' || $transaction_type->name == 'job_request')

									<div class="col-md-12">

										<label for="shipping_date">Delivery Date</label>

										{{ Form::text('job_completed_date',  date('d-m-Y', strtotime('+1 day')), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

									</div>

									@endif

									@if($transaction_type->name == 'job_invoice' || $transaction_type->name == 'job_invoice_cash')

									<div class="col-md-12">

										<label for="shipping_date">Delivery On</label>

										{{ Form::text('job_completed_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

									</div>

									@endif
								

								</div>

							</div>

						</div>

					<div class="col-md-3">

							<div class="form-group ">

								<div class="row">

								@if($transaction_type->name == 'job_card')

									<div class="col-md-12">

										

										<label for="date" class="required">Job Card Status</label>

										{{ Form::select('jobcard_status_id', $job_card_status, $job_status, ['class' => 'form-control select_item ', 'id' => 'jobcard_status_id']) }}

										

									</div>

								@endif
								</div>
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="vehicle_category" class="control-label required">Vehicle Category</label>

								{{ Form::select('vehicle_category', $vehicle_category, null, ['class' => 'form-control select_item', 'id' => 'vehicle_category', 'disabled']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="last_visit" class="control-label">Vehicle Permit type</label>

								{{ Form::text('permit_type', null, ['class'=>'form-control','disabled','id' => 'permit_type']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="last_visit" class="control-label">Vehicle Last Visit</label>

								{{ Form::text('last_visit',null, ['class'=>'form-control','disabled','id' => 'vehicle_last_visit']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="last_job_card" class="control-label">Vehicle Last Job Card</label>

								{{ Form::text('last_job_card', null, ['class'=>'form-control','disabled','id' => 'last_update_jc']) }}

								</div>

							</div>

						</div>

					</div> 
					

					<div class="row">

						<div class="col-md-3">

							<div class="form-group  col-md-12">

								<div class="row">

								<label class="required" for="service_type">Service Type</label>

								{{ Form::select('service_type', $vehicle_sevice_type, $sevice_type, ['class' => 'form-control select_item', 'id' => 'service_type']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row ">

								<label for="last_visit" class="control-label">Vehicle Insurance</label>

								{{ Form::text('vehicle_insurance', null, ['class'=>'form-control','disabled','id' => 'vehicle_insurance']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12 ">

								<div class="row">

								<label for="last_visit" class="control-label">Warranty KM</label>

								{{ Form::text('warranty_km', null, ['class'=>'form-control','disabled','id' => 'warranty_km']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row ">

								<label for="last_job_card" class="control-label">Warranty Years</label>

								{{ Form::text('warrenty_yrs', null, ['class'=>'form-control','disabled','id' => 'warrenty_yrs']) }}

								</div>

							</div>

						</div>

					</div>  

					<div class="row">

						<div class="col-md-3">

							<div class="form-group  col-md-12">

								<div class="row ">

								<label for="last_job_card" class="control-label">FC Due</label>

								{{ Form::text('fc_due', null, ['class'=>'form-control','disabled','id' => 'fc_due']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="last_visit" class="control-label">Permit Due</label>

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

								<label for="last_job_card" class="control-label">Tax Due</label>

								{{ Form::text('tax_due', null, ['class'=>'form-control','disabled','id' => 'tax_due']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="last_job_card" class="control-label">Insurance Due</label>

								{{ Form::text('insurance_due', null, ['class'=>'form-control','disabled','id' => 'insurance_due']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="last_visit" class="control-label">Bank Loan</label>

								{{ Form::text('bank_loan', null, ['class'=>'form-control','disabled','id' => 'bank_loan']) }}

								</div>

							</div>

						</div>

						<div class="col-md-3">

							<div class="form-group col-md-12">

								<div class="row">

								<label for="last_job_card" class="control-label">Month Due Date</label>

								{{ Form::text('month_due_date', null, ['class'=>'form-control','disabled','id' => 'month_due_date']) }}

								</div>

							</div>

						</div>

					</div> 
					<div class="row">
						<div class="form-group col-md-6">
						
						<label for="vehicle_note" class="control-label">Vehicle Note</label>
						{{ Form::textarea('vehicle_note', null, ['class'=>'form-control', 'size' => '30x2']) }}
						</div>

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
							<!-- <input type="checkbox" name="show_complaints" class="show_com" id="show_complaints">
							<label for="show_complaints"><span></span>Show complaints</label> -->

							<label>Complaints Completed</label> 
							<span class="completed_value" style="color:#0000FF;cursor: pointer;">
								0/0
							</span>
							<span class="applied_completed_value" style="color:#0000FF;cursor: pointer;display:none;">
								0/0
							</span>
						</div>

						<div class = "form-group col-md-12" style="float:left;">
							<div class="row">
								<div class="col-md-12 show_complaint" style="display:none;">
									<label for="complaints">Complaints</label>

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
									<div id="left" style="color: black;">
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
												<h6> Total Discount:</h6>
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
						

						<div style="float:right; width: 130px; margin: 10px;display: none;">                

							<select name="tax_types" class='form-control select_item' disabled >

							<option value="2">Exclude Tax</option>
							<option value="1">Include Tax</option>
							<option value="0">Out Of Scope</option>

							</select>

						</div>
					

						<div style="float:right;margin:10px;padding-top: 5px; display: none">

							<div style="float:left;font-weight: bold;color: #4b5056;">

								<label for="group_name_show">Customer Group :</label>

							</div>&nbsp;&nbsp;

							<div style="float:right;">

								{{ Form::text('group_name_show',null,['class'=> 'form-control',	'id' =>'group_name_show','disabled']) }}

							</div>						               

						</div>				

					</div>										

				</div>				
									

				<div class="clearfix"></div>

				<div class="form-group" style="margin-top: 5px; padding: 10px;" >										

					<table id="crud_table" style="border-collapse: collapse; margin-bottom: 0px; box-shadow: 0 4px 8px 4px #A9A9A9, 0 6px 20px 0 #A9A9A9;" class="table table-bordered crud_table crud_table_shadow">

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

									<select name="item_id" class="form-control select_item" id="item_id"  >

									<option value="">Select Item</option>

									<?php $selected_item = null; ?> 

									@foreach($items as $item)

									@if($selected_item != $item->category)

										<optgroup label="{{$item->category}}"> @endif

										<?php $selected_item = $item->category; ?>

										<option data-tax="{{$item->include_tax}}" data-purchase_tax="{{$item->include_purchase_tax}}" data-rate = "" value="{{$item->id}}" id="item-name">{{$item->name}}</option>
										</optgroup>

									@endforeach	

									</select> 

									<input type="hidden" name="parent_id">
									<input type="hidden" name="batch_id">
								</div>

								<!--to add item for plus symbol-->
								<div style="float:right;" id="jc_item_create">
									<a href="javascript:;" id="" data-toggle="tooltip" data-placement="top" title="Add new item"  class="jc_item_create"><i class="fa fa-cube " style="padding: 2px;" aria-hidden="true"></i></a>
								</div>

								<div style="float:right; display: none;" id="item_batch" class="item_batch" data-toggle="tooltip" data-placement="top" title="Select Item Batch">

								<a href="javascript:;"><i class="fa fa-cart-plus" style="padding: 2px;" aria-hidden="true"></i></a>

								</div>

							</td>

							<td data-toggle="tooltip" data-placement="top" title="Desctiption- Appears in Print" >
								{{ Form::textarea('description', null, ['class'=>'form-control', 'style'=>' height: 26px;' , 'placeholder' => 'Description']) }}
							</td>

							
							<td data-toggle="tooltip" data-placement="top" title="Hr-Duration of this work" style="">
								{{ Form::text('duration', null, ['class'=>'form-control'  , 'placeholder' => 'Duration']) }} 
								
							</td>
							

							@if($discount_option)

								<td style= "@if($transaction_type->name == 'job_card') display:none @endif" data-toggle="tooltip" data-placement="top" title="Disount Type">

									<select name='discount_id' class='form-control select_item ' id = 'discount_id'>

									<option value="">Select Discount</option>

									@foreach($discounts as $discount) 

									<option value="{{$discount->id}}" data-value="{{$discount->value}}">{{$discount->display_name}}</option>

									@endforeach
									</select>
								</td>

						

							@if($discount_option)
								<td style= "@if($transaction_type->name == 'job_card') display:none @endif" data-toggle="tooltip" data-placement="top" title="Disount %">
								{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}
								 </td>
							@endif

							@endif

							<td data-toggle="tooltip" data-placement="top" title="Unit Price - before the tax">
								{{ Form::text('rate', null, ['class'=>'form-control decimal']) }}
							</td>

							<td data-toggle="tooltip" data-placement="top" title="Currently Available Stock">
								{{ Form::text('in_stock', null, ['class'=>'form-control decimal', 'disabled', 'id' => 'in_stock']) }}
							</td>

							<td data-toggle="tooltip" data-placement="top" title="Selling Quantity">
								{{ Form::text('quantity', null, ['class'=>'form-control decimal quantity','id' => 'quantity']) }}
							</td>

							<td data-toggle="tooltip" data-placement="top" title="Price B.Tax * Qty (without tax)">
								{{ Form::text('amount', null, ['class'=>'form-control decimal']) }}
							</td>

							<td style="" data-toggle="tooltip" data-placement="top" title="Item's GST tax">

								<select name='tax_id' class='form-control select_item taxes' id = 'tax_id' >

									<option value="">Select Tax</option>

									@foreach($taxes as $tax) 

									<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>

									@endforeach

								</select>
							</td>

							
							<td style= "@if($transaction_type->name == 'job_card') display:none @endif" data-toggle="tooltip" data-placement="top" title="Tax Amount">
								{{ Form::text('tax_amount', null, ['class'=>'form-control decimal']) }}
							</td>
							
							
							<td data-toggle="tooltip" data-placement="top" title="Price * Qty with Tax">
								{{ Form::text('tax_total', null, ['class'=>'form-control decimal']) }}
							</td>
							

							@if($transaction_type->name == "job_card")
								<td data-toggle="tooltip" data-placement="top" title="Who works for this item">
									{{ Form::select('assigned_employee_id', $employees, $selected_employee, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}
								</td>
							@endif

							<!-- {{ Form::text('start_time', null, ['class'=>'form-control datetimepicker2','data-date-format' => 'dd-mm-yyyy h:i:s a']) }} -->

							@if($transaction_type->name == "job_card")
								<td data-toggle="tooltip" data-placement="top" title="When the work starts">
									{{ Form::text('start_time', $current_date, ['class'=>'form-control datetimepicker2','id' => 'end_time']) }}
								</td>
							@endif

							
							<td style= "display:none" data-toggle="tooltip" data-placement="top" title="When the work End">
								{{ Form::text('end_time', $add_date, ['class'=>'form-control datetimepicker2','id' => 'end_time']) }}
							</td>
							

							@if($transaction_type->name == "job_card")
								<td data-toggle="tooltip" data-placement="top" title="Status of this item/work">
									{!! Form::select('job_item_status', $job_item_status, $item_status, ['class' => 'form-control select_item']); !!}
								</td>
							@endif		

							

							<td  data-toggle="tooltip" data-placement="top" title="Click to add item into the list"><a class="grid_label action-btn edit-icon add_row_append"><i class="fa fa-plus"></i></a></td>

						</tr>

						</tbody>

					</table>
				</div>

				<div class="form-group" style=" margin-top: 10px; overflow-y: scroll; height:330px;" >

					<table id="append_table" style="border-collapse: collapse;" class="table table-bordered append_table">

						<thead style="">

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


			@if($transaction_type->name == 'job_card')

				<div class="tab-pane" id="attachments">

						<!--Start Before Image -->

						<div class="row">								

							<div class="col-lg-12 col-md-12 col-sm-12">

									<h5>Before</h5>

									<input type="hidden" name="attachment_uid" value="{{$uuid}}">

										<div class="dropzone" id="BeforeImages" >

											<div class="fallback">

											</div>

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

						</div>

						<!--End Before Image -->					



						<!--Start Progress Image -->

						<div class="row">									

							<div class="col-lg-12 col-md-12 col-sm-12">

								<h5>Progress</h5>

								<input type="hidden" name="attachment_uid" value="{{$uuid}}">

								<div class="dropzone" id="ProgressImages" >

									<div class="fallback">

									</div>

								</div>

								<br>
								<div class="myProgress">

									<div id="ProgressPBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="display:none;">Processing uploded file...</div>

								</div>

							</div>

								<div class="col-lg-12 col-md-12 col-sm-12">

									<div class="col-md-12 pull-right" style="padding:5px 20px">

									<button type="button" class="btn btn-success Insert_files pull-right" id="SavePImg" style="float: right;">Upload Files

									</button> 
									</div>
					

								</div>

						</div>

						<!--End Progress Image -->

							<!--Start After Image -->

						<div class="row">                                 

							<div class="col-lg-12 col-md-12 col-sm-12">

								<h5>After</h5>

								<input type="hidden" name="attachment_uid" value="{{$uuid}}">

									<div class="dropzone" id="AfterImages" >

									<div class="fallback">

									</div>

									</div>                                                      

									<br>

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

						</div>

						<!--End After Image -->				

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

								@foreach($reading_factor as $reading)

									<tr>                        

										<td>

											<span style="float: right; padding-left: 5px;">{{$i}}</span>

										</td>                           

										<!-- <td>{{ $reading->division_name}}

											{{ Form::hidden('wms_division_id', $reading->wms_division_id) }}

										</td> -->

										<td>{{ $reading->reading_factor_name}}

											{{ Form::hidden('wms_reading_factor_id', $reading->reading_factor_id) }}

										</td>

										<td>{{ Form::text('reading_values', null, ['class'=>'form-control ']) }}</td>

										<!-- <td>{{ Form::text('reading_calculation', null, ['class'=>'form-control numbers']) }}</td> -->

										<td>{{ Form::text('reading_notes', null, ['class'=>'form-control']) }}</td>

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

										@foreach($vehicle_check_list as $checklist)

										<tr>

											<td>

												<span style="float: right; padding-left: 5px;">{{$i}}</span>

												{{ Form::hidden('wms_checklist_id', null) }}

											</td>


											<td>{{ $checklist->name}}

												{{ Form::hidden('checklist_id', $checklist->id) }}

											</td>

											<?php $wfm_checklist="wms_checklist_status".$i; ?>

											<td>  {{ Form::checkbox('wms_checklist_status', '1', null, array('id' =>$wfm_checklist )) }}    <label for="wms_checklist_status<?php echo $i?>"><span></span></label></td>

											<!-- <td>{{ Form::text('reading_calculation', null, ['class'=>'form-control numbers']) }}</td> -->

											<td>{{ Form::text('wms_checklist_notes', null, ['class'=>'form-control']) }}</td>

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

		<button type="reset" class="btn btn-default clear cancel_transaction">Close</button>		

		<!-- @if($transaction_type->name == "job_card" )
		<button type="submit" class="btn btn-success tab_print_btn"> Print  </button>
		@endif -->

		<button type="submit" class="btn btn-success tab_print_btn" style="display: none;">Print</button>
		<button type="submit" class="btn btn-success tab_send_btn" style="display: none;">Send</button>
		<button type="submit" class="btn btn-success tab_sms_btn sms_limit" style="display: none;" id="sms_btn">Send SMS</button>

		<button type="submit" class="btn btn-success tab_copy_btn"  style="display: none;">Copy</button>

		<button type="submit" class="btn btn-success tab_copy_btn_cash"  style="display: none;">Copy Invoice Cash</button>

		<button type="submit" class="btn btn-success tab_copy_btn_credit"  style="display: none;">Copy Invoice Credit</button>

		<button type="submit" class="btn btn-success tab_copy_invoice"  style="display: none;">Copy Invoice</button>


		@if($transaction_type->name == "goods_receipt_note")
		<button type="submit" class="btn btn-success tab_update_goods_btn update_goods"  style="display: none;">Update Inventory</button>
		@endif

		@if($transaction_type->name != "job_card")
		<button type="submit" class="btn btn-success tab_approve_save_btn"> Approve </button>
		@endif

		<button type="submit" class="btn btn-success tab_save_close_btn">Save and Close </button>
		<button type="submit" class="btn btn-success tab_save_btn">Save</button> 
		<div style="margin:-25px auto 0px; width: 150px;">

		<div class="col-md-12"> @if($transaction_type->name != "sales_cash" || $transaction_type->name != "job_invoice_cash")

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

	var transaction_id = null; 
	

	$(document).ready(function() {
	

		$('[data-toggle="tooltip"]').tooltip();			

		@if(!empty($transactions) && $transaction_type != null)

		order('{{$transactions->order_no}}', '{{$transaction_type->name}}', "");

		@endif

		basic_functions();		

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

		

		$('.add_vehicle').on('click', function(e) {
			e.preventDefault();
			$.get("{{ route('jc_vehicle_registered.create') }}", function(data) {
				//$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').attr("data-id",0);
				$('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
			$('.loader_wall_onspot').hide();
			
		});
		
		$('.discount_popup').on('click', function(e) {
			e.preventDefault();
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

	/*For grouped Complaints Popup*/

	$('.jobcard_complaint , .completed_value').on('click',function(e){
		e.preventDefault();	
        $.get("{{ route('jobcard_complaint_create') }}", function(data) {
              	$('.group_item_modal .modal-container').html(data);
              	$('.group_item_modal').find('.modal-footer').find('.apply_save').attr('id','{{$uuid}}');
        });
        $('.group_item_modal').find('.modal-dialog').addClass('modal-lg');
        $('.group_item_modal').modal('show');
	});

	$('.applied_complaint , .applied_completed_value').on('click',function(){
		$('.group_item_modal').modal('show');
	});
	/*end*/



	$('textarea.complaint').keyup(function()
	{
		var content = $('textarea.complaint').val();
		$('textarea.job_complaint').val(content);

	});

		$('#update_customer_info').on('click',function(){
			if($(this).prop("checked") == true)
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
			$('.show_complaint').show();
		} 
		else 
		{
			$('.show_complaint').hide();

		}
	});
	

	$(".billing").hide();

	$(".shipping").hide();



	$('body').on('change', 'input[name=before_image], input[name=after_image]', function(e) {

		var obj = $(this);

		var image = obj.closest('div').find('img');
		var name = obj.closest('.image_container').find('input[type=text]');

		if (obj[0].files && obj[0].files.length > 0) {

			var reader = new FileReader();
			reader.readAsDataURL(obj[0].files[0]);
			reader.onload = function (e) {

				image.attr('src', e.target.result);
			}

			name.prop('disabled', false);
		} else {

			name.val('');
			name.prop('disabled', true);

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

			$.get("{{ route('transaction.create', ['sales_cash']) }}", function(data) 
			{

				$('.full_modal_content').show();
				$('.full_modal_content').html("");
				$('.full_modal_content').html(data);
				$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
				$('.loader_wall_onspot').hide();

			});



		} else if($(this).val() == "credit") {      

			$.get("{{ route('transaction.create', ['sales']) }}", function(data) 
			{

				$('.full_modal_content').show();
				$('.full_modal_content').html("");
				$('.full_modal_content').html(data);
				$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
				$('.loader_wall_onspot').hide();

			});

		}



	});



	$("input[name=job_sale_type]").on('change', function(){		

		$('.loader_wall_onspot').show();

		if($(this).val() == "cash") {
			$.get("{{ route('transaction.create', ['job_invoice_cash']) }}", function(data)
			{
				$('.full_modal_content').show();
				$('.full_modal_content').html("");
				$('.full_modal_content').html(data);
				$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
				$('.loader_wall_onspot').hide();

			});

		} 

		else if($(this).val() == "credit") {			

			$.get("{{ route('transaction.create', ['job_invoice']) }}", function(data) 
			{
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



	$('select[name=period]').on('change', function()
	{
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



	$('select[name=end]').on('change', function()
	{
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



	/*function initialiseDates() { $('.datetimepicker2').each(function () 

		{ 

			$(this).removeClass('hasDatepicker').removeData('datepicker').unbind().datetimepicker({ 

				dateFormat: 'dd/mm/yy' 

			}); 

		}); 

	};*/
	


	


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



	/*$('body').on('change', '.total', function(){

		var obj = $(this);
		console.log(obj);

	}); */



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
	
	$('.job_card_status_change').on('click',function(){
		
			
			var id = $(this).attr('data-id');
		
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

		});


	

	$('.cancel_transaction').on('click', function(e) {
		e.preventDefault();

		$('input[name=make_recurring]').prop('checked', false);
		$('input[name=make_recurring]').trigger('change');
		if($('.recurring').is(':hidden')) {
			
			//console.log("cancel working");
			$('.close_full_modal').trigger('click');

		} else {
			$('.voucher_name').text("{{$transaction_type->display_name}}# {{$voucher_no}}");
			$('.recurring').hide();
			$('.voucher_code').show();
		}
		
	});

	$('.close_full_modal').on('click',  function() {
		//console.log("cancel in layout custom");

		$('.full_modal_content').html("");
		//console.log($('.full_modal_content').html("").length);
		$('.full_modal_content').removeAttr('style');
		$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
		$('body').css('overflow', '');
		
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
		var model =  $("select[name=vehicle_model_id]" );

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

		if(type == "" && $("select[name=reference_type]").val() == "direct") 

		{
			$('.transactionform input:not(input[type=button]):not(input[type=submit]):not(input[type=reset]):not(input[name=_token]):not(input[name=order_id]):not(input[name=invoice_date]):not(input[type=radio]):not(input[type=checkbox])').val("");

			$('.transactionform select:not([name=reference_type]):not([name=tax_types])').val("");

			$('.transactionform select:not([name=reference_type]):not([name=tax_types]):not("#state")').trigger('change');

		} 

		else if(type != "" && id != "") 
		{
			order(id, type, 1); 
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

	});*/

	

	

	
	


	$('body').off('click', '.add_row').on('click', '.add_row', function()
	{
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

			//console.log(clone);

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

				//$('#append_table').append(clone);

			}

			obj.parent().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');

			//obj.parent().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

			//item.find('optgroup > option[value!="' + selected_item + '"]').wrap('<span>');

			//$('.select_item').select2();

			$(".select_item").select2({               
                placeholder: "Select Item",
                alowClear:true
            });
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



		for (var i in selected_item_array) {

			$('select[name=item_id]:last').find('optgroup > span > option[value="' + selected_item_array[i] + '"]').unwrap();
		}

		$('select[name="item_id"]:last > span > option').unwrap();

		var row_index = $('.crud_table tbody > tr').length;

		//console.log(row_index);

		if(row_index > 1) {

			$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

		} else {

			$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

		}

		table();

	});




	$('body').off('click', '.add_row_append').on('click', '.add_row_append', function()
	{
		var obj = $(this);

		var quantity = obj.closest("tr").find('input[name="quantity"]').val();
		var item_id = obj.closest("tr").find('select[name="item_id"]').val();
		var parent_id = obj.closest("tr").find('input[name="parent_id"]').val();
		var batch_id = obj.closest("tr").find('input[name="batch_id"]').val();
		var item_text = obj.closest("tr").find('select[name="item_id"] option:selected').text();
		var tax_id =obj.closest("tr").find('select[name="tax_id"]').val();

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

			var crud_clone = $(this).closest(' tbody > tr').clone();
			
			
			crud_clone.find('td:last').html('<a class="grid_label action-btn delete-icon remove_row_append"><i class="fa fa-trash-o"></i></a>');

			crud_clone.find('.datetimepicker2').datetimepicker({
				rtl: false,
				orientation: "left",
				todayHighlight: true,
				autoclose: true
			});	
			

			crud_clone.find('select[name="item_id"]').remove();
			//crud_clone.find('input[name="duration"]').hide();

			var row_index = $('.append_table tbody > tr').length;	
						
			var New_data_row = row_index+1;

			crud_clone.find( "td:eq(0) > span" ).text(New_data_row);
			crud_clone.find( "td:eq(0) > span" ).removeClass('index_number');
			crud_clone.find( "td:eq(0) > span" ).addClass('index_number_append');

			crud_clone.find( "td:eq(1)" ).html('<input type="hidden" name="append_item_id" value="'+item_id+'"  class="form-control"><input type="hidden" name="parent_id" value="'+parent_id+'"><input type="hidden" name="batch_id" value="'+batch_id+'" ><input type="text" name="append_item" class ="form-control" disabled="ture" style="width:200px;float: left;" value="'+item_text+'">');

			
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

	//$('.item_batch').on('click', function(e) {

		$('body').on('click', '.item_batch', function() {
		
        //e.preventDefault();
		
		var id = $(this).attr('data-id');
		var tr_id = $(this).closest('tr').attr('id');
       
		$.get("{{ url('inventory/item-batches') }}/"+id, function(data) 
		{
			//$('.loader_wall_onspot').show();
			$('.crud_modal .modal-container').html("");
			//$('.crud_modal .modal-container').attr("data-id",0);
			$('.crud_modal .modal-container').html(data);
			

		});
		
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

		/* get (+ sign) current row id */
		$('.crud_modal').find('.modal-dialog').attr('data-tr',tr_id);
		/*end*/

		$('.crud_modal').modal('show');

	});
	


	$('body').on('change', 'select[name=item_id]', function(event,params) 
	{		
		var obj = $(this);
		var id = obj.val();

		var vehicle_id =$('select[name=registration_number]').val();

		var over_all_discount = $('input[name=new_discount_value]').val();

		var transaction_module =('{{ $transaction_type->module }}');

		obj.closest('tr').find('select[name=discount_id]').trigger('change');


		if(transaction_module == 'trade_wms') {

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
						//console.log(parseInt(batch_quantity));

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

						//console.log(params);

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

							obj.closest('tr').find('td > input[name=base_price]').val(data.base_price).prop('disabled', true);

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

				$('.alert-danger').text('Please Select Vehicle...');

				$('.alert-danger').show();

				setTimeout(function() { $('.alert').fadeOut(); }, 3000);              

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

						var group = data.group;
						
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

						//console.log(params);

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


						@if($type == 'purchases' || $type == 'purchase_order')

							if(obj.closest('tr').find('input[name=quantity]').val() == "") {

							obj.closest('tr').find('input[name=quantity]').val(data.moq);
						}
						@else

							if(obj.closest('tr').find('input[name=quantity]').val() == "") {

							obj.closest('tr').find('input[name=quantity]').val(1);
						}
						@endif

						@if($type == 'purchases' || $type == 'purchase_order' || $type == 'goods_receipt_note')

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

						if(modules == 'inventory'){

							obj.closest('tr').find('td > input[name=base_price]').val(data.base_price).prop('disabled', true);

							obj.closest('tr').find('input[name=new_base_price]').val(data.base_price);

						}else{
							obj.closest('tr').find('td > input[name=base_price]').val(data.price).prop('disabled', true);			
						}

						
						

						obj.closest('tr').find('td > select[name=tax_id]').val(data.tax_id).prop('disabled', false);

						obj.closest('tr').find('td > select[name=tax_id]').trigger('change');

						//obj.closest('tr').find('td > select[name=tax_id]').trigger('change');                     

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
				obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock],input[name=tax_amount],input[name=tax_total]').val("");

				table();
			}       
		}

	});



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

	

	load_data();



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

	}   

	

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



		var tab_save_close = false;
		var jc_save_close = false;
		var jc_store_only = false;
		var approve = 0;
		var sms = 0;
		var print = 0;
		var send_po = 0;
		var tab_update_goods_btn = 0;

	$(".tab_save_btn, .tab_save_close_btn, .tab_approve_save_btn").off().on('click', function(e) {

		var transaction_module =('{{ $transaction_type->module }}');

		var that = $(this);

		e.preventDefault();			

		var next_tab = $('.nav-tabs li a.active').parent().next('li:visible').find('a').attr('href');

		var next_other_tab = $('.nav-tabs li a.active').parent().next('li:visible').next('li:visible').find('a').attr('href');

		var validator = $('.transactionform').validate();

		var item_id = $('body').find(".crud_table").find('select[name="item_id"]').val();		

		if(that.hasClass('jc_store_btn')) {
			jc_store_only = true;
		}else{
			jc_store_only = false;
		}

		if(that.hasClass('tab_save_close_btn') && that.hasClass('jc_store')) {

			jc_save_close = true;

		} else {
			jc_save_close = false;
		}


		if(that.hasClass('tab_save_close_btn')) {
			tab_save_close = true;
		} else {
			tab_save_close = false;
		}
			

		if(validator.checkForm() == true) {

			$('.form-group').removeClass('has-error');
			$('.help-block').remove();

			/*if(next_tab) {

				$('a[href="'+next_tab+'"]')[0].click();

				if(next_other_tab == undefined) {

					if(that.hasClass('tab_save_close_btn')) {
						that.text("Save and Close");
					}
					else if(that.hasClass('tab_approve_save_btn')) {
						that.text("Approve");

					}else {
						that.text("Save");
					}
				}
				return false;
			}*/

			if(that.hasClass('tab_save_close_btn')) {
				that.text("Save and Close");
			}
			else if(that.hasClass('tab_approve_save_btn')) {
				that.text("Approve");

			}else {
				that.text("Save");
			}


			if($(".transactionform").valid()) {

				if($(this).hasClass('tab_approve_save_btn')) {

					approve = 1;

					$('.tab_approve_save_btn').text("Approved");
					$('.tab_update_goods_btn').show();
					$('#sms_btn').show();

				} else {
					approve = 0;					
				}

				$(".transactionform").submit();
			}

		} 
		else {

			$('.form-group').addClass('has-error');
			validator.showErrors();			
		}

	});


	$(".tab_send_btn").on('click', function(e) {

			e.preventDefault();
			$.ajax({

				url: "{{ route('transaction.send_all') }}",

				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',
					id: transaction_id,
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
							
							
							id: transaction_id,
							type: '{{ $transaction_type->name }}',
							
						},
						success: function(data, textStatus, jqXHR) {
		                    alert_message(data.message, "success");
						}

					});
				}
				else{

					$('#error_dialog #title').text('Limit Exceeded!');
					$('#error_dialog #message').html('{{ config('constants.error.sms_no') }}'  + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
					$('#error_dialog').modal('show');

					return false;
				}
			}
		});

	});


	$('.tab_copy_btn').off().on('click', function(e) {

			e.preventDefault();
			//$('.loader_wall_onspot').show();
			var obj = $(this);
			var id = transaction_id;
			var transaction_name = '';

			if('{{ $transaction_type->name }}' == 'estimation'){
				transaction_name = 'sale_order';
			}
			else if('{{ $transaction_type->name }}' == 'sale_order'){
				transaction_name = 'sales';
			}
			else if('{{ $transaction_type->name }}' == 'sales' || '{{ $transaction_type->name }}' == 'sales_cash'){

				transaction_name = 'delivery_note';
			}
			else if('{{ $transaction_type->name }}' == 'purchase_order'){
				transaction_name = 'purchases';
			}
			else if('{{ $transaction_type->name }}' == 'purchases'){
				transaction_name = 'goods_receipt_note';
			}

			else if('{{ $transaction_type->name }}' == 'job_request'){
				transaction_name = 'job_invoice';
			}

			$('<form>', {

			"id": "dynamic_form",

			"method": "POST",

			"html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',

			"action": '{{ route("add_to_account") }}'

			}).appendTo(document.body).submit();

			$('#dynamic_form').remove();

	});

	$('.tab_copy_btn_cash').on('click', function(e) {

			e.preventDefault();

			//$('.loader_wall_onspot').show();

			var obj = $(this);
			var id = transaction_id;
			var transaction_name = 'sales_cash';


			$('<form>', {

			"id": "dynamic_form",

			"method": "POST",

			"html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',

			"action": '{{ route("add_to_account") }}'

			}).appendTo(document.body).submit();
			$('#dynamic_form').remove();
	});

	$('.tab_copy_btn_credit').on('click', function(e) {

			e.preventDefault();

			//$('.loader_wall_onspot').show();

			var obj = $(this);
			var id = transaction_id;
			var transaction_name = 'sales';


			$('<form>', {

			"id": "dynamic_form",

			"method": "POST",

			"html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',

			"action": '{{ route("add_to_account") }}'

			}).appendTo(document.body).submit();
			$('#dynamic_form').remove();
	});


	$(".tab_print_btn").on('click', function(e) {
		var obj = $(this);
		var id = transaction_id;
		print_transaction(id);
	});


	$('.tab_copy_invoice').off().on('click', function(e) {

			e.preventDefault();

			//$('.loader_wall_onspot').show();

			var obj = $(this);
			var id = transaction_id;
			var transaction_name = 'sales';


			$('<form>', {

			"id": "dynamic_form",

			"method": "POST",

			"html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',

			"action": '{{ route("add_to_account") }}'

			}).appendTo(document.body).submit();
			$('#dynamic_form').remove();
	});



	$("body").on('click', '.tab_update_goods_btn', function(e) {

		e.preventDefault();
		var obj = $(this);
		var id = transaction_id;
		var transaction_name = 'goods_receipt_note';

		$.ajax({

				url: "{{ route('transaction.update_inventory') }}",
				type: 'post',
				data: {
					
					_token: '{{ csrf_token() }}',
					id: id,
					type: transaction_name
					
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



	$('body').on('change','#multi_discount_id',function(){

		var id = $(this).val();
		$('#discount_id').val(id);

	});


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
		var discount_id =parent.find('input[name=discount_value]').val();
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
			} 

			else if(obj.val() == 2) 
			{
				var exclude_tax = rate - (rate * (tax_value * 100)/(100 + (tax_value * 100))).toFixed(2);

				parent.find('input[name=rate]').val(exclude_tax);
			} 

			else if(obj.val() == 0) 
			{
				parent.find('select[name=tax_id]').val("").trigger('change');
				parent.find('input[name=rate]').val(rate);
			}



			var discount_value = isNaN(discount_id) ? 0 : discount_id/100;
			var amount = isNaN(parent.find('input[name=rate]').val()) ? 0 : (parent.find('input[name=rate]').val()*quantity).toFixed(2);
			var tax_amount = amount*tax_value;
			var discount_amount = amount*discount_value;        

			parent.find('input[name=amount]').val(amount); 

			table();

		});

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
	});


	$('body').on('blur', 'input[name=quantity], input[name=rate], input[name=discount], select[name=tax_id], select[name=discount_id], input[name=discount_value], input[name=in_stock]', function(){
			
		var obj = $(this);
		var parent = obj.closest('tr');
		var tax_type = $('select[name=tax_types]').val();
		var rate =  parent.find('input[name=rate]').val();
		var in_stock =  parent.find('input[name=in_stock]').val();
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

				service_type: {
					required: true
				},

				registration_number: {
					required: true
				}  

				/*order_id: {

					remote: {

						url: "{{ route('get_transaction_order') }}",

						type: "post",

						data: {

						 _token : '{{ csrf_token() }}',

						 type: $(".transactionform select[name=order_type]").val()

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
					required: "Voucher Term is required"
				},

				

				due_date: {
					required: "Due Date is required"
				},

				jobcard_status_id: {
					required: "Jobcard Status is required"
				},

				service_type: {
					required: "Service Type is required"
				},

				registration_number: {
					required: "Registration No. is required"
				}

				/*order_id: {
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
				$('.tab_approve_save_btn').attr("disabled", true);

				var from_page = $('.full_modal_content').attr("data-id");

				var transaction_module = ('{{ $transaction_type->module }}');

				/*Transaction Data*/				

				var transaction_data = {

					_token: '{{ csrf_token() }}',

					@if(!empty($transactions))

					_method: 'PATCH',

					id: '{{ $transactions->id }}',

					@endif


					/*Add Item or Job and Parts - WMS*/

					@if($transaction_type->module == 'trade_wms')

						item_id: $('.append_table').find('input[name=append_item_id]').map(function() { 
							return this.value;
						}).get(),

						batch_id: $('.append_table').find('input[name=batch_id]').map(function() { 
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

						new_selling_price: $('.append_table').find('input[name=new_base_price]').map(function()	{
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

					@endif

					/* End */


					@if($transaction_type->module != 'trade_wms')

						item_id: $('select[name=item_id]').map(function()
						{ 
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

						amount: $('input[name=amount]').map(function()
						{
							return this.value; 
						}).get(),

						new_selling_price: $('input[name=new_base_price]').map(function() { 
							return this.value; 
						}).get(),

						selling_price: $('input[name=base_price]').map(function() { 
							return this.value; 
						}).get(),

						tax_id: $('select[name=tax_id]').map(function()
						{
							return this.value; 
						}).get(),

						discount_id: $('select[name=discount_id]').map(function() { 
							return this.value;
						}).get(),

						discount_value: $('input[name=discount_value]').map(function() { 
							return this.value; 
						}).get(),

						assigned_employee_id: $('select[name=assigned_employee_id]').map(function()
						{ 
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

						stock_update: $('input[name=stock_update]:checked').val(),
						complaints: $('textarea[name=complaint]').val(),
						discount: $('input[name=discount]').val(),
						discount_is_percent: $('input[name=discount_is_percent]:checked').val(),
						
						over_all_discount : $('input[name=new_discount_value]').val(),

						attachment_uid:$('input[name=attachment_uid]').val(),
						shift_id: $('select[name=shift_id]').val(),
						pump_id: $('select[name=pump_id]').val(),
						tax_type: $('select[name=tax_types]').val(),
						driver : $('input[name=driver]').val(),
						driver_contact : $('input[name=driver_contact]').val(),

					
						reference_id: $('input[name=reference_id]').val(),
						type: '{{ $transaction_type->name }}',
					
						person_type: $('input[name=account_person_type_id]:checked').val(),
						people_type: $('input[name=customer]:checked').val(),
						people_id: $('select[name=people_id]:not([disabled])').val(),

						invoice_date: $('input[name=invoice_date]').val(),
						due_date: $('input[name=due_date]').val(),
					
						term_id: $('select[name=voucher_term_id]').val(),
						
						
						order_id: $('input[name=order_id]').val(),
						payment_method_id: $('select[name=payment_method_id]').val(),
						ledger_id: $('select[name=ledger_id]').val(),
						employee_id: $('select[name=employee_id]').val(),

						update_customer_info : $('input[name=update_customer_info]').val(),
						name: $('input[name=customer_name]').val(),
						mobile: $('input[name=customer_mobile]').val(),
						email: $('input[name=customer_email]').val(),
						gst: $('input[name=customer_gst]').val(),

						address: ($('textarea[name=customer_address]').val()).replace('\n', '<br>'),
						billing: $('input[name=billing_checkbox]:checked').val(),
						billing_name: $('input[name=billing_name]').val(),
						billing_mobile: $('input[name=billing_mobile]').val(),
						billing_email: $('input[name=billing_email]').val(),
						billing_gst:$('input[name=billing_gst]').val(),
						billing_address: ($('textarea[name=billing_address]').val()).replace('\n', '<br>'),

						shipping: $('input[name=shipping_checkbox]:checked').val(),
						shipping_name: $('input[name=shipping_name]').val(),
						shipping_mobile: $('input[name=shipping_mobile]').val(),
						shipping_email: $('input[name=shipping_email]').val(),
						shipping_address: ($('textarea[name=shipping_address]').val()).replace('\n', '<br>'),
						shipment_mode_id: $('select[name=shipment_mode_id]').val(),
						shipping_date: $('input[name=shipping_date]').val(),
			

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

						payment_terms: $('select[name=payment_terms]').val(),
						service_type: $('select[name=service_type]').val(),
						registration_no: $('select[name=registration_number]').val(),
						engine_no: $('input[name=engine_number]').val(),
						chasis_no: $('input[name=chassis_number]').val(),
					
						delivery_details: $('input[name=delivery_details]').val(),					

						vehicle_last_visit: $('input[name=last_visit]').val(),
						vehicle_last_job: $('input[name=last_job_card]').val(),
						vehicle_next_visit: $('input[name=next_visit_date]').val(),
						vehicle_mileage: $('input[name=vehicle_mileage]').val(),

						next_visit_mileage: $('input[name=next_visit_mileage]').val(),
						vehicle_next_visit_reason: $('input[name=next_visit_reason]').val(),
						vehicle_note: $('textarea[name=vehicle_note]').val(),
					
						job_date: $('input[name=job_date]').val(),
						
						job_due_date: $('input[name=job_due_date]').val(),
						job_completed_date: $('input[name=job_completed_date]').val(),
						jobcard_status_id: $('input[name=jobcard_status_id]').val(),

					

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

						
						return $(this).is(":checked") ? 1 : 0;
							//return $(this).attr("checked") ? 1 : 0;;
						}).get(),

						checklist_notes:$('input[name=wms_checklist_notes]').map(function() { 
							return this.value; 
						}).get(),

				};

				/* Transaction Data End */


				var txn_id = transaction_id;


				if(txn_id)
				{
					var txn_url = "{{ route('transaction.update') }}";

					transaction_data.id = txn_id;
					transaction_data._method = 'PATCH';

				}else{
					
					var txn_url = "{{ route('transaction.store', $transaction_type->name) }}";
				}

				$.ajax({

					url: txn_url,
					type: 'post',
					data: transaction_data,	
					beforeSend:function() {
						$('.loader_wall_onspot').show();
						//$('.tab_save_btn').attr("disabled", true);
						//$('.tab_save_close_btn').attr("disabled", true);
						//$('.tab_approve_save_btn').attr("disabled", true);
					},

					 dataType: "json",

					success:function(data, textStatus, jqXHR)
					{

						if(transaction_id == null)
						{
							$('.tab_approve_save_btn').attr("disabled", true);
							$('.tab_save_btn').attr("disabled", true);
							$('.tab_save_close_btn').attr("disabled", true);
						}

						if(transaction_id == null)
						{
							$('.tab_approve_save_btn').attr("disabled", false);
							$('.tab_save_btn').attr("disabled", false);
							$('.tab_save_close_btn').attr("disabled", false);
						}

						if(from_page == 2){
								
								$('.loader_wall_onspot').hide();
								location.assign("{{route('trade_wms.schedule_board')}}");
								$('.alert-success').text('Transaction Created Successfully..!');
								$('.alert-success').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
								return false;
								
						}/*else if(tab_save_close == true){
								$('.close_full_modal').trigger('click');

							}*/else if(tab_save_close == true && jc_save_close == true){
								
									$('.loader_wall_onspot').hide();
									location.assign("{{route('home_page.index')}}");	
									/*$('.alert-success').text('Transaction Created Successfully..!');
									$('.alert-success').show();

									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
									return false;*/

							}else if(jc_store_only == true){
								
								$('.loader_wall_onspot').hide();
								$('.alert-success').text('Transaction Created Successfully..!');
								$('.alert-success').show();
									setTimeout(function() { $('.alert').fadeOut(); }, 3000);
									return false;
							}
							else if(data.status == "0") {

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

							transaction_id = data.data.id;
							

							var customer = ($('select[name=people_id]:not([disabled]) option:selected').val() == "") ? '' : $('select[name=people_id]:not([disabled]) option:selected').text();

							var selected_text = "Pending";
							var selected_class = "badge-warning";
							var approval_text = "Draft";
							var approval_class = "badge-warning";

							if(data.data.transaction_type == "sales_cash") {
								selected_text = "Paid";
								selected_class = "badge-success";
							}

							else if(data.data.status == 0) {

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

							} 
							else if(data.data.approval_status == 1) {

								approval_text = "Approved";
								approval_class = "badge-success";

							}

							var approve_selected = "";

							var draft_selected = "";

								var html = "";

									html +=`<tr>

									<td>

										<input id="`+data.data.id+`" class="item_checkbox" name="transaction" value="`+data.data.id+`" type="checkbox">

										<label for="`+data.data.id+`"><span></span></label>
									</td>

									<td>`+data.data.order_no+`</td>     

									@if($transaction_type->name == "purchases" || $transaction_type->name == "estimation" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash")

									<td>`+data.data.reference_type+`</td> @endif

									@if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
										<td>`+data.data.jc_order_no+`</td>
									@endif


									@if($transaction_type->name == "purchases" || $transaction_type->name == "estimation" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "goods_receipt_note" || 

										$transaction_type->name == "delivery_note" || 

										$transaction_type->name == "job_invoice" || 

										$transaction_type->name == "job_invoice_cash" || $transaction_type->name == "job_request")

										<td>`+data.data.reference_no+`</td> @endif


									@if($transaction_type->name == "job_card" 
									|| $transaction_type->name == "job_request"
									|| $transaction_type->name == 'job_invoice' 
									|| $transaction_type->name == "job_invoice_cash")
									<td>`+data.data.registration_id+`</td> @endif


									@if($transaction_type->name == "job_request")

										<td>`+data.data.service_type+`</td> 
									@endif    

									<td>`+data.data.people+`</td>


									@if($transaction_type->module == "trade" || $transaction_type->module == "inventory")

										<td>`+data.data.people_contact+`</td>
									@endif


									@if($transaction_type->name == "job_card"
									)
									<td>`+data.data.assigned_to+`</td> 
									
									@endif


									<td>`+data.data.total+`</td>

									@if($transaction_type->name == "job_card" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash"
									)
								
									<td>`+data.data.advance_amount+`</td> 
									@endif
									


									@if($transaction_type->name == "job_card" 

									|| $transaction_type->name == "job_request" 

									|| $transaction_type->name == "job_invoice" 

									|| $transaction_type->name == "job_invoice_cash")

									<td>`+data.data.job_date+`</td> @endif


									@if($transaction_type->module == "trade" || $transaction_type->module == "inventory")

									<td>`+data.data.date+`</td> @endif `;


									if(/*data.data.transaction_type == "purchases" || 

										data.data.transaction_type == "sales" || 
										data.data.transaction_type == "sales_cash" || 
										data.data.transaction_type == "delivery_note" ||
										data.data.transaction_type == "credit_note" || 
										data.data.transaction_type == "debit_note" ||*/
										data.data.transaction_type == "sale_order" )
									{

										html +=`<td>`+data.data.due_date+`</td>`;

									}

									/*if( data.data.transaction_type == "job_invoice"
										|| data.data.transaction_type == "job_invoice_cash" )
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
										data.data.transaction_type == "job_invoice" || 
										data.data.transaction_type == "job_invoice_cash"  ) 

									{
										if(data.data.transaction_type == "sales_cash" || data.data.transaction_type == "job_invoice_cash") {

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
										if(data.data.approval_status == 1 || 

										data.data.transaction_type == "sales_cash" || 

										data.data.transaction_type == "job_invoice_cash") {

										approve_selected = "selected";

										} else if(data.data.status == 0) {

										draft_selected = "selected";

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
										

									if(data.data.transaction_type == "purchase_order"|| data.data.transaction_type == "purchases" || data.data.transaction_type == "goods_receipt_note"
										|| data.data.transaction_type == "debit_note" || data.data.transaction_type == "estimation"|| data.data.transaction_type == "sale_order"|| data.data.transaction_type == "sales" || data.data.transaction_type == "sales_cash" || data.data.transaction_type == "delivery_note" || data.data.transaction_type == "credit_note")
									{
										html+=`<td>
										
					<div class="action_options">
					</div>
					<button type="button" class="btn btn-info" id="actions"><span class="fa fa-caret-down"></span>&nbsp;Action</button></td>`;
							}

								html+=`</tr>`;


								if(txn_id == null){
									call_back(html, `add`, data.message);
								}else{
									call_back(html, `edit`, data.message, data.data.id);	
								}							


							var transaction_module =('{{ $transaction_type->module }}');

							if(transaction_module == "fuel_station"){

								var easy_way = $('.full_modal_content').attr("data-id");

								if(easy_way != 0 || easy_way == '' || easy_way == null){

									@if(!empty($transactions))

									call_back(html, `edit`, data.message, data.data.id);

									@else

									call_back(html, `add`, data.message);
									@endif 							
								}
							}


							if(tab_save_close == true) {
								$('.close_full_modal').trigger('click');
							}


							if(approve == 1) {

								$('.tab_approve_save_btn').text("Approved");

								$('.tab_save_close_btn').hide();
								$('.tab_save_btn').hide();

								if(data.data.transaction_type == "estimation"){

									$('.tab_send_btn').text("Send Estimate");

									$('.tab_copy_btn').text("Copy to SO");

									$('.tab_copy_btn_credit').show();

									$('.tab_copy_btn_cash').show();
								}

								if(data.data.transaction_type == "sale_order"){

									$('.tab_send_btn').text("Send SO");

									$('.tab_copy_btn_credit').show();

									$('.tab_copy_btn_cash').show();
								}

								if(data.data.transaction_type == "sales" || data.data.transaction_type == "sales_cash"){

									$('.tab_send_btn').text("Send Invoice");
									$('.tab_copy_btn').text("Copy to Delivery Challan");
								}

								if(data.data.transaction_type == "delivery_note" ){

									$('.tab_send_btn').text("Send Delivery Challan");					
								}

								if(data.data.transaction_type == "purchase_order" ){

									$('.tab_send_btn').text("Send PO");

									$('#sms_btn').show();

								}

								if(data.data.transaction_type == "purchases" ){             

									$('.tab_copy_btn').text("Copy to GRN");

								}

								$('.tab_send_btn').show();
								$('#sms_btn').show();
								//$('.tab_copy_btn').show();
								$('.tab_print_btn').show();
								$('.tab_update_goods_btn').show();

								transaction_id = data.data.id;
							}


							  
							$('.loader_wall_onspot').hide();
							$('.tab_save_btn').attr("disabled", false);
							$('.tab_save_close_btn').attr("disabled", false);
							$('.tab_approve_save_btn').attr("disabled", false);

							//call_back(html, `add`, data.message);
							//$('.alert-success').text('Transaction Created Successfully..!');
							//$('.alert-success').show();
							//setTimeout(function() { $('.alert').fadeOut(); }, 3000);

							

						}

					},

					error:function(jqXHR, textStatus, errorThrown) {

						//alert("New Request Failed " +textStatus);

					}
				});
				

			}

		}); 


	 /* Getting datas of vehicles based on license number */

		$('select[name=registration_number]').on('change', function(event) {
		
			var id = $('select[name=registration_number]').val();

			if(id != ""){

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
											
						$('#people_type').prop('checked',true);
						$('#business_type').prop('checked', false);
						$('.people').show();
						$('.business').hide();
						$('.business select[name=people_id]').prop('disabled', true);
						$('.people select[name=people_id]').prop('disabled', false);
						$('.people select[name=people_id]').append("<option value='"+data.data.owner_id+"'>"+data.data.cus_name+"</option>");
						trigger_people = $('.people select[name=people_id]');

					}

					else if(data.data.user_type == 1) {	

						$('#business_type').prop('checked', true);
						$('#people_type').prop('checked', false);
						$('.people').hide();
						$('.business').show();
						$('.business select[name=people_id]').prop('disabled',false);
						$('.people select[name=people_id]').prop('disabled', true);
						$('.business select[name=people_id]').append("<option value='"+data.data.owner_id+"'>"+data.data.cus_name+"</option>");
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
					$("#vehicle_last_visit").val(data.data.last_update_date);
					$("#last_update_jc").val(data.data.last_update_jc);
					$("#driver").val(data.data.driver);
					$("#driver_contact").val(data.data.driver_contact);
					$("#cus_name").val(data.data.cus_name);
					$('#customer_gst').val(data.data.gst);
					$('#billing_gst').val(data.data.gst);
					

					if(data.data.additional_contacts == " "){
						$('#additional_contacts').css('display','none');
					}
					else{
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

					$("#month_due_date").val(data.data.month_due_date);
					$("#warranty_km").val(data.data.warranty_km);
					$("#warrenty_yrs").val(data.data.warranty_yrs);
					$("#vehicle_name").val(data.data.vehicle_name);			

					if(data.data.group_name != null)
					{
						$("#group_name_show").val(data.data.group_name.name);
					}
					else
					{
						$("#group_name_show").val("");
					}

					var spec =data.data.spec.specification;
					var spec_values = data.data.spec_values.spec_values;
					var specification = '';                    

					if(spec){

					for (i = 0; i < spec.length; ++i) {

						//var spec = $(".spec_values"+i).val(data.data.spec_values.spec_values[i]);
					$("#specification tbody tr").remove(); 

					specification += "<tr><td>"+spec[i]+"</td><td>"+spec_values[i]+"</td></tr>";                       

					}                   

					$('#specification').find('tbody').append(specification);

					}

					$("#engine_no, #chassis_no, #purchase_date, #drivetrain, #fuel_type, #vehicle_category, #no_of_wheels, #vehicle_make, #rim_wheel, #vehicle_model, #tyre_size, #vehicle_variant, #body_type, #vehicle_usage, #vehicle_version,#vehicle_last_visit,#last_update_jc,#driver,#permit_type,#fc_due,#permit_due,#tax_due,#vehicle_insurance,#bank_loan,#month_due_date,#warranty_km,#warrenty_yrs,#group_name_show,#vehicle_name,#specification,#vehicle_name").trigger('change');

					$(trigger_people).trigger('change');

				},

				error:function(jqXHR, textStatus, errorThrown) {

					//alert("New Request Failed " +textStatus);

				}

			});

			}

		});

	/* End of getting datas of vehicles based on license number */  



	function order(id, type, status) {	

		var obj = $('select[name=item_id]');
		var vehicle_id = $('select[name=registration_number]').val();
		var transaction_module =  ('{{ $transaction_type->module }}');		

		$.ajax({
			url: "{{ route('get_order_details') }}",
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				order_id: id,
				type: type,
				status: status 
			 },

			success:function(data, textStatus, jqXHR) {

				$('.transactionform input:not(input[name=invoice_date]):not(input[type=button]):not(input[type=submit]):not(input[type=reset]):input:not(input[name=_token]):not(input[type=radio]):not(input[type=checkbox])').val("");

					$('.transactionform select:not([name=reference_type]):not([name=tax_types]):not([name=employee_id])').val("");

					$('.transactionform select:not([name=reference_type]):not([name=tax_types]):not("#state")').trigger('change');                



					$('select[name=order_type]').val(type);
					$('select[name=order_type]').trigger('change');
					$('input[name=order_id]').val(id);

					var transactions = data.response;
					var transaction_items = data.data;
					var item_batch = data.item_batch;
					var service_batch = data.service_batch;


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

					$('select[name=people_id]').val(data.response.people_id);
					$('select[name=people_id]').trigger('change');


					setTimeout(function() {

						$('input[name=customer_name]').val(data.response.name);
						$('input[name=customer_mobile]').val(data.response.mobile);
						$('input[name=customer_email]').val(data.response.email);
						$('textarea[name=customer_address]').val((data.response.address).replace("<br>", "\n"));

						$('input[name=billing_name]').val(data.response.billing_name);
						$('input[name=billing_mobile]').val(data.response.billing_mobile);
						$('input[name=billing_email]').val(data.response.billing_email);
						//$('textarea[name=billing_address]').val((data.response.billing_address).replace("<br>", "\n"));
						$('input[name=shipping_name]').val(data.response.shipping_name);
						$('input[name=shipping_mobile]').val(data.response.shipping_mobile);
						$('input[name=shipping_email]').val(data.response.shipping_email);
						//$('textarea[name=shipping_address]').val((data.response.shipping_address).replace("<br>", "\n"));

					}, 500);



					$('select[name=registration_number]').val(data.response.registration_id);
					$('select[name=registration_number]').trigger('change');
					$('select[name=vehicle_category]').val(data.response.vehicle_category_id);
					$('select[name=vehicle_make]').val(data.response.vehicle_make_id);
					$('select[name=vehicle_model]').val(data.response.vehicle_model_id);
					$('select[name=vehicle_variant]').val(data.response.vehicle_variant_id);
					$('input[name=name_of_job]').val(data.response.name_of_job);
					$('input[name=job_date]').val(data.response.job_date);
					$('input[name=job_due_date]').val(data.response.job_due_date);
					$('input[name=job_completed_date]').val(data.response.job_completed_date);
					$('select[name=service_type]').val(data.response.service_type);                     
					$('input[name=next_visit_date]').val(data.response.vehicle_next_visit);
					$('input[name=vehicle_mileage]').val(data.response.vehicle_mileage);
					$('input[name=next_visit_mileage]').val(data.response.vehicle_mileage);
					$('input[name=next_visit_reason]').val(data.response.vehicle_next_visit_reason);
					$('textarea[name=vehicle_note]').val(data.response.vehicle_note);
					$('textarea[name=complaint]').val(data.response.vehicle_complaints);
					$('select[name=voucher_term_id]').val(data.response.term_id);
					$('input[name=reference_id]').val(data.response.id);

					//$('select[name=voucher_term_id]').trigger('change'); 

					@if(!empty($transactions))

					$('input[name=invoice_date]').val(data.response.date);

					$('input[name=due_date]').val(data.response.due_date);

					@endif



					$('select[name=payment_method_id]').val(data.response.payment_method_id);
					$('select[name=payment_method_id]').trigger('change');
					$('select[name=employee_id]').val(data.response.employee_id);
					$('select[name=employee_id]').trigger('change');
					$('textarea[name=billing_address]').val(data.response.billing_address);

					/*if(data.response.shipment_mode_id != null) {

						$('input[name=shipping_checkbox]').prop('checked', true);

						$(".shipping").show();

					}*/

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

							transaction_item.find('input[name=in_stock]').val(transaction_items[i].batch_stock);						

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

							
						}
						
					}
						

					var row_index = $('.crud_table tbody > tr').length;

					$('.append_table').find('tr').find('td:last').html('<a class="grid_label action-btn delete-icon remove_row_append"><i class="fa fa-trash-o"></i></a>');

					$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row_append"><i class="fa fa-plus"></i></a>');


				}							

				$('.select_item').select2();

				table();

			},

			error:function(jqXHR, textStatus, errorThrown) {}

		});
	}


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

		var row_index = $('.crud_table tbody > tr').length;	

		//console.log(row_index);
		//clone.closest('tr').attr("id","tr_"+row_index);

		$('body').find('.items').find('input[name=amount], select[name=tax_id], select[name=discount_id], input[name=discount_value]').each(function() 
		{
			if($(this).attr('name') == 'amount') {
				amount += parseFloat(($(this).val()) ? $(this).val():0);
				//console.log(amount);
			}

			else if($(this).attr('name') == 'tax_id') {

				var tax_value = $(this).find('option:selected').data('value');

				tax_amount += parseFloat( isNaN(tax_value) ? 0 : tax_value/100 ) * ($(this).closest('tr').find('input[name=amount]').val());
			}

			else if($(this).attr('name') == 'discount_id' || $(this).attr('name') == 'discount_value')
			{
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


		//var tax_value = $(this).find('option:selected').data('value');

		//var single_item = parseFloat( isNaN(tax_value) ? 0 : tax_value/100 ) * ($(this).closest('tr').find('input[name=amount]').val());


		$('.total_rows').find('tr.discount_row').remove();

		sum_discount = parseFloat(0.00);
		var discount_name_array = [];
		var discount_value_array = [];
		var discount_amount_array = [];
		var discount_item_amount = [];


		$('body').find('.items').find('input[name=discount_value]').each(function() 
		{
			var obj = $(this);

			if(obj.val() != "") {

				var discount_value = obj.val();

				var discount_name = (obj.closest('tr').find(' select[name=discount_id]').val() != "") ? obj.closest('tr').find(' select[name=discount_id] option:selected').text() : discount_value;

				var item_amount = parseFloat(obj.closest('tr').find('input[name=amount]').val());

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


		$('body').find('.items').find('select[name=tax_id]').each(function()
		{		
			var obj = $(this);
			var data = obj.find('option:selected').data('tax'); 
			var tax_value1 = $(this).find('option:selected').data('value');

			var item_id = obj.closest('tr').find('select[name=item_id]').val();		


			/* tax Include */

			//var   tax_amount1 = parseFloat(isNaN(tax_value1) ? 0 : tax_value1/100 ) * ($(this).closest('tr').find('input[name=amount]').val());

			var rate_element = ((obj.closest('tr').find('input[name=rate]').val()).isNaN) ? 0 : obj.closest('tr').find('input[name=rate]').val();			

			var amount_element = ((obj.closest('tr').find('input[name=amount]').val()).isNaN) ? 0 : obj.closest('tr').find('input[name=amount]').val();

			var single_discount = parseFloat(obj.closest('tr').find('input[name=discount_value]').val());

			var single_item_discount = parseFloat(( isNaN(single_discount) ? 0 : single_discount)/100)*(amount_element);

			var single_item_amount = (parseFloat(amount_element) - parseFloat(single_item_discount).toFixed(2));

			var single_item_rate = (parseFloat(rate_element) - parseFloat(single_item_discount).toFixed(2));

			var single_total_tax = (parseFloat(( isNaN(tax_value1) ? 0 : tax_value1)/100)*parseFloat(single_item_amount).toFixed(2));

			var single_rate_tax = (parseFloat(( isNaN(tax_value1) ? 0 : tax_value1)/100)*parseFloat(single_item_rate).toFixed(2));


			if(obj.val() != "") {

				for(var i in data) {

					var tax_type = $('select[name=tax_types]').val();

					@if($type == 'purchase_order' || $type == 'purchases' || $type == 'debit_note')

						var tax_included = obj.closest('tr').find('select[name=item_id] option:selected').data('purchase_tax');

					@elseif($type == 'sale_order' || $type == 'sales' || $type == 'sales_cash' || $type == 'delivery_note')

						var tax_included = obj.closest('tr').find('select[name=item_id] option:selected').data('tax');

					@elseif($type == 'job_card' || $type == 'job_request' || $type == 'job_invoice' || $type == 'job_invoice_cash')

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

					} 

					else if(tax_type == 2) {  //exclude

						//item_amount = (parseFloat(amount_element)).toFixed(2);

						//total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(item_amount);

						item_amount = (parseFloat(amount_element) - parseFloat(single_item_discount).toFixed(2));						

						total_tax = (parseFloat((( isNaN(tax_value) ? 0 : tax_value)/100))*(item_amount).toFixed(2));
					} 

					else if(tax_type == 0) { //no

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

				//end

				/* item unit price with tax*/
				/*var price_plus_tax = obj.closest('tr').find('input[name=price_plus_tax]');

				var price_plus_tax_toal = (parseFloat(single_item_rate)  + parseFloat(single_rate_tax));

				price_plus_tax.val( (parseFloat(isNaN(price_plus_tax_toal) ?0.00 : parseFloat(price_plus_tax_toal) )).toFixed(2));*/

				/*end*/
				

				creditLimit();

				/*},

				 error:function(jqXHR, textStatus, errorThrown) {

				 }

				});*/

			}

			else{ /* if tax is null for separate item */

				var taxtotal = obj.closest('tr').find('input[name=tax_total]');

				var single_item_tax_toal = (parseFloat(single_item_amount)  + parseFloat(single_total_tax));

				taxtotal.val((parseFloat(isNaN(single_item_tax_toal) ? 0.00 :  parseFloat(single_item_tax_toal) )).toFixed(2));
			}


			//single item tax amount - row

				var taxamount = obj.closest('tr').find('input[name=tax_amount]');

				taxamount.val((parseFloat(isNaN(single_total_tax) ? 0.00 :parseFloat(single_total_tax) )).toFixed(2));
				
			// end
				
			//single item tax with total - row

				var taxtotal = obj.closest('tr').find('input[name=tax_total]');

				var single_item_tax_toal = (parseFloat(single_item_amount)  + parseFloat(single_total_tax));

				taxtotal.val( (parseFloat(isNaN(single_item_tax_toal) ?0.00 : parseFloat(single_item_tax_toal) )).toFixed(2));

			//end

		});	

		
		

		/* Box - discount */

		if(discount != null) 
		{
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

		//start

		//to get credit limit-total    

		$tot = $('.sub_total').text(); 

		//$credit = $('.credit_limit_value').val();

		$credit = $('input[name=credit_limit_text]').val();     

		$credit_limit_total = $credit-$tot;     

		 $('.credit_limit_value').text(parseFloat($credit_limit_total).toFixed(2));


		//end

		creditLimit();

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



	$(document).ready(function(){

				var BeforeImages = new Dropzone("div#BeforeImages", {		

					method: 'POST',

					paramName: 'file',

						params:{

							_token:'{{ csrf_token() }}',

							attachment_uid:'{{$uuid}}',

							image_category:1

							},

						url: "{{ url('/transaction/attachments/wms_attachment') }}",

						dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",

						clickable: true,

						maxFilesize: 10, // MB

						parallelUploads: 10,

						acceptedFiles: "image/*",

						maxFiles: 10,

						uploadMultiple: true,

						autoProcessQueue: false,

						addRemoveLinks: true,

						removedfile: function (file) {						

								file.previewElement.remove();			

								},

						queuecomplete: function () {	

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



						



								var ProgressImages = new Dropzone("div#ProgressImages", {

					

										method: 'POST',

										paramName: 'file',

										params:{

													_token:'{{ csrf_token() }}',

													attachment_uid:'{{$uuid}}',

													image_category:2



											},

										url: "{{ url('/transaction/attachments/wms_attachment') }}",

										dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",

										clickable: true,

										maxFilesize: 10, // MB

										parallelUploads: 10,

										acceptedFiles: "image/*",

										maxFiles: 10,

										uploadMultiple: true,

										autoProcessQueue: false,

										addRemoveLinks: true,

										removedfile: function (file) {

											// 

												file.previewElement.remove();

											 //$(this).remove();

												},

										queuecomplete: function () {



										//      ProgressImages.removeAllFiles();



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



				var AfterImages = new Dropzone("div#AfterImages", {			

					method: 'POST',

					paramName: 'file',

					params:{

						_token:'{{ csrf_token() }}',

						attachment_uid:'{{$uuid}}',

						image_category:3

					},

					url: "{{ url('/transaction/attachments/wms_attachment') }}",

					dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",

					clickable: true,

					maxFilesize: 10, // MB

					parallelUploads: 10,

					acceptedFiles: "image/*",

					maxFiles: 10,

					uploadMultiple: true,

					autoProcessQueue: false,

					addRemoveLinks: true,

					removedfile: function (file) {					

						file.previewElement.remove();				

					},

					queuecomplete: function () {

					//  AfterImages.removeAllFiles();
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


					 /* init: function () {

					//  document.querySelector("li.remove-files").onclick = function() {

					//  Task_attachments.removeAllFiles(true);



					this.on('addedfile', function (file) {				

					$('.dz-remove').hide();

					*/



					 $('#SaveBeforeImg').click(function(e){  

						e.preventDefault();

						$('#BeforePBar').show();

						BeforeImages.processQueue();

					});


					$('#SavePImg').click(function(e){  

						e.preventDefault();

						$('#ProgressPBar').show();

						ProgressImages.processQueue();

					});

					$('#SaveAfterImg').click(function(e){  

						e.preventDefault();
						$('#AfterPBar').show();
						AfterImages.processQueue();

					})



			status=0;	

	});


</script> 

<script>

		ClassicEditor.create(document.querySelector( '#editor' ),{

			removePlugins: [ 'Heading', 'Link' ,'bold', 'italic','blockQuote','bulletedList' ],

		toolbar: ['numberedList']

		}

	);		

</script>

{{--

@stop

--}}

