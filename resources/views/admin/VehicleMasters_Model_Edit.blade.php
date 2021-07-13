<div class="modal-header">
	<h4 class="modal-title float-right">Edit  Model </h4>
</div>

	{!!Form::model($vehicle_model, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		<div class="row">
			<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('type', 'Type', ['class' => ' control-label required']) !!}
				
					{!! Form::select('type',$type,$selected_type->id,['class' => 'form-control select_item']) !!}
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('category', 'Category', ['class' => ' control-label required']) !!}
				
					{!! Form::select('category',$category,$selected_category->id,['class' => 'form-control select_item']) !!}
					</div>
				</div>
			</div>
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('make', 'makename', ['class' => ' control-label required']) !!}
				
					{!! Form::select('make',$make,$model->vehicle_make_id,['class' => 'form-control select_item']) !!}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
			{!! Form::label('model', 'Edit  model', array('class' => 'control-label  required','id'=>'item')) !!}
{!! Form::hidden('modelid', $model->id ) !!}
				<div class="form-group">
				{!! Form::text('model',$model->name,['class' => 'form-control']) !!}
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
			
			make: { 
				required: true },
			modelid: { 
				required: true },
			model:{ 
				required: true ,

				
				remote:function(element) {
        return {
        	 url: '{{ route('vehilclemodel_check') }}',
		 			type: "post",
		 			data: {
						 	_token :$('input[name=_token]').val(),
						 	id:$('input[name=id]').val(),
						 	make:$('select[name=make]').val(),
						 	category:$('select[name=category]').val(),
						}
       			 }
        									},
        			},                
					},

		messages: {
			//name: { required: "Unit Name is required." },
			model: { required: " model name is required.",
			 remote: "The model Name is already exists!" },                
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
			url: '{{ route('vehiclemodel_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				type:$('select[name=type]').val(),
				category:$('select[name=category]').val(),
				modelid: $('input[name=modelid]').val(),
				model: $('input[name=model]').val(),
		make: $('select[name=make]').val(),
				
				
				//description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) {
				
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
				     <td>`+data.data.category.display_name+`</td>
					<td>`+data.data.make+`</td>
						<td>`+data.data.model+`</td>
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