

<div class="modal-header">
    <h4 class="modal-title float-right">Add Project </h4>
    <a class="pull-left"  data-dismiss="modal"><i class="fa fa-close "></i></a>
</div>

{!! Form::open(['class' => 'form-horizontal validateform project','enctype'=>'multipart/form-data']) !!}
{{ csrf_field() }}

<div class="modal-body">
    <div class="form-body">

        <div class="row">
            <div class="col-md-10 modal_align">
                <div class="form-group">
                    {!! Form::hidden( 'token',$apiKey) !!}
                    {!! Form::hidden( 'organization_id',Session::get('organization_id')) !!}
                 <!--    <label class="input_icon" for="project_name"><i class="fa fa-plus-circle "></i>&nbsp;Add project</label> -->
                    {!! Form::text('project_name', null, array('class' => 'form-control','id'=>'project_name','style'=>'width: 90%;height:29px')) !!}
                      <span class="fa fa-plus-circle floating-label" style="color: #999;""><span>&nbsp;Task details</span></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 modal_align">
                <div class="form-group">
                    <label class="input_icon"  for="project_details" style="width:90%"><i class="fa fa-info-circle "></i>&nbsp;Project details</label>
                    {!! Form::textarea('project_details', null, array('class' => 'form-control','rows'=>4,'style'=>'width:90%','id'=>'project_details')) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-10 modal_align">
                  <label class="input_icon_fixed pull-left" for="deadline_date" style="width:40%" ><i class="fa fa-calendar "></i>&nbsp;Deadline</label>
                  {!! Form::text('deadline_date', date('d-m-Y'), array('class' => 'form-control accounts-date-picker pull-left input_box_hidden','id'=> 'deadline_date','data-date-format' => 'dd-mm-yyyy','style'=>'width:50%;','placeholder'=>"select date")) !!}
              </div>

          </div>
      </div>
      <div class="form-group">
        <div class="row">
            <div class="col-md-10 modal_align">

                <label class="input_icon_fixed pull-left" for="create_by" style="width:40%" ><i class="fa fa-user "></i>&nbsp;Created by </label>
                <div class="pull-left input_box_hidden" style="width:50%;">
                 {!! Form::select('create_by',$EmployeeList,GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id), array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'create_by','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select")) !!}
             </div> 
         </div>

     </div>
 </div><!-- 
 <div class="form-group">
    <div class="row">
        <div class="col-md-10 modal_align">

            <label class="input_icon_fixed pull-left" for="project_category_id" style="width:40%" ><i class="fa fa-user "></i>&nbsp;Category</label>
            <div class="pull-left input_box_hidden" style="width:50%;">
             {!! Form::select('project_category_id',$project_category_list,Auth::user()->person_id, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'project_category_id','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select")) !!}
         </div> 
     </div>

 </div>
</div>


 -->

<div class="form-group">
    <div class="row">
        <div class="col-md-10 modal_align">

            &nbsp;<label for="upload-photo" class="" style="color:#999">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="upload[]" id="upload-photo" multiple class="upload_files" />
            <div id="file-list-display"> </div>




        </div>

    </div>
        </div><!-- 
        upload-photo -->





    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success button" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-success button" >Add Project</button>
</div>
{!! Form::close() !!}

<script>

   console.log($(".upload_files").length);
   var image_upload = new Dropzone(".upload_files", {
      paramName: 'file',
      url: "{{route('upload.projectfile')}}",
      params: {
          _token: '{{ csrf_token() }}'
      },
      dictDefaultMessage: "Drop or click to upload image",
      clickable: true,
      maxFilesize: 5, // MB
      acceptedFiles: "image/*",
      maxFiles: 10,
      autoProcessQueue: false,
      addRemoveLinks: true,
      removedfile: function(file) {
          file.previewElement.remove();
      },
      queuecomplete: function() {
          image_upload.removeAllFiles();
      }
  });

   $(document).ready(function()
   {
    
      var date = new Date();
    date.setDate(date.getDate());

    $('#deadline_date').datepicker({ 
      todayHighlight: true,
      startDate: date,
      minDate: 0
    });

    
    basic_functions();


   $('.validateform').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            project_name: { required: true  },
            create_by: { required: true  },
            project_category_id: { required: true  },

        },

        messages: {
            project_name: { required: "Project Name is required."},
            create_by: { required: "Create User Name is required."},
            project_category_id: { required: "Project Category Name is required."},

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
          inputs={"project_name":$('input[name=project_name]').val(),
          "project_details":$('textarea[name=project_details]').val(),
          "organization_id":$('input[name=organization_id]').val(),
          "deadline_date":$('input[name=deadline_date]').val(),
          "project_category_id":$('input[name=project_category_id]').val(),
          "create_by":$('select[name=create_by]').val(),
          "project_category_id":$('select[name=project_category_id]').val(),
          /*  "upload_files":formData,*/
      }
      $.ajax({
        url: 'http://localhost/propel/api/wfm/AddProject',
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

             image_upload.on("sending", function(file, xhr, response) {
                 response.append("id", data.status);
             });

             image_upload.processQueue();

             $('.loader_wall_onspot').hide();
             $(".crud_modal").modal('hide');

       // $("body")
       var li_list = data.latest_projects;

       var htmlData = '';
       for(var key in li_list) { 

        htmlData +=  '<li><a data-link="job-allocation" href="#"><span>'+li_list[key]+'</span></a></li>'; 
           //     console.log(htmlData);
       }

       $("body").find("ul.project_list").html(htmlData);
       $.each(data.last_added_project, function(key, value) {   


         $('body').find("select#project_category_list").append($("<option></option>").attr("value",key).text(value)); 
         });
       custom_success_msg(data.message);

       return false;
   }
},
error:function(jqXHR, textStatus, errorThrown) {
                    //alert("New Request Failed " +textStatus);
                }
            });
  }
});
});
</script>
