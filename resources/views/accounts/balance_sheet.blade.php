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

 <div class="generate_pdf" >

    <h6 style="text-align: center;">{{$branch}}</h6>
    <h3 style="text-align: center;">Balance Sheet</h3>
    <div id="date_range" style="text-align: center; margin: 0 auto; display: block; width: 250px;" class="tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change date range">
        <i class="icon-calendar"></i>&nbsp;
        <span class="thin uppercase hidden-xs"></span>&nbsp;<i class="fa fa-angle-down"></i>
    </div>


<div style="display:none" class="text-center no_data">There are no transactions between the selected period.</div>

<div class="row transaction_table">

    <div class="col-md-12 ">

        <div class="row">  
            
            <div class="col-md-6">
                <h3 style="width:100%; padding:10px;" class="text-center">Liabilities</h3>
                    <div class="liability_container"></div>
            </div>

            <div class="col-md-6">
               <h3 style="width:100%; padding:10px;" class="text-center">Assets</h3>
                    <div class="asset_container"></div>
            </div>
        </div>

        <div class="col-md-12" style="font-size:14px;">

            <div class="row">

                <div class="col-md-6" style="border-top:1px solid #F5F5F5; padding:10px 0;">
                    <div style="width:48%;float:left; margin-left:2%">Total</div>
                    <div style="width:50%;float:left;text-align:right">
                        <span class="removeSign negativeSign total_liability"></span>
                    </div>
                </div>

                <div class="col-md-6" style="border-top:1px solid #F5F5F5; padding:10px 0;">
                    <div style="width:46%;float:left; margin-left:4%">Total</div>
                    <div style="width:50%;float:left;text-align:right">
                        <span class="removeSign negativeSign total_asset"></span>
                    </div>
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
            doc.save('Balance-Sheet.pdf');
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
            url: "{{ route('get_balance_sheet') }}",
            type: 'post',
            data: {
                _token: $('input[name=_token]').val(),
                start_date: start.format('YYYY-MM-DD'),
                end_date: end.format('YYYY-MM-DD')
            },
            dataType: "json",
            success: function(data, textStatus, jqXHR) {

                $('#date_range span').html('As of   '+end.format('MMMM D, YYYY'));
                $('.asset_container').empty();
                $('.liability_container').empty();
                $('.liability_container').append(data.liabilities);
                $('.asset_container').append(data.assets);

                var liable_suspense = 0;
                var total_liability = 0;
                var asset_suspense = 0;
                var total_asset = 0;

                if(data.suspense != 0 && data.suspense < 0) {
                    liable_suspense +=  data.suspense;
                    $('.liability_container').append('<ol class="tree_list"><li><i>&nbsp;</i><div style="font-style:italic">Difference in Opening Balances</div><div style="padding-left:10px" class="removeSign negativeSign">'+data.suspense+'</div></li></ol>');

                }else if(data.suspense != 0 && data.suspense > 0) {
                    asset_suspense +=  data.suspense;
                    $('.asset_container').append('<ol class="tree_list"><li><i>&nbsp;</i><div style="font-style:italic">Difference in Opening Balances</div><div style="padding-left:10px" class="removeSign negativeSign">'+data.suspense+'</div></li></ol>');
                }


                if(data.statement.report == 'profit') {
                    $('.liability_container').append('<ol class="tree_list"><li><i>&nbsp;</i><div style="font-weight: bold;">Profit</div><div class="removeSign negativeSign" style="text-align:right; width:45%">'+data.statement.report_amount+'</div></li>');
                } else if(data.statement.report == 'loss') {
                    $('.asset_container').append('<ol class="tree_list"><li><i>&nbsp;</i><div style="font-weight: bold;">Loss</div><div style="text-align:right; width:45%" class="removeSign negativeSign">'+data.statement.report_amount+'</div></li>');
                }

                if(data.total_asset.length > 0) {
                    total_asset = data.total_asset[0].closing_balance;
                   
                    if(data.statement.report == 'profit') {
                        $('.total_asset').html(Math.abs(parseFloat(total_asset) + parseFloat(asset_suspense)));
                    } else {
                        $('.total_asset').html(Math.abs(parseFloat(total_asset) + parseFloat(data.statement.report_amount) + parseFloat(asset_suspense)));
                    }
                } else {
                    $('.total_asset').html(Math.abs(parseFloat(Math.abs(asset_suspense)) + parseFloat(data.statement.report_amount)));
                }

                if(data.total_liability.length > 0) {

                    total_liability = data.total_liability[0].closing_balance;

                    if(data.statement.report == 'loss') {
                       $('.total_liability').html(Math.abs(parseFloat(total_liability) + parseFloat(Math.abs(liable_suspense))));
                    } else {
                        $('.total_liability').html(Math.abs(parseFloat(total_liability) + parseFloat(data.statement.report_amount) + parseFloat(Math.abs(liable_suspense))));
                    }
                } else {
                    if(data.statement.report == 'loss') {
                        $('.total_liability').html(Math.abs(parseFloat(liable_suspense)));
                    } else {
                        $('.total_liability').html(Math.abs(parseFloat(liable_suspense)) + parseFloat(data.statement.report_amount));
                    }
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