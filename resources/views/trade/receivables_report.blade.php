@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

	<style>
.dataTables_wrapper .dt-buttons {
  float:right;  
  text-align:center;
  color: #fff;
  top:-140px;
}
.buttons-excel {
  background-color: red;
  color: white;
}

    .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
        .btn
        {
            line-height: 1;
        }
      
		input.form-control
		{
			height: 25px;
			
		}
		
</style>
@stop
@include('includes.trade')
@section('content')

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
 <div class="alert alert-danger date">The End Date not before the Start Date.</br>
        <center>Please enter the valid end date.</center>
</div> 

@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
  <h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Receivables Report</b></h5>
</div>

<div class="modal-body">
	<div class="clearfix"></div>
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<div class="form-inline">            
	                <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="from_date">From Date</label>
	                    {!! Form::text('from_date', null, array('class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'mydate','style'=>'color: #919191;')) !!}
	                </div>
	                <div class="col-md-2 form-group">
	                    <label class="col-form-label" for="to_date">To Date</label>
	                    {!! Form::text('to_date', null, array('class' => 'form-control date-picker to-date-picker', 'data-date-format' => 'dd-mm-yyyy','style'=>'color: #919191;','id'=>'to')) !!}
	                </div>
	                <div class="col-md-2">
	                	<br><br>
				        <button type="submit" class="btn btn-success search"> Search </button>
				    </div>	
	            </div>
			</div>			
		</div>
	</div>
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
	  <tr>
		<th>{{ Form::checkbox('checkbox_all', 'checkbox_all', null ) }} <label for="check_all"><span></span></label></th>
		 <th> Receipt Number </th>  
		 <th> Reference Number </th>
		<th> Customer Name </th>
		<th> Date </th>
		<th> Amount </th>
		<th> View </th>
	  </tr>
	</thead>
	 <tbody>
    </tbody>
  </table>
</div>

@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript">
   var datatable = null;

  var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [], 

		dom: 'lBfrtip',
		buttons: [
			{
				extend: 'excel',
				exportOptions: {
					columns: ":not(.noExport)"
				},
				text: 'Export to Excel',
				footer: false
			}
		]


	};
    $(document).ready(function() {

    	datatable = $('#datatable').DataTable(datatable_options);

        $("#mydate").datepicker().datepicker("setDate", new Date());

	$('.search').on('click',function(){
       
		 var html='';
        var from_date = $('input[name=from_date]').val();
		var to_date = $('input[name=to_date]').val();
          $.ajax({
			url : '{{ route('receipt_details') }}',
			type: 'POST',
			data:
			{
				_token: '{{ csrf_token() }}',
				from_date: from_date,
				to_date : to_date
			},
			success:function(data,textStatus,jqXHR)
			{

				var report = data.data;
				$('#datatable tbody').empty();
				
				if( data.status == 1)
				{
					for(var i in report)
					{
						var order_no = report[i].order_no;
						var customer = report[i].customer_name;
						if(order_no == null){
                            order_no = '';
						}else{
							order_no = report[i].order_no;
						}
						if(customer == null){
							customer = '';
						}else{
							customer = report[i].customer_name;
						}
						html+=`<tr>
	        			<td><input id="`+report[i].transaction_id+`" class="item_check" name="reports" value="`+report[i].transaction_id+`" data-name="`+report[i].transaction_id+`" type="checkbox">
						<label for="`+report[i].id+`"><span></span></label></td>
	        			<td>`+report[i].voucher_no+`</td>
	        			<td>`+order_no+`</td>
	        			<td>`+customer+`</td>
	        			<td>`+report[i].date+`</td>
	        			<td>`+report[i].amount+`</td>
	        			<td><a data-id="`+report[i].id+`" class="grid_label action-btn grid_label edit-icon view"><i class="fa li_eye fa-xs"></i></a>
	        			</td>
	    			</tr>`;
	    			}
					optional_call_back(html,`add`,``);
					$('body').on('click', '.view', function(e) {6019
    e.preventDefault(); 
    var id = $(this).attr('data-id');
    $('.loader_wall_onspot').show();
    $('body').css('overflow', 'hidden');
    $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
      $.get("{{ url('trade/vouchers') }}/"+id+"/view", function(data) {
        $('.full_modal_content').show();
        $('.full_modal_content').html("");
        $('.full_modal_content').html(data);
        $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
          $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
        $('.loader_wall_onspot').hide();
      });
    });
  });
				

				}
				else
				{
					call_back_optional(``,`add`,``);
					alert_message(data.message,'error');
				}	
			},
			error:function()
			{

			}
		});
		});
function optional_call_back(data, modal, message, id = null) 
  	{
		  datatable.destroy();

		  datatable =  $('#datatable').DataTable(datatable_options);
		  datatable.clear().draw();


		  if($.trim(data))
		  {

		   data = data.replace(/^\s*|\s*$/g, '');
		   data = data.replace(/\\r\\n/gm, '');

		   var expr = "</tr>\\s*<tr";
		   var regEx = new RegExp(expr, "gm");
		   var newRows = data.replace(regEx, "</tr><tr");
		   datatable.rows.add($(newRows )).draw();
		 }

	}
	
});
  </script>
@stop