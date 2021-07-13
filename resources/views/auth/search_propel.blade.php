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

<br><br><br><br>
  <h5>Please enter your propel id to continue your registration</h5>
	<br>
	@if($errors->any())
	<div class="alert alert-info"> {{$errors->first()}} </div>
	@endif

	{!! Form::open([
	  'route' => 'register_propel_id',
	  'class' => 'horizontal-form propelform',
	  'id' => 'searchform'
	  ]) !!}
{{ csrf_field() }}
	  <div class="form-group">
		<input name="propel_id" type="text" class="form-control" placeholder="Propel-Id" />
	  </div>

		<button type="submit" class="btn btn-success">Send OTP</button>
		<a href="{{route('login')}}" style="color: #fff;" class="btn btn-success">Cancel</a>
	  {!! Form::close() !!} 
	

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
				propel_id: {
					required: true,
					remote: {
						url: '{{ route('check_propel_id') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					}
				}
			},

			messages: {
				propel_id: {
					required: "Propel-Id is required.",
					remote: "Propel-Id does not exist!"
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