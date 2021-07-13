<div class="modal-header">
    <h4 class="modal-title float-right">Add Leave Types</h4>
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
				{!! Form::label('name', 'Leave Type Name', array('class' => 'control-label required')) !!}
				{!! Form::text('name', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('code', 'Code', array('class' => 'control-label required')) !!}	
				{!! Form::text('code', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">				
				{{ Form::checkbox('yeary_limits', '1', null, ['id' => 'yeary_limits']) }}
				 <label for="yeary_limits"><span></span>Yearly Limit</label>
			</div>			
			<div class="col-md-6">
				{{ Form::checkbox('monthly_limits', '1', null, ['id' => 'monthly_limits']) }}				
				 <label for="monthly_limits"><span></span>Monthly Limit</label>
			</div>
			
		</div>

		<div class="row">
			<div class="col-md-6 yearly_limits" style="display:none">
				<div class="form-group">			
					{!! Form::label('yearly_limit', 'Yearly Limit', array('class' => 'control-label')) !!}
					{!! Form::text('yearly_limit', null,['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('yearly_carry_limit', 'Yearly Carry Limit', array('class' => 'control-label')) !!}
					{!! Form::text('yearly_carry_limit', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6 monthly_limits" style="display:none">
				<div class="form-group">
					{!! Form::label('monthly_limit', 'Monthly Limit', array('class' => 'control-label')) !!}
					{!! Form::text('monthly_limit', null,['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('monthly_carry_limit', 'Monthly Carry Limit', array('class' => 'control-label')) !!}
					{!! Form::text('monthly_carry_limit',null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>		

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('applicable_to', 'Applicable To', ['class' => 'control-label']) !!}
			
				{!!	Form::select('applicable_to', ['' => 'Select','gender'=>'Gender','employment_type'=>'Employment Type','department'=>'Department','designation'=>'Designation'], null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				{!! Form::label('effective_from', 'Effective From', ['class' => 'control-label']) !!}
			
				{!!	Form::select('effective_from', ['' => 'Select','0'=>'From Joining Date','1'=>'After 3 months','2'=>'After 6 months','3'=>'After a year','4'=>'User Defined Date'], null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>
		</div>

		<div class="row gender applicable" style="display:none">
			<div class="col-md-6">
				<div class="form-group">
				{!!	Form::select('applicable_gender',$genders, null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>			
		</div>

		<div class="row employment_type applicable" style="display:none">
			<div class="col-md-6">
				<div class="form-group">				
				{!!	Form::select('applicable_employment_type',$employment_types, null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>			
		</div>
		<div class="row department applicable" style="display:none">
			<div class="col-md-6">
				<div class="form-group" >				
				{!!	Form::select('applicable_department',$departments, null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>			
		</div>
		<div class="row designation applicable" style="display:none">
			<div class="col-md-6">
				<div class="form-group">				
				{!!	Form::select('applicable_designation',$designations, null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>			
		</div>		

		<div class="row periods" style="display:none">
			<div class="col-md-3">				
				{!! Form::text('activation_period', null,['class' => 'form-control']) !!}
			</div>
			<div class="col-md-3">	
				{!!	Form::select('period_type', ['' => 'Select','0'=>'Days','1'=>'Months'], null, ['class' => 'form-control select_item']); !!}
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				{{ Form::checkbox('part_of_weekoff', '1', null, ['id' => 'part_of_weekoff']) }}
				 <label for="part_of_weekoff"><span></span>Leave between weekoff</label>
			</div>			
			<div class="col-md-6">
				{{ Form::checkbox('part_of_holiday', '1', null, ['id' => 'part_of_holiday']) }}
				<label for="part_of_holiday"><span></span>Leave between Holiday</label>
			</div>			
		</div>

		<div class="row">
			<div class="col-md-6">
				{{ Form::checkbox('before_weekoff', '1', null, ['id' => 'before_weekoff']) }}
				<label for="before_weekoff"><span></span>Leave Before Weekoff</label>
			</div>		
			<div class="col-md-6">
				{{ Form::checkbox('after_weekoff', '1', null, ['id' => 'after_weekoff']) }}
				<label for="after_weekoff"><span></span>Leave After Weekoff</label>	
			</div>			
		</div>

		<div class="row">
			<div class="col-md-6">
				{{ Form::checkbox('before_holiday', '1', null, ['id' => 'before_holiday']) }}
				<label for="before_holiday"><span></span>Leave Before Holiday</label>
			</div>
			
			<div class="col-md-6">
				{{ Form::checkbox('after_holiday', '1', null, ['id' => 'after_holiday']) }}
				<label for="after_holiday"><span></span>Leave After Holiday</label>	
			</div>			
		</div>		

		<div class="row">
			<div class="col-md-12">
				{!! Form::label('pay_status', 'Pay Status', ['class' => 'control-label']) !!}
			</div>			
			<div class="col-md-12">
			{!! Form::radio('pay_status', '1', true, ['id' => 'paid']) !!}
			<label for="paid" class = 'control-label col-md-2'><span></span>Paid</label>

			{!! Form::radio('pay_status', '0', null, ['id' => 'unpaid']) !!}
			<label for="unpaid" class = 'control-label col-md-2'><span></span>Un Paid</label>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				{!! Form::label('color', 'Color', array('class' => 'control-label required')) !!}
			</div>
			<div class="col-md-12">
				<div id="colorpicker" class="input-group colorpicker-component"> 
					{{Form::text('color', null, ['class'=>'form-control'])}}
			      	<span class="input-group-addon"><i></i></span>
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
	$(document).ready(function() {
	   basic_functions();
	   $('#colorpicker').colorpicker();
	});
	
	$("input[name=yeary_limits]").change(function(){
		if($(this).is(':checked'))
		{			
			$('.yearly_limits').show();
		}
		else {
			$('.yearly_limits').hide();
		}
	});

	$("input[name=monthly_limits]").change(function(){
		if($(this).is(':checked'))
		{			
			$('.monthly_limits').show();
		}
		else {
			$('.monthly_limits').hide();
		}
	});

	$('select[name=applicable_to]').on('change', function(){

		if($(this).val() == "gender")
		{
			$('.applicable').hide();
			$('.gender').show();
		}
		else if($(this).val() == "employment_type")
		{
			$('.applicable').hide();
			$('.employment_type').show();
		}
		else if($(this).val() == "department")
		{
			$('.applicable').hide();
			$('.department').show();
		}
		else if($(this).val() == "designation")
		{
			$('.applicable').hide();
			$('.designation').show();
		}
	});

	$('select[name=effective_from]').on('change', function(){

		if($(this).val() == "4")
		{			
			$('.periods').show();
		}
		else
		{
			$('.periods').hide();
		}
	});	

	 basic_functions();

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
		    name: { required: true },
		    code: { required: true },                
		},

		messages: {
		    name: { required: "Name is required." },
		    code: { required: "Code is required." },                
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
            url: '{{ route('leave_types.store') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                name: $('input[name=name]').val(),
                code: $('input[name=code]').val(),
                yearly_limit: $('input[name=yearly_limit]').val(),
                yearly_carry_limit: $('input[name=yearly_carry_limit]').val(),
                monthly_limit: $('input[name=monthly_limit]').val(),
                monthly_carry_limit: $('input[name=monthly_carry_limit]').val(),
                part_of_weekoff: $('input[name=part_of_weekoff]:checked').val(),
                part_of_holiday: $('input[name=part_of_holiday]:checked').val(),
                before_weekoff: $('input[name=before_weekoff]:checked').val(),
                after_weekoff: $('input[name=after_weekoff]:checked').val(),
                before_holiday: $('input[name=before_holiday]:checked').val(),
                after_holiday: $('input[name=after_holiday]:checked').val(),
                applicable_gender: $('select[name=applicable_gender]').val(),
                applicable_employment_type: $('select[name=applicable_employment_type]').val(),
                applicable_department: $('select[name=applicable_department]').val(),
                applicable_designation: $('select[name=applicable_designation]').val(),
                effective_from: $('select[name=effective_from]').val(),
                period_type: $('select[name=period_type]').val(),
                activation_period: $('input[name=activation_period]').val(),
                pay_status: $('input[name=pay_status]:checked').val(),
                color: $('input[name=color]').val(),
                },
            success:function(data, textStatus, jqXHR) {

                call_back(`<tr role="row" class="odd">
                	<td><input id="`+data.data.id+`" class="item_check" name="leavetype" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label></td>
                    <td>`+data.data.name+`</td>
                    <td>`+data.data.code+`</td>
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