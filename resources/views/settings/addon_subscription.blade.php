@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
@section('content')

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>



@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

<div class="fill header">
		  <h4 class="float-left page-title">My Addons</h4>
		</div>




<div class="float-left" style="width: 100%; padding-top: 10px">
			<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>											
				<th> Order ID </th>  
				<th> Transaction ID </th>                           
				<th> Term Period </th>
				<th> Addon </th>
				<th> Count </th>                      
				<th> Total Price </th>                             
				<th> Payment Status </th>
				<th> Action </th>
			</tr>
		</thead>
		<tbody>
		@foreach($subscriptions as $subscription)
			<tr>						
				<td>{{ $subscription->order_id }}</td>	
				<td>{{ $subscription->transaction_id }}</td>					
				<td>{{ $subscription->term }}<br> <b>Expires On:</b> {{ $subscription->expire_on }}</td>
				<td>{{ $subscription->addon_name }}</td>
				<td>{{ $subscription->addon_value }}</td>
				<td><i class="fa fa-inr"></i> {{ $subscription->total_price }}</td>
				<td>
				@if($subscription->payment_status == 1)
				<label class="grid_label badge badge-success">Success</label>
				@elseif($subscription->payment_status == 0)
				<label class="grid_label badge badge-danger">Failed</label>
				@endif</td>
				<td>
					@if($subscription->payment_status == 1)
					<a href="" class="grid_label badge badge-primary invoice">Invoice</a>
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>

					
@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
   <script type="text/javascript">
   var datatable = null;

   var datatable_options = {"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};


	$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);


	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('roles.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
   });

	});
	</script>
@stop