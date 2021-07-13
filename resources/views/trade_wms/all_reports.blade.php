@extends('layouts.master')

@section('head_links') @parent

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

  <style>

  .popover{

        max-width: 348px !important;

  }

  .table tr:nth-child(even) {

     background-color: white !important; 

    }





table {

  border-spacing: 50px 0;

  table-layout: fixed;

}

.table td,tr {

     border:1.5px solid #faf9f7;

     padding:0px!important;

}

.button_td{

  padding:3px!important;

  width:200px!important;

}

.label_td{

 width:200px!important;

}

.data_td{

    width:100px;

}

</style>

@stop

@include('includes.trade_wms')

@section('content')



@if(Session::has('flash_message'))

  <div class="alert alert-success" style="display: block;">

    {{ Session::get('flash_message') }}

  </div>

@endif



@if($errors->any())

  <div class="alert alert-danger" style="display: block;">

    @foreach($errors->all() as $error)

      {{ $error }}

    @endforeach

  </div>

@endif

<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">

  <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Reports</b></h5>

</div>



<div class="fill header">

  <div class="all_ul">

    <div class="form-inline" style = "padding-left: 150px;">            

      <div class="col-md-2 form-group">

        <label class="col-form-label" for="to_date">From Date</label>

        <div class='input-group date'  id="from-date">

          <input type='text' class="form-control from_date"/>

          <span class="input-group-addon">

          <span class="fa fa-calendar" style="font-size:24px"></span>

          </span>

        </div>

      </div>

       <div class="col-md-2 form-group">

          <label class="col-form-label" for="to_date">To Date</label>

          <div class='input-group date'  id="to-date">

            <input type='text' class="form-control to_date" />

            <span class="input-group-addon">

            <span class="fa fa-calendar" style="font-size:24px"></span>

            </span>

            </div>

       </div>

      <div style="display:none;">

        <input type='date' class="form-control finacial_year" />

      </div>

      <div class="col-md-3 col-sm-3 form-group" style = "padding-top: 31px;">

        <button type="button" class="btn btn-outline-primary btn-sm show" style="float:right">Show</button>&nbsp;

        <button type="button" class="btn btn-outline-secondary reset" >Reset</button>

      </div>

    </div> 

  </div>   

</div>

