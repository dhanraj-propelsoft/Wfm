@extends('layouts.master')
@section('head_links') @parent
@if(app()->environment() == "production")
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.13/daterangepicker.min.css">
@elseif(app()->environment() == "local")
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}">
@endif
@stop
@include('includes.accounts')
@section('content')
<h6 style="text-align: center;">{{$branch}}</h6>
<h3 style="text-align: center;">Journal Report</h3>
<div id="date_range" style="text-align: center; margin: 0 auto; display: block; width: 250px;" class="tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change date range">
                                <i class="icon-calendar"></i>&nbsp;
                                <span class="thin uppercase hidden-xs"></span>&nbsp;
                                <i class="fa fa-angle-down"></i>
                            </div>
<div style="display:none" class="text-center no_data">There are no transactions between the selected period.</div>
<table class="transaction_table table table_empty table-striped table-hover">
  <thead>
    <tr>
      <th> Account </th>
      <th> Debit </th>
      <th> Credit </th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
@stop

@section('dom_links')
@parent 
@if(app()->environment() == "production")
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.13/daterangepicker.min.js" type="text/javascript"></script> 
@elseif(app()->environment() == "local")
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
@endif
<script type="text/javascript">

	$(document).ready(function() {

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
            url: "{{ route('get_journal_report') }}",
            type: 'post',
            data: {
                _token: $('input[name=_token]').val(),
                start_date: start.format('YYYY-MM-DD'),
                end_date: end.format('YYYY-MM-DD')
            },
            dataType: "json",
            success: function(res, textStatus, jqXHR) {
                $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('.transaction_table').empty();
                if(res.length > 0) {
                    $('.transaction_table').show();
                    $('.no_data').hide();
                } else {
                    $('.transaction_table').hide();
                    $('.no_data').show();
                }
                for(var i in res) {

                    var table = '<table class="table table_empty table-striped table-hover"><thead><tr> \
 <th width="33%"><span class="rearrangedatetext">'+res[i].date+'</span>  &nbsp;&nbsp;&nbsp; '+res[i].voucher_no+'</th> \
<th width="33%">Debit</th><th width="33%">Credit</th></tr></thead><tbody>';

for(var journal in res[i].data) {

table += '<tr> \
                            <td>'+res[i].data[journal].account+'</td> \
                            <td>'+res[i].data[journal].debit+'</td> \
                            <td>'+res[i].data[journal].credit+'</td> \
                        </tr>';
                        

                        }

table += '</tbody><tr style="background:#F7F5F3"><td></td> \
<td class="removeSign">'+res[i].debit+'</td> \
<td class="removeSign">'+res[i].credit+'</td> \
</tr></table><br>';



                    $('.transaction_table').append(table);
                }

                $('.loader_wall').hide();
                removeSign();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //alert("New Request Failed " +textStatus);
            }
        });
	}

	});

	</script> 
@stop