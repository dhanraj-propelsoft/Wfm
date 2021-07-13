<div class="form-body" >
 <div class="row">
  <div class="col-md-12"  >

    <div class="form-group" >

      {!! Form::hidden( 'token',$apiKey) !!}
      {!! Form::hidden( 'person_id',Auth::user()->person_id) !!}
      {!! Form::text('project_name', null, array('class' => 'inputText','id'=>'project_name','style'=>' border: 1px solid #ced4da;color: #999;')) !!}

      <span class="fa fa-plus-circle floating-label" style="color: #999;"><span>&nbsp;Add Project</span></span>
    </div>

  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      {!! Form::text('project_details', null, array('class' => 'inputTextArea','rows'=>4,'style'=>' border: 1px solid #ced4da;color: #999;','id'=>'project_details')) !!}

      <span class="fa fa-plus-circle floating-label" style="color: #999;"><span>&nbsp;Project details</span></span>
    </div>
  </div>
</div> 

<div class="row">

 <div class="col-xs-6 col-md-6"   >
   <div class="input-group" style="    border: 1px solid #ddd;" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Project Deadline')}}">
    <span class="input-group-addon" for="create_date" style="color: #919191;"><i class="fa fa-user"></i></span>

    {!! Form::text('deadline_date', date('d-m-Y'), array('class' => 'form-control accounts-date-picker pull-left input_box_hidden','id'=> 'deadline_date','data-date-format' => 'dd-mm-yyyy','style'=>'width:50%;','placeholder'=>"select date")) !!}

  </div>
</div>


<div class="col-xs-6 col-md-6">
  <div class="input-group" style="    border: 1px solid #ddd;" data-toggle='tooltip'  title="{{GetLabelName(Session::get('organization_id'),'project category')}}">
    <span class="input-group-addon" for="category" style="color: #919191;"><i class="fa fa-user"></i></span>

    {!! Form::select('project_category_id',$project_category_list,GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id), array('class' => 'form-control pull-left  select_item select2-hidden-accessible','id' => 'project_category','style'=>'width:50%;color:#999;height:29px','placeholder'=>"select","type"=>"select")) !!}

  </div>
</div>





</div>


<div class="row">




  <div class="col-xs-6 col-md-6"   >
   <div class="input-group" style="    border: 0px;bottom:-1px; ">
    <label for="upload-photo" class="" style="color:#999;cursor: pointer;">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="attachment" id="upload-photo" multiple/>
  </div>
  <div class="col-xs-10 col-md-10" style="margin-left:16px;" >
    <ul class="tagit ui-widget ui-widget-content ui-corner-all" id="attachment_datalist"  > 
    </ul>
  </div>
</div> 

</div>






</div>