@extends('layouts.master')
@section('head_links') @parent
@stop
@include('includes.settings')
@section('content')



<!-- Modal Starts -->
	<div class="modal fade bs-modal-lg invoice_modal" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-container">
				<div class="modal-header"><h4 style="text-transform:capitalize;" class="modal-title float-right">Payment </h4></div>
				{!! Form::open(['class' => 'form-horizontal invoicevalidateform']) !!}
					<div class="modal-body">
						<div class="form-body">
							<div class="row">
								<div class="form-group col-md-4">	
									{{ Form::label('invoice_payment_date', 'Payment Date', array('class' => 'control-label col-md-12 required')) }}
									<div class="col-md-12">
										{!! Form::text('payment_date', null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) !!}
									</div>
								</div>	
								<div class="form-group col-md-4">	
									{{ Form::label('invoice_payment_method', 'Payment Method', array('class' => 'control-label col-md-12 required')) }}
									<div class="col-md-12">
										{{ Form::select('invoice_payment_method', $payment, null, ['class' => 'form-control select_item', 'id' => 'invoice_payment_method']) }}
									</div>
								</div>	
								<div class="form-group col-md-4">	
									{{ Form::label('invoice_payment_ledger', 'Payment From', array('class' => 'control-label col-md-12 required')) }}
									<div class="col-md-12">
										{{ Form::select('invoice_payment_ledger', $ledgers, null, ['class' => 'form-control select_item', 'id' => 'invoice_payment_ledger']) }}
									</div>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-md-4">	
									{{ Form::label('invoice_due_amount', 'Due Amount', array('class' => 'control-label col-md-12 required')) }}
									<div class="col-md-12">
										{!! Form::text('invoice_due_amount', null, ['class'=>'form-control',  'disabled']) !!}
									</div>
								</div>
								<div class="form-group col-md-4">	
									{{ Form::label('invoice_payment_amount', 'Payment Amount', array('class' => 'control-label col-md-12 required')) }}
									<div class="col-md-12">
										{!! Form::text('invoice_payment_amount', null, ['class'=>'form-control price']) !!}
									</div>
								</div>
								<div class="form-group col-md-4">	
									{{ Form::label('payment_details', 'Payment Details', array('class' => 'control-label col-md-12')) }}
									<div class="col-md-12">
										{!! Form::text('description', null, ['class'=>'form-control']) !!}
									</div>
								</div>
							</div>
							
							<!-- <div class="row">
								<div class="form-group col-md-4">	
									<div class="col-md-12">
										{{ Form::checkbox('grn_info', '1', null, array('id' => 'grn_info')) }} 
										<label for="grn_info"><span></span>Need  Info</label>
									</div>
								</div>
								<div style="display: none;" class="form-group col-md-4 grn">	
									{{ Form::label('grn_no', ' No.', array('class' => 'control-label col-md-12 required')) }}
									<div class="col-md-12">
										{!! Form::text('grn_no', null, ['class'=>'form-control']) !!}
									</div>
								</div>
							</div> -->
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success">Submit</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
<!-- Modal Ends -->



<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Notifications</h4>
</div>
<div class="clearfix"></div>

