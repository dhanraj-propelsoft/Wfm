@extends('layouts.master')
@section('head_links')
@parent
<style>

/* START - Card design*/
@import url('https://fonts.googleapis.com/css?family=Raleway');

$font: 'Raleway', sans-serif;

$pink: #C98491;
$gold: #E5B879;
$green: #A0B79F;
$white: #FFE3D8;
$navy: #495771;

.card_container {
  width: 800px;
  height: 500px;
  margin: auto;
  position: relative;
}

.card_profile {
  position: absolute;
  left: 10%;
  top: 10%;
  height: 400px;
  width: 300px;
  box-shadow: 0px 7px 15px #9c7373;
  border-bottom: 0px solid #888a8c;
}


.card_top {
  position: absolute;
  width: 100%;
  height: 50%;
  cursor: pointer;
  background-size: cover;
  z-index:1;
}
.card_bottom {
  position: absolute;
  width: 100%;
  height: 54%;
  top: 46%;
  background: #fafafa;
  z-index:1;
}

.card_h1 {
  font-family: $font;
  letter-spacing: 2px;
  text-align: center;
  color: $navy;
  margin-top: 13%;
  font-size: 1.2em;
  text-transform: uppercase;
  cursor: pointer;
}

.card_a {
  font-family: $font;
  letter-spacing: 2px;
  text-align: center;
  color: #495771;
  margin-top: 13%;
  font-size: 1.2em;
  text-transform: uppercase;
}

.card_button {
  width:100%;
  padding: 15px;
  font-size: 0.75em;
  display: inline-block;
  background: $navy;
  font-family: $font;
  text-transform: uppercase;
  letter-spacing: 3px;
  color: $white;
  border: none;
  margin-top: 10px;
  opacity: .8;
  margin-right: 0px;
  cursor: pointer;
  &:hover {
    opacity: 1;
    transition: all .3s ease;
  }
}

/*overlay menu*/
.card_menu {
  width: 20px;
  height: 20px;
  /*padding: 8px;*/
  opacity: .9;
  position: absolute;
  right: 5px;
  -webkit-transition: all .7s ease;
  transition: all .7s ease;
   z-index:10;
}

.card_menu_open_click-top {
     z-index:10;
  background: red;
  transform: translateY(7px) rotateZ(45deg);
}
.card_menu_open_click-middle {
     z-index:10;
  opacity: 0;
}
.card_menu_open_click-bottom {
     z-index:10;
  background: red;
  transform: translateY(-7px) rotateZ(-45deg);
}

/*hamburger menu animation*/
.card_bar {
   z-index:4;
  display: block;
  height: 3px;
  width: 20px;
  background: black;
  margin: 4px auto;
  -webkit-transition: all .5s ease;
  transition: all .5s ease;
  text-align: right;
}


.card_overlay_hide {
  position: absolute;
  background: #e0b477;
  top: 0;
  left: 0;
  width: 101%;
  height: 0%;
  opacity: 0;
  visibility: hidden;
  transition: opacity .5s, visibility .5s, height .5s;
  z-index:2;
}


.card_overlay_view {
  position: absolute;
  background: #e0b477;
  top: 0;
  left: 0;
  width: 101%;
  height: 100%;
  opacity: 1;
  visibility: visible;
  transition: opacity .5s, visibility .5s, height .5s;
  z-index:2;
}


.card_nav {
  margin-top: 20px;
  margin-bottom: 20px;
  margin-left: 0px;
  margin-right: 10px;
  height: 90%;
  border: 0px solid black;
}

.card_nav_li {
    list-style-type: none;
    margin: 28px auto;
    font-family: $font;
    font-size: 1em;
  }

  .card_nav_a {
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #495771;
    padding: 10px;
    border: solid 1px transparent;
  }

  .card_nav_a:hover {
    color: white;
    transition: all .5s ease;
    border: solid 1px transparent;
    background: #495771;
    padding: 10px;
  }


.card_background {
  height: 0vh;
  top: 0;
  width: 100vw;
  position: absolute;
  visibility: hidden;
  opacity: 0;
  background-position: left 20% top 0px;
  transition: opacity .5s, visibility .5s, height .5s;
}

