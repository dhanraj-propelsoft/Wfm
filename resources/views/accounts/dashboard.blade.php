@extends('layouts.master')
@section('head_links') @parent
<style>


	#income_expense, #top_expenses {
		width:600px;
		height: 300px;
	}
tr td
  {
    font-weight: bold;
  }
</style>
@stop
@include('includes.accounts')
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
 <!--  <div class="col-md-6">
 <div class="dashboard_container">
     <div class="title_container">
     <h5>Incomes vs Expenses</h5>
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
 </div> -->
 <div class="col-md-6">
  <div class="dashboard_container">
  <div class="title_container">
    <div class="row">
      <div class="float-left" style="margin-left: 20px;">
        <h5>Incomes vs Expenses</h5>
      </div>
      <div class="float-right" >
        <div class="row" style="margin-left: 100px;">
          {{ Form::text('from_date',$from_date1,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy','style' => 'width:150px;border-radius:4px 4px 4px 4px;width:100px;height:25px;','placeholder' => 'From']) }}
          {{ Form::text('to_date',$to_date1,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy','style' => 'width:150px;border-radius:4px 4px 4px 4px;width:100px;height:25px;','placeholder' => 'To']) }}
          <button style=" height:25px;margin-left: 15px; border-radius: 3px 3px 3px 3px" type="submit" class="date btn btn-success search"><i class="fa fa-search" ></i></button>
        </div>
      </div>
    </div>
  </div>
  <div class="" id="to">
    <table class="table table-hover table-striped table-bordered inco">
      <h6> INCOME </h6>
      @foreach($total_incomes as $total_income)
      <tr  id="in">
        <td>{{ $total_income->group_name }}</td>
        <td>{{ $total_income->ledger_name }}</td>
        <td style="text-align: right;">{{ $total_income->total_income }}</td>
        
      </tr>
      @endforeach
      <tr>
        <td colspan="2" style="text-align: right;">Total</td>
        <td style="text-align: right;">{{ $sum_of_income }}</td>
      </tr>

    </table>
    <table class="table table-hover table-striped table-bordered expen">
      <h6> EXPENSE </h6>
      @foreach($total_expensess as $total_expense)
      <tr  id="ex">
        <td>{{ $total_expense->group_name }}</td>
        <td>{{ $total_expense->ledger_name }}</td>
        <td style="text-align: right;">{{ $total_expense->total_expense }}</td>
        
      </tr>
      @endforeach
      <tr>
        <td colspan="2" style="text-align: right;">Total</td>
        <td style="text-align: right;">{{ $sum_of_expense }}</td>
      </tr>

       <tr>
        <td colspan="2" style="text-align: right;">Difference</td>
        <td style="text-align: right;">{{  $sum_of_income - $sum_of_expense }}</td>
      </tr>
    </table>

  </div>
  </div>
  </div>
  <div class="col-md-6">
  <div class="dashboard_container">
	<div class="title_container">
	<h5>Top Expenses <span style="font-size: 12px">Last 12 months</span></h5>
	<!-- <div class="dashboard_option_container">
	  <div class="dashboard_option_action">This month <i class="fa fa-caret-down "></i> </div>
	  <ul class="dashboard_option_list">
	  <li><a class="multidelete">This month</a></li>
	  <li><a class="multitime">This financial quarter</a></li>
	  <li><a class="multitime">This financial year</a></li>
	  <li><a class="multidelete">Last month</a></li>
	  <li><a class="multitime">Last financial quarter</a></li>
	  <li><a class="multitime">Last financial year</a></li>
	  </ul>
	</div> -->
	</div>
	<div id="top_expenses"></div>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 



<script type="text/javascript">
	$(document).ready(function() {

		  google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
        
      function drawChart() {
        var data = google.visualization.arrayToDataTable({!! $ff !!});

        var options = {
        
                series: {
                    0: { targetAxisIndex: 0, },
                    1: { targetAxisIndex: 0, }
                },
                vAxes: {
                    0: { textPosition: 'none' , axisTitlesPosition : 'none'},
                    1: {}
                },
        };

        var chart = new google.charts.Bar(document.getElementById('top_expenses'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
        //chart.draw(data, options);
      }

       $('.search').on('click',function(e){
        //alert();
        e.preventDefault();
        var html='';
        var html1='';
        var html2='';
        var html3='';
        var html4='';
       
        $.ajax({
          type: 'post',
          url : '{{ route('account_dashboard_search') }}',
          data: 
          {
            _token : '{{ csrf_token() }}',
            from_date :$('input[name=from_date]').val(),
            to_date : $('input[name=to_date]').val()
          },
          success:function(data)
          {
            
            var total_expenses = data.total_expensess;
            var total_incomes = data.total_incomes;
            var diff = data.sum_of_income - data.sum_of_expense;
           
             $('#to').empty();
            for(var i in total_incomes){
           
                html2 +=`<tr>
                          <td>`+total_incomes[i].group_name+`</td>
                          <td>`+total_incomes[i].ledger_name+`</td>
                          <td style="text-align: right;">`+total_incomes[i].total_income+`</td>
                          </tr>
                          `;


            }
            html2 += `<tr>
                      <td colspan="2" style="text-align: right;">Total</td>
                      <td style="text-align: right;">`+data.sum_of_income+`</td>
                      </tr>`;
                      html3 =`<table class="table table-hover table-striped table-bordered inco"><h6> INCOME </h6>`+html2+`</table>`;
                     

           
            $('#to').html(html3);

           
            for(var i in total_expenses){
            
                html1 +=`<tr>
                          <td>`+total_expenses[i].group_name+`</td>
                          <td>`+total_expenses[i].ledger_name+`</td>
                          <td style="text-align: right;">`+total_expenses[i].total_expense+`</td>
                          </tr>
                          `;

            }
           
            html1 += `<tr>
                      <td colspan="2" style="text-align: right;">Total</td>
                      <td style="text-align: right;">`+data.sum_of_expense+`</td>
                      </tr>`;
           
            
            html1 += `<tr>
                      <td colspan="2" style="text-align: right;">Difference</td>
                      <td style="text-align: right;">`+diff+`</td>
                      </tr>`;
                      html4 =`<table class="table table-hover table-striped table-bordered inco"> <h6> EXPENSE </h6>`+html1+`</table>`;
                     
            $('#to').append(html4);
                     
           
           
          },
          error:function()
          {

          }


        });


      });

	

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
			legend: {
				show: false
			},
			colors: ["#90C843", "#FF7976"],
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


		$.plot($("#top_expenseses"), [{
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
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}

</script> 
@stop