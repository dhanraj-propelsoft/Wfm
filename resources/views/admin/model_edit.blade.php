<div class="modal-header">
	<h4 class="modal-title float-right">Edit  Items</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">
		<div class="form-group col-md-12"> 
			{{ Form::label('categorytype', 'Item Type', array('class' => 'control-label col-md-12 required')) }}
				<div class="col-md-12">
					<div class="row">
		@foreach($inventory_types  as $types)
		<div class="col-md-4">
			<input type="radio" name="types" id="{{$types->name}}"  value="{{$types->id}}" <?php echo ($types->id==$model->item_id) ? 'checked' : 'false'; ?>/>
			<label for="{{$types->name}}"><span></span>{{$types->display_name}}</label>
		</div>
		@endforeach
					</div>
				</div>
			</div>
	
								
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('maincategoryname', 'maincategoryname', ['class' => ' control-label required']) !!}
				
					{!! Form::select('maincategoryname',$main_category,$model->main_category_id,['class' => 'form-control select_item','id' => 'maincategory']) !!}
			
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('categoryname', 'categoryname', ['class' => ' control-label required']) !!}
				
					{!! Form::select('categoryname',$category,$model->category_id,['class' => 'form-control select_item']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('type', 'type', ['class' => ' control-label required']) !!}
				
					{!! Form::select('type',$type,$model->typeid,['class' => 'form-control select_item']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('make', 'make', ['class' => ' control-label required']) !!}
				
					{!! Form::select('make',$make,$model->makeid,['class' => 'form-control select_item']) !!}
				</div>
			</div>
		</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
			{!! Form::label('model', 'Edit   model', array('class' => 'control-label  required','id'=>'maincategoryname')) !!}
{!! Form::hidden('itemid', $item->id ) !!}
{!! Form::hidden('maincategoryid', $model->main_category_id) !!}
{!! Form::hidden('itemcategorytype', $model->item_id) !!}				

<div class="form-group">
				{!! Form::text('model',$model->modelname,['class' => 'form-control']) !!}
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
			itemid:{
				required: true },
			
			categoryname: { 
				required: true },
			type: { 
				required: true },
					make: { 
				required: true },
			model: { 
				required: true ,

				
				remote:function(element) {
        return {
        	 url: '{{ route('checkedit _model') }}',
		 			type: "post",
		 			data: {
			 			 _token :$('input[name=_token]').val(),

						 itemid: $('input[name=itemid]').val(),    
						            
        				categoryname: $('select[name=categoryname]').val(),
        				type: $('select[name=type]').val(),
        				make: $('select[name=make]').val(),
						
        },
        }
        },
       
			},                
		},

		messages: {
			//name: { required: "Unit Name is required." },
			model: { required: " model name is required.",
			 remote: "The model Name is already exists!" },                
		},
	
	// $('.validateform').validate({
	// 	errorElement: 'span', //default input error message container
	// 	errorClass: 'help-block', // default input error message class
	// 	focusInvalid: false, // do not focus the last invalid input
	// 	rules: {
	// 		types:{ required: true },
	// 		name: { required: true },
			
	// 		//parent_department: { required: true },
	// 	},

	// 	messages: {
	// 		types: { required: "Select must in type  required." },
	// 		name: { required: "Main Category Name is  required." },

	// 		//parent_department: { required: "Parent Department Name is required." },
	// 	},

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
			url: '{{ route('model_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				 types: $('input[name=types]:checked').val(),
				 itemcategoryid: $('input[name=itemcategorytype]').val(),   
						itemid: $('input[name=itemid]').val(), 

						maincategoryid: $('input[name=maincategoryid]').val(),
						 maincategoryname: $('select[name=maincategoryname]').val(), 
						  model: $('input[name=model]').val(),   
						           
        				categoryname: $('select[name=categoryname]').val(),
        				type: $('select[name=type]').val(),
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
				     <td>`+data.data.name+`</td>
				     <td>`+data.data.itemtype+`</td>
				     <td>`+data.data.main+`</td>
					<td>`+data.data.category+`</td>
					     <td>`+data.data.type+`</td>
					<td>`+data.data.make+`</td>
					
					<td></td>
					<td>`+data.data.created_by+`</td>
					<td>`+data.data.created_at+`</td>
					<td><a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
						<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control">
							<option `+active_selected+` value="1">Active</option>
							<option `+inactive_selected+`value="2">In-active</option>
							
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
    
$('#maincategory').change(function(){
	
//console.log("workit");
          var catID = $(this).val();  
          //console.log(makeID);  
    if(catID){
         $.ajax({
         		 type:"GET",
	           url:"{{url('admin/get_category_name_list')}}/"+catID,
	           success:function(res){               
			            if(res)
			            {
			                $("#categoryname").empty();
			                $("#categoryname").append('<option>Select</option>');
			                $.each(res,function(key,value){
			                    $("#categoryname").append('<option value="'+key+'">'+value+'</option>');
			                });
			           
			            }
			            else{
			               $("#categoryname").empty();
		                }
          		 }
                });
    }else{
        $("#categoryname").empty();
       
    }      
   });
</script>