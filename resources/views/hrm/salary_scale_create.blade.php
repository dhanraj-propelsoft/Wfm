<div class="modal-header">
	<h4 class="modal-title float-right">Add Salary Scale</h4>
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
					{!! Form::label('name', 'Name', array('class' => 'control-label  required')) !!}				
					{!! Form::text('name', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('code', 'Code', array('class' => 'control-label')) !!}
			
				{!! Form::text('code', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('frequency_id', 'Frequency', ['class' => 'control-label']) !!}
				
					{!! Form::select('frequency_id', $payroll_frequency, null, ['class' => 'form-control select_item', 'id' => 'frequency_id']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6" style="border:1px solid #ccc">
				<div class="row" style="background: #ccc; padding: 10px 0; border:1px solid #b2b2b2;">
					<div class="col-md-12">Earnings</div>
				</div>

				<div class="row earnings" style="padding: 10px 0;">
					<div class="col-md-6">
						{!! Form::select('earning_id', $salary_earnings, null, ['class' => 'form-control select_item', 'id' => 'earning_id']) !!}
					</div>
					<div class="col-md-4">
						{!! Form::text('earning_value',null,['class' => 'form-control', 'disabled' => 'disabled']) !!}
					</div>

					<div class="col-md-1 action_container">
					@if(count($salary_earnings) > 2)
					<div class="grid_label action-btn edit-icon add_row_column"><i class="fa fa-plus"></i></div>
					@endif
					</div>
				</div>
			</div>

			<div class="col-md-6" style="border:1px solid #ccc">
				<div class="row" style="background: #ccc; padding: 10px 0; border:1px solid #b2b2b2;">
					<div class="col-md-12">Deductions</div>
				</div>

				<div class="row deduction" style="padding: 10px 0;">
					<div class="col-md-6">
						{!! Form::select('deduction_id', $salary_deduction, null, ['class' => 'form-control select_item', 'id' => 'deduction_id']) !!}
					</div>
					<div class="col-md-4">
						{!! Form::text('deduction_value',null,['class' => 'form-control', 'disabled' => 'disabled']) !!}
					</div>
					
					<div class="col-md-1 action_container">
						@if(count($salary_deduction) > 2)
						<div class="grid_label action-btn edit-icon add_row_column"><i class="fa fa-plus"></i></div>
						@endif
					</div>
					
				</div>
			</div>
		</div>
		<br>

		<div class="row">
			<div class="col-md-3">
				{{ Form::checkbox('round','1',null, ['id' => 'round']) }}
				<label class="control-label" for="round"><span></span>Round Off</label>
			</div>
		</div>

		<div class="row round_off" style="display: none;">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::select('round_off', ['' => 'Select','0'=>'Normal','1'=>'Upward','2'=>'Downward'], null, ['class' => 'form-control select_item', 'id' => 'round_off']) !!}
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

	 $("input[name=round]").on('change', function(){
		if($(this).is(':checked'))	{			
			$('.round_off').show();
		}
		else{
			$('.round_off').hide();
		}
	});

	$("body").on('click', '.remove_column', function() {
		var obj = $(this);
		var selected_option = obj.closest(".row").find('select').find(':selected').val();
		var attr_name = obj.closest(".row").find('select').attr('name');
		var attr_name_text = "'"+attr_name+"'";
		var last_row = obj.closest(".row").parent().find('.row').last();
		obj.closest(".row").remove();

		if(attr_name_text.indexOf("earning_id") > 0) {

			var class_name = 'earnings';

			$('.'+class_name).find('select[name="'+attr_name+'"] option[value="' + selected_option + '"]').unwrap();
			if($('.'+class_name).length == 1) {
				$('.'+class_name).last().find('.action_container').html("<div class='grid_label action-btn edit-icon add_row_column'><i class='fa fa-plus'></i></div>");
			}
			 else if($('.'+class_name).length > 1) {
				$('.'+class_name).last().find('.action_container').html("<div class='grid_label action-btn edit-icon add_row_column'><i class='fa fa-plus'></i></div> <div class='grid_label action-btn delete-icon remove_column'><i class='fa fa-trash-o'></i></div>");
			} else {
				$('.'+class_name).last().find('.action_container').html("<div class='grid_label action-btn edit-icon add_row_column'><i class='fa fa-plus'></i></div>");

				$('.'+class_name).find('select[name="'+attr_name+'"] span option').unwrap();
			}

		}  else if(attr_name_text.indexOf("deduction_id")) {

			var class_name = 'deduction';

			$('.'+class_name).find('select[name="'+attr_name+'"] option[value="' + selected_option + '"]').unwrap();
			if($('.'+class_name).length == 1) {
				$('.'+class_name).last().find('.action_container').html("<div class='grid_label action-btn edit-icon add_row_column'><i class='fa fa-plus'></i></div>");
			}
			 else if($('.'+class_name).length > 1) {
				$('.deduction').last().find('.action_container').html("<div class='grid_label action-btn edit-icon add_row_column'><i class='fa fa-plus'></i></div> <div class='grid_label action-btn delete-icon remove_column'><i class='fa fa-trash-o'></i></div>");
			} else {
				$('.'+class_name).last().find('.action_container').html("<div class='grid_label action-btn edit-icon add_row_column'><i class='fa fa-plus'></i></div>");

				$('.'+class_name).find('select[name="'+attr_name+'"] span option').unwrap();
			}
		}
	});

	$("body").on('click', '.add_row_column', function() {
		var obj = $(this);
		var selected_option = obj.closest(".row").find('select');
		var selected_opt = obj.closest(".row").find('select > option');
		var last_row = obj.closest(".row");

		if(selected_option.val() != "") {
			$('.select_item').each(function() { 
				var select = $(this);  
				if(select.data('select2')) { 
					select.select2("destroy"); 
				}
			});

			var last_clone = obj.closest(".row").clone(true);
			var last_selected = selected_option.find(':selected').val();
			last_clone.find('select option[value="' + last_selected + '"]').wrap('<span>');
			//console.log(selected_option.children().length);
			if(selected_opt.length > 2) {
				last_clone.insertAfter(last_row);
			}
			last_clone.find('input[type=text]').val("");
			obj.parent().html("<div class='grid_label action-btn delete-icon remove_column'><i class='fa fa-trash-o'></i></div>");
			if(selected_opt.length > 3) {
				last_row.next().find('.action_container').html("<div class='grid_label action-btn edit-icon add_row_column'><i class='fa fa-plus'></i></div> <div class='grid_label action-btn delete-icon remove_column'><i class='fa fa-trash-o'></i></div>");
			} else {
				last_row.next().find('.action_container').html("<div class='grid_label action-btn delete-icon remove_column'><i class='fa fa-trash-o'></i></div>");
			}
			selected_option.find('option[value!="' + last_selected + '"]').wrap('<span>');
			$('.select_item').select2();
		}
	});

	/*$('body').off('click', '.add_row').on('click', '.add_row', function() {
		
		$('.select_item').each(function() { 
			var select = $(this);  
			if(select.data('select2')) { 
				select.select2("destroy"); 
			}
		});

		var clone = $(this).closest('.row').clone();
		clone.find('select, input[type=text]').val("");
		clone.find('.date-picker').datepicker({
			rtl: false,
			orientation: "left",
			todayHighlight: true,
			autoclose: true
		});

		clone.find('.action_container').html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

		$(this).closest('.row').after(clone);
		$('.select_item').select2();
	  });

		$('body').on('click', '.remove_row', function() {
		$(this).closest('.row').remove();
	});*/


	$("select[name='earning_id']").on('change', function() {
		if($(this).val() != "") {
			$(this).closest(".row").find("input[name='earning_value']").prop('disabled', false);
		} else {
			$(this).closest(".row").find("input[name='earning_value']").prop('disabled', true);
			$(this).closest(".row").find("input[name='earning_value']").val("");
		}
	});

	$("select[name='deduction_id']").on('change', function() {
		if($(this).val() != "") {
			$(this).closest(".row").find("input[name='deduction_value']").prop('disabled', false);
		} else {
			$(this).closest(".row").find("input[name='deduction_value']").prop('disabled', true);
			$(this).closest(".row").find("input[name='deduction_value']").val("");
		}
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
			url: '{{ route('salary_scale.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				code: $('input[name=code]').val(),
				frequency_id: $('select[name=frequency_id]').val(),
				
				earning_id: $("select[name=earning_id]").map(function() { 
                        return this.value; 
                    }).get(),
				earning_value: $("input[name=earning_value]").map(function() { 
                        return this.value; 
                    }).get(),
				deduction_id: $("select[name=deduction_id]").map(function() { 
                        return this.value; 
                    }).get(),
				deduction_value: $("input[name=deduction_value]").map(function(){
                        return this.value; 
                    }).get(),
				
				round_off: $('select[name=round_off]').val(),
				round_off_limit: $('input[name=round_off_limit]').val(),
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="salary_scale" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
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