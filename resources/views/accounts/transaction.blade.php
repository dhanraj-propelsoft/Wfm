@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.personal')
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
	<h4 class="float-left page-title">Transactions</h4>

	<a class="btn btn-danger float-right multidelete" style="color: #fff; margin-right: 5px;">+ Delete</a>
	<a class="btn btn-success float-right add_income" style="color: #fff; margin-right: 5px;">+ New Income</a>
	<a class="btn btn-danger float-right add_expense" style="color: #fff; background-color:#af0505; margin-right: 5px;">+ New Expense</a>

</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px">

	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
		<tr>
		<th width="1"> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
		<th> Date </th> 
		<th> Category </th>
		<th> Amount </th>
		<th> Action </th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		@foreach($transactions as $transaction)

	<tr>
		<td width="1">{{ Form::checkbox('transaction',$transaction->id, null, ['id' => $transaction->id, 'class' => 'item_check']) }}<label for="{{$transaction->id}}"><span></span></label></td>
		<td>{{ $transaction->date }}</td>
		<td>{{ $transaction->category }}</td>
		<td>
		<span style="color: <?php if($transaction->transaction_type == "expense") { ?> #ff0000 <?php } else { ?> #00af00 <?php } ?>">
		{{ $transaction->amount }}</span>
		</td>		
		<td>  
			<a data-id="{{$transaction->id}}" data-order="{{$transaction->order_no}}" data-type="{{$transaction->transaction_type_id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			<a data-id="{{$transaction->id}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
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

	 var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [-1]}],"order": [], "stateSave": true };

	 function add_bill(id) {

	 }

	$(document).ready(function() {

		$('body').on('click', '.multidelete', function() {
			var url = "{{ route('personal_transaction.multidestroy') }}";
			multidelete($(this), url, '{{ csrf_token() }}', $(".table_container"));
		});

		datatable = $('#datatable').DataTable(datatable_options);


		$('.add_income, .add_expense').on('click', function(e) {
			e.preventDefault();
			var that = $(this);
			$.get("{{ route('personal_transaction.create') }}", function(data) {
			  $('.crud_modal .modal-container').html("");
			  $('.crud_modal .modal-container').html(data);
			  var transaction_category = $('.crud_modal .modal-container').find('select[name=transaction_category]');
			  var type = "";

			  if(that.hasClass('add_income')) {
				$('.crud_modal .modal-container').find('.modal-title').text("Add Income Transaction");
				$('.crud_modal .modal-container').find('.account').text("Income Account");
				$('.crud_modal .modal-container').find('.source').text("Source of Income");
					type = "income";
			  } else if(that.hasClass('add_expense')) {
				$('.crud_modal .modal-container').find('.modal-title').text("Add Expense Transaction");
				$('.crud_modal .modal-container').find('.account').text("Expense Account");
				$('.crud_modal .modal-container').find('.source').html("Source&nbsp;of&nbsp;Expense");
					type = "expense";
			  }
					
				$('.crud_modal .modal-container').find('input[name=transaction_type]').val(type);

			  $.ajax({
					url: '{{ route('get_personal_transaction_category') }}',
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						type: type,  
					},
					success:function(data, textStatus, jqXHR) {
						transaction_category.empty();
						transaction_category.append('<option value="">Select Category</option>');

						for(var i in data) {
							transaction_category.append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
						}
					}
				});

			});
			//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
	  });

	  $('body').on('click', '.edit', function(e) {
			e.preventDefault();
			$.get("{{ url('user/personal/transaction') }}/"+$(this).data('id')+"/edit", function(data) {
			  $('.crud_modal .modal-container').html("");
			  $('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').modal('show');
	  });


	  $('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('personal_transaction.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	   });


	});
	</script>
@stop