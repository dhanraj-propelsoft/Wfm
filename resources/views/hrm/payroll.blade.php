@extends('layouts.master')
@section('head_links') @parent
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}" />
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.print.min.css') }}" media='print' />
<style>
.batch_container {
	left: 65px;
	top: 60px;
}.dataTables_filter{
	margin-left: 300px;
}
#datatable_paginate{
	margin-left:350px;
}
.datatable_length{
	width: 80px;
}
.datepicker-months{
	background-color: #9ac0db;
}

</style>
@stop
@include('includes.hrm')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Payroll/Salary List</h4>
  <br><br>
</div> 
  <div class="col-md-12 " style="">
  <div class="row">
 <label style="margin-top:8px;color:red">Select a Payroll Month:</label>
  <div class="col-md-2" >
		{{ Form::text('salary_date', null, ['class' => 'form-control datepicker  salary_date']) }}
  </div>
    <div class="col-md-8" >
    	<select class="form-group type_status" style="height:30px;width:100px">
    		<option value="">Select Status</option>
    		<option value="1">Paid</option>
    		<option value="2">Un-Paid</option>
    		<option value="3">All</option>
    	</select>
    	<!-- <input type="text" name="" class="form-control"> -->
    <button style=" height:30px; border-radius: 5.5px" type="submit" class="date btn btn-success filter_data"><i class="fa fa-search" style="margin: 2px"></i>view Payroll/Salary List</button>
    	@permission('department-create')
		<a class="btn btn-danger add" href="{{ route('generate_payroll') }}" style="color: #fff;height:30px;margin-left: 10px;">+ Generate New Payroll/Salary</a>	
 		@endpermission
    </div>
    <div class="col-md-4" >
       
  	 <!-- <a class="btn btn-danger float-right refresh" style="color: #fff;height:30px">Refresh</a>  --> 

</div>
</div>
</div>


