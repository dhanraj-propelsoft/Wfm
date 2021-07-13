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
  <h4 class="float-left page-title">Business Customer Report</h4>
  
	<!-- <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

</div>

<div class="modal-body">
	<div class="clearfix"></div>
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<div class="form-inline">
	                <div class="col-md-3 form-group">
	                    <label class="col-sm-3 col-form-label" for="customer_name">Customer Name</label>
	                    <input type="text" class="form-control" name="customer_name" id="customer_name">
	                </div>	            
	                <div class="col-md-3 form-group">
	                    <label class="col-md-5 col-form-label" for="customer_type">Customer Type</label>
	                    {{ Form::text('customer_type', null, ['class'=>'form-control select_item', 'autocomplete' => 'off', 'id' => 'customer_type']) }}
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
			<div class="col-md-10"></div>
			<button type="submit" class="btn btn-success tab_save_btn"> Search </button>&nbsp;
			<button type="submit" class="btn btn-primary tab_save_btn"> Export </button>			
		</div>
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
		<th> Propel ID </th> 
		<th> Customer Name </th> 
		<th> Customer Type </th>
		<th> Contact Number </th>
		<th> Trade Amount </th>
		<th> Status </th>
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