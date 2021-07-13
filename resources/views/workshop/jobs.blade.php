@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/codebase/skins/dhtmlxgantt_meadow.css') }}">
@stop
@include('includes.workshop')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header"> </div>
<div id="my-form" style="display: none;" class="modal fade show"  role="dialog">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLiveLabel">Edit Task</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
	  </div>
	  <div class="modal-body">
	  <div class="form-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label for="name" class=" control-label required">Task</label>
			
				<input class="form-control" name="task" id="name" type="text">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label for="name" class=" control-label required">Choose resource by</label>
				<div class="col-md-12">
				{{ Form::radio('resource_by', '1', null, array('id' => 'department')) }}
				<label for="department"><span></span>Department</label>
				{{ Form::radio('resource_by', '1', null, array('id' => 'designation')) }}
				<label for="designation"><span></span>Designation</label>
				{{ Form::radio('resource_by', '1', null, array('id' => 'skill')) }}
				<label for="skill"><span></span>Skill</label>
				</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label for="name" class=" control-label required">Assigned To</label>
			
				{{ Form::select('job_type_id', ['1' => 'Uday'], null, ['class' => 'form-control select_item', 'id' => 'employee_id',  'multiple' => 'multiple']) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				<label for="name" class=" control-label required">Status</label>
			
				{{ Form::select('job_type_id', ['0' => 'In Progress', '1' => 'Completed', '2' => 'On Hold'], null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}
				</div>
			</div>
		</div>


	  </div>
	  </div>
	  <div class="modal-footer">
		<input type="button" name="delete" value="Delete" class="btn btn-danger float-left" />
		<input type="button" name="close" value="Close" class="btn btn-default float-right" />
		<input type="button" name="save" value="Save" class="btn btn-success float-right" />
	  </div>
	</div>
  </div>
</div>
<div id="jobs" style='width:100%; height:500px;'></div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/codebase/dhtmlxgantt.js') }}"></script> 
<script type="text/javascript">

   $(document).ready(function() {

		sidebar_minimized();

	
	

	var jobs =  {
			data:[
				{id:1, text:"TN 01 AA 1234", start_date:"01-04-2013 01:10:30", duration:"",order:10,
					progress:0.4, open: true},
				{id:2, text:"Body Maintenance",    start_date:"01-04-2013 01:10:30", duration:2, order:10,
					progress:0.6, parent:1, open: true, "users": ["John", "Mike", "Anna"]},
				{id:3, text:"Dent Removal",    start_date:"01-04-2013 01:10:30", duration:1, order:20,
					progress:0.6, parent:2, "users": ["John"]},
				{id:4, text:"Painting",    start_date:"01-04-2013 02:10:30", duration:1, order:20,
					progress:0.6, parent:2, "users": ["Anna"]}
			],
					links:[
			{ id:3, source:3, target:4, type:"0"}
		]
		};

		gantt.config.xml_date="%d-%m-%Y %H:%i";
	gantt.config.scale_unit = "hour";
	gantt.config.step = 1;
	gantt.config.date_scale = "%g %a";
	gantt.config.min_column_width = 20;
	gantt.config.duration_unit = "minute";
	gantt.config.duration_step = 60;
	gantt.config.scale_height = 75;

	gantt.config.subscales = [
		{unit:"day", step:1, date : "%j %F, %l"},
		{unit:"minute", step:15, date : "%i"}
	];

	gantt.config.columns = [
		{name:"text", label:"Task name", tree:true, width:'*' },
		{name:"progress", label:"Progress", width:80, align: "center",
			template: function(item) {
				if (item.progress >= 1)
					return "Complete";
				if (item.progress == 0)
					return "Not started";
				return Math.round(item.progress*100) + "%";
			}
		},
		{name:"assigned", label:"Assigned to", align: "center", width:100,
			template: function(item) {
				if (!item.users) return "Nobody";
				return item.users.join(", ");
			}
		}
	];

		gantt.templates.task_class = function(st,end,item){
			return item.$level==0?"gantt_project":""
		};

		gantt.attachEvent("onTaskDblClick", function(id,e){
		var parent = (gantt.getTask(id)).parent;
		var children = (gantt.getChildren(id)).length;

		if(parent == 0 || (parent != 0 && children > 0)) {
			return false;
		}

		return true;
});



		gantt.init("jobs");
		gantt.parse(jobs);

		});



(function(){
  var taskId = null;
   
  gantt.showLightbox = function(id) {
	taskId = id;
	var task = gantt.getTask(id);
	
	var form = getForm();
	var input = form.querySelector("[name='task']");
	input.focus();
	input.value = task.text;
  
	form.style.display = "block"; 
	$('body').append('<div class="modal-backdrop fade show"></div>');
	
	form.querySelector("[name='save']").onclick = save;
	form.querySelector("[name='close']").onclick = cancel;
	form.querySelector("[name='delete']").onclick = remove;
  };
  
  gantt.hideLightbox = function(){
	getForm().style.display = ""; 
	$('body').find('.modal-backdrop').remove();
	taskId = null;
  }
   
  
  function getForm() { 
	return document.getElementById("my-form"); 
  }; 
  
  function save() {
	var task = gantt.getTask(taskId);
	
	task.text = getForm().querySelector("[name='description']").value;
	
	if(task.$new){
	  gantt.addTask(task,task.parent);
	}else{
	  gantt.updateTask(task.id);
	}
	
	gantt.hideLightbox();
  }
  
  function cancel() {
	var task = gantt.getTask(taskId);
	
	if(task.$new)
	   gantt.deleteTask(task.id);
	gantt.hideLightbox();
  }
  
  function remove() {
	gantt.deleteTask(taskId);
	gantt.hideLightbox();
  }

})();

	</script> 
@stop