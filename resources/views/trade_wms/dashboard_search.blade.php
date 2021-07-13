<div>
	<div class="clearfix"></div>
		<div class="row">
			<a href="{{ route('contact.index', ['wms-customer']) }}">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat" style="background: #5cb85c;">
						<div class="visual">
							<i class="fa fa-users"></i>
						</div>
						<div class="details">						
								<div class="number">								
									<span class="counter">{{$count_customer}}</span>								
								</div>						
							<div class="desc"> Customers </div>
						</div>
						<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
					</div>
				</div>
	        </a>
	        <a href="{{ route('transaction.index', ['job_invoice']) }}">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat" style="background: #3498db;">
						<div class="visual">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<div class="details">
							<div class="number"> 
								<span> <i style="font-size: 28px;" class="fa fa-inr"></i></span>
								<span data-counter="counterup" data-value="12,5">{{number_format($total_sales->total_amount, 2)}}</span></div>
							<div class="desc"> Total Sales </div>
						</div>
						<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
					</div>
				</div>
	        </a>
	        <a href="{{ route('cash_transaction.index', ['wms_receipt']) }}">
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
	        </a>
	        <a href="{{ route('vehicle_registered.index') }}">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat" style="background: #5483b6;">
						<div class="visual">
							<i class="fa fa-envelope-o"></i>
						</div>
						<div class="details">
							<div class="number">
								<span data-counter="counterup" data-value="12,5">{{$vehicles_registers}} </span> </div>
							<!-- <div class="desc"> New Orders </div> -->
	                        <div class="desc"> Total Vehicles</div>

						</div>
						<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
					</div>
				</div>	
	        </a>	
		</div>
	 <div class="row">
	      <div class="col-md-6">
	        <div class="dashboard_container">
	              <div class="title_container">
	                <a href="{{ route('contact.index', ['wms-customer']) }}">
	                <h5 style="color:black;">Top 10 Consumers</h5>
	                </a>
	              </div>
	              <div id="consumers"></div>
	        </div>
	      </div>
	     <div class="col-md-6">
	        <div class="dashboard_container" style="height: 430px">
	              <div class="title_container">
	                <a href="{{ route('transaction.index', ['job_invoice']) }}">
	                <h5 style="color:black;">Monthly Sales by Goods and Service</h5>
	                </a>
	              </div>
	              <div id="sales" style="margin-top:-30px;margin-bottom: 10px;margin-left:-5px"></div>
	        </div>
	      </div>
	</div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    function drawVisualization() {
  // Create and populate the data table.
  var data = google.visualization.arrayToDataTable({!! $sale_value !!});

  // Create and draw the visualization.
  new google.visualization.ColumnChart(document.getElementById('sales')).
      draw(data,
           {
            width:450, height:350,
            vAxis: {title: "Amount"}, isStacked: true,
            hAxis: {title: "Months"}}
      );
}

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawVisualization);



	 function load_map()
    {
        if ($(window).width() < 770) {
           $('#consumers').css('width', '200px');
        }else if ($(window).width() < 900) {
           $('#consumers').css('width', '220px');
        }else if ($(window).width() < 1030) {
           $('#consumers').css('width', '330px');
        }else if ($(window).width() < 1090) {
           $('#consumers').css('width', '340px');
        }else if ($(window).width() < 1200) {
           $('#consumers').css('width', '390px');
        }else if ($(window).width() < 1300) {
           $('#consumers').css('width', '400px');
        }else if ($(window).width() < 1450) {
           $('#consumers').css('width', '525px');
        }else if ($(window).width() < 1690) {
           $('#consumers').css('width', '600px');
        }else if ($(window).width() < 1920) {
           $('#consumers').css('width', '750px');
        }else if ($(window).width() < 2570) {
           $('#consumers').css('width', '1080px');
        }
        

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

    }

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