
	@if($plan_name =='Professional')

        <li>
        	<div class="sidebar-submenu" style="display: block;width:74% !important" >
	            <div  class="">
	             
	                {!! Form::select('organization_id', $organizations,Session::get('organization_id'),['class' => 'md-input-container md-block md-input-has-placeholder select_item select2-hidden-accessible getOrgData','id'=>'input_2 organization_id']) !!}
	                
	            </div>        
           </div>
        </li>
   
        <div class="sidebar-submenu" style="display: block;overflow-y: scroll;height:150px">
       
      
            <div class="search">
    
                {!! Form::select('project_list',$projects,null, array('class' => 'form-control md-input-container md-block md-input-has-placeholder   select_item select2-hidden-accessible round sidebar-select ','id' => 'project_category_list','style'=>'width:50%;height:29px','placeholder'=>"Select Project"),$projects_tasks_link) !!} 

                @permission('wfm-add-project-menu')
                <a class="Add_project"  >
                    <span class="pull-right" style="position: absolute;right: -40px;top: 0px;font-size: 20px;"> 
                          <i class="fa fa-plus-circle " data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Add project')}}"></i>
                      </span>
                </a>
                @endpermission
  
        	</div>
        
        	<a class="Add_project"  >
            <span class="pull-right" style="position: absolute;right: -10px;top: -35px;color: #868e96;font-size: 20px;">
                    <i class="fa fa-plus-circle " data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Add project')}}"></i>
                  </span>
          	</a>
     

          
          	<ul class="project_list" style="padding: 5px">
                @if(isset($ProjectList) && count($ProjectList)>0)
                        @foreach($ProjectList as $project)

                        <li style="display: inline-flex;width: 100%;">
                            <a data-link="job-allocation" class="<?php ?> getproject project_{{$project->project_id}}" id="project_{{$project->project_id}}" data-id="{{$project->project_id}}" data-href="{{ url('wfm/dashboard') }}/{{$project->organization_id}}/{{$project->project_id}}"  data-org-id="{{Session::get('organization_id')}}">
                                    <div style="width:203px">
                                        
                                        <span class="pull-left text-overlap" style="width:140px" data-toggle='tooltip' title="{{$project->project_name}}">{{$project->project_name}}</span>

                                        <span class="count project_popup_{{$project->project_id}} pull-right" data-html="true"   data-id="projectcount_{{$project->project_id}} " class=""  data-popup-id="project_popup_{{$project->project_id}}" style="border-top:#ffab60;"> {{$project->COUNT}} </span>
                                    </div>
                            </a>
                        </li>

                        @endforeach
            </ul>

        	@endif
    	</div>

	    <div class="sidebar-submenu" style="display: block;overflow-y: scroll;height:150px;margin: 9% 0 0 0;">
	        

	                    <div class="" style="width: 77%;">  
	                            <span >
	                                {!! Form::select('employee_id',$EmployeeList,null, array('class' => 'form-control md-input-container md-block md-input-has-placeholder   select_item select2-hidden-accessible round sidebar-select getTaskByO_U','id' => 'employee_id','style'=>'width:50%;height:29px','placeholder'=>"Select User",'data-id'=>Session::get('organization_id'))) !!} 
	                            <span>
	                    </div>  
	     
	                    <ul style="padding: 5px" class="org_user_list">
	                            @if(isset($TaskUser) && count($TaskUser)>0)
	                                    @foreach($TaskUser as $user)
	        
	                                   <li class=" getTaskByO_U task_user_{{$user->employee_id}}"  data-id="{{$user->organization_id}}" data-u-id="{{$user->employee_id}}" style="height:20px">
	                                        <a class=" " >
	                                            <div style="padding: 0 0 0 2px;width:140px" class="text-overlap org_user_{{$user->employee_id}}">
	                                                {{$user->first_name}}
	                                            </div>
	                                            <span class="count" style="float: right;position: relative;top: 6px; right: 4px;">
	                                                <span class="task_user_count" style="text-align: center"> <?php echo $user->taskcount; ?> </span>
	                                            </span>
	                                        </a>
	                                    </li>

	                            @endforeach       
	                            @endif
	                    </ul>
	    </div>
           
         
      	<hr class="hr-danger" style="width:100%;width: 118%;position: relative;left: -21px;margin: 30px 0 30px 0;" />
       

          	@permission('wfm-chart-view-menu')
             <li ><a href="{{ url('wfm/dashboard/summary') }}/All">Chart View</a></li>
           	@endpermission

            @permission('wfm-manage-projects-menu')
                 <li><a href="{{ route('wfm.project_list') }}">Manage Projects</a></li>
            @endpermission
            
            @permission('wfm-master-dataset-menu')
                    <li><a href="{{ url('wfm/wfm_settings_professional') }}">Master Dataset</a></li>
            @endpermission

	@endif        