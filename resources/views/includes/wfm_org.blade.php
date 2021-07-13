
@section('sidebar')
<style type="text/css">
body{
    background-color: #fafcfe;
}
html,body,table,p
{
  font-size:90%;

}

.md-input-container {
    border: 1px solid  #495057;
    position: relative;
    width: 85%;
    border-radius: 20px;
    padding: 0;
    height: 22px;
    margin: 2px 0 0;
}
input::placeholder  {
    color: #495057;
}
.md-input-container input[type=text]
{

 -webkit-appearance: none;
}
.md-input-container:not(.md-input-has-value) input:not(:focus)
{
  color: transparent;

}
.md-input-container.md-block {
    display: block;
    margin: 0 auto;
}

.md-input {
    border: 0;
    color: rgba(255,255,255,.62);
    font-size: 11px;
    margin: 0;
    height: auto;
    line-height: 19px;
    font-style: italic;
    padding: 0 15px;
}
.md-input {
    -webkit-box-ordinal-group: 3;
    -webkit-order: 2;
    order: 2;
    display: block;
    margin-top: 0;
    background: none;
    padding: 2px 2px 1px;
    border-width: 0 0 1px;
    line-height: 26px;
    height: 20px;
    -ms-flex-preferred-size: 26px;
    border-radius: 0;
    border-style: solid;
    width: 100%;
    box-sizing: border-box;
    float: left;
}
.md-input::placeholder
{

    color: rgba(255,255,255,.62);
}
.md-input-container:after {
    content: '';
    display: table;
    clear: both;
}
.md-button {
    position: absolute;
    right: 6px;
    top: 3px;
    width: 14px;
}

.md-icon {
    font-size: 13px;
    color: #959595;
}
.md-button md-icon, .md-button:not([disabled]) md-icon
{
 width: auto;
 height: auto;
 min-height: 0;
 min-width: 0;
 line-height: normal;
}
/*span.blocktext
{

      margin-left: auto;
    margin-right: auto;
    padding: 0 10% 0 10%;
    text-align: justify;
    float: left;
    width:30%;
}*/
.center-table th {
    text-align: center;
    vertical-align: middle;
}

.center-table td {
    text-align: center;
    vertical-align: middle;
}
.md-icon {
    margin: auto;
    background-repeat: no-repeat;
    display: inline-block;
    vertical-align: middle;
    fill: currentColor;
    height: 24px;
    width: 24px;
    min-height: 24px;
    min-width: 24px;
}
.md-button {
    position: absolute;
    right: 6px;
    top: 3px;
    width: 14px;
}
.md-button, .md-button:not([disabled])
{
 min-height: 0;
 min-width: 0;
 background: 0 0;
 padding: 0;
 margin: 0;
 width: auto;
 height: auto;
 line-height: normal;
}
.md-button {
 border:0px;
 letter-spacing: .01em;
 cursor:pointer;
}
.text-overlap
{
  white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    float: left
}
p,table,.dcolor{
    color:#919191;
}
select {
   /* border: 1px solid #fff;*/
    background-color: rgba(255,255,255,.5);
    padding: 5px;
}

select option{
    background-color: transparent !important;
    border: 1px solid #e4e4e4;
    color: #000;
    -webkit-appearance: none; 
    -moz-appearance: none; 
}
.md-block1 {
    padding: 0;
    border: 1px solid #e8e8e8;
    border-radius: 2px;
    margin: 0 0 10px;
    overflow: hidden;
    }

.md-block1.slctddn label:not(.md-no-float):not(.md-container-ignore) {
    position: relative;
    vertical-align: top;
    z-index: 10;
    pointer-events: auto;
}
 .md-block1:not(.txtareaDV) label:not(.md-no-float):not(.md-container-ignore) md-icon {
    color: inherit;
    font-size: 16px;
    margin: 0;
    width: auto;
    height: auto;
    vertical-align: text-top;
    min-width: 23px;
}

