<!-- <div class="bs-modal-lg modal project_actions" id="project_actions" tabindex="-1" role="basic" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content"> -->

      <div class="modal-header">

        <h4 class="modal-title">Project Actions</h4>

      <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>

      <div class="modal-body">

      	  <div class="container-fluid">

{!! Form::open(['class' => 'form-horizontal projectform']) !!}
{{ csrf_field() }}
<div class="form-body" >

    <div class="row">

    	<div class="col-md-12">

    		<div class="form-group" >

    			{!! Form::text('project_name', $projects->project_name, array('class' => 'inputText','id'=> $projects->id,'style'=>'border: 1px solid #ced4da;color: #999;','required')) !!}

    			<!-- <input type="text"  class="inputText" name="project_name" style=" border: 1px solid #ced4da;color: #999; " required/> -->

    			<span class="fa fa-plus-circle floating-label" style="color: #999;"><span>&nbsp;Project Name</span></span>

    		</div>

    	</div>

    </div>

</div>

 <div class="row">

 	<div class="col-md-12">

		 <div class="form-group">

			{!! Form::text('project_details', $projects->project_details, array('class' => 'inputTextArea','rows'=>4,'style'=>' border: 1px solid #ced4da;color: #999;','id'=>'project_details')) !!}

			<span class="fa fa-plus-circle floating-label" style="color: #999;"><span>&nbsp;Project details</span></span>

		  </div>

		</div>

	 </div>



	  <div class="row">

	   <div class="col-xs-6 col-md-6"   >

	   	<div class="input-group" style="    border: 1px solid #ddd;" data-toggle="tooltip" title="" data-original-title="End Date">

		<span class="input-group-addon" for="end_date" style="color: #919191;"><i class="fa fa-calendar-times-o"></i></span>

		<!-- {!! Form::text('end_date', date_($projects->deadline_date), array('class' => 'form-control date-picker to-date-picker', 'data-date-format' => 'dd-mm-yyyy','style'=>'color: #919191;','id'=>'end_date','placeholder'=>'End date')) !!} -->
        {!! Form::select('create_by', $employee_list,$project_owner, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'create_by','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select Project Owner")) !!}

	</div>

	</div>

<div class="col-xs-6 col-md-6">

		<div class="input-group" style="border: 1px solid #ddd;" data-toggle="tooltip" title="" data-original-title="Project Owner">

		  <span class="input-group-addon" for="category" style="color: #919191;"><i class="fa fa-user"></i></span>
<?php /* ?>
		  {!! Form::select('project_owner',$employee_list, $project_owner->id, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','style'=>'width:50%;color:;height:29px','tabindex'=>'-1', 'aria-hidden'=>'true')) !!} 
		  php
<?php */ ?>
			 {!! Form::select('project_owner', $employee_list, null,array('class' => 'select-tag form-control select2-hidden-accessible employeelist', 'id'=>'tags','data-select2-id'=>'10','tabindex'=>'-1', 'aria-hidden'=>'true','multiple')) !!}
		  <!--  <select class="form-control pull-left  select_item select2-hidden-accessible"  style="width:50%;color:;height:29px" type="select" name="project_owner" tabindex="-1" aria-hidden="true"><option  selected disabled hidden >Project Owner</option><option value="3"></option><option value="6"></option><option value="7"></option><option value="8"></option></select> -->

		</div>

	  </div>

	</div>

	<div class="row">

		<div class="col-xs-6 col-md-6"   >

	   	<div class="input-group" style="border: 1px solid #ddd;" data-toggle='tooltip'>

		<span class="input-group-addon"  style="color: #919191;height:30px;"><i class="fa fa-user"></i>&nbsp;Open Issues</span>

		{!! Form::text('open_issues', $open_issues, array('class' => 'inputText issues','style'=>'border: 1px solid #ced4da;color: #999;height:30px;border-left-style: hidden;padding-top:0px !important;padding-left: 8px;','required')) !!}

		</div>

	</div>

<div class="col-xs-6 col-md-6">

		<div class="input-group" style="border: 1px solid #ddd;" data-toggle='tooltip'>

		<span class="input-group-addon"  style="color: #919191;height:30px;"><i class="fa fa-user"></i>&nbsp;project status</span>

		{!! Form::text('project_status', $project_status, array('class' => 'inputText','id'=>'','style'=>'border: 1px solid #ced4da;color: #999;height:30px;border-left-style: hidden;padding-top:0px !important;padding-left: 8px;','required')) !!}

	</div>

	  </div>

	</div>

	<div class="row">
                <div class="col-xs-8 col-md-8" style="margin-left:16px;">

                    <!-- <label for="upload-photo" class="" style="color:#999;cursor: pointer;">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="attachment" id="upload-photo" multiple/> -->
                 
                    <div class="dropzone" id="Project_uploadFilesEdit">
                    </div>
                    <div class="col-xs-10 col-md-10" style="margin-left:16px;">

                    </div>
                </div>
            </div>
            <div class="row" id="project_attachment_container">
                <ul class="tagit ui-widget ui-widget-content ui-corner-all task_attachments" id="attachment_datalist"
                    style="margin: 10px">

                    @if(isset($attachments))
                    @foreach($attachments as $attachment)
                   
                  
					
                    <li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable text-overlap" style="width:100px" data-toggle="tooltip" title=""><i ></i><a class="tagit-label "  target="_blank" href="{{asset('public/attachment')}}/{{$attachment->file_wpath}}" > {{$attachment->file_original_name}} </a><a class="tagit-close"><span class="text-icon del_attach"  data-id="{{$attachment->id}}" data-toggle="confirmation">×</span><span class="ui-icon ui-icon-close"></span></a></li>
                    @endforeach
                    @endif
                </ul>
            </div>

 <div class="row">

 	<div class="col-md-12">

		<div class="form-group">

			<span class="fa fa-plus-circle floating-label" style="color: #999;"><span>&nbsp;Project Comments</span></span>

	<input type="textarea" name="project_comments"  class="inputTextArea" style=" border: 1px solid #ced4da;color: #999; " />

		 </div>

		</div>

	 </div>











</div>

	  </div>

      <div class="modal-footer">

<div class="btn-group float-right">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

        <button type="button" class="btn btn-secondary save"  data-dismiss="modal">Save</button>

        <button type="button" class="btn btn-secondary archive" value="3"  data-value="closed">Close & Archive</button>

        <button type="button" class="btn btn-secondary disable" value="2" data-value="disabled">Disable & archive</button>

        <button type="button" class="btn btn-secondary enable" value="1" data-value="Enabled">Enable & Reopen</button>

    </div>

       </div>

      <!--  </div>

    
  </div>
 
</div> -->
{!! Form::close() !!}
@include('modals.dropzone_preview')
<script>
			var id="{{$id}}";
            var previewNode = document.querySelector("#template");
            previewNode.id = "";
            var previewTemplate = previewNode.parentNode.innerHTML;
            previewNode.parentNode.removeChild(previewNode);
            var Task_attachments = new Dropzone("div#Project_uploadFilesEdit", {
            method: 'POST',
            paramName: 'file',
            url: "{{URL::to('/api/wfm/AddProject')}}/"+id,

            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem("password_secret"),
                contentType: 'json'

            },
            dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",
            clickable: true,
            maxFilesize: 5, // MB
            parallelUploads: 10,
            previewTemplate: previewTemplate,
            previewsContainer: "#project_attachment_container",
            acceptedFiles: "image/*,.xlsx,.xls,.pdf,.doc,.docx,.txt",
            maxFiles: 10,
            uploadMultiple: true,
            autoProcessQueue: false,
            addRemoveLinks: true,
            removedfile: function (file) {
                file.previewElement.remove();
                // $(this).remove();
            },
            queuecomplete: function () {
                Task_attachments.removeAllFiles();
            }, init: function () {
                //	document.querySelector("li.remove-files").onclick = function() {
                //	Task_attachments.removeAllFiles(true);

                this.on('addedfile', function (file) {
                    //console.log(file);
                    $('.dz-remove').hide();
                    //	formData.append('upload_files[]',file);
                    file.previewElement.querySelector(".file-tooltip").setAttribute("data-toggle", "tooltip");
                    file.previewElement.querySelector(".file-tooltip").setAttribute("title", file.name);

                });

                var submitButton = document.querySelector(".save")
                myDropzone = this; // closure

                submitButton.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if ($('.projectform').valid()) {
                        //	console.log()
                        if (Task_attachments.getQueuedFiles().length > 0) {

                            Task_attachments.processQueue(); // Tell Dropzone to process all queued files.
                        } else {

                            var blob = new Blob();
                            blob.upload = {'chunked': myDropzone.defaultOptions.chunking};
                            myDropzone.uploadFile(blob);

                        }
                    }
                });
                this.on('sending', function (file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST

                    inputs = {
                        "project_name": $('input[name=project_name]').val(),
                        "project_details": $('input[name=project_details]').val(),
                        "end_date":$('input[name=end_date]').val(),
                        "project_owner":'',
                        "open_issues":$('input[name=open_issues]').val(),
                        "project_status":$('input[name=project_status]').val(),
                       
                    };
                    formData.append('data', JSON.stringify(inputs));


                });

                this.on("successmultiple", function (file, response) {
                    console.log(response);


                    if (response.status == 1) {
                        console.log("success");
                        $('.loader_wall_onspot').hide();
                        $(".wfm_project_crud_modal").modal('hide');

                        var li_list = response.latest_projects;

                        var htmlData = '';
                        for(var key in li_list) {
                            //console.log(key);
                  
                            //  console.log(key);
                        }

                        $.each(response.last_added_project, function (key, value) {


                            $('body').find("select#project_category_list").append($("<option></option>").attr("value", key).text(value));
                        });
                          custom_success_msg(response.message);


                    }


                });
                this.on("errormultiple", function (files, response) {
                    // 	alert(response);
                    //    this.removeFile(files);
                    // Gets triggered when there was an error sending the files.
                    // Maybe show form again, and notify user of error
                });

            }
        });
		

        $('.projectform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
            

                project_name: {
                    required: true,
                    remote: {
                      url: '{{ route('check_wfm_project') }}',
                        type: "post",
                        data: {
                            _token :$('input[name=_token]').val(),
                            organization_id:$('.GetProjectForm option:selected').val(),
                        }
                    } },
                end_date: {required: true},
               

            },

            messages: {
                project_name: {required: "Project Name is required.", remote: "Project name already exists!" },
                 end_date: {required: "Deadline date is required."},
            },

            invalidHandler: function (event, validator) {
                //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            }

        });
            
</script>