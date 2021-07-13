@extends('../layouts.master')
@section('head_links') @parent
<style>


  #consumers, #sales {
		width: 600px;
		height: 320px;
	}
}


</style>
@stop
@include('../includes.trade_wms')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>

<div class="fill header">
  <h4 class="float-left page-title">Dashboard</h4>
</div>
<div class="clearfix"></div>
	<div class="row">
		
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="dashboard-stat" style="background: #5cb85c;">
					<div class="visual">
						<i class="fa fa-users"></i>
					</div>
					<div class="details">						
							<div class="number">								
								<span class="counter">{{$total_customer}}</span>								
							</div>						
						<div class="desc"> Customers </div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="dashboard-stat" style="background: #3498db;">
					<div class="visual">
						<i class="fa fa-shopping-cart"></i>
					</div>
					<div class="details">
						<div class="number"> 
							<span> <i style="font-size: 28px;" class="fa fa-inr"></i></span>
							<span data-counter="counterup" data-value="12,5">{{number_format($total_sales, 2)}}</div>
						<div class="desc"> Total Sales </div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="dashboard-stat" style="background: #ff7800;">
					<div class="visual">
						<i class="fa fa-money"></i>
					</div>
					<div class="details">
						<div class="number">
                            <span> <i style="font-size: 28px;" class="fa fa-inr"></i></span>
							<span data-counter="counterup" data-value="549">{{number_format($total_receivables, 2)}}</span>
						</div>
						<div class="desc"> Total Receivables </div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="dashboard-stat" style="background: #5483b6;">
					<div class="visual">
						<i class="fa fa-envelope-o"></i>
					</div>
					<div class="details">
						<div class="number">
							<span data-counter="counterup" data-value="12,5">{{$new_order}} </span> </div>
						<div class="desc"> New Orders </div>
					</div>
					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
				</div>
			</div>		
	</div>
 <div class="row">
      <div class="col-md-6">
        <div class="dashboard_container">
              <div class="title_container">
                <h5>Top 10 Consumers</h5>
              </div>
              <div id="consumers"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="dashboard_container">
              <div class="title_container">
                <h5>Sales</h5>
              </div>
              <div id="sales"></div>
        </div>
      </div>
</div> 
<!-- <div class="row">
  <div class="col-md-12">
	<div class="dashboard_container">
	  <div class="title_container">
		<h5>Cash Flow</h5>
		<div class="dashboard_option_container">
		  <div class="dashboard_option_action">This month <i class="fa fa-caret-down "></i> </div>
		  <ul class="dashboard_option_list">
			<li><a class="multidelete">This month</a></li>
			<li><a class="multitime">This financial quarter</a></li>
			<li><a class="multitime">This financial year</a></li>
			<li><a class="multidelete">Last month</a></li>
			<li><a class="multitime">Last financial quarter</a></li>
			<li><a class="multitime">Last financial year</a></li>
		  </ul>
		</div>
	  </div>
	  <div id="income_expense"></div>
	</div>
  </div>
</div> -->
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.pie.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.resize.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.categories.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.waypoints.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.counterup.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/morris/morris.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/morris/raphael-min.js') }}"></script> 

<script type="text/javascript">
	$(document).ready(function() {

		if ($(window).width() < 770) {
           $('#consumers, #sales').css('width', '200px');
        }else if ($(window).width() < 900) {
           $('#consumers, #sales').css('width', '220px');
        }else if ($(window).width() < 1030) {
           $('#consumers, #sales').css('width', '330px');
        }else if ($(window).width() < 1090) {
           $('#consumers, #sales').css('width', '340px');
        }else if ($(window).width() < 1200) {
           $('#consumers, #sales').css('width', '390px');
        }else if ($(window).width() < 1300) {
           $('#consumers, #sales').css('width', '400px');
        }else if ($(window).width() < 1450) {
           $('#consumers, #sales').css('width', '525px');
        }else if ($(window).width() < 1690) {
           $('#consumers, #sales').css('width', '600px');
        }else if ($(window).width() < 1920) {
           $('#consumers, #sales').css('width', '750px');
        }else if ($(window).width() < 2570) {
           $('#consumers, #sales').css('width', '1080px');
        }
		
		
		var previousPoint2 = null;

		var data1 = <?php echo $sales_data; ?>;


		 	$.plot($("#sales"),

                    [{
                        data: data1,
                        lines: {
                            fill: 0.2,
                            lineWidth: 0,
                        },
                        color: ['#BAD9F5']
                    }, {
                        data: data1,
                        points: {
                            show: true,
                            fill: true,
                            radius: 4,
                            fillColor: "#9ACAE6",
                            lineWidth: 2
                        },
                        color: '#9ACAE6',
                        shadowSize: 1
                    }, {
                        data: data1,
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3
                        },
                        color: '#9ACAE6',
                        shadowSize: 0
                    }],

                    {
                        xaxis: {
                            tickLength: 0,
                            tickDecimals: 0,
                            mode: "categories",
                            min: 0,
                            font: {
                                lineHeight: 18,
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
                            },
                            tickFormatter: function (v, axis) {
                                return "Rs: " + v; }
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        }
                    });

		 		$("#sales").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint2 != item.dataIndex) {
                            previousPoint2 = item.dataIndex;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                            showChartTooltip(item.pageX, item.pageY, item.datapoint[0],  'Rs:'+ item.datapoint[1] );
                        }
                    }
                });

                $('#sales').bind("mouseleave", function() {
                    $("#tooltip").remove();
                });

	});

// ******    Bar Chart   *******
    
    var data = <?php echo $customers_total_value; ?>;
    var dataset = [{ label: "Top 10 Consumers", data: data, color: "#5482FF" }];
    var ticks = <?php echo $customers_names; ?>;
 
        var options = {
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
                axisLabel: "Company Names",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10,
                ticks: ticks
            },
            yaxis: {
                axisLabel: "Total Values",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 3,
                tickFormatter: function (v, axis) {
                    return "Rs: " + v;
                }
            },
            legend: {
                noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw"
            },
            grid: {
                hoverable: true,
                borderWidth: 2,
                backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
            }
        };

        $(document).ready(function () {
            $.plot($("#consumers"), dataset, options);
            $("#consumers, #sales").UseTooltip();
        });
 
        function gd(year, month, day) {
            return new Date(year, month, day).getTime();
        }
 
        var previousPoint = null, previousLabel = null;
 
        $.fn.UseTooltip = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
 
                        //console.log(item.series.xaxis.ticks[x].label);
                        if($(this).attr("id") == "consumers"){
                            showTooltip(item.pageX,
                            item.pageY,
                            color,
                            "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong> Rs " + y );
                        } else if($(this).attr("id") == "sales"){
                            showTooltip(item.pageX,
                            item.pageY,
                            color,
                            "Sales of " + item.series.xaxis.ticks[x].label + "<br><strong> Rs: " + y +"</strong>" );
                        }
                        
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };
 
        function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }

// ******    Bar chart ends    *******

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + Math.round(series.percent) + "%</div>";
	}

	function showChartTooltip(x, y, xValue, yValue) {
            $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 40,
                border: '0px solid #ccc',
                padding: '2px 6px',
                'background-color': '#fff'
            }).appendTo("body").fadeIn(200);
        }

</script> 
@stop