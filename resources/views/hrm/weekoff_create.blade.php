<div class="modal-header">
    <h4 class="modal-title float-right">Add Week-Off</h4>
</div>

    {!! Form::open([
        'class' => 'form-horizontal validateform'
    ]) !!}
    {{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('name', 'Name', array('class' => 'control-label required')) !!}
				{!! Form::text('name', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('effective_date', 'Effective Date', array('class' => 'control-label  required')) !!}
					{!! Form::text('effective_date', null,['class' => 'form-control accounts-date-picker']) !!}
				</div>
			</div>
		</div>				

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('first_week_off', 'First Week-Off', array('class' => 'control-label  required')) !!}
			
				{!! Form::select('first_week_off', $weekdays,null,['class' => 'form-control select_item']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('first_week_off_period', 'First Week-Off Period', ['class' => 'control-label']) !!}
			
				{!!	Form::select('first_week_off_period', ['' => 'Select','0'=>'Every','1'=>'1st','2'=>'2nd','3'=>'3rd','4'=>'4th','5'=>'Last','6'=>'Alt(1, 3)','7'=>'Alt(2, 4)','8'=>'Alt(1, 3, 5)'], null, ['class' => 'form-control select_item','disabled' => 'disabled']); !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				{{ Form::checkbox('first_week_half_day', '1', null, ['id' => 'first_week_half_day']) }}
				<label for="first_week_half_day"><span></span>First Week Half Day</label>
			</div>
			<div class="col-md-6">	
				{{ Form::checkbox('first_full_day_rule', '1', null, ['id' => 'first_full_day_rule']) }}
				<label for="first_full_day_rule"><span></span>First Fullday Rule</label>
			</div>
		</div>

		<div class="row first_half_minimum" style="display:none;">
			<div class="col-md-3">
				{!! Form::label('first_half_minimum', 'Minimum Hours for Halfday', array('class' => 'control-label')) !!}
			</div>
			<div class="col-md-3">
				{!! Form::text('first_half_minimum',null,['class' => 'form-control timepicker timepicker-no-seconds', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'first_half_minimum']) !!}
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('second_week_off', 'Second Week-Off', array('class' => 'control-label')) !!}
			
				{!! Form::select('second_week_off', $weekdays,null,['class' => 'form-control select_item','disabled' => 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('second_week_off_period', 'Second Week-Off Period', ['class' => 'control-label']) !!}
			
				{!!	Form::select('second_week_off_period', ['' => 'Select','0'=>'Every','1'=>'1st','2'=>'2nd','3'=>'3rd','4'=>'4th','5'=>'Last','6'=>'Alt(1, 3)','7'=>'Alt(2, 4)','8'=>'Alt(1, 3, 5)'], null, ['class' => 'form-control select_item','disabled' => 'disabled']); !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">	
				{{ Form::checkbox('second_week_half_day', '1', null, ['id' => 'second_week_half_day']) }}
				<label for="second_week_half_day"><span></span>Second Week Half Day</label>
			</div>
			<div class="col-md-6">	
				{{ Form::checkbox('second_full_day_rule', '1', null, ['id' => 'second_full_day_rule']) }}
				<label for="second_full_day_rule"><span></span>Second Fullday Rule</label>
			</div>
		</div>

		<div class="row second_half_minimum" style="display: none;">
			<div class="col-md-3">
				{!! Form::label('second_half_minimum', 'Minimum Hours for Halfday', array('class' => 'control-label')) !!}
			</div>
			<div class="col-md-3">
				{!! Form::text('second_half_minimum',null,['class' => 'form-control timepicker timepicker-no-seconds', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'second_half_minimum']) !!}
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-6">
				{!! Form::label('color', 'Color', array('class' => 'control-label required')) !!}	
				<div id="colorpicker" class="input-group colorpicker-component"> 
					{{Form::text('color', '#3D77A8', ['class'=>'form-control'])}}
			      	<span class="input-group-addon"><i></i></span>
			    </div>
			</div>
			<div class="form-group col-md-6">
				{!! Form::label('paid_status', 'Pay Status', ['class' => 'control-label required']) !!}

				<div class="input-group col-md-12">
					{!! Form::radio('paid_status', '1', true, ['id' => 'paid']) !!}
					<label for="paid" class = 'control-label col-md-4'><span></span>Paid</label>

					{!! Form::radio('paid_status', '0','', ['id' => 'unpaid']) !!}
					<label for="unpaid" class = 'control-label col-md-4'><span></span>Un Paid</label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('description', 'Description', ['class' => 'control-label']) !!}
			
					{!! Form::textarea('description', null, array('class' => 'form-control','rows'=>'3 ','cols'=>'40')) !!}
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

	$(document).ready(function() {
	   basic_functions();
	   $('#colorpicker').colorpicker();
	});

	$('input[name=first_half_minimum]').val('');
	$('input[name=second_half_minimum]').val('');

	$("input[name=first_week_half_day]").on('change', function(){
		if($(this).is(':checked'))
		{			
			$('.first_half_minimum').show();
		}
		else 
		{
			$('.first_half_minimum').hide();
		}
	});

	$("input[name=second_week_half_day]").on('change', function(){
		if($(this).is(':checked'))
		{
			$('.second_half_minimum').show();
		}
		else {
			$('.second_half_minimum').hide();
		}
	});

	$("select[name=first_week_off]").on('change',function(){

		var selectedItem = $(this).val();
		 
		$('select[name=second_week_off] > span option').unwrap();

		$('select[name=second_week_off]').find('option[value="' + selectedItem + '"]').wrap("<span>");
	});


	$("select[name=first_week_off]").on('change', function(){

		if($(this).val() != "")
		{
			$('select[name=first_week_off_period]').prop('disabled',false);
			$('select[name=second_week_off]').prop('disabled',false);
			
		}else{
			$('select[name=first_week_off_period]').prop('disabled',true);
			$('select[name=second_week_off]').prop('disabled',true);
			
		} 
		
	});

	$("select[name=second_week_off]").change(function(){

		if($(this).val() != "")
		{			
			$('select[name=second_week_off_period]').prop('disabled',false);
		}else{			
			$('select[name=second_week_off_period]').prop('disabled',true);
		} 
		
	});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
		    name: { required: true },
		    effective_date: { required: true },
		    first_week_off: { required: true },
		},

		messages: {
		    name: { required: "Name is required." },
		    effective_date: { required: "Date is required." },
		   	first_week_off: { required: "First Week-Off is required." },
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
            url: '{{ route('weekoff.store') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                name: $('input[name=name]').val(),
                effective_date: $('input[name=effective_date]').val(),
                first_week_off: $('select[name=first_week_off]').val(),
                first_week_off_period: $('select[name=first_week_off_period]').val(),
                first_week_half_day: $('input[name=first_week_half_day]:checked').val(),
                first_half_minimum: $('input[name=first_half_minimum]').val(),
                first_full_day_rule: $('input[name=first_full_day_rule]:checked').val(),
                second_week_off: $('select[name=second_week_off]').val(),
                second_week_off_period: $('select[name=second_week_off_period]').val(),
                second_week_half_day: $('input[name=second_week_half_day]:checked').val(),
                second_half_minimum: $('input[name=second_half_minimum]').val(),
                second_full_day_rule: $('input[name=second_full_day_rule]:checked').val(),
                description: $('textarea[name=description]').val(),
                color: $('input[name=color]').val(),
                paid_status: $('input[name=paid_status]:checked').val(),
                },
            success:function(data, textStatus, jqXHR) {

                call_back(`<tr role="row" class="odd">
                	<td><input id="`+data.data.id+`" class="item_check" name="weekoff" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label></td>
                    <td>`+data.data.name+`</td>
                    <td>`+data.data.effective_date+`</td>
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