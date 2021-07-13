<div class="modal-header">
	<h4 class="modal-title float-right">Add Attendance Setting</h4>
</div>

	{!!Form::model($attendance, [
		'class' => 'form-horizontal validateform'
	]) !!}                                       
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		<div class="row">
			<div class="form-group col-md-6">
				{!! Form::label('name', 'Name', array('class' => 'control-label col-md-6 required')) !!}

				<div class="col-md-12">
					{!! Form::text('name', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group col-md-6">
				{!! Form::label('standard_working_hours', 'Standard Working Hours', array('class' => 'control-label col-md-10')) !!}

				<div class="col-md-12">
					{!! Form::text('standard_working_hours', null,['class' => 'form-control timepicker-24']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				{!! Form::label('min_hours_for_full_day', 'Minimum Hours for Full Day', array('class' => 'control-label col-md-12')) !!}

				<div class="col-md-12">
					{!! Form::text('min_hours_for_full_day', null,['class' => 'form-control timepicker-24']) !!}
				</div>
			</div>
			<div class="form-group col-md-6">
				{!! Form::label('min_hours_for_half_day', 'Minimum Hours for Half Day', array('class' => 'control-label col-md-12')) !!}

				<div class="col-md-12">
					{!! Form::text('min_hours_for_half_day', null,['class' => 'form-control timepicker-24']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				{!! Form::label('min_hours_for_official_half_day', 'Minimum Hours for Official Half Day', array('class' => 'control-label col-md-12')) !!}

				<div class="col-md-12">
					{!! Form::text('min_hours_for_official_half_day', null,['class' => 'form-control timepicker-24']) !!}
				</div>
			</div>
			<div class="form-group col-md-6">
				{!! Form::label('grace_time', 'Grace Time', array('class' => 'control-label col-md-8')) !!}

				<div class="col-md-12">
					{!! Form::text('grace_time', null,['class' => 'form-control timepicker-24']) !!}
				</div>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('deduction_days', 'Deduction Days', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::text('deduction_days', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
			{!! Form::checkbox('cancel_deduction', 1, null,['id' => 'cancel_deduction']) !!}
			<label for="cancel_deduction"><span></span>Cancel Deduction, If Total Hours greater than Standard Working Hours.</label>
		</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
			{!! Form::label('deduct_from', 'Deduct From', array('class' => 'control-label')) !!}
			</div>

			<div class="col-md-12">
			{!! Form::radio('deduct_from', '0', false, ['id' => 'cl']) !!}
			<label for="cl" class = 'control-label col-md-2'><span></span>CL</label>

			{!! Form::radio('deduct_from', '1', false, ['id' => 'lop']) !!}
			<label for="lop" class = 'control-label col-md-2'><span></span>LOP</label>
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
	});
	

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true },               
		},

		messages: {
			name: { required: "Attendance Setting Name is Required." },                 
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
			url: '{{ route('attendance_setting.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				name: $('input[name=name]').val(),
				standard_working_hours: $('input[name=standard_working_hours]').val(),
				min_hours_for_full_day: $('input[name=min_hours_for_full_day]').val(),
				min_hours_for_half_day: $('input[name=min_hours_for_half_day]').val(),
				min_hours_for_official_half_day: $('input[name=min_hours_for_official_half_day]').val(),
				grace_time: $('input[name=grace_time]').val(),
				deduction_days: $('input[name=deduction_days]').val(),
				cancel_deduction: $("input[name=cancel_deduction]:checked").val(),
				deduct_from: $("input[name=deduct_from]:checked").val(),
			},   
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td>`+data.data.name+`</td>
					<td>`+data.data.standard_working_hours+`</td>
					<td>`+data.data.min_hours_for_full_day+`</td>
					<td>`+data.data.min_hours_for_half_day+`</td>
					<td>`+data.data.min_hours_for_official_half_day+`</td>
					<td>`+data.data.grace_time+`</td>
					<td>`+data.data.deduction_days+`</td>
					<td><label class="grid_label badge badge-success">`+data.data.cancel_deduction+`</label></td>
					<td><label class="grid_label badge badge-success">`+data.data.deduct_from+`</label>
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