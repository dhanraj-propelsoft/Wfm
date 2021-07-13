@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@if(Session::get('module_name') == "trade_wms")
	@include('includes.trade_wms')
@endif
@section('content')
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
<style>
	#header{
		height:35px;
		width: 102%;
		background-color: #e3e3e9;
		margin-left: -10px;
		margin-bottom: 20px;
	}
	#bg{
		background-color: #e4e7f5;
	}
</style>
<div class="fill header" id="header">
	<h4 class="float-left page-title">Today Summary</h4>

</div>
<div class="float-left" style="width: 100%; padding-top: 10px">
	<div>
		<center><h5>Summarised Transaction for Today {!!$today_date !!}</h5></center>
	</div>
	<div class="col-md-10" style="margin-top: 20px;margin-left: 90px;">
		<h5 style="font-weight: bold;">Purchase Report</h5>
		<table class="table">
			<thead >
						<tr >
							<th width="10%" id="bg"></th>
							<th width="10%" id="bg"></th>
							<th width="6%"  id="bg">Quantity</th>				
							<th width="10%" id="bg">Price+Tax</th>						
						</tr>
						</thead>
			<tbody>
				<tr>
					<td>Purchase</td>
					<td>Goods</td>
					<td>{!! $total_purchase_goods_quantity !!}</td>
					<td>{!! $total_purchase_goods_amount !!}</td>
				</tr>
				<tr>
					<td>Purchase</td>
					<td>Service</td>
					<td>{!! $total_purchase_service_quantity !!}</td>
					<td>{!! $total_purchase_service_amount !!}</td>
				</tr>
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				<td style="font-weight: bold;">Total</td>
				<td>{!! $total_purchase_goods_amount + $total_purchase_service_amount  !!}</td>
			</tfoot>
		</table>
	</div>

	<div class="col-md-10" style="margin-top: 20px;margin-left: 90px;">
		<h5 style="font-weight: bold;">Sales Report</h5>
		<table class="table">
			<thead >
						<tr >
							<th width="10%" id="bg"></th>
							<th width="10%" id="bg"></th>
							<th width="6%"  id="bg">Quantity</th>				
							<th width="10%" id="bg">Price+Tax</th>						
						</tr>
						</thead>
			<tbody>
				<tr>
					<td>Sales</td>
					<td>Goods</td>
					<td>{!! $total_sales_goods_quantity !!}</td>
					<td>{!! $total_sales_goods_amount !!}</td>
				</tr>
				<tr>
					<td>Sales</td>
					<td>Service</td>
					<td>{!! $total_sales_service_quantity !!}</td>
					<td>{!! $total_sales_service_amount !!}</td>
				</tr>
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				<td style="font-weight: bold;">Total</td>
				<td>{!! $total_sales_goods_amount + $total_sales_service_amount  !!}</td>
			</tfoot>
		</table>
	</div>

	
	<div class="col-md-10" style="margin-top: 20px;margin-left: 90px;">
		<h5 style="font-weight: bold;">Jobcard Report</h5>
		<table class="table table-bordered">
			<thead >
						<tr>
							<th width="10%" id="bg">Jobcard</th>
							<th width="10%" id="bg">New</th>
							<th width="6%"  id="bg">Progress</th>				
							<th width="10%" id="bg">Ready</th>
							<th width="10%" id="bg">Closed</th>						
						</tr>
						</thead>
			<tbody>
				<tr>
					<td>{!! $jobcard_status->new_jobcard + $jobcard_status->progress_jobcard+$jobcard_status->ready_jobcard+$jobcard_status->closed_jobcard   !!} </td>
					<td>{!! $jobcard_status->new_jobcard  !!}</td>
					<td>{!!  $jobcard_status->progress_jobcard !!}</td>
					<td>{!!  $jobcard_status->ready_jobcard  !!}</td>
					<td>{!!  $jobcard_status->closed_jobcard  !!}</td>
					
				</tr>
			</tbody>
		</table>
	</div>

	<div class="col-md-10" style="margin-top: 20px;margin-left: 90px;">
		<table class="table table-bordered">
			<thead >
						<tr>
							<th width="10%" id="bg">Type</th>
							<th width="10%" id="bg">Draft</th>
							<th width="6%"  id="bg">Approved</th>				
							<th width="10%" id="bg">Credit Bill</th>
							<th width="10%" id="bg">Credit Amount</th>	
							<th width="10%" id="bg">Total Pending</th>						
						</tr>
			</thead>
			<tbody>
				<tr>
					<td>Estimation</td>
					<td>{!! $estimations->draft !!}</td>
					<td>{!! $estimations->Approved !!}</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
				<tr>
					<td>Invoice</td>
					<td>{!! $invoices->draft !!}</td>
					<td>{!! $invoices->Approved !!}</td>
					<td>{!! $invoices->wms_credit + $invoices->trade_credit !!}</td>
					<td>{!! $invoice_credit_total !!}</td>
					<td>{!! $invoice_credit_pending->total !!}</td>
				</tr>
				<tr>
					<td>Purchase</td>
					<td>{!! $purchases->draft !!}</td>
					<td>{!! $purchases->Approved !!}</td>
					<td>{!! $purchases->credit_bill !!}</td>
					<td>{!! $purchase_credit_total !!}</td>
					<td>{!! $purchase_credit_pending->total !!}</td>
				</tr>
			</tbody>
		</table>
	</div>
<div class="col-md-4" style="margin-top: 20px;margin-left: 90px;">
<table class="table table-bordered">
	<thead >
		<tr>
			<th width="10%" id="bg">Cash Summary</th>
			<th width="10%" id="bg">Amount</th>						
		</tr>
			</thead>
			<tbody>
				<tr>
					<td>Receipt/WMS receipt</td>
					<td>{!! $wms_receipt_payment->wms_receipt !!}</td>
				</tr>
				<tr>
					<td>Payment</td>
					<td>{!! $wms_receipt_payment->payment !!}</td>
				</tr>
				@foreach($cash_icici as $cash_icici)
				<tr>
					<td>{{ $cash_icici->ledger_name }}</td>
					<td>{{ $cash_icici->closing_balance }}</td>
				</tr>
				@endforeach
			</tbody>
</table>
</div>
@stop
@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}">
	
</script>

<script type="text/javascript">

</script>
@stop