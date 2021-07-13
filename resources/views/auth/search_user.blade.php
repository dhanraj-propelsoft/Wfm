@extends('layouts.app')

@section('content')
@section('module')
@parent
CRM <small>System</small> @stop
@section('breadcrumbs')
@parent
<li> <a href="#">Search Users</a> </li>
<!-- <li> <a href="#">Creating a New Account</a> </li> 
 -->
@stop
<div class="user-login">
<div class="row bs-reset">
<div class="col-md-6 bs-reset">
  <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>
</div>
<div class="col-md-6 login-container bs-reset">
<div class="logo_container"> <img src="{{ URL::to('/') }}/assets/layout/images/logo.png" /> </div>
  <div style="margin-top: 15px" class="login-content">

 <!--  <h1>Search User</h1> -->
  <!--  <a href="{{ route('propel_register_user') }}" class="btn btn-success float-right propel_id" style="color: #fff; margin: 0 auto; width: 250px;">No thanks! I have Propel-Id</a>  --><br>
	
	@if($errors->any())
	<div class="alert alert-info"> {{$errors->first()}} </div>
	@endif
	<!-- start propel id login -->

<div @if(empty($result) && isset($input)) style = "display: none" @endif>
	  <h4 style="font-weight: bold;">Creating a New Account</h4><br>
		{!! Form::open([
		  'route' => 'register_propel_id',
		  'class' => 'horizontal-form propelform',
		  'id' => 'searchform'
		 
		  ]) !!}
			{{ csrf_field() }}
			<div class="row">
				  	<div class="col-md-12" >
						<div class="form-group">
							<input name="propel_id" type="text" class="form-control" placeholder="Enter Propel-ID if known(optional)" />
						</div>
				  	</div>
					<!-- <div class="col-md-3">
						<button type="submit" class="btn btn-success">Send OTP</button> 
					</div> -->
			</div>		
		{!! Form::close() !!} 
		<hr style="size:10px;">
