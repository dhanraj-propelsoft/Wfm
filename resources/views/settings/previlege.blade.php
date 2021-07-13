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
		  <h4 class="float-left page-title">User Privileges</h4>
		</div>




<div class="float-left" style="width: 100%; padding-top: 10px">
			<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th> User (Employee) </th>	
				<th> Role </th>
				<th> Action </th>
			</tr>
		</thead>
		<tbody>
		@foreach($employees as $employee)
			<tr>
				<td>{{ $employee->name }}</td>
				<td>
					@if(!empty(App\User::find($employee->user_id)->roles))
						@foreach(App\User::find($employee->user_id)->roles as $v)
							<label class="grid_label badge badge-success roles">{{ $v->display_name }}</label>
						@endforeach
					@endif
									
				</td>
				<td>
					<a data-id="{{$employee->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>

					<a data-id="{{ $employee->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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

	$('body').on('click', '.edit', function(e) {
		var id = $(this).data('id');
		var identify_field = $('input[name=identify_field]').val();

		e.preventDefault();
	$.get("{{ url('privileges') }}/"+id+"/"+identify_field+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
    });


	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('privilege.destroy') }}';
		delete_row(id, parent, delete_url);
   });

		function delete_row(id, parent, delete_url) {
			$('.delete_modal_ajax').modal('show');
				$('.delete_modal_ajax_btn').off().on('click', function() {
					$.ajax({
						 url: delete_url,
						 type: 'post',
						 data: {
							_method: 'delete',
							_token : '{{ csrf_token() }}',
							id: id,
							},
						 dataType: "json",
							success:function(data, textStatus, jqXHR) {
								datatable.destroy();
								parent.find('label.roles').remove();
								parent.find('.delete').remove();
								datatable = $('#datatable').DataTable(datatable_options);
								$('.delete_modal_ajax').modal('hide');
								$('.alert-success').text(data.message);
								$('.alert-success').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							},
						 error:function(jqXHR, textStatus, errorThrown) {
							}
						});
				});
			}

	});
	</script>
@stop