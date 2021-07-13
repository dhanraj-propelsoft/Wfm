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
		font-size: 22px;
		font-family: 'ProximaNovaLight', 'ProximaNovaRegular', 'Source Sans Pro', Arial, sans-serif;
	}
  </style>
@stop

@if( $type == "admin" && (App\Organization::checkModuleExists('super_admin', Session::get('organization_id'))))
@include('includes.admin')
@else
@include('includes.settings')
@endif
@section('content')
@include('includes.add_user')
<div class="alert alert-success">
</div>
<div class="content">
<div class="row">
<div class="col-md-3">
<ul class="list-unstyled profile-nav">
	<li style="position:relative"> 
		<img width="100%" src="{{ URL::to('/') }}/public/users/images/no_image.jpg" style="background: #eee; border-radius: 5px; padding: 10px;" class="img-responsive" alt="Employee Image"/>
@if($organization->business_id == $id)
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
@endif
			</li>
			</ul>
		
			<h4 style="text-align: center;"></h4>
			<h5 style="text-align: center;"></h5>
		
</div>
<div class="col-md-9">

<h3 class="float-left"></h3><div class="clearfix"></div>
<h5 class="float-left"></h5>
<div class="clearfix"></div>

<ul class="nav nav-tabs">
  <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#business_contact">Bussiness Contact</a> </li>
  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#communication">Communication</a> </li>
  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#ownership">Ownership</a> </li>
  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#management">Management Staff</a> </li>
  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#staff">Staff</a></li>
  
</ul>

{!! Form::open(['class' => 'form-horizontal validateform']) !!}

