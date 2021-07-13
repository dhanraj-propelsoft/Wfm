@extends('layouts.master')
@section('head_links') @parent
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}" />
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.print.min.css') }}" media='print' />
<style>
.batch_container {
	left: 65px;
	top: 120px;
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
  <h4 class="float-left page-title">
  Generate Payroll and Approve
  </h4>
</div>

<div class="clearfix"></div>

<div class="form-body ">

<div class="row">
<div class="col-md-3">
	<div class="form-group">
			{!! Form::label('salary_scale', 'Salary Scale', array('class' => 'control-label col-md-12 required')) !!}
			<div class="col-md-12">
				{{ Form::select('salary_scale', $salary_scale, null, ['class' => 'form-control select_item']) }}
			</div>

		</div>
</div>

<div class="col-md-3">
	<div class="form-group">
			{!! Form::label('date', 'Date', array('class' => 'control-label col-md-12 required')) !!}
			<div class="col-md-12">
				{{ Form::text('date', null, ['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) }}
			</div>

		</div>
</div>

<div class="col-md-4">
	<a class="btn btn-danger float-left search" style="color: #fff; margin-top: 22px;">Generate Payroll</a>
	<a class="btn btn-success back" style="color: #fff; margin-top: 22px;margin-left:50px">Back To Payroll List</a>
</div>
</div>

</div>
<div class="clearfix"></div>
<div class="float-left table_container" style="width: 100%; padding-top: 10px; margin-top: 20px;">
	<div class="batch_container">
	<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
	</div>
	<ul class="batch_list">
	  <li><a data-value="1" class="multiapprove">Approve</a></li>
	 
	</ul>
	</div>
  <p class="text" style="color: blue;margin-left: 20px;display: none;"><-Click Here to Approve the Payroll</p>
	<div id="datatable_wrapper">
  		<table id="datatable" class="table data_table" width="100%" cellspacing="0">
  		
  		</table>
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script> 
<script type="text/javascript">
	$(document).ready(function() {
		$('body').on('click', '.back', function() {
			window.location="payroll";
		});

		$('body').on('click', '.multiapprove', function() {
			var url = "{{ route('hrm_payroll.multiapprove') }}";
			active_status($(this), $(this).data('value'), url);
		});

		function active_status(obj, status, url) {

			var overtimes = [];
			var payment_methods = [];
			var employees = [];
			var frequencies = [];
			var ledgers = [];

			obj.closest(".table_container").find('tbody tr').each(function() {
				var overtime = $(this).find("td:first").find("input.item_check:checked").data('overtime');
				var payment_method = $(this).find("td:first").find("input.item_check:checked").data('payment_method');
				var frequency = $(this).find("td:first").find("input.item_check:checked").data('frequency');
				var employee = $(this).find("td:first").find("input.item_check:checked").attr('id');
				var ledger = $(this).find("td:first").find("input.item_check:checked").data('ledger');

				
				if(typeof(overtime) != "undefined") {
					overtimes.push(overtime);
				}

				if(typeof(payment_method) != "undefined") {
					payment_methods.push(payment_method);
				}

				if(typeof(employee) != "undefined") {
					employees.push(employee);
				}

				if(typeof(frequency) != "undefined") {
					frequencies.push(frequency);
				}

				if(typeof(ledger) != "undefined") {
					ledgers.push(ledger);
				}

			});

			$('.loader_wall').show();

			$.ajax({
					url: url,
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						date: $('input[name=date]').val(),
						over_time: overtimes.join(","),
						payment_method_id: payment_methods.join(","),
						salary_scale_id: $('select[name=salary_scale]').val(),
						employee_id: employees.join(","),
						frequency: frequencies.join(","),
						ledger_id: ledgers.join(",")
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						var list = data.data.list;
						for(var i in list) {
							if(status != "" || status !=null) {
								$("input.item_check[value="+list[i]+"]").closest('tr').find('span.status').removeClass('badge-warning');
								$("input.item_check[value="+list[i]+"]").closest('tr').find('span.status').addClass('badge-success');
								$("input.item_check[value="+list[i]+"]").closest('tr').find('span.status').text('Salary Generated');
								$("input.item_check[value="+list[i]+"]").remove();
							}
						}
						$(obj).closest('.batch_container').hide();
						$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
						$("input.item_check, input[name=check_all]").prop('checked', false);
						$('.loader_wall').hide();
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
		}

		$('.search').on('click', function() {

			var table = $('.data_table');
			
			var id = $('select[name=salary_scale]').val();
			var date = $('input[name=date]').val();

			if(id != "" && date != "") {
			table.html("");
			$.ajax({
				 url: "{{ route('get_salary_scale') }}",
				 type: 'post',
				 data: {
				  _token : '{{ csrf_token() }}',
				  id: id,
				  },
				 dataType: "json",
				  success:function(data, textStatus, jqXHR) {

				  	//console.log(data);

				  	if(data.length == 0) {
				  		return false;
				  	}

				  	table.append(`<thead><tr><th width="1"> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th><th>Employee</th></tr></thead>`);
				  	table.append(`<tbody></tbody>`);

				  	var table_head = $('.data_table').find('thead tr');
					var table_body = $('.data_table tbody');

				  	for (var i in data) {
				  		table_head.append('<th>'+data[i].name+'</th>');
				  	}
				  	table_head.append(`<th>OT Wage</th>`);
				  	table_head.append(`<th>LOP</th>`);
				  	table_head.append(`<th>Total Salary</th>`);
				  	table_head.append(`<th>Status</th>`);

				  	table_body.html(``);
					$.ajax({
					 url: "{{ route('get_employee_salary') }}",
					 type: 'post',
					 data: {
					  _token : '{{ csrf_token() }}',
					  id: id,
					  date: date
					  },
					 dataType: "json",
					  success:function(data, textStatus, jqXHR) {

					  	var html = ``;

					  	for (var j in data) {

				  			html += `<tr><td>`;

				  			if (data[j].status != 1) {
				  				html += `<input id="`+data[j].employee_id+`" data-overtime="`+data[j].overtime+`" data-payment_method="`+data[j].payment_method+`" data-frequency="`+data[j].frequency+`" data-ledger="`+data[j].ledger_id+`" value="`+data[j].employee_id+`"  class="item_check" name="payroll" type="checkbox">
                    		<label for="`+data[j].employee_id+`"><span></span></label>`;
                    		}

				  			html += `</td><td>`+data[j].employee+`</td>`;

				  			for (var k in data[j].salary) {
				  				html += `<td>`+data[j].salary[k]+`</td>`;
				  			}

				  			html += `<td>`+data[j].overtime+`</td>`;
				  			html += `<td>0.00</td>`;
				  			html += `<td>`+data[j].total+`</td>`;

				  			if (data[j].status == 0) {
				  				html += `<td><span class="grid_label badge badge-success status">Salary Generated</span></td>`;
				  			} else {
				  				html += `<td><span class="grid_label badge badge-warning status">Un Paid</span></td>`;
				  			}
				  			
				  			html += `</tr>`;
				  		}
					  	table_body.append(html);
					  	$('.text').show();

					  },
					 error:function(jqXHR, textStatus, errorThrown) {
					  }
					});



				  },
				 error:function(jqXHR, textStatus, errorThrown) {
				  }
				});
			}

			});
	});
</script> 
@stop