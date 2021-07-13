<div class="modal-header">
	<h4 class="modal-title float-right">Add Shift</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('name', 'Shift Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('from_time', 'From Time', array('class' => 'control-label col-md-3 required')) !!}

			<div class="col-md-12">
				{!! Form::text('from_time', null,['class' => 'form-control timepicker-no-seconds']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('to_time', 'To Time', array('class' => 'control-label col-md-2 required')) !!}

			<div class="col-md-12">
				{!! Form::text('to_time', null,['class' => 'form-control timepicker-no-seconds']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('total_hours', 'Total Hours', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::text('total_hours', null,['class' => 'form-control', 'readonly'=>'ture','id'=>'hours']) !!}
			</div>
		</div>
		<!-- <div class="form-group">
			{!! Form::label('attendance_settings_id', 'Attendance Settings Name', array('class' => 'control-label col-md-6 required')) !!}
		
			<div class="col-md-12">
				{{Form::select('attendance_settings_id', $attendance_setting, null, ['class'=>'form-control select_item'])}}
			</div>
		</div> -->
		<div class="form-group">
			{!! Form::label('break_id', 'Break Name', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">

				@foreach($work_break as $work)
					<input type="checkbox" name="break_id" id="{{ $work->break_id }}"  value="{{ $work->break_id }}">
					<label for="{{ $work->break_id }}"><span></span>{{ $work->break_name }}</label>
					
				@endforeach
				<!-- {{Form::select('break_id', $work_break, null, ['class'=>'form-control select_item'])}} -->
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
	$(document).ready(function() {

	   basic_functions();

	   $("#to_time, #from_time").on('change',function() {

		var startTime = moment($('#from_time').val(),"h:mm A");
		var endTime = moment($('#to_time').val(),"h:mm A");

		if(startTime.format("A") == "PM" && endTime.format("A") == "AM") {
			startTime.subtract(1, 'day');
		}

		var time = moment.duration(endTime.diff(startTime));
		var hours = Math.floor(time.asHours());
		var mins = Math.floor(time.asMinutes()) - hours * 60;

		$('#hours').val(hours+"."+ parseFloat((mins * 100) / 60).toFixed());

		});
	});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true },               
			from_time: { required: true },
			to_time: { required: true },
			total_hours: { required: true },
			attendance_settings_id: { required: true },               
		},

		messages: {
			name: { required: "Shift Name is Required." },                
			from_time: { required: "From Time is Required." },
			to_time: { required: "To Time is Required." },
			total_hours: { required: "Total Hours is Required." },
			attendance_settings_id: { required: "Attendance Settings Name is Required." },               
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
			url: '{{ route('shifts.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				from_time: $('input[name=from_time]').val(),
				to_time: $('input[name=to_time]').val(),
				total_hours: $('input[name=total_hours]').val(),
				/*attendance_settings_id: $('select[name=attendance_settings_id]').val(),*/
				break_id: $("input[name=break_id]:checked").map(function() { 
						return this.value; 
					}).get()
			},   
			success:function(data, textStatus, jqXHR) {

				var break_html = ``;

				if((data.data.breaks).length > 0) {
					var breaks = data.data.breaks;
					for(i in breaks) {
						break_html += `<span style='width:100%; padding:10px; float:left; white-space: nowrap;'><b>`+breaks[i].break_name+`</b>   &nbsp;&nbsp;&nbsp;&nbsp;  `+breaks[i].start_time+` - `+breaks[i].end_time+`</span><br>`;
					}
				}	

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="shift" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.from_time+`</td>
					<td>`+data.data.to_time+`</td>
					<td>`+data.data.total_hours+`</td>
					<td>`+break_html+`</td>
					<!-- <td>`+data.data.attendance_settings_id+`</td> -->
					<td>
						<label class="grid_label badge badge-success status">Active</label>
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option value="1">Active</option>
							<option value="0">In-active</option>
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