<div class="float-left table_container" style="width: 100%; margin-top:60px;">
	<div class="batch_container" style="margin-top:30px">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			<li><a class="multidelete">Delete the full Batch</a></li>
			<li><a data-value="1" data-mode="employee" class="multipayment">Make Payment  Employee Wise</a></li>
			<li style="display:none"><a data-value="1" data-mode="batch"class="multipayment">Make Payment  Batch Wise</a></li>
		</ul>
	</div>
	<p style="margin-bottom:-70px;margin-left:70px;color: blue;"><-Click Here to Pay or delete Payroll/Salary</p>
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	@permission('payroll-approval')
	  		<th>{{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} 
	  			<label for="check_all"><span></span></label></th>
	  	@endpermission
	  	<th>BATCH</th>
	  	<th>Employee</th>
		<th> Date </th> 
		<th> Salary Scale </th>
		<th> Payment Method </th>
		<th> Gross Salary</th>
		<th> Status </th>
		<th> Action </th> 
	  </tr>
	</thead>
	<tbody>
		@foreach($salaries as $salary)
		<tr>
			@permission('payroll-approval')
				<td width="1">{{ Form::checkbox('id',$salary->id, null, ['id' => $salary->id, 'class' => 'item_check']) }}<label for="{{$salary->id}}"><span></span></label></td>
			@endpermission
			 <td>BATCH-{{$salary->batch}}</td>
			 <td>{{$salary->employee}}</td>
		  <td class="rearrangedatetext">{{ $salary->salary_date }}</td>

		  <td>{{ $salary->salary_scale }}</td>
		  <td>{{ $salary->payment_method }}</td>
		  <td>{{ $salary->gross_salary }}</td>
		  <td>
			@if($salary->status == '1')
			  <label class="grid_label badge badge-success">Paid</label>
			@elseif($salary->status == '0')
			  <label class="grid_label badge badge-warning">Un-Paid</label>
			@endif

			<!-- @permission('salary-edit')
			  <select style="display:none" id="{{ $salary->id }}" class="active_status form-control">
				<option @if($salary->status == 1) selected="selected" @endif value="1">Active</option>
				<option @if($salary->status == 0) selected="selected" @endif value="0">In-Active</option>
			  </select>
			@endpermission -->
		  </td>
		  <td>
		   <a data-id="{{ $salary->id }}" class="print_icon action-btn print-icon payslip"><i class="fa icon-basic-printer"></i></a>  
		  		
		  			  <a data-id="{{ $salary->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
		  		
		  			<!-- @permission('salary-delete')
		  			  <a data-id="{{ $salary->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 
		  			@endpermission --> 
		  					  </td> 
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
<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript">
	var datatable = null;

   	var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};
	 
	$(document).ready(function() {
		var startDate = new Date();
		$('.datepicker').datepicker({
    autoclose: true,
    minViewMode: 1,
    format: 'MM'
}).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('.to').datepicker('setStartDate', startDate);
    }); 

		datatable = $('#datatable').DataTable(datatable_options);

		$('body').on('click', '.filter_data', function(e) {
  
  			var html='';

			var salary_date= $('.salary_date').val();
			var type_status=$('.type_status').val();

			$.ajax({
				url : '{{ route('hrm_salary_status') }}',
				type: 'POST',
				data:
					{
						_token: '{{ csrf_token() }}',
						salary_date : salary_date ,
						type_status : type_status,
				
				},
				success:function(data, textStatus, jqXHR) {
					var payroll=data.data;
				

				
             		$('#datatable tbody').empty();
				
					if( data.status == 1)
					{
						for(var i in payroll)
						{
							var label="";
							if(payroll[i].status==1){
								label='<label class="grid_label badge badge-success">Paid</label>'
							}	
							else{
								label='<label class="grid_label badge badge-warning">Un-Paid</label>';

							}
					
							html+=`<tr>
							<td><input id="`+payroll[i].id+`" class="item_check" name="batch" value="`+payroll[i].id+`" type="checkbox">
							<label for="`+payroll[i].id+`"><span></span></label></td>
							<td>BATCH-`+payroll[i].batch+`</td>
							<td>`+payroll[i].employee+`</td>
	        				<td>`+payroll[i].salary_date+`</td>
	        				<td >`+payroll[i].salary_scale+`</td>
	        				<td>`+payroll[i].payment_method+`</td>
	        				<td>`+payroll[i].gross_salary+`</td>        			
	        				<td>`+label+`</td> 
	        				<td> <a data-id="`+payroll[i].id+`" class="print_icon action-btn print-icon payslip"><i class="fa icon-basic-printer"></i></a> </td>		
	      			 	
	    					</tr>`;
	    					}
	  		 				//$('tbody').html(html);
							// call_back_on(html);
							call_back_optional(html,`add`,``);

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
		

		$('body').on('click', '.multidelete', function() {
			var url = "{{ route('hrm_payroll.multidelete') }}";
			var pay_ids = [];

			$(".table_container").find('tbody tr').each(function() {
				var id = $(this).find("td:first").find("input.item_check:checked").val();

				if(typeof(id) != "undefined") {
					pay_ids.push(id);
				}

			});

			$('.delete_modal_ajax').modal('show');
			$('.delete_modal_ajax_btn').off().on('click', function() {
				$.ajax({
					url: url,
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						pay_ids: pay_ids.join(",")
					},
					dataType: "json",
					beforeSend: function() {
						$('.loader_wall').show();
					},
					success: function(data, textStatus, jqXHR) {
						var list = data.data.list;
						for(var i in list) {
							$("input.item_check[value="+list[i]+"]").closest('tr').remove();
						}
						$('.batch_container').hide();
						$('.data_table').find('thead tr th:first :checkbox').prop('indeterminate', false);
						$("input.item_check, input[name=check_all]").prop('checked', false);
						$('.loader_wall').hide();
						$('.delete_modal_ajax').modal('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});
		});

		$('body').on('click', '.multipayment', function() {
			var url = "{{ route('hrm_payroll.multipayment') }}";
			var pay_ids = [];
			var status = $(this).data('value');
			var gen_mode = $(this).data('mode');


			$(".table_container").find('tbody tr').each(function() {
				var id = $(this).find("td:first").find("input.item_check:checked").val();

				if(typeof(id) != "undefined") {
					pay_ids.push(id);
				}

			});
			// console.log(pay_ids);
			// return false;

			$.ajax({
				url: url,
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: pay_ids.join(","),
					status: status,
					gen_mode: gen_mode
				},
				dataType: "json",
				beforeSend: function() {
					$('.loader_wall').show();
				},
				success: function(data, textStatus, jqXHR) {
					var list = data.data.list;
					
					for(var i in list) {
						if(status == 1) {

							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.grid_label').removeClass('badge-warning');
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.grid_label').addClass('badge-success');
							$("input.item_check[value="+list[i]+"]").closest('tr').find('.grid_label').text('Paid');
							$("input.item_check[value="+list[i]+"]").remove();
						}
					}
					$('.batch_container').hide();
					$('.data_table').find('thead tr th:first :checkbox').prop('indeterminate', false);
					$("input.item_check, input[name=check_all]").prop('checked', false);
					
					$('.loader_wall').hide();
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});

		});
		
		$('body').on('click', '.payslip', function(e) {
			e.preventDefault(); 
			var id = $(this).data('id');
			$('.loader_wall_onspot').show();
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('payslip') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id
					},
					success:function(data, textStatus, jqXHR) {

						
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
						container.html("");

						if(container.html(data.salary_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='employee']").text(data.employee_name);
							container.find("[data-value='employee_id']").text(data.employee_code);
							container.find("[data-value='designation']").text(data.employee_designation);
							container.find("[data-value='department']").text(data.employee_designation);
							container.find("[data-value='net_pay']").text(data.employee_designation);
							container.find("[data-value='net_pay_words']").text(data.employee_designation);
							container.find("[data-value='date']").text(data.employee_designation);
							container.find("[data-value='total_earnings']").text(data.earning_total);
							container.find("[data-value='total_deductions']").text(data.deduction_total);
							container.find("[data-value='gross_total']").text(data.gross_total);
							container.find("[data-value='net_total']").text(data.net_total);
							container.find("[data-value='gross_amount_words']").text(data.gross_amount_words);
							container.find("[data-value='net_pay_words']").text(data.net_amount_words);
							container.find("[data-value='salary_month_year']").text(data.salary_month_year);

							var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

							var row = container.find('.item_table tbody tr').clone();

							var earnings_html = ``;
							var deductions_html = ``;

							if((data.earnings).length > 0) {
								for (var i = 0; i < (data.earnings).length; i++) {
									var new_row = row.clone();

									new_row.find('td').first().text(data.earnings[i].pay_head);
									new_row.find('td').next().text(data.earnings[i].value);

									earnings_html += `<tr>`+new_row.html()+`</tr>`;
								}

								if((data.earnings).length < (data.deductions).length) {
									var num = (data.deductions).length - (data.earnings).length;
									for(var j = 0; j < num; j++) {
										earnings_html += `<tr><td style="padding:5px; text-align:left;">&nbsp;&nbsp;&nbsp;</td><td style="padding:5px; text-align:right;"></td></tr>`;
									}
									
								}

							} else {
								container.find('.earnings tbody').hide();
							}	
							

							container.find('.earnings tbody').empty();
							container.find('.earnings tbody').append(earnings_html);


								for (var i = 0; i < (data.deductions).length; i++) {
									var new_row = row.clone();

									new_row.find('td').first().text(data.deductions[i].pay_head);
									new_row.find('td').next().text(data.deductions[i].value);

									deductions_html += `<tr>`+new_row.html()+`</tr>`;
								}

								if((data.earnings).length > (data.deductions).length) {
									var num = (data.earnings).length - (data.deductions).length;
									for(var j = 0; j < num; j++) {
										deductions_html += `<tr><td style="padding:5px; text-align:left;">&nbsp;&nbsp;&nbsp;</td><td style="padding:5px; text-align:right;"></td></tr>`;
									}

								}	
							

							container.find('.deductions tbody').empty();
							container.find('.deductions tbody').append(deductions_html);

							$(".earnings tr:even, .deductions tr:even").css("background-color", row_color);	


								var divToPrint=document.getElementById('print');
	  						var newWin=window.open('','Propel');


	  						newWin.document.open();
	  						newWin.document.write(`<html>
	  							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	  							<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
	  							<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
								<script> 

								window.onload=function() { window.print(); }

								$(document).ready(function() {
			


									$('body').on('click', '.print', function() {
									//printDiv();
									});



							}); </scr`+`ipt>


							 </body></html>`);

	  						
	  						newWin.document.close();

	  						$('.print_content #print').removeAttr('style');
							$('.print_content #print').html("");
							$('.print_content').removeAttr('style');
							$('.print_content .modal-footer').hide();
							$('.print_content').animate({top: '0px'}); 
							$('body').css('overflow', '');			
							
						}

						$('.loader_wall_onspot').hide();
					}
				});
		
			});
				
		});

	});	

</script> 
@stop