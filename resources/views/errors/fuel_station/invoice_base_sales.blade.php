@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop



@if(Session::get('module_name') == "fuel_station")
	@include('includes.fuel_station')
	@endif
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
  <h4 class="float-left page-title"> Invoice Base Sales</h4>
  </div>
  <div class="modal-body">
	<div class="clearfix"></div>
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<div class="form-inline">

	                  
	                <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="from_date">From Date</label>
	                    {{ Form::text('from_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'id' => 'from_date']) }}
	                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
	                              <div class="col-md-2 form-group">
	                    <label class=" col-form-label" for="to_date">To Date</label>{{ Form::text('to_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off', 'id' => 'to_date']) }}
	                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
	                 <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="invoice_number">Invoice Number</label>
	                    <input type="text" class="form-control" name="invoice_number" id="invoice_number">
	                </div>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
	                 <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="customer_name">Customer Name</label>
	                    <input type="text" class="form-control" name="customer_name" id="customer_name">
	                </div>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
	                
	                 <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="vehicle_number">Vehicle Number</label>
	                    <input type="text" class="form-control" name="vehicle_number" id="vehicle_number">
	                </div>	 
	            </div>
			</div>			
		</div>
		<div class="row">
			<div class="col-md-10"></div>
			<button type="submit" class="btn btn-success tab_save_btn"> Search </button>&nbsp;
			<button type="submit" class="btn btn-primary tab_save_btn"> Export </button>			
		</div>
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			@permission('department-delete')
				<li><a class="multidelete">Delete</a></li>
			@endpermission
			@permission('department-edit')
				<li><a data-value="1" class="multiapprove">Make Active</a></li>
				<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
			@endpermission
		</ul>
	</div>
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	<th> Date </th>
		<th> Time MM:SS </th> 
		<th>Invoice Number</th>
		<th> Product name</th>
		<th> Sales Quality </th>
		<th> Sales Amount </th>
		<th>Customer</th>
		<th> Status </th>
	  </tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
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
		$.get("{{ route('pump.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/departments') }}/"+$(this).data('id')+"/edit", function(data) {
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
			//alert(status);
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('pump.status') }}";
			var data=change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
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