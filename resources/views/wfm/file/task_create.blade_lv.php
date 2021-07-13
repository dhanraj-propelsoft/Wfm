
<div class="modal-header">
	
	<h4 class="modal-title pull-left">Add Task</h4>
	<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>

</div>
<style type="text/css">
	
</style>

{!! Form::open(['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}

<div class="modal-body">
	<div class="container-fluid">
		<div class="form-body">
			<div class="row">
				<div class="col-md-12" >
					<div class="form-group field" >
						
						{!! Form::hidden( 'token',$apiKey) !!}
						{!! Form::text('task_name', null, array('class' => 'inputText','style'=>' border: 1px solid #ced4da;color: #999; ' ,'id'=>'things','required')) !!}
						<label for="things" style="color: #999;"">&nbsp;Task To Do <i>(task)</i></label>
						</div>

						</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">

									{!! Form::textarea('task_details', null, array('class' => 'inputTextArea','rows'=>4,'style'=>'border: 1px solid #ced4da;color: #999;' ,'id'=>'task_details','required' )) !!}
									<span class="fa fa-plus-circle floating-label" style="color: #999;"">&nbsp;Task details</span>
									</div>
									</div>
									</div>


									<div class="row">

										<div class="col-xs-6 col-md-6"   >
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon " for="project_type" style="color: #919191;"><i class="fa fa-user"></i></span><!-- 
												<select id="project_type" type="select" class="form-control" name="project_type" style="color: #919191;">
													<option  selected disabled hidden >Under The Project</option>
													<option>Project 1</option>
													<option>Project 1</option>
													<option>Project 2</option>
													<option>Project 3</option>
												</select> -->
												 {!! Form::select('project_id', $projects,null, array('class' => 'form-control','id' => 'project_id','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select", "type"=>"select")) !!}
											</div>
										</div> 

										<div class="col-xs-6 col-md-6">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon" for="Deadline" style="color: #919191;"><i class="fa fa-calendar"></i></span>
												<input id="Deadline" type="" class="form-control date-picker" name="Deadline" placeholder="Deadline" style="color: #919191;" data-date-format = 'dd-mm-yyyy'>
											</div>
										</div>
									</div>
									<div class="row">

										<div class="col-xs-6 col-md-6">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon" for="created_by" style="color: #919191;"><i class="fa fa-user"></i></span>
												<select id="create_by" type="select" class="form-control" name="project" placeholder="Select Project" style="color: #919191;">
													<option selected disabled hidden>Created By</option>
													<option >Myself</option>
													<option>Employee 1</option>
													<option>Employee 2</option>
													<option>Employee 3</option>
												</select>
											</div>
										</div> 

										<div class="col-xs-6 col-md-6">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon" for="assign_to" style="color: #919191;"><i class="fa fa-user"></i></span>
												<select id="assign_to" type="select" class="form-control" name="assign_to" placeholder="assign_to" style="color: #919191;">
													<option selected disabled hidden>Assign To</option>
													<option>Myself</option>
													<option>Employee 1</option>
													<option>Employee 2</option>
													<option>Employee 3</option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-xs-6 col-md-6">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon" for="start_date" style="color: #919191;"><i class="fa fa-calendar"></i></span>
												<input id="start_date"  class="form-control date-picker" name="start_date"  placeholder="Start Date" style="color: #919191;" data-date-format = 'dd-mm-yyyy'>

											</div>
										</div> 

										<div class="col-xs-6 col-md-6">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon" for="Due date" style="color: #919191;"><i class="fa fa-calendar"></i></span>
												<input id="Due date"  placeholder="Due Date" class="form-control to-date-picker" name="due_date" style="color: #919191;" data-date-format = 'dd-mm-yyyy'>
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-xs-6 col-md-6">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon" for="size" style="color: #919191;"><i class="fa fa-user"></i></span>
												<select id="size" type="select" class="form-control" name="size"  style="color: #919191;">
													<option selected disabled hidden>Size</option>
													<option>1</option>

													<option>2</option>
													<option>3</option>
													<option>4</option>
												</select>

											</div>
										</div> 

										<div class="col-xs-6 col-md-6">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-user"></i></span>
												<input id="worth" type="text" class="form-control  " name="worth" placeholder="Worth" style="color: #919191;">
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-xs-8 col-md-8">
											<div class="input-group" >
												<div class="input_icon_fixed pull-left " style="width:100%" >
													<span class="pull-left " style="vertical-align: middle;    top: 10px;position: relative;"><i class="fa fa-list "></i>&nbsp;Priority</span>
													<p class="pull-left" style="margin: 3% 0 0 27%;" >&nbsp;{!! Form::radio('Priority', 'false', null,['style'=>"display:initial;"]) !!}</p>   
													<span class="pull-left" style="margin: 0 0 0 1%"><?php echo priority(4); ?></span>
													<p class="pull-left" style="margin: 3% 0 0 5%;" >&nbsp;{!! Form::radio('Priority', 'false', null,['style'=>"display:initial;"]) !!}</p>
													<span class="pull-left" style="margin: 0 0 0 1%;"><?php echo priority(3); ?></span>
													<p class="pull-left" style="margin: 3% 0 0 5%;" >&nbsp;{!! Form::radio('Priority', 'false', null,['style'=>"display:initial;"]) !!}</p>
													<span class="pull-left" style="margin: 0 0 0 1%;"><?php echo priority(2); ?></span>
													<p class="pull-left" style="margin: 3% 0 0 5%;" >&nbsp;{!! Form::radio('Priority', 'false', null,['style'=>"display:initial;"]) !!}</p>
													<span class="pull-left" style="margin: 0 0 0 1%;"><?php echo priority(1); ?></span>


												</div>

											</div>
										</div> 

										<div class="col-xs-3 col-md-3 pull-right" style="margin: 0 0 0 -1%">
											<div class="input-group">
												<p style="margin-top:6px"> No of days:&nbsp;</p>
												<div class="btn_round days pull-left">5</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-xs-12 col-md-12">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-repeat"></i>&nbsp;Repeat</span>		 
												{!! Form::select('repeat', [1=>'Never',2=>'Every Day',3=>'Week Day',4=>'Every Month',5=>'Every Year',6=>'Customized'],1, array('class' => 'form-control pull-left  select_item select2-hidden-accessible GetRepeatOption','id' => 'repeat','style'=>'width:50%;color:#999;height:29px','placeholder'=>'select')) !!}


											</div>
										</div>
									</div>

									<div class="form-group" id="Taskdue_week"  style="display: none;" > 
										<div class="row" >
											<div class="col-md-10 modal_align">

												<div class="weekDays-selector" style="margin: 0 3%;">
													<input type="checkbox" id="weekday-mon" class="weekday" />
													<label for="weekday-mon">M</label>
													<input type="checkbox" id="weekday-tue" class="weekday" />
													<label for="weekday-tue">T</label>
													<input type="checkbox" id="weekday-wed" class="weekday" />
													<label for="weekday-wed">W</label>
													<input type="checkbox" id="weekday-thu" class="weekday" />
													<label for="weekday-thu">T</label>
													<input type="checkbox" id="weekday-fri" class="weekday" />
													<label for="weekday-fri">F</label>
													<input type="checkbox" id="weekday-sat" class="weekday" />
													<label for="weekday-sat">S</label>
													<input type="checkbox" id="weekday-sun" class="weekday" />
													<label for="weekday-sun">S</label>
												</div>

											</div>

										</div>
									</div>
									<div class="row" >
										<div class="col-xs-8 col-md-8">
											<div class="form-group" id="Taskdue_date"  style="display: none;height: 29px">
												<div class="input-group" style="    border: 1px solid #ddd;">
													<span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-calendar"></i>&nbsp;Task Date</span>	

													{!! Form::text('task_date', null, array('class' => 'form-control accounts-date-picker pull-left input_box_hidden', 'data-date-format' => 'dd-mm-yyyy','placeholder'=>"select date",'id'=>'task_date')) !!}

												</div>
											</div>
										</div>

									</div>
									<div class="row">

										<div class="col-xs-12 col-md-12">
											<div class="input-group" style="    border: 1px solid #ddd;">
												<span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-tag"></i>&nbsp;Tags</span>
												<input name="tags" id="mySingleFieldTags" value="fancy, new, tag, demo">  
											</div> 


										</div>
									</div>


								</div>


								<div class="row">
									<div class="col-xs-8 col-md-8" style="margin-left:16px;">

										<label for="upload-photo" class="" style="color:#999;">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="photo" id="upload-photo" multiple/>
									</div>
								</div>

							</div>


						</div>























					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-success button" style="">Add Task</button>
					</div>
					{!! Form::close() !!}

					<script>
						$(document).ready(function()
						{
		//alert();

		var date = new Date();
		date.setDate(date.getDate());

		$('#start_date').datepicker({ 
			todayHighlight: true,
			startDate: date
		});

		basic_functions();




		$("input[name=start_date]").on("change", function(){

// alert($(this).val());




if($(this).val()!="")
{
	$('.to-date-picker').datepicker({
		rtl: false,
		orientation: "left",
		todayHighlight: true,
		autoclose: true,
		minDate: '0',
		startDate:$("input[name=start_date]").val()
		

	});
	$("input[name=due_date]").prop("disabled", false);
}
else
{
	$("input[name=due_date]").prop("disabled", true);
}
});







	//status=false;
	$(".GetRepeatOption").on("change",function(){
	   // 
//
repeat_type=$(this).val();
console.log(repeat_type);
if(repeat_type==3)
{
	$("#Taskdue_week").css("display","block")
	$("#Taskdue_date").css("display","none");
}else
if(repeat_type==4||repeat_type==5||repeat_type==6)
{
	$("#Taskdue_week").css("display","none");
	$("#Taskdue_date").css("display","block");

	if(repeat_type==6)
	{
		$("#Task_customized").css("display","block"); 
	}else{
		$("#Task_customized").css("display","none"); 
	}

}else{
	$("#Taskdue_date").css("display","none");
	$("#Task_customized").css("display","none"); 

	$("#Taskdue_week").css("display","none");
}
});



	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			task_status_name: { required: true  },

		},

		messages: {
			task_status_name: { required: "Task status Name is required."},

		},

		invalidHandler: function(event, validator) 
		{ 
			//display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) 
		{ // hightlight error inputs
			$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},

		submitHandler: function(form) {

			$('.loader_wall_onspot').show();

			$.ajax({
				url: '{{ route('task.store') }}',
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					task_status_name: $('input[name=task_status_name]').val(),

				},
				success:function(data, textStatus, jqXHR) {
					if(data.status==0)
					{

						$('.loader_wall_onspot').hide();
						input='input[name=task_status_name]';
						field_name="task status Name";
						error_msg(input,field_name);
						return false;
					}
					call_back(`<tr role="row" class="odd">

						<td>`+data.data.name+`</td>
						<td>
						<label class="grid_label badge badge-success status">Active</label>
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
						<option value="1">Active</option>
						<option value="0">In-active</option>
						</select>
						</td>                           
						<td>
						<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
						<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
						</td></tr>`, `add`, data.message);

					$('.loader_wall_onspot').hide();

				},
				error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
				}
			});
		}
	});



});
						$(document).ready(function(){
							$('body [data-toggle="tooltip"]').tooltip(); 

   // $("select[name=tags] option").hide();
//$("select[name=tags]").tagsinput('items')
/*   $('select[name=tags]').select2({
	placeholder: "tags1,tags2",
	 allowClear: true
	});*/

});

</script>


<script>
	$(function(){
		var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];




			//-------------------------------
			// Remove confirmation
			//-------------------------------
			$('#mySingleFieldTags').tagit({
				availableTags: sampleTags,
				removeConfirmation: true,
			});
			console.log(sampleTags);
			
		});

	</script>

