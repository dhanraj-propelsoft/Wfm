@extends('layouts.master_wfm')
@include('includes.wfm1')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
@stop
@section('content')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
<style>
.md-input-container input::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #212529;
  opacity: 1; /* Firefox */
}
.md-input
{
  color:#212529 !important;
  border-width:0 
}

.ficona-filetype-rar:before {
  content: "\e93c";
}
.ficona-filetype-zip:before {
  content: "\e93d";
}
.ficona-filetype-bmp:before {
  content: "\e901";
}
.ficona-filetype-gif:before {
  content: "\e931";
}
.ficona-filetype-html:before {
  content: "\e92a";
}
.ficona-filetype-pdf:before {
  content: "\e902";
}
.ficona-filetype-png:before {
  content: "\e92c";
}
.ficona-filetype-psd:before {
  content: "\e92d";
}
.ficona-filetype-txt:before {
  content: "\e926";
}
.ficona-filetype-jpg:before {
  content: "\e927";
}
.ficona-filetype-ppt:before {
  content: "\e90b";
}
.ficona-filetype-doc:before {
  content: "\e90c";
}
.ficona-filetype-xls:before {
  content: "\e903";
}
.ficona-filetype-jpg {
    color: #e59c1e;
}
 #comment_attachment_datalist [class*=ficona-filetype] {
    font-size: 16px;
    vertical-align: text-bottom;
    position: relative;
    top: 1px;
    margin: 0;
    margin-right: 3px;
}
hr {
  height: 1px;
  margin-left: 15px;
  margin-bottom:-3px;
}
.hr-danger{
  background-image: -webkit-linear-gradient(left, #e9ecef, #e9ecef, #e9ecef);
}
.dropzone .dz-preview .dz-progress .dz-upload { background: #32A336;  }
.dz-filename{

  background: #e9ecef !important;
  padding: 4px !important;
  border-radius: 100px !important;
}
.dz-success-mark,.dz-error-mark
{
  display: none !important;
}
.task_editcontent {
  -moz-appearance: textfield-multiline;
  -webkit-appearance: textarea;
  border: 1px solid gray;
  font: medium -moz-fixed;
  font: -webkit-small-control;
  height: 28px;
  overflow: auto;
  padding: 2px;
  resize: both;
  width: 400px;
}
.select2-container--default .select2-selection--single
{
  background-color: transparent !important;
  border: 1px solid #e4e4e4;
  color: #000;
  -webkit-appearance: none; 
  -moz-appearance: none;
}
[contenteditable]:focus {
  outline: 1px solid #ced4da;
  border:0px;
}
[contenteditable]:not(:focus){
  /*border: 1px solid #ced4da;*/
}
.example-1 {
  position: relative;
  overflow-y: scroll;
  overflow-x: hidden;
  height: 250px;
}
#fountainG{
  position:relative;
  width:84px;
  height:10px;
  margin:auto;
}

.fountainG{
  position:absolute;
  top:0;
  background-color:rgb(0,0,0);
  width:10px;
  height:10px;
  animation-name:bounce_fountainG;
  -o-animation-name:bounce_fountainG;
  -ms-animation-name:bounce_fountainG;
  -webkit-animation-name:bounce_fountainG;
  -moz-animation-name:bounce_fountainG;
  animation-duration:1.5s;
  -o-animation-duration:1.5s;
  -ms-animation-duration:1.5s;
  -webkit-animation-duration:1.5s;
  -moz-animation-duration:1.5s;
  animation-iteration-count:infinite;
  -o-animation-iteration-count:infinite;
  -ms-animation-iteration-count:infinite;
  -webkit-animation-iteration-count:infinite;
  -moz-animation-iteration-count:infinite;
  animation-direction:normal;
  -o-animation-direction:normal;
  -ms-animation-direction:normal;
  -webkit-animation-direction:normal;
  -moz-animation-direction:normal;
  transform:scale(.3);
  -o-transform:scale(.3);
  -ms-transform:scale(.3);
  -webkit-transform:scale(.3);
  -moz-transform:scale(.3);
  border-radius:7px;
  -o-border-radius:7px;
  -ms-border-radius:7px;
  -webkit-border-radius:7px;
  -moz-border-radius:7px;
}

#fountainG_1{
  left:0;
  animation-delay:0.6s;
  -o-animation-delay:0.6s;
  -ms-animation-delay:0.6s;
  -webkit-animation-delay:0.6s;
  -moz-animation-delay:0.6s;
}

#fountainG_2{
  left:10px;
  animation-delay:0.75s;
  -o-animation-delay:0.75s;
  -ms-animation-delay:0.75s;
  -webkit-animation-delay:0.75s;
  -moz-animation-delay:0.75s;
}

#fountainG_3{
  left:21px;
  animation-delay:0.9s;
  -o-animation-delay:0.9s;
  -ms-animation-delay:0.9s;
  -webkit-animation-delay:0.9s;
  -moz-animation-delay:0.9s;
}

#fountainG_4{
  left:31px;
  animation-delay:1.05s;
  -o-animation-delay:1.05s;
  -ms-animation-delay:1.05s;
  -webkit-animation-delay:1.05s;
  -moz-animation-delay:1.05s;
}

#fountainG_5{
  left:42px;
  animation-delay:1.2s;
  -o-animation-delay:1.2s;
  -ms-animation-delay:1.2s;
  -webkit-animation-delay:1.2s;
  -moz-animation-delay:1.2s;
}

#fountainG_6{
  left:52px;
  animation-delay:1.35s;
  -o-animation-delay:1.35s;
  -ms-animation-delay:1.35s;
  -webkit-animation-delay:1.35s;
  -moz-animation-delay:1.35s;
}

#fountainG_7{
  left:63px;
  animation-delay:1.5s;
  -o-animation-delay:1.5s;
  -ms-animation-delay:1.5s;
  -webkit-animation-delay:1.5s;
  -moz-animation-delay:1.5s;
}

#fountainG_8{
  left:73px;
  animation-delay:1.64s;
  -o-animation-delay:1.64s;
  -ms-animation-delay:1.64s;
  -webkit-animation-delay:1.64s;
  -moz-animation-delay:1.64s;
}



@keyframes bounce_fountainG{
  0%{
    transform:scale(1);
    background-color:rgb(0,0,0);
  }

  100%{
    transform:scale(.3);
    background-color:rgb(255,255,255);
  }
}

@-o-keyframes bounce_fountainG{
  0%{
    -o-transform:scale(1);
    background-color:rgb(0,0,0);
  }

  100%{
    -o-transform:scale(.3);
    background-color:rgb(255,255,255);
  }
}

@-ms-keyframes bounce_fountainG{
  0%{
    -ms-transform:scale(1);
    background-color:rgb(0,0,0);
  }

  100%{
    -ms-transform:scale(.3);
    background-color:rgb(255,255,255);
  }
}

@-webkit-keyframes bounce_fountainG{
  0%{
    -webkit-transform:scale(1);
    background-color:rgb(0,0,0);
  }

  100%{
    -webkit-transform:scale(.3);
    background-color:rgb(255,255,255);
  }
}

@-moz-keyframes bounce_fountainG{
  0%{
    -moz-transform:scale(1);
    background-color:rgb(0,0,0);
  }

  100%{
    -moz-transform:scale(.3);
    background-color:rgb(255,255,255);
  }
}
.blur_background
{
  -webkit-filter: blur(1px);
  -moz-filter: blur(1px);
  -o-filter: blur(1px);
  -ms-filter: blur(1px);
  filter: blur(25px);
}

/**/

span.multiselect-native-select {
  position: relative
}
span.multiselect-native-select select {
  border: 0!important;
  clip: rect(0 0 0 0)!important;
  height: 1px!important;
  margin: -1px -1px -1px -3px!important;
  overflow: hidden!important;
  padding: 0!important;
  position: absolute!important;
  width: 1px!important;
  left: 50%;
  top: 30px
}
.multiselect-container {
  position: absolute;
  list-style-type: none;
  margin: 0;
  padding: 0
}
.multiselect-container .input-group {
  margin: 5px
}
.multiselect-container>li {
  padding: 0
}
.multiselect-container>li>a.multiselect-all label {
  font-weight: 700
}
.multiselect-container>li.multiselect-group label {
  margin: 0;
  padding: 3px 20px 3px 20px;
  height: 100%;
  font-weight: 700
}
.multiselect-container>li.multiselect-group-clickable label {
  cursor: pointer
}
.multiselect-container>li>a {
  padding: 0
}
.multiselect-container>li>a>label {
  margin: 0;
  height: 100%;
  cursor: pointer;
  font-weight: 400;
  padding: 3px 0 3px 30px
}
.multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
  margin: 0
}
.multiselect-container>li>a>label>input[type=checkbox] {
  margin-bottom: 5px
}
.btn-group>.btn-group:nth-child(2)>.multiselect.btn {
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px
}
.form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
  padding: 3px 20px 3px 40px
}
.form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
  margin-left: -20px;
  margin-right: 0
}
.task_edit_btn {
  background: #ffab60;
  border: 1px solid #ffab60;
  text-transform: uppercase;
  font-weight: 600;
  font-size: 12px;
  color: white;
  padding: 2px 10px;
  margin:2px 0;
  border-radius: 3px;
  cursor: pointer;
}