/*internet explorer scrollbalken*/
body{
  scrollbar-base-color: #C0C0C0;
  scrollbar-base-color: #C0C0C0;
  scrollbar-3dlight-color: #C0C0C0;
  scrollbar-highlight-color: #C0C0C0;
  scrollbar-track-color: #EBEBEB;
  scrollbar-arrow-color: black;
  scrollbar-shadow-color: #C0C0C0;
  scrollbar-dark-shadow-color: #C0C0C0;
}
/*mozilla scrolbalken*/
@-moz-document url-prefix(http://),url-prefix(https://) {
scrollbar {
   -moz-appearance: none !important;
   background: rgb(0,255,0) !important;
}
thumb,scrollbarbutton {
   -moz-appearance: none !important;
   background-color: rgb(0,0,255) !important;
}

thumb:hover,scrollbarbutton:hover {
   -moz-appearance: none !important;
   background-color: rgb(255,0,0) !important;
}

scrollbarbutton {
   display: none !important;
}

scrollbar[orient="vertical"] {
  min-width: 15px !important;
}
}
/*Scrollbar css*/
::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #eee;
}
::-webkit-scrollbar {
    width: 7px;
    background-color: #F5F5F5;
    padding-right: 2px;
}
::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #f2f2f2;
}
::-webkit-scrollbar-thumb:hover {
    background-color: #b7b7b7;
}
button.button
{
      background: #ffc490;
      border:  #ffc490;
}
.btn-secondary:hover
{
      background: #ffab60;
      border:  #ffc490;
}
.btn-secondary-round
{
  border:2px solid   #3e4855;
     
}

/*Scrollbar css*/
 /*
#sidebar-menu {
    line-height:4em;
    position: relative;
}*/
   /*  //   box-shadow: 0 0 6px #ccc;*/

#sidebar-menu::after {
    content: ' ';
    width: 2px;
    height: 100%;
    background-color: #ccc;
    display: block;
    position: absolute;
    top: 0;
    right: 0;
}
span.count
{
  height: 15px;
  width: 25px;
  color: #fff;
  background: #ffab60;
  border-radius: 50%;
  margin: 0 0 0 14%;
  text-align: center;
  font-size: 11px;
  cursor:pointer;
}
/*#page-sidebar #sidebar-menu li a:hover span.count
{
    background-color: #ffab60;
}
#page-sidebar #sidebar-menu li a:hover
{
    color:#ffab60;
}*/
/*#page-sidebar #sidebar-menu li a.selected span.count
{
    background-color: #ffab60;
}
#page-sidebar #sidebar-menu li a.selected
{
    color:#ffab60;
}*/
.table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>th {
    color: #f5f8fb;
    background-color: #999;
    }
    .progress{
        background: #eee;
    padding: 1px 3px 3px;
    font-size: 12px;
    color: #888;
    border-radius: 0;
    }

element.style {
}
*, ::after, ::before {
    box-sizing: border-box;
}
user agent stylesheet
div {
    display: block;
} 
.popover {
    border-top:3px solid !important;
    border-top-color: #ffab60 !important;
    }
.popover.bs-popover-auto[x-placement^=bottom] .arrow::before, .popover.bs-popover-bottom .arrow::before {
    top: -.8rem;
    border-bottom-color: #ffab60 !important;
}
.popover.bs-popover-auto[x-placement^=bottom] .arrow::after, .popover.bs-popover-auto[x-placement^=bottom] .arrow::before, .popover.bs-popover-bottom .arrow::after, .popover.bs-popover-bottom .arrow::before {
    margin-left: 3.5rem;
    }
.popover.bs-popover-auto[x-placement^=bottom], .popover.bs-popover-bottom {
    margin-top: .8rem;
    left: -48px !important;
  /* width: 28%;*/
    height: 30%;
}
.popover.popover-footer {
  margin: 0;
  padding: 8px 14px;
  font-size: 14px;
  font-weight: 400;
  line-height: 18px;
  background-color: #F7F7F7;
  border-top: 1px solid #EBEBEB;
}
.fa-pie-chart .dispaly {
  display: block;
  font-size: 11px;
  padding-right: 1.5px;
      
}
.fa-folder-open .display {
  display: block;
  font-size: 11px;
  padding-left:1.5px;
      
}
.round {
    width: 115%;
    border-radius: 20px;
    border: 1px #0000004a solid;
    padding: 5px 5px 5px 25px;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 5;
    height: 25px;
    left:10px;
}

.search {
    position: relative;
    width: 160px;
    height: 30px;
}


</style>


