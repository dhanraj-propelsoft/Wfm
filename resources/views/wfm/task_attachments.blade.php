<style type="text/css">
div>span.fa>span, div label{
	font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
}
select,option,input,textarea,select span	 {
	color: #919191;
}
p.pull-left.priority_option:nth-child(0)
{
	margin: 3% 0 0 27%
}
p.pull-left.priority_option:not(:nth-child(0))
{
	margin: 3% 0 0 5%
}
.select2-container
{
	height:100% !important;
}
.control-label:after {
	content:"*";
	color:red;
	font-size: x-large;
}


/*Upload Icon CSS*/
*, *:before, *:after {
  box-sizing: border-box;
}

@-webkit-keyframes spin {
  from {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  to {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}

@keyframes spin {
  from {
    -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
  }
  to {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}
body, html {
  min-height: 100vh;
  background: #666;
  font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  font-weight: 300;
}

.table1 {
  display: table;
/*  width: 100%;
  height: 100vh;*/
  position: relative;
}

.table-cell {
  display: table-cell;
  vertical-align: middle;
}

.modal1 {
/*  width: 300px;
  height: 400px;*/
  margin: 0 auto;
  background: #fff;
/*  box-shadow: 0 40px 50px rgba(0, 0, 0, 0.35);*/
  padding: 40px;
}

#mediaFile {
  position: absolute;
  top: -1000px;
}

#profile {
  border-radius: 100%;
  width: 200px;
  height: 200px;
  margin: 0 auto;
  position: relative;
 /* top: -80px;
  margin-bottom: -80px;*/
  cursor: pointer;
  background: #f4f4f4;
  display: table;
  background-size: cover;
  background-position: center center;
 /* box-shadow: 0 5px 8px rgba(0, 0, 0, 0.35);*/
}
#profile .dashes {
  position: absolute;
  top: 0;
  left: 0;
  border-radius: 100%;
  width: 100%;
  height: 100%;
  border: 4px dashed #ddd;
  opacity: 1;
}
#profile label {
  display: table-cell;
  vertical-align: middle;
  text-align: center;
  padding: 0 30px;
  color: grey;
  opacity: 1;
}
#profile.dragging {
  background-image: none !important;
}
#profile.dragging .dashes {
  -webkit-animation-duration: 10s;
          animation-duration: 10s;
  -webkit-animation-name: spin;
          animation-name: spin;
  -webkit-animation-iteration-count: infinite;
          animation-iteration-count: infinite;
  -webkit-animation-timing-function: linear;
          animation-timing-function: linear;
  opacity: 1 !important;
}
#profile.dragging label {
  opacity: 0.5 !important;
}
#profile.hasImage .dashes, #profile.hasImage label {
  opacity: 0;
  pointer-events: none;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}



.stat {
  width: 50%;
  text-align: center;
  float: left;
  padding-top: 20px;
  border-top: 1px solid #ddd;
}
.stat .label {
  font-size: 11px;
  font-weight: bold;
  letter-spacing: 1px;
  text-transform: uppercase;
}
.stat .num {
  font-size: 21px;
  padding: 3px 0;
}

.editable {
  position: relative;
}
.editable i {
  position: absolute;
  top: 10px;
  right: -20px;
  opacity: 0.3;
}


/*Upload Icon CSS*/

</style>

{!! Form::open(['class' => 'form-horizontal validateform','enctype' => 'multipart/form-data']) !!}
{{ csrf_field() }}
<div class="modal-header">

	<h4 class="modal-title pull-left ">Add Attachments</h4>
</div>



</div>



<div class="modal-body " >
	<div class="container-fluid" id="GetTaskFormData">
		<div class="form-body">
			
			


			
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<!-- <div class="table1">
						<div class="table-cell">
							<div class="modal1">
								<div id="profile">
									<div class="dashes"></div>
									<label>Click to browse or drag an image here</label></div>
								



								
									
								
								</div>
							</div>
						</div> -->
						<!-- 						<form action="~/api/Upload" class="dropzone" enctype="multipart/form-data" id="dropzoneForm" method="post" name="dropzoneForm">
						 <div class="fallback">
						 <input multiple name="file" type="file"/> <input type="submit" value="Upload"/>
						 </div>
						 </form> -->
						<div class="dropzone" id="uploadFile"> </div>

					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 comment_mediaFiles" >

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

									
									
									





									

								</div>


							</div>
						</div>
					</div>
				</span>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary close_model">Cancel</button>
			<button type="button" class="btn btn-secondary " id="Insert_files">Insert File</button>
		</div>
		{!! Form::close() !!}

		<script>

