@extends('layouts.master')
@section('head_links') @parent
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
@section('content')
@include('includes.add_business')

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
  <h4 class="float-left page-title">Branches</h4>
  @permission('branches-create')
	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  @endpermission
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
	<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
	</div>
	<ul class="batch_list">
	  <li><a class="multidelete">Delete</a></li>
	 
	</ul>
	</div>
  <table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  	<tr>
	  		<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label>
	  		</th>		
			<th>Branch Name</th> 
			<th>Description</th>
			<th>Action</th>
	  </tr>
	</thead>
	<tbody>
	  @foreach($branches as $branch)
		<tr> 
			<td width="1">{{ Form::checkbox('branch',$branch->id, null, ['id' => $branch->id, 'class' => 'item_check']) }}<label for="{{$branch->id}}"><span></span></label>
			</td>
		  	<td>{{ $branch->branches_name }}</td>
		  	<td>{{ $branch->description }}</td>
		  	<td>           
			@permission('branches-edit')
			 	<a data-id="{{ $branch->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			@endpermission
			@permission('branches-delete')
				<a data-id="{{ $branch->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 
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
		$.get("{{ route('branches.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/branches') }}/"+$(this).data('id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.status', function(e) {
	$(this).hide();
	$(this).parent().find('select').css('display', 'block');
  });



	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('branches.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	});

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('branches.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}");
	});

	
  });
  </script>
@stop