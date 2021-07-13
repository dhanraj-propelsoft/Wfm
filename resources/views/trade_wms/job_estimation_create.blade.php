<style>
.vehicle_info {
 border:2px solid black;
 padding: 33px;

 border-radius: 5px;
 background-color: #D3D3D3;
}
</style>
<div class="content">
	<div class="fill header">
		<h3 class="float-left">Job Estimation</h3>
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
		 
		 		<div class="row">

					<div class="col-md-6">
						<div class="form-group col-md-12">
							<div class="row">
						        <div class="col-md-12">
					                <label for="order_id">Name of the Job or work</label>
					                {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'assigneed_to']) }}
			              		</div>
		                	</div>
		                </div>
			            
			            <div class="form-group col-md-12">
			          		<div class="row">
			                	<div class="col-md-6">
			                		{{ Form::label('service_type', 'Service Type', array('class' => 'control-label required')) }}
					                {{ Form::select('service_type', ['0' => 'Paid', '1' => 'AMC', '2' => 'Free', '3' => 'Reward'], null, ['class' => 'form-control ', 'id' => 'service_type', 'placeholder' => 'Select Service Type']) }}
			             		</div>
								<div class="col-md-6 customer_type" style= "@ display:none"> 
									{{ Form::label('customer', 'Customer Type', array('class' => 'control-label required')) }} <br>
									<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
									<label for="business_type"><span></span>Business</label>
									<input id="people_type" type="radio" name="customer" value="0" />
									<label for="people_type"><span></span>People</label>
								</div>
		  	          		</div>
		  	          	</div>
		  	          	<div class="form-group col-md-12">
			          		<div class="row">
				                <div class="col-md-6 search_container people" > 
									{{ Form::label('people', null, array('class' => 'control-label required')) }}
									{{ Form::select('people_id', [], null, ['class' => 'form-control person_id', 'id' => 'person_id', 'disabled']) }}
									{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
									{{ Form::checkbox('account_person_type_id', null, true, ['id' => 'account_person_type_id']) }}
								<div class="content"></div>
								</div>
								<div class="col-md-6 search_container business" style= "display:none"> 
									{{ Form::label('business', null, array('class' => 'control-label required')) }}
									{{ Form::select('people_id', [], null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}
									{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
									{{ Form::checkbox('account_person_type_id', null, true, ['id' => 'account_person_type_id']) }}
								<div class="content"></div>
								</div>	
				                <div class="col-md-6">
					                <label for="assigneed_to">Attended By</label>
					                {{ Form::select('assigneed_to', [], null, ['class' => 'form-control', 'id' => 'assigneed_to']) }}
				              	</div>
		  	            	</div>
		  	            </div>
		  	            <div class="form-group col-md-12">
				          	<div class="row">
				                <div class="col-md-6">
				                	{{ Form::label('date', 'Date', array('class' => 'control-label required')) }}
					               	{{ Form::text('date', null, ['class' => 'form-control', 'id' => 'assigneed_to']) }}
				              	</div>
				              	<div class="col-md-6">
				              		{{ Form::label('expiry_date', 'Expiry Date', array('class' => 'control-label required')) }}
					                {{ Form::text('date', null, ['class' => 'form-control', 'id' => 'assigneed_to']) }}
				              	</div>
				            </div>
				        </div>
				        <div class="form-group col-md-12">
							<div class="row">
				                <div class="col-md-6">
					                <label for="date">Delivery Method</label>
									{{ Form::select('shipment_mode_id', [], null, ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}
				              	</div>
				              	<div class="col-md-6">
					                <label for="shipping_date">Delivery On</label>
									{{ Form::text('shipping_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
				             	</div>
				            </div>
				        </div>
				        <div class="form-group col-md-12">
				            <div class="row">
				                <div class="col-md-6">
					                <label for="payment_terms">Payment Terms</label>
									{{ Form::select('payment_terms', ['0'=>'Immediate','1'=>'15 Net','2'=>'30 Net'], null, ['class' => 'form-control select_item', 'id' => 'payment_terms', 'placeholder' => 'Select Payment Terms']) }}
				              	</div>
				              	<div class="col-md-6">
					                <label for="payment_terms">Payment Method</label>
									{{ Form::select('payment_method', [], null, ['class' => 'form-control select_item', 'id' => 'payment_terms', 'placeholder' => 'Select Payment Method']) }}
				              	</div>

				              	
				            </div>
		            	</div>
      				</div>
		            <div class="col-md-6 ">
			            	<div class="form-group col-md-6 vehicle_info">
								<div class="row">
									<label for="reg_number">Registration Number</label>
					                {{ Form::select('reg_number', [],null, ['class' => 'form-control ']) }}
							    </div>
			              	
			              		<div class="row">
			              			<label for="vehicle_make">Vehicle Make</label>
				                    {{ Form::text('vehicle_make', null, ['class' => 'form-control']) }}
						        </div>
			              	
			            	
			              	    <div class="row">
			              	   		<label for="vehicle_model">Vehicle Model</label>
				                    {{ Form::text('vehicle_model', null, ['class' => 'form-control']) }}
						           
			                	</div>
			                
			            	
			                    <div class="row">
									<label for="vehicle_varient">Vehicle Varient</label>
				                    {{ Form::text('vehicle_varient', null, ['class' => 'form-control']) }}
						           
			              		</div>
			              	
			            	
				           	</div>
				           	<div class="col-md-6">
			              			<label for="total">Total Amount</label>
									{{ Form::text('total', null, ['class' => 'form-control', 'id' => 'assigneed_to']) }}
					                
				            </div>
						
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
					                      	<div style="display: none;" class="col-md-12"> 
						                      	{{ Form::checkbox('billing_checkbox', '1', null, array('id' => 'billing_checkbox')) }}
						                      	<label for="billing_checkbox"><span></span>Billing address is different</label>
					                    	</div>
					                    </div>
					                  	</label>
					                </div>

					                <div class="col-md-3  billing">
						                <label for="date">Billing Name</label>
						                <input type="text" class="form-control" name="billing_name" value="" autocomplete="off" /> 
					            	</div>
					                <div class="col-md-3  billing">
						                <label for="date">Billing Mobile</label>
						                <input type="text" class="form-control" name="billing_mobile" value="" autocomplete="off"  /> 
					            	</div>
					                <div class="col-md-3  billing">
						                <label for="date">Billing Email</label>
						                <input type="text" class="form-control" name="billing_email" value="" autocomplete="off"  /> 
						            </div>
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
							            <input type="text" class="form-control" name="shipping_name" value="" autocomplete="off"  />
					            	</div>
					                <div class="col-md-3 shipping">
						                <label for="date">Shipping Mobile</label>
						                <input type="text" class="form-control" name="shipping_mobile" value="" autocomplete="off"  />
					                </div>
					                <div class="col-md-3 shipping">
						                <label for="date">Shipping Email</label>
						                <input type="text" class="form-control" name="shipping_email" value="" autocomplete="off"  /> 
					            	</div>
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
	



	<div class="save_btn_container">
  		<button type="reset" class="btn btn-default clear cancel_transaction">Close</button>
		<button type="submit" class="btn btn-success tab_approve_save_btn"> Approve </button>
  		<button type="submit" class="btn btn-success tab_save_close_btn">Save and Close </button>
  		<button type="submit" class="btn btn-success tab_save_btn">Save</button>
	</div>
	
</div>

{!! Form::close() !!} 





{{--

@stop

@section('dom_links')
@parent

--}}



<script type="text/javascript">

$('.cancel_transaction').on('click', function(e) {
		e.preventDefault();
		$('.close_full_modal').trigger('click');
		
	});
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
</script>