@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<style>
  .dashboard-stat{
    width: 190px;
    height: 70px;
    /* margin-left: 20px; */
  }
  .box_content{
    color: #fff;
    font-size: 18px;
    padding: 16px;
     /*position: absolute;
    margin-top: 15px;
   margin-left: 65px; */
  }

  .spacer{
    width: 1%;   
}
.add_transaction_page{
  width:50%;
  float:right;
  z-index: 0;
  display:none;
  margin-top: 36px;
  margin-right: -64px;
}
#main{
  width:100%;
  float:left;
}
.color{
  background:  #ff9999 !important;
}
</style>
@stop
@include('includes.accounts')
@section('content')

<div class="fill header" style="height:45px;width: 102%;background-color: #e3e3e9;margin-left: -10px;margin-bottom: 20px;">
    <div class="row" style="padding-top: 5px;">
        <div style="float: left;margin-left: 40px;">
          <h5 class="float-left page-title"><b>Bank \ Cash Transactions</b></h5>
        </div>
    </div>
</div> 

 <div class="form-inline" style="margin-bottom:  20px;">            
        <div class="col-md-3 col-sm-3 col-lg-3 form-group" style="/*margin-left: 175px;*/">
          <label class="col-form-label" for="from-date">From Date:</label>
          <div class='input-group from-date'  id="from-date">
            <input name='from_date' type='text' value={{$from_date_to_show}} class="form-control date-picker" data-date-format ="dd-mm-yyyy"/>
            <span class="input-group-addon">
              <span class="fa fa-calendar"></span>
            </span>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-lg-3 form-group">
          <label class="col-form-label" for="to_date">To Date:</label>
          <div class='input-group from-date'  id="to-date">
            <input name='to_date' type='text' value={{$to_date_to_show}} class="form-control date-picker" data-date-format="dd-mm-yyyy"/>
            <span class="input-group-addon">
              <span class="fa fa-calendar"></span>
            </span>
          </div>
        </div>
         <div class="form-group">
          <button type="button" class="btn btn-outline-success btn-sm search" style="float:right">Search</button>
        </div> 
      </div>   
 <div> 
    <div class="clearfix"></div>
    <div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
    @if($errors->any())
      <div class="alert alert-danger"> @foreach($errors->all() as $error)
        <p>{{ $error }}</p> @endforeach 
      </div>
    @endif
    <div class="row" id="box_values">
        @foreach($box_values as $box_value)
        <div class="col-md-2">
         <div class="dashboard-stat" id="{{$box_value->ledger_id}}" data-group_id = "{{$box_value->group_id}}" data-group_name = "{{$box_value->group_name}}" data-ledger_name = "{{$box_value->ledger_name}}" style="background: #ffa64d;">
        <div class="box_content" id="ledger_{{ strtolower(str_replace(' ', '', $box_value->ledger_name))}}">
          <center>
           {{$box_value->ledger_name}}
           @if($box_value->opening_balance_type == "debit")
            <span class="fa fa-inr" id="amt">{{$box_value->closing_balance}}</span>
            @else
             <span class="fa fa-inr" id="amt">{{$box_value->closing_balance1}}</span>
             @endif
           </center>
        </div>
      </div>
        </div>
        <div class="spacer"></div>
        @endforeach
    </div>
 </div> 
