<div class="content">
	<div class="fill header">
		<h3 class="float-left">Job Delivery</h3>
		<div class="alert alert-danger">asfasfsaf</div>
		<div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i></div>
	</div>
	<div class="clearfix"></div>
	<br>
	<ul class="nav nav-tabs">
		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#order_details">Job Details</a> </li>
	    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#vehicles">Vehicles</a> </li>
	    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#item_details">Job Items</a> </li>      
	    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#attachments">Attachments</a> </li>
	    
	</ul>

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}
	{{ csrf_field() }}
<div class="tab-content" style= border-top: 0px; padding: 10px;">

	<div class="tab-pane active" id="order_details">
		<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">
		  <div class="form-group">

            <div class="row">
            
              <div class="col-md-3">
                <label for="order_id">Reference Type</label>

                {{ Form::select('order_type', [], null, ['class' => 'form-control']) }}
              </div>

                <div class="col-md-3">
                <label for="order_id">Reference Number</label>

                {{ Form::text('order_id',null, ['class' => 'form-control']) }}
              </div>
            </div>
          	<div class="row">
            
              <div class="col-md-3">
                <label class="control-label required" for="order_id"> Cutomer Type</label> <br>
					<div class="custom-panel" >
							<input id="cash_type" type="radio" name="sale_type" value="cash" checked="checked" />
							<label for="cash_type" class="custom-panel-radio"><span></span>Cash</label>

							<input id="credit_type" type="radio" name="sale_type"  value="credit"checked="checked" />
							<label for="credit_type"><span></span>Credit</label>
					</div>
              </div>

                <div class="col-md-3">
                <label for="custermer">Customer</label>

                {{ Form::select('custermer',[],null, ['class' => 'form-control']) }}
              </div>
          	</div>
          	<div class="row">
            
              <div class="col-md-3">
                <label for="date">Delivery Mode</label>
				{{ Form::select('shipment_mode_id', [], '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}
              </div>

                <div class="col-md-3">
                <label for="delivery_details">Delivery Details</label>
				{{ Form::text('delivery_details', null ,['class' => 'form-control number']) }} 
              </div>
            </div>
          	<div class="row">
            
              <div class="col-md-3">
                <label for="date">Date</label>

                {{ Form::select('date', [], null, ['class' => 'form-control', 'id' => 'assigneed_to']) }}
              </div>

                <div class="col-md-3">
               <label for="shipping_date">Delivery On</label>
				{{ Form::text('shipping_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
              </div>
            </div>
	        <div class="row">
	              <div class="col-md-3">
	               <label for="delivery_by">Delivery By</label>
					{{ Form::text('delivery_by', null ,['class' => 'form-control']) }}
	              </div>       
	        </div>
	        <div class="form-group custom-panel">

	          <div class="row custom-panel-address">

	            <div class="col-md-12 ">

	              <div class="row ">

	                <div class="col-md-12">
	                  <label><b>Customer Address</b></label>
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

	                  <label><!-- <b>Billing Address</b> -->
	                    <div class="row">
	                      <div style="display: none;" class="col-md-12"> {{ Form::checkbox('billing_checkbox', '1', null, array('id' => 'billing_checkbox')) }}
	                      <label for="billing_checkbox"><span></span>Billing address is different</label>
	                    </div>
	                    </div>
	                  </label>

	                </div>

	                <div class="col-md-3  billing">
	                <label for="date">Billing Name</label>
	                <input type="text" class="form-control" name="billing_name" value="" autocomplete="off" /> </div>

	                <div class="col-md-3  billing">
	                <label for="date">Billing Mobile</label>
	                <input type="text" class="form-control" name="billing_mobile" value="" autocomplete="off"  /> </div>

	                <div class="col-md-3  billing">
	                <label for="date">Billing Email</label>
	                <input type="text" class="form-control" name="billing_email" value="" autocomplete="off"  /> </div>

	                <div class="col-md-3  billing">
	                <label for="date">Billing Address</label>
	                <textarea name="billing_address" class="form-control"
	                  style="height: 30px;" ></textarea> 
	                </div>  
	              </div>
	            </div>
	          </div>

	          <div class="row custom-panel-address">
	            <div class="col-md-12">
	              <div class="row ">
	                <div class="col-md-12">

	                  <label><!-- <b>Shipping Address</b> -->
	                    <div class="row">
	                      <div style="display: none;" class="col-md-12"> {{ Form::checkbox('shipping_checkbox', '1', null, array('id' => 'shipping_checkbox')) }}
	                      <label for="shipping_checkbox"><span></span>Shipping address is different</label>
	                    </div>
	                    </div>
	                  </label>

	                </div>

	                <div class="col-md-3 shipping">
	                <label for="date">Shipping Name</label>
	                <input type="text" class="form-control" name="shipping_name" value="" autocomplete="off"  /></div>

	                <div class="col-md-3 shipping">
	                <label for="date">Shipping Mobile</label>
	                <input type="text" class="form-control" name="shipping_mobile" value="" autocomplete="off"  /> </div>

	                <div class="col-md-3 shipping">
	                <label for="date">Shipping Email</label>
	                <input type="text" class="form-control" name="shipping_email" value="" autocomplete="off"  /> </div>

	                <div class="col-md-3 shipping">
	                <label for="date">Shipping Address</label>
	                <textarea name="shipping_address" class="form-control" style="height: 30px;" > </textarea>
	                </div>  
	              </div>
	            </div>

	          </div>
	        </div>

          </div>
		</div>
	</div>

	<div class="tab-pane" id="vehicles">
		<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">
			<div class="form-group">
            <div class="row">
            
              <div class="col-md-3">
                  <label for="registration_number" class="control-label required">Registration Number</label>
				  {{ Form::select('registration_number', [], null, ['class' => 'form-control select_item', 'id' => 'registration_number']) }}
                </div>
                <div class="col-md-3">
                  <label for="vehicle_mileage" class="control-label required">Vehicle Odometer Mileage</label>
				  {{ Form::text('vehicle_mileage', null, ['class' => 'form-control numbers']) }}
              </div>

            </div>

            <div class="row">
            
              	<div class="col-md-3">
                  <label for="engine_number" class="control-label">Vehicle Engine Number</label>
				  {{ Form::text('engine_number', null, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-3">
                 <label for="chassis_number" class="control-label">Vehicle Chasis Number</label>
				 {{ Form::text('chassis_number', null, ['class' => 'form-control']) }}
              	</div>

            </div>
			
			<div class="row">
            
              	<div class="col-md-3">
                  	<label for="vehicle_category" class="control-label required">Vehicle Category</label>
						{{ Form::select('vehicle_category', [], null, ['class' => 'form-control select_item', 'id' => 'vehicle_category', 'disabled']) }}
               	</div>
                <div class="col-md-3">
                  	<label for="last_visit" class="control-label">Vehicle Last Visit</label>
					{{ Form::text('last_visit', null, ['class'=>'form-control']) }}
              	</div>

            </div>
			
			<div class="row">
            
              	<div class="col-md-3">
                  	<label for="vehicle_make" class="control-label required">Vehicle Make</label>
					{{ Form::select('vehicle_make', [], null, ['class' => 'form-control select_item', 'id' => 'vehicle_make', 'disabled']) }}
               	</div>
                <div class="col-md-3">
                  	<label for="last_job_card" class="control-label">Vehicle Last Job Card</label>
					{{ Form::text('last_job_card', null, ['class'=>'form-control']) }}
              	</div>

            </div>

            <div class="row">
            
              	<div class="col-md-3">
                  	<label for="vehicle_model" class="control-label required">Vehicle Model</label>
					{{ Form::select('vehicle_model', [], null, ['class' => 'form-control select_item', 'id' => 'vehicle_model', 'disabled']) }}
               	</div>
                <div class="col-md-3">
                  	<label for="next_visit_date" class="control-label required">Vehicle Next Visit - Date</label>
					{{ Form::text('next_visit_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
            	</div>
			</div>
            <div class="row">
            
              	<div class="col-md-3">
                  	<label for="vehicle_variant" class="control-label required">Vehicle Variant</label>
					{{ Form::select('vehicle_variant', [], null, ['class' => 'form-control select_item', 'id' => 'vehicle_variant', 'disabled']) }}
               	</div>
                <div class="col-md-3">
                  	<label for="next_visit_mileage" class="control-label required">Vehicle Next Visit - Odometer Mileage</label>
					{{ Form::text('next_visit_mileage', null, ['class'=>'form-control numbers']) }}
              	</div>
              	<div class="col-md-3">
              	<label for="next_visit_reason" class="control-label required">Vehicle Next Visit Reason</label>
				{{ Form::text('next_visit_reason', null, ['class'=>'form-control']) }}
				</div>
            </div>
            <div class="row">
					<div class="col-md-6">
						<label for="vehicle_note" class="control-label">Vehicle Note</label>
						{{ Form::textarea('vehicle_note', null, ['class'=>'form-control', 'size' => '30x2']) }}
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
						<th width="25%"> Work & Job	</th>
						<th width="12%">Description</th>
						<th width="6%">Quantity</th>
						<th width="10%">Rate</th>
						<th width="10%" width="14%">Amount</th>
						<th width="10%">Tax</th>
						<th width="10%">Discount Type</th>
						<th width="10%">Discount</th>
						
						<th width="10%"></th>
						
					</tr>
					
					</thead>
					<tbody>
					<tr class="parent items">
						<td class="sorter"><span class="index_number" style="float: right; padding-left: 5px;">1</span></td>
						<td>
							<select name="item_id" class="form-control select_item" id="item_id">
							<option value="">Select Item</option>
							
							</select> <input type="hidden" name="parent_id">
							<div class='item_container'></div>

						</td>
						<td>
							{{ Form::textarea('description', null, ['class'=>'form-control', 'style'=>' height: 26px;' , 'placeholder' => 'Description']) }}
						</td>
						<div class='description_container'></div>

						<td>
							{{ Form::text('quantity', null, ['class'=>'form-control decimal']) }}
							<div class='quantity_container'></div>
						</td>

						<td>
							{{ Form::text('rate', null, ['class'=>'form-control numbers']) }} 
							<div class='rate_container'></div>
						</td>

						<td>
							{{ Form::text('amount', null, ['class'=>'form-control numbers']) }}
						</td>

						
						<td>
							<select name='tax_id' class='form-control select_item taxes' id = 'tax_id'>
								<option value="">Select Tax</option>
									
										<option value="" data-value="" data-tax="" data-type=""></option>
									
							</select>								
						</td>
						<td>
							<select name='discount_id' class='form-control select_item taxes' id = 'discount_id'>
									 <option value="">Select Discount</option>
									
									 <option value="" data-value=""></option>
									
								</select>
						</td>
						<td>
						{{ Form::text('discount_value', null, ['class'=>'form-control decimal']) }}
						</td>
					
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

	<div class="tab-pane" id="attachments">
     	<div class="clearfix"></div>
			<div class="form-group"><br/>
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
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="before_image" />
						</div>
						<div class="col-md-6 pull-left"> 
							{{ Form::text('before_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}	
						</div>
					</div>
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="after_image" />
						</div>
						<div class="col-md-6 pull-left">
							{{ Form::text('after_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}
						</div>						
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="before_image" />
						</div>
						<div class="col-md-6 pull-left"> 
							{{ Form::text('before_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}	
						</div>
					</div>
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="after_image" />
						</div>
						<div class="col-md-6 pull-left">
							{{ Form::text('after_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}
						</div>						
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="before_image" />
						</div>
						<div class="col-md-6 pull-left"> 
							{{ Form::text('before_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}	
						</div>
					</div>
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="after_image" />
						</div>
						<div class="col-md-6 pull-left">
							{{ Form::text('after_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}
						</div>						
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="before_image" />
						</div>
						<div class="col-md-6 pull-left"> 
							{{ Form::text('before_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}	
						</div>
					</div>
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="after_image" />
						</div>
						<div class="col-md-6 pull-left">
							{{ Form::text('after_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}
						</div>						
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="before_image" />
						</div>
						<div class="col-md-6 pull-left"> 
							{{ Form::text('before_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}	
						</div>
					</div>
					<div class="col-md-6 image_container">
						<div class="col-md-6 pull-left">
							<img alt="Select Image" width="100" height="100" />
							<input type="file" name="after_image" />
						</div>
						<div class="col-md-6 pull-left">
							{{ Form::text('after_text', null, ['class'=>'form-control', 'disabled' => 'disabled']) }}
						</div>						
					</div>
				</div>
								
				<div class="row">
					<div class="col-md-6">
						<label for="before_note" class="control-label"> Notes </label>
						{{ Form::textarea('before_note', null, ['class'=>'form-control', 'size' => '30x2']) }}
					</div>
					<div class="col-md-6">
						<label for="after_note" class="control-label"> Notes </label>
						{{ Form::textarea('after_note', null, ['class'=>'form-control', 'size' => '30x2']) }}
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
	
</div>
</div>
{!! Form::close() !!} 





{{--

@stop

@section('dom_links')
@parent

--}}



<script type="text/javascript">


		

</script> 


{{--
@stop
--}}