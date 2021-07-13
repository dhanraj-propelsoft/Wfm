{{--
@extends('layouts.master')

@section('content')
@include('includes.add_user')
@include('includes.add_business')

--}}




<style>
label {
 margin: .5rem 0;
}
</style>


<div class="content">

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
  <!-- </div> -->
  <div class="clearfix"></div>
  {!! Form::open(['class' => 'form-horizontal transactionform']) !!}
  {{ csrf_field() }} 
  <!--   <div class="modal-body"> --> 

<div class="form-body" style="padding: 15px 25px 55px; margin-top: 15px; ">
  <ul class="nav nav-tabs">
    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#order_details">Order Details</a> </li>
    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#item_details">Item Details</a> </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="order_details">

		<div class="form-group">
				<div class="row">

					<div style=" @if(count($reference_voucher) <= 1) display: none; @endif " class="col-md-3">
					<label for="reference_type">Reference Type</label>

						<select name='reference_type' class='form-control select_item terms' id = 'reference_type'>

							@foreach($reference_voucher as $reference)					

							 <option @if($selected_reference_voucher == $reference->name) selected @endif value="{{$reference->name}}">{{$reference->display_name}}</option>
							@endforeach
						</select>
					</div>



					@if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash")
					<div class="col-md-3">
						<label class="control-label required" for="order_id">Type</label> <br>
						<input id="cash_type" type="radio" name="sale_type" value="cash" @if($transaction_type->name == "sales_cash") checked="checked" @endif />
						<label for="cash_type"><span></span>Cash</label>
						<input id="credit_type" type="radio" name="sale_type"  value="credit" @if($transaction_type->name == "sales") checked="checked" @endif />
						<label for="credit_type"><span></span>Credit</label>
					</div>
					@endif

					@if($transaction_type->name != "sales_cash")

						<div class="col-md-3" style= " display:none ">
							<label for="order_id">{{$order_type}}</label>

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
					@endif
			</div>
			</div>

		<div class="form-group">
				<div class="row">
					<div class="col-md-3 customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
					{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
					<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
					<label for="business_type"><span></span>Business</label>
					<input id="people_type" type="radio" name="customer" value="0" />
					<label for="people_type"><span></span>People</label>
					</div>
					<div class="col-md-3 search_container people" style= "@if($customer_label == null) display:none @endif"> 
						{{ Form::label('people', $customer_label, array('class' => 'control-label required')) }}

						{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id', 'disabled']) }}
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


					<div class="col-md-3" style= "@if($date_label == null) display:none @endif">
					<label class="required" for="date">{{$date_label}}</label>
						{{ Form::text('invoice_date', ($transaction_type->date_setting == 0) ? date('d-m-Y') : null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 
					</div>
					
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

					@if($transaction_type->name != "sales_cash")
					<div class="col-md-3" style= "@if($term_label == null) display:none @endif">
					<label for="voucher_term_id">{{$term_label}}</label>
						<select name='voucher_term_id' class='form-control select_item terms' id = 'voucher_term_id'>
							 <option value="">Select Term</option>
							 @foreach($voucher_terms as $term) 
							 <option value="{{$term->id}}" data-value="{{$term->days}}">{{$term->display_name}}</option>
							 @endforeach
						</select>
					</div>
					@endif					
					
				</div>
			</div>


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

			<div class="form-group">
				<div class="row">

					@if(!empty($job))
						@if($job == "job")
							<div class="col-md-3">
								<label class="required" for="make_id">Make</label>
								{{ Form::select('make_id', $make, $selected_make, ['class' => 'form-control select_item', 'id' => 'make_id']) }} </div>
							<div class="col-md-3">
							<label class="required" for="vehicle_model_id">Model</label>
							<?php $vehicle_model_id = (!empty($transactions->vehicle_model_id)) ? $transactions->vehicle_model_id : null; ?>
							{{ Form::select('vehicle_model_id', $model, $vehicle_model_id, ['class' => 'form-control select_item', 'id' => 'vehicle_model_id']) }} </div>
						@endif
					@endif
				
					@if($transaction_type->name != "sales_cash")
					<div class="col-md-3"  style= "@if($due_date_label == null) display:none @endif">
						<label for="due_date">{{$due_date_label}}</label>



						{{ Form::text('due_date', $due_date, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'disabled']) }} </div>
					@endif

					<div class="col-md-3" style= "@if($payment_label == null) display:none @endif">
					<label for="payment_mode">{{$payment_label}}</label>
					{{ Form::select('payment_method_id', $payment, null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }}

				</div>
				
					@if($type == "job_card")
					<div class="col-md-3">
					<label for="employee_id">Job Types</label>
					{{ Form::select('job_type_id', $job_type, null, ['class' => 'form-control select_item', 'id' => 'employee_id',  'multiple' => 'multiple']) }} </div>
					@endif

					<div class="col-md-3" style= "@if($sales_person_label == null) display:none @endif">
					<label for="employee_id">{{$sales_person_label}}</label>
					{{ Form::select('employee_id', $employees, null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }} </div>

					<div class="col-md-3">
					<label for="date">Shippment Mode</label>
					{{ Form::select('shipment_mode_id', $shipment_mode, '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }} </div>

					<div class="col-md-3">
						
					<label for="shipping_date">Shipping Date</label>
					{{ Form::text('shipping_date',null, ['class'=>'form-control datetype date-picker','data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'disabled']) }} </div>
				</div>
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

			<div class="form-group">
				<div class="row">


					<div class="col-md-12">
						<div class="row">
						<div class="col-md-12">
							<label><b>{{$address_label}}</b></label>
							</div>

							<div class="col-md-3">
							<label for="date">Name</label>
							{{ Form::text('customer_name',null, ['class'=>'form-control display_name', 'autocomplete' => 'off']) }} </div>

							<div class="col-md-3">
							<label for="date">Mobile</label>
							{{ Form::text('customer_mobile', null, ['class'=>'form-control mobile', 'autocomplete' => 'off']) }} </div>

							<div class="col-md-3">
							<label for="date">Email</label>
							{{ Form::text('customer_email', null, ['class'=>'form-control email', 'autocomplete' => 'off']) }} </div>

							<div class="col-md-3">
							<label for="date">Address:</label>
							{{ Form::textarea('customer_address', null, ['class'=>'form-control address', 'size' => '30x2']) }} 
							</div>	
						</div>
					</div>

					<div class="col-md-12">
						<div class="row">
							

							<div class="col-md-12">

								<label><!-- <b>Billing Address</b> -->
									<div class="row">
										<div class="col-md-12"> {{ Form::checkbox('billing_checkbox', '1', $company_label, array('id' => 'billing_checkbox')) }}
										<label for="billing_checkbox"><span></span>Billing address is different</label>
									</div>
									</div>
								</label>

							</div>

							<div class="col-md-3  billing">
							<label for="date">Billing Name</label>
							<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="billing_name" value="{{$company_name}}" autocomplete="off" /> </div>

							<div class="col-md-3  billing">
							<label for="date">Billing Mobile</label>
							<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="billing_mobile" value="{{$company_mobile}}" autocomplete="off"  /> </div>

							<div class="col-md-3  billing">
							<label for="date">Billing Email</label>
							<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  email @endif " name="billing_email" value="{{$company_email}}" autocomplete="off"  /> </div>

							<div class="col-md-3  billing">
							<label for="date">Billing Address</label>
							<textarea name="billing_address" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  address @endif " cols="30" rows="2"> {{$company_address}}</textarea> 
							</div>	
						</div>
					</div>

					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">

								<label><!-- <b>Shipping Address</b> -->
									<div class="row">
										<div class="col-md-12"> {{ Form::checkbox('shipping_checkbox', '1', $company_label, array('id' => 'shipping_checkbox')) }}
										<label for="shipping_checkbox"><span></span>Shipping address is different</label>
									</div>
									</div>
								</label>

							</div>

							<div class="col-md-3 shipping">
							<label for="date">Shipping Name</label>
							<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  display_name @endif " name="shipping_name" value="{{$company_name}}" autocomplete="off"  /></div>

							<div class="col-md-3 shipping">
							<label for="date">Shipping Mobile</label>
							<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')  mobile @endif " name="shipping_mobile" value="{{$company_mobile}}" autocomplete="off"  /> </div>

							<div class="col-md-3 shipping">
							<label for="date">Shipping Email</label>
							<input type="text" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   email @endif " name="shipping_email" value="{{$company_email}}" autocomplete="off"  /> </div>

							<div class="col-md-3 shipping">
							<label for="date">Shipping Address</label>
							<textarea name="shipping_address" class="form-control @if($transaction_type->name == 'sales_cash' || $transaction_type->name == 'sales' || $transaction_type->name == 'sale_order' || $transaction_type->name == 'delivery_note' || $transaction_type->name == 'estimation' || $transaction_type->name == 'credit_note')   address @endif " cols="30" rows="2" > {{$company_address}}</textarea>
							</div>	
						</div>
					</div>
				</div>
			</div>

    </div>


    <div class="tab-pane" id="item_details">



		<div class="clearfix"></div>

			<div style="float:right; width: 130px; margin: 10px;"> 
			<select name="tax_types" class='form-control select_item'>
				<option value="2">Exclude Tax</option>
				<option value="1">Include Tax</option>
				<option value="0">Out Of Scope</option>
			</select>
		</div>

<div class="clearfix"></div>

		<div class="form-group">
			<table style="border-collapse: collapse;" class="table table-bordered crud_table">
				<thead>
				<tr>
					<th width="4%">#</th>
					<th width="25%">@if($job == "job" || $type == "job_card") Service @else Item @endif </th>
					<th width="12%">Description</th>
					<th style="@if($job == "job" || $type == "job_card") display: none @endif" width="10%">Quantity</th>
					<th width="8%">Rate</th>
					<th width="8%" style="@if($job == "job" || $type == "job_card") display: none @endif" width="14%">Amount</th>
					<th width="15%">Tax</th>

					@if($discount_option)
					<th width="13%">Discount Type</th>
					<th width="6%">Discount</th>
					@endif
					<th width="7%"></th>
				</tr>
				<tr>
				</thead>
				<tbody>
				<tr>
					<td class="sorter"><span class="index_number" style="float: right; padding-left: 5px;">1</span></td>
					<td><select name="item_id" class="form-control select_item" id="item_id">
						<option value="">Select Item</option>
						<?php $selected_item = null; ?>
						
						
							@foreach($items as $item)
								@if($selected_item != $item->category) 
						
						<optgroup label="{{$item->category}}"> @endif
								
						
						<?php $selected_item = $item->category; ?>
						<option data-tax="{{$item->include_tax}}" data-purchase_tax="{{$item->include_purchase_tax}}" data-rate = "" value="{{$item->id}}">{{$item->name}}</option>
						
						
							@endforeach
								</optgroup>
					</select>
					</td>
					<td>{{ Form::textarea('description', null, ['class'=>'form-control', 'style'=>' height: 26px;' , 'placeholder' => 'Description']) }}</td>
					<td style="@if($job == "job" || $type == "job_card") display: none @endif">{{ Form::text('quantity', null, ['class'=>'form-control decimal']) }}</td>
					<td>{{ Form::text('rate', null, ['class'=>'form-control numbers']) }}</td>
					<td style="@if($job == "job" || $type == "job_card") display: none @endif">{{ Form::text('amount', null, ['class'=>'form-control numbers']) }}</td>
					<td>
					<select name='tax_id' class='form-control select_item taxes' id = 'tax_id'>
						<option value="">Select Tax</option>
						@foreach($taxes as $tax) 
						<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
						@endforeach
					</select> 
				
					 </td>
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
					<td><a style="display: none;" class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a></td>
				</tr>
				</tbody>
			</table>
		</div>


		
		<div style="padding: 10px 0;" class="form-group">
			<div class="row">
			<div class="col-md-12">
			<div style="float:right; border:1px solid #ccc; padding: 5px;">
				<table class= "total_rows" style="float:right;" cellpadding="5em">
					<tr>
					<td><h6 style="float:right; text-align:right; font-weight:bold;">Sub Total</h6></td>
					<td></td>
					<td><h6 class="sub_total" style="float:right; text-align:right; width: 150px;">0.00</h6></td>
					</tr>
					<tr style="display: none;">
					<td><h6 style="float:right; text-align:right; font-weight:bold; ">Discount</h6></td>
					<td><span style="float:left; padding-left: 10px; width:60px">{{ Form::text('discount', 0, ['class'=>'form-control']) }}</span><span class="discount_picker_container"><span class="discount_type">%</span>
						<ul class="discount_picker">
						<li class="percent">%</li>
						<li class="rupee">Rs</li>
						</ul>
						</span>{{ Form::checkbox('discount_is_percent', '1', true, array('id' => 'discount_is_percent', 'style' => 'float:right; display: none')) }} </td>
					<td><h6 class= "discount" style="float:right; text-align:right; width: 150px;">0.00</h6></td>
					</tr>
				 
					<tr>
					<td><h5 style="float:right; text-align:right; font-weight:bold;">Total</h5></td>
					<td></td>
					<td><h5 class= "total"  style="float:right; text-align:right; width: 150px;">0.00</h5>
						<input type="hidden" name="total">
					</td>
					</tr>
				</table>
				</div>
			</div>
			</div>
		</div>


    </div>
  </div>
</div>

<div class="save_btn_container">

	<button type="reset" class="btn btn-default clear cancel_transaction">Close</button>
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
		order('{{$transactions->order_no}}', '{{$transaction_type->name}}');
		@endif

	basic_functions();


	$("table").rowSorter({
		handler: "td.sorter",
		onDrop: function() { 
			var i = 1;
			$('.crud_table').find('tbody tr').each(function() {
				$(this).find('.index_number').text(i++);
			}) 
		}
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
		$('input[name=due_date], input[name=shipping_date]').val("");
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
			var term_days = $(this).find('option:selected').data('value');
			var due_date =  $('input[name=invoice_date]').datepicker('getDate');

			due_date.setDate(due_date.getDate()+term_days);

			$('input[name=due_date]').datepicker("setDate", due_date);
		}
		
	});



	$('body').on('input', 'input[name=quantity], input[name=rate], input[name=discount], select[name=tax_id], select[name=discount_id], input[name=discount_value]', function(){

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
		
		parent.find('input[name=amount]').val(amount);
 
		table();
		
	});

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

	$('body').on('input', 'input[name=amount]', function(){

		var obj = $(this);
		var parent = obj.closest('tr');
		var quantity = parent.find('input[name=quantity]').val();
		var rate =  parent.find('input[name=rate]').val();
		var amount = parent.find('input[name=amount]').val();

		var new_rate = amount/quantity;

		parent.find('input[name=rate]').val(new_rate.toFixed(2));

		table();

	});
	

	$('body').on('change', 'input[name=discount_is_percent]', function(){
		
		table();

	});

	$('body').on('change', 'select[name=item_id]', function() {

			var obj = $(this);
			var id = obj.val();
			obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=tax_id], select[name=discount_id]').val("");
			obj.closest('tr').find('select[name=tax_id], select[name=discount_id]').trigger('change');

			if(id != "") {
				$.ajax({
					 url: "{{ route('get_item_rate') }}",
					 type: 'post',
					 data: {
						_token: '{{ csrf_token() }}',
						id: id,
						date: $('input[name=invoice_date]').val()
					 },
					 success:function(data, textStatus, jqXHR) {
						obj.find('option:selected').attr('data-rate', data.price);

						@if($type == 'purchases' || $type == 'purchase_order')
							if(obj.closest('tr').find('input[name=quantity]').val() == "") {
							obj.closest('tr').find('input[name=quantity]').val(data.moq);
						}
						@else
							if(obj.closest('tr').find('input[name=quantity]').val() == "") {
							obj.closest('tr').find('input[name=quantity]').val(1);
						}
						@endif
						
						@if($type == 'purchases' || $type == 'purchase_order')
							obj.closest('tr').find('input[name=rate]').val(data.purchase_price);
						@else
							obj.closest('tr').find('input[name=rate]').val(data.price);
						@endif

						obj.closest('tr').find('select[name=tax_id]').val(data.tax_id);
						obj.closest('tr').find('select[name=tax_id]').trigger('change');
					 },
					 error:function(jqXHR, textStatus, errorThrown) {}
				});
			} else {
				table();
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




var sms = 0;
var print = 0;
var approve = 0;
var send_po = 0;
var tab_update_goods_btn = 0;
var tab_save_close = false;

$(".tab_send_btn, .tab_approve_btn, .tab_print_btn, .tab_sms_btn, .tab_update_goods_btn, .tab_save_close_btn, .tab_save_btn").off().on('click', function(e) {

	//alert(print);

	
	var that = $(this);

	
			e.preventDefault();
			var next_tab = $('.nav-tabs li a.active').parent().next('li:visible').find('a').attr('href');
			var next_other_tab = $('.nav-tabs li a.active').parent().next('li:visible').next('li:visible').find('a').attr('href');

			var validator = $('.transactionform').validate();

			if($(this).hasClass('tab_send_btn')) {
				send_po = 1;
				approve = 0;
				print = 0;
				sms = 0;
				tab_update_goods_btn = 0;
				tab_save_close = false;
			} else if($(this).hasClass('tab_approve_btn')) {
				send_po = 0;
				approve = 1;
				print = 0;
				sms = 0;
				tab_update_goods_btn = 0;
				tab_save_close = false;
			} else if($(this).hasClass('tab_print_btn')) {
				send_po = 0;
				approve = 0;
				print = 1;
				sms = 0;
				tab_update_goods_btn = 0;
				tab_save_close = false;
			} else if($(this).hasClass('tab_sms_btn')) {
				send_po = 0;
				approve = 0;
				print = 0;
				sms = 1;
				tab_update_goods_btn = 0;
				tab_save_close = false;
			} else if($(this).hasClass('tab_update_goods_btn')) {
				send_po = 0;
				approve = 0;
				print = 0;
				sms = 0;
				tab_update_goods_btn = 1;
				tab_save_close = false;
			} else if($(this).hasClass('tab_save_close_btn')) {
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
				if(next_tab) {
					$('a[href="'+next_tab+'"]')[0].click();

					//console.log(next_other_tab);
					/*if(next_other_tab == undefined) {
						$(this).text("Save");
					}*/

					/*if(next_other_tab == undefined) {
						if(that.hasClass('tab_save_close_btn')) {
							that.text("Save and Close");
						}else if(that.hasClass('tab_approve_save_btn')) {
							that.text("Approve");
						}  else {
							that.text("Save");
						}
						
					}*/
					return false;
				}

				if($(".transactionform").valid()) {
					$(".transactionform").submit();
				}
			} else {
				$('.form-group').addClass('has-error');

				validator.showErrors();

			}
		});


$('.make_transaction').off().on('click', function(e) {
			e.preventDefault();
			//$('.loader_wall_onspot').show();
			var obj = $(this);
			var id = obj.data('id');
			var transaction_name = obj.data('name');

			$('<form>', {
    "id": "dynamic_form",
    "method": "POST",
    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'">',
    "action": '{{ route("add_to_account") }}'
}).appendTo(document.body).submit();

			$('#dynamic_form').remove();

			/*$.ajax({
				url: "{{ route('add_to_transaction') }}",
				type: 'post',
				data: {
					_token : '{{ csrf_token() }}',
					id: id,
					transaction_name : transaction_name
				},
				success:function(data, textStatus, jqXHR) {
					$('.loader_wall_onspot').hide();
					$('.alert-success').html(data.message);
					$('.close_full_modal').trigger('click');
					$('.alert-success').show();


					setTimeout(function() { $('.alert').fadeOut(); }, 3000);
				}
			});*/

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
				}/*,
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
				}/*,
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
					tax_type: $('select[name=tax_types]').val(),
					reference_type: $('select[name=reference_type]').val(),
					reference_id: $('input[name=reference_id]').val(),
					type: '{{ $transaction_type->name }}',
					order_id: $('select[name=order_id]').val(),
					approval_status: $('select[name=approval_status]').val(),
					people_type: $('input[name=customer]:checked').val(),
					people_id: $('select[name=people_id]:not([disabled])').val(),
					invoice_date: $('input[name=invoice_date]').val(),
					due_date: $('input[name=due_date]').val(),
					vehicle_model_id: $('select[name=vehicle_model_id]').val(),
					term_id: $('select[name=voucher_term_id]').val(),
					interval: $('select[name=interval]').val(),
					period: $('select[name=period]').val(),
					week_day_id: $('select[name=week_day_id]').val(),
					day: $('select[name=day]').val(),
					frequency: $('input[name=frequency]').val(),
					start_date: $('input[name=start_date]').val(),
					end_date: $('input[name=end_date]').val(),
					end: $('select[name=end]').val(),
					end_occurrence: $('input[name=end_occurrence]').val(),
					order_id: $('input[name=order_id]').val(),
					payment_method_id: $('select[name=payment_method_id]').val(),
					ledger_id: $('select[name=ledger_id]').val(),
					employee_id: $('select[name=employee_id]').val(),
					name: $('input[name=customer_name]').val(),
					mobile: $('input[name=customer_mobile]').val(),
					email: $('input[name=customer_email]').val(),
					address: ($('textarea[name=customer_address]').val()).replace('\n', '<br>'),
					billing_name: $('input[name=billing_name]').val(),
					billing_mobile: $('input[name=billing_mobile]').val(),
					billing_email: $('input[name=billing_email]').val(),
					billing_address: ($('textarea[name=billing_address]').val()).replace('\n', '<br>'),
					shipping: $('input[name=shipping]:checked').val(),
					shipping_name: $('input[name=shipping_name]').val(),
					shipping_mobile: $('input[name=shipping_mobile]').val(),
					shipping_email: $('input[name=shipping_email]').val(),
					shipping_address: ($('textarea[name=shipping_address]').val()).replace('\n', '<br>'),
					shipment_mode_id: $('select[name=shipment_mode_id]').val(),
					shipping_date: $('input[name=shipping_date]').val(),
					item_id: $('select[name=item_id]').map(function() { 
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
					tax_id: $('select[name=tax_id]').map(function() { 
						return this.value; 
					}).get(),
					discount_id: $('select[name=discount_id]').map(function() { 
						return this.value; 
					}).get(),
					discount_value: $('input[name=discount_value]').map(function() { 
						return this.value; 
					}).get(),
					discount: $('input[name=discount]').val(),
					discount_is_percent: $('input[name=discount_is_percent]:checked').val(),

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
					new_group: $('input[name=new_group]').map(function() { 
						return this.value; 
					}).get(),
					required_status: $('input[name=required_status]:checked').map(function() { 
						return this.value; 
					}).get(),

					transaction_field_id: $('input[name=transaction_field]').map(function() { 
						return $(this).data('name');
					}).get(),
					transaction_field_value: $('input[name=transaction_field]').map(function() { 
						return $(this).val();
					}).get(),
					make_recurring: $('input[name=make_recurring]:checked').val(),
					sms: sms,
					print: print,
					approve: approve,
					send_po: send_po,
					update_goods: tab_update_goods_btn
					},
					
					beforeSend:function() {
						$('.loader_wall_onspot').show();
					},
				 	dataType: "json",
					success:function(data, textStatus, jqXHR) {
						if(data.status == "0") {
							$('.close_full_modal').trigger('click');
							$('.loader_wall_onspot').hide();
							$('.alert-danger').text(data.message);
							$('.alert-danger').show();

							setTimeout(function() { $('.alert').fadeOut(); }, 3000);
						} else {
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
								<td>
									<input id="`+data.data.id+`" class="item_checkbox" name="discount" value="`+data.data.id+`" type="checkbox">
									<label for="`+data.data.id+`"><span></span></label>
								</td>
								<td>`+data.data.order_no+`</td>
								@if($transaction_type->name == "purchases" || $transaction_type->name == "estimation" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash")<td>`+data.data.reference_type+`</td> @endif
								@if($transaction_type->name == "purchases" || $transaction_type->name == "estimation" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash")<td>`+data.data.reference_no+`</td> @endif
									
								<td>`+data.data.people+`</td>
								<td>`+data.data.people_contact+`</td>
								<td>`+data.data.total+`</td>
								<td>`+data.data.date+`</td>	
								<td>`+data.data.due_date+`</td>`;

								if(data.data.transaction_type == "estimation")
										{
											`<td>`+data.data.shipping_date+`</td>`;
										}

								if(data.data.transaction_type == "sales" || data.data.transaction_type == "purchases" || data.data.transaction_type == "sales_cash" ) {
									html +=`<td>`+data.data.balance+`</td>	
				 
										<td>
										<label class="grid_label badge `+selected_class+`">`+selected_text+`</label>
										</td>`;
								}
							

								if(data.data.approval_status == 1) {
									approve_selected = "selected";
								} else if(data.data.status == 0) {
									draft_selected = "selected";
								}

							html +=`<td>
								<label class="grid_label badge `+approval_class+` status">`+approval_text+`</label>
								</td>
							</tr>`;	

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

						if(print == 1) {
							print_transaction({{$id}});
						}


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

	
	function order(id, type) {

		$.ajax({
				 url: "{{ route('get_order_details') }}",
				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',
					order_id: id,
					organization_id: '{{$organization}}',
					type: type
					
				 },
				 success:function(data, textStatus, jqXHR) {

				 	$('.transactionform input:not(input[type=button]):not(input[type=submit]):not(input[type=reset]):not(input[name=_token]):not(input[type=radio]):not(input[type=checkbox])').val("");
				 	$('.transactionform select:not([name=reference_type]):not([name=tax_types]):not([name=employee_id])').val("");
				 	$('.transactionform select:not([name=reference_type]):not([name=tax_types]):not("#state")').trigger('change');
				 	
				 	$('select[name=order_type]').val(type);
				 	$('select[name=tax_types]').val(data.response.tax_type);
				 	$('select[name=approval_status]').val(data.response.approval_status);

				 	$('select[name=order_type], select[name=tax_types], select[name=approval_status]').trigger('change');
				 	$('input[name=order_id]').val(id);

					var transactions = data.response;
					var transaction_items = data.data;
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
					$('select[name=voucher_term_id]').val(data.response.term_id);
					$('input[name=reference_id]').val(data.response.id);
					//$('select[name=voucher_term_id]').trigger('change'); 
					@if(!empty($transactions))
					$('input[name=invoice_date]').val(data.response.date);
					$('input[name=due_date]').val(data.response.due_date);
					@endif

					setTimeout(function() {

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

					}, 500, data);
					


					$('select[name=payment_method_id]').val(data.response.payment_method_id);
					$('select[name=payment_method_id]').trigger('change');
					$('select[name=employee_id]').val(data.response.employee_id);
					$('select[name=employee_id]').trigger('change');
					$('textarea[name=billing_address]').val(data.response.billing_address);

					$('input[name=billing_checkbox]').prop('checked', true);
					$(".billing").show();

					//if(data.response.shipment_mode_id != null) {
						$('input[name=shipping_checkbox]').prop('checked', true);
						$(".shipping").show();
					//}

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

					var clone = $(".crud_table tbody").find('tr:first').clone(true, true);

					clone.find('select[name=item_id], select[name=tax_id], select[name=discount_id], input[name=quantity], input[name=rate], input[name=amount]').val("");
					clone.find('select > optgroup > span >  option').unwrap();

					$(".crud_table tbody tr").remove();

					var index_number = 1;
					var item_array = [];

					for(var i in transaction_items) {

						var transaction_item = clone.clone(true, true);

						item_array.push(transaction_items[i].item_id);


						transaction_item.find('.index_number').text(index_number + parseInt(i));
						transaction_item.find('select[name=item_id]').val(transaction_items[i].item_id);						
						transaction_item.find('input[name=rate]').val(transaction_items[i].rate);
						transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity);
						transaction_item.find('input[name=amount]').val(transaction_items[i].amount);
						transaction_item.find('select[name=tax_id]').val(transaction_items[i].tax_id);
						transaction_item.find('select[name=discount_id]').val(transaction_items[i].discount_id);
						transaction_item.find('input[name=discount_value]').val(transaction_items[i].discount_value);

						if(transaction_items.length == (index_number + parseInt(i)) ) {

							for(var j in item_array) {
								transaction_item.find('select[name=item_id] > optgroup > option[value="' + item_array[j] + '"]').wrap('<span>');
							}

						} else {
							transaction_item.find('select[name=item_id] > optgroup > option').wrap('<span>');
						}

						transaction_item.find('select[name=item_id] > optgroup > span > option[value="' + transaction_items[i].item_id + '"]').unwrap();

						transaction_item.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');


						$(".crud_table tbody").append(transaction_item);

					}


					if($(".crud_table tbody tr").length == 1) {

						if($(".crud_table tbody tr:last").find('select[name=item_id] > optgroup > option').length > 1) {
							$(".crud_table tbody tr:last").find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
						}
						else {
							$(".crud_table tbody tr:last").find('td').last().html('');
						}

						
					}
					else {
							if($(".crud_table tbody tr:last").find('select[name=item_id] > optgroup > option').length > 1) {

								$(".crud_table tbody tr:last").find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
							}
							else {
							$(".crud_table tbody tr:last").find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');
							}
					}

				$('.select_item').select2();

				table();

				$('input:not([type=search]), select:not([name=datatable_length]), textarea').prop('disabled', true);
				
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
		$('input[name=amount], select[name=tax_id], select[name=discount_id], input[name=discount_value]').each(function() {

			if($(this).attr('name') == 'amount') {
				amount += parseFloat($(this).val());
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
		$('input[name=discount_value]').each(function() {
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
		

		$('select[name=tax_id]').each(function() {
			var obj = $(this);
			if(obj.val() != "") {
				

				$.ajax({
					 url: "{{ route('get_tax') }}",
					 type: 'post',
					 data: {
						_token: '{{ csrf_token() }}',
						id: obj.val()
					 },
					 success:function(data, textStatus, jqXHR) {
						
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
								item_amount = (parseFloat(obj.closest('tr').find('input[name=amount]').val())/((obj.find('option:selected').data('value')/100)+1)).toFixed(2);
								total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(item_amount);
							} else if(tax_type == 2) {  //exclude
								item_amount = (parseFloat(obj.closest('tr').find('input[name=amount]').val())).toFixed(2);
								total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(item_amount);
							} else if(tax_type == 0) { //no
								item_amount = parseFloat(obj.closest('tr').find('input[name=amount]').val());
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

							tax_html += `<tr class="tax_row">
											<td><h6 style="float:right; text-align:right; font-size:14px; font-weight:bold; ">`;

								if(tax_type == 1) {
									tax_html += `Includes `;
								}
									tax_html += tax_name_array[tax]+` @`+tax_value_array[tax]+`% on `+tax_item_amount[tax]+`</h6></td>
											<td></td>
											<td><h6 style="float:right; text-align:right; width: 150px;">`+(tax_amount_array[tax]).toFixed(2)+`</h6></td>
											</tr>`;

							if(tax_type != 1) {			  
								sum_tax += parseFloat(tax_amount_array[tax]);
							}					

							
						}
						$('.total_rows').find('tr').last().prev().after(tax_html);
						$('.total').text( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2)      );
					 },
					 error:function(jqXHR, textStatus, errorThrown) {}
				});


			}
		});


		sub_total = (amount).toFixed(2);

		if(discount != null) {
			if($('input[name=discount_is_percent]').is(':checked')) {
				discount_transactions = parseFloat((discount/100)*sub_total);
			}
			else {
				discount_transactions = parseFloat(discount);			
			}

		}

			//console.log(sum_discount);
		$('.sub_total').text(sub_total);
		$('.discount').text((discount_transactions != "" && discount_transactions != 0) ? "- "+parseFloat(discount_transactions).toFixed(2) : 0.00);
		$('.total').text(sub_total - sum_discount); // - parseFloat(discount_transactions)
		$('input[name=total]').val(sub_total - sum_discount);
	}


</script> 


{{--
@stop
--}}