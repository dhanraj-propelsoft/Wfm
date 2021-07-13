<div class="modal-body">
<div class="col-md-12">
	@if($where =='item')
	  <div class="row">
	  	<div class="col-md-3"><b>Item Name:{{$item_name}}</b></div>
	  	<div class="col-md-2"><b>Total Qty:{{$total_quantity}}</b></div>
	  	<div class="col-md-4"><b>Total Purchase Value:{{$total_pu_value}}</b></div>
	  	<div class="col-md-3"><b>Total Sale value:{{$total_sale_value}}</b></div>
	  </div>
	@endif
</div>
<br>
	@if($where =='item')
	<table  class="table" width="100%" cellspacing="0">
		<thead>
			<th>Batch number</th> 
			<th>Purchase company Number</th>
			<th>Date</th>
			<th>Quantity</th>
			<th>Purchase Id</th>
			<th>Purchase+Tax</th>
			<th>Total</th>
			<th>Selling+Tax</th>
			<th>Total</th>
		</thead>
		<tbody>	
			@foreach($item_batches as $item_batch)
			<tr>
				<td>{{$item_batch->batch_number}}</td>
				<td>{{$item_batch->purchase_company_name}}</td>
				<td>{{$item_batch->date}}</td>
				<td>{{$item_batch->quantity}}</td>
				<td>{{$item_batch->order_no}}</td>
				<td>{{$item_batch->purchase_plus_tax_price}}</td>
				<td>{{$item_batch->quantity * $item_batch->purchase_plus_tax_price}}</td>
				<td>{{$item_batch->selling_plus_tax_price}}</td>
				<td>{{$item_batch->quantity * $item_batch->selling_plus_tax_price}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@endif
	@if($where !='item')
	<table  class="table" width="100%" cellspacing="0">
		<thead>
			<th>Date</th>
			<th>Purchase company Number</th>
			<th>Batch number</th> 
			<th>Purchase+Tax Price</th>
		</thead>
		<tbody>	
			@foreach($item_batches as $item_batch)
			<tr>
				<td>{{$item_batch->date}}</td>
				<td>{{$item_batch->purchase_company_name}}</td>
				<td>{{$item_batch->batch_number}}</td>
				<td>{{$item_batch->purchase_plus_tax_price}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@endif
</div>
<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>