.card_show {
  height: 100vh;
  width: 100vw;
  /*background-image: url(https://preview.ibb.co/jOUoSH/pie.jpg);*/
  background-repeat: no-repeat;
  background-position: left 20% top 0px;
  visibility: visible;
  opacity: .5;
  transition: opacity .5s, visibility .5s, height .5s;
  position: absolute;
}

/* END Card design*/

fieldset.scheduler-border {
	width: 1200px;
  	height: 210px;
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}
legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
  .rectanglebox {
  border: 2px solid #bdccf0;
  width: 450px;
  height: 120px;
  padding: 15px;
  background-color:#f0f2f5;
  box-shadow: 10px 10px #e4e8eb;
}
.scrollbar {
  border: 1px solid black;
  overflow-x: auto;
  max-width: 1250px; /* you need to adjust 400px for padding and border width of fieldset */
  display: inline-block;
}
</style>
@stop
@section('content')

<!-- News board -->
{!! Form::hidden('count_of_arrary',$count_of_news,array('class' => 'control-label count_of_arrary')) !!}
<div class="col-md-12 offset-md-2 news_update fixed-bottom" style="display:none;">
	<fieldset class="scheduler-border scrollbar">
 		<legend class="scheduler-border">News/Update</legend>
    		<table>
   			<tr>
   				@foreach($dashboard_news as $dashboard_news)
   				<td class="col-md-5">
   				<p class="rectanglebox" style="margin-top: 20px">{{ $dashboard_news->message }}
   				</p>
   			    </td>
   		 		@endforeach
   			</tr>
   			</table>
   	</fieldset>
 </div>

