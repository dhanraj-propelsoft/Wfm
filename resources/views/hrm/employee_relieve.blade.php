@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.hrm')
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
  <h4 class="float-left page-title">Employee Relieve</h4>
  @permission('employee-relieve-create')
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  @endpermission
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">	
	<table id="datatable" class="table data_table" width="100%" cellspacing="0">
		<thead>
		  <tr>
			<th> Employee Name </th> 
			<th> Relieve Date </th>
			<th> Reason </th>
			<th> Action </th>
		  </tr>
		</thead>
	<tbody>
	@foreach($employee_relieving as $employee_relieve)
		<tr>
		  	<td>{{ $employee_relieve->employee_name }}</td>
		  	<td>{{ $employee_relieve->relieved_date }}</td>
		  	<td>{{ $employee_relieve->reason }}</td>
			<td>
				@permission('employee-relieve-edit')
			  		<a data-id="{{ $employee_relieve->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
				@endpermission
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

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};


  	$(document).ready(function() {

		datatable = $('#datatable').DataTable(datatable_options);

  		$('.add').on('click', function(e) {
			e.preventDefault();
			$.get("{{ route('employee_relieve.create') }}", function(data) {
			  $('.crud_modal .modal-container').html("");
			  $('.crud_modal .modal-container').html(data);
			});
			//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
  		});

	  	$('body').on('click', '.edit', function(e) {
			e.preventDefault();
			$.get("{{ url('hrm/employee-relieve') }}/"+$(this).data('id')+"/edit", function(data) {
			  $('.crud_modal .modal-container').html("");
			  $('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').modal('show');
  		});  

  	});
</script>
@stop