<div class="modal-header">
	<h4 class="modal-title float-right">Add Leave Request</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		@permission('leave-approval')
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
		@endpermission

		<div class="form-group">
			{!! Form::label('leave_type_id', 'Leave Type', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::select('leave_type_id', $leaves_type, null, ['class' => 'form-control select_item', 'id' => 'leave_type_id']); !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('from_date', 'From Date', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('from_date', null,['class' => 'form-control accounts-date-picker']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('to_date', 'To Date', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('to_date', null,['class' => 'form-control accounts-date-picker']) !!}
			</div>
		</div>
		<div class="form-group">
				{!! Form::label('leave_days', 'Leave Days', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('leave_days', null, ['class' => 'form-control']) !!}
				
			</div>
		</div>	

		<div class="form-group">
			{!! Form::label('reason', 'Reason', ['class' => 'control-label col-md-3 required']) !!}
			<div class="col-md-12">
				{!! Form::textarea('reason', null, array('class' => 'form-control','rows'=>'3 ','cols'=>'40')) !!}
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

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			leave_type_id: { required: true },
			from_date: { required: true },
			to_date: { required: true },
			
		},

		messages: {
			leave_type_id: { required: "Leave Type is required." },
			from_date: { required: "From Date is required." },
			to_date: { required: "To date is required." },
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

			var employee = "";

			if($('select[name=employee_id]').length > 0) {
				employee = $('select[name=employee_id]').val();
			}

			$.ajax({
			url: '{{ route('leaves.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',                
				leave_type_id: $('select[name=leave_type_id]').val(),
				from_date: $('input[name=from_date]').val(),
				to_date: $('input[name=to_date]').val(),
				leave_days: $('input[name=leave_days]').val(),
				reason: $('textarea[name=reason]').val(),
				admin_employee_id : employee,
				},
			success:function(data, textStatus, jqXHR) {

				var approve_selected = "";
				var pending_selected = "";
				var not_approve_selected = "";
				
				var selected_text = "Pending";
				var selected_class = "badge-warning";

				if(data.data.approval_status == 1) {
					approve_selected = "selected";
					selected_text = "Approved";
					selected_class = "badge-success";
				} else if(data.data.approval_status == 0) {
					pending_selected = "selected";
					selected_text = "Pending";
					selected_class = "badge-warning";
				} else if(data.data.approval_status == 2) {
					not_approve_selected = "selected";
					selected_text = "Cancelled";
					selected_class = "badge-danger";
				}

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="leaves" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label></td>
					<td>`+data.data.employeename+`</td>
					<td>`+data.data.leavetype+`</td>
					<td>`+data.data.leave_days+`</td>
					<td>`+data.data.reason+`</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`" data-id="`+employee+`" class="active_status form-control">
							<option `+pending_selected+` value="0">Pending</option>
							<option `+approve_selected+` value="1">Approved</option>
							<option `+not_approve_selected+` value="2">Cancelled</option>
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

});
</script>