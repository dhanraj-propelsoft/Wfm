@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.admin')
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
  <h4 class="float-left page-title">Customer</h4>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
	<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  		
	  	<th>Propel ID</th> 
		<th>Company Name</th> 
		<th> Allowed  </th>
		<th>Used  </th>
		<th>Balance</th>
		<th> Plan  </th>
		<th>  Change Plan</th>
	  </tr>
	</thead>
	<tbody>
				
				@foreach($customer as $customers)
				<tr>
					
					 <td > {{$customers->propel_id}} </td>
					  <td><a href="{{ route('business_profile.show', [$customers->business_id, 'admin']) }}">{{ $customers->company_name }}</a></td>
					<td> {{$customers->value}} </td>
					<td> {{$customers->used}} </td>
					
					<td > <?php $balance=$customers->value - $customers->used;
					if ($balance>=0) {
						echo $balance;
						}else{
						echo "0";
					}?> </td><td>{{$customers->plan_name}}</td>
					<td></td>
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

   var datatable_options = {"order": [[1, "asc"]], "stateSave": true};

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('hrm_departments.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/departments') }}/"+$(this).data('id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  	$('body').on('click', '.delete', function(){
	var id = $(this).data('id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('hrm_departments.destroy') }}';
	delete_row(id, parent, delete_url);
   	});

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('hrm_departments.multidestroy') }}";
		multidelete($(this), url);
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('hrm_departments.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url);
	});
	

  });
  </script>
@stop

