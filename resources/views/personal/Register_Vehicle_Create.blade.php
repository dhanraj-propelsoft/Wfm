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
</style>


<div class="modal-header">
	<h4 class="modal-title float-right"> Add Registered Vehicle</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

		<div class="modal-body">
		 <div class="form-body">
		  <div class="row">
			<div class="col-md-3 form-group">
              {!! Form::label('registration_no', 'Registration No', array('class' => 'control-label required')) !!}
		                   <!--  <input type="text" name="registration_no" class="form-control registerno" id="registration_no" placeholder="TNA 01 AA 1234"  pattern="[1-9]{3}[A-Z]{4}"> -->
                {{ Form::text('registration_no', null, ['class'=>'form-control registerno', 'id' => 'registration_no','autocomplete'=>'off','data-rule-validrto'=>'true','placeholder'=>'TNA 01 AA 1234', 'onkeyup'=>"this.value = this.value.toUpperCase();"]) }} 	                   
		    </div>
		   <div class="col-md-3 customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
					{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
			<div class="custom-panel" >
			<input id="people_type" type="radio" name="customer" value="0"  checked="checked" />
			<label for="people_type" ><span></span>People</label>
			<input id="business_type" type="radio" name="business" value="0"   readonly="readonly" />
			<label for="people_type" ><span></span>Business</label>
			</div>
		   </div>
     		<div class="col-md-3 form-group">
	            {!! Form::label('customername', 'CustomerName', array('class' => 'control-label required')) !!}
	             {{ Form::select('customername', $people, null, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
		    </div>

	    	<div class="col-md-3 form-group">
		       {!! Form::label('vehicle_category', 'Vehicle Category', array('class' => 'control-label required')) !!}
		        {{ Form::select('vehicle_category', $vehicle_category, null, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
		    </div>

    	   </div>
			<div class="row">
			 <div class="col-md-3 form-group">
		      {!! Form::label('vehicle_config', 'Vehicle Configuration', array('class' => 'control-label required')) !!}
		        {{ Form::select('vehicle_config', $vehicle_config, null, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
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
		                    {{ Form::select('vehicle_make', $vehicle_make_id, null, ['class'=>'form-control select_item', 'id' => 'vehicle_make']) }}
		                 		                </div>     
		                 		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_model', 'Model', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_model', ['0' => 'Select Model'], null, ['class'=>'form-control select_item', 'id' => 'vehicle_model']) }}
		                 		                </div>	            
		                 		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_variant', 'Variant', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_variant', ['0' => 'Select Varient'], null, ['class'=>'form-control select_item', 'id' => 'vehicle_variant']) }}
		                 		                </div> -->

		    </div>
				

				<!-- <div class="form-group">
					<div class="row">
						
					</div>
				</div> -->
			<hr style="border: 1px solid black;">
			<div class="row">
			<div class="col-md-3 ">
				{{ Form::label('vehicle_permit_type','Vehicle Permit type',[ 'class' => 'control-label']) }}
				{{ Form::select('vehicle_permit_type',$permit_type,null,['class' => 'form-control']) }}
			</div>
			<div class="col-md-3 form-group">
				{{ Form::label('fc_due','FC Due',['class' => 'control-label']) }}
				{{ Form::text('fc_due',null,['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
			</div>
			<div class="col-md-3 form-group">
				{{ Form::label('permit_due','Permit Due',['class' => 'control-label']) }}
				{{ Form::text('permit_due',null,['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
			</div>
			<div class="col-md-3 form-group">
				{{ Form::label('tax_due','Tax Due',['class' => 'control-label']) }}
				{{ Form::text('tax_due',null, ['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
			</div>
			</div>
				
			<div class="row">
			<div class="col-md-3 form-group">
				{{ Form::label('vehicle_insurance' ,'Vehicle Insurance', ['class' => 'control-label']) }}
				{{ Form::text('vehicle_insurance',null,['class' => 'form-control']) }}
			</div>
			<div class="col-md-3 form-group">
				{{ Form::label('premium_date','Insurance Due', ['class' => 'control-label']) }}
				{{ Form::text('premium_date',null,['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
			</div>
			<div class="col-md-3 form-group">
				{{ Form::label('bank_loan','Bank Loan', ['class' => 'control-label']) }}
				{{ Form::select('bank_loan',['1' => 'Yes','0' => 'No'],null ,['class' =>'form-control','placeholder' => 'Select Loan']) }}
			</div>
				<div class="col-md-3 form-group">
				{{ Form::label('month_due_date','Month Due Date',['class' =>'control-label']) }}
				{{ Form::text('month_due_date',null, ['class' => 'form-control date-picker datetype','data-date-format' => 'dd-mm-yyyy']) }}
				</div>
			</div>
		<div class="row">
			<div class="col-md-3 form-group">
				{{ Form::label('warranty_km','Warranty KM', ['class' => 'control-label']) }}
				{{ Form::text('warranty_km',null,[ 'class' => 'form-control']) }}
			</div>
			<div class="col-md-3 form-group">
			 {{ Form::label('warranty_years','Warranty Years', ['class' => 'control-label']) }}
			 {{ Form::number('warranty_years',null, ['class' => 'form-control','min'=>1,'max' => 10]) }}
			</div>
			<div class="col-md-3 form-group">
				{{ Form::label('driver' , 'Driver', [ 'class' => 'control-label']) }}
				{{ Form::text('driver', null , ['class' => 'form-control']) }}
			</div>
			<div class="col-md-3 form-group">
			 {{ Form::label('driver_mobile_no' , 'Driver Mobile No', [ 'class' => 'control-label']) }}
			 {{ Form::text('driver_mobile_no', null , ['class' => 'form-control']) }}
			</div>
		</div>
				
				
	<hr style="border: 1px solid black;">
	<div class="row" >
        @if(isset($specifications))
          @foreach($specifications as $specification) 
            <?php
            $values = $specification->value; 
            $var = explode(",",$values);
            $value_id = $specification->value_id;
            $value = explode(",",$value_id);
            $combined_array = array_combine($value, $var);
             // print_r($combined_array);
                                ?>
            <div class="col-md-3 form-group"> 
              {{ Form::label($specification->spec_name,$specification->spec_name, ['class' => 'control-label spec required']) }}
               @if($specification->list == '1')
                                     
                {!! Form::select('value',$combined_array,null, ['class' => 'form-control select_item select_spec','data-id' =>$specification->spec_id,'id' =>'spec_value']) !!}
                                 
                @elseif($specification->list == '0')
	           	 {{ Form::text('spec_value',$specification->display_name, ['class'=>'form-control spec_name','id' => $specification->display_id,'data-id' => $specification->spec_id]) }}

				@endif
             </div>
          @endforeach

        @endif
	</div>
	<div class="row">
	<div class="col-md-12 form-group">
		{!! Form::label('description', 'Description', ['class' => 'control-label ']) !!}			
		{!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'2', 'cols'=>'40']) !!}
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
	/*$('.registerno').keyup(function() {
	$('#registration_no').inputmask('ZZZ [9][9] [Z][Z] 9999');
	});*/

    /* $('.registerno').keyup(function() {
	     $("#registration_no").inputmask({
	            mask: "99:59:59",
	            definitions: {'5': {validator: "[0-5]"}}
	    });
 	});
*/
	basic_functions();
		

	$('.make_year').datepicker({
        autoclose: true,
        viewMode: "years", 
    	minViewMode: "years",
        format: 'yyyy'
    });

	

	

	

    

	$('#person_id').each(function() {
		$(this).prepend('<option value="0"></option>');
		select_user($(this));
	});

	$('#business_id').each(function() {
		$(this).prepend('<option value="0"></option>');
		select_business($(this));
	});
    	
   
   

   

 	$('#vehicle_make').change(function() {
	
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
				
				success:function(data, textStatus, jqXHR) {
					var model = data.result;
					//console.log(model);
					for (var i in model) {
						$('#vehicle_variant').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");
					}
				},
				error:function(jqXHR, textStatus, errorThrown){
				}
			});
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

});


$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			registration_no: { 
				required: true ,
				remote: {
					url: '{{ route('vms.get_register_number') }}',
					type: 'post',
					data: {
						_token: $('input[name=_token]').val()
					}
				}

			},                
			customername: { required: true },                
			/*vehicle_name: { required: true },*/               
			vehicle_category: { required: true },
			vehicle_config : {required: true },
			vehicle_make : { required: true },  
			vehicle_model : { required: true },
			vehicle_variant : { required: true },             
		},

		messages: {
			registration_no: { required: "Registration No is required.", remote: " Reg No Already Exits! "},               
			customername: { required: "customer Name is required." },               
			/*vehicle_name: { required: "Vehicle Name is required." },*/               
			vehicle_category: { required: "Vehicle Category is required." },  
			vehicle_make : { required: "Make Name is required." },
			vehicle_config: { required: "Configuration is required" },
			vehicle_model : { required: "Model Name is required." },             
			vehicle_variant : { required: "Varient Name is required." },             

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
			 	 var values = $('input[name="spec_value"]').map(function(){return this.value}).get();
			 	 var list_key = $( "select[name=value]" ).map(function(){ return $(this).attr('data-id') }).get();
                 var text_key = $( "input[name=spec_value]" ).map(function(){ return $(this).attr('data-id') }).get();
                 //alert(text_key);
			$.ajax({
			url: '{{ route('myvehicle_registerion.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				articles: articles,
				values:values,
				list_key:list_key,
				text_key:text_key,
				registration_no: $('input[name=registration_no]').val(),
				user_type: $('input[name=customer]:checked').val(),
				customername: $('select[name=customername]:not([disabled])').val(),
				vehicle_category: $('select[name=vehicle_category]').val(),
				vehicle_config:$('select[name=vehicle_config]').val(),
				vehicle_make: $('select[name=vehicle_make]').val(),
				vehicle_model: $('select[name=vehicle_model]').val(),
				vehicle_variant: $('select[name=vehicle_variant]').val(),
				
				engine_no: $('input[name=engine_no]').val(),
				chassis_no: $('input[name=chassis_no]').val(),
				manufacturing_year: $('input[name=manufacturing_year]').val(),
				vehicle_permit_type: $('select[name=vehicle_permit_type]').val(),
				fc_due: $('input[name=fc_due]').val(),
				permit_due: $('input[name=permit_due]').val(),
				tax_due: $('input[name=tax_due]').val(),
				vehicle_insurance: $('input[name=vehicle_insurance]').val(),
				premium_date: $('input[name=premium_date]').val(),
				bank_loan: $('select[name=bank_loan]').val(),
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
					</td></tr>`, `add`, data.message);

				$('.loader_wall_onspot').hide();
				},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});


	

</script>