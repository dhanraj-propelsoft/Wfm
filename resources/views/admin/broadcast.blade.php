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
  <h4 class="float-left page-title">Broadcast</h4>
  <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> 	
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th>Title</th> 
		<th>Message Type</th> 
		<th>Module</th>
		<th>Organization</th>
		<th>User</th>
		<th>Active</th>
		<th>Action</th>		
	  </tr>
	</thead>
	<tbody>
		@foreach($broadcasts as $broadcast)
		<tr>
			<td>{{$broadcast->tittle}}</td>
			<td>{{$broadcast->message_type}}</td>
			<td>{{$broadcast->module_name}}</td>
			<td>{{$broadcast->organization_name}}</td>
			<td>{{$broadcast->user_name}}</td>
			<td>@if($broadcast->active == '1')
			  <label class="grid_label badge badge-success status">Active</label>
			@elseif($broadcast->active == '0')
			  <label class="grid_label badge badge-warning status">In-Active</label>
			 @else($broadcast->active == 'null')
			 <label class="grid_label badge badge-warning status">In-Active</label>
			@endif
			<select style="display:none" id="{{ $broadcast->id }}" class="active_status form-control">
					<option @if($broadcast->active == 1) selected="selected" @endif value="1">Active</option>
					<option @if($broadcast->active == 0) selected="selected"@endif value="0">In-Active</option>
			</select>
			</td>		
			<td><a data-id="{{ $broadcast->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			<a data-id="{{ $broadcast->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a></td>
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
   datatable = $('#datatable').DataTable(datatable_options);
   $(document).ready(function() {
   	 	$('body').on('click', '.add', function(e){
		e.preventDefault();	
		$.get("{{ route('broadcast_details') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-md');
		$('.crud_modal').modal('show');
  });
   	 	$('body').on('click', '.edit', function(e){
		e.preventDefault();	
		$.get("{{  url('admin/broadcast_details') }}/"+$(this).data('id'), function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-md');
		$('.crud_modal').modal('show');
  });
   	  $('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block').prop('selected', false);
  	});
   	  $('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('active_status') }}";
			var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
		
		});
  		$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = "{{ route('admin.broadcast_delete') }}";
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
      });

   });
  </script>
@stop