<div class="all_ul"style=" padding-top: 36px;">

    <ul class="list-group">

      <li class="list-group-item">

        <span class = "font-weight-bold">Stock Report</span><br><br>

        <div class="col-md-10 col-md-offset-1 result" style="display:none;margin-left:0px">

              <table class="table table stock_reporttable">

                <tbody>

                  <tr >            

                    <td class="label_td" style="" ><span class="text-primary"><b>No of Items</b><b style=" margin-left:110px!important"class="colons">:</b></span></td>

                    <td class="data_td" align="right"><p class="stack_report" style="margin-top: 11px"> <span class="text-success" ><b data-value="no_of_items"></b></span></p></td>

                    <td width="60px"></td>

                    <td class="label_td" ><span class="text-primary"><b>Total Stock value</b><b class="colons" style=" margin-left:70px!important">:</b></span></td>



                     <td class="data_td"  align="right" ><p class="stack_report" style="margin-top: 11px"> <span class="text-success"><b data-value="stack_total_value" style=""></b></span></p></td>  

                      <td width="20px"></td>

                     <td class="button_td" style=""><button type="button" class="btn btn-outline-info current_stock table_view" data-name="Stock Report" data-toggle="tooltip" title="Current stock value as on today for each Goods">Current Stock Value </button></td> 

                     <td class="button_td" style=""><button type="button" class="btn btn-outline-info report " data-name="Stock Report" data-toggle="tooltip" title="Current stock value as on today for each Goods">Current Stock Value old </button></td>  

                  </tr>

                  <tr >

                    <td class="label_td" ><span class="text-primary" style=""><b>Total Goods Purchased</b><b class="colons" style=" margin-left:35px!important">:</b></span>

                      </td>

                    <td class="data_td" align="right"><p class="stack_report" style="margin-top: 11px"> <span class="text-success"><b data-value="totol_goods_purchase"></b></span></p></td>

                    <td width="20px"></td>

                    <td  class="label_td"><span class="text-primary" style=""><b>Total Goods Solded</b><b class="colons" style=" margin-left:53px!important">:</b></span>

                    </td>



                     <td class="data_td" align="right" ><p class="stack_report" style="margin-top: 11px">  <span class="text-success"><b data-value="total_goods_sale" ></b></span></p></td> 

                      <td width="20px"></td>

                     <td  class="button_td" align="left" >

                      <button type="button" class="btn btn-outline-info low_stock_button table_view" data-name="Low Stock Report " data-toggle="tooltip" title="Overall Goods lesser than MOQ" style="">Low Stock  Report</button>

                       <td  class="button_td" align="left" >

                       <button type="button" class="btn btn-outline-info report " data-name="Low Stock Report " data-toggle="tooltip" title="Overall Goods lesser than MOQ" style="">Low Stock  Report old</button>

                    </td>  

                  </tr>

                  <tr>

                    <td class="label_td" ><span class="text-primary" ><b>Difference In The Period</b><b class="colons" style=" margin-left:30px!important">:</b></span>

                      </td>

                    <td  align="right"><p class="stack_report" style="margin-top: 11px"> <span class="text-success"><b data-value="diference_period_amount"></b></span></p></td>

                    <td width="60px"></td>

                    <td class="label_td"><span class="" style="color:#e4e7ed"><b>Adjustment By Delete(TBD)</b><b class="colons"style=" margin-left:5px!important">:</b></span>

                    </td>



                     <td  align="right" ><p class="stack_report" style="margin-top: 11px">  <span class="text-success"><b data-value=""></b></span></p></td> 

                      <td width="20px"></td>

                     <td class="button_td"><button type="button" class="btn btn-outline-info jobcard_low_stock_button table_view" data-name="Job card Low Stocks" title="Stock to be purchased based on Jobcards" data-toggle="tooltip"  style="">JobCard LowStock Report</button></td>

                      <td class="button_td"><button type="button" class="btn btn-outline-info report" data-name="Job card Low Stocks" title="Stock to be purchased based on Jobcards" data-toggle="tooltip"  style="">JobCard LowStock Report old</button></td>

                  </tr>

                  <tr >

                    <td class="label_td" ><span class="text-primary" style=""><b>Low Stock Items</b><b class="colons"style=" margin-left:77px!important">:</b></span>

                      </td>

                    <td class="data_td"  align="right"><p class="stack_report" style="margin-top: 11px"> <span class="text-success"><b data-value="count_lowstock"></b></span></p></td>

                    <td width="60px"></td>

                    <td class="label_td" ><span class="text-primary" style=""><b>Low Stock Purchase</b><b class="colons" style=" margin-left:44px!important">:</b></span>

                    </td>



                     <td class="data_td" align="right" ><p class="stack_report" style="margin-top: 11px">  <span class="text-success"><b data-value="lowstock_amount"></b></span></p></td> 

                      <td width="20px"></td>

                     <td class="button_td" align="left"><button type="button" class="btn btn-outline-info stock_flow"  data-name="Stock Flow"  data-toggle="tooltip" title="Opening, Inward and outward stocks in this period" >Stock Flow</button> </td>   

                  </tr>

                </tbody>

              </table>

        </div> 

        <div style="display: none;">

        <table id="stock_report" class="display nowrap" style="width:100%">

              <thead>

                  <tr>

                      <th>Item Id</th>

                      <th>Category</th>

                      <th>Sub Category</th>

                      <th>Type</th>

                      <th>Make</th>

                      <th>Identifier</th>

                      <th>Item Name</th>

                      <th>HSN</th>

                      <th>Purchase Unit Rate</th>

                      <th>Purchase Rate + Tax</th>

                      <th>Selling Tax</th>

                      <th>Selling Unit Rate</th>

                      <th>Selling Rate + Tax</th>

                      <th>Stock Qty</th>

                      <th>Total Purchase Value</th>

                      <th>Total Selling Value</th>

                  </tr>

              </thead>

              <tbody>

              </tbody>

              <tfoot>

              </tfoot>

        </table>

        </div>

        <div style="display: none;">

        <table id="all_low_stock_report" class="display nowrap" style="width:100%">

                  <thead>

                      <tr>

                          <th>Item Id</th>

                          <th>Category</th>

                          <th>Sub Category</th>

                          <th>Type</th>

                          <th>Make</th>

                          <th>Identifier</th>

                          <th>Item Name</th>

                          <th>HSN</th>

                          <th>In_Stock</th>

                          <th>Purchase Rate + Tax</th>

                          <th>Minimum Order Quantity</th>

                          <th>Total Purchase Value</th>        

                      </tr>

                  </thead>

                  <tbody>

                  </tbody>

                  <tfoot>

                  </tfoot>

        </table>

        </div>

      </li>

      <li class="list-group-item" style="display:none">

          <span class = "font-weight-bold">Job card Low Stocks</span><br><br>

          <div  class="" style="display:none;">

            <p class="low_stack_report"><span class="text-primary"><b>No of Items:</b></span>

            <span class="text-success"><b data-value="no_of_items"></b></span>

            <span class="text-primary" style="padding-left: 177px;"><b>Total Purchase:</b></span>

            <span class="text-success"><b data-value="lowstack_total_purchase"></b></span>

            </p>     

           </div>

           <div style="display: none;">

                  <table id="low_stock_report" class="display nowrap" style="width:100%">

                        <thead>

                            <tr>

                                <th>Jc Number</th>

                                <th>Vehicle Number</th>           

                                <th>Item Name</th>

                                <th>Order Quantity</th>

                                <th>In Stock</th>

                                <th>Pruchase Price + tax</th>

                                <th>Total Amount</th>

                                <th>Assigned To</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                        <tfoot>

                        </tfoot>

                    </table>

          </div>

          <div style="display: none;">

            <table id="stock_flow_report" class="display nowrap" style="width:100%">

                <thead>

                    <tr>

                      <th rowspan="2">Item</th>

                      <th colspan="2">Opening Balance</th>

                      <th colspan="2">Inwards</th>

                      <th colspan="2">Outwards</th>

                      <th colspan="2">Closing Balance</th>

                    </tr>

                    <tr>

                      <th>Qty</th>

                      <th>Value</th>

                      <th>Qty</th>

                      <th>Value</th>

                      <th>Qty</th>

                      <th>Value</th>

                      <th>Qty</th>

                      <th>Value</th>

                    </tr>

                </thead>

                <tbody>

                </tbody>

                <tfoot>

                </tfoot>

            </table>

          </div>

      </li>

      <li class="list-group-item">

        <span class = "font-weight-bold">Invoice Report</span><br><br>

        <div class="col-md-10 result" style="display:none;margin-left:0px">

          <table class=" table table invoice_reporttable">

            <tbody>

              <tr>            

                <td class="label_td" ><span class="text-primary"><b>No of all Invoices</b><b class="colons" style=" margin-left:75px!important">:</b></span>

                 </td>



                <td class="data_td" align="right"><p class="invoice_report" style="margin-top: 11px"> <span class="text-success"><b data-value="no_of_all_invoice"></b></span></p></td>



                <td width="60px"></td>



                <td class="label_td" ><span class="text-primary" style=""><b>No of Cash Invoices</b><b class="colon5" style=" margin-left:42px!important">:</b></span>   </td> 



                 <td class="data_td" align="right" ><p class="invoice_report" style="margin-top: 11px"><span class="text-success"><b data-value="no_of_cash_invoice"></b></span></p></td>  

                 <td width="20px"></td>

                 <td class="button_td" style="" align="left"> <button type="button" class="btn btn-outline-info sales_report_button table_view" data-name="Invoice Report" data-id="1" data-toggle="tooltip" title="All Invoices with items in this period" style="">Sales Report</button></td> 

                 <td class="button_td" style="" align="left"><button type="button" class="btn btn-outline-info report" data-name="Invoice Report" data-id="1" data-toggle="tooltip" title="All Invoices with items in this period" style="">Sales Report old</button></td> 

              </tr>

              <tr>

                  <td class="label_td"> <span class="text-primary" style=""><b>Credit Invoice Pending</b><b class="colons" style=" margin-left:40px!important">:</b></span>

       

                  </td>

                  <td class="data_td" align="right"><p class="invoice_report" style="margin-top: 11px"> <span class="text-success"><b data-value="pending_invoice"></b></span></p></td>



                  <td width="20px"></td>



                  <td class="label_td" > <span class="text-primary" style=""><b>No of Credit Invoices</b><b class="colons" style=" margin-left:37px!important">:</b></span>

         

                  </td>



                   <td class="data_td" align="right" ><p class="invoice_report" style="margin-top: 11px"> <span class="text-success"><b data-value="no_of_credit_invoice"></b></span></p></td> 

                    <td width="20px"></td> 

                   <td  align="left" class="button_td"> <button type="button" class="btn btn-outline-info receiveable_report_button table_view  " data-name="Receivable Report" data-toggle="tooltip" title="Pending Receivables to Suppliers in this Period" style="">Receivables Report</button>            

                  </td> 

                  <td  align="left" class="button_td"> <button type="button" class="btn btn-outline-info report" data-name="Receivable Report" data-toggle="tooltip" title="Pending Receivables to Suppliers in this Period" style="">Receivables Report old</button>            

                  </td>   

              </tr>

              <tr>

                  <td class="label_td" > <span class="text-primary" style=""><b>Total Invoice Value(Rs)</b><b class="colons" style=" margin-left:40px!important">:</b></span>

        

                  </td>

                  <td  class="data_td" align="right"><p class="invoice_report" style="margin-top: 11px"> <span class="text-success"><b data-value="total_invoice_value"></b></span></p></td>

                  <td width="60px"></td>

                  <td class="label_td"> <span class="text-primary" style=""><b>Receiveable Pending</b><b class="colons" style=" margin-left:38px!important">:</b></span>

                  </td>



                   <td class="data_td" align="right" ><p class="invoice_report" style="margin-top: 11px"><span class="text-success"><b data-value="total_receivables"></b></span></p></td> 

                   <td></td>                

              </tr>

              <tr>

                  <td class="label_td"> <span class="text-primary" style=""><b>Total Cash Invoice Value(Rs)</b><b class="colons" style=" margin-left:5px!important">:</b></span>     

                  </td>

                  <td class="data_td" align="right"><p class="invoice_report" style="margin-top: 11px"> <span class="text-success"><b data-value="total_cash_value"></b></span></p></td>

                  <td width="60px"></td>

                  <td class="label_td" ><span class="text-primary" style=""><b>Sales by Spares</b><b class="colons" style=" margin-left:67px!important">:</b></span>    

                  </td>



                   <td class="data_td" align="right" ><p class="invoice_report" style="margin-top: 11px"><span class="text-success"><b data-value="sales_by_spares"></b></span></p></td> 

                   <td  width="150px" align="left"></td>   

              </tr>

              <tr>

                 

                  <td class="label_td" ><span class="text-primary" style=""><b>Total Credit Invoice Value(Rs)</b><b class="colons">:</b></span>      

                  </td>



                   <td  class="data_td" align="right" ><p class="invoice_report" style="margin-top: 11px"><span class="text-success"><b data-value="total_credit_value"></b></span>  </p></td> 

                   <td width="60px"></td>

                    <td class="label_td"> <span class="text-primary" style=""><b>Sales by Work/Service</b><b class="colons" style=" margin-left:30px!important">:</b></span>

       

                  </td>

                  <td  class="data_td" align="right"><p class="invoice_report" style="margin-top: 11px">  <span class="text-success"><b data-value="sales_by_works"></b></span></p></td>

                  

                   <td  width="150px" align="left"></td>   

              </tr>

            </tbody>

          </table>

        </div>  

        <div style="display: none;">

          <table id="invoice_report" class="display nowrap" style="width:100%">

            <thead>

                <tr>

                    <th>Date</th>

                    <th>Document No</th>

                    <th>Customer</th>

                    <th>Goods/Service</th>

                    <th>Item Name</th>

                    <th>Hsn</th>

                    <th>Unit Rate</th>

                    <th>Quantity</th>

                    <th>Total</th>

                    <th>Discount</th>

                    <th>SGST</th>

                    <th>CGST</th>

                    <th>IGST</th>

                    <th>Total Tax</th>

                    <th>Total Sales</th>

                    <th>Pending Payment</th>

                    <th>Payment Mode</th>

                    <th>Sale Type</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

            </tfoot>

          </table>

        </div>

        <div style="display: none;">

            <table id="receiveable_report" class="display nowrap" style="width:100%">

                  <thead>

                      <tr>

                       

                          <th>Customer Name</th>

                          <th>Invoice Number</th>

                          <th>Total Sales</th>

                          <th>Pending Payment</th>               

                      </tr>

                  </thead>

                  <tbody>

                  </tbody>

                  <tfoot>

                </tfoot>

              </table>

        </div>

      </li>

      <li class="list-group-item">

        <span class = "font-weight-bold">Purchase Report</span><br><br>

       <div  class=" col-md-10  result" style="display:none;margin-left:0px">

         <table class=" table table purchase_reporttable ">

              <tbody>

                <tr>            

                  <td  class="label_td"><span class="text-primary"><b>No of Purchases</b><b class="colons" style=" margin-left:75px!important">:</b></span>

                   </td>



                  <td class="data_td" align="right"><p class="purchase_report_data" style="margin-top: 11px">  <span class="text-success"><b data-value="no_of_purchase"></b></span></p></td>



                  <td width="60px"></td>



                  <td class="label_td" ><span class="text-primary" style=""><b>Total Purchase Value(Rs)</b><b class="colons" style=" margin-left:20px!important" >:</b></span>    



                   <td class="data_td" align="right" ><p class="purchase_report_data" style="margin-top: 11px"><span class="text-success"><b data-value="total_purchase"></b></span></p></td>  

                    <td width="20px"></td>

                   <td class="button_td" style="" align="left"><button type="button" class="btn btn-outline-info purchase_report_button table_view" data-name="Purchase Report" data-toggle="tooltip" title="All purchases with items in this period"  style="">Purchase Report</button></td> 

                   <td class="button_td" style="" align="left"><button type="button" class="btn btn-outline-info report" data-name="Purchase Report" data-toggle="tooltip" title="All purchases with items in this period"  style="">Purchase Report old</button></td>  

                </tr>

              

               <tr>            

                  <td style="border-bottom-width:-50px"><span class="text-primary"><b> </b></span>

                   </td>



                  <td class="data_td" align="right"><p class="purchase_report_data" style="margin-top: 11px">  <span class="text-success"><b data-value=""></b></span></p></td>



                  <td width="60px"></td>



                  <td  class="label_td"><span class="text-primary" style=""><b>Total Payable(Rs)</b><b class="colons" style=" margin-left:67px!important">:</b></span>    



                   <td  class="data_td" align="right" ><p class="purchase_report_data" style="margin-top: 11px"> <span class="text-success"><b data-value="total_payable"></b></span></p></td>  

                    <td width="20px"></td>

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info payable_button table_view" data-name="Payable Report"  data-toggle="tooltip" title="Payables by customers in this period" style="">Payables Report</button></td> 

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info report" data-name="Payable Report"  data-toggle="tooltip" title="Payables by customers in this period" style="">Payables Report old</button></td>  

                </tr>

             

               

              </tbody>

          </table>

          </div><div style="display: none;">

           <table id="purchase" class="display nowrap" style="width:100%">

            <thead>

                <tr>

                    <th>Date</th>

                    <th>Document Number</th>

                    <th>Customer</th>

                    <th>Item Number</th>

                    <th>HSN</th>

                    <th>New Selling Price</th>

                    <th>Purchase Price</th>

                    <th>Quantity</th>

                    <th>Total</th>

                    <th>SGST</th>

                    <th>CGST</th>

                    <th>IGST</th>

                    <th>Tax Amount</th>

                    <th>Total Sales</th>

                    <th>Pending Payment</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

          </tfoot>

        </table>

        </div>

        <div style="display: none;">

              <table id="payable_report" class="display nowrap" style="width:100%">

                    <thead>

                        <tr>

                            <th>Supplier Name</th>

                            <th>Purchase Number</th>

                            <th>Total Purchase</th>

                            <th>Pending Payment</th>             

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                    <tfoot>

                  </tfoot>

                </table>

        </div>

      </li>

      <li class="list-group-item">

        <span class = "font-weight-bold"><!-- Daily  -->Expense Report</span><br><br> 

        <div  class=" col-md-10  result" style="display:none;margin-left:0px">

          <table class=" table table expense_reporttable">

              <tbody>

                <tr>            

                  <td  class="label_td"><span class="text-primary"><b>No of Daily Expenses</b><b class="colons" style=" margin-left:45px!important">:</b></span>      

                 </td>

                  <td class="data_td" align="right"><p class="expense_details" style="margin-top: 11px"><span class="text-success"><b data-value="no_of_expenses"></b></span></p></td>



                  <td width="60px"></td>



                  <td class="label_td" ><span class="text-primary" style=""><b>Total Expense Value(Rs)</b><b class="colons" style=" margin-left:25px!important">:</b></span>    



                   <td class="data_td" align="right" ><p class="expense_details" style="margin-top: 11px"><span class="text-success"><b data-value="total_expense"></b></span></td>  

                     <td width="20px"></td>

                   <td style=""  align="left" class="button_td"> <button type="button" class="btn btn-outline-info daily_expenses_button table_view" data-name="Expense Report" data-toggle="tooltip" title="Expenses List in this period"style="">PettyCash Report</button></td>

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info table_view daily_expenses_type" data-name="Daily Report By Type" data-toggle="tooltip" >Report by Type</button></td>

                   <!-- <td style=""  align="left" class="button_td"> <button type="button" class="btn btn-outline-info report" data-name="Expense Report" data-toggle="tooltip" title="Expenses List in this period"style="">PettyCash Report old </button></td> -->  

                </tr>        

              </tbody>

          </table>

        <!--   <table class=" table table expense_reporttable1">

              <tbody>

                <tr>            

                  <td  class="label_td"><span class="text-primary"><b></b><b class="colons" style=" margin-left:45px!important"></b></span>      

                 </td>

                  <td class="data_td" align="right"><p class="expense_details1" style="margin-top: 11px"><span class="text-success"><b data-value=""></b></span></p></td>



                  <td width="60px"></td>



                  <td class="label_td" ><span class="text-primary" style=""><b>Company Expenses(Rs)</b><b class="colons" style=" margin-left:25px!important">:</b></span>    



                   <td class="data_td" align="right" ><p class="expense_details1" style="margin-top: 11px"><span class="text-success"><b data-value="total_expense1"></b></span></td>  

                     <td width="20px"></td>

                   <td style=""  align="left" class="button_td"> <button type="button" class="btn btn-outline-info company_expenses_button table_view" data-name="CompanyExpense Report" data-toggle="tooltip" title="Expenses List in this period"style="">Company Expenses Report</button></td>

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info table_view company_expenses_type" data-name="Company Expenses By Type" data-toggle="tooltip" >Report by Type</button></td>-->

                   <!-- <td style=""  align="left" class="button_td"> <button type="button" class="btn btn-outline-info report" data-name="CompanyExpense Report" data-toggle="tooltip" title="Expenses List in this period"style="">Company Report old</button></td> -->   

                <!--</tr>        

              </tbody>

          </table> -->

        </div>

        <div style="display:none;">

          <table id="daily_expenses" class="display nowrap" style="width:100%">

              <thead>

                <tr>

                  <th>Date</th>

                  <th>Voucher Number</th>

                  <th>Expense Name</th>    

                  <th>Expense Amount</th>  

                  <th>Person Name</th>            

                  <th>Notes</th>

                </tr>

              </thead>

              <tbody>

              </tbody>

              <tfoot>

              </tfoot>

          </table>

        </div>

        <div style="display:none;">

          <table id="company_expenses" class="display nowrap" style="width:100%">

            <thead>

              <tr>

                <th>Date</th>

                <th>Voucher Number</th>

                <th>Expenses Voucher Number</th>    

                <th>From Account</th>    

                <th>To Account</th>    

                <th>Ledger Name</th>    

                <th>Reference/Notes</th>        

                <th>Expense Amount</th>           

              </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

            </tfoot>

          </table>

        </div>          

      </li>

      <li class="list-group-item">

        <span class = "font-weight-bold">Customers/Vehicles</span><br><br>

        <div  class=" col-md-10  result" style="display:none;margin-left:0px">

           <table class=" table table customer_reporttable">

              <tbody>

                <tr>            

                  <td  class="label_td" style=""><span class="text-primary"><b>No of Customers</b><b class="colons" style=" margin-left:70px!important">:</b></span>      

                  </td>

                  <td class="data_td" align="right"><p class="customer_details" style="margin-top: 11px"><span class="text-success"><b data-value="no_of_customers"></b></span></p></td>



                  <td width="60px"></td>



                  <td class="label_td"><span class="text-primary" style=""><b>No Of New Customers</b><b class="colons" style=" margin-left:40px!important">:</b></span>    



                   <td class="data_td" align="right" ><p class="customer_details" style="margin-top: 11px"><span class="text-success"><b data-value="total_newcustomers"></b></span></td>  

                     <td width="20px"></td>

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info customer_report_button table_view" data-name="Customer Report" style=""data-toggle="tooltip" title="Sales by Customer in this period">Customer Report</button></td>

                   <td width="20px"></td>

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info report" data-name="Customer Report" style=""data-toggle="tooltip" title="Sales by Customer in this period">Customer Report old</button></td>  

                </tr>

                <tr>            

                  <td  class="label_td"><span class="text-primary"><b>No of Vehicles</b><b class="colons" style=" margin-left:85px!important">:</b></span>      

                  </td>

                  <td class="data_td" align="right"><p class="customer_details" style="margin-top: 11px"><span class="text-success"><b data-value="total_vehicles"></b></span></p></td>



                  <td width="60px"></td>



                  <td class="label_td"><span class="text-primary" style=""><b>No Of New Vehicles</b><b class="colons" style=" margin-left:55px!important">:</b></span>    



                   <td class="data_td" align="right" ><p class="customer_details" style="margin-top: 11px"><span class="text-success"><b data-value="total_newvehicles"></b></span></td>  

                     <td width="20px"></td>

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info customer_vehicles_button table_view" data-name="Customers/Vehicles" data-toggle="tooltip" title="Sales by Vehicles All time" style="">Vehicle Report</button></td>

                   <td style=""  align="left" class="button_td"><button type="button" class="btn btn-outline-info report" data-name="Customers/Vehicles" data-toggle="tooltip" title="Sales by Vehicles All time" style="">Vehicle Report old </button></td>   

                </tr>        

              </tbody>

            </table>

        </div>   

        <div style="display: none;">

          <table id="customer_vehicles" class="display nowrap" style="width:100%">

            <thead>

                <tr>

                    <th>Vehicle Number</th>

                    <th>Created On</th>

                    <th>Vehicle Name</th>

                    <th>Customer Name</th>

                    <th>Customer Group</th>

                    <th>Credit Limit</th>

                    <th>Phone Number</th>

                    <th>City</th>

                    <th>Total Sales</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

             </tfoot>

           </table>

        </div>

        <div style="display: none;">

            <table id="customer_report" class="display nowrap" style="width:100%">

                  <thead>

                      <tr>

                          <th>Customer Name</th>

                          <th>Customer Group</th>

                          <th>Credit Limit</th>

                          <th>Phone Number</th>

                          <th>City</th>

                          <th>Created On</th>              

                          <th>Total Sales</th>

                          <th>Pending</th>

                      </tr>

                  </thead>

                  <tbody>

                  </tbody>

                  <tfoot>

                </tfoot>

              </table>

        </div>

      </li>

    </ul>

</div>

<!-- view list -->

