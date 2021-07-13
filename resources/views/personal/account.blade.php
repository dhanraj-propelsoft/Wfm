@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.personal_accounts')
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
  <h4 class="float-left page-title">Cash and Bank</h4>  
	<a class="btn btn-danger float-right refresh" style="color: #fff; margin-left: 5px;">Refresh</a>
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
	  <tr>
		<th> Accounts Name </th> 
		<th> Accounts Details </th> 
		<th> Balance </th>
		<th> Status </th>
		<th> Action </th>
	  </tr>
	</thead>
	<tbody>
	  @foreach($personal_accounts as $personal_account)
		<tr>
		  <td>{{ $personal_account->name }}</td>
		  <td>{{ $personal_account->account_number }}</td>
		  <td class="replaceSign">{{ $personal_account->closing_balance }}</td>
		  <td>
			@if($personal_account->status == '1')
			  <label class="grid_label badge badge-success status">Active</label>
			@elseif($personal_account->status == '0')
			  <label class="grid_label badge badge-warning status">In-Active</label>
			@endif
			
			<select style="display:none" id="{{ $personal_account->id }}" class="active_status form-control">
			  <option @if($personal_account->status == 1) selected="selected" @endif value="1">Active</option>
			  <option @if($personal_account->status == 0) selected="selected" @endif value="0">In-Active</option>
			</select>            
		  </td>
		  <td>
			  <a data-id="{{ $personal_account->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			  
			  @if($personal_account->delete_status == '1')
				<a data-id="{{ $personal_account->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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

  replaceSign();

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('account.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('user/account') }}/"+$(this).data('id')+"/edit", function(data) {
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
	  var url = "{{ route('account_status_approval') }}";
	  change_status(id, obj, status, url, "{{ csrf_token() }}");
	});



  $('body').on('click', '.delete', function(){
	var id = $(this).data('id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('account.destroy') }}';
	delete_row(id, parent, delete_url, "{{ csrf_token() }}");
   });

  });
  </script>
@stop