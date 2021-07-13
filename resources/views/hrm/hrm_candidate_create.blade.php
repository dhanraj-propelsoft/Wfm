<div class="modal-header">
	<h4 class="modal-title float-right">Add Candidates</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}


<div class="modal-body">
	<div class="form-body">
		
		<div class="form-inline">
			<div class="row">
				<div class="col-md-4 ">
					<div class="form-group"> 
				{{ Form::label('name', 'Name', array('class' => 'control-label required')) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				{{ Form::text('name', null, ['class' => 'form-control ', 'id' => 'person_id']) }}
					</div>
				
				<!-- <div class="content"></div> -->
			  </div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('max_education', 'Max Education', array('class' => 'control-label required')) !!}
						{!! Form::text('max_education', null ,['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('designations', 'Applied For', array('class' => 'control-label  required')) !!}
						{{ Form::select('designations', $designations, null, ['class'=>'form-control select_item']) }}
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
						{!! Form::text('phone_number', null,['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('email_id', 'Email ID', array('class' => 'control-label required')) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						{!! Form::text('email_id', null,['class' => 'form-control']) !!}

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
				
			</div>	
		</div>
		<br>
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
						{!! Form::text('interview_on_first', null,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('interview_by_first', 'Interview By', array('class' => 'control-label ')) !!}
						{{ Form::select('interview_by_first', $employees, null, ['class'=>'form-control select_item']) }}

					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group">						 
			{!! Form::label('comments_first', 'Comments', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::textarea('comments_first', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
			</div>
		</div>
		<br>
		<div class="form-inline">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('interview_on_second', 'Interview On', array('class' => 'control-label')) !!}
						{!! Form::text('interview_on_second', null,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}

					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('interview_by_second', 'Interview By', array('class' => 'control-label')) !!}
						{{ Form::select('interview_by_second', $employees, null, ['class'=>'form-control select_item']) }}

					</div>
				</div>
			</div>	
		</div>	
	
		<div class="form-group">						 
			{!! Form::label('comments_second', 'Comments', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::textarea('comments_second', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
			</div>
		</div>	
		<div class="form-inline">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('status', 'Status', array('class' => 'control-label')) !!}
						{{ Form::select('status', $recruitment_statuses, null, ['class'=>'form-control select_item']) }}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('last_modified', 'Last Modified', array('class' => 'control-label')) !!}
						{!! Form::text('last_modified', null,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('photo', 'Photo', array('class' => 'control-label col-md-4')) !!}
						{!! Form::file('photo', null,['class' => 'form-control']) !!}

					</div>
				</div>
			</div>	
		</div>	
	
		<div class="form-inline">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">

						{!! Form::label('attachments_1', 'Attachments', array('class' => 'control-label ')) !!}&nbsp;&nbsp;
						{!! Form::text('attachments_1', null,['class' => 'form-control']) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						{!! Form::file('attachments_1', null,['class' => 'form-control']) !!}

					</div>
				</div>
			</div>	
		</div>
		
		<div class="form-inline">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{!! Form::label('attachments_2', 'Attachments', array('class' => 'control-label ')) !!}&nbsp;&nbsp;
						{!! Form::text('attachments_2', null,['class' => 'form-control']) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						{!! Form::file('attachments_2', null,['class' => 'form-control']) !!}

					</div>
				</div>
				
			</div>	
		</div>
		
		<div class="form-inline">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{!! Form::label('attachments_3', 'Attachments', array('class' => 'control-label')) !!}&nbsp;&nbsp;
						{!! Form::text('attachments_3', null,['class' => 'form-control']) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

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


	   /*$('select[name=person_id]').each(function() {
	  $(this).prepend('<option value="0"></option>');
	  select_user($(this));
	});*/
	$('#observed_date').datepicker("setDate", new Date());

	
	});



	

$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			/*person_id: { 
				required: true,
				remote: {
					url: '{{ route('check_employee') }}',
					type: "post",
					data: {
					 _token :$('input[name=_token]').val()
					}
				} 
			},*/
			name: { required: true },
			max_education: { required: true },
			designations: { required: true },
			phone_number: { required: true },
			email_id: { required: true },
			skill_set_1: { required: true },
		},

		messages: {
			/*person_id: { 
				required: "Person is required.",
				remote: "Person already exists!" 
			},*/
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
			url: '{{ route('candidate.store') }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				/*person_id: $('select[name=person_id]').val(),*/
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


				//console.log(data.data);

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
				else if(data.data.status == 5) {
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
					<td><input id="`+data.data.id+`" class="item_check" name="vacancy" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					
					<td></td>

					<td>`+data.data.name+`</td>
					<td>`+data.data.contact+`</td>
					<td>`+data.data.skill+`</td>
					<td>`+data.data.applied_designation+`</td>
					<td>`+data.data.applied_on+`</td>
					
					
					<td>

						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
							<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option value="1">New</option>
							<option value="2">Progress</option>
							<option value="3">Passed</option>
							<option value="4">Failed</option>
							<option value="5">Offered</option>
							<option value="6">Recruited</option>
							<option value="7">Discarded</option>
							<option value="8">On hold</option>
						</select>
					</td>
					<td>
					<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`, `add`, data.message);
				
				$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>