<div class="currentstock_datatable" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report current_stock " data-name="Stock Report" data-toggle="tooltip">Export excel

    </button>

  </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Current Stock Report</h3>

    <!-- <div style="display:none; margin-left: 20px" class="text-center no_data">There are no transactions between the selected period.</div>  -->

    <table class="Current_stock_table table table_empty table-striped table-hover">               <thead><tr>

                <th>Item Id</th>

                <th>Category</th>

                <th>Sub Category</th>

                <th>Type</th>

                <th>Make</th>

                <th>Identifier</th>

                <th>Item Name</th>

                <th>HSN</th>

                <th>Purchase Unit Rate</th>

                <th>Purchase Rate + Tax</th>

                <th>Selling Tax</th>

                <th>Selling Unit Rate</th>

                <th>Selling Rate + Tax</th>

                <th>Stock Qty</th>

                <th>Total Purchase Value</th>

                <th>Total Selling Value</th>

            </tr>

      </thead>

      <tbody>

      </tbody>

      <tfoot>

      </tfoot>

    </table>

  </div>

</div>

<div class="low_stock_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

     <button type="button" class="btn btn-success report " data-name="Low Stock Report " data-toggle="tooltip" style="">Export Excel</button>

  </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Low Stock Report</h3>

    <table class="low_stock_table table table_empty table-striped table-hover">

      <thead>

          <tr>

                  <th>Item Id</th>

                  <th>Category</th>

                  <th>Sub Category</th>

                  <th>Type</th>

                  <th>Make</th>

                  <th>Identifier</th>

                  <th>Item Name</th>

                  <th>HSN</th>

                  <th>In_Stock</th>

                  <th>Purchase Rate + Tax</th>

                  <th>Minimum Order Quantity</th>

                  <th>Total Purchase Value</th>        

          </tr>

          </thead>

                  <tbody>

                  </tbody>

                  <tfoot>

                </tfoot>

              </table>

  </div>

</div>

<div class="jobcard_low_stock_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back">Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Job card Low Stocks" data-toggle="tooltip"  style="">Export Excel</button>

     

  </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Jobcard Low Stock Report</h3>

    <table  class="jobcard_low_stock_table table table_empty table-striped table-hover">

      <thead>

                            <tr>

                                <th>Jc Number</th>

                                <th>Vehicle Number</th>           

                                <th>Item Name</th>

                                <th>Order Quantity</th>

                                <th>In Stock</th>

                                <th>Purchase Price + tax</th>

                                <th>Total Amount</th>

                                <th>Assigned To</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                        <tfoot>

                        </tfoot>

                    </table>

  </div>

</div>

<div class="stockflow_table" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report"  data-name="Stock Flow"  data-toggle="tooltip">Export Excel</button>

  </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Stock Report</h3>
    <h6 style="margin-left:400px;" class="stock_report_date"></h6>   

    <!-- <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

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

  </div>

</div>

<div class="sales_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Invoice Report" data-id="1" data-toggle="tooltip" style="">Export Excel</button>

  </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Sales Report</h3>

    

   <!--  <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table  class="sales_report_table table table_empty table-striped table-hover">

  <thead>

                <tr>

                    <th>Date</th>

                    <th>Document No</th>

                    <th>Customer</th>

                    <th>Goods</th>

                    <th>Item Name</th>

                    <th>Hsn</th>

                    <th>Unit Rate</th>

                    <th>Quantity</th>

                    <th>Total</th>

                    <th>Discount</th>

                    <th>SGST</th>

                    <th>CGST</th>

                    <th>IGST</th>

                    <th>Total Tax</th>

                    <th>Total Sales</th>

                    <th>Pending Payment</th>

                    <th>Payment Mode</th>

                    <th>Sale Type</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

            </tfoot>

          </table>

  </div>

</div>

<div class="receiveable_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Receivable Report" data-toggle="tooltip" style="">Export Excel</button> 

  </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Receiveable Report</h3>

    

   <!--  <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="receiveable_report_table table table_empty table-striped table-hover">

  <thead>

                      <tr>

                       <th>Customer Name</th>

                          <th>Invoice Number</th>

                          <th>Total Sales</th>

                          <th>Pending Payment</th>               

                      </tr>

                  </thead>

                  <tbody>

                  </tbody>

                  <tfoot>

                </tfoot>

              </table>

  </div>

</div>

<div class="purchase_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Purchase Report" data-toggle="tooltip"  style="">Export Excel</button>

    </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Purchase Report</h3>

    

   <!--  <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="purchase_report_table table table_empty table-striped table-hover">

  <thead>

                <tr>

                    <th>Date</th>

                    <th>Document Number</th>

                    <th>Customer</th>

                    <th>Item Number</th>

                    <th>HSN</th>

                    <th>New Selling Price</th>

                    <th>Purchase Price</th>

                    <th>Quantity</th>

                    <th>Total</th>

                    <th>SGST</th>

                    <th>CGST</th>

                    <th>IGST</th>

                    <th>Tax Amount</th>

                    <th>Total Sales</th>

                    <th>Pending Payment</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

          </tfoot>

        </table>

  </div>

</div>

<div class="payable_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Payable Report"  data-toggle="tooltip" style="">Export Excel</button>

    

    </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Payable Report</h3>

    

    <!-- <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="payable_report_table table table_empty table-striped table-hover">

  <thead>

                        <tr>

                            <th>Supplier Name</th>

                            <th>Purchase Number</th>

                            <th>Total Purchase</th>

                            <th>Pending Payment</th>             

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                    <tfoot>

                  </tfoot>

                </table>

  </div>

</div>

<div class="daily_expenses_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Expense Report" 

    data-toggle="tooltip">Export Excel</button>

     </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">PettyCash Expenses</h3>

     <!--  <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="daily_expenses_table table table_empty table-striped table-hover">

              <thead>

                <tr>

                  <th>Date</th>

                  <th>Voucher Number</th>

                  <th>Expense Name</th>    

                  <th>Expense Amount</th>  

                  <th>Person Name</th>            

                  </tr>

              </thead>

              <tbody>

              </tbody>

              <tfoot>

              </tfoot>

          </table>

  </div>

</div>

<div class="daily_expenses_type_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <!-- <button type="button" class="btn btn-success report" data-name="Expense Report" 

    data-toggle="tooltip">Export Excel</button> -->

     </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Daily Reports By Type</h3>

     <!--  <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="daily_expenses__type_table table table_empty table-striped table-hover">

              <thead>

                <tr>                  

                  <th>Expense Name</th>    

                  <th>Expense Amount</th>            

                  </tr>

              </thead>

              <tbody>

              </tbody>

              <tfoot>

              </tfoot>

          </table>

  </div>

</div>

<div class="company_expenses" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="CompanyExpense Report" data-toggle="tooltip" style="">Export Excel</button>

    

    </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Company Expenses</h3>  

    <!-- <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="company_expenses_table table table_empty table-striped table-hover">

  <thead>

             <tr>

                <th>Date</th>

                <th>Voucher Number</th>  

                <th>Expenses Voucher Number</th> 

                <th>From Account</th>  

                <th>To Account</th>  

                <th>Ledger Name</th>  

                <th>Reference/Notes</th>    

                <th>Expense Amount</th>           

             </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

            </tfoot>

          </table>

  </div>

</div>

<div class="company_expenses_by_types" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <!-- <button type="button" class="btn btn-success report" data-name="CompanyExpense Report" data-toggle="tooltip" style="">Export Excel</button> -->

    

    </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Company Reports By Type</h3>  

    <!-- <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="company_expenses_types_table table table_empty table-striped table-hover">

  <thead>

             <tr>

                <th>Ledger Name</th>  

                <th>Expense Amount</th>           

             </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

            </tfoot>

          </table>

  </div>

</div>

<div class="customer_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Customer Report" style=""data-toggle="tooltip">Export Excel</button>

    

    </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Customer Report</h3>

    

   <!--  <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="customer_report_table table table_empty table-striped table-hover">

  <thead>

                      <tr>

                          <th>Customer Name</th>

                          <th>Customer Group</th>

                          <th>Credit Limit</th>

                          <th>Phone Number</th>

                          <th>City</th>

                          <th>Created On</th>              

                          <th>Total Sales</th>

                          <th>Pending</th>

                      </tr>

                  </thead>

                  <tbody>

                  </tbody>

                  <tfoot>

                </tfoot>

              </table>

  </div>

</div>

<div class="customer_vechile_report" >

  <div class="row form-group" style="float: right;margin-right: 80px">

    <button  type="button" class="btn btn-success back" >Back</button>&nbsp;&nbsp;

    <button type="button" class="btn btn-success report" data-name="Customers/Vehicles" data-toggle="tooltip"  style="">Export Excel </button>

  </div>

  <div  style="margin-top: 20px">

    <h6 style="text-align: center;">{{$branch}}</h6>

    <h3 style="text-align: center;">Customer & Vehicles Report</h3>

    

    <!-- <div style="display:none;" class="text-center no_data">There are no transactions between the selected period.</div> -->

  <table class="customer_vechile_report_table table table_empty table-striped table-hover">

                  <thead>

                <tr>

                    <th>Vehicle Number</th>

                    <th>Created On</th>

                    <th>Vehicle Name</th>

                    <th>Customer Name</th>

                    <th>Customer Group</th>

                    <th>Credit Limit</th>

                    <th>Phone Number</th>

                    <th>City</th>

                    <th>Total Sales</th>

                </tr>

            </thead>

            <tbody>

            </tbody>

            <tfoot>

             </tfoot>

           </table>

  </div>

</div>

<!-- ** view list end ** -->

@stop

@section('dom_links')

@parent

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

<!-- <script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/buttons_export_config_header.js') }}"></script>  -->

<script>

    var datatable = null;

    var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};



  $(document).ready(function() {

 datatable = $('#datatable').DataTable(datatable_options);



  

$('.all_ul').show();

  $('.stockflow_table').hide();

  $('.currentstock_datatable').hide();

  $('.low_stock_report').hide();

  $('.jobcard_low_stock_report').hide();

  $('.sales_report').hide();

  $('.receiveable_report').hide();

  $('.purchase_report').hide();

  $('.payable_report').hide();

  $('.company_expenses').hide();

  $('.customer_report').hide();

  $('.daily_expenses_report').hide();

  $('.customer_vechile_report').hide();

  $('.daily_expenses_type_report').hide(); 

  $('.company_expenses_by_types').hide(); 

  

  

$('.stock_flow').on('click',function(){

  $('.all_ul').hide();

  $('.stockflow_table').show();

  $('.low_stock_report').hide();

  $('.currentstock_datatable').hide();

  $('.receiveable_report').hide();

  $('.purchase_report').hide();

  $('.payable_report').hide();

  $('.company_expenses').hide();

  $('.customer_report').hide();

  $('.daily_expenses_report').hide();

  $('.customer_vechile_report').hide();

  $('.daily_expenses_type_report').hide(); 

  $('.company_expenses_by_types').hide(); 

   

});



$('.current_stock').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.low_stock_report').hide();

       $('.receiveable_report').hide();

       $('.currentstock_datatable').show();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.daily_expenses_report').hide();

       $('.customer_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

        

});



$('.low_stock_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.receiveable_report').hide();

       $('.low_stock_report').show();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

        $('.customer_vechile_report').hide();

        $('.daily_expenses_type_report').hide();

        $('.company_expenses_by_types').hide();  

         

});



$('.jobcard_low_stock_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.receiveable_report').hide();

       $('.jobcard_low_stock_report').show();

       $('.purchase_report').hide();

       $('.company_expenses').hide();

       $('.payable_report').hide();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

        

    });



$('.sales_report_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.receiveable_report').hide();

       $('.sales_report').show();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide();

       $('.company_expenses_by_types').hide();  

       

});



$('.receiveable_report_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').show();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

        

});



$('.purchase_report_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').show();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

        

});



$('.payable_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').hide();

       $('.payable_report').show();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

        

});



$('.company_expenses_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').show();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

       

});



$('.customer_report_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').show();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

        

});



$('.customer_vehicles_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.daily_expenses_report').hide();

       $('.customer_vechile_report').show();

       $('.daily_expenses_type_report').hide(); 

       $('.company_expenses_by_types').hide(); 

        

});



$('.daily_expenses_button').on('click',function(){

       $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_report').show();

       $('.daily_expenses_type_report').hide();

       $('.company_expenses_by_types').hide(); 

        

});

$('.daily_expenses_type').on('click',function(){

      $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_report').hide();

       $('.daily_expenses_type_report').show();

        $('.company_expenses_by_types').hide();

});



$('.company_expenses_type').on('click',function(){

      $('.all_ul').hide();

       $('.stockflow_table').hide();

       $('.currentstock_datatable').hide();

       $('.low_stock_report').hide();

       $('.jobcard_low_stock_report').hide();

       $('.sales_report').hide();

       $('.receiveable_report').hide();

       $('.purchase_report').hide();

       $('.payable_report').hide();

       $('.company_expenses').hide();

       $('.customer_report').hide();

       $('.customer_vechile_report').hide();

       $('.daily_expenses_report').hide();

       $('.daily_expenses_type_report').hide();

       $('.company_expenses_by_types').show();

});



  



$('.back').on('click',function(){

    $('.all_ul').show();

    $('.stockflow_table').hide();

    $('.currentstock_datatable').hide();

    $('.low_stock_report').hide();

    $('.jobcard_low_stock_report').hide();

    $('.sales_report').hide();

    $('.receiveable_report').hide();

    $('.purchase_report').hide();

    $('.payable_report').hide();

    $('.company_expenses').hide();

    $('.customer_report').hide();

    $('.customer_vechile_report').hide();

    $('.daily_expenses_report').hide();

    $('.daily_expenses_type_report').hide();

    $('.company_expenses_by_types').hide();

   

  });

       

       $('[data-toggle="tooltip"]').tooltip(); 

   /* $('body').on('click', '.csv_export', function(){

    $(".buttons-csv")[0].click(); //trigger the click event

  });*/

var currentTime = new Date(); 

var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);

/*From date for all text box*/

$(".from_date").datepicker().datepicker("setDate",startDateFrom);

$('#from-date').datepicker({

todayHighlight: true,

autoclose:true

 });

/*end*/



/*To date for all text box*/

 $(".to_date").datepicker().datepicker("setDate",currentTime);

$('#to-date').datepicker({

todayHighlight: true,

autoclose:true

 });

/*end*/

var financial_year=null;

/*Options to all*/

var stock_datatable = null;

var low_stock_datatable = null;

var stock_flow=null;

var invoice_datatable = null;

var purchase_dateatable = null;

var customer_datatable = null;

var expense_datatable = null;

var customerreport_datatable=null;

var receiveablereport_datatable=null;

