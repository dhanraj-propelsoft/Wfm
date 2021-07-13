@extends('layouts.app')
@section('content')
<div class="user-login">
  <div class="row bs-reset">
	<div class="col-md-6 bs-reset">
	  <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>
	</div>
	<div class="col-md-6 login-container bs-reset">
	  <div class="logo_container"> <img src="{{ URL::to('/') }}/assets/layout/images/logo.png" /> </div>
	  <div style="margin-top: 95px" class="login-content"> @if(empty($time_exceeded))
		<h1>OTP Verification</h1>
		<p> Enter your otp details below: </p>
		@endif
		
		@if(Session::has('flash_message'))
		<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
		@endif
		
		@if(isset($time_exceeded))
		<div style="text-align:center">{!! $time_exceeded['time_exceeded'] !!}</div>
		@else
		@if($errors->any())
		<div class="alert alert-danger"> @foreach($errors->all() as $error)
		  <p>{{ $error }}</p>
		  @endforeach </div>
		@endif
		
		@if(isset($otperror))
		<div class="alert alert-danger">
		  <p>{{ $otperror }}</p>
		</div>
		@endif
		
		
		@if(isset($reset))
		{!! Form::open(['method' => 'POST', 'class' => 'registerform validateform', 'route' => ['password.login']]) !!}
		@else
		<!-- {!! Form::open(['method' => 'POST', 'class' => 'registerform validateform', 'route' => ['user.activatelogin']]) !!} -->
		{!! Form::open(['method' => 'POST', 'class' => 'registerform validateform', 'route' => ['user_create']]) !!} 
		@endif
		<div class="form-group">
		  <label class="control-label visible-ie8 visible-ie9">OTP</label>
		  <input type="password" name="otp" value="" class="form-control" placeholder="OTP" autocomplete="off" />
		  @if(isset($reset))
		 <!--  <input name="user_id" value="{{$user_id}}" type="hidden" /> -->
		  <input name="user_id" value="{{$user_id}}" type="hidden" />
		  @else
		  <!-- <input name="user_token"  value="{{$token}}" type="hidden" /> -->
		  <input name="token"   value="{{$token}}" type="hidden" />
		  @endif </div>
		<!-- @if(isset($resend))
		@if(count($resend) > 0) <span>{{ $resend['resend'] }}</span> @else
		@if(!isset($reset)) <a href="{{ route('otp.resend', [$token]) }}">Resend OTP!</a> @endif
		@endif
		@endif -->
		<div class="form-actions pull-right">
		  
		  <button type="submit" id="register-submit-btn" class="btn btn-success "> Verify <i class="m-icon-swapright m-icon-white"></i> </button>
		  <a href="{{ route('otp.resend', [$token]) }}" class="btn btn-primary ">Resend OTP!</a>
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

		$('.validateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {                
				otp: {
					required: true
				}                
			},

			messages: {               
				otp: {
					required: "OTP is required."
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
				$('.loader_wall').show();
				form.submit(); // form validation success, call ajax form submit
			}
		});

	})

	</script> 
@stop
@endif