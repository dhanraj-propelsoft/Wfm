<div class="modal-header">
	<h4 class="modal-title float-right">Edit Department</h4>
</div>

	{!!Form::model($department, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
  <div class="form-body">
	  {!! Form::hidden('id', null) !!}
	<div class="form-group">
	  {!! Form::label('name', 'Department Name', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('name', null,['class' => 'form-control']) !!}
	  </div>
	</div>    
	<div class="form-group">
	  {!! Form::label('parent_id', 'Parent Department', array('class' => 'control-label col-md-4')) !!}

	  <div class="col-md-12">
		{{Form::select('parent_id', $parent_department, null, ['class'=>'form-control select_item'])}}
	  </div>
	</div>    
	<div class="form-group">             
	  {!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}
	  
	  <div class="col-md-12">
		{!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
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
		name: { required: true },
		//parent_department: { required: true },                
	},

	messages: {
		name: { required: "Department Name is required." },
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
			 url: '{{ route('hrm_departments.update') }}',
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				name: $('input[name=name]').val(),
				parent_department: $('select[name=parent_id]').val(),
				description: $('textarea[name=description]').val()                
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
						<td>`+data.data.name+`</td>
						<td>`+data.data.parent_department+`</td>
						<td>`+data.data.description+`</td>
						<td>
							<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
							<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
								<option `+active_selected+` value="1">Active</option>
								<option `+inactive_selected+` value="0">In-Active</option>
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