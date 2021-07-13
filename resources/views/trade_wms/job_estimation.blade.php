@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop

@if(Session::get('module_name') == "trade_wms")
	@include('includes.trade_wms')
@else
	@include('includes.inventory')
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
	<h4 class="float-left page-title">Job Estimation</h4>
	<div class="btn-group float-right">
		<a class="btn btn-danger float-left add" style="color: #fff">New</a>
		<!-- <a class="btn btn-danger float-left edit" style="color: #fff; display: none;">Edit</a> -->
		<a class="btn btn-danger float-left multidelete" style="color: #fff">Delete</a>
		<a class="btn btn-danger float-left multiapprove" data-status="1" style="color: #fff">Approve</a>
		<a class="btn btn-danger float-left print" style="color: #fff">Print</a>
		<a class="btn btn-danger float-left excel_export" style="color: #fff">Export to Excel</a>
	</div>
	
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th class="noExport"> 
				{{ Form::checkbox('checkbox_all', 'checkbox_all', null, ['id' => 'checkbox_all'] ) }} <label for="checkbox_all"><span></span></label></th>
			
			<th>Job Request Number</th>
			<th> Service Type </th>
			<th> Vehicle Details </th>
			<th> Supplier </th>		
			<th> Customer Contact </th> 
			<th> Job Request Amount</th> 
			<th> Job Requested  Date </th>
	 		<th> Job Due date </th>
			<th> Status </th>
		</tr>
	</thead>
	<tbody>
		
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

		$('.add').on('click', function(e) {
			e.preventDefault(); 
			var that = $(this);
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

				
					$.get("{{ route('job_estimation.create') }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					  $('.loader_wall_onspot').hide();
					});
				
		
			});
				
		});

		
		

});
</script>
@stop