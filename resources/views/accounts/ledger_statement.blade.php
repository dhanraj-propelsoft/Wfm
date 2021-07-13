@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.accounts')
@section('content')
<div class="fill header">
    <button class="btn btn-primary float-right pdf_generation button">Generate PDF</button>
</div>

    <div class='generate_pdf_title' style="float: left; margin-left: 40%; ">   
      
        <div class="" style="">
            <h5 style="text-align: center;">{{$branch}}</h5>
        </div>
        <div class="" style="">            
            <h5 style="text-align: center;">{{$ledger}}</h5>
        </div>
        <div class="">
            <div id="date_range" style="text-align: center; " class="tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change date range">
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase hidden-xs"></span>&nbsp;
                <i class="fa fa-angle-down"></i>
            </div>
        </div>

    </div> 

    <div style="display:none" class="text-center no_data">There are no transactions between the selected period.</div>
    
        <table class="transaction_table table table_empty table-striped table-hover generate_pdf">
          <thead>
            <tr>
              <th> Date </th>
              <th> Account </th>
              <th> Voucher No </th>
              <th> Voucher Type </th>
              <th> Reference No. </th>
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
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.autotable.min.js') }}"></script>
 

<script type="text/javascript">

    var datatable = null;

    var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

    $(document).ready(function() {


        $('.pdf_generation').click(function () {

            /*Table format - pdf , Automatically splitted in multiple pages*/
            $('.loader_wall_onspot').show();

            var pdf = new jsPDF('p', 'pt', 'a4');
            
            var html=$(".generate_pdf_title").html();

            pdf.fromHTML(html,250,0, {
           
            }); 
            
            pdf.autoTable({html: '.generate_pdf',margin: {top: 100}});
            pdf.save('Statement.pdf');

            $('.loader_wall_onspot').hide();        
            /*End*/          

        });

        datatable = $('#datatable').DataTable(datatable_options);

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

    $(document).on('click', '.process_inventory', function(e) {
        e.preventDefault(); 

        var id=$(this).data('id');

        $.get("{{ url('accounts/inventory_report') }}/"+id, function(data) {
            $('.crud_modal .modal-container').html("");
            $('.crud_modal .modal-container').html(data);
        });

        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
                      
    });
        
    $(document).on('click', '.purchase_process', function(e) {
        e.preventDefault(); 

        var id=$(this).data('id');
                          

        $.get("{{ url('accounts/purchase_process') }}/"+id, function(data) {
            $('.crud_modal .modal-container').html("");
            $('.crud_modal .modal-container').html(data);
         });

        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
                      
    });


    function get_data(start, end) {
    
        $('.loader_wall').show();
        $.ajax({
                url: "{{ route('get_ledger') }}",
                type: 'post',
                data: {
                        _token: $('input[name=_token]').val(),
                        start_date: start.format('YYYY-MM-DD'),
                        id: '{{$id}}',
                        group_name: '{{$parent}}',
                        end_date: end.format('YYYY-MM-DD')
                
                },
                dataType: "json",
                success: function(data, textStatus, jqXHR) {
                    var account_ledger = data.account_ledger_name;
                    if(end.format('MMMM D, YYYY') == moment().format('MMMM D, YYYY')) {
                      $('#date_range span').html('As of   '+end.format('MMMM D, YYYY'));
                    } else {
                      $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }

                    $('.transaction_table > tbody').empty();
                    if(data.ledger_statement.length > 0 || data.opening_balance.length > 0 || data.closing_balance.length > 0) {
                        $('.transaction_table').show();
                        $('.no_data').hide();
                    } else {
                        $('.transaction_table').hide();
                        $('.no_data').show();
                    }

                    var opening_debit = "0.00";
                    var opening_credit = "0.00";
                
                    if(data.opening_balance[0]) {
                        if(data.opening_balance[0].opening_balance_type == "debit") {
                            opening_debit = data.opening_balance[0].closing_balance;
                        } else if(data.opening_balance[0].opening_balance_type == "credit") {
                            opening_credit = data.opening_balance[0].closing_balance;
                        }
                    }

                    // console.log(opening_debit);

                    $('.transaction_table > tbody').append(`<tr>
                        <td class="rearrangedatetext">`+data.opening_date+`</td>
                        <td>Opening Balance</td><td></td><td></td><td></td>
                        <td class="removeSign">`+opening_debit+`</td>
                        <td class="removeSign">`+opening_credit+`</td>
                      </tr>`);
                   
                   /* if(data.account_ledger_name == 'sales')
                    {*/
                        for(var i in data.ledger_statement) {
                            var ref = "";
                            if(data.ledger_statement[i].reference_no != null) { ref = data.ledger_statement[i].reference_no; }
                                $('.transaction_table > tbody').append(`<tr>
                                <td class="rearrangedatetext">`+data.ledger_statement[i].date+`</td>
                                <td>`+data.ledger.ledger+`</td> 
                                <td> 
                                    <a href="javascript:;" data-id="`+data.ledger_statement[i].id+`"   class="grid_label  purchase_process">`+data.ledger_statement[i].voucher_no+`</a> 
                                </td>
                                <td>`+data.ledger_statement[i].voucher_type+`</td> 
                                <td>#`+ref+`</td>
                                <td class="removeSign">`+data.ledger_statement[i].debit+`</td>
                                <td class="removeSign">`+data.ledger_statement[i].credit+`</td> 
                                </tr>`);
                    
                        }        
                
                   /* }*/

                    /*if(data.account_ledger_name != 'purchases' && data.account_ledger_name != 'sales')
                    {
                        console.log(data.ledger_statement);
                        for(var i in data.ledger_statement) {
                            var ref = "";
                            if(data.ledger_statement[i].reference_no != null) { ref = data.ledger_statement[i].reference_no; }
                                $('.transaction_table > tbody').append(`<tr>
                                <td class="rearrangedatetext">`+data.ledger_statement[i].date+`</td>
                                <td>`+data.ledger.ledger+`</td> 
                                <td> 
                                <a href="javascript:;" data-id="`+data.ledger_statement[i].id+`" class="grid_label  purchase_process edit">`+data.ledger_statement[i].voucher_no+`</a> 
                                </td>
                                <td>`+data.ledger_statement[i].voucher_type+`</td> 
                                <td>#`+ref+`</td>
                                <td class="removeSign">`+data.ledger_statement[i].debit+`</td>
                                <td class="removeSign">`+data.ledger_statement[i].credit+`</td> 
                                </tr>`);
                    
                        }          
                    }
                    if(data.account_ledger_name == 'purchases')
                    {
                         console.log('purchases');
                        for(var i in data.ledger_statement) {
                            var ref = "";
                            if(data.ledger_statement[i].reference_no != null) { ref = data.ledger_statement[i].reference_no; }
                                $('.transaction_table > tbody').append(`<tr>
                                <td class="rearrangedatetext">`+data.ledger_statement[i].date+`</td>
                                <td>`+data.ledger.ledger+`</td> 
                                <td> 
                                <a href="javascript:;" data-id="`+data.ledger_statement[i].id+`"   class="grid_label  purchase_process">`+data.ledger_statement[i].voucher_no+`</a> 
                                </td>
                                <td>`+data.ledger_statement[i].voucher_type+`</td> 
                                <td>#`+ref+`</td>
                                <td class="removeSign">`+data.ledger_statement[i].debit+`</td>
                                <td class="removeSign">`+data.ledger_statement[i].credit+`</td> 
                                </tr>`);  
                        }          
                    }*/
                
                    var closing_debit = "0.00";
                    var closing_credit = "0.00";

                    if(data.closing_balance[0]) {
                        if(data.closing_balance[0].balance_type == "Dr") {
                            closing_debit = data.closing_balance[0].closing_balance;
                        } else if(data.closing_balance[0].balance_type == "Cr") {
                            closing_credit = data.closing_balance[0].closing_balance;
                        }
                    }
                
                    $('.transaction_table > tbody').append(`<tr>
                        <td class="rearrangedatetext">`+data.closing_date+`</td>
                        <td>Closing Balance</td><td></td><td></td><td></td>
                        <td class="removeSign">`+closing_debit+`</td>
                        <td class="removeSign">`+closing_credit+`</td>
                      </tr>`);

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