/*Multi Select*/


/*Multi Select*/
</style>

      <div class="alert alert-success">
      </div>
      <div class="alert alert-danger label_errror" style="display: none">
        <ul class="error_li">

        </ul>
      </div>

      @if($errors->any())
      <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
      </div>
      @endif

    <div class="fill header">
    
       <p class="float-left page-title" style="font-style: italic;"></p>
     
     </div>
     <div class="float-left table_container" style="width: 100%; padding-top: 10px;">
      <div class="batch_container">
        <div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
        </div>
        <ul class="batch_list">
          <li><a class="multidelete">Delete</a></li>
          <li><a data-value="1" class="multiapprove">Make Active</a></li>
          <li><a data-value="0" class="multiapprove">Make In-Active</a></li>
          <input type="hidden" name="data-token" value="{{$password_secrets}}">
        </ul>
      </div>
      <div class="row">
        <!--START Loader  11.12.2018-->
                <div id="fountainG" class="table_container_loader" style="position: absolute;
                top: 50%;
                right: 50%;
                z-index: 999;display: none">
                <div id="fountainG_1" class="fountainG"></div>
                <div id="fountainG_2" class="fountainG"></div>
                <div id="fountainG_3" class="fountainG"></div>
                <div id="fountainG_4" class="fountainG"></div>
                <div id="fountainG_5" class="fountainG"></div>
                <div id="fountainG_6" class="fountainG"></div>
                <div id="fountainG_7" class="fountainG"></div>
                <div id="fountainG_8" class="fountainG"></div>
              </div>
      <!--START Loader  11.12.2018-->
              <div class="col-md-12" style="overflow-x: auto" id="table_container">
               <?php //echo  Request::segment(1).Request::segment(2); ?>
                         <table id="datatable" class="table data_table tableContent" width="100%" cellspacing="0">
                          <thead>
                            <tr>

                              <th style=""></th>
                              <!-- <th><span class="blocktext"> Code </span></th> -->
                              <th><span class="blocktext"> Name</span></th>
                              <th>Poject </th>
                              <th>Assigned to</th>
                              <th>Due Date</th>
                              <th>Status</th>
                            </tr>
                          </thead>

                          <tbody>

                       
                          @forelse( $CurrentUserTasks as $project_task)
                          @if($project_task->task_name)

                          <tr class="popUp get_detailsbar" href="#0" class="" data-panel="main" data-id="{{$project_task->task_id}}" data-org-id="{{$project_task->organization_id}}" id="task_id_{{$project_task->task_id}}" data-pro-id="{{$project_task->project_id}}"  data-activity-log="/org_{{$project_task->organization_id}}/pro_{{$project_task->project_id}}/task_{{$project_task->task_id}}" data-action-id="{{$project_task->status}}" data-token=''>

                                <td data-sort="{{$project_task->priority_id}}"><?php echo priority($project_task->priority_id); ?></td>


                                <!-- <td>{{$project_task->task_code}}</td> -->
                                <td>{{$project_task->task_name}}</td>
                                <td>{{$project_task->project_name}}</td>

                                <td>{{$project_task->first_name  }}</td>
                                <td>{{date_($project_task->end_date)  }}</td>

                                <td id="TaskStatus_{{$project_task->task_id}}">{{GetTaskStatus($project_task->task_status)}}</td>



                          </tr>
                          @endif
                          @empty
                          @endforelse

                          </tbody>




                        </table>

                  </div>

        </div>

    </div>
<!-- Panel -->

