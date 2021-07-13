
<div class="modal-header">
	<h4 class="modal-title float-right">Add  items</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			
			<div class="form-group col-md-12"> 
			{{ Form::label('categorytype', 'Item Type', array('class' => 'control-label col-md-12 required')) }}
			<div class="col-md-12">
			  <div class="row">
			   @foreach($inventory_types as $type)
				<div class="col-md-4">
			 		<input type="radio" name="categorytype" class="categorytype"id="{{$type->id}}" value="{{$type->id}}" <?php ($type->id=="1") ? 'selected=selected' : ''; ?> />
			  		<label for="{{$type->id}}"><span></span>{{$type->display_name}}</label>
				</div>
			   @endforeach
			 </div>
			</div>
		    </div>
														
		</div>
	</div>
				 			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('maincategoryname', 'maincategoryname', ['class' => ' control-label required']) !!}
				
					{!! Form::select('maincategoryname',[],null,['class' => 'form-control select_item','id' => 'maincategory']) !!}
			
					</div>
				</div>
			</div>
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('categoryname', 'categoryname', ['class' => ' control-label required']) !!}
				
					{!! Form::select('categoryname',[],null,['class' => 'form-control select_item','id' => 'category']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('type', 'type', ['class' => ' control-label required']) !!}
				
					{!! Form::select('type',[],null,['class' => 'form-control select_item','id' => 'type']) !!}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('make', 'make', ['class' => ' control-label required']) !!}
				
					{!! Form::select('make',[],null,['class' => 'form-control select_item','id' => 'make']) !!}
					</div>
				</div>
			</div>
				
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
			{!! Form::label('model', 'Add  item', array('class' => 'control-label  required','id'=>'item')) !!}

				<div class="form-group">
				{!! Form::text('model', null,['class' => 'form-control']) !!}
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
	// function Get_radio()
	// {
	// 	if($('input[type=select] label span').hasClass('input[type=select]:selected+label span'))
	// 	{
		

	// 	}
	// }
 //  var checked_box_val=null;
	// $('body').on('click','label span',function()
	// {
	// 		 var id=$(this).closest('label').attr('for');
		
	// 		$('#'+id).attr('checked',true);
	// 		checked_box_val = $('#'+id).val();
	// 		console.log(checked_box_val);
	// })
	$('.select_item').on('change', function() {
    var responseId = $(this).val();
    console.log(responseId);
});
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			categorytype:{ required: true },
			maincategoryname:{required :true },
			categoryname:{ required: true },
			type: { required: true } ,
			make: { required: true } ,
			model: { required: true ,
			
			
			remote:function(element) {
        return {
        	url: '{{ route('check_model') }}',
		 			type: "post",
            data: {
			 			 _token :$('input[name=_token]').val(),
						categoryname: $('select[name=categoryname]').val(),
						type: $('select[name=type]').val(),
						make: $('select[name=make]').val(),
						
						  },
						}
						
						  						 
						}
					}
				},
	
messages: {
			//name: { required: "Unit Name is required." },
			model: { required: " model name is required.",
			 remote: "The model Name is already exists!." },
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
			url: '{{ route('model_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				categorytype: $('input[name=categorytype]:checked').val(),
				
				maincategoryname: $('select[name=maincategoryname]').val(),
				maincategoryname1: $('select[name=maincategoryname]').text(),
				categoryname: $('select[name=categoryname]').val(),

				type: $('select[name=type]').val(),
				make: $('select[name=make]').val(),
				
				model: $('input[name=model]').val(),
				        
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td>`+data.data.id+`</td>
				     <td>`+data.data.name+`</td>
				      <td>`+data.data.categorytype+`</td>
				     <td>`+data.data.main+`</td>
					<td>`+data.data.category+`</td>
					     <td>`+data.data.type+`</td>
					<td>`+data.data.make+`</td>
					
					<td></td>
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
$('.categorytype').click(function(){

          var categorytypeID = $(this).val();    
    if(categorytypeID){
        $.ajax({
           type:"GET",
           url:"{{url('admin/get-categorytype-list')}}/"+categorytypeID,
           success:function(res){               
            if(res){
                $("#maincategory").empty();
                $("#maincategory").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#maincategory").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#maincategory").empty();
            }
           }
        });
    }else{
        $("#maincategory").empty();
        $("#category").empty();
    }      
   });
 $('#maincategory').change(function(){
 	//console.log('work it');
          var categoryID = $(this).val();    
    if(categoryID){
        $.ajax({
           type:"GET",
           url:"{{url('admin/get-category-list')}}/"+categoryID,
           success:function(res){               
            if(res){
                $("#category").empty();
                $("#category").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#category").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#category").empty();
            }
           }
        });
    }else{
        $("#category").empty();
        $("#type").empty();
    }      
   });
   $('#category').change(function(){
    var typeID = $(this).val();    
    if(typeID){
        $.ajax({
           type:"GET",
           url:"{{url('admin/get-type-list')}}/"+typeID,
           success:function(res){               
            if(res){
                $("#type").empty();
                 $("#type").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#type").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#type").empty();
            }
           }
        });
    }else{
        $("#type").empty();
    }
        
   });

    $('#type').change(function(){
    var makeID = $(this).val();    
    if(makeID){
        $.ajax({
           type:"GET",
           url:"{{url('admin/get-make-list')}}/"+makeID,
           success:function(res){               
            if(res){
                $("#make").empty();
                 $("#make").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#make").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#make").empty();
            }
           }
        });
    }else{
        $("#make").empty();
    }
        
   });
</script>