<div>	
	<div class="clearfix"></div>
	<div class="row">
		<a href="{{ route('contact.index', ['vendor']) }}">

		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat " style="background: #2991D8;">
					<div class="visual">
						<i class="fa fa-users"></i>
					</div>
				<div class="details">
					<div class="number">
						<span class="counter" style="color:white;">{{$total_supplier}}</span>
					</div>
					<div class="desc" >
						<span style="color:white;"> Suppliers</span>
					</div>
					
				</div>
				<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
			</div>
		</div>
		</a>

		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat" style="background: #E7505A;">
				<div class="visual">
					<i class="fa fa-list-ul"></i>
				</div>
				<div class="details">
					<div class="number">
						<span data-counter="counterup" data-value="12,5">{{$total_products}}</span></div>
					<div class="desc"> Total Products </div>
				</div>
				<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
			</div>
		</div>

		<a href="{{ route('transaction.index', ['purchases']) }}">

		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat" style="background: #2DBBC8;">
				<div class="visual">
					<i class="fa fa-shopping-cart"></i>
				</div>
				<div class="details">
					<div class="number">
						<i style="font-size: 28px;" class="fa fa-inr"></i>
						<span data-counter="counterup" data-value="549">{{number_format($total_purchases, 2)}}</span>
					</div>
					<div class="desc"> Total Purchases </div>
				</div>
				<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
			</div>
		</div>
		</a>

		<a href="{{ route('cash_transaction.index', ['payment']) }}">
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat" style="background: #8E44AD;">
				<div class="visual">
					<i class="fa fa-money"></i>
				</div>
				<div class="details">
					<div class="number">
						<i style="font-size: 28px;" class="fa fa-inr"></i>
						<span data-counter="counterup" data-value="89">{{number_format($total_payables, 2)}}</span></div>
					<div class="desc"> Total Payables </div>
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
					<a href="{{ route('contact.index', ['vendor']) }}">
						<h5 style="color:black;">Top 15 Suppliers</h5>
					</a>
				</div>
				<div id="suppliers"></div>
			</div>
		</div>
	  	<div class="col-md-6">
			<div class="dashboard_container">
			  	<div class="title_container">
			  		<a href="{{ route('low_stock_report.index') }}">
						<h5 style="color:black;">Low Stock </h5>
					</a>
			  	</div>
			  	<div id="notifications">
				  	<ul class="feeds">
						<li>
							<div class="col1">
								<div class="cont">
								<div class="cont-col1">
										<div style="background: #ead941;" class="label label-sm label-success">
											<i class="fa fa-bell-o"></i>
										</div>
									</div>
									<div class="cont-col2">
										<div class="desc"> You have {{$low_stock_count}} Low Stock Item.<br>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
					<table class="table" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>Item Name</th>
								<th>In Stock</th>
							</tr>
						</thead>
						<tbody>
							@foreach($low_stocks as $stock)
								<tr>
									<td>{{ $stock->name }}</td>
									<td>{{ $stock->low_stock }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
	 	</div>
	</div>
</div>

<script>
	function load_map()
	{

	datatable = $('#datatable').DataTable();

		//$('.counter').counterUp();

		if ($(window).width() < 770) {
           $('#suppliers, #notifications').css('width', '200px');
        }else if ($(window).width() < 900) {
           $('#suppliers, #notifications').css('width', '220px');
        }else if ($(window).width() < 1030) {
           $('#suppliers, #notifications').css('width', '330px');
        }else if ($(window).width() < 1090) {
           $('#consumers, #notifications').css('width', '340px');
        }else if ($(window).width() < 1200) {
           $('#suppliers, #notifications').css('width', '390px');
        }else if ($(window).width() < 1300) {
           $('#suppliers, #notifications').css('width', '400px');
        }else if ($(window).width() < 1450) {
           $('#suppliers, #notifications').css('width', '525px');
        }else if ($(window).width() < 1690) {
           $('#suppliers, #notifications').css('width', '600px');
        }else if ($(window).width() < 1920) {
           $('#suppliers, #notifications').css('width', '750px');
        }else if ($(window).width() < 2570) {
           $('#suppliers, #notifications').css('width', '1080px');
        }

        var previousPoint2 = null;


// ******  Top 15 Suppliers Bar Chart Begins  *******
    
    var data = <?php echo $customers_total_value; ?>;    
    var dataset = [{ label: "Top 15 Suppliers", data: data, color: "#5482FF" }];
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
            $.plot($("#suppliers"), dataset, options);
            $("#suppliers").UseTooltip();
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
                        if($(this).attr("id") == "suppliers"){
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

		/*var income_expense_data = [{ label: "Electricity",  data: 60}, { label: "Internet",  data: 20}, { label: "Beverages",  data: 60}, { label: "Salary",  data: 20},{ label: "Travelling",  data: 60}, { label: "Expense",  data: 20}, { label: "Tax",  data: 60}, { label: "Maintenance",  data: 20}, { label: "Allowance",  data: 60}, { label: "Misc",  data: 20}];*/

		$.plot('#suppliers', data, {
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

		}

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + Math.round(series.percent) + "%</div>";
	}
</script>