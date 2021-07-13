	<style>
		.groove {border-style: groove;}
	</style>
	<div class="modal-header">
       <h5 class="modal-title float-right">Employee Attendance</h5>
    </div>
    <div class="modal-body">
    	<div >
    		<h5 class="groove">Name:{{$employee_name}}</h5>
    	</div>
    	<div style="height:400px;overflow-y:auto;margin-top: 20px;">
    	<table class="table table-bordered">
    		 @foreach($attedance_view as $attedance_view)
    		<tr>
    			<td>{{$attedance_view->attended_date}}</td>
    			<td>{{$attedance_view->name}}</td>
    		</tr>
    		 @endforeach
        </table>
        </div>
        <div class="modal-footer" style="height: 30px;margin-top:20px;">
    	<button type="button" class="btn btn-success float-right" data-dismiss="modal">Close</button>
</div>
    </div>