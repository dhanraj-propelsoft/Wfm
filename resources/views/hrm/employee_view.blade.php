@extends('layouts.master')

@section('head_links') @parent

  <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

  <link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}" />

  <link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.print.min.css') }}" media='print' />

  <style>



	#container ul.nav.nav-tabs {

		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f9fafa+0,eef5f8+100 */

		background: #f9fafa; /* Old browsers */

		background: -moz-linear-gradient(top,  #f9fafa 0%, #eef5f8 100%); /* FF3.6-15 */

		background: -webkit-linear-gradient(top,  #f9fafa 0%,#eef5f8 100%); /* Chrome10-25,Safari5.1-6 */

		background: linear-gradient(to bottom,  #f9fafa 0%,#eef5f8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */

		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f9fafa', endColorstr='#eef5f8',GradientType=0 ); /* IE6-9 */

		box-shadow:0px 1px 2px #ccc;

		padding: 15px 10px;

		border-radius: 10px;

	}



	.nav-tabs .nav-link {

		padding: 0.5rem .8rem;

	}

	.nav-tabs .nav-link:hover {

		border: 1px solid transparent;

		border-radius: none;

	}



	.nav-tabs .nav-link.active {

		background: #D2DEE2;

	   box-shadow: inset 0 3px 1px #B9C2D1;

	   border-radius: 15px;

	   border: 1px solid transparent;

	}



	label {

		font-weight: bold;

	}



	/* input[type=text], .select2, textarea.form-control, .radio{

		display: none;

	} */



	.text {

		float: left;

		width: 100%;

	}



	.text-styled {

		color: #5682b7;

		font-size: 15px;

		font-family: 'ProximaNovaLight', 'ProximaNovaRegular', 'Source Sans Pro', Arial, sans-serif;

	}

  </style>

@stop

@include('includes.hrm')

@section('content')

<div class="alert alert-success">

</div>

<div class="content">

<div class="row">





<div class="col-md-3">

<ul class="list-unstyled profile-nav">

	<li style="position:relative"> 

		<img width="100%" src="{{ URL::to('/') }}/public/users/images/no_image.jpg" style="background: #eee; border-radius: 5px; padding: 10px;" class="img-responsive" alt="Employee Image"/>



		<a id="change_photo" class="change_image">Change Photo</a>

			{!! Form::open(['method' => 'POST','class' => 'profile-edit','files' => true]) !!}



			<div id="photo_container" class="row" style="display:none"> 

				<label class="upload">

				<input type="file" name="employee_image" />

				<input type="hidden" name="employee_id" value="" />

				<span>Upload</span>

				</label>

			 </div>

		{!! Form::close() !!} 



			</li>

</ul>

		@foreach($designations as $emp_designation)

			<h4 style="text-align: center;">{{ $emp_designation->designation_name }}</h4>

			<h5 style="text-align: center;">{{ $emp_designation->department_name }}</h5>

		@endforeach

</div>

<div class="col-md-9">

<h3 class="float-left">{{ $official->employee_name }}</h3><div class="clearfix"></div>

<h5 class="float-left">{{ $official->employee_code }}</h5>



<div class="clearfix"></div>



<ul class="nav nav-tabs">

  <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#official">Employee</a> </li>

  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#personal">Official Details</a> </li>

  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#contact">Communication Address</a> </li>

  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#education">Education Info</a> </li>

  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#skills">Skills</a> </li>

  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#experience">Experience</a> </li>

  <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#leaves">Leaves</a> </li> -->

  

  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#salary">Salary Info</a> </li>

  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#banking">Bank</a> </li>

 <!--  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#calendar">My Calendar</a> </li> -->

</ul>



{!! Form::open(['class' => 'form-horizontal validateform']) !!}



	<div class="tab-content" style= border-top: 0px; padding: 10px;">



		<div class="tab-pane active" id="official">

			<div class="clearfix"></div>

				<div class="official_edit_tab btn btn-info">Edit</div>

				<div style="display: none;" class="official_update_tab btn btn-success">Update</div>

				<br><br>

			<div>

				<div class="form-body">



					<div class="form-group">

						<div class="row">					

							<div class="col-md-2">

								{{ Form::label('employee_first_name', 'First Name', array('class' => 'control-label required')) }}

							</div>			

							<div class="col-md-4 ">

								{{ Form::text('employee_first_name', $official->first_name, ['class'=>'form-control first_name']) }}

											

								<span class="text text-styled">{{ $official->first_name or '' }}</span>		

							</div>

						</div>

					</div>



					<div class="form-group">

						<div class="row">

							<div class="col-md-2">

								{{ Form::label('employee_last_name', 'Last Name', array('class' => 'control-label ')) }}

							</div>			

							<div class="col-md-4 ">

								{{ Form::text('employee_last_name', ($official != null) ? $official->last_name : null, ['class'=>'form-control last_name']) }}

											

								<span class="text text-styled">{{ $official->last_name or '' }}</span>		

							</div>

						</div>

					</div>



					<div class="form-group">

						<div class="row">

							<div class="col-md-2">

								{{ Form::label('employee_code', 'Employee Code', array('class' => 'control-label required')) }}

							</div>			

							<div class="col-md-4 ">

								{{ Form::text('employee_code', $official->employee_code, ['class'=>'form-control']) }}		

								<span class="text text-styled ">{{ $official->employee_code }}</span>		

							</div>

						</div>

					</div>



					<div class="form-group">

						<div class="row">

						  <div class="col-md-2">

							{{ Form::label('email', 'Email', array('class' => 'control-label required')) }}

							 </div>

						  <div class="col-md-4">

						  	{{ Form::text('email', ($official != null) ? $official->email : null, ['class'=>'form-control email']) }}

						  	<span class="text text-styled ">{{ $official->email or ''}}</span>

						  </div>

						</div>

					</div>



					<div class="form-group">

						<div class="row">

						  	<div class="col-md-2">

							{{ Form::label('phone_no', 'Mobile', array('class' => 'control-label required')) }}

							</div>

						  	<div class="col-md-4">				

							{{ Form::text('phone_no', ($official != null) ? $official->phone_no : null, ['class'=>'form-control mobile']) }} 

							<span class="text text-styled ">{{ $official->phone_no or '' }}</span>

							</div>

						</div>

					</div>



					<div class="form-group">

						<div class="row">

								<div class="col-md-2">

								<label for="gender_id">Gender</label>

							</div>

							<div class="col-md-4">

								{{ Form::select('gender_id',$genders, $official->gender_id, ['class' => 'form-control select_item', 'id' => 'gender_id']) }}

								<span class="text text-styled">{{ $official->gender_name }}</span> 

							</div>

							

						</div>

					</div>



					<div class="form-group">

						<div class="row">

							<div class="col-md-2">

								<label for="blood_group_id">Blood Group</label>

							</div>

							<div class="col-md-4">

								{{ Form::select('blood_group_id',$blood_groups, $official->blood_group_id, ['class' => 'form-control select_item', 'id' => 'blood_group_id']) }}

								<span class="text text-styled">{{ $official->blood_group }}</span> 

							</div>

						</div>

					</div>



					<div class="form-group">

						<div class="row">

							<div class="col-md-2">

								<label for="marital_status">Marital Status</label>

							</div>

							<div class="col-md-4">

								{{ Form::select('marital_status',$marital_status, ($official != null) ? $official->marital_status : null, ['class' => 'form-control select_item', 'id' => 'marital_status']) }}

								<span class="text text-styled">{{ $official->marital_status_name }}</span> 

							</div>

						</div>

					</div>

					

				</div>

		 	</div>

		</div>



		<div class="tab-pane" id="personal">

			<div class="clearfix"></div>

				<div class="personal_edit_tab btn btn-info">Edit</div>

				<div style="display: none;" class="personal_update_tab btn btn-success">Update</div>

				<br><br>

		  	<div>



		  		<div class="row">



		  			<div class="col-md-6">



		  				<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									 {{ Form::label('staff_type_id', 'Employee Type', array('class' => 'control-label required')) }}

								</div>			

								<div class="col-md-6">

									{!! Form::select('staff_type_id',$staff_type, $official->staff_type_id, ['class' => 'select_item form-control' ]) !!}

								<span class="text text-styled">{{ $official->staff_type }}</span>

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									 {!! Form::label('joined_date','Joining Date', array('class' => 'control-label required')) !!}

								</div>			

								<div class="col-md-6">

									{!! Form::text('joined_date',($work_periods != null) ? $work_periods->joined_date : null,['class' => 'form-control date-picker rearrangedate','data-date-format' => 'dd-mm-yyyy']) !!}

									<span class="text text-styled rearrangedatetext">{{ $work_periods->joined_date }}</span>

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									 {!! Form::label('employment_type_id','Job Type', array('class' => 'control-label required')) !!}

								</div>			

								<div class="col-md-6">

									{!! Form::select('employment_type_id',$job_type, ($work_periods != null) ? $work_periods->job_type_id : null, ['class' => 'select_item form-control' ]) !!} 

									<span class="text text-styled">{{ $work_periods->job_type }}</span>

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									 {!! Form::label('confirmation_period','Confirmation Period', array('class' => 'control-label required')) !!}

								</div>			

								<div class="col-md-6">

									{{ Form::text('confirmation_period',($work_periods != null) ? $work_periods->confirmation_period : null, ['class'=>'form-control']) }} 

									<span class="text text-styled">{{ $work_periods->confirmation_period }}</span>

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									 {!! Form::label('branch_id', 'Branch', array('class' => 'control-label required')) !!}

								</div>			

								<div class="col-md-6">

									{!! Form::select('branch_id',$branch,($work_periods != null) ? $work_periods->branch_id : null, ['class' => 'select_item form-control' ]) !!}

									<span class="text text-styled">{{ $work_periods->branch_name }}</span>

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									{!! Form::label('department_id', 'Department', array('class' => 'control-label required')) !!}

								</div>			

								<div class="col-md-6">									

									{!! Form::select('department_id',$department, ($job != null) ? $job->designation_id : null, ['class' => 'select_item form-control' ]) !!} 

									<span class="text text-styled">{{ $job->department_name or '' }}</span>

									

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									{!! Form::label('designation_id', 'Designation', array('class' => 'control-label required')) !!}

								</div>			

								<div class="col-md-6">

									{!! Form::select('designation_id',$designation,($job != null) ? $job->designation_id : null, ['class' => 'select_item form-control' ]) !!}

									<span class="text text-styled">{{ $job->designation_name or '' }}</span>

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									{!! Form::label('shift_id', 'Shift', array('class' => 'control-label required')) !!}

								</div>			

								<div class="col-md-6">

									{!! Form::select('shift_id',$shift,($official != null) ? $official->shift_id : null, ['class' => 'select_item form-control' ]) !!}

									<span class="text text-styled">{{ $job->designation_name or '' }}</span>

								</div>			

							</div>

						</div>



		  			</div>



		  			<div class="col-md-6">



		  				<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									{!! Form::label('license_type_id','License Type', array('class' => 'control-label ')) !!}

								</div>			

								<div class="col-md-6">

									{!! Form::select('license_type_id',$license_type, $official->license_type_id, ['class' => 'select_item form-control' ]) !!}

									<span class="text text-styled">{{ $official->license_type }}</span>

								</div>

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									{!! Form::label('license_no','License Number', array('class' => 'control-label')) !!}

								</div>			

								<div class="col-md-6">

									{{ Form::text('license_no', $official->license_no, ['class'=>'form-control']) }} 

									<span class="text text-styled">{{ $official->license_no or ''}}</span>

								</div>			

							</div>

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									{!! Form::label('employee_pan_no','PAN Number', array('class' => 'control-label')) !!}

								</div>			

								<div class="col-md-6">

									{{ Form::text('employee_pan_no', $official->pan_no, ['class'=>'form-control']) }} 

									<span class="text text-styled">{{ $official->pan_no or '' }}</span>

								</div>

							</div>						

						</div>



						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-4">

									{!! Form::label('employee_pan_no','PAN Number', array('class' => 'control-label')) !!}

								</div>			

								<div class="col-md-6">

									{{ Form::text('employee_pan_no', $official->pan_no, ['class'=>'form-control']) }} 

									<span class="text text-styled">{{ $official->pan_no or '' }}</span>

								</div>

							</div>						

						</div>



					</div>



		  		</div>



		  	</div>



		</div>



		<div class="tab-pane" id="contact">

			<div class="clearfix"></div>

				<div class="contact_edit_tab btn btn-info">Edit</div>

					<div style="display: none;" class="contact_update_tab btn btn-success">Update</div>

					<br><br>  

						

					<div class="row">

						@if(count($employee_address) > 0)



							@foreach($employee_address as $address)

								<?php $permanent = ""; $permanent_label = ""; if($address->address_type == '1') { $permanent = "permanent_"; $permanent_label = "Permanent Address";  } else { $permanent_label = "Present Address"; } ?>



								{{ Form::hidden($permanent.'address_id', $address->id) }}





							<div class="col-md-6 present">



								{!! Form::label('address', $permanent_label, ['class' => 'control-label required']) !!}



								<div class="form-group col-md-12">	

									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('contact_person', 'Name', array('class' => 'control-label ')) }}

										</div>



										<div class="col-md-5">							

											{{ Form::text($permanent.'contact_person', $address->person, ['class'=>'form-control','placeholder' => 'Contact Person']) }} 

											<span class="text text-styled">{{ $address->person }}</span>

										</div>

									</div>

								</div>



								<div class="form-group col-md-12">	

									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('address', 'Address', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-5">

											{!! Form::textarea($permanent.'address', $address->address, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} 

										  <span class="text">{{ $address->address }}</span>

										</div>

									</div>

								</div>



								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('state_id', 'State', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-5">

										  

										  	<?php 

				  							$states = null;

				  							if(isset($emp_address->state_id)) { $states = $emp_address->state_id; } ?>

											{!! Form::select($permanent.'state_id',$state, $states, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

										  <span class="text">{{ $emp_address->state_name }}</span>

										</div>

									</div>

								</div>



								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('city_id', 'City', array('class' => 'control-label ')) }}

										</div>



										<div class="col-md-6">						  

										  	<?php 

				  							$cities = null;

				  							if(isset($emp_address->city_id)) { $cities = $emp_address->city_id; } ?>

											{!! Form::select($permanent.'city_id', $city, $cities, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!} 

										  <span class="text">{{ $emp_address->city_name }}</span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('landmark', 'Land Mark', array('class' => 'control-label ')) }}

										</div>	

										<div class="col-md-6">

										  

										{!! Form::text($permanent.'landmark',$address->landmark, ['class' => 'form-control']) !!} 

										  <span class="text">{{ $address->landmark }}</span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('pin', 'Pincode', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">							 

											{!! Form::text($permanent.'pin',$address->pin, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} 

										  <span class="text">{{ $address->pin }}</span>

										</div>

									</div>

								</div>



							</div>



							@endforeach





							@if(count($employee_address) == 1)

								<?php $present = ""; $present_label = ""; if($address->address_type == '0') { $present = "permanent_"; $present_label = "Permanent Address"; } else { $present_label = "Present Address"; } ?>



							<div class="col-md-6 present">



								{!! Form::label('address', $present_label, ['class' => 'control-label required']) !!}



								<div class="form-group col-md-12">	

									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('contact_person', 'Name', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">							

											{{ Form::text('contact_person',null, ['class'=>'form-control','placeholder' => 'Contact Person']) }}

											<span class="text text-styled"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('address', 'Address', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  

											{!! Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('state_id', 'State', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">									  

										  	<?php 

				  							$states = null;

				  							if(isset($emp_address->state_id)) { $states = $emp_address->state_id; } ?>

											{!! Form::select('state_id',$state, $states, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('city_id', 'City', array('class' => 'control-label ')) }}

										</div>



										<div class="col-md-6">							  

										  	<?php 

				  							$cities = null;

				  							if(isset($emp_address->city_id)) { $cities = $emp_address->city_id; } ?>

											{!! Form::select('city_id',$city, $cities, ['class' => 'select_item form-control','id'=> 'city' ]) !!}

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('landmark', 'Land Mark', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  <div class="form-group">

											{!! Form::text('landmark',null, ['class' => 'form-control']) !!} </div>

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('pin', 'Pincode', array('class' => 'control-label ')) }}

										</div>	



										<div class="col-md-6">

										  <div class="form-group">

											{!! Form::text('pin',null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} </div>

										  <span class="text"></span>

										</div>

									</div>

								</div>



							</div>

						 @endif



						@else

							<div class="col-md-6 present">



								{!! Form::label('present_address', 'Present Address', ['class' => 'control-label required']) !!}



								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('contact_person', 'Name', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">							

											{{ Form::text('contact_person',null, ['class'=>'form-control','placeholder' => 'Contact Person']) }}

											<span class="text text-styled"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('address', 'Address', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  

											{!! Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('state_id', 'State', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">									  

										  	<?php 

				  							$states = null;

				  							if(isset($emp_address->state_id)) { $states = $emp_address->state_id; } ?>

											{!! Form::select('state_id',$state, $states, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('city_id', 'City', array('class' => 'control-label ')) }}

										</div>



										<div class="col-md-6">							  

										  	<?php 

				  							$cities = null;

				  							if(isset($emp_address->city_id)) { $cities = $emp_address->city_id; } ?>

											{!! Form::select('city_id',$city, $cities, ['class' => 'select_item form-control','id'=> 'city' ]) !!}

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('landmark', 'Land Mark', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  <div class="form-group">

											{!! Form::text('landmark',null, ['class' => 'form-control']) !!} </div>

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">



									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('pin', 'Pincode', array('class' => 'control-label ')) }}

										</div>	



										<div class="col-md-6">

										  <div class="form-group">

											{!! Form::text('pin',null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} </div>

										  <span class="text"></span>

										</div>

									</div>

								</div>

							</div>



							<div class="col-md-6 permanent" >

								{!! Form::label('permanent_address', 'Permanent Address', ['class' => 'control-label required']) !!}



								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('contact_person', 'Name', array('class' => 'control-label ')) }}

										</div>



										<div class="col-md-6">																

											{{ Form::text('permanent_contact_person',null, ['class'=>'form-control','placeholder' => 'Contact Person']) }}</div>

											<span class="text text-styled"></span>

										

									</div>

								</div>

								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 		{{ Form::label('address', 'Address', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  

											{!! Form::textarea('permanent_address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('state_id', 'State', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  

										  	<?php 

				  							$states = null;

				  							if(isset($emp_address->state_id)) { $states = $emp_address->state_id; } ?>

											{!! Form::select('permanent_state_id',$state, $states, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('city_id', 'City', array('class' => 'control-label ')) }}

										</div>



										<div class="col-md-6">

										  

										  	<?php 

				  							$cities = null;

				  							if(isset($emp_address->city_id)) { $cities = $emp_address->city_id; } ?>

											{!! Form::select('permanent_city_id', $city, $cities, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('landmark', 'Land Mark', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  

											{!! Form::text('permanent_land_mark',null, ['class' => 'form-control']) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>

								<div class="form-group col-md-12">

									<div class="row">

										<div class="col-md-3">

									 	{{ Form::label('pin', 'Pincode', array('class' => 'control-label ')) }}

										</div>

										<div class="col-md-6">

										  

											{!! Form::text('permanent_pin',null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} 

										  <span class="text"></span>

										</div>

									</div>

								</div>



							</div>

						@endif

					</div>

		</div>	  		



		<div class="tab-pane" id="education">

		  	<div class="clearfix"></div>	

				<br><br>

			<div class="form-body">

			  <div class="form-group">

			   <table style="border-collapse: collapse;" class="table table-bordered">

					<thead>

					  <tr>

						<th>Qualification</th>

						<th>Institution</th>

						<th>Year</th>

						<th>Percentage</th>

						<th></th>

					  </tr>

					  <tr>

					</thead>

					<tbody>

					@foreach($employee_educations as $employee_education)

					<tr>

						<td>

							{{ Form::hidden('education_id',$employee_education->id) }}

							{{ Form::hidden('employee_id',$employee_education->employee_id) }}

							<div>

							{!! Form::text('qualification', $employee_education->qualification,['class' => 'form-control']) !!}

							<span class="text"> {{$employee_education->qualification}} </span>

							</div>

						</td>

						<td>

							<div>

								{!! Form::text('institution', $employee_education->institution,['class' => 'form-control']) !!}

								<span class="text">{{$employee_education->institution}}</span>

							</div>



							<div style="margin-top: 5px">

								{!! Form::select('education_state_id',$state, $employee_education->state_id, ['class' => 'select_item form-control' ,'id'=> 'education_state_id' ]) !!}

								<span class="text">{{$employee_education->state_name or ''}}</span>

							</div>



							<div style="margin-top: 5px">



								{!! Form::select('education_city_id',$city, $employee_education->city_id, ['class' => 'select_item form-control' ,'id'=> 'education_city_id' ]) !!}



								<span class="text">{{$employee_education->city_name or ''}}</span>

							</div>

						</td>

						<td>

							<div>{{ Form::text('year', $employee_education->year, ['class'=>'form-control numbers']) }}

							<span class="text">{{$employee_education->year}}</span>

							</div>

						</td>

						<td>

							<div>{{ Form::text('percentage', $employee_education->percentage, ['class'=>'form-control numbers']) }}

							<span class="text">{{$employee_education->percentage}}</span></div>

						</td>

						<td>

							<a class="grid_label action-btn edit-icon education_edit"><i class="fa li_pen"></i></a>

							<a data-id="{{ $employee_education->id }}" style="display:none" class="grid_label action-btn edit-icon education_update update"><i class="fa li_eye"></i></a>

							<a data-id="{{ $employee_education->id }}" class="grid_label action-btn delete-icon education_delete"><i class="fa fa-trash-o"></i></a>

						</td>

					</tr>

					@endforeach

					<tr>

						<td>

							{{ Form::hidden('education_id',null) }}

							{{ Form::hidden('employee_id',null) }}

							<div>

							{!! Form::text('qualification', null,['class' => 'form-control']) !!}

							<span class="text"></span>

						</div>

						</td>

						<td>

							<div>

								{!! Form::text('institution', null,['class' => 'form-control']) !!}

							<span class="text"></span>

							</div>

							<div style="margin-top: 5px">

								{!! Form::select('education_state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state']) !!}

							<span class="text"></span>

							</div>

							<div style="margin-top: 5px">

								{!! Form::select('education_city_id',['' => 'Select City'], null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}

							<span class="text"></span>

							</div>

						</td>

						<td>

							<div>

							{{ Form::text('year',null, ['class'=>'form-control numbers']) }}

							<span class="text"></span>

							</div>

						</td>

						<td>

							<div>

							{{ Form::text('percentage', null, ['class'=>'form-control numbers']) }}

							<span class="text"></span>

							</div>

						</td>

						<td>

							<a style="display:none" class="grid_label action-btn edit-icon education_edit edit_btn"><i class="fa li_pen"></i></a>



							<a data-id="" style="display:none" class="grid_label action-btn edit-icon education_update update update_btn"><i class="fa li_eye"></i></a>



							<a style="display:none" class="grid_label action-btn delete-icon remove_row education_delete"><i class="fa fa-trash-o"></i></a>



							<a class="grid_label action-btn edit-icon add_grid_row"><i class="fa fa-plus"></i></a>

						</td>

					</tr>

					</tbody>

			  </table>

			  </div>

			</div>

		</div>



		<div class="tab-pane" id="skills">

			<div class="clearfix"></div>

				<br><br>

			<div class="form-body">

			  <div class="form-group">



				<table style="border-collapse: collapse;" class="table table-bordered">

					<thead>

					  <tr>

						<th>Skill Set</th>

						<th>Skill Level</th>

						<th>Experience</th>

						<th></th>

					  </tr>

					  <tr>

					</thead>

					<tbody>

					@foreach($emp_skills as $skill)

					<tr>

						<td>

							{!! Form::hidden('skill_id',$skill->id) !!}

							{{ Form::hidden('employee_id',$skill->employee_id) }}

							<div>

							{!! Form::text('skill', $skill->skill,['class' => 'form-control']) !!}

							<span class="text">{{$skill->skill}}</span></div>

						</td>

						<td>

							<div>

							{!! Form::text('skill_level', $skill->skill_level,['class' => 'form-control ']) !!}

							<span class="text ">{{$skill->skill_level}}</span></div>

						</td>

						<td>

							<div>

							{!! Form::text('experience',$skill->experience,['class' => 'form-control']) !!}

							<span class="text ">{{$skill->experience}}</span></div>

						</td>

						<td>

							<a class="grid_label action-btn edit-icon skill_edit"><i class="fa li_pen"></i></a>



							<a data-id="{{ $skill->id }}" style="display:none" class="grid_label action-btn edit-icon skill_update update_skill"><i class="fa li_eye"></i></a>

							

							<a data-id="{{ $skill->id }}" class="grid_label action-btn delete-icon skill_delete"><i class="fa fa-trash-o"></i></a>

						</td>

					  </tr>

					  @endforeach

					<tr>

						<td>

							{{ Form::hidden('skill_id',null) }}

							{{ Form::hidden('employee_id',null) }}

							<div>

							{!! Form::text('skill', null,['class' => 'form-control']) !!}

							<span class="text"></span></div>

						</td>

						<td>

							<div>

							{!! Form::text('skill_level', null,['class' => 'form-control']) !!}

							<span class="text"></span></div>

						</td>

						<td>

							<div>

							{!! Form::text('experience',null,['class' => 'form-control ']) !!}

							<span class="text"></span></div>

						</td>

						<td>

							<a style="display:none" class="grid_label action-btn edit-icon skill_edit edit_btn"><i class="fa li_pen"></i></a>



							<a data-id="" style="display:none" class="grid_label action-btn edit-icon skill_update update_skill update_btn"><i class="fa li_eye"></i></a>



							<a style="display:none" class="grid_label action-btn delete-icon remove_row skill_delete"><i class="fa fa-trash-o"></i></a>



							<a class="grid_label action-btn edit-icon add_grid_row"><i class="fa fa-plus"></i></a>

						</td>

					</tr>

					</tbody>

			  </table>

			  </div>

			</div>

		</div>



		<div class="tab-pane" id="experience">

			<div class="clearfix"></div>

				<br><br>

			<div class="form-body">

			  <div class="form-group">



				<table style="border-collapse: collapse;" class="table table-bordered">

					<thead>

					  <tr>

						<th>Company Name</th>

						<th>Joined Date</th>

						<th>Relieved Date</th>

						<th></th>

					  </tr>

					  <tr>

					</thead>

					<tbody>

					@foreach($employee_experiences as $employee_experience)

					<tr>

						<td>

							{!! Form::hidden('experience_id',$employee_experience->id) !!}

							{{ Form::hidden('employee_id',$employee_experience->employee_id) }}

							<div>

							{!! Form::text('organization_name', $employee_experience->organization_name,['class' => 'form-control']) !!}

							<span class="text">{{$employee_experience->organization_name}}</span></div>

						</td>

						<td>

							<div>

							{!! Form::text('previous_joined_date', $employee_experience->joined_date,['class' => 'form-control date-picker rearrangedate','data-date-format' => 'dd-mm-yyyy']) !!}

							<span class="text rearrangedatetext">{{$employee_experience->joined_date}}</span></div>

						</td>

						<td>

							<div>

							{!! Form::text('previous_relieved_date',$employee_experience->relieved_date,['class' => 'form-control date-picker rearrangedate','data-date-format' => 'dd-mm-yyyy']) !!}

							<span class="text rearrangedatetext">{{$employee_experience->relieved_date}}</span></div>

						</td>

						<td>

							<a class="grid_label action-btn edit-icon experience_edit"><i class="fa li_pen"></i></a>



							<a data-id="{{ $employee_experience->id }}" style="display:none" class="grid_label action-btn edit-icon experience_update update_experience"><i class="fa li_eye"></i></a>

							

							<a data-id="{{ $employee_experience->id }}" class="grid_label action-btn delete-icon experience_delete"><i class="fa fa-trash-o"></i></a>

						</td>

					  </tr>

					  @endforeach

					<tr>

						<td>

							{{ Form::hidden('experience_id',null) }}

							{{ Form::hidden('employee_id',null) }}

							<div>

							{!! Form::text('organization_name', null,['class' => 'form-control']) !!}

							<span class="text"></span></div>

						</td>

						<td>

							<div>

							{!! Form::text('previous_joined_date', null,['class' => 'form-control date-picker rearrangedate','data-date-format' => 'dd-mm-yyyy']) !!}

							<span class="text"></span></div>

						</td>

						<td>

							<div>

							{!! Form::text('previous_relieved_date',null,['class' => 'form-control date-picker rearrangedate','data-date-format' => 'dd-mm-yyyy']) !!}

							<span class="text"></span></div>

						</td>

						<td>

							<a style="display:none" class="grid_label action-btn edit-icon experience_edit edit_btn"><i class="fa li_pen"></i></a>



							<a data-id="" style="display:none" class="grid_label action-btn edit-icon experience_update update_experience update_btn"><i class="fa li_eye"></i></a>



							<a style="display:none" class="grid_label action-btn delete-icon remove_row experience_delete"><i class="fa fa-trash-o"></i></a>



							<a class="grid_label action-btn edit-icon add_grid_row"><i class="fa fa-plus"></i></a>

						</td>

					</tr>

					</tbody>

			  </table>

			  </div>

			</div>

		</div>			



		<div class="tab-pane" id="salary">

			<div class="clearfix"></div>

				<div class="salary_edit_tab btn btn-info">Edit</div>

				<div style="display: none;" class="salary_update_tab btn btn-success">Update</div>

				<br><br>

		  

			<div class="form-body">



				<div class="row">



					<div class="col-md-5">

						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-6">

									 {{ Form::label('salary_scale_id', 'Salary Scale', array('class' => 'control-label required')) }}

								</div>			

								<div class="col-md-6">

									{!! Form::select('salary_scale_id',$employee_salary_scale, ($employee_salary !=null) ? $employee_salary->salary_scale_id : null, ['class' => 'select_item form-control' ]) !!}

							<span class="text">{{$employee_salary->salary_scale or ''}}</span> 

								</div>			

							</div>

						</div>

						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-6">

									 {{ Form::label('payment_method_id', 'Payment Method', array('class' => 'control-label required')) }}

								</div>			

								<div class="col-md-6">

									{!! Form::select('payment_method_id',$payment, ($employee_salary !=null) ? $employee_salary->payment_method_id : null, ['class' => 'select_item form-control' ]) !!} 

							<span class="text">{{$employee_salary->payment_method or ''}}</span> 

								</div>			

							</div>

						</div>

						<div class="form-group col-md-12">

							<div class="row">

								<div class="col-md-6">

									 {{ Form::label('ot_wage', 'OT Wage', array('class' => 'control-label ')) }}

								</div>			

								<div class="col-md-6">

									{!! Form::text('ot_wage', ($employee_salary != null) ? $employee_salary->ot_wage : 0.00,['class' => 'form-control']) !!}

							<span class="text">{{ $employee_salary->ot_wage or ''}}</span>

								</div>			

							</div>

						</div>				  	

					</div>



					<div class="col-md-7  payhead_container">

						@foreach($pay_heads as $pay_head)

						<div class="form-group col-md-12">

							<div class="row ">		

								<div class="col-md-3 pay_head_value">

									<label for="pay_head_id">{{$pay_head->pay_head}}</label>

								</div>

								<div class="col-md-5" >

									{!! Form::hidden('pay_head_id',$pay_head->payhead_id,['class' => 'form-control','placeholder' =>'Earnings']) !!}

									{!! Form::text('value',  $pay_head->value,['class' => 'form-control','placeholder' =>'Value']) !!}

									<span class="text">{{ $pay_head->value or ''}}

									</span>

								</div>

							</div>

						</div>

						@endforeach



					</div>		



				</div>

			</div>

		</div>



		<div class="tab-pane" id="banking">

			<div class="clearfix"></div>

				<div class="bank_edit_tab btn btn-info">Edit</div>

				<div style="display: none;" class="bank_update_tab btn btn-success">Update</div>

				<br><br>



				<div class="form-group">

						<div class="row">

							<div class="col-md-2">							 

								 {{ Form::label('account_no', 'Account Number', array('class' => 'control-label required')) }}

							</div>			

							<div class="col-md-3">

								{!! Form::text('account_no', ($emp_bank != null) ? $emp_bank->account_no : null,['class' => 'form-control','placeholder'=>'Enter Account No']) !!}

								<span class="text">{{ $emp_bank->account_no or ''}}</span>

							</div>			

						</div>

					</div>

					<div class="form-group">

						<div class="row">

							<div class="col-md-2">							 

								{{ Form::label('ifsc', 'IFSC Code', array('class' => 'control-label required')) }}

							</div>			

							<div class="col-md-3">

								{!! Form::text('ifsc', ($emp_bank != null) ? $emp_bank->ifsc : null,['class' => 'form-control', 'placeholder'=>'Enter (or) Search Here...']) !!}

								<span class="text">{{ $emp_bank->ifsc or ''}}</span>

							</div>			

						</div>

					</div><br>

					<div class="form-group">

						<div class="row">

							<div class="col-md-2">							

								{{ Form::label('micr', 'MICR Code', array('class' => 'control-label ')) }}

							</div>			

							<div class="col-md-3">

								{!! Form::text('micr', ($emp_bank != null) ? $emp_bank->micr : null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}

								<span class="text">{{ $emp_bank->micr or ''}}</span>

							</div>			

						</div>

					</div>

					<div class="form-group">

						<div class="row">

							<div class="col-md-2">							

								{{ Form::label('bank_name', 'Bank', array('class' => 'control-label ')) }}

							</div>			

							<div class="col-md-3">

								{!! Form::text('bank_name', ($emp_bank != null) ? $emp_bank->bank_name : null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}

								<span class="text">{{ $emp_bank->bank_name or ''}}</span>

							</div>			

						</div>

					</div>



					



					<div class="form-group">

						<div class="row">

							<div class="col-md-2">							

								{{ Form::label('state_bank', 'State', array('class' => 'control-label ')) }}

							</div>			

							<div class="col-md-3">



								{!! Form::text('state_bank', ($emp_bank != null) ? $emp_bank->state :null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}

								<span class="text">{{ $emp_bank->state or ''}}</span>

							</div>			

						</div>

					</div>

					<div class="form-group">

						<div class="row">

							<div class="col-md-2">							

								{{ Form::label('city_bank', 'City', array('class' => 'control-label ')) }}

							</div>			

							<div class="col-md-3">

								{!! Form::text('city_bank', ($emp_bank != null) ? $emp_bank->city : null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}

								<span class="text">{{ $emp_bank->city or ''}}</span>

							</div>			

						</div>

					</div>

					<div class="form-group">

						<div class="row">

							<div class="col-md-2">							

								{{ Form::label('bank_branch', 'Branch', array('class' => 'control-label ')) }}

							</div>			

							<div class="col-md-3">

								{!! Form::text('bank_branch', ($emp_bank != null) ? $emp_bank->bank_branch : null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}

								<span class="text">{{ $emp_bank->bank_branch or ''}}</span>



								{!! Form::hidden('bank_id', ($emp_bank != null) ? $emp_bank->bank_id : null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}

							</div>			

						</div>

					</div>			

		</div>





		

		



			{!! Form::close() !!} 

	</div>



</div>

</div>

@stop



@section('dom_links')

@parent

<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>

<script type="text/javascript">

  

$(document).ready(function() {



	var bankname = $('select[name=bank_name]').val();



	 $('input[type=text], .select2, textarea.form-control, .radio').hide();



	 $('.cancel_transaction').on('click', function(e) {

		e.preventDefault();

		$('.close_full_modal').trigger('click');		

	});





	$('.validateform').validate({



		errorElement: 'span', //default input error message container

		errorClass: 'help-block', // default input error message class

		focusInvalid: false, // do not focus the last invalid input

		rules: {

			

			joined_date: { required: true },

			employment_type_id: { required: true },

			branch_id: { required: true },

			department_id: { required: true },

			designation_id: { required: true },

			salary_scale_id: { required: true },

			payment_method_id: { required: true },	

		},



		messages: {

			joined_date: { required: "Joining Date is required." },

			employment_type_id: { required: "Job Type is required." },

			branch_id: { required: "Branch is required." },

			department_id: { required: "Department is required." },

			designation_id: { required: "Designation is required." },

			salary_scale_id: { required: "Salary Scale is required." },

			payment_method_id: { required: "Payment Method is required." },

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

			

		}

	});





	$('select[name=salary_scale_id]').on('change', function(){

		var html = ``;

		 var salary_scale_id = $(this).val();



		$.ajax({

			url: '{{ route('employees.salary_scale') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',								

				salary_scale_id: salary_scale_id,	

			},

			success:function(data, textStatus, jqXHR) {



				var result = data.data;



				for(var i in result) {

					



					html += `<div class="form-group col-md-12 ">

								<div class="row ">

									<div class="col-md-6 pay_head_value">

										<label for="pay_head_id">`+result[i].pay_head+`</label>

									</div>

									<div class="col-md-5" >

										<input type="hidden" name="pay_head_id" class="form-control" value="`+result[i].pay_head_id+`" />

										<input type="text" style="display:block" name="value" class="form-control" value="`+result[i].value+`" />

										<span style="display:none" class="text">`+result[i].pay_head+`</span>

									</div>

								</div>

							</div>`;	

				}



				



				$('.payhead_container').html(html);		



			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});



	});



	$('#mycalendar').fullCalendar({

	height: 400

	});



	$('.add_table_row').on('click', function(){

		$(this).closest('tr').find('input, .select_item').show();

	});



	$('body').on('click', '.remove_row', function() {

			

			//$(this).closest('tr').remove();



	});



	$('body').on('click', '.experience_delete', function(){	



		var id = $(this).data('id');



		//console.log(id);	

		if(id){

			

			var parent = $(this).closest('tr');

			var delete_url = '{{ route('employees.experience_delete') }}';

			delete_row(id, parent, delete_url);

		}

		else{

			$(this).closest('tr').remove();



		}

		



		

	});



	$('body').on('click', '.education_delete', function(){



		var id = $(this).data('id');



		if(id){

			

			var parent = $(this).closest('tr');

			var delete_url = '{{ route('employees.education_delete') }}';

			delete_row(id, parent, delete_url);

		}

		else{

			$(this).closest('tr').remove();

		}



   	});



   	$('body').on('click', '.skill_delete', function(){



   		var id = $(this).data('id');

		if(id){

			

			var parent = $(this).closest('tr');

			var delete_url = '{{ route('employees.skill_delete') }}';

			delete_row(id, parent, delete_url);

		}

		else{

			$(this).closest('tr').remove();

		}



   	});



	function delete_row(id, parent, delete_url) {

	  $('.delete_modal_ajax').modal('show');

		$('.delete_modal_ajax_btn').off().on('click', function() {

			  $.ajax({

			 url: delete_url,

			 type: 'post',

			 data: {

			  _method: 'delete',

			  _token : '{{ csrf_token() }}',

			  id: id,

			  },

			 dataType: "json",

			  success:function(data, textStatus, jqXHR) {

				parent.remove();

				$('.delete_modal_ajax').modal('hide');

				$('.alert-success').text(data.message);

				$('.alert-success').show();



				setTimeout(function() { $('.alert').fadeOut(); }, 3000);

			  },

			 error:function(jqXHR, textStatus, errorThrown) {

			  }

			});

		});

	}



	$('.official_edit_tab').on('click', function() {

		$(this).closest('.tab-pane').find('.official_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();

		$(this).closest('.tab-pane').find('.official_update_tab').show();

		$(this).closest('.tab-pane').find('.text').hide();

		$(this).hide();

	});



	$('.official_update_tab').on('click', function() {

		var obj = $(this);

		if($(".validateform").valid()) {

			$.ajax({

				url: '{{ route('employees.official_info_update') }}',

				type: 'post',

				data: {

					_token: '{{ csrf_token() }}',

					_method: 'PATCH',

					id: '{{$id}}',



					first_name: $('input[name=employee_first_name]').val(),

					last_name: $('input[name=employee_last_name]').val(),

					employee_code: $('input[name=employee_code]').val(),

					email: $('input[name=email]').val(),

					phone_no: $('input[name=phone_no]').val(),

					gender_id: $('select[name=gender_id]').val(),

					blood_group_id: $('select[name=blood_group_id]').val(),

					marital_status: $('select[name=marital_status]').val(),

					



				},

				success:function(data, textStatus, jqXHR) {



					obj.closest('.tab-pane').find('input[type=text]').each(function() {

						$(this).closest('div').find('.text').text($(this).val());

					});



					obj.closest('.tab-pane').find('select').each(function() {

						$(this).closest('div').find('.text').text($(this).find('option:selected').text());

					});



					obj.closest('.tab-pane').find('.official_edit_tab').show();

					obj.closest('.tab-pane').find('.text').show();

					obj.closest('.tab-pane').find('.official_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();

					obj.hide();

				},

				error:function(jqXHR, textStatus, errorThrown) {

					//alert("New Request Failed " +textStatus);

				}

			});	

		}	

	});



	$('.personal_edit_tab').on('click', function() {

		$(this).closest('.tab-pane').find('.personal_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();

		$(this).closest('.tab-pane').find('.personal_update_tab').show();

		$(this).closest('.tab-pane').find('.text').hide();

		$(this).hide();

	});



	$('.personal_update_tab').on('click', function() {

		var obj = $(this);

		$.ajax({

			url: '{{ route('employees.personal_info_update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				employee_id: '{{$id}}',

					joined_date: $('input[name=joined_date]').val(),

					staff_type_id: $('select[name=staff_type_id]').val(),

					employment_type_id: $('select[name=employment_type_id]').val(),

					confirmation_period: $('input[name=confirmation_period]').val(),

					branch_id: $('select[name=branch_id]').val(),

					department_id: $('select[name=department_id]').val(),

					designation_id: $('select[name=designation_id]').val(),

					shift_id: $('select[name=shift_id]').val(),



					pan_no: $('input[name=employee_pan_no]').val(),

					aadhar_no: $('input[name=employee_aadhar_no]').val(),

					passport_no: $('input[name=employee_passport_no]').val(),

					license_type_id: $('select[name=license_type_id]').val(),

					license_no: $('input[name=license_no]').val(),

			},

			success:function(data, textStatus, jqXHR) {

				obj.closest('.tab-pane').find('input[type=text]').each(function() {

					$(this).closest('div').find('.text').text($(this).val());

				});



				obj.closest('.tab-pane').find('select').each(function() {

					$(this).closest('div').find('.text').text($(this).find('option:selected').text());

				});



				obj.closest('.tab-pane').find('.personal_edit_tab').show();

				obj.closest('.tab-pane').find('.text').show();

				obj.closest('.tab-pane').find('.personal_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();

				obj.hide();

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});		

	});



	$('.contact_edit_tab').on('click', function() {

		$(this).closest('.tab-pane').find('.contact_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();

		$(this).closest('.tab-pane').find('.contact_update_tab').show();

		$(this).closest('.tab-pane').find('.text').hide();

		$(this).hide();

	});





	$('.contact_update_tab').on('click', function() {

		var obj = $(this);

		$.ajax({

			url: '{{ route('employees.contact_info_update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				employee_id: '{{$id}}',

				present_id: $('input[name=address_id]').val(),

				permanent_id: $('input[name=permanent_address_id]').val(),

				



				contact_person: $('input[name=contact_person]').val(),

				address: $('textarea[name=address]').val(),

				city_id: $('select[name=city_id]').val(),

				pin: $('input[name=pin]').val(),

				landmark: $('input[name=landmark]').val(),

				permanent_contact_person: $('input[name=permanent_contact_person]').val(),

				permanent_address: $('textarea[name=permanent_address]').val(),

				permanent_city_id: $('select[name=permanent_city_id]').val(),

				permanent_pin: $('input[name=permanent_pin]').val(),

				permanent_landmark: $('input[name=permanent_landmark]').val(),

			},

			success:function(data, textStatus, jqXHR) {

				obj.closest('.tab-pane').find('input[type=text]').each(function() {

					$(this).closest('div').find('.text').text($(this).val());

				});



				obj.closest('.tab-pane').find('select').each(function() {

					$(this).closest('div').find('.text').text($(this).find('option:selected').text());

				});



				obj.closest('.tab-pane').find('.contact_edit_tab').show();

				obj.closest('.tab-pane').find('.text').show();

				obj.closest('.tab-pane').find('.contact_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();

				obj.hide();

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});		

	});





	$('.education_edit').on('click', function() {

		var parent = $(this).closest('tr');

		parent.find('input, select, textarea, .select2, .select_item').show();

		parent.find('.text').hide();

		$(this).parent().find('.update').show();

		$(this).hide();	

	});



	$('.education_update').on('click', function() {



		var obj = $(this);

		var parent = obj.closest('tr');



		$.ajax({

			url: '{{ route('employees.education_info_update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				id: parent.find("input[name=education_id]").val(),

				employee_id: '{{$id}}',

				qualification: parent.find("input[name=qualification]").val(),

				institution: parent.find("input[name=institution]").val(),

				education_city_id: parent.find("select[name=education_city_id]").val(),

				year: parent.find("input[name=year]").val(),

				percentage: parent.find("input[name=percentage]").val(),

			},

			success:function(data, textStatus, jqXHR) {

				parent.find('input, select, textarea, .select2').hide();

				parent.find('.text').show();

				parent.find('.education_edit').show();

				obj.hide();



				obj.closest('tr').find('td').last().find('a').attr('data-id',data.data.id);



				parent.find('input[type=text]').each(function() {

					$(this).closest('div').find('.text').text($(this).val());

				});



				parent.find('select').each(function() {

					$(this).closest('div').find('.text').text($(this).find('option:selected').text());

				});

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});		

	});



	$('.experience_edit').on('click', function() {

		var parent = $(this).closest('tr');

		parent.find('input, select, textarea, .select2, .select_item').show();

		parent.find('.text').hide();

		$(this).parent().find('.update_experience').show();

		$(this).hide();	

	});



	$('.experience_update').on('click', function() 

	{

		var obj = $(this);

		var parent = obj.closest('tr');



		$.ajax({

			url: '{{ route('employees.employee_experience_update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				id: parent.find("input[name=experience_id]").val(),

				employee_id: '{{$id}}',

				organization_name: parent.find("input[name=organization_name]").val(),

				previous_joined_date: parent.find("input[name=previous_joined_date]").val(),

				previous_relieved_date: parent.find("input[name=previous_relieved_date]").val(),

			},

			success:function(data, textStatus, jqXHR) {

				parent.find('input, select, textarea, .select2').hide();

				parent.find('.text').show();

				parent.find('.experience_edit').show();

				obj.hide();



				obj.closest('tr').find('td').last().find('a').attr('data-id',data.data.id);



				parent.find('input[type=text]').each(function() {

					$(this).closest('td').find('.text').text($(this).val());

				});



				parent.find('select').each(function() {

					$(this).closest('td').find('.text').text($(this).find('option:selected').text());

				});

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});		

	});



	$('.skill_edit').on('click', function() {

		var parent = $(this).closest('tr');

		parent.find('input, select, textarea, .select2, .select_item').show();

		parent.find('.text').hide();

		$(this).parent().find('.update_skill').show();

		$(this).hide();	

	});



	$('.skill_update').on('click', function() 

	{

		var obj = $(this);

		var parent = obj.closest('tr');



		$.ajax({

			url: '{{ route('employees.employee_skills_update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				id: parent.find("input[name=skill_id]").val(),

				employee_id: '{{$id}}',

				skill: parent.find("input[name=skill]").val(),

				skill_level: parent.find("input[name=skill_level]").val(),

				experience: parent.find("input[name=experience]").val(),

			},

			success:function(data, textStatus, jqXHR) {

				parent.find('input, select, textarea, .select2').hide();

				parent.find('.text').show();

				parent.find('.skill_edit').show();

				obj.hide();



				obj.closest('tr').find('td').last().find('a').attr('data-id',data.data.id);



				parent.find('input[type=text]').each(function() {

					$(this).closest('td').find('.text').text($(this).val());

				});



				parent.find('select').each(function() {

					$(this).closest('td').find('.text').text($(this).find('option:selected').text());

				});

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});		

	});





	$('.salary_edit_tab').on('click', function() {

		$(this).closest('.tab-pane').find('.salary_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();

		$(this).closest('.tab-pane').find('.salary_update_tab').show();

		$(this).closest('.tab-pane').find('.text').hide();

		$(this).hide();

	});



	$('.salary_update_tab').on('click', function() {

		var obj = $(this);

		if($(".validateform").valid()) {

		$.ajax({

			url: '{{ route('employees.salary_info_update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				id: '{{$id}}',

				salary_scale_id: $('select[name=salary_scale_id]').val(),

				payment_method_id: $('select[name=payment_method_id]').val(),

				ot_wage: $('input[name=ot_wage]').val(),

				

				pay_head_id: $("input[name=pay_head_id]").map(function() { 

                        return this.value; 

                    }).get(),

				payhead_value: $("input[name=value]").map(function() { 

                        return this.value; 

                    }).get()

			},

			success:function(data, textStatus, jqXHR) {



				obj.closest('.tab-pane').find('input[type=text]').each(function() {

					$(this).closest('div').find('.text').text($(this).val());

				});



				obj.closest('.tab-pane').find('select').each(function() {

					$(this).closest('div').find('.text').text($(this).find('option:selected').text());

				});



				obj.closest('.tab-pane').find('.salary_edit_tab').show();

				obj.closest('.tab-pane').find('.text').show();

				obj.closest('.tab-pane').find('.salary_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();

				obj.hide();

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

				}

			});

		}

	});



	$('.bank_edit_tab').on('click', function() {

		$(this).closest('.tab-pane').find('.bank_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();

		$(this).closest('.tab-pane').find('.bank_update_tab').show();

		$(this).closest('.tab-pane').find('.text').hide();

		$(this).hide();

	});



	$('.bank_update_tab').on('click', function() {

		var obj = $(this);

		$.ajax({

			url: '{{ route('employees.bank_info_update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				id: '{{$id}}',				

				account_no: $('input[name=account_no]').val(),

				ifsc: $('input[name=ifsc]').val(),

				micr: $('input[name=micr]').val(),

				bank_name: $('input[name=bank_name]').val(),

				bank_branch: $('input[name=bank_branch]').val(),

				bank_id: $('input[name=bank_id]').val(),

			},

			success:function(data, textStatus, jqXHR) {



				obj.closest('.tab-pane').find('input[type=text]').each(function() {

					$(this).closest('div').find('.text').text($(this).val());

				});



				obj.closest('.tab-pane').find('select').each(function() {

					$(this).closest('div').find('.text').text($(this).find('option:selected').text());

				});



				obj.closest('.tab-pane').find('.bank_edit_tab').show();

				obj.closest('.tab-pane').find('.text').show();

				obj.closest('.tab-pane').find('.bank_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();

				obj.hide();

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});

	});



		$("input[name=ifsc]").autocomplete({

		     	

		     	source: "{{ route('ifsc_search') }}",

		      	minLength: 2,

		      	select: function( event, ui ) {	



		      	console.log(ui);	      	



		       		$('input[name=ifsc]').val(ui.item.label);		       		



		       		$('input[name=bank_name]').val(ui.item.bank_name);

		       		$('input[name=state_bank]').val(ui.item.state_name);

		       		$('input[name=city_bank]').val(ui.item.city_name);

		       		$('input[name=bank_branch]').val(ui.item.branch_name);

		       		$('input[name=micr]').val(ui.item.micr_code);



		       		$('input[name=bank_id]').val(ui.item.id);       		



		       	}

		 });      	



	$('.date-picker').datepicker('destroy');







	$('body').off('click', '.add_grid_row').on('click', '.add_grid_row', function() {



		/*$('.select_item').each(function() {

			var select = $(this); 



			console.log(select); 

			if(select.data('select2')) { 

				select.select2("destroy"); 

			}

		});*/



		$('.date-picker').datepicker('destroy');



		var clone = $(this).closest('tr').clone(true, true);



		//clone.find('input, .select2, .update_btn, .remove_row').show();



		$(this).closest('tr').after(clone);

		//$('.select_item').select2();



		$('.date-picker').datepicker('update');



		$(this).hide();

		$(this).closest('tr').find('input, .select2, .update_btn, .remove_row, select').show();

	});









	$( "select[name=state_id], select[name=billing_state_id], select[name=education_state_id], select[name=permanent_state_id]" ).change(function () {



		var city;



		if($(this).attr('name') == "state_id") {

			city = $(this).closest('.present').find( "select[name=city_id]" );

		} else if($(this).attr('name') == "billing_state_id") {

			city = $(this).closest('.row').find( "select[name=billing_city_id]" );

		} else if($(this).attr('name') == "education_state_id") {

			city = $(this).closest('.row').find( "select[name=education_city_id]" );

		}

		else if($(this).attr('name') == "permanent_state_id") {

			city = $(this).closest('.permanent').find( "select[name=permanent_city_id]" );

		}	



		var select_val = $(this).val();

		city.empty();

		city.append("<option value=''>Select City</option>");

			$.ajax({

				 url: '{{ route('get_city') }}',

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

			},

			error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

			}

		});

	});



	/*$('select[name=bank_name]').on('change', function(e) {

		var bank = $(this).val();

		$('.loader_wall_onspot').show();



		$.get("{{route('get_bank_state')}}?bank=" + bank, function(data) {

			$('.loader_wall_onspot').hide();



			$('select[name=state_bank]').empty();

			$('select[name=state_bank]').append('<option value="">Select State</option>');

			$('select[name=city_bank]').empty();

			$('select[name=city_bank]').append('<option value="">Select City</option>');

			$('select[name=bank_branch]').empty();

			$('select[name=bank_branch]').append('<option value="">Select Branch</option>');

			$('input[name=ifsc], input[name=micr]').val();







			$.each(data['state'], function(index, data) {

				$('select[name=state_bank]').append('<option value="'+data.state+'">'+data.state+'</option>');

			});

			$('select[name=state_bank], select[name=city_bank], select[name=bank_branch]').val("").trigger("change");



		});

	});



	$('select[name=state_bank]').on('change', function(e) {

		var state = $(this).val();

		var bank = $('select[name=bank_name]').val();

		$('.loader_wall_onspot').show();



		$.get("{{route('get_bank_city')}}?state=" + state + '&bank=' + bank, function(data) {

			$('.loader_wall_onspot').hide();



			$('select[name=city_bank]').empty();

			$('select[name=city_bank]').append('<option value="">Select City</option>');

			$('select[name=bank_branch]').empty();

			$('select[name=bank_branch]').append('<option value="">Select Branch</option>');

			$('input[name=ifsc], input[name=micr]').val();



			$.each(data['city'], function(index, data) {

				$('select[name=city_bank]').append('<option value="' + data.city + '">' + data.city + '</option>');

			});



		});

	});



	$('select[name=city_bank]').on('change', function(e) {



		var city = $(this).val();

		var bank = $('select[name=bank_name]').val();

		var state = $('select[name=state_bank]').val();

		$('.loader_wall_onspot').show();



		$.get("{{route('get_bank_branch')}}?city=" + city + '&bank=' + bank + '&state=' + state, function(data) {

			$('.loader_wall_onspot').hide();



			$('select[name=bank_branch]').empty();

			$('select[name=bank_branch]').append('<option value="">Select Branch</option>');

			$('input[name=ifsc], input[name=micr]').val();



			$.each(data['branch'], function(index, data) {

				$('select[name=bank_branch]').append('<option value="' + data.branch + '">' + data.branch + '</option>');

			});

		});

	});



	$('select[name=bank_branch]').on('change', function(e) {



		var branch = $(this).val();

		var bank = $('select[name=bank_name]').val();

		var state = $('select[name=state_bank]').val();

		var city = $('select[name=city_bank]').val();

	   

		$('.loader_wall_onspot').show();



		$.get("{{route('get_bank_code')}}?branch=" + branch+ '&bank=' + bank + '&state=' + state+ '&city=' + city, function(data) {

			$('.loader_wall_onspot').hide();



			$('input[name=ifsc]').val(data.ifsc);

			$('input[name=micr]').val(data.micr);

		});

	});*/



 });



</script> 

@stop