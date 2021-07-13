<div class="modal-header">
    <h4 class="modal-title float-right">Edit Break</h4>
</div>

    {!! Form::model($breaks, ['class' => 'form-horizontal validateform']) !!}
    {{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}

		<div class="form-group">
			{!! Form::label('name', 'Break Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('start_time', 'Start Time', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::text('start_time',null,['class' => 'form-control timepicker timepicker-no-seconds', 'data-date-format' => 'dd-mm-yyyy', 'placeholder'=>'From Time', 'id'=>'start_time']) !!}
			</div>
		</div>
		
		<div class="form-group">						 
			{!! Form::label('end_time', 'End Time', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::text('end_time',null,['class' => 'form-control timepicker timepicker-no-seconds', 'data-date-format' => 'dd-mm-yyyy', 'placeholder'=>'From Time', 'id'=>'end_time']) !!}
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
		    name: { required: true },
		    //parent_department: { required: true },                
		},

		messages: {
		    name: { required: "Break Name is required." },
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
            url: '{{ route('breaks.update') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                id: $('input[name=id]').val(),
                name: $('input[name=name]').val(),
                start_time: $('input[name=start_time]').val(),
                end_time: $('input[name=end_time]').val(), 
                },
            success:function(data, textStatus, jqXHR) {

                call_back(`<tr role="row" class="odd">
                	<td><input id="`+data.data.id+`" class="item_check" name="break" value="`+data.data.id+`" type="checkbox">
                    	<label for="`+data.data.id+`"><span></span></label>
                    </td>
                    <td>`+data.data.name+`</td>
                    <td>`+data.data.start_time+`</td>
                    <td>`+data.data.end_time+`</td>
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

});
</script>