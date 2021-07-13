<div class="modal-header">
	<h4 class="modal-title float-right">Add Holiday Type</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		<div class="form-group">
			{!! Form::label('name', 'Holiday Type', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('code', 'Code', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('code', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('pay', 'Pay Status', array('class' => 'control-label col-md-4')) !!}			
			<div class="col-md-12">
			{!! Form::radio('pay_status', '1', true, ['id' => 'paid']) !!}
			<label for="paid" class = 'control-label col-md-3'><span></span>Paid</label>

			{!! Form::radio('pay_status', '0','', ['id' => 'unpaid']) !!}
			<label for="unpaid" class = 'control-label col-md-3'><span></span>Un Paid</label>
			</div>
		</div>		

		<div class="form-group">
			{!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-12">
				{!! Form::textarea('description', null, array('class' => 'form-control','rows'=>'3 ','cols'=>'40')) !!}
			</div>
		</div>

		<div class="form-group">
			{!! Form::label('color', 'Color', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				<div id="colorpicker" class="input-group colorpicker-component"> 
					{{Form::text('color', '#3D77A8', ['class'=>'form-control'])}}
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
	basic_functions();

	$(document).ready(function() {
	   basic_functions();
	   $('#colorpicker').colorpicker();
	});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true },
			code: { required: true },                
		},

		messages: {
			name: { required: "Holiyday Type is required." },
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
			url: '{{ route('holiday_types.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				code: $('input[name=code]').val(),
				pay_status: $('input[name=pay_status]:checked').val(),
				description: $('textarea[name=description]').val(),
				color: $('input[name=color]').val(),
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="holidaytype" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
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