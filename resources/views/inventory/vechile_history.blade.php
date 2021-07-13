<div class="modal-header">
	<h5 class="modal-title float-right"><b>Vechile History</b></h5>
	
</div>
<div class="modal-body">
	<table id="datatable"  class="table data_table table-hover" width="100%" cellspacing="0" >
		<thead>
			<th>Job Card</th> 
			<th>JobDate</th>
			<th>ItemName</th>
			<th>Phone</th>
			<th>Complaints</th>
			<th>Item Status</th>
		</thead>
		<tbody>
		@foreach($reports as $report)
			<tr>
				<td>{{$report->order_no}}</td>
				<td>{{$report->job_date}}</td>
				<td>{{$report->name}}</td>
				<td>{{$report->mobile_no}}</td>
				<td data-toggle="tooltip" data-placement="top" title="{{ $report->vehicle_complaints }}">{{str_limit(strip_tags($report->vehicle_complaints),20,'...')}}</td>
				
				<td>@if($report->job_item_status == '1')

            <label class="grid_label badge" style="background-color: #ff9933">Open</label>

	          @elseif($report->job_item_status == '2')

	            <label class="grid_label badge" style="background-color: #33cc33">Closed</label>

	          @elseif($report->job_item_status == '3')

	            <label class="grid_label badge" style="background-color: #ff3300">On Hold</label>

	          @elseif($report->job_item_status == '4')

	            <label class="grid_label badge" style="background-color: #FFFF00">Progress</label>

	          @endif</td>
	         
			</tr>
		@endforeach	
			
		</tbody>
	</table>
</div>
<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script>
	var datatable = null;
   	var datatable_options = {"order": [[1, "asc"]], "stateSave": true};
   	datatable = $('#datatable').DataTable(datatable_options);
</script>