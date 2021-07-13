<div>
@if($status == 0)

		<label>ID : No Transactions found</label>
	@else
		@foreach($view_estimation as $view_estimations)
		<label>ID : {{ $view_estimations->order_no }}</label><br>
		<label>Due : {{ $view_estimations->balance }}</label><br>
		<a href="javascript:;" data-id="{{ $view_estimations->id }}" data-user_type="{{ $view_estimations->user_type }}" data-people_id="{{ $view_estimations->people_id }}" data-type="{{ $name }}" 
		data-reference_no="{{ $view_estimations->order_no }}" data-total="{{ $view_estimations->total }}" 
		data-balance="{{ $view_estimations->balance }}" class="grid_label badge badge-success process_invoice" data-toggle="tooltip" data-placement="top" title=" Click here to pay">Process Payment</a>
		@endforeach
	@endif
</div>



<script type="text/javascript">
	$(document).ready(function(){
		var user_type;
		var people_id;
		var type;
		var reference_id = [];
		var order_id= [];
		var amount= [];
		var balance;
		


	


	
});

</script>
