@extends('layouts.app')

@section('content')
@section('module')
@parent
CRM <small>System</small> @stop
@section('breadcrumbs')
@parent
<li> <a href="#">Search Users</a> </li>
@stop
<div class="user-login">
<div class="row bs-reset">
<div class="col-md-6 bs-reset">
  <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>
</div>
<div class="col-md-6 login-container bs-reset">
  <div style="margin-top: 15px" class="login-content">

<br><br>


	@if(Session::has('flash_message'))
	<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
	@endif
@if ($is_registered == true)
<br><br>
	<div style="text-align: center;">Propel-ID has been already registered with {{$mobile}} and {{$email}} usernames.<br><br> Try <a href="{{ url('/login') }}">Login</a>.</div>
<br><br>
@else
  <h1>Register User</h1>
<br>
<div style="text-align: center;">An Otp has been sent to {{$mobile}} @if($email != null) and {{$email}} @endif.<br><br></div>

	<br><br>
	  <!-- BEGIN FORM--> 
	  
	  {!! Form::open([
	  'route' => 'user_register.store_propel',
	  'class' => 'horizontal-form propelform',
	  'id' => 'searchform'
	  ]) !!}
	  
	  
	  {{ csrf_field() }}
	  <input type="hidden" name="person_id" value="{{$person_id}}">
	  <div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
			<input id="otp" placeholder="OTP" type="text" class="form-control" name="otp" value="{{ old('otp') }}" required autofocus>
		</div>
	@if($email == null) 
		<div class="form-group">
		<input name="email" type="text" class="form-control" placeholder="Email Address" value="{{ old('email')}}" />
	  </div>
	  @endif
		<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
			<input id="password" type="password" class="form-control" name="password" placeholder="Password" placeholder="Password" required>
		</div>
		<div class="form-group">
				<input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required>
		</div>
	

	  <div class="form-actions">
		<div class="row">
		  <div class="col-md-offset-3 col-md-9">
			<button type="submit" class="btn btn-success">Submit</button>
		  </div>
		</div>
	  </div>
	  {!! Form::close() !!} 
	  <!-- END FORM--> 
	  
@endif
	
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script>


$('.propelform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				otp: {
					required: true,
				},
				email: {
					required: true,
				},
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
				otp: {
					required: "OTP is required.",
				},
				email: {
					required: true,
					email: true,
					remote: {
						url: '{{ route('check_user_email_address') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					}
				},
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


</script> 
@stop