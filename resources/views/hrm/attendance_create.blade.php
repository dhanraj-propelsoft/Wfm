<div class="modal-header">
	<h4 class="modal-title float-right">Add Attendance</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform attendance_form'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		<div class="form-group">
			{!! Form::label('department_id', 'Department', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::select('department_id', $departments, null,['class' => 'select_item']) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('employee_id', 'Employee', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::select('employee_id', ['' => 'Select Employee'], null,['class' => 'select_item']) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('attendance_type_id', 'Type', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				<select name="attendance_type_id" class="select_item">
				<option value="">Select Type</option>
				@foreach($attendance_types as $attendance_type)
					<option data-status="{{$attendance_type->attendance_status}}" value="{{$attendance_type->id}}">{{$attendance_type->name}}</option>
				@endforeach
			  </select>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('shift_id', 'Shift', ['class' => 'control-label col-md-3 required']) !!}
			<div class="col-md-12">
				<select name="shift_id" class="select_item">
				<option value="">Select Shift</option>
				@foreach($shifts as $shift)
					<option data-start="{{$shift->from_time}}" data-end="{{$shift->to_time}}" value="{{$shift->id}}">{{$shift->name}}</option>
				@endforeach
			  </select>
			</div>
		</div>

		<div class="row">
		<div class="col-md-6">
			{!! Form::label('in_time', 'In-Time', ['class' => 'control-label col-md-12 required']) !!}
			<div class="col-md-12">
				{!! Form::text('in_time', null,['class' => 'form-control timepicker-no-seconds']) !!}
			</div>
		</div>
		<div class="col-md-6">
			{!! Form::label('out_time', 'Out-Time', ['class' => 'control-label col-md-12 required']) !!}
			<div class="col-md-12">
				{!! Form::text('out_time', null,['class' => 'form-control timepicker-no-seconds']) !!}
			</div>
		</div>
		</div>

	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

<script>
$(document).ready(function()
{

	 basic_functions();

	 $('.attendance_form input[name=in_time], .attendance_form input[name=out_time]').val("");

	$( "select[name=department_id]" ).change(function () {
		var employee = $( "select[name=employee_id]" );
		var id = $(this).val();
		employee.val("");
		employee.select2('val', '');
		employee.empty();
		employee.append("<option value=''>Select Employee</option>");
		if(id != "") {
		$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_employee_by_department') }}',
				 type: 'get',
				 data: {
					department_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {

						var result = data.result;
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

	 $('.attendance_form select[name=shift_id]').on('change', function() {
			if($(this).val() != "") {
				$('.attendance_form input[name=in_time]').val(time_convertion($(this).find('option:selected').data('start')));
				$('.attendance_form input[name=out_time]').val(time_convertion($(this).find('option:selected').data('end')));
			} else {
				$('.attendance_form input[name=in_time]').val("");
				$('.attendance_form input[name=out_time]').val("");
			}
		});

	 function time_convertion (time) {
	  // Check correct time format and split into components
	  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

	  if (time.length > 1) { // If time format correct
		time = time.slice (1);  // Remove full string match value
		time[3] = "";
		time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
		time[0] = +time[0] % 12 || 12; // Adjust hours
	  }
	  return time.join (''); // return adjusted time or original string
	}

	$('.attendance_form').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			employee_id: { required: true },
			shift_id: { required: true },
			attendance_type_id: { required: true },
			in_time: { required: true },
			out_time: { required: true },              
		},

		messages: {
			employee_id: { required: "Employee is required." },
			shift_id: { required: "Shift is required." },
			attendance_type_id: { required: "Attendance type is required." },
			in_time: { required: "In Iime is required." },
			out_time: { required: "Out Time is required." }, 
			//parent_department: { required: "Parent Department Name is required." },                
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
			url: '{{ route('attendance_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				employee_id: $('.attendance_form select[name=employee_id]').val(),
				shift_id:$('.attendance_form select[name=shift_id]').val(),
				attendance_type_id: $('.attendance_form select[name=attendance_type_id]').val(),
				in_time:$('.attendance_form input[name=in_time]').val(),
				out_time:$('.attendance_form input[name=out_time]').val(),
				date: '{{$date}}',	
				status: $('.attendance_form select[name=attendance_type_id] option:selected').data('status')
				},
			success:function(data, textStatus, jqXHR) {

				var options = ``;

				for(i in data.data.attendance_type_list) {
					options = `<option data-color="`+data.data.attendance_type_list[i].color+`" value="`+data.data.attendance_type_list[i].id+`">`+data.data.attendance_type_list[i].name+`</option>`;
				}

					call_back(`<tr> <td width="1"><div style=""><input id="`+data.data.employee_name+`" class="item_check" name="employee" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.employee_name+`"><span></span></label></div></td>
								<td>`+data.data.employee_name+`</td>
								<td>`+data.data.date+`</td>
								<td>`+data.data.shift+`</td>
								<td>`+data.data.in_time+`</td>
								<td>`+data.data.out_time+`</td>
								<td>
								<label class="grid_label badge status" style="background:`+data.data.color+` ; color: #fff;">`+data.data.attendance_type+`</label>
								<select style="display:none" id="`+data.data.employee_id+`" class="active_status form-control">
								<option value="">Select Status</option>`+options+`
								</select>
								</td>
								<td><a data-id="`+data.data.id+`" class="grid_label action-btn delete-icon delete" style=""><i class="fa fa-trash-o"></i></a>
											</td></tr>`, `add`, data.message);
				

				$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

});
</script>