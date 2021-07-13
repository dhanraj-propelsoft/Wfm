  {{--
@extends('layouts.master')

@section('content')
@include('includes.add_user')
@include('includes.add_business')

--}}


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
		}
		.dropdown-menu > a {
		 color: white;
		}

label {
 margin: .5rem 0;
}


#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 100%;
  height: 20px;
  background-color: #337ab7;
}

#left
{
	float: left;
	padding: 10px;
	margin: auto 0;
	
}
#right
{
	float: right;
	padding: 10px;
	

}
#design
{
	
	float:left;
	margin:2px;
	border:1px solid #4b6cb7;
	padding-right: 5px;

}
table#new
{

	border: 1px solid #4b6cb7;
	
	border-spacing: 2px 2px;
	
	background-color:#f9fafe;


}


td#new
{
	border: 1px solid #4b6cb7;
	margin: 1px;
	font-weight: bold;
	color: blue;
	padding: 0.5px;
	
	
}
.row-full{
      width: 81.5vw;
       margin-left: 1vw;
      height: 30px;
          
}
.show-full{
      width: 81.5vw;
       margin-left: 1vw;
      height: 90%;
          
}
.order
{
	list-style-type:none;
}
.custom-panel{
	border: 1px solid #d7dbe0; 
	border-radius: 2px;
}
/* .ck .ck-content .ck-editor__editable .ck-rounded-corners .ck-blurred.ck-editor__editable_inline ol
{
	list-style-type: inline !important;
} */
.ck.ck-editor__editable_inline> ol li
{
	list-style-type: inline !important;
}
#select2-registration_number-container{
	background-color: yellow;
}
.custom-panel-radio{

	margin-bottom: 4px !important;
	margin-left: 3px !important;
}
.custom-panel-address{

	margin-bottom: 4px !important;
	margin-left: 3px !important;
	margin-right: 3px !important;
}

.dropzone .dz-preview .dz-progress, .dropzone .dz-preview .dz-size
    {
        display: none !important;
    }
.dropzone .dz-preview .dz-filename{
    top:0;
    position: absolute;
   }
   .dz-image{

   }
   .dz-image img{width: 100%;height: 100%;}
   .dropzone .dz-preview:hover .dz-image img{
   	    filter: blur(0px) !important;
   	cursor: pointer;
   }
   .dz-filename span
   {
   	display: none;
   }
</style>

