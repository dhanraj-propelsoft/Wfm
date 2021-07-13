@extends('layouts.master')
@section('head_links') @parent
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.workshop')
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
  <h4 class="float-left page-title">Job Allocation</h4>
  @permission('holidays-create')
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
		<th>Model</th> 
		<th>Work</th>
		<th>Estimated Time</th>
		<th>Rate</th>
		<th>Action</th>
	  </tr>
	</thead>
	<tbody>
	  @foreach($jobs as $job)
		<tr>
		  <td width="1"><input type="checkbox" id="{{ $job->item_id }}_{{ $job->model_id }}" data-item_id="{{ $job->item_id }}" data-model_id="{{ $job->model_id }}" name="job" class="item_check"><label for="{{ $job->item_id }}_{{ $job->model_id }}"><span></span></label>
		  </td>
		  <td>{{ $job->display_name }}</td>
		  <td>{{ $job->work }}</td>
		  <td>{{ $job->estimated_time }}</td>
		  <td> 

		  <?php $rate = App\Custom::get_least_closest_date(json_decode($job->rate_data, true));
				if($job->include_tax != null) {
					echo App\Custom::two_decimal($rate['price']*(($job->tax/100) + 1)) ;
				}
				else {
					echo $rate['price'];
				}
				echo "<span style='color:#aaa'> From ".Carbon\Carbon::parse($rate['date'])->format('jS \\of M Y')."</span>" ;			

			?>
			
		  </td>
		  <td>
		  @permission('holidays-edit')
				<a data-item_id="{{ $job->item_id }}" data-model_id="{{ $job->model_id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			@endpermission
			@permission('holidays-delete')
			  <a data-item_id="{{ $job->item_id }}" data-model_id="{{ $job->model_id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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
		$.get("{{ route('work_allocation.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('workshop/job-allocation') }}/"+$(this).data('item_id')+"/"+$(this).data('model_id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
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
		var url = "{{ route('holidays_status_approval') }}";
		change_status(id, obj, status, url, "{{ csrf_token() }}");
	});


  $('body').on('click', '.delete', function(){
	var item_id = $(this).data('item_id');
	var model_id = $(this).data('model_id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('work_allocation.destroy') }}';
	delete_row(item_id,model_id, parent, delete_url, "{{ csrf_token() }}");
   });


	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('work_allocation.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}");
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('holidays.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
	});


  });
  </script>
@stop