@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))


    <li>
    <div class="sidebar-submenu" style="display: block;width:74% !important" >
        <div  class="">
          <!--   <select class="md-input-container md-block md-input-has-placeholder select_item select2-hidden-accessible" id="input_2" style="background-color: none">
                <option value="">Company</option>
                <option value="1">Mani Traders</option>
                <option value="2">Kumaran Traders</option>
            </select> -->
            {!! Form::select('organization_id', $organizations,Session::get('organization_id'),['class' => 'md-input-container md-block md-input-has-placeholder select_item select2-hidden-accessible getOrgData','id'=>'input_2 organization_id']) !!}
            
        </div>
		  <!-- <ul>
			  	
		  		<li><a data-link="job-allocation" href="#"><span>Project1</span></a></li>
		  		<li><a data-link="job-allocation" href="#"><span>Project2</span></a></li>
		  		<li><a data-link="job-allocation" href="#"><span>Project3</span></a></li>
           </ul> -->
       </div>

    </li>
    <!--    <li style="poistion:relative;" class="Position"><a class="sub-menu"><i class="fa fa-list-alt"></i><span>{{GetLabelName(Session::get('organization_id'),'Project List')}}</span></a> -->
      <div class="sidebar-submenu" style="display: block;overflow-y: scroll;height:150px">
			 
        <?php /* ?>{!! Form::select('project_list',$projects,null, array('class' => 'form-control md-input-container md-block md-input-has-placeholder   select_item select2-hidden-accessible','id' => 'project_category_list','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select")) !!} <?php */ ?>
         <div class="search">
     <!--  <input type="text" name="search" class="round" placeholder="Search Projects.." /> -->
    {!! Form::select('project_list',$projects,null, array('class' => 'form-control md-input-container md-block md-input-has-placeholder   select_item select2-hidden-accessible round sidebar-select ','id' => 'project_category_list','style'=>'width:50%;height:29px','placeholder'=>"Select Project"),$projects_tasks_link) !!} @permission('wfm-add-project-menu')
    <a class="Add_project"  ><span class="pull-right" style="position: absolute;right: -40px;top: 0px;font-size: 20px;"><i class="fa fa-plus-circle " data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Add project')}}"></i></span></a>@endpermission
  
         </div>
      <a class="Add_project"  ><span class="pull-right" style="position: absolute;right: -10px;top: -35px;color: #868e96;font-size: 20px;"><i class="fa fa-plus-circle " data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Add project')}}"></i></span></a>
     <ul class="project_list" style="padding: 5px">
        @if(isset($ProjectList) && count($ProjectList)>0)
         @foreach($ProjectList as $project)
            
      
         

        <li style="display: inline-flex;width: 100%;"><a data-link="job-allocation" class="<?php ?> getproject project_{{$project->project_id}}" id="project_{{$project->project_id}}" data-id="{{$project->project_id}}" data-href="{{ url('wfm/dashboard') }}/{{$project->organization_id}}/{{$project->project_id}}"  data-org-id="{{Session::get('organization_id')}}"><div style="width:203px"><span class="pull-left text-overlap" style="width:140px" data-toggle='tooltip' title="{{$project->project_name}}">{{$project->project_name}}</span><span class="count project_popup_{{$project->project_id}} pull-right" data-html="true"   data-id="projectcount_{{$project->project_id}} " class=""  data-popup-id="project_popup_{{$project->project_id}}" style="border-top:#ffab60;"> {{$project->COUNT}} </span>
            </div></a>
            </li>

        @endforeach
        @endif
 

  
            </li>
            </ul>
    </div>


        <!-- <li><a class="" ><span style="margin-left: 16%;"><i class="fa fa-tasks"></i><span>New Project</span><span class="pull-right"><i class="fa fa-plus-circle "></i><span></a></li> -->
         <!--    <li class="TaskUser"><a class="" ><span style="margin-left: 16%;"><i class="fa fa-users"></i>{{GetLabelName(Session::get('organization_id'),'User')}}</span></a>
             </li> -->

 
       <div class="sidebar-submenu" style="display: block;overflow-y: scroll;height:150px;margin: 9% 0 0 0;">
        

      <div class="" style="width: 77%;"><span >{!! Form::select('employee_id',$EmployeeList,null, array('class' => 'form-control md-input-container md-block md-input-has-placeholder   select_item select2-hidden-accessible round sidebar-select getTaskByO_U','id' => 'employee_id','style'=>'width:50%;height:29px','placeholder'=>"Select User",'data-id'=>Session::get('organization_id'))) !!} <span></div>  
     
      <ul style="padding: 5px" class="org_user_list">
     @if(isset($TaskUser) && count($TaskUser)>0)
       @foreach($TaskUser as $user)
        
       <li class=" getTaskByO_U task_user_{{$user->employee_id}}"  data-id="{{$user->organization_id}}" data-u-id="{{$user->employee_id}}" style="height:20px"><a class=" " ><div style="padding: 0 0 0 2px;width:140px" class="text-overlap org_user_{{$user->employee_id}}">{{$user->first_name}}</div><span class="count" style="float: right;position: relative;top: 6px; right: 4px;"><span class="task_user_count" style="text-align: center"> <?php echo $user->taskcount; ?> </span></span></a></li>

       @endforeach       
      @endif
           <!--    <li>   <div  class="md-input-container md-block md-input-has-placeholder">
            <input type="text" placeholder=" search users" ng-model="searchProject.name" class="ng-pristine ng-valid md-input ng-empty ng-touched" aria-label="search projects" id="input_2" aria-invalid="false"><div class="md-errors-spacer"></div>
            <button class="md-button md-ink-ripple" type="button" ng-transclude="">
                <i class="fa fa-search" aria-hidden="true" style="color:#495057;"></i>
                <div class="md-ripple-container" style=""></div></button>
            </div></li> -->
