<div class="alert alert-success">
	{{ Session::get('message') }}
</div>

<div class="modal-header">
	<h4 class="modal-title float-right">Appraisal KPIs</h4>
</div>

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('name', 'KPI Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>		
		<div class="form-group">
			{!! Form::label('weight', 'Weight', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('weight', null,['class' => 'form-control']) !!}
		<!-- <span></span>  -->
			</div>
		</div>		
		<div class="form-group">						 
			{!! Form::label('definition', 'Definition', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::textarea('definition', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('valid_from', 'Valid From', array('class' => 'control-label col-md-4')) !!}

			<div class="col-md-12">
				{!! Form::text('valid_from', null,['class' => 'form-control numbers date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}
			</div>
		</div>
	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Save</button>
</div>
	
{!! Form::close() !!}

<script>
	$(document).ready(function() {
	   basic_functions();
		  
	   });


	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true },					
			 
			weight: { required:true },                
		},

		messages: 
		{
			name: { required: "Name is required." },
			weight: { required: "Weight is required." },                 
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
			url: '{{ route('appraisal_kpi.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				weight: $('input[name=weight]').val(),
				definition: $('textarea[name=definition]').val(),
				valid_from: $('input[name=valid_from]').val(),                               
				},
			success:function(data, textStatus, jqXHR) {
				//console.log(data.data);
				//var weight=data.data.weight;
				
				if(data.status == 1)
				{
				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="team" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.description+`</td>
					<td>`+data.data.weight+`</td>
					<td>`+data.data.valid_from+`</td>
					</tr>`, `add`, data.message);
				}
				else(data.status == 0)
				{
				$('.loader_wall_onspot').hide();
				$('.crud_modal').modal('show');
				//$('div .alert-success').val(weight);
				custom_success_msg(data.message);
				}
				
				
				
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});


</script>