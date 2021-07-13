<div class="modal-header">
	<h4 class="modal-title float-right">Edit Business Nature</h4>
</div>

	{!!Form::model($businessnature, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		<div class="form-group">
			{!! Form::label('display_name', 'Business Nature Name', array('class' => 'control-label col-md-5 required')) !!}

			<div class="col-md-12">
				{!! Form::text('display_name', null,['class' => 'form-control']) !!}
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
	$(document).ready(function() {

		basic_functions();
	});
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			display_name: {
				required: true,
				remote: {
					url: '{{ route('check_business_nature_name') }}',
					type: "post",
					data: {
					 	_token :$('input[name=_token]').val(),
					 	id:$('input[name=id]').val()
					}
				}
			}
		},

		messages: {
			display_name: { required: "Business Nature Name is required.", 
			remote: "Business Nature Name is already exists!" }
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
			url: '{{ route('business_nature.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				name: $('input[name=display_name]').val(),                
				},
			success:function(data, textStatus, jqXHR) {

				var active_selected = "";
				var inactive_selected = "";
				var selected_text = "In-Active";
				var selected_class = "badge-warning";

				if(data.data.status == 1) {
					active_selected = "selected";
					selected_text = "Active";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					inactive_selected = "selected";
				}

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="department" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.display_name+`</td>
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

</script>