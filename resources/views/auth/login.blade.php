@extends('layouts.app')
@section('content')
<div class="user-login">
  <div class="row bs-reset">
	<div class="col-md-6 bs-reset">
	  <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>
	</div>
	<div class="col-md-6 login-container bs-reset">
	  <div class="logo_container"> <img src="{{ URL::to('/') }}/assets/layout/images/logo.jpg" /> </div>
	  <div style="margin-top: 95px" class="login-content">
		<h1>PROPEL Login</h1>
		<p> Welcome to Propel ERP. Every Business solutions under one roof. </p>
		@if(Session::has('flash_message'))
		<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
		@endif
		@if($errors->any())
		<div class="alert alert-danger"> @foreach($errors->all() as $error)
		  <p>{{ $error }}</p>
		  @endforeach </div>
		@endif 
		
		<!-- BEGIN LOGIN FORM -->
		<form class="login-form" method="POST" action="{{ url('/login') }}">
		  {{ csrf_field() }}
		  <div class="row">
			<div class="col-md-6 {{ $errors->has('email') ? ' has-error' : '' }}">
			  <input class="form-control form-control-solid placeholder-no-fix form-group" id="login" type="text" placeholder="Email or Mobile" name="mobile"  value="{{ old('email') }}"/>
			  @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
			<div class="col-md-6 {{ $errors->has('password') ? ' has-error' : '' }}">
			  <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" placeholder="Password" name="password"/>
			  @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>
		  </div>
		  <div class="row">
			<div class="col-sm-4">
			  <div class="rem-password">
				<p>
				  <input type="checkbox" class="rem-checkbox" />
				  &nbsp;&nbsp; Remember Me </p>
			  </div>
			</div>
			<div class="col-sm-8 text-right">
			  <div class="forgot-password"> <a href="{{route('reset_password')}}" id="forget-password" class="forget-password">Forgot Password?</a> </div>
			  <button class="btn btn-success" id="login" type="submit">Sign In</button>
			</div>
		  </div>
		  <div class="create-account">
			<p> Don't have an account yet ?&nbsp; <a id="register-btn" href="{{ route('search_register_user') }}"> Create an account </a> </p>
		  </div>
		</form>
		<!-- END LOGIN FORM --> 
	  </div>
	</div>
  </div>
</div>
@stop

  @section('dom_links')
  @parent 
<script>
	$(document).ready(function(e) {
		$('#login').keyup(function() {
			if($.isNumeric($('#login').val())) {
				$('#login').attr('name', 'mobile');
			} else {
				$('#login').attr('name', 'email');
			}
		});

		$('.login-form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				email: {
					required: true
				},
				mobile: {
					required: true
				},
				password: {
					required: true
				},
				remember: {
					required: false
				}
			},

			messages: {
				email: {
					required: "Username is required."
				},
				mobile: {
					required: "Username is required."
				},
				password: {
					required: "Password is required."
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

			errorPlacement: function(error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},

			submitHandler: function(form) {
				form.submit(); // form validation success, call ajax form submit
			}
		});

	})

	</script> 
@stop