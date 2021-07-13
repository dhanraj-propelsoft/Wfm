@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.accounts')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  @if($type == "payment")
    <h4 class="float-left page-title">Cash Payment</h4>
  @elseif($type == "receipt")
    <h4 class="float-left page-title">Cash Receipt</h4>
  @elseif($type == "deposit")
    <h4 class="float-left page-title">Bank Deposit</h4> 
  @elseif($type == "withdrawal")
    <h4 class="float-left page-title">Bank Withdraw</h4> 
  @elseif($type == "journal")
    <h4 class="float-left page-title">Journal Entry</h4> 
  @elseif($type == "credit_note")
    <h4 class="float-left page-title">Credit Note</h4> 
  @elseif($type == "debit_note")
    <h4 class="float-left page-title">Debit Note</h4>
  @endif

  @permission('ledger-group-create') <a class="btn btn-danger float-right add transaction_change" style="color: #fff">+ Create New</a> @endpermission
  <div class="float-right form-inline" style="padding-top: 7px;">
        <div>
          {{ Form::text('from_date',$firstDay_only_trade_wms,['class' => 'form-control date-picker', 'placeholder' => 'From Date', 'data-date-format' => 'dd-mm-yyyy','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
          {{ Form::text('to_date',$today,['class' => 'form-control date-picker','placeholder' => 'To Date','data-date-format' => 'dd-mm-yyyy','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
          <button style=" height:25px;margin-right: 20px; border-radius: 3px 3px 3px 3px" type="submit" class="date btn btn-success search_all"><i class="fa fa-search" ></i></button>
        </div>
        <input type="hidden" name="type" value="{{ $type }}">
    </div>
  @permission('ledger-group-approval')
  @if(isset($settings))
  <div style=" height: 20px; margin:5px 10px 0 0;" class="pull-right" ><span class="tooltips cursor-help" style="font-size:13px; pointer:" data-container="body" data-placement="top" data-original-title="Automatically approve ledger.">Auto Approval</span>
    <input name="approval" data-checkbox="{{$settings->id}}" class="make-switch" <?php if($settings->status == "1") { echo 'checked="true"'; } else { echo 'checked="false"'; } ?> data-size="mini" type="checkbox">
  </div>
  @endif
  @endpermission </div>
<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th> Date </th>

        <th> Voucher Number </th>
        @if($type == "payment" || $type == "withdrawal" || $type == "journal"|| $type == "credit_note"|| $type == "debit_note")
          <th>Debit Account</th>
        @endif

        @if($type == "receipt" || $type == "deposit" || $type == "journal" || $type == "credit_note"|| $type == "debit_note")
          <th>Credit Account</th> 

        @endif
      	
        <th> Amount </th>
        <th> Reference </th>
        <th> Action </th>
      </tr>
    </thead>
    <tbody>
    @foreach($vouchers as $voucher)    
    
    <tr>
      <td>{{ $voucher->date }}</td>

      <td>{{ $voucher->voucher_no }}</td>
      @if($type == "payment" || $type == "withdrawal" || $type == "journal"|| $type == "credit_note"|| $type == "debit_note")
        <td>{{ $voucher->ledger_name }}</td>

      @endif

     @if($type == "receipt" || $type == "deposit" || $type == "journal" || $type == "credit_note"|| $type == "debit_note")
        <td>{{ $voucher->credit_ledger_name }}</td>
         
      @endif
      <td>{{ $voucher->total_amount }}</td>
      <td>{{ $voucher->reference }}</td>
      <td>
         <!-- {{ $voucher->entry_id }}
        {{ $voucher->reference_voucher_id }} -->
        <a data-id="{{$voucher->id}}" class="edit transaction_change" ><span><i class="fa fa-eye"></i></span></a>
      	<a data-id="{{$voucher->id}}" @if($voucher->entry_id || $voucher->reference_voucher_id) style="display:none;" @endif class="grid_label action-btn edit-icon edit transaction_change"><i class="fa li_pen"></i></a>
      	<a data-id="{{$voucher->id}}" @if($voucher->entry_id || $voucher->reference_voucher_id) style="display:none;" @endif class="grid_label action-btn delete-icon delete transaction_change"><i class="fa fa-trash-o"></i></a>
      </td>
      </tr>
    @endforeach
      </tbody>
    
  </table>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript">
   var datatable = null;

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[2, "desc"]], "stateSave": true};

$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
    e.preventDefault(); 
    $('.loader_wall_onspot').show();
    var type_name = $('input[name=type]').val();
    $('body').css('overflow', 'hidden');
    $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
      $.get("{{ url('accounts/vouchers/transaction_entries_create') }}/"+type_name, function(data) {
        $('.full_modal_content').show();
        $('.full_modal_content').html("");
        $('.full_modal_content').html(data);
        $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
          $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
        $('.loader_wall_onspot').hide();
      });
    });
  });


  $('body').on('click', '.edit', function(e) {
    e.preventDefault(); 
    var id = $(this).data('id');
    $('.loader_wall_onspot').show();
    $('body').css('overflow', 'hidden');
    $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
      $.get("{{ url('accounts/vouchers') }}/"+id+"/transaction_entries_edit", function(data) {
        $('.full_modal_content').show();
        $('.full_modal_content').html("");
        $('.full_modal_content').html(data);
        $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
          $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
        $('.loader_wall_onspot').hide();
      });
    });
  });



	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});

	$('body').on('click', '.delete', function(){
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = '{{ route('vouchers.destroy') }}';
			delete_row(id, parent, delete_url, '{{ csrf_token() }}');
	 });


  $('body').on('click','.search_all',function(){

    var html='';
    var from_date = $('input[name=from_date]').val();
    var to_date = $('input[name=to_date]').val();

    $.ajax({
      type: 'post',
      url: '{{ route('vouchers.get_all_transactions_datas')}}',
      data: {
        _token: '{{ csrf_token() }}',
        from_date : from_date,
        to_date : to_date,
        type_name : $('input[name=type]').val(),

      },
      success:function(data,jqXHR,textStatus)
      {
        var datas = data.data;
            $('#datatable tbody').empty();

            for(var i in datas)
            {
              var reference = datas[i].reference;
              if(reference == null){
                reference = "";
              }

              html+=`<tr>
                      <td>`+datas[i].date+`</td>

                      <td>`+datas[i].voucher_no+`</td>`;
                      if(datas[i].voucher_type == "Cash Payment" || datas[i].voucher_type == "Bank Withdraw" || datas[i].voucher_type == "Journal Entry"|| datas[i].voucher_type == "Credit Note"|| datas[i].voucher_type == "Debit Note")
                      {
                      html+=`<td>`+datas[i].ledger_name+`</td>`;
                      }
        
                      

                     if(datas[i].voucher_type == "Cash Receipt" || datas[i].voucher_type == "Bank Deposit" ||datas[i].voucher_type == "Journal Entry" || datas[i].voucher_type == "Credit Note"|| datas[i].voucher_type == "Debit Note" || datas[i].voucher_type == "WMS Receipt")
                    {
                    html+=`<td>`+datas[i].credit_ledger_name+`</td>`;

                    }
         
      
                      html+=`
                      <td>`+datas[i].total_amount+`</td>
                      <td>`+reference+`</td>
                      <td><a data-id="`+datas[i].id+`" class="edit transaction_change" ><span><i class="fa fa-eye"></i></span></a>`;
                       if(datas[i].entry_id != null || datas[i].reference_voucher_id != null)
                      {

                        html+=`</td>`;
                      }
                      else
                      {
                        html+=`<a data-id="`+datas[i].id+`" class="grid_label action-btn edit-icon edit transaction_change" ><span><i class="fa li_pen"></i></span></a><a data-id="`+datas[i].id+`" class="grid_label action-btn delete-icon delete transaction_change" ><span><i class="fa fa-trash-o"></i></span></a></td>`;
                      }
                      html+=`</tr>`;
                 
            }

            call_back_optional(html,`add`,``);

      }      

    });

  });

});
	</script> 
@stop