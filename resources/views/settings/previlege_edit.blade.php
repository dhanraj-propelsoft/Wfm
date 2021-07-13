<div class="modal-header">
	<h4 class="modal-title float-right">Edit Privilege</h4>
</div>

	{!!Form::model($employee, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
  <div class="form-body">
	  {!! Form::hidden('id', null) !!}
	<div class="form-group">
	  {!! Form::label('name', 'Employee', array('class' => 'control-label col-md-4')) !!}

	  <div class="col-md-12">
	
		{!! Form::text('name', $employee_name, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
	  </div>
	</div>    
	<div class="form-group">
	  {!! Form::label('roles', 'Roles', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">
	  <select name="roles" class="form-control select_item"  multiple="multiple">
        @foreach($roles as $role)
            <option @if (App\User::find($user->id)->hasRole($role->name)) selected="" @endif value="{{$role->id}}">{{$role->display_name}}</option>
        @endforeach
      </select>
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
		roles: { required: true },
		//parent_department: { required: true },                
	},

	messages: {
		roles: { required: "Role is required." },
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
			 url: '{{ route('privilege.update') }}',
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				roles: $("select[name=roles]").val()         
				},
			 success:function(data, textStatus, jqXHR) {

			 	var html = ``;

			 	html += `<tr role="row" class="odd"><td>`+data.data.name+`</td><td>`;

			 	for (var i in data.data.roles) {
			 		html += `<label class="grid_label badge badge-success roles">`+data.data.roles[i]+`</label> `;
			 	}
				

				html += `</td><td><a data-id="`+data.data.id+`" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a> <a data-id="`+data.data.id+`" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a></td>
				</tr>`;

				call_back(html, `edit`, data.message, data.data.id);

				$('.loader_wall_onspot').hide();

				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>