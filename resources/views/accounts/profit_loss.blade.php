@extends('layouts.master')
@section('head_links') @parent
@if(app()->environment() == "production")
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.13/daterangepicker.min.css">
<style>

</style>
@elseif(app()->environment() == "local")
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<style>


</style>
@endif
@stop
@include('includes.accounts')
@section('content')


<button class="btn btn-primary float-right pdf_generation button">Generate PDF</button>

<div class="generate_pdf" >

	<h6 style="text-align: center;">{{$branch}}</h6>
	<h3 style="text-align: center;">Incomes and Expenses</h3>
	<div id="date_range" style="text-align: center; margin: 0 auto; display: block; width: 250px;" class="tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change date range"> <i class="icon-calendar"></i>&nbsp; <span class="thin uppercase hidden-xs"></span>&nbsp; <i class="fa fa-angle-down"></i> </div>


<div style="display:none" class="text-center no_data">There are no transactions between the selected period.</div>

<div class="row transaction_table">

<div class="col-md-12 ">
	<div class="row">
		<div class="col-md-6">
		  <h3 style="width:100%; padding:10px;" class="text-center">Expense</h3>
		  <div class="expense_container"></div>
		  <div class="expense_result">
			<div style="width:50%;float:left;"> </div>
			<div style="width:50%;float:left;"> <span class="removeSign negativeSign profit"> </span> <span class="negativeSign loss"> </span> </div>
		  </div>
		</div>
		<div class="col-md-6">
		  <h3 style="width:100%; padding:10px;" class="text-center">Income</h3>
		  <div class="income_container"></div>
		  <div class="income_result">
			<div style="width:50%;float:left;"> </div>
			<div style="width:50%;float:left;"> <span class="removeSign negativeSign profit"> </span> <span class="negativeSign loss"> </span> </div>
		  </div>
		</div>
	  </div>
  	<div class="col-md-12" style="font-size:14px;">
	<div class="row">
	  <div class="col-md-6" style="border-top:1px solid #F5F5F5; padding:10px 0;">
		<div style="width:48%;float:left; margin-left:2%">Total</div>
		<div style="width:50%;float:left;text-align:right"> <span class="removeSign negativeSign total_income"></span> </div>
	  </div>
	  <div class="col-md-6" style="border-top:1px solid #F5F5F5; padding:10px 0;">
		<div style="width:46%;float:left; margin-left:4%">Total</div>
		<div style="width:50%;float:left;text-align:right"> <span class="removeSign negativeSign total_expense"></span> </div>
	  </div>
	</div>
  	</div>

</div>

@stop

