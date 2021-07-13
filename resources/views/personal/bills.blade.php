@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.personal_accounts')
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
  <h4 class="float-left page-title">Inward Vouchers</h4>  
	<a class="btn btn-danger float-right refresh" style="color: #fff; margin-left: 5px;">Refresh</a>
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
	  <tr>
		<th> Serial No. </th> 
		<th> Bill Number </th> 
		<th> Billed By </th> 
		<th> Customer </th>
		<th> Invoice Amount </th>
		<th> Invoice Date </th>
		<th> Balance Due </th>
		<th> Payment Status </th>
		<th> Action </th>
	  </tr>
	</thead>
	<tbody>
	<?php $i = count($bills); ?>
	  @foreach($bills as $bill)
		<tr style="@if($bill->reference_id == 0) background: #fcef8d; @endif">
		  <td><a class="edit" data-id="{{$bill->id}}">{{ $i }}</a></td>
		  <td>{{ $bill->order_no }}</td>
		  <td>{{ $bill->business }}</td>
		  <td>{{ $bill->people }}</td>
		  <td>{{ $bill->total }}</td>
		  <td>{{ $bill->date }}</td>
		  <td>{{ $bill->balance_due }}</td>
		  <td>
			@if($bill->status == '1')
			  <label class="grid_label badge badge-success">Paid</label>
			@elseif($bill->status == '0')
			  <label class="grid_label badge badge-warning">Pending</label>
			@endif           
		  </td> 
		  <td>
		  	<a href="javascript:;" data-id="{{$bill->id}}" data-organization="{{$bill->organization_id}}" class="grid_label badge badge-primary edit">View</a>
			<a href="javascript:;" data-id="{{$bill->id}}" data-date="{{$bill->original_date}}" data-total="{{$bill->total}}" class="grid_label badge  @if($bill->reference_id == 0) badge-info add_expense @else badge-success @endif">@if($bill->reference_id == 0) Add @else Added @endif to Transaction</a>
			</td>
		</tr>
		<?php $i--; ?>
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
   var that = null;

   var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [-1]}],"order": [[5, "desc"]], "stateSave": true };

   function call_back(data, modal, message, id = null) {

   }

   function add_bill(id) {
   	datatable.destroy();
   		if($('.edit[data-id="' + id + '"]')) {
			$('.edit[data-id="' + id + '"]').closest('tr').remove();
	  	}
		datatable = $('#datatable').DataTable(datatable_options);
	  $('.loader_wall_onspot').hide();
	  that.closest("tr").removeAttr('style');
	  that.text("Added to transaction");
	  that.removeClass("badge-info");
	  that.removeClass("add_expense");
	  that.addClass("badge-success");
	  $('.crud_modal').modal('hide');
   }


  $(document).ready(function() {

  datatable = $('#datatable').DataTable(datatable_options);


  $('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('account.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });

  $('body').on('click', '.add_expense', function(e) {
			e.preventDefault();
			that = $(this);
			$.get("{{ url('user/personal/transaction/create/') }}/"+$(this).data('id')+"/transaction", function(data) {
			  $('.crud_modal .modal-container').html("");
			  $('.crud_modal .modal-container').html(data);
			  var transaction_category = $('.crud_modal .modal-container').find('select[name=transaction_category]');
			  var type = "";

			  $('.crud_modal .modal-container').find('input[name=amount]').val(that.data('total'));
			  $('.crud_modal .modal-container').find('input[name=date]').val(that.data('date'));

			  if(that.hasClass('add_expense')) {
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
		$('.loader_wall_onspot').show();
		$.get("{{ url('user/personal/bills') }}/"+$(this).data('id')+"/show/"+$(this).data('organization'), function(data) {
			$('.full_modal_content').show();
			$('.full_modal_content').html("");
			$('.full_modal_content').html(data);
			$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
			$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
			$('.loader_wall_onspot').hide();
		});
  });

  $('body').on('click', '.status', function(e) {
	$(this).hide();
	$(this).parent().find('select').css('display', 'block');
  });

  $('body').on('change', '.active_status', function(e) {
      var status = $(this).val();
      var id = $(this).attr('id');
      var obj = $(this);
      var url = "{{ route('account_status_approval') }}";
      change_status(id, obj, status, url, "{{ csrf_token() }}");
    });

  $('body').on('click', '.delete', function(){
	var id = $(this).data('id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('account.destroy') }}';
	delete_row(id, parent, delete_url, "{{ csrf_token() }}");
   });

  });
  </script>
@stop