<!-- All Module with quick link -->
<div class="form-body" >
    <br>
    <br>
    @if(Session::get('organization_id'))
        <div class="row">

            @if (App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
            	@permission('inventory')
                    <div class="col-md-4">
                        <div class="card_profile">
                            <div id="overlay_inventory" class="card_overlay_hide">
                              <div class="card_menu" id="menuOverlay" menu_id="inventory">
                                <div class="card_bar card_menu_open_click-top" id="bartop"></div>
                                <div class="card_bar card_menu_open_click-middle" id="barmiddle"></div>
                                <div class="card_bar card_menu_open_click-bottom" id="barbottom"></div>
                              </div>
                                <ul class="card_nav">
                                <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('contact.index', ['vendor']) }}">Supplier</a></li>
                                <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('transaction.index', ['goods_receipt_note']) }}">Goods Receipt Note</a></li>
                                <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('transaction.index', ['debit_note']) }}">Purchase Return</a></li>
                                <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('internal_consumption.index') }}">Internal Consumption</a></li>
                                <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('cash_transaction.index', ['payment']) }}">Payables</a></li>
                                <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('item.index', ['items']) }}">Inventory Items</a></li>
                                <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('receipts_report') }}"  >Receivables Report</a></li>
                              </ul>
                            </div>
                            <div class="card_top" url-route="{{ route('inventory.dashboard') }}"  style="background-image: url('{{ URL::to('/') }}/public/package/Inventory_1.jpg');">
                            </div>
                            <div class="card_bottom">
                              <div class="card_menu" id="menuNonOverlay" menu_id="inventory">
                                <div class="card_bar"></div>
                                <div class="card_bar"></div>
                                <div class="card_bar"></div>
                              </div>
                              <h1 class="card_h1" url-route="{{ route('inventory.dashboard') }}">Inventory</h1>
                              <br>
                              <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="card_button" url-route="{{ route('transaction.index', ['purchase_order']) }}">Purchase Order</button>
                                </div>
                                <div class="col-md-12 text-center">
                                  <button class="card_button" url-route="{{ route('transaction.index', ['purchases']) }}">Purchase</button>
                                </div>
                              </div>
                            </div>
                            <div id="background_inventory" class="card_background" style="background-image: url('{{ URL::to('/') }}/public/package/Inventory_1.jpg');"></div>
                        </div>
                    </div>
                @endpermission
            @endif

            @if (App\Organization::checkModuleExists('trade_wms', Session::get('organization_id')))
            	@permission('trade_wms')
                    <div class="col-md-4">
                      <div class="card_profile">
                        <div id="overlay_workshop" class="card_overlay_hide">
                          <div class="card_menu" id="menuOverlay" menu_id="workshop">
                            <div class="card_bar card_menu_open_click-top" id="bartop"></div>
                            <div class="card_bar card_menu_open_click-middle" id="barmiddle"></div>
                            <div class="card_bar card_menu_open_click-bottom" id="barbottom"></div>
                          </div>
                            <ul class="card_nav">
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('transaction.index', ['job_request']) }}"  >Estimation</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('cash_transaction.index', ['wms_receipt']) }}"  >Receivables</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('contact.index', ['wms-customer']) }}"  >Customer</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('vehicle_registered.index') }}"  >Registered Vehicles</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('vehicle_list_report') }}"  >Vehicle Invoice Report</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('receivables_report') }}"  >Receivables Report</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('item.index', ['items']) }}"  >Items</a></li>
                          </ul>
                        </div>
                        <div class="card_top" url-route="{{ route('trade_wms.job_board') }}" style="background-image: url('{{ URL::to('/') }}/public/package/wms_main_1.jpg');">
                        </div>
                        <div class="card_bottom">
                          <div class="card_menu" id="menuNonOverlay" menu_id="workshop">
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                          </div>
                          <h1 class="card_h1" url-route="{{ route('trade_wms.job_board') }}">Workshop</h1>
                          <br>
                          <div class="row">
                            <div class="col-md-12 text-center">
                              <button class="card_button" url-route="{{ route('jobcard.index') }}">Job Card</button>
                            </div>
                            <div class="col-md-12 text-center">
                              <button class="card_button" url-route="{{ route('transaction.index', ['job_invoice']) }}">Job Invoice</button>
                            </div>
                        </div>
                        </div>
                        <div id="background_workshop" class="card_background" style="background-image: url('{{ URL::to('/') }}/public/package/wms_main_1.jpg');"></div>

                      </div>
                    </div>
                @endpermission
            @endif

            @if (App\Organization::checkModuleExists('books', Session::get('organization_id')))
            	@permission('books')
                    <div class="col-md-4">
                      <div class="card_profile">
                        <div id="overlay_books" class="card_overlay_hide">
                          <div class="card_menu" id="menuOverlay" menu_id="books">
                            <div class="card_bar card_menu_open_click-top" id="bartop"></div>
                            <div class="card_bar card_menu_open_click-middle" id="barmiddle"></div>
                            <div class="card_bar card_menu_open_click-bottom" id="barbottom"></div>
                          </div>
                            <ul class="card_nav">
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('stock_report') }}"  >Stock Report</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('bank_transactions.index') }}"  >Bank/Cash Transactions</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('profit_and_loss') }}"  >Incomes and Expenses</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('journal_report') }}"  >Journal Report</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('expenses.index') }}"  >Petty Cash Expenses</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('trial_balance') }}"  >Trial Balance</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('vouchers.index') }}"  >Day Book</a></li>
                          </ul>
                        </div>
                        <div class="card_top" url-route="{{ route('books.dashboard') }}" style="background-image: url('{{ URL::to('/') }}/public/package/Books_1.jpg');">
                        </div>
                        <div class="card_bottom">
                          <div class="card_menu" id="menuNonOverlay" menu_id="books">
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                          </div>
                          <h1 class="card_h1" url-route="{{ route('books.dashboard') }}">Books</h1>
                          <br>
                          <div class="row">
                            <div class="col-md-12 text-center">
                              <button class="margin-left card_button" url-route="{{ route('ledger_statement.index') }}">Statement of Accounts</button>
                            </div>
                            <div class="col-md-12 text-center">
                              <button class="card_button" url-route="{{ route('balance_sheet') }}">Balance Sheet</button>
                            </div>
                        </div>
                        </div>
                        <div id="background_books" class="card_background" style="background-image: url('{{ URL::to('/') }}/public/package/Books_1.jpg');"></div>

                      </div>
                    </div>
                @endpermission
            @endif

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="row">

            @if (App\Organization::checkModuleExists('hrm', Session::get('organization_id')))
            	@permission('hrm')
                    <div class="col-md-4">
                      <div class="card_profile">
                        <div id="overlay_hrm" class="card_overlay_hide">
                          <div class="card_menu" id="menuOverlay" menu_id="hrm">
                            <div class="card_bar card_menu_open_click-top" id="bartop"></div>
                            <div class="card_bar card_menu_open_click-middle" id="barmiddle"></div>
                            <div class="card_bar card_menu_open_click-bottom" id="barbottom"></div>
                          </div>
                            <ul class="card_nav">
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('leaves.index') }}"  >Leave Request</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('permissions.index') }}"  >Permission Request</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('team.index') }}"  >Team</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('employee_relieve.index') }}"  >Employee Relieve</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('payroll.index') }}"  >Payroll</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('documents.index') }}"  >Documents</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('log_registers.index') }}"  >In-Out Register</a></li>
                          </ul>
                        </div>
                        <div class="card_top"  url-route="{{ route('hrm.dashboard') }}"  style="background-image: url('{{ URL::to('/') }}/public/package/hrm_1.jpg');">
                        </div>
                        <div class="card_bottom">
                          <div class="card_menu" id="menuNonOverlay" menu_id="hrm">
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                          </div>
                          <h1 class="card_h1" url-route="{{ route('hrm.dashboard') }}" >Human Resource</h1>
                          <br>
                          <div class="row">
                            <div class="col-md-12 text-center">
                              <button class="margin-left card_button" url-route="{{ route('hrm_attendance.index') }}">Attendance</button>
                            </div>
                            <div class="col-md-12 text-center">
                              <button class="card_button" url-route="{{ route('employees.index') }}">Employee</button>
                            </div>
                        </div>
                        </div>
                        <div id="background_hrm" class="card_background" style="background-image: url('{{ URL::to('/') }}/public/package/hrm_1.jpg');"></div>
                      </div>
                    </div>
                @endpermission
            @endif

            @if (App\Organization::checkModuleExists('trade', Session::get('organization_id')))
            	@permission('trade')
                    <div class="col-md-4">
                      <div class="card_profile">
                        <div id="overlay_trade" class="card_overlay_hide">
                          <div class="card_menu" id="menuOverlay" menu_id="trade">
                            <div class="card_bar card_menu_open_click-top" id="bartop"></div>
                            <div class="card_bar card_menu_open_click-middle" id="barmiddle"></div>
                            <div class="card_bar card_menu_open_click-bottom" id="barbottom"></div>
                          </div>
                            <ul class="card_nav">
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('transaction.index', ['estimation']) }}"  >Estimate</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('transaction.index', ['delivery_note']) }}"  >Delivery Challan</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('transaction.index', ['credit_note']) }}"  >Sale Return</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('cash_transaction.index', ['receipt']) }}"  >Receivables</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('contact.index', ['customer']) }}"  >Customer</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('item.index', ['items']) }}"  >Items</a></li>
                            <li class="card_nav_li" ><a class="card_nav_a" href="{{ route('receipt_report') }}"  >Receivables Report</a></li>
                          </ul>
                        </div>
                        <div class="card_top" url-route="{{ route('trade.dashboard') }}" style="background-image: url('{{ URL::to('/') }}/public/package/trade_1.jpg');">
                        </div>
                        <div class="card_bottom">
                          <div class="card_menu" id="menuNonOverlay" menu_id="trade">
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                            <div class="card_bar"></div>
                          </div>
                          <h1 class="card_h1" url-route="{{ route('trade.dashboard') }}" >Trade</h1>
                          <br>
                          <div class="row">
                            <div class="col-md-12 text-center">
                              <button class="margin-left card_button" url-route="{{ route('transaction.index', ['sale_order']) }}">Sales Order</button>
                            </div>
                            <div class="col-md-12 text-center">
                              <button class="card_button" url-route="{{ route('transaction.index', ['sales']) }}">Invoice</button>
                            </div>
                        </div>
                        </div>
                        <div id="background_trade" class="card_background" style="background-image: url('{{ URL::to('/') }}/public/package/trade_1.jpg');"></div>
                      </div>
                    </div>
                @endpermission
            @endif

            @if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))
                <div class="col-md-4">
                  <div class="card_profile">
                    <div id="overlay_workforce" class="card_overlay_hide">
                      <div class="card_menu" id="menuOverlay" menu_id="workforce">
                        <div class="card_bar card_menu_open_click-top" id="bartop"></div>
                        <div class="card_bar card_menu_open_click-middle" id="barmiddle"></div>
                        <div class="card_bar card_menu_open_click-bottom" id="barbottom"></div>
                      </div>
                        <ul class="card_nav">
                        <li class="card_nav_li" ><a class="card_nav_a" href="#"  >XXX XXXX</a></li>
                        <li class="card_nav_li" ><a class="card_nav_a" href="#"  >XXX XXXX</a></li>
                        <li class="card_nav_li" ><a class="card_nav_a" href="#"  >XXX XXXX</a></li>
                        <li class="card_nav_li" ><a class="card_nav_a" href="#"  >XXX XXXX</a></li>
                        <li class="card_nav_li" ><a class="card_nav_a" href="#"  >XXX XXXX</a></li>
                        <li class="card_nav_li" ><a class="card_nav_a" href="#"  >XXX XXXX</a></li>
                        <li class="card_nav_li" ><a class="card_nav_a" href="#"  >XXX XXXX</a></li>
                      </ul>
                    </div>
                    <div class="card_top" url-route="{{ route('wfm.dashboard') }}" style="background-image: url('{{ URL::to('/') }}/public/package/wfm_1.jpg');">
                    </div>
                    <div class="card_bottom">
                      
                      <h1 class="card_h1" url-route="{{ route('wfm.dashboard') }}" >Workforce</h1>
                      <br>
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <button class="margin-left card_button" url-route="{{ route('wfm.project_list') }}">Manage Projects</button>
                        </div>
                        <div class="col-md-12 text-center">
                          <button class="card_button" url-route="{{ url('wfm/wfm_settings_professional') }}">Master Dataset</button>
                        </div>
                    </div>
                    </div>
                    <div id="background_workforce" class="card_background" style="background-image: url('{{ URL::to('/') }}/public/package/wfm_1.jpg');"></div>
                  </div>
                </div>
            @endif

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="row">
            @if (App\Organization::checkModuleExists('super_admin', Session::get('organization_id')))
                <div class="col-md-4">
                  <div class="card_profile">
                    <div class="card_top" url-route="{{ route('admin.dashboard') }}" route style="background-image: url('{{ URL::to('/') }}/public/package/system_admin.jpg');">
                    </div>
                    <div class="card_bottom">
                      <h1 class="card_h1" url-route="{{ route('admin.dashboard') }}" >Propel Admin</h1>
                          <br>
                          <div class="row">
                            <div class="col-md-12 text-center">
                              <button class="margin-left card_button" url-route="{{ route('organization.index') }}">Organization</button>
                            </div>
                            <div class="col-md-12 text-center">
                              <button class="card_button" url-route="{{ route('person.index') }}">Persons</button>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            @endif

        </div>
        <br>
        <br>
        <br>
    @endif
</div>

@endsection
@section('dom_links')
@parent
<script>
$(".card_menu").click(function(){

    var id = $(this).attr('id');
    var menu_id = $(this).attr('menu_id');

    console.log(id);
    console.log(menu_id);

    var overlayid = '#overlay_'+ menu_id;
    var backgroundid = '#background_'+ menu_id;

    $(backgroundid).toggleClass("card_show");

    if (id == 'menuNonOverlay') {
      $(overlayid).removeClass("card_overlay_hide");
      $(overlayid).toggleClass("card_overlay_view");
    }else{
      $(overlayid).removeClass("card_overlay_view");
      $(overlayid).toggleClass("card_overlay_hide");
    }

});


$('.loader_wall').show();
$(document).ready(function() {
	var count_of_arrary=$('.count_of_arrary').val();
	if(count_of_arrary != 0){
		$('.news_update').show();
	}

	 //on select event
    $('.card_top, .card_h1, .card_button').on('click',function(){
		var url = $(this).attr('url-route');
		window.location.href = url;
    } );


});

$(window).on('load', function(){
	$('.loader_wall').fadeOut();
});

</script>
@stop