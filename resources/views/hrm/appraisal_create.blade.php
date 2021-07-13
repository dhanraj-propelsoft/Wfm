<div class="modal-header">
	<h4 class="modal-title float-right">Initiation by year</h4>
</div>

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('appraisal_year', 'Appraisal Year', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-4">
				{!! Form::text('appraisal_year', null,['class'=>'form-control make_year', 'autocomplete' => 'off', 'id' => 'manufacturing_year']) !!}
			</div>
		</div>	
		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>Employee Name</th>
					<th>Designation</th>
					<th>Joined On</th>
					<th>Applicable</th>
				</tr>
			</thead>
			<tbody>
				@foreach($employees as $employee)
				<tr>
					<td> {{ $employee->first_name }} </td>
					<td> {{ $employee->designation_name }}</td>
					<td> {{ $employee->joined_date }} </td>
					<td> {{ Form::checkbox('check_all', $employee->id, null, ['id' => $employee->id,'class' =>'input_checkbox','checked'] ) }} <label for="{{ $employee->id }}"><span></span></label></td>
				</tr>
				@endforeach
			</tbody>
		</table>	
		
	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success save">Save</button>
	<button type="submit" class="btn btn-success discard">Discard</button>

</div>
	
{!! Form::close() !!}

<script type="text/javascript">
	var datatable = null;

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

	$(document).ready(function() {
	   basic_functions();
  	datatable = $('#datatable').DataTable(datatable_options);
	   
		  /* $(".checked").attr('checked', true);*/

		   $('.make_year').datepicker({
		        autoclose: true,
		        viewMode: "years", 
		    	minViewMode: "years",
		        format: 'yyyy'
		    });


		   $('.save').on('click',function(e){
		   		
		   			e.preventDefault();
		   				//alert();
		   			/*var val = [];
			        $(':checkbox:checked').each(function(i)
			        {
			          val[i] = $(this).val();
			         alert(val);
			        });*/
			         //console.log(val);
			        $.ajax({

			        	url: '{{ route('appraisal.store') }}',
			        	type: 'post',
			        	data: 
			        	{
			        		_token: '{{ csrf_token() }}',
			        		id:  $('input[name=check_all]:checked').map(function() { 
						return this.value; 
					}).get(),
			        		appraisal_year : $('input[name=appraisal_year]').val(),
			        	},
			        	success:function(data,textStatus,jqXHR)
			        	{
			        			//alert();
			        		call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="appraisal" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td><a class="direct_random"><u>`+data.data.name+`</u></a></td>
					<td></td>

					<td>`+data.data.appraisal_year+`</td>
					<td><label class="grid_label badge badge-success status">Progress</label></td>
					<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option value="0">Progress</option>
							<option value="1">Appealed</option>
							<option value="2">Resulted</option>
					</select>
					<td>	
					</td>
					<td></td>
					<td></td>
					<td>
					
					</td></tr>`, `add`, data.message);
				
				$('.loader_wall_onspot').hide();
						

  					
			        	},
			        	error:function(textStatus,jqXHR,errorThrown)
			        	{

			        	}




			        });
		   			
		  		 });
			});

</script>