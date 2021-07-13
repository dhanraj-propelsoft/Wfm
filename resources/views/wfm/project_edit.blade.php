    {!! Form::open(['class' => 'form-horizontal projectform']) !!}
    {{ csrf_field() }}
    <div class="modal-header">

        <h4 class="modal-title pull-left">Project Action</h4>


        <div class="ui-select pull-left">
         
            <button type="button" class="close pull-right close_model"
                    style="margin: -20px -15px -15px auto;">&times;</button>
        </div>

    </div>
    <style type="text/css">
        div > span.fa > span, div label {
            font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        option:first-child {
            display: none;
        }
    </style>

    <div class="modal-body">

    <div class="container-fluid">

    {!! Form::open(['class' => 'form-horizontal projectform']) !!}
    {{ csrf_field() }}
    <div class="form-body" >

    <div class="row">

    <div class="col-md-12">

    <div class="form-group" >

        {!! Form::hidden( 'token',$password_secrets) !!}
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



    <div class="col-xs-6 col-md-6">
        <div class="input-group form-group" style="border: 1px solid #ddd;" data-toggle='tooltip'
             title="{{GetLabelName(Session::get('organization_id'),'Project Deadline')}}">
            <span class="input-group-addon" for="create_date" style="color: #919191;"><i
                        class="fa fa-calendar-times-o"></i></span>

            {!! Form::text('deadline_date', date_($projects->deadline_date), array('class' => 'form-control accounts-date-picker pull-left input_box_hidden','id'=> 'deadline_date','data-date-format' => 'dd-mm-yyyy','style'=>'width:50%;','placeholder'=>"select date")) !!}

        </div>
    </div>
    <div class="col-xs-6 col-md-6 form-group">
        <div class="input-group " style="border: 1px solid #ddd;" data-toggle='tooltip'
             title="Project Owner">
            <span class="input-group-addon " for="owner" style="color: #919191;"><i class="fa fa-user"></i></span>

            {!! Form::select('project_owner',  $employee_list, $projects->created_by, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'create_by','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select Project Owner")) !!}

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

    {!! Form::text('project_status', $project_status, array('class' => 'inputText','id'=>$project_status_id,'style'=>'border: 1px solid #ced4da;color: #999;height:30px;border-left-style: hidden;padding-top:0px !important;padding-left: 8px;','required')) !!}

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

    <input type="textarea" name="project_comments"  class="inputTextArea" style=" border: 1px solid #ced4da;color: #999; " value="{{$projects->project_comments}}" />

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
        localStorage.setItem("password_secret", $('input[name=token]').val());

        function createInput(type, name, id, class1, value, element) {
            var input = document.createElement('input');
            input.type = type;
            input.name = name;
            input.className = class1;
            input.id = id;
            input.value = value;
            $(element).before(input);
        }
        var id;

       function randomRange(min, max) {
      return ~~(Math.random() * (max - min + 1)) + min
    }


        function removeAttachment(ele) {
            //alert("tyest");
            hidden_attachment_id = $(ele).attr("data-id");
            hidden_attachment_type_id = $(ele).attr("data-type-id");
            hidden_attachment_mime_id = $(ele).attr("data-mime-id");
            console.log($(ele).closest("li").remove());
            $("#" + hidden_attachment_id).remove();
            $("#" + hidden_attachment_type_id).remove();
            $("#" + hidden_attachment_mime_id).remove();
        }
        $(document).ready(function () {
      /*      dropzone_id=$(".dropzone").attr('id')+randomRange(1, 20);
            $(".dropzone").attr('id',dropzone_id);
            console.log($('div#'+dropzone_id).length);*/
         //   div_dropzone="div#"+dropzone_id;
           // console.log($(".dropzone").attr('id'));


            $('body [data-toggle="tooltip"]').tooltip();
            //alert();
            var date = new Date();
            date.setDate(date.getDate());

            $("input[name=date]").datepicker({
                todayHighlight: true,
                startDate: date
            });

            $('input[name=project_name]').on('keyup', function () {
                if ($(".Adding_project_exist").length > 0) {
                    $(".Adding_project_exist").delay(1000).fadeOut(350);
                }
            });

            basic_functions();

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
                    'Authorization': 'Bearer ' + $('input[name=token]').val(),
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
                            "end_date":$('input[name=deadline_date]').val(),
                            "project_owner":$('select[name=project_owner] option:selected').val(),
                             "project_status":$('input[name=project_status]').attr('id'),
                             "project_comments":$('input[name=project_comments]').val(),
                           
                        };
                        formData.append('data', JSON.stringify(inputs));


                    });

                    this.on("successmultiple", function (file, response) {
                        console.log(response);


                        if (response.status == 1) {
                            console.log("success");
                            $('.loader_wall_onspot').hide();
                            $(".wfm_crud_modal").modal('hide');
;
                              alert_message(response.message);


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
        });   
    </script>
