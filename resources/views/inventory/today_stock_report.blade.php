@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<style>
        .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
        .btn
        {
            line-height: 1;
        }
</style>
@stop
@include('includes.inventory')
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
<div class="fill header" style="margin-top: 10px;">
  <center><h4 class="page-title">{{ $today }} Stock Purchase and Sales</h4></center>	
</div>
<br>
<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th>S.No</th> 
	  	<th >Item Name</th> 
		<th>Item Make</th> 
		<th colspan="2" >Inward</th>
		<th colspan="2" >Outward</th> 
		<th >In Stock</th>
	  </tr>
	  <tr>
	  	  <td></td>
	  	  <td></td>
	  	  <td></td>
          <td>Qty</td>
          <td>Amount</td>
          <td>Qty</td>
          <td>Amount</td>
          <td></td>
       </tr>
	</thead>
	<tbody>
		<?php $count = 0; ?>
		@for ($i = 0; $i < count($today_stock_reports); $i++)
		<tr>
			<td><?php echo ++$count; ?></td> 
			<td>{{ $today_stock_reports[$i]->item_name }}</td>
			<td>{{ $today_stock_reports[$i]->make_name }}</td>
			<td>{{ $today_stock_reports[$i]->inward_quantity }}</td>
			<td>{{ $today_stock_reports[$i]->inward_amount }}</td>
			<td>{{ $today_stock_reports[$i]->outward_quantity }}</td>
			<td>{{ $today_stock_reports[$i]->outward_amount }}</td>
			<td>{{ $today_stock_reports[$i]->in_stock }}</td>
		</tr>
		 @endfor
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
	var datatable_options = {"order": [[1, "asc"]], "stateSave": true};
$(document).ready(function() {
		datatable = $('#datatable').DataTable(datatable_options);
});
</script>
@stop