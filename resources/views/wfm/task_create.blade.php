<style type="text/css">
    div > span.fa > span, div label {
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    select, option, input, textarea, select span {
        color: #919191;
    }

    p.pull-left.priority_option:nth-child(0) {
        margin: 3% 0 0 27%
    }

    p.pull-left.priority_option:not(:nth-child(0)) {
        margin: 3% 0 0 5%
    }

    .select2-container {
        height: 100% !important;
    }

    .control-label:after {
        content: "*";
        color: red;
        font-size: x-large;
    }

    /* ul  li.tagit-choice:hover {
        border: 1px solid rgba(200, 200, 200, 0.8);
        background-color: rgba(255, 255, 255, 0.8);
    }*/
    ul li.tagit-choice {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100px;
    }

    ul.tagit {
        padding: 1px 0px;
    }

    .dropzone {
        padding: 0;
        min-height: 20px;
    }
</style>

{!! Form::open(['class' => 'form-horizontal validateform','enctype' => 'multipart/form-data']) !!}
{{ csrf_field() }}
<div class="modal-header">
    <?php $return_fields = ['project_id' => 'projects', 'assigned_by' => 'employeelist', 'assigned_to' => 'employeelist', 'size_id' => 'sizelist'] ?>
    <h4 class="modal-title pull-left ">Add Task</h4>

    <div class="ui-select pull-left" style="">
        <div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-d"
             data-iconpos="right" data-theme="c"
             class="ui-btn ui-shadow ui-btn-corner-all ui-btn-icon-right ui-btn-up-c">{!! Form::select('organization_id',$organizations,$organization_id, array('class' => 'form-control GetProjectCategory ','id' => 'task_organisation','placeholder'=>"select",'type'=>'select','data-function'=>'gettaskform','data-name'=>'organization_id','data-return'=>json_encode($return_fields),'data-form-id'=>'GetTaskFormData','data-title-class'=>'task_tooltip','style'=>'border:0;    background: none !important;position: relative;
	right: 30px;')) !!}</div>
        <button type="button" class="close pull-right close_model"
                style="margin: -50px -15px -15px auto;">&times;</button>
        </br>
    </div>
    <div class="alert alert-danger">The end date not before the start date.</br>
        <center>Please enter the valid end date.</center>
    </div>
    <div class="task_exist" style="display:none">
        <p style='color:red;'></p></div>

</div>


<div class="modal-body ">
    <div class="container-fluid" id="GetTaskFormData">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::hidden( 'token',$apiKey) !!}
                        <input type="hidden" name="person_id" value="<?php  echo Auth::user()->person_id;?>"/>
                        <input type="hidden" name="organisation_session_id"
                               value="<?php echo Session::get('organization_id'); ?>">
                        {!! Form::text('task_name',null, array('class' => 'inputText','id'=>'task_name','style'=>'color: #999;','required')) !!}
                        <span class="fa fa-plus-circle floating-label " style="color: #999;"><span class="control-label">&nbsp;Task To Do <i>(task)</i></span>
                        </span>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">

                        {!! Form::text('task_details', null, array('class' => 'inputTextArea'  )) !!}
                        <span class="fa fa-plus-circle floating-label" style="color: #999;"><span class="">&nbsp;Task details</span></span>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-xs-6 col-md-6 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'Under the project')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon " for="project_type" style="color: #919191;"><i
                                    class="fa fa-cubes"></i><span class="control-label"></span></span>
                        {!! Form::select('project_id', $projects,null, array('class' => 'form-control','id' =>'project_id','placeholder'=>"Under The Project","type"=>"select",'color'=>'red')) !!}
                    </div>
                </div>

                <div class="col-xs-6 col-md-6 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'deadline')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                    <span class="input-group-addon" for="Deadline" style="color: #919191;"><i
                                    class="fa fa-calendar"></i></span><label id="Deadline"
                        style="color:#999;margin:auto 0">Lifetime</label>


                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-xs-6 col-md-6 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'assigned by')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="created_by" style="color: #919191;"><i
                                    class="fa fa-user"></i></span>

                        {!! Form::select('assigned_by',$EmployeeList,GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id), array('class' => 'form-control','id' => 'assigned_by','placeholder'=>"select",'type'=>'select')) !!}
                    </div>
                </div>
                <?php if($TaskCount > 0){ ?>
                <div class="col-xs-5 col-md-5 task_tooltip ChangeDivattr" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'assigned to')}}" id="getTaskCount">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="assign_to" style="color: #919191;"><i
                                    class="fa fa-user"></i></span>
                        <?php $return_fields = ['task_count' => 'taskcount'] ?>

                        {!! Form::select('assigned_to',$EmployeeList,GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id), array('class' => 'form-control','id' => 'assigned_to','placeholder'=>"select",'type'=>'select','data-return'=>json_encode($return_fields),'data-function'=>'gettaskcount','data-change-attr'=>'ChangeDivattr')) !!}
                    </div>
                </div>
                <div class="col-xs-1 col-md-1 " data-toggle="tooltip" title=""
                     style="border: 1px solid #ddd;text-align: left;display:  block;max-width: 5% !important;"
                     data-original-title="No of Tasks" id="task_count" style="" aria-describedby="tooltip">
                    <span class="input-group-addon" for="assign_to"
                          style="color: #919191;text-align: center;margin: 0 auto;padding: 5px 0px 0px 0px;">{{$TaskCount}}</span>
                </div>
                <?php }else{ ?>

                <div class="col-xs-6 col-md-6 task_tooltip ChangeDivattr" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'assigned to')}}" id="getTaskCount">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="assign_to" style="color: #919191;"><i
                                    class="fa fa-user"></i></span>


                        {!! Form::select('assigned_to',$EmployeeList,GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id), array('class' => 'form-control','id' => 'assigned_to','placeholder'=>"select",'type'=>'select','data-return'=>json_encode($return_fields),'data-function'=>'gettaskcount','data-change-attr'=>'ChangeDivattr')) !!}
                    </div>
                </div>
                <div class="col-xs-1 col-md-1 " data-toggle="tooltip" title=""
                     style="border: 1px solid #ddd;text-align: left;display:  none;max-width: 5% !important;"
                     data-original-title="No of Tasks" id="task_count" style="" aria-describedby="tooltip">
                    <span class="input-group-addon" for="assign_to"
                          style="color: #919191;text-align: center;margin: 0 auto;padding: 5px 0px 0px 0px;">{{$TaskCount}}</span>
                </div>
                <?php } ?>
            </div>

            <div class="row">

                <div class="col-xs-6 col-md-6 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'Create date')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="start_date" style="color: #919191;"><i
                                    class="fa fa-calendar"></i></span>
                        {!! Form::text('create_date', date('d-m-Y'), array('class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'create_date','style'=>'color: #919191;','placeholder'=>'Start date','data-date-format' => 'dd-mm-yyyy')) !!}
                    </div>
                </div>

                <div class="col-xs-6 col-md-6 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'Due date')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="Due date" style="color: #919191;"><i
                                    class="fa fa-calendar"></i></span>
                        <!-- <input id="Due date"  placeholder="Due Date" class="form-control to-date-picker" name="due_date" style="color: #919191;" data-date-format = 'dd-mm-yyyy'> -->
                        {!! Form::text('end_date', date('d-m-Y'), array('class' => 'form-control date-picker to-date-picker', 'data-date-format' => 'dd-mm-yyyy','style'=>'color: #919191;','id'=>'end_date','placeholder'=>'End date')) !!}

                    </div>
                </div>
            </div>

             <div class="row">

                <div class="col-xs-6 col-md-6 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'Size')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="size" style="color: #919191;"><i
                                    class="fa fa-user"></i></span>

                        {!! Form::select('size_id',$Sizes,null, array('class' => 'form-control','id' => 'size_id','placeholder'=>"select",'type'=>'select')) !!}

                    </div>
                </div>

                <div class="col-xs-6 col-md-6 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'Worth')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-user"></i></span>
                        {!! Form::text('worth_id', null, array('class' => 'form-control','style'=>'color: #919191;','id'=>'worth_id')) !!}
                    </div>
                </div>
            </div> 

            <div class="row">

                <div class="col-xs-8 col-md-8 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'priority')}}">
                    <div class="input-group" id="priority_group">
                        <div class="input_icon_fixed pull-left " style="width:100%">
                            <span class="pull-left " style="vertical-align: middle;    top: 10px;position: relative;"><i
                                        class="fa fa-list "></i>&nbsp;Priority</span>
                            @if($Priority)

                                @foreach($Priority as $id=>$priority_type)
                                    @if($id!=2)
                                        <?php $value = ""; ?>
                                    @else
                                        <?php $value = $id; ?>

                                    @endif
                                    <p class="pull-left priority_option" style="">
                                        &nbsp;{!! Form::radio('priority_id', $id, $value,['style'=>"display:initial;"]) !!}</p>
                                    <span class="pull-left" style="margin: 0 0 0 1%"><?php echo priority($id); ?></span>
                                @endforeach
                            @endif


                        </div>


                    </div>
                </div>

                <div class="col-xs-3 col-md-3 pull-right" style="margin: 0 0 0 -1%">
                    <div class="input-group">
                        <p style="margin-top:6px"> No of days:&nbsp;</p>

                        <div class="btn_round days pull-left calculate btn-secondary-round" style="position: inherit;">
                            0
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
										<div class="col-xs-12 col-md-12">
											<div class="input-group task_tooltip" style="    border: 1px solid #ddd;" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'repeat')}}">
												<span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-repeat"></i>&nbsp;Repeat</span>		 
												{!! Form::select('repeat', [''=>'Never',2=>'Every Day',3=>'Week Days',4=>'Every Month',5=>'Every Year',6=>'Customized'],1, array('class' => 'form-control pull-left  select_item select2-hidden-accessible GetRepeatOption','id' => 'repeat','style'=>'width:50%;color:#999;height:29px','placeholder'=>'select')) !!}


                    </div>
                </div>
            </div> -->

            <div class="form-group" id="Taskdue_week" style="display: none;">
                <div class="row">
                    <div class="col-md-10 modal_align">

                        <div class="weekDays-selector" style="margin: 0 3%;">
                            <input type="checkbox" id="weekday-mon" class="weekday"/>
                            <label for="weekday-mon">M</label>
                            <input type="checkbox" id="weekday-tue" class="weekday"/>
                            <label for="weekday-tue">T</label>
                            <input type="checkbox" id="weekday-wed" class="weekday"/>
                            <label for="weekday-wed">W</label>
                            <input type="checkbox" id="weekday-thu" class="weekday"/>
                            <label for="weekday-thu">T</label>
                            <input type="checkbox" id="weekday-fri" class="weekday"/>
                            <label for="weekday-fri">F</label>
                            <input type="checkbox" id="weekday-sat" class="weekday"/>
                            <label for="weekday-sat">S</label>
                            <input type="checkbox" id="weekday-sun" class="weekday"/>
                            <label for="weekday-sun">S</label>
                        </div>

                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-8 col-md-8">
                    <div class="form-group" id="Taskdue_date" style="display: none;height: 29px">
                        <div class="input-group" style="    border: 1px solid #ddd;">
                            <span class="input-group-addon  " for="worth" style="color: #919191;"><i
                                        class="fa fa-calendar"></i>&nbsp;Task Date</span>

                            {!! Form::text('task_date', null, array('class' => 'form-control accounts-date-picker pull-left input_box_hidden', 'data-date-format' => 'dd-mm-yyyy','placeholder'=>"select date",'id'=>'task_date')) !!}

                        </div>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-xs-12 col-md-12 task_tooltip" data-toggle='tooltip'
                     title="{{GetLabelName(Session::get('organization_id'),'tags')}}">
                    <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-tag"></i>&nbsp;Tags</span>

                        {!! Form::select('task_tag', [], null,array('class' => 'select-tag form-control select2-hidden-accessible', 'data-date-format' => 'dd-mm-yyyy','id'=>'tags','data-select2-id'=>'10','tabindex'=>'-1', 'aria-hidden'=>'true','multiple')) !!}
                    </div>


                </div>
            </div>


            <div class="row">
                <div class="col-xs-8 col-md-8" style="margin-left:16px;">

                    <!-- <label for="upload-photo" class="" style="color:#999;cursor: pointer;">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="attachment" id="upload-photo" multiple/> -->
                    <div class="dropzone" id="UploadTaskFiles">
                    </div>
                    <div class="col-xs-10 col-md-10" style="margin-left:16px;">

                    </div>
                </div>
            </div>
            <div class="row" id="attachment_container">
                <ul class="tagit ui-widget ui-widget-content ui-corner-all task_attachments" id="attachment_datalist"
                    style="margin: 10px">
                </ul>
            </div>


        </div>
    </div>
