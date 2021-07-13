<div class="content">
<!-- <div class="modal-header"> -->
<div class="fill header">
  <h3 class="float-left voucher_name" style="text-transform: capitalize;">{{$type}}</h3>
  <div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i></div>
</div>
<!-- </div> -->
<div class="clearfix"></div>
{!! Form::open(['class' => 'form-horizontal transactionform']) !!}
  {{ csrf_field() }}
<!--   <div class="modal-body"> -->
<div class="form-body" style="padding: 15px 25px; ">
	<div class="form-group">
		<div class="row">
		   <div class="col-md-3 customer_type" style= "@if($customer_type_label == null) display:none @endif"> 
							{{ Form::label('customer', $customer_type_label, array('class' => 'control-label required')) }} <br>
							<div class="custom-panel" >
								<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
								<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
								<input id="people_type" type="radio" name="customer" value="0" />
								<label for="people_type" ><span></span>People</label>
							</div>
						</div>

						<div class="col-md-3 search_container people " style= "@if($customer_label == null) display:none @endif">

							{{ Form::label('people', $customer_label, array('class' => 'control-label required')) }}

							{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id', 'disabled']) }}

							{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}

							{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}

							<div class="content"></div>
						</div>


						<div class="col-md-3 search_container business" style= "@if($customer_label == null) display:none @endif">

							{{ Form::label('business', $customer_label, array('class' => 'control-label required')) }}

							{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}

							{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}

							{{ Form::checkbox('account_person_type_id', $person_type, true, ['id' => 'account_person_type_id']) }}

							<div class="content"></div>
						</div>
	  	</div>
	</div>
  	<div class="form-group">
		<div class="row">

			<div class="col-md-3">
				<label for="date">{{$date}}</label>
				{{ Form::text('invoice_date', ($transaction_type->date_setting == 0) ? date('d-m-Y') : null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
			</div>

			<div class="col-md-3">
				<label for="payment_method">{{$payment_method}}</label>
				{{ Form::select('payment_method_id', $payment, null, ['class' => 'form-control select_item', 'id' => 'payment_method_id']) }}
			</div>


		  	<div class="col-md-3">
				<label for="ledger_id">{{$ledger_label}}</label>
				{{ Form::select('ledger_id', $ledgers, null, ['class' => 'form-control select_item', 'id' => 'ledger_id']) }}
			</div>

		  <div class="col-md-3">
			<label for="ledger_id">Payment Details</label>
			{{ Form::text('description', null, ['class'=>'form-control', 'autocomplete' => 'off']) }}
			</div>

			

		</div>
  	</div>

  	<div class="form-group">
  		<div style="text-align: center; display: none;" class="no_data">There is no invoices for this {{$customer_label}}!</div>
		<table style="border-collapse: collapse; display: none;" class="table table-bordered invoice_table">
		  	<thead>
				<tr>
			  	<th width="2%"></th>
			  	<th width="16%">Invoice No.</th>
			  	<th width="10%">Due Date</th>  
			  	<th width="16%">Total Amount</th>
			  	<th width="16%">Due Amount</th>
			  	<th width="10%">{{$reference_type}} No.</th>
			  	<th width="20%">Amount</th>
				</tr>			
		  	</thead>
			<tbody>
			</tbody>
		</table>
  	</div>

  <!-- <div class="form-group">
  	<div class="row">
  	  <div class="col-md-12">
  		<table class= "total_rows" style="float:right;" cellpadding="5em">
  		 
  		 
  		  <tr>
  			<td><h5 style="float:right; text-align:right; font-weight:bold;">Total</h5></td>
  			<td></td>
  			<td><h5 class= "total"  style="float:right; text-align:right; width: 150px;">0.00</h5>
  				<input type="hidden" name="total">
  			</td>
  		  </tr>
  		</table>
  	  </div>
  	</div>
  </div> -->
  
<!--   </div> -->
  <div class="save_btn_container">
	<button type="reset" class="btn btn-default cancel_transaction clear">Close</button>
	<button style="float:right" type="submit" class="btn btn-success save">Save </button>
	<button type="button" class="btn btn-danger tab_print_btn"> Print </button>
  </div>
  {!! Form::close() !!} 



<script type="text/javascript">

var current_select_item = null;
  
  $(document).ready(function() {

	basic_functions();

		$('body').on('click', '.tab_print_btn', function(e) {
			
			//e.preventDefault();

			/*//$('.person_row').hide();
			$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').show();
			validator.resetForm();
			$('.invoice_modal').find('.modal-title').text($(this).data('reference_no'));
			$('.invoice_modal').find('input[name=invoice_due_amount]').val($(this).data('balance'));
			$('.invoice_modal').find('input[name=invoice_payment_amount]').val($(this).data('balance'));

			user_type = $(this).data('user_type');
			people_id = $(this).data('people_id');
			type = $(this).data('type');
			reference_id.push($(this).data('id'));
			order_id.push($(this).data('reference_no'));

			//console.log(reference_id);
			balance = $(this).data('balance');
			
			//var id = reference_id.push($(this).data('id'));

			console.log(reference_id);
			
			print_transaction(reference_id);*/

		});




	$('.cancel_transaction').on('click', function(e) {
		e.preventDefault();
		$('.close_full_modal').trigger('click');
		
	});


	$('.business').hide();

	$('input[name=customer]').on('change', function() {

		if($(this).val() == "people") {
			$('.people').show();
			$('.business').hide();
			$('.people').find('select').prop('disabled', false);
			$('.business').find('select').prop('disabled', true);
			$('.business').find('select').val('');
		} else if($(this).val() == "business")  {
			$('.business').show();
			$('.people').hide();
			$('.business').find('select').prop('disabled', false);
			$('.people').find('select').prop('disabled', true);
			$('.people').find('select').val('');
		}
		
	});

  });

 



	$('.transactionform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				people_id: {
					required: true
				},
				invoice_date: {
					required: true
				},
				ledger_id: {
					required: true
				}
			},

			messages: {
				people_id: {
					required: "Customer is required"
				},
				invoice_date: {
					required: "Invoice date is required"
				},
				ledger_id: {
					required: "Sales account is required"
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
				$.ajax({
				 url: "{{ route('cash_transaction.store') }}",
				 type: 'post',
				 data: {
						_token: '{{ csrf_token() }}',
						by_people: 1,
						user_type: $('input[name=customer]:checked').val(),
						people_id: $('select[name=people_id]').val(),
						invoice_date: $('input[name=invoice_date]').val(),
						payment_method_id: $('select[name=payment_method_id]').val(),
						description: $('input[name=description]').val(),
						ledger_id: $('select[name=ledger_id]').val(),
						type: '{{ $transaction_type->name }}',
						reference_id: $('input[name=check]:checked').map(function() {
							return this.value; 
						}).get(),
						order_id: $('input[name=check]:checked').closest('tr').find('input[name=order_id]').map(function() {
							return this.value; 
						}).get(),
						grn_no: $('input[name=check]:checked').closest('tr').find('input[name=grn_no]').map(function() {
							return this.value; 
						}).get(),
						amount: $('input[name=check]:checked').closest('tr').find('input[name=amount]').map(function() { 
							if(typeof(this.value) == 'undefined') {
								return '0.00';
							} else {
								return this.value;
							}
							
						}).get()
					},
					beforeSend:function() {
						$('.loader_wall_onspot').show();
					},
				 	dataType: "json",
					success:function(data, textStatus, jqXHR) {

						location.reload();
						
						/*var html = "";
							html +=`<tr>
								<td> `+data.data.customer+` </td> <td> `+data.data.total+` </td><td> <a href="javascript:;" data-user_type="`+data.data.user_type+`" data-id="`+data.data.people_id+`" data-people_id="`+data.data.people_id+`" class="grid_label badge badge-success process_people edit">Process Payment</a> </td></tr>`;	

						call_back(html, `edit`, data.message, data.data.people_id);*/

						$('.close_full_modal').trigger('click');
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
			}
	});


</script> 
