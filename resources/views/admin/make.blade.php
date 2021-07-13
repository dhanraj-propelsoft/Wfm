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
  <h4 class="float-left page-title">Item Makes</h4>
 
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
 <th>DB Primary Key ID</th>
	  			<th> Name </th> 
		<th> CreatedBy </th>

		
		<th> CreatedOn </th>
		<th>Action</th><th></th>
	  </tr>
	</thead>
	<tbody>
	  @foreach($item_makes as $item_make)
		<tr>
				<td>{{ $item_make->id }}</td>
		
		  	<td>{{ $item_make->name }}</td>
		  	<td>
		  	{{ $item_make->user_name }}
		  	</td>
		 
		  	<td>
		  		{{ $item_make->start_date }}
		  	</td>
		  	 <td>
                	<a data-id="{{ $item_make->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					
				       
				</td>
				<td>

					@if($item_make->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($item_make->status == 0)
						<label class="grid_label badge badge-warning status">In-Active
						</label>
						
					@endif

					<select style="display:none" id="{{ $item_make->id }}" class="active_status form-control">
					<option @if($item_make->status == 1) selected="selected" @endif value="1">Active</option>
					<option @if($item_make->status == 0) selected="selected" @endif value="0">In-Active</option>
					
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
		$.get("{{ route('itemmake_create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });
  $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        isFirstIteration = true;
         $.get("{{ url('admin/makeedit') }}/"+$(this).data('id')+"/edit", function(data) {
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
			var url = "{{ route('make.status') }}";
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