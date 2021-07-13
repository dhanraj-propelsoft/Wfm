@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.hrm')
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

<div class="fill header">
  <h4 class="float-left page-title">Attendance Report</h4>
  <br><br><br>
</div>
<div class="float-right">
<a href="{{ route('hrm_attendance.index') }}" ><button class="btn btn-success">Back</button></a>
</div>
<div class="row">
 	<label style="margin-top:8px;color:red;margin-left: 20px;">Month:</label>
  <div class="col-md-2">
		{{ Form::text('month', null, ['class' => 'form-control datepicker  month']) }}
  </div>
  </div>

<div class="float-left table_container" style="width: 100%;margin-top: 20px;">	
  	<table class="table data_table table-hover attedance_report" id="datatable" width="100%" cellspacing="0">
		<thead>
			 <tr>
	  	<th>Name of Employee </th>
		<th>Present</th>
		<th>Casual Leave</th>
		<th>Leave</th>
		<th>Sick Leave</th>
		<th data-toggle="tooltip" title="Local Holiday,National Holiday,Weekoff">Others</th>
		<th>Total</th>
		<th>Details</th>
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

   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};
  	$(document).ready(function() {	
  		 datatable = $('#datatable').DataTable(datatable_options);
  	$('[data-toggle="tooltip"]').tooltip(); 
	$('.datepicker').datepicker({
    		autoclose: true,
    		minViewMode: 1,
   			 format: 'MM'
}).on('changeDate', function(selected){
			var date = $(this).datepicker('getDate'),
            day  = date.getDate(),  
            month = date.getMonth() + 1,              
            year =  date.getFullYear();	
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('.to').datepicker('setStartDate', startDate);
       		var month= month;
        	var year=year;
		$.ajax({
                 url:"{{route('get_attedance_report')}}",                       
                 type: "POST",                
                 data:{ "_token": "{{ csrf_token() }}",
                  month :month,year:year},
                 success:function(data, textStatus, jqXHR)
                  {
                 	var attedance_report=data.result.attendance;
                  var month=data.result.month;
                  var year=data.result.year;
                 	$('#datatable tbody').empty();
                 	var attendance_report_table=``;
                 	 for(var i in attedance_report)
                          {  
                           attendance_report_table+= '<tr>											<td>'+attedance_report[i].first_name+'</td>					    <td>'+attedance_report[i].present+'</td>					    <td>'+attedance_report[i].casual_leave+'</td>				    <td>'+attedance_report[i].formal_leave+'</td>				    <td>'+attedance_report[i].sick_leave+'</td>					    <td>'+attedance_report[i].others_leave+'</td>				    <td>'+(parseInt(attedance_report[i].present)+parseInt(attedance_report[i].casual_leave)+parseInt(attedance_report[i].formal_leave)+parseInt(attedance_report[i].sick_leave)+parseInt(attedance_report[i].others_leave))+'</td>							<td><a data-id='+attedance_report[i].id+' data-month='+month+' data-year='+year+' class="view">View</a></td></tr>';
                     }  
                        call_back_optional(attendance_report_table,`add`,``);                         
				}
             });
     $('body').on('click', '.view', function(e){
       e.preventDefault(); 
       var id=$(this).data('id');
       var month=$(this).data('month');
       var year=$(this).data('year');
    $.get("{{ url('hrm/attedance_details_view') }}/"+id+"/"+month+"/"+year+"/view", function(data) {
       $('.crud_modal .modal-container').html("");
       $('.crud_modal .modal-container').html(data);
     });
    $('.crud_modal').find('.modal-dialog').addClass('modal-sm');
    $('.crud_modal').modal('show');
    });
 });
});
</script> 
@stop