<div class="row">

  <div class="col-md-12">
	  <ul class="notifications">

	@foreach($notifications as $notification)

		<li style="
		@if($notification['category'] == "job_invoice" || $notification['category'] == "job_invoice_cash" ) 
		@if($notification['notification_status'] == "2") background-color:#98FB98 @endif @endif

		@if($notification['category'] == "purchase_order" || $notification['category'] == "sales" || $notification['category'] == "sales_cash") 
		@if($notification['notification_status'] == "3") background-color:#98FB98 @endif @endif


		@if($notification['category'] == "job_invoice" || $notification['category'] == "job_invoice_cash" || $notification['category'] == "purchase_order" || $notification['category'] == "sales" || $notification['category'] == "sales_cash"  ) 
		@if($notification['notification_status'] == "1") background-color:#F08080 @endif @endif

		@if($notification['category'] == "ledger")
		@if($notification['notification_status'] == "0")		background-color:#F08080 @endif @endif

		@if($notification['category'] == "ledger")
		@if($notification['notification_status'] == "4")		background-color:#FFFF66 @endif @endif

		@if($notification['category'] == "receipt" || $notification['category'] == "payment") background-color:#F08080 @endif		

		@if($notification['category'] == "job_invoice" || $notification['category'] == "job_invoice_cash" || $notification['category'] == "purchase_order" || $notification['category'] == "sales" || $notification['category'] == "sales_cash" ) 
		@if($notification['notification_status'] == "4") background-color:#FFFF66 @endif @endif"
		>

		<div class="label label-sm label-success">
			<i class="fa fa-bell-o"></i>
		</div>
		<div style="width: 100%; text-align: right; font-style: italic;" class="date" data-toggle="tooltip" data-placement="top" title="this time created at vendor Company"> {{$notification['time']}} </div>
		<h5 style="float: left; padding-left: 10px;"> {{$notification['message']}} </h5><br>
		<div style="float: right; margin-top: -15px;" class="action_dropdown">
		<a>Action</a>
		<div>
			<ul>
				@if($notification['category'] == "purchase_order" || $notification['category'] == "purchases")
				
				<li><a id="{{ $notification['id'] }}" onclick="event.preventDefault();
				 document.getElementById('sale_order{{ $notification['id'] }}').submit();" class="add_to_account">Make SaleOrder</a>
					<form id="sale_order{{ $notification['id'] }}" action="{{ route('add_to_account') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input type="text" name="id" value="{{ $notification['id'] }}">
						<input type="text" name="type" value="sale_order">
						<input type="text" name="notification_type" value="remote">
					</form>
				</li>
				<li><a id="{{ $notification['id'] }}" onclick="event.preventDefault();
				 document.getElementById('estimation{{ $notification['id'] }}').submit();" class="add_to_account" >Make Estimate</a>
					<form id="estimation{{ $notification['id'] }}" action="{{ route('add_to_account') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input type="text" name="id" value="{{ $notification['id'] }}">
						<input type="text" name="type" value="estimation">
						<input type="text" name="notification_type" value="remote">
					</form>
				</li>
				<li><a id="{{ $notification['id'] }}" onclick="event.preventDefault();
				 document.getElementById('sales{{ $notification['id'] }}').submit();" class="add_to_account" >Make Invoice</a>
					<form id="sales{{ $notification['id'] }}" action="{{ route('add_to_account') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input type="text" name="id" value="{{ $notification['id'] }}">
						<input type="text" name="type" value="sales">
						<input type="text" name="notification_type" value="remote">
					</form>
				</li>

				@if($notification['category'] == "purchases")

					<li><a id="{{ $notification['id'] }}" class="add_expense" >Add as Expense</a></li>

				@endif

			@elseif($notification['category'] == "sales" || $notification['category'] == "sales_cash")
				<li><a id="{{ $notification['id'] }}" onclick="event.preventDefault();
				 document.getElementById('purchases{{ $notification['id'] }}').submit();" class="add_to_account">Make Purchase</a>
					<form id="purchases{{ $notification['id'] }}" action="{{ route('add_to_account') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input type="text" name="id" value="{{ $notification['id'] }}">
						<input type="text" name="type" value="purchases">
						<input type="text" name="notification_type" value="remote">
					</form>
				</li>


			@elseif($notification['category'] == "job_invoice" || $notification['category'] == "job_invoice_cash")

				<li style=""><a id="{{ $notification['id'] }}" onclick="event.preventDefault();
				document.getElementById('purchases{{ $notification['id'] }}').submit();" class="add_to_account">View</a>
					<form id="purchases{{ $notification['id'] }}" action="{{ route('add_to_account') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input type="text" name="id" value="{{ $notification['id'] }}">
						<input type="text" name="type" value="purchases">
						<input type="text" name="notification_type" value="remote">
					</form>
				</li>

				<li><a id="{{ $notification['id'] }}" class="add_expense" >Make Expense</a></li>


			@elseif($notification['category'] == "delivery_note")
				<li><a id="{{ $notification['id'] }}" onclick="event.preventDefault();
				 document.getElementById('goods_receipt_note{{ $notification['id'] }}').submit();" class="add_to_account">Make Goods Receipt Note</a>
					<form id="goods_receipt_note{{ $notification['id'] }}" action="{{ route('add_to_account') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
						<input type="text" name="id" value="{{ $notification['id'] }}">
						<input type="text" name="type" value="goods_receipt_note">
						<input type="text" name="notification_type" value="remote">
					</form>
				</li>
			@elseif($notification['category'] == "ledger")
				<li><a id="{{ $notification['id'] }}" style="color: #333;" href="{{ route('ledgers.show', [$notification['id']]) }}" class="add_to_ledger">View</a></li>
			@elseif($notification['category'] == "group")
				<li><a id="{{ $notification['id'] }}" style="color: #333;" href="{{ route('group.show', [$notification['id']]) }}">View</a></li>
			@elseif($notification['category'] == "estimation" || $notification['category'] == "sale_order")
				<li><a id="{{ $notification['id'] }}" style="color: #333;" href="{{ route('remote_transaction', [$notification['id']]) }}">View</a></li>
			@elseif($notification['category'] == "credit_note" || $notification['category'] == "debit_note")
				<li><a id="{{ $notification['id'] }}" style="color: #333;" href="{{ route('remote_transaction', [$notification['id']]) }}">Read</a></li>
			@elseif($notification['category'] == "receipt" || $notification['category'] == "payment")
				<li><a class="process_invoice" 
				data-id="{{ $notification['id'] }}" 
				data-user_type="{{ $notification['user_type'] }}"
				data-people_id="{{ $notification['user_type'] }}"
				data-reference_no="" 
				data-total="{{ $notification['total'] }}"
				data-balance="{{ $notification['total'] }}"
				data-type="{{$notification['type']}}" 
				data-date="{{$notification['date']}}"
				style="color: #333;" href="javascript:;">Make @if($notification['category'] == 'receipt') Payment @elseif($notification['category'] == 'payment') Receipt @endif</a></li>
			@endif

			<!-- @if($notification['type'] == "accounts" && $notification['category'] == "ledger")
					href="{{ route('ledgers.show', [$notification['id']]) }}" 
				@elseif($notification['type'] == "accounts" && $notification['category'] == "group")
					href="{{ route('group.show', [$notification['id']]) }}" 
				@elseif($notification['type'] == "trade" && $notification['category'] == "transaction")
					href="{{ route('remote_transaction', [$notification['id']]) }}" 
			@endif -->
			</ul>
		  </div>
		</div>
	</li>

	@endforeach


	  </ul>
  </div>
