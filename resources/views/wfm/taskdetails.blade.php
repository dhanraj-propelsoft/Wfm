
<div class="container-fluid">
    <div class="form-body">
       <div class="row">
        <div class="col-md-12" >
          <div class="form-group" >
            {!! Form::hidden( 'token',$apiKey) !!}
            <input type="hidden" name="person_id"  value="<?php  echo Auth::user()->person_id;?>" />

            {!! Form::text('task_name', null, array('class' => 'inputText','id'=>'task_name things','style'=>'color: #999;','required')) !!}
            <span class="fa fa-plus-circle floating-label" style="color: #999;""><span>&nbsp;Task To Do <i>(task)</i></span></span>
            </div>

            </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">

                  {!! Form::text('task_details', null, array('class' => 'inputTextArea','style'=>'color: #999;' ,'required' )) !!}
                  <span class="fa fa-plus-circle floating-label" style="color: #999;""><span>&nbsp;Task details</span></span>
                  </div>
                  </div>
                  </div>


                  <div class="row">

                    <div class="col-xs-6 col-md-6"   data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Under the project')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon " for="project_type" style="color: #919191;"><i class="fa fa-user"></i></span>
                        {!! Form::select('project_id', $projects,10, array('class' => 'form-control  ','id' => 'project_id','placeholder'=>"select","type"=>"select",'color'=>' #999')) !!}
                      </div>
                    </div> 

                    <div class="col-xs-6 col-md-6" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'deadline')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="Deadline" style="color: #919191;"><i class="fa fa-calendar"></i></span><label  id="Deadline"  style="color:#999;margin:auto 0">Lifetime</label>
                        

                      </div>
                    </div>
                  </div>
                  <div class="row">

                    <div class="col-xs-6 col-md-6" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'assigned by')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="created_by" style="color: #919191;"><i class="fa fa-user"></i></span>
                        
                        {!! Form::select('assigned_by',$EmployeeList,GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id), array('class' => 'form-control','id' => 'assigned_by','placeholder'=>"select",'type'=>'select')) !!}
                      </div>
                    </div> 

                    <div class="col-xs-6 col-md-6" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'assigned to')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="assign_to" style="color: #919191;"><i class="fa fa-user"></i></span>
                     
                        {!! Form::select('assigned_to',$EmployeeList,GetEmployeeData(Session::get('organization_id'),Auth::user()->person_id), array('class' => 'form-control','id' => 'assigned_to','placeholder'=>"select",'type'=>'select')) !!}
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-6 col-md-6" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Create date')}}" >
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="start_date" style="color: #919191;"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('create_date', date('d-m-Y'), array('class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy','id'=>'create_date','style'=>'color: #919191;','placeholder'=>'Start date','data-date-format' => 'dd-mm-yyyy')) !!}
                      </div>
                    </div> 

                    <div class="col-xs-6 col-md-6" data-toggle='tooltip'  title="{{GetLabelName(Session::get('organization_id'),'Due date')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="Due date" style="color: #919191;"><i class="fa fa-calendar"></i></span>
                       
                        {!! Form::text('end_date', date('d-m-Y'), array('class' => 'form-control date-picker to-date-picker', 'data-date-format' => 'dd-mm-yyyy','style'=>'color: #919191;','id'=>'end_date','placeholder'=>'End date')) !!}
                        
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-6 col-md-6" data-toggle='tooltip'  title="{{GetLabelName(Session::get('organization_id'),'Size')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon" for="size" style="color: #919191;"><i class="fa fa-user"></i></span>
                    
                        {!! Form::select('size_id',$Sizes,null, array('class' => 'form-control','id' => 'size_id','placeholder'=>"select",'type'=>'select')) !!}

                      </div>
                    </div> 

                    <div class="col-xs-6 col-md-6" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'Worth')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-user"></i></span>
                        {!! Form::text('worth_id', null, array('class' => 'form-control','style'=>'color: #919191;','id'=>'worth_id')) !!}
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-8 col-md-8" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'priority')}}">
                      <div class="input-group" id="priority_group" >
                        <div class="input_icon_fixed pull-left " style="width:100%" >
                          <span class="pull-left " style="vertical-align: middle;    top: 10px;position: relative;"><i class="fa fa-list "></i>&nbsp;Priority</span>
                          @if($Priority)

                          @foreach($Priority as $id=>$priority_type)
                          @if($id!=2)
                          <?php $value=""; ?>
                          @else
                          <?php $value=$id; ?>
                          
                          @endif
                          <p class="pull-left priority_option" style="" >&nbsp;{!! Form::radio('priority_id', $id, $value,['style'=>"display:initial;"]) !!}</p>   
                          <span class="pull-left" style="margin: 0 0 0 1%"><?php echo priority($id); ?></span>
                          @endforeach
                          @endif 

                  

                        </div>


                      </div>
                    </div> 

                    <div class="col-xs-3 col-md-3 pull-right" style="margin: 0 0 0 -1%">
                      <div class="input-group">
                        <p style="margin-top:6px"> No of days:&nbsp;</p>
                        <div class="btn_round days pull-left calculate btn-secondary-round">0</div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-xs-12 col-md-12">
                      <div class="input-group" style="    border: 1px solid #ddd;" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'repeat')}}">
                        <span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-repeat"></i>&nbsp;Repeat</span>    
                        {!! Form::select('repeat', [''=>'Never',2=>'Every Day',3=>'Week Days',4=>'Every Month',5=>'Every Year',6=>'Customized'],1, array('class' => 'form-control pull-left  select_item select2-hidden-accessible GetRepeatOption','id' => 'repeat','style'=>'width:50%;color:#999;height:29px','placeholder'=>'select')) !!}


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

                    <div class="col-xs-12 col-md-12" data-toggle='tooltip' title="{{GetLabelName(Session::get('organization_id'),'tags')}}">
                      <div class="input-group" style="    border: 1px solid #ddd;">
                        <span class="input-group-addon  " for="worth" style="color: #919191;"><i class="fa fa-tag"></i>&nbsp;Tags</span>
                        <select class="select-tag form-control select2-hidden-accessible" multiple="" data-select2-id="10" tabindex="-1" aria-hidden="true">
                          <option data-select2-id="42">red</option>
                          <option data-select2-id="43">blue</option>
                          <option data-select2-id="44">green</option>
                        </select>
                      </div> 


                    </div>
                  </div>


              


                <div class="row">
                  <div class="col-xs-8 col-md-8" style="margin-left:16px;" >

                    <label for="upload-photo" class="" style="color:#999;cursor: pointer;">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="attachment" id="upload-photo" multiple/>
                  </div>
                  <div class="col-xs-10 col-md-10" style="margin-left:16px;" >
                    <ul class="tagit ui-widget ui-widget-content ui-corner-all" id="attachment_datalist"  > 
                    </ul>
                  </div>
                </div>

              </div>


            </div>
          </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('load',function()
            {
              $(".select-tag").select2({
                tags: true,
                tokenSeparators: [',', ' ']
              })
            }
</script>