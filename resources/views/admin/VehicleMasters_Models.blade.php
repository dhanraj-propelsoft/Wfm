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
  <h4 class="float-left page-title">Model</h4>
 
	<a class="btn btn-danger float-right add" id="add" style="color: #fff">+ New</a>
  

</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
	<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th>DB Primary ID</th> 
		<th> Type  </th>
		<th>Category </th>
		<th>Make</th>
		<th>Model</th>
		<th>CreatedBy </th>	
		<th>CreatedOn</th>
		<th>Edit</th>
		<th></th>
		 </tr>
	</thead>
	<tbody>
				@foreach($models as $model)
				<tr>
					<td>{{$model->id}}</td>
					<td> {{$model->type}} </td>
					<td>{{$model->category}}</td>
					<td> {{$model->make}} </td>
					<td> {{$model->model}} </td>
					<td> {{$model->user_name}} </td>	
					<td> {{$model->start_date}} </td> 
					<td>
                	<a data-id="{{ $model->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>     
				</td>
					<td>
					@if($model->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($model->status == 0)
						<label class="grid_label badge badge-warning status">In-Active
						</label>	
					@endif
					<select style="display:none" id="{{ $model->id }}" class="active_status form-control">
					<option @if($model->status == 1) selected="selected" @endif value="1">Active</option>
					<option @if($model->status == 0) selected="selected" @endif value="0">In-Active</option>
					</select>
				</td>	
				</tr>
				@endforeach
			</tbody>
  </table>
</div>

@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmodel-0.1.32/pdfmodel.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmodel-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript">
   var datatable = null;

   var datatable_options = {"order": [[1, "asc"]], "stateSave": true};

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('#add').on('click', function(e) {
  	console.log("work it process");
		e.preventDefault();
		$.get("{{ route('Vehicle_Modelcreate') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  	$('body').on('click', '.edit', function(e) {
        e.preventDefault();
        isFirstIteration = true;
         $.get("{{ url('admin/vehicle_modeledit') }}/"+$(this).data('id')+"/edit", function(data) {
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
			var url = "{{ route('vehicle_model.status') }}";
			var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
			// $("#expire_"+id).text(data.expire_on);
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

