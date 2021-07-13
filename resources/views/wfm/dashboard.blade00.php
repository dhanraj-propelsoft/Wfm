@extends('layouts.master')
@include('includes.wfm')
@section('content')



    <div class="alert alert-success">
        {{ Session::get('flash_message') }}
    </div>



@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif


<div class="fill header">
  <h4 class="float-left page-title">Dashboard</h4>
  <!--   <a class="btn btn-danger float-right add" style="color: #fff">+ New</a> -->
  </div>
  <div class="col-lg-6">
                            

                                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link active show" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="true">Contact</a>
                                            </li>
                                        </ul>

                              
                        </div>
<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
    <div class="batch_container">
        <div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
        </div>
        <ul class="batch_list">
            <li><a class="multidelete">Delete</a></li>
            <li><a data-value="1" class="multiapprove">Make Active</a></li>
            <li><a data-value="0" class="multiapprove">Make In-Active</a></li>
        </ul>
    </div>
    <div class="row">
    <div class="col-md-9 " style="overflow-x: auto">
   



  <table id="datatable" class="table data_table panel  panel-default" width="80%" cellspacing="0">
    <thead>
      <tr>
        <th> #</th>  
        <th style="width: 30px">Task Number</th> 
        <th>Task Summery</th>
        <th>Created by</th>
        <th>Assigned to</th>
        <th>Planned start</th>
        <th>Planned End</th>
        <th>Importance</th>
        <th>Status</th>
        <th>Substatus</th>
        <th>Size</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody><tr>
          <td width="1">1</td>    
          <td class="popUp">Pro1_Task1</td>
          <td>Task1</td>
          <td>kumar</td>
          <td>Raja</td>
          <td>21.08.2018</td>
          <td>29.08.2018</td>
           <td>High</td>
           <td>New</td>
           <td>Unassigned</td>
           <td></td>
           <td><button class="btn btn-primary">Edit</button><button class="btn btn-primary">Delete</button></td>
        </tr>
    </tbody>
  </table>
</div>
 <div class="col-md-3" style="overflow-x: auto">
    <div class="panel panel-default Task_details" style="display: none">
  <div class="panel-heading font-bold" style="">Task details</div>
  <div class="panel-body">
    <table id="datatable" class="table data_table panel  panel-default" width="80%" cellspacing="0">
        <thead class="font-bold">
          <tr>
            <th colspan="3">Task Desription
          </th>
          </tr>
      </thead>
      <tbody>
          <tr><td class="font-bold">Sub status</td><td><input type="text" class="form-control"></td></tr>
      </tbody>
  </table>

  </div>
    </div>   
    <div class="panel panel-default">

  <div class="panel-body" style="overflow-x: auto"><table id="datatable" class="table data_table panel  panel-default" width="80%" cellspacing="0">
        <thead class="font-bold">
          <tr>
            <th >Task Number </th>
            <th >Member </th>
            <th >Field </th>
            <th >Change Description </th>
          </tr>
      </thead>
      <tbody>
          <tr><td class="font-bold">#pro_1</td><td>Rajesh</td><td>Status</td><td>New to Unassigned</td></tr>
      </tbody>
  </table></div>
    </div> 
</div>
</div>

</div>

					
@stop

@section('dom_links')
@parent

   <script type="text/javascript">
 $(".popUp").click(function(){
    $(".Task_details").css('display','block');
 })
	</script>
@stop