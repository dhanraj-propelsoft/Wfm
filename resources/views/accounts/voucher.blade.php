@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.accounts')
@section('content')

<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif

<div class="fill header">
  <h4 class="float-left page-title">Voucher List</h4>
  @permission('voucher-master-create') <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> @endpermission
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
    <thead>
      	<tr>
	        <th> Voucher Name </th>
	        <th> Voucher Type </th>
	        <th> Voucher Code </th>
	        <th> Action </th>
	     </tr>
    </thead>
    <tbody>
    
    @foreach($account_voucher_masters as $account_voucher_master)
    <tr>
      	<td>{{ $account_voucher_master->display_name }}</td>
      	<td>{{ $account_voucher_master->voucher_type_name }}</td>
      	<td>{{ $account_voucher_master->code }}</td>
      	<td>           
            @permission('voucher-master-edit')
              <a data-id="{{ $account_voucher_master->id }}" class="grid_label action-btn edit-icon edit">
              <i class="fa li_pen"></i></a>
            @endpermission

            @if($account_voucher_master->delete_status == 1)
	            @permission('voucher-master-delete')
	              <a data-id="{{ $account_voucher_master->id }}" class="grid_label action-btn delete-icon delete">
	              <i class="fa fa-trash-o"></i></a> 
	            @endpermission
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
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>

<script type="text/javascript">
   var datatable = null;

   var datatable_options = {"stateSave": true};

  $(document).ready(function() {

  datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
        e.preventDefault();
        $.get("{{ route('voucher_list.create') }}", function(data) {
          $('.crud_modal .modal-container').html("");
          $('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        $.get("{{ url('accounts/voucher/list') }}/"+$(this).data('id')+"/edit", function(data) {
          $('.crud_modal .modal-container').html("");
          $('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
  });

  $('body').on('click', '.delete', function(){
    var id = $(this).data('id');
    var parent = $(this).closest('tr');
    var delete_url = '{{ route('voucher_list.destroy') }}';
    delete_row(id, parent, delete_url, '{{ csrf_token() }}');
   });


  });
  </script>
@stop