<!-- cd-panel -->



  <div class="col-lg-7 col-md-6 col-sm-4  DetailsBar" style="position: absolute;right:0;top:0;background-color: #fafcfe;display: none;border-left: 3px  #eee;z-index: 9; height:100%;top:-60px" id="Toggle_screen"> 
        <!-- loader TASK DETAILS -->
    <div id="fountainG" style="position: absolute;top: 50%;right: 50%;
      z-index: 999;display: none" class="task_details_loader">
      <div id="fountainG_1" class="fountainG"></div>
      <div id="fountainG_2" class="fountainG"></div>
      <div id="fountainG_3" class="fountainG"></div>
      <div id="fountainG_4" class="fountainG"></div>
      <div id="fountainG_5" class="fountainG"></div>
      <div id="fountainG_6" class="fountainG"></div>
      <div id="fountainG_7" class="fountainG"></div>
      <div id="fountainG_8" class="fountainG"></div>
    </div>
      <!-- loader -->
        <div class="row blur_background" style="background-color:#e9ecef; box-shadow:5px 10px 14px 9px;" id="DetailsContent">

            <div style="width:100%" >

              <div class="box_title pull-left" style="margin: 5px 0 0 0;height: 31px"><span>Task Information</span>
              </div>
              <div class="pull-right  " style="margin:5px 0 0 auto;">
                <button class="task_edit_btn Editor-close edit-mode" style="background-color: white;color:#495057;display: none">Cancel</button>      
                <i class="fa fa-expand" style=" font-weight: bold; margin: 5px 15px 5px 0;  border-radius: 5px;cursor: pointer;"  id="toggle_taskDetails"></i>
                <i class="fa fa-close close_event"  title="close" style=" font-weight: bold; margin: 5px 15px 5px 0;  border-radius: 5px;cursor: pointer;" id="" ></i>
              </div>
            </div>

            <div class="col-lg-12 col-md-8 col-sm-6 Task" style="background: #fafcfe;padding: 3%">
                              
              <div class="row" style="padding-bottom: 2%">
                <div class="col-lg-2 col-md-3 col-sm-3 " style="padding-right: 0">
                      <span style="font-weight: bold;">Task</span>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 task_name_div" style="padding-left: 0;min-height: 10px;max-height: 200px" contenteditable="false" >

                    <span class="task_name edit_task_name"></span>
                  
                    <span class="update_task_name" style="cursor: pointer;display: none">
                      <input type="text" name="task_name"></span>       
                </div>

                <div class="col-lg-1 col-md-1 col-sm-1 task_name_div" style="padding-right: 0" >
                      <i class="fa fa-check task_name_submit"  style=" font-weight: bold; margin: 5px 15px 5px 0;  border-radius: 5px;cursor: pointer;color: green;display: none;" ></i>
                      <i class="fa fa-close task_name_revert"  title="close" style=" font-weight: bold; margin: 5px 15px 5px 0;  border-radius: 5px;cursor: pointer;color: red;display: none;"></i>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 " style="padding-right: 0">
                  <span style="font-weight: bold;">Prority</span>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 priority_div" style="padding-left: 0;min-height: 10px;max-height: 200px" contenteditable="false">
                  <span class="edit_priority">
                        <?php echo priority(3) ?>
                  </span>
                  <span class="update_priority" style="cursor: pointer;display: none">
                    {!! Form::select('priority', $Priority, null, ['class' => 'form-control editable-input select','data-url'=>URL::to('/api/wfm/update_taskdetails')]); !!}             
                  </span>       
                </div>                   
              </div> 

              <div class="row EditableContent" style="padding-bottom: 2%">
                <div class="col-lg-2 col-md-3 col-sm-3 " style="padding-right: 0">
                      <span style="font-weight: bold;">Task details</span>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 task_details_div" style="padding-left: 0;min-height: 10px;max-height: 200px" contenteditable="false" id="EditableDiv">
                  <span class="task_descript edit_task_details">
                  </span>
                  <span class="update_task_details" style="cursor: pointer;display: none">
                    <textarea class="form-control editable-input task_descript" name="task_details" data-input-name="task_details" data-url="{{URL::to('/api/wfm/update_task')}}"></textarea>
                  </span>       
                </div>
                
                <div class="col-lg-1 col-md-1 col-sm-1 task_details_div" style="padding-right: 0">
                  <i class="fa fa-check task_details_submit"  style=" font-weight: bold; margin: 5px 15px 5px 0;  border-radius: 5px;cursor: pointer;color: green;display: none;" ></i>
                  <i class="fa fa-close task_details_revert"  title="close" style=" font-weight: bold; margin: 5px 15px 5px 0;  border-radius: 5px;cursor: pointer;color: red;display: none;"></i>
                </div>

                <div class="col-lg-2 col-md-3 col-sm-3" style="padding-right: 0">
                  <span style="font-weight: bold;">Project</span>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 project_div" style="padding-left: 0;min-height: 10px;max-height: 200px" contenteditable="false" >
                  <span class="task_project edit_project"></span>
                  <span class="update_project" style="cursor: pointer;display: none">
                      {!! Form::select('project', $projects, null, ['class' => 'form-control']); !!}
                  </span>       
                </div>

              </div>

              <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3" style="font-weight: bold;">
                        Start Date
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4  task_start_date" style="padding-left:0;margin:0;min-width: 190px" >
                </div> 
                <div class="col-lg-2 col-md-3 col-sm-3" style="font-weight: bold;">
                      End Date
                </div>

                <div class="col-lg-2 col-md-3 col-sm-3  end_date_div" style="margin:0;min-width: 190px;padding-left: 0">
                    <div class="form-group pull-left EditableContent">

                    <span class="pull-left task_end_date EditableText" ></span>
                                          
                    <span class="Edit_textarea update_end_date" style="cursor: pointer;display: none">

                      <input class="date-picker editable-input" type="text" name="end-date" data-date-format="yyyy-mm-dd">

                    </span>
                    </div>


                                          </div>
                              </div>
                              <div class="row">
                                      <div class="col-lg-2 col-md-3 col-sm-3 " style="">
                                        <b>Created By:</b>
                                      </div>
                                      
                                      <div class="col-lg-4 col-md-4 col-sm-4 task_created_by" style="margin:0;min-width: 190px;padding-left: 0" >
                                        
                                      </div> 
                                      
                                      <div class="col-lg-2 col-md-3 col-sm-3 " style="padding-right: 0"><b>Assigned To:</b></div>

                                     <div class="col-lg-3 col-md-3 col-sm-3 assignedto_div" style="margin:0;padding-left: 0">
                                        <span class="pull-left assigned_to EditableText" ></span>
                                        <span class="Edit_textarea update_assignedto" style="cursor: pointer;display: none">
                                          {!! Form::select('assigned_to', $EmployeeList, null, ['class' => 'form-control']); !!}
                                      
                                       </span>
                                    


                                   </div>
                           </div>

                          <div class="row" >
                           
                            <div class="col-lg-2 col-md-3 col-sm-3 "><b>Action:</b></div>

                            <?php $return_fields=['action_progress_id'=>'progress']; ?>

                            <?php $input_fields=['action_progress_id'=>'progress']; ?>


                              <div class="col-lg-4 col-md-4 col-sm-4 box_title " style="margin:0;min-width: 190px" >

                               <div style="min-height: 35px;display: flex" class="progress_data pull-left" > 

                                {!! Form::select('action', ['0'=>'select'], null, ['class' => 'form-control', 'style' => 'width: 100%','id'=>'action_progress_id','data-function'=>'getupdateprogress']); !!}

                                <button type="button" class="progress btn UpdateProgress"  style="min-height: 29px;vertical-align: middle;min-width: 29px; text-align: center;margin-left: 1px;padding: 5px;"><i class="fa fa-check" style="text-align: center; padding: 0 0 0 3px;color: green" data-inputs=''></i></button>

                               </div>
                             </div>
                           
                            <div class="col-lg-2 col-md-3 col-sm-3 " style="padding-right: 0"><b>Status:</b></div>
                           
                           <div class="task_status"><?php  ?></div>
                          
                          </div>
                          <div class="row" style="margin-bottom: 4px">
                         
                            <div class="col-lg-2 col-md-3 col-sm-3 "><b>Tags:</b></div>
                            <div class="col-lg-8 col-md-8 col-sm-8 box_title tags" style="margin:0;" >
                          
                            </div>

                          </div>
                          <div class="row" style=" display: -webkit-box;min-height: 31px">
                            <div class="col-lg-2 col-md-3 col-sm-3 " style="margin:0">
                                <span class="pull-left"><b>Followers:</b></span>  
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-3" data-toggle="tooltip" title="Add followers" style="cursor: pointer;">
                                    <div class="btn_round add_follower_btn">
                                      <i class="fa fa-user icon "></i>
                                    </div>
                            </div>

                            <div class="followers pull-left col-lg-10 col-md-9 col-sm-8" style="padding-left: 0">


                                      <span class="btn_round" for="assign_to" style="color: #004085;text-align: center;margin: 0 auto;"  data-toggle="tooltip" title="Admin">Ad</span>

                              </div>


                          </div>

                          <div class="row follower-input" style="display: none">
                            <div class="col-lg-4 col-md-3 col-sm-3 "  >
                              <div class="input-group " >
                                {!! Form::select('followers', $EmployeeList, null,array('class' => 'select_item form-control select2-hidden-accessible employeelist', 'id'=>'tags','data-select2-id'=>'10','tabindex'=>'-1', 'aria-hidden'=>'true','multiple')) !!}

                                <button type="button" class="add_follower btn "  style="min-height: 30px;vertical-align: middle;min-width: 29px; text-align: center;margin-left: 1px;">

                                  <i class="fa fa-check" style="text-align: center; padding: 0 0 0 3px;color: green;" data-inputs=''></i>

                                </button>  

                                <!-- <i class="fa fa-pencil followers_icon"style="position: relative;color: rgb(170, 170, 170);cursor: pointer;left:15px;" ></i>  -->

                              </div> 


                            </div>
                          </div>

                          <div style="">
                              <div class="row bar-header activity_log" style="height:20px;margin-left: -12px">
                                <span class="box_title" style="margin:0 auto 0 0;">Task Activity</span> 
                              </div>
                              
                              <div class="col-lg-12 col-md-10 col-sm-8 card example-1 scrollbar bordered thin" style="border:0px;height:350px">



              
                              
                                <div class="row" style=" display: -webkit-box;min-height: 55px;margin-top: 2%">
                                  <div class="col-lg-2 col-md-3 col-sm-3 " style="padding-right: 0;margin-top:2%: ">
                                   
                                    <b>Comments:</b>

                                  </div>
                                  <div class="col-lg-9 col-md-7 col-sm-7 box_title " style="margin:0;min-width: 190px">
                                   
                                    <textarea class="form-control" style="width:100%;min-height:10px;max-height:55px" id="things" rows="5" name="task_comment" cols="50" data-row-id="task_id_133"></textarea>

                                  </div>
                                </div>
                                <div class="row" >

                                  <div class="col-lg-2 col-md-3 col-sm-3 " style="padding-right: 0;margin-top:2%: "><b></b>

                                  </div>
                                  <div class="col-lg-9 col-md-7 col-sm-7 box_title " >

                                  <div class="pull-right" style="margin: 5px">

                                    <button type="submit" class="task_edit_btn btn-comment" style="background-color: #ffab60;border: #004085;"
                                    >Add Comment</button>

                                  </div>
                                </div>
                                </div>

                                      <div class="card-body  row">
                                        <div class="col-sm-12 col-md-12 col-lg-12" >
                                             &nbsp;<label id="image-upload1" class="Task_attachments" style="color:#999;cursor:pointer;">Attachment...<i class="fa fa-paperclip "></i></label>
                                        </div>
                                        <!-- <ul class="tagit ui-widget ui-widget-content ui-corner-all" id="comment_attachment_datalist">
                                        



                                        </ul> -->
                                         <ul class="tagit ui-widget ui-widget-content ui-corner-all" id="task_attachment_datalist">
                                        



                                        </ul>
                                        <div class="col-sm-12 col-md-12 col-lg-12" id="task_comment">
                                          <p class="content_para" ></p>



                                        </div>
                                        <!-- <div class="col-sm-12 col-md-12 col-lg-12" id="task_activity_log">




                                        </div> -->
                                      </div> 
                              </div>




                        </div>
                    </div>
            </div>
  </div>






<!-- START DropZOne Template Panel -->
          <div  id="preview-template" style="visibility: hidden">
            <div style="margin: 2%">
              <div class="dz-filename" style="width:100% !important"><span data-dz-name=""></span><span style="padding:2px 2px 2px 4px"><i class="fa fa-close"></i></span></div>
              <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
            </div>
          </div>
<!-- END DropZOne Template Panel -->


@stop

@section('dom_links')
@parent
<?php //print_r(count($latest_project_data));exit; ?>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/2.0.0/handlebars.js"></script>  
<script type="text/javascript">
// var datatable = null;

  var datatable_options = {"ordering": false};
/*console.log($('.task_user_4').length);
console.log($('.task_user_4 span.popoverThis').text('12'));
console.log($('.org_user_list').find('li').length);*/

$(document).ready(function() {


 var colors = ["#00ffff","#f0ffff","#f5f5dc","#A05C4E","#0000ff",
 "#a52a2a","#00ffff","#00008b","#008b8b","#a9a9a9","#006400","#bdb76b",
 "#8b008b","#556b2f","#ff8c00","#9932cc","#8b0000","#e9967a","#9400d3",
 "#ff00ff","#ffd700","#008000","#4b0082","#f0e68c","#add8e6","#e0ffff",
 "#90ee90","#d3d3d3","#ffb6c1","#ffffe0","#00ff00","#ff00ff","#800000",
 "#000080","#808000","#ffa500","#ffc0cb","#800080","#800080","#ff0000",
 "#c0c0c0","#ffffff","#ffff00"];

 $(".select-tag").select2({
  tags: true,
  tokenSeparators: [',', ' '],
  templateSelection: function (data, container) {
    var selection = $('.select-tag').select2('data');
    var idx = selection.indexOf(data);
            data.idx = idx;
            $(container).css("background-color", colors[data.idx]);
            return data.text;
          },  
  });

 $(".jumper").on("click", function( e ) {

  e.preventDefault();

  $("body, html").animate({ 
    scrollTop: $( $(this).attr('href') ).offset().top 
  }, 8000);

});


 })

