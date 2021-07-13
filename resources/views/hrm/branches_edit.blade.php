<div class="modal-header">
	<h4 class="modal-title float-right">Edit Branch</h4>
</div>

	{!! Form::model($branches, ['class' => 'form-horizontal validateform']) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

{!! Form::hidden('id', null) !!}

		<div class="form-group">
			{!! Form::label('business', 'Bussiness', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12 search_container">
				{{ Form::select('business', $business, $branches->id, ['class' => 'form-control select_item']) }}
				<div class="content"></div>
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('branch_name', 'Name', array('class' => 'control-label col-md-4')) !!}
			<div class="col-md-12">
				{!! Form::text('branch_name', null,['class' => 'form-control']) !!}
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-12">
				{!! Form::textarea('description', null, array('class' => 'form-control','rows'=>'3 ','cols'=>'40')) !!}
			</div>
		</div>

	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

<script>
$(document).ready(function()
{
	 basic_functions();

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			branch_idbranch_id: { required: true },
			//parent_department: { required: true },                
		},

		messages: {
			branch_id: { required: "Bussiness is required." },
			//parent_department: { required: "Parent Department Name is required." },                
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
			url: '{{ route('branches.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				business_id: $('select[name=business]').val(),
				branch_name: $('input[name=branch_name]').val(),
				description: $('textarea[name=description]').val(),
				},
			success:function(data, textStatus, jqXHR) {

				if(data.status == 1) {
				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="branch" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>            	
					<td>`+data.data.branch_name+`</td>
					<td>`+data.data.description+`</td>
					<td>                            
						<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
						<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
						</td></tr>`, `edit`, data.message, data.data.id);
				} else {
					call_back(``, `add`, data.message);
				}

				$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

});
</script>