<div class="modal-header">
	<h4 class="modal-title float-right">Add Customers</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			<div class="col-md-4">
				{{ Form::label('name', 'Name', ['class'=>' control-label required']) }} 
			</div>			
			<div class="col-md-8">
				{!! Form::text('name', null , ['class' => 'form-control', 'placeholder' => 'Name']) !!}
				
			</div>	
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('mobile_no', 'Mobile', ['class'=>'control-label required']) }}
			</div>			
			<div class="col-md-8">
				{!! Form::text('mobile_no', null , ['class' => 'form-control', 'placeholder' => 'Mobile']) !!} 				
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('email_address', 'E-Mail ID', ['class'=>'control-label']) }}
			</div>			
			<div class="col-md-8">
				{!! Form::text('email_address', null , ['class' => 'form-control', 'placeholder' => 'E-Mail ID']) !!}				
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('pan', 'PAN', ['class'=>'control-label']) }}
			</div>			
			<div class="col-md-8">
				{!! Form::text('pan', null , ['class' => 'form-control', 'placeholder' => 'PAN']) !!}				
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('aadhar_no', 'Aadhar No', ['class'=>'control-label']) }}
			</div>			
			<div class="col-md-8">
				{!! Form::text('aadhar_no', null , ['class' => 'form-control', 'placeholder' => 'Aadhar No']) !!}				
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('passport_no', 'Passport No', ['class'=>'control-label']) }}
			</div>			
			<div class="col-md-8">
				{!! Form::text('passport_no', null , ['class' => 'form-control', 'placeholder' => 'Passport No']) !!}				
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('license_no', 'License No', ['class'=>'control-label']) }}
			</div>			
			<div class="col-md-8">
				{!! Form::text('license_no', null , ['class' => 'form-control', 'placeholder' => 'License No']) !!}		
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('address', 'Address', ['class'=>'control-label']) }}
			</div>			
			<div class="col-md-8">
				{!! Form::text('address', null , ['class' => 'form-control', 'placeholder' => 'Address']) !!}				
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('state', 'State', ['class'=>'control-label required']) }}
			</div>			
			<div class="col-md-8">
				{{ Form::select('user_state', $states, null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }}				
			</div>			
		</div>

		<div class="row">
			<div class="col-md-4">
				{{ Form::label('city', 'city', ['class'=>'control-label required']) }}
			</div>			
			<div class="col-md-8">
				{{ Form::select('user_city', ['' => 'Select city'], null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }} 			
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
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			//name: { required: true },
			name: { 
				required: true,
				remote: {
						url: '{{ route('vehicle_body_type_name') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					}
			},                
		},

		messages: {
			//name: { required: "Unit Name is required." },
			name: { required: "Body Type Name is required.", remote: "Body Type Name is already exists!" },                
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
			var type=2;
			$.ajax({
			url: '{{ route('simple_user_add') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				first_name: $('input[name=name]').val(),
				mobile_no: $('input[name=mobile_no]').val(),
				email_address: $('input[name=email_address]').val(),               
				pan: $('input[name=pan]').val(),               
				aadhar_no: $('input[name=aadhar_no]').val(),               
				passport_no: $('input[name=passport_no]').val(),               
				license_no: $('input[name=license_no]').val(),
				address: $('input[name=address]').val(),
				
				city_id: $('select[name=user_city]').val(),
				person_type:type        

				},
			success:function(data, textStatus, jqXHR) {

				alert();
				},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
	$(document).ready(function(){
	$('select[name=user_state]').on('change', function () {

			var obj = $(this);

			var city = $( "select[name=user_city]" );

			var select_val = $(this).val();

			city.empty();

			city.append("<option value=''>Select City</option>");

				if(select_val != "") {

			$('.loader_wall_onspot').show();

				$.ajax({

					 url: '{{ route('get_city') }}',

					 type: 'post',

					 data: {

						_token : '{{ csrf_token() }}',

						state: select_val

						},

					 dataType: "json",

						success:function(data, textStatus, jqXHR) {

							var result = data.result;

							for(var i in result) {	

								city.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");

							}

							$('.loader_wall_onspot').hide();

						},

					 error:function(jqXHR, textStatus, errorThrown) {

						//alert("New Request Failed " +textStatus);

						}

					});

				}

			});



	});

</script>