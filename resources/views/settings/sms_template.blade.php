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
		 <h4 class="float-left page-title">SMS Templates</h4>
		 <a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
</div>
<div class="float-left" style="width: 100%; padding-top: 10px">
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>Summary</th>	
				<th>SMS Text</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($sms_templates as $sms_template)
			<tr>
				<td>{{$sms_template->sms_type}}</td>
				<td>{{str_limit(strip_tags($sms_template->sms_content),50,'...')}}</td>
				<td><a data-id="{{ $sms_template->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
				<a data-id="{{ $sms_template->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a></td>
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

   	$('body').on('click', '.add', function(e){
		e.preventDefault();	
		$.get("{{ route('sms_template_details') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-md');
		$('.crud_modal').modal('show');
  	});
  		$('body').on('click', '.edit', function(e){
		e.preventDefault();	
		$.get("{{  route('sms_template_details') }}/"+$(this).data('id'), function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-md');
		$('.crud_modal').modal('show');
  });
  		$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = "{{ route('settings.sms_templates_delete') }}";
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
      });
  </script>
@stop