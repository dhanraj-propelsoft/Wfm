<div class="modal-header">
	<h4 class="modal-title float-right">Add Employee Relieve</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('department_id', 'Department Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('department_id', $parent_dept, null, ['class'=>'form-control select_item'])}}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('employee_id', 'Employee', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::select('employee_id', ['' => 'Select Employee'], null,['class' => 'select_item']) !!}
			</div>
		</div>		
		<div class="form-group">						 
			{!! Form::label('reason', 'Reason', ['class' => 'control-label col-md-3 required']) !!}
			
			<div class="col-md-12">
				{!! Form::textarea('reason', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'30']) !!}
			</div>
		</div>
		<div class="form-group">						 
			{!! Form::label('relieved_date', 'Relieved Date', ['class' => 'control-label col-md-3 required']) !!}
			
			<div class="col-md-12">
				{!! Form::text('relieved_date', null, ['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy']) !!}
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
	});
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			department_id: { required: true },
			employee_id: { required: true },
			reason: { required: true },
			relieved_date: { required: true },
		},

		messages: {
			department_id: { required: "Department Name is required." },
			employee_id: { required: "Employee Name is required." },
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
			url: '{{ route('employee_relieve.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',				
				department_id: $('select[name=department_id]').val(),
				employee_id: $('select[name=employee_id]').val(),
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