var payable_datatable=null;

var all_lowstockreport_datatable=0;



  stock_datatable =  $('#stock_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Stock Report', 'title': 'Stock Report', exportOptions: { columns: ":not(.noExport)" },  footer: true }, ],

    fixedHeader: {

            header: true,

            footer: true

        }

 



});

all_lowstockreport_datatable =  $('#all_low_stock_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'ALL Low Stock Report', 'title': 'ALL Low Stock Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

    fixedHeader: {

            header: true,

            footer: true

        }





});



  low_stock_datatable =  $('#low_stock_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Low Stock Report', 'title': 'Low Stock Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

    fixedHeader: {

            header: true,

            footer: true

        }





});

   stock_flow_datatable =  $('#stock_flow_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Stock flow', 'title': 'Stock flow', 

    exportOptions: { format: {

                            header: function ( data, columnIdx ) {

                                if(columnIdx==1){

                                return 'column_1_header';

                                }

                                else{

                                return data;

                                }

                            }

                        } },  footer: true } ],

    fixedHeader: {

            header: true,

            footer: true



        }

 



});



  invoice_datatable =  $('#invoice_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: '(Invoice Report', 'title': 'Invoice Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],fixedHeader: {

            header: true,

            footer: true

        }





});



  purchase_datatable =  $('#purchase').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Purchase Report', 'title': 'Purchase Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],fixedHeader: {

            header: true,

            footer: true

        }





});



 customer_datatable =  $('#customer_vehicles').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Customer & Vehicles', 'title': 'Customer & Vehicles details', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],fixedHeader: {

            header: true,

            footer: true

        }





});



 expense_datatable =  $('#daily_expenses').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Daily Expense Report', 'title': 'Daily Expense Report', exportOptions: { columns: ":not(.noExport)" },  footer: true }, ],

    fixedHeader: {

            header: true,

            footer: true

        }

 



});

  company_expense_datatable =  $('#company_expenses').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Company Expense Report', 'title': 'CompanyExpense Report', exportOptions: { columns: ":not(.noExport)" },  footer: true }, ],

    fixedHeader: {

            header: true,

            footer: true

        }

 



});

customerreport_datatable =  $('#customer_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Customer Reports', 'title': 'Customer Reports details', exportOptions: { columns: ":not(.noExport)" },  footer: true }, ],

    fixedHeader: {

            header: true,

            footer: true

        }

 



});

receiveablereport_datatable =  $('#receiveable_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Receiveable Reports', 'title': 'Receiveable Reports details', exportOptions: { columns: ":not(.noExport)" },  footer: true }, ],

    fixedHeader: {

            header: true,

            footer: true

        }

 



});

 payable_datatable =  $('#payable_report').DataTable({



    dom: 'B',

    buttons: [ { extend: 'excel', filename: 'Peyable Reports', 'title': 'Payable Report details', exportOptions: { columns: ":not(.noExport)" },  footer: true }, ],

    fixedHeader: {

            header: true,

            footer: true

        }



});

  /*end*/

  /*When click show*/

  $('.show').on('click',function(){

    var from_date = $('.from_date').val();

    var to_date = $('.to_date').val();

    $('.stock_report_date').empty().append(from_date+'-'+to_date);

      $.ajax({

      url : '{{ route('get_report_details') }}',

      type: 'POST',

      data:

      {

        _token: '{{ csrf_token() }}',

        from_date: from_date,

        to_date : to_date

      },

      success:function(data,textStatus,jqXHR)

      { 

        console.log(data.data);

        

        $('.invoice_report').find("[data-value='no_of_all_invoice']").text(data.total_invoice.total_invoice);

        $('.invoice_report').find("[data-value='no_of_cash_invoice']").text(data.total_cashinvoice.total_cash_invoice);

        $('.invoice_report').find("[data-value='no_of_credit_invoice']").text(data.total_creditinvoice.total_invoice_credit);

        $('.invoice_report').find("[data-value='pending_invoice']").text(data.credit_invoice_pending);

        $('.invoice_report').find("[data-value='total_cash_value']").text(data.total_cashinvoice_value);

       var total_cashvalue;

        if(data.total_creditinvoice_value==null){

              total_cashvalue="0.00"

        }else{

          total_cashvalue=parseFloat(data.total_creditinvoice_value).toFixed(2);



        }

        $('.invoice_report').find("[data-value='total_credit_value']").text(total_cashvalue);

        if(data.total_invoice_value==null)

        {

          var total_invoice_data="0.00";



        }else{

          var total_invoice_data=data.total_invoice_value;

        }

        $('.invoice_report').find("[data-value='total_invoice_value']").text(total_invoice_data);

         $('.invoice_report').find("[data-value='total_receivables']").text(data.total_receivables);

        $('.customer_details').find("[data-value='no_of_customers']").text(data.total_customers.total_customers);

        $('.customer_details').find("[data-value='total_vehicles']").text(data.total_vehicles.total_vehicles);

        $('.customer_details').find("[data-value='total_newcustomers']").text(data.total_newcustomers);

        $('.customer_details').find("[data-value='total_newvehicles']").text(data.total_newvehicles);

        $('.purchase_report_data').find("[data-value='no_of_purchase']").text(data.total_purchase.total_purchase);

        $('.purchase_report_data').find("[data-value='total_purchase']").text(data.total_purchase.total_purchase_value);

         $('.purchase_report_data').find("[data-value='total_payable']").text(parseFloat(data.total_payable).toFixed(2));

        $('.stack_report').find("[data-value='no_of_items']").text(data.total_items);

        $('.stack_report').find("[data-value='stack_total_value']").text(data.total_items_value);

        $('.stack_report').find("[data-value='totol_goods_purchase']").text(parseFloat(data.total_purchase_sale).toFixed(2));

        $('.stack_report').find("[data-value='total_goods_sale']").text(parseFloat(data.total_goods_sale).toFixed(2));

         $('.stack_report').find("[data-value='diference_period_amount']").text(parseFloat(data.total_goods_sale-data.total_purchase_sale).toFixed(2));

         $('.stack_report').find("[data-value='count_lowstock']").text(data.no_lowstock_items);

         var lowstock_amount;

        if(data.lowstock_amount==null){

          lowstock_amount="0.00";

        }else{

          lowstock_amount=parseFloat(data.lowstock_amount).toFixed(2);

        }

         $('.stack_report').find("[data-value='lowstock_amount']").text(lowstock_amount);

        $('.low_stack_report').find("[data-value='no_of_items']").text(data.jc_job_card_total);

        $('.low_stack_report').find("[data-value='lowstack_total_purchase']").text(data.jc_job_card_total_amount);

        

        $('.invoice_report').find("[data-value='sales_by_spares']").text(parseFloat(data.sales_by_spares).toFixed(2));

        $('.invoice_report').find("[data-value='sales_by_works']").text(data.sales_by_works);

        $('.expense_details').find("[data-value='no_of_expenses']").text(data.total_expenses.total_expenses);

        var total_expense;

        if(data.total_expenses.total==null){

         total_expense='0.00';

         }else{

          total_expense=parseFloat(data.total_expenses.total).toFixed(2);

         }

        $('.expense_details').find("[data-value='total_expense']").text(total_expense);



        var total_company_expense;

        

        if(data.company_expense.expenses_total==null){

         total_company_expense='0.00';

         }else{

          total_company_expense=parseFloat(data.company_expense.expenses_total).toFixed(2);

         }

        $('.expense_details1').find("[data-value='total_expense1']").text(total_company_expense);

        $('.result').css('display','block');
        stock_flow_report(from_date,to_date)

               

      },

      error:function()

      {



      }

    });

  });

  /*end*/
