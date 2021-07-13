
<div class="modal fade bs-modal-lg invoice_modal" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-container">
                <div class="modal-header">
                    <div class="alert alert-success msg" style="display:none">
                        {{ Session::get('flash_message') }}
                    </div>
                    <h4 style="text-transform:capitalize;" class="modal-title float-right">Job Card - Advance Payment </h4>
                </div>
			    {!! Form::open(['class' => 'form-horizontal invoicevalidateform']) !!}
				<div class="modal-body">
					<div class="form-body">
						<div class="row person_row">
							<div class=" form-group col-md-4">
							   	<div class=" col-md-12">
									<label for="payment_method">Job Card #</label>
									<!-- {{ Form::select('job_card',[], null, ['class' => 'form-control select_item', 'id' => 'job_card']) }} -->
									{{ Form::text('job_card', null, ['class'=>'form-control','data-id' => '','disabled']) }}
								</div>
							</div>

							<div class="form-group col-md-4 customer_type" style="display:none;"> 
                                {{ Form::label('customer', 'Customer Type', array('class' => 'control-label col-md-12 required')) }}
							   	<div class=" col-md-12">
						  			<input id="business_type" type="radio" name="customer" value="business"  />
							  		<label for="business_type"><span></span>Business</label>
							  		<input id="people_type" type="radio" name="customer" value="people"  />
							  		<label for="people_type"><span></span>People</label>
						  		</div>
						  	</div>

						    <div class="form-group col-md-4 search_container people"> 
                                {{ Form::label('people', 'Customer', array('class' => 'control-label col-md-12 required')) }}
						  	  	<div class=" col-md-12">
						  			<!-- {{ Form::select('people_id', [], null, ['class' => 'form-control person_id', 'id' => 'person_id']) }} -->
									  <div class="content"></div>
							  		  {{ Form::text('people_id', null, ['class'=>'form-control','data-id' => '','disabled']) }}
									  
						  		</div>
						  	</div> 

						  	<div class=" form-group col-md-4 search_container business"> 
                                {{ Form::label('business', 'Customer', array('class' => 'control-label col-md-12 required')) }}
						  	  	<div class=" col-md-12">
							  		<!-- {{ Form::select('people_id',[], null, ['class' => 'form-control business_id', 'id' => 'business_id', 'disabled']) }} -->
									  <div class="content"></div>
							  		{{ Form::text('people_id', null, ['class'=>'form-control','data-id' => '','disabled']) }}
									  
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
									{{ Form::select('invoice_payment_method',[], null, ['class' => 'form-control select_item', 'id' => 'invoice_payment_method']) }}
								</div>
							</div>

						</div>

						<div class="row">
							<div class="form-group col-md-4">
								{{ Form::label('invoice_payment_ledger', 'Payment By', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{{ Form::select('invoice_payment_ledger', [], null, ['class' => 'form-control select_item', 'id' => 'invoice_payment_ledger']) }}
								</div>
							</div>

							<!-- <div class="form-group col-md-4 payment_amount">
								{{ Form::label('payment_amount', 'Total Amount', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12"> -->
									{!! Form::hidden('payment_amount', null, ['class'=>'form-control price','disabled']) !!}
 								<!--</div>
 							</div>-->

							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_amount', 'Payment Amount (Min Rs.1)', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{!! Form::text('invoice_payment_amount', null, ['class'=>'form-control price']) !!}
								</div>
							</div>

							<!--<div class="form-group col-md-4">
								{{ Form::label('payment_details', 'Payment Details', array('class' => 'control-label col-md-12')) }}
								<div class="col-md-12">
									{!! Form::textarea('description', null, ['class'=>'form-control','rows'=>"2",'cols'=>"50"]) !!}
								</div>
							</div>-->
						</div>

						<div class="row">
							<div class="form-group col-md-8">
								{{ Form::label('payment_details', 'Payment Details', array('class' => 'control-label col-md-12')) }}
								<div class="col-md-12">
									{!! Form::textarea('description', null, ['class'=>'form-control','rows'=>"2",'cols'=>"50"]) !!}
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success">Save</button>
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

		var amount= [];
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
			
				$.ajax({
				 	url: "{{ route('cash_transaction.store') }}",
				 	type: 'post',
				 	data: {
						_token: '{{ csrf_token() }}',
						checked_value: 'no',
						user_type: $('.invoice_modal').find('input[name=customer]:checked').val(),
						people_id: $('.invoice_modal').find('input[name=people_id]').data('id'),
						invoice_date: $('.invoice_modal').find('input[name=payment_date]').val(),
						payment_method_id: $('.invoice_modal').find('select[name=invoice_payment_method]').val(),
						ledger_id: $('.invoice_modal').find('select[name=invoice_payment_ledger]').val(),
						description: $('.invoice_modal').find('input[name=description], textarea').val(),
						type: 'wms_receipt',
						reference_voucher: $('.invoice_modal').find('input[name=job_card]').data('id'),
						amount: [total_amount],
					
					},
					beforeSend:function() {
						$('.loader_wall_onspot').show();
					},
					dataType: "json",
                    success:function(data, textStatus, jqXHR) 
                    {       
                       
                        $('.loader_wall_onspot').hide();
                        $('.msg').text(data.message);
                        $('.msg').show();
                        location.reload();
	                 
					}
				});
			}
		});

	});

</script>