<div class="content"> 

	
  <!-- <div class="modal-header"> -->
  <div class="fill header">

  	<h3 class="float-left voucher_name"> 
    @if(!empty($transactions))
      FSM Invoice# {{ $transactions->order_no }}
      @else
    FSM Invoices#  {{$voucher_no}}
      @endif
    </h3>
    
    <div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i> </div>
    
  </div>
 
  <div class="clearfix"></div>
  {!! Form::open(['class' => 'form-horizontal transactionform']) !!}
  {{ csrf_field() }} 


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

	<div class="form-body" style="padding: 15px 25px 55px; margin-top: 5px; ">
		<ul class="nav nav-tabs">
			<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#order_details">Invoice Details</a> </li>
	    </ul>
		    <div class="tab-content">
				<div class="tab-pane active" id="order_details" >
					<div class="details">
		    			<div class="row" >
		    				<div class="form-group col-md-3">
								<label class="control-label required" for="order_id">Type</label> <br>
								<div class="custom-panel" >
									<input id="cash_type" type="radio" name="job_sale_type" value="cash" @if($transaction_type->name == "job_invoice_cash" ) checked="checked" @endif  />

									<label for="cash_type" class="custom-panel-radio"><span></span>Cash</label>

									<input id="credit_type" type="radio" name="job_sale_type"  value="credit" @if($transaction_type->name == "job_invoice" ) checked="checked" @endif  />
									<label for="credit_type"><span></span>Credit</label>


								</div>
							</div>
						
							<div class="col-md-3">
		    				<div class="form-group">	
			    				<div class="row" >		    					
									<div class="col-md-10" >
							    		<label for="registration_number" class="control-label required">Registration Number</label>
										{{ Form::select('registration_number', $vehicles_register, $anonymous_vehicle_id, ['class' => 'form-control select_item registration_number', 'id' => 'registration_number','style' => 'background-color:yellow']) }}
									</div>

									<div class="col-md-2" style="padding-top: 33px; padding-left: 5px;">
										<a href="javascript:;" id="" class="add_vehicle" ><i class="fa fa-car"></i></a>
									</div>
								</div>		
		    				</div>
		    				</div>
		    				<div class="col-md-3" >
								<div class="row">
									<label for="vehicle_name" class="required">Make/ Modal / variant / Version</label>
									{{ Form::text('vehicle_name', $vehicle_configuration, ['class' => 'form-control', 'id' => 'vehicle_name','disabled']) }}
								</div>
							</div>
							<div class="col-md-3">
								<div class=" customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
									{{ Form::label('customer', $customer_type_label, 		array('class' => 'control-label required')) }}
									<div class="custom-panel" style="background-color: #e9ecef">
										<input id="business_type" type="radio" name="customer"   value="1" />

										<label for="business_type" class="custom-panel-radio"><span></span>Business</label>

										<input id="people_type" type="radio" name="customer" value="0" checked="checked" />

										<label for="people_type" ><span></span>People</label>
									</div>
								</div>
							</div>
						</div>
						<div class="row" > 
							<div class="col-md-3" >							
								<div class=" search_container people " style= "@if($customer_label == null) display:none @endif">

									{{ Form::label('people', $customer_label, array('class' => 'control-label required')) }}

									{{ Form::select('people_id', $people, $person_id, ['class' => 'form-control person_id', 'id' => 'person_id']) }}

									{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}

									{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}

								<div class="content"></div>
								</div>

								<div class=" search_container business" style= "@if($customer_label == null) display:none @endif">

									{{ Form::label('business', $customer_label, array('class' => 'control-label required')) }}

									{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}

									{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}

									{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}

									<div class="content"></div>
								</div>
							</div>							
							<div class="col-md-3">
				    			<div class="col-md-12">
				    				<div class="row">
							    		<label for="driver" class="control-label required">Contact / Driver Name</label>
										{{ Form::text('driver', $driver_name, ['class'=>'form-control','id' => 'driver']) }}
									</div>
								</div>
				    		</div>
				    		<div class="col-md-3" >
				    			<div class="form-group col-md-12">
				    				<div class="row">
					    				<label for="driver_number" class="control-label required ">Contact / Driver Number</label>
										{{ Form::number('driver_number', $driver_number, ['class'=>'form-control','id' => 'driverphone']) }}
									</div>
								</div>
				    		</div>
				    		<div class="col-md-3">
								<label for="milage" class="control-label required">Vehicle Odometer Mileage</label>
								{{ Form::text('milage', null, ['class' => 'form-control ']) }}
							</div>
		    			</div> 
			   			<div class="row" >
			   					<div class="col-md-3" style="width: 230px">
	    							<label class="required" for="date">Invoice Date</label>
									{{ Form::text('job_date',  date('d-m-Y') , ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }} 

								</div>	
			   					<div class="col-md-3">
									<div class="form-group">
									<div class="col-xs-12">
		           						 {!! Form::label('due_date','Payment Due Date ', array('class' => 'control-label required ')) !!}
		          				 		 {!! Form::text('due_date',Carbon\Carbon::today()->format('d.m.Y'),array('class'=>'form-control datepicker','data-date-format'=>'dd.mm.yyyy')) !!}
		       				 		</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										{!! Form::label('invoice_by', ' Invoiced By ', array('class' => 'control-label  required')) !!}

										{!! Form::select('invoice_by',$employee,null,['class' => 'form-control']) !!}
									</div>
								</div>
								
								
								<div class="col-md-3">
									<div class="form-group">
										<div class="col-xs-12">
			           						<label for="shipping_date" class="required">Delivery On</label>

											{{ Form::text('shipping_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
			       				 		</div>
									</div>
								</div>
						</div>
						<div class="row">
								<div class="col-md-3">
			    					<label for="payment_terms" class="required">Payment Terms</label>
									{{ Form::select('payment_terms', $payment_terms, $payment_term, ['class' => 'form-control select_item', 'id' => 'payment_terms']) }}
			    				</div>
								
							
								<div class="col-md-2">
								  	<label for="shift_name" class="control-label required">Shift Name</label>
									{{ Form::select('shift_name',$shift,$shift_id, ['class' => 'form-control ', 'id' => 'shift_name']) }}
								</div>
								<div class="col-md-2">
									<div class="form-group">
										{!! Form::label('pumpname', 'Pump Name ', array('class' => 'control-label  required')) !!}

										{!! Form::select('pumpname',$pumpname,$pump_id,['class' => 'form-control pumpname', 'id' => 'pumpname']) !!}
									</div>
								</div>
								
								<div class="col-md-2">
			    					<label for="payment_mode" class="required">Payment Method</label>
									{{ Form::select('payment_method_id', $payment, null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }} 
			    				</div>
			    				
								<div class="col-md-3">
									<div class="form-group col-md-12">
										<div class="row">
										<label for="customer_group" class="customer_group">Customer Group</label>
										{{ Form::text('customer_group', null, ['class' => 'form-control', 'id' => 'customer_group','disabled']) }}
										</div>
									</div>
								</div>
						</div>
			  	 	</div>	
				
					<div class="row"  style="margin-top: 55px;margin-left: -8px">
						<div class="col-md-12"style="">	
							<table style="border-collapse: collapse;" class="table table-bordered crud_table">
								<thead>
									<tr>
										<th style="width:30px" >#</th>
										<th style="width:110px">Products </th>		
										<th style="width:110px" >Disc.Type</th>		
										<th style="width:70px" >Unit Price</th>
										<th style="width:60px" >Disc %</th>
										<th style="width:60px">Stock</th>
										<th style="width:60px">Qty</th>
										<th style="width:70px">Rate</th>	
										<th style="width:90px">Tax %</th>
										<th style="width:70px">Tax Amount</th>
										<th style="width:70px">Total</th>
										<th style="width:25px"></th>
									</tr>
								</thead>
								<tbody>
								<tr class="parent items">
									<td class="sorter"><span class="index_number" style="float: right; padding-left: 5px;">1</span></td>							
										<td>

											{{ Form::select('item_id', $items,$itemdetails->id, ['class'=>'form-control decimal ']) }}

										</td>
									<td >
										<select name='discount_id' class='form-control select_item taxes' id = 'discount_id'>
												 <option value="">Select Discount</option>
												 @foreach($discounts as $discount) 
												 <option value="{{$discount->id}}" data-value="{{$discount->value}}">{{$discount->display_name}}</option>
												 @endforeach
											</select>
									</td>
									<td>

											{{ Form::text('rate',$itemdetails->selling_price,  ['class'=>'form-control numbers rate']) }} 

											<div class='rate_container'></div>

									</td>

										
										<td>{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}
									</td>

									<td>

										{{ Form::text('in_stock',$itemdetails->in_stock,  ['class'=>'form-control numbers', 'disabled', 'id' => 'in_stock']) }}

									</td>					
									<td>

										{{ Form::text('quantity', $quantity, ['class'=>'form-control decimal quantity','id' => 'quantity']) }}

										<div class='quantity_container'></div>
				

									</td>
									<td>
										{{ Form::text('amount', number_format($rate,2), ['class'=>'form-control numbers','id' => 'amount']) }}

									</td>
									<td>
										<select name='tax_id' class='form-control select_item taxes' id = 'tax_id' >
										<option value="">Select Tax</option>
												@foreach($taxes as $tax) 
											<option value="{{$tax->id}}" <?=($tax_groupid==$tax->id)?'selected':'';?>  data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
												@endforeach
											</select>
										<div class='tax_container'></div> 
									</td>
									<td>
										{{ Form::text('tax_amount', number_format($total_taxamount,2), ['class'=>'form-control decimal','disabled']) }}
										<div class='tax_amount'></div> 
									</td>						
									<td>

										{{ Form::text('tax_total', number_format($total_amount,2), ['class'=>'form-control decimal']) }}
										<div class='tax_total'></div>
									</td>
									<td>
										<a style="display: none;" class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>	
					<div class="row form-group" style="margin-top: 35px;margin-left: 400px;max-height:10px">
						<table id="new" class= "total_rows" align="right">
							<tr>
								<td id="new" style="max-height:30px">
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
				
					<div class="form-group custom-panel col-md-12" style="margin-top: 80px;">
						<div class="row custom-panel-address">
							<div class="col-md-12 ">
								<div class="row ">
									<div class="col-md-12">
										<label><b>{{$address_label}}</b></label>
									</div>
									<div class="col-md-3">
										<label for="date">Name</label>
											{{ Form::text('customer_name',$person->first_name, ['class'=>'form-control display_name', 'autocomplete' => 'off','id'=>'customer_name']) }} 
									</div>
									<div class="col-md-3">
										<label for="date">Mobile</label>
											{{ Form::text('customer_mobile', $person->mobile_no, ['class'=>'form-control mobile','id'=>'customer_mobile', 'autocomplete' => 'off']) }} 
									</div>
									<div class="col-md-3">
										<label for="date">Email</label>
											{{ Form::text('customer_email',  $person->email_address, ['class'=>'form-control email', 'autocomplete' => 'off','id'=>'customer_email']) }} 
									</div>
									<div class="col-md-3">
										<label for="date">Address:</label>
											{{ Form::textarea('customer_address', $person->billing_city, ['class'=>'form-control address', 'style'=>' height: 30px;','id'=>'customer_address']) }} 
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
												<div class="col-md-12">
													{{ Form::checkbox('billing_checkbox', '1',null, array('id' => 'billing_checkbox')) }}
													<label for="billing_checkbox"><span></span>Billing address is different</label>
												</div>
											</div>
										</label>
									</div>
									<div class="col-md-3  billing">
										<label for="date">Billing Name</label>
											<input type="text" class="form-control " name="billing_name" autocomplete="off" /> 
									</div>
									<div class="col-md-3  billing">
										<label for="date">Billing Mobile</label>
											<input type="text" class="form-control  " name="billing_mobile" autocomplete="off"  />
									</div>
									<div class="col-md-3  billing">
										<label for="date">Billing Email</label>
											<input type="text" class="form-control  " name="billing_email"  autocomplete="off"  />
									</div>
									<div class="col-md-3  billing">
										<label for="date">Billing Address</label>
											<textarea name="billing_address" class="form-control  "	style="height: 30px;" ></textarea> 
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="save_btn_container">

				  	<button type="reset" class="btn btn-default clear cancel_transaction">Close</button>  	
				  	<button type="submit" class="btn btn-success tab_approve_save_btn"> Approve </button>
				  	<button type="submit" class="btn btn-success tab_save_close_btn">Save and Close </button>

				  	<button type="submit" class="btn btn-success tab_save_btn">Save</button> 
			  
			</div>

{!! Form::close() !!} 





{{--

@stop

@section('dom_links')
@parent

--}}



<script type="text/javascript">
	table();
	$(document).ready(function ()
	{
		$(".billing").hide(); 

		 $('#billing_checkbox').click(function ()
		  {
	        if($(this).is(":checked"))
	        {

				$(".billing").show();
			} 
			else 
			{
				$(".billing").hide(); 
			}
	   	 });
	});

	
	var data ="<?php echo $vehicle_data->user_type ?> ";
	
	if(data==0){
		$('.business').hide();
		$('.people').show();

	}
	else{

		$('.business').show();
		$('.people').hide();
		
		}
		$('select[name=registration_number]').on('change', function(event) {
			//alert();
			
			var id = $('select[name=registration_number]').val();
			
			$.ajax({
				url: "{{ route('get_customer_details') }}",  // VehicleVariantController
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: id,
				},
				dataType: "json",
				success:function(data, textStatus, jqXHR) {

				$("#customer_name").val('');
				$("#customer_mobile").val('');
				$("#customer_email").val('');
				$("#customer_address").val('');
				$("#customer_name").val(data.first_name);
				$("#customer_mobile").val(data.mobile_no);
				$("#customer_email").val(data.email_address);
				$("#customer_address").val(data.billing_city);
							
					}
						});
		});

	// ****  Getting datas of vehicles based on details *****

		$('select[name=registration_number]').on('change', function(event) {
			//alert();
			
			var id = $('select[name=registration_number]').val();
			//console.log(id);
					
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
						//$('input[type=radio]').prop('readonly',true);
						trigger_people = $('.people select[name=people_id]');
					}
					else if(data.data.user_type == 1) {
						$('#business_type').prop('checked', true);
						$('#people_type').prop('checked', false);
						$('.people').hide();
						$('.business').show();
						$('.business select[name=people_id]').prop('disabled',false);
						$('.people select[name=people_id]').prop('disabled', true);						trigger_people = $('.business select[name=people_id]');
					}

								
					
					$("#registration").val(data.data.registration_no);
					$("#engine_no").val(data.data.engine_no);

					
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
                      $("#driverphone").val(data.data.driver_number);
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
		});
 		// ****  Getting datas of item  based on datas *****

		$('body').on('change', 'select[name=item_id]', function() {

			var obj = $(this);
		
			var id = obj.val();

			var vehicle_id = $('select[name=registration_number]').val();								

			var transaction_module ='fuel_station';

			//console.log($type);
				
			obj.closest('tr').find('select[name=discount_id]').trigger('change');		

				if(id != "") 
				{
					$.ajax({
						url: "{{ route('get_item_rate') }}",
						type: 'post',
						data: {
							_token: '{{ csrf_token() }}',
							id: id,
							transaction_module : 'fuel_station',

							date: $('input[name=invoice_date]').val()
						 },
						success:function(data, textStatus, jqXHR) {
							//console.log(data);

							
							var group = data.group;
							var is_group = data.is_group;
							var segment_price = data.segment_price;
							var modules = data.modules;

							$('.rate').val('');
							$('#amount').val('');
							$('input[name=tax_amount]').val('');
							$('input[name=tax_total]').val('');

							obj.closest('tr').nextUntil( 'tr.parent' ).remove();

							obj.closest('tr').find('.item_container, .rate_container, .quantity_container, .tax_container, .description_container').empty();

							obj.closest('tr').find('input[name=rate]').val(data.base_price);
						
							obj.closest('tr').find('td > input[name=in_stock]').val(data.in_stock);
						
							obj.closest('tr').find('input[name=quantity]').val(1);							
							obj.closest('tr').find('td > input[name=amount]').val(data.base_price);
											
							obj.closest('tr').find('td > select[name=tax_id]').val(data.tax_id);

							obj.closest('tr').find('td > select[name=tax_id]').trigger('change');
							
							if(parseInt(data.in_stock) <= 0 )
							{
								obj.closest('tr').find('td > select[name=job_item_status]').val(3);
								obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');
							}else{
								obj.closest('tr').find('td > select[name=job_item_status]').val(1);
								obj.closest('tr').find('td > select[name=job_item_status]').trigger('change');
							}



							for(var i in group) {

								//console.log(group[i].item_id);
								
								if(is_group == 0){

									obj.closest('table').find('.select_item').each(function() { 
										var select = $(this);  
										if(select.data('select2')) { 
											select.select2("destroy"); 
										}
									});

									var clone = obj.closest('tr').clone();

									obj.closest('tr').find('td > input[name=quantity], td > input[name=rate], td > input[name=amount], td > input[name=in_stock]').val("");

									clone.find('td > select[name=tax_id], td > select[name=discount_id], input[name=discount_value]').prop('disabled', false);
									 


									obj.closest('tr').find('select[name=tax_id]').val("");
									clone.addClass('items');
									clone.removeClass('parent');

									

									clone.find('select[name=item_id]').closest('td').html("<select name='item_id' class='select_item'> <option value='"+group[i].item_id+"'>"+group[i].name+"</option></select> <input type='hidden' name='parent_id' value='"+id+"'>");								

									clone.find('input[name=rate]').prop('disabled', false).val(group[i].price);
									clone.find('input[name=amount]').prop('disabled', false).val(group[i].price);
									clone.find('input[name=quantity]').prop('disabled', false).val(group[i].quantity);
									clone.find('textarea[name=description]').prop('disabled', false).val(group[i].description);
									clone.find('select[name=tax_id]').show();
									clone.find('.index_number').remove();
									clone.find('td').last().empty();

									clone.find('select[name=tax_id]').closest('td > select[name=tax_id]').val(group[i].tax_id).trigger('change');

									//clone.addClass("sub"+clone.index());

									obj.closest('tr').after(clone);

									
							

								

							}
							else
							{	

							obj.closest('table').find('.select_item').each(function() { 
								var select = $(this);  
								if(select.data('select2')) { 
									select.select2("destroy"); 
								}
							});

							var clone = obj.closest('tr').clone();	

							clone.find('input, select, textarea').remove();

							clone.removeClass('parent items');

							clone.find('td:nth(1)').html(`<div style='padding: 4px;'> <input disabled type='text' class='form-control' value="`+group[i].name+`"> </div>`);	

							clone.find('.index_number').remove();
							clone.find('td').last().empty();
							obj.closest('tr').after(clone);		

								
							}
							$('.select_item').select2();
						}
							
					},

						error:function(jqXHR, textStatus, errorThrown) {}

					});
 					
				}
				else {
					obj.closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock]').val("");				

									
				}
				
		

		});
        $('#tax_id').on('change',function(){
 		var obj = $(this);

	
		var parent = obj.closest('tr');
		var rate =  parent.find('input[name=rate]').val();
		var quantity = parent.find('input[name=quantity]').val();

 		var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
		//console.log(tax_id);

		var discount_id = parent.find('input[name=discount_value]').val();
		//console.log(discount_id);
		var tax_value = isNaN(tax_id) ? 0 : tax_id/100;

		var discount_value = isNaN(discount_id) ? 0 : discount_id/100;

		var amount = (rate*quantity).toFixed(2);
		var tax_amount = (amount*tax_value).toFixed(2);
		//console.log(tax_amount);
		var discount_amount = (amount*discount_value).toFixed(2);
			table();

	 });
	 $('#discount_id').on('change',function(){
	 		var obj = $(this);
	 		//alert("work it");
		
			var parent = obj.closest('tr');
			var rate =  parent.find('input[name=rate]').val();
			var quantity = parent.find('input[name=quantity]').val();

	 		var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
			

			var discount_id = parent.find('input[name=discount_value]').val();
		
			var tax_value = isNaN(tax_id) ? 0 : tax_id/100;

			var discount_value = isNaN(discount_id) ? 0 : discount_id/100;


			var amount = (rate*quantity).toFixed(2);
			var tax_amount = (amount*tax_value).toFixed(2);
			//console.log(tax_amount);
			var discount_amount = (amount*discount_value).toFixed(2);
			table();

	 });
		// ****  Getting datas of datas  based on calculation  *****

	$('body').on('input', 'input[name=quantity], input[name=rate], input[name=discount], select[name=tax_id], select[name=discount_id], input[name=discount_value], input[name=in_stock]', function(){

		$('input[name=tax_total]').val('');
		var obj = $(this);
	
		var parent = obj.closest('tr');
		var tax_type = $('select[name=tax_types]').val();
		var rate =  parent.find('input[name=rate]').val();
		var in_stock =  parent.find('input[name=in_stock]').val();		
		var quantity = parent.find('input[name=quantity]').val();
		
		//alert(rate);
		
		var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
		//console.log(tax_id);

		var discount_id = parent.find('input[name=discount_value]').val();
		var tax_value = isNaN(tax_id) ? 0 : tax_id/100;

		var discount_value = isNaN(discount_id) ? 0 : discount_id/100;

		var amount = (rate*quantity).toFixed(2);
		var tax_amount = (amount*tax_value).toFixed(2);
		//console.log(tax_amount);
		var discount_amount = (amount*discount_value).toFixed(2);
		
		parent.find('input[name=amount]').val(amount);
		parent.find('input[name=tax_amount]').val (tax_amount);
 
		table();
		
	 });

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

			if($(this).attr('name') == 'amount')
			{
				amount += parseFloat($(this).val());
			}
			else if($(this).attr('name') == 'tax_id')
			{
				var tax_value = $(this).find('option:selected').data('value');
				tax_amount += parseFloat( isNaN(tax_value) ? 0 : tax_value/100 ) * ($(this).closest('tr').find('input[name=amount]').val());
			}
			else if($(this).attr('name') == 'discount_id' || $(this).attr('name') == 'discount_value') 
			{

				var discount_value = $(this).closest('tr').find('input[name=amount]').val();


				var discount_name = ($(this).find('option:selected').val() != "") ?  $(this).find('option:selected').text() : "";

				discount_amount += parseFloat(( isNaN(discount_value) ? 0 : discount_value)/100)*($(this).closest('tr').find('input[name=amount]').val());	
			}
		});

		$('.total_rows').find('tr.discount_row').remove();

		sum_discount = parseFloat(0.00);

		var discount_name_array = [];

		var discount_value_array = [];

		var discount_amount_array = [];

		var discount_item_amount = [];


		$('body').find('.items').find('input[name=discount_value]').each(function() {

			var obj = $(this);

			if(obj.val() != "") 
			{
				var discount_value = obj.val();
				var discount_name = (obj.closest('tr').find(' select[name=discount_id]').val() != "") ? obj.closest('tr').find(' select[name=discount_id] option:selected').text() : discount_value;
				var item_amount = parseFloat(obj.closest('tr').find('input[name=amount]').val());
				var total_discount = parseFloat(( isNaN(discount_value) ? 0 : discount_value)/100)*(item_amount);
				if(!discount_name_array.includes(discount_name)) 
				{
					discount_name_array.push(discount_name);

					discount_value_array.push(discount_value);

					discount_amount_array.push(total_discount);

					discount_item_amount.push(item_amount);

				}
				else 
				{
					var index = discount_name_array.indexOf(discount_name);

					discount_amount_array[index] = parseFloat(discount_amount_array[index]) + parseFloat(total_discount);

					discount_item_amount[index] = parseFloat(discount_item_amount[index]) + parseFloat(item_amount);

				}

			}

		});



		for(var discount in discount_name_array)
		 {

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
			console.log(data);
			var tax_value1 = $(this).find('option:selected').data('value');
			var amount_element = ((obj.closest('tr').find('input[name=amount]').val()).isNaN) ? 0 : obj.closest('tr').find('input[name=amount]').val();
			var single_discount = parseFloat(obj.closest('tr').find('input[name=discount_value]').val());
			var single_item_discount = parseFloat(( isNaN(single_discount) ? 0 : single_discount)/100)*(amount_element);
			var single_item_amount = (parseFloat(amount_element) - parseFloat(single_item_discount).toFixed(2));
			var single_total_tax = parseFloat(( isNaN(tax_value1) ? 0 : tax_value1)/100)*(single_item_amount);

			if(obj.val() != "") 
			{	
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





						item_amount = (parseFloat(amount_element) - parseFloat(single_item_discount).toFixed(2));



						 total_tax = parseFloat(( isNaN(tax_value) ? 0 : tax_value)/100)*(item_amount);
				

				


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

				//console.log(sum_tax);

				//start to total



				var taxtotal = obj.closest('tr').find('input[name=tax_total]');

				

				var single_item_tax_toal = (parseFloat(single_item_amount)  + parseFloat(single_total_tax));



				taxtotal.val(single_item_tax_toal.toFixed(2));

				//end

				//start trade and trade-wms tax amount

				var tax_amount = $('.total_rows').find('input[name=tax_amount]');

				tax_amount.val(sum_tax.toFixed(2));

				//end

				//start to single tax total

				var taxamount = obj.closest('tr').find('input[name=tax_amount]');

					

				taxamount.val(single_total_tax.toFixed(2));

				//end



				$('.total').text( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2) );							



				creditLimit();



			



				

				/*},

				 error:function(jqXHR, textStatus, errorThrown) {



				 }

				});*/

			}



			else
			{ /* if tax is null for separate item */
				var taxtotal = obj.closest('tr').find('input[name=tax_total]');
				var single_item_tax_toal = (parseFloat(single_item_amount)  + parseFloat(single_total_tax));
				taxtotal.val(single_item_tax_toal.toFixed(2));
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



		//start to total discount for trade

		var total_discount = $('.total_rows').find('input[name=sum_discount]');

				total_discount.val(sum_discount.toFixed(2));

		//end



		//console.log(sum_discount);

		$('.sub_total').text(sub_total);

		$('.discount').text((discount_transactions != "" && discount_transactions != 0) ? "- "+ parseFloat(discount_transactions).toFixed(2) : 0.00);

		//$('.total').text(sub_total - sum_discount); // - parseFloat(discount_transactions)

		

		$('input[name=total]').val(sub_total - sum_discount);



		$('.total').text( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2) );



		$('input[name=wms_total]').val( (parseFloat(isNaN($('input[name=total]').val()) ? 0.00 : $('input[name=total]').val()) + parseFloat(sum_tax) ).toFixed(2) );

		

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




		}else{

			$('.credit_limit h6').css('color', '#000000');

		}

	}


	// ****  Getting datas of add row  *****

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
			
			clone.find('select[name=item_id], select[name=tax_id], input[name=tax_amount], select[name=discount_id], input[name=quantity], input[name=new_base_price],input[name=rate], input[name=amount],input[name=tax_total],textarea,input[name=discount_value]').prop("disabled", false).val("");

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


	
	// ****  Getting datas oftable ()operation  *****

	

	
 

	// ****  Getting datas of calcution some  *****

	$('body').on('change input', 'input[name=tax_total]', function(){
	var total_taxamount = $('input[name=tax_total]').val();
	var quantity = $('input[name=quantity]').val();
	var tax_value = $('select[name=tax_id]').find('option:selected').data('value');
	var tax_amount = total_taxamount / (1 + tax_value/100);	

	//console.log(tax_amount);
   $('input[name=rate]').val(parseFloat(tax_amount).toFixed(2));
   var rate = $('input[name=rate]').val();
   var amount = rate * quantity;
   $('input[name=amount]').val(parseFloat(amount).toFixed(2));
	});


	var current_select_item = null;

	var transaction_id = null;


	$(document).ready(function() {

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
		// ****  Getting datas of vehicles add *****

	$('.add_vehicle').on('click', function(e) {
			e.preventDefault();
			$.get("{{ route('vehicle_registered.create') }}", function(data) {
				//$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').attr("data-id",0);
				$('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
			$('.loader_wall_onspot').hide();
			
		});
	$('.purchase_year').datepicker({

        autoclose: true,

        viewMode: "years", 

    	minViewMode: "years",

        format: 'yyyy'

    });



	$('textarea.complaint').keyup(function()

	{

		//alert();



		var content = $('textarea.complaint').val();

		//console.log(content);

		$('textarea.job_complaint').val(content);

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

		

			$('.close_full_modal').trigger('click');

		
		

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
		if(row_index > 1) {

			$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

		} else {

			$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

		}
		table();
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

	$('select[name=people_id]').on('change', function(){

		

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

						//console.log(data);

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

				var term_days = $(this).find('option:selected').data('value');

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

	
		$('body').on('change', 'input[name=quantity]', function(){



		var obj = $(this);

		var parent = obj.closest('tr');

		

		var in_stock =  parent.find('input[name=in_stock]').val();

		var quantity = parent.find('input[name=quantity]').val();

		var job_item_status = parent.find('select[name=job_item_status]').val();



		//console.log(in_stock);



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



	});


	$('body').on('change','#multi_discount_id',function(){

		var id = $(this).val();

		//alert(id);

		$('#discount_id').val(id);

	});



	$('body').on('keyup','input[name=new_discount_value]',function(){

		//alert();

		var dis_val = $(this).val();

		$('input[name=discount_value]').val(dis_val);

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

		var approve = 0;

		var sms = 0;

		var print = 0;		

		var send_po = 0;

		var tab_update_goods_btn = 0;

		



	$(".tab_save_btn, .tab_save_close_btn, .tab_approve_save_btn").off().on('click', function(e) {

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

			

			if(validator.checkForm() == true) {

				$('.form-group').removeClass('has-error');

				$('.help-block').remove();

				if(next_tab) {

					$('a[href="'+next_tab+'"]')[0].click();

					//console.log(next_other_tab);

					

					if(next_other_tab == undefined) {

						if(that.hasClass('tab_save_close_btn')) {

							that.text("Save and Close");

						}else if(that.hasClass('tab_approve_save_btn')) {

							that.text("Approve");

						}  else {

							that.text("Save");

						}

					}





					return false;

				}



				if($(".transactionform").valid()) {

					if($(this).hasClass('tab_approve_save_btn')) {

						approve = 1;

						$('.tab_approve_save_btn').text("Approved");

						$('.tab_update_goods_btn').show();

					} else {

						approve = 0;

					}

					$(".transactionform").submit();



				}

			} else {

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



	$(".tab_sms_btn").on('click', function(e) {

		e.preventDefault();			



			$.ajax({

				url: "{{ route('transaction.sms_send') }}",

				 type: 'post',

				 data: {

					_token: '{{ csrf_token() }}',

					id: transaction_id,

					type: '{{ $transaction_type->name }}',

					

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

					type: transaction_name,

					

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


		$('.transactionform').validate({

			errorElement: 'span', //default input error message container

			errorClass: 'help-block', // default input error message class

			focusInvalid: false, // do not focus the last invalid input

			rules: {

				
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

				if(transaction_id != null) {

					$.ajax({

						 url: "{{ route('transaction.update') }}",

						 type: 'post',

						 data: {

							_token: '{{ csrf_token() }}',

							_method:  'PATCH',

							id: transaction_id,
							shift_id: $('select[name=shift_name]').val(),
							pump_id: $('select[name=pumpname]').val(),

							attachment_uid:$('input[name=attachment_uid]').val(),

							complaints : $('textarea[name=complaint]').val(),

							tax_type: $('select[name=tax_types]').val(),

							reference_type: $('select[name=reference_type]').val(),
							duration : $('input[name=duration]').map(function() { 
								return this.value; 
							}).get(),

							reference_id: $('input[name=reference_id]').val(),

							type: '{{ $transaction_type->name }}',

							order_id: $('select[name=order_id]').val(),

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

							billing: $('input[name=billing_checkbox]:checked').val(),

							billing_name: $('input[name=billing_name]').val(),

							billing_mobile: $('input[name=billing_mobile]').val(),

							billing_email: $('input[name=billing_email]').val(),

							billing_address: ($('textarea[name=billing_address]').val()).replace('\n', '<br>'),

							shipping: $('input[name=shipping_checkbox]:checked').val(),

							shipping_name: $('input[name=shipping_name]').val(),

							shipping_mobile: $('input[name=shipping_mobile]').val(),

							shipping_email: $('input[name=shipping_email]').val(),

						
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

							new_selling_price: $('input[name=new_base_price]').map(function() { 

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

							over_all_discount : $('input[name=new_discount_value]').val(),

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

							approve: approve,



							payment_terms: $('select[name=payment_terms]').val(),

							

							service_type: $('select[name=service_type]').val(),

							registration_no: $('select[name=registration_number]').val(),

							engine_no: $('input[name=engine_number]').val(),

							chasis_no: $('input[name=chassis_number]').val(),

							

							purchase_date: $('input[name=purchase_date]').val(),

							delivery_details: $('input[name=delivery_details]').val(),

							vehicle_last_visit: $('input[name=last_visit]').val(),

							vehicle_last_job: $('input[name=last_job_card]').val(),

							vehicle_next_visit: $('input[name=next_visit_date]').val(),

							vehicle_mileage: $('input[name=vehicle_mileage]').val(),

							next_visit_mileage: $('input[name=next_visit_mileage]').val(),

							vehicle_next_visit_reason: $('input[name=next_visit_reason]').val(),

							vehicle_note: $('textarea[name=vehicle_note]').val(),

							before_job_notes: $('textarea[name=before_note]').val(),

							after_job_notes: $('textarea[name=after_note]').val(),



							name_of_job: $('input[name=name_of_job]').val(),



							job_date: $('input[name=job_date]').val(),

							

							job_due_date: $('input[name=job_due_date]').val(),

							

							job_completed_date: $('input[name=job_completed_date]').val(),



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



							jobcard_status_id: $('select[name=jobcard_status_id]').val(),



							wms_division_id: $('input[name=wms_division_id]').map(function() { 

								return this.value; 

							}).get(),

							wms_reading_factor_id: $('input[name=wms_reading_factor_id]').map(function() { 

								return this.value; 

							}).get(),

							reading_values: $('input[name=reading_values]').map(function() { 

								return this.value; 

							}).get(),

							reading_calculation: $('input[name=reading_calculation]').map(function() { 

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

							//console.log($(this).prop("checked",true));

							return $(this).is(":checked") ? 1 : 0;

								//return $(this).attr("checked") ? 1 : 0;;

							}).get(),

							checklist_notes:$('input[name=wms_checklist_notes]').map(function() { 

								return this.value; 

							}).get(),



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

									$('.loader_wall_onspot').hide();

									if(tab_save_close == true) {

										location.assign("{{route('transaction.index', $transaction_type->name)}}");

									}

								}

							},

						 error:function(jqXHR, textStatus, errorThrown) {

							//alert("New Request Failed " +textStatus);

							}

						});



				} else {

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
							shift_id: $('select[name=shift_name]').val(),
							pump_id: $('select[name=pumpname]').val(),

							attachment_uid:$('input[name=attachment_uid]').val(),

							tax_type: $('select[name=tax_types]').val(),

							reference_type: $('select[name=reference_type]').val(),
							duration : $('input[name=duration]').map(function() { 
								return this.value; 
							}).get(),

							reference_id: $('input[name=reference_id]').val(),

							type: '{{ $transaction_type->name }}',

							order_id: $('select[name=order_id]').val(),

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

							billing: $('input[name=billing_checkbox]:checked').val(),

							billing_name: $('input[name=billing_name]').val(),

							billing_mobile: $('input[name=billing_mobile]').val(),

							billing_email: $('input[name=billing_email]').val(),

							billing_address: ($('textarea[name=billing_address]').val()).replace('\n', '<br>'),

							shipping: $('input[name=shipping_checkbox]:checked').val(),

							shipping_name: $('input[name=shipping_name]').val(),

							shipping_mobile: $('input[name=shipping_mobile]').val(),

							shipping_email: $('input[name=shipping_email]').val(),



							

							

							shipment_mode_id: $('select[name=shipment_mode_id]').val(),

							shipping_date: $('input[name=shipping_date]').val(),

							item_id: $('select[name=item_id]').map(function() { 

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

							tax_id: $('select[name=tax_id]').map(function() { 

								return this.value; 

							}).get(),

							discount_id: $('select[name=discount_id]').map(function() { 

								return this.value; 

							}).get(),

							discount_value: $('input[name=discount_value]').map(function() { 

								return this.value; 

							}).get(),

							



							complaints: $('textarea[name=complaint]').val(),

							discount: $('input[name=discount]').val(),

							discount_is_percent: $('input[name=discount_is_percent]:checked').val(),

							over_all_discount : $('input[name=new_discount_value]').val(),



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

							approve: approve,





							payment_terms: $('select[name=payment_terms]').val(),

							service_type: $('select[name=service_type]').val(),

							registration_no: $('select[name=registration_number]').val(),

							engine_no: $('input[name=engine_number]').val(),

							chasis_no: $('input[name=chassis_number]').val(),

							purchase_date: $('input[name=purchase_date]').val(),

							delivery_details: $('input[name=delivery_details]').val(),

							

							vehicle_last_visit: $('input[name=last_visit]').val(),

							vehicle_last_job: $('input[name=last_job_card]').val(),

							vehicle_next_visit: $('input[name=next_visit_date]').val(),

							vehicle_mileage: $('input[name=vehicle_mileage]').val(),

							next_visit_mileage: $('input[name=next_visit_mileage]').val(),

							vehicle_next_visit_reason: $('input[name=next_visit_reason]').val(),

							vehicle_note: $('textarea[name=vehicle_note]').val(),

							before_job_notes: $('textarea[name=before_note]').val(),

							after_job_notes: $('textarea[name=after_note]').val(),



							name_of_job: $('input[name=name_of_job]').val(),



							job_date: $('input[name=job_date]').val(),

							

							job_due_date: $('input[name=job_due_date]').val(),

							

							job_completed_date: $('input[name=job_completed_date]').val(),



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



							jobcard_status_id: $('select[name=jobcard_status_id]').val(),



							





							wms_division_id: $('input[name=wms_division_id]').map(function() { 

								return this.value; 

							}).get(),

							wms_reading_factor_id: $('input[name=wms_reading_factor_id]').map(function() { 

								return this.value; 

							}).get(),

							reading_values: $('input[name=reading_values]').map(function() { 

								return this.value; 

							}).get(),

							reading_calculation: $('input[name=reading_calculation]').map(function() { 

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

							//console.log($(this).prop("checked",true));

							return $(this).is(":checked") ? 1 : 0;

								//return $(this).attr("checked") ? 1 : 0;;

							}).get(),

							checklist_notes:$('input[name=wms_checklist_notes]').map(function() { 

								return this.value; 

							}).get(),
							},

							

							beforeSend:function() {

								$('.loader_wall_onspot').show();

							},

						 dataType: "json",

							success:function(data, textStatus, jqXHR)
							{
								///console.log(data.data.registration_no);

								 window.location.href = 'easy_way';
					
								$('.loader_wall_onspot').hide();
							}
							});						

						};
 					}
 				});
		function order(id, type, status) {
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

						



						$('select[name=vehicle_category]').val(data.response.vehicle_category_id)

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



						var clone = $(".crud_table tbody").find('tr:first').clone(true, true);



						clone.find('select[name=item_id], select[name=tax_id], select[name=discount_id], input[name=quantity], input[name=rate], input[name=amount]').val("");

						clone.find('select > optgroup > span >  option').unwrap();



						$(".crud_table tbody tr").remove();



						var index_number = 1;

						var item_array = [];



						for(var i in transaction_items) {



							var transaction_item = clone.clone(true, true);



							item_array.push(transaction_items[i].item_id);



						/*if(transaction_items[i].parent_item_id == null) {

							transaction_item.find('.index_number').text(index_number);

							index_number++;

						} else {

							transaction_item.find('input[name=parent_id]').val(transaction_items[i].parent_item_id);

							transaction_item.find('.index_number').remove();

							transaction_item.find('.remove_row').remove();

						}*/



						



						// new line added



						transaction_item.find('.index_number').text(index_number + parseInt(i));



						//



						transaction_item.find('textarea[name=description]').val(transaction_items[i].description);



						transaction_item.find('input[name=in_stock]').val(transaction_items[i].in_stock);



						@if($type == 'purchases' || $type == 'purchase_order'  || $type == 'goods_receipt_note')



							transaction_item.find('input[name=base_price]').val(data.selling_price[i]);

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







						if(parseInt(transaction_items[i].quantity) > parseInt(transaction_items[i].in_stock))

						{

							transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#FF0000');

							//transaction_item.find('select[name=job_item_status]').val(3);

							transaction_item.find('select[name=job_item_status]').trigger('change');

							

						}else{

							transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#000000');

							//transaction_item.find('select[name=job_item_status]').val(1);

							transaction_item.find('select[name=job_item_status]').trigger('change');			

						}



						/* If Repeated items need use this */



						/*if(transaction_items.length == (index_number + parseInt(i)) ) {



							for(var j in item_array) {

								transaction_item.find('select[name=item_id] > optgroup > option[value="' + item_array[j] + '"]').wrap('<span>');

							}



						} else {

							transaction_item.find('select[name=item_id] > optgroup > option').wrap('<span>');

						}*/



						transaction_item.find('select[name=item_id] > optgroup > span > option[value="' + transaction_items[i].item_id + '"]').unwrap();



						transaction_item.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');



						



						if(transaction_items[i].is_group == 0) {

							transaction_item.removeClass("items");

							

						} else if(transaction_items[i].is_group == null) {

							//transaction_item.removeClass("parent");

							transaction_item.find(".remove_row").remove();

						}

						



						 if(transaction_items[i].parent_item_id == null){

							transaction_item.find('input[name=parent_id]').val(transaction_items[i].parent_item_id);

							//transaction_item.find('.index_number').remove();

							transaction_item.find('.remove_row').remove();

						}





						$(".crud_table tbody").append(transaction_item);



						if(transaction_items[i].is_group == 1) {

							$(".crud_table tbody tr").last().find('select[name=item_id]').trigger('change');

						}

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

					},

					error:function(jqXHR, textStatus, errorThrown) {}

			});



	}

</script>
<script>



		ClassicEditor.create( document.querySelector( '#editor' ),{

				removePlugins: [ 'Heading', 'Link' ,'bold', 'italic','blockQuote','bulletedList' ],

				toolbar: ['numberedList']

			}

			);

		//CKEDITOR.replace( '#editor',{ toolbar: ['numberedList'] } );

		  //$('textarea').ckeditor();

</script>

