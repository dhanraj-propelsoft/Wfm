<script>
  $(function(){
    var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];

            $('#mySingleFieldTags').tagit({
              availableTags: sampleTags,
              removeConfirmation: true,
            });
            console.log(sampleTags);
            
          });
        </script>
        <style type="text/css">
        .form-group {
          margin-bottom: 0.5rem;
        }
        label {
          margin-bottom: 0;
        }
        label,input,select,.select2-create_by-container{
          min-height: 30px;
        }
      </style>

      <div class="modal-header">
        <h4 class="modal-title float-right">Add Task </h4>
        <a class="pull-left"  data-dismiss="modal"><i class="fa fa-close "></i></a>
      </div>

      {!! Form::open(['class' => 'form-horizontal validateform']) !!}
      {{ csrf_field() }}

      <div class="modal-body">
        <div class="form-body">

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0 auto">
              <div class="form-group">
                <label class="input_icon" for="things"><i class="fa fa-plus-circle "></i>&nbsp;Things to do<span style="font-style: italic;">(task)</span></label>
                
                {!! Form::hidden( 'token',$apiKey) !!}
                {!! Form::text('task_name', null, array('class' => 'form-control','style'=>'width: 96.5%;' ,'id'=>'things')) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0 auto">
              <div class="form-group">
                <label class="input_icon"   for="task_details" style="width: 89%"><i class="fa fa-plus-circle "></i>&nbsp;Task details</label>
                {!! Form::textarea('task_details', null, array('class' => 'form-control','rows'=>4,'style'=>'width: 96.5%;' ,'id'=>'task_details' )) !!}
              </div>
            </div>
          </div>
          <div class="form-group">

            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0 auto">
                <div class="col-lg-6 col-md-6 col-sm-6 pull-left" style="margin: 0 auto 0 0;min-width:30px;padding-left: 0">
                  <label class="input_icon_fixed pull-left" for="project_id" style="width:50%" ><i class="fa fa-folder-open-o "></i>&nbsp;Under the project</label>


                  <div class="pull-left input_box_hidden" style="width:50%;">
                   {!! Form::select('project_id', $projects,null, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'project_id','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select")) !!}
                 </div>
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 pull-left" style="margin: 0 auto 0 0;min-width:30px;padding-left: 0">

                <label class="input_icon_fixed pull-left" for="Deadline" style="width:48%" ><i class="fa fa-folder-open-o "></i>&nbsp;Deadline</label><label class="input_icon_fixed pull-left" id="Deadline" style="width:48%" ></label>


              </div>
            </div>

          </div>
        </div>
        <div class="form-group">

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0 auto">
              <div class="col-lg-6 col-md-6 col-sm-6 pull-left" style="margin: 0 auto 0 0;min-width:30px;padding-left: 0">

                <label class="input_icon_fixed pull-left" for="create_by" style="width:50%" ><i class="fa fa-user "></i>&nbsp;Created by</label>
                <div class="pull-left input_box_hidden" style="width:50%;">
                 {!! Form::select('assigned_by',$EmployeeList,0, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'create_by','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select")) !!}
               </div> 
             </div>
             <div class="col-lg-6 col-md-6 col-sm-6 pull-left" style="margin: 0 auto 0 0;min-width:30px;padding-left: 0">

              <label class="input_icon_fixed pull-left" for="create_by" style="width:48%" ><i class="fa fa-user "></i>&nbsp;Assign to</label>
              <div class="pull-left input_box_hidden" style="width:50%;">
               {!! Form::select('assigned_to', $EmployeeList,0, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'create_by','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select")) !!}
             </div> 
           </div>
         </div>

       </div>
     </div>
     <div class="form-group">

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 3% auto 0">

          <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">

           <div class="pull-left  col-lg-3 col-md-3 col-sm-3" style="min-height:30px;padding-left: 0;padding-right: 0" >
             <label class="input_icon_fixed pull-left" for="create_by"><i class="fa fa-calendar "></i></label>
             {!! Form::text('create_date', null, array('class' => 'form-control accounts-date-picker pull-left input_box_hidden', 'data-date-format' => 'dd-mm-yyyy','style'=>'width:76%;','id'=>'create_date','placeholder'=>'Start date')) !!}
           </div>
           <div class="pull-left  col-lg-3 col-md-3 col-sm-3" style="min-height:30px;padding-left: 0;padding-right: 0;margin:0 0 0 15%" >
             <label class="input_icon_fixed pull-left" for="end_date" ><i class="fa fa-calendar "></i></label>
             {!! Form::text('end_date', null, array('class' => 'form-control accounts-date-picker pull-left input_box_hidden', 'data-date-format' => 'dd-mm-yyyy','style'=>'width:76%;','id'=>'end_date','placeholder'=>'Due date')) !!}
           </div>

           <div class="pull-right  col-lg-3 col-md-3 col-sm-3" style="min-height:30px;padding-left: 0;padding-right: 0" >
            <span style=" color: #d8741c; 
            vertical-align: middle;
            padding: 8px;
            " class="pull-right">Days</span><div class="btn_round days " style="float: right;">4 </div> 
          </div>


        </div>
      </div>

    </div>

  </div>
  <div class="form-group">

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0% auto 0">

        <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">
          <div class="pull-left" style="width:50%">

           <label class="input_icon_fixed pull-left" for="worth_id" style="width:50%!important" ><i class="fa fa-user "></i>&nbsp;Size</label>
           <div class="pull-left input_box_hidden" style="width:45%;margin-right: 5%">
             {!! Form::select('size_id', ['Size',1,2,3,4,5],0, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'worth_id','style'=>'width:30%!important;color:#999;height:29px','placeholder'=>"Size")) !!}
           </div>
         </div>
         <div class="pull-right"  style="width:50%">


           <label class="input_icon_fixed pull-left" for="worth_id" style="width:50%!important;margin-left: 0%" ><i class="fa fa-user "></i>&nbsp;Worth</label>
           <div class="pull-left input_box_hidden" style="width:50%!important;">
            {!! Form::text('worth_id', null, array('class' => 'form-control pull-left input_box_hidden numeric', 'style'=>'width:104%;','id'=>'worth_id')) !!}
          </div>
          <!-- </div> -->
        </div>


      </div>
    </div>

  </div>

</div>
<div class="form-group">

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0% auto 0">

      <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">
        <div class="input_icon_fixed pull-left " style="width:99%" >
          <span class="pull-left " style="vertical-align: middle;    top: 10px;position: relative;"><i class="fa fa-list "></i>&nbsp;Priority</span>
          <p class="pull-left" style="margin: 1% 0 0 27%;" >&nbsp;{!! Form::radio('priority_id', '4', null,['style'=>"display:initial;"]) !!}</p>   
          <span class="pull-left" style="margin: 0 0 0 1%"><?php echo priority(4); ?></span>
          <p class="pull-left" style="margin: 1% 0 0 5%;" >&nbsp;{!! Form::radio('priority_id', '3', null,['style'=>"display:initial;"]) !!}</p>
          <span class="pull-left" style="margin: 0 0 0 1%;"><?php echo priority(3); ?></span>
          <p class="pull-left" style="margin: 1% 0 0 5%;" >&nbsp;{!! Form::radio('priority_id', '2', null,['style'=>"display:initial;"]) !!}</p>
          <span class="pull-left" style="margin: 0 0 0 1%;"><?php echo priority(2); ?></span>
          <p class="pull-left" style="margin: 1% 0 0 5%;" >&nbsp;{!! Form::radio('priority_id', '1', null,['style'=>"display:initial;"]) !!}</p>
          <span class="pull-left" style="margin: 0 0 0 1%;"><?php echo priority(1); ?></span>


        </div>

      </div>

    </div>
  </div>
</div>
<div class="form-group">

  <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0% auto 0">

    <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">
      <label class="input_icon_fixed pull-left" for="repeat" style="width:50%" ><i class="fa fa-repeat "></i>&nbsp;Repeat</label>
      <div class="pull-left input_box_hidden" style="width:50%;">
       {!! Form::select('repeat', [1=>'Never',2=>'Every Day',3=>'Week Day',4=>'Every Month',5=>'Every Year',6=>'Customized'],1, array('class' => 'form-control pull-left  select_item select2-hidden-accessible GetRepeatOption','id' => 'repeat','style'=>'width:50%;color:#999;height:29px','placeholder'=>'select')) !!}
     </div>

   </div>

 </div>
</div>
</div>
<div class="form-group" id="Taskdue_week"  style="display: none;" > 
  <div class="row" >
   <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0% auto 0">

    <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">

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
</div>
<div class="form-group" id="Task_customized"  style="display: none;height: 30">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0% auto 0">

      <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">
        <div class="pull-left" style="width:50%">

         <label class="input_icon_fixed pull-left" for="frequency" style="width:50%!important;margin-left: 0%" ><i class="fa fa-user "></i>&nbsp;Frequency</label>
         <div class="pull-left input_box_hidden" style="width:50%!important;">
          {!! Form::text('frequency', null, array('class' => 'form-control pull-left input_box_hidden numeric', 'style'=>'width:104%;','id'=>'frequency')) !!}
        </div>
      </div>
      <div class="pull-right"  style="width:50%">


       <label class="input_icon_fixed pull-left" for="interval" style="width:50%!important;margin-left: 0%" ><i class="fa fa-user "></i>&nbsp;Interval</label>
       <div class="pull-left input_box_hidden" style="width:50%!important;">
        {!! Form::text('interval', null, array('class' => 'form-control pull-left input_box_hidden numeric', 'style'=>'width:104%;','id'=>'interval')) !!}
      </div>
      <!-- </div> -->
    </div>


  </div>
</div>
</div>
</div>
<div class="form-group">

  <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0% auto 0">

    <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">
     &nbsp;<label for="upload-photo" class="" style="color:#999">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="photo" id="upload-photo" multiple/>


   </div>

 </div>
</div>
</div>

<div class="form-group">

  <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0% auto 0">

    <div class="col-lg-9 col-md-9 col-sm-9" style="margin: 0 auto ;min-width:30px;padding-left: 0">
      <label class="input_icon" for="tags"><span style="font-style: italic;"></span></label>


      <input name="tags" id="mySingleFieldTags" value="fancy, new, tag, demo">


    </div>

  </div>
</div>
</div>


</div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-success button" style="">Add Task</button>
</div>
{!! Form::close() !!}


<script>


  $(document).ready(function()
  {


    //status=false;
    $(".GetRepeatOption").on("change",function(){
       // 
//
repeat_type=$(this).val();
//console.log(repeat_type);
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
})



    basic_functions();

    //console.log(formData.entries());

    var append_tr_datafields=["priority_id","","task_name","Working","Completed","create_date","end_date","","size_id",""];
    
    basic_functions();
    var formData = new FormData();



    $('.validateform').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
          task_name: { required: true  },

        },

        messages: {
          task_name: { required: "Task name is required."},

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

            localStorage.setItem("password_secret",$('input[name=token]').val());
            inputs={
             "task_name":$('input[name=task_name]').val(),
             "task_details":$('textarea[name=task_details]').val(),
             "project_id":$('select[name=project_id]').val(),
             "priority_id":$('input[name=priority_id]:checked').val(),
             "assigned_by":$('select[name=assigned_by]').val(),
             "assigned_to":$('select[name=assigned_to]').val(),
             "create_date":$('input[name=create_date]').val(),
             "end_date":$('input[name=end_date]').val(),
             "size_id":$('select[name=size_id]').val(),
             "worth_id":$('input[name=worth_id]').val(),
             "repeat":$('input[name=repeat]').val(),
             "frequency":$('input[name=frequency]').val(),
             "interval":$('input[name=interval]').val(),
             "tags":$('input[name=tags]').val(),
             "return_fields":append_tr_datafields,
           }
           $.ajax({
            url: 'http://localhost/propel/api/wfm/AddTask',
            type: 'post',
            headers: {
             'Content-Type': 'application/json',
             'Authorization': 'Bearer ' + localStorage.getItem("password_secret"),
           },
           data:
           JSON.stringify(inputs),
           contentType: false,
           processData: false,
           success:function(data, textStatus, jqXHR) {
            if(data.status==1)
            {

         /*  image_upload.on("sending", function(file, xhr, response) {
           response.append("id", data.status);
           });

           image_upload.processQueue();*/

           $('.loader_wall_onspot').hide();
           $(".task_crud_modal ").modal('hide');
          

           custom_success_msg(data.message);
           //console.log(data.data);
           
           call_back(data.data, `add`, data.message);
                //  $('body #datatable > tbody:last-child').append();
           return false;
         }
       },
       error:function(jqXHR, textStatus, errorThrown) {
                    //alert("New Request Failed " +textStatus);
                  }
                });
         }
       });
    

    /*Tooltip*/
    $('body [data-toggle="tooltip"]').tooltip(); 
    /*close Tooltip*/

    $("select[name=project_id]").on("change",function()
    {
      var pr_id=$(this).val();
      if(pr_id)
      {
        $.ajax({
          url: '{{route('getprojectdetails')}}',
          type: 'post',
          headers:
          { 
          },
          data: {
                    id:pr_id,
                    return_fields:'deadline_date',
                     _token: '{{ csrf_token() }}',
            
                },
         success:function(data, textStatus, jqXHR) {
          if(data.status==1)
          {
              $("#Deadline").html(data.data);
                console.log(data.data);
               return false;
         }
       },
       error:function(jqXHR, textStatus, errorThrown) {
                  }
                });
      }else{
            $("#Deadline").html("");
      }
    });
  });
</script>
