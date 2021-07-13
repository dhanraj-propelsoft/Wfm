
<div class="modal fade bs-modal-lg invoice_modal" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-container">
			<div class="modal-header">

				<div class="alert alert-success msg" style="display:none">
	{{ Session::get('flash_message') }}
</div>
<h4 style="text-transform:capitalize;" class="modal-title float-right">Payment </h4></div>
			{!! Form::open(['class' => 'form-horizontal invoicevalidateform']) !!}
				<div class="modal-body">

					<div class="form-body">

						<div style="display: none;" class="row person_row">

							<div class=" form-group col-md-4">
							   	<div class=" col-md-12">
									<label for="payment_method">Job Card</label>
									{{ Form::select('job_card',[], null, ['class' => 'form-control select_item', 'id' => 'job_card']) }}
								</div>
							</div>

							<div class="form-group col-md-4 customer_type"> {{ Form::label('customer', 'Customer Type', array('class' => 'control-label col-md-12 required')) }}
							   	<div class=" col-md-12">
						  			<input id="business_type" type="radio" name="customer" value="business"  />
							  		<label for="business_type"><span></span>Business</label>
							  		<input id="people_type" type="radio" name="customer" value="people"  />
							  		<label for="people_type"><span></span>People</label>
						  		</div>
						  	</div>

						  <div class="form-group col-md-4 search_container people"> {{ Form::label('people', 'People', array('class' => 'control-label col-md-12 required')) }}
						  	  	<div class=" col-md-12">
						  			{{ Form::select('people_id', [], null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
						  			<div class="content"></div>
						  		</div>
						  	</div> 

						  	<div class=" form-group col-md-4 search_container business"> {{ Form::label('business', 'Customer', array('class' => 'control-label col-md-12 required')) }}
						  	  	<div class=" col-md-12">
							  		{{ Form::select('people_id',[], null, ['class' => 'form-control business_id', 'id' => 'business_id', 'disabled']) }}
							  		<div class="content"></div>
						  	    </div>
						  	</div>
						</div>

						<div class="row">
							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_date', 'Payment Date', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{!! Form::text('payment_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) !!}
								</div>
							</div>

							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_method', 'Payment Method', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{{ Form::select('invoice_payment_method',$payment, null, ['class' => 'form-control select_item', 'id' => 'invoice_payment_method']) }}
								</div>
							</div>

							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_ledger', 'Payment By', array('class' => 'control-label col-md-12 required')) }}
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

							<div class="form-group col-md-4 payment_amount">	
								{{ Form::label('payment_amount', 'Total Amount', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{!! Form::text('payment_amount', null, ['class'=>'form-control price','disabled']) !!}
								</div>
							</div>

							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_amount', 'Payment Amount (Min 1 Rs)', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{!! Form::text('invoice_payment_amount', null, ['class'=>'form-control price']) !!}
								</div>
							</div>

							<div class="form-group col-md-4">	
								{{ Form::label('payment_details', 'Payment Details', array('class' => 'control-label col-md-12')) }}
								<div class="col-md-12">
									{!! Form::textarea('description', null, ['class'=>'form-control','rows'=>"4",'cols'=>"50"]) !!}
								</div>
							</div>
							<div class="form-group col-md-6 reduction" style="display:none;">
							 <span style="color:#b73c3c;">Click Yes If You want to Close this Invoice Without Balance..</span>
							Yes<input type="checkbox" name="vehicle" class="float-right" style = "display:block;width: 22px;height: 19px;">
							</div>

							 

						</div>
						
						
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-success">Submit</button>
					 <button type="button" class="btn btn-danger tab_print_btn" value=""> Print </button>	 
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		basic_functions();

		var user_type;
		var people_id;
		var type;
		var reference_id = [];
		var order_id= [];
		var amount= [];
		var balance;
		//var job_card;

		var validator = $('.invoicevalidateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				user_type: {
					required: true
				},
				people_id: {
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
				people_id: {
					required: "People is required"
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

				var checked_value = '';
    
				var total_amount = $('.invoice_modal').find('input[name=invoice_payment_amount]').val();
				
				var grn_no = $('.invoice_modal').find('input[name=grn_no]').val();

				var checkbox = $('.invoice_modal').find('.reduction').find('input[type="checkbox"]');
                    
				if(checkbox.prop("checked") == true){
					checked_value = 'yes';
				}else{
					checked_value = 'no';
				}

				var due_amount = $('.invoice_modal').find('input[name=invoice_due_amount]').val();

				if(typeof(user_type) == 'undefined') {
					user_type = $('.invoice_modal').find('input[name=customer]:checked').val();
				}

				if(typeof(people_id) == 'undefined') {
					people_id = $('.invoice_modal').find('select[name=people_id]').val();
				}

				if(typeof(type) == 'undefined') {
					type = 'wms_receipt';
				}
				$.ajax({
				 	url: "{{ route('cash_transaction.store') }}",
				 	type: 'post',
				 	data: {
						_token: '{{ csrf_token() }}',
						checked_value: checked_value,
						user_type: user_type,
						people_id: people_id,
						invoice_date: $('.invoice_modal').find('input[name=payment_date]').val(),
						payment_method_id: $('.invoice_modal').find('select[name=invoice_payment_method]').val(),
						ledger_id: $('.invoice_modal').find('select[name=invoice_payment_ledger]').val(),
						description: $('.invoice_modal').find('input[name=description], textarea').val(),
						type: type,
						reference_id: reference_id,
						reference_voucher: $('.invoice_modal').find('select[name=job_card]').val(),
						order_id: order_id,
						amount: [total_amount],
						grn_no: [grn_no],
						due_amount: due_amount
					},
					beforeSend:function() {
						$('.loader_wall_onspot').show();
					},
					dataType: "json",
					success:function(data, textStatus, jqXHR) {       
                        if(data.status == 1)
						{
							$('.invoice_modal').find('.tab_print_btn').val(data.acount_entryid);
							$('.loader_wall_onspot').hide();
							$('.msg').text("Receipt number: "+ data.voucher_name.voucher_no +" Created and Saved Successfully");
							//setTimeout(function() { $('.msg').fadeOut(); }, 6000)
		                    $('.msg').show();
		                    $('.invoice_modal').find('.btn-success').hide();
							$('.invoice_modal').modal('hide');

		                   location.reload();

						}
						else
						{
							$('.loader_wall_onspot').hide();
							$('.msg').text(data.message);
			                $('.msg').show();
			                location.reload();
	                         

						}

					}
				});
			}
		});

		$('body').on('click', '.process_invoice', function(e) {
			e.preventDefault();


			console.log("process invoice");
			$('#centralModalSm').modal('hide');

			$('.person_row').hide();
			$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').show();
			$('.invoice_modal').find('.payment_amount').hide();
			$('.invoice_modal').find('.reduction').css('display','block');
			$('.invoice_modal').find('select[name=job_card]').closest('.form-group').hide();
			validator.resetForm();
		    type = $(this).data('type'); 
			
			$('.invoice_modal').find('.modal-title').text(type +":"+ $(this).data('reference_no'));
			$('.invoice_modal').find('input[name=invoice_due_amount]').val($(this).data('balance'));
			$('.invoice_modal').find('input[name=invoice_payment_amount]').val($(this).data('balance'));
            
			user_type = $(this).data('user_type');
			people_id = $(this).data('people_id');
		
			reference_id.push($(this).data('id'));
			console.log($(this).data('id'));
			order_id.push($(this).data('reference_no'));
			console.log($(this).data('reference_no'));

			//console.log(reference_id);
			balance = $(this).data('balance');
                 
			$('.invoice_modal').modal('show');
			   $('.invoice_modal').find('.btn-default').text('Close');
			   $('.invoice_modal').find('.tab_print_btn').hide();
			$('.invoice_modal').find('.btn-success').on('click',function(){
				$('.invoice_modal').find('.tab_print_btn').show();
			});
			         
            $('.invoice_modal').find('.tab_print_btn').on('click',function(){
				 var entry_id=$(this).val();
	             var id = reference_id
	             receipt_transaction(id,entry_id);
	             $('.invoice_modal').find('.btn-success').prop('disabled',true);
			});
		});

		/*$('.btn-default').on('click', function(e) {
		e.preventDefault();
		   location.reload();
		});*/



	});

	</script>

