<div class="modal-header">
	<h4 class="modal-title float-right">Edit Category Items</h4>
</div>

	{!!Form::model($category, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		<div class="form-group">
			{!! Form::label('type', 'Vehicle Type', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('type',$type,$selected_type->id, ['class'=>'form-control select_item'])}}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('name', 'Category Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name',$category_details->display_name,['class' => 'form-control']) !!}
			</div>
		</div>
	</div>
		</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

{{--

	@stop

@section('dom_links')
@parent 				
 

--}}

<script>
	$(document).ready(function() {

		

		basic_functions();
	});
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			//name: { required: true },
			name: { 
				required: true,
				remote: {
						url: '{{ route('vehicle_categoryname') }}',
						type: "post",
						data: {
						 	_token :$('input[name=_token]').val(),
						 	id:$('input[name=id]').val()
						}
					}
			},                
		},

		messages: {
			//name: { required: "Unit Name is required." },
			name: { required: "Category Name is required.", remote: "Category Name is already exists!" },                
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
			url: '{{ route('vehiclecategory_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id: $('input[name=id]').val(),
				type: $('select[name=type]').val(),
				category: $('input[name=name]').val(),
				
				
				//description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) {
				//console.log(data.data.status);
                var active_selected = "";
				var inactive_selected = "";
				
				var selected_text = "active";
				var selected_class = "badge-success";

				if(data.data.status == 1) {
					active_selected  = "selected";
					selected_text = "Active";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					inactive_selected = "selected";
					selected_text = "In-Active";
					selected_class = "badge-warning";
				} 
				call_back(`<tr role="row" class="odd">
					<td>`+data.data.id+`</td>
					<td>`+data.data.type.display_name+`</td>
					<td>`+data.data.name+`</td>
				
					<td>`+data.data.created_by+`</td>
					<td>`+data.data.created_at+`</td>
					<td> <a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control">
							<option `+active_selected+` value="1">active</option>
							<option `+inactive_selected+`value="2">inactive</option>
							
						</select>
					</td>
					
					</tr>`,`edit`, data.message, data.data.id);

				$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});



</script>