</div>
@stop

@section('dom_links')
@parent 

<script type="text/javascript">

	$(document).ready(function() {

		var user_type = '';
		var people_id = '';
		var type = '';
		var reference_id = '';
		var order_id = '';
		var balance = '';
		var date = '';
		var name ='';
		var mobile ='';

		$('body').on('click', '.process_invoice', function(e) {
			e.preventDefault();

			user_type = $(this).data('user_type');
			people_id = $(this).data('people_id');
			type = $(this).data('type');
			reference_id = $(this).data('id');
			order_id = $(this).data('reference_no');
			balance = $(this).data('balance');
			date = $(this).data('date');

			$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').show();
			validator.resetForm();
			$('.invoice_modal').find('.modal-title').text(type);
			$('.invoice_modal').find('input[name=payment_date]').val(date);
			$('.invoice_modal').find('input[name=invoice_due_amount]').val($(this).data('balance'));
			$('.invoice_modal').find('input[name=invoice_payment_amount]').val($(this).data('balance'));
			$('.invoice_modal').modal('show');

		});

		/*$('.discord').on('click', function() {
			var obj = $(this);
			var id = obj.attr('id');

			$.ajax({
				url: "{{route('discard_notifications')}}",
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: id,
					status: '4',
				},
				success: function(data, textStatus, jqXHR) {
					obj.closest('li').slideUp();
					obj.closest('li').remove();
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});

		});*/

		$('.add_to_account').on('click', function() {
			var obj = $(this);
			var id = obj.attr('id');

			$.ajax({
				url: "{{route('discard_notifications')}}",
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: id,
					status: '4',
				},
				success: function(data, textStatus, jqXHR) {
					console.log(data.status);
					//obj.closest('li').css("background-color","F08080");
					//obj.closest('li').remove();
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});

		});

		$('.add_to_ledger').on('click', function() {
			var obj = $(this);
			var id = obj.attr('id');

			$.ajax({
				url: "{{route('ledger_notifications')}}",
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: id,
					status: '4',
				},
				success: function(data, textStatus, jqXHR) {
					console.log(data.status);
					//obj.closest('li').css("background-color","F08080");
					//obj.closest('li').remove();
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});

		});

		

		$('.action_dropdown ul a').each( function(){
			$(this).on('click', function(e){
				e.preventDefault();
				var obj = $(this);
				var id = obj.attr('id');
				var url = obj.attr('href');

				if (typeof url !== typeof undefined && url !== false) {
					$.ajax({
						url: '{{ route('notification_status') }}',
						type: 'post',
						data:{
							id: id,
							_token: '{{ csrf_token() }}',
						},
						success:function(data, textStatus, jqXHR) {
							location.assign(url);
						},
						error:function(jqXHR, textStatus, errorThrown) {
						}
					});
				}
			});
		});


		$('.add_expense').on('click', function(e) {
			e.preventDefault();
			var id = $(this).attr('id');

			var transaction_name = 'purchases';
			//var notification_type = 'remote';		


			$.ajax({
				url: "{{ route('add_to_transaction') }}",
				type: 'post',
				data: {
					_token : '{{ csrf_token() }}',
					id: id,
					transaction_name : transaction_name,
				},
				success:function(data, textStatus, jqXHR) {
					window.location.replace('{{ route('notifications') }}');
				}
			});

		});

		var validator = $('.invoicevalidateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				user_type: {
					required: true
				},
				payment_date: {
					required: true
				},
				invoice_payment_method: {
					required: true
				},
				invoice_payment_ledger: {
					required: true
				},
				invoice_payment_amount: {
					required: true
				},
				grn_no: {
					required: true
				}
			},

			messages: {
				user_type: {
					required: "Customer type is required"
				},
				payment_date: {
					required: "Payment Date is required"
				},
				invoice_payment_method: {
					required: "Payment Method is required"
				},
				invoice_payment_ledger: {
					required: "Payment From is required"
				},
				invoice_payment_amount: {
					required: "Payment Amount is required"
				},
				grn_no: {
					required: "GRN No. is required"
				}
			},

			invalidHandler: function(event, validator) { //display error alert on form submit   
				$('.alert-danger', $('.login-form')).show();
			},

			highlight: function(element) { // hightlight error inputs
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function(label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},

			submitHandler: function(form) {
				var total_amount = $('.invoice_modal').find('input[name=invoice_payment_amount]').val();
				var grn_no = $('.invoice_modal').find('input[name=grn_no]').val()

							
				$.ajax({
				 	url: "{{ route('cash_transaction.store') }}",
				 	type: 'post',
				 	data: {
						_token: '{{ csrf_token() }}',
						user_type: user_type,
						people_id: people_id,
						invoice_date: $('.invoice_modal').find('input[name=payment_date]').val(),
						payment_method_id: $('.invoice_modal').find('select[name=invoice_payment_method]').val(),
						ledger_id: $('.invoice_modal').find('select[name=invoice_payment_ledger]').val(),
						description: $('.invoice_modal').find('input[name=description]').val(),
						type: type,
						reference_id: reference_id,
						order_id: order_id,
						amount: [total_amount],
						grn_no: [grn_no]
					},
					beforeSend:function() {
						$('.loader_wall_onspot').show();
					},
					dataType: "json",
					success:function(data, textStatus, jqXHR) {
						
						location.reload();
						$('.loader_wall_onspot').hide();
						$('.invoice_modal').modal('hide');

					}
				});
			}
		});

	});

</script> 
@stop