	<div class="modal-header">
	<h4 class="modal-title float-right">Edit  variant items</h4>
</div>

	
	{!!Form::model($vehicle_variant, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
				<div class="form-group">
			{!! Form::label('model', 'Vehicle Model', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('model',$model,$selected_values->id, ['class'=>'form-control select_item', 'id' => 'model_id'])}}

			</div>
		</div>
		<div class="form-group">
			{!! Form::label('make', 'Vehicle Make', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::text('make',$selected_values->make, ['class'=>'form-control','id'=>$selected_values->make_id,'disabled'])}}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('category', 'Vehicle Category', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('category',$category,$selected_values->category_id, ['class'=>'form-control select_item', 'id' => 'category_id'])}}
			</div>
		</div>
				<div class="form-group">
			{!! Form::label('type', 'Vehicle Type', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('type',$type,$selected_values->type_id, ['class'=>'form-control select_item', 'id' => 'type_id'])}}
			</div>
		</div>
            
			   <div class="form-group">
			{!! Form::label('name', 'Vehicle Variant Name', array('class' => 'control-label col-md-5 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', $selected_values->varient,['class' => 'form-control','id'=>'']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('version', 'Version', array('class' => 'control-label col-md-4 required ')) !!}

			<div class="col-md-12">
				
				 <select name="version" class="select-tag form-control  select2-hidden-accessible" id="tags"  multiple>
			            <option value="<?php echo $selected_values->version ?>" selected="selected">{{ $selected_values->version }}</option>
			            
			     </select> 
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

{{--

	@stop

@section('dom_links')
@parent 				
 

--}}

<script>
	$(document).ready(function() {

		

		
	});
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
		    make: { required: true } ,
			category: { required: true } ,
			model: { required: true } ,
			version: { 
				required: true },
			variantid: { 
				required: true },
			variant: 
			{ 
				required: true ,

				
				remote:function(element) 
				{
       			 return {
        				 	url: '{{ route('vehiclevariant_editcheck') }}',
				 			type: "post",
				 			data: {
						 			 _token :$('input[name=_token]').val(),
						 			  variantid: $('input[name=variantid]').val(),    
									 version: $('select[name=version]').val(),      
									          
        				
						
        						  },
        				}
      			},
       
			},                
		},

		messages: 
		{
    		model: { required: "model  Name is required." },
    		category: { required: "Category is required." },
    		make: { required: "Make  Name is required." },
    		version: { required: "version is required." },
			variant: { required: " variant name is required.",
			 remote: "The variant Name is already exists!" },                
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
			url: '{{ route('variant_update') }}',
			type: 'post',
			data: {
						_token: '{{ csrf_token() }}', 
						id: $('input[name=id]').val(),
						model_id:$('select[name=model]').val(),
						make_id:$('input[name=make]').attr('id'),
						category_id:$('select[name=category]').val(),
						type_id:$('select[name=type]').val(),
						varient:$('input[name=name]').val(),
						version:$('select[name=version]').val(),
						description:$('textarea[name=description]').val()              
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
					<td>`+data.data.name+`</td>
					<td>`+data.data.version+`</td>
					<td>`+data.data.created_by+`</td>
					<td>`+data.data.created_at+`</td>
					<td><a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
				
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control">
							<option `+active_selected+` value="1">active</option>
							<option `+inactive_selected+`value="2">inactive</option>
							
						</select>
					</td>
				</tr>`,`edit`,data.message, data.data.id);


				$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});


$('#make').change(function(){
	

          var makeID = $(this).val();    
    if(makeID){
         $.ajax({
         		 type:"GET",
	           url:"{{url('admin/vehicleget_model-list')}}/"+makeID,
	           success:function(res){               
			            if(res)
			            {
			                $("#model").empty();
			                $("#model").append('<option>Select</option>');
			                $.each(res,function(key,value){
			                    $("#model").append('<option value="'+key+'">'+value+'</option>');
			                });
			           
			            }
			            else{
			               $("#model").empty();
		                }
          		 }
                });
    }else{
        $("#model").empty();
       
    }      
   });
 var colors = ["#919191"];

        $('.select-tag').select2({
		    tags: true,
		    multiple: true,
		    tokenSeparators: [',']
		});
</script>