var comment_attachments = new Dropzone("div#uploadFile", {
	  paramName: 'file',
	  url: "{{url('api/wfm/attachement')}}",
	  params:{
	  	organization_id:$('.Task_attachments').attr('data-org-id'),
      project_id:$('.Task_attachments').attr('data-pro-id'),
	  	task_id:$('.Task_attachments').attr('data-task-id'),
	  },
	    headers: {
	       'Authorization': 'Bearer ' +$('input[name=data-token]').val(),
    	},
	  dictDefaultMessage: "Drop or click to upload  files",
	  clickable: true,
	  maxFilesize: 5, // MB
    parallelUploads: 4,
	  acceptedFiles: "image/*,.xlsx,.xls,.pdf,.doc,.docx,.txt",
	  maxFiles: 4,
    uploadMultiple:true,
	  autoProcessQueue: false,
	  addRemoveLinks: true,
	  removedfile: function(file) {
		  file.previewElement.remove();
	  },
	  queuecomplete: function() {
		  comment_attachments.removeAllFiles();
	  },init: function() {
 
    
    var submitButton = document.querySelector("#Insert_files")
        myDropzone = this; // closure
   
    submitButton.addEventListener("click", function(e) {

      if(comment_attachments.getQueuedFiles().length > 0)
          {
             e.preventDefault();
             e.stopPropagation();
            console.log(comment_attachments.processQueue());
            comment_attachments.processQueue(); // Tell Dropzone to process all queued files.
           // Task_attachments.processQueue(); // Tell Dropzone to process all queued files.
          }
    });
     this.on("successmultiple", function(file,response) {
           //console.log(response);
          // console.log(tr);
           //console.log(JSON.parse(JSON.parse(json).data));
           $.each(response.uploaded_files, function(id, name) {
           
           attachment=response.attachment_path[id];
           console.log(attachment);
           attach_url=JSON.parse(attachment);
              var filePath = {!! json_encode(asset('public/attachment')) !!}+'/'+attach_url;
                console.log(filesPath);
          type = name.split(".")[1];
          //console.log(type);
          if(type=="pdf")
          {
            icon="fa fa-file-pdf-o";
          }
          if(type=="docx"||type=="doc")
          {
            icon="fa fa-word-pdf-o";
          }
          if(type=="png"||type=="jpg")
          {
            icon="fa fa-file-photo-o";
          }

           if(type=="xlsx"||type=="xls")
          {
            icon="fa fa-file-excel-o";
          }
           if(type=="txt")
          {
            icon="fa fa-file";
          }
             $("#comment_attachment_datalist").append('<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable text-overlap" style="width:100px"><i class="'+icon+'"></i><a class="tagit-label"  target="_blank" href="'+filePath+'"> '+name+'</a><a class="tagit-close"><span class="text-icon del_attach"  data-id="'+id+'">×</span><span class="ui-icon ui-icon-close"></span></a></li>');
           });
            //console.log('<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable text-overlap" style="width:100px"><i class="'+icon+'"></i><a class="tagit-label"  target="_blank" href="'+filePath+'"> '+name+'</a><a class="tagit-close"><span class="text-icon del_attach"  data-id="'+id+'">×</span><span class="ui-icon ui-icon-close"></span></a></li>');
             $(".wfm_attachment_modal").modal('hide');
             alert_message("Files attached");
            });
     this.on("errormultiple", function(files, response) {
      alert(response);
    //    this.removeFile(files); 
      // Gets triggered when there was an error sending the files.
      // Maybe show form again, and notify user of error
    })
}
  	});
      


</script>