</div>
<!-- end propel id login -->
	
	@if(Session::has('flash_message'))
	<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
	@endif
	<div @if(empty($result) && isset($input)) style = "display: none" @endif class="create_business"> 
	
		 {!! Form::open([
			  'route' => 'register_propel_id',
			  'class' => 'horizontal-form propelform',
			  'id' => 'searchform',
			  'style' => 'display: none;'
			  ]) !!}
				{{ csrf_field() }}
		<div class="form-group">
			<input name="propel_id" type="text" class="form-control" placeholder="Propel-Id" />
		</div>
		<hr style="height: 10px;">
		<button type="submit" class="btn btn-success">Submit</button>
			  {!! Form::close() !!}
	  
	  <!-- BEGIN FORM--> 
	  
	  {!! Form::open([
	  'route' => 'search_register',
	  'class' => 'horizontal-form searchform',
	  'id' => 'searchform'
	  ]) !!}
	  
	  
	  {{ csrf_field() }}
	 	<div class="form-group">
			<input name="first_name" type="text" class="form-control lettersonly" placeholder="First Name" value="{{$input['first_name'] or ''}}" />
	 	</div>
	  	<div class="form-group">
			<input name="last_name" type="text" class="form-control lettersonly" placeholder="Last Name" value="{{$input['last_name'] or ''}}" />
	  	</div>
	 <!--  <div class="form-group">
	 		<input name="dob" type="text" class="form-control date-picker datetype" placeholder="DOB" autocomplete="off" value="{{$input['dob'] or ''}}" data-date-format="dd-mm-yyyy" />
	 </div> -->
	  	<div class="form-group">
			<?php 
				$selectedstate = "";
				if(isset($input)) $selectedstate = $input['state']; ?>
			<select class="form-control select_item" name="state" placeholder="state">
			  <option value="">Select State</option>
				@foreach($state as $c)										      	
				<option @if($c['id'] == $selectedstate ) selected @endif value="{{$c['id']}}">{{$c['name']}}</option>
				@endforeach											   
			</select>
	  	</div>
	  	<div class="form-group">
			<select class="form-control select_item" name="city" placeholder="city">
			  <option value="">Select City</option>
			  @if(isset($input)) 
				<option selected="" value="{{$input['city']}}"></option>
			  @endif 	  											   
			</select>
	  	</div>
		<div class="form-group">
			<input name="mobile_no" type="text" class="form-control numbers mobile" placeholder="Mobile Number" value="{{$input['mobile_no'] or ''}}" />
		</div>
		<div class="form-group">
			<input name="email_address" type="text" class="form-control" placeholder="Email Address" value="{{$input['email_address'] or ''}}" />
		</div>
		{!! Form::close() !!} 

		<div class="form-actions">
			<div class="row">
			  <div class="col-md-offset-3 col-md-9">

				<!-- <button type="submit" class="btn btn-success send_otp" >Send OTP</button> --> 
				<button type="submit" class="btn btn-success sign_up">Sign Up</button>
			  	<!-- <button type="submit" class="btn btn-success">Search</button> -->
				<input class="btn btn-default reset" type="reset" value="Reset" />
				<a href="{{route('login')}}" style="color: #fff;" class="btn btn-primary">Cancel</a>

			  </div>
			</div>
		</div>
		<div class="row">
			<div class=" col-md-12">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<p style="font-size: 12px;">By clicking Sign Up,&nbsp;you agree to our <span style="color:#0fd7ef;">Terms</span>,&nbsp;<span style="color:#0fd7ef;">Data Policy</span> and <span style="color:#0fd7ef;">Cookie Policy</span>.<br>You may receive SMS notifications from us.</p>
			</div>
		</div>
	
	  <!-- END FORM--> 
	</div>

			
	@if(isset($status))
		@if($status == 1)
			<div style="text-align: center;font-size: 20px; color: red;font-weight:bold;">Entered User information is already registered in Propel System.<br><br> Try <a href="{{ url('/login') }}">Login</a>
			</div>
		@endif

		@if($status == 2)
		<div style="margin-top: 25px" class="portlet box blue">
			 <div class="portlet-body">
				<div class="table-toolbar">
					<div style="text-align: center;font-size: 20px; color: red;font-weight:bold;">Confirm below details or Edit to modify<br><br></div>
					  
	
					<table class="table table-hover table-bordered" id="sample_editable_1">
							
						
								<tr>
								  <td style="font-weight:bold;"> User ID </td><td> {{ $results->crm_code }} </td>
								</tr> 
								<tr>
								   <td style="font-weight:bold;"> Name </td><td><span @if($results->first_name == $input['first_name']) style="" @endif >
									{{ $results->first_name }} </span> <br></td>
								</tr>
								<tr>
									<td style="font-weight:bold;">Mobile Number</td>
									<td><span @if($results->mobile_no == $input['mobile_no']) style="" @endif >
										{{ $results->mobile_no }} </span> <br></td>
								</tr>
								<tr>
									<td style="font-weight:bold;">E-mail</td>
									<td><span @if($results->email_address == $input['email_address']) style="" @endif >
									{{ $results->email_address }} </span></td>
								</tr>
								<tr>
								<td style="font-weight:bold;"></td>
								  	<td colspan="2" align="center"> @if (isset($results))
									{!! Form::open([
									'method' => 'POST',
									 'route' => ['user_register.store']
									]) !!}
									<input name="first_name" type="hidden" value="{{ $results->first_name }}" class="form-control">
									<input name="email_address" type="hidden" value="{{ $results->email_address }}" class="form-control">
									<input name="dob" type="hidden" value="{{ $results->dob }}" class="form-control">
									<input name="mobile_no" type="hidden" value="{{ $results->mobile_no }}" class="form-control">
									<input name="city" type="hidden" value="{{ $results->city }}" class="form-control">
									<input name="person_id" type="hidden" value="{{ $results->person_id }}" class="form-control">
									<input name="token" type="hidden" value="{{ $results->token }}" class="form-control">
									{!! Form::submit('Confirm', ['class' => 'btn btn-success']) !!}
									<!-- <a href="{{ route('search_register_user') }}" class="btn btn-primary">Edit</a> -->
									<a href="javascript:history.go(-1)" class="btn btn-primary">Edit</a>
	
									{!! Form::close() !!}
									
									@endif 
									</td>
								</tr>
							
					</table>
				</div>
			</div>
		</div>
		@endif

		@if($status == 3)
			@if($check->status != null)
				<div style="text-align: center;font-size: 20px; color: red;font-weight:bold;">Entered Mobile number or Email is already registered in Propel System or use Propel ID to <a href="{{ route('search_register') }}">Register</a>.<br><br> 
				</div>
				<div>
					<table class="table table-hover table-bordered" id="sample_editable_1">
						@if($check->status =='1')
							<tr>
								<td style="font-weight:bold;"> Name </td><td><span>
								{{ $name }} </span> <br></td>
							</tr>
							<tr>
								<td style="font-weight:bold;">Mobile Number</td>
								<td><span>
								{{ $mobile}} </span> <br></td>
							</tr>
							<tr>
								<td style="font-weight:bold;">E-mail</td>
								<td><span>
								{{ $email }} </span></td>
							</tr>
							<tr>
								<td style="font-weight:bold;"></td>
								<td colspan="2" align="center"><a href="javascript:history.go(-1)" class="btn btn-primary">Edit</a></td>
							</tr>
	
						@endif
						@if($check->status =='0')

							<tr>
							 	<td style="font-weight:bold;"> User ID </td><td> {{ $check->crm_code }} </td>
							</tr>
							<tr>
								<td style="font-weight:bold;"> Name </td><td><span @if($check->first_name == $input['first_name']) style="" @endif >
								{{ $name }} </span> <br></td>
							</tr>
							<tr>
								<td style="font-weight:bold;">Mobile Number</td>
								<td><span @if($check->mobile_no == $input['mobile_no']) style="" @endif >
									{{ $mobile }} </span> <br></td>
							</tr>
							<tr>
								<td style="font-weight:bold;">E-mail</td>
								<td><span @if($check->email_address == $input['email_address']) style="" @endif >{{ $email }} </span></td>
							</tr>
							<tr>
										
								<td colspan="2" align="center"> @if (isset($check))
								{!! Form::open(['method' => 'POST','route' => ['user_register.store']
											]) !!}
									<input name="first_name" type="hidden" value="{{ $check->first_name }}" class="form-control">
									<input name="email_address" type="hidden" value="{{ $check->email_address }}" class="form-control">
									<input name="dob" type="hidden" value="{{ $check->dob }}" class="form-control">
									<input name="mobile_no" type="hidden" value="{{ $check->mobile_no }}" class="form-control">
									<input name="city" type="hidden" value="{{ $check->city }}" class="form-control">
									<input name="person_id" type="hidden" value="{{ $check->person_id }}" class="form-control">
									<input name="token" type="hidden" value="{{ $check->token }}" class="form-control">
									{!! Form::submit('Confirm', ['class' => 'btn btn-success']) !!}
									<!-- <a href="{{ route('search_register_user') }}" class="btn btn-primary">Edit</a> -->
									<a href="javascript:history.go(-1)" class="btn btn-primary">Edit</a>
		
									{!! Form::close() !!}
									@endif 
								</td>
							</tr>
									
						@endif
					</table>
				</div>
			@endif
		@endif

		@if($status == 4)
			<div style="text-align: center;font-size: 20px; color: red;font-weight:bold;">You have a existing Propel ID.Please use your ID to register.<br> Try <a href="{{ route('search_register') }}">Register</a>
			</div>
		@endif

		@if($status == 0)
	
		<div>
			<div style="text-align: center;font-size: 20px; color: red;font-weight:bold;">Please confirm the entered details to proceed.<br><br> 
			</div>
			<table class="table table-hover table-bordered" id="sample_editable_1">
				<tr>
					<td style="font-weight:bold;"> Name </td>
					<td>{{ $input['first_name'] }} </td>
				</tr>
				<tr>
					<td style="font-weight:bold;">Mobile Number</td>
					<td>{{$input['mobile_no']}}</td>
				</tr>
				<tr>
					<td style="font-weight:bold;">E-mail</td>
					<td>{{$input['email_address'] }}</td>
				</tr>
				<tr>
					<td colspan="2">
					<div class="text-center"> <a id="trigger_user" style="color: #fff" class="btn btn-primary">Confirm</a> 
					</div>
					</td>
				</tr>
			</table>
			
		</div>
		@endif	

		@endif




		</div>
	</div>
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script>

