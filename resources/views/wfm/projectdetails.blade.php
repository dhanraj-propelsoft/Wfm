



            
@forelse( $CurrentUserTasks as $project_task)
@if($project_task->task_name)
<tr class="popUp get_detailsbar" data-id="{{$project_task->task_id}}" data-org-id="{{$project_task->organization_id}}" id="task_id_{{$project_task->task_id}}" data-pro-id="{{$project_task->project_id}}"  data-activity-log="/org_{{$project_task->organization_id}}/pro_{{$project_task->project_id}}/task_{{$project_task->task_id}}" data-action-id="{{$project_task->status}}" data-token=''>

  <td data-sort="{{$project_task->priority_id}}"><?php echo priority($project_task->priority_id); ?></td>

<?php //var_dump(GetTaskAction($project_task->status)); dd();?>
  <td>{{$project_task->task_code}}</td>
  <td>{{$project_task->task_name}}</td>
  <td>{{$project_task->project_name}}</td>

  <td>{{GetEmployeeNameById($project_task->assigned_to)  }}</td>
 <td>{{date_($project_task->end_date)  }}</td> 
 
  <td id="TaskStatus_{{$project_task->task_id}}">{{GetTaskStatus($project_task->task_status)}}</td>



</tr>
@endif
@empty
@endforelse

