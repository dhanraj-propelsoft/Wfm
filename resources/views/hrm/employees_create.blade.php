<div class="content">
	<div class="fill header">
		<h3 class="float-left">New Employee </h3>
		<div class="alert alert-danger"></div>
		<div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i></div>
	</div>
	<div class="clearfix"></div>
	<br>
	<ul class="nav nav-tabs">
		<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#employee">Employee</a> </li>
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#official">Official Details</a> </li>
		<!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#communication_address">Communication Address</a> </li> -->
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#communication_address">Communication Address</a> </li>
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#education">Educational Details</a> </li>
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#skill">Skills</a> </li>
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#experience">Previous Experience</a> </li>
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#salary">Salary Details</a> </li>
		<!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#banking">Bank</a> </li> -->
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#banking">Bank</a> </li>
	</ul>

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}
	{{ csrf_field() }}

	<div class="tab-content" style= border-top: 0px; padding: 10px;" id="tabs">

		<div class="tab-pane active" id="employee">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">				

				<div class="row">

					<div class="col-md-6">

					  	<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('person_id', 'Enter Employee', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-7 search_container">
									{{ Form::select('person_id', $people, null, ['class' => 'form-control individual person_id select_item', 'id' => 'person_id']) }}
									{{ Form::checkbox('account_person_type_id', 'employee', true, ['id' => 'account_person_type_id']) }}
									{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
									<div class="content"></div>				
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('employee_code', 'Employee Code', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-7">
									{{ Form::text('employee_code', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('employee_first_name', 'First Name', array('class' => 'control-label required')) }}
								</div>
								<div class="col-md-2">
									{!! Form::select('title',$title, null, ['class' => 'select_item form-control title' ]) !!}						
								</div>	
								
								<div class="col-md-5">
									{{ Form::text('employee_first_name', null, ['class'=>'form-control first_name']) }}						
								</div>
										
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('employee_last_name', 'Last Name', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-7">
									{{ Form::text('employee_last_name', null, ['class'=>'form-control last_name']) }}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
							  <div class="col-md-3">
								{{ Form::label('email', 'Email', array('class' => 'control-label required')) }}
								 </div>
							  <div class="col-md-7">
							  	{{ Form::text('employee_email', null, ['class'=>'form-control email']) }}
							  </div>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
							  	<div class="col-md-3">
								{{ Form::label('phone_no', 'Mobile', array('class' => 'control-label required')) }}
								</div>
							  	<div class="col-md-7">				
								{{ Form::text('phone_no', null, ['class'=>'form-control mobile']) }} 
								</div>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
							  	<div class="col-md-3">
								{{ Form::label('gender_id', 'Gender', array('class' => 'control-label required')) }}
								 </div>
							  	<div class="col-md-7">				
								@foreach($genders as $gender)
										<input type="radio" name="gender_id" class="gender" id="{{ $gender->id }}"  value="{{ $gender->id }}">
										<label for="{{ $gender->id }}"><span></span>{{$gender->name}}</label>
									@endforeach
								</div>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
							  	<div class="col-md-3">
								{{ Form::label('blood_group_id', 'Blood Group', array('class' => 'control-label ')) }}
								</div>
							  	<div class="col-md-7">				
								{{ Form::select('blood_group_id',$blood_groups, null, ['class' => 'form-control select_item blood_group', 'id' => 'blood_group_id']) }} 
								</div>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
							  	<div class="col-md-3">
								{{ Form::label('marital_status', 'Marital Status', array('class' => 'control-label ')) }}
								</div>
							  	<div class="col-md-7">				
								{{ Form::select('marital_status', $marital_status, null, ['class' => 'form-control select_item marital_status', 'id' => 'marital_status']) }} 
								</div>
							</div>
						</div>
					  	
					</div>

		  			<div class="col-md-6">

		  				<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('emp_img', 'Employee Image', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-5">
									<div class="dropzone" id="employee-image-upload"> </div>
								</div>
								<!-- <div class="col-md-4">
									<div style="position: absolute; height: 200px; width: 220px;" class="dropzone" id="employee-image-upload"> </div>
				  				</div> -->			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('emp_files', 'Employee Files', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-9">
									<div class="dropzone" id="employee-file-upload"> </div>
								</div>			
							</div>
						</div>

		  				<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('person_id', 'Files', array('class' => 'control-label ')) }}
								</div>			
							<div class="col-md-9">
						  	<div class="input-group control-group increment" >
					          <input type="file" name="filename[]" class="form-control">
					          <input type="text" name="name_of_file[]" class="form-control" placeholder="Ex: Resume">
					          <div class="input-group-btn"> 
					            <button class="btn btn-success add_docs" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
					          </div>
					        </div>
					        <div class="clone hide">
					          <div class="control-group input-group" style="margin-top:10px">
					            <input type="file" name="filename[]" class="form-control">
					            <input type="text" name="name_of_file[]" class="form-control" placeholder="Ex: Resume">
					            <div class="input-group-btn"> 
					              <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
					            </div>
			          			</div>
			        			</div>
		   		 				</div>		
							</div>
						</div>						  					

		  			</div>

				</div>
				
			</div>
			
		</div>

		<div class="tab-pane" id="official">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">

				<div class="row">

					<div class="col-md-6">

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('staff_type_id', 'Employee Type', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('staff_type_id',$staff_type, $selected_staff, ['class' => 'select_item form-control' ]) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('joined_date', 'Joining Date', array('class' => 'control-label  required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::text('joined_date', date('d-m-Y') ,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy']) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('employment_type_id', 'Job Type', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('employment_type_id',$job_type, $selected_employment, ['class' => 'select_item form-control' ]) !!}
								</div>			
							</div>
						</div>
						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('confirmation_period', 'Confirmation Period', array('class' => 'control-label')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('confirmation_period', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>
						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('branch_id', 'Branch', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('branch_id',$branch, $selected_branch, ['class' => 'select_item form-control' ]) !!}
								</div>			
							</div>
						</div>
						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('department_id', 'Department', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('department_id',$department, $selected_department, ['class' => 'select_item form-control' ]) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('designation_id', 'Designation', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('designation_id',$designation, $selected_designation, ['class' => 'select_item form-control' ]) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('shift_id', 'Shift', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('shift_id',$shift, $selected_shift, ['class' => 'select_item form-control' ]) !!}
								</div>			
							</div>
						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('license_type_id', 'License Type', array('class' => 'control-label')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('license_type_id',$license_type, null, ['class' => 'select_item form-control' ]) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('license_no', 'License Number', array('class' => 'control-label ')) }}
								</div>
								<div class="col-md-6">
									{{ Form::text('license_no', null, ['class'=>'form-control']) }}
								</div>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('employee_pan_no', 'PAN Number', array('class' => 'control-label ')) }}
								</div>
								<div class="col-md-6">
									{{ Form::text('employee_pan_no', null, ['class'=>'form-control']) }}
								</div>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('employee_aadhar_no', 'Aadhar Number', array('class' => 'control-label ')) }}
								</div>
								<div class="col-md-6">
									{{ Form::text('employee_aadhar_no', null, ['class'=>'form-control']) }}
								</div>
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('employee_passport_no', 'Passport Number', array('class' => 'control-label ')) }}
								</div>
								<div class="col-md-6">
									{{ Form::text('employee_passport_no', null, ['class'=>'form-control']) }}
								</div>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
		

		<div class="tab-pane" id="communication_address">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">

				<div class="row">

					<div class="col-md-6 present">

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-4">
									 <b>{!! Form::label('address', 'Present Address', ['class' => 'control-label']) !!}</b>
								</div>			
											
							</div>
						</div><br>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('contact_person', 'Name', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('contact_person', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>

						<!-- <div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-4">
									 {{ Form::label('mobile_no', 'Mobile No', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('mobile_no', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div> -->

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('address', 'Address', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'2 ','cols'=>'20']) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('land_mark', 'Land Mark', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('land_mark', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('pin', 'Pincode', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('pin', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>				

						<div class="form-group col-md-12">						
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('state_id', 'State', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('city_id', 'City', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('city_id', ['' => 'Select City'], null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}
								</div>			
							</div>
						</div>

					</div>


					<div class="col-md-6 permanent">

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 <b>{!! Form::label('same_as_address', 'Permanent Address', ['class' => 'control-label']) !!}</b>
								</div>			
								<div class="col-md-6">
									{{ Form::checkbox('same_as_address', '1', null, ['id' => 'same_as_address']) }}				
								<label for="same_as_address"><span></span>Same as Present Address</label>
								</div>			
							</div>
						</div><br>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('permanent_contact_person', 'Name', array('class' => 'control-label')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('permanent_contact_person', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>

						<!-- <div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-4">
									 {{ Form::label('mobile_no', 'Mobile No', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('mobile_no', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div> -->

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('permanent_address', 'Address', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::textarea('permanent_address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'2 ','cols'=>'20']) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('permanent_land_mark', 'Land Mark', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('permanent_land_mark', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('permanent_pin', 'Pincode', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{{ Form::text('permanent_pin', null, ['class'=>'form-control']) }}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('permanent_state_id', 'State', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('permanent_state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!}
								</div>			
							</div>
						</div>

						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-3">
									 {{ Form::label('permanent_city_id', 'City', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('permanent_city_id', ['' => 'Select City'], null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}
								</div>			
							</div>
						</div>


					</div>

				</div>			

			</div>
			
		</div>	

		<div class="tab-pane" id="education">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">
			  <div class="form-group">
				<div class="row">
					<div class="col-md-2">
						<label for="qualification">Qualification</label>
						{!! Form::text('qualification', null,['class' => 'form-control']) !!}
					</div>
					<div class="col-md-3">
						<label for="institution">Institution</label>
						{!! Form::text('institution', null,['class' => 'form-control']) !!}
					</div>
					<div class="col-md-2">
						<label for="education_state_id">State</label>
						{!! Form::select('education_state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!}
					</div>
					<div class="col-md-2">
						<label for="education_city_id">City</label>
						{!! Form::select('education_city_id',['' => 'Select City'], null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}
					</div>
					<div class="col-md-1">
						<label for="year">Year</label>
						{{ Form::text('year', null, ['class'=>'form-control numbers']) }} 
					</div>
					<div class="col-md-1">
						<label for="percentage">Percentage</label>
						{{ Form::text('percentage', null, ['class'=>'form-control numbers']) }}
						<span style="position: absolute; right: -5px; bottom: 5px;">%</span>
					</div>
					<div class="col-md-1 action_container">
					<label for=""> &nbsp;&nbsp;&nbsp; </label><br />
						<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>
					</div>
				</div>
			  </div>
			</div>
		</div>

		<div class="tab-pane" id="skill">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">
				<div class="form-group">
					<div class="row">
						<div class="col-md-5">
							<label for="skill">Skill Set </label>
							{!! Form::text('skill', null,['class' => 'form-control']) !!}
						</div>
						<div class="col-md-3">
							<label for="skill_level">Skill Level</label>
							{!! Form::text('skill_level', null,['class' => 'form-control','placeholder' => 'Out of 10']) !!}
						</div>
						<div class="col-md-3">
							<label for="experience">Experience In Years</label>
							{!! Form::text('experience', null,['class' => 'form-control' ]) !!}
						</div>

						<div class="col-md-1 action_container">
						<label for=""></label><br />
							<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="tab-pane" id="experience">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">
			  <div class="form-group">
				<div class="row">
					<div class="col-md-5">
						<label for="organization_name">Company Name</label>
						{!! Form::text('organization_name', null,['class' => 'form-control']) !!}
					</div>
					<div class="col-md-3">
						<label for="previous_joined_date">Joined Date</label>
						{!! Form::text('previous_joined_date', null,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy']) !!}
					</div>
					<div class="col-md-3">
						<label for="previous_relieved_date">Relieved Date</label>
						{!! Form::text('previous_relieved_date', null,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy']) !!}
					</div>
					<div class="col-md-1 action_container">
					<label for=""></label><br />
						<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>
					</div>
				</div>
			  </div>
			</div>
		</div>

		<div class="tab-pane" id="salary">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">

				<div class="row">

					<div class="col-md-5">
						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-6">
									 {{ Form::label('salary_scale_id', 'Salary Scale', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('salary_scale_id',$employee_salary_scale, null, ['class' => 'select_item form-control required' ]) !!}
								</div>			
							</div>
						</div>
						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-6">
									 {{ Form::label('payment_method_id', 'Payment Method', array('class' => 'control-label required')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::select('payment_method_id',$payment, null, ['class' => 'select_item form-control' ]) !!} 
								</div>			
							</div>
						</div>
						<div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-6">
									 {{ Form::label('ot_wage', 'OT Wage', array('class' => 'control-label ')) }}
								</div>			
								<div class="col-md-6">
									{!! Form::text('ot_wage', '0.00',['class' => 'form-control']) !!}
								</div>			
							</div>
						</div>				  	
					</div>

					<div class="col-md-7 payhead_container">
						
					</div>

				</div>		
			</div>
		</div>	

		<div class="tab-pane" id="banking">
			<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 420px;">

					<div class="form-group">
						<div class="row">
							<div class="col-md-2">							 
								 {{ Form::label('account_no', 'Account Number', array('class' => 'control-label required')) }}
							</div>			
							<div class="col-md-3">
								{!! Form::text('account_no', null,['class' => 'form-control','placeholder'=>'Enter Account No']) !!}
							</div>			
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2">							 
								{{ Form::label('ifsc', 'IFSC Code', array('class' => 'control-label required')) }}
							</div>			
							<div class="col-md-3">
								{!! Form::text('ifsc', null,['class' => 'form-control', 'placeholder'=>'Enter (or) Search Here...']) !!}
							</div>			
						</div>
					</div><br>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2">							
								{{ Form::label('micr', 'MICR Code', array('class' => 'control-label ')) }}
							</div>			
							<div class="col-md-3">
								{!! Form::text('micr', null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}
							</div>			
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2">							
								{{ Form::label('bank_name', 'Bank', array('class' => 'control-label ')) }}
							</div>			
							<div class="col-md-3">
								{!! Form::text('bank_name', null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}
							</div>			
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2">							
								{{ Form::label('state_bank', 'State', array('class' => 'control-label ')) }}
							</div>			
							<div class="col-md-3">
								{!! Form::text('state_bank', null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}
							</div>			
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2">							
								{{ Form::label('city_bank', 'City', array('class' => 'control-label ')) }}
							</div>			
							<div class="col-md-3">
								{!! Form::text('city_bank', null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}
							</div>			
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2">							
								{{ Form::label('bank_branch', 'Branch', array('class' => 'control-label ')) }}
							</div>			
							<div class="col-md-3">
								{!! Form::text('bank_branch', null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}

								{!! Form::hidden('bank_id', null,['class' => 'form-control','placeholder'=>'Auto fill','disabled']) !!}
							</div>			
						</div>
					</div>						
				
				
			</div>
		</div>

		<div class="save_btn_container">
			<button type="reset" class="btn btn-default clear cancel_transaction">Cancel</button>
			<button style="float:right" type="submit" class="btn btn-success">Submit</button>
			<button style="float:right" type="button" class="btn btn-success tab_save_btn">Next</button>
		</div>
		
	</div>

{!! Form::close() !!} 

</div>

<script type="text/javascript">
  
$(document).ready(function() {
	
	basic_functions();
	

	$(".add_docs").click(function(){ 
          var html = $(".clone").html();
          $(".increment").after(html);
    });

      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".control-group").remove();
      });

	$('.cancel_transaction').on('click', function(e) {
		e.preventDefault();
		$('.close_full_modal').trigger('click');		
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
						//designation.append("<option value=''></option>");
						$('.loader_wall_onspot').hide();
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
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

	$("input[name=same_as_address]").on('change', function(){
		var contact_person = $("input[name=contact_person]").val();
		var address = $("textarea[name=address]").val();
		var state_id = $("select[name=state_id]").val();
		var city_id = $("select[name=city_id]").val();
		var pin = $("input[name=pin]").val();
		var land_mark = $("input[name=land_mark]").val();

		//alert($("textarea[name=billing_address]").val(address));

		if($(this).is(':checked'))
		{
			$("input[name=permanent_contact_person]").val(contact_person).prop('disabled',true);
			$("textarea[name=permanent_address]").val(address).prop('disabled',true);
			$("select[name=permanent_state_id]").val(state_id).trigger('change');
			$("select[name=permanent_state_id]").prop('disabled',true);
			$("select[name=permanent_city_id]").append($("select[name=city_id]").clone().contents());
			$("select[name=permanent_city_id]").val(city_id).trigger('change');
			$("select[name=permanent_city_id]").prop('disabled',true);
			$("input[name=permanent_pin]").val(pin).prop('disabled',true);
			$("input[name=permanent_land_mark]").val(land_mark).prop('disabled',true);
		}
		else {
			$(".permanent").find("input,textarea,select").val("").prop('disabled',false);
			$("select[name=permanent_state_id]").val("").trigger("change");
			$("select[name=permanent_city_id]").empty();
			$("select[name=permanent_city_id]").append("<option value=''>Select City</option>");
			$("select[name=permanent_city_id]").val("").trigger("change");
		}
	});

	$('body').off('click', '.add_row').on('click', '.add_row', function() 
	{
		$('.select_item').each(function() { 
			var select = $(this);  
			if(select.data('select2')) { 
				select.select2("destroy"); 
			} 
		});

		var clone = $(this).closest('.row').clone();

		clone.find('select, input[type=text]').val("");
		
		clone.find('.date-picker').datepicker({
			rtl: false,
			orientation: "left",
			todayHighlight: true,
			autoclose: true
		});

		clone.find('.action_container').html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

		$(this).closest('.row').after(clone);
		$('.select_item').select2();
	});


	$('body').on('click', '.remove_row', function() {	
		$(this).closest('.row').remove();
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

	var employee_image_upload = new Dropzone("div#employee-image-upload", {
	  paramName: 'file',
	  url: "{{route('employee_image_upload')}}",
	  params: {
		  _token: '{{ csrf_token() }}'
	  },
	  dictDefaultMessage: "Drop or click to upload employee image",
	  clickable: true,
	  maxFilesize: 5, // MB
	  acceptedFiles: "image/*",
	  maxFiles: 10,
	  autoProcessQueue: false,
	  addRemoveLinks: true,
	  removedfile: function(file) {
		  file.previewElement.remove();
	  },
	  queuecomplete: function() {
		  employee_image_upload.removeAllFiles();
	  }
	});

	var employee_file_upload = new Dropzone("div#employee-file-upload", {
	  paramName: 'file',
	  url: "{{route('employee_file_upload')}}",
	  params: {
		  _token: '{{ csrf_token() }}'
	  },
	  dictDefaultMessage: "Drop or click to upload employee files",
	  clickable: true,
	  maxFilesize: 5, // MB
	  acceptedFiles: "image/*,.xlsx,.xls,.pdf,.doc,.docx",
	  maxFiles: 10,
	  autoProcessQueue: false,
	  addRemoveLinks: true,
	  removedfile: function(file) {
		  file.previewElement.remove();
	  },
	  queuecomplete: function() {
		  employee_file_upload.removeAllFiles();
	  }
  	});


	$('select[name=person_id]').each(function() {
	  $(this).prepend('<option value="0"></option>');
	  select_user($(this));
	});


	$('.side_panel').on('click', function() {
	  $('.slide_panel_bg').fadeIn();
	  $('.settings_panel').animate({ right: 0 });
	});

	$('.close_side_panel').on('click', function() {
	  $('.slide_panel_bg').fadeOut();
	  $('.settings_panel').animate({ right: "-25%" });
	});

	//$('body').find('.detailed_user').hide();


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





		$(".tab_save_btn").on('click', function(e) {
			e.preventDefault();
			var next_tab = $('.nav-tabs li a.active').parent().next('li:visible').find('a').attr('href');
			var next_other_tab = $('.nav-tabs li a.active').parent().next('li:visible').next('li:visible').find('a').attr('href');

			var validator = $(".validateform").validate();
			
			if(validator.checkForm() == true) {
				$('.form-group').removeClass('has-error');
				$('.help-block').remove();
				if(next_tab) {
					$('a[href="'+next_tab+'"]')[0].click();
					//console.log(next_other_tab);
					/*if(next_other_tab == undefined) {
						$(this).text("Save");
					}*/
					return false;
				}

				/*if($(".validateform").valid()) {
					$(".validateform").submit();
				}*/
				} else {
					$('.form-group').addClass('has-error');
					validator.showErrors();

				}
		});




	$('.validateform').validate({
		//ignore: [],
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			person_id: { 
				required: true,
				remote: {
					url: '{{ route('check_employee') }}',
					type: "post",
					data: {
					 _token :$('input[name=_token]').val()
					}
				} 
			},
			staff_type_id: { required: true },
			employee_first_name: { required: true },
			employee_code: { required: true },
			payment_method_id: { required: true },
			gender_id: { required: true },
			phone_no: { required: true },
			employee_email: { required: true },
			joined_date: { required: true },
			employment_type_id: { required: true },
			branch_id: { required: true },
			department_id: { required: true },
			designation_id: { required: true },
			shift_id: { required: true },
			salary_scale_id: { required: true},
		},

		messages: {
			person_id: { 
				required: "Person is required.",
				remote: "Person already exists!" 
			},
			staff_type_id: { required: "Employee Type is required" },
			employee_first_name: { required: "First Name is required." },
			employee_code: { required: "Code is required." },
			payment_method_id: { required: "Payment Method is required." },
			gender_id : { required: "Gender is required." },
			phone_no : { required: "Mobile No. is required." },
			employee_email : { required: "Email Id is required." },
			joined_date : { required: "Joined Date is required." },
			employment_type_id : { required: "Job Type is required." },
			branch_id : { required: "Branch is required." },
			department_id : { required: "Department is required." },
			designation_id : { required: "Designation is required." },
			shift_id : { required: "Shift is required." },
			salary_scale_id: {  required: "Salary Scale is required." },
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
			$('.loader_wall_onspot').show();

			$.ajax({
			url: '{{ route('employees.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				staff_type_id: $('select[name=staff_type_id]').val(),
				person_id: $('select[name=person_id]').val(),
				employee_code: $('input[name=employee_code]').val(),
				title_id: $('select[name=title]').val(),
				first_name: $('input[name=employee_first_name]').val(),
				last_name: $('input[name=employee_last_name]').val(),
				email: $('input[name=employee_email]').val(),
				phone_no: $('input[name=phone_no]').val(),
				gender_id: $('input[name=gender_id]').val(),
				blood_group_id: $('select[name=blood_group_id]').val(),
				shift_id: $('select[name=shift_id]').val(),
				marital_status: $('select[name=marital_status]').val(),
				pan_no: $('input[name=employee_pan_no]').val(),
				aadhar_no: $('input[name=employee_aadhar_no]').val(),
				passport_no: $('input[name=employee_passport_no]').val(),
				license_type_id: $('select[name=license_type_id]').val(),
				license_no: $('input[name=license_no]').val(),

				joined_date: $('input[name=joined_date]').val(),
				employment_type_id: $('select[name=employment_type_id]').val(),
				confirmation_period: $('input[name=confirmation_period]').val(),
				branch_id: $('select[name=branch_id]').val(),
				department_id: $('select[name=department_id]').val(),
				designation_id: $('select[name=designation_id]').val(),

				contact_person: $('input[name=contact_person]').val(),
				address: $('textarea[name=address]').val(),
				city_id: $('select[name=city_id]').val(),
				pin: $('input[name=pin]').val(),
				land_mark: $('input[name=land_mark]').val(),
				permanent_contact_person: $('input[name=permanent_contact_person]').val(),
				permanent_address: $('textarea[name=permanent_address]').val(),
				permanent_city_id: $('select[name=permanent_city_id]').val(),
				permanent_pin: $('input[name=permanent_pin]').val(),
				permanent_land_mark: $('input[name=permanent_land_mark]').val(),

				filename: $("input[name=filename]").map(function() { 
						return this.value; 
					}).get(),
				
				qualification: $("input[name=qualification]").map(function() { 
						return this.value; 
					}).get(),
				institution: $("input[name=institution]").map(function() { 
						return this.value; 
					}).get(),
				education_city_id: $("select[name=education_city_id]").map(function() { 
						return this.value; 
					}).get(),
				year: $("input[name=year]").map(function() { 
						return this.value; 
					}).get(),
				percentage: $("input[name=percentage]").map(function() { 
						return this.value; 
					}).get(),

				skill: $("input[name=skill]").map(function() { 
						return this.value; 
					}).get(),
				skill_level: $("input[name=skill_level]").map(function() { 
						return this.value; 
					}).get(),
				experience: $("input[name=experience]").map(function() { 
						return this.value; 
					}).get(),

				
				organization_name: $("input[name=organization_name]").map(function() { 
						return this.value;
					}).get(),
				previous_joined_date: $("input[name=previous_joined_date]").map(function() { 
						return this.value;
					}).get(),
				previous_relieved_date: $("input[name=previous_relieved_date]").map(function() { 
						return this.value;
					}).get(),

				salary_scale_id: $('select[name=salary_scale_id]').val(),
				payment_method_id: $('select[name=payment_method_id]').val(),
				ot_wage: $('input[name=ot_wage]').val(),

				pay_head_id: $("input[name=pay_head_id]").map(function() { 
						return this.value; 
					}).get(),
				payhead_value: $("input[name=value]").map(function() { 
						return this.value; 
					}).get(),
				bank_id: $('input[name=bank_id]').val(),
				bank_name: $('input[name=bank_name]').val(),
				account_no: $('input[name=account_no]').val(),
				bank_branch: $('input[name=bank_branch]').val(),
				ifsc: $('input[name=ifsc]').val(),
				micr: $('input[name=micr]').val(),
				},
			success:function(data, textStatus, jqXHR) {

				var html = ``;

				if(data.status == '1') {

					employee_image_upload.on("sending", function(file, xhr, response) {
						response.append("id", data.data.id);
					});

					employee_file_upload.on("sending", function(file, xhr, response) {
							response.append("id", data.data.id);
						});

					employee_image_upload.processQueue();
					employee_file_upload.processQueue();

					html += `<tr role="row" class="odd">
					<td>`+data.data.name+`</td>
					<td>`+data.data.code+`</td>
					<td>`+data.data.phone_no+`</td>
					<td>`+data.data.email+`</td>
					<td>`+data.data.blood_group+`</td>
					<td>`+data.data.gender+`</td>
					<td>
					<a href="{{url('hrm/employees')}}/`+data.data.id+`" data-id="`+data.data.id+`" class="grid_label action-btn show-icon show"><i class="fa li_eye"></i></a>					
					</td></tr>`;

					call_back(html, `add`, data.message);

					$('.close_full_modal').trigger('click');
					$('.loader_wall_onspot').hide();

				} else if(data.status == '0') {
					$('.alert-danger').text(data.message[Object.keys(data.message)[0]]);
					$('.alert-danger').show();
					$('.loader_wall_onspot').hide();

					setTimeout(function() {
						$('.alert-danger').text("");
						$('.alert-danger').fadeOut();
					}, 38000);
				}

				
				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

  });
</script> 