/*var image_upload = new Dropzone('#image-upload', {
  paramName: 'file',
  url: '{{URL::to('/api/wfm/attachement')}}',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
  },


  previewTemplate : document.querySelector('#preview-template').innerHTML,
  
  dictDefaultMessage: "Drop or click to upload image",
  clickable: true,
    maxFilesize: 5, // MB
    acceptedFiles: "image/*,application/*",
    maxFiles: 10,
    autoProcessQueue: true,
    addRemoveLinks: true,
    removedfile: function(file) {
  //    file.previewElement.remove();
},

sending: function(file, xhr, formData) {
  formData.append("business","1");
},
queuecomplete: function() {
  image_upload.removeAllFiles();
},
success: function(file, response){
 image_upload.processQueue();
 console.log(response);
 $(".dz-file-preview").css("display","flex");
 $("a.dz-remove").hide();
 $(".dz-image").hide();

 $('.img-responsive').show();
//      $('.dropzone').hide();

}
});*/
Dropzone.autoDiscover = false;
/*  
  var myDropzone = new Dropzone('#image-upload', { 
  paramName: 'file',
  url: '{{URL::to('/api/wfm/attachement')}}',
  headers: {
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
            },

  previewTemplate : document.querySelector('#preview-template').innerHTML,
  
});
myDropzone.on('sending', function(file, xhr, formData){
    formData.append('Description', 'Some description');
    
  }); */   
  

  /*var myDropzone = new Dropzone("#mediaFile", {
    url: '{{URL::to('/api/wfm/attachement')}}',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
      _token: '{{ csrf_token() }}',
      'X-Requested-With': 'XMLHttpRequest'
    },

    previewTemplate : document.querySelector('#preview-template').innerHTML,


    maxFiles:1,
    queueLimit:1,
    maxFilesize: 5, // MB
    acceptedFiles: "image/*,application/*",



    sending:function(file, xhr, formData){
      formData.append('name',"test" );
      formData.append('description',"test1" );

    },

    success: function(file, response){
      alert(response);
    },

    autoProcessQueue: false,
  });
  */




  /*start slidebar event*/
  var show_details=false;
  var activity_log_path="{{ URL::to('/') }}/public/activity_log";
  var activity_log_suffix=".txt";
  var followerlist;
  var EmployeeList;
  $('body').on('click','.get_detailsbar',function (e) {
    $(".DetailsBar").show("slide", { direction: "right" }, 1000);
    $('#popup_menu').css('display','none');
    $('.task_details_loader').css("display","block");
    $('#DetailsContent').addClass("blur_background");
    $(this).parent().find('tr').removeClass('selected');
    $(this).addClass('selected');
    var id=$(this).attr('data-id');
    var org_id=$(this).attr('data-org-id');
    var pro_id=$(this).attr('data-pro-id');
    var data_row_id=$(this).attr('id');
    var activity_log_url=activity_log_path+$(this).attr('data-activity-log')+activity_log_suffix;
    getFlatfileData(activity_log_url);
    $.ajax({
     url: "{{ url('wfm/taskdetails') }}/"+id,
      type: 'get',
      cache: false,
      dataType: "json",
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(data, textStatus, jqXHR) {

      if(data.status==1)
      {

        
        // $("#EditableDiv").css("border","1px solid #ced4da");

        $('.text_editor').show();
        $('.EditableText').show();
        $('.Edit_textarea').hide();
        $('.unedit-mode').show();
        $('.edit-mode').hide();

        $('.task_details_loader').css("display","none");
        $('#DetailsContent').removeClass("blur_background");
              //console.log(data);//task_headings,task_headings
          $('.follower-input').hide();

          $('.Task_attachments').attr('data-org-id',data.task_details.organization_id);
          $('.Task_attachments').attr('data-pro-id',data.task_details.project_id);
          $('.Task_attachments').attr('data-task-id',data.task_details.task_id);
          //task_description task_project task_start_date task_end_date
          tr_data="<td style='width:30%;text-align: center'>"+data.task_details.task_name+"</td><td style='width:30%;text-align: center'>"+data.task_details.task_status+"</td>";
          $(".task_headings").html(tr_data);

          $("#edit_task_desc").attr('data-task-id',data.task_details.task_id);
          $('.task_name').html('<div>' + data.task_details.task_name+ '</div>');
          $('input[name=task_name]').val(data.task_details.task_name);
          //$(".task_description div span").html(data.task_details.task_details);
          $("#EditableDiv").find(".task_descript").html(data.task_details.task_details);
          $(".task_descript").attr('data-id',id);
         // $(".task_created_by").text(data.task_details.first_name);
         $(".task_created_by").html('<div>' + data.task_details.created_by_name + '</div>');
         $(".assigned_to").html('<div>' + data.task_details.assigned_to_name + '</div>');
         //$("select.assigned_to").text( data.assigned_to).prop('selected',true);

           /* $(".task_start_date").text(data.task_details.create_date);
           $(".task_end_date").text(data.task_details.end_date);*/
           $(".task_start_date").html('<div>' + data.task_details.create_date+ '</div>');
           //$("input[name=end-date]").val(data.task_details.end_date);
           $(".task_end_date").html('<div>' + data.task_details.end_date+ '</div>');
           $(".task_end_date").val(data.task_details.end_date);
           $(".task_end_date").attr('data-id',id);
           $(".assigned_to").attr('data-id',data.task_details.task_details_id);
           $(".task_project").text(data.task_details.project_name);
           $(".task_status").text(data.task_status);

           $("select[name=action]").attr('data-assigned-myself',data.task_details.is_assigned_myself);
           $("select[name=action]").attr('data-taskdetails_id',data.task_details.task_details_id);
          $("select[name=action]").attr('data-row-id',data_row_id);

         $("textarea[name=task_comment]").attr('data-row-id',data_row_id);
         $("select[name=followers]").attr('data-project-id',pro_id);
         $("select[name=followers]").attr('data-organisation-id',org_id);
         $("select[name=followers]").attr('data-task-id',id);

        $(".task_name_submit,.task_details_submit").attr('data-task-id',data.task_details.task_id);
        $('select[name=project]').attr('data-task-id',data.task_details.task_id);

        $('select[name=priority]').attr('data-task-id',data.task_details.task_id);

        $('select[name=assigned_to]').attr('data-task-id',data.task_details.task_id);

        $('.date-picker').attr('data-task-id',data.task_details.task_id);

        $(".task_name_div,.task_details_div,.priority_div,.project_div,.end_date_div,.assignedto_div").attr('id',data.task_type);

        $('.edit_priority').empty().html(data.prority_label);
        
  
         followers=data.followers;
         follower_Data="";
         followerlist=[];
         $.each(followers, function(key, value) {
          follower_Data +=`<span class="btn_round follower" data-row-id="`+data_row_id+`" data-follower-id="`+key+`"  style="color: #004085;text-align: center;margin: 0 auto;position:unset;cursor:pointer" data-toggle="tooltip" title="`+value+`" data-original-title="`+value+`">`+value[0]+value[1]+`</span>`;
          followerlist.push(value);
        });

         EmployeeList=data.employees;



       $("select[name=assign_to]").empty();
       $("select[name=assign_to]").append(new Option("Select User", " "));
       $.each(EmployeeList, function(key, value) {
        $("select[name=assign_to]")
        .append($("<option/>")
          .attr("value",key)
          .text(value));
      });
       $("select.assigned_to option").filter(function() {
        return this.text == data.assigned_to; 
      }).attr('selected', true);


       $("select[name=followers]").empty();
       $("select[name=followers]").append(new Option("Select User", " "));
       $.each(EmployeeList, function(key, value) {
        $("select[name=followers]")
        .append($("<option></option>")
          .attr("value",key)
          .text(value));
      });

       $(".followers").html(follower_Data);

       $(".progress_label").show();
       $(".progress_data").css('visibility','visible');
       $("select[name=action]").empty();
       
       Actions=data.task_action;
       $.each(Actions, function(key, value) {
        $("select[name=action]")
        .append($("<option></option>")
          .attr("value",key)
          .text(value));
      });

       $('.task_tags').remove();
       if(data.tags)
       {

          $.each(data.tags, function(key, value) {
            if(value){

          $('.tags').append('<label class="grid_label badge badge-success status task_tags" style="background-color: #ff7611 !important;font-size:90%;margin-right:2px">'+value+'</label>');
            }
           });
       }
       if(data.comment_attachments)
       {
    
        attachement=data.comment_attachments;

        $("#comment_attachment_datalist").empty(); 
        for (var key in data.comment_attachments) {
          filename=attachement[key]['file_original_name'];
          type = filename.split(".")[1];
      
          icon=attachment_type(type);
        var filePath = {!! json_encode(asset('public/attachment')) !!}+"/"+attachement[key]['file_wpath'];
        attachment_append_data(id="comment_attachment_datalist",data,key,icon,filePath);
       }


     }
     if(data.task_attachments)
       {
    

        attachement=data.task_attachments;
        $("#task_attachment_datalist").empty(); 
        for (var key in data.task_attachments) {
       
          filename=attachement[key]['file_original_name'];
          type = filename.split(".")[1];
         
          icon=attachment_type(type);
        var filePath = {!! json_encode(asset('public/attachment')) !!}+"/"+attachement[key]['file_wpath'];
          attachment_append_data(id="task_attachment_datalist",data,key,icon,filePath);
       }


     }

     $("select[name=action]").val(data.current_action);

     var comment_append_id=$("#task_comment");
     Append_data="";
     $(comment_append_id).empty();
     comments=data.task_comments;
     $(comments.reverse()).each(function (key,data){
     Append_data +=Comments(comments[key]);
      });
     $("#task_comment").append(Append_data);

     }
   },
   error: function(jqXHR, textStatus, errorThrown) {}
 });

});


