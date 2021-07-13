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
  <h4 class="float-left page-title">Log Register</h4>
  @permission('ot-register-create')
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  @endpermission
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			@permission('ot-register-delete')
				<li><a class="multidelete">Delete</a></li>
			@endpermission
		</ul>
	</div>
  <table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
		<th> Log Date </th>
		<th>Person Type</th>
		<th>Name</th>
		<th> In Time </th>
		<th> Out Time </th>
		<th> Action </th>
	  </tr>
	</thead>
	<tbody>
	@foreach($log_registers as $log_register)
		<tr>
			<td width="1">{{ Form::checkbox('log_register',$log_register->id, null, ['id' => $log_register->id, 'class' => 'item_check']) }}<label for="{{$log_register->id}}"><span></span></label></td>
			<td> {{ $log_register->log_date }} </td>
		  	<td> {{ $log_register->person_type}} </td>
		  	<td>
		  		@if($log_register->person_id != '')
					{{ $log_register->person_name }}
				@endif
				@if($log_register->employee_id != '')
					{{ $log_register->employee_name }}
				@endif 
			</td>
		  	<td> {{ $log_register->in_time }} </td>
		  	<td> {{ $log_register->out_time }} </td>		  	

		  	<td>
		  		@permission('ot-register-edit')
					<a data-id="{{ $log_register->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
            	@endpermission
            	@permission('ot-register-delete')
              		<a data-id="{{ $log_register->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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

  $(document).ready(function() {

  
  datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('log_registers.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/log-register') }}/"+$(this).data('id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  });



  	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('log_registers.destroy') }}';
		delete_row(id, parent, delete_url, '{{ csrf_token() }}');
   	});

  	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('log_registers.multidestroy') }}";
		multidelete($(this), url, '{{ csrf_token() }}');
	});

  });
  </script>
@stop