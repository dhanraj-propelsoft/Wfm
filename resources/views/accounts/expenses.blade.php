@extends('layouts.master')
@section('head_links') @parent
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}" />
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.print.min.css') }}" media='print'/>
 <style>
  .popover{
        max-width: 348px !important;
  }
  .input-group-addon {
    padding: .375rem .75rem;
    margin-bottom: 0;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    text-align: center;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: .25rem;
}
.input-group-addon:not(:first-child){
      border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.input-group.date .input-group-addon {
    cursor: pointer;
}
</style>
@stop
@if($module_name == "books")
@include('includes.accounts')

@elseif($module_name == "mship")
@include('includes.mship')
@endif
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Petty Cash Expenses</h4>
  <div style="padding-top: 23px;float: right;">
  <button type="button" class="btn btn-info btn-sm update">Update</button>
  <button type="button" class="btn btn-info btn-sm edit" style="display:none;float: left;">Update1</button>
  <button type="button" class="btn btn-success btn-sm referesh">
  	<i class="fa fa-refresh fa-spin" ></i> Refresh </button>
</div>
</div>
<div class="clearfix"></div>
<div class="row">
	<div class="col-sm-5">
<div id='calendar'></div>
<br>
<div style="border:1px solid;">
	<div class="form-inline" >            
      <div class="col-md-4 form-group">
          <label class="col-form-label" for="to_date">From Date</label>
      <div class='input-group date'  id="from-date">
        <input type='text' class="form-control from_date"/>
        <span class="input-group-addon">
        <span class="fa fa-calendar"></span>
        </span>
        </div>
      </div>
  <div class="col-md-4 form-group">
      <label class="col-form-label" for="to_date">To Date</label>
      <div class='input-group date'  id="to-date">
        <input type='text' class="form-control to_date" />
        <span class="input-group-addon">
        <span class="fa fa-calendar"></span>
        </span>
        </div>
  </div>
  <div class="col-md-2 form-group" style = "padding-top: 31px;">
    <button type="button" class="btn btn-outline-primary btn-sm show" style="float:right">show</button>
  </div>
  </div>
  <br>
 <div class="report" style="display:none;">
  <ul class="list-group">
  <li class="list-group-item">
   <p class="expense_report"><span class="text-primary"><b>Total Amount of Expenses:</b></span>
    <span class="text-success"><b data-value="amount"></b></span>
   </p>
</li>
</ul>
<div id="barchart" style="margin-left: 30px;margin-top: 20px">	
</div>
</div>
</div>
</div>
<div class="col-sm-7" style="padding-top: 42px;">
	<table id="datatable" class="table data_table expense_table" width="100%" cellspacing="0">
		<thead>
			<tr>
				<td style="display:none"></td>
				<td style="display:none"></td>
				<th>Sl No </th>    
				<th>Expenses</th>  
				<th>Amount</th>            
				<th>Employee</th>
				<th>Notes</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			<?php $count = 0; ?>
			 @foreach($expenses_lists as $expenses_list)
			<tr>
				<td class="col_id" style="display:none">{{$expenses_list->id}}</td>
				<td class="ledger_id" style="display:none;">{{$expenses_list->expense_ledger_id}} </td>
				<td><?php echo ++$count; ?></td>     
				<td class="name">{{$expenses_list->display_name}}</td> 
				<td>{!! Form::text('amount', null,['class' => 'form-control','style'=>'width: 88%;']) !!}</td>          
				<td>{{ Form::select('employee',$employee, null, ['class' => 'form-control select_item ']) }}</td>
				<td>{!! Form::textarea('description', null, ['class'=>'form-control','rows'=>"1",'cols'=>"20"]) !!}</td>
				<td>{!! Form::text('date', null,['class' => 'form-control','disabled']) !!}</td>
			</tr>
			 @endforeach
		</tbody>
		<tfoot calss="footer">
			<tr>
				<td></td>
				<td></td>
				<td calss="col_total"><span style="font-size:20px;"><i>Total:</i><span data-value="total"></span></span></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
</div>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
	var datatable = null;
	var report_datatable = null;

	var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true,"paging":   false,
        "ordering": false,"searching":   false,};
    report_datatable =  $('#report').DataTable({

    dom: 'B',
    buttons: [ { extend: 'excel', filename: 'Stock Report', 'title': 'Stock Report', exportOptions: { columns: ":not(.noExport)" },  footer: true }, ],
    fixedHeader: {
            header: true,
            footer: true
        }
 

});
	$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);
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
	var d = new Date();

	var curr_date = d.getDate();

	var curr_month = d.getMonth();

	var curr_year = d.getFullYear();

	$('input[name=date]').val(curr_year+"-"+curr_month+"-"+curr_date);
