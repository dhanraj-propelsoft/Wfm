<div class="modal-header">
    <h4 class="modal-title float-right">Edit Accounts</h4>
</div>

    {!!Form::model($vehicles, [
        'class' => 'form-horizontal validateform'
    ]) !!}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		
		
				
		
				<div class="row">
					<div class="col-md-3 form-group">
			            {!! Form::label('registration_no', 'Registration No', array('class' => 'control-label required')) !!}
			            {{ Form::text('registration_no',$vehicles->registration_no, ['class'=>'form-control registerno', 'id' => 'registration_no','autocomplete'=>'off','data-rule-validrto'=>'true','placeholder'=>'TN 01 AAA 1234', 'onkeyup'=>"this.value = this.value.toUpperCase();"]) }}
			        </div>
			         <div class="col-md-3 customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
						{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
						<div class="custom-panel" >
							
							<input id="people_type" type="radio" name="customer" value="0" @if($vehicles->user_type == "0") checked="checked" @endif />
							<label for="people_type" ><span></span>People</label>
							<input id="business_type" type="radio" disabled="disabled" name="customer"  value="1" @if($vehicles->user_type == "1")   @endif/>
							<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
						</div>
					</div>

					
					@if($vehicles->user_type == "0")
					 <div class="col-md-3 search_container people " style= "@if($customer_label == null) display:none @endif">
							{{ Form::label('people', $customer_label, array('class' => 'control-label required')) }}
							{{ Form::select('people_id', $people, $person_name, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
							{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
							{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
							<div class="content"></div>
					</div>
					@endif
					@if($vehicles->user_type == "1")
					<div class="col-md-3 search_container business" style= "@if($customer_label == null) display:none @endif">
							{{ Form::label('business', $customer_label, array('class' => 'control-label required')) }}
							{{ Form::select('people_id', $business, $business_name, ['class' => 'form-control business_id', 'id' => 'business_id']) }}
							{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
							{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
							<div class="content"></div>
					</div>
					@endif	
					   <div class="col-md-3 form-group">
			                    {!! Form::label('vehicle_category', 'Vehicle Category', array('class' => 'control-label required')) !!}
			                    {{ Form::select('vehicle_category', $vehicle_category, $vehicles->vehicle_category_id, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
			                </div>
			          </div>
	 <div class="row ">
			     	<div class="col-md-3 form-group">
		                {!! Form::label('vehicle_configuration_id', 'Vehicle Configuration', array('class' => 'control-label required')) !!}
		                {{ Form::select('vehicle_configuration_id', $vehicle_config, null, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
		            </div> 
		            <div class="col-md-3 form-group">
		                {!! Form::label('engine_no', 'Engine No', array('class' => 'control-label')) !!}
		                {{ Form::text('engine_no', null, ['class'=>'form-control', 'id' => 'engine_no']) }}
		            </div>     
		            <div class="col-md-3 form-group">
		                {!! Form::label('chassis_no', 'Chassis No', array('class' => 'control-label')) !!}
		                {{ Form::text('chassis_no', null, ['class'=>'form-control', 'id' => 'chassis_no']) }}
		            </div>
		            <div class="col-md-3 form-group">
		                {!! Form::label('manufacturing_year', 'Manufacturing Year', array('class' => 'control-label')) !!}
		                {{ Form::text('manufacturing_year', null, ['class'=>'form-control make_year', 'autocomplete' => 'off', 'id' => 'manufacturing_year']) }}
		            </div>
		        </div>
		        <hr style="border: 1px solid black;">
				<div class="row">
						<div class="col-md-3 ">
							{{ Form::label('vehicle_permit_type','Vehicle Permit type',[ 'class' => 'control-label']) }}
							{{ Form::select('vehicle_permit_type',$permit_type,$vehicles->permit_type,['class' => 'form-control']) }}
						</div>
						<div class="col-md-3 form-group">
							{{ Form::label('fc_due','FC Due',['class' => 'control-label']) }}
							{{ Form::text('fc_due',$vehicles->fc_due,['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
						</div>
						<div class="col-md-3 form-group">
							{{ Form::label('permit_due','Permit Due',['class' => 'control-label']) }}
							{{ Form::text('permit_due',$vehicles->permit_due,['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
						</div>
						<div class="col-md-3 form-group">
							{{ Form::label('tax_due','Tax Due',['class' => 'control-label']) }}
							{{ Form::text('tax_due',$vehicles->tax_due, ['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
						</div>
				</div>		
			</div>
					<div class="row">
		                <div class="col-md-3 form-group">
							{{ Form::label('vehicle_insurance' ,'Vehicle Insurance', ['class' => 'control-label']) }}
							{{ Form::text('vehicle_insurance',$vehicles->insurance,['class' => 'form-control']) }}
						</div>
						<div class="col-md-3 form-group">
							{{ Form::label('premium_date','Insurance Due', ['class' => 'control-label']) }}
							{{ Form::text('premium_date',$vehicles->premium_date,['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
						</div>
						<div class="col-md-3 form-group">
							{{ Form::label('bank_loan','Bank Loan', ['class' => 'control-label']) }}
							{{ Form::select('bank_loan',['1' => 'Yes','0' => 'No'], $vehicles->bank_loan ,['class' =>'form-control']) }}
						</div>
						<div class="col-md-3 form-group">
							{{ Form::label('month_due_date','Month Due Date',['class' =>'control-label']) }}
							{{ Form::text('month_due_date', $vehicles->month_due_date, ['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
						</div>
		               
		            
				</div>

				<div class="row">
					<div class="col-md-3 form-group">
						{{ Form::label('warranty_km','Warranty KM', ['class' => 'control-label']) }}
						{{ Form::text('warranty_km', $vehicles->warranty_km,[ 'class' => 'form-control']) }}
					</div>
					<div class="col-md-3 form-group">
						{{ Form::label('warranty_years','Warranty Years', ['class' => 'control-label']) }}
						{{ Form::number('warranty_years', $vehicles->warranty_years, ['class' => 'form-control','min' => 1 , 'max' => 10]) }}
					</div>
					<div class="col-md-3 form-group">
						{{ Form::label('driver' , 'Driver', [ 'class' => 'control-label']) }}
						{{ Form::text('driver', $vehicles->driver , ['class' => 'form-control']) }}
					</div>
					<div class="col-md-3 form-group">
							{{ Form::label('driver_mobile_no' , 'Driver Mobile No', [ 'class' => 'control-label']) }}
							{{ Form::text('driver_mobile_no',$vehicles->driver_mobile_no, ['class' => 'form-control']) }}
						</div>
				</div>

				<hr style="border: 1px solid black;">	
		        <div class="row">
                          @if(isset($specifications)) 
                          @foreach($specifications as $specification) 
                                <?php $values = $specification->value; 
                                      $var = explode(",",$values);
                                      $value_id = $specification->value_id;
                                       $value = explode(",",$value_id);
                                       $combined_array = array_combine($value, $var);
                                      //print_r($combined_array);
                                ?>
                          <div class="col-md-3 form-group"> 
                          {{ Form::label($specification->spec_name,$specification->spec_name, ['class' => 'control-label spec required']) }}
                          @if($specification->list == '1')
                                 {!! Form::select('value',$combined_array,$specification->value, ['class' => 'form-control select_item select_spec','data-id' =>$specification->spec_id,'id' =>'spec_value']) !!}
                                 
                          @elseif($specification->list == '0')

					            {{ Form::text('insert_value',$specification->spec_value, ['class' => 'form-control','data-id' =>$specification->spec_id,'id' =>'insert_value']) }}

					      @endif
                          </div>
                           @endforeach

                             @endif 
				   </div>      
				<div class="form-group">
				<div class="row">
					<div class="col-md-12">
						{!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}
						{!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'2', 'cols'=>'40']) !!}
					</div>
				</div>
			</div>	

		
		
		</div>	
		
			</div>
<div class="modal-footer">                                            
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

<script>
$(document).ready(function() {
	basic_functions();

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
		    name: { required: true },                
		},

		messages: {
		    name: { required: "Account Name is required." },                
		},

		invalidHandler: function(event, validator) 
		{ 
		    //display error alert on form submit   
		    $('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) 
		{ // hightlight error inputs
		    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
		    label.closest('.form-group').removeClass('has-error');
		    label.remove();
		},

        submitHandler: function(form) {
            $('.loader_wall_onspot').show();

                 var articles = $( "select[name=value] option:selected" ).map(function(){ return $(this).text() }).get();  
			 	 var values = $('input[name="insert_value"]').map(function(){return this.value}).get(); 
			 	 var list_key = $( "select[name=value]" ).map(function(){ return $(this).attr('data-id') }).get();
                 var text_key = $( "input[name=insert_value]" ).map(function(){ return $(this).attr('data-id') }).get();
                //alert(text_key);
            $.ajax({
            url: '{{ url('user/vms/registered_vehicle_update') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
           		articles: articles,
				values:values,
				list_key:list_key,
				text_key:text_key,
              id:$('input[name=id]').val(),
				registration_no: $('input[name=registration_no]').val(),
				user_type: $('input[name=customer]:checked').val(),
				
				vehicle_category: $('select[name=vehicle_category]').val(),
				vehicle_make: $('select[name=vehicle_make]').val(),
				vehicle_model: $('select[name=vehicle_model]').val(),
				vehicle_variant: $('select[name=vehicle_variant]').val(),
				vehicle_configuration_id: $('select[name=vehicle_configuration_id]').val(),
				
				engine_no: $('input[name=engine_no]').val(),
				chassis_no: $('input[name=chassis_no]').val(),
				manufacturing_year: $('input[name=manufacturing_year]').val(),
				vehicle_permit_type: $('select[name=vehicle_permit_type]').val(),
				fc_due: $('input[name=fc_due]').val(),
				permit_due: $('input[name=permit_due]').val(),
				tax_due: $('input[name=tax_due]').val(),
				vehicle_insurance: $('input[name=vehicle_insurance]').val(),
				premium_date: $('input[name=premium_date]').val(),
				bank_loan: $('input[name=bank_loan]').val(),
				month_due_date: $('input[name=month_due_date]').val(),
				warranty_km: $('input[name=warranty_km]').val(),
				warranty_years: $('input[name=warranty_years]').val(),
				driver: $('input[name=driver]').val(),
				driver_mobile_no: $('input[name=driver_mobile_no]').val(),
				description: $('textarea[name=description]').val()               
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.registration_no+`</td>
					<td>`+data.data.owner_name+`</td>
						
					<td>`+data.data.vehicle_category+`</td>
					<td>`+data.data.vehicle_config_name+`</td>
					<td>`+data.data.description+`</td>
					<td>
						<label class="grid_label badge badge-success status">Active</label>
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option value="1">Active</option>
							<option value="0">In-active</option>
						</select>
					</td>
					<td>
					<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`, `edit`, data.message,data.data.id);

				$('.loader_wall_onspot').hide();
				},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
	});