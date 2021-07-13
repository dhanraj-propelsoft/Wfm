<div class="modal-header">
	<h4 class="modal-title float-right">Appraisals</h4>
</div>

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
	<!-- 	<div class="form-inline"> -->
			<div class="row col-md-12">
				<div class="col-md-3 ">
					<div class="form-group">
				
			{!! Form::label('appraisal_year', 'Appraisal Year', array('class' => 'control-label required')) !!}
			{!! Form::text('appraisal_year', null,['class' => 'form-control make_year']) !!}
				
					</div>	
				</div>
				<div class="col-md-3 ">
					<div class="form-group">
			
			{!! Form::label('name', 'Employee Name', array('class' => 'control-label  required')) !!}
			{{ Form::select('name', $employee_name, null, ['class' => 'form-control select_item']) }}
				
				
					</div>
				</div>
				<div class="col-md-3 ">
					<div class="form-group">
				
			{!! Form::label('designation', 'Designation', array('class' => 'control-label col-md-2')) !!}
			{!! Form::text('designation', null,['class' => 'form-control','disabled' =>'true']) !!}
				
				
					</div>
				</div>
				<div class="col-md-3 ">
					<div class="form-group">
				
			{!! Form::label('join_date', 'Joined Date', array('class' => 'control-label ')) !!}
			{!! Form::text('join_date', null,['class' => 'form-control','disabled' =>'true']) !!}
				
				
					</div>
				</div>
			</div>
		<!-- </div> -->
	
		<div class="row col-md-12">
				
				<div class="col-md-3 ">
					<div class="form-group">
			
			{!! Form::label('salary_scale', 'Salary Scale', array('class' => 'control-label  ')) !!}
			{!! Form::text('salary_scale', null,['class' => 'form-control','disabled' =>'true']) !!}
				
				
					</div>
				</div>
				<div class="col-md-3 ">
					<div class="form-group">
				
			{!! Form::label('applicable', 'Appraisal Applicable', array('class' => 'control-label')) !!}
			{{ Form::checkbox('applicable','1',null, array('id' => 'applicable')) }}
			<label for="applicable"><span></span></label>
				
					</div>
				</div>
				<div class="col-md-3 ">
					<div class="form-group">
				
			{!! Form::label('meeting_on', 'Last Meeting on', array('class' => 'control-label  ')) !!}
			{!! Form::text('meeting_on', null,['class' => 'form-control numbers date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) !!}
				
					</div>
				</div>
				<div class="col-md-3 ">
					<div class="form-group">
			{!! Form::label('status', 'Status', array('class' => 'control-label')) !!}
			{{ Form::select('status', ['0' =>'Progress' ,'1' => 'Appealed', '3' => 'Resulted'], null, ['class' => 'form-control select_item']) }}
				
					</div>
				</div>
			</div>

			<div class="row col-md-12">
		<table class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>KPI Name</th>
					<th>Definition</th>
					<th>Discussion</th>
					<th>Feedback</th>
					<th>Weight</th>
					<th>Score</th>
				</tr>
			</thead>
			<tbody>
				@foreach($appraisals_kpis  as $appraisals_kpi)
				<tr>
					<td> {{ $appraisals_kpi->name }} </td>
					<td></td>
					<td></td>
					<td></td>
					<td> {{ $appraisals_kpi->weight }} </td>
					<td >{!! Form::text('score', null,['class' => 'form-control score_size']) !!}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		</div>	



			<div class="row col-md-12">
				
				<div class="col-md-4 ">
					<div class="form-group">
				
			{!! Form::label('total_kpi', 'Total KPI', array('class' => 'control-label  required')) !!}
			{!! Form::text('total_kpi', null,['class' => 'form-control']) !!}
				
				
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="form-group">
				
			{!! Form::label('total_score', 'Total Score', array('class' => 'control-label  required')) !!}
			{!! Form::text('total_score', null,['class' => 'form-control']) !!}
				
				
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="form-group">
				
			{!! Form::label('score', 'Score out of 5', array('class' => 'control-label required')) !!}
			{!! Form::text('score', null,['class' => 'form-control']) !!}
				
					</div>
				</div>
			</div>

			<div class="row col-md-12">
				<div class="col-md-4 ">
					<div class="form-group">
				
			{!! Form::label('promotion', 'Promotion', array('class' => 'control-label ')) !!}
			{{ Form::select('promotion', $designation, null, ['class' => 'form-control select_item']) }}
				
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="form-group">
				
			{!! Form::label('package', 'Package Changed', array('class' => 'control-label ')) !!}
			{{ Form::select('package', [], null, ['class' => 'form-control select_item']) }}
					</div>	
				</div>
				<div class="col-md-4 ">
					<div class="form-group">
			
			{!! Form::label('salary_increase', 'Salary Increase', array('class' => 'control-label ')) !!}
			{{ Form::checkbox('salary_increase','1',null, array('id' => 'salary_increase')) }}
			<label for="salary_increase"><span></span></label>
			
					</div>
				</div>
				
			</div>
	
		<div class="form-group">
			{!! Form::label('feedfack', 'Overall Feedback', array('class' => 'control-label col-md-6')) !!}

			<div class="col-md-12">
				{!! Form::textarea('feedfack', null, array('class' => 'form-control','rows'=>'3 ','cols'=>'40')) !!}
			</div>
		</div>

		
	
	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Save</button>
	<button type="submit" class="btn btn-success">Reset</button>

</div>
	
{!! Form::close() !!}

<script>
	$(document).ready(function() {
	   basic_functions();
	    $('.make_year').datepicker({
		        autoclose: true,
		        viewMode: "years", 
		    	minViewMode: "years",
		        format: 'yyyy'
		    });

		   
	   });


	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true},					
			 
			weight: 
				{ 
				required:true, 
				},                
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
				                              
				},
			success:function(data, textStatus, jqXHR) {
				//console.log(data.data);

				
				

				
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});


</script>