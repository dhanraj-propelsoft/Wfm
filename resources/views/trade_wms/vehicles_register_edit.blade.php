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
.custom-panel{
	border: 1px solid #d7dbe0; 
	border-radius: 2px;
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
#spec_value-error{
	color:red;
}

</style>
<div class="modal-header">
	<h4 class="modal-title float-right">Edit Vehicle</h4>
</div>

	{!!Form::model($vehicles, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}

			
				<div class="row">
					<div class="col-md-3 form-group">
			            {!! Form::label('registration_no', 'Registration No', array('class' => 'control-label required')) !!}
			            {{ Form::text('registration_no', null, ['class'=>'form-control registerno', 'id' => 'registration_no','autocomplete'=>'off','data-rule-validrto'=>'true','placeholder'=>'TN 01 AAA 1234', 'onkeyup'=>"this.value = this.value.toUpperCase();"]) }}
			        </div>
			        <div class="col-md-6">
			        	{{ Form::label('customer', 'Customer', array('class' => 'control-label required')) }} <br>
			        	<div class="row custom-panel">
					        <div class="col-md-6 customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
								<!-- {{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br> -->
								<div class="" >
									<input id="business_type" type="radio" name="customer"  value="1" @if($vehicles->user_type == "1") checked="checked"  @endif/>
									<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
									<input id="people_type" type="radio" name="customer" value="0" @if($vehicles->user_type == "0") checked="checked" @endif />
									<label for="people_type" ><span></span>People</label>
								</div>
							</div>

							
							
							<div class="col-md-6 search_container people " style= "padding: 5px;@if($customer_label == null) display:none @endif">
									<!-- {{ Form::label('people', $customer_label, array('class' => 'control-label required')) }} -->
									{{ Form::select('people_id', $people, $person_name, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
									{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
									{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
									<div class="content"></div>
							</div>
							
							<div class="col-md-6 search_container business" style= "padding: 5px;@if($customer_label == null) display:none @endif">
									<!-- {{ Form::label('business', $customer_label, array('class' => 'control-label required')) }} -->
									{{ Form::select('people_id', $business, $business_name, ['class' => 'form-control business_id', 'id' => 'business_id']) }}
									{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
									{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}
									<div class="content"></div>
							</div>
						</div>
					</div>
					
			                <!-- <div class="col-md-4 search_container"> 
			                							{{ Form::label('person_id', 'Select Owner', array('class' => 'control-label required')) }}
			                							{{ Form::select('person_id', $people,$vehicles->owner_id , ['class' => 'form-control person_id select_item', 'id' => 'person_id']) }}
			                							{{ Form::checkbox('account_person_type_id', 'employee', true, ['id' => 'account_person_type_id']) }}
			                							{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
			                							<div class="content"></div>
			                						</div> -->
							<!-- <div class="col-md-3">
								{!! Form::label('vehicle_segment', 'Vehicle Segment', array('class' => 'control-label required')) !!}
								{{ Form::select('vehicle_segment', [], null, ['class'=>'form-control select_item col-md-6', 'id' => 'vehicle_segment']) }}
							</div>  -->
			                <!-- <div class="col-md-3">
			                	{!! Form::label('vehicle_name', 'Vehicle Name', array('class' => 'control-label required')) !!}
			                   {{ Form::select('vehicle_name', $config_name, null, ['class'=>'form-control select_item', 'id' => 'vehicle_name']) }}
			                </div> -->
			          <div class="col-md-3 form-group">
		                {!! Form::label('vehicle_configuration_id', 'Vehicle Configuration', array('class' => 'control-label required')) !!}
		                {{ Form::select('vehicle_configuration_id', $vehicle_config, null, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
		            </div> 
						
				</div>
		
				<div class="row ">
					 <div class="col-md-3 form-group">
			                    {!! Form::label('vehicle_category', 'Vehicle Category', array('class' => 'control-label required')) !!}
			                    {{ Form::select('vehicle_category', $vehicle_category, $vehicles->vehicle_category_id, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
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
		                <!-- <div class="col-md-3 form-group">
		                	{!! Form::label('vehicle_make', 'Make', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_make', $vehicle_make_id, $vehicles->vehicle_make_id, ['class'=>'form-control select_item', 'id' => 'vehicle_make']) }}
		                </div>     
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_model', 'Model', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_model', $vehicle_model_id, $vehicles->vehicle_model_id, ['class'=>'form-control select_item', 'id' => 'vehicle_model']) }}
		                </div>	            
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_variant', 'Variant', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_variant', $vehicle_variant_id, $vehicles->vehicle_variant_id, ['class'=>'form-control select_item', 'id' => 'vehicle_variant']) }}
		                </div> -->
		            
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
			
			<!-- <div class="row form-group">
				<div class="col-12">
					<div class="form-inline">
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_body_type', 'Body Type', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_body_type', [], null, ['class'=>'form-control select_item', 'id' => 'vehicle_body_type']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                	{!! Form::label('vehicle_rim_type', 'Rim / Wheel', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_rim_type', [], null, ['class'=>'form-control select_item', 'id' => 'vehicle_rim_type']) }}
		                </div>     
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_tyre_type', 'Tyre Type', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_tyre_type', [], null, ['class'=>'form-control select_item', 'id' => 'vehicle_tyre_type']) }}
		                </div>	            
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_tyre_size', 'Tyre Size', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_tyre_size', [], null, ['class'=>'form-control select_item', 'id' => 'vehicle_tyre_size']) }}
		                </div>
		            </div>
				</div>			
			</div> -->
			<!-- <div class="row form-group">
				<div class="col-12">
					<div class="form-inline">
		                <div class="col-md-3 form-group">
		                    {!! Form::label('fuel_type', 'Fuel Type', array('class' => 'control-label')) !!}
		                    {{ Form::select('fuel_type', [], null, ['class'=>'form-control select_item', 'id' => 'fuel_type']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                	{!! Form::label('vehicle_wheel_type', 'No of Wheels', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_wheel_type', [], null, ['class'=>'form-control select_item', 'id' => 'vehicle_wheel_type']) }}
		                </div>     
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_drivetrain', 'Drivetrain', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_drivetrain', [], null, ['class'=>'form-control select_item', 'id' => 'vehicle_drivetrain']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_usage', 'Vehicle Usage', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_usage', [], null, ['class'=>'form-control select_item', 'id' => 'vehicle_usage']) }}
		                </div>
		            </div>
				</div>			
			</div> -->
			
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
				<div class="row">
						<div style="padding-left: 16px;">
							<a style="color: #3366ff;" class="add_contact">Add Additional Contact Info</a>
						</div>
					</div>
					<div class="row" id="textboxDiv">
						 @if(isset($contacts))
						@foreach($contacts as $key => $contact)
							<div class="col-md-3 form-group">
								{{ Form::label('Contact_person ' , 'Contact Person', [ 'class' => 'control-label']) }}
								{{ Form::text('Contact_person',$key, ['class' => 'form-control']) }}
							</div>
							<div class="col-md-3 form-group">
							{{ Form::label('Contact_mobile' , 'Mobile', [ 'class' => 'control-label']) }}
							{{ Form::text('Contact_mobile',$contact, ['class' => 'form-control']) }}
							</div>	
						@endforeach
						@endif
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
                          	 {{ Form::label($specification->spec,$specification->spec, ['class' => 'control-label spec required']) }}
                          	 @if($specification->list == 1)
                          	    {!! Form::select('value',$combined_array,$specification->spec_value_id, ['class' => 'form-control select_item select_spec','data-id' =>$specification->spec_id,'id' =>'spec_value','data-rid'=>$specification->id]) !!}
                          	    @elseif($specification->list == '0')

					            {{ Form::text('spec_value',$specification->spec_value, ['class' => 'form-control','data-id'=>$specification->id,'data-sid'=>$specification->spec_id]) }}
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

			<div class="dropzone" id="image-upload" style="width: 194px;"> 
					@if($vehicles->vehicle_image != null)
					<a target="_blank" href="{{asset('public/vehicle_images/vehicle_id-'.$vehicles->id.'/'.$vehicles->id.'.jpg')}}">
					<img alt="Select Image"  src="{{asset('public/vehicle_images/vehicle_id-'.$vehicles->id.'/'.$vehicles->id.'.jpg')}}" width="120" height="120" />
					</a> 	
					@endif						 
			</div>
		</div>
	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>


	{!! Form::close() !!}

<script type="text/javascript">
	basic_functions();

	var image_upload = new Dropzone("div#image-upload", {
	  paramName: 'file',
	  url: "{{route('upload_vehicle_image')}}",
	  params: {
		  _token: '{{ csrf_token() }}'
	  },
	  dictDefaultMessage: "Drop or click to Change image",
	  clickable: true,
	  maxFilesize: 5, // MB
	  acceptedFiles: "image/*",
	  maxFiles: 1,
	  autoProcessQueue: false,
	  addRemoveLinks: true,
	  uploadMultiple: false,
	  removedfile: function(file) {
		  file.previewElement.remove();
	  },
	  maxfilesexceeded: function (file) {
            image_upload.removeFile(file);
        },
	  success: function(file, response){
		image_call_back(response.data.path, response.data.id);
	  }
	});

	$('.make_year').datepicker({
        autoclose: true,
        viewMode: "years", 
    	minViewMode: "years",
        format: 'yyyy'
    });

    
	if ($("#people_type").is(":checked")) {
        $('.people').show();
		$('.business').hide();
		$('.people').find('select').prop('disabled', false);
		$('.business').find('select').prop('disabled', true);
}

	if ($("#business_type").is(":checked")) {
         $('.business').show();
		$('.people').hide();
		$('.business').find('select').prop('disabled', false);
		$('.people').find('select').prop('disabled', true);
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


	$('#registration_no').blur(function(){
		
		var vehicle_no = $(this).val();
		if(vehicle_no)
		{
			$.ajax({

			url : '{{ route('get_register_number') }}',
			type : 'post',
			data: 
			{
				_token : '{{csrf_token() }}',
				registration_no : vehicle_no
			},
			success:function(data)
			{
						

				if( data.status == "0")
				{
					$('.vehicle_search_modal_ajax').modal('show');
						$('.vehicle_search_modal_ajax').find('.yes_btn').css('display','none');
						$('.vehicle_search_modal_ajax').find('#content').text('This Vehicle is already exits !.');

						$('.add_modal_ajax_btn').on('click',function(){
							$('.vehicle_search_modal_ajax').modal('hide');

							$('#registration_no').val('');

							
						});
				}
				if(data.status == "1" && data.data != null)
				{

						
						$('.vehicle_search_modal_ajax').modal('show');
						$('.vehicle_search_modal_ajax').find('#content').text('');
						$('.vehicle_search_modal_ajax').find('#content').text('This vehicle already exists in this system..Do you want to copy?');

						$('.vehicle_search_modal_ajax').find('.yes_btn').css('display','block');
						$('.vehicle_search_modal_ajax').find('.add_modal_ajax_btn').text('');
						$('.vehicle_search_modal_ajax').find('.add_modal_ajax_btn').text('No');

						$('.add_modal_ajax_btn').on('click',function(){
							$('.vehicle_search_modal_ajax').modal('hide');

							$('#registration_no').val('');

							
						});	
						$('.yes_btn').on('click',function()
						{
							$('.vehicle_search_modal_ajax').modal('hide');


							if(data.data.user_type == 0) {
								$('#people_type').prop('checked', true);
								$('#business_type').prop('checked', false);
								$('.people').show();
								$('.business').hide();
								$('.business select[name=people_id]').prop('disabled', true);
								$('.people select[name=people_id]').prop('disabled', false);
							}
							else if(data.data.user_type == 1) {
								$('#business_type').prop('checked', true);
								$('#people_type').prop('checked', false);
								$('.people').hide();
								$('.business').show();
								$('.business select[name=people_id]').prop('disabled', false);
								$('.people select[name=people_id]').prop('disabled', true);
							}
							if( data.name)
							{
								$('select[name=people_id]').append("<option value='"+data.data.owner_id+"'>"+data.name+"</option>");
							}
					
							$('select[name=people_id]').val(data.data.owner_id);
							$('select[name=people_id]').trigger('change');
							//$('select[name=people_id]').val(data.data.owner_id);
							$('select[name=vehicle_config]').val(data.data.vehicle_configuration_id);
							$('select[name=vehicle_config]').trigger('change');
							$('select[name=vehicle_category]').val(data.data.vehicle_category_id);
							$('#engine_no').val(data.data.engine_no);
							$('#chassis_no').val(data.data.chassis_no);
							$('#manufacturing_year').val(data.data.manufacturing_year);
							$('#permit_type').val(data.data.permit_type);
							$('#fc_due').val(data.data.fc_due);
							$('#permit_due').val(data.data.permit_due);
							$('#tax_due').val(data.data.tax_due);
							$('#vehicle_insurance').val(data.data.insurance);
							$('#premium_date').val(data.data.premium_date);
							$('#bank_loan').val(data.data.bank_loan);
							$('#month_due_date').val(data.data.month_due_date);
							$('#warranty_km').val(data.data.warranty_km);
							$('#warranty_years').val(data.data.warranty_years);
							$('#driver').val(data.data.driver);
							$('#driver_mobile_no').val(data.data.driver_mobile_no);

						});		
			 		

				}
				
				
			},
			error:function()
			{

			}
			});
		}
		
		

	});

	var text_box = $('input[name = Contact_person]').length;
	$('.add_contact').on('click',function(){
		var box = ++text_box;
		if(text_box > 4){
			$('.delete_modal_ajax').find('.modal-title').text("Alert:");
			$('.delete_modal_ajax').find('.modal-body').text("Allowed To Store Only Four Contacts");
			$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
			$('.delete_modal_ajax').modal('show');
		}else{
			$("#textboxDiv").append("<div class='col-md-3 form-group'><label for='contact' class='control-label'>Contact Person "+box+"</label><input class='form-control' id='Contact_person"+box+"' name='Contact_person' type='text' placeholder ='Name With designation'></div><div class='col-md-3 form-group'><label for='mobile_no' class='control-label'>Mobile No</label><input class='form-control' id='Contact_mobile"+box+"' name='Contact_mobile' type='text'></div>"); 
		} 
	});

	$('select[name=vehicle_configuration_id]').on('change',function(){
          var Configuration = $(this).val();
          $.ajax({
				url: '{{ route('get_vehicle_category') }}',
				type: "get",
				data: {
					Configuration: Configuration,
				},
				success:function(data, textStatus, jqXHR) {
					//console.log(data);
					var id = data.category_id;
					if(id == null){
						id = '';
					}else{
						id = data.category_id;
					}
					var name = data.display_name;
					if(name == null){
						name = '';
					}else{
						name = data.display_name;
					}
					$('select[name=vehicle_category]').empty();
					$('select[name=vehicle_category]').html(`<option value=`+id+`>`+name+`</option>`);
				},
				error:function(jqXHR, textStatus, errorThrown){
				}
			});
	});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			registration_no: { required: true },                
			people_id: { required: true },                
			/*vehicle_name: { required: true },*/               
			vehicle_category: { required: true },
			vehicle_make : { required: true },  
			vehicle_model : { required: true },
			vehicle_variant : { required: true }, 
			vehicle_configuration_id: { required:true },
			spec_value:  { required: true },         
            value:{ required: true },                 
		},

		messages: {
			registration_no: { required: "Registration No is required." },               
			people_id: { required: "Owner Name is required." },               
			/*vehicle_name: { required: "Vehicle Name is required." },*/               
			vehicle_category: { required: "Vehicle Category is required." }, 
			vehicle_make : { required: "Make Name is required." },
			vehicle_model : { required: "Model Name is required." },             
			vehicle_variant : { required: "Varient Name is required." },  
			vehicle_configuration_id : { required: " Configuration is required."},
			spec_value : { required: "Specification value is required." },             
            value: { required: "Specification value is required." },                 
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
                 var text_registered_id = $( "input[name=spec_value]" ).map(function(){ return $(this).attr('data-id') }).get();
                 var text = $( "input[name=spec_value]" ).map(function(){ return this.value}).get();
                 var text_spec_id = $( "input[name=spec_value]" ).map(function(){ return $(this).attr('data-sid') }).get();
                 var list_spec_id = $("select[name=value]").map(function(){ return $(this).attr('data-id') }).get();
                 var list_registered_id = $("select[name=value]" ).map(function(){ return $(this).attr('data-rid') }).get();
                 var value_id = $("select[name=value] option:selected").map(function(){ return $(this).val() }).get();
                 var values = $("select[name=value] option:selected").map(function(){ return $(this).text() }).get();
                 var contact_person = $("input[name=Contact_person]").map(function(){ return $(this).val() }).get();
                
            	var contact_number = $("input[name=Contact_mobile]").map(function(){ return $(this).val() }).get();

			$.ajax({
			url: '{{ route('vehicle_registered.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method:'PATCH',
				text_registered_id:text_registered_id,
				text:text,
				text_spec_id:text_spec_id,
				list_spec_id:list_spec_id,
				list_registered_id:list_registered_id,
				value_id:value_id,
				values:values,
				id:$('input[name=id]').val(),
				registration_no: $('input[name=registration_no]').val(),
				user_type: $('input[name=customer]:checked').val(),
				people_id: $('select[name=people_id]:not([disabled])').val(),
				//vehicle_name: $('select[name=vehicle_name]').val(),
				vehicle_category: $('select[name=vehicle_category]').val(),
				vehicle_make: $('select[name=vehicle_make]').val(),
				vehicle_model: $('select[name=vehicle_model]').val(),
				vehicle_variant: $('select[name=vehicle_variant]').val(),
				vehicle_configuration_id: $('select[name=vehicle_configuration_id]').val(),
				/*vehicle_body_type: $('select[name=vehicle_body_type]').val(),
				vehicle_rim_type: $('select[name=vehicle_rim_type]').val(),
				vehicle_tyre_type: $('select[name=vehicle_tyre_type]').val(),
				vehicle_tyre_size: $('select[name=vehicle_tyre_size]').val(),
				fuel_type: $('select[name=fuel_type]').val(),
				vehicle_wheel_type: $('select[name=vehicle_wheel_type]').val(),
				vehicle_drivetrain: $('select[name=vehicle_drivetrain]').val(),
				vehicle_usage: $('select[name=vehicle_usage]').val(),
				is_own: $('select[name=is_own]').val(),*/
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
				description: $('textarea[name=description]').val(),
				contact_person: contact_person,
				contact_number: contact_number,                 
				},
			success:function(data, textStatus, jqXHR) {


				image_upload.on("sending", function(file, xhr, response) {
								response.append("id", data.data.id);
							});

				image_upload.processQueue();

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
					<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>&nbsp;<a data-id="`+data.data.id+`" class="grid_label action-btn edit-icon vechile_history"><i class="fa fa-history" aria-hidden="true"></i></a>
					</td></tr>`, `edit`, data.message,data.data.id);

				$('.loader_wall_onspot').hide();
				},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

// ****  Getting datas of vehicles based on vehicle name  *****

	$('#vehicle_name').change(function() {
		var id = $('#vehicle_name option:selected').val();
		//alert(id);
		
		$.ajax({
			url: "{{ route('get_vehicle_all_data') }}",
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id: id,
			},
			dataType: "json",
			success:function(data, textStatus, jqXHR) {
				$("#vehicle_category").val(data.data.vehicle_category_id);
				$("#vehicle_make").val(data.data.vehicle_make_id);
				$("#vehicle_model").val(data.data.vehicle_model_id);
				$("#vehicle_variant").val(data.data.vehicle_variant_id);
				$("#drivetrain").val(data.data.vehicle_drivetrain_id);
				$("#vehicle_body_type").val(data.data.vehicle_body_type_id);
				$("#vehicle_rim_type").val(data.data.vehicle_rim_type_id);
				$("#vehicle_tyre_type").val(data.data.vehicle_tyre_type_id);
				$("#vehicle_tyre_size").val(data.data.vehicle_tyre_size_id);
				$("#fuel_type").val(data.data.fuel_type_id);				
				$("#vehicle_wheel_type").val(data.data.vehicle_wheel_type_id);				
				$("#vehicle_drivetrain").val(data.data.vehicle_drivetrain_id);

				$("#drivetrain, #fuel_type, #vehicle_category, #vehicle_wheel_type, #vehicle_make, #vehicle_rim_type, #vehicle_model, #vehicle_tyre_size, #vehicle_tyre_type, #vehicle_variant, #vehicle_body_type, #vehicle_drivetrain").trigger('change');
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
	});

// ****  End of getting datas of vehicles based on license number  *****

	$(document).ready(function(){
	$('.registerno').keyup(function() {
        $('#registration_no').inputmask("mask", 
              {"mask": "aa[a][9][9][A][A][99]99",
               placeholder:"",
               removeMaskOnSubmit: true,
               autoclear: false,
               autoUnmask: true,
               insertMode: false,
               greedy: false,
               skipOptionalPartCharacter: " ",
               }); 
     });
		$('#vehicle_make').change(function() {
			//alert();
			var make = $('#vehicle_make option:selected').val();
			//alert(make);
			$('#vehicle_model').html('');
			$('#vehicle_model').append("<option value=''>Select Model</option>");
			$('#vehicle_variant').html('');
			$('#vehicle_variant').append("<option value=''>Select Varient</option>");
			$.ajax({
				url: '{{ route('get_vehicle_model_name') }}',
				type: "post",
				data: {
					_token: '{{ csrf_token() }}',
					id: make,
				},
				dataType: "json",
				success:function(data, textStatus, jqXHR) {
					var model = data.result;
					for (var i in model) {
						$('#vehicle_model').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");
					}
				},
				error:function(jqXHR, textStatus, errorThrown){
				}
			});
		});
//**** End of getting model based on make ****//

//**** Start of getting varient based on modal ****//
		$('#vehicle_model').change(function() {
			var model_id = $('#vehicle_model option:selected').val();
			//alert(make);
			$('#vehicle_variant').html('');
			$('#vehicle_variant').append("<option value=''>Select Varient</option>");
			$.ajax({
				url: '{{ route('get_vehicle_variant_name') }}',
				type: "post",
				data: {
					_token: '{{ csrf_token() }}',
					id: model_id,
				},
				dataType: "json",
				success:function(data, textStatus, jqXHR) {
					var model = data.result;
					for (var i in model) {
						$('#vehicle_variant').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");
					}
				},
				error:function(jqXHR, textStatus, errorThrown){
				}
			});
		});	
//**** End of getting varient based on modal ****//	
	
	});

</script>