$(document).ready(function() {

	$('.personal_account, .business_account').on('click', function() {
			$('.choose_account').css('display', 'none');
			$('.create_business').css('display', 'block');
	});


	var redirect = "{{ route('user_register.store') }}";

	$( "select[name=state]" ).on('change', function () {
	var city = $( "select[name=city]" );
	var select_val = $(this).val();
	city.empty();
	city.append("<option value=''>Select City</option>");
	if(select_val != "") {
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
	}
	
  });



	$("#trigger_user").on('click', function() {
		$("#trigger_user").text("Please Wait. While we process your data!");
		$("#errorlist").hide();
		//alert($('#searchform input[name="first_name"]').val());
		if($('#search_form input[name="first_name"]').val() == "") {
						$("#errorlist").text("Name field should not be empty");
						$("#errorlist").fadeIn(500).show();
		} else if($('#search_form input[name="dob"]').val() == "") {
						$("#errorlist").text("DOB field should not be empty");
						$("#errorlist").fadeIn(500).show();
		}else if($('#search_form select[name="state"]').val() == "") {
						$("#errorlist").text("State field should not be empty");
						$("#errorlist").fadeIn(500).show();
		}else if($('#search_form select[name="city"]').val() == "") {
						$("#errorlist").text("City field should not be empty");
						$("#errorlist").fadeIn(500).show();
		}else if($('#search_form input[name="mobile_no"]').val() == "") {
						$("#errorlist").text("Mobile field should not be empty");
						$("#errorlist").fadeIn(500).show();
		} else {
			$.redirectPost(redirect, {_token: $('#searchform input[name="_token"]').val(),
				first_name: $('#searchform input[name="first_name"]').val(),
				last_name: $('#searchform input[name="last_name"]').val(),
				dob: $('#searchform input[name="dob"]').val(),
				state: $('#searchform select[name="state"]').val(),
				city: $('#searchform select[name="city"]').val(),
				mobile_no: $('#searchform input[name="mobile_no"]').val(),
				email_address: $('#searchform input[name="email_address"]').val(),
				mother_name: $('#searchform input[name="mother_name"]').val(),
				father_name: $('#searchform input[name="father_name"]').val()
			});
				
				}

	});


	/*$(".sign_up").on('click', function(e) {
		e.preventDefault();
		/*$("#trigger_user").text("Please Wait. While we process your data!");
		$("#errorlist").hide();
		//alert($('#searchform input[name="first_name"]').val());
		if($('#search_form input[name="first_name"]').val() == "") {
						$("#errorlist").text("Name field should not be empty");
						$("#errorlist").fadeIn(500).show();
		} else if($('#search_form input[name="dob"]').val() == "") {
						$("#errorlist").text("DOB field should not be empty");
						$("#errorlist").fadeIn(500).show();
		}else if($('#search_form select[name="state"]').val() == "") {
						$("#errorlist").text("State field should not be empty");
						$("#errorlist").fadeIn(500).show();
		}else if($('#search_form select[name="city"]').val() == "") {
						$("#errorlist").text("City field should not be empty");
						$("#errorlist").fadeIn(500).show();
		}else if($('#search_form input[name="mobile_no"]').val() == "") {
						$("#errorlist").text("Mobile field should not be empty");
						$("#errorlist").fadeIn(500).show();
		} else {
			$.redirectPost(redirect, {_token: $('#searchform input[name="_token"]').val(),
				first_name: $('#searchform input[name="first_name"]').val(),
				last_name: $('#searchform input[name="last_name"]').val(),
				dob: $('#searchform input[name="dob"]').val(),
				state: $('#searchform select[name="state"]').val(),
				city: $('#searchform select[name="city"]').val(),
				mobile_no: $('#searchform input[name="mobile_no"]').val(),
				email_address: $('#searchform input[name="email_address"]').val(),
				mother_name: $('#searchform input[name="mother_name"]').val(),
				father_name: $('#searchform input[name="father_name"]').val()
			});
				
				}

	});*/

	$.extend({

	redirectPost: function(location, args)
	{
		console.log(args);
		var form = $('<form></form>');
		form.attr("method", "post");
		form.attr("action", location);

		$.each( args, function( key, value ) {
			var field = $('<input></input>');

			field.attr("type", "hidden");
			field.attr("name", key);
			field.attr("value", value);

			form.append(field);
		});
		$(form).appendTo('body').submit();
	}
});


});


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


