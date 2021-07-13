@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
	<style>
		.dt-buttons {
			display: none;
		}
		.dataTables_length {
			margin-bottom: -35px;
		}
		 .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
       
	</style>
@stop
@if($module=='sales')
	@include('includes.trade')
@elseif($module=='purchases')
	@include('includes.inventory')
@elseif($module=='wms_sales')
	@include('includes.trade_wms')
@endif

@section('content')

@if(Session::has('flash_message'))
	<div class="alert alert-success" style="display: block;">
		{{ Session::get('flash_message') }}
	</div>
@endif

@if($errors->any())
	<div class="alert alert-danger" style="display: block;">
		@foreach($errors->all() as $error)
			{{ $error }}
		@endforeach
	</div>
@endif

<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
	<h5 class="float-left" style="padding-top: 8px;padding-left: 10px;"><b>GST Report</b></h5>
	<div style="margin-right: 25px;padding-top: 5px;">
		<a class="btn btn-danger float-right csv_export" style="color: #fff">Export CSV</a>
	</div>
</div>
<br><br>
<div class="clearfix"></div>		

		<div class="row">			
			<div class="col-md-3">
				<div class="form-group">
					@if($module == "sales" || $module == "purchases")
					{!!	Form::select('select_type', ['' => 'Select','purchases'=>'Purchases','sales'=>'Sales','all'=> 'All'], null, ['class' => 'form-control select_item']); !!}
					@endif
					@if($module == "wms_sales")
					{!! Form::select('select_type',['' => 'Select','wms_sales' => 'Job Invoice', 'purchases' => 'Purchases'],null,['class' => 'form-control select-item']); !!}
					@endif
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<input style="float: left; width: auto;" name="start_date" type="text" class="form-control user_log_date date-picker datetype" placeholder="From Date" data-date-format="dd-mm-yyyy" />
					
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<input style="float: left; width: auto;" name="end_date" type="text" class="form-control user_log_date date-picker datetype" placeholder="To Date" data-date-format="dd-mm-yyyy" />
					<button style="float: left; padding: 3px 12px; border-radius: 0 3px 3px 0" type="submit" class="date btn btn-success search"><i class="fa fa-search" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
<div class="clearfix"></div>
<br><br>

	<div class="float-left" style="width: 100%; padding-top: 10px">
		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0" style="display: none;">
			<thead>
				<tr>
					<th>Invoice Type</th>
					<th>Invoice Number</th>
					<th>Invoice Date</th>
					<th style="display: none;">Customer Billing Name </th>
					<th style="display: none;">Customer GSTIN </th>
					<th style="display: none;">Customer Billing GSTIN </th>
					<th style="display: none;">State & Place of Supply </th>
					<th style="display: none;">Reference Number </th>
					<th style="display: none;">Item Number</th>
					<th style="display: none;">Item Description</th>
					<th style="display: none;">Is the item a GOOD (G) or SERVICE (S)</th>	
					<th>HSN or SAC code</th>
					<th style="display: none;">Item Quantity</th>	
					<th style="display: none;">Item Unit of Measurement</th>	
					<th style="display: none;">Item Rate</th>
					<th style="display: none;">Discount %</th>
					<th style="display: none;">Total Item Discount</th> 
					<th style="display: none;">Amount</th>
					<th style="display: none;">Item Taxable Value</th>
					<th>GST / IGST</th>
					<th>CGST</th>	
					<th>SGST</th>	
					<th>CGST Amount</th>	
					<th>SGST Amount</th>	
					<th>IGST Amount</th>	
					<th style="display: none;">Cess Rate</th>	
					<th style="display: none;">Cess Amount</th>	
					<th style="display: none;"> Totals</th>	
					<th>Total Transaction Value</th>	
					<th style="display: none;">Type of Export</th>
					<th style="display: none;">Shipping Port Code - Export</th>	
					<th style="display: none;">Shipping Bill Number - Export</th>	
					<th style="display: none;">Shipping Bill Date -Export</th>	
					<th style="display: none;">Is this a Bill of Supply</th>	
					<th style="display: none;">Is Reverse Charge Applicable?</th>
					<th style="display: none;">Is this a Nil Rated/Exempt/NonGST item?</th>		
					<th style="display: none;">Return Filing Period</th>	
					<th style="display: none;">Is this document cancelled?</th>	
					<th style="display: none;">Customer Billing State</th>	
					<th style="display: none;">Date of Linked Advance Receipt</th>
					<th style="display: none;">Voucher Number of Linked Advance Receipt</th>	
					<th style="display: none;">Adjustment Amount of the Linked Advance Receipt</th>	
					<th style="display: none;">Is the customer a Composition dealer or UIN registered?</th>	
					<th style="display: none;">Customer</th> 
					<th style="display: none;">Pincode</th>	
				</tr>
			</thead>
			<tbody>
			
			</tbody>
			<tfoot>
			</tfoot>
		</table>
	</div>
   
