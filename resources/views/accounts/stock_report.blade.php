@extends('layouts.master')
@section('head_links') @parent
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.accounts')
@section('content')
<style type="text/css">
    
  

.table>tfoot>tr>th, 
.table>tfoot>tr>td {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 5px solid #ddd;
    border-bottom: 5px solid #ddd;
   
}
</style>
<h6 style="text-align: center;">{{$branch}}</h6>
<h3 style="text-align: center;">Stock Report</h3>
<div id="date_range" style="text-align: center; margin: 0 auto; display: block; width: 250px;" class="tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change date range">
                                <i class="icon-calendar"></i>&nbsp;
                                <span class="thin uppercase hidden-xs"></span>&nbsp;
                                <i class="fa fa-angle-down"></i>
                            </div>
<div style="display:none" class="text-center no_data">There are no transactions between the selected period.</div>

<table class="transaction_table table table_empty table-striped table-hover">
  <thead>
    <tr>      
        <th>Item</th>   
        <th colspan="2">Opening Balance</th>
        <th colspan="2">Inwards</th>
        <th colspan="2">Outwards</th>
        <th colspan="2">Closing Balance</th>

    </tr>
  

    <tr>
        <td></td>
        <td>Qty</td>
        <td>Value</td>
        <td>Qty</td>
        <td>Value</td>
        <td>Qty</td>
        <td>Value</td>
        <td>Qty</td>
        <td>Value</td>
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
<script type="text/javascript">

    var datatable = null;

    var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

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

    function get_data(start, end) {        

        $('.loader_wall').show();
        $.ajax({
            url: "{{ route('get_stock_report') }}",
            type: 'post',
            data: {
                _token: $('input[name=_token]').val(),
                start_date: start.format('YYYY-MM-DD'),
                end_date: end.format('YYYY-MM-DD')
            },
            dataType: "json",
            success: function(data, textStatus, jqXHR) {

                 var res = data.result;
                 
                //var opening_balance = data.opening_balance;

                $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('.transaction_table').empty();
                
                if(res.length > 0) {
                    $('.transaction_table').show();
                    $('.no_data').hide();
                } else {
                    $('.transaction_table').hide();
                    $('.no_data').show();
                }


               

                var table = 
                    '<table class="table table_empty table-striped table-hover"><thead><tr> \ <th width="20%">Item</th><th width="20%" colspan="2">Opening Balance</th><th width="20%" colspan="2">Inwards</th><th width="20%" colspan="2">Outwards</th><th width="20%" colspan="2">Closing Balance</th></tr><tr><td></td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> </tr></thead> <tbody>';     

    for(var i in res) {

            /*var in_stock = res[i].in_stock;
            var qty = res[i].quantity;
            var purchase_price = res[i].purchase_price;

            var opening_quantity = in_stock - qty;
            var opening_value = opening_quantity * purchase_price;*/

       
            table += '<tr> \
            <td><a href="javascript:;" data-id="'+res[i].entry_id+'" class="grid_label  process_inventory">'+res[i].item_name+'</a></td> \
            <td>'+res[i].opening_quantity+'</td> \
            <td>'+res[i].opening_value+'</td> \
            <td>'+res[i].inwards_quantity+'</td> \
            <td>'+res[i].inwards_value+'</td> \
            <td>'+res[i].outwards_quantity+'</td> \
            <td>'+res[i].outwards_value+'</td> \
            <td>'+res[i].closing_quantity+'</td> \
            <td>'+res[i].closing_value+'</td> \
            </tr>';

            }         

            table += '<tfoot><tr><th style="padding:10px" width="20%">Grand Total</th> <td>'+data.grand_quantity+'</td> <td>'+data.grand_value+'</td> <td>'+data.grand_inwards_quantity+'</td> <td>'+data.grand_inwards_value+'</td> <td>'+data.grand_outwards_quantity+'</td> <td>'+data.grand_outwards_value+'</td> <td>'+data.grand_closing_quantity+'</td> <td>'+data.grand_closing_value+'</td></tr></tfoot>';

            table +=   '</table>';

            $('.transaction_table').append(table);
              

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