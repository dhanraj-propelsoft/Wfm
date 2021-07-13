@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@section('head_links') @parent
<style>



	#income_expense, #regisration_no, #income_percentage {
		width: 480px;
		height: 300px;
	}

</style>
@stop
@include('includes.vehicle_management')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif

<div class="fill header">
  <h4 class="float-left page-title">VMS Dashboard</h4>
</div>
<div class="clearfix"></div>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat" style="background: #2991D8;">
			<div class="visual">
				<i class="fa fa-truck"></i>
			</div>
			<div class="details">
				<div class="number">
					<span class="counter"> {{ $vehicle_count }} </span>
				</div>
				<div class="desc"> Total Vehicle </div>
			</div>
			<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat" style="background: #0a932a;">
			<div class="visual">
				<i class="fa fa-money"></i>
			</div>
			<div class="details">
				<div class="number">
					<span> <i style="font-size: 28px;" class="fa fa-inr"></i></span>
					<span data-counter="counterup" data-value="12,5"> {{number_format(0, 2)}} </span></div>
				<div class="desc"> Total Income </div>
			</div>
			<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat" style="background: #E7505A;">
			<div class="visual">
				<i class="fa fa-money"></i>
			</div>
			<div class="details">
				<div class="number">
					<span> <i style="font-size: 28px;" class="fa fa-inr"></i></span>
					<span data-counter="counterup" data-value="12,5"> {{number_format(0, 2)}} </span></div>
				<div class="desc"> Total Expense </div>
			</div>
			<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat" style="background: #e85c25;">
			<div class="visual">
				<i class="fa fa-money"></i>
			</div>
			<div class="details">
				<div class="number">
					<span> <i style="font-size: 28px;" class="fa fa-inr"></i></span>
					<span data-counter="counterup" data-value="12,5"> {{number_format(0, 2)}} </span></div>
				<div class="desc"> Total Profit </div>
			</div>
			<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
		</div>
	</div>
</div>
<div class="row">
  <div class="col-md-6">
  <div class="dashboard_container">
	<div class="title_container">
	<h5>Income vs Expense</h5>
	</div>
	<div id="income_expense"></div>
  </div>
  </div>
  <div class="col-md-6">
	<div class="dashboard_container">
	  <div class="title_container">
		<h5>Registration Number</h5>
	  </div>
	  	<div id="regisration_no">		
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Register No</th>
						<th>Owner Name</th>
						<th>Vehicle Name</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vehicles_registers as $vehicle)
					<tr>
						<td><i class="fa fa-crosshairs"></i></td>
						<td>{{ $vehicle['registration_no'] }}</td>
						<td>{{ $vehicle['owner_name'] }}</td>
						<td>{{ $vehicle['vehicle_name'] }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
	  	</div>
	</div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
  <div class="dashboard_container">
	<div class="title_container">
	<h5>Income Percentage</h5>
	</div>
	<div id="income_percentage"></div>
  </div>
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.pie.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.resize.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.categories.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.waypoints.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.counterup.min.js') }}"></script> 
<script type="text/javascript">
	$(document).ready(function() {

		//$('.counter').counterUp();

		var income_expense_data = [{ label: "Income",  data: 60}, { label: "Expense",  data: 20}];

		$.plot('#income_expense', income_expense_data, {
			series: {
				pie: {
					show: true,
					radius: 1,
					innerRadius: 0.5,
					label: {
						show: true,
						radius: 3/4,
						formatter: labelFormatter,
						background: {
							opacity: 0.5
						}
					}
				}
			},
		});

		var visitors = [
				['02/2013', 1500],
				['03/2013', 2500],
				['04/2013', 1700],
				['05/2013', 800],
				['06/2013', 1500],
				['07/2013', 2350],
				['08/2013', 1500],
				['09/2013', 1300],
				['10/2013', 4600]
			];

		$.plot($("#income_percentage"), [{
						data: visitors,
						lines: {
							fill: 0.6,
							lineWidth: 0
						},
						color: ['#f89f9f']
					}, {
						data: visitors,
						points: {
							show: true,
							fill: true,
							radius: 5,
							fillColor: "#f89f9f",
							lineWidth: 3
						},
						color: '#fff',
						shadowSize: 0
					}],

					{
						xaxis: {
							tickLength: 0,
							tickDecimals: 0,
							mode: "categories",
							min: 0,
							font: {
								lineHeight: 14,
								style: "normal",
								variant: "small-caps",
								color: "#6F7B8A"
							}
						},
						yaxis: {
							ticks: 5,
							tickDecimals: 0,
							tickColor: "#eee",
							font: {
								lineHeight: 14,
								style: "normal",
								variant: "small-caps",
								color: "#6F7B8A"
							}
						},
						grid: {
							hoverable: true,
							clickable: true,
							tickColor: "#eee",
							borderColor: "#eee",
							borderWidth: 1
						}
					});

	});

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + Math.round(series.percent) + "%</div>";
	}

</script> 
@stop