$('.searchform, .registerform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				first_name: {
					required: true
				},
				
				mobile_no: {
					required: true,
					/*remote: {
						url: '{{ route('check_user_mobile_number') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					}*/
				},
				email_address: {
					required: true,
					email: true,
					/*remote: {
						url: '{{ route('check_user_email_address') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					}*/
				},
				mother_name: {
					required: true
				},
				father_name: {
					required: true
				},
				state:
				{
					required : true
				},
				city :
				{
					required : true
				}

			},

			messages: {
				first_name: {
					required: "Name is required."
				},
				
				mobile_no: {
					required: "Mobile Number is required.",
					remote: "Mobile Number already exists!"
				},
				email_address: {
					required: "Email is required.",
					remote: "Email already exists!"
				},
				mother_name: {
					required: "Mother Name is required."
				},
				father_name: {
					required: "Father Name is required."
				},
				state :
				{
					required: "State is required."
				},
				city:
				{
					required: "City is required."
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

$('.sign_up').on('click',function(){
	//alert();

	var propel = $('input[name=propel_id]').val();
	console.log(propel);
	if(propel)
	{
		//alert();
		$('.propelform').validate();
		$('.propelform').submit();

	}
	else
	{
		$('.searchform').validate();
		$('.searchform').submit();

	}
});
</script> 
@stop