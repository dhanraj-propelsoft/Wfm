{!! Form::open(['class' => 'form-horizontal projectform']) !!}
{{ csrf_field() }}
<div class="modal-header">

    <h4 class="modal-title pull-left">Add Project</h4>


    <?php $return_fields = ['project_category_id' => 'project_categorylist'] ?>
    <div class="ui-select pull-left">
        <div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-d"
             data-iconpos="right" data-theme="c"
             class="ui-btn ui-shadow ui-btn-corner-all ui-btn-icon-right ui-btn-up-c">{!! Form::select('organization_id',$organizations,$organization_id, array('class' => 'form-control GetProjectForm','id' => 'organisation','placeholder'=>"select",'type'=>'select','data-function'=>'getprojectform','data-name'=>'organization_id','data-return'=>json_encode($return_fields),'data-form-id'=>'GetProjectFormData','data-title-class'=>'input-group','style'=>'border:0;    background: none !important;position: relative;
  right: 30px;')) !!}</div>
        <button type="button" class="close pull-right close_model"
                style="margin: -50px -15px -15px auto;">&times;</button>
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
    <div class="container-fluid" id="GetProjectFormData">
        <div class="form-body">

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">

                        {!! Form::hidden( 'token',$apiKey) !!}
                        {!! Form::hidden( 'person_id',Auth::user()->person_id) !!}
                        {!! Form::text('project_name', null, array('class' => 'inputText','id'=>'project_name','style'=>' border: 1px solid #ced4da;color: #999;')) !!}

                        <span class="fa fa-plus-circle floating-label"
                              style="color: #999;"><span>&nbsp;Add Project</span></span>

                        <div class="Adding_project_exist" style="display:none">
                            <p style='color:red;'></p></div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <!--     <input type="textarea"  class="inputTextArea" style=" border: 1px solid #ced4da;color: #999; " required/> -->
                        {!! Form::text('project_details', null, array('class' => 'inputTextArea','rows'=>4,'style'=>' border: 1px solid #ced4da;color: #999;','id'=>'project_details')) !!}
                        <span class="fa fa-plus-circle floating-label"
                              style="color: #999;"><span>&nbsp;Project details</span></span>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-xs-6 col-md-6 form-group">
                    <div class="input-group " style="border: 1px solid #ddd;" data-toggle='tooltip'
                         title="Project Owner">
                        <span class="input-group-addon " for="owner" style="color: #919191;"><i class="fa fa-user"></i></span>

                        {!! Form::select('create_by', $EmployeeList,$project_owner, array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'create_by','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select Project Owner")) !!}

                    </div>
                </div>

                <div class="col-xs-6 col-md-6">
                    <div class="input-group form-group" style="border: 1px solid #ddd;" data-toggle='tooltip'
                         title="{{GetLabelName(Session::get('organization_id'),'Project Deadline')}}">
                        <span class="input-group-addon" for="create_date" style="color: #919191;"><i
                                    class="fa fa-calendar-times-o"></i></span>

                        {!! Form::text('deadline_date', date('d-m-Y'), array('class' => 'form-control accounts-date-picker pull-left input_box_hidden','id'=> 'deadline_date','data-date-format' => 'dd-mm-yyyy','style'=>'width:50%;','placeholder'=>"select date")) !!}

                    </div>
                </div>


            </div>


            <div class="row">


                <div class="col-xs-6 col-md-6">
                  {{--  <div class="form-group" style=" border: 0px;bottom:-1px; ">
                        <label for="upload-photo" class="" style="color:#999;cursor: pointer;">Attachment...<i
                                    class="fa fa-paperclip "></i></label><input type="file" name="attachment"
                       id="upload-photo" multiple/>
                    </div>--}}
                    <div class="col-xs-10 col-md-10" style="margin-left:16px;">
                        <ul class="tagit ui-widget ui-widget-content ui-corner-all" id="attachment_datalist">
                        </ul>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6">
                    <div class="form-group" style="border: 1px solid #ddd;" data-toggle='tooltip'
                         title="{{GetLabelName(Session::get('organization_id'),'project category')}}">
                        <span class="col-md-2" for="category" style="color: #919191;"><i
                                    class="fa fa-cubes"></i></span>

                        {!! Form::select('project_category_id',$project_category_list,null, array('class' => 'form-control col-md-4  select_item select2-hidden-accessible','id' => 'project_category_id','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select","type"=>"select")) !!}

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-8 col-md-8" style="margin-left:16px;">

                    <!-- <label for="upload-photo" class="" style="color:#999;cursor: pointer;">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="attachment" id="upload-photo" multiple/> -->
                    @php $dropzoneId = isset($dz_id) ? $dz_id : "UploadProjectFiles".str_random(8); @endphp
                    <div class="dropzone" id="{{$dropzoneId}}">
                    </div>
                    <div class="col-xs-10 col-md-10" style="margin-left:16px;">

                    </div>
                </div>
            </div>
            <div class="row" id="project_attachment_container">
                <ul class="tagit ui-widget ui-widget-content ui-corner-all task_attachments" id="attachment_datalist"
                    style="margin: 10px">
                </ul>
            </div>


        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary close_model" name="cancel_btn">Cancel</button>
    <button type="button" class="btn btn-secondary " id="form_submit">Add Project</button>
</div>
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
  return(Math.random() * (max - min + 1)) + min
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

// 
            var previewNode = document.querySelector("#template");
            previewNode.id = "";
            var previewTemplate = previewNode.parentNode.innerHTML;
            previewNode.parentNode.removeChild(previewNode);
            // 
            var Task_attachments = new Dropzone({{$dropzoneId}}, {
            method: 'POST',
            paramName: 'file',
            url: "{{URL::to('/api/wfm/AddProject')}}",
// 
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem("password_secret"),
                contentType: 'json'

            },
            // 
            dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",
            clickable: true,
            maxFilesize: 5, // MB
            parallelUploads: 10,
// 
            previewTemplate: previewTemplate,
            previewsContainer: "#project_attachment_container",
            // 
            acceptedFiles: "image/*,.xlsx,.xls,.pdf,.doc,.docx,.txt",
            maxFiles: 10,
            uploadMultiple: true,
            autoProcessQueue: false,
            addRemoveLinks: true,
            removedfile: function (file) {
                // 
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
                    // 
                    file.previewElement.querySelector(".file-tooltip").setAttribute("data-toggle", "tooltip");
                    file.previewElement.querySelector(".file-tooltip").setAttribute("title", file.name);
                    // 

                });

                var submitButton = document.querySelector("#form_submit")
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
                        "deadline_date": $('input[name=deadline_date]').val(),
                        "project_category_id": $('input[name=project_category_id]').val(),
                        "create_by": $('select[name=create_by]').val(),
                        "project_category_id": $('select[name=project_category_id]').val(),

                        "organization_id": $('select[name=organization_id]').val(),
                        "person_id": $('input[name=person_id]').val(),

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
                            htmlData +=  `<li style="display: inline-flex;width: 100%;"><a data-link="job-allocation" class="getproject" id="project_`+li_list['id']+`" data-id="`+li_list[`id`]+`" data-href="
                                    {{ url('wfm/dashboard') }}/`+li_list[`id`]+`" ><span>`+li_list[`project_name`]+`</span></a><span class="count popoverThis" data-html="true" title="<i class='fa fa-pie-chart' style='font-size:40px;text-align: center;color: #666;'><span class='dispaly'>View Summery</span></i><i class='fa fa-folder-open' style='font-size:40px;text-align: center;color: #666;'><span class='display'>Manage Project</span></i>" data-toggle="popover"  data-placement="bottom" data-content="<div><li style='color: #666;'><a>Edit Project</a></li><li style='color: #666;'><a>Close Project</a></li><li style='color: #666;'><a>Archive Project</a></li><li style='color: #666;'><a>Project Log</a></li></div><div></div>"  data-id="projectcount_`+li_list[`project_name`]+`" id="project_popup_`+li_list[`id`]+`"  data-popup-id="project_popup_`+li_list[`id`]+`" style="border-top:#ffab60;"> `+li_list[`count`]+` </span>

                            </li>`;
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
                organization_id: {required: true},


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
                create_by: {required: true},
                project_category_id: {required: true},

            },

            messages: {
                organization_id: {required: "Please select the organisation."},
                project_name: {required: "Project Name is required.", remote: "Project name already exists!" },
                create_by: {required: "Project Owner Name is required."},
                project_category_id: {required: "Project Category Name is required."},

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


        /*select org data*/


    });
</script>
