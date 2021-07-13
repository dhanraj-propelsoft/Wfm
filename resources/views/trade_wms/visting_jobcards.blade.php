@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop

@if(Session::get('module_name') == "trade_wms")
	@include('includes.trade_wms')
@endif

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
	<h4 class="float-left page-title">Next Visit Due Vehicle</h4>
	
	
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th>Job Card Number</th>
		    <th>Vehicle Details </th>
			<th>Customer Name</th>	
			<th>Customer Mobile</th>	
			<th>Assigned To</th> 
			<th>Visiting Date</th> 
		</tr>
	</thead>
	<tbody>
		@foreach($Transaction_data as $Transaction_data)
	    <tr>
		<td>{{$Transaction_data->order_no}}</td>
		<td>{{$Transaction_data->registration_no}}</td>
		<td>{{$Transaction_data->name}}</td>
		<td>{{$Transaction_data->mobile}}</td>
		<td>{{$Transaction_data->first_name}}</td>
		<td>{{$Transaction_data->vehicle_next_visit}}</td>
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

	 var isFirstIteration = true;
	
	 var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [], 

		dom: 'lBfrtip',
		buttons: [
			{
			extend: 'pdf',
				footer: true,
				exportOptions: {
					columns: [1,2]
				}
			},
			{
				extend: 'csv',
				footer: false,
				exportOptions: {
					columns: [1,2]
				}
			},
			{
				extend: 'excel',
				exportOptions: {
					columns: ":not(.noExport)"
				},
				footer: false
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ":not(.noExport)",
					stripHtml: false,
				},
				autoPrint: true
			}
		]


	};
	$(document).ready(function() {

		datatable = $('#datatable').DataTable();

		
		
		

});
</script>
@stop