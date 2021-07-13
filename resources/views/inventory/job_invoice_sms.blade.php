<div class="alert alert-danger">
    {{ Session::get('flash_message') }}
</div>
<div class="modal-header">
	<h4 class="modal-title float-right">Send SMS/Send Email</h4>
	 <a  class="close" data-dismiss="modal">&times;</a>
</div>
{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}
<div class="modal-body" style="height:400px; overflow-y: auto;overflow-x: hidden;">
			{!! Form::hidden('default_message',$sms_content,array('class' => 'control-label default_message')) !!}
			{!! Form::hidden('mge',$mge,array('class' => 'control-label mge')) !!}
			{!! Form::hidden('order_no',null,array('class' => 'control-label pdf_name')) !!}
			{!! Form::hidden('email_id',$email,array('class' => 'control-label email_id')) !!}
		<div class="row">
			<div class="col-md-4">
				{{ Form::label('', 'Send SMS', array('class' => 'control-label ')) }}
			</div>
			<div class="col-md-6" data-toggle="tooltip" data-placement="top" title="Check Send SMS & Uncheck not Send SMS">
				 {{ Form::checkbox('sms_checkbox', 1, true,['id' => 'sms_checkbox']) }} <label for="sms_checkbox"><span></span></label></th>
	        </div>
		</div><br>
		<div class="row">
			<div class="col-md-4">
				{{ Form::label('sms', 'Choose the SMS:', array('class' => 'control-label ')) }}
			</div>
			<div class="col-md-6">
				{!! Form::select('message_type',$sms_summary,null,['class' =>'form-control sms_type']) !!}
	         </div>
		</div><br>
		<div class="row">
			<div class="col-md-4">
				{{ Form::label('mobile_no', 'Mobile No', array('class' => 'control-label ')) }}
			</div>
			<div class="col-md-6">
				{!! Form::text('mobile_no',$mobile_no,array('class' =>'form-control mobile_no')) !!}
	         </div>
		</div><br>
		<div class="row" style="margin-top: 20px;">
			<div class="col-md-12">
			{{ Form::textarea('message',$sms_summary,['class' => 'form-control message', 'rows' =>06]) }}
			 </div> 
		</div><br>
		@if($mge != 'Job Card')
		<a href="#" class="show_email">Email Options</a><hr width="470px" style="margin-top: -05px;">
		@endif
		
		 
		

		<br>
		<div class="email_options" style="display: none;"><br>
				<div class="row">
					<div class="col-md-4">
						{{ Form::label('', 'Send Email', array('class' => 'control-label ')) }}
					</div>
					<div class="col-md-2" data-toggle="tooltip" data-placement="top" title="Check Send Email & Uncheck not Send Email.">
						 {{ Form::checkbox('email_checkbox', 'sms_checkbox', null, ['id' => 'email_checkbox'] ) }} <label for="email_checkbox"><span></span></label></th>
			        </div>
			       
			      
				</div><br>
				<div class="row">
					<div class="col-md-4">
						{{ Form::label('email_attchments', 'Email Attachement', array('class' => 'control-label ')) }}
					</div>
					<div class="col-md-6">
						<!-- {!! Form::text('email_attchments','NO Tax JobEstimations',array('class' =>'form-control','disabled' =>'disabled')) !!} -->
						{!! Form::select('email_attchments',[''=>'Select Print Type','no_tax'=>'NO Tax JobEstimations','hsn_based_invoice'=>'B2B_HSNbased_Invoice'],null,['class' =>'form-control select_item email_attchments']) !!}
			         </div>
			          <div class="col-md-2" style="margin-left:-25px; ">
						<!--  <button type="button" class="btn btn-primary show_pdf">Download pdf</button> -->
						 <a href="#" class="btn btn-primary show_pdf" style="display: none;">Show Pdf</a>
			        </div>
				</div><div class="alert_message" style="color: red;margin-left: 165px;"></div><br>
				<div class="row">
					<div class="col-md-4">
						{{ Form::label('email', 'Email ID', array('class' => 'control-label ')) }}
					</div>
					<div class="col-md-6">
						{!! Form::text('email_id',null,array('class' =>'form-control email')) !!}
			         </div>
				</div>
				</div>
		</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-danger">Send</button>
