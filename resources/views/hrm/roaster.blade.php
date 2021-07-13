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
  <h4 class="float-left page-title">Roaster</h4>
  @permission('department-create')
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  @endpermission
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			<li><a class="multidelete">Delete</a></li>
			<li><a data-value="1" class="multiapprove">Make Active</a></li>
			<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
		</ul>
	</div>
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
		<th> Roaster </th> 
		<th> Duration </th>
		<th> Description </th>
		<th> Status </th>
		<th> Action </th>
	  </tr>
	</thead>
	<tbody>
	  @foreach($roasters as $roaster)
		<tr>
			<td width="1">{{ Form::checkbox('roaster',$roaster->id, null, ['id' => $roaster->id, 'class' => 'item_check']) }}<label for="{{$roaster->id}}"><span></span></label></td>
		  <td>{{ $roaster->name }}</td>
		  <td>
		  	<span class="rearrangedatetext">{{ $roaster->from_date }}</span> - 
		  	<span class="rearrangedatetext">{{ $roaster->to_date }}</span>
		  </td>
		  <td>{{ $roaster->description }}</td>
		  <td>
			@if($roaster->status == '1')
			  <label class="grid_label badge badge-success status">Active</label>
			@elseif($roaster->status == '0')
			  <label class="grid_label badge badge-warning status">In-Active</label>
			@endif

			@permission('roaster-edit')
			  <select style="display:none" id="{{ $roaster->id }}" class="active_status form-control">
				<option @if($roaster->status == 1) selected="selected" @endif value="1">Active</option>
				<option @if($roaster->status == 0) selected="selected" @endif value="0">In-Active</option>
			  </select>
			@endpermission
		  </td>
		  <td>
			@permission('roaster-edit')
			  <a data-id="{{ $roaster->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			@endpermission

			@permission('roaster-delete')
			  <a data-id="{{ $roaster->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 
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

	function call_back(data, modal, message, id = null) {
	  datatable.destroy();
	  if($('.edit[data-id="' + id + '"]')) {
		$('.edit[data-id="' + id + '"]').closest('tr').remove();
	  }
	$('.data_table tbody').prepend(data);
	datatable = $('#datatable').DataTable(datatable_options);
	$('.crud_modal').modal('hide');

	$('.alert-success').text(message);
	$('.alert-success').show();

	setTimeout(function() { $('.alert').fadeOut(); }, 3000);
  }

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('roaster.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/roaster') }}/"+$(this).data('id')+"/edit", function(data) {
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
			var url = "{{ route('department_status_approval') }}";
			change_status(id, obj, status, url, "{{ csrf_token() }}");
		});


  $('body').on('click', '.delete', function(){
	var id = $(this).data('id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('hrm_departments.destroy') }}';
	delete_row(id, parent, delete_url, "{{ csrf_token() }}");
   });

$('body').on('click', '.multidelete', function() {
	var url = "{{ route('hrm_departments.multidestroy') }}";
	multidelete($(this), url, "{{ csrf_token() }}");
});

$('body').on('click', '.multiapprove', function() {
	var url = "{{ route('hrm_departments.multiapprove') }}";
	multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
});


  });
  </script>
@stop