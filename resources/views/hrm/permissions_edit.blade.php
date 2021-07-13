<div class="modal-header">
    <h4 class="modal-title float-right">Edit Permission Request</h4>
</div>

    {!! Form::model($permissions, ['class' => 'form-horizontal validateform']) !!}
    {{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		{{Form::hidden('id',null)}}

		<div class="form-group">
			{!! Form::label('employee_id', 'Employee', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::select('employee_id',$employee,null,['class' => 'form-control select_item']) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('permission_date', 'Permission Date', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('permission_date', null,['class' => 'form-control accounts-date-picker rearrangedate']) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('reason', 'Reason', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::textarea('reason', null,['class' => 'form-control','rows'=>'3 ','cols'=>'40']) !!}
			</div>
		</div>		
		<div class="form-group">
			{!! Form::label('from_time', 'From Time', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::text('from_time',null,['class' => 'form-control timepicker timepicker-no-seconds', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'from_time']) !!}
			</div>
		</div>
		
		<div class="form-group">						 
			{!! Form::label('to_time', 'To Time', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::text('to_time',null,['class' => 'form-control timepicker timepicker-no-seconds', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'to_time']) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('total_hours', 'Total Hours', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('total_hours', null, ['class' => 'form-control', 'readonly'=>'ture','id'=>'hours'] ) !!}
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

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
		    permission_date: { required: true },
		    //parent_department: { required: true },
		},

		messages: {
		    permission_date: { required: " Date is required." },
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
            url: '{{ route('permissions.update') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                id: $('input[name=id]').val(),
                employee_id: $('select[name=employee_id]').val(),
                permission_date: $('input[name=permission_date]').val(),
                reason: $('textarea[name=reason]').val(),
                from_time: $('input[name=from_time]').val(),
                to_time: $('input[name=to_time]').val(),                             
                },
            success:function(data, textStatus, jqXHR) { 

            			var active_approve_selected = "";
						var inactive_approve_selected = "";
						var pending_approve_selected = "";
						var selected_approve_text = "Pending";
						var selected_approve_class = "badge-warning";

						if(data.data.approval_status == 1) {
							active_approve_selected = "selected";
							selected_approve_text = "Approved";
							selected_approve_class = "badge-info";
						} else if(data.data.status == 0) {
							pending_approve_selected = "selected";
							selected_approve_text = "Pending";
						}
						else if(data.data.status == 2) {
							inactive_approve_selected = "selected";
							selected_approve_text = "Not Approved";
						}           	

                call_back(`<tr role="row" class="odd">
                	<td><input id="`+data.data.id+`" class="item_check" name="permissions" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label></td>
                    <td>`+data.data.employee_name+`</td>
                    <td>`+data.data.reason+`</td>
                    <td>`+data.data.total_hours+`</td>
                    <td>
						<label class="grid_label badge `+selected_approve_class+` status">`+selected_approve_text+`</label>
						<select style="display:none" id="`+data.data.id+`" class="approval_status form-control">
							<option `+active_approve_selected+` value="1">Approved</option>
							<option `+inactive_approve_selected+` value="2">Not Approved</option>
							<option `+inactive_approve_selected+` value="0">Pending</option>
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

    $('.input-group-addon').find('.fa-calendar').on('click', function()
	{
    	$(this).closest('.form-group').find('.date-picker').trigger('focus');
	});

	$("#to_time,#from_time").on('change',function(){

	   var startTime = $('#from_time').val();
	   var endTime = $('#to_time').val();

	   var startTimeArray = startTime.split(":");
	   var startInputHrs = parseInt(startTimeArray[0]);
	   var startInputMins = parseInt(startTimeArray[1]);

	   var endTimeArray = endTime.split(":");
	   var endInputHrs = parseInt(endTimeArray[0]);
	   var endInputMins = parseInt(endTimeArray[1]);

	   var startMin = startInputHrs*60 + startInputMins;
	   var endMin = endInputHrs*60 + endInputMins;

	   var result;

	   if (endMin < startMin) {
	       var minutesPerDay = 12*60; 
	       result = minutesPerDay - startMin;  // Minutes till midnight
	       result += endMin; // Minutes in the next day
	   } else {
	      result = endMin - startMin;
	   }

	   var minutesElapsed = result % 60;
	   var hoursElapsed = (result - minutesElapsed) / 60;   

	   var diff = + hoursElapsed + ":" + (minutesElapsed < 10 ?
	            '0'+minutesElapsed : minutesElapsed);

	       	
	        document.getElementById('hours').value = diff;	

		});

});
</script>