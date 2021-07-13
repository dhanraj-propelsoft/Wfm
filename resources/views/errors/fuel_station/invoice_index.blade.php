@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
<style>
		.dt-buttons {
			display: none;
		}
		.dataTables_length {
			margin-bottom: -35px;
		}
		.dropdown-menu > a:hover {
		    background-color: #e74c3c;
		    color:white;
		}
		.dropdown-menu {
		    background-color: #e74c3c !important;
		   min-width: 3rem;
		}
		.dropdown-menu > a {
		 color: white;
		}
	</style>

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
  <h4 class="float-left page-title">Invoice</h4>
   <a class="btn btn-danger float-right refresh" style="color: #fff">Refresh</a><a class="btn btn-danger float-right multidelete" style="display:none;color: #fff" >Delete</a>
  
  <div class="dropdown float-right">
		<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				New Sale
					
		</button>
		<div class="dropdown-menu "  aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item hover  creditsale" data-name="Credit" > Credit Sale</a>
			
			<a class="dropdown-item hover  cashsale"  data-name="Cash "> Cash Sale</a>
					
		</div>
	</div>

</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  <th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
		<th> Invoice Number </th> 
		<th> Vechicle Number </th>
		<th> Customer </th>
		<th> Invoice Amount</th>
		<th> Invoice Date </th>
		<th> Due Date </th>
		<th> Balance Due </th>
		<th> Payment Status </th>
		<th>Status </th>
	  </tr>
	</thead>
	<tbody>
		@foreach($transactions as $transaction)
		<tr>
		<td width="1">{{ Form::checkbox('transaction',$transaction->id, null, ['id' => $transaction->id, 'class' => 'item_checkbox','data-id'=>$transaction->vehicle_id]) }}<label for="{{$transaction->id}}"><span></span></label>
					</td>
		<td>{{$transaction->order_no}}</td>
		<td>{{$transaction->registration_no}}</td>
		<td>{{$transaction->customer}}</td>
		<td>{{$transaction->total}}</td>
		<td>{{$transaction->job_date}}</td>
		<td>{{$transaction->job_due_date}}</td>
		<td>{{$transaction->balance}}</td>
		<td>
						@if($transaction->status == 0)
							<label class="grid_label badge badge-warning">Pending</label> 
						@elseif($transaction->status == 1)
							<label class="grid_label badge badge-success">Paid</label> 
						@elseif($transaction->status == 2)
							<label class="grid_label badge badge-info">Partially Paid</label> 
						@elseif($transaction->status == 3)
							<label class="grid_label badge badge-danger">Over due {{App\Custom::time_difference(Carbon\Carbon::now()->format('Y-m-d H:i:s'), Carbon\Carbon::parse($transaction->original_due_date)->format('Y-m-d'), 'd')}} days</label> 
						@endif
					</td>
					<td>		
						@if($transaction->approval_status == 0)
							<label class="grid_label badge badge-warning status">Draft</label> 
						@elseif($transaction->approval_status == 1)
							<label class="grid_label badge badge-success status">Approved</label> 
						@endif
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

 	 $('.item_checkbox').on('click', function(e) {
	 	$('.multidelete').show();
	 });

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/departments') }}/"+$(this).data('id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  	});

  	
  	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');

		var parent = $(this).closest('tr');
		var delete_url = '{{ route('tank.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	});
	
  	
$('.cashsale').on('click', function(e) {
			e.preventDefault(); 
			var that = $(this);
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

				

					$.get("{{ route('invoice_create',['job_invoice_cash']) }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					  $('.loader_wall_onspot').hide();
					});
				
			});
				
		});

$('.creditsale').on('click', function(e) {
			e.preventDefault(); 
			var that = $(this);
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

				

					$.get("{{ route('invoice_create',['job_invoice']) }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					  $('.loader_wall_onspot').hide();
					});
				
			});
				
		});
  });
  </script>
@stop