function stock_flow_report(from_date,to_date){

  var starting_date = moment(from_date, "MM/DD/YYYY").format("YYYY-MM-DD");
  var ending_date = moment(to_date, "MM/DD/YYYY").format("YYYY-MM-DD");
   
     $.ajax({
           url: "{{ route('get_stock_report') }}",
            type: 'post',
            data: {
                _token: $('input[name=_token]').val(),
                start_date:starting_date,
                end_date: ending_date
            },
            dataType: "json",
            success: function(data, textStatus, jqXHR) {     
         
            //stock flow   
                    var res = data.result;   
                    $('.transaction_table').empty();
                     datatable.destroy();
                    var stockflow_table = 
                      '<table class="table table_empty table-striped table-hover" id="datatable flow" style="margin-left: center;"><thead><tr><th width="20%">Item</th><th width="20%" colspan="2">Opening Balance</th><th width="20%" colspan="2">Inwards</th><th width="20%" colspan="2">Outwards</th><th width="20%" colspan="2">Closing Balance</th></tr><tr><td></td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> </tr></thead> <tbody>';     
                    for(var i in res)
                    {          
                      stockflow_table += '<tr><td><a href="javascript:;" data-id="'+res[i].entry_id+'" class="grid_label  process_inventory">'+res[i].item_name+'</a></td><td>'+res[i].opening_quantity+'</td><td>'+res[i].opening_value+'</td><td>'+res[i].inwards_quantity+'</td><td>'+res[i].inwards_value+'</td><td>'+res[i].outwards_quantity+'</td> <td>'+res[i].outwards_value+'</td><td>'+res[i].closing_quantity+'</td><td>'+res[i].closing_value+'</td></tr>';
                     }         

                    stockflow_table += '<tfoot><tr><th style="padding:10px" width="20%">Grand Total</th><td><b>'+data.grand_quantity+'</b></td><td><b>'+data.grand_value+'</b></td><td><b>'+data.grand_inwards_quantity+'</b></td> <td><b>'+data.grand_inwards_value+'</td></b> <td><b>'+data.grand_outwards_quantity+'</b></td> <td><b>'+data.grand_outwards_value+'</b></td><td><b>'+data.grand_closing_quantity+'</b></td><td><b>'+data.grand_closing_value+'</b></td></tr></tfoot>';
                      stockflow_table +=   '</table>';
                      $('.transaction_table').append(stockflow_table);
                       if($('#flow').length>0){
                          $('#flow').DataTable()
                        }
                          if($('.c_v').length > 0 ){
                               $('.c_v').DataTable(datatable_options)
                              }                 
            //*finish*
              $('.loader_wall').hide();
                removeSign();
            },

            error: function(jqXHR, textStatus, errorThrown) {
                //alert("New Request Failed " +textStatus);
            }
        });
  }


  /*when click any report*/

  $('.report').on('click',function()

  {

      var html = ``;

      var foot = ``;

      var url = window.location.href;

      var page = $.trim($('.page-title').clone().find('a').remove().end().text());

      var obj= $(this);

      var name = obj.attr('data-name');       

      var from_date = $('.from_date').val();

      var to_date = $('.to_date').val();

       $.ajax({

        url : '{{ route('export_report') }}',

        type: 'POST',

        data:

        {

        _token: '{{ csrf_token() }}',

        name: name,

        from_date: from_date,

        to_date : to_date,

        financial_date:financial_year

       },

      dataType: "json",

      success:function(data,textStatus,jqXHR)

      { 

      if(data.name == "Stock Report")

            {

                  stock_datatable.destroy();

                 $('#stock_report tbody').empty();

                 $('#stock_report tfoot').empty();

                  var stock_report = data.stock_report;

                  var total_sale_value = 0;

                  var total_purchase_value = 0;

                  var total_purchase_price = 0;

                  var total_selling_price = 0;

                  for (var i in stock_report) {

                  var tax_value = stock_report[i].tax_value;

                  var selling_amount = stock_report[i].selling_price;

                  var purchase_price = stock_report[i].purchase_price;

                  if(purchase_price == null){

                    purchase_price = 0.0;

                  }else{

                    purchase_price = stock_report[i].purchase_price;

                  }

                  var tax_amount = parseFloat(isNaN(tax_value) ? 0 : tax_value/100 ) * parseFloat(selling_amount);  

                  var selling_rate = (parseFloat(selling_amount) + parseFloat(tax_amount));

                  var purchase_price_with_tax = (parseFloat(purchase_price) + parseFloat(tax_amount));



                  var base_price = stock_report[i].base_price;

                  var in_stock = stock_report[i].in_stock;

                  if(in_stock > 0){

                    in_stock = stock_report[i].in_stock;

                  }else{

                    in_stock = 0;

                  }

                  var total_stock_value = (parseFloat(selling_rate) * parseFloat(in_stock));

                  var total_purchase = (parseFloat(purchase_price_with_tax) * parseFloat(in_stock));

                  total_sale_value =    total_sale_value + selling_rate;

                  total_purchase_value = total_purchase_value + purchase_price_with_tax;

                  total_purchase_price =total_purchase_price + total_purchase;

                  total_selling_price = total_selling_price + total_stock_value;

                  html += `<tr>

                  <td>`+stock_report[i].id+`</td>

                  <td>`+stock_report[i].main_category_name+`</td>

                  <td>`+stock_report[i].category_name+`</td>

                  <td>`+stock_report[i].type_name+`</td>

                  <td>`+stock_report[i].make_name+`</td>

                  <td>`+stock_report[i].identifier_a+`</td>

                  <td>`+stock_report[i].name+`</td>

                  <td>`+stock_report[i].hsn+`</td>

                  <td>`+purchase_price+`</td>

                  <td>`+parseFloat(purchase_price_with_tax).toFixed(2)+`</td>

                  <td>`+tax_amount+`</td>

                  <td>`+selling_amount+`</td>

                  <td>`+selling_rate+`</td>

                  <td>`+in_stock+`</td>

                  <td>`+total_purchase+`</td>

                  <td>`+total_stock_value+`</td>

                  </tr>`;

                  }



                  foot += `<tr>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td>Total Items = `+data.total_in_stock+`</td>

                  <td></td>

                  <td></td>

                  <td>Total = `+parseFloat(total_purchase_value).toFixed(2)+`</td>

                  <td></td>

                  <td></td>

                  <td>Total = `+parseFloat(total_sale_value).toFixed(2)+`</td>

                  <td></td>

                  <td>Total Purchase Value = `+total_purchase_price+`</td>

                  <td>Total Sale Value = `+total_selling_price+`</td>

                  </tr>`

                  $('#stock_report tbody').append(html);

                  $('#stock_report tfoot').append(foot);

                  stock_datatable =  $('#stock_report').DataTable({



                  dom: 'B',

                  buttons: [ { extend: 'excel', filename: 'Stock Report', 'title': 'Stock Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                  fixedHeader: {

                          header: true,

                          footer: true

                      }





                  });

                         stock_datatable.button('.buttons-excel' ).trigger();



            }

      else if(data.name == "All Low Stock Report")

            {

                   all_lowstockreport_datatable.destroy();

                  $('#all_low_stock_report tbody').empty();

                  $('#all_low_stock_report tfoot').empty();

                  var all_low_stock = data.low_stock;





                  for (var i in all_low_stock) {



                  var identifier_a = all_low_stock[i].identifier_a;

                    if(identifier_a == null){

                    identifier_a ='';

                  }else{

                    identifier_a = all_low_stock[i].identifier_a;

                  }



                  var tax_value = all_low_stock[i].tax_value;

                    if(tax_value == undefined){

                        tax_value = 0.00;

                      }else{

                        tax_value = parseFloat( isNaN(all_low_stock[i].tax_value) ? 0 : all_low_stock[i].tax_value/100 );

                     }



                  var selling_price = all_low_stock[i].selling_price;

                    if(selling_price == undefined){

                        selling_price = 0;

                      }

                    else{

                        selling_price = all_low_stock[i].selling_price;

                      }



                  var moq = all_low_stock[i].moq;

                    if(moq == undefined){

                        moq = 1;

                      }

                    else{

                        moq = all_low_stock[i].moq;

                      }

                  var amount=(selling_price*moq);

                  var tax_amount=(amount*tax_value);

                  

                  var purchase_price = all_low_stock[i].purchase_price;

                    if(purchase_price == undefined){

                        purchase_price = 0;

                      }

                    else{

                        purchase_price = all_low_stock[i].purchase_price;

                      }

                    var purchase_amountwithtax=(Number(purchase_price)+Number(tax_amount)).toFixed(2);

                  

               

                    var total_amount=(purchase_amountwithtax*moq).toFixed(2);



                

                    

                    html += `<tr>

                    <td>`+all_low_stock[i].item_id+`</td>

                    <td>`+all_low_stock[i].main_category_name+`</td>

                    <td>`+all_low_stock[i].category_name+`</td>

                    <td>`+all_low_stock[i].type_name+`</td>

                    <td>`+all_low_stock[i].make_name+`</td>

                    <td>`+all_low_stock[i].identifier_a+`</td>

                    <td>`+all_low_stock[i].item_name+`</td>

                    <td>`+all_low_stock[i].hsn+`</td>

                    <td>`+all_low_stock[i].in_stock+`</td>

                          

                    <td>`+purchase_amountwithtax+`</td>

                    <td>`+moq+`</td>

                    <td>`+total_amount+`</td>

                 

                     </tr>`;

                    }

               

                    foot += `<tr>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td>Total Items=`+data.total_items+`</td>

                    <td></td>

                    <td></td>

                    <td>Total Purchase Amount=`+data.total_purchase_price+`</td>

                    <td></td>

                    <td>Total Amount=`+data.total_payment+`</td>

                    </tr>`;

                    $('#all_low_stock_report tbody').append(html);

                    $('#all_low_stock_report tfoot').append(foot);

                     all_lowstockreport_datatable =  $('#all_low_stock_report').DataTable({



                      dom: 'B',

                      buttons: [ { extend: 'excel', filename: 'All Low Stock Report', 'title': 'All Low Stock Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                      fixedHeader: {

                              header: true,

                              footer: true

                          }





                       });

                    all_lowstockreport_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "Job card Low Stocks")

            { 

                  low_stock_datatable.destroy();

                  $('#low_stock_report tbody').empty();

                  $('#low_stock_report tfoot').empty();



                  var low_stock = data.low_stock_reports;



                  for (var i in low_stock) {

                    var tax_value = low_stock[i].tax_value;

                    if(tax_value == undefined){

                        tax_value = 0.00;

                      }else{

                        tax_value = low_stock[i].tax_value;

                     }

                    

                     var rate=low_stock[i].rate;

                     var quantity=low_stock[i].qty;

                    



                          

                    var selling_price = low_stock[i].selling_price;



                    var purchase_price = low_stock[i].purchase_price;

                    

                    var tax_amount = parseFloat(isNaN(tax_value) ? 0 : tax_value/100 ) * parseFloat(selling_price);

                  

                   

                    var sale_price = (parseFloat(selling_price) + parseFloat(tax_amount));

                   

                    var purchase = (parseFloat(purchase_price) + parseFloat(tax_amount)).toFixed(2);

                   

                   

                   

                   var total_amount=(Number(purchase)*Number(quantity)).toFixed(2);



                    

                    html += `<tr>

                 

                  <td>`+low_stock[i].jc_no+`</td>

                  <td>`+low_stock[i].registration_no+`</td>

                  <td>`+low_stock[i].name+`</td>

                  <td>`+quantity+`</td>

                  <td>`+low_stock[i].in_stock+`</td>

                  <td>`+purchase+`</td> 

                  <td>`+total_amount+`</td>         

                  <td>`+low_stock[i].assigned_to+`</td>

                  </tr>`;

                  }

                  foot += `<tr>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td></td>

                  <td>Total Amount=`+data.total_amount+`</td>

                  <td></td>

                  </tr>`;

                  $('#low_stock_report tbody').append(html);

                  $('#low_stock_report tfoot').append(foot);

                   low_stock_datatable =  $('#low_stock_report').DataTable({



                    dom: 'B',

                    buttons: [ { extend: 'excel', filename: 'Low Stock Report', 'title': 'Low Stock Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                    fixedHeader: {

                            header: true,

                            footer: true

                       }

                     });

                    low_stock_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "Invoice Report")

            {  

                var exact_csgt='';

                var exact_igst = '';

                var cgst='';

                var igst='';

                invoice_datatable.destroy();

                 $('#invoice_report tbody').empty();

                 $('#invoice_report tfoot').empty();

                var invoice_report = data.invoice_report;

                for (var i in invoice_report) {

                  var tax_type = invoice_report[i].tax_type;

                  var gst = invoice_report[i].gst;

                  if(gst == null){

                    gst = '';

                  }else{

                    gst = invoice_report[i].gst;

                  }

                  var tax = invoice_report[i].taxes;

                  var discount = invoice_report[i].discount;

                  var payment_mode = invoice_report[i].payment_mode;

                  if(payment_mode == null){

                    payment_mode = '';

                  }else{

                    payment_mode = invoice_report[i].payment_mode;

                  }

                  if(discount == null){

                    discount = '';

                  }else{

                    discount = invoice_report[i].discount;

                  }



                  if(tax_type == 1){

                     exact_csgt = tax.split('CGST');

                      cgst = exact_csgt[0];

                      igst = '';

                  }else if(tax_type == 2){

                    exact_igst = tax.split('IGST');

                     igst = exact_igst[0];

                     cgst = '';

                  }



                  if(cgst == 'undefined'){

                    cgst = '';



                  }



                  if(igst == 'undefined'){

                    igst = '';

                    }

                      var amount = (invoice_report[i].rate*invoice_report[i].quantity).toFixed(2);

                      var tax_amount = (amount*invoice_report[i].tax_value).toFixed(2);

                      var sale_type = invoice_report[i].sale_type

                      if(sale_type == 'Job Invoice Cash'){

                        sale_type = 'Cash';

                      }else if(sale_type == 'Job Invoice Credit'){

                        sale_type = 'Credit';

                      }else if(sale_type == null){

                        sale_type = '';

                      }

                   

                   

                      html += `<tr>

                    <td>`+invoice_report[i].date+`</td>

                    <td>`+invoice_report[i].order_no+`</td>

                    <td>`+invoice_report[i].customer+`</td>

                    <td>`+invoice_report[i].category+`</td>

                    <td>`+invoice_report[i].name+`</td>

                    <td>`+invoice_report[i].hsn+`</td>

                    <td>`+invoice_report[i].rate+`</td>

                    <td>`+invoice_report[i].quantity+`</td>

                    <td>`+invoice_report[i].amount+`</td>

                    <td>`+discount+`</td>

                    <td>`+cgst+`</td>

                    <td>`+cgst+`</td>

                    <td>`+igst+`</td>

                    <td>`+gst+`</td>

                    <td>`+invoice_report[i].total_sales+`</td>

                    <td>`+invoice_report[i].pending_payment+`</td>

                    <td>`+payment_mode+`</td>

                    <td>`+sale_type+`</td>

                    </tr>`;

             

                    }

                    foot += `<tr>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td>Total = `+data.total_sales+`</td>

                    <td>Total Pending = `+data.total_pending+`</td>

                    <td></td>

                    <td></td>

                    </tr>`;

                    $('#invoice_report tbody').append(html);

                    $('#invoice_report tfoot').append(foot);

                    invoice_datatable =  $('#invoice_report').DataTable({



                    dom: 'B',

                    buttons: [ { extend: 'excel', filename: 'Invoice Report', 'title': 'Invoice Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                      fixedHeader: {

                            header: true,

                            footer: true

                }



                  });

                    invoice_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "Purchase Report")

            {

                    var purchase_report = data.purchase_reports;

                    var tax='';

                    var csgt='';

                    var igst='';

                    var csgt_value='';

                    var igst_value='';

                    purchase_datatable.destroy();

                    $('#purchase tbody').empty();

                    $('#purchase tfoot').empty();

                    for (var i in purchase_report) {

                      var tax_type = purchase_report[i].tax_type;

                      var tax_value = parseFloat( isNaN(purchase_report[i].tax_value) ? 0 : purchase_report[i].tax_value/100 );

                      var amount = (purchase_report[i].rate*purchase_report[i].quantity).toFixed(2);

                      var tax_amount = (amount*tax_value).toFixed(2);

                      if(tax_type == 1){

                         tax = purchase_report[i].tax;

                        cgst = tax.split('CGST');

                        csgt_value = cgst[0];

                      }else if(tax_type == 2){

                         tax = purchase_report[i].tax;

                         igst = tax.split('IGST');

                         igst_value = igst[0];

                      } 

                  

                        html += `<tr>

                      <td>`+purchase_report[i].date+`</td>

                      <td>`+purchase_report[i].order_no+`</td>

                      <td>`+purchase_report[i].customer+`</td>

                      <td>`+purchase_report[i].item_name+`</td>

                      <td>`+purchase_report[i].hsn+`</td>

                      <td>`+purchase_report[i].new_selling_price+`</td>

                      <td>`+purchase_report[i].purchase_price+`</td>

                      <td>`+purchase_report[i].quantity+`</td>

                      <td>`+purchase_report[i].total_amount+`</td>

                      <td>`+csgt_value+`</td>

                      <td>`+csgt_value+`</td>

                      <td>`+igst_value+`</td>

                      <td>`+tax_amount+`</td>

                      <td>`+purchase_report[i].total_sales+`</td>

                      <td>`+purchase_report[i].amount+`</td>

                      </tr>`;

                      }

                      foot += `<tr>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td>Total = `+data.total_purchase+`</td>

                      <td></td>

                      </tr>`;

                      $('#purchase tbody').append(html);

                      $('#purchase tfoot').append(foot);

                       purchase_datatable =  $('#purchase').DataTable({



                        dom: 'B',

                      buttons: [ { extend: 'excel', filename: 'Purchase Report', 'title': 'Purchase Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                      fixedHeader: {

                            header: true,

                            footer: true

                        }



                        });

                  purchase_datatable.button('.buttons-excel' ).trigger();



            }

      else if(data.name == "Customers/Vehicles")

            {

                      var customer_vehicles = data.vehicle_details;

                      customer_datatable.destroy();

                $('#customer_vehicles tbody');

                $('#customer_vehicles tfoot');

                for (var i in customer_vehicles) {

                var group_name =customer_vehicles[i].group_name;

                var credit_limit = customer_vehicles[i].credit_limit;

                var total_sale = customer_vehicles[i].total_sale;



                if(total_sale == null){

                  total_sale = '';

                }else{

                  total_sale =  customer_vehicles[i].total_sale;

                }

                if(credit_limit == null){

                  credit_limit = '';

                }else{

                  credit_limit =  customer_vehicles[i].credit_limit;

                }

                if(group_name == null){

                  group_name = '';

                }else{

                  group_name =customer_vehicles[i].group_name;

                }

                html += `<tr>

                        <td>`+customer_vehicles[i].registration_no+`</td>

                        <td>`+customer_vehicles[i].created_at+`</td>

                        <td>`+customer_vehicles[i].vehicle_name+`</td>

                        <td>`+customer_vehicles[i].customer+`</td>

                        <td>`+group_name+`</td>

                        <td>`+credit_limit+`</td>

                        <td>`+customer_vehicles[i].mobile_no+`</td>

                        <td>`+customer_vehicles[i].city+`</td>

                        <td>`+total_sale+`</td>

                      </tr>`;

                        }



                        foot += `<tr>

                        <td>Total Vehicle = `+data.total_customer+`</td>

                        <td></td>

                        <td>Total Customer = `+data.total_vehicle+`</td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td>Total Sale = `+data.total_sales+`</td>

                      </tr>`;

                        $('#customer_vehicles tbody').append(html);

                        $('#customer_vehicles tfoot').append(foot);

                       customer_datatable =  $('#customer_vehicles').DataTable({



                  dom: 'B',

                  buttons: [ { extend: 'excel', filename: 'Customer & Vehicles', 'title': 'Customer & Vehicles details', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                  fixedHeader: {

                          header: true,

                          footer: true

                      }





              });



                    customer_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name =="Expense Report")

          {  

                expense_datatable.destroy();

               $('#daily_expenses tbody').empty();

               $('#daily_expenses tfoot').empty();

                  var expense_reports = data.expenses_reports;

                   var total_expenses = data.total_expenses;

                  for (var i in expense_reports) {

                    var employee = expense_reports[i].first_name;

                    if(employee == null){

                      employee = "";

                    }else{

                    employee = expense_reports[i].first_name;

                    }

              html += `<tr>

                    <td>`+expense_reports[i].date+`</td>

                    <td>`+expense_reports[i].voucher_no+`</td>

                    <td>`+expense_reports[i].display_name+`</td>

                    <td>`+expense_reports[i].exp_amount+`</td>

                    <td>`+employee+`</td>

                    <td></td>

                  </tr>`;

                }

                foot += `<tr>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td>Total: `+total_expenses+`</td>

                        <td></td>

                        <td></td>

                        </tr>`

             $('#daily_expenses tbody').append(html);

             $('#daily_expenses tfoot').append(foot);

              expense_datatable =  $('#daily_expenses').DataTable({

                dom: 'B',

             buttons: [ { extend: 'excel', filename: 'Daily Expenses Report', 'title': 'Daily  Expenses Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

             fixedHeader: {

                header: true,

                footer: true

              }

            });

            expense_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "Customer Report")

            {

              var customer_details = data.customer_details;



                customerreport_datatable.destroy();

               $('#customer_report tbody').empty();

                $('#customer_report tfoot').empty();

               for (var i in customer_details) {

                var group_name =customer_details[i].group_name;       

                var credit_limit = customer_details[i].credit_limit;

                var total_sale = customer_details[i].total_sale;

                if(total_sale == null){

                  total_sale = '';

                }else{

                  total_sale =  customer_details[i].total_sale;

                }

                if(credit_limit == null){

                  credit_limit = '';

                }else{

                  credit_limit =  customer_details[i].credit_limit;

                }

                if(group_name == null){

                  group_name = '';

                }else{

                  group_name =customer_details[i].group_name;

                }

                html += `<tr>

                <td>`+customer_details[i].customer+`</td>

                <td>`+group_name+`</td>

                <td>`+credit_limit+`</td>

                <td>`+customer_details[i].mobile_no+`</td>

                <td>`+customer_details[i].city+`</td>

                <td>`+customer_details[i].created_at+`</td>

                <td>`+total_sale+`</td>

                <td>`+customer_details[i].pending+`</td>

               

                </tr>`;

                }



                foot += `<tr>

                <td>Total Customer = `+data.total_customer+`</td>

                <td></td>

                <td></td>

                <td></td>

                <td></td>

                <td></td>

                <td>Total Sales= `+data.total_sale+`</td>

                <td> Total pending amount= `+data.pending_payment+`</td>

                </tr>`;

                $('#customer_report tbody').append(html);

                $('#customer_report tfoot').append(foot);

                customerreport_datatable =  $('#customer_report').DataTable({



                dom: 'B',

                buttons: [ { extend: 'excel', filename: 'Customer Reports', 'title': 'Customer Reports details', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                fixedHeader: {

                        header: true,

                        footer: true

                    }

                 });

                customerreport_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "Receivable Report")

            {  

                var receivables = data.receivables; 

                receiveablereport_datatable.destroy();

                $('#receiveable_report tbody').empty();

                $('#receiveable_report tfoot').empty();

                 for (var i in receivables) {                       

                var total_sale = receivables[i].total_sale;

                if(total_sale == null){

                  total_sale = '';

                }else{

                  total_sale =  receivables[i].total_sale;

                }                     

                html += `<tr>

                <td>`+receivables[i].customer_name+`</td>

                <td>`+receivables[i].invoice_number+`</td>

                <td>`+total_sale+`</td>

                <td>`+receivables[i].pending+`</td>         

                </tr>`;

                }

                foot += `<tr>

                <td>Total Customer = `+data.total_customer+`</td>

                <td></td>

                <td>Total Sales= `+data.total_sale+`</td>

                 <td>Total Pending Amount= `+data.pending_payment+`</td>          

                </tr>`;

                $('#receiveable_report tbody').append(html);

                $('#receiveable_report tfoot').append(foot);

                receiveablereport_datatable =  $('#receiveable_report').DataTable({

                dom: 'B',

                buttons: [ { extend: 'excel', filename: 'Receiveable Reports', 'title': 'Receiveable Reports details', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                fixedHeader: {

                        header: true,

                        footer: true

                    }

                 });

                receiveablereport_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "Payable Report")

            {

              var payable = data.payable_report;

              var total_supplier = data.total_supplier;

              if(total_supplier == null){

                  total_supplier = '';

                }else{

                  total_supplier = data.total_supplier;

                }   

              payable_datatable.destroy();

              $('#payable_report tbody').empty();

              $('#payable_report tfoot').empty();        

               for (var i in payable) {                  

                  var total_sale = payable[i].total_sale;



                if(total_sale == null){

                  total_sale = '';

                }else{

                  total_sale =  payable[i].total_sale;

                }            

               

                html += `<tr>

                <td>`+payable[i].supplier+`</td>

                <td>`+payable[i].purchase_number+`</td>

                <td>`+total_sale+`</td>

                <td>`+payable[i].pending+`</td>           

                </tr>`;

                }



                foot += `<tr>

                <td>Total Supplier = `+total_supplier+`</td>

                <td></td>

                <td>Total Purchase(Rs)= `+parseFloat(data.total_sale).toFixed(2)+`</td>

                <td>Total Pending Amount= `+parseFloat(data.pending_payment).toFixed(2)+`</td>          

                </tr>`;



                $('#payable_report tbody').append(html);

                $('#payable_report tfoot').append(foot);



               payable_datatable =  $('#payable_report').DataTable({

                dom: 'B',

                buttons: [ { extend: 'excel', filename: 'Payable Reports', 'title': 'Payable Report details', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                fixedHeader: {

                        header: true,

                        footer: true

                    }





                 });



               payable_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "CompanyExpense Report")

            {

               company_expense_datatable.destroy();

               $('#company_expenses tbody').empty();

               $('#company_expenses tfoot').empty();

               var company_expenses_reports = data.company_expenses_report;

               var total_company_expense = data.total_company_expenses;

              

               for (var i in company_expenses_reports) {

               var employee = company_expenses_reports[i].first_name;

                if(employee == null){

                  employee = "";

                }else{

                  employee = company_expenses_reports[i].first_name;

                }

                 html += `<tr>

                    <td>`+company_expenses_reports[i].date+`</td>

                    <td>`+company_expenses_reports[i].voucher_no+`</td>

                    <td>`+company_expenses_reports[i].expense_voucher+`</td>

                    <td>`+company_expenses_reports[i].from_account+`</td>

                    <td>`+company_expenses_reports[i].to_account+`</td>

                    <td>`+company_expenses_reports[i].ledger_name+`</td>

                    <td>`+company_expenses_reports[i].reference+`</td> 

                    <td>`+company_expenses_reports[i].expense_amount+`</td>       

                  </tr>`;

                 }

                 foot += `<tr>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td>Total: `+parseFloat(total_company_expense).toFixed(2)+`</td>

                        </tr>`

                 $('#company_expenses tbody').append(html);

                 $('#company_expenses tfoot').append(foot);

                company_expense_datatable =  $('#company_expenses').DataTable({



                  dom: 'B',

                  buttons: [ { extend: 'excel', filename: 'Company Expenses Report', 'title': 'Company Espenses Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                  fixedHeader: {

                          header: true,

                          footer: true

                      }

                  });

                company_expense_datatable.button('.buttons-excel' ).trigger();

            }

      else if(data.name == "Stock Flow")

            {

                stock_flow_datatable.destroy();

                $('#stock_flow_report tbody').empty();

                var stock_flow_data = data.result;

                var opening_quantity=data.grand_quantity;

                var opening_value=data.grand_value;

                var inwards_quantity=data.grand_inwards_quantity;

                var inwards_value=data.grand_inwards_value;

                var outwards_quantity=data.grand_outwards_quantity;

                var outwards_value=data.grand_outwards_value;

                var closing_quantity=data.grand_closing_quantity;

                var closing_value=data.grand_closing_value;



                for (var i in stock_flow_data) {

                html += `<tr>

                    <td>`+stock_flow_data[i].item_name+`</td>

                    <td>`+stock_flow_data[i].opening_quantity+`</td>

                    <td>`+stock_flow_data[i].opening_value+`</td>

                    <td>`+stock_flow_data[i].inwards_quantity+`</td>

                    <td>`+stock_flow_data[i].inwards_value+`</td>

                    <td>`+stock_flow_data[i].outwards_quantity+`</td>

                    <td>`+stock_flow_data[i].outwards_value+`</td>

                    <td>`+stock_flow_data[i].closing_quantity+`</td>

                    <td>`+stock_flow_data[i].closing_value+`</td>

                    </tr>`;

                 }

                 foot += `<tr>

                        <td>Grand Total</td>

                        <td>`+opening_quantity+`</td>

                        <td>`+opening_value+`</td>

                        <td>`+inwards_quantity+`</td>

                        <td>`+inwards_value+`</td>

                        <td>`+outwards_quantity+`</td>

                        <td>`+outwards_value+`</td>

                        <td>`+closing_quantity+`</td>

                        <td>`+closing_value+`</td>

                         </tr>`



                        

                 $('#stock_flow_report tbody').append(html);

                 $('#stock_flow_report tfoot').append(foot);



                 stock_flow_datatable =  $('#stock_flow_report').DataTable({



                  dom: 'B',

                  buttons: [ { extend: 'excel', filename: 'Stock Flow Report', 'title': 'Stock Flow Report', exportOptions: { columns: ":not(.noExport)" },  footer: true } ],

                  fixedHeader: {

                          header: true,

                          footer: true

                      }

              });

                stock_flow_datatable.button('.buttons-excel' ).trigger();

            }

          },

      error:function()

      {

      }

    });

  });

  /*end*/

  //stock_flow only get fyear date.

   

   //

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



    function get_data(start, end)

    { 

      var obj= $(this);

      var name = obj.attr('data-name'); 

      from_date=start.format('DD/MM/YYYY');



       financial_year=from_date;

          $('.loader_wall').show();

      

        $.ajax({

           url: "{{ route('get_stock_report') }}",

            type: 'post',

            data: {

                _token: $('input[name=_token]').val(),

                start_date:from_date,

                end_date: end.format('YYYY-MM-DD')

            },

            dataType: "json",

            success: function(data, textStatus, jqXHR) {     

                 

         

          //stock flow   

                  var res = data.result;     

                  $('.transaction_table').empty();

                   datatable.destroy();

                  var stockflow_table = 

                    '<table class="table table_empty table-striped table-hover" id="datatable flow" style="margin-left: center;"><thead><tr><th width="20%">Item</th><th width="20%" colspan="2">Opening Balance</th><th width="20%" colspan="2">Inwards</th><th width="20%" colspan="2">Outwards</th><th width="20%" colspan="2">Closing Balance</th></tr><tr><td></td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> <td>Qty</td> <td>Value</td> </tr></thead> <tbody>';     

                  for(var i in res)

                  {          

                    stockflow_table += '<tr>                                                      <td><a href="javascript:;" data-id="'+res[i].entry_id+'" class="grid_label  process_inventory">'+res[i].item_name+'</a></td><td>'+res[i].opening_quantity+'</td><td>'+res[i].opening_value+'</td><td>'+res[i].inwards_quantity+'</td><td>'+res[i].inwards_value+'</td><td>'+res[i].outwards_quantity+'</td> <td>'+res[i].outwards_value+'</td><td>'+res[i].closing_quantity+'</td><td>'+res[i].closing_value+'</td></tr>';

                   }         



                  stockflow_table += '<tfoot><tr><th style="padding:10px" width="20%">Grand Total</th><td><b>'+data.grand_quantity+'</b></td><td><b>'+data.grand_value+'</b></td><td><b>'+data.grand_inwards_quantity+'</b></td> <td><b>'+data.grand_inwards_value+'</td></b> <td><b>'+data.grand_outwards_quantity+'</b></td> <td><b>'+data.grand_outwards_value+'</b></td><td><b>'+data.grand_closing_quantity+'</b></td><td><b>'+data.grand_closing_value+'</b></td></tr></tfoot>';

                    stockflow_table +=   '</table>';

                    $('.transaction_table').append(stockflow_table);

                     if($('#flow').length>0){

                        $('#flow').DataTable()

                      }

          

          

         



                           if($('.c_v').length > 0 ){

                             $('.c_v').DataTable(datatable_options)

                            }                 

          //*finish*

              $('.loader_wall').hide();

                removeSign();

            },



            error: function(jqXHR, textStatus, errorThrown) {

                //alert("New Request Failed " +textStatus);

            }

        });

    }



    $('.table_view').on('click',function(){

        var obj= $(this);

      var name = obj.attr('data-name'); 

      var from_date = $('.from_date').val();

      var to_date = $('.to_date').val();

     

        $('.stock_flow_fdate').val(start.format('DD/MM/YYYY'));

          $('.loader_wall').show();

      

        $.ajax({

           url: "{{route('export_report') }}",

            type: 'post',

            data: {

                _token: $('input[name=_token]').val(),

                name:name,

                from_date: from_date,

                to_date : to_date

            },

            dataType: "json",

            success: function(data, textStatus, jqXHR) {     

        var name=data.name;



          //current stock_report

          if(name=="Stock Report")

          {

              var curent_stock_data = data.stock_report;

              datatable.destroy();

              $('.Current_stock_table').empty();

              datatable.destroy();

              var stock_reporttable='<div style="overflow-x:auto;"><table class="table table_empty table-striped table-hover c_s" id="datatable"><thead><tr><th height="30px" width="70px">Item Id</th><th height="30px" width="70px">Category</th><th width="120px" height="30px">Sub Category</th><th width="70px" >Type</th><th width="70px">Make</th><th width="70px">Identifier</th><th width="90px">Item Name </th> <th width="70px">HSN</th><th width="140px">Purchase Unit Rate</th><th width="150px">Purchase Rate + Tax</th><th width="100px">Selling Tax </th><th width="140px">Selling Unit Rate</th><th width="140px"> Selling Rate + Tax</th><th width="100px"> Stock Qty</th><th width="150px">Total Purchase Value</th><th width="140px">Total Selling Value</th></tr></thead><tbody></div>';

                  var total_sale_value = 0;

                  var total_purchase_value = 0;

                  var total_purchase_price = 0;

                  var total_selling_price = 0;

                  for(var i in curent_stock_data)

                  {            

                      var tax_value = curent_stock_data[i].tax_value;

                      var selling_amount = curent_stock_data[i].selling_price;

                      var purchase_price = curent_stock_data[i].purchase_price;

                      if(purchase_price == null){

                        purchase_price = 0.0;

                      }else{

                        purchase_price = curent_stock_data[i].purchase_price;

                      }

                      var tax_amount = parseFloat(isNaN(tax_value) ? 0 : tax_value/100 ) * parseFloat(selling_amount);  

                      var selling_rate = (parseFloat(selling_amount) + parseFloat(tax_amount));

                      var purchase_price_with_tax = (parseFloat(purchase_price) + parseFloat(tax_amount));



                      var base_price = curent_stock_data[i].base_price;

                      var in_stock = curent_stock_data[i].in_stock;

                      if(in_stock > 0){

                        in_stock = curent_stock_data[i].in_stock;

                      }else{

                        in_stock = 0;

                      }

                      var total_stock_value = (parseFloat(selling_rate) * parseFloat(in_stock));

                      var total_purchase = (parseFloat(purchase_price_with_tax) * parseFloat(in_stock));

                      total_sale_value =    total_sale_value + selling_rate;

                      total_purchase_value = total_purchase_value + purchase_price_with_tax;

                      total_purchase_price =total_purchase_price + total_purchase;

                      total_selling_price = total_selling_price + total_stock_value; 

                      stock_reporttable += `<tr>

                      <td>`+curent_stock_data[i].id+`</td>

                      <td>`+curent_stock_data[i].main_category_name+`</td>

                      <td>`+curent_stock_data[i].category_name+`</td>

                      <td>`+curent_stock_data[i].type_name+`</td>

                      <td>`+curent_stock_data[i].make_name+`</td>

                      <td>`+curent_stock_data[i].identifier_a+`</td>

                      <td>`+curent_stock_data[i].name+`</td>

                      <td>`+curent_stock_data[i].hsn+`</td>

                      <td>`+purchase_price+`</td>

                      <td>`+parseFloat(purchase_price_with_tax).toFixed(2)+`</td>

                      <td>`+parseFloat(tax_amount).toFixed(2)+`</td>

                      <td>`+selling_amount+`</td>

                      <td>`+selling_rate+`</td>

                      <td>`+in_stock+`</td>

                      <td>`+total_purchase+`</td>

                      <td>`+total_stock_value+`</td>

                      </tr>`;

                  }

                  stock_reporttable += `<tfoot><tr>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td><b>Total Items = `+data.total_in_stock+`</b></td>

                    <td></td>

                    <td></td>

                    <td><b>Total = `+parseFloat(total_purchase_value).toFixed(2)+`</b></td>

                    <td></td>

                    <td></td>

                    <td><b>Total = `+parseFloat(total_sale_value).toFixed(2)+`</b></td>

                    <td></td>

                    <td><b>Total Purchase Value = `+total_purchase_price+`</b></td>

                    <td><b>Total Sale Value = `+total_selling_price+`</b></td>

                    </tr></tfoot>`;

                      stock_reporttable +=   '</table>';

                     $('.Current_stock_table').append(stock_reporttable);

                     if($('.c_s').length>0){

                       $('.c_s').DataTable({

                         "scrollX": true,

                         "pageLength":25

                                });

                     }  

          } 

        //low stock report 

          else if(name=="All Low Stock Report")

          {     

       

            var low_stock_data = data.low_stock;

            $('.low_stock_table').empty();

            datatable.destroy();

            var low_stock_report_table = 

                  '<div style="overflow-x:auto;"><table class="table table_empty table-striped table-hover l_s" id="datatable"><thead><tr><th height="30px" width="70px">Item Id</th><th height="30px" width="70px">Category</th><th width="120px" height="30px">Sub Category</th><th width="70px" >Type</th><th width="70px">Make</th><th width="70px">Identifier</th><th width="90px">Item Name </th> <th width="70px">HSN</th><th width="70px">In_Stock</th><th width="150px">Purchase Rate + Tax</th><th width="140px">Minimum Order Quantity</th><th width="150px">Total Purchase Value</th></tr></thead><tbody></div>';     



                for(var i in low_stock_data)

                {

                  low_stock_report_table += '<tr><td>'+low_stock_data[i].item_id+'</td>           <td>'+low_stock_data[i].main_category_name+'</td><td>'+low_stock_data[i].category_name+'</td><td>'+low_stock_data[i].type_name+'</td><td>'+low_stock_data[i].make_name+'</td><td>'+low_stock_data[i].identifier_a+'</td> <td>'+low_stock_data[i].item_name+'</td><td>'+low_stock_data[i].hsn+'</td><td>'+low_stock_data[i].in_stock+'</td><td>'+low_stock_data[i].total_purchase_price+'</td><td>'+low_stock_data[i].moq+'</td><td>'+low_stock_data[i].total_amount+'</td></tr>';



                }         



                 low_stock_report_table += `<tfoot><tr>

                <td></td>

                <td></td>

                <td></td>

                <td></td>

                <td></td>

                <td></td> 

                <td><b>Total Items = `+parseFloat(data.total_items).toFixed(2)+`</b></td>

                <td></td>

                <td></td> 

                <td><b>Total Purchase Amount=`+parseFloat(data.total_purchase_price).toFixed(2)+`</b></td>

                <td></td>

                <td><b>Total Amount = `+parseFloat(data.total_payment).toFixed(2)+`</b></td>

                </tr></tfoot>`;

                low_stock_report_table +=   '</table>';



                $('.low_stock_table').append(low_stock_report_table);

                if($('.l_s').length>0){

                $('.l_s').DataTable({

                      "scrollX": true,

                      "pageLength":25

                                });

               }

          }

          else if(name=="Job card Low Stocks")

          { 

            //jobcard low stock report

            var jobcard_low_stock_data=data.low_stock_reports;

            $('.jobcard_low_stock_table').empty();

            datatable.destroy();

            var jobcard_low_stock_table = 

                        '<table class="table table_empty table-striped table-hover j_l" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Jc Number</th><th height="30px" width="120px">Vehicle Number</th><th width="120px" height="30px">Item Name</th><th width="70px">Order Quantity</th><th width="70px">In_Stock</th><th width="150px">Purchase Rate + Tax</th><th width="150px">Total Amount</th><th width="70px">Assigned To</th></tr></thead><tbody>';     



                      for(var i in jobcard_low_stock_data)

                      {

                        jobcard_low_stock_table += '<tr><td>'+jobcard_low_stock_data[i].jc_no+'</td><td>'+jobcard_low_stock_data[i].registration_no+'</td><td>'+jobcard_low_stock_data[i].name+'</td><td>'+jobcard_low_stock_data[i].qty+'</td><td>'+jobcard_low_stock_data[i].in_stock+'</td><td>'+jobcard_low_stock_data[i].total_purchase_price+'</td> <td>'+jobcard_low_stock_data[i].total_payment+'</td><td>'+jobcard_low_stock_data[i].assigned_to+'</td></tr>';



                      }         



                       jobcard_low_stock_table += `<tfoot><tr>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td></td>

                      <td><b>Total Items = `+parseFloat(data.total_amount).toFixed(2)+`</b></td>

                      <td></td>

                      </tr></tfoot>`;

                      jobcard_low_stock_table +=   '</table>';



                      $('.jobcard_low_stock_table').append(jobcard_low_stock_table);

                        if($('.j_l').length>0){

                            $('.j_l').DataTable({

                            "pageLength":25})

                               }

          }

              //Sales Report

          else if(name=="Invoice Report")

          {                         

                var invoice_report = data.invoice_report;

                     $('.sales_report_table').empty();

                      datatable.destroy();

                     var sales_report_table = 

                              '<div style="overflow-x:auto;"><table class="table table_empty table-striped table-hover sales_report_table sal" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Date</th><th height="30px" width="120px">Document No</th><th width="120px" height="30px">Customer</th><th width="120px">Goods/Service</th><th width="90px">Item_name</th><th width="90px">HSN</th><th width="70px">Unit Rate</th><th width="70px">Quantity</th><th width="70px">Total</th><th width="70px">Discount</th><th width="70px">SGST</th><th width="70px">CGST</th><th width="70px">IGST</th><th width="70px">Total Tax</th><th width="70px">Total Sale</th><th width="70px">pending Payment</th><th width="70px">payment Mode</th><th width="70px">Sale Type</th></tr></thead><tbody>';     

                              var exact_csgt='';

                              var exact_igst = '';

                              var cgst='';

                              var igst='';

         

                            for (var i in invoice_report) {

                            var tax_type = invoice_report[i].tax_type;

                            var gst = invoice_report[i].gst;

                            if(gst == null){

                              gst = '';

                            }else{

                              gst = invoice_report[i].gst;

                            }

                            var tax = invoice_report[i].taxes;

                            var discount = invoice_report[i].discount;

                            var payment_mode = invoice_report[i].payment_mode;

                            if(payment_mode == null){

                              payment_mode = '';

                            }else{

                              payment_mode = invoice_report[i].payment_mode;

                            }

                            if(discount == null){

                              discount = '';

                            }else{

                              discount = invoice_report[i].discount;

                            }



                            if(tax_type == 1){

                               exact_csgt = tax.split('CGST');

                                cgst = exact_csgt[0];

                                igst = '';

                            }else if(tax_type == 2){

                              exact_igst = tax.split('IGST');

                               igst = exact_igst[0];

                               cgst = '';

                            }



                            if(cgst == 'undefined'){

                              cgst = '';



                            }



                            if(igst == 'undefined'){

                              igst = '';

                            }

                                var amount = (invoice_report[i].rate*invoice_report[i].quantity).toFixed(2);

                                var tax_amount = (amount*invoice_report[i].tax_value).toFixed(2);

                                var sale_type = invoice_report[i].sale_type

                                if(sale_type == 'Job Invoice Cash'){

                                  sale_type = 'Cash';

                                }else if(sale_type == 'Job Invoice Credit'){

                                  sale_type = 'Credit';

                                }else if(sale_type == null){

                                  sale_type = '';

                                }

                                       

                              

                            sales_report_table += '<tr>                                           <td>'+invoice_report[i].date+'</td>                                 <td>'+invoice_report[i].order_no+'</td>                             <td>'+invoice_report[i].customer+'</td>                             <td>'+invoice_report[i].category+'</td>                             <td>'+invoice_report[i].name+'</td>                                 <td>'+invoice_report[i].hsn+'</td>                                  <td>'+invoice_report[i].rate+'</td>                                 <td>'+invoice_report[i].quantity+'</td>                             <td>'+invoice_report[i].amount+'</td>                               <td>'+invoice_report[i].discount+'</td>                             <td>'+cgst+'</td><td>'+cgst+'</td>                                  <td>'+igst+'</td><td>'+gst+'</td>                                   <td>'+invoice_report[i].total_sales+'</td>                          <td>'+invoice_report[i].pending_payment+'</td>                      <td>'+invoice_report[i].payment_mode+'</td>                         <td>'+invoice_report[i].sale_type+'</td></tr>';

                            } 

                            sales_report_table += `<tfoot><tr>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <td></td>

                                <b><td><b>Total= `+parseFloat(data.total_sales).toFixed(2)+`</td></b>

                                <td><b>Total Pending=`+parseFloat(data.total_pending).toFixed(2)+`</b></td>

                                <td></td>

                                <td></td>

                                </tr></tfoot>`;        

                           sales_report_table +=   '</table>';

                         $('.sales_report_table').append(sales_report_table);

                            if($('.sal').length>0){

                               $('.sal').DataTable({

                               "scrollX": true,

                                "pageLength":25

                               });

                      

                                   }                  

                           

          }

           //Receviabe Report

                              

           else if(name=="Receivable Report")

           {

                var receivables_data=data.receivables; 

           

                              var receivables_total_customer=data.total_customer;

                              var receivables_total_sale=data.total_sale;

                              var receivables_total_pending=data.pending_payment;

                              $('.receiveable_report_table').empty();

                                datatable.destroy();

                              var receiveable_report_table = 

                                '<table class="table table_empty table-striped table-hover rec" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Customer Name</th><th height="30px" width="120px">Invoice Number</th><th width="120px" height="30px">Total Sale</th><th width="70px">pending Payment</th></tr></thead><tbody>';     



                              for(var i in receivables_data)

                              { 

                                  var total_sale = receivables_total_sale;

                                 if(total_sale == null){

                                      total_sale = '';

                                    }else{

                                      total_sale = receivables_total_sale;

                                    }

                              receiveable_report_table += '<tr><td>'+receivables_data[i].customer_name+'</td><td>'+receivables_data[i].invoice_number+'</td><td>'+receivables_data[i].total_sale+'</td><td>'+receivables_data[i].pending+'</td></tr>';

                              }         

                               receiveable_report_table += `<tfoot><tr>



                              <td><b>Total Customer= `+ parseFloat(receivables_total_customer).toFixed(2)+`</b></td>

                              <td></td> 

                              <td><b>Total Sales= `+parseFloat(receivables_total_sale).toFixed(2)+`</b></td>

                              <td><b>Total Pending Amount= `+parseFloat(receivables_total_pending).toFixed(2)+`</b></td>

                              </tr></tfoot>`;

                                receiveable_report_table +=   '</table>';

                                $('.receiveable_report_table').append(receiveable_report_table);

                                if($('.rec').length>0){

                                $('.rec').DataTable({

                                "pageLength":25})

                                   }

             }

            //Purchase Report 



            else if(name=="Purchase Report")

            {   

                      var purchase_report_data=data.purchase_reports;

                      var purchase_report_total=data.total_purchase; 

                  

                      $('.purchase_report_table').empty();

                       datatable.destroy();

                      var purchase_report_table ='<div style="overflow-x:auto;"><table class="table table_empty table-striped table-hover pur" id="datatable" style="margin-left: center; width=100px"><thead><tr><th height="30px" width="90px">Date</th><th height="30px" width="120px">Document No</th><th width="120px" height="30px">Customer</th><th width="120px">Item Number</th><th width="90px">HSN</th><th width="70px">NewSelling Price</th><th width="70px">Purechase Price</th><th width="70px">Quantity</th><th width="70px">Total</th><th width="70px">SGST</th><th width="70px">CGST</th><th width="70px">IGST</th><th width="70px">Tax Amount</th><th width="70px">Total Sale</th><th width="70px">pending Payment</th></tr></thead><tbody>';  

                              var tax='';

                              var csgt='';

                              var igst='';

                              var csgt_value='';

                              var igst_value='';

                               for(var i in purchase_report_data)

                               { 

                                var tax_type = purchase_report_data[i].tax_type;

                                var tax_value = parseFloat( isNaN(purchase_report_data[i].tax_value) ? 0 : purchase_report_data[i].tax_value/100 );

                                var amount = (purchase_report_data[i].rate*purchase_report_data[i].quantity).toFixed(2);

                                var tax_amount = (amount*tax_value).toFixed(2);

                                if(tax_type == 1){

                                   tax = purchase_report_data[i].tax;

                                  cgst = tax.split('CGST');

                                  csgt_value = cgst[0];

                                }else if(tax_type == 2){

                                   tax = purchase_report_data[i].tax;

                                   igst = tax.split('IGST');

                                   igst_value = igst[0];

                                }                           

                              purchase_report_table += '<tr>                                      <td>'+purchase_report_data[i].date+'</td>                                                             <td>'+purchase_report_data[i].order_no+'</td>                                                             <td>'+purchase_report_data[i].customer+'</td>                                                             <td>'+purchase_report_data[i].item_name+'</td>                                                              <td>'+purchase_report_data[i].hsn+'</td>                           <td>'+purchase_report_data[i].new_selling_price+'</td>             <td>'+purchase_report_data[i].purchase_price+'</td>                <td>'+purchase_report_data[i].quantity+'</td>                      <td>'+purchase_report_data[i].total_amount+'</td>                  <td>'+csgt_value+'</td>                                            <td>'+csgt_value+'</td>                                            <td>'+igst_value+'</td>                                            <td>'+tax_amount+'</td>                                            <td>'+purchase_report_data[i].total_sales+'</td>                   <td>'+purchase_report_data[i].balance+'</td></tr>';

                      

                                  } 

                                purchase_report_table += `<tfoot><tr>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td><b>Total:`+parseFloat(purchase_report_total).toFixed(2)+`</b></td>

                                    <td></td>

                                    </tr></tfoot>`;        

                               purchase_report_table +=   '</table></div>';

                              $('.purchase_report_table').append(purchase_report_table);



                             if($('.pur').length>0){

                            $('.pur').DataTable({

                               "scrollX": true,

                               "pageLength":25

                                });

                            }

                 }

           else if(name=="Payable Report")

           {

              //Payable Report

            

                        var payable_report_data=data.payable_report;

                        var payable_report_supplier=data.total_supplier;

                        var payable_report_sale=data.total_sale;

                       var payable_report_pending=data.pending_payment;

                         $('.payable_report_table').empty();

                         datatable.destroy();

                        var payable_report_table = 

                                '<table class="table table_empty table-striped table-hover pay" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Supplier Name</th><th height="30px" width="120px">Purechase Number</th><th width="120px" height="30px">Total Purechase</th><th width="70px">pending Payment</th></tr></thead><tbody>';     



                              for(var i in payable_report_data)

                              {

                                payable_report_table += '<tr><td>'+payable_report_data[i].supplier+'</td><td>'+payable_report_data[i].purchase_number+'</td><td>'+payable_report_data[i].total_sale+'</td><td>'+payable_report_data[i].pending+'</td></tr>';



                              }        



                               payable_report_table += `<tfoot><tr>

                              <td><b>Total Supplier: `+ parseFloat(payable_report_supplier).toFixed(2)+`</b></td>

                              <td></td> 

                              <td><b>Total Purchase(Rs)= `+parseFloat(payable_report_sale).toFixed(2)+`</b></td>

                              <td><b>Total Pending Amount= `+parseFloat(payable_report_pending).toFixed(2)+`</b></td>

                              </tr></tfoot>`;

                              payable_report_table +=   '</table>';

                              $('.payable_report_table').append(payable_report_table);

                              if($('.pay').length>0){

                            $('.pay').DataTable({

                               "pageLength":25

                            })

                           }

                }

            else if(name =="Expense Report")

            {             

              //Daily Expenses

              var daily_expenses_data=data.expenses_reports;

              var daily_expenses_total=data.total_expenses;

                $('.daily_expenses_table').empty();

                datatable.destroy();

                        var daily_expenses_report_table = 

                              '<table class="table table_empty table-striped table-hover daily" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Date</th><th height="30px" width="120px">Voucher Number</th><th width="120px" height="30px">Expense Name</th><th width="70px">Expense Amount</th><th width="70px">Person Name</th></tr></thead><tbody>';

                              for(var i in daily_expenses_data)

                              {

                                daily_expenses_report_table += '<tr><td>'+daily_expenses_data[i].date+'</td><td>'+daily_expenses_data[i].voucher_no+'</td><td>'+daily_expenses_data[i].display_name+'</td><td>'+daily_expenses_data[i].exp_amount+'</td><td>'+daily_expenses_data[i].first_name+'</td></tr>';



                              }         

                               daily_expenses_report_table += `<tfoot><tr>

                              <td></td>

                              <td></td>

                              <td></td>

                               <td><b>Total: `+parseFloat(daily_expenses_total).toFixed(2)+`</b></td>

                               <td></td>

                              </tr></tfoot>`;

                              daily_expenses_report_table +=   '</table>';

                              $('.daily_expenses_table').append(daily_expenses_report_table);

                              if($('.daily').length>0){

                                  $('.daily').DataTable({

                                     "pageLength":25

                                  })

                            }

              }

              else if(name=="Daily Report By Type"){

                var expenses_reports_by_types=data.expenses_reports_by_types;

                var total_expenses=data.total_expenses;

                $('.daily_expenses__type_table').empty();

                var daily_expenses_type_report= 

                              '<table class="table table_empty table-striped table-hover type" id="datatable" style="margin-left: center;"><thead><tr><th width="120px" height="30px">Expense Name</th><th width="70px">Expense Amount</th></tr></thead><tbody>';

                              for(var i in expenses_reports_by_types)

                              {

                                daily_expenses_type_report += '<tr><td>'+expenses_reports_by_types[i].name+'</td><td>'+expenses_reports_by_types[i].amount+'</td></tr>';



                              }         

                               daily_expenses_type_report += `<tfoot><tr>

                              <td></td>

                              <td><b>Total: `+parseFloat(total_expenses).toFixed(2)+`</b></td>

                              </tr></tfoot>`;

                              daily_expenses_type_report +=   '</table>';

                              $('.daily_expenses__type_table').append(daily_expenses_type_report);

                                if($('.type').length>0){

                                  $('.type').DataTable({

                                     "pageLength":25

                                  })

                            }



              }

          else if(name=="CompanyExpense Report")

          {

              //Company Expenses 

                    var company_expenses_data = data.company_expenses_report;

                    var company_expenses_total = data.total_company_expenses;

               $('.company_expenses_table').empty();

                    datatable.destroy();

                    var company_expenses_table = 

                                '<table class="table table_empty table-striped table-hover company" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Date</th><th height="30px" width="120px">Voucher Number</th><th height="30px" width="120px">Expenses Voucher Number</th><th height="30px" width="120px">From Account</th><th height="30px" width="120px">To Account</th><th height="30px" width="120px">Ledger Name</th><th width="120px" height="30px">Reference/Notes</th><th width="120px" height="30px">Expense Amount</th></tr></thead><tbody>';     



                              for(var i in company_expenses_data)

                              {

                                if(company_expenses_data[i].expense_voucher!=null){

                                company_expenses_table += '<tr><td>'+company_expenses_data[i].date+'</td><td>'+company_expenses_data[i].voucher_no+'</td><td>'+company_expenses_data[i].expense_voucher+'</td><td>'+company_expenses_data[i].from_account+'</td><td>'+company_expenses_data[i].to_account+'</td><td>'+company_expenses_data[i].ledger_name+'</td><td>'+company_expenses_data[i].reference+'</td><td>'+company_expenses_data[i].expense_amount+'</td></tr>';

                              }



                              }         



                               company_expenses_table += `<tfoot><tr>

                              <td></td>

                              <td></td>

                               <td></td>

                              <td></td>

                               <td></td>

                              <td></td>

                              <td></td>

                              <td><b>Total:`+parseFloat(company_expenses_total).toFixed(2)+`</b></td>

                              </tr></tfoot>`;

                              company_expenses_table +=   '</table>';



                              $('.company_expenses_table').append(company_expenses_table);

                              if($('.company').length>0){

                                $('.company').DataTable({

                                   "pageLength":25

                                })

                           }

                         }



                      else if(data.name=="Company Expenses By Type"){

                        var company_expenses_by_type=data.company_expenses_report;

                        var company_expenses_total=data.total_company_expenses;

                        $('.company_expenses_types_table').empty();

                        var company_expenses_type = 

                                '<table class="table table_empty table-striped table-hover company_types" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="120px">Ledger Name</th><th width="120px" height="30px">Expense Amount</th></tr></thead><tbody>';     

                              for(var i in company_expenses_by_type)

                              {

                                company_expenses_type += '<tr><td>'+company_expenses_by_type[i].ledger_name+'</td><td>'+company_expenses_by_type[i].amount+'</td></tr>';

                              }         

                               company_expenses_type += `<tfoot><tr>

                              <td></td>

                               <td><b>Total:`+parseFloat(company_expenses_total).toFixed(2)+`</b></td>

                              </tr></tfoot>`;

                              company_expenses_type +=   '</table>';

                              $('.company_expenses_types_table').append(company_expenses_type);



                               if($('.company_types').length>0){

                                $('.company_types').DataTable({

                                   "pageLength":25

                                })

                           }





                         }

                      else if(data.name=="Customer Report"){

          //Customer Report

                 var customer_report_data= data.customer_details;

                 //var customer_details = data.customer_details;



                 var customer_total_customer=data.total_customer;

                 var customer_total_sale=data.total_sale;

                 var customer_pending=data.pending_payment;

                      $('.customer_report_table').empty();

                      datatable.destroy();

                 var customer_report_table = 

                                '<table class="table table_empty table-striped table-hover customer" id="datatable" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Customer Name</th><th height="30px" width="120px">Customer Group</th><th width="120px" height="30px">Credit Limit</th><th width="120px" height="30px">Phone Number</th><th width="120px" height="30px">City</th><th width="120px" height="30px">Created On</th><th width="120px" height="30px">Total Sales</th><th width="120px" height="30px">Pending</th></tr></thead><tbody>';    

                              for(var i in customer_report_data)

                              {

                                customer_report_table += '<tr><td>'+customer_report_data[i].customer+'</td><td>'+customer_report_data[i].group_name+'</td><td>'+customer_report_data[i].credit_limit+'</td><td>'+customer_report_data[i].mobile_no+'</td><td>'+customer_report_data[i].city+'</td><td>'+customer_report_data[i].created_at+'</td><td>'+customer_report_data[i].total_sale+'</td><td>'+customer_report_data[i].pending+'</td></tr>';



                              }         



                               customer_report_table += `<tfoot><tr>

                              <td><b>Total Customer:`+parseFloat(customer_total_customer).toFixed(2)+`</b></td>

                              <td></td>

                              <td></td>

                              <td></td>

                              <td></td>

                              <td><b>Total Sale:`+parseFloat(customer_total_sale).toFixed(2)+`</b></td>

                              <td><b>Total Pending Amount:`+parseFloat(customer_pending).toFixed(2)+`</b></td>

                              </tr></tfoot>`;

                              customer_report_table +=   '</table>';

                              $('.customer_report_table').append(customer_report_table);

                              if($('.customer').length>0){

                            $('.customer').DataTable({

                               "pageLength":25

                            })

                                }

                              }

      //Customer & Vechile Report

          else if(name=="Customers/Vehicles"){

                  var customer_vehicles_data=data.vehicle_details;

                  var customer_vehicles_total_customer=data.total_vehicle;

                  var customer_vehicles_total_vehicle=data.total_customer;

                  var customer_vehicles_total_sale=data.total_sales;

                   $('.customer_vechile_report_table').empty();

                          datatable.destroy();

                    var customer_vechile_report_table = 

                                '<table class="table table_empty table-striped table-hover c_v" style="margin-left: center;"><thead><tr><th height="30px" width="90px">Vehicle Number</th><th height="30px" width="120px">Created On</th><th width="120px" height="30px">Vehicle Name</th><th width="120px" height="30px">Customer Name</th><th width="120px" height="30px">Customer Group</th><th width="120px" height="30px">Credit Limit</th><th width="120px" height="30px">Phone Number</th><th width="120px" height="30px">City</th><th width="120px" height="30px">Total Sales</th></tr></thead><tbody>';     



                              for(var i in customer_vehicles_data)

                              {

                                customer_vechile_report_table += '<tr><td>'+customer_vehicles_data[i].registration_no+'</td><td>'+customer_vehicles_data[i].created_at+'</td><td>'+customer_vehicles_data[i].vehicle_name+'</td><td>'+customer_vehicles_data[i].customer+'</td><td>'+customer_vehicles_data[i].group_name+'</td><td>'+customer_vehicles_data[i].credit_limit+'</td><td>'+customer_vehicles_data[i].mobile_no+'</td><td>'+customer_vehicles_data[i].city+'</td><td>'+customer_vehicles_data[i].total_sale+'</td></tr>';

                              }         

                               customer_vechile_report_table += `<tfoot><tr>

                              <td><b>Total Vechile:`+parseFloat(customer_vehicles_total_vehicle).toFixed(2)+`</b></td>

                              <td></td>

                              <td><b>Total Customer:`+parseFloat(customer_vehicles_total_customer).toFixed(2)+`</b></td>

                              <td></td>

                              <td></td>

                              <td></td>

                              <td></td>

                              <td><b>Total Sales:`+customer_vehicles_total_sale+`</b></td>

                              </tr></tfoot>`;

                              customer_vechile_report_table +=   '</table>';

                              $('.customer_vechile_report_table').append(customer_vechile_report_table);



                           if($('.c_v').length > 0 ){

                             $('.c_v').DataTable({

                               "pageLength":25

                             })

                            } 

                  }                

          //*finish*

              $('.loader_wall').hide();

                removeSign();

            },



            error: function(jqXHR, textStatus, errorThrown) {

                //alert("New Request Failed " +textStatus);

            }

        });

});

    



  $('.reset').on('click',function(){

      location.reload();

  });

});

</script>

@stop