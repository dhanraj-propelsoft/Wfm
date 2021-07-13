<div class="modal-header">
	<h4 class="modal-title float-right">Add   variants</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

		<div class="modal-body">
			<div class="form-body">
				<div class="form-group">
			{!! Form::label('model', 'Vehicle Model', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('model',$model, null, ['class'=>'form-control select_item', 'id' => 'model_id'])}}

			</div>
		</div>
		<div class="form-group">
			{!! Form::label('make', 'Vehicle Make', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::text('make',null, ['class'=>'form-control','id'=>'','disabled'])}}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('category', 'Vehicle Category', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('category',$category,null, ['class'=>'form-control select_item', 'id' => 'category_id'])}}
			</div>
		</div>
				<div class="form-group">
			{!! Form::label('type', 'Vehicle Type', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('type',$type,null, ['class'=>'form-control select_item', 'id' => 'type_id'])}}
			</div>
		</div>
            
			   <div class="form-group">
			{!! Form::label('name', 'Vehicle Variant Name', array('class' => 'control-label col-md-5 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control','id'=>'']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('version', 'Version', array('class' => 'control-label col-md-4 required ')) !!}

			<div class="col-md-12">
				
				 {!! Form::select('version', [], null,array('class' => 'select-tag form-control select2-hidden-accessible', 'data-date-format' => 'dd-mm-yyyy','id'=>'tags','data-select2-id'=>'10','tabindex'=>'-1', 'aria-hidden'=>'true','multiple')) !!}
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

     
$('select[name=model]').on('change',function(){
   var model_id = $(this).val();
   if(model_id != ''){
   		$.ajax({
        url: '{{ route('vehicle_varient.get_details') }}',
        type: 'get',
        data: {
         model_id: model_id,            
        },
        success:function(data, textStatus, jqXHR) {
        $('input[name=make]').attr('id',data.data.make_id);
        $('input[name=make]').val(data.data.make);
        if(data.data.category_id == null){
        	$('select[name=category]').append(`<option value="">`+data.category.category+`</option>`);
        }else{
        $('select[name=category]').html(`<option value=`+data.data.category_id+`>`+data.data.category+`</option>`);
        }
        if(data.data.type_id == null){
        	$('select[name=type]').append(`<option value="">`+data.type.type+`</option>`);
        }else{
        	 $('select[name=type]').html(`<option value=`+data.data.type_id+`>`+data.data.type+`</option>`);
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
			
			
			make: { required: true } ,
			category: { required: true } ,
			model: { required: true } ,
			version:{ required: true },
			variant: { required: true ,
					/*remote:function(element) 
					{
	        		return
	        			{
	        				url: '{{ route('vehiclecheck_varient') }}',
			 				type: "post",
	           				data: 
	           				{
					 			 _token :$('input[name=_token]').val(),
								model: $('select[name=model]').val(),
								make: $('select[name=make]').val(),
								version: $('select[name=version]').val(),
							},
						}
					}*/
				}
			},
	
	messages: {
		model: { required: "model  Name is required." },
		make: { required: "Make  Name is required." },
		version: { required: "version is required." },
		category: { required: "Category is required." },
		variant: { required: " variant name is required.",
		remote: "The varient Name is already exists!." },
		},

		// messages: {
		// 	categoryname: { required: "Select must  category name  required." },
		// 	remote: { required: " Category Name is  allready exist." },

		// 	//parent_department: { required: "Parent Department Name is required." },
		// },

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
			url: '{{ route('vehicle_varient_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				model_id:$('select[name=model]').val(),
				make_id:$('input[name=make]').attr('id'),
				category_id:$('select[name=category]').val(),
				type_id:$('select[name=type]').val(),
				varient:$('input[name=name]').val(),
				version:$('select[name=version]').val(),
				description:$('textarea[name=description]').val()
				},
			success:function(data, textStatus, jqXHR)
			{

				if(data.status==1)
				{

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
											
										<label class="grid_label badge badge-success status">Active</label>
										<select style="display:none" id="`+data.data.status+`" class="active_status form-control">
										<option value="1">Active</option>
										<option value="0">In-active</option>
										</select>
									</td>
					</tr>`,`add`,data.message, data.data.id);

				$('.loader_wall_onspot').hide();
			
				}
				else
				{
					alert_message(data.message,'success');
					
				}
				$('.loader_wall_onspot').hide();
				$('.crud_modal').modal('hide');
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
 $(".select-tag").select2({
            tags: true,
            tokenSeparators: [',', ' '],
            templateSelection: function (data, container) {
                var selection = $('.select-tag').select2('data');
                var idx = selection.indexOf(data);
                return data.text;
            },
        })
 $('.buttons-excel').css('display','none');


$('body').on('click', '.excel_export', function(){
        $(".buttons-excel")[0].click(); //trigger the click event
    });

</script>