function attachment_type(type)
{
          if(type=="pdf")
          {
            icon="fa fa-file-pdf-o";
          }
          if(type=="docx"||type=="doc")
          {
            icon="fa fa-file-word-o";
          }
          if(type=="png"||type=="jpg")
          {
            icon="fa fa-file-photo-o";
          }

           if(type=="xlsx"||type=="xls")
          {
            icon="fa fa-file-excel-o";
          }
           if(type=="txt")
          {
            icon="fa fa-file";
          }
          return icon;     
}


function attachment_append_data(id,data,key,icon,filePath)
{
    $("#"+id).append('<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable text-overlap" style="width:100px" data-toggle="tooltip" title="'+attachement[key]['file_original_name']+'"><i class="'+icon+'"></i><a class="tagit-label "  target="_blank" href="'+filePath+'" > '+attachement[key]['file_original_name']+'</a><a class="tagit-close"><span class="text-icon"  data-id="'+attachement[key]['id']+'" data-toggle="confirmation">×</span><span class="ui-icon ui-icon-close"></span></a></li>');
}





$('.add_follower').on('click',function(){

  org_id=$('select[name=followers]').attr('data-organisation-id');
  pro_id=$('select[name=followers]').attr('data-project-id');
  task_id=$('select[name=followers]').attr('data-task-id');
  task_name=$('.task_name').text();
  //console.log(org_id+","+pro_id+","+task_id);
  inputs={
   _token:'{{csrf_token()}}',
   followers:$('select[name=followers]').val(),
   organization_id:org_id,
   project_id:pro_id,
   task_id:task_id
 }
 var activity_log_url="/org_"+org_id+"/pro_"+pro_id+"/"+"task_"+task_id+activity_log_suffix;
      
       $.ajax({
         url: '{{URL::to('/api/wfm/addfollowers')}}',
         type: 'post',
         headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
        },
        data:
        JSON.stringify(inputs),
        contentType: false,
        processData: false,
        success: function(data, textStatus, jqXHR) {
          followers=data.followers;
          followers_id=data.followers_id;
         follower_Data ="";
    $.each(data.followers, function(key, value) {
      follower_Data +=`<span class="btn_round follower"  style="color: #004085;text-align: center;margin: 0 auto;position:unset;cursor:pointer"  data-row-id="task_id_`+task_id+`" rel="tooltip" data-follower-id="`+followers_id[key]+`" title="`+value+`" data-original-title="`+value+`">`+value[0]+value[1]+`</span>`;
      followerlist.push(value);
      // updateFlatfileData(data_type="4",org_id,activity_log_url,task_name,action="Follow",value);
    });
        $(".followers").append(follower_Data);
        $(".follower-input").hide();
        custom_success_msg(data.message);
  }
});
     });


function getFlatfileData(url)
{ 
  

 $.ajax({
  url: url,
  type: "GET",
  dataType: "text",
  cache: false,
  success: function (data){
   

    var activity_log_data=$.parseJSON(data);
    Append_data="";
    if(activity_log_data!=null)
    {
      var append_id=$("#task_activity_log");
      $(append_id).empty();
      $(activity_log_data.reverse()).each(function (key,data){
        Append_data +=ActivityLog(data);

      });

    }else{
      Append_data +='<p class="content_para" >No activity Found</p>';
    }
    $(append_id).html(Append_data);
  },
  error: function(data)
  {
                  //file not exists

                  console.log('file not exist');
                }
              });
}
function updateFlatfileData(data_type,org_id,log_url,subject,action,user="")
{
  inputs={
   data_type:data_type,
   organization_id:org_id,
   url:log_url,
   subject:subject,
   action:action,
   user:user,
 }
 $.ajax({
  url: "{{ url('api/wfm/update_activelog') }}",
  type: 'post',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
  },
  data:
  JSON.stringify(inputs),
  contentType: false,
  processData: false,
  success: function (data){
    //  console.log(data);
    if(data.status==1)
    {
      var activity_log_data=(data.log_data);
      Append_data="";
      if(activity_log_data!=null)
      {
        var append_id=$("#task_activity_log");

        Append_data +=ActivityLog(activity_log_data);
        console.log(Append_data);


      }else{

        Append_data +='<p class="content_para" >No activity Found</p>';
      }
      $(append_id).prepend(Append_data);
    }

  },
  error: function(data)
  {
                  //file not exists

                  console.log('file not exist');
                }
              });
}

function ActivityLog(data)
{
  if(data.DataType==1)
  {
    return '<p class="content_para" ><span style="color:#d7d4d4">'+formatDate(data.Time)+'</span> <b style="text-transform:capitalize">'+data.User+'</b> '+data.Action+'d the  <b style="text-transform:capitalize"> Task</b></p>';
  }
  if(data.DataType==2)
  {
   return '<p class="content_para" ><span style="color:#d7d4d4">'+formatDate(data.Time)+'</span> <b style="text-transform:capitalize">'+data.User+'</b>  changed the task <b style="text-transform:capitalize"> '+data.Subject+'</b> action to <b style="text-transform:capitalize">'+data.Action+'</b></p>';

 }
 if(data.DataType==3)
 {

  return '<p class="content_para" ><span style="color:#d7d4d4">'+formatDate(data.Time)+'</span> <b style="text-transform:capitalize">'+data.User+'</b> Updated <b>'+data.Subject+' </b> Task</p>';

}
if(data.DataType==4)
{
  /*    return '<p class="content_para" > <div class="btn_round days pull-left calculate btn-secondary-round">&nbsp;'+data.User[0]+data.User[1]+'</div>&nbsp;<span style="color:#aaa;font-weight:200">'+data.Comment+'</span></br><span style="color:#d7d4d4;font-size:11px">&nbsp;'+formatDate(data.Time)+'</span></p>';*/
  return '<p class="content_para" ><span style="color:#d7d4d4">'+formatDate(data.Time)+'</span> <b style="text-transform:capitalize">'+data.User+'</b> Started <b style="text-transform:capitalize">'+data.Action+'</b> this <b style="text-transform:capitalize">'+data.Subject+'</b> Task</p>';

}

}

function Comments(data){

  var Data='<div class="btn_round days pull-left calculate btn-secondary-round"   title="'+data.commenter_name+'">&nbsp;'+data.commenter_name[0]+data.commenter_name[1]+'</div>&nbsp;<span style="color:#aaa;font-weight:200;position:relative;left:30px">'+data.comments+'</span></br><span style="color:#d7d4d4;font-size:11px;position:relative;left:30px">&nbsp;'+formatDate(data.updated_at)+'</span>';

  return '<div class="content_para" >'+Data+'<div class="pull-right '+data.last_modified_by+'_commentor" style="width:10px;height:10px;display:none;cursor:pointer" data-id="'+data.id+'"><i class="fa fa-close"></i></div></div>'; 
}



/*console.log($("div.content_para").length);*/


$('body').on('click','.getTaskFilter',function (e) {

  $(".data_table tbody").find('tr').remove();
  FilterName=$(this).find('a').text();

  url="{{ url('wfm/taskfilter') }}";
  $.ajax({
    url: url,
    type: 'post',
    data:{
      'organization_id':$(this).attr('data-organisation-id'),
      'assigned_by':$(this).attr('data-assigned-by'),
      'assigned_to':$(this).attr('data-assigned-to'),
      'project_id':$(this).attr('data-project-id'),
      'follow_id':$(this).attr('data-followed-by-me'),
      'priority_id':$(this).attr('data-high-priority'),
      'due_date':$(this).attr('data-due-today'),


   // ="" data-high-priority="" data-due-today
   '_token': $('meta[name="csrf-token"]').attr('content'),

 },
 dataType: "HTML",
 success: function(data, textStatus, jqXHR) {
   call_back_optional(data);
   page_breadcrumb('wfm/taskfilter',FilterName);

 }
})
})




var edit_task_id;
var data_option_class;
var content;

// this is hide by Ajith
// $(document).on({
//   mouseenter: function () {
  
//         //stuff to do on mouse enter
//         $(this).find('.Editable_commentor').show();
//       },
//       mouseleave: function () {
//         $(this).find('.Editable_commentor').hide();

//         //stuff to do on mouse leave
//       }
//     }, "div.content_para");

