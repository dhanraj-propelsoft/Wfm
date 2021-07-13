<div class="modal-header">
    <h4 class="modal-title float-right">Add Log Register</h4>
</div>

    {!! Form::open([
        'class' => 'form-horizontal validateform'
    ]) !!}                                        
    {{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('log_date', 'Log Date', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('log_date', null,['class' => 'form-control accounts-date-picker']) !!}
			</div>
		</div>		

		<div class="form-group">
			{!! Form::label('in_time', 'In Time', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('in_time',null,['class' => 'form-control timepicker timepicker-no-seconds','data-date-format' => 'dd-mm-yyyy', 'id'=>'in_time']) !!}
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('out_time', 'To Time', ['class' => 'control-label col-md-3 required']) !!}
			
			<div class="col-md-12">
				{!! Form::text('out_time',null,['class' => 'form-control timepicker timepicker-no-seconds', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'out_time']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('purpose', 'Purpose', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::textarea('purpose', null,['class' => 'form-control','rows'=>'3 ','cols'=>'40']) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('person_type_id', 'Person Type', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				<select name="person_type_id" class="form-control select_item">
					<option value="">Select Person Type</option>
						@foreach($person_types as $person_type)
						<option value="{{$person_type->id}}" data-name="{{$person_type->type}}">{{$person_type->name}}</option>
						@endforeach
				</select>
			</div>
		</div>

		<div class="form-group employee" style="display:none;">
			{!! Form::label('employee_id', 'Employee', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::select('employee_id', $employees,null,['class' => 'form-control select_item','id'=>'employee_id']) !!}
			</div>
		</div>

		<div class="form-group person" style="display:none;">
			{!! Form::label('person_id', 'Person', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::text('person_id',null,['class'=>'form-control']) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('employer_note', 'Note', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::textarea('employer_note', null,['class' => 'form-control','rows'=>'3 ','cols'=>'40']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('description', 'Description', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::textarea('description', null,['class' => 'form-control','rows'=>'3 ','cols'=>'40']) !!}
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

	 $("select[name=person_type_id]").on('change', function(){
		if(!$(this).is(':selected'))
		{
			$('select[name=employee_id]').val('');
			$('input[name=person_id]').val('');
		}
	});

	 $('select[name=person_type_id]').on('change', function()
	{
			var current= $(this).find('option:selected').data('name');
			//alert(current);
			if(current == 0)
			{
				$('.person').show();
			}
			else
			{
				$('.person').hide();
			}

			if(current == 1)
			{
				$('.employee').show();
			}
			else
			{
				$('.employee').hide();
			}
			//$(this).css('display','block');
	});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
		    log_date: { required: true },
		    in_time: { required: true },
		    out_time: { required: true },             
		    person_type_id: { required: true },             
		},

		messages: {
		    log_date: { required: " Date is required." },
		    in_time: { required: " In Time is required." },
		    out_time: { required: " Out Time is required." },		                   
		    person_type_id: { required: " Person Type is required." },		                   
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
            url: '{{ route('log_registers.store') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                log_date: $('input[name=log_date]').val(),
                in_time: $('input[name=in_time]').val(),
                out_time: $('input[name=out_time]').val(),
                purpose: $('textarea[name=purpose]').val(),
                description: $('textarea[name=description]').val(),
                employer_note: $('textarea[name=employer_note]').val(),
                person_type_id: $('select[name=person_type_id]').val(),
                person_id: $('input[name=person_id]').val(),
                employee_id: $('select[name=employee_id]').val(),
                },
            success:function(data, textStatus, jqXHR) {   	

                call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="log_register" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
                	<td>`+data.data.log_date+`</td>
                	<td>`+data.data.person_type+`</td>
                    <td>`+data.data.employee_name+`</td>
                    <td>`+data.data.in_time+`</td>
                    <td>`+data.data.out_time+`</td>    
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