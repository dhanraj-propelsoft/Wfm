<div class="modal-header">
	<h4 class="modal-title float-right">Add Pay Head</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('payhead_type_id', 'Pay Head Type', array('class' => 'control-label required')) !!}
			
				{!! Form::select('payhead_type_id',$payhead_types,null,['class' => 'form-control select_item']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
				{!! Form::label('name', 'Name', array('class' => 'control-label required')) !!}
				{!! Form::text('name', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
				{!! Form::label('code','Code', array('class' => 'control-label  required')) !!}
				{!! Form::text('code', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					{!! Form::label('display_name', 'Display Name on Pay slip', array('class' => 'control-label')) !!}
					{!! Form::text('display_name', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('calculation_type', 'Calculation Type', array('class' => 'control-label required')) !!}
			
				{!! Form::select('calculation_type',['' => 'Select','0'=>'Flat','1'=>'Percent'],null,['class' => 'form-control select_item']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 formula" style="display:none;">
				<div class="form-group">
					{!! Form::label('formula', 'Calculate From', array('class' => 'control-label')) !!}
					<br>
					{{ Form::radio('formula','0', null, ['class' => 'md-radiobtn', 'id'=> 'nett']) }}
					<label class="control-label" for="nett"><span></span>Sub Total</label>
				
					{{ Form::radio('formula','1', null, ['class' => 'md-radiobtn', 'id'=> 'specific']) }}
					<label class="control-label" for="specific"><span></span>Specific Pay Head</label>

					{{ Form::radio('formula','2', null, ['class' => 'md-radiobtn', 'id'=> 'earnings']) }}
					<label class="control-label" for="earnings"><span></span>Earnings</label>

					{{ Form::radio('formula','3', null, ['class' => 'md-radiobtn', 'id'=> 'deductions']) }}
					<label class="control-label" for="deductions"><span></span>Deductions</label>
				</div>
			</div>
		</div>		
		
		<div class="row parent_pay_head"  style="display:none;">
			<div class="col-md-12 ">
				<div class="form-group">
				{!! Form::label('pay_head_id', 'Parent', array('class' => 'control-label')) !!}
			
				{!! Form::select('pay_head_id', $pay_head, null, ['class' => 'form-control select_item', 'id' => 'pay_head_id','multiple'=>'multiple']) !!}
				</div>
			</div>
		</div>

		<!-- <div class="row">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('group_id', 'Under', array('class' => 'control-label')) !!}
			
				{!! Form::select('group_id',$groups,null,['class' => 'form-control select_item']) !!}
				</div>
			</div>
		</div> -->

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('wage_type', 'Wage Type', array('class' => 'control-label')) !!}
			</br>			
				{{ Form::radio('wage_type','0','true', ['class' => 'md-radiobtn', 'id'=> 'hour']) }}
				<label class="control-label" for="hour"><span></span>Hour Based</label>

				{{ Form::radio('wage_type','1','', ['class' => 'md-radiobtn', 'id'=> 'day']) }}
				<label class="control-label" for="day"><span></span>Day Based</label>

				{{ Form::radio('wage_type','2','', ['class' => 'md-radiobtn', 'id'=> 'month']) }}
				<label class="control-label" for="month"><span></span>Month Based</label>
				</div>
			</div>
		</div>

		<div class="form-group fixed_month" style="display:none;">
			{!! Form::label('fixed_month', 'Fixed Month', array('class' => 'control-label col-md-4')) !!}
			<div class="col-md-12">
				{{ Form::radio('fixed_month','0','', ['class' => 'md-radiobtn', 'id'=> 'days']) }}				
				<label class="control-label" for="days"><span></span>Fixed Days</label>

				{{ Form::radio('fixed_month','1','', ['class' => 'md-radiobtn', 'id'=> 'months']) }}
				<label class="control-label" for="months"><span></span>Calendar Month</label>							
			</div>
		</div>

		<div class="form-group fixed_days" style="display:none;">
			{!! Form::label('fixed_days', 'Fixed Days', array('class' => 'control-label col-md-4')) !!}
			<div class="col-md-12">
				{!! Form::text('fixed_days', null,['class' => 'form-control numbers']) !!}
			</div>
		</div>

		<div class="form-group minimum_attendance" style="display:none;">
			{!! Form::label('minimum_attendance', 'Minimum Attendance (Subtract days from current month)', array('class' => 'control-label col-md-12')) !!}
			<div class="col-md-12">
				{!! Form::text('minimum_attendance', null,['class' => 'form-control numbers']) !!}
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

	$("input[name=is_attendance_based]").on('change',function(){
		$('input[name=attendance_days]').prop('checked', false);
		$('input[name=minimum_attendance]').val('');
	});

	$("input[name=wage_type]").on('change',function(){
		$('input[name=fixed_month]').prop('checked', false);
		$('input[name=fixed_days]').val('');
		$('input[name=minimum_attendance]').val('');
	});

	$('select[name=calculation_type]').on('change', function()
	{
		$('input[name=formula]').prop('checked', false);

		//alert($(this).val());

		if($(this).val() == 1)
		{
			$('.formula').show();
		}
		else if($(this).val() == 0)
		{
			$('.formula').hide();
			$('.parent_pay_head').hide();
		}
	});

	$('input[name=formula]').on('change', function()
	{
		if($(this).val() == 1)
		{
			$('.parent_pay_head').show();
		}
		else
		{
			$('.parent_pay_head').hide();
		}
	});

	$('input[name=wage_type]').on('change', function() {

		$('input[name=fixed_month]').prop('checked', false);		

		if($(this).val() == 2 ) {
			$('.fixed_month').show();
		} else {
			$('.fixed_month').hide();
			$('.fixed_days').hide();
			$('.minimum_attendance').hide();
		}
	});

	$('input[name=fixed_month]').on('change', function() {

		if($(this).val() == 0 ) {
			$('.fixed_days').show();
			$('.minimum_attendance').hide();
		} 
		else if($(this).val() == 1 ) {
			$('.fixed_days').hide();
			$('.minimum_attendance').show();
		}
	});


	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			payhead_type_id: { required: true },
			name: { required: true },
			code: { required: true },
			calculation_type:{required: true},
		},
		messages: {
			payhead_type_id: { required: "Pay Head Type is required." },
			name: { required: "Name is required." },
			code: { required: "Code is required." },
			calculation_type: { required: "Calculation Type is required." }, 
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
			url: '{{ route('pay_head.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				payhead_type_id: $('select[name=payhead_type_id]').val(),
				name: $('input[name=name]').val(),
				display_name: $('input[name=display_name]').val(),
				code: $('input[name=code]').val(),
				calculation_type: $('select[name=calculation_type]').val(),
				formula: $('input[name=formula]:checked').val(),
				wage_type: $('input[name=wage_type]:checked').val(),               
				fixed_month: $('input[name=fixed_month]:checked').val(),
				fixed_days: $('input[name=fixed_days]').val(),
				is_attendance_based: $('input[name=is_attendance_based]:checked').val(),
				attendance_days: $('input[name=attendance_days]:checked').val(),
				minimum_attendance: $('input[name=minimum_attendance]').val(),
				ledger_id: $('select[name=ledger_id]').val(),
				pay_head_id: $('select[name=pay_head_id]').val(),
				description: $('textarea[name=description]').val(),
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="payhead" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.code+`</td>
					<td>`+data.data.wage_type+`</td>
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