@section('dom_links')
@parent 
@if(app()->environment() == "production")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.13/daterangepicker.min.js" type="text/javascript"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
@elseif(app()->environment() == "local")
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
@endif
<script type="text/javascript">

	$(document).ready(function() {

		$('.pdf_generation').click(function () {

			var doc = new jsPDF('p', 'pt', 'a4');
        	doc.internal.scaleFactor = 1.80;

            doc.addHTML($('.generate_pdf'), 0, 0, {
            'background': '#fff',
            'border':'2px solid gray',
            pagesplit: true
             
	        }, function() {
	            doc.save('Income-Expense.pdf');
	        });

		});


	var start = moment(fiscal_year, "DD-MM-YYYY");
	var end = moment();
	var this_quarter_start = "";
	var this_quarter_end = "";

	var prev_quarter_start = "";
	var prev_quarter_end = "";

	if(moment().month() == 0 || moment().month() == 1 || moment().month() == 2) {

		this_quarter_start = "01 01 "+moment().year();
		this_quarter_end = "03 31 "+moment().year();

		prev_quarter_start = "10 01 "+moment().subtract(1, 'year').format('YYYY');
		prev_quarter_end = "12 31 "+moment().subtract(1, 'year').format('YYYY');

	} else if(moment().month() == 3 || moment().month() == 4 || moment().month() == 5) {

		this_quarter_start = "04 01 "+moment().year();
		this_quarter_end = "06 30";

		prev_quarter_start = "01 01 "+moment().year();
		prev_quarter_end = "03 31 "+moment().year();

	} else if(moment().month() == 6 || moment().month() == 7 || moment().month() == 8) {

		this_quarter_start = "07 01 "+moment().year();
		this_quarter_end = "09 30 "+moment().year();

		prev_quarter_start = "04 01 "+moment().year();
		prev_quarter_end = "06 30 "+moment().year();

	} else if(moment().month() == 9 || moment().month() == 10 || moment().month() == 11) {

		this_quarter_start = "10 01 "+moment().year();
		this_quarter_end = "12 31 "+moment().year();

		prev_quarter_start = "07 01 "+moment().year();
		prev_quarter_end = "09 30 "+moment().year();

	}

		get_data(start, end);

		$('#date_range').daterangepicker({
		startDate: start,
		endDate: end,
		ranges: {
		   'Today': [moment(), moment()],
		   'This Week': [moment().startOf('week'), moment().endOf('week')],
		   'This Month': [moment().startOf('month'), moment().endOf('month')],
		   'This Quarter': [moment(this_quarter_start,"MM DD YYYY"), moment(this_quarter_end,"MM DD YYYY")],
		   'This Year': [moment().startOf('year'), moment().endOf('year')],
		   'This Financial Year': [moment(fiscal_year, "DD-MM-YYYY"), moment()],
		   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		   'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
		   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		   'Last Quarter': [moment(prev_quarter_start,"MM DD YYYY"), moment(prev_quarter_end,"MM DD YYYY")],
		   'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
		}
	}, function callback(start, end) {
		get_data(start, end);
	});


		function get_data(start, end) {
		

		$('.loader_wall').show();

		$.ajax({
			url: "{{ route('get_profit_and_loss') }}",
			type: 'post',
			data: {
				_token: $('input[name=_token]').val(),
				start_date: start.format('YYYY-MM-DD'),
				end_date: end.format('YYYY-MM-DD')
			},
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				$('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

				
				$('.expense_container').empty();
				$('.income_container').empty();

			   /* if(data.total_asset.length > 0 || data.total_liability.length > 0) {
					$('.transaction_table').show();
					$('.no_data').hide();
				} else {
					$('.transaction_table').hide();
					$('.no_data').show();
				}*/
					
				$('.expense_container').append(data.expense);

				$('.income_container').append(data.income);

				$('.expense_result, .income_result').html(data.statement.report);


				if(data.statement.report == "profit") {
				   $('.expense_result').css({'margin':'10px 0 '});
					$('.expense_result').html('<div style="width:50%;float:left;">Profit</div><div style="width:48%;float:left;text-align:right"><span class="removeSign">'+data.statement.report_amount+'</span></div>');
					$('.income_result').html('');
					$('.income_result').removeAttr('style');
				} else if(data.statement.report == "loss") {
					$('.income_result').css({'margin':'10px 0 '});
					$('.income_result').html('<div style="width:50%;float:left;">Loss</div><div style="width:48%;float:left;text-align:right"><span class="removeSign">'+data.statement.report_amount+'</span></div>');
					$('.expense_result').html('');
					$('.expense_result').removeAttr('style');
				}

				$('.profit').html(data.statement.report_amount);
				$('.loss').html(data.statement.report_amount);
				
				if(data.statement.report == "profit") {
					$('.total_income').html(parseFloat(data.statement.incomes));
					$('.total_expense').html(parseFloat(data.statement.expenses) + parseFloat(data.statement.report_amount));
				} else if(data.statement.report == "loss") {
					$('.total_income').html(parseFloat(data.statement.incomes) + parseFloat(data.statement.report_amount));
					$('.total_expense').html(parseFloat(data.statement.expenses)); 
				}


				$('.loader_wall').hide();
				removeSign();
				negativeSign();
				tree_list();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
		
	}

	});

	</script> 
@stop