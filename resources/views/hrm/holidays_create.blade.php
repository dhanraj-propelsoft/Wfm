<div class="modal-header">
    <h4 class="modal-title float-right">Add Holidays</h4>
</div>

    {!! Form::open([
        'class' => 'form-horizontal validateform'
    ]) !!}
    {{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		<div class="form-group">
			{!! Form::label('name', 'Holiyday Name', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('holiday_date', 'Holiday Date', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('holiday_date', null,['class' => 'form-control accounts-date-picker']) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				{{ Form::checkbox('continue_status','1', true, ['id' => 'continue_status']) }}
				<label for="continue_status"><span></span>Same date on every year</label>
				
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('holiday_type_id', 'Holiday Types', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{{ Form::select('holiday_type_id',$holiday_types,null,['class'=>'form-control select_item']) }}
			</div>
		</div>
				

		<div class="form-group">
			{!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-12">
				{!! Form::textarea('description', null, array('class' => 'form-control','rows'=>'3 ','cols'=>'40')) !!}
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
		    holiday_date: { required: true },
		    holiday_type_id: { required: true },
		},

		messages: {
		    name: { required: "Holiyday Name is required." },
		    holiday_date: { required: "Date is required." },
		    holiday_type_id: { required: "Holiyday Type is required." },
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
            url: '{{ route('holidays.store') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                name: $('input[name=name]').val(),
                holiday_date: $('input[name=holiday_date]').val(),
                continue_status: $('input[name=continue_status]:checked').val(),
                holiday_type_id: $('select[name=holiday_type_id]').val(),
                description: $('textarea[name=description]').val()
                },
            success:function(data, textStatus, jqXHR) {

                call_back(`<tr role="row" class="odd">
                	<td><input id="`+data.data.id+`" class="item_check" name="team" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
                	</td>
                    <td>`+data.data.name+`</td>
                    <td>`+data.data.holiday_date+`</td>
                    <td>`+data.data.description+`</td>
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