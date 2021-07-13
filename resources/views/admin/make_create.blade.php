<div class="modal-header">
	<h4 class="modal-title float-right">Add  item Make</h4>
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
			{!! Form::label('itemmake', 'Add make ', array('class' => 'control-label  required','id'=>'itemtype')) !!}

				<div class="form-group">
				{!! Form::text('itemmake', null,['class' => 'form-control']) !!}
			</div></div>
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
			
			itemmake: { required: true , 
			
			remote:function(element) {
        return {
        	url: '{{ route('makename_check') }}',
		 			type: "post",
            data: {
			 			 _token :$('input[name=_token]').val(),
						
						  },
						}
						
						  						 
						}
			
				} 
		},
messages: {
			//name: { required: "Unit Name is required." },
		itemmake: { required: " item name is required.",
			 remote: "The item Name is already exists!." },
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
			url: '{{ route('itemmake_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
			
				itemmake: $('input[name=itemmake]').val(),
				
				//description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td>`+data.data.id+`</td>
				     <td>`+data.data.name+`</td>
				
					<td>`+data.data.created_by+`</td>
					<td>`+data.data.created_at+`</td>
					<td><a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
					<td>
											
										<label class="grid_label badge badge-success status">Active</label>
										<select style="display:none" id="`+data.data.status+`" class="active_status form-control">
										<option value="1">Active</option>
										<option value="0">In-active</option>
										</select>
									</td>
					
					</tr>`,`add`,data.message, data.data.id);

			$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});



</script>