@extends('layouts.master')
@section('head_links') @parent
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}" />
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.print.min.css') }}" media='print' />
<style>


  #income_expense, #notifications {
		width: 480px;
		height: 300px;
	}
 #leave_employees {
		width: 800px;
		height: 300px;
		margin: 0 auto;
	}
  #holidays {
	width: 480px;
	height: 400px;
  }
	#calendar {
	width: 480px;
	height: 400px;
  }
  .fc-event {
	border: 0px solid #3a87ad;
}
  .fc-event .fc-content {
	border: 0;
	padding: 5px 7px;
}
</style>
@stop
@include('includes.hrm')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Dashboard</h4>
</div>
	<div class="clearfix"></div>
		<div class="row">
		  	<div class="col-md-6">
			<div class="dashboard_container">
			  <div class="title_container">
				<h5>Team</h5>
				<div class="dashboard_option_container">
				  <div class="dashboard_option_action"> Today Date </div>
				</div>
			  </div>
			  <div id="income_expense"></div>
			</div>
		  </div>
		  <div class="col-md-6">
			<div class="dashboard_container">
			  <div class="title_container">
				<h5>Notifications</h5>
			  </div>
			  <div id="notifications">
			  	<ul class="feeds">
			  	@foreach($notifications as $notification)
					<li>
						<div class="col1">
							<div class="cont">
							<div class="cont-col1">
									<div class="label label-sm label-success">
										<i class="fa fa-bullhorn"></i>
									</div>
								</div>
								<div class="cont-col2">
									<div class="desc"> {{$notification['message']}}<br>
									<span style="padding: 2px 0px; float: left; font-size: 11px;">{{$notification['time']}}</span>
									</div>
								</div>
							</div>
						</div>
					</li>
				@endforeach
				</ul>
			</div>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col-md-12">
			<div class="dashboard_container">
			  <div class="title_container">
				<h5>Top 5 Leave Taking Employees</h5>
			  </div>
			  <div class="row">
					<div id="leave_employees"></div>						
				</div>
			 
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col-md-12">
			<div class="dashboard_container">
			  <div class="title_container">
				<h5>Job Openings</h5>
			  </div>
			  	<!-- <div class="row">
			  						<div class="col-md-3">
			  							<div class="easy-pie-chart">
			  								<div class="number transactions" data-percent="{{$designations_chart->departments}}">
			  									<span>{{$designations_chart->departments}}</span>% </div>
			  								<a class="title" href="javascript:;"> Department
			  									<i class="icon-arrow-right"></i>
			  								</a>
			  							</div>
			  						</div>
			  						<div class="margin-bottom-10 visible-sm"> </div>
			  						<div class="col-md-3">
			  							<div class="easy-pie-chart">
			  								<div class="number visits" data-percent="{{$designations_chart->designations}}">
			  									<span>{{$designations_chart->designations}}</span>% </div>
			  								<a class="title" href="javascript:;"> Designations
			  									<i class="icon-arrow-right"></i>
			  								</a>
			  							</div>
			  						</div>
			  						<div class="margin-bottom-10 visible-sm"> </div>
			  						<div class="col-md-3">
			  							<div class="easy-pie-chart">
			  								<div class="number bounce" data-percent="{{$designations_chart->filledposition}}">
			  									<span>{{$designations_chart->filledposition}}</span>% </div>
			  								<a class="title" href="javascript:;"> Filled Positions
			  									<i class="icon-arrow-right"></i>
			  								</a>
			  							</div>
			  						</div>
			  						<div class="margin-bottom-10 visible-sm"> </div>
			  						<div class="col-md-3">
			  							<div class="easy-pie-chart">
			  								<div class="number bounce" data-percent="{{$designations_chart->openings}}">
			  									<span>{{$designations_chart->openings}}</span>% </div>
			  								<a class="title" href="javascript:;"> Openings
			  									<i class="icon-arrow-right"></i>
			  								</a>
			  							</div>
			  						</div>
			  					</div> -->
			  <table class="table table-hover table-light" width="100%" cellspacing="0">
				<thead>
				  <tr class="uppercase">
					<th style="text-align: center;"> Department </th>
					<th style="text-align: center;"> Designation </th>
					<th style="text-align: center;"> Filled Postions </th>
					<th style="text-align: center;"> Openings </th>
				  </tr>
				</thead>
				<tbody>
				
				@foreach($designations as $designation)
				<tr>
				  <td style="text-align: center;" width="25%"><span>{{ $designation->department }}</span></td>
				  <td style="text-align: center;" width="25%"><span>{{ $designation->name }}</span></td>
				  <td style="text-align: center;" width="25%"><span>{{ $designation->filledposition }}</span></td>
				  <td style="text-align: center;" width="25%"><span class="text">{{ $designation->positions }}</span> {!! Form::text('positions',$designation->positions,['class'=>'form-control', 'style' => 'display:none']) !!} </td>
				</tr>
				@endforeach
				  </tbody>
				
			  </table>
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
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/jquery-easypiechart/jquery.easypiechart.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script> 
<script type="text/javascript">
	$(document).ready(function() {

		$('.feeds').slimScroll({
			height: '400'
		});

		var employees_leave_data = [ { label: "Top 5 Employees", data: {{$employees_leave_data}}, color: "#5482FF"} ];

		$.plot($("#leave_employees"), employees_leave_data, {
            series: {
                bars: {
                    show: true
                }
            },
            bars: {
                align: "center",
                barWidth: 0.5
            },
            xaxis: {
                axisLabel: "World Cities",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10,
                ticks: {!! $employees_data !!}
            },
            yaxis: {
                axisLabel: "Average Temperature",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 3
            },
            legend: {
                noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw"
            },
            grid: {
                hoverable: true,
                borderWidth: 0.1,
                backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
            }
        });

		var income_expense_data = {!! $teams !!};

		$.plot('#income_expense', income_expense_data, {
			series: {
				pie: {
					show: true,
					radius: 1,
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

		var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();

			var h = {};

			if ($('#calendar').width() <= 400) {
				$('#calendar').addClass("mobile");
				h = {
					left: 'title, prev, next',
					center: '',
					right: 'today'
				};
			} else {
				$('#calendar').removeClass("mobile");
				h = {
						left: 'title',
						center: '',
						right: 'prev,next,today'
					};
			}



			$('#calendar').fullCalendar('destroy'); // destroy the calendar
			$('#calendar').fullCalendar({ //re-initialize the calendar
				disableDragging: false,
				header: h,
				editable: true,
				events: [{
					title: 'All Day',
					start: new Date(y, m, 1),
					backgroundColor: '#F8CB00'
				}, {
					title: 'Long Event',
					start: new Date(y, m, d - 5),
					end: new Date(y, m, d - 2),
					backgroundColor: '#89C4F4'
				}, {
					title: 'Repeating Event',
					start: new Date(y, m, d - 3, 16, 0),
					allDay: false,
					backgroundColor: '#F3565D'
				}, {
					title: 'Repeating Event',
					start: new Date(y, m, d + 6, 16, 0),
					allDay: false,
					backgroundColor: '#1bbc9b'
				}, {
					title: 'Meeting',
					start: new Date(y, m, d + 9, 10, 30),
					allDay: false
				}, {
					title: 'Lunch',
					start: new Date(y, m, d, 14, 0),
					end: new Date(y, m, d, 14, 0),
					backgroundColor: '#95a5a6',
					allDay: false
				}, {
					title: 'Birthday',
					start: new Date(y, m, d + 1, 19, 0),
					end: new Date(y, m, d + 1, 22, 30),
					backgroundColor: '#9b59b6',
					allDay: false
				}, {
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					backgroundColor: 'yellow',
					url: 'http://google.com/'
				}]
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


		$.plot($("#top_expenses"), [{
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


		$('.easy-pie-chart .number.transactions').easyPieChart({
				animate: 1000,
				size: 75,
				lineWidth: 3,
				barColor: 'yellow'
			});

			$('.easy-pie-chart .number.visits').easyPieChart({
				animate: 1000,
				size: 75,
				lineWidth: 3,
				barColor: 'green'
			});

			$('.easy-pie-chart .number.bounce').easyPieChart({
				animate: 1000,
				size: 75,
				lineWidth: 3,
				barColor: 'red'
			});

	});

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center;  color:white;'>" + Math.round(series.percent) + "%</div>";
	}


</script> 
@stop