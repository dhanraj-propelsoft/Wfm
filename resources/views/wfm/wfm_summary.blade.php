@extends('layouts.master_wfm_chart')
@include('includes.wfm_chart')
@section('head_links') @parent
<style>
.dropdown-menu{
  min-width: 11rem !important;
}
</style>
@stop
@section('content')
  <!-- <div class="dropdown btn-group float-left">
    <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" style="color: #fff">select Project
    <span class="caret"></span></button>
    @if(isset($project_lists))
 @foreach($project_lists as $project_list)
    <ul class="dropdown-menu">
      <li id="{{$project_list->id}}" class="submenu">{{$project_list->project_name}}</li>
    </ul>
     @endforeach
     @endif
  </div>  -->

<div class="btn-group float-right"  style="top:10px;">
<!-- <a class="btn btn-danger float-left new" style="color: #fff">New</a> -->
<a class="btn btn-danger float-left refresh" data-status="1" style="color: #fff">Refresh</a>
<a class="btn btn-danger float-left " onclick="goBack()" data-status="1" style="color: #fff">Back</a>
</div>
@if(isset($projects))
<div class="float-left" style="width: 100%; padding-top: 30px">
<label><b>Project Name:</b></label>
<!-- <input type="text" name="project_name" value="{{$projects->project_name}}"  readonly style="width:17%;border: 1px solid #ddd;"> -->
<select id="ddlProduct" style="width:120px;height:25px;">
  <option>Select Project</option>
  <option <?php if(request()->route('id')=="All"){echo "selected";}  ?>>All</option>
  @if(isset($project_lists))
  @foreach($project_lists as $project_list)
    <option value="{{$project_list->id}}" <?php if(request()->route('id')==$project_list->id){echo "selected";}  ?>>{{$project_list->project_name}}</option>
     @endforeach
 @endif
     </select>
<label><b>Project Owner:</b></label>
<input type="text" name="project_owner" value="{{$projects->first_name}}"  readonly style="width:14%;border: 1px solid #ddd;">
<label><b>Project End Date:</b></label>
<input type="text" name="end_date" value="{{date_($projects->deadline_date)}}"  readonly style="width:10%;border: 1px solid #ddd;">
<label><b>Total Tasks:</b></label>
<input type="text" name="total_tasks" value="{{$projects->total_tasks}}"  readonly style="width:5%;border: 1px solid #ddd;">
 @endif
 @if(isset($open_tasks))
<label><b>Open Tasks</b></label>
<input type="text" name="project_name" value="{{$open_tasks->open_tasks}}" placeholder="Open Tasks" readonly style="width:5%;border: 1px solid #ddd;">
@endif
      <div class="row">
 <div class="col-xs-6 col-md-6" id="piechart"></div>
<div class="col-xs-6 col-md-6" id="chart_div"></div>
</div>
</div>
    
@stop
@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
 function drawChart () {
    var data = google.visualization.arrayToDataTable({!! $barchart_values !!});
      var data1 = google.visualization.arrayToDataTable({!! $data !!});
       var options2= {'title':'Issues By Members', 'width':550, 'height':400};   
       var options= {'title':'Issues By Status', 'width':500, 'height':400};     
   var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
    var chart2 = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
  chart2.draw(data1,options2)
    google.visualization.events.addListener(chart2, 'click', function (e) {
        var match = e.targetID.match(/hAxis#\d+#label#(\d+)/);
        if (match) {
            var rowIndex = parseInt(match[1]);
            var axisLabel = data.getValue(rowIndex, 0);
        }
    });
    google.visualization.events.addListener(chart, 'click', function (e) {
        var match = e.targetID.match(/hAxis#\d+#label#(\d+)/);
        if (match) {
            var rowIndex = parseInt(match[1]);
            var axisLabel = data.getValue(rowIndex, 0);
        }
    });
    
        chart.draw(data, options);
        chart2.draw(data1, options2);

}
function goBack() {
    window.history.back();
}
$('#ddlProduct').on('change',function(){
  var id=$("#ddlProduct").val();
 // alert(id);
   url='{{ url("wfm/dashboard/summary") }}/' + id,
                       window.location.href = url;
                     
});
</script>


@stop