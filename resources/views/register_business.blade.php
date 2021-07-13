@extends('layouts.app')

@section('content')
@section('module')
@parent
CRM <small>System</small> @stop
@section('breadcrumbs')
@parent
<li> <a href="#">Search Business</a> </li>
@stop
<div class="user-login">
<div class="row bs-reset">
<div class="col-md-6 bs-reset">
  <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>
</div>
<div class="col-md-6 login-container bs-reset">
  <div style="margin-top: 15px" class="login-content">

<br><br>

		@if($errors->any())
		<div class="alert alert-info"> {{$errors->first()}} </div>
		@endif

	@if(Session::has('flash_message'))
	<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
	@endif



@if ($is_registered == true)
<br><br>
	<div style="text-align: center;">Business-ID has been already registered.<br><br> For more informations contact propel support.</div>
<br><br>
@else
  <h1>Register Business</h1>
<br>
<div style="text-align: center;">An Otp has been sent to {{$mobile}} @if($email != null) and {{$email}} @endif.<br><br></div>

	<br><br>
	  <!-- BEGIN FORM--> 
	  
	  {!! Form::open([
	  'route' => 'register_business.add_modules',
	  'class' => 'horizontal-form propelform',
	  'id' => 'searchform'
	  ]) !!}
	  
	  
	  {{ csrf_field() }}
	  <input type="hidden" name="business_id" value="{{$business_id}}">

	  <div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
			<input id="otp" placeholder="OTP" type="text" class="form-control" name="otp" value="{{ old('otp') }}" required autofocus>
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
				}   
			},

			messages: {
				otp: {
					required: "OTP is required.",
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
				$('.loader_wall').show();
				form.submit(); // form validation success, call ajax form submit
			}
		});


</script> 
@stop