<div class="column">
   <div id="main">
     <!--  <div class="form-inline" style="margin-top: -19px;">            
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
     </div> -->
      <a class="btn btn-danger float-right openbtn" style="color: #fff;padding-top: 1px;">New</a> 
      <table id="datatable" class="table data_table table-hover contra_table" cellspacing="0">
        <thead>
          <th>Voucher No</th>
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
            <td>{{$table_value->date}}</td>
            <td>{{$table_value->from_account}}</td>
            <td>{{$table_value->to_account}}</td>
            <td>{{$table_value->amount}}</td>
            <td><a class="grid_label action-btn edit-icon edit" id="{{$table_value->id}}"><i class="fa li_pen"></i></a><a class="grid_label action-btn delete-icon delete" id="{{$table_value->id}}" data-id="{{$table_value->transaction_id}}"><i class="fa fa-trash-o"></i></a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
  </div>
  <div id="mySidebar" class="add_transaction_page">

    <form class="form-horizontal validateform">
       <div class="form-inline">  
        <div class="col-sm-offset-2 col-sm-3 col-md-3 col-lg-3">
          <button type="submit" class="btn btn-success save ">Save</button>
          <a style="display: none;width: 73px;" class="btn btn-success update">Update</a>
        </div>
        <div class="col-sm-offset-2 col-md-3 col-sm-3 col-lg-3">
          <button type="button" class="btn btn-outline-default btn-sm" id="close" style="float:left">Close</button>
        </div>
      </div>
      <br><br>
      <div class="form-group voucher_box" style="display:none;">
        <label class="control-label col-sm-3 col-md-3" for="voucher_no" style="color: #b73c3c;">Voucher No:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="voucher_no" id="voucher_no" disabled="disabled">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="from_account" style="color: #b73c3c;">From Account:</label>
        <div class="col-sm-10 col-md-10">
         <select class="form-control" name="from_account" id="from_account">
            <option value=''>Select Account</option>
            @foreach($select_values as $select_value)
            <option value='{{$select_value->id}}'data-group_id="{{$select_value->group_id}}" data-group_name="{{$select_value->group_name}}">{{$select_value->display_name}}</option>
            @endforeach
          </select> 
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="to_account" style="color: #b73c3c;">To Account:</label>
        <div class="col-sm-10 col-md-10">
          {{ Form::select('to_account',[''=> 'Select Account'],null,['id' => 'to_account','class' => 'form-control select_item']) }}
         <!-- <select class="form-control" name="to_account" id="to_account">
           <option value=''>Select Account</option>
            @foreach($select_values as $select_value)
            <option value='{{$select_value->id}}'>{{$select_value->display_name}}</option>
            @endforeach
          </select>  -->
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="date" style="color: #b73c3c;">Date:</label>
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
        <label class="control-label col-sm-3 col-md-3" for="amount" style="color: #b73c3c;">Amount:</label>
        <div class="col-sm-10 col-md-10">
          <input type="text" class="form-control" name="amount" id="amount">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="reference_no">Reference No:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="reference_no" id="reference_no">
        </div>
      </div>

     <!--  <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="notes">Notes:</label>
        <div class="col-sm-10 col-md-10">
          <textarea class="form-control" rows="5" name="notes" id="notes"></textarea>
        </div>
      </div> -->

      
     <!--    <div class="form-inline">  
     <div class="col-sm-offset-2 col-sm-3 col-md-3 col-lg-3">
       <button type="submit" class="btn btn-success save ">Save</button>
       <a style="display: none;width: 73px;" class="btn btn-success update">Update</a>
     </div>
     <div class="col-sm-offset-2 col-md-3 col-sm-3 col-lg-3">
       <button type="button" class="btn btn-outline-default btn-sm" id="close" style="float:left">Close</button>
     </div>
           </div> -->


    </form>
  </div>
</div> 

@stop
@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript">

  var datatable = null;

  var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true,"searching": false};

