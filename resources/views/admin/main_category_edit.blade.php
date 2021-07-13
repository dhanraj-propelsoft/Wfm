<div class="modal-header">
	<h4 class="modal-title float-right">Edit  Main category items</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
		  	<div class="form-group col-md-12"> 
				{{ Form::label('main_edit', 'Item Type', array('class' => 'control-label col-md-12 required')) }}
				<div class="col-md-12">
					<div class="row">
					     @foreach($main_edit_type as $type)
						<div class="col-md-4">
						<input type="radio" name="types" id="{{$type->name}}" value="{{$type->id}}" <?php echo ($type->id==$main_edit_category->category_type_id) ? 'checked' : 'false'; ?> 
											  		/>
						  <label for="{{$type->name}}"><span></span>{{$type->display_name}}</label>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>

								
		<div class="form-group">
			{!! Form::label('name', 'edit Main Category', array('class' => 'control-label col-md-4 required','id'=>'maincategoryname')) !!}
             
            {!! Form::hidden('id', $main_edit_category->id ) !!}
			<div class="col-md-12">
				{!! Form::text('categoryname', $main_edit_category->name,['class' => 'form-control']) !!}
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
			types: { required: true },
			id:{ reqired: true },
			categoryname: { 
				required: true,

remote:function(element) {
        return {
        	 url: '{{ route('categoryname_check') }}',
		 			type: "post",
		 			data: {
			 			 _token :$('input[name=_token]').val(),
						 types: $('input[name=types]:checked').val(),
						 id: $('input[name=id]').val(),              
        },
        }
				// remote: {
				// 		url: '{{ route('categoryname_check') }}',
				// 		type: "post",
				// 		data: {
				// 		 _token :$('input[name=_token]').val()
				// 		}
				// 	}
			},                
		},
	},

		messages: {
			//name: { required: "Unit Name is required." },
			categoryname: { required: "Main category name is required.",
			 remote: "The main category Name is already exists!" },                
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
			url: '{{ route('main_category.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				 id: $('input[name=id]').val(),
				categoryname: $('input[name=categoryname]').val(),
				types: $('input[name=types]:checked').val(),
				
				
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


function findselected() { 
    var result = $('input[name="types"]:checked').val();
    console.log(result);
    if(result=="Yes"){

        document.getElementById("maincategoryname").setAttribute('disabled', true);
    }
    else{
        document.getElementById("maincategoryname").removeAttribute('disabled');
    }
}
findselected();
</script>