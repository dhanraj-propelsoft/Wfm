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
		<h1>OTP Verification</h1>
		<p> Enter your otp details below: </p>
	
		
		@if(Session::has('flash_message'))
		<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
		@endif

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
		

		{!! Form::open(['method' => 'POST', 'class' => 'registerform validateform', 'route' => ['register_business.add_modules']]) !!}

		<div class="form-group">
		  <label class="control-label visible-ie8 visible-ie9">OTP</label>
		  {!! Form::hidden('business_id', $id) !!}
		  <input type="password" name="otp" value="" class="form-control" placeholder="OTP" autocomplete="off" />
		</div>

		  <a class="resend" href="javascript:;">Resend OTP!</a>
		
		<div class="form-actions">
		  <button type="submit" id="register-submit-btn" class="btn btn-success pull-right"> Submit <i class="m-icon-swapright m-icon-white"></i> </button>
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

		$('.resend').on('click', function() {
			$('<form>', {
    "method": "POST",
    "html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="business_id" value="{{$id}}">',
    "action": '{{ route("register_business.send_otp") }}'
}).appendTo(document.body).submit();
		});

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
