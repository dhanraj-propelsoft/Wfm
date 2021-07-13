@extends('layouts.app')

@section('content')

<div class="user-login">

<div class="row bs-reset">

<div class="col-md-6 bs-reset">

  <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>

</div>

<div class="col-md-6 login-container bs-reset">

  <div  class="login-content">

	<h1>Search Business</h1>



	 <a href="{{ route('propel_register_business') }}" class="btn btn-success float-right propel_id" style="color: #fff; margin: 0 auto; width: 290px;">No thanks! I have Propel Business-Id</a><br>

	<br>





	<br>

	@if($errors->any())

	<div class="alert alert-info"> {{$errors->first()}} </div>

	@endif

	

	@if(Session::has('flash_message'))

	<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>

	@endif

	<?php $error_value = null; ?>

	@if(empty($results))

	{!! Form::open(['route' => 'search_business','class' => 'horizontal-form validateform']) !!}



	<div class="form-group"> {!! Form::text('business_name', null , ['class' => 'form-control', 'placeholder' => 'Organization/Company Name']) !!} </div>

	<div class="form-group"> {!! Form::text('mobile_no', null, ['class' => 'form-control', 'placeholder' => 'Mobile Number']) !!} </div>

	<div class="form-group"> {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone Number']) !!} </div>

	<div class="form-group"> {!! Form::text('email_address', null, ['class' => 'form-control', 'placeholder' => 'Email Address']) !!} </div>

	<div class="form-group"> {!! Form::text('web_address', null, ['class' => 'form-control', 'placeholder' => 'Web Address']) !!} </div>

	<div class="form-group"> {!! Form::text('gst', null, ['class' => 'form-control', 'placeholder' => 'GST']) !!} </div>

	<div class="form-group"> {!! Form::text('pan', null, ['class' => 'form-control', 'placeholder' => 'PAN']) !!} </div>

	<div class="form-group"> {!! Form::text('tin', null, ['class' => 'form-control', 'placeholder' => 'TIN']) !!} </div>

	<div class="form-actions">

	  <div class="row">

		<div class="col-md-offset-3 col-md-9">

		  <button type="submit" class="btn btn-success">Search</button>

		  <input class="btn btn-default" type="reset" value="Reset" />

		</div>

	  </div>

	</div>

	

	{!! Form::close() !!} 

	<!-- END REGISTRATION FORM --> 

	@endif

	

	@if( isset($results))

	

	

	@unless($results->count())

	<div class="row">

	  <div class="col-sm-12"> {!! Form::open(['route' => 'register_business', 'class' => 'horizontal-form' ]) !!}

		<input type="hidden" name="business_name" value="{{$request->business_name}}">

		<input type="hidden" name="business_nature" value="{{$request->business_nature}}">

		<input type="hidden" name="business_professionalism" value="{{$request->business_professionalism}}">

		<input type="hidden" name="mobile_no" value="{{$request->mobile_no}}">

		<input type="hidden" name="phone" value="{{$request->phone}}">

		<input type="hidden" name="web_address" value="{{$request->web_address}}">

		<input type="hidden" name="email_address" value="{{$request->email_address}}">

		<input type="hidden" name="gst" value="{{$request->gst}}">

		<input type="hidden" name="pan" value="{{$request->pan}}">

		<input type="hidden" name="tin" value="{{$request->tin}}">

		<br>

		<br>

		<br>

		<br>

		<br>

		<br>

		<div class="text-center">

		  <button type="submit" class="btn btn-primary">No Business found. Do you want to add?</button>

		</div>

		{!! Form::close() !!} </div>

	</div>

	@else

	<div class="portlet box blue">

	  <div class="portlet-body">

		<div class="table-toolbar">

		  <table class="table table-striped table-hover table-bordered" id="sample_editable_1">

			<thead>

			  <tr>

				<th> Company </th>

				<th> Actions </th>

			  </tr>

			</thead>

			<tbody class="borderless">

			

			@foreach($results as $result)
					
			
			<tr>
				<td>Name</td>
				<td>{{ $result->business_name }}</td>
			</tr>
			<tr>
				<td>Propel ID</td>
				<td>{{ $result->bcrm_code }}</td>
			</tr>
			<tr>
				<td>Mobile</td>
				<td>{{ $result->mobile_no }}</td>
			</tr>
			<tr>
				<td>Phone</td>
				<td>{{ $result->phone }}</td>
			</tr>
			<tr>
				<td>GST</td>
				<td>{{ $result->gst }}</td>
			</tr>
			<tr>
				<td>Web</td>
				<td>{{ $result->web_address }}</td>
			</tr>
			<tr>
				<td>Email</td>
				<td>{{ $result->email_address }}</td>
			</tr>
			<tr>
				
				<td colspan="2" align="center">
					@if($result->business_id == "" || $result->business_id == null)
				
				{!! Form::open(['method' => 'POST', 'route' => ['register_business.send_otp']]) !!}
				<input name="name" type="hidden" value="{{ $result->business_name }}" class="form-control">
				<input name="business_id" type="hidden" value="{{ $result->id }}" class="form-control">
				{!! Form::submit('Register Organization', ['class' => 'btn btn-success']) !!}
				<a href="javascript:history.go(-1)" class="btn btn-primary">Edit</a>
			

				{!! Form::close() !!}
				
				@else <a class="btn btn-default"><b>Already Registered.Please use your login or contact 

Propel Office or fogot password.</b></a> @endif 
				</td>
			</tr>

			<!-- <tr>
			
			  <td>
			
			
			
			  <table class="borderless">
			
			  	<tr> <td>Name</td> <td>{{ $result->business_name }}</td> </tr>
			
			  	<tr> <td>Propel ID</td> <td>{{ $result->bcrm_code }}</td> </tr>
			
			  	<tr> <td>Mobile</td> <td>{{ $result->mobile_no }}</td> </tr>
			
			  	<tr> <td>Phone</td> <td>{{ $result->phone }}</td> </tr>
			
			  	<tr> <td>Web</td> <td>{{ $result->web_address }}</td> </tr>
			
			  	<tr> <td>Email</td> <td>{{ $result->email_address }}</td> </tr>
			
			  </table>
			
			
			
			  <td> @if($result->business_id == "" || $result->business_id == null)
			
				
			
				{!! Form::open(['method' => 'POST', 'route' => ['register_business.send_otp']]) !!}
			
				<input name="name" type="hidden" value="{{ $result->business_name }}" class="form-control">
			
				<input name="business_id" type="hidden" value="{{ $result->id }}" class="form-control">
			
				{!! Form::submit('Register Organization', ['class' => 'btn btn-success']) !!}
			
				{!! Form::close() !!}
			
				
			
				@else <a class="btn btn-default">Already Registered</a> @endif </td>
			
			</tr> -->

			@endforeach

			  </tbody>

			

		  </table>

		</div>

		@endunless

		

		

		@endif </div>

	</div>

  </div>

</div>

@stop



  @section('dom_links')

  @parent 

<script>

   

	$('.validateform').validate({

			errorElement: 'span', //default input error message container

			errorClass: 'help-block', // default input error message class

			focusInvalid: false, // do not focus the last invalid input

			rules: {

				business_name: {

					required: true

				},

				

				mobile_no: {

					required: true,

					mobileIND: true

				},

				pan: {

					 pan: true

				},

				gst: {

					required: true,

					gst: true

				},

				tin: {

					 tin: true

				}

			},



			messages: {

				business_name: {

					required: "Business Name is required."

				},

				gst: {

					required: "GST is required."

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