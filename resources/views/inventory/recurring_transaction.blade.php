@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@if($transaction_type->type == 0) 
@include('includes.inventory')
@elseif($transaction_type->type == 1) 
@include('includes.trade')
@endif
@section('content')
@include('includes.add_user')
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
	<h4 class="float-left page-title">{{$transaction_type->display_name}}</h4>

	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>

</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
		<tr>
		<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
		<th> Date </th> 
		<th> Customer </th>             
		<th> Due Date </th>              
		<th> Amount </th>             
		<th> Status </th>
		<th> Action </th>
		</tr>
	</thead>
	<tbody>
		@foreach($transactions as $transaction)

	<tr>
		<td width="1">{{ Form::checkbox('transaction',$transaction['id'], null, ['id' => $transaction['id'], 'class' => 'item_check']) }}<label for="{{$transaction['id']}}"><span></span></label></td>
		<td>{{ $transaction['date'] }}</td> 
		<td>{{ $transaction['customer'] }}</td>             
		<td>{{ $transaction['due_date'] }}</td>              
		<td>{{ $transaction['total'] }}</td>             
		<td>
			@if($transaction['status'] == 0)
			<label class="grid_label badge badge-warning">Pending</label> 
			@elseif($transaction['status'] == 1)
			<label class="grid_label badge badge-success">Paid</label> 
			@elseif($transaction['status'] == 2)
			<label class="grid_label badge badge-info">Partially Paid</label> 
			@elseif($transaction['status'] == 3)
			<label class="grid_label badge badge-danger">Over due {{App\Custom::time_difference(Carbon\Carbon::now()->format('Y-m-d H:i:s'), Carbon\Carbon::parse($transaction['original_due_date'])->format('Y-m-d'), 'd')}} days</label> 
			@endif
		</td>		
		<td> 
			<!-- <a data-id="{{$transaction['id']}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a> -->
			<a href="javascript:;" data-id="{{$transaction['id']}}" data-order="{{$transaction['order_no']}}" data-type="{{$transaction['type']}}" data-start="{{$transaction['date']}}" data-due="{{$transaction['due_date']}}" class="grid_label badge badge-info create">Add</a>
			<!-- <a data-id="{{$transaction['id']}}" class="grid_label action-btn print-icon"><i class="fa icon-basic-printer"></i></a>  -->
			@if($type == 'purchase')
			<a href="javascript:;" data-id="{{$transaction['id']}}" class="grid_label badge badge-success">Make Payment</a>
			@elseif($type == 'sale')
			<a href="javascript:;" data-id="{{$transaction['id']}}" class="grid_label badge badge-success">Receive Payment</a>

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

	 var datatable_options = {"stateSave": true};

	$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

		$('.add').on('click', function(e) {
			e.preventDefault(); 
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

					$.get("{{ route('transaction.create', [$type]) }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.loader_wall_onspot').hide();
					});
		
			});
				
		});



		$('.create').on('click', function(e) {
			e.preventDefault(); 
			var obj = $(this);
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

					$.get("{{ route('transaction.create', [$type]) }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.full_modal_content').find('input[name=order_id]').closest('.form-group').hide();
					  $('.full_modal_content').find('input[name=invoice_date]').val(obj.data('start'));
					  $('.full_modal_content').find('input[name=due_date]').val(obj.data('due'));
					  //console.log($('.full_modal_content').find('input[name=end_date]'));			  
					  $('.loader_wall_onspot').hide();
					  order(obj.data('order'), obj.data('type'));
					});
		
			});
				
		});


	
			$('body').on('click', '.delete', function(){
		
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = '{{ route('transaction.destroy') }}';
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
								parent.remove();
								datatable = $('#datatable').DataTable(datatable_options);
								$('.delete_modal_ajax').modal('hide');
								alert_message(data.message, "success");
							},
						 error:function(jqXHR, textStatus, errorThrown) {
							}
						});
			    });
		    }

	});
	</script>
@stop