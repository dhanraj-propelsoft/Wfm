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
<button class="btn btn-primary float-right pdf_generation button">Generate PDF</button>

<div class='generate_pdf_title' style="float: left; margin-left: 40%; "> 

    <h6 style="text-align: center;">{{$branch}}</h6>
    <h3 style="text-align: center;">Trial Balance</h3>
    <div id="date_range" style="text-align: center; margin: 0 auto; display: block; width: 250px;" class="tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change date range">
            <i class="icon-calendar"></i>&nbsp;
            <span class="thin uppercase hidden-xs"></span>&nbsp;
            <i class="fa fa-angle-down"></i>
        </div>
</div>

<div style="display:none" class="text-center no_data">There are no transactions between the selected period.</div>

    <table class="transaction_table table table_empty table-striped table-hover generate_pdf">
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
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.autotable.min.js') }}"></script>

@elseif(app()->environment() == "local")
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.autotable.min.js') }}"></script>

@endif
<script type="text/javascript">

    $(document).ready(function() {

        $('.pdf_generation').click(function () {
            /*Table format - pdf , Automatically splitted in multiple pages*/
            $('.loader_wall_onspot').show();
            var pdf = new jsPDF('p', 'pt', 'a4');
            var html=$(".generate_pdf_title").html();

            pdf.fromHTML(html,250,0, {
           
            }); 
            
            pdf.autoTable({html: '.generate_pdf',margin: {top: 80}});
            pdf.save('Trial-Balance.pdf');

            $('.loader_wall_onspot').hide();
            /*End*/
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
            url: "{{ route('get_trial_balance') }}",
            type: 'post',
            data: {
                _token: $('input[name=_token]').val(),
                start_date: start.format('YYYY-MM-DD'),
                end_date: end.format('YYYY-MM-DD')
            },
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                if(end.format('MMMM D, YYYY') == moment().format('MMMM D, YYYY')) {
                    $('#date_range span').html('As of   '+end.format('MMMM D, YYYY'));
                } else {
                    $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
                
                
                
                $('.transaction_table > tbody').empty();
        if(data.total.credit != null || data.total.debit != null) {
            $('.transaction_table').show();
            $('.no_data').hide();
        } else {
            $('.transaction_table').hide();
            $('.no_data').show();
        }
                var result = data.ledger_list;
                for(account_head in result) {
                    $('.transaction_table > tbody').append('<tr> \
                            <td style="background:#fff;"  colspan="3">'+account_head+'</td> \
                    </tr>');
                    for(var i in result[account_head]) {
                        
                        $('.transaction_table > tbody').append('<tr> \
                            <td><a href="{{ url('accounts/ledger') }}/'+result[account_head][i].id+'/'+result[account_head][i].group_id+'">'+result[account_head][i].ledger+'</a></td> \
                            <td class="removeSign">'+result[account_head][i].debit+'</td> \
                            <td class="removeSign">'+result[account_head][i].credit+'</td> \
                        </tr>');
                    }
                }

                if(data.suspense != 0 && data.suspense < 0) {
                    $('.transaction_table > tbody').append('<tr> \
                            <td style="background:#fff;"><i>Difference in Opening Balances</i></td> \
                            <td class="removeSign" style="background:#fff;"></td> \
                            <td class="removeSign" style="background:#fff;">'+data.suspense+'</td> \
                    </tr>');

                    $('.transaction_table > tbody').append('<tr> \
                            <td style="background:#fff;">Total</td> \
                            <td class="removeSign" style="background:#fff;">'+data.total.debit+'</td> \
                            <td class="removeSign" style="background:#fff;">'+(parseFloat(Math.abs(data.suspense))+parseFloat(Math.abs(data.total.credit)))+'</td> \
                    </tr>');

                } else if(data.suspense != 0 && data.suspense > 0) {
                    $('.transaction_table > tbody').append('<tr> \
                            <td style="background:#fff;"><i>Difference in Opening Balances</i></td> \
                            <td class="removeSign" style="background:#fff;">'+data.suspense+'</td> \
                            <td class="removeSign" style="background:#fff;"></td> \
                    </tr>');

                    $('.transaction_table > tbody').append('<tr> \
                            <td style="background:#fff;">Total</td> \
                            <td class="removeSign" style="background:#fff;">'+(parseFloat(Math.abs(data.suspense))+parseFloat(Math.abs(data.total.debit)))+'</td> \
                            <td class="removeSign" style="background:#fff;">'+data.total.credit+'</td> \
                    </tr>');
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