/*$('div.content_para').hover(

 function () {
  $(this).find('.Editable').show();
  alert($(".content_para").length);
}, 

function () {
 $(this).find('.Editable').hide();
}
);*/
/*$('.task_edit_icon').on('click',function(){
  edit_task_id=$(this).attr('data-editcontent-id');
  data_option_class=$(this).attr("data-save-options-class");

  $("."+data_option_class).show();
  $(".desc_edit_options").hide();

  $("#"+edit_task_id).removeClass('task_edit')
  $("#"+edit_task_id).addClass('task_editcontent')
  $(".task_editcontent").attr('contenteditable',true);
  content=$("#"+edit_task_id+" span").text();

});



$('body').on("click",'.task_edit_close',function(){
  $("#"+edit_task_id).addClass('task_edit');
  $("#"+edit_task_id).removeAttr('contenteditable');
  $("#"+edit_task_id).removeClass('task_editcontent');
  $("."+data_option_class).hide();
  $(".desc_edit_options").show();
})


*/
/*
$('body').on("click",'.task_save',function(){
 task_description=$("#"+edit_task_id+" span").text();
 task_id=$("#"+edit_task_id).attr('data-task-id');

 $.ajax({
  url: "{{ url::to('wfm/UpdateTask') }}",
  type: 'post',
  data:{
   '_token': $('meta[name="csrf-token"]').attr('content'),
   'description':task_description,
   'task_id':task_id,
 },
 dataType: "json",
 success: function(data, textStatus, jqXHR) {
  if(data.status==1)
  {
   custom_success_msg(data.message);
   $("#"+edit_task_id).addClass('task_edit');
   $("#"+edit_task_id).removeAttr('contenteditable');
   $("#"+edit_task_id).removeClass('task_editcontent');
   $("."+data_option_class).hide();
   $(".desc_edit_options").show();
 }
}
}) 
})*/
$('body').on('click','#TaskFilter',function (e) {

  var searchTask=$(".search_task").val();

  var organization_id=$(this).attr('data-org-id');
  if(searchTask!=""||organization_id!="")
  {
    $("#table_container").addClass('blur_background');
    $(".table_container_loader").show();
    url="{{ url::to('wfm/searchtask') }}";
    $.ajax({
      url: url,
      type: 'post',
      data:{
       '_token': $('meta[name="csrf-token"]').attr('content'),
       'organization_id':organization_id,
       'search':searchTask
     },
     dataType: "html",
     success: function(data, textStatus, jqXHR) {
      //$("#tableContent").html(data);
      page_breadcrumb('wfm/searchtask','',searchTask);
      call_back_optional(data);
      $("#table_container").removeClass('blur_background');
      $(".table_container_loader").hide();

    }
  })  
  }

})

function call_back_optional(data) {
  datatable.destroy();

  datatable=$('#datatable').DataTable()
  datatable.clear().draw();


  if($.trim(data))
  {

   data = data.replace(/^\s*|\s*$/g, '');
   data = data.replace(/\\r\\n/gm, '');

   var expr = "</tr>\\s*<tr";
   var regEx = new RegExp(expr, "gm");
   var newRows = data.replace(regEx, "</tr><tr");
   datatable.rows.add($(newRows )).draw();
 }
}
/*START Project List*/
var newurl = "{{url::to('wfm/dashboard/')}}" ;

/*start  select project*/
$('body').on('click','.getproject',function () {
  var id=$(this).attr("data-id");
  var link=$(this).attr('data-href');
  var project_name=$(this).find('div span.text-overlap').text();
  $('.chart').attr('id',id);
  var organization_id=$(this).attr('data-org-id');
  if($(".project_list").find("li a.selected"))
  {
    $(".project_list").find("li a.selected").removeClass("selected");
    $(this).addClass("selected"); 
    $("span#getProjectName").text("/"+$(this).find('span:first-child').text()+"/");

  }
  $("#table_container").addClass('blur_background');
  $(".table_container_loader").show();


  if(id){

    window.history.pushState({path:newurl},'',link);

    page_breadcrumb('wfm/dashboard/',"Project",project_name);
    

    $.ajax({
      url: link,
      type: 'get',
      dataType: "html",
      success: function(data, textStatus, jqXHR) {

      // $('.data_table tbody').find('tr').remove();
      call_back_optional(data);
       //console.log(data);
       $("#table_container").removeClass('blur_background');
       $(".table_container_loader").hide();

     }
   })

  }
});

$('body').on('change','select[name=project_list]',function () {
      //  alert(newurl);
      $('.chart').attr('id',$(this).val());
      project_name=$(this).find(":selected").text();
      var link=$(this).find(':selected').attr('data-href');
      window.history.pushState({path:newurl},'',link);

      $("#table_container").addClass('blur_background');
      $(".table_container_loader").show();

      $.ajax({
        url: link,
        type: 'get',
        dataType: "html",
        success: function(data, textStatus, jqXHR) {
          page_breadcrumb('wfm/dashboard/',"Project",project_name);
          $('.data_table tbody').find('tr').remove();
          call_back_optional(data);

          $("#table_container").removeClass('blur_background');
          $(".table_container_loader").hide();

        }
      })
    /*   $('.data_table tbody').find('tr').remove();
    call_back_optional(data, `add`);*/
        //alert(link);
      })


/*END Project List*/

var FollowerName;
$(document).on({
  mouseenter: function () {
        //stuff to do on mouse enter
        FollowerName=$(this).text();
        $(this).text('');
        $(this).addClass('fa fa-close');
      },
      mouseleave: function () {
        $(this).removeClass('fa fa-close');
        $(this).text(FollowerName);

        //stuff to do on mouse leave
      }
    }, "span.btn_round")

/*START TASK EDIT CANCEL 22.11.2018*/

/*      $(document).on({
    mouseenter: function () {
        //stuff to do on mouse enter
    //  console.log($(this).find('.EditableText').is(":visible"));
    if($(this).find('.EditableText').is(":visible")==true)
    {
        $(this).find('.text_editor').show();
    }
       
    },
    mouseleave: function () {
        $(this).find('.text_editor').hide();
       
        //stuff to do on mouse leave
    }
  }, ".EditableContent")*/

/*  $('body').on('click','.text_editor',function (e) {
    $(this).parent().parent().find("#EditableDiv").css("border","0");
    $(this).parent().parent().find("#EditableDiv").css("outline","0");

    $(this).hide();
    TextContent=$(this).parent().find('.EditableText').text();
    id=$(this).parent().find('.EditableText').attr('data-id');

    $(this).parent().find('.EditableText').hide();
    $(this).parent().find('.Edit_textarea').show();
    $(this).parent().find('.Edit_textarea .editable-input').not('.select').text(TextContent);

$(this).parent().find('.Edit_textarea .editable-input .select').val(TextContent);

$(this).parent().find('.Edit_textarea .editable-input').attr('data-id',id);


});*/

$('body').on('click','.text_editor',function (e) {
  $("#EditableDiv").css("border","0");
  $("#EditableDiv").css("outline","0");

  /*    $('.text_editor').hide();*/
  TextContent=$('.EditableText').text();
  id=$('.EditableText').attr('data-id');

  $('.EditableText').hide();
  $('.Edit_textarea').show();
  $('.unedit-mode').hide();
  $('.edit-mode').show();
    //$('.Edit_textarea .editable-input').not('.select').text(TextContent);

//$('.Edit_textarea .editable-input .select').val(TextContent);

//$('.Edit_textarea .editable-input').attr('data-id',id);


});

$('body').on('click','.Editor-close',function (e) {
    //border: ;
    //if('')
    $("#EditableDiv").css("border","1px solid #ced4da");;
    
    $('.text_editor').show();
    $('.EditableText').show();
    $('.Edit_textarea').hide();

    $('.edit-mode').hide();
    $('.unedit-mode').show();

  });




$('body').on('click','.Editor-save',function (e) {

  task_description=$('textarea.task_descript').val();
  assigned_to=$("select.assigned_to").val();
  end_date=$('.task_end_date.editable-input').val();
  tr_id="task_id_"+$('.editable-input').attr('data-id');
  activity_log_url=$("#"+tr_id).attr('data-activity-log')+activity_log_suffix;
  org_id=$("#"+tr_id).attr('data-org-id');
  action="Task Details Updated";
  task_name=$('.task_name').text();

  updateFlatfileData(data_type="3",org_id,activity_log_url,task_name,action);
  inputs={
    assigned_to:assigned_to,
    end_date:end_date,
    task_details:task_description,
  }

  $.ajax({
    url:"{{url('api/wfm/update_task')}}"+"/"+id,
    type: 'post',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
    },
    data:
    JSON.stringify(inputs),
    contentType: false,
    processData: false,
    success: function(data, textStatus, jqXHR) {
      if(data.status==1)
      {
       $('.unedit-mode').show();
       $('.edit-mode').hide();
       $('.text_editor').show();
       $("#EditableDiv").css("border","1px solid #ced4da");;
       $('.EditableText').show();
       $('.Edit_textarea').hide();
        custom_success_msg(data.message);/*
        console.log(data.data);
        $(div_attr).parent().parent().find('.EditableText').text("");
        $(div_attr).parent().parent().find('.EditableText').text(data.data);
        $(div_attr).parent().parent().find('.text_editor').show();
        $(div_attr).parent().parent().find('.EditableText').show();
        $(div_attr).parent().parent().find('.Edit_textarea').hide();
        */
      }
    }
  });


});

/*END TASK EDIT CANCEL 22.11.2018*/

/*START USER TASK BY ORG 23.11.2018*/

