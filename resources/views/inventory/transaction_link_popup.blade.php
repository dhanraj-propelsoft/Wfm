<style>
	.label{
		font-weight: bold;
	}
	#font_size{
		font-size: 12px;
	} 
	#bg{
		background-color: #dce1e6;
	}
} 
</style>

<div class="modal-header">
	<h4 style="background-color: #dce1e6;margin-left: 240px;">
		@if(!empty($transactions))
	      {{ $transaction_type->display_name }}# {{ $transactions->order_no }}
	    @else
	      {{$transaction_type->display_name}}# {{$voucher_no}}
	      @endif</h4>
	    <a  class="close" data-dismiss="modal">&times;</a>
</div>

<div class="modal-body">
	<div class="form-body">
 		<div class="container">
  			<div class="row">
    			<div class="col-sm">
     				<label for="address" class="label">Address</label>
					{{ Form::text('customer_name',$transactions->name, ['class'=>'form-control',
					'id' => 'font_size','disabled' =>'disabled','autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Name']) }} 
				    {{ Form::text('customer_mobile', $transactions->mobile, ['class'=>'form-control','id' => 'font_size','disabled' =>'disabled', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Mobile']) }} 
				    {{ Form::text('customer_email', $transactions->email, ['class'=>'form-control ','id' => 'font_size','disabled' =>'disabled', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Email' ]) }}
				    {{ Form::text('customer_gst', $transactions->gst, ['class'=>'form-control','id' => 'font_size','disabled' =>'disabled','autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'GST' ]) }}
				    {{ Form::textarea('customer_address', $address, ['class'=>'form-control','id' => 'font_size','disabled' =>'disabled','style'=>' height: 40px;','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Address']) }}
				    <div style="margin-top: 02px;">
				    <label for="sub_total" class="control-label label">Total</label>
					{{ Form::text('wms_total',null, ['class' => 'form-control total', 'id' => 'sub_total font_size','disabled']) }}
					</div>
    			</div>

    		@if($transaction_type->module == "inventory" || $transaction_type->module == "trade")
    			<div class="col-sm">
    				<label for="po_date" class="label">Date</label>
    				{{ Form::text('po_date', $transactions->date, ['class'=>'form-control date-picker datetype rearrangedate','id' => 'font_size','disabled' =>'disabled', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

    				@if($transaction_type->name == "purchase_order")	
    				<div style="margin-top: 07px;">
					<label for="employee_id" class="label">Created By</label>
					{{ Form::select('employee_id', $employees,$by->id, ['class' => 'form-control select_item dropdown', 'id' => 'font_size']) }}
					</div>
					@endif

					@if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == 'purchases')
					<div style="margin-top: 07px;">
					<label for="payment_terms" class="label" >Payment Terms</label>
					{{ Form::select('payment_terms',$voucher_terms,$transactions->term_id, ['class' => 'form-control select_item dropdown',
					'id' => 'font_size', 'placeholder' => 'Select Payment Terms']) }}
					</div>
					@endif

					<!--below expiry date and due date are equal based on transcation type start -->
					@if($transaction_type->name == "estimation") 
					<div style="margin-top: 07px;">
					<label for="job_due_date" class="label">Expiry Date</label>
					{{ Form::text('job_due_date', $transactions->due_date, ['class'=>'form-control date-picker datetype rearrangedate','id' => 'font_size','disabled' =>'disabled','data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
					</div>
					@endif

					@if($transaction_type->name == "purchases" || $transaction_type->name == "sales" ||  $transaction_type->name == "sales_cash")
					<div style="margin-top: 07px;">
					<label for="job_due_date" class="label">Due Date</label>
					{{ Form::text('job_due_date', $transactions->due_date, ['class'=>'form-control date-picker datetype rearrangedate','id' => 'font_size','disabled' =>'disabled','data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
					</div>
					@endif
					<!-- ** expiry date and due date end **  -->
    			</div>
    		@endif

    		@if($transaction_type->module == "trade_wms")
    			<div class="col-sm">
     				<label for="invoice_date" class="label">Date</label>
					{{ Form::text('invoice_date', $wms_transaction->job_date, ['class'=>'form-control date-picker datetype rearrangedate','id' => 'font_size','disabled' =>'disabled', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}

					<!--below Assigned to and Invoiced By are equal based on transcation type start -->
					@if($transaction_type->name == "job_card" || $transaction_type->name == "job_request")
					<div style="margin-top: 07px;">
					<label for="employee_id" class="label">Assigned to</label>
					{{ Form::select('employee_id', $employees,$by->id, ['class' => 'form-control select_item dropdown', 'id' => 'font_size']) }}
					</div>
					@endif

					@if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" )	
					<div style="margin-top: 07px;">
					<label for="employee_id" class="label">Invoiced By</label>
					{{ Form::select('employee_id', $employees,$by->id, ['class' => 'form-control select_item dropdown', 'id' => 'font_size']) }}
					</div>
					<!-- ** Assigned to and Invoiced By end **  -->	
				
					<div style="margin-top: 07px;">
					<label for="payment_terms" class="label" >Payment Terms</label>
					{{ Form::select('payment_terms', $payment_terms, $wms_transaction->payment_terms, ['class' => 'form-control select_item dropdown',
					'id' => 'font_size', 'placeholder' => 'Select Payment Terms']) }}
					</div>
					@endif

					<!--below Job Due Date and Expiry Date and  and Payment Due Date are equal based on transcation type start -->	
					@if($transaction_type->name == "job_card")	
					<div style="margin-top: 07px;">
					<label for="job_due_date" class="label">Job Due Date</label>
					{{ Form::text('job_due_date', $wms_transaction->job_due_date, ['class'=>'form-control date-picker datetype rearrangedate','id' => 'font_size','disabled' =>'disabled','data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
					</div>
					@endif

					@if($transaction_type->name == "job_request")
					<div style="margin-top: 07px;">
					<label for="job_due_date" class="label">Expiry Date</label>
					{{ Form::text('job_due_date', $wms_transaction->job_due_date, ['class'=>'form-control date-picker datetype rearrangedate','id' => 'font_size','disabled' =>'disabled','data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
					</div>
					@endif

					@if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" )	
					<div style="margin-top: 07px;">
					<label for="job_due_date" class="label">Payment Due Date</label>
					{{ Form::text('job_due_date', $wms_transaction->job_due_date, ['class'=>'form-control date-picker datetype rearrangedate','id' => 'font_size','disabled' =>'disabled','data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
					</div>
					@endif
					<!-- ** Job Due Date and Expiry Date and Payment Due Date end **  -->	
    			</div>
    		@endif

    			<div class="col-sm">
      				<label for="address" class="label"> Bill To Address</label>
					{{ Form::text('customer_name',$transactions->billing_name, ['class'=>'form-control','id' => 'font_size',
					'disabled' =>'disabled', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Name']) }} 
				    {{ Form::text('customer_mobile', $transactions->billing_mobile, ['class'=>'form-control','id' => 'font_size','disabled' =>'disabled', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Mobile']) }} 
				    {{ Form::text('customer_email', $transactions->billing_email, ['class'=>'form-control ','id' => 'font_size','disabled' =>'disabled', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Email' ]) }}
				    {{ Form::text('customer_gst', $transactions->billing_gst, ['class'=>'form-control','id' => 'font_size',
				    'disabled' =>'disabled','autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'GST' ]) }}
				    {{ Form::textarea('customer_address', $billing_address, ['class'=>'form-control','id' => 'font_size','disabled' =>'disabled','style'=>' height: 40px;','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Address']) }}
				    @if($transaction_type->name != "job_card")
				    <label for="approved"  class="label" style=";">Approved/Draft</label>
					{{ Form::text('approved', $approval_status, ['class' => 'form-control','id' => 'font_size', 'disabled' => 'disabled']) }}
					@endif
    			</div>
  			</div>

  			<div style="margin-top: 20px; height:150px; overflow-y: auto;overflow-x: hidden;">
  				<table class="table table-bordered item_table">
						<thead >
						<tr >
							<th width="2%"  id="bg">#</th>
							<th width="25%" id="bg">Job & Parts </th>
							<th width="10%" id="bg">Description</th>
							<th width="6%"  id="bg">Disc%</th>
							<th width="6%"  id="bg">Qty</th>
							<th width="10%" id="bg">Rate</th>
							<th width="10%" id="bg">Tax%</th>							
							<th width="10%" id="bg">TaxAmount</th>						
							<th width="10%" id="bg">Total</th>
							
						</tr>
						</thead>
						<tbody>
							<?php $count = 0; ?>
							@for ($i = 0; $i < count($job_parts); $i++)
								<tr>
									<?php $b_rate=$job_parts[$i]->quantity*$job_parts[$i]->rate;
										$holl_total=$b_rate+$job_parts[$i]->tax_amount;
										$disc_total=$b_rate*$job_parts[$i]->discount;
										$with_disc_total=$b_rate-$disc_total;
									$overall_total=$with_disc_total+$job_parts[$i]->tax_amount;
										?>

									<td><?php echo ++$count; ?></td> 

									<td>{{ Form::text('parts_name',$job_parts[$i]->item_name, ['class' => 'form-control','id' => 'font_size']) }}</td>

									<td>{{ Form::text('description',$job_parts[$i]->description, ['class' => 'form-control','id' => 'font_size','placeholder'=>'Descript']) }}</td>

									<td>{{ Form::text('discount',$job_parts[$i]->discount_percent, ['class' => 'form-control','id' => 'font_size']) }}</td>

									<td>{{ Form::text('qty',$job_parts[$i]->quantity, ['class' => 'form-control','id' => 'font_size']) }}</td>

									<td>{{ Form::text('rate',$b_rate, ['class' => 'form-control','id' => 'font_size']) }}</td>

									<td>{{ Form::text('gst_name',$job_parts[$i]->tax, ['class' => 'form-control','id' => 'font_size']) }} </td>

									<td><input type="text" name="tax_amount" value="<?php echo $job_parts[$i]->tax_amount?>" class="form-control {{'tax_amount_'.$i}}" id="font_size"></td>

									<td>{{ Form::text('total',$overall_total, ['class' => 'form-control','id' => 'font_size']) }}</td>

								</tr>
								 @endfor
						
						</tbody>
					</table>
  			</div>
		</div>
	</div>
</div>
<div class="modal-footer d-flex justify-content-center"> 
	<button type="submit" class="btn btn-success edit-popup" data-id="{{ $transactions->id }}" data-module="{{ $module_name }}">Edit</button>                             
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<!-- <button type="button" name="qjc_print" class="btn btn-primary qjc_print" data-id="{{$id}}" data-formate="{{ $print_data }}">Quick Print</button> -->
	
</div>
<script type="text/javascript">
	
$(document).ready(function() {
	$('.dropdown').prop("disabled", true);

$('.qjc_print').on('click',function()
{
	//alert();
	var transaction_id = $(this).attr('data-id');
	var data = $(this).attr('data-formate');

	$('.delete_modal_ajax').modal('hide'); 
	$('.crud_modal').modal('show');


	//print_transaction(id,data);



	 		$('.loader_wall_onspot').show();
       		// $('#myModal_popup_show1').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('print_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: transaction_id,
						data:data

					},
					success:function(data, textStatus, jqXHR) {
					//console.log(data);
					//console.log(data.transaction_data);

					// I added new popup modal to print so hid this

                         
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();
						//$('.print_content').hide();


						var container = $('.print_content').find("#print");


						//new coding to show new popup 
						/*$('.print_popup_content').show();
						
						$('.print_popup_content').hide();


						var container = $('.print_popup_modal').find("#print_value");*/
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='voucher_type']").text(data.transaction_type);
							container.find("[data-value='po']").text(data.po_no);
							container.find("[data-value='purchase']").text(data.purchase_no);
							container.find("[data-value='grn']").text(data.grn_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='payment_mode']").text(data.payment_mode);
							container.find("[data-value='resource_person']").text(data.resource_person);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='shipping_address']").text(data.shipping_address);
							container.find("[data-value='billing_address']").text(data.billing_address);
	                        container.find("[data-value='customer_vendor']").text(data.customer_vendor);
	                        container.find("[data-value='vehicle_number']").text(data.vehicle_number);
	                        container.find("[data-value='make_model_variant']").text(data.make_model_variant);
	                        container.find("[data-value='company_name']").text(data.company_name);
	                        container.find("[data-value='company_phone']").text(data.company_phone);
	                        container.find("[data-value='company_address']").text(data.company_address);
	                        container.find("[data-value='email_id']").text(data.email_id);
	                        container.find("[data-value='amount']").text(data.amount);
	                        container.find("[data-value='payment_method']").text(data.payment_method);
	                        container.find("[data-value='km']").text(data.km);
	                        container.find("[data-value='assigned_to']").text(data.assigned_to);
	                        container.find("[data-value='company_gst']").text(data.company_gst);
	                        container.find("[data-value='customer_gst']").text(data.customer_gst);

							var row = container.find('.invoice_item_table tbody tr').clone();

							var invoice_items = ``;
	                        var total_amount = 0;
	                        var total_discount = 0;
	                        var  total_length= 10;
	                        var length = total_length - (data.invoice_items).length;
							for (var i = 0; i < (data.invoice_items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();
								var discount = data.invoice_items[i].discount;
	                            var discount_value = $.parseJSON(discount);
	                            var amount= data.invoice_items[i].rate * data.invoice_items[i].quantity;
								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.invoice_items[i].name);
								new_row.find('.col_hsn').text(data.invoice_items[i].hsn);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_discount').text(discount_value.amount);
								new_row.find('.col_rate').text(parseFloat(data.items[i].rate).toFixed(2));
								new_row.find('.col_amount').text(parseFloat(data.items[i].amount).toFixed(2));
								new_row.find('.col_t_amount').text(parseFloat(amount).toFixed(2));
								
	                           var total_amount = parseFloat(amount)+parseFloat(total_amount);

	                           var total_discount = parseFloat(discount_value.amount)+parseFloat(total_discount);

								invoice_items += `<tr>`+new_row.html()+`</tr>`;
							}

							for(var i=1; i <= length;i++){

								var new_row = row.clone();
	                            
								invoice_items += `<tr>`+new_row.html()+`</tr>`;

							}

							container.find("[data-value='total_discount']").text(total_discount);
		                    container.find("[data-value='total_amount']").text(parseFloat(total_amount).toFixed(2));
							container.find('.invoice_item_table tbody').empty();
							container.find('.invoice_item_table tbody').append(invoice_items);

							var hsn_invoice_tax_values = Object.values(data.hsn_based_invoice_tax);
	                       
	                       //HSN based tax table
	                        var hsn_row = container.find('.hsnbasedTable tbody tr').clone();
	                        var hsn_tax = ``;
	                        var  totalhsn_length= 6;
	                        var hsn_length = totalhsn_length - hsn_invoice_tax_values.length;
	                        for(var i = 0; i < hsn_invoice_tax_values.length; i++){
	                        	var hsn_new_row = hsn_row.clone();
	                        	var taxable = parseFloat(hsn_invoice_tax_values[i].taxable).toFixed(2);
	                        	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                        	var gst = hsn_invoice_tax_values[i].name;
	                        	var sgst = hsn_invoice_tax_values[i].display_name;
	                        	if(sgst != null){
	                                var sgst_value = sgst.split('CGST');
	                        	}else{
	                                  sgst_value = '';
	                        	}
	                            
	                        	if(gst == null){
	                            	var exact_tax = '';
	                            }else{
	                            	var exact_tax = sgst_value[0];
	                            }
	                        	if(hsn_invoice_tax_values[i].tax_type == 1){
	                        	hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text("");
	                            hsn_new_row.find('.col_igst_amount').text("");
	                            hsn_new_row.find('.col_cgst').text(exact_tax);
	                            hsn_new_row.find('.col_cgst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_sgst').text(exact_tax);
	                            hsn_new_row.find('.col_sgst_amount').text(tax_amount);
	                            }else{
	                            	var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
	                            	var igst = hsn_invoice_tax_values[i].display_name;
	                            	if(igst != null){
	                            		var exact_igst = igst.split('IGST');
	                            	}else{
	                            		exact_igst = '';
	                            	}
	                            	
	                            	if(gst == null){
	                            		var exact_tax = '';
	                            	}else
	                            	{
	                            		var exact_tax = exact_igst[0];
	                            	}
	                            hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
	                            hsn_new_row.find('.col_tax_value').text(taxable);
	                            hsn_new_row.find('.col_igst').text(exact_tax);
	                            hsn_new_row.find('.col_igst_amount').text(tax_amount);
	                            hsn_new_row.find('.col_cgst').text("");
	                            hsn_new_row.find('.col_cgst_amount').text("");
	                            hsn_new_row.find('.col_sgst').text("");
	                            hsn_new_row.find('.col_sgst_amount').text("");
	                            }
	                        	hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;
	                        }

	                        for(var i=1; i <= hsn_length;i++){

								var hsn_new_row = hsn_row.clone();
	                            
								hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;


							}
	                        container.find('.hsnbasedTable tbody').empty();
							container.find('.hsnbasedTable tbody').append(hsn_tax);
							var invoice_tax_values = Object.values(data.invoice_tax);
							var tax_row = container.find('.floatedTable tbody tr').clone();
	                        var invoice_tax = ``;
	                        var  total_length= 6;
	                       	var gst_length = total_length - invoice_tax_values.length;
	                        var total_cgst = 0;
	                        var total_sgst = 0;
	                        var total_igst = 0;
	                        for (var i = 0; i < invoice_tax_values.length; i++) {
								var new_row = tax_row.clone();  
	                            var gst = invoice_tax_values[i].name;
	                            var sgst = invoice_tax_values[i].display_name;
	                            var tax_amount = parseFloat(invoice_tax_values[i].Tax_amount).toFixed(2);
	                            var taxable = parseFloat(invoice_tax_values[i].taxable).toFixed(2);
	                            if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';

	                            }else{
	                            	var exact_value = gst.split('GST');
	                            	var exact_sgst = sgst.split('SGST');
	                            }
	                        if(invoice_tax_values[i].tax_type == 1){
								new_row.find('.col_gst').html(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text("");
								new_row.find('.col_igst_amount').text("");
								new_row.find('.col_cgst').text(exact_sgst[0]);
								new_row.find('.col_cgst_amount').text(tax_amount);
								new_row.find('.col_sgst').text(exact_sgst[0]);
								new_row.find('.col_sgst_amount').text(tax_amount);
								var total_cgst = parseFloat(tax_amount)+parseInt(total_cgst);
								var total_sgst = parseFloat(tax_amount)+parseInt(total_sgst);			
								}
							else
							{
	                         	if(gst == null){
	                            	var exact_value = '';
	                            	var exact_sgst  = '';
	                            	var taxable = '';
	                            	var tax_amount = '';
	                            }else{
	                            	var exact_value = gst.split('IGST');
	                            	var exact_sgst = sgst.split('IGST');
	                            }

	                            new_row.find('.col_gst').text(exact_value[0]);
	                            new_row.find('.col_tax_value').text(taxable);
								new_row.find('.col_igst').text(exact_sgst[0]);
								new_row.find('.col_igst_amount').text(tax_amount);
								new_row.find('.col_cgst').text("");
								new_row.find('.col_cgst_amount').text("");
								new_row.find('.col_sgst').text("");
								new_row.find('.col_sgst_amount').text("");
	                    
	                            if (tax_amount == ''){
	                            	var tax_amount = 0;
	                            }else{
	                            	var tax_amount = tax_amount;
	                            }
	                          	var total_igst = parseFloat(tax_amount)+parseInt(total_igst);
							}		
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;							
						}


	             
	                    for(var i=1; i <= gst_length;i++)
	                    {

							var new_row = tax_row.clone();
	                        
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;


						}
			

	                    var  total_tax = total_cgst + total_sgst + total_igst;
	                    var round_of = Math.ceil(total_tax);
	                    var Rount_off_value = round_of - total_tax;
	                    var total = total_tax + total_amount;
	                    var total_amount= Rount_off_value + total;

	                  	var total_withtax = Math.ceil(total_amount);
	                  	var words = new Array();
	                    words[0] = '';
	                    words[1] = 'One';
	                    words[2] = 'Two';
					    words[3] = 'Three';
					    words[4] = 'Four';
					    words[5] = 'Five';
					    words[6] = 'Six';
					    words[7] = 'Seven';
					    words[8] = 'Eight';
					    words[9] = 'Nine';
					    words[10] = 'Ten';
					    words[11] = 'Eleven';
					    words[12] = 'Twelve';
					    words[13] = 'Thirteen';
					    words[14] = 'Fourteen';
					    words[15] = 'Fifteen';
					    words[16] = 'Sixteen';
					    words[17] = 'Seventeen';
					    words[18] = 'Eighteen';
					    words[19] = 'Nineteen';
					    words[20] = 'Twenty';
					    words[30] = 'Thirty';
					    words[40] = 'Forty';
					    words[50] = 'Fifty';
					    words[60] = 'Sixty';
					    words[70] = 'Seventy';
					    words[80] = 'Eighty';
					    words[90] = 'Ninety';
					    amount = total_withtax.toString();
					    var atemp = amount.split(".");
					    var number = atemp[0].split(",").join("");
					    var n_length = number.length;
					    var words_string = "";
					    if (n_length <= 9) {
					        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
					        var received_n_array = new Array();
					        for (var i = 0; i < n_length; i++) {
					            received_n_array[i] = number.substr(i, 1);
					        }
					        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
					            n_array[i] = received_n_array[j];
					        }
					        for (var i = 0, j = 1; i < 9; i++, j++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                if (n_array[i] == 1) {
					                    n_array[j] = 10 + parseInt(n_array[j]);
					                    n_array[i] = 0;
					                }
					            }
					        }
					        value = "";
					        for (var i = 0; i < 9; i++) {
					            if (i == 0 || i == 2 || i == 4 || i == 7) {
					                value = n_array[i] * 10;
					            } else {
					                value = n_array[i];
					            }
					            if (value != 0) {
					                words_string += words[value] + " ";
					            }
					            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Crores ";
					            }
					            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Lakhs ";
					            }
					            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
					                words_string += "Thousand ";
					            }
					            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
					                words_string += "Hundred and ";
					            } else if (i == 6 && value != 0) {
					                words_string += "Hundred ";
					            }
					        }
					        words_string = words_string.split("  ").join(" ");
	   					}

	                	container.find("[data-value='total_cgst']").text(total_cgst.toFixed(2));
	                	container.find("[data-value='total_sgst']").text(total_sgst.toFixed(2));
	                	container.find("[data-value='total_igst']").text(total_igst.toFixed(2));
	                	container.find("[data-value='round_off']").text(Rount_off_value.toFixed(2));
	                	container.find("[data-value='total_amountwithtax']").text(parseFloat(total_withtax).toFixed(2));
	                	container.find("[data-value='rupees']").text(words_string+"Only");
	                	container.find('.floatedTable tbody').empty();
	                	container.find('.floatedTable tbody').append(invoice_tax);                     

	                	var row = container.find('.no_tax_item_table tbody tr').clone();

						var no_tax_sale = ``;
	                    var total_tax_amount = 0;
	                    var sub_total_amount = 0;
			               /* for (var i = 0; i < (data.no_tax_sale).length; i++) {
								var j = i + 1;
								var new_row = row.clone();

								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.no_tax_sale[i].name);
								new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
								new_row.find('.col_rate').text(data.no_tax_sale[i].rate);
								new_row.find('.col_discount').text(data.no_tax_sale[i].discount);
								new_row.find('.col_amount').text(parseFloat(data.no_tax_sale[i].amount).toFixed(2));
			                    var tax_amount = data.no_tax_sale[i].tax_amount;
			                    var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);
			                    var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
								no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
							}*/
						var k =0;
							
						for (var i = 0; i < (data.no_tax_sale).length; i++) {
						
							var j = i + 1;
							var new_row = row.clone();
							var unit_rate = data.no_tax_sale[i].rate;
							var discount_amount = data.no_tax_sale[i].discount;
							
							var amount = data.no_tax_sale[i].amount;
							if(unit_rate == undefined){
								unit_rate = 0;
							}else{
								unit_rate = data.no_tax_sale[i].rate;
							}

							if(discount_amount == undefined){
								discount_amount = 0;
							}else{
								discount_amount = data.no_tax_sale[i].discount;
							}

							if(amount == undefined){
								amount = 0;
							}else{
								amount = data.no_tax_sale[i].amount;
							}
							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.no_tax_sale[i].name);
							new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
							new_row.find('.col_rate').text(parseFloat(unit_rate).toFixed(2));
							new_row.find('.col_discount').text(parseFloat(discount_amount).toFixed(2));
							new_row.find('.col_amount').text(parseFloat(amount).toFixed(2));
	                        var tax_amount = data.no_tax_sale[i].tax_amount;
	                        if(tax_amount == null){
	                        	tax_amount = 0;
	                        }else{
	                        	tax_amount = data.no_tax_sale[i].tax_amount;
	                        }
	                  		 var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);

	                        var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
							no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
				
							k = parseInt(k) + parseInt(data.no_tax_sale[i].discount_amount);
						

						}
						
						   
	                    var total_amount_withtax = parseFloat(total_tax_amount) + parseFloat(sub_total_amount);
	                       
						container.find('.total_table .invoice_sub_total').text(parseFloat(sub_total_amount).toFixed(2));
						container.find('.total_table .tax_value').text(parseFloat(total_tax_amount).toFixed(2));
						
						container.find('.total_table .sum_discount').text(k.toFixed(2));
						container.find('.total_table .invoice_total_amount').text(total_amount_withtax.toFixed(2));
						container.find('.no_tax_item_table tbody').empty();
						container.find('.no_tax_item_table tbody').append(no_tax_sale);

						

						var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

						var row = container.find('.item_table tbody tr').clone();

						var items = ``;

						for (var i = 0; i < (data.items).length; i++) {
							var j = i + 1;
							var new_row = row.clone();

							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.items[i].name);
							new_row.find('.col_hsn').text(data.items[i].hsn);
							new_row.find('.col_gst').text(data.items[i].gst);
							new_row.find('.col_discount').text(data.items[i].discount);
							new_row.find('.col_quantity').text(data.items[i].quantity);
							new_row.find('.col_rate').text(data.items[i].rate);
							new_row.find('.col_amount').text(data.items[i].amount);

							items += `<tr>`+new_row.html()+`</tr>`;
						}

						container.find('.item_table tbody').empty();

						container.find('.item_table tbody').append(items);

						container.find('.total_table .sub_total').text(data.sub_total);
						container.find('.total_table .total').text(data.total);

						var discount_row = container.find('.total_table .discounts').clone();
						var tax_row = container.find('.total_table .taxes').clone();

						var total = ``;

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.taxes).length; i++) {

							var new_row = tax_row.clone();

							new_row.find('.tax_name').text(data.taxes[i].key);
							new_row.find('.tax_value').text(data.taxes[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}
						container.find('.total_table .discounts, .total_table .taxes').remove();
						container.find(".total_table tr").first().after(total);
	                
						var divToPrint=document.getElementById('print');
						//console.log(divToPrint);
						//$('.crud_modal').find('.modal-container').html(divToPrint.innerHTML);
						//console.log($('.crud_modal').find('.modal-container').length);
						/*$.get("{{ route('print_popup.create') }}", function(data) {
							console.log(data);
							$('.print_popup_modal .modal-container').html("");
							$('.print_popup_modal .modal-container').html(divToPrint.innerHTML);
						});
						


						$('.print_popup_modal').modal('show');

						$('.type_print').on('click',function(){*/

     					//printDiv1();
						//console.log($('.print_popup_modal .modal-container'));
						//window.onload=function() { window.print(); }
						//window.print();
						//$('.print_popup_modal .modal-container').print();

					   /* });*/
						//$('.print_popup_modal .modal-container').append("<button class=							'btn btn-primary'>Print</button");

				
							var newWin=window.open("","Propel"/*,"width=690,height=900"*/);

							newWin.document.open();
							newWin.document.write(`<html>
								<style>


							  @page {
							        size: A4;
							        margin: 0;
							    }

							</style>
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
});


	

$('.edit-popup').on('click',function (e) {
	  $('.crud_modal').modal('hide'); 
		e.preventDefault();
			//var id = {!! $id !!};
			var id = $(this).data('id');
			//$('.actions_edit').trigger('click');
			var module_name=$(this).data('module');
			console.log("popup_id"+id);
			console.log(typeof(id));
			if(id != "" && typeof(id) != "undefined") {
				$('.loader_wall_onspot').show();
					$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
						console.log("go edit");
						$.get("{{ url('transaction') }}/"+id+"/edit/"+module_name, function(data) {
							console.log("in edit");
						  $('.full_modal_content').show();
						  //$('.full_modal_content').html("");
						  $('.full_modal_content').html(data);
						  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
						  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
						  $('.loader_wall_onspot').hide();
						  $('.crud_modal').modal('hide');  
						});
					});
				}
	});

	var sum_amount=0;
    $('input[name=total]').each(function(){
        	sum_amount += +$(this).val(); 
    });
    to_fixed_total= parseFloat(sum_amount).toFixed(2);
    $('input[name=wms_total]').val(to_fixed_total);

    var tax_amount=[];
    var res=[];
    $('input[name=tax_amount]').each(function(){
        	tax_amount = $(this).val();
       		var to_fixed = parseFloat(tax_amount).toFixed(2);
       		res.push(to_fixed);
    });
	
    
	var rowCount = $('.item_table tbody tr ').length;
	for (var i = 0; i < rowCount; i++) {
		$(".tax_amount_"+i).val(res[i])
	}


});	

