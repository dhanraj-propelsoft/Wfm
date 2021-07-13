@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.personal_people')
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
	<h4 class="float-left page-title">My Circle People</h4>  
		<a class="btn btn-danger float-right refresh" style="color: #fff; margin-left: 5px;">Refresh</a>
		<a class="btn btn-danger float-right multidelete" style="color: #fff; margin-left: 5px;">Delete</a>
		<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px">
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th width="1"> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th> 
				<th> Person Name </th> 
				<th> Relationship </th> 
				<th> Mobile </th> 
				<th> Email </th>
				<th> Aadhar </th>
				<th> PAN </th>
				<th> Status </th>
				<th> Action </th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 1; ?>
			@foreach($people as $person)
				<tr>
					<td width="1">{{ Form::checkbox('person',$person->id, null, ['id' => $person->id, 'class' => 'item_check']) }}<label for="{{$person->id}}"><span></span></label></td>
					<td>{{ $person->name }}</td>
					<td>{{ $person->relationship }}</td>
					<td>{{ $person->mobile }}</td>
					<td>{{ $person->email }}</td>
					<td>{{ $person->aadhar }}</td>
					<td>{{ $person->pan }}</td>
					<td>
						@if($person->status == '1')
							<label class="grid_label badge badge-success status">Active</label>
						@elseif($person->status == '0')
							<label class="grid_label badge badge-warning status">In-Active</label>
						@endif
						
						<select style="display:none" id="{{ $person->id }}" class="active_status form-control">
							<option @if($person->status == 1) selected="selected" @endif value="1">Active</option>
							<option @if($person->status == 0) selected="selected" @endif value="0">In-Active</option>
						</select>            
					</td>
					<td>
						<a data-id="{{ $person->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
							
						<a data-id="{{ $person->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<?php $i++; ?>
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

	 var datatable_options = {"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};


	$(document).ready(function() {

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('personal_people.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}", $(".table_container"));
	});

	datatable = $('#datatable').DataTable(datatable_options);

	$('.add').on('click', function(e) {
				e.preventDefault();
				$.get("{{ route('personal_people.create') }}", function(data) {
					$('.crud_modal .modal-container').html("");
					$('.crud_modal .modal-container').html(data);
				});
				//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
				$('.crud_modal').modal('show');
	});

	$('body').on('click', '.edit', function(e) {
				e.preventDefault();
				$.get("{{ url('user/people') }}/"+$(this).data('id')+"/edit", function(data) {
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
      var url = "{{ route('personal_people_status_approval') }}";
      change_status(id, obj, status, url, "{{ csrf_token() }}");
    });

	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('personal_people.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	 });

	});
	</script>
@stop