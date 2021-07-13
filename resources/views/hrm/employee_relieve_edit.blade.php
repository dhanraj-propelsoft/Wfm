<div class="modal-header">
	<h4 class="modal-title float-right">Add Employee Relieve</h4>
</div>

	{!! Form::model($employee_relieve, [
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}				
		{!! Form::hidden('employee_id', null) !!}				
		<div class="form-group">						 
			{!! Form::label('reason', 'Reason', ['class' => 'control-label col-md-3 required']) !!}
			
			<div class="col-md-12">
				{!! Form::textarea('reason', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'30']) !!}
			</div>
		</div>
		<div class="form-group">						 
			{!! Form::label('relieved_date', 'Relieved Date', ['class' => 'control-label col-md-3 required']) !!}
			
			<div class="col-md-12">
				{!! Form::text('relieved_date', null, ['class' => 'form-control datetype date-picker rearrangedate','data-date-format' => 'dd-mm-yyyy']) !!}
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
			reason: { required: true },
			relieved_date: { required: true },
		},

		messages: {
			reason: { required: "Reason is required." },
			relieved_date: { required: "Relieve Date is required." },
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
			url: '{{ route('employee_relieve.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				employee_id: $('input[name=employee_id]').val(),
				relieved_date: $('input[name=relieved_date]').val(),
				reason: $('textarea[name=reason]').val()                
			},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">					
					<td>`+data.data.employee_name+`</td>
					<td>`+data.data.relieved_date+`</td>					
					<td>`+data.data.reason+`</td>			
					<td>
						<a data-id="`+data.data.id+`"class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>
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