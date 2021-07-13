<div class="modal-header" style="background-color: #e9ecef;">
	<h5 class="modal-title float-right"><b>Inventory Report</b></h5>
	<a  class="close" data-dismiss="modal">&times;</a>
</div>	

	{!! Form::open(['class' => 'form-horizontal validateform']) !!}                                        
	{{ csrf_field() }}

<div class="modal-body" style="overflow-y: scroll;max-height: 600px;">
  	<div class="form-body">

	  	{!! Form::hidden('id', null) !!}	     
	
		<table id="" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
			  <tr>			  	
				<th> Item Name </th> 
				<th> Instock </th>
				<th> Purchase Price </th>
				<th> Debit</th>				
			  </tr>
			</thead>
			<tbody>
		
				<tr>
					<td>{{$item_details->name}}</td>					
					<td>{{$available_quantity}}</td>
					<td>{{$item_details->purchase_price}}</td>
					<td>{{$balance}}</td>
				</tr>
			</tbody>
	  	</table>

	  	<hr>

	  	<div class="">
			<h5 class="modal-title float-left">Inventory History :</h5>
		</div>	 
	
		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
			  <tr>			  	
				<th> Voucher Type </th> 
				<th> Voucher No </th>
				<th> Quantity </th>
				<th> Instock </th>
				<th> Date </th>
				<th> Delete Status </th>
			  </tr>
			</thead>
			<tbody>
			@foreach($stock_datas as $stock_data)
				<tr>
				  
					@if(isset($stock_data->voucher_type))
					  	<td>{{$stock_data->voucher_type}}</td>
					@else
					  	<td>-</td>
					@endif

					@if(isset($stock_data->order_no))
					  	<td>{{$stock_data->order_no}}</td>
					@else
					  	<td>-</td>
					@endif

					@if(isset($stock_data->quantity))
					  	<td>{{$stock_data->quantity}}</td>
					@else
					  	<td>-</td>
					@endif 

					@if(isset($stock_data->in_stock))
					  	<td>{{$stock_data->in_stock}}</td>
					@else
					  	<td>-</td>
					@endif  	

					@if(isset($stock_data->date))
						<td class="">{{$stock_data->date}}</td>
					@else
					  	<td>-</td>
					@endif

					@if(isset($stock_data->status) == 1)
						<td class="">No</td>
					@else
					  	<td>Yes</td>
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