/*Calender */
var date = new Date();
var d = date.getDate();
var m = date.getMonth();
var y = date.getFullYear();
var clicked_date = '';
$('#calendar').fullCalendar({
	eventRender: function (eventObj, $el) {
        $el.popover({
            title: "Daily Expenses",
            trigger: 'hover',
            placement: 'top',
            container: 'body'
        });
    },
    eventLimit: true,
    eventColor: '#ccddff',
    events: {!! json_encode($expense) !!},
    dayClick: function(date, jsEvent, view) {
				update_expense($(this),  date.format());
			}

});
/**/
	function update_expense(obj, date) {
        $('input[name=date]').val(date);
		$('input[name=amount]').val('0.0');
		$(".fc-day").removeAttr('style');
		obj.css('background-color', '#e3f1f9');
		$.ajax({
		url: "{{ route('expenses.show') }}",
		type: 'get',
		data: {
		date: date			 
		},
		success: function(data, textStatus, jqXHR) {
			var html="";
			var expenses = data.expenses;
			var employee = data.employee;
			
			if(expenses.length > 0){
				$('.expense_table tfoot tr').find("[data-value='total']").text(data.amount.amount);
				var count = 1;
				for (var i in expenses) {
					var employee_name = expenses[i].first_name;
					var description = expenses[i].description;
					if(employee_name == null){
						employee_name = 'Select Employee';
					}else{
						employee_name = expenses[i].first_name;
				}
				if(description == null){
					description = "";
				}else{
					description = expenses[i].description;
				}
				html += `<tr class="entry" id="`+expenses[i].id+`" data-id="`+expenses[i].transaction_id+`">
				<td style="display:none" class="col_id">`+expenses[i].expense_id+`</td>
				<td>`+ count++ +`</td>
				<td class="name">`+expenses[i].display_name+`</td>
				<td><input type="text" class="form-control" style="width: 88%;" name="amount" id="`+expenses[i].expense_trans_id+`" value="` + expenses[i].amount + `" ></td>
				<td>`;
				html += `<select class="form-control select_item" name="employee">
					<option value="`+expenses[i].employee+`" selected="selected">`+employee_name+`</option>`;
					for (var j in employee) {
						html += `<option  value="` + employee[j].id + `">` + employee[j].first_name + `</option>`;
									}
									html += `</select>
				</td>`;
				html += `<td><textarea class="form-control" rows="1" cols="20" name="description">`+description+`</textarea></td>
				<td><input class="form-control" disabled="" name="date" type="text" value="`+expenses[i].date+`"></td>
				</tr>`;
			}
			
			$('.expense_table tbody').html(html);
			$('input[name=amount]').on('keyup change',function() {
    			var sum = 0;
    			$('input[name=amount]').each(function(){
        		sum += +$(this).val();
    		});
    		$('.expense_table tfoot tr').find("[data-value='total']").text(parseFloat(sum).toFixed(2));
			});
			$('.update').css('display','none');
			$('.edit').css('display','block');
		}else{
			$('.edit').css('display','none');
			$('.update').css('display','block');
		}
				 },
			 error: function(jqXHR, textStatus, errorThrown) {
				
			 }
		});
			
	}	
        $('input[name=amount]').on('keyup change',function() {
    		var sum = 0;
    		$('input[name=amount]').each(function(){
        	sum += +$(this).val();
    	});
    	$('.expense_table tfoot tr').find("[data-value='total']").text(parseFloat(sum).toFixed(2));
		});

        $('.update').on('click',function(){
        	$('.loader_wall_onspot').show();
            var name = $(".name").map(function(){ return $(this).text() }).get();
            var amount =  $('input[name=amount]').map(function(){ return $(this).val() }).get();
            var employee = $( "select[name=employee] option:selected" ).map(function(){ return $(this).val() }).get(); 
            var notes = $('input[name=description], textarea').map(function(){ return $(this).val() }).get();
            var description = notes.toString();
           	var mystring = description.replace(/,/g, " ");
            var voucher_type = 'Cash Payment';
            var date = $('input[name=date]').val();
            var payment_mode = 'cash';
            var reference_voucher_id = $('.col_id').map(function(){ return $(this).text() }).get();
            var debit_ledger=$('.ledger_id').map(function(){ return $(this).text() }).get();
            // var debit_ledger = 'Daily Expenses';
            var credit_ledger = 'Cash';
            var total_amount = $('.expense_table tfoot tr').find("[data-value='total']").text();
           
            $.ajax({
			 url: "{{ route('expenses.store') }}",
			 type: 'post',
			 data: {
			 _token: '{{ csrf_token() }}',
			 voucher_type: voucher_type,
			 date: date,
			 payment_mode: payment_mode,
			 reference_voucher_id: reference_voucher_id,
			 reference_voucher: name,
			 amount: amount,
			 employee: employee,
			 notes: mystring,
			 description:notes,
			 debit_ledger: debit_ledger,
			 credit_ledger: credit_ledger,
			 total_amount: total_amount
			},
			 dataType: "json",
			success: function(data, textStatus, jqXHR) {
				if(data.status == 1){
				$('.loader_wall_onspot').hide();
				$('.alert-success').text('Dalily Expenses Added for the date '+data.data.date);
				$('.alert-success').show();
				update_expense($("#calendar"),data.data.date);
				}
				 },
			 error: function(jqXHR, textStatus, errorThrown) {
				
			 }
		  });
        });

        $('.referesh').on('click',function(){
        	location.reload(true);
        });

        $('.edit').on('click',function(){
        	$('.loader_wall_onspot').show();
            var name = $(".name").map(function(){ return $(this).text() }).get();
            var amount =  $('input[name=amount]').map(function(){ return $(this).val() }).get();
            var employee = $( "select[name=employee] option:selected" ).map(function(){ return $(this).val() }).get(); 
            var notes = $('input[name=description], textarea').map(function(){ return $(this).val() }).get();
            var voucher_type = 'Cash Payment';
            var date = $('input[name=date]').val();
            var payment_mode = 'cash';
            var reference_voucher_id = $('.col_id').map(function(){ return $(this).text() }).get();
              var debit_ledger=$('.ledger_id').map(function(){ return $(this).text() }).get();
            // var debit_ledger = 'Daily Expenses';
            var credit_ledger = 'Cash';
           	var total_amount = $('.expense_table tfoot tr').find("[data-value='total']").text();
           	var entry_id = $('.entry').attr('id');
           	var transcation_id = $('.entry').attr('data-id');
           	var expense_trans_id = $('input[name=amount]').map(function(){ return $(this).attr('id') }).get();
           	 var notes = $('input[name=description], textarea').map(function(){ return $(this).val() }).get();

           	 $.ajax({
			 url: "{{ route('expenses.update') }}",
			 type: 'post',
			 data: {		
			 _token: '{{ csrf_token() }}',
			 _method:'PATCH',
			 voucher_type: voucher_type,
			 date: date,
			 payment_mode: payment_mode,
			 reference_voucher_id: reference_voucher_id,
			 reference_voucher: name,
			 amount: amount,
			 employee: employee,
			 notes: notes,
			 debit_ledger: debit_ledger,
			 credit_ledger: credit_ledger,
			 total_amount: total_amount,
			 entry_id: entry_id,
			 transcation_id: transcation_id,
			 expense_trans_id: expense_trans_id,
			 notes: notes
			},
			 dataType: "json",
			success: function(data, textStatus, jqXHR) {
				if(data.status == 1){
				$('.loader_wall_onspot').hide();
				$('.alert-success').text('Dalily Expenses Updated for the date '+data.date);
				$('.alert-success').show();
				}
				 },
			 error: function(jqXHR, textStatus, errorThrown) {
				 
			 }
		  });
        });

    $('.show').on('click',function(){
    	var from_date = $('.from_date').val();
       	var to_date = $('.to_date').val();
       $.ajax({
			 url: "{{ route('expenses.report') }}",
			 type: 'post',
			 data: {		
			 _token: '{{ csrf_token() }}',
			from_date: from_date,
        	to_date : to_date, 
			},
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				if(data.status == 1){
				$('.report').css('display','block');
				$('.expense_report').find("[data-value='amount']").text(data.total_expense.total);
				}
				 var barChartData=JSON.parse(data.barchart);
						 google.charts.load('current', {'packages':['corechart']});
      	 				 google.charts.setOnLoadCallback(drawChart);
  					 		function drawChart() {
  					  	var data = google.visualization.arrayToDataTable(barChartData);
						var options = {
								       title: 'Petty Case Expenses',
								       // width: 700,
								        height: 300,
								       legend: 'none',
								       bar: {groupWidth: '45%'},
								       vAxis: { 
								             title :'Amount',
								              },
								        hAxis: { 
								        	 title :'Expenses',
								        	textPosition : 'out',
											slantedText: true,
											slantedTextAngle:60
								    		},
								     };
						var chart = new google.visualization.ColumnChart(document.getElementById('barchart'));
								  chart.draw(data, options);
      				}
				 },
			 error: function(jqXHR, textStatus, errorThrown) {
				 
			 }
		  });
    });
        
});
	</script> 
@stop