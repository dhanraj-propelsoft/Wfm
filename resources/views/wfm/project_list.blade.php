@extends('layouts.master_wfm_chart')
@include('includes.wfm_chart')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<style>
input[name=checkbox_all] {
  display: none;
}
input[type=checkbox] {
 display: inline-block;
 width: 19px;
 height: 19px;
 margin: -2px 10px 0 0;
 vertical-align: middle;
 background: url(../images/check_radio_sheet.png) left top no-repeat;
 cursor: pointer;
}
.select2-selection__rendered{
  color:#999 !important;
}
</style>
@stop
@section('content')


<div class="btn-group float-right" style="top:20px">
  <a class="btn btn-danger float-left new" style="color: #fff">New</a>
  <a class="btn btn-danger float-left edit Edit_project" style="color: #fff;">Edit</a>
  <a  class="btn btn-danger float-left summary" style="color: #fff">Summary</a>
  <a class="btn btn-danger float-left refresh" data-status="1" style="color: #fff">Refresh</a>
</div>


<div class="alert alert-success">

</div>


<div class="float-left" style="width: 100%; padding-top: 30px">
  <table id="datatable" class="table data_table" width="100%" cellspacing="0">
    <thead>
      <tr>
       <th> {{ Form::checkbox('checkbox_all', 'checkbox_all', null ) }} </th>
       <th>Project Code</th>
       <th>Project Name</th>
       <th>Project Details</th>
       <th>End Date</th>
       <th>Project Owner</th>
       <th>Total Tasks</th>
       <th>Open Tasks</th>
       <th>Project Status</th>
     </tr>
   </thead>
   @if(isset($projects))
   @foreach($projects as $project)
   <tbody>
    <td width="1">{{ Form::checkbox('project',$project->id, null, ['id' => $project->id, 'class' => 'item_check']) }}</td>
    <td>{{$project->project_code}}</td>
    <td>{{$project->project_name}}</td>
    <td>{{$project->project_details}}</td>
    <td>{{$project->deadline_date}}</td>
    <td>{{$project->project_owner}}</td>
    <td>{{$project->total_tasks}}</td>
    <td>{{$project->open_tasks}}</td>
    <td>{{project_status($project->project_status)}}</td>

  </tbody>
  @endforeach
  @endif
</table>
</div>