<div class="tab-content" style= border-top: 0px; padding: 10px;">


	<div class="tab-pane active" id="business_contact">
		<div class="clearfix"></div><br>

		@if($organization->business_id == $id)
			<div class="business_contact_edit_tab btn btn-info">Edit</div>
			<div style="display: none;" class="business_contact_update_tab btn btn-success">Update</div>
			<br><br>
			@endif
		<div class="form-body">
			<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						{!! Form::label('business_name','Business Name', array('class' => 'control-label required')) !!}
					</div>	
					<div class="col-md-4">
						{!! Form::text('business_name', $business->business_name,['class' => 'form-control ']) !!}
						<span class="text text-styled">{{$business->business_name}}</span>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
				  <div class="col-md-4">
					{!! Form::label('alias', 'Alice', array('class' => 'control-label required')) !!}
				</div>
					<div class="col-md-4">
					{!! Form::text('alias', $business->alias, ['class' => 'form-control' ]) !!}
					<span class="text text-styled">{{$business->alias}}</span>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
				  	<div class="col-md-4">
						{!! Form::label('business_nature_id','Nature Of Business', array('class' => 'control-label required')) !!}
					</div>
					<div class="col-md-4">
						{!! Form::select('business_nature_id',$businessnature, $business->business_nature_id, ['class' => 'select_item form-control' ]) !!}
						<span class="text text-styled">{{ $business->business_nature }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						{!! Form::label('business_professionalism_id','Business Profession', array('class' => 'control-label required')) !!}
					</div>
						<div class="col-md-4">
						{!! Form::select('business_professionalism_id',$businessprofessionalism, $business->business_professionalism_id, ['class' => 'select_item form-control' ]) !!}
						<span class="text text-styled">{{$business->business_professionalism}}</span>
					</div>
				</div>
			</div>			

			<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						{!! Form::label('gst','GST No', array('class' => 'control-label required')) !!}
					</div>	
					<div class="col-md-4">
						{!! Form::text('gst', $business->gst,['class' => 'form-control ']) !!}
						<span class="text text-styled">{{$business->gst}}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						{!! Form::label('business_pan','PAN', array('class' => 'control-label required')) !!}
					</div>	
					<div class="col-md-4">
						{!! Form::text('business_pan', $business->pan,['class' => 'form-control ']) !!}
						<span class="text text-styled">{{$business->pan}}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						{!! Form::label('business_tin','TIN', array('class' => 'control-label required')) !!}
					</div>	
					<div class="col-md-4">
						{!! Form::text('business_tin', $business->tin,['class' => 'form-control ']) !!}
						<span class="text text-styled">{{$business->tin}}</span>
					</div>				
				</div>
			</div>			
		</div>
	</div>

	<div class="tab-pane" id="communication">
		<div class="clearfix"></div>
		<br>
		@if($organization->business_id == $id)
		<div class="communication_edit_tab btn btn-info">Edit</div>
			<div style="display: none;" class="communication_update_tab btn btn-success">Update</div>
			<br><br>
		@endif

							<div class="form-group">
								<div class="row">										
									<div class="col-md-6">
										{!! Form::text('placename', $businesscommuincation->placename,['class' => 'form-control ']) !!}
										<span class="text text-styled">{{$businesscommuincation->placename}}</span>
									</div>
									<div class="col-md-6">
										{!! Form::text('mobile_no', $businesscommuincation->mobile_no,['class' => 'form-control ','id' => 'mobile_number']) !!}
										<span class="text text-styled">{{$businesscommuincation->mobile_no}}</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">										
									<div class="col-md-6">
										{!! Form::text('email_id', $businesscommuincation->email_address,['class' => 'form-control','placeholder' => 'Name@gmail.com']) !!}
										<span class="text text-styled">{{$businesscommuincation->email_address}}</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-12">							  
										{!! Form::textarea('address', $businesscommuincation->address, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} 
									  <span class="text">{{ $businesscommuincation->address }}</span>
									</div>
								</div>
							</div>				

							<div class="form-group">
								<div class="row">
									<div class="col-md-6">			 

									  	<?php 
			  							$states = null;
			  							if(isset($businesscommuincation->state_id)) { $states = $businesscommuincation->state_id; } ?>
										{!! Form::select('state_id',$state, $states , ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

									  <span class="text">{{ $businesscommuincation->state_name }}</span>
									</div>
									<div class="col-md-6">
									
									  	<?php 
			  							$cities = null;
			  							if(isset($businesscommuincation->city_id)) { $cities = $businesscommuincation->city_id; } ?>
										{!! Form::select('city_id', $city, $cities, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}
									  <span class="text">{{ $businesscommuincation->city_name }}</span>
									</div>
								</div> 
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-6">
									 
										{!! Form::text('pin',$businesscommuincation->pin, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} 
									  <span class="text">{{ $businesscommuincation->pin }}</span>
									</div>

									<div class="col-md-6">
									  
										{!! Form::text('google',$businesscommuincation->google, ['class' => 'form-control', 'placeholder' => 'Google Location']) !!} 
									  <span class="text">{{ $businesscommuincation->google }}</span>
									</div>
								</div>
							</div>
	</div>	

	<div class="tab-pane" id="ownership">
		<div class="clearfix"></div>
			<br><br>
		<div class="form-body">
		  <div class="form-group">

		@if( count($ownerships) > 0)
			<table style="border-collapse: collapse;" class="table table-bordered">
				<thead>
				  <tr>
				  	<th>Employee</th>
					
					<th>Employee Code</th>
					<th>Phone No</th>
					<th>E-Mail</th>
					@if($organization->business_id == $id)
					<th>Action</th>
					@endif
				  </tr>
				  <tr>
				</thead>
				<tbody>
				@foreach($ownerships as $ownership)
				<tr>
					<td>		

						<!-- <div>{!! Form::select('department_id',$department, null, ['class' => 'select_item form-control' ]) !!}
						</div>
					 	<div>{!! Form::select('designation_id',['' => 'Select Designation'],null, ['class' => 'select_item form-control' ]) !!}
					 	</div>
						<div>{!! Form::select('employee_id',['' => 'Select Employee'],$ownership->id, ['class' => 'select_item form-control' ]) !!}<span class="text text-styled">{{$ownership->employee_name}}</span></div> -->

						<div>
						{!! Form::hidden('employee_id', $ownership->id,['class' => 'form-control']) !!}{{$ownership->employee_name}}</div>			
					</td>
					
					<td>
						<div>
							{!! Form::text('employee_code', $ownership->employee_code,['class' => 'form-control']) !!}
							<span class="text">{{$ownership->employee_code}}</span>
						</div>						
					</td>
					<td>
						<div>
							{!! Form::text('phone_no', $ownership->phone_no,['class' => 'form-control']) !!}
							<span class="text">{{$ownership->phone_no}}</span>
						</div>
					</td>
					<td>
						<div>
							{!! Form::text('email', $ownership->email,['class' => 'form-control']) !!}
							<span class="text">{{$ownership->email}}</span>
						</div>
					</td>
					@if($organization->business_id == $id)
					<td>
						<a class="grid_label action-btn edit-icon ownership_edit"><i class="fa li_pen"></i></a>

						<a data-id="{{ $ownership->id }}" style="display:none" class="grid_label action-btn edit-icon ownership_update update_ownership"><i class="fa li_eye"></i></a>
						
						<a data-id="{{ $ownership->id }}" class="grid_label action-btn delete-icon ownership_delete"><i class="fa fa-trash-o"></i></a>
					</td>
					@endif
				  </tr>
				  @endforeach

				  @if($organization->business_id == $id)
				<tr>
					<td>
						<div>
						{!! Form::select('department_id',$department, null, ['class' => 'select_item form-control' ]) !!} </div>
						
						<div>
						{!! Form::select('designation_id',['' => 'Select Designation'],null, ['class' => 'select_item form-control' ]) !!}</div>
						
						<div>
						{!! Form::select('employee_id',['' => 'Select Employee'],null, ['class' => 'select_item form-control' ]) !!}
						<span class="text text-styled"></span> </div>
					</td>
					
					<td>
						<div>
						{!! Form::text('employee_code', null,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						<div>
						{!! Form::text('phone_no', null,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						{!! Form::text('email', null,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						<a style="display:none" class="grid_label action-btn edit-icon ownership_edit edit_btn"><i class="fa li_pen"></i></a>

						<a data-id="" style="display:none" class="grid_label action-btn edit-icon ownership_update update_ownership update_btn"><i class="fa li_eye"></i></a>

						<a style="display:none" class="grid_label action-btn delete-icon remove_row ownership_delete"><i class="fa fa-trash-o"></i></a>

						<a class="grid_label action-btn edit-icon add_grid_row"><i class="fa fa-plus"></i></a>
					</td>
				</tr>
				@endif
				</tbody>
		  	</table>
		@endif
		  </div>
		</div>
	</div>

	<div class="tab-pane" id="management">
		<div class="clearfix"></div>
			<br><br>
		<div class="form-body">
		  <div class="form-group">
		  	@if(count($managements) > 0)
			<table style="border-collapse: collapse;" class="table table-bordered">
				<thead>
				  <tr>
					<th>Management Staff</th>
					<th>Employee Code</th>
					<th>Phone No</th>
					<th>E-Mail</th>
					@if($organization->business_id == $id)
					<th>Action</th>
					@endif
				  </tr>
				  <tr>
				</thead>
				<tbody>
				@foreach($managements as $management)
				<tr>
					<td>
						<!-- <div>{!! Form::select('department_id',$department, null, ['class' => 'select_item form-control' ]) !!}
						</div>
					 	<div>{!! Form::select('designation_id',['' => 'Select Designation'],null, ['class' => 'select_item form-control' ]) !!}
					 	</div>
						<div>{!! Form::select('employee_id',['' => 'Select Employee'],$management->id, ['class' => 'select_item form-control' ]) !!}<span class="text text-styled">{{$management->employee_name}}</span></div> -->

						{!! Form::hidden('employee_id', $management->id,['class' => 'form-control']) !!}{{$management->employee_name}}</div>
					</td>
					<td>
						<div>
							{!! Form::text('employee_code', $management->employee_code,['class' => 'form-control']) !!}
							<span class="text">{{$management->employee_code}}</span>
						</div>						
					</td>
					<td>
						<div>
							{!! Form::text('phone_no', $management->phone_no,['class' => 'form-control']) !!}
							<span class="text">{{$management->phone_no}}</span>
						</div>
					</td>
					<td>
						<div>
							{!! Form::text('email', $management->email,['class' => 'form-control']) !!}
							<span class="text">{{$management->email}}</span>
						</div>
					</td>
					@if($organization->business_id == $id)
					<td>
						<a class="grid_label action-btn edit-icon management_edit"><i class="fa li_pen"></i></a>

						<a data-id="{{ $management->id }}" style="display:none" class="grid_label action-btn edit-icon ownership_update update_ownership"><i class="fa li_eye"></i></a>
						
						<a data-id="{{ $management->id }}" class="grid_label action-btn delete-icon ownership_delete"><i class="fa fa-trash-o"></i></a>
					</td>
					@endif
				  </tr>
				  @endforeach

				  @if($organization->business_id == $id)
				<tr>
					<td>
						<div>							
						{!! Form::select('department_id',$department, null, ['class' => 'select_item form-control' ]) !!} </div>
						
						<div>
						{!! Form::select('designation_id',['' => 'Select Designation'],null, ['class' => 'select_item form-control' ]) !!}</div>
						
						<div>
						{!! Form::select('employee_id',['' => 'Select Employee'],null, ['class' => 'select_item form-control' ]) !!}
						<span class="text text-styled"></span> </div>	
					</td>
					<td>
						<div>
						{!! Form::text('employee_code', $management->employee_code,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						<div>
						{!! Form::text('phone_no', $management->phone_no,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						{!! Form::text('email', $management->email,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						<a style="display:none" class="grid_label action-btn edit-icon management_edit edit_btn"><i class="fa li_pen"></i></a>

						<a data-id="" style="display:none" class="grid_label action-btn edit-icon management_update update_management update_btn"><i class="fa li_eye"></i></a>

						<a style="display:none" class="grid_label action-btn delete-icon remove_row management_delete"><i class="fa fa-trash-o"></i></a>

						<a class="grid_label action-btn edit-icon add_grid_row"><i class="fa fa-plus"></i></a>
					</td>
				</tr>
				@endif
				</tbody>
		  </table>
		  @endif
		  </div>
		</div>
	</div>

	<div class="tab-pane" id="staff">
		<div class="clearfix"></div>
			<br><br>
		<div class="form-body">
		  <div class="form-group">

		@if(count($staffs) > 0)
			<table style="border-collapse: collapse;" class="table table-bordered">
				<thead>
				  <tr>
					<th>Staff</th>
					<th>Employee Code</th>
					<th>Phone No</th>
					<th>E-Mail</th>
					@if($organization->business_id == $id)
					<th>Action</th>
					@endif
				  </tr>
				  <tr>
				</thead>
				<tbody>
				@foreach($staffs as $staff)
				<tr>
					<td>
						<!-- <div>{!! Form::select('department_id',$department, null, ['class' => 'select_item form-control' ]) !!}
						</div>
					 	<div>{!! Form::select('designation_id',['' => 'Select Designation'],null, ['class' => 'select_item form-control' ]) !!}
					 	</div>
						<div>{!! Form::select('employee_id',['' => 'Select Employee'], $staff->id, ['class' => 'select_item form-control' ]) !!}<span class="text text-styled">{{$staff->employee_name}}</span></div> -->

						{!! Form::hidden('employee_id', $staff->id,['class' => 'form-control']) !!}{{$staff->employee_name}}</div>
					</td>
					<td>
						<div>
							{!! Form::text('employee_code', $staff->employee_code,['class' => 'form-control']) !!}
							<span class="text">{{$staff->employee_code}}</span>
						</div>						
					</td>
					<td>
						<div>
							{!! Form::text('phone_no', $staff->phone_no,['class' => 'form-control']) !!}
							<span class="text">{{$staff->phone_no}}</span>
						</div>
					</td>
					<td>
						<div>
							{!! Form::text('email', $staff->email,['class' => 'form-control']) !!}
							<span class="text">{{$staff->email}}</span>
						</div>
					</td>
					@if($organization->business_id == $id)
					<td>
						<a class="grid_label action-btn edit-icon staff_edit"><i class="fa li_pen"></i></a>

						<a data-id="{{ $staff->id }}" style="display:none" class="grid_label action-btn edit-icon ownership_update update_ownership"><i class="fa li_eye"></i></a>
						
						<a data-id="{{ $staff->id }}" class="grid_label action-btn delete-icon ownership_delete"><i class="fa fa-trash-o"></i></a>
					</td>
					@endif
				  </tr>
				  @endforeach
				 @if($organization->business_id == $id)
				<tr>
					<td>
						<div>							
						{!! Form::select('department_id',$department, null, ['class' => 'select_item form-control' ]) !!} </div>
						
						<div>
						{!! Form::select('designation_id',['' => 'Select Designation'],null, ['class' => 'select_item form-control' ]) !!}</div>
						
						<div>
						{!! Form::select('employee_id',['' => 'Select Employee'],null, ['class' => 'select_item form-control' ]) !!}
						<span class="text text-styled"></span> </div>	
					</td>
					<td>
						<div>
						{!! Form::text('employee_code', $staff->employee_code,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						<div>
						{!! Form::text('phone_no', $staff->phone_no,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						{!! Form::text('email', $staff->email,['class' => 'form-control']) !!}
						<span class="text"></span></div>
					</td>
					<td>
						<a style="display:none" class="grid_label action-btn edit-icon staff_edit edit_btn"><i class="fa li_pen"></i></a>

						<a data-id="" style="display:none" class="grid_label action-btn edit-icon staff_update update_staff update_btn"><i class="fa li_eye"></i></a>

						<a style="display:none" class="grid_label action-btn delete-icon remove_row staff_delete"><i class="fa fa-trash-o"></i></a>

						<a class="grid_label action-btn edit-icon add_grid_row"><i class="fa fa-plus"></i></a>
					</td>
				</tr>
				@endif
				</tbody>
		  	</table>
		@endif
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
		},

		messages: {
			joined_date: { required: "Joining Date is required." },
			employment_type_id: { required: "Job Type is required." },
			branch_id: { required: "Branch is required." },
			department_id: { required: "Department is required." },
			designation_id: { required: "Designation is required." },
			salary_scale_id: { required: "Salary Scale is required." },
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


	$( "select[name=department_id]" ).change(function () {
		var designation = $( "select[name=designation_id]" );
		var id = $(this).val();
		designation.val("");
		designation.select2('val', '');
		designation.empty();
		if(id != "") {
		$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_designation') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					department_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						designation.append("<option value=''>Select Designation</option>");
						for(var i in result) {	
							designation.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	$( "select[name=designation_id]" ).change(function () {
		var employee= $( "select[name=employee_id]" );
		var id = $(this).val();
		employee.val("");
		employee.select2('val', '');
		employee.empty();
		if(id != "") {
			$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_employee') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					designation_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						employee.append("<option value=''>Select Employee</option>");
						for(var i in result) {
							employee.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	

	$('#mycalendar').fullCalendar({
	height: 400
	});

	$('.add_table_row').on('click', function(){
		$(this).closest('tr').find('input, .select_item').show();
	});

	$('body').on('click', '.experience_delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('employees.experience_delete') }}';
		delete_row(id, parent, delete_url);
	});

	$('body').on('click', '.education_delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('employees.education_delete') }}';
		delete_row(id, parent, delete_url);
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

	$('.business_contact_edit_tab').on('click', function() {
		$(this).closest('.tab-pane').find('.business_contact_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();
		$(this).closest('.tab-pane').find('.business_contact_update_tab').show();
		$(this).closest('.tab-pane').find('.text').hide();
		$(this).hide();
	});

	$('.business_contact_update_tab').on('click', function() {
		var obj = $(this);
		if($(".validateform").valid()) {
			$.ajax({
				url: '{{ route('business_profile.business_contact_update') }}',
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					_method: 'PATCH',
					id: '{{$id}}',
					business_name: $('input[name=business_name]').val(),
					alias: $('input[name=alias]').val(),
					business_nature_id: $('select[name=business_nature_id]').val(),
					business_professionalism_id: $('select[name=business_professionalism_id]').val(),
					
					gst: $('input[name=gst]').val(),
					pan: $('input[name=business_pan]').val(),
					tin: $('input[name=business_tin]').val(),
					
					},
				success:function(data, textStatus, jqXHR) {

					obj.closest('.tab-pane').find('input[type=text]').each(function() {
						$(this).closest('div').find('.text').text($(this).val());
					});

					obj.closest('.tab-pane').find('select').each(function() {
						$(this).closest('div').find('.text').text($(this).find('option:selected').text());
					});

					obj.closest('.tab-pane').find('.business_contact_edit_tab').show();
					obj.closest('.tab-pane').find('.text').show();
					obj.closest('.tab-pane').find('.business_contact_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();
					obj.hide();
				},
				error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
				}
			});	
		}	
	});

	$('.communication_edit_tab').on('click', function() {
		$(this).closest('.tab-pane').find('.communication_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();
		$(this).closest('.tab-pane').find('.communication_update_tab').show();
		$(this).closest('.tab-pane').find('.text').hide();
		$(this).hide();
	});

	$('.communication_update_tab').on('click', function() {
		var obj = $(this);

		var mobile_number =  $('#mobile_number').val();

		
		$.ajax({
			url: '{{ route('business_profile.communication_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: '{{$id}}',
				placename: $('input[name=placename]').val(),
				mobile_no: mobile_number,
				address: $('textarea[name=address]').val(),
				email_address: $('input[name=email_address]').val(),
				email_id:$('input[name=email_id]').val(),

				web_address: $('input[name=web_address]').val(),
				city_id: $('select[name=city_id]').val(),
				pin: $('input[name=pin]').val(),
				google: $('input[name=google]').val(),
				
				},
			success:function(data, textStatus, jqXHR) {


				obj.closest('.tab-pane').find('input[type=text]').each(function() {
					$(this).closest('div').find('.text').text($(this).val());
				});

				obj.closest('.tab-pane').find('select').each(function() {
					$(this).closest('div').find('.text').text($(this).find('option:selected').text());
				});

				obj.closest('.tab-pane').find('.communication_edit_tab').show();
				obj.closest('.tab-pane').find('.text').show();
				obj.closest('.tab-pane').find('.communication_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();
				obj.hide();
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});		
	});

	$('.ownership_edit').on('click', function() {
		var parent = $(this).closest('tr');
		parent.find('input, select, textarea, .select2, .select_item').show();
		parent.find('.text').hide();
		$(this).parent().find('.update_ownership').show();
		$(this).hide();	
	});

	$('.ownership_update').on('click', function() 
	{
		var obj = $(this);
		var parent = obj.closest('tr');

		$.ajax({
			url: '{{ route('business_profile.ownership_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',				
				employee_id: parent.find("input[name=employee_id]").val(),		
				employee_code: parent.find("input[name=employee_code]").val(),
				phone_no: parent.find("input[name=phone_no]").val(),
				email: parent.find("input[name=email]").val(),
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

	$('.management_edit').on('click', function() {
		var parent = $(this).closest('tr');
		parent.find('input, select, textarea, .select2, .select_item').show();
		parent.find('.text').hide();
		$(this).parent().find('.update_ownership').show();
		$(this).hide();	
	});

	$('.management_update').on('click', function() 
	{
		var obj = $(this);
		var parent = obj.closest('tr');

		$.ajax({
			url: '{{ route('business_profile.ownership_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				
				
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


	$('.staff_edit').on('click', function() {
		var parent = $(this).closest('tr');
		parent.find('input, select, textarea, .select2, .select_item').show();
		parent.find('.text').hide();
		$(this).parent().find('.update_ownership').show();
		$(this).hide();	
	});

	$('.staff_update').on('click', function() 
	{
		var obj = $(this);
		var parent = obj.closest('tr');

		$.ajax({
			url: '{{ route('business_profile.ownership_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',				
				
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
								
				account_no: $('input[name=account_no]').val(),
				ifsc: $('input[name=ifsc]').val(),
				micr: $('input[name=micr]').val(),
				bank_name: $('select[name=bank_name]').val(),
				bank_branch: $('select[name=bank_branch]').val(),
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

	$('.date-picker').datepicker('destroy');

	$('body').off('click', '.add_grid_row').on('click', '.add_grid_row', function() {

		$('.select_item').each(function() {
			var select = $(this);  
			if(select.data('select2')) { 
				select.select2("destroy"); 
			}
		});



		//$('.date-picker').datepicker('destroy');

		var clone = $(this).closest('tr').clone(true, true);
		clone.find('select[name=item_id], input[name=quantity]').val("");
		clone.find('input, .select2, .update_btn, .remove_row').show();
		$(this).closest('tr').after(clone);
		$('.select_item').select2();

		//$('.date-picker').datepicker('update');

		$(this).hide();
		$(this).closest('tr').find('input, .select2, .update_btn, .remove_row').show();
	  	
	  	var person_id = $(this).closest('tr').find('select[name=person_id]');
	  //select_user($(this));

	  });

	$('body').on('click', '.remove_row', function() {
	
		$(this).closest('tr').remove();

	});

	$( "select[name=state_id], select[name=billing_state_id], select[name=education_state_id]" ).change(function () {

		var city;

		if($(this).attr('name') == "state_id") {
			city = $(this).closest('.row').find( "select[name=city_id]" );
		} else if($(this).attr('name') == "billing_state_id") {
			city = $(this).closest('.row').find( "select[name=billing_city_id]" );
		} else if($(this).attr('name') == "education_state_id") {
			city = $(this).closest('.row').find( "select[name=education_city_id]" );
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

	$('select[name=person_id]').each(function() {
	  $(this).prepend('<option value="0"></option>');
	  select_user($(this));
	});

		$('select[name=bank_name]').on('change', function(e) {
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
		});



  });

</script> 
@stop