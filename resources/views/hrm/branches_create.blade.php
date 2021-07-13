<div class="modal-header">
	<h4 class="modal-title float-right">Add Branch</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		<div class="form-group">
			{!! Form::label('business', 'Bussiness', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12 search_container">
				{{ Form::select('business', $business, null, ['class' => 'form-control select_item']) }}
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

	 $('select[name=business]').each(function() {
			$(this).prepend('<option value="0"></option>');
			select_business($(this));
		});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			business: { required: true },
			//parent_department: { required: true },                
		},

		messages: {
			business: { required: "Bussiness is required." },
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
			url: '{{ route('branches.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				business_id: $('select[name=business]').val(),
				branch_name: $('input[name=branch_name]').val(),
				description: $('textarea[name=description]').val()
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
					</td></tr>`, `add`, data.message);
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