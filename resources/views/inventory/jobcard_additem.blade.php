
<div class="modal-header">
	<h4 class="modal-title float-right">Add item Make</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		

			<div class="row">
				<div class="col-md-4">
					{!! Form::select('from[]',$items,null,array('class' => 'form-control lstview','id' => 'lstview','multiple'=>'multiple','size'=>'8')) !!}

              
				<br>

				{!! Form::select('from[]',$global_items,null,array('class' => 'form-control lstview','id' => 'lstview','multiple'=>'multiple','size'=>'8')) !!}

				
				
				</div>
				
				<div class="col-md-2">
					<button type="button" id="lstview_undo" class="btn btn-danger btn-block">undo</button>
					<button type="button" id="lstview_rightAll" class="btn btn-default btn-block"><i class="fa fa-forward"></i></button>
					<button type="button" id="lstview_rightSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-right"></i></button>
					<button type="button" id="lstview_leftSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-left"></i></button>
					<button type="button" id="lstview_leftAll" class="btn btn-default btn-block"><i class="fa fa-backward"></i></button>
					<button type="button" id="lstview_redo" class="btn btn-warning btn-block">redo</button>
				</div>
				
				<div class="col-md-4">
                    <select name="to[]" id="lstview_to" class="form-control country" size="12" multiple="multiple" style="height: 390px"></select>
				</div>
			</div>
		
			</div>
		</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="button" class="btn btn-success success">Submit</button>
</div>
	
{!! Form::close() !!}

{{--

	@stop

@section('dom_links')
@parent 				
 

--}}

<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	
    

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.lstview').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
          
        }
    });
});
</script>

<script>

	$('.success').on('click', function(e) {
		var country=[];

	$. each($(".country option"), function(){	
  
						country.push($(this).val());
					});

		console.log(country);
		

	});

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