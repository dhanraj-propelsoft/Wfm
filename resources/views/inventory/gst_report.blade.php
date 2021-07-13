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
	</style>
@stop
@include('includes.inventory')
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

<div class="fill header">
	<h4 class="float-left">GST Report</h4>
<a class="btn btn-danger float-right csv_export" style="color: #fff">Export CSV</a>
</div>
<div class="clearfix"></div>		

		<div class="row">			
			<div class="col-md-3">
				<div class="form-group">					
					{!!	Form::select('select_type', ['' => 'Select','purchases'=>'Purchases','sales'=>'Sales','all'=> 'All'], null, ['class' => 'form-control select_item']); !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<input style="float: left; width: auto;" name="date" type="text" class="form-control user_log_date datetype" placeholder="Month" data-date-format="mm-yyyy" />
					<button style="float: left; padding: 3px 12px; border-radius: 0 3px 3px 0" type="submit" class="date btn btn-success"><i class="fa fa-search" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
<div class="clearfix"></div>
<br><br>

<div class="float-left" style="width: 100%; padding-top: 10px">
	<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>Invoice Type</th>
					<th>Invoice Number</th>
					<th>Invoice Date</th>
					<th style="display: none;">Customer Billing Name </th>
					<th style="display: none;">Customer Billing GSTIN </th>
					<th style="display: none;">State Place of Supply </th>
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
					<th>GST Rate</th>
					<th>CGST Rate</th>	
					<th>SGST Rate</th>	
					<th>IGST Rate</th>
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
					<th style="display: none;">Customer Billing Address</th>	
					<th style="display: none;">Customer Billing City</th>	
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

	var datatable = null;

	$('body').on('click', '.csv_export', function(){
		$(".buttons-csv")[0].click(); //trigger the click event
	});

	datatable =  $('#datatable').DataTable({

		dom: 'B',
		buttons: [ { extend: 'csv', filename: 'Data export', 'title': '', exportOptions: { columns: ":not(.noExport)" },  footer: false } ]


});

	$('.user_log_date').datepicker({
		autoclose: true,
		minViewMode: 1,
		format: 'mm-yyyy'
	});

	$('.date').on('click', function() {

			var date = $('input[name=date]').val();

			if(date != "") {
				get_gst(date);
			}
	});

	get_gst('{{$date}}');

	function get_gst(date) {

			var html = ``;
			var url = window.location.href;
			var page = $.trim($('.page-title').clone().find('a').remove().end().text());

			$.ajax({
				 url: "{{ route('gst_report.get_gst_report') }}",
				 type: 'post',
				 data: {
					
					_token :"{{csrf_token()}}",
					
					date:date,
					select_type: $('select[name=select_type]').val(),
					},
				dataType: "json",
					success:function(data, textStatus, jqXHR) {

					var transactions = data.transactions;
					
					datatable.destroy();
					$('#datatable tbody').empty();


					for (var i in transactions) {
						html += `<tr>
					<td>`+transactions[i].transaction_type+`</td>
					<td>`+transactions[i].id+`</td>
					<td class="rearrangedatetext">`+transactions[i].date+`</td>
					<td style="display: none;">`+transactions[i].billing_name+`</td>
					<td style="display: none;">Customer Billing GSTIN </td>
					<td style="display: none;">State Place of Supply </td>
					<td style="display: none;">`+transactions[i].reference_no+`</td>
					<td style="display: none;">`+transactions[i].item_no+`</td>
					<td style="display: none;">`+transactions[i].description+`</td>
					<td style="display: none;">`+transactions[i].category_type+`</td>
					<td>`+transactions[i].hsn+`</td>
					<td style="display: none;">`+transactions[i].quantity+`</td>
					<td style="display: none;">`+transactions[i].unit+`</td>
					<td style="display: none;">`+transactions[i].rate+`</td>
					<td style="display: none;">`+transactions[i].is_discount_percent+`</td>
					<td style="display: none;">`+transactions[i].discount_amount+`</td> 
					<td style="display: none;">`+transactions[i].amount+`</td>
					<td style="display: none;">Item Taxable Value</td>
					<td>`+transactions[i].gst+`</td>
					<td>`+transactions[i].cgst+`</td>
					<td>`+transactions[i].sgst+`</td>
					<td>`+transactions[i].igst+`</td>
					<td>`+transactions[i].cgst_amount+`</td>
					<td>`+transactions[i].sgst_amount+`</td>
					<td>`+transactions[i].igst_amount+`</td><td style="display: none;">Cess Rate</td>
					<td style="display: none;">Cess Amount</td>
					<td style="display: none;"> Totals</td>
					<td>`+transactions[i].total+`</td>
					<td style="display: none;">Type of Export</td>
					<td style="display: none;">Shipping Port Code - Export</td>
					<td style="display: none;">Shipping Bill Number - Export</td>
					<td style="display: none;">`+transactions[i].shipping_date+`</td>
					<td style="display: none;">Is this a Bill of Supply</td>
					<td style="display: none;">Is Reverse Charge Applicable?</td>
					<td style="display: none;">Is this a Nil Rated/Exempt/NonGST item?</td>
					<td style="display: none;">`+transactions[i].billing_address+`</td>
					<td style="display: none;">`+transactions[i].billing_address+`</td>
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




					$('#datatable tbody').append(html);

					datatable =  $('#datatable').DataTable({ 
		dom: 'B',
		buttons: [ { extend: 'csv', filename: 'Data export', 'title': '', exportOptions: { columns: ":not(.noExport)" },  footer: false } ]


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