$(document).ready(function() {

  datatable = $('#datatable').DataTable(datatable_options);

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
    $('input[name=voucher_no]').val('');
    $('.voucher_box').css('display','none');
    $('#from_account').val('');
    $('select[name=to_account]').val('');
    $('input[name=date]').val('');
    $('#amount').val('');
    $('#reference_no').val('');
  });

  $('#from_account').on('change',function(){
    //alert();
    $('#to_account').html('');
    $('#to_account').append("<option value=''>Select Account</option>");

    $.ajax({
      type : 'get',
      url: '{{ route('get_to_account') }}',
      data:{
        id: $('#from_account').val()
      },
      success:function(data)
      {
        //alert();
        console.log(data.data);
        var datas = data.data;
        for(var i in datas)
        {
          $('#to_account').append("<option value='"+datas[i].id+"' data-group_id='"+datas[i].group_id+"' data-group_name='"+datas[i].group_name+"'>"+datas[i].display_name+"</option>");
        }
      },
      error:function()
      {

      }

    });

  });

  var addclass = 'color';

  var $cols = $('body').on('click','.dashboard-stat',function(){
   
   
    //$cols.removeClass(addclass);
    $('.dashboard-stat').removeClass(addclass);

    $(this).addClass(addclass);

    var ledger_id = $(this).attr('id');
    var group_id = $(this).attr('data-group_id');
    var group_name = $(this).attr('data-group_name');
    var ledger_name =  $(this).attr('data-ledger_name');
    var from_date = $('input[name=from_date]').val();
    var to_date = $('input[name=to_date]').val();
    

    var html = '';
   
    $.ajax({
              type: 'post',
              url: "{{ route('bank_transactions.reset')}}",
              data: {
                      _token: '{{ csrf_token() }}',
                      group_id : group_id,
                      ledger_id: ledger_id,
                      ledger_name: ledger_name,
                      from_date : from_date,
                      to_date : to_date
              },
              success:function(data,jqXHR,textStatus)
              {
                $('#from_account').val(ledger_id);
                $('#from_account').attr('data-group_id',group_id);
                $('#from_account').attr('data-group_name',group_name);
                $('#to_account').html('');
                $('#to_account').append("<option value=''>Select Account</option>");
                var to_account = data.select_value;
                for(var i in to_account)
                {
                $('#to_account').append("<option value='"+to_account[i].id+"' data-group_id='"+to_account[i].group_id+"' data-group_name='"+to_account[i].group_name+"'>"+to_account[i].display_name+"</option>");

                }
                var datas = data.data
                $('#datatable tbody').empty();
                for(var i in datas)
                {
                  html+=`<tr>
                      <td>`+datas[i].voucher_no+`</td>
                      <td>`+datas[i].date+`</td>
                      <td>`+datas[i].from_account+`</td>
                      <td>`+datas[i].to_account+`</td>
                      <td>`+datas[i].amount+`</td>
                      <td>
                       <a data-id="`+datas[i].transaction_id+`" id="`+datas[i].id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>
                      <a data-id="`+datas[i].transaction_id+`" id="`+datas[i].id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
                      </td>>/tr>`
                 
                }
                call_back_optional(html,`add`,``);
              }      

      });
  });

  $('#from_account').on('change',function(){
        var group_name = $(this). children("option:selected").attr('data-group_name');
        var group_id = $(this). children("option:selected").attr('data-group_id');
        $('#from_account').attr('data-group_id',group_id);
        $('#from_account').attr('data-group_name',group_name);
  });

  $('.validateform').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    rules: {
      from_account: { required: true },
      to_account: { required: true },
      amount: { required: true },
      date: { required: true },
      voucher_no: { required: true },
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
      voucher_no: { required: "Voucher No is required." },             
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
      
        var group_id = $('#from_account').attr('data-group_id');
        var group_name = $('#from_account').attr('data-group_name');
        var ledger = '';
        if(group_name == 'Cash-in-hand'){
            ledger = 'deposit';
        }else if(group_name == 'Bank Account')
        {
          ledger = 'withdrawal';
        }
        var from_account = $('#from_account').val();
        $.ajax({
            url: "{{ route('bank_transactions.store') }}",
            type: 'post',
            data: {
                  _token: '{{ csrf_token() }}',  
                  date:$('input[name=date]').val(),
                  amount:$('#amount').val(),
                 /* notes: $('#notes').val(),*/
                  reference_no:$('#reference_no').val(),
                  from_account: $('#from_account').val(),
                  to_account: $('#to_account').val(),
                  ledger:ledger
            },
            success: function(data, textStatus, jqXHR) {
             
             var tot_val = data.data.amount;
             var str = data.data.to_account;
             var str_re = str.toLowerCase();
             var amount=parseInt($("#ledger_"+str_re).find('span').text());
             var total = parseFloat(amount) + parseFloat(tot_val);
             $("#ledger_"+str_re).find('span').text(total);

             
            

                

              call_back(`<tr role="row" class="odd">
                <td>`+data.data.voucher_no+`</td>
                <td>`+data.data.date+`</td>
                <td>`+data.data.from_account+`</td>
                <td>`+data.data.to_account+`</td>
                <td>`+data.data.amount+`</td>
                <td>
                <a data-id="`+data.data.transaction_id+`" id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>
                <a data-id="`+data.data.transaction_id+`" id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
                </td></tr>`, `add`, data.message);
                $('#main').css('width', '100%');
                $('#mySidebar').css('display', 'none');
                $('.alert-success').css('z-index',9);
                $('.loader_wall_onspot').hide();
               // location.reload();


            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
        });

        
    }
  });

  $('#datatable').on('click', '.edit', function(){
      var id = $(this).attr('id');
         $.ajax({
            url: "{{ url('accounts/bank_transactions') }}/"+id+"/edit",
            type: 'get',
            data: {
            },
            success: function(data, textStatus, jqXHR) {
              $('.update').css('display','block');
              $('.save').css('display','none');
              $('.update').attr('id',id);
              $('.update').attr('data-name',data.data.voucher_name);
              $('input[name=voucher_no]').val(data.data.voucher_no);
              $('.voucher_box').css('display','block');
              $('#from_account').val(data.data.from_account);
              $('select[name=to_account]').val(data.data.to_account);
              $('input[name=date]').val(data.data.date);
              $('#amount').val(data.data.amount);
              $('#reference_no').val(data.data.reference_voucher);
              $('#reference_no').attr('data-voucher_id',data.data.voucher_id);
             /* $('#notes').val(data.data.description);*/
              $('#main').css('width', '50%');
              $('#mySidebar').css('display', 'block');
             
            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
      });

  });

  $('.update').on('click',function(){

    $.ajax({
            url: "{{ route('bank_transactions.update') }}",
            type: 'post',
            data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    id:$(this).attr('id'),
                    date:$('input[name=date]').val(),
                    amount:$('#amount').val(),
                   /* notes: $('#notes').val(),*/
                    reference_no:$('#reference_no').val(),
                    from_account: $('#from_account').val(),
                    to_account: $('#to_account').val(),
                    reference_voucher: $('#reference_no').attr('data-voucher_id'),
                    ledger:$(this).attr('data-name')
            },
            beforeSend: function() {
                  $('.loader_wall_onspot').show();
            },
            success:function(data, textStatus, jqXHR) {

             var tot_val = data.data.amount;
             var str = data.data.to_account;
             var str_re = str.toLowerCase();
             var amount=parseInt($("#ledger_"+str_re).find('span').text());
             var total = parseFloat(amount) + parseFloat(tot_val);
             $("#ledger_"+str_re).find('span').text(total);

              call_edit(`<tr role="row" class="odd">
                <td>`+data.data.voucher_no+`</td>
                <td>`+data.data.date+`</td>
                <td>`+data.data.from_account+`</td>
                <td>`+data.data.to_account+`</td>
                <td>`+data.data.amount+`</td>
                <td>
                <a data-id="`+data.data.transaction_id+`" id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>
                <a data-id="`+data.data.transaction_id+`" id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
                </td></tr>`, `edit`, data.message, data.data.id);
                $('#main').css('width', '100%');
                $('#mySidebar').css('display', 'none');
                $('.alert-success').css('z-index',9);
                //location.reload(true);
                $('.loader_wall_onspot').hide();
            },
            error:function(jqXHR, textStatus, errorThrown) {
        
            }
      });
  })

  $('#datatable').on('click', '.delete', function(){
      var id = $(this).attr('id');
      var transaction_id = $(this).attr('data-id');
      var parent = $(this).closest('tr');
      var delete_url = "{{ route('bank_transactions.destroy') }}";
      $.ajax({
             url: delete_url,
             type: 'post',
             data: {
                    _method: 'delete',
                    _token : "{{ csrf_token() }}",
                    id: id,
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


  $('.search').on('click',function(){
      var from_date = $('input[name=from_date]').val();
      var to_date = $('input[name=to_date]').val();
    
      var html ='';

      $.ajax({
              type: 'post',
              url: "{{ route('bank_transactions.search')}}",
              data: {
                      _token: '{{ csrf_token() }}',
                      from_date : from_date,
                      to_date : to_date,
              },
              success:function(data,jqXHR,textStatus)
              {

                $('.dashboard-stat').removeClass(addclass);
                var datas = data.data.data;
              
                $('#datatable tbody').empty();

               
            for(var i in datas)
                {
                  var description = datas[i].description;
                  if(description == null){
                    description = "";
                  }

                  html+=`<tr>
                      <td>`+datas[i].voucher_no+`</td>
                      <td>`+datas[i].date+`</td>
                      <td>`+datas[i].from_account+`</td>
                      <td>`+datas[i].to_account+`</td>
                      <td>`+datas[i].amount+`</td>
                      <td>
                      <a data-id="`+datas[i].id+`" id="`+datas[i].id+`" class="grid_label action-btn edit-icon edit transaction_change" ><span><i class="fa li_pen"></i></span></a>
                      <a data-id="`+datas[i].id+`" id="`+datas[i].id+`" class="grid_label action-btn delete-icon delete transaction_change" ><span><i class="fa fa-trash-o"></i></span></a>
                      </td>>/tr>`
                 
                }
               
              
              
             call_back_optional(html,`add`,``);

              }      

      });
  });

  $('#close').on('click',function(){
      $('#main').css('width', '100%');
      $('#mySidebar').css('display', 'none');
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