@extends('layouts.master')
@section('head_links') @parent
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}" />
<link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.print.min.css') }}" media='print'/>
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
  <h4 class="float-left page-title">Attendance</h4>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-sm-5">
  	<!-- <div style="float: right;margin-left: 10px;">
  		<button class="btn btn-block" id="refresh">Refresh</button>
  	</div> -->
	<div id="calendar">	
	</div>
	<div style="margin-top: 20px">
    	<a href="{{ route('attendance_report_view') }}"><button class="btn btn-success">Attendance Report</button></a>
    </div>
  </div>
  <div class="col-sm-7">
	<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	  <div class="batch_container" style="left: 45px; top: 35px;">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i> </div>
		<ul class="batch_list">
			@permission('attendance-delete')
			  <li><a class="multidelete">Delete</a></li>
			@endpermission
			<!-- @permission('attendance-edit')
			  <li><a class="multitime">Set Attendance</a></li>
			@endpermission -->
		</ul>
	  </div>
	  <!-- <div class="row">
	  		<div class="form-group col-md-3 offset-md-2"> {!! Form::label('attendance_type_id', 'Type', array('class' => 'control-label col-md-2 required')) !!}
	  		  <div class="col-md-12">
	  		  <select name="attendance_type_id" class="select_item">
	  		  	<option value="">Select Type</option>
	  		  	@foreach($attendance_types as $attendance_type)
	  				<option data-status="{{$attendance_type->attendance_status}}" value="{{$attendance_type->id}}">{{$attendance_type->name}}</option>
	  		  	@endforeach
	  		  </select>
	  		  </div>
	  		</div>
	  		<div class="form-group col-md-2 shift"> {!! Form::label('shift_id', 'Shift', array('class' => 'control-label col-md-2 required')) !!}
	  		  <div class="col-md-12">
	  		  <select name="shift_id" class="select_item">
	  		  	<option value="">Select Shift</option>
	  		  	@foreach($shifts as $shift)
	  				<option data-start="{{$shift->from_time}}" data-end="{{$shift->to_time}}" value="{{$shift->id}}">{{$shift->name}}</option>
	  		  	@endforeach
	  		  </select>
	  		  </div>
	  		</div>
	  		<div class="form-group col-md-2 in_time"> {!! Form::label('in_time', 'In', array('class' => 'control-label col-md-2 required')) !!}
	  		  <div class="col-md-12">{!! Form::text('in_time', null,['class' => 'form-control timepicker-no-seconds']) !!}</div>
	  		</div>
	  		<div class="form-group col-md-2 out_time"> {!! Form::label('out_time', 'Out', array('class' => 'control-label col-md-2 required')) !!}
	  		  <div class="col-md-12">{!! Form::text('out_time', null,['class' => 'form-control timepicker-no-seconds']) !!}</div>
	  		</div>
	  </div> -->
	  <div style="margin-top: -30px;">
	  	<p style="color: #2a0c7d;;"><b>STEP 1 :</b> Select the Date</p>
	  	<p style="color: #2a0c7d;;"><b>STEP 2:</b> Select the Employees</p>
	  </div>
	  <div style="overflow-y: scroll;max-height: 300px;height: 300px;border : 1px solid #3e4855;">
	

	  <table id="datatable" class="table data_table calendar_table" width="100%" cellspacing="0">
		<thead>
		  <tr>
			<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all']) }}
			  <label for="check_all"><span></span></label>
			</th>
			<th> Employee </th>
			<th> Date</th>
			<th> Shift </th>
			<th> In </th>
			<th> Out </th>
			<th> Status </th>
			<th> Action </th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>
	  </div>
	  <br>
	  <div>
	  	<p style="color: #2a0c7d;float: left;margin-right: 40%;"><b>STEP 3 :</b> Set the Attendance,Shift and Time</p>
	  	<p style="color: #2a0c7d;"><b>STEP 4 :</b> Click on Register Attendance</p>
	  </div>

	  <div class="row">
	  	<div class="form-group col-md-3">
	  		{!! Form::label('attendance_type_id', 'Type', array('class' => 'control-label col-md-2 required')) !!}
		 
			<select name="attendance_type_id" class="select_item">
			  	<!-- <option value="">Select Type</option> -->
		  	@foreach($attendance_types as $attendance_type)
				<option data-status="{{$attendance_type->attendance_status}}" value="{{$attendance_type->id}}">{{$attendance_type->name}}</option>
		  	@endforeach
			</select>
		 
	  	</div>
	  	<div class="form-group col-md-2 shift">
	  		{!! Form::label('shift_id', 'Shift', array('class' => 'control-label col-md-2 required')) !!}
		 
		   <select name="shift_id" class="select_item">
		  <!-- 	<option value="">Select Shift</option> -->
		  	@foreach($shifts as $shift)
				<option data-start="{{$shift->from_time}}" data-end="{{$shift->to_time}}" value="{{$shift->id}}">{{$shift->name}}</option>
		  	@endforeach
		   </select>
		  
	  	</div>
	  	<div class="form-group col-md-2 in_time">
	  		{!! Form::label('in_time', 'In', array('class' => 'control-label col-md-2 required')) !!}
		 	{!! Form::text('in_time', $in_out->from_time,['class' => 'form-control timepicker-no-seconds']) !!}
	  	</div>
	  	<div class="form-group col-md-2 out_time">
	  		{!! Form::label('out_time', 'Out', array('class' => 'control-label col-md-2 required')) !!}
		 	{!! Form::text('out_time', $in_out->to_time,['class' => 'form-control timepicker-no-seconds']) !!}
	  	</div>
	  	<div class="form-group col-md-2">
	  		  {!! Form::submit('Register attendance', ['class' => 'btn btn-success multitime','style' => 'height:40px;margin-top:20px;']) !!}
	  	</div>
	  	
	  
	  </div>


	 <!--  <div>
	 
	 		<div class="row">
	 		<div class="form-group col-md-3"> {!! Form::label('attendance_type_id', 'Type', array('class' => 'control-label col-md-2 required')) !!}
	 		  <div class="col-md-12">
	 		  <select name="attendance_type_id" class="select_item">
	 		  	<option value="">Select Type</option>
	 		  	@foreach($attendance_types as $attendance_type)
	 				<option data-status="{{$attendance_type->attendance_status}}" value="{{$attendance_type->id}}">{{$attendance_type->name}}</option>
	 		  	@endforeach
	 		  </select>
	 		  </div>
	 		</div>
	 		<div class="form-group col-md-3 shift"> {!! Form::label('shift_id', 'Shift', array('class' => 'control-label col-md-2 required')) !!}
	 		  <div class="col-md-12">
	 		  <select name="shift_id" class="select_item">
	 		  <option value="">Select Shift</option>
	 		  	@foreach($shifts as $shift)
	 				<option data-start="{{$shift->from_time}}" data-end="{{$shift->to_time}}" value="{{$shift->id}}">{{$shift->name}}</option>
	 		  	@endforeach
	 		  </select>
	 		  </div>
	 		</div>
	 		<div class="form-group col-md-2 in_time"> {!! Form::label('in_time', 'In', array('class' => 'control-label col-md-2 required')) !!}
	 		  <div class="col-md-12">{!! Form::text('in_time', $in_out->from_time,['class' => 'form-control timepicker-no-seconds']) !!}</div>
	 		</div>
	 		<div class="form-group col-md-2 out_time"> {!! Form::label('out_time', 'Out', array('class' => 'control-label col-md-2 required')) !!}
	 		  <div class="col-md-12">{!! Form::text('out_time', $in_out->to_time,['class' => 'form-control timepicker-no-seconds']) !!}</div>
	 
	 		</div>
	 		
	 		  {!! Form::submit('Register attendance', ['class' => 'btn btn-success multitime','style' => 'height:40px;margin-top:20px;']) !!}
	 		  
	 		
	 </div>
	 	</div> -->
	<div>
		<p style="color: #2a0c7d;;"><b>STEP 5:</b>Individually we can change the attendance in each lines,or delete.</p>
	</div>
	
	 <div>
	  @permission('attendance-create') 
	  <div>
	  	<p style="color: #2a0c7d;;float: left;"><b>STEP 6:</b> Any additional shift or timings for an employee Click here</p>
	  </div>
	  <div style="float: left;margin-left: 10px;">
	  	<a class="grid_label badge badge-danger float-right add" style="color: #fff">+ Add Attendance</a> 
	  </div>
	  @endpermission 
	</div>
	
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script> 





