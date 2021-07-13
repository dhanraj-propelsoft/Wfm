@extends('layouts.master')

@section('head_links') @parent

 <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
  <style>
  .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
  </style>

@stop



@if(Session::get('module_name') == "trade_wms")

  @include('includes.trade_wms')

@else

  @include('includes.inventory')

@endif



@section('content')



<div class="alert alert-success">

  {{ Session::get('flash_message') }}

</div>



@if($errors->any())

  <div class="alert alert-danger">

    @foreach($errors->all() as $error)

      <p>{{ $error }}</p>

    @endforeach

  </div>

@endif



<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">

  <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Job Status</b></h5>

  

<!--     <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->

  

</div>



<div class="float-left table_container" style="width: 100%; padding-top: 10px;">



  <table id="example" class="table data_table table-hover" width="100%" cellspacing="0">

    <thead>

      <tr>

        <th>{{ Form::checkbox('checkbox_all', 'checkbox_all', null ) }} <label for="check_all"><span></span></label></th>

        <th>Job</th>

        <th>Customer Name</th>

        <th>Item</th>

        <th>Assigned To</th>

        <th>From</th>

        <th>To</th>
         <th>Duration(hour)</th>
        <th>Status</th>

      </tr>

    </thead>

    <tbody>

    @foreach($transaction_details as $transaction_detail)

      <tr>

        <td width="1" style="padding-left: 7px;">{{ Form::checkbox('transaction_detail',$transaction_detail->id, null, ['id' => $transaction_detail->transaction_id, 'class' => 'item_check']) }}<label for=""><span></span></label></td>    

        <td>{{$transaction_detail->job_card_name}}</td>                      

        <td>{{$transaction_detail->customer_name}}</td> 

        <td>{{$transaction_detail->item}}</td> 

        <td>{{$transaction_detail->assigned_to}}</td>            

        <td>{{$transaction_detail->start_date}}</td>  

        <td>{{$transaction_detail->due_date}}</td>
         <td>{{$transaction_detail->duration}}</td>
        <td>@if($transaction_detail->job_item_status == '1')

            <label class="grid_label badge  status" style="background-color: #ff9933">Open</label>

          @elseif($transaction_detail->job_item_status == '2')

            <label class="grid_label badge  status" style="background-color: #33cc33">Closed</label>

          @elseif($transaction_detail->job_item_status == '3')

            <label class="grid_label badge  status" style="background-color: #ff3300">On Hold</label>

          @elseif($transaction_detail->job_item_status == '4')

            <label class="grid_label badge  status" style="background-color: #FFFF00">Progress</label>

          @endif

          

          <select style="display:none" id="{{ $transaction_detail->transaction_id }}" class="active_status form-control" name="status">

            <option @if($transaction_detail->job_item_status == 1) selected="selected" @endif value="1">Open</option>

            <option @if($transaction_detail->job_item_status == 2) selected="selected" @endif value="2">Closed</option>

            <option @if($transaction_detail->job_item_status == 3) selected="selected" @endif value="3">On Hold</option>

            <option @if($transaction_detail->job_item_status == 4) selected="selected" @endif value="4">Progress</option>

          </select></td>

      </tr>

    @endforeach

    </tbody>

  </table>

</div>



@stop



@section('dom_links')

@parent

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.dataTables.min.js') }}"></script>

<script type="text/javascript">

  var datatable = null;



  var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

  

   var groupColumn = 1;

 

    var table = $('#example').DataTable({

        "columnDefs": [

            { "visible": false, "targets": groupColumn }

        ],

        "order": [[ groupColumn, 'asc' ]],

        "displayLength": 25,

        "drawCallback": function ( settings ) {

            var api = this.api();

            var rows = api.rows( {page:'current'} ).nodes();

            var last=null;

 

            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {

                if ( last !== group ) {

                    $(rows).eq( i ).before(

                        '<tr class="group"><td colspan="8">Job Card No:'+group+'</td></tr>'

                    );

 

                    last = group;

                }

            } );

        }

    } );

 

    // Order by the grouping

   /* $('#example tbody').on( 'click', 'tr.group', function () {

        var currentOrder = table.order()[0];

        if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {

            table.order( [ groupColumn, 'desc' ] ).draw();

        }

        else {

            table.order( [ groupColumn, 'asc' ] ).draw();

        }

    } );*/



  $(document).ready(function() {



    datatable = $('#datatable').DataTable(datatable_options);

    $('body').on('click', '.status', function(e) {

      $(this).hide();

      $(this).parent().find('select').css('display', 'block');

    });



    $('body').on('change', '.active_status', function(e) {

      var status = $(this).val();

      var id = $(this).attr('id');

      var obj = $(this);

      var url = "{{ route('Jobstatus.status_approval') }}";

      status_approval(id, obj, status, url, "{{ csrf_token() }}");

    });



});

</script>

@stop