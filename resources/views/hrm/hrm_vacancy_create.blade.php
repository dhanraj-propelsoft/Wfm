<div class="modal-header">
	<h4 class="modal-title float-right">Add Vacancies</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('designations', 'Designations', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
			{{ Form::select('designations', $designations, null, ['class'=>'form-control select_item', 'id' => 'designation_id']) }}
			</div>
		</div>		
		<div class="form-group">
			{!! Form::label('no_of_vacancies', 'No of Vacancies', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('no_of_vacancies', null,['class' => 'form-control']) !!}
				
			</div>
		</div>	
		<div class="form-group">
			{!! Form::label('no_of_positions', 'No of Positions', array('class' => 'control-label col-md-4 ')) !!}

			<div class="col-md-12">
				{!! Form::text('no_of_positions', null ,['class' => 'form-control' ,'id' => 'no_of_positions', 'disabled' =>'true']) !!}
				
			</div>
		</div>
		<div class="form-group">						 
			{!! Form::label('team', 'Team', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
			{{ Form::select('team', $teams, null, ['class'=>'form-control select_item']) }}
				
			</div>
		</div>	
		<div class="form-group">						 
			{!! Form::label('notes', 'Notes', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::text('notes', null,['class' => 'form-control']) !!}
				
			</div>
		</div>
		<div class="form-group">						 
			{!! Form::label('employee', 'Requested_By', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{{ Form::select('employee', $employee, null, ['class'=>'form-control select_item']) }}
				
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('created_at', 'Created/Update Date', array('class' => 'control-label col-md-4 ')) !!}

			<div class="col-md-12">
				{!! Form::text('created_at', null,['class' => 'form-control  date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'observed_date']) !!}
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

	  basic_functions();




	$('select[name=designations]').change(function(){
		var id=$(this).val();
		//var position=$('input[name=no_of_positions]').val('');
		//alert(id);

		$.ajax({
			url: '{{ route('get_positions') }}',
			type: 'get',
			data:
				{
				/*_token: '{{ csrf_token() }}',*/
				id:id
				},
			success:function(data,textStatus,jqXHR)
			{
				//alert();
				//console.log(data.positions);

				var pos = data.positions;
				//console.log(pos);
				$('input[name=no_of_positions]').val(pos.positions);

			},
			error:function()
			{

			}

		});
		});

	$('#observed_date').datepicker("setDate", new Date());
	});



	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			designations: { required: true },
			no_of_vacancies: { required: true },

			
		},

		messages: {
			designations: { required: "Designation Name is required." },
			no_of_vacancies: { required: "No of Vacancy is required." },

		

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
			url: '{{ route('vacancy.store') }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				designations: $('select[name=designations]').val(),
				positions: $('input[name=no_of_positions]').val(),
				vacancies: $('input[name=no_of_vacancies]').val(),
				team: $('select[name=team]').val(),
				notes: $('input[name=notes]').val(),
				employee_id: $('select[name=employee]').val(),                            
				created_at: $('input[name=created_at]').val(),                               
				},
			success:function(data, textStatus, jqXHR) {
				
				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="vacancy" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					
					<td></td>

					<td>`+data.data.designation+`</td>
					<td>`+data.data.no_of_position+`</td>

					<td>`+data.data.no_of_vacancy+`</td>
					<td>`+data.data.create_date+`</td>
					<td>
						<label class="grid_label badge badge-success status">Open</label>
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option value="1">Close</option>
							<option value="0">Open</option>
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