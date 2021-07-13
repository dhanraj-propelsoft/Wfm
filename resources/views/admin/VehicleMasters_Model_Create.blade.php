<div class="modal-header">
	<h4 class="modal-title float-right">Add  model</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('type', 'Select Type', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('type',$type, null, ['class'=>'form-control select_item'])}}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('category', 'Select Category', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('category',[], null, ['class'=>'form-control select_item','id'=>'category'])}}
			</div>
		</div>
				<div class="form-group">
			{!! Form::label('make', 'Select Make', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('make',$itemtype, null, ['class'=>'form-control select_item','id'=>'make'])}}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('model', 'Add model', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::text('model',null, ['class'=>'form-control'])}}
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

		
     $('select[name=type]').on('change',function(){
          var type_id = $(this).val();
          if(type_id != ''){
   		$.ajax({
        url: '{{ route('vehicle_model.get_typecategory') }}',
        type: 'get',
        data: {
         type_id: type_id,            
        },
        success:function(data, textStatus, jqXHR) {
       
       $('select[name=category]').empty();
	   $('select[name=category]').append('<option value="">Select Category</option>');
		for(var i in data) {
			var category_name= data[i].display_name;
			if(category_name == null){
				category_name = '';
			}else{
				category_name= data[i].display_name;
			}
							$('select[name=category]').append('<option value="'+data[i].id+'">'+category_name+'</option>');
						}
       
       
        },
        error:function(jqXHR, textStatus, errorThrown) {
        
        }
      });
   }
     });
		
	});
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			type: { required: true },
			category: { required: true },
			make : { required: true },      
			model: { 
				required: true,
				remote:function(element)
							{
								return{
								
									url: '{{ route('vehilclemodel_check')}}',
									type: "post",
									data: {
										 _token :$('input[name=_token]').val(),
										category:$('#category').val(),
										make: $('#make').val(),
									},
								}
							}
			}, 
		         
		},

		messages: {
			//name: { required: "Unit Name is required." },
			model: { required: "Vehicle Model Name is required.", remote: "Vehicle Model Name is already exists!" },  
			make: { required: "Make Name is required." },   
			type: { required: "Type Name is required." }, 
			category: { required: "Category Name is required." },            
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
			url: '{{ route('vehiclemodel_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				type:$('select[name=type]').val(),
				category:$('select[name=category]').val(),
				make: $('select[name=make]').val(),
				model: $('input[name=model]').val(),
				
				//description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) {
				call_back(`<tr role="row" class="odd">
					<td>`+data.data.id+`</td>
				     <td>`+data.data.type.display_name+`</td>
				     <td>`+data.data.category.display_name+`</td>
					<td>`+data.data.make+`</td>
					<td>`+data.data.model+`</td>
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
					
					</tr>`,`add`, data.message);

			$('.loader_wall_onspot').hide();
				


				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});



</script>