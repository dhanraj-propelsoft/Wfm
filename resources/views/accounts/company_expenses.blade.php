@extends('layouts.master')
@section('head_links') @parent
 <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}"> 
<style type="text/css">
  .add_expenses_page{
  width:50%;
  float:right;
  z-index: 0;
  display:none;
  /*margin-top: 36px;*/
  margin-right: -64px;
}
#main{
  width:100%;
  float:left;
}
label {
 margin: .5rem 0;
}
.validateform
{
  line-height: 0.2px;
}
</style>
@stop
@if($module_name == "books")
@include('includes.accounts')
@elseif($module_name =="mship")
@include('includes.mship')
@endif

@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header" style="height:45px;width: 102%;background-color: #e3e3e9;margin-left: -10px;margin-bottom: 20px;">
    <div class="row" style="padding-top: 5px;">
        <div style="float: left;margin-left: 40px;">
          <h5 class="float-left page-title"><b>Company Expenses</b></h5>
        </div>
    </div>
</div>    
<div class="column">
   <div id="main">
    <div class="form-inline" style="margin-top: -19px;">            
        <div class="col-md-3 col-sm-3 col-lg-3 form-group" style="margin-left: 175px;">
          <label class="col-form-label" for="from-date">From Date:</label>
          <div class='input-group from-date'  id="from-date">
            <input type='text' class="form-control from_date"/>
            <span class="input-group-addon">
              <span class="fa fa-calendar"></span>
            </span>
          </div>
        </div>
          <div class="col-md-3 col-sm-3 col-lg-3 form-group">
          <label class="col-form-label" for="to_date">To Date:</label>
          <div class='input-group from-date'  id="to-date">
            <input type='text' class="form-control to_date"/>
            <span class="input-group-addon">
              <span class="fa fa-calendar"></span>
            </span>
          </div>
        </div>
         <div class="form-group">
          <button type="button" class="btn btn-outline-success btn-sm search" style="float:right">Search</button>
        </div> 
      </div>
      <a class="btn btn-danger float-right openbtn" style="color: #fff;padding-top: 1px;">New</a> 
       <button type="button" class="btn btn-success btn-sm float-right referesh">
      <i class="fa fa-refresh fa-spin" ></i> Refresh </button>
      <table id="datatable" class="table data_table table-hover" cellspacing="0">
        <thead>
          <th>Voucher No</th>
          <th>Expenses Voucher No</th>
          <th>Date</th>
          <th>From</th>
          <th>To</th>
          <th>Amount</th>
          <th>Action</th>
        </thead>
        <tbody>
         @foreach($table_values as $table_value)
          <tr>
            <td>{{$table_value->voucher_no}}</td>
            <td>{{$table_value->expense_voucher}}</td>
            <td>{{$table_value->date}}</td>
            <td>{{$table_value->from_account}}</td>
            <td>{{$table_value->to_account}}</td>
            <td>{{$table_value->amount}}</td>
            <td><a class="grid_label action-btn delete-icon delete" id="{{$table_value->id}}" data-id="{{$table_value->expenses_id}}" data-entry_id="{{$table_value->entry_id}}"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon edit" id="{{$table_value->id}}" data-to_account="{{$table_value->to_account}}"><i class="fa li_pen"></i></a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
  </div>
  <div id="mySidebar" class="add_expenses_page" style="">
    
    <form class="form-horizontal validateform">
       <div class="form-group" style="margin-left: 15px;">
        <div class="row col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-success btn-sm save">Save</button>&nbsp;&nbsp;&nbsp;
          <a style="display: none;width: 70px;" class="btn btn-success btn-sm update">Update</a>&nbsp;&nbsp;&nbsp;
          <button type="button" class="btn btn-outline-default btn-sm" id="close" style="float: right;">Close</button>
        </div>
      
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="from_account" style="color: #b73c3c;margin-top: 0.1px;">From Account:</label>
        <div class="col-sm-10 col-md-10">
         <select class="form-control" id="from_account" name="from_account">
            <option value=''>Select Account</option>
            @foreach($from_accounts as $from_account)
            <option value='{{$from_account->id}}'data-group_id="" data-group_name="">{{$from_account->display_name}}</option>
            @endforeach
          </select> 
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-10 col-md-10">
          <label class="control-label" for="to_account" style="color: #b73c3c;margin-top: 0.1px;">To Account:</label>
            <div class="row custom-panel">
              <div class="col-md-6 customer_type" style= ""> 
                <div class="" style="background-color: #e9ecef">
                  <input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
                  <label for="business_type" class="custom-panel-radio"><span></span>Business</label>
                  <input id="people_type" type="radio" name="customer" value="0"  />
                  <label for="people_type" ><span></span>People</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="row">
                      <div class="form-group col-md-12 search_container business"> 
                           <!--  {{ Form::label('people','Customer', array('class' => 'control-label col-md-12 required')) }} -->
                        {{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
                        {{ Form::select('people_id', $business, null, ['class' => 'form-control business_id', 'id' => 'business_id']) }}
                        {{ Form::checkbox('account_person_type_id', 'vendor', true, ['id' => 'account_person_type_id']) }}
                        <div class="content"></div>
                    
                    </div> 

                    <div class=" form-group col-md-12 search_container people"> 
                     <!--  {{ Form::label('business', 'Customer', array('class' => 'control-label col-md-12 required')) }} -->
                        {{ Form::select('people_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
                        {{ Form::checkbox('user_type', '0', null, ['id' => 'user_type']) }}
                  
                        {{ Form::checkbox('account_person_type_id', 'vendor', null, ['id' => 'account_person_type_id']) }}
                        <div class="content"></div>
                       
                    </div>
                    <!-- <div class="col-md-12 search_container people " style= "">
                                      {{ Form::select('people_id', $people, null, ['class' => 'form-control person_id people_id', 'id' => 'person_id']) }}
                                      {{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
                                      {{ Form::checkbox('account_person_type_id', 'vendor', true, ['id' => 'account_person_type_id']) }}
                                      <div class="content"></div>
                                    </div>
                                    <div class="col-md-12 search_container business" style= "">
                                      {{ Form::select('people_id',$business, null, ['class' => 'form-control business_id people_id', 'id' => 'business_id']) }}
                                      {{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
                                      {{ Form::checkbox('account_person_type_id', 'vendor', true, ['id' => 'account_person_type_id']) }}
                                      <div class="content"></div>
                                    </div> -->                
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
     
      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="expense_ledger" style="color: #b73c3c;margin-top: 0.1px;">Expenses Ledgers:</label>
        <div class="col-sm-10 col-md-10">
         <select class="form-control" id="expense_ledger" name="expense_ledgers">
           <option value=''>Select Ledger</option>
            @foreach($expense_ledgers as $expense_ledger)
            <option value='{{$expense_ledger->id}}'>{{$expense_ledger->ledger_name}}</option>
            @endforeach
          </select> 
        </div>
      </div>
       <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="date" style="color: #b73c3c;margin-top: 0.1px;">Date:</label>
        <div class="col-sm-10 col-md-10 form-group">
          <div class='input-group date'  id="date">
           <input class="form-control  valid" data-date-format="dd-mm-yyyy" name="date" type="text" aria-describedby="date-error" aria-invalid="false">
            <span class="input-group-addon">
              <span class="fa fa-calendar"></span>
            </span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <div class="col-md-10">
            <div class="row">
              <div class="col-md-4">
                <label class="control-label col-md-12" for="amount" style="color: #b73c3c;margin-top: 0.1px;">Amount Before Tax:</label>
                <div class="col-md-12">
                  <input type="text" class="form-control" id="amount_before_tax" name="amount">
                </div>

              </div>
              <div class="col-md-4">
                <label class="control-label col-md-12" for="tax" style="margin-top: 0.1px;/*color: #b73c3c;*/">Tax % <!-- included -->:</label>
                <div class="col-md-12">
                <select class="form-control" id="tax_id" name="tax" >
                 <option value=''>Select Tax</option>
                  @foreach($taxes as $tax)
                 <!--  <option value='{{$tax->id}}' data-type='{{$tax->tax_type}}' data-value='{{$tax->value}}'>{{$tax->name}}</option> -->
                  <option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
                  @endforeach
                </select> 
                </div>
              </div>
              <div class="col-md-4">
                <label class="control-label col-md-12" for="amount_after_tax" style="color: #b73c3c;margin-top: 0.1px;" >Amount After Tax:</label>
                <div class="col-md-12">
                  <input type="text" class="form-control" id="amount_after_tax" name="amount_after_tax" readonly>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="reference_no" style="margin-top: 0.1px;">Reference No:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="reference_no" name="reference_no">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="address" style="margin-top: 0.1px;">Address:</label>
        <div class="col-sm-10 col-md-10">
          <textarea class="form-control" rows="1" id="address" name="address" disabled="disabled"></textarea>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="city" style="color: #b73c3c;margin-top: 0.1px;">City:</label>
        <div class="col-sm-10 col-md-10">
          <input type="text" class="form-control" id="city" name="city" disabled="disabled">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="state" style="color: #b73c3c;margin-top: 0.1px;">State:</label>
       <div class="col-sm-10 col-md-10">
          <input type="text" class="form-control" id="state" name="state" disabled="disabled">
        </div>
      </div>

    <!--   <div class="form-group">
      <label class="control-label col-sm-3 col-md-3" for="date" style="color: #b73c3c;margin-top: 0.1px;">Date:</label>
      <div class="col-sm-10 col-md-10 form-group">
        <div class='input-group date'  id="date">
         <input class="form-control  valid" data-date-format="dd-mm-yyyy" name="date" type="text" aria-describedby="date-error" aria-invalid="false">
          <span class="input-group-addon">
            <span class="fa fa-calendar"></span>
          </span>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-3 col-md-3" for="amount" style="color: #b73c3c;margin-top: 0.1px;">Amount:</label>
      <div class="col-sm-10 col-md-10">
        <input type="text" class="form-control" id="amount" name="amount">
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-3 col-md-3" for="reference_no" style="margin-top: 0.1px;">Reference No:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="reference_no" name="reference_no">
      </div>
    </div> -->

     <!--  <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="notes">Notes:</label>
        <div class="col-sm-10 col-md-10">
          <textarea class="form-control" rows="5" id="notes"></textarea>
        </div>
      </div> -->

     <!--  <div class="form-group">
       <label class="control-label col-sm-3 col-md-3" for="tax" style="margin-top: 0.1px;/*color: #b73c3c;*/">Tax % included:</label>
       <div class="col-sm-10">
         <select class="form-control" id="tax" name="tax">
          <option value=''>Select Tax</option>
           @foreach($taxes as $tax)
          <option value='{{$tax->id}}' data-type='{{$tax->tax_type}}' data-value='{{$tax->value}}'>{{$tax->name}}</option>
           <option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>
           @endforeach
         </select> 
       </div>
     </div> -->

     <!--  <div class="form-group">
       <div class="row col-sm-offset-2 col-sm-10">
         <button type="submit" class="btn btn-success btn-sm save ">Save</button>&nbsp;&nbsp;&nbsp;
         <a style="display: none;width: 70px;" class="btn btn-success btn-sm update">Update</a>&nbsp;&nbsp;&nbsp;
         <button type="button" class="btn btn-outline-default btn-sm" id="close" style="float: right;">Close</button>
       </div>
      
        
      
     </div> -->

    </form>
  </div>
</div>
@include('includes.add_user')
@include('includes.add_business')
@stop
@section('dom_links')
@parent 
 <script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript">

  var datatable = null;

  var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true,"searching": false};

$(document).ready(function() {

   $('#amount_before_tax').on('input',function(){
    var amount_before_tax_value = $(this).val();
    $('#amount_after_tax').val(amount_before_tax_value);
    
  });

  $('.referesh').on('click',function(){
      location.reload(true);
   });

  $('#tax_id').on('change',function(){
    var price = $('#amount_before_tax').val();
    var tax_value = $('#tax_id').find('option:selected').data('value');
   
    if(tax_value == undefined)
    {
      tax_value == 0.00;
    }
    else
    {
      tax_value = tax_value;
    }
    var tax_amount = tax_value/100;
   
    var total_price = price * tax_amount;
   
    $('#amount_after_tax').val((parseFloat(price) + parseFloat(total_price)).toFixed(2));



  });

  datatable = $('#datatable').DataTable(datatable_options);

  $('.people').hide();

  $('#people_type').on('click', function(){
    $('.people').show();    
    $('.business').hide();  
    $('.people').find('select').prop('disabled', false);
    $('.business').find('select').prop('disabled', true);
    $('.people input[name=user_type]').prop('checked',true);
    $('.business input[name=user_type]').prop('checked',false);
    
    $('.people input[name=account_person_type_id]').prop('checked',true);
    $('.business input[name=account_person_type_id]').prop('checked',false);
  });

  $('#business_type').on('click', function(){ 
    
    $('.business').show();
    $('.people').hide();
    $('.business').find('select').prop('disabled', false);
    $('.people').find('select').prop('disabled', true);  
    $('.people input[name=user_type]').prop('checked',false);
    $('.business input[name=user_type]').prop('checked',true);
    $('.people input[name=account_person_type_id]').prop('checked',false);
    $('.business input[name=account_person_type_id]').prop('checked',true); 
  }); 

  $('#person_id').each(function() {
    $(this).prepend('<option value="0"></option>');
    select_user($(this));   
  });

  $('#business_id').each(function() {
    $(this).prepend('<option value="0"></option>');
    select_business($(this));
  });

  var currentTime = new Date(); 
  var DateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
  $(".date").datepicker().datepicker("setDate",currentTime);
  $(".from_date").datepicker().datepicker("setDate",DateFrom);
  $(".to_date").datepicker().datepicker("setDate",currentTime);
    $('#date').datepicker({
        todayHighlight: true
    });
    $('#from_date').datepicker({
        todayHighlight: true
    });
    $('#to_date').datepicker({
        todayHighlight: true
    });

  $('.openbtn').on('click',function(){
    $('#main').css('width', '50%');
    $('#mySidebar').css('display', 'block');
    $('select[name=from_account]').val('');
    $('#business_type').prop('checked', true);
    $('#people_type').prop('checked', false);
    $('.people').hide();
    $('.business').show();
    $('.business select[name=people_id]').prop('disabled',false);
    $('.people select[name=people_id]').prop('disabled', true);
    $('.business select[name=people_id]').val('');
    $('select[name=expense_ledgers]').val('');
    $('input[name=address]').val('');
    $('input[name=state]').val('');
    $('input[name=city]').val('');
    $('input[name=amount]').val('');
    $('select[name=tax]').val('');
    $('input[name=reference_no]').val('');
    
    $('.update').css('display','none');
    $('.save').css('display','block');
  
  });

  $('.search').on('click',function(){
      var from_date = $('.from_date').val();
      var to_date = $('.to_date').val();
      var html = '';

      $.ajax({
              type: 'post',
              url: "{{ route('company_expenses.search')}}",
              data: {
                      _token: '{{ csrf_token() }}',
                      from_date : from_date,
                      to_date : to_date,
              },
              success:function(data,jqXHR,textStatus)
              {
                var datas = data.data;
                $('#datatable tbody').empty();
                for(var i in datas)
                {
                  html+=`<tr>
                      <td>`+datas[i].voucher_no+`</td>
                      <td>`+datas[i].expense_voucher+`</td>
                      <td>`+datas[i].date+`</td>
                      <td>`+datas[i].from_account+`</td>
                      <td>`+datas[i].to_account+`</td>
                      <td>`+datas[i].amount+`</td>
                      <td>
                      <a id="`+datas[i].id+`" data-id="`+datas[i].expenses_id+`" data-entry_id="`+datas[i].entry_idid+`" class="grid_label action-btn delete-icon delete transaction_change" ><span><i class="fa fa-trash-o"></i></span></a>
                      <a id="`+datas[i].id+`" data-to_account="`+datas[i].to_account+`" class="grid_label action-btn edit-icon edit transaction_change" ><span><i class="fa li_pen"></i></span></a>
                      </td>>/tr>`
                 
                }

                call_back_optional(html,`add`,``);

              }      

      });
  });

  $('select[name=people_id]').on('change',function(e){
      e.preventDefault();
      var supplier_id = $(this).val();
      var type = $('input[name=customer]:checked').val();

      $.ajax({
            url: "{{ route('company_expenses.get_address') }}",
            type: 'post',
            data: {
                    _token: '{{ csrf_token() }}',
                    supplier_id:supplier_id,
                    type:type
            },
            success:function(data, textStatus, jqXHR) {
             $('input[name=address]').val(data.data.address);
             $('input[name=state]').val(data.data.state);
             $('input[name=city]').val(data.data.city);

            },
            error:function(jqXHR, textStatus, errorThrown) {
        
            }
      });

  });

  $('.validateform').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    rules: {
      from_account: { required: true },
      to_account: { required: true },
      expense_ledgers: { required: true },
      date: { required: true },
      amount: { required: true },
      /*tax:{ required: true },*/
      name: { 
        required: true,
        remote: {
            /*url: '{{ route('vehicle_make_name') }}',
            type: "post",
            data: {
             _token :$('input[name=_token]').val()
            }*/
          }
      },                
    },

    messages: {
      from_account: { required: "From Account is required." },
      to_account: { required: "To Account is required." },
      amount: { required: "Amount is required." },
      date: { required: "Date is required." },
      expense_ledgers: { required: "Expense Ledger is required." },  
     /* tax: { required: "Tax is required." },*/             
    },

    invalidHandler: function(event, validator) 
    { 
      //display error alert on form submit   
      $('.alert-danger', $('.login-form')).show();
    },

    highlight: function(element) 
    { // hightlight error inputs
      $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
    },

    success: function(label) {
      label.closest('.form-group').removeClass('has-error');
      label.remove();
    },

    submitHandler: function(form) {
        $('.loader_wall_onspot').show();
        var data = $('select[name=people_id]:not([disabled])').val();
          
        $.ajax({
            url: "{{ route('company_expenses.store') }}",
            type: 'post',
            data: {
                  _token: '{{ csrf_token() }}',  
                  date:$('input[name=date]').val(),
                  amount:$('input[name=amount]').val(),
                 /* notes: $('#notes').val(),*/
                  reference_no:$('input[name=reference_no]').val(),
                  from_account:$('select[name=from_account]').val(),
                  to_account: $('select[name=people_id]:not([disabled])').val(),
                  people_id: $('select[name=people_id]:not([disabled])').val(),
                  user_type: $("input[name='user_type']:checked").val(),
                  ledger:$('select[name=expense_ledgers]').val(),
                  tax:$('select[name=tax] option:selected').attr('data-value'),
                  tax_val : $('select[name=tax] option:selected').data('tax'),
                  tax_type:$('select[name=tax] option:selected').attr('data-type'),
                  amount_after_tax : $('input[name=amount_after_tax]').val(),
                  tax_percent:$('select[name=tax] option:selected').val()
            },
            success: function(data, textStatus, jqXHR) {
              call_back(`<tr role="row" class="odd">
                <td>`+data.data.voucher_no+`</td>
                <td></td>
                <td>`+data.data.date+`</td>
                <td>`+data.data.from_account+`</td>
                <td>`+data.data.to_account+`</td>
                <td>`+data.data.amount+`</td>
                <td><a data-id="`+data.data.expensese_id+`" id="`+data.data.id+`" data-entry_id = "`+data.data.entry_id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
                  <a class="grid_label action-btn edit-icon edit" id="`+data.data.id+`" data-to_account="`+data.data.to_account+`"><i class="fa li_pen"></i></a>
                </td></tr>`, `add`, data.message);
                $('#main').css('width', '100%');
                $('#mySidebar').css('display', 'none');
                $('.alert-success').css('z-index',9);
                $('.loader_wall_onspot').hide();

            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
        });

        
    }
  });

   $('#datatable').on('click', '.edit', function(){
      var id = $(this).attr('id');
      var to_account = $(this).attr('data-to_account');
      $.ajax({
            url: "{{ url('accounts/company_expenses') }}/"+id+"/edit",
            type: 'get',
            data: {
              to_account:to_account
            },
            success: function(data, textStatus, jqXHR) {
              $('input[name=date]').val(data.data.date);
              $('select[name=from_account]').val(data.data.from_account);
              if(data.user_type == 0) {
                $('#people_type').prop('checked', true);
                $('#business_type').prop('checked', false);
                $('.people').show();
                $('.business').hide();
                $('.business select[name=people_id]').prop('disabled', true);
                $('.people select[name=people_id]').prop('disabled', false);
                trigger_people = $('.people select[name=people_id]');
              }
              else if(data.user_type == 1) {
                $('#business_type').prop('checked', true);
                $('#people_type').prop('checked', false);
                $('.people').hide();
                $('.business').show();
                $('.business select[name=people_id]').prop('disabled',false);
                $('.people select[name=people_id]').prop('disabled', true);
                trigger_people = $('.business select[name=people_id]');
              }
              $('select[name=people_id]').val(data.to_account);
              $('select[name=expense_ledgers]').val(data.expense_ledger_id);
              $('input[name=address]').val(data.address);
              $('input[name=state]').val(data.state);
              $('input[name=city]').val(data.city);
              $('input[name=amount]').val(data.before_tax_amount);
              $('input[name=amount_after_tax]').val(data.amount);
              $('input[name=reference_no]').val(data.data.reference_voucher);

              $('select[name=tax]').val(data.tax_id);
              $('.update').css('display','block');
              $('.save').css('display','none');
              $('.update').attr('id',data.data.id);
              $('.update').attr('data-id',data.entry_id);
              $('.update').attr('data-name',data.data.voucher_no);
              $(trigger_people).trigger('change');
              $('#main').css('width', '50%');
              $('#mySidebar').css('display', 'block');
             
            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
      });

  });

$('#close').on('click',function(){
  $('#main').css('width', '100%');
  $('#mySidebar').css('display', 'none');
  
  
}); 

  $('.update').on('click',function(){

    $.ajax({
            url: "{{ route('company_expenses.update') }}",
            type: 'post',
            data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    id:$(this).attr('id'),
                    entry_id:$(this).attr('data-id'),
                    amount:$('input[name=amount]').val(),
                    reference_no:$('input[name=reference_no]').val(),
                    people_type: $("input[name='customer']:checked").val(),
                    from_account:$('select[name=from_account]').val(),
                    to_account: $('select[name=people_id]:not([disabled])').val(),
                    people_id: $('select[name=people_id]:not([disabled])').val(),
                    user_type: $("input[name='user_type']:checked").val(),
                    ledger:$('select[name=expense_ledgers]').val(),
                    tax:$('select[name=tax] option:selected').attr('data-value'),
                    tax_val : $('select[name=tax] option:selected').data('tax'),
                    tax_type:$('select[name=tax] option:selected').attr('data-type'),
                    amount_after_tax : $('input[name=amount_after_tax]').val(),
                    tax_percent:$('select[name=tax] option:selected').val()
            },
            beforeSend: function() {
                  $('.loader_wall_onspot').show();
            },
            success:function(data, textStatus, jqXHR) {
              call_edit(`<tr role="row" class="odd">
                <td>`+data.data.voucher_no+`</td>
                <td>`+data.data.expense_voucher_no+`</td>

                <td>`+data.data.date+`</td>
                <td>`+data.data.from_account+`</td>
                <td>`+data.data.to_account+`</td>
                <td>`+data.data.amount+`</td>
                <td><a data-id="`+data.data.expensese_id+`" id="`+data.data.id+`" data-entry_id = "`+data.data.entry_id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
                  <a class="grid_label action-btn edit-icon edit" id="`+data.data.id+`" data-to_account="`+data.data.to_account+`"><i class="fa li_pen"></i></a>
                </td></tr>`, `edit`, data.message, data.data.id);
                $('#main').css('width', '100%');
                $('#mySidebar').css('display', 'none');
                $('.alert-success').css('z-index',9);
                $('.loader_wall_onspot').hide();
            },
            error:function(jqXHR, textStatus, errorThrown) {
        
            }
    });
  });


   $('#datatable').on('click', '.delete', function(){
      var id = $(this).attr('id');
      var expensese_id = $(this).attr('data-id');
      var entry_id = $(this).attr('data-entry_id');
      var parent = $(this).closest('tr');
      var delete_url = "{{ route('company_expenses.destroy') }}";
      $.ajax({
             url: delete_url,
             type: 'post',
             data: {
                    _method: 'delete',
                    _token : "{{ csrf_token() }}",
                    id: id,
                    expensese_id:expensese_id,
                    entry_id:entry_id
              },
             dataType: "json",
             beforeSend: function() {
                  $('.loader_wall_onspot').show();
            },
            success:function(data, textStatus, jqXHR) {
              if(data.status == '1'){
                  datatable.destroy();
                  parent.remove();
                  datatable = $('#datatable').DataTable(datatable_options);
                  $('.loader_wall_onspot').hide();
                  alert_message(data.message, "success");
                  $('.alert-success').css('z-index',9)
                } else{
                  $('.loader_wall_onspot').hide();
                  alert_message(data.message, "error");
                  $('.alert-danger').css('z-index',9)
                }
            },
            error:function(jqXHR, textStatus, errorThrown) {
            }
      });
  });

   function call_edit(data, modal, message, id = null) {

    datatable.destroy();

    if($('.edit[id="' + id + '"]')) {
      $('.edit[id="' + id + '"]').closest('tr').remove();
    }

    $('.data_table tbody').prepend(data);

    datatable = $('#datatable').DataTable(datatable_options);

    alert_message(message, "success");
  }
});  
</script> 
@stop