</div>
@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script>

$(document).ready(function() {

	$('.search').on('click', function() {
		
			var start_date = $('input[name=start_date]').val();
			var end_date = $('input[name=end_date]').val();
			var select_type = $('select[name=select_type]').val();

			if(start_date != "" && end_date != "" && select_type != "") {
				get_gst(start_date,end_date);
				$('#datatable').show();
			}else{
				$('#datatable').hide();
			}
	});

	var datatable = null;

	$('body').on('click', '.csv_export', function(){
		$(".buttons-csv")[0].click(); //trigger the click event
	});

	datatable =  $('#datatable').DataTable({

		dom: 'B',
		buttons: [ { extend: 'csv', filename: 'Data export', 'title': '', exportOptions: { columns: ":not(.noExport)" },  footer: false } ]


});


	


	// Default index page show all records

	//get_gst('{{$start_date}}','{{$end_date}}');

	function get_gst(start_date,end_date) {

			var html = ``;
			var foot = ``;
			var url = window.location.href;
			var page = $.trim($('.page-title').clone().find('a').remove().end().text());

			$.ajax({
				 url: "{{ route('gst_report.get_gst_report') }}",
				 type: 'post',
				 data: {
					
					_token :"{{csrf_token()}}",
					
					start_date:start_date,
					end_date:end_date,
					select_type: $('select[name=select_type]').val(),
					},
				dataType: "json",
					success:function(data, textStatus, jqXHR) {
					var transactions = data.transactions;
					var total_tax_amount = 0;
					var cgst_value = '';
					var sgst_value = '';
					var exact_value = '';
					datatable.destroy();
					$('#datatable tbody').empty();
					$('#datatable tfoot').empty();

	for (var i in transactions) {

					var date=transactions[i].date;
					
					var tax_type = transactions[i].tax_type;
					var tax_value = transactions[i].tax_value;
					var cgst = transactions[i].cgst;
	            if(tax_type == 1){
					if(cgst != null){
						exact_value = cgst.split('CGST');
						cgst_value = exact_value[0];
						sgst_value = exact_value[0];
					}else{
						cgst_value = '';
						sgst_value = '';
						
					}
				}	
				
				var tax_amount = transactions[i].tax_amount;
				if(tax_amount == null){
					tax_amount = 0;
				} else{
					tax_amount = parseFloat(transactions[i].tax_amount).toFixed(2);
				}
                
				var total_tax_amount = parseFloat(total_tax_amount) + parseFloat(tax_amount);
				

				var billing_name = transactions[i].billing_name;
				var billing_address = transactions[i].billing_address;
				if(billing_name == null){
					billing_name = '';
				}else{
					billing_name = transactions[i].billing_name;
				}

				if(billing_address == null){
					billing_address = '';
				}else{
					billing_address = transactions[i].billing_address;
				}
					

					var reference_number = transactions[i].reference_no;
					if(reference_number == null){
						reference_number = '';
					}else{
						reference_number = transactions[i].reference_no;
					}

					var gst =transactions[i].company_gst;
					if(gst == null){
						gst = '';
					}else{
						gst =transactions[i].company_gst;
					}

					var discount = transactions[i].discount_value;
					if(discount == null){
						discount = '';
					}else{
						discount = transactions[i].discount_value;
					}

					var tax = transactions[i].tax;
					if(tax == null){
						tax = '';
						cgst_value = '';
						sgst_value = '';
						tax_value = '';
					}else{
						tax = transactions[i].tax;
					}
					 
					if(transactions[i].date == null)
					{
						date = transactions[i].job_date;
					}
						html += `<tr>
					<td>`+transactions[i].transaction_type+`</td>
					<td>`+transactions[i].order_no+`</td>
					<td >`+date+`</td>
					<td style="display: none;">`+billing_name+`</td>
					<td style="display: none;">`+gst+`</td>
					<td style="display: none;">`+transactions[i].billing_gst+`</td>
					<td style="display: none;">`+billing_address+`</td>
					<td style="display: none;">`+reference_number+`</td>
					<td style="display: none;">`+transactions[i].item_no+`</td>
					<td style="display: none;">`+transactions[i].description+`</td>
					<td style="display: none;">`+transactions[i].category_type+`</td>
					<td>`+transactions[i].hsn+`</td>
					<td style="display: none;">`+transactions[i].quantity+`</td>
					<td style="display: none;">`+transactions[i].unit+`</td>
					<td style="display: none;">`+transactions[i].rate+`</td>
					<td style="display: none;">`+discount+`</td>
					<td style="display: none;">`+transactions[i].discount_amount+`</td> 
					<td style="display: none;">`+transactions[i].amount+`</td>
					<td style="display: none;">`+parseFloat(transactions[i].taxable_amount).toFixed(2)+`</td>
					<td>`+tax+`</td>
					<td>`+cgst_value+`</td>
					<td>`+sgst_value+`</td>`
					if(tax_type == 1){
						html+= `<td>`+parseFloat(tax_value).toFixed(2)+`</td>
								<td>`+parseFloat(tax_value).toFixed(2)+`</td>
								<td></td>`
					}else{
						html+= `<td></td>
								<td></td>
								<td>`+parseFloat(tax_value).toFixed(2)+`</td>`
					}
					
					html+= `<td style="display: none;">Cess Rate</td>
					<td style="display: none;">Cess Amount</td>
					<td style="display: none;">`+tax_amount+`</td>
					<td>`+parseFloat(transactions[i].total).toFixed(2)+`</td>
					<td style="display: none;">Type of Export</td>
					<td style="display: none;">Shipping Port Code - Export</td>
					<td style="display: none;">Shipping Bill Number - Export</td>
					<td style="display: none;">`+transactions[i].shipping_date+`</td>
					<td style="display: none;">Is this a Bill of Supply</td>
					<td style="display: none;">Is Reverse Charge Applicable?</td>
					<td style="display: none;">Is this a Nil Rated/Exempt/NonGST item?</td>
					<td style="display: none;">Return Filing Period</td>
					<td style="display: none;">Is this document cancelled?</td>
					<td style="display: none;">`+transactions[i].billing_address+`</td>
					<td style="display: none;">Date of Linked Advance Receipt</td>
					<td style="display: none;">Voucher Number of Linked Advance Receipt</td>
					<td style="display: none;">Adjustment Amount of the Linked Advance Receipt</td>
					<td style="display: none;">Is the customer a Composition dealer or UIN registered?</td>
					<td style="display: none;">`+transactions[i].customer_name+`</td>
					<td style="display: none;">`+transactions[i].pin+`</td>
				</tr>`;
	}

					foot += `
				<tr>
					<td></td>
					<td></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>	
					<td></td>
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td> 
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>
					<td style="display: none;"></td>	
					<td style="display: none;"></td>
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>			
					<td style="display: none;"></td>	
					<td style="display: none;"></td>
					<td style="display: none;">Total Tax Amount = `+parseFloat(total_tax_amount).toFixed(2)+`</td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>		
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td>	
					<td style="display: none;"></td> 
					<td style="display: none;"></td>	
				</tr>
			`;


					$('#datatable tbody').append(html);
					$('#datatable tfoot').append(foot);

					datatable =  $('#datatable').DataTable({ 
		dom: 'B',
		buttons: [ { extend: 'csv', filename: 'Data export', 'title': '', exportOptions: { columns: ":not(.noExport)" },   footer: true } ]


	});


				$('.loader_wall_onspot').hide();

					},
					error:function(jqXHR, textStatus, errorThrown) {
					//
					}
			});
				
			}
});
</script>
@stop