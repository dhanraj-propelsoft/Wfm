<div class="modal-header" style="background-color: #e9ecef;">
	<h5 class="modal-title float-right"><b>Report</b></h5>
	<a  class="close" data-dismiss="modal">&times;</a>
</div>	

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}                                        
	{{ csrf_field() }}

<div class="modal-body" style="overflow-y: scroll;max-height: 600px;">
  	<div class="form-body">

	  	{!! Form::hidden('id', null) !!}	     
	
	

	  	<hr>

	  	<!-- <div class="">
	  		 			<h5 class="modal-title float-left">History :</h5>
	  		 		</div> -->	 
	
		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
			  <tr>			  	
				<th>Voucher Type </th> 
				<th>Debit Ledger </th>
				<th>Credit Ledger </th>
				<th>Description </th>
				<th>Amount</th>
			
			  </tr>
			</thead>
			<tbody>
			@foreach($datas as $data)
				<tr>
				  
					@if(isset($data->voucher_no))
					  	<td>{{$data->voucher_no }}</td>
					@else
					  	<td>-</td>
					@endif

					@if(isset($data->debit_ledger_name))
					  	<td>{{$data->debit_ledger_name}}</td>
					@else
					  	<td>-</td>
					@endif

					@if(isset($data->credit_ledger_name))
					  	<td>{{$data->credit_ledger_name}}</td>
					@else
					  	<td>-</td>
					@endif 

					@if(isset($data->description))
					  	<td>{{$data->description}}</td>
					@else
					  	<td>-</td>
					@endif  	

					@if(isset($data->date))
						<td class="">{{$data->amount}}</td>
					@else
					  	<td>-</td>
					@endif

					
				</tr>

				@endforeach
			 
			</tbody>
	  	</table>
	
  	</div>
</div>

<div class="modal-footer" style="background-color: #e9ecef;">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	
</div>


{!! Form::close() !!}




<script type="text/javascript">
   
   var datatable = null;

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true}; 
				

  $(document).ready(function() {
	 basic_functions();

	 datatable = $('#datatable').DataTable(datatable_options);
  });


</script>

