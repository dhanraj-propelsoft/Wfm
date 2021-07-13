@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
	<style>
    .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
        .btn
        {
            line-height: 1;
        }
    </style>
@stop

@if($transaction_type->type == 0) 
@include('includes.inventory')
@elseif($transaction_type->type == 1) 
@include('includes.trade')
@elseif($transaction_type->type == 2) 
@include('includes.trade_wms')
@endif
@section('content')
@include('includes.add_user')
@include('includes.add_business')



<!-- Modal Starts -->
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
									{{ Form::select('job_card', $job_card, null, ['class' => 'form-control select_item', 'id' => 'job_card']) }}
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

						  <div class="form-group col-md-4 search_container people"> {{ Form::label('people', $customer_label, array('class' => 'control-label col-md-12 required')) }}
						  	  	<div class=" col-md-12">
						  			{{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
						  			<div class="content"></div>
						  		</div>
						  	</div> 

						  	<div class=" form-group col-md-4 search_container business"> {{ Form::label('business', 'Customer', array('class' => 'control-label col-md-12 required')) }}
						  	  	<div class=" col-md-12">
							  		{{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id', 'disabled']) }}
							  		<div class="content"></div>
						  	    </div>
						  	</div>
						</div>

						<div class="row">
							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_date', 'Payment Date', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{!! Form::text('payment_date', ($transaction_type->date_setting == 0) ? date('d-m-Y') : null, ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) !!}
								</div>
							</div>

							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_method', 'Payment Method', array('class' => 'control-label col-md-12 required')) }}
								<div class="col-md-12">
									{{ Form::select('invoice_payment_method', $payment, null, ['class' => 'form-control select_item', 'id' => 'invoice_payment_method']) }}
								</div>
							</div>

							<div class="form-group col-md-4">	
								{{ Form::label('invoice_payment_ledger', 'Payment to Account', array('class' => 'control-label col-md-12 required')) }}
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
						
						<!-- <div class="row">
							<div class="form-group col-md-4">	
								<div class="col-md-12">
									{{ Form::checkbox('grn_info', '1', null, array('id' => 'grn_info')) }} 
									<label for="grn_info"><span></span>Need {{$reference_type}} Info</label>
								</div>
							</div>
							<div style="display: none;" class="form-group col-md-4 grn">	
								{{ Form::label('grn_no', $reference_type.' No.', array('class' => 'control-label col-md-12 required')) }}
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
					 <button type="button" class="btn btn-danger tab_print_btn" value=""> Print </button>	 
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
<!-- Modal Ends -->


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

<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
	<h5 class="float-left page-title" style="text-transform: capitalize;text-transform: capitalize;padding-top: 8px;padding-left: 10px;"><b>{{$title1}}</b></h5>
		<button type="button" class="btn btn-success float-right export_excel">Export CSV</button> 
	@if($transaction_type->type == 2) 
	<!--<div style="margin-right: 25px;padding-top: 5px;">-->

		<!-- <a class="btn btn-danger add" style="color: #fff;margin-left: 750px;">+ New</a> -->
	<!--</div>-->
	@endif
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
	<div style="width: 150px;" class=" float-left ">
		<select class=" float-left select_item payable_by ">
			<option value="">Select Payable By</option>
			<option value="people">{{$user}}</option>
			<option value="invoice">{{$account_type}}</option>
		</select>
	</div>
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0"><thead></thead><tbody></tbody></table>
		<table id="excel_table" width="100%" cellspacing="0" style="display:none;"><thead></thead><tbody></tbody></table>
</div>

@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script type="text/javascript">
	var datatable = null;

	var datatable_options = {"stateSave": true};

	var transaction_id = null;

	function call_back(data, modal, message, id = null) {
		location.reload();
	}

	people_change();
	//people_change1();

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

		load_data('');

		$('.people').hide();

	// statr To get customer name baseed on choosing job card
		$('select[name=job_card]').on('change',function(){
			//alert();
			var job_card_value = $('select[name=job_card]').val();
			//alert(job_card_value);
			$.ajax({
				url: '{{ route('get_job_card_customer_name') }}',
				type: 'get',
				data: 
					{
						
						id: job_card_value
					},
				success:function(data)
				{
					
					if(data.name.user_type == 0)
					{

						$('.people').show();
						$('.business').hide();
						$('.people').find('select').prop('disabled', false);
						$('.business').find('select').prop('disabled', true);
						$('#people_type').prop('checked',true);
						$('input[name=payment_amount]').val(data.name.total);
						$('input[name=invoice_payment_amount]').val(data.name.total);
						$('select[name=people_id]').val(data.name.person_id);
						//$('.business_id').val("");

						


					}
					else if(data.name.user_type == 1)
					{
						$('.business').show();
						$('.people').hide();
						$('.business').find('select').prop('disabled', false);
						$('.people').find('select').prop('disabled', true);
						$('#business_type').prop('checked',true);
						$('input[name=payment_amount]').val(data.name.total);
						$('input[name=invoice_payment_amount]').val(data.name.total);
						$('select[name=people_id]').val(data.name.business_id);
						//$('.person_id').val("");


					}
				},
				error:function()
				{

				}

			});


		});
	//end

		$('input[name=customer]').on('change', function(){
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

		$('.payable_by').on('change', function() {
			load_data($(this).val());
		});

       
		
         
		function receipt_transaction(id,entry_id) {
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('receipt_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id,
						entry_id: entry_id,	
					},
					success:function(data, textStatus, jqXHR) {
                                  //console.log(data.items);
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='receipt_no']").text(data.receipt_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='wording_amount']").text(data.wording_amount);
							container.find("[data-value='received_from']").text(data.received_from);
							container.find("[data-value='mode']").text(data.mode);
							container.find("[data-value='on_date']").text(data.on_date);
							container.find("[data-value='amount']").text(data.amount);
                            container.find("[data-value='jc_no']").text(data.jc_no);

							container.find("[data-value='company_name']").text(data.company_name);
							container.find("[data-value='company_address']").text(data.company_address);
							container.find("[data-value='city']").text(data.city);
							container.find("[data-value='pin']").text(data.pin);
							container.find("[data-value='mobile_no']").text(data.company_phone);
							container.find("[data-value='company_email_id']").text(data.company_email_id);
							container.find("[data-value='customer_name']").text(data.customer_name);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='customer_mobile_no']").text(data.customer_mobile_no);
							container.find("[data-value='customer_email']").text(data.customer_email);
                             


							var divToPrint=document.getElementById('print');
	  						var newWin=window.open('','Propel');


	  						newWin.document.open();
	  						newWin.document.write(`<html>
	  							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	  							<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
	  							<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
								<script> 

								window.onload=function() { window.print(); }

								$(document).ready(function() {
			


									$('body').on('click', '.print', function() {
									//printDiv();
									});



							}); </scr`+`ipt>


							 </body></html>`);

	  						
	  						newWin.document.close();

	  						$('.print_content #print').removeAttr('style');
							$('.print_content #print').html("");
							$('.print_content').removeAttr('style');
							$('.print_content .modal-footer').hide();
							$('.print_content').animate({top: '0px'}); 
							$('body').css('overflow', '');

						}

						$('.loader_wall_onspot').hide();

					}
				});
		
			});
				
		}
      

      	function print_receipt_transaction(id) {
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('print_receipt_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id
					},
					success:function(data, textStatus, jqXHR) {
                                 // console.log(data.jc_no);
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='receipt_no']").text(data.receipt_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='wording_amount']").text(data.wording_amount);
							container.find("[data-value='received_from']").text(data.received_from);
							container.find("[data-value='mode']").text(data.mode);
							container.find("[data-value='on_date']").text(data.on_date);
							container.find("[data-value='amount']").text(data.amount);
                            container.find("[data-value='jc_no']").text(data.jc_no);

							container.find("[data-value='company_name']").text(data.company_name);
							container.find("[data-value='company_address']").text(data.company_address);
							container.find("[data-value='city']").text(data.city);
							container.find("[data-value='pin']").text(data.pin);
							container.find("[data-value='mobile_no']").text(data.company_phone);
							container.find("[data-value='company_email_id']").text(data.company_email_id);
							container.find("[data-value='customer_name']").text(data.customer_name);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='customer_mobile_no']").text(data.customer_mobile_no);
							container.find("[data-value='customer_email']").text(data.customer_email);
                             


							var divToPrint=document.getElementById('print');
	  						var newWin=window.open('','Propel');


	  						newWin.document.open();
	  						newWin.document.write(`<html>
	  							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	  							<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
	  							<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
								<script> 

								window.onload=function() { window.print(); }

								$(document).ready(function() {
			


									$('body').on('click', '.print', function() {
									//printDiv();
									});



							}); </scr`+`ipt>


							 </body></html>`);

	  						
	  						newWin.document.close();

	  						$('.print_content #print').removeAttr('style');
							$('.print_content #print').html("");
							$('.print_content').removeAttr('style');
							$('.print_content .modal-footer').hide();
							$('.print_content').animate({top: '0px'}); 
							$('body').css('overflow', '');

						}

						$('.loader_wall_onspot').hide();

					}
				});
		
			});
					
		}
		/*$('body').on('click', '.tab_print_btn', function(e) {

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
                    var id=reference_id;
                        	
                 //  receipt_transaction(id);

		});

		*/


		$('input[name=invoice_payment_amount]').keyup(function() {
			var payment = parseFloat($(this).val());
			var due_amount = parseFloat($('input[name=invoice_due_amount]').val());
			if( payment > due_amount ) {
				$(this).val(due_amount);
			}
		});

		$('body').on('click', '.process_invoice', function(e) {
			e.preventDefault();
			$('.person_row').hide();
			$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').show();
			$('.invoice_modal').find('.payment_amount').hide();
			$('.invoice_modal').find('.reduction').css('display','block');
			$('.invoice_modal').find('select[name=job_card]').closest('.form-group').hide();
			validator.resetForm();
			$('.invoice_modal').find('.modal-title').text("{{$type}}:"+ $(this).data('reference_no'));
			$('.invoice_modal').find('input[name=invoice_due_amount]').val($(this).data('balance'));
			$('.invoice_modal').find('input[name=invoice_payment_amount]').val($(this).data('balance'));
            
			user_type = $(this).data('user_type');
			people_id = $(this).data('people_id');
			type = $(this).data('type');
			reference_id.push($(this).data('id'));
			order_id.push($(this).data('reference_no'));

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

		$('body').on('click', '.add', function(e) {
			e.preventDefault();
			$('.person_row').show();
			$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').hide();
			$('.invoice_modal').find('select[name=job_card]').closest('.form-group').show();
			$('.invoice_modal').find('.modal-title').text('{{$type}}: (This is for Advance)');
			$('.invoice_modal').find('.payment_amount').show();
			$('.invoice_modal').find('.reduction').css('display','none');
			$('.invoice_modal').find('.tab_print_btn').hide();
			$('.invoice_modal').find('.btn-success').text('Save');
			$('.invoice_modal').find('.btn-default').text('Close');
			$('.invoice_modal').find('.btn-success').on('click',function(){
				$('.invoice_modal').find('.tab_print_btn').show();
			});
			$('.invoice_modal').find('.tab_print_btn').on('click',function(){
                   var id=$(this).val();
                           
 
                    print_receipt_transaction(id);

                    
			});
			

		     $('.invoice_modal').modal('show');

		});
		
		/*$('input[name="grn_info"]').on('change', function() {
			if($(this).is(":checked")) {
				$('.invoice_modal').find(".grn").show();
			} 
			else {
				$('.invoice_modal').find(".grn").hide();
				$('.invoice_modal').find('input[name=grn_no]').val('');
			}
		});*/

		$('body').on('click', '.process_people', function(e) {
			e.preventDefault(); 
			var that = $(this);
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

				$.get("{{ route('cash_transaction.create', $type) }}", function(data) {
				  	$('.full_modal_content').show();
				  	$('.full_modal_content').html("");
				  	$('.full_modal_content').html(data);
				  	$('.full_modal_content').find('.customer_type').closest('.form-group').hide();

					if(that.data('user_type') == 0) {
						$('.full_modal_content').find('#business_type').prop('checked', 'false');
						$('.full_modal_content').find('#people_type').prop('checked', 'true').trigger('change');
						$('.full_modal_content').find('select.person_id').val(that.data('people_id'));
						$('.full_modal_content').find('select.business_id').val('');
						$('.full_modal_content').find('select.person_id').trigger('change');
					} else if(that.data('user_type') == 1) {
						$('.full_modal_content').find('#people_type').prop('checked', 'false');
						$('.full_modal_content').find('#business_type').prop('checked', 'true').trigger('change');
						$('.full_modal_content').find('select.person_id').val('');
						$('.full_modal_content').find('select.business_id').val(that.data('people_id'));
						$('.full_modal_content').find('select.business_id').trigger('change');
					}

					$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					$('.loader_wall_onspot').hide();
				});
			});				
		});

	
		$('body').on('click', '.delete', function(){
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = '{{ route('transaction.destroy') }}';
			delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	    });

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
					required: "{{$user}} is required"
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

				var due_amount = $('.invoice_modal').find('input[name=invoice_due_amount]').val();

				if(typeof(user_type) == 'undefined') {
					user_type = $('.invoice_modal').find('input[name=customer]:checked').val();
				}

				if(typeof(people_id) == 'undefined') {
					people_id = $('.invoice_modal').find('select[name=people_id]').val();
				}

				var checkbox = $('.invoice_modal').find('.reduction').find('input[type="checkbox"]');
                    
				if(checkbox.prop("checked") == true){
					checked_value = 'yes';
				}else{
					checked_value = 'no';
				}

				

				if(typeof(type) == 'undefined') {
					type = '{{$type}}';
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
						description: $('.invoice_modal').find('input[name=description]').val(),
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
                                 
                          
                         /* $('.invoice_modal').find('.btn-success').attr('disabled','disabled');

                               	$('.invoice_modal').find('.btn-success').on('click',function(){
				
                 $('.invoice_modal').find('.tab_print_btn').removeAttr('disabled');
                    
			});
                           */ 
					/*	datatable.destroy();

						var current_element = $('body').find('.process_invoice[data-id="'+ reference_id[0] +'"]');
						if(current_element.length > 0) {
							var balance_amount = parseFloat(balance) - parseFloat(total_amount);

							if(balance_amount == 0) {
								console.log(current_element.closest('tr').remove());
								current_element.closest('tr').remove();
							} else {
								current_element.closest('tr').find('.total').text(balance_amount.toFixed(2));
							}
						}
						
						location.reload();*/
						
						//$('.invoice_modal').modal('hide');

						/*var html = "";
							html +=`<tr>
								<td>`+data.data.order_no+`</td>		      	
								<td>`+data.data.date+`</td>
								<td>`+data.data.people+`</td>	
								<td>`+data.data.due_date+`</td>
								<td>`+data.data.total+`</td>
								<td class="status">  </td>		      	
								<td>
								<!-- <a data-id="`+data.data.id+`" class="grid_label action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp; -->
								<a data-id="`+data.data.id+`" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a></td>
							</tr>`;	

							$('.data_table tbody').prepend(html);
							datatable = $('#datatable').DataTable({"stateSave": true});
							$('.crud_modal').modal('hide');

							$('.alert-success').text(data.message);
							$('.alert-success').show();

							setTimeout(function() { $('.alert').fadeOut(); }, 3000);*/
						

					}
				});
			}
		});

	});

		

		function people_change() {

		  	$('body').on('change', 'select.person_id, select.business_id', function() {
				
				$('.invoice_table').hide();
				$('.no_data').hide();
				var people_id = $(this).val();
				var user_type;
				if(people_id != "" && people_id != "0") {

					if($(this).hasClass('person_id')) {
							user_type = '0';
						} 
					else if($(this).hasClass('business_id')) {
							user_type = '1';
						}

						$.ajax({
							url: "{{ route('get_invoice') }}",
							type: 'post',
							data: {
								_token: '{{ csrf_token() }}',
								people_id: people_id,
								user_type: user_type,
								type: '{{ $transaction_type->name }}',
								@if($type == "payment")
									transaction_type: 'purchases'
								@elseif($type == "receipt")
									transaction_type: 'sales'
								@elseif($type == "wms_receipt")
									transaction_type: 'job_invoice'
								@endif
							 },
							 dataType: "json",
							 success:function(data, textStatus, jqXHR) {

								var html = "";
								if(data.length > 0) {
									for(var i in data) {

										html += `<tr>
											<td>
												<input id="`+data[i].id+`" value="`+data[i].id+`" name="check" value="1" type="checkbox" checked="checked" >
												<label for="`+data[i].id+`"><span></span></label>
											</td>
											<td width="10%">`+data[i].order_no+`<input type="hidden" name="order_id" value="`+data[i].order_no+`"></td>
											  <td width="25%">`+data[i].due_date+`</td>  
											  <td width="12%">`+data[i].total+`</td>
											  <td width="14%">`+data[i].balance+`</td>
											  <td width="15%"><input type="text" name="grn_no" class="form-control" value="" /></td>
											  <td width="15%"><input type="text" name="amount" class="form-control price" value="`+data[i].balance+`" /></td>	  
											</tr>`

									}

									$('.invoice_table').find('tbody').html(html);
							 		$('.invoice_table').show();
								} else {
							 		$('.no_data').show();
								}

							 },
							 error:function(jqXHR, textStatus, errorThrown) {}
						});

					}

			});
	    }

	    /*function people_change1() {

		  	$('body').on('change', 'select.person_id, select.business_id', function() {
				
				$('.invoice_table').hide();
				$('.no_data').hide();
				var people_id = $(this).val();
				var user_type;
				if(people_id != "" && people_id != "0") {

					if($(this).hasClass('person_id')) {
							user_type = '0';
						} 
					else if($(this).hasClass('business_id')) {
							user_type = '1';
						}

						$.ajax({
							url: "{{ route('get_wms_invoice') }}",
							type: 'post',
							data: {
								_token: '{{ csrf_token() }}',
								people_id: people_id,
								user_type: user_type,
								type: '{{ $transaction_type->name }}',
								@if($type == "payment")
									transaction_type: 'purchases'
								@elseif($type == "wms_receipt")
									transaction_type: 'job_invoice'
								@endif
							 },
							 dataType: "json",
							 success:function(data, textStatus, jqXHR) {

								var html = "";
								if(data.length > 0) {
									for(var i in data) {

										html += `<tr>
											<td>
												<input id="`+data[i].id+`" value="`+data[i].id+`" name="check" value="1" type="checkbox" checked="checked" >
												<label for="`+data[i].id+`"><span></span></label>
											</td>
											<td width="10%">`+data[i].order_no+`<input type="hidden" name="order_id" value="`+data[i].order_no+`"></td>
											  <td width="25%">`+data[i].due_date+`</td>  
											  <td width="12%">`+data[i].total+`</td>
											  <td width="14%">`+data[i].balance+`</td>
											  <td width="15%"><input type="text" name="grn_no" class="form-control" value="" /></td>
											  <td width="15%"><input type="text" name="amount" class="form-control price" value="`+data[i].balance+`" /></td>	  
											</tr>`

									}

									$('.invoice_table').find('tbody').html(html);
							 		$('.invoice_table').show();
								} else {
							 		$('.no_data').show();
								}

							 },
							 error:function(jqXHR, textStatus, errorThrown) {}
						});

					}

			});
	    }*/

		function load_data(type) {

			//var voucher_name = 'job_invoice';


			$.ajax({
				url: '{{route('payment')}}',
				type: 'post',
				data: {
					_token : '{{ csrf_token() }}',
					type: type,
					transaction_type: '{{$type}}',

				},
			 	dataType: "json",
				success:function(data, textStatus, jqXHR) {
					//console.log(data);
					if(datatable != null) {
						datatable.destroy();
					}					

					var result = data.data;

					$('#datatable').find('thead, tbody').empty();

					if(type == "people") {
						var thead = `<tr><th> {{$user}} </th><th> Amount </th><th> Action </th></tr>`;

						var tbody = ``;

						for (var i = 0; i < result.length; i++) {
							tbody += `<tr><td> `+result[i].customer+` </td> <td> `+result[i].total+` </td><td> <a href="javascript:;" data-id="`+result[i].people_id+`"  data-user_type="`+result[i].user_type+`" 
							data-people_id="`+result[i].people_id+`" class="grid_label badge badge-success process_people edit">Process Payment</a> </td></tr>`;
							
						}

					} else {
					var thead = `<tr><th> Reference No. </th><th> Payables by </th><th> Created On </th><th> {{$user}} </th><th> Due Date </th><th>Total Amount </th><th> Amount </th> <th> Payable Status </th><th> Action </th></tr>`;

						var tbody = ``;

						for (var i = 0; i < result.length; i++) {

							var status = ``;
								var total_amount=parseInt(result[i].total);
							if(result[i].advance_amount != null){
								total_amount=parseInt(result[i].total)+parseInt(result[i].advance_amount);
							}

							if(result[i].status == 0) {
								status = `<label class="grid_label badge badge-warning">Pending</label>`;
							} else if(result[i].status == 1) {
								status = `<label class="grid_label badge badge-success">Paid</label>`;
							} else if(result[i].status == 2) {
								status = `<label class="grid_label badge badge-info">Partially Paid</label>`;
							} else if(result[i].status == 3) {
								status = `<label class="grid_label badge badge-danger">Over due ` + result[i].overdue + ` days</label> `;
							}


							tbody += `<tr><td><a style="color:#3366ff;" class="reference" data-id="`+result[i].id+`"> `+result[i].order_no+`</a></td><td> {{$transaction_id->display_name}} </td><td> `+result[i].created_on+` </td><td> `+result[i].customer+` </td><td> `+result[i].due_date+` </td><td>`+total_amount+`</td><td class="total"> `+result[i].balance+` </td> <td class="status"> `+status+` </td><td>`;

							if(result[i].status != 1) {
								tbody += ` 
								<a href="javascript:;" 
								data-id="`+result[i].id+`" 
								data-user_type="`+result[i].user_type+`" 
								data-people_id="`+result[i].people_id+`" 
								data-type="{{$type}}" 
								data-reference_no="`+result[i].order_no+`" 
								data-total="`+result[i].total+`" 
								data-balance="`+result[i].balance+`"
								class="grid_label badge badge-success process_invoice">Process Payment</a>`;
							}

							tbody += `</td></tr>`;
							
						}

					}					

					$('#datatable').find('thead').html(thead);
					$('#datatable').find('tbody').html(tbody);
					 $('#excel_table').find('thead').html(thead);
				     $('#excel_table').find('tbody').html(tbody);

					datatable = $('#datatable').DataTable(datatable_options);
                                
					$('#datatable tbody').on('click', '.reference', function () {
		
						isFirstIteration = true;
						var id = $(this).data('id');
        				var vehicle_id = $(this).data('vehicle_id');       

					if(id != "" && typeof(id) != "undefined") {

							$('.loader_wall_onspot').show();
						$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

							$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
					  		$('.full_modal_content').show();
					  		$('.full_modal_content').html("");
					  		$('.full_modal_content').html(data);
					  		$('.full_modal_content').find('.transactionform').find('.tab_delete_btn').hide();
					  		$('.full_modal_content').find('.transactionform').find('.tab_send_btn').hide();
					   		$('.full_modal_content').find('.transactionform').find('.tab_approve_btn').hide();
					    	$('.full_modal_content').find('.transactionform').find('.dropdown-toggle').hide();
					  		$('.full_modal_content').find('.transactionform').find('.tab_sms_btn ').hide();
					 		$('.full_modal_content').find('.transactionform').find('.form-body').find('.tab-content :input').prop( "disabled", true );
					  		$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  		$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					  		$('.loader_wall_onspot').hide();
					  
							});
		
						});
				}

		});  

				},
			 	error:function(jqXHR, textStatus, errorThrown) {
				}
			});
		}

$(".export_excel").click(function() {
   
 TableToExcel.convert(document.getElementById("excel_table"));
});
	</script>
@stop