@extends('layouts.app')
@section('content')
<div class="user-login">
  <div class="row bs-reset">
	<div class="col-md-6 bs-reset">
	  <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>
	</div>
	<div class="col-md-6 login-container bs-reset">
	  <div class="logo_container"> <img src="{{ URL::to('/') }}/assets/layout/images/logo.png" /> </div>
	  <div style="margin-top: 95px" class="login-content">
		<h1>Sign Up</h1>
		<p> Enter your details below: </p>
		@if(Session::has('flash_message'))
		<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
		@endif
		<?php $error_value = null; ?>
		<div style="background:#fff"> @foreach($request->request as $key => $error)
		  @if(is_int($key))
		  <?php $error_value = $error; ?>
		  <p style="padding:2px 5px; color:red">{{ $error }}</p>
		  @endif
		  @endforeach </div>
		{!! Form::open([
		'method' => 'POST',
		'class' => 'registerform validateform',
		'route' => ['user.activatelogin']
		]) !!}
		{{ csrf_field() }}
		<input name="first_name" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->first_name or '' }}" class="form-control"  placeholder="Name"  />
		<input name="last_name" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->last_name or '' }}" class="form-control"  placeholder="Name"  />
		<input name="mobile_no" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->mobile_no or '' }}" class="form-control"  placeholder="Mobile Number"  />
		<input name="email_address" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->email_address or '' }}" class="form-control"  placeholder="Email"  />
		<input name="dob" <?php if($error_value == null) { echo 'type="hidden"'; } ?> class="form-control date-picker datetype" placeholder="DOB" value="{{$request->dob or ''}}" data-date-format="dd-mm-yyyy" />
		<input name="mother_name" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->mother_name or '' }}" class="form-control"  />
		<input name="father_name" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->father_name or '' }}" class="form-control"   />
		<input name="state" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->state or '' }}" class="form-control"  placeholder="State"  />
		<input name="city" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->city or '' }}" class="form-control"  placeholder="City"  />
		@if(isset($request->person_id))
		<input name="person_id" type="hidden" value="{{ $request->person_id or '' }}"  />
		@endif
		
		
		<input name="otp" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->otp or '' }}" class="form-control"  placeholder="otp"  />

		<input name="user_token" <?php if($error_value == null) { echo 'type="hidden"'; } ?> value="{{ $request->token or '' }}" class="form-control"  placeholder="token"  />


		<div class="form-group">
		  <label class="control-label visible-ie8 visible-ie9">Password</label>
		  {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!} </div>
		<div class="form-group">
		  <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
		  <div class="controls"> {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password']) !!} </div>
		</div>
		<div class="form-actions">
		  <button type="submit" id="register-submit-btn" class="btn btn-success pull-right"> Sign Up <i class="m-icon-swapright m-icon-white"></i> </button>
		</div>
		{!! Form::close() !!} 
		<!-- END REGISTRATION FORM --> 
		
	  </div>
	</div>
  </div>
</div>
@stop

  @section('dom_links')
  @parent 
<script>
	$(document).ready(function(e) {

		$( "select[name=state]" ).on('change', function () {
		var city = $( "select[name=city]" );
		var select_val = $(this).val();
		city.empty();
		city.append("<option value=''>Select City</option>");
		$('.loader_wall_onspot').show();
			$.ajax({
				 url: "{{ route('get_city') }}",
				 type: 'post',
				 data: {
					_token :$('input[name=_token]').val(),
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
  });


		$('.validateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {                
				password: {
					required: true,
					minlength: 6
				},
				 password_confirmation: {
					required: true,
					minlength: 6,
					equalTo: '[name="password"]'
				}                
			},

			messages: {               
				password: {
					required: "Password is required."
				},
				password_confirmation: {
					required: "Confirm Password is required.",
					equalTo: "Password did not match."
				}
			},

			invalidHandler: function(event, validator) { //display error alert on form submit   
				$('.alert-danger', $('.login-form')).show();
			},

			highlight: function(element) { // hightlight error inputs
				$(element)
					.closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function(label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},
			
			submitHandler: function(form) {
				form.submit(); // form validation success, call ajax form submit
			}
		});

	})

	</script> 
@stop