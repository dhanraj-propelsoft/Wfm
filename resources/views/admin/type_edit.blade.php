<div class="modal-header">
	<h4 class="modal-title float-right">Edit Item Type</h4>
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
					{!! Form::label('categoryname', 'categoryname', ['class' => ' control-label required']) !!}
				
					{!! Form::select('categoryname',$categorys,$itemtype->category_id,['class' => 'form-control select_item']) !!}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
			{!! Form::label('item', 'Edit   Category', array('class' => 'control-label  required','id'=>'item')) !!}
{!! Form::hidden('itemid', $itemtype->id ) !!}
				<div class="form-group">
				{!! Form::text('item',$itemtype->name,['class' => 'form-control']) !!}
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
			
			categoryname: { 
				required: true },
			itemid: { 
				required: true },
			item:{ 
				required: true ,

				
				remote:function(element) {
        return {
        	 url: '{{ route('typename_checkedit') }}',
		 			type: "post",
		 			data: {
			 			 _token :$('input[name=_token]').val(),
						 itemid: $('input[name=itemid]').val(),              
        				categoryname: $('select[name=categoryname]').val(),
						
        					},
       			 }
        									},
        			},                
					},

		messages: {
			//name: { required: "Unit Name is required." },
			item: { required: " item name is required.",
			 remote: "The item Name is already exists!" },                
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
			url: '{{ route('type_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				 itemid: $('input[name=itemid]').val(),
				item: $('input[name=item]').val(),
		categoryname: $('select[name=categoryname]').val(),
				
				
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