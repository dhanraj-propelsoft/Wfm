<div class="modal-header">
	<h4 class="modal-title float-right">Add Specification</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
	<div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Type</label>
      {!! Form::select('type',$type , null, ['class' => 'form-control select_item']) !!}
    </div>
    <div class="form-group col-md-6">
      <label for="specification">Specification</label>
      {!! Form::select('specification',$specification , null, ['class' => 'form-control select_item']) !!}
    </div>
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
  </div>
<div class="form-row">
    <div class="form-group col-md-6">
      <label for="used">Used In Workshop</label>
      <select name="used" class="form-control select_item">
      <option value="0" selected="selected">NO</option>
      <option value="1">YES</option>
</select>
    </div>
    <div class="form-group col-md-6">
      <label for="pricing">Used For Pricing</label>
       <select name="pricing" class="form-control select_item">
      <option value="0" selected="selected">NO</option>
      <option value="1">YES</option>
</select>
    </div>
  </div>
	

	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Save</button>
</div>
	
{!! Form::close() !!}
<script>
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		/*rules: {
			//name: { required: true },
			name: { 
				required: true,
				remote: {
						url: '',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					}
			},                
		},

		messages: {
			//name: { required: "Unit Name is required." },
			name: { required: "Vehicle Makers Name is required.", remote: "Vehicle Makers Name is already exists!" },                
		},*/
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
			url: '{{ route('specification.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				type: $('select[name=type]').val(),
				specification: $('input[name=specification]').val(),
				description: $('textarea[name=description]').val(),
				used: $('select[name=used]').val(),
				pricing: $('select[name=pricing]').val()              
				},
			success:function(data, textStatus, jqXHR) {
                         console.log(data);
				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.specification+`</td>
					<td>`+data.data.type+`</td>
					<td>`+data.data.description+`</td>
					<td>
						<label class="grid_label badge badge-warning status">No</label>
						<select style="display:none" id="`+data.data.used+`" class="active_status form-control">
							<option value="1">YES</option>
							<option value="0">NO</option>
						</select>
					</td>
					<td>
						<label class="grid_label badge badge-warning status">No</label>
						<select style="display:none" id="`+data.data.pricing+`" class="active_status form-control">
							<option value="1">YES</option>
							<option value="0">NO</option>
						</select>
					</td>

					<td>
						<label class="grid_label badge badge-success status">Active</label>
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option value="1">Active</option>
							<option value="0">In-active</option>
						</select>
					</td>
					</tr>`, `add`, data.message);

				$('.loader_wall_onspot').hide();
				}
			});
		}
	});

</script>
