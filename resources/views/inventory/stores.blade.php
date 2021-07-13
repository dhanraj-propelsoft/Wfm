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

<div class="alert alert-danger">

</div>



@if($errors->any())

	<div class="alert alert-danger">

		@foreach($errors->all() as $error)

			<p>{{ $error }}</p>

		@endforeach

	</div>

@endif



<div class="fill header">

  <h4 class="float-left page-title">Stores</h4>

  @permission('stores-create')

	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>

  @endpermission

</div>



<div class="float-left" style="width: 100%; padding-top: 10px">

  <table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">

	<thead>

	  <tr>

		<th> Name </th> 

		<th> Warehouse Name </th>               

		<th> Description </th>

		<th> Status </th>

		<th> Action </th>

	  </tr>

	</thead>

	<tbody>

	  @foreach($stores as $store)

		<tr>

		  <td>{{ $store->name }}</td>              

		  <td>{{ $store->placename }}</td>              

		  <td>{{ $store->description }}</td>

		  <td>

			@if($store->status == '1')

			  <label class="grid_label badge badge-success status">Active</label>

			@elseif($store->status == '0')

			  <label class="grid_label badge badge-warning status">In-Active</label>

			@endif



			@permission('stores-edit')

			  <select style="display:none" id="{{ $store->id }}" class="active_status form-control">

				<option @if($store->status == 1) selected="selected" @endif value="1">Active</option>

				<option @if($store->status == 0) selected="selected" @endif value="0">In-Active</option>

			  </select>

			@endpermission

		  </td>

		  <td>           

			@permission('stores-edit')

			  <a data-id="{{ $store->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>

			@endpermission



			@permission('stores-delete')

			  <a data-id="{{ $store->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>

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



   var datatable_options = {"stateSave": true};





  $(document).ready(function() {



  datatable = $('#datatable').DataTable(datatable_options);



  $('.add').on('click', function(e) {

		e.preventDefault();

		$.get("{{ route('stores.create') }}", function(data) {

		  $('.crud_modal .modal-container').html("");

		  $('.crud_modal .modal-container').html(data);

		});

		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');

		$('.crud_modal').modal('show');

  });



  $('body').on('click', '.edit', function(e) {

		e.preventDefault();

		$.get("{{ url('inventory/stores') }}/"+$(this).data('id')+"/edit", function(data) {

		  $('.crud_modal .modal-container').html("");

		  $('.crud_modal .modal-container').html(data);

		});

		$('.crud_modal').modal('show');

  });



  $('body').on('click', '.status', function(e) {

	$(this).hide();

	$(this).parent().find('select').css('display', 'block');

  });



  	$('body').on('change', '.active_status', function(e) {

		var status = $(this).val();

		var id = $(this).attr('id');

		var obj = $(this);

		var url = "{{ route('store_status_approval') }}";

		change_status(id, obj, status, url, "{{ csrf_token() }}");

	});





  $('body').on('click', '.delete', function(){

	var id = $(this).data('id');

	var parent = $(this).closest('tr');

	var delete_url = '{{ route('stores.destroy') }}';

	delete_row(id, parent, delete_url, "{{ csrf_token() }}");

   });



  });

  </script>

@stop