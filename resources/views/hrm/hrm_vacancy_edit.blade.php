<div class="modal-header">
	<h4 class="modal-title float-right">Edit Vacancy</h4>
</div>

	{!! Form::model($vacancy,[
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
	  {!! Form::hidden('id', null) !!}

		<div class="form-group">
			{!! Form::label('designations', 'Designations', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{{Form::select('designations', $designations, $vacancy->designation_id, ['class'=>'form-control select_item'])}}
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
				{!! Form::text('no_of_positions', null ,['class' => 'form-control' ,'id' => 'no_of_positions' , 'disabled' => 'true']) !!}
				
			</div>
		</div>
		<div class="form-group">						 
			{!! Form::label('team', 'Team', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
			{{ Form::select('team', $teams, $vacancy->team_id , ['class'=>'form-control select_item']) }}
				
			</div>
		</div>		
		<div class="form-group">						 
			{!! Form::label('notes', 'Notes', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{!! Form::text('notes', null,['class' => 'form-control']) !!}
				
			</div>
		</div>
		<div class="form-group">						 
			{!! Form::label('employee', 'Created_By', ['class' => 'control-label col-md-3']) !!}
			
			<div class="col-md-12">
				{{ Form::select('employee', $employee, $vacancy->employee_id , ['class'=>'form-control select_item']) }}
				
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('created_at', 'Created/Update Date', array('class' => 'control-label col-md-4 ')) !!}

			<div class="col-md-12">
				{!! Form::text('created_at', null,['class' => 'form-control  date-picker' ,'id'=>'observed_date']) !!}
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
			 url: '{{ route('vacancy.update') }}',
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',

				id: $('input[name=id]').val(),
				designations: $('select[name=designations]').val(),
				positions: $('input[name=no_of_positions]').val(),
				vacancies: $('input[name=no_of_vacancies]').val(),
				team: $('select[name=team]').val(),
				notes: $('input[name=notes]').val(),
				employee_id: $('select[name=employee]').val(), 
				created_at: $('input[name=created_at]').val(),
				},
			 success:function(data, textStatus, jqXHR) {

			 	var active_selected = "";
				var inactive_selected = "";
				var selected_text = "Close";
				var selected_class = "badge-warning";

				if(data.data.status == 1) {
					active_selected = "selected";
					selected_text = "Open";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					inactive_selected = "selected";
				}

				call_back(`<tr role="row" class="odd">
						<td><input id="`+data.data.id+`" class="item_check" name="team" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
						</td>
						<td></td>
						<td>`+data.data.designation+`</td>
						<td>`+data.data.positions+`</td>
						<td>`+data.data.no_of_vacancy+`</td>
						<td>`+data.data.create_date+`</td>
						<td>
							<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
							<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
								<option `+active_selected+` value="1">Active</option>
								<option `+inactive_selected+` value="0">In-Active</option>
							</select>
						</td>
						<td>
						  <a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
						  <a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
						</td></tr>`, `edit`, data.message, data.data.id);

				$('.loader_wall_onspot').hide();

				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>