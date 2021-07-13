@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<style>
.dashboard_container{
	height:400px !important;
}
.ex1{
	overflow-y:scroll;
	overflow-x:visible;
	height:100%;
}
</style>
@stop
@include('includes.trade_wms')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header" style="height:45px;width: 102%;background-color: #e3e3e9;margin-left: -10px;margin-bottom: 20px;">
	<div class="row" style="padding-top: 5px;">
		<div  style="margin-left: 40px;">
  		<h5 class="float-left page-title"><b> Job Status Dashboard</b></h5>
  		</div>
	  	
	</div>
</div>




<div class="clearfix"></div>

	<div class="row">
		   	@if(isset($box1)) 
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{ route('Jobstatus.index') }}">
				<div class="dashboard-stat" style="background: #5cb85c;">
					<div class="visual">
						<i class="fa fa-users"></i>
					</div>
					<div class="details">						
							<div class="number">								
								<span class="counter"><?php echo $box1->box1_value; ?></span>								
							</div>						
						<div class="desc"> New,First Inspected,Estimation Pending </div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
				</a>
			</div>
			@endif  
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{ route('Jobstatus.index') }}">
				<div class="dashboard-stat" style="background: #3498db;">
					<div class="visual">
						<i class="fa fa-shopping-cart"></i>
					</div>
					<div class="details">
						<div class="number"> 
							<span> <i style="font-size: 28px;" ></i><?php echo $box2->box2_value; ?></span>
							<span data-counter="counterup" data-value="12,5"></span></div>
						<div class="desc">Estimation Approved,Work In Progress </div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{ route('Jobstatus.index') }}">
				<div class="dashboard-stat" style="background: #ff7800;">
					<div class="visual">
						<i class="fa fa-money"></i>
					</div>
					<div class="details">
						<div class="number">
                            <span> <i style="font-size: 28px;" ></i><?php echo $box3->box3_value; ?></span>
							<span data-counter="counterup" data-value="549"></span>
						</div>
						<div class="desc"> Final Inspected,Vehicle Ready </div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="{{ route('transaction.index', ['job_card']) }}">
				<div class="dashboard-stat" style="background: #5483b6;">
					<div class="visual">
						<i class="fa fa-envelope-o"></i>
					</div>
					<div class="details">
						<div class="number">
							<span> <i style="font-size: 28px;" ></i><?php echo $box4->box4_value; ?></span>
							<span data-counter="counterup" data-value="12,5"></span> </div>
						<div class="desc">Closed</div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
				</a>
			</div>	
	</div>
 
<div class="row">
  	<div class="col-md-6">
		<div class="dashboard_container">
	  		<div class="title_container">
	  			<a href="{{ route('Jobstatus.index') }}">
				<h5 style="color: red;font-weight: bold;">JobCard Status</h5>
				</a>
	  		</div>
	  		<div id="piechart_3d""></div>
		</div>
  	</div>
  	 <div class="col-md-6">
		<div class="dashboard_container">
	  		<div class="title_container">
	  			<a href="{{ route('Jobstatus.index') }}">
				<h5 style="color: red;font-weight: bold;">On Hold Items And Services</h5>
				</a>
	  		</div>
	  		<div class="ex1"> 
	  			<table id="example" class="table data_table" width="100%" cellspacing="0">
				    <thead>
				      <tr>
				       <th>Vehicle Name</th>
				       <th>Job/Spare</th>
				       <th>Assigned To</th>
				     </tr>
				   	</thead>
				    @if(isset($tables))
				   	@foreach($tables as $table)
				   	<tbody>
					   <td>{{$table->registration_no}}</td>
					   <td>{{$table->item}}</td>
					   <td>{{$table->assigned_to}}</td>
				  	</tbody>
				   	  @endforeach
				  	@endif
				</table>
			</div>
		</div> 
  	</div> 
</div> 
 <div class="row">
	<div class="col-md-6">
		<div class="dashboard_container" >
	  		<div class="title_container">
	  			<a href="{{ route('Jobstatus.index') }}">
				<h5 style="color: red;font-weight: bold;">Vehicle and its Item Status</h5>
				</a>
	  		</div>
	  		<div id="chart"></div>
		</div>
  	</div>
  	<div class="col-md-6">
		<div class="dashboard_container">
	  		<div class="title_container">
	  			<a href="{{ route('Jobstatus.index') }}">
				<h5 style="color: red;font-weight: bold;">Users and their Works</h5>
				</a>
	  		</div>
	  		<div id="peoples"></div>
		</div> 
  	</div> 
</div> 
</div> 
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
      google.charts.load('42', {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawVisualization);
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(Visualization);

       function drawChart() {
        var data = google.visualization.arrayToDataTable({!! $pie_chart_value !!});
        var options = {
          chartArea: {width: 400, height: 300},
          is3D: true,
          pieSliceText: 'value'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }


function drawVisualization() {
  // Create and populate the data table.
  var data = google.visualization.arrayToDataTable({!! $bar_chart_value !!});

  // Create and draw the visualization.
  new google.visualization.BarChart(document.getElementById('peoples')).
      draw(data,
           {
           	colors: ["#ff9933","#33cc33","#ff3300","#FFFF00"],
            width:500, height:300,
            vAxis: {title: "Employee"}, isStacked: true,
            hAxis: {title: "No of works/Spares"}}
      );
}


google.setOnLoadCallback(drawVisualization);


function Visualization() {
  // Create and populate the data table.
  var data = google.visualization.arrayToDataTable({!! $bar_chart_value2 !!});

  // Create and draw the visualization.
  new google.visualization.BarChart(document.getElementById('chart')).
      draw(data,
           {
           	colors: ["#ff9933","#33cc33","#ff3300","#FFFF00"],
            width:500, height:300,
            vAxis: {title: "Vehicle"}, isStacked: true,
            hAxis: {title: "No of works/Spares"}}
      );
}


google.setOnLoadCallback(Visualization);
    </script>
@stop