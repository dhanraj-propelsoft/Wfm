
<div class="content">
  <div class="fill header">
	<h3 class="float-left voucher_id">{{$view_receipt_detail->voucher_name}}#{{$view_receipt_detail->voucher_no}}</h3>
	<div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i></div>
	<div style="float: right;padding-right: 30px;">
	<button type="submit" class="btn btn-success tab_print_btn" id="{{$view_receipt_detail->id}}">Print</button>
	<button  class="btn btn-primary cancel" id="cancel">Back</button>
</div>
  </div>
  <div class="clearfix"></div>
  <div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 500px;">
	<div class="form-group">
	  <div class="row">
		<div class="col-md-2">
		  <label class="required" for="vouchers">Type:</label>
		  {!! Form::text('type',$view_receipt_detail->voucher_name,['class' => 'form-control','disabled']) !!}
		</div>
		<div class="col-md-2">
		  <label class="required" for="date">Date:</label>
		  {!! Form::text('date',$view_receipt_detail->date,['class' => 'form-control','disabled']) !!} </div>

        <div class="col-md-2 reference_container">
		  <label for="reference_id">Reference #:</label>
		  {!! Form::text('reference_id',$view_receipt_detail->order_no,['class' => 'form-control', 'disabled']) !!} 
		</div>
<div class="col-md-4"> </div>
	  </div>
	</div>
		<div class="form-group">
	  <div class="row">
	  	<div class="col-md-2">
		  <div class="payment" style=" width: 100%;" >
			<label for="payment">Payment:</label>
			{!! Form::text('payment',$view_receipt_detail->payment,['class' => 'form-control', 'disabled']) !!} </div>
		</div>
	  	<!-- <div class="col-md-2">
		  <div  class="cheque_book">
			<label for="cheque_no">Cheque Book:</label>	
			{!! Form::text('check_book',$view_receipt_detail->cheque_no,['class' => 'form-control', 'disabled']) !!}  </div>
		</div> -->
	  </div>
	</div>
	
	<div class="form-group account_data">
	  <table class="table crud_table">
		<thead>
		  <tr>
			<th width="25%" class="debit_ledger">Debit Ledger</th>
			<th width="25%" class="credit_ledger">Credit Ledger</th>
			<th width="25%">Description</th>
			<th width="15%">Amount</th>
		  </tr>
		</thead>
		<tbody>
		  <tr>	
			<td class="debit_ledger">{{$view_receipt_detail->debit_ledger}}</td>
			<td class="credit_ledger">{{$view_receipt_detail->credit_ledger}}</td>
			<td>{{$view_receipt_detail->description}}</td>
			<td>{{$view_receipt_detail->amount}}</td>
		  </tr>
		</tbody>
	  </table>
	  <div class="form-group">
		<div class="row">
		  <div class="col-md-11 float-right">
			<h5 class="total" style="float:right; text-align:right; width: 150px;">Rs.{{$view_receipt_detail->amount}}</h5>
			<h5 style="float:right; text-align:right; font-weight:bold;">Total</h5>
		  </div>
		</div>
	  </div>
	</div>	
  </div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$('.tab_print_btn').on('click',function(){
           var id = $(this).attr('id');

            print_receipt_transaction(id);
	});
    
    $('#cancel').on('click', function(e){
    e.preventDefault();
     var url = "{{ url('inventory/report/receipt') }}"
     window.location.replace(url);
});

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
});

</script> 
