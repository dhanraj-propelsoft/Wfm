<div class="modal-header">
	<h4 class="modal-title float-right">Add Document</h4>
</div>

	{!! Form::open(['files' => true, 
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('name', 'Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
				
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('document_type', 'Document Type', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('document_type', $types, null, ['class'=>'form-control select_item'])}}
			</div>
		</div>		
				
		<div class="form-group">						 
			{!! Form::label('summary', 'Document Summary', ['class' => 'control-label col-md-4']) !!}
			
			<div class="col-md-12">
				{!! Form::text('summary', null,['class' => 'form-control']) !!}
				
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('from', 'Valid From', array('class' => 'control-label col-md-4 ')) !!}

			<div class="col-md-12">
				{!! Form::text('from', null,['class' => 'form-control  date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'observed_date']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('to', 'Valid To', array('class' => 'control-label col-md-4 ')) !!}

			<div class="col-md-12">
				{!! Form::text('to', null,['class' => 'form-control  date-picker', 'data-date-format' => 'dd-mm-yyyy']) !!}
			</div>
		</div>
		<div class="form-group">
				{!! Form::label('file', 'Document', array('class' => 'control-label col-md-4')) !!}
						

			<div class="col-md-12">
				<input type="file" name="file" />
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
	$(document).ready(function() {

		var myfile = '';

			$('input[name="file"]').on('change', function(e) {

				var reader = new FileReader();
				if(e.target.files && e.target.files.length > 0) {
					reader.readAsDataURL(e.target.files[0]);
					reader.onload = function () {
				     myfile = reader.result[1];
				   };
				}
				});

	   basic_functions();
	$('#observed_date').datepicker("setDate", new Date());

	});
	

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true },
			document_type: { required: true },

			
		},

		messages: {
			name: { required: "Name is required." },
			document_type: { required: "Document Type is required." },

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
			url: '{{ route('document.store') }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				document_type: $('select[name=document_type]').val(),
				summary: $('input[name=summary]').val(),
				from: $('input[name=from]').val(),
				to: $('input[name=to]').val(),
				myfile:  $('input[name=file]').val(),
                

				                               
				},
			success:function(data, textStatus, jqXHR) {
				
				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="vacancy" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					
					<td></td>

					<td>`+data.data.name+`</td>
					<td></td>
					<td>`+data.data.document_type+`</td>
					<td>`+data.data.uploaded_on+`</td>
					<td>`+data.data.valid_from+`</td>

					
					<td>
						<label class="grid_label badge badge-success status">Active</label>
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option value="1">Active</option>
							<option value="0">In-Active</option>
						</select>
					</td>
					<td>
					<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`, `add`, data.message);
				
				$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>