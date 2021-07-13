<div class="modal-header">
	<h4 class="modal-title float-right">Edit Payroll Frequency</h4>
</div>

	{!! Form::model($payroll_frequency,['class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{{ Form::hidden('id',null) }}

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('name', 'Name', array('class' => 'control-label required')) !!}
					{!! Form::text('name', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('code', 'Code', array('class' => 'control-label col-md-4 required')) !!}
					{!! Form::text('code', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('frequency_type', 'Frequenchy Type', ['class' => 'control-label']) !!}
				
					{!!	Form::select('frequency_type', ['' => 'Select','0'=>'Daily','1'=>'Weekly','2'=>'Monthly'], null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>

			<div class="col-md-4 month" @if($payroll_frequency->frequency_type != 2)  style="display:none;" @endif>
				<div class="form-group " >
					{!! Form::label('salary_period', 'Monthly On', ['class' => 'control-label']) !!}
			
					{!!	Form::select('salary_period', ['' => 'Day','1'=>'First','2'=>'Second','3'=>'Third','4'=>'Fourth','0'=>'Last'], null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>

			<div class="col-md-4 week" @if($payroll_frequency->week_day_id == null)  style="display:none;" @endif>
				<div class="form-group">
					{!! Form::label('week_day_id', 'Weekly On', ['class' => 'control-label']) !!}
			
					{!! Form::select('week_day_id', $weekdays,null,['class' => 'form-control select_item']) !!}
				</div>
			</div>

			<div class="col-md-4 day" @if($payroll_frequency->salary_day == null)  style="display:none;" @endif>
				<div class="form-group" >
					{!! Form::label('salary_day','Days', ['class' => 'control-label']) !!}
			
					{{ Form::select('salary_day',$days,null ,['class' => 'form-control select_item']) }}
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

	$("select[name=frequency_type]").on('change', function(){
			$('select[name=salary_period], select[name=week_day_id]').val('');
			$('select[name=salary_day]').val(1);
			$('select[name=salary_period], select[name=week_day_id], select[name=salary_day]').trigger('change');
	});

	$("select[name=salary_period]").on('change', function(){
			$('select[name=week_day_id]').val('');
			$('select[name=salary_day]').val(1);
			$('select[name=week_day_id], select[name=salary_day]').trigger('change');
	});
	

	$('select[name=frequency_type]').on('change', function()
	{
		if($(this).val() == 0)
		{
			$('.month').hide();
			$('.week').hide();
			$('.day').hide();
		}		
		else if($(this).val() == 1)
		{
			$('.week').show();
			$('.month').hide();
			$('.day').hide();
		}
		else if($(this).val() == 2)
		{
			$('.month').show();
			$('.day').show();
			$('.week').hide();
		}			
	});

	$('select[name=salary_period]').on('change', function()
	{
		if($(this).val() != '')
		{			
			$('.week').show();
			$('.day').hide();			
		}
		else{
			$('.week').hide();
			$('.day').show();
		}
	});	

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true },
			frequency_type: { required: true },                
		},

		messages: {
			name: { required: " Name is required." },
			frequency_type: { required: "Frequench Type is required." },                
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
			url: '{{ route('payroll_frequency.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method:'PATCH',
				id: $('input[name=id]').val(),
				name: $('input[name=name]').val(),
				code: $('input[name=code]').val(),
				frequency_type: $('select[name=frequency_type]').val(),
				week_day_id: $('select[name=week_day_id]').val(),
				salary_day: $('select[name=salary_day]').val(),
				salary_period: $('select[name=salary_period]').val(),
				},
			success:function(data, textStatus, jqXHR) {

				var active_selected = "";
				var inactive_selected = "";
				var selected_text = "In-Active";
				var selected_class = "badge-warning";

				if(data.data.status == 1) {
					active_selected = "selected";
					selected_text = "Active";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					inactive_selected = "selected";
				}

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="payroll_frequency" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.code+`</td>
					<td>`+data.data.frequency+`</td>
					<td>`+data.data.salary_period+`</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option `+active_selected+` value="1">Active</option>
							<option `+inactive_selected+` value="0">In-Active</option>
						</select>
					</td>
					<td>
					<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`, `edit`, data.message,data.data.id);

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