$('body').on('click change','.getTaskByO_U',function (e) {
  organization_id=$(this).attr("data-id");
     // console.log($('.getTaskByO_U').is('checkbox'));

     User=$(this).find('a div').text();
     if(!User)
     {

       User=$(this).find(':selected').text();
     }
     user_id=$(this).attr("data-u-id");
     if($(this).val()!="")
     {
      user_id=$(this).val();
    }

    $("#table_container").addClass('blur_background');
    $(".table_container_loader").show();
    url="{{ url::to('wfm/get_task_orguser') }}/"+organization_id+"/"+user_id;
    $.ajax({

      url:url,
      type: 'get',
      contentType: false,
      processData: false,
      success: function(data, textStatus, jqXHR) {
        if(data.status==1)
        {
        //  $('#tableContent').html(data.html);
        $('.data_table tbody').find('tr').remove();

        page_breadcrumb('wfm/get_task_orguser','',User);
        call_back_optional(data.html);
        $("#table_container").removeClass('blur_background');
        $(".table_container_loader").hide();

//          custom_success_msg(data.message);


}
}

})
  });
/*END USER TASK BY ORG 23.11.2018*/

/*attachement in comment*/
function formatDate(date) {
  var date = new Date(date);

  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  getMonth=date.getMonth()+1 ;
  getDate=date.getDate() ;
  getYearDate=date.getFullYear() + " " + strTime
  return  getDate+'-'+getMonth+'-'+getYearDate;
}





$('body').on('click','.UpdateProgress',function () {

  status=$('select[name=action]').val();
  task_name=$('.task_name').text();
  tr_id=$('select[name=action]').attr('data-row-id');
  id=$('select[name=action]').attr('data-progress_id');
  function_name=$('select[name=action]').attr('data-function');
  task_id=$("#"+tr_id).attr('data-id');
  
  var inputs={
   _token :$('meta[name="csrf-token"]').attr('content'),
   'id':id,  
   'action_id':status,
   'task_id':$("#"+tr_id).attr('data-id'),
 };
 /*Start Update Flat file Data*/
 org_id=$("#"+tr_id).attr('data-org-id');

 activity_log_url=$("#"+tr_id).attr('data-activity-log')+activity_log_suffix;

 action=$('select[name=action]').find('option:selected').text();


 /*End Update Flat file Data*/
//alert("test");
$.ajax({
  url: '{{URL::to('/wfm/')}}/'+function_name+'',

  type: 'POST',
  data:inputs,

  dataType: "json",
  success: function(data, textStatus, jqXHR) {
          //  console.log($.parseJSON(return_fields));
          if(data.status==1)
          {

            custom_success_msg(data.message);
            updateFlatfileData(data_type="2",org_id,activity_log_url,task_name,action);

        //    console.log($("#TaskStatus_"+task_id).length);
        $("#TaskStatus_"+task_id).html(data.task_status);
        $('select[name=action]').val(data.task_status_id);

          //  conslole($('#TaskStatus_'+tr_id).length);
         //   $('#TaskStatus_'+tr_id).html(data.task_status);
       }

     },
     error: function(jqXHR, textStatus, errorThrown) {}
   });

})


$('body').on('click','.btn-comment',function (e) {

  tr_id=$('textarea[name=task_comment]').attr('data-row-id');
  task_id=$("#"+tr_id).attr('data-id');  
  var comment=$('textarea[name=task_comment]').val();
  var organization_id=$("#task_id_"+task_id).attr('data-org-id');
  /*Update Flat file Data*/
  if(comment)
  {
    inputs={
      task_id:task_id,
      comments:comment,
      organization_id:organization_id
    }

    $.ajax({

      url:"{{url('api/wfm/comments')}}",
      type: 'post',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
      },

      data:
      JSON.stringify(inputs),
      contentType: false,
      processData: false,
      success: function(data, textStatus, jqXHR) {
        if(data.status==1)
        { 
          console.log(data.task_comments);
          $('textarea[name=task_comment]').val("");
          var comment_append_id=$("#task_comment");
          Append_data="";
          $(comment_append_id).empty();
          comments=data.task_comments;
          $(comments.reverse()).each(function (key,data){
          Append_data +=Comments(comments[key]);
          });
     }
   }
 });
  }
  /*Update Flat file Data*/
});


$('body').on('click','.Editable_commentor',function () {
  id=$(this).attr("data-id");
  $(this).parent().remove();
  $.ajax({
   url: '{{URL::to('/api/wfm/delete_comment/')}}/'+id,
   type: 'delete',
   headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
  },

  contentType: false,
  processData: false,
  success: function(data, textStatus, jqXHR) {
    if(data.status==1)
    {
     /* updateFlatfileData(data_type="4",org_id,activity_log_url,task_name,action="Unfollow",follower_name);*/
     custom_success_msg(data.message);

   }
 },
 error: function(jqXHR, textStatus, errorThrown) {}
});
})

$('body').on('change','.getTaskCount',function (e) {
  e.preventDefault();
  var id=$(this).attr('data-id');
 // organization_id=$("#task_organisation").val();
  $.ajax({
    url: "{{ url('wfm/usertaskcount') }}/"+id,
    type: 'get',
    data:{
     '_token': $('meta[name="csrf-token"]').attr('content'),

   },
   dataType: "json",
   success: function(data, textStatus, jqXHR) {
    if(data.status==1)
    {
              console.log(data);//task_headings,task_headings
//$("#task_count span").text(data.task_count);
}
},
error: function(jqXHR, textStatus, errorThrown) {}
});

})
/*end slidebar event*/

/*expand a compress screen*/
$('body').on('click','#toggle_taskDetails',function () {
  if ($(this).hasClass('fa fa-expand')) {
    $(this).removeClass('fa fa-expand');
    $(this).addClass('fa fa-compress');
    $("#Toggle_screen").removeClass('col-lg-5 col-md-6 col-sm-4  DetailsBar');
    $("#Toggle_screen").addClass('col-lg-12 col-md-12 col-sm-12  DetailsBar');
  } else {
    $(this).removeClass('fa fa-compress');
    $(this).addClass('fa fa-expand');
    $("#Toggle_screen").removeClass('col-lg-12 col-md-12 col-sm-12  DetailsBar');
    $("#Toggle_screen").addClass('col-lg-5 col-md-6 col-sm-4  DetailsBar');
  }
});
/*expand a compress screen*/




/*Start Add follower*/


$('body').on('click','.add_follower_btn',function () {
//console.log(followerlist);
$("select[name=followers]").empty();
$.each(EmployeeList, function(key, value) {
  $("select[name=followers]")
  .append($("<option></option>")
    .attr("value",key)
    .text(value));
});

$.each(followerlist, function(key,value){
  jQuery("select[name=followers] option:contains('"+value+"')").attr('disabled', 'disabled').hide();
});


if($(".follower-input").is(":visible")){
  $('.follower-input').hide();
}else{
  $('.follower-input').show();

}

})

