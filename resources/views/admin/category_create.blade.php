<div class="modal-header">
	<h4 class="modal-title float-right">Add category items</h4>
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
					{!! Form::label('main_categoryname', 'maincategoryname', ['class' => ' control-label required']) !!}
				
					{!! Form::select('main_categoryname',$main_category,null,['class' => 'form-control select_item','id' => 'maincategory']) !!}
					</div>
				</div>
			</div>
			
				
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
			{!! Form::label('categoryname', 'Add  Category', array('class' => 'control-label  required','id'=>'maincategoryname')) !!}

				<div class="form-group">
				{!! Form::text('categoryname', null,['class' => 'form-control']) !!}
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
			main_categoryname:{ required: true },
			categoryname: { required: true ,
			
			
			remote:function(element) {
        return {
        	url: '{{ route('item_categorynamecreate_check') }}',
		 			type: "post",
            data: {
			 			 _token :$('input[name=_token]').val(),
						 main_categoryname: $('select[name=main_categoryname]').val(),
						  },
						}
						
						  						 
						}
					}
				},
	
messages: {
			//name: { required: "Unit Name is required." },
			categoryname: { required: " category name is required.",
			 remote: "The category Name is already exists!." },
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
			url: '{{ route('category_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				main_categoryname: $('select[name=main_categoryname]').val(),
				categoryname: $('input[name=categoryname]').val(),
				
				//description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td>`+data.data.id+`</td>
				     <td>`+data.data.name+`</td>
					<td>`+data.data.main_category+`</td>
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