<script type="text/javascript">

	var current_date = '{{$current_date}}';

	function call_back(data, modal, message, id = null) {

		if ($('.edit[data-id="' + id + '"]')) {
			$('.edit[data-id="' + id + '"]').closest('tr').remove();
		}
		$('.data_table tbody').prepend(data);
		$('.crud_modal').modal('hide');
		$('.alert-success').text(message);
		$('.alert-success').show();

		setTimeout(function() {
			$('.alert').fadeOut();
		}, 3000);
	}

	$(document).ready(function() {

		sidebar_minimized();

		//$('input[name=in_time], input[name=out_time]').val("");

		var maxDate = new Date('{{$current_date}}');

$('#calendars').fullCalendar({
			dayClick: function(date, jsEvent, view) {
				update_attendance($(this), maxDate, date.format());
			},
			events: function(start, end, timezone, callback) {
				callback([{
				}]);
			},
			dayRender: function(date, cell) {
				if (date > maxDate) {
					$(cell).addClass('disabled');
				}
			},
			viewRender: function(view) {
				if (view.start > maxDate) {
					$('#calendar').fullCalendar('gotoDate', maxDate);
				}
			},
			loading: function(bool, view) {
				if (!bool) {
					update_attendance($(".fc-today"), maxDate, '{{$current_date}}');
				}
			}
		});


var mycal = $('#calendar').fullCalendar({

	eventRender: function (eventObj, $el) {
		
        $el.popover({
            title: "Total Employees Present / Total Employees in Company",
         /*   content: eventObj.title,*/
            trigger: 'hover',
            placement: 'top',
            container: 'body'
        });
    },
    eventLimit: true,
    eventColor: '#ccddff',
    events: {!! json_encode($present_employee) !!},

 
    dayClick: function(date, jsEvent, view) {
				update_attendance($(this), maxDate, date.format());
				
			},
			
			dayRender: function(date, cell) {
				if (date > maxDate) {
					$(cell).addClass('disabled');
				}
			},
			viewRender: function(view) {
				if (view.start > maxDate) {
					$('#calendar').fullCalendar('gotoDate', maxDate);
				}
			},
			loading: function(bool, view) {
				if (!bool) {
					update_attendance($(".fc-today"), maxDate, '{{$current_date}}');
				}
			}

});


$('#refreshss').on('click', function(){
	//alert();
  //$("#calendar").refetchEvents();
  //mycal.fullCalendar('refetchEvents');
	//$('#calendar').fullCalendar('render');
	//console.log($('#calendar').fullCalendar().length);
	$.ajax({
		url : '{{ route('get_present_details') }}',
		method : 'get',
		data :
		{

		},
		success:function(data)
		{
			//alert();
		},
		error:function()
		{

		}

	});

});
		$('body').on('change', 'input[name=check_all]', function(e) {
			if ($(this).is(":checked")) {

				$(this).closest('table').find('tbody tr').each(function() {
					if($(this).find('td:first div').is(":visible")) {
						$(this).find(':checkbox').prop('checked', true);
					} else {
						$(this).find(':checkbox').prop('checked', false);
					}
				});

				$(this).closest('.table_container').find('.batch_container').show();
				
			} else {

				$(this).closest('table').find('tbody tr').find('td:first :checkbox').prop('checked', false);
				$(this).closest('.table_container').find('.batch_container').hide();
			}
		});

		$('body').on('change', '.item_check', function(e) {
			if ($(".item_check:checked").length > 0) {
				$(this).closest('table').find('thead tr th:first :checkbox').prop('indeterminate', true);
				$(this).closest('.table_container').find('.batch_container').show();
			} else {
				$(this).closest('table').find('thead tr th:first :checkbox').prop('indeterminate', false);
				$(this).closest('.table_container').find('.batch_container').hide();
			}
		});

		//$('select[name=shift_id]').trigger('change');

		$('select[name=shift_id]').on('change', function() {
			if($(this).val() != "") {
				$('input[name=in_time]').val(time_convertion($(this).find('option:selected').data('start')));
				$('input[name=out_time]').val(time_convertion($(this).find('option:selected').data('end')));
			} else {
				$('input[name=in_time]').val("");
				$('input[name=out_time]').val("");
			}
		});

		$('select[name=attendance_type_id]').on('change', function() {
			if($(this).find('option:selected').data('status') != "1") {
				$('.shift, .in_time, .out_time').hide();
			} else {
				$('.shift, .in_time, .out_time').show();
			}
		});

		function update_attendance(obj, maxDate, date) {

			if (new Date(date) <= maxDate) {
				$(".fc-day").removeAttr('style');
				obj.css('background-color', '#e3f1f9');
				current_date = date;
				var id = obj.val();

				$.ajax({

					url: "{{ route('get_attendance_details') }}",
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						id: id,
						date: date,
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {

						var result = data.result.attendance;
						var attendance_type = data.result.attendance_types;
						//console.log(result);
						var html = "";						

						for (var i in result) {

							var shift_name = (result[i].shift_name != null) ? result[i].shift_name : "";
							var in_time = (result[i].in_time != null) ? result[i].in_time : "";
							var out_time = (result[i].out_time != null) ? result[i].out_time : "";

							html += `<tr> <td width="1"><div  style="`;

							if(result[i].employee_id == null) {
								html += `display:none;`;
							} 

							html += `" ><input id="` + result[i].employee_name + `" class="item_check" name="employee" value="` + result[i].id + `" type="checkbox"><label for="` + result[i].employee_name + `"><span></span></label></div></td>
								<td>` + result[i].employee_name + `</td>
								<td>`+current_date+`</td>
								<td>` + shift_name + `</td>
								<td>` + in_time + `</td>
								<td>` + out_time + `</td>
								<td>`;

							if(result[i].type_name != null) {
								html += `<label class="grid_label badge status" style='background:` + result[i].color + ` ; color: #fff;'>` + result[i].type_name + `</label>`;
							}	else {
								html += `<label class="grid_label badge status" style='background:grey ; color: #fff;'>Register Attendance</label>`;
							}

							html += `<select style="display:none" id="` + result[i].employee_id + `" class="active_status form-control">`;
									html += `<option value="">Select Status</option>`;
									for (var j in attendance_type) {
										html += `<option data-color="`+attendance_type[j].color+`" value="` + attendance_type[j].id + `">` + attendance_type[j].name + `</option>`;
									}
									html += `</select>
								</td>
								<td>`;
								
									/*html += `<a data-id="` + result[i].id + `" class="grid_label action-btn edit-icon edit" style="`; 
									if(result[i].id == "") {
									html += `display:none;`;
									 }
									html += `"><i class="fa li_pen"></i></a>`;*/
									html +=	`<a data-id="` + result[i].id + `" class="grid_label action-btn delete-icon delete" style="`;
									if(result[i].id == "") {
									html += `display:none;`;
									 } 
									html += `"><i class="fa fa-trash-o"></i></a>
											</td></tr>`;
						}
						$('.table').find('tbody').html(html);
					}
				});
			}
		}	

		$('.add').on('click', function(e) {
			e.preventDefault();
			$.get("{{ url('hrm/attendance/create') }}/" + current_date, function(data) {
				$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').modal('show');
		});

		$('body').on('click', '.edit', function(e) {
			e.preventDefault();
			$.get("{{ url('hrm/attendance') }}/" + $(this).data('id') + "/edit", function(data) {
				$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').modal('show');
		});

		$('body').on('click', '.status', function(e) {

			$(this).hide();
			$(this).parent().find('select').css('display', 'block');
		});

		$('body').on('click', '.multidelete', function() {
			var url = "{{ route('attendance.multidestroy') }}";
			multidelete($(this), url);
		});

		$('body').on('click', '.multitime', function() {
			var url = "{{ route('attendance.multitime') }}";
			var values = [];
			var employees = [];
			var obj = $(this);
			
			obj.closest(".table_container").find('tbody tr').each(function() {
			var value = $(this).find("td:first").find("input:checked").val();
			var employee = $(this).find("td:first").find("input:checked").closest('tr').find('.active_status').attr('id');

			if(employee != undefined && employee != null) {
				employees.push(employee);
			}
			
			if(value != undefined && value != null) {
				values.push(value);
			}
			});

			var shift_id = $('select[name=shift_id]');
			var attendance_type_id = $('select[name=attendance_type_id]');
			if($('select[name=attendance_type_id]').find('option:selected').data('status') == "1" && shift_id.val() == "") {
				return false;
			}
			else if(attendance_type_id.val() != "") {
			$.ajax({

				url: url,
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: (values.length > 1 ) ? values.join(",") : values[0],
					employee_id: (employees.length > 1 ) ? employees.join(",") : employees[0],
					shift_id:shift_id.val(),
					attendance_type_id:attendance_type_id.val(),
					in_time:$('input[name=in_time]').val(),
					out_time:$('input[name=out_time]').val(),
					attended_date:current_date,	
					status: $('select[name=attendance_type_id] option:selected').data('status')	
				},
				dataType: "json",
				success: function(data, textStatus, jqXHR) {

					var list = data.data.list;
					var date = data.data.date;
					var attendance_type_name = data.data.attendance_type_name;
					
					for(var i in list) {
						
						$('.calendar_table').find("select").each(function() {
							if($(this).attr('id') == list[i].employee_id) {

								var row = $(this).closest('tr');
								console.log(row.length);

								row.find("td").first().find(":checkbox").val(list[i].attendance_id);
								row.find("td:nth-child(4)").text(($('select[name=shift_id]').val() != "") ? $('select[name=shift_id] option:selected').text() : "");
								row.find("td:nth-child(5)").text($('input[name=in_time]').val());
								row.find("td:nth-child(6)").text($('input[name=out_time]').val());
								row.find("td:nth-child(7)").find('label').text($('select[name=attendance_type_id] option:selected').text());
								row.find("td:nth-child(7)").find('select').attr('id', list[i].employee_id);
								row.find("td:nth-child(7)").find('label').css("background", list[i].color);
								row.find("td").last().find('a').data('id', list[i].attendance_id);
								row.find("td").last().find('a').show();
								$('.calendar_table').find('tr').find(':checkbox:first').prop('checked', false);
								$('.calendar_table').find('tr').find(':checkbox:first').prop('indeterminate', false);
								obj.closest('.table_container').find('.batch_container').hide();
							}
						})						
					}


					$('.vehicle_search_modal_ajax').find('#content').text('');
					$('.vehicle_search_modal_ajax').modal('show');
					$('.vehicle_search_modal_ajax').find('#content').text("Employees had been registered as  "+attendance_type_name+"   "+date);
					$('.vehicle_search_modal_ajax').find('.yes_btn').css('display','none');

					$('.add_modal_ajax_btn').on('click',function(){
					$('.vehicle_search_modal_ajax').modal('hide');

					});
					/*mycal.setOption({ "title": data.data.present_employee, "start": date, "end": date 
   						 });*/

   				if(attendance_type_name == "Present")
   				{

				 $("#calendar").fullCalendar('renderEvent', { "title": data.data.present_employee, "start": date, "end": date 
   						 });
   				}

				}
			});
	
			}
		});


		$('body').on('change', '.active_status', function(e) {

			var attendance_type_id = $(this).val();
			var id = $(this).attr('id');
			var current = $(this);
			if(attendance_type_id != "") {
				$.ajax({
					url: "{{ route('attendance_update') }}",
					type: 'post',
					data: {
						_token: "{{ csrf_token() }}",
						employee_id: id,
						shift_id: $('select[name=shift_id]').val(),
						date: current_date,
						attendance_type_id: attendance_type_id,	
						status: $('select[name=attendance_type_id] option:selected').data('status')		
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {

						current.parent().find('label').css('background', data.data.color);
						current.hide();
						current.parent().find('label').show();
						current.parent().find('label').text(current.find('option:selected').text());
						current.closest('tr').find('td').last().find('a').show();
						current.closest('tr').find('td').last().find('a').attr('data-id', data.data.id);
						current.closest('tr').find('td').first().find('div').show();
						current.closest('tr').find('td').first().find(':checkbox').val(data.data.id);
					

						if(data.data.attendance_type == "Present")
		   				{
		   					
						 $("#calendar").fullCalendar('renderEvent', { "title": data.present_employee, "start": data.data.date, "end": data.data.date 
		   						 });
		   				}

					},
					error: function(jqXHR, textStatus, errorThrown) {
						//alert("New Request Failed " +textStatus);
					}
				});
			}
		});

		$(".checker span").removeClass('checked')
		$(':checkbox').prop('checked', false);

		$('body').on('click', '.delete', function() {
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = "{{ route('hrm_attendance.destroy') }}";
			delete_row(id, parent, delete_url);
		});

		function delete_row(id, row, delete_url) {
			$('.delete_modal_ajax').modal('show');
			$('.delete_modal_ajax_btn').off().on('click', function() {
				$.ajax({
					url: delete_url,
					type: 'post',
					data: {
						_method: 'delete',
						_token: '{{ csrf_token() }}',
						id: id
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						row.find("td").first().find(":checkbox").val("");
						row.find("td:nth-child(4)").text("");
						row.find("td:nth-child(5)").text("");
						row.find("td:nth-child(6)").text("");
						row.find("td:nth-child(7)").find('label').text("Register Attendance");
						row.find("td:nth-child(7)").find('label').css("background", "grey");
						row.find("td").last().find('a').hide();

						$('.delete_modal_ajax').modal('hide');
						$('.alert-success').text(data.message);
						$('.alert-success').show();

						setTimeout(function() {
							$('.alert').fadeOut();
						}, 3000);
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});
		}

		function multidelete(obj, url) {
			var values = [];
			obj.closest(".table_container").find('tbody tr').each(function() {
				var value = $(this).find("td:first").find("input:checked").val();
				if(value != undefined && value != null) {
				values.push(value);
			}
			});
			if(values.length > 0) {
			$('.delete_modal_ajax').modal('show');
			$('.delete_modal_ajax_btn').off().on('click', function() {
				$.ajax({
					url: url,
					type: 'post',
					data: {
						_method: 'delete',
						_token: '{{ csrf_token() }}',
						id: (values.length > 1 ) ? values.join(",") : values[0]
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						var list = data.data.list;
						var row;
						if(list.length > 0) {
							for(var i in list) {

								row = $("input.item_check[value="+list[i]+"]").closest('tr');

								row.find("td").first().find(":checkbox").val("");
								row.find("td:nth-child(4)").text("");
								row.find("td:nth-child(5)").text("");
								row.find("td:nth-child(6)").text("");
								row.find("td:nth-child(7)").find('label').text("Register Attendance");
								row.find("td:nth-child(7)").find('label').css("background", "grey");
								row.find("td").last().find('a').hide();
							}
						}
						else {
							row = $('.calendar_table tbody').find('tr');
							row.find("td").first().find(":checkbox").val("");
							row.find("td:nth-child(4)").text("");
							row.find("td:nth-child(5)").text("");
							row.find("td:nth-child(6)").text("");
							row.find("td:nth-child(7)").find('label').text("Register Attendance");
							row.find("td:nth-child(7)").find('label').css("background", "grey");
							row.find("td").last().find('a').hide();
						}
							
						$(obj).closest('.batch_container').hide();
						$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
						$("input.item_check, input[name=check_all]").prop('checked', false);
						$('.delete_modal_ajax').modal('hide');
					},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});
			}
		}
	});	

	function time_convertion (time) {
	  // Check correct time format and split into components
	  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

	  if (time.length > 1) { // If time format correct
	    time = time.slice (1);  // Remove full string match value
	    time[3] = "";
	    time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
	    time[0] = +time[0] % 12 || 12; // Adjust hours
	  }
	  return time.join (''); // return adjusted time or original string
	}

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			select_shift: {
				required: true
			},
		},

		messages: {
			select_shift: {
				required: "Shift Name is required."
			},
		},

		invalidHandler: function(event, validator) {
			//display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) { // hightlight error inputs
			$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},

		errorPlacement: function(error, element) {
			error.insertAfter(element.closest('.input-group'));
		},

		submitHandler: function(form) {
			form.submit(); // form validation success, call ajax form submit
		}
	});
</script> 
@stop