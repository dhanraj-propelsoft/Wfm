<div class="modal-header">
	<h4 class="modal-title float-right">Edit Vacancy</h4>
</div>

	{!! Form::model($candidate,[
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
	  {!! Form::hidden('id', null) !!}

		<div class="form-inline">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('name', 'Name', ['class' => 'control-label  required']) !!}
						{!! Form::text('name', null, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('max_education', 'Max Education', array('class' => 'control-label required')) !!}
						{!! Form::text('max_education', $candidate->education ,['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('designations', 'Applied For', array('class' => 'control-label  required')) !!}
						{{ Form::select('designations', $designations, $candidate->designation_id, ['class'=>'form-control select_item']) }}
					</div>
				</div>
			</div>	
		</div>
		<div class="form-inline">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('applied_on', 'Applied On', array('class' => 'control-label')) !!}
						{!! Form::text('applied_on', null,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy','id'=>'observed_date']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('phone_number', 'Phone Number', ['class' => 'control-label required']) !!}
						{!! Form::text('phone_number', $candidate->contact_number,['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label
							('email_id', 'Email ID', array('class' => 'control-label required')) !!}
						{!! Form::text('email_id', $candidate->email,['class' => 'form-control']) !!}

					</div>
				</div>
			</div>	
		</div>
		<div class="form-inline">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('experience', 'Experience', array('class' => 'control-label')) !!}
						{!! Form::text('experience', null,['class' => 'form-control']) !!}

					</div>
				</div>
				<div class="col-md-8">
					<div class="form-group">
						{!! Form::label('photo', 'Photo', array('class' => 'control-label col-md-4')) !!}
						{!! Form::file('photo', null,['class' => 'form-control']) !!}

					</div>
				</div>
			</div>	
		</div>
		<div class="form-inline">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('skill_set_1', 'Skill Set', array('class' => 'control-label col-md-4 required')) !!}
						{!! Form::text('skill_set_1', null,['class' => 'form-control']) !!}

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('skill_set_2', 'Skill Set', array('class' => 'control-label col-md-4')) !!}
						{!! Form::text('skill_set_2', null,['class' => 'form-control']) !!}

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('skill_set_3', 'Skill Set', array('class' => 'control-label col-md-4')) !!}
						{!! Form::text('skill_set_3', null,['class' => 'form-control']) !!}
					</div>
				</div>
			</div>	
		</div>
		<div class="form-inline">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('interview_on_first', 'Interview On', array('class' => 'control-label')) !!}
						{!! Form::text('interview_on_first', $candidate->tech_interview_on,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('interview_by_first', 'Interview By', array('class' => 'control-label ')) !!}
						{{ Form::select('interview_by_first', $employees, $candidate->tech_employee_id, ['class'=>'form-control select_item']) }}

					</div>
				</div>
			</div>	
		</div>
		<div class="form-group">						 
			{!! Form::label('comments_first', 'Comments', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::textarea('comments_first', $candidate->tech_comments, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
			</div>
		</div>
		<div class="form-inline">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('interview_on_second', 'Interview On', array('class' => 'control-label')) !!}
						{!! Form::text('interview_on_second', $candidate->hr_interview_on,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('interview_by_second', 'Interview By', array('class' => 'control-label')) !!}
						{{ Form::select('interview_by_second', $employees, $candidate->hr_employee_id, ['class'=>'form-control select_item']) }}

					</div>
				</div>
			</div>	
		</div>	
		<div class="form-group">						 
			{!! Form::label('comments_second', 'Comments', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::textarea('comments_second', $candidate->hr_comments, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
			</div>
		</div>	
		<div class="form-inline">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('status', 'Status', array('class' => 'control-label')) !!}
						{{ Form::select('status', $recruitment_statuses, $candidate->recruitment_status, ['class'=>'form-control select_item']) }}
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('last_modified', 'Last Modified', array('class' => 'control-label')) !!}
						{!! Form::text('last_modified', null,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}
					</div>
				</div>
			</div>	
		</div>	
		<div class="form-inline">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('attachments_1', 'Attachments', array('class' => 'control-label col-md-4')) !!}
						{!! Form::file('attachments_1', null,['class' => 'form-control']) !!}

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('attachments_2', 'Attachments', array('class' => 'control-label col-md-4')) !!}
						{!! Form::file('attachments_2', null,['class' => 'form-control']) !!}

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('attachments_3', 'Attachments', array('class' => 'control-label col-md-4')) !!}
						{!! Form::file('attachments_3', null,['class' => 'form-control']) !!}


					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Save</button>
	<button type="submit" class="btn btn-success">Discard</button>
	<button type="submit" class="btn btn-success">Employee</button>

</div>
	
{!! Form::close() !!}
				
<script>
  $(document).ready(function() {
	 basic_functions();
  });
  $('.validateform').validate({
	errorElement: 'span', //default input error message container
	errorClass: 'help-block', // default input error message class
	focusInvalid: false, // do not focus the last invalid input
	rules: {
			name: { required: true },
			max_education: { required: true },
			designations: { required: true },
			phone_number: { required: true },
			email_id: { required: true },
			skill_set_1: { required: true },                
	},

	messages: {
			name: { required: "Name is required." },
			max_education: { required: "Education is required." },
			designations:  { required: "Designation is required" },
			phone_number: { required: "Phone Number is required" },
			email_id: { required: "Email is required" },
			skill_set_1 : { required: "Skill is required" },              
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
			 url: '{{ route('candidate.update') }}',
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),

				name: $('input[name=name]').val(),
				max_education: $('input[name=max_education]').val(),
				designations: $('select[name=designations]').val(),
				applied_on: $('input[name=applied_on]').val(),
				phone_number: $('input[name=phone_number]').val(),
				email_id: $('input[name=email_id]').val(),
				experience: $('input[name=experience]').val(),
				skill_set_1: $('input[name=skill_set_1]').val(),
				skill_set_2: $('input[name=skill_set_2]').val(),
				skill_set_3: $('input[name=skill_set_3]').val(),
				interview_on_first: $('input[name=interview_on_first]').val(),
				interview_by_first: $('input[name=interview_by_first]').val(),
				comments_first: $('input[name=comments_first]').val(),
				interview_on_second: $('input[name=interview_on_second]').val(),
				interview_by_second: $('input[name=interview_by_second]').val(),
				comments_second: $('input[name=comments_second]').val(),
				status: $('select[name=status]').val(),
				last_modified: $('input[name=last_modified]').val(),
				},
			 success:function(data, textStatus, jqXHR) {

			 	/*var active_selected = "";
				var inactive_selected = "";*/
				

				if(data.data.status == 1) 
				{
					active_selected = "selected";
					selected_text = "New";
					selected_class = "badge-default";
				}
				else if(data.data.status == 2) 
				{
					active_selected = "selected";
					selected_text = "Progress";
					selected_class = "badge-success";
				}
				else if(data.data.status == 3) 
				{
					active_selected = "selected";
					selected_text = "Passed";
					selected_class = "badge-warning";
				}
				else if(data.data.status == 4) 
				{
					active_selected = "selected";
					selected_text = "Failed";
					selected_class = "badge-danger";
				}
				else if(data.data.status == 5) 
				{
					active_selected = "selected";
					selected_text = "Offered";
					selected_class = "badge-default";
				}
				else if(data.data.status == 6) 
				{
					active_selected = "selected";
					selected_text = "Recruited";
					selected_class = "badge-primary";
				}
				else if(data.data.status == 7)
				 {
					active_selected = "selected";
					selected_text = "Discarded";
					selected_class = "badge-info";
				} 
				else if(data.data.status == 8)
				 {
					inactive_selected = "selected";
					var selected_text = "On hold";
					var selected_class = "badge-warning";
				}

				call_back(`<tr role="row" class="odd">
						<td><input id="`+data.data.id+`" class="item_check" name="team" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
						</td>
						<td></td>
						<td>`+data.data.name+`</td>
						<td>`+data.data.contact+`</td>
						<td>`+data.data.skill+`</td>
						<td>`+data.data.applied_designation+`</td>
						<td>`+data.data.applied_on+`</td>
						<td>
							<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
							<select style="display:none" id="{{ $candidate->id }}" class="active_status form-control">
							<option @if($candidate->recruitment_status == 1) selected="selected" @endif value="1">New</option>
							<option @if($candidate->recruitment_status == 2) selected="selected" @endif value="2">Progress</option>
							<option @if($candidate->recruitment_status == 3) selected="selected" @endif value="3">Passed</option>
							<option @if($candidate->recruitment_status == 4) selected="selected" @endif value="4">Failed</option>
							<option @if($candidate->recruitment_status == 5) selected="selected" @endif value="5">Offered</option>
							<option @if($candidate->recruitment_status == 6) selected="selected" @endif value="6">Recruited</option>
							<option @if($candidate->recruitment_status == 7) selected="selected" @endif value="7">Discarded</option>
							<option @if($candidate->recruitment_status == 8) selected="selected" @endif value="8">On hold</option>
						</select>
							
						</td>
						<td>
						  <a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
						  <a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
						</td></tr>`, `edit`, data.message, data.data.id);

				$('.loader_wall_onspot').hide();

				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>