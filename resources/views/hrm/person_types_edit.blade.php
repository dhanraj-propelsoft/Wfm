<div class="modal-header">
	<h4 class="modal-title float-right">Edit Person Type</h4>
</div>

	{!!Form::model($person, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
  <div class="form-body">
	  {!! Form::hidden('id', null) !!}
	<div class="form-group">
	  {!! Form::label('name', 'Person Type Name', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">
		{!! Form::text('name', null,['class' => 'form-control']) !!}
	  </div>
	</div>
	<div class="form-group">             
	  {!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}
	  
	  <div class="col-md-12">
		{!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
	  </div>
	</div>
	<div class="form-group col-md-12">
	  {!! Form::checkbox('type','1', null, array('id' => 'holiday')) !!}
	  <label for="holiday"> <span></span> Consider as Employee </label>
	</div>
  </div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
  
{!! Form::close() !!}
				
<script>
  $('.validateform').validate({
	errorElement: 'span', //default input error message container
	errorClass: 'help-block', // default input error message class
	focusInvalid: false, // do not focus the last invalid input
	rules: {
		name: { required: true },                
	},

	messages: {
		name: { required: "Person Type Name is required." },              
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
			 url: '{{ route('person_types.update') }}',
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				name: $('input[name=name]').val(),                
				description: $('textarea[name=description]').val(),
				type: $('input[name=type]:checked').val(),              
				},
			 success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="department" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					<td>`+data.data.name+`</td>
					<td>`+data.data.description+`</td>
					<td>`+data.data.type+`</td>
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