</ul>
</div>
           
         <!--  <input type="text" name="search" class="round" placeholder="Search Projects.." /> -->
        <!-- {!! Form::text('search_task',null, array('class' => 'form-control md-input-container md-block md-input-has-placeholder  round','id' => 'search_task','style'=>'width:90%;color:#999;height:29px','placeholder'=>"Go to Task")) !!} 
        {!! Form::text('search_task',null, array('class' => 'ng-pristine ng-valid md-input ng-empty ng-touched','id' => 'search_task','style'=>'width:90%;color:#999;height:29px','placeholder'=>"Go to Task")) !!}
         <i class="fa fa-search" style="
        font-size: 16px;right: -20px;z-index: 50px;z-index: 999;position:absolute;left: 90;top:0px;padding-top: 3px;color: #17a2b8;cursor: pointer;" id="TaskFilter"  data-org-id="{{Session::get('organization_id')}}"></i> -->
     
  
         
        <hr class="hr-danger" style="width:100%;width: 118%;position: relative;left: -21px;
    margin: 30px 0 30px 0;" />
       <?php  /*?>   @permission('wfm-project-list-menu')
          <li><a href="{{ route('wfm.project_list') }}">Project list</a></li>
          @endpermission <?php */ ?>

          @permission('wfm-chart-view-menu')
         <li ><a href="{{ url('wfm/dashboard/summary') }}/All">Chart View</a></li>
         @endpermission

          @permission('wfm-manage-projects-menu')
         <li><a href="{{ route('wfm.project_list') }}">Manage Projects</a></li>
         @endpermission
          @permission('wfm-master-dataset-menu')
          <li><a href="{{ url('wfm/wfm_settings') }}">Master Dataset</a></li>
          @endpermission
          <?php //print_r($latest_projects);exit; ?>

            @endif
            @endif
            @stop

            @section('dom_links')
          
            @parent


<script>
$(document).ready(function(){   
     $('[data-toggle="popover"]').popover(); 

});
$('.count').on('click',function(){
    var id = $(this).attr('data-popup-id'); 
      //$(this).not($("#"+id)).popover('hide'); 
      $('[data-toggle="popover"]').not(this).popover('hide');
})
var isVisible = false;
var clickedAway = false;

$('.chart').on('click',function(){
   //alert();
 /*
 url='{{ url("wfm/dashboard/summary") }}/' +,
                       window.location.href = url;
*/});
/*$('.popoverThis').popover({
    html: true,
    trigger: 'manual'
}).click(function (e) {
    $(this).popover('show');
    clickedAway = false
    isVisible = true
    e.preventDefault()
});*/

/*$(document).click(function (e) {
    if (isVisible & clickedAway) {
        $('.popoverThis').popover('hide')
        isVisible = clickedAway = false
    } else {
        clickedAway = true
    }
});*/
</script>
            @stop

            