@stop
@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script>

  function removeAttachment(ele){
  //alert("test");
  hidden_attachment_id=$(ele).attr("data-id");
  hidden_attachment_type_id=$(ele).attr("data-type-id");
  hidden_attachment_mime_id=$(ele).attr("data-mime-id");
  //console.log($(ele).closest( "li" ).remove());
  $("#"+hidden_attachment_id).remove();
  $("#"+hidden_attachment_type_id).remove();
  $("#"+hidden_attachment_mime_id).remove();
}
function createInput(type,name,id,class1,value,element)
{
  var input=document.createElement('input');
  input.type=type;
  input.name=name;
  input.className=class1;
  input.id=id;
  input.value=value;
  $(element).before(input);
}
$(document).ready(function() {


 $('datatable').DataTable( {
  dom: 'lBfrtip',
  buttons: [
  {
    extend: 'pdfHtml5',
    orientation: 'landscape',
    pageSize: 'LEGAL'
  }
  ]
} );
//checkbox click event
$('body').on('change','input:checkbox',function(){
  var $inputs = $('input:checkbox')
  if($(this).is(':checked')){
    console.log("Unchedcked");
   $inputs.not(this).prop('checked',false);;
   console.log($inputs.not(this).length)
 }else{
    console.log("checked");
   $inputs.attr('checked',true);
 }
});
//datepicker
$('.date-picker').datepicker({
  todayHighlight: true
});

//add proejct modal

$('body').on('click','.new', function() {
 $.get("{{ route('project.create') }}", function(data) {

  $('.wfm_project_crud_modal .modal-container').html("");
  $('.wfm_project_crud_modal .modal-container').html(data);
  //$('.crud_modal .modal-container .modal-header h4').append("("+Buss_Name+")");
});
 $('.wfm_project_crud_modal').modal('show');
});




  //hide edit modal
  $('.edit').on('click',function(e){
    e.preventDefault();
    $('.project_actions').modal('hide');
  });

  $('.summary').attr("disabled",true);

//edit and update projects
// $('input[type="checkbox"]'). click(function(){
//  if($(this). prop("checked") == true){
//    var id=$(this).attr('id');
//    $('.edit').on('click',function(){
//                  // alert(id);
//                  //alert('{{url("wfm/dashboard/projectList/edit")}}/' + id);
//                  $('.project_actions').modal('show');
//                  $.ajax({
//                   url: '{{url("wfm/dashboard/projectList/edit")}}/' + id,
//                   type: 'get',
//                   data: {



//                   },
//                   success: function(data, textStatus, jqXHR) {

//                          //console.log(data.data.project_owner);
//                          console.log(data);
//                          $('input[name=project_name]').val(data.data.project_name)
//                          $('input[name=project_details]').val(data.data.project_details)
//                          $('input[name=end_date]').val(data.data.deadline_date)
//                          if(data.data.upload_file!=null){
//                            $("#attachment_datalist").html('<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable"><span class="tagit-label">'+data.data.upload_file+'</span><a class="tagit-close"><span class="text-icon" onclick="removeAttachment(this)" data-id="attachment_'+id+'" data-type-id="attachment_type_'+id+'" data-mime-id="attachment_mime_'+id+'">×</span><span class="ui-icon ui-icon-close"></span></a></li>');
//                          }
//                             //$('input[name=attachment]').val(data.data.upload_file);
//                             $('input[name=open_issues]').val(data.open_issues);
//                              //$('archive').val(data.open_issues),
//                              $('input[name=project_status]').val(data.project_status),
//                              $('select[name=project_owner]').val(data.project_owner);
//                              $('select[name=project_owner]').children('option').remove();
//                              $.each(data.employee_list, function(key, value) {
//                               $('select[name=project_owner]')
//                               .append($("<option></option>")
//                                 .attr("value",key)
//                                 .text(value));
//                             });

//                              $(".save").on('click',function(e)
//                              {
//                               e.preventDefault();
//                                // alert("Successfuy Updated");
//                                id=$('.item_check:checked').val();
//                               // return false;
//                                $.ajax({

//                                  url: '{{ url("wfm/dashboard/projectList/update") }}/' + id,
//                                  type:'post',
//                                  data: {
//                                   _token: '{{csrf_token()}}',
//                                   _method:'PATCH',
//                                   project_name: $('input[name=project_name]').val(),
//                                   project_details: $('input[name=project_details]').val(),
//                                   deadline_date: $('input[name=end_date]').val(),
//                                   project_owner: $('select[name=project_owner]').val(),
//                                   attachment:   $("#attachment_datalist").text(),
//                                   open_issues:  $('input[name=open_issues]').val(),
//                                   comments:$('input[name=project_comments]').val(),
//                                   project_status:  $('input[name=project_status]').attr('id'),
//                                 },
//                                 success: function(data, textStatus, jqXHR) {
//                                  console.log(data);
//                                  var my_row=$('input[id='+id+']').closest('tr');
//                                  $(my_row).find("td:eq(2)").text(data.data.project_name);
//                                  $(my_row).find("td:eq(3)").text(data.data.project_details);
//                                  $(my_row).find("td:eq(4)").text(data.data.deadline_date);
//                                  $(my_row).find("td:eq(5)").text(data.data.project_owner);

//                                }
//                              });


//          });

//                            }
//                          });

//                });
//  }
//  else if($(this). prop("checked") == false){
//   $('.project_actions').modal('hide');
// }
// });
//attachments
$('body').on('click', '.Edit_project', function (e) {

  id=$('input[name=project]:checked').val();

  if(id)
  {
          e.preventDefault();
              $.get('{{url("wfm/dashboard/projectList/edit")}}/' + id, function (data) {
                //	console.log(data);
                  $('.wfm_crud_modal .modal-container').html("");
                $('.wfm_crud_modal .modal-container').html(data);
                //	$('.wfm_crud_modal .modal-container .modal-header h4').append("("+Buss_Name+")");
            });
            $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
            $('.wfm_crud_modal').modal('show');
                  

  }



			});


$('input[type="checkbox"]'). click(function(){
 if($(this). prop("checked") == true){
   // alert();
   var id=$(this).attr('id');
   $('.summary').on('click',function(){
     $('.summary').removeAttr("disabled");
     url='{{ url("wfm/dashboard/summary") }}/' + id,
     window.location.href = url;

   });
 }
});

$("body").on('click','.archive',function(e)
{
 
  e.preventDefault();
  var get= $('input[name=open_issues]').val();
  if(get==0){
    $('.archive').attr("data-dismiss","modal"); 
  }
  else{
   alert("Still there are open issues,unable to close project")  
 }
 $('input[name=project_status]').val('Closed');
 $('input[name=project_status]').attr('id', $(this).val()); 
});
$("body").on('click','.disable',function(e)
{

  e.preventDefault();
  $('input[name=project_status]').val('Disabled');
  $('input[name=project_status]').attr('id', $(this).val());
});  
$("body").on('click','.enable',function(e)
{
  e.preventDefault();
  $('input[name=project_status]').val('Enabled');
  $('input[name=project_status]').attr('id', $(this).val());
}); 
});  


</script>
@stop