</div>
</span>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary close_model">Cancel</button>
    <button type="button" class="btn btn-secondary " id="task_submit" style="">Add Task</button>
</div>
{!! Form::close() !!}

        <!-- Dropzone Preview Element -->
@include('modals.dropzone_preview')

        <!-- /Dropzone Preview Element -->


<script>
    console.log(document.querySelector("#template").length);

    function DateValidation(from_date_id, toDateId) {
        $("#" + from_date_id).datepicker({
            numberOfMonths: 2,
            onSelect: function (selected) {
                $("#" + toDateId).datepicker("option", "minDate", selected)
            }

        });
        $("#" + toDateId).datepicker({
            numberOfMonths: 2,
            onSelect: function (selected) {
                $("#" + from_date_id).datepicker("option", "maxDate", selected)
            }
        });
    }

    var colors = ["#00ffff", "#f0ffff", "#f5f5dc", "#A05C4E", "#0000ff",
        "#a52a2a", "#00ffff", "#00008b", "#008b8b", "#a9a9a9", "#006400", "#bdb76b",
        "#8b008b", "#556b2f", "#ff8c00", "#9932cc", "#8b0000", "#e9967a", "#9400d3",
        "#ff00ff", "#ffd700", "#008000", "#4b0082", "#f0e68c", "#add8e6", "#e0ffff",
        "#90ee90", "#d3d3d3", "#ffb6c1", "#ffffe0", "#00ff00", "#ff00ff", "#800000",
        "#000080", "#808000", "#ffa500", "#ffc0cb", "#800080", "#800080", "#ff0000",
        "#c0c0c0", "#ffffff", "#ffff00"];


    //getValues();

    $(document).ready(function () {
        localStorage.setItem("password_secret", $('input[name=token]').val());


        $(".select-tag").select2({
            tags: true,
            tokenSeparators: [',', ' '],
            templateSelection: function (data, container) {
                var selection = $('.select-tag').select2('data');
                var idx = selection.indexOf(data);

                //console.log(">>Selection",data.text, data.idx, idx);
                data.idx = idx;

                $(container).css("background-color", colors[data.idx]);
                return data.text;
            },
        })


        var date = new Date();
        date.setDate(date.getDate());

        $('#create_date').datepicker({
            todayHighlight: true,
            startDate: date,

            //	console.log(selected);
        }).on("change", function () {

            $("#end_date").datepicker("option", "minDate", $("input[name=end_date]").datepicker('getDate'));
            //	alert();
        });

        basic_functions();

        //DateValidation("create_date","end_date");


        function calculate(d1, d2) {
            var oneDay = 24 * 60 * 60 * 1000;
            var diff = 0;
            if (d1 && d2) {

                diff = Math.round(Math.abs((d2.getTime() - d1.getTime()) / (oneDay)));
            }

            return diff;

        }

//create_date
//end_date
        $("input[name=create_date],input[name=end_date]").on("change", function () {
            var startDate = $("input[name=create_date]").val();
            var endDate = $("input[name=end_date]").val();
            //alert(new Date(startDate));

            var parts = endDate.split("-");

            var sd = new Date(parseInt(parts[2], 10),
                    parseInt(parts[1], 10) - 1,
                    parseInt(parts[0], 10))
//alert(Date.parse(sd));


            var parts2 = startDate.split("-");

            var ed = new Date(parseInt(parts2[2], 10),
                    parseInt(parts2[1], 10) - 1,
                    parseInt(parts2[0], 10))

            if ((Date.parse(sd) < Date.parse(ed))) {
                //alert("Start date should be Less than End date");
                $('.alert-danger').show();
                $('input[name=end_date]').val(" ");
                $(".calculate").text("0");
            }
            else {
                $(".calculate").text(calculate($("input[name=create_date]").datepicker('getDate'), $("input[name=end_date]").datepicker('getDate')));
                $('.alert-danger').hide();
            }
        });


        //status=false;
        $(".GetRepeatOption").on("change", function () {
            //
//
            repeat_type = $(this).val();
            if (repeat_type == 3) {
                $("#Taskdue_week").css("display", "block")
                $("#Taskdue_date").css("display", "none");
            } else if (repeat_type == 4 || repeat_type == 5 || repeat_type == 6) {
                $("#Taskdue_week").css("display", "none");
                $("#Taskdue_date").css("display", "block");

                if (repeat_type == 6) {
                    $("#Task_customized").css("display", "block");
                } else {
                    $("#Task_customized").css("display", "none");
                }

            } else {
                $("#Taskdue_date").css("display", "none");
                $("#Task_customized").css("display", "none");

                $("#Taskdue_week").css("display", "none");
            }
        });


        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var Task_attachments = new Dropzone("div#UploadTaskFiles", {
            method: 'POST',
            paramName: 'file',
            url: "{{URL::to('/api/wfm/AddTask')}}",

            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem("password_secret"),
                contentType: 'json'

            },
            dictDefaultMessage: "Attachment...<i class='fa fa-paperclip '></i>",
            clickable: true,
            maxFilesize: 5, // MB
            parallelUploads: 10,
            previewTemplate: previewTemplate,
            previewsContainer: "#attachment_container",
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

                var submitButton = document.querySelector("#task_submit")
                myDropzone = this; // closure

                submitButton.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if ($('.validateform').valid()) {
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


                        "task_name": $('input[name=task_name]').val(),
                        "task_details": $('input[name=task_details]').val(),
                        "project_id": $('select[name=project_id]').val(),
                        "priority_id": $('input[name=priority_id]:checked').val(),
                        "assigned_by": $('select[name=assigned_by]').val(),
                        "assigned_by_name": $('select[name=assigned_by]').find("option:selected").text(),
                        "assigned_to": $('select[name=assigned_to]').val(),
                        "assigned_to_name": $('select[name=assigned_to]').find("option:selected").text(),
                        "create_date": $('input[name=create_date]').val(),
                        "end_date": $('input[name=end_date]').val(),
                        "size_id": $('select[name=size_id]').val(),
                        "worth_id": $('input[name=worth_id]').val(),
                        "repeat": $('input[name=repeat]').val(),
                        "frequency": $('input[name=frequency]').val(),
                        "interval": $('input[name=interval]').val(),
                        "tags": $('input[name=tags]').val(),
                        /*"return_fields":append_tr_datafields,
                         "attachments":attachment_data,*/

                        "organization_id": $('select[name=organization_id]').val(),
                        "person_id": $('input[name=person_id]').val(),
                        "tags": $('select[name=task_tag]').val(),
                    };
                    formData.append('data', JSON.stringify(inputs));


                });

                this.on("successmultiple", function (file, response) {
                    console.log(response);

                    if (response.status == 0) {
                        custom_validator_msg(response.message);

                        $('.loader_wall_onspot').hide();
                        $('.task_exist').show();
                        $('.task_exist').append(`<
                        div > < p
                        style = 'color:red;' >`+response.message +`</
                        p > < / div >`)

                        return false;
                    }

                    if (response.status == 1) {
                        console.log("success");
                        $('.loader_wall_onspot').hide();
                        $(".wfm_crud_modal").modal('hide');
                        custom_success_msg(response.message);
                        var pro_id = parseInt(response.Project_id);
                        if ($(".project_popup_" + pro_id).length > 0) {
                            var count = $(".project_popup_" + pro_id).text();
                            count = parseInt(count) + parseInt(1);
                            $(".project_popup_" + pro_id).text(count);
                        } else {
                            if ($(".project_list").find('li').length < 5) {

                                if (response.UserTask == 1) {
                                    var li_data = `<
                                    li
                                    style = "display: inline-flex;width: 100%;" > < div
                                    style = "width:203px" > < a
                                    data - link = "job-allocation"
                                class
                                    = "getproject selected"
                                    id = "project_`+pro_id+`"
                                    data - id = "`+pro_id+`"
                                    data - href = "{{ url('wfm/dashboard') }}/`+organisation_id+`/`+pro_id+`"
                                    data - org - id = "`+organisation_id+`" > < span
                                class
                                    = "pull-left text-overlap"
                                    style = "width:140px"
                                    data - toggle = 'tooltip'
                                    title = "`+response.UserProject+`" >`+response.UserProject +`</
                                    span > < / a > < span
                                class
                                    = "count project_popup_`+pro_id+` pull-right"
                                    data - html = "true"
                                    data - id = "projectcount_`+pro_id+` "
                                class
                                    = ""
                                    data - popup - id = "project_popup_`+pro_id+`"
                                    style = "border-top:#ffab60;" > 1 < / span > < / div > < / li >`;
                                    $('.project_list').append(li_data);
                                    console.log(li_data);
                                }
                            }

                        }
                    }


                    if (response.UserTask == 1) {
                        if ($('.task_user_' + response.UserEm_Id).length > 0) {
                            count = $('.task_user_' + response.UserEm_Id + ' span.task_user_count').text();
                            count = parseInt(count) + parseInt(1);
                            $(".task_user_4 span.task_user_count" + response.UserEm_Id).text(count);
                        } else {

                            if ($(".org_user_list").find('li').length < 5) {
                                if (response.UserTask == 1) {
                                    var li_data =`<
                                    li
                                class
                                    = "getTaskByO_U task_user_`+response.UserEm_Id+`"
                                    data - id = "`+organisation_id+`"
                                    data - u - id = "`+response.UserEm_Id+`"
                                    style = "height:20px" > < a
                                class
                                    = " " > < div
                                    style = "padding: 0 0 0 2px;width:140px"
                                class
                                    = "text-overlap org_user_`+response.UserEm_Id+`" >`+response.Name_User +`</
                                    div > < span
                                class
                                    = "count"
                                    style = "float: right;position: relative;top: 6px; right: 4px;" > < span
                                class
                                    = "task_user_count"
                                    style = "text-align: center" > 1 < / span > < / span > < / a > < / li >`;

                                    $('.org_user_list').append(li_data);
                                    console.log(li_data);
                                }
                            }

                        }
                    }


                    
                    var current_org_id = $("input[name=organisation_session_id]").val();

                    if (current_org_id == response.org_id) {
                        //call_back(response.data, `add`, data.message);

                    }
                    return false;
                    /*    $.each(response.uploaded_files, function(id, name) {

                     attachment=response.attachment_path[id];
                     attach_url=JSON.parse(attachment);
                     var filePath =
                    {!! json_encode(asset('public/attachment')) !!}+'/'+attach_url;
                     type = name.split(".")[1];
                     });*/
                });
                this.on("errormultiple", function (files, response) {
                    // 	alert(response);
                    //    this.removeFile(files);
                    // Gets triggered when there was an error sending the files.
                    // Maybe show form again, and notify user of error
                });

            }
        });

        
        var validator=$('.validateform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                organization_id: {required: true},
                task_name: {
                    required: true,
                
                },
                project_id: {
                            required: true,
                            
                            },
                priority_id: {required: true},
                assigned_by: {required: true},
                assigned_to: {required: true},
                create_date: {required: true},
                end_date: {required: true}

            },

            messages: {
                organization_id: {required: "Please select the organisation."},

                project_id: {required: "Project is required."},
                priority_id: {required: "Priority is required."},
                assigned_by: {required: "Assigner name is required."},
                assigned_to: {required: "Assigne name is required."},
                create_date: {required: "Start date is required."},
                end_date: {required: "End date is required."},
                task_name: {
                    required: "Task Name is required.",
                    remote: "Task Name is already exists!"
                },


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
            },
            /*
             submitHandler: function(form) {

             $('.loader_wall_onspot').show();

             localStorage.setItem("password_secret",$('input[name=token]').val());
             //console.log($("input[name='attachment_multiple[]']").val());

             var attachment_data = $(".attachment_multiple").map(function(){
             return this.value;
             }).get();
             //console.log(attachment_data);
             var apiKey=$('input[name=token]').val();

             var append_tr_datafields=["priority_id","","task_name","project_name","first_name","end_date","status",""];
             organisation_id=$('select[name=organization_id]').val();
             localStorage.setItem("password_secret",apiKey)
             inputs={
             "task_name":$('input[name=task_name]').val(),
             "task_details":$('input[name=task_details]').val(),
             "project_id":$('select[name=project_id]').val(),
             "priority_id":$('input[name=priority_id]:checked').val(),
             "assigned_by":$('select[name=assigned_by]').val(),
             "assigned_by_name":$('select[name=assigned_by]').find("option:selected").text(),
             "assigned_to":$('select[name=assigned_to]').val(),
             "assigned_to_name":$('select[name=assigned_to]').find("option:selected").text(),
             "create_date":$('input[name=create_date]').val(),
             "end_date":$('input[name=end_date]').val(),
             "size_id":$('select[name=size_id]').val(),
             "worth_id":$('input[name=worth_id]').val(),
             "repeat":$('input[name=repeat]').val(),
             "frequency":$('input[name=frequency]').val(),
             "interval":$('input[name=interval]').val(),
             "tags":$('input[name=tags]').val(),
             "return_fields":append_tr_datafields,
             "attachments":attachment_data,

             "organization_id":$('select[name=organization_id]').val(),
             "person_id":$('input[name=person_id]').val(),
             "tags":$('select[name=task_tag]').val(),
             }

             $.ajax({
             url: '{{URL::to('/api/wfm/AddTask')}}',

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
             console.log(data);
             if(data.status==0){
             custom_validator_msg(data.message);

             $('.loader_wall_onspot').hide();
             $('.task_exist').show();
             $('.task_exist').append(`<div><p style='color:red;'>`+data.message+`</p></div>`)

             return false;
             }

             if(data.status==1)
             {
             console.log("success");
             $('.loader_wall_onspot').hide();
             $(".wfm_crud_modal").modal('hide');

             var pro_id=parseInt(data.Project_id);
             if($(".project_popup_"+pro_id).length>0)
             {
             var count=$(".project_popup_"+pro_id).text();
             count=parseInt(count)+parseInt(1);
             $(".project_popup_"+pro_id).text(count);
             }else{
             if($(".project_list").find('li').length<5)
             {

             if(data.UserTask==1)
             {
             var li_data= `<li style="display: inline-flex;width: 100%;"><div style="width:203px"><a data-link="job-allocation" class="getproject selected" id="project_`+pro_id+`" data-id="`+pro_id+`" data-href="{{ url('wfm/dashboard') }}/`+organisation_id+`/`+pro_id+`"  data-org-id="`+organisation_id+`"><span class="pull-left text-overlap" style="width:140px" data-toggle='tooltip' title="`+data.UserProject+`">`+data.UserProject+`</span></a><span class="count project_popup_`+pro_id+` pull-right" data-html="true"   data-id="projectcount_`+pro_id+` " class=""  data-popup-id="project_popup_`+pro_id+`" style="border-top:#ffab60;"> 1 </span></div></li>`;
             $('.project_list').append(li_data);
             console.log(li_data);
             }
             }

             }
             }




             if(data.UserTask==1)
             {
             if($('.task_user_'+data.UserEm_Id).length>0){
             count=$('.task_user_'+data.UserEm_Id+' span.task_user_count').text();
             count=parseInt(count)+parseInt(1);
             $(".task_user_4 span.task_user_count"+data.UserEm_Id).text(count);
             }else{

             if($(".org_user_list").find('li').length<5)
             {
             if(data.UserTask==1)
             {
             var li_data=`<li class="getTaskByO_U task_user_`+data.UserEm_Id+`"  data-id="`+organisation_id+`" data-u-id="`+data.UserEm_Id+`" style="height:20px"><a class=" " ><div style="padding: 0 0 0 2px;width:140px" class="text-overlap org_user_`+data.UserEm_Id+`">`+data.Name_User+`</div><span class="count" style="float: right;position: relative;top: 6px; right: 4px;"><span class="task_user_count" style="text-align: center"> 1 </span></span></a></li>`;

             $('.org_user_list').append(li_data);
             console.log(li_data);
             }
             }

             }
             }




             custom_success_msg(data.message);
             var current_org_id=$("input[name=organisation_session_id]").val();

             if(current_org_id==data.org_id)
             {
             call_back(data.data, `add`, data.message);

             }
             return false;
             },
             error:function(jqXHR, textStatus, errorThrown) {
             }
             });
             }*/
        });


    });