$('body').on('click','.follower',function () {
  id=$(this).attr("data-follower-id");
  $(this).remove();
  task_row_id=$(this).attr('data-row-id');
  follower_name=$(this).attr('data-original-title');
  task_name=$('.task_name').text();
 // data_row_id=$(this).attr('data-row-id');
 org_id=$("#"+task_row_id).attr('data-org-id');
 data_activity_log=$("#"+task_row_id).attr('data-activity-log');
 activity_log_url=data_activity_log+activity_log_suffix;

      /*$.each(followerlist, function(key,value){
        if(value==""+follower_name+"")
        {
          console.log(true);
        
  jQuery("select[name=followers] option:contains('"+value+"')").removeAttr('disabled').show();
      }
    });*/
    value=""+follower_name+"";
    console.log(removeArray(followerlist,value));
    $.ajax({
     url: '{{URL::to('/api/wfm/deletefollowers/')}}/'+id,
     type: 'delete',
     headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
    },

    contentType: false,
    processData: false,
    success: function(data, textStatus, jqXHR) {
      if(data.status==1)
      {
        updateFlatfileData(data_type="4",org_id,activity_log_url,task_name,action="Unfollow",follower_name);
        custom_success_msg('Follower Deleted successfully');
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {}
  });
  })
/*START DELETE ATTACHMENTS*/

// this is tempory hide by Ajith
// $('body').on('click','.del_attach', function(e) {
//   id=$(this).attr("data-id");

//     li=$(this).closest('li');
//   if(id)
//   {
//     e.preventDefault();
//     $('.delete_modal_ajax').modal('show')
//     .one('click', '.delete_modal_ajax_btn', function(e) {
//     $(li).remove();
//      $.ajax({
//        url: '{{ URL('/api/wfm/delete_attachment/') }}/'+id,
//        type: 'delete',
//        headers: {
//         'Content-Type': 'application/json',
//         'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
//       },

//       contentType: false,
//       processData: false,
//       success: function(data, textStatus, jqXHR) {
//         if(data.status==1)
//         {
//           $('.delete_modal_ajax').modal('hide')
         
//         }
//       },
//       error: function(jqXHR, textStatus, errorThrown) {}
//     });
//    });

    
//   }
// });

/*$('body').on('click','.download_attachment', function(e) {
  id=$(this).parent().find('a.tagit-close').find('.del_attach').attr('data-id');


  if(id)
  {
    e.preventDefault();
    
    url='{{ URL('/api/wfm/download_attachment/') }}/'+id;
 //   encode_url='{{ urldecode('+url+') }}';
     $.ajax({
       url: url,
       type: 'get',
       headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
      },

      contentType: false,
      processData: false,
      success: function(data, textStatus, jqXHR) {
        if(data.status==1)
        {
         url='{{ URL('/wfm/attachment_download/') }}/'+id;
          //$('.delete_modal_ajax').modal('hide')
          window.open(url
,
  '_blank' 
);
         
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {}
    });


    
  }
});*/

 /* $.ajax({
     url: '{{URL::to('/api/wfm/delete_attachment/')}}/'+id,
     type: 'delete',
     headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
    },

    contentType: false,
    processData: false,
    success: function(data, textStatus, jqXHR) {
      if(data.status==1)
      {
        updateFlatfileData(data_type="4",org_id,activity_log_url,task_name,action="Unfollow",follower_name);
        custom_success_msg('Follower Deleted successfully');
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {}
  });*/




  /*END DELETE ATTACHMENTS*/

  $('body [data-toggle="tooltip"]').tooltip();
  /*End Add follower*/

/* var evt = new MouseEvent("click", {
        view: window,
        bubbles: true,
        cancelable: true,
        clientX: 20,
      //43   whatever properties you want to give it
    }),
        ele = document.getElementById("button");
        ele.dispatchEvent(evt);*/

        function confirmation_popup(msg,btn1_value,btn2_value)
        {
          $("div.confirmation_modal_ajax  h4.modal-title").text('Alert');
          $("div.confirmation_modal_ajax  div.modal-body").text(msg);
          $("div.confirmation_modal_ajax  div.modal-footer button.btn.default").text(btn2_value).on('click',function(){
            $('.wfm_project_crud_modal').css('z-index','1050');
            $(".confirmation_modal_ajax").modal('hide');
          });
          $("div.confirmation_modal_ajax  div.modal-footer button.delete_modal_ajax_btn").text(btn1_value).on('click',function(){

            $(".confirmation_modal_ajax").modal('hide');
          });;
          $(".confirmation_modal_ajax").modal('show');
        }

//$(document).foundation('tooltip');


function removeArray(arr) {
  var what, a = arguments, L = a.length, ax;
  while (L > 1 && arr.length) {
    what = a[--L];
    while ((ax= arr.indexOf(what)) !== -1) {
      arr.splice(ax, 1);
    }
  }
  return arr;
}


$(".task_details_div").hover(
  function(){   
  if($(this).attr('id') == 'Assigned By' || $(this).attr('id') == 'Assigned To'){
   $('.edit_task_details').hide();
   $('.update_task_details').show();
   $('.task_details_submit').show();
   $('.task_details_revert').show();
 }
    }, function(){
   $('.edit_task_details').show();
   $('.update_task_details').hide();
   $('.task_details_submit').hide();
   $('.task_details_revert').hide();
});


$(".task_name_div").hover(
  function(){   
  
  if($(this).attr('id') == 'Assigned By'){
   $('.edit_task_name').hide();
   $('.update_task_name').show();
   $('.task_name_submit').show();
   $('.task_name_revert').show();
 }
    }, function(){
  $('.edit_task_name').show();
   $('.update_task_name').hide();
   $('.task_name_submit').hide();
   $('.task_name_revert').hide();
});

$(".project_div").hover(
  function(){
  if($(this).attr('id') == 'Assigned By' || $(this).attr('id') == 'Assigned To'){
   $('.edit_project').hide();
   $('.update_project').show(); 
 }
    }, function(){
   $('.edit_project').show();
   $('.update_project').hide(); 
});

$(".end_date_div").hover(
  function(){
  if($(this).attr('id') == 'Assigned By' || $(this).attr('id') == 'Assigned To'){
   $('.task_end_date').hide();
   $('.update_end_date').show();
 }
    }, function(){     
   $('.task_end_date').show();
   $('.update_end_date').hide();
});

$(".assignedto_div").hover(
  function(){ 
  if($(this).attr('id') == 'Assigned By' || $(this).attr('id') == 'Assigned To'){
   $('.assigned_to').hide();
   $('.update_assignedto').show();
 }
    }, function(){    
   $('.assigned_to').show();
   $('.update_assignedto').hide();
});

$(".priority_div").hover(
  function(){ 
  if($(this).attr('id') == 'Assigned By' || $(this).attr('id') == 'Assigned To'){
   $('.edit_priority').hide();
   $('.update_priority').show();
 }
    }, function(){    
    $('.edit_priority').show();
   $('.update_priority').hide();
});

$('body').on('click','.task_details_submit',function () {
  $.ajax({
      url: "{{ route('task_details_submit') }}",
      type: 'post',
      data: {  
        _token: '{{ csrf_token() }}',
        task_id:$(this).attr('data-task-id'),
        task_details:$('textarea[name=task_details]').val(),
        },
      success:function(data, textStatus, jqXHR) {

        $('.edit_task_details').show();
        $('.edit_task_details').text(data.task_details);
        $('.update_task_details').hide();
        $('.task_details_submit').hide();
        $('.task_details_revert').hide();

        var comment_append_id=$("#task_comment");
        Append_data="";
        $(comment_append_id).empty();
        comments=data.task_comments;
        $(comments.reverse()).each(function (key,data){
        Append_data +=Comments(comments[key]);
        });
        $("#task_comment").append(Append_data);
        },
      error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
  });
});

$('body').on('click','.task_name_submit',function () {
  $.ajax({
      url: "{{ route('task_name_submit') }}",
      type: 'post',
      data: {  
        _token: '{{ csrf_token() }}',
        task_id:$(this).attr('data-task-id'),
        task_name:$('input[name=task_name]').val(),
        },
      success:function(data, textStatus, jqXHR) {
     
        $('.edit_task_name').show();
        $('.edit_task_name').text(data.task_name);
        $('.update_task_name').hide();
        $('.task_name_submit').hide();
        $('.task_name_revert').hide();
        var comment_append_id=$("#task_comment");
        Append_data="";
        $(comment_append_id).empty();
        comments=data.task_comments;
        $(comments.reverse()).each(function (key,data){
        Append_data +=Comments(comments[key]);
        });
        $("#task_comment").append(Append_data);

        },
      error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
  });
});

$('select[name=project]').on('change',function(){
  
  $.ajax({
      url: "{{ route('project_update') }}",
      type: 'post',
      data: {  
        _token: '{{ csrf_token() }}',
        task_id:$(this).attr('data-task-id'),
        project_id:$(this).val()
        },
      success:function(data, textStatus, jqXHR) {
      
        $('.task_project').text(data.project_name);
        $('.update_project').hide();
        var comment_append_id=$("#task_comment");
        Append_data="";
        $(comment_append_id).empty();
        comments=data.task_comments;
        $(comments.reverse()).each(function (key,data){
        Append_data +=Comments(comments[key]);
        });
        $("#task_comment").append(Append_data);
        },
      error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
  });
});

$('select[name=assigned_to]').on('change',function(){
  
  $.ajax({
      url: "{{ route('assigned_to_update') }}",
      type: 'post',
      data: {  
        _token: '{{ csrf_token() }}',
        task_id:$(this).attr('data-task-id'),
        assigned_id:$(this).val()
        },
      success:function(data, textStatus, jqXHR) { 
        // $('.task_project').text(data.project_name);
        // $('.update_project').hide();
        // var comment_append_id=$("#task_comment");
        // Append_data="";
        // $(comment_append_id).empty();
        // comments=data.task_comments;
        // $(comments.reverse()).each(function (key,data){
        // Append_data +=Comments(comments[key]);
        // });
        // $("#task_comment").append(Append_data);
        },
      error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
  });
});


$('.date-picker').datepicker({
     endDate: new Date(),
     autoclose: true,
     }).on('changeDate', function(ev){
    
    $.ajax({
      url: "{{ route('end_date_update') }}",
      type: 'post',
      data: {  
        _token: '{{ csrf_token() }}',
        task_id:$(this).attr('data-task-id'),
        end_date:$(this).val()
        },
      success:function(data, textStatus, jqXHR) { 
        
        $('.task_end_date').text(data.task_end_date);
        $(this).hide();
        var comment_append_id=$("#task_comment");
        Append_data="";
        $(comment_append_id).empty();
        comments=data.task_comments;
        $(comments.reverse()).each(function (key,data){
        Append_data +=Comments(comments[key]);
        });
        $("#task_comment").append(Append_data);
        },
      error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
  });
});


$('select[name=priority]').on('change',function(){
  
  $.ajax({
      url: "{{ route('priority_update') }}",
      type: 'post',
      data: {  
        _token: '{{ csrf_token() }}',
        task_id:$(this).attr('data-task-id'),
        priority_id:$(this).val()
        },
      success:function(data, textStatus, jqXHR) {
        

        $('.edit_priority').empty().html(data.priority);
        var comment_append_id=$("#task_comment");
        Append_data="";
        $(comment_append_id).empty();
        comments=data.task_comments;
        $(comments.reverse()).each(function (key,data){
        Append_data +=Comments(comments[key]);
        });
        $("#task_comment").append(Append_data);
        },
      error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
  });
});

</script>

@stop