</div>
{!! Form::close() !!}
{{--

	@stop

@section('dom_links')
@parent 				
 

--}}

<script type="text/javascript">
	$(document).ready(function() {
	 var message_content=$('.default_message').val();
	 $('.message').val(message_content);
	 var mge=$('.mge').val();
	
	$('body').on('change', '.sms_type', function(e) {
			var message = $(this).val();
			$('.message').val(message);
	});

	$('.show_email').on('click', function(e) {
		
			$('.email_options').toggle();
	});

	$('body').on('click','.show_pdf',function(e) {
			e.preventDefault();
			 generate_pdf('show_pdf');
			 
			
					
	});
	$('#email_checkbox').on('change', function() {
    if ($(this).is(':checked')) 
    {
     $('input[name=email_id]').val($('.email_id').val());
     	
    }else{
   		$('input[name=email_id]').val('');
        // $('.show_pdf').hide();
    }
  	});
$('.email_attchments').on('change', function(e) {
	$('.alert_message').empty();
		$.ajax({	
				url: "{{ route('print_transaction') }}",
				type: 'post',
				data: {
						_token : '{{ csrf_token() }}',
						id: "{!!$id!!}"
					  },
				success:function(data, textStatus, jqXHR) 
				{	
					var pdf_type = $('.email_attchments').val();
					var voucher_type = data.transaction_type;
					
					if(data.transaction_type == "Job Invoice Cash" || data.transaction_type == "Job Invoice Credit" || data.transaction_type == "Invoice Cash"){
						var voucher_type= 'INVOICE';
					}
					if(data.transaction_type == "Job Estimation"){
						var voucher_type= 'Estimation';
					}
					
					var customer_gst='';
					var vehicle_number = '';
					var make='';
					if(data.customer_gst != null){
						var customer_gst = data.customer_gst;
					}
					if(data.vehicle_number != null){
						var vehicle_number = data.vehicle_number;
					}
					if(data.make_model_variant != null){
						var make = data.make_model_variant;
					}
					
						var k=1;
						var pdf_view = '';
						
					if(pdf_type == "no_tax")
					{	
						if(data.no_tax_sale.length > 30){
								$('#email_checkbox').prop('checked', false);
								$('.show_pdf').hide();
								$('.email_attchments').val('');
								$('.email').val('');
								$('.alert_message').empty().append("*Pdf has one page (~30 items) only...");
								return false;
						}

//No Tax Job Estimation Start 
			pdf_view += `<style>
				              .item_table {
				                border-collapse: collapse;
				              }
				               @media print {
				              body {
				                -webkit-print-color-adjust: exact;
				              }
				              }
				              </style>
              	<div data-type="landscape" style="width: 260mm; height: 350mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;border:2px solid;margin-left:35px;" class="workspace">
                <div style="position: relative; min-height: 200px; height: 262px;" class="header_container content_container">

                <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 7.09999px; left: 309.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(62, 72, 85); font-size: 22px; font-family: &quot;MS Serif&quot;, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 38.2333px; left: 327.217px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 60.3333px; left: 350.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(62, 72, 85); font-size: 16px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="company_phone">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 84.3333px; left: 340.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(62, 72, 85); font-size: 22px; font-family: &quot;MS Serif&quot;, serif; font-weight: bold;" class="value_result" data-value="voucher_type">`+voucher_type+`</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 106.233px; left: 0.233337px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 980px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 134.333px; left: 13.3333px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="vehicle_number">`+vehicle_number+`</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 153.333px; left: 24.3333px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Make : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="make_model_variant">`+make+`t</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 172.467px; left: 1.46667px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 193.333px; left: 10.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Address : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 217.567px; left: 20.5667px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_gst">`+customer_gst+`</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 237.567px; left: 20.5667px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Mobile :</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_mobile">customer_gst</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 135.567px; left: 607.567px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Number : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif; font-weight: bold;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 156.333px; left: 619.333px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Dated : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="date">Estimation Date</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 175.333px; left: 609.333px;margin-left:-20px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Prepared by : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="assigned_to">Estimation Date</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 203px; left: 276px;" class="draggable ui-draggable ui-draggable-handle"></div></div>

                <div style="position: relative;" class="body_container content_container">
                <hr style="border: 1px solid black; width: 980px;">
                  <table class="no_tax_sales_table" style="border-top:1px solid;border-bottom:1px solid;" width="100%">
                    <thead>
                      <tr style="font-family: &quot;Courier New&quot;, monospace; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
                        <th style="" class="col_id">#</th>
                        <th style="" class="col_desc">Item Description</th>
                        <th style="" class="col_quantity">Quantity</th>
                        <th style="" class="col_rate">UnitRate RS</th>
                        <th style="" class="col_discount">Discount RS</th>
                        <th style="" class="col_amount">Total Rs</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="padding: 5px;" class="col_id">&nbsp;</td>
                        <td style="padding: 5px;" class="col_desc">&nbsp;</td>
                     <td style="padding: 5px;" class="col_quantity">&nbsp;</td>
                     <td style="padding: 5px;" class="col_rate">&nbsp;</td>
                        <td style="padding: 5px;" class="col_discount">&nbsp;</td>
                        <td style="padding: 5px;" class="col_amount">&nbsp;</td>
                      </tr>
                      <tr>
                        <td style="padding: 5px;" class="col_id">&nbsp;</td>
                        <td style="padding: 5px;" class="col_desc">&nbsp;</td>
                     <td style="padding: 5px;" class="col_quantity">&nbsp;</td>
                     <td style="padding: 5px;" class="col_rate">&nbsp;</td>
                        <td style="padding: 5px;" class="col_discount">&nbsp;</td>
                        <td style="padding: 5px;" class="col_amount">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <br>
                <div class="total_container content_container">
                  <table class="total_table" style="border-bottom:1px solid;" width="100%" align="right">
                    <tbody>
                      <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td>Total</td>
                        <td class="sales_total_amount" style="text-align: right;">0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div style="position: relative; float: left; width: 100%; height: 50px;" class="body_container content_container">
                <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 3.33331px; left: 4.33331px;" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(62, 72, 85); font-size: 14px; font-family: &quot;MS Serif&quot;, serif;">Disclaimer: <br>Thanks for your Business with us. This document is autogenerated from system. <br>If this has any mistakes in items and errors in calculations, please let us know.<br>Since this is autogenerated,this may not require Any Signatures.<br>Please use the Bank details and pay money on demand.<br></div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; position: absolute; top: 6.23334px; left: 577.217px;" class="draggable ui-draggable ui-draggable-handle"><div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1.11667px; width: 200px; height: 110px;" class="rectangle_result">&nbsp`+data.custom_values+`</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
              </div>`;
//No Tax Job Estimation End**

					

					}else if(pdf_type == "hsn_based_invoice"){
						
						if(data.no_tax_sale.length > 15){
								$('#email_checkbox').prop('checked', false);
								$('.show_pdf').hide();
								$('.email_attchments').val('');
								$('.alert_message').empty().append("*Pdf has one page (~15 items) only...");
								return false;
						}
// HSN Invoice Start						
		pdf_view += `<style>
				.workspace
				{
				    display: block;
				    page-break-inside: avoid;
				  page-break-before: avoid;
				  page-break-after: avoid;
				    -webkit-region-break-inside: avoid; 
				}
				</style>
				<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
				<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);margin-left:25px;margin-right:25px;">
				  <div style="position: relative; min-height: 300px; height: 321px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="float: left; position: absolute; top: 143px; left: 0px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
				 <hr style="border: 1px solid black; width: 1033px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 166px; left: 8px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;margin-left:10px;" class="value_result" data-value="vehicle_number">`+vehicle_number+`</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 191px; left: 10px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;margin-left:10px;" class="value_result" data-value="make_model_variant">`+make+`</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 212px; left: 10px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;margin-left:-05px;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 233px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 254px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST No:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 170px; left: 663px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher#: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 193px; left: 680px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 10.109375px; left: 370.109px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 45.1094px; left: 384.109px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 79.1094px; left: 287.1094px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 79.4531px; left: 523.453px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 120.2188px; left: 400.219px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 22px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">`+voucher_type+`</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 276px; left: -2px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: Arial, sans-serif;" class="label_result">Mobile No:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: Arial, sans-serif;" class="value_result" data-value="customer_mobile"> Customer Mobile No</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 213px; left: 651px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: Arial, sans-serif;" class="label_result">Prepard by:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: Arial, sans-serif;" class="value_result" data-value="assigned_to">Mechannic Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
				<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="body_container">
				<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="invoice_item_table">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">DISCOUNT</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
				    </tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">E &amp; OE</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				   </tr>
				   </tfoot>
				  
				</table>
				<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="hsnbasedTable">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">HSN/SAC</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
				</tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				</tbody>
				<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
				</tr>
				</tfoot>
				</table>
				<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="ft">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">CGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">SGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">IGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Round off</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">TOTAL:</td>
				<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				</tbody></table>
				</td>
				</tr></tbody></table>
				</div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 12px;" class="footer_container content_container">
				  Discription:Goods once sold can not be taken back!
				  <div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 17px; left: 0px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float:left; padding-right:15px;" class="label_result">Label</div><div style="right:-5px; top: 15px; width:15px;" class="remove"><i class="fa fa-times"></i></div></div><div style="float:left;" class="value_result">Value</div> <div class="remove"><i class="fa fa-times"></i></div></div></div></div>
				  </div>
				</div>`;
// HSN Invoice End**--- 
					}
					$('.pdf_view_print').empty().append(pdf_view);
					// $('.message').val(data.customer_address);
// // Value set pdf Start
		     				// $("[data-value='voucher_type']").text(data.transaction_type);
							$("[data-value='po']").text(data.po_no);
							$("[data-value='purchase']").text(data.purchase_no);
 							$("[data-value='grn']").text(data.grn_no);
 							$("[data-value='date']").text(data.date);
							$("[data-value='payment_mode']").text(data.payment_mode);
							$("[data-value='resource_person']").text(data.resource_person);
							$("[data-value='customer_address']").text(data.customer_address);
							$("[data-value='estimate_no']").text(data.estimate_no);
							$("[data-value='shipping_address']").text(data.shipping_address);
							$("[data-value='billing_address']").text(data.billing_address);
                            $("[data-value='customer_vendor']").text(data.customer_vendor);
                            // $("[data-value='vehicle_number']").text(data.vehicle_number);
                            // $("[data-value='make_model_variant']").text(data.make_model_variant);
                            $("[data-value='company_name']").text(data.company_name);
                            $("[data-value='company_phone']").text(data.company_phone);
                            $("[data-value='company_address']").text(data.company_address);
                            $("[data-value='email_id']").text(data.email_id);
                            $("[data-value='amount']").text(data.amount);
                            $("[data-value='payment_method']").text(data.payment_method);
                            $("[data-value='km']").text(data.km);
                            $("[data-value='assigned_to']").text(data.assigned_to);
                            $("[data-value='company_gst']").text(data.company_gst);
                            // $("[data-value='customer_gst']").text(data.customer_gst);
                            $("[data-value='customer_mobile']").text(data.customer_mobile);
                            $("[data-value='customer_communication_gst']").text(data.customer_communication_gst);
                            $("[data-value='billing_communication_gst']").text(data.billing_communication_gst);
                              $("[data-value='driver']").text(data.driver);
                            $("[data-value='driver_mobile_no']").text(data.driver_mobile_no);
                            $("[data-value='warranty']").text(data.warranty);
                            $("[data-value='insurance']").text(data.insurance);
                            $("[data-value='mileage']").text(data.mileage);
                            $("[data-value='engine_no']").text(data.engine_no);
                            $("[data-value='chassis_no']").text(data.chassis_no);
                            $("[data-value='job_due_on']").text(data.job_due_on);
                            $("[data-value='last_visit_on']").text(data.last_visit_on);
                            $("[data-value='next_visit_on']").text(data.next_visit_on);
                            $("[data-value='service_on']").text(data.service_on);
                            $("[data-value='last_visit_jc']").text(data.last_visit_jc);


                            /*Job card print*/
                            

                            var row = $('.job_card_table tbody tr').clone();

                            var job_card_item = ``;

                            var total_job_items_length = 12;

                            var job_card_length = total_job_items_length - (data.job_card_items).length;
                          

                            	for (var i = 0; i < (data.job_card_items).length; i++) {

								var j = i + 1;

								var new_row = row.clone();



								new_row.find('.col_s_no').text(j);

								new_row.find('.col_items').text(data.job_card_items[i].item_name);

								new_row.find('.col_qty').text(data.job_card_items[i].qty);

								new_row.find('.col_total_price').text(data.job_card_items[i].amt);

								job_card_item += `<tr>`+new_row.html()+`</tr>`;

							}

                            for(var i=1; i <= job_card_length;i++){
								var job_new_row = row.clone();
								job_card_item += `<tr>`+job_new_row.html()+`</tr>`;

							}
                            
							$('.job_card_table tbody').empty();

							$('.job_card_table tbody').append(job_card_item);

							var complaints = data.complaints;
                             if(complaints != null){
                             	var vehicle_complaints = complaints.split('\n',8).join('<br>');
                             }else{
                             	var vehicle_complaints = '';
                             }

                            $("[data-value='complaints']").html(vehicle_complaints);

                             var checklist_details = Object.values(data.checklist_details);

							 var row = $('.checklist tbody tr').clone();

							 var checklist = ``;

							 var total_checklist = 12;
                             
                             var checklist_total = total_checklist - (checklist_details).length;

                            for (var i = 0; i < (checklist_details).length; i++) {
                                   var check_new_row = row.clone();
                                   
                                check_new_row.find('.col_checklist').text(checklist_details[i].checklist);
                                check_new_row.find('.col_notes').text(checklist_details[i].notes);

                                checklist += `<tr>`+check_new_row.html()+`</tr>`;
                            }
                            
                            for(var i=1; i <= checklist_total;i++){
								var check_new_row = row.clone();
								checklist += `<tr>`+check_new_row.html()+`</tr>`;

							}

                            
                            $('.checklist tbody').empty();
                            $('.checklist tbody').append(checklist);
                            var fuel_value = data.fuel_level;
                            var fuel = ``;
                            if(fuel_value != null){
                            	fuel = data.fuel_level[0].notes;
                            }else{
                            	fuel = '';
                            }
                            
                            $("[data-value='fuel_checklist']").text(fuel);
                            $("[data-value='top']").text(data.first_checklists[4].notes);
                            $("[data-value='right']").text(data.first_checklists[3].notes);
                            $("[data-value='left']").text(data.first_checklists[2].notes);
                            $("[data-value='front']").text(data.first_checklists[1].notes);
                            $("[data-value='back']").text(data.first_checklists[0].notes);

                            /*END*/
                           
                            
							var row_color = $('.item_table tbody tr:nth-child(2)').css('backgroundColor');

							var row = $('.invoice_item_table tbody tr').clone();

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
                                var amount= data.items[i].rate * data.items[i].quantity - discount_value.amount;
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

							$("[data-value='total_discount']").text(total_discount);
		                    $("[data-value='total_amount']").text(parseFloat(total_amount).toFixed(2));
							$('.invoice_item_table tbody').empty();
							$('.invoice_item_table tbody').append(invoice_items);

							var hsn_invoice_tax_values = Object.values(data.hsn_based_invoice_tax);
	                       
	                       //HSN based tax table
	                        var hsn_row = $('.hsnbasedTable tbody tr').clone();
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
	                        $('.hsnbasedTable tbody').empty();
							$('.hsnbasedTable tbody').append(hsn_tax);












							var invoice_tax_values = Object.values(data.invoice_tax);
							var tax_row = $('.floatedTable tbody tr').clone();
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

								
								var total_cgst = parseFloat(tax_amount)+parseFloat(total_cgst);
								 

								var total_sgst = parseFloat(tax_amount)+parseFloat(total_sgst);


								
								}
							else{
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

                    
                            for(var i=1; i <= gst_length;i++){

								var new_row = tax_row.clone();
                                
								invoice_tax += `<tr>`+new_row.html()+`</tr>`;


							}

							

		                    var  total_tax = total_cgst + total_sgst + total_igst +total_amount;
		                    var round_of = Math.ceil(total_tax);
		                    var Rount_off_value = round_of - total_tax;
		                    var total = total_tax + total_amount;
		                    var total_amount= Rount_off_value + total_tax;

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

   
   
    

   
		                        $("[data-value='total_cgst']").text(total_cgst.toFixed(2));
		                        $("[data-value='total_sgst']").text(total_sgst.toFixed(2));
		                        $("[data-value='total_igst']").text(total_igst.toFixed(2));
		                        $("[data-value='round_off']").text(Rount_off_value.toFixed(2));
		                        $("[data-value='total_amountwithtax']").text(parseFloat(total_withtax).toFixed(2));
		                        $("[data-value='rupees']").text(words_string+"Only");
		                        $('.floatedTable tbody').empty();
		                        $('.floatedTable tbody').append(invoice_tax);                     
		 
		                        var row = $('.no_tax_item_table tbody tr').clone();

							var no_tax_sale = ``;
                            var total_tax_amount = 0;
                            var sub_total_amount = 0;
							for (var i = 0; i < (data.no_tax_sale).length; i++) {
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
							}

							   
                               var total_amount_withtax = parseFloat(total_tax_amount) + parseFloat(sub_total_amount);
                              
							$('.total_table .invoice_sub_total').text(parseFloat(sub_total_amount).toFixed(2));
							$('.total_table .tax_value').text(parseFloat(total_tax_amount).toFixed(2));
							$('.total_table .invoice_total_amount').text(total_amount_withtax.toFixed(2));
							$('.no_tax_item_table tbody').empty();
							$('.no_tax_item_table tbody').append(no_tax_sale);
                            //to show items in b2c no tax job invoice..
							var row = $('.no_tax_sales_table tbody tr').clone();

							var no_tax_estimation = ``;
							var total_sale_amount = 0.00;
							for (var i = 0; i < (data.no_tax_estimation).length; i++) {
								var j = i + 1;
								var sales_new_row = row.clone();
								var tax_amount = data.no_tax_estimation[i].tax_amount;
								if(tax_amount == null){
									tax_amount = 0.00;
								}
								var tax_rate = data.no_tax_estimation[i].tax_rate;
								if(tax_rate == null){
									tax_rate = 0.00;
								}
								var unit_price = data.no_tax_estimation[i].rate;
								var quantity = data.no_tax_estimation[i].quantity;
								var price = parseFloat(tax_rate) + parseFloat(unit_price);
								var amount = parseFloat(quantity) * parseFloat(unit_price);
								var total_amount = parseFloat(amount) + parseFloat(tax_amount);
								
								
								sales_new_row.find('.col_id').text(j);
								sales_new_row.find('.col_desc').text(data.no_tax_estimation[i].name);
								sales_new_row.find('.col_quantity').text(data.no_tax_estimation[i].quantity);
								sales_new_row.find('.col_rate').text(parseFloat(price).toFixed(2));
								sales_new_row.find('.col_discount').text(data.no_tax_estimation[i].discount);
								sales_new_row.find('.col_amount').text(parseFloat(total_amount).toFixed(2));
								var total_sale_amount = parseFloat(total_amount) + parseFloat(total_sale_amount);
								no_tax_estimation += `<tr>`+sales_new_row.html()+`</tr>`;
							}

							$('.sales_total_amount').text(parseFloat(total_sale_amount).toFixed(2));

							$('.no_tax_sales_table tbody').empty();
							var rowSpanCount = data.no_tax_sale.length <= 4 ?4:1;
													
									 for($i=1;$i< rowSpanCount;$i++) {
									no_tax_estimation += `<tr colspan="6"><td >&nbsp;</td></tr>`;
									}
							$('.no_tax_sales_table tbody').append(no_tax_estimation);

							var row = $('.item_table tbody tr').clone();

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

							$('.item_table tbody').empty();

							$('.item_table tbody').append(items);

							$('.total_table .sub_total').text(data.sub_total);
							$('.total_table .total').text(data.total);

							var discount_row = $('.total_table .discounts').clone();
							var tax_row = $('.total_table .taxes').clone();

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
							$('.total_table .discounts, .total_table .taxes').remove();
							$(".total_table tr").first().after(total);

// // Value set pdf End***
							 
							
					$('.pdf_name').val(voucher_type+'-'+data.id);
					$('.show_pdf').show();

					}
			});
	});


	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: { 
			  mobile_no: { 
		            required: function (element) {
		               if($('#sms_checkbox').is(':checked')) {
		                    return true;
		                }
		                return false;
		            },
		            minlength: 1 
		        } ,
		       email_id: { 
		         required: function (element) {
		               if($('#email_checkbox').is(':checked')) {
		                    return true;
		                }
		                return false;
		            },
		            minlength: 1 
		        } ,
		       email_attchments: { 
		         required: function (element) {
		               if($('#email_checkbox').is(':checked')) {
		                    return true;
		                }
		                return false;
		            },
		            minlength: 1 
		        }
    	}, 
    	messages: { 
    		mobile_no: { required: " Mobile No is required."},
           	email_id: { required: " Email is required."},
           	email_attchments:{ required: " Email Type is required."},
    	}, 
		invalidHandler: function(event, validator)
		{ 
			//display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) 
		{ // hightlight error inputs
			$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},
		submitHandler: function(form) {
			$('.loader_wall_onspot').show();
			generate_pdf('send_pdf');
			$.ajax({
			url: '{{ route('send_sms_and_email') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id:'{!!$id!!}',
				sms_checkbox:$('#sms_checkbox').is(":checked"),
				mobile_no:$('input[name=mobile_no]').val(),
				mge:mge,
				message: $('textarea[name=message]').val(),
				email_checkbox:$('#email_checkbox').is(":checked"), 
				email_id:$('.email').val(),
				pdf_name : $('input[name=order_no]').val(),
				},
			success:function(data, textStatus, jqXHR) {
				alert_message(data.message, "success");
				$('.crud_modal').modal('hide');
				$('.loader_wall_onspot').hide();
				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	function generate_pdf($where){

							
		
								var doc = new jsPDF('p', 'pt', 'letter');
						      	doc.internal.scaleFactor = 1.80;
						        $('.pdf_view_print').show();
						        doc.addHTML($('.pdf_view_print'), 10, 20, {
						        'background': '#fff',
						        },
						      function() {
								
									if($where == "show_pdf"){
										window.open(doc.output('bloburl'), '_blank');
										// doc.output('dataurlnewwindow');

										
									}
									
									if($where == "send_pdf"){
										var pdf = btoa(doc.output()); 
											$.ajax({
											 url: '{{ route('invoice_pdf_path') }}',
											 type: 'post',
											 data: {
												_token : '{{ csrf_token() }}',
												data: pdf,
												order_no:$('.pdf_name').val(),
												},
											 dataType: "json",
												success:function(data, textStatus, jqXHR) {
												},
												error:function(jqXHR, textStatus, errorThrown) {
												}

											});	
									}
								});
								$('.pdf_view_print').hide();
								return false;
								
		
	}
	 });
</script>