/*    $('select[name=project_id]').on('change', function() {
             $("#task_name").removeData("previousValue"); 
             $("#project_id").removeData("previousValue");
             $('.validateform').data('validator').element('#task_name');
                 });
*/
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


    function removeAttachment(ele) {
        //alert("tyest");
        hidden_attachment_id = $(ele).attr("data-id");
        hidden_attachment_type_id = $(ele).attr("data-type-id");
        hidden_attachment_mime_id = $(ele).attr("data-mime-id");
        console.log($(ele).closest("li").remove());
        $("#" + hidden_attachment_id).remove();

    }
    /*
     function append_table_data(attribute_array="" , td_data_array)
     {
     tr_attribute="";

     if(attribute_array!="")
     {
     attr_data="";
     if(Array.isArray(attribute_array)==true){

     $.each(attribute_array, function( index, value ) {

     attr_data= index+"='"value"'";

     });

     }


     }

     tr_data="";
     tr_data="<tr "+tr_attribute+">"
     if(Array.isArray(td_data_array)==true){

     $.each(td_data_array, function( index, value ) {

     td_data="<td>"+value+"</td>";

     });

     }

     }

     */
    $(document).ready(function () {


        $('body [data-toggle="tooltip"]').tooltip();


        $('input[name="attachment"]').on('change', function (e) {
            e.preventDefault();
            var reader = new FileReader();
            if (e.target.files && e.target.files.length > 0) {
                reader.readAsDataURL(e.target.files[0]);
                reader.onload = function () {
                    fileName = e.target.files[0].name;
                    fileType = e.target.files[0].type;
                    fileExtension = fileName.split('.').pop();
                    id = $(".attachment").length;
                    id = id + 1;
                    input_value = reader.result.split(',')[1];
                    element = $('input[name=attachment]');

                    createInput("hidden", "attachment_multiple[]", "attachment_" + id, "attachment_multiple", input_value, element);
                    $("#attachment_datalist").append('<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable"><span class="tagit-label">' + fileName + '</span><a class="tagit-close"><span class="text-icon" onclick="removeAttachment(this)" data-id="attachment_' + id + '">×</span><span class="ui-icon ui-icon-close"></span></a></li>');

                };
            }


        });


        $('input[name=task_name]').on('keyup', function () {
            if ($(".task_exist").length > 0) {
                $(".task_exist").delay(1000).fadeOut(350);
            }
        });


        $("select[name=project_id]").on("change", function () {
            var pr_id = $(this).val();
            var org_id = $('select[name=organization_id]').val();
            if (pr_id == 0 || pr_id == "") {
                $("#Deadline").html('Lifetime');
                return false;
            }

            if (pr_id || pr_id != 0) {
                //console.log(pr_id);
                $.ajax({
                    url: '{{route('getprojectdetails')}}',
                    type: 'post',
                    headers: {},
                    data: {
                        id: pr_id,
                        org_id: org_id,
                        return_fields: 'deadline_date',
                        _token: '{{ csrf_token() }}',

                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.status == 0) {
                            $('.loader_wall_onspot').hide();
                            $('.task_exist').show();
                            $('.task_exist').append(`<
                            div > < p
                            style = 'color:red;' >`+data.message +`</
                            p > < / div >`)
                        }

                        if (data.status == 1) {
                            $("#Deadline").html(data.data);
                            //console.log(data.data);
                            $("#tags").empty();

                            selectValues = data.tags;
                            //console.log(selectValues);
                            $.each(selectValues, function (key, value) {
                                $("#tags")
                                        .append($("<option></option>")
                                                .attr("value", key)
                                                .text(value));
                            });
                            return false;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    }
                });
            } else {
                $("#Deadline").html("");
            }
        });

        function append_tr_data(data) {
            /*	tr_data="";
             tr_data+ ="<tr class='popUp get_detailsbar' data-id='"+data.task_id+"' data-org-id='"+data.organization_id+"' id='"+data.task_id+"' data-pro-id='"+data.task_id+"' data-activity-log='/org_"+data.organization_id+"/pro_"+data.project_id+"/task_43' data-token="" role='row'>";
             tr_data+ =" <td data-sort='"+data.priority_id+"'>"+data.priority+"</td>";

             tr_data+ ="<td></td>";
             tr_data+ ="<td>"+data.employeeName+"</td>";

             tr_data+ ="<td>"+data.create_date+"</td>";
             tr_data+ ="<td>"+data.end_date+"</td>";
             tr_data+ ="<td></td>";
             tr_data+ ="<td>"+data.size_id+"</td>";
             tr_data+ ="<td></td>";
             tr_data+ ="<td></td>";
             tr_data+ ="</tr>";*/

            /*
             <td data-sort="2"><div class="pull-left" data-toggle="tooltip" title="" data-original-title="Normal"><i class="fa fa-chevron-up " style="; position: relative;display: inherit;top: 6.5px;color:#007bff"></i><i class="fa fa-chevron-up priority_medium" style=" position: relative;top: -0.5px;color:#007bff"></i></div></td>

             <td class="sorting_1">Manimaran Paid 5000</td>
             <td></td>

             <td>manimaran</td>
             <td>25-10-2018</td>
             <td>01-01-1970</td>
             <td></td>
             <td></td>
             <td></td></tr>*/


        }


        $('body').on('change load', '#assigned_to', function () {


            emp_id = $(this).val();
            org_id = $("#task_organisation option:selected").val();

            //console.log( return_fields+","+field_val+","+field_name);

            //	return false


            $.ajax({
                url: '{{URL::to('/wfm/')}}/gettaskcount/' + emp_id + '/' + org_id,

                type: 'get',


                dataType: "json",
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                    if (data.status == 1) {


                        if (data.taskcount > 0) {
                            $("#getTaskCount").addClass('col-xs-5 col-md-5');

                            $("#getTaskCount").removeClass('col-xs-6 col-md-6');
                            $("#task_count").show();
                            $("#task_count span").text(data.taskcount);

                            //console.log(field_name);
                            //console.log(data[field_value]);
                        } else {
                            $("#getTaskCount").removeClass('col-xs-5 col-md-5');

                            $("#getTaskCount").addClass('col-xs-6 col-md-6');
                            $("#task_count").hide();

                        }
                        //	alert_message(data.message,"success");


                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });

        })

// $("#task_count").tooltip('show');
    });


</script>

