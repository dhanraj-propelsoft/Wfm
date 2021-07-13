@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.trade_wms')
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
  <h4 class="float-left page-title">Vehicle Maintanance Report</h4>
  
	<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

</div>

<div class="modal-body">
	<div class="clearfix"></div>
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<div class="form-inline">
	                <div class="col-sm-1 form-group"></div>	            
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-3 col-form-label" for="vehicle_number">Vehicle Number</label>
	                    <input type="text" class="form-control" name="vehicle_number" id="vehicle_number">
	                </div>	            
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-2 col-form-label" for="from_date">From Date</label>
	                    {{ Form::text('from_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'id' => 'from_date']) }}
	                </div>
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-2 col-form-label" for="to_date">To Date</label>{{ Form::text('to_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'id' => 'to_date']) }}
	                </div>
	            </div>
			</div>			
		</div>
		<div class="row">
			<div class="col-md-9"></div>
			<button type="submit" class="btn btn-success tab_save_btn"> Search </button>&nbsp;
			<button type="submit" class="btn btn-primary tab_save_btn"> Export </button>			
		</div>
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>		 
		<th> Registration Number </th>
		<th> Owner Name </th>
		<th> Serviced On </th>
		<th> Service For </th>
		<th> Serviced At </th>
		<th> Mileage </th>
		<th> Service (Job) </th>
		<th> Quantity </th>
		<th> Reading (Description) </th>
		<th> Reading Factor </th>
		<th> Reading </th>
		<th> Reading Factor </th>
		<th> Reading Factor</th>
		<th> Notes </th>
	  </tr>
	</thead>
	
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

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};


  $(document).ready(function() {

	datatable = $('#datatable').DataTable();

  


  });
  </script>
@stop