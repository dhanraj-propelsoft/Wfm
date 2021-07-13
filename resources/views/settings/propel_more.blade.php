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
	<h4 class="float-left page-title">MoreFrom Propel</h4>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;" id="table_data">		<div class="row col-md-2">
        <h5 class="modal-title float-right">Trade WMS app</h5>
 	</div>
	<div class="row ">
    	 <div class="col-md-2" style="padding-top: 30px" >
     <a href="{{ route('app.download', ['id' => $Current_version]) }}"><button type="button" class="btn btn-success"><i class="fa fa-download"></i> Current Version</button></a>
   		 </div>   
    </div>
    <div class="row ">
     		<div class="col-md-2" style="padding-top: 30px" >
       		<a href="{{ route('app.download', ['id' => $previous_version]) }}"><button type="button" class="btn btn-success"><i class="fa fa-download"></i> Previous Version</button></a>
     		</div>  
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 30px;" id="table_data">
	<div class="row col-md-2">
        <h5 class="modal-title float-right">Learn PropelSoft</h5>
        <div style="padding-top: 30px" >
        	<iframe width="560" height="315" src="https://www.youtube.com/embed/dwIslpSaTR4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
 	</div>
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