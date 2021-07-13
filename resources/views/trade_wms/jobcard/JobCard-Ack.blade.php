
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<?php Log::info('JobCard_Detail-Blade:-Before Link JobCardDetail style pages');?>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('resources/views/trade_wms/jobcard/JobCardDetail/JobCard-Detail.css') }}">
<?php Log::info('JobCard_Detail-Blade:-After link JobCardDetail style pages');?>

<?php Log::info('JobCard_Detail-Blade:-Before Link transaction style pages');?>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/transaction.css') }}">
<?php Log::info('JobCard_Detail-Blade:-After link transaction style pages');?>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/theme.css') }}">

<style>


@media screen and (max-width: 800px) {
  table {
    border: 0;
    padding-left: 0;
  }
}
.px-4 {
    padding-left: 1.5rem!important;
    padding-right: 1.5rem!important;
    padding-bottom: .25rem!important;
    padding-top: .25rem!important;
    font-size: 20px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}
.px-4-footer {
    padding-left: 1.5rem!important;
    padding-right: 1.5rem!important;
    padding-bottom: .25rem!important;
    padding-top: .25rem!important;
    font-size: 13px;

}

.popular{
  z-index: 1;
    border: 1px solid #00000020
}
.borderless td, .borderless th {
    border: none !important;
}
.borderless tr{
  
  background-color: transparent !important;
}
.align-header-td {
  padding-left:20px !important
}
.align-header-td-sub{
  padding:0 0 0 45px !important;
  margin:0 !important
}
</style>
</head>
<body>

  <div  class="estimation">
    
<div class ="card1" >
  <div class="demo">
    <div class="container">
      <div class="card card-pricing  px-3 mb-4 popular" style="margin-top:25px;">
      
        <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-default-open" style="border:0 !important;margin-top:0">
            
            <h3 class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white shadow-sm text-center" style="margin-top:0" >Job Card Acknowledgement</h3>
        
         
         <div >  
            @if(isset($company_info)) 
            <table class="table borderless " style="margin-left:10%;width:33%;float:left" > 
                <tr>
                  <td scope="row" style="text-decoration: underline"><b></b></td>
                  </tr>
                  <tr>
                  <td scope="row" class="align-header-td"><b>{{$company_info->org_name}}</b></td>
                  </tr>
                  <tr>
                    <td class="align-header-td">
                      {{$company_info->org_address}}<br>
                      {{$company_info->city_name}} <br>
                      {{$company_info->state_name}}<br>
                      {{$company_info->org_pin}}<br>
                    </td>
                  </tr>
                  <tr>
                    <td class="align-header-td"><b>Contact: </b>&nbsp;&nbsp;&nbsp; {{$company_info->org_ph}}</td>
                  </tr>
                  <tr>
                    <td class="align-header-td"><b>GST : </b>&nbsp;&nbsp;&nbsp; {{$company_info->org_gst}}</td>
                  </tr>
                </table>
                {{-- <p><b>Organization Name :</b>{{$company_info->org_name}}</p>
                <p><b>Address : {{$company_info->org_address}}</b></p>
                <p><b>Mobile :</b> {{$company_info->org_ph}}</p>
                <p><b>Gst :</b> {{$company_info->org_gst}}</p>
               --}}
             
            @endif
            @if(isset($customer_details)) 
            <table class="table borderless " style="margin-left:25px;width:33%" > 
              <tr>
                <td scope="row" style="text-decoration: underline"><b></b></td>
                </tr>
                <tr>
                <td scope="row" class="align-header-td"><b>Customer Name: </b>&nbsp;&nbsp;&nbsp;{{$customer_details->customer_name}}</b></td>
                </tr>
                <tr>
                  <td class="align-header-td" ><b>Contact: </b>&nbsp;&nbsp;&nbsp;{{$customer_details->customer_mobile}}</td>
                </tr>
                <tr>
                  <td class="align-header-td" ><b>Registration No: </b>&nbsp;&nbsp;&nbsp;{{$customer_details->registration_no}}</td>
                </tr>
                <tr>
                  <td class="align-header-td" ><b>Model:</b>&nbsp;&nbsp;&nbsp;{{$customer_details->make_model_variant}}</td>
                </tr>
                <tr>
                  <td class="align-header-td"><b>Job Card #: </b>&nbsp;&nbsp;&nbsp; {{$customer_details->jobcard_no}}</td>
                </tr>
                <tr>
                  <td class="align-header-td"><b>Current Status: </b>&nbsp;&nbsp;&nbsp; {{$customer_details->current_status}}</td>
                </tr>
                <tr>
                  <td class="align-header-td"><b>Last Update : </b>&nbsp;&nbsp;&nbsp;{{$customer_details->last_updated}}</td>
                </tr>
              </table>
           @php
               /*
           @endphp   <div class="col-md-3">
                <p><b>Name : </b>{{$customer_details->customer_name}}</p>
                <p ><b>Vehicle Number :</b>{{$customer_details->registration_no}}</p>
                <p><b>Mobile :</b>{{$customer_details->customer_mobile}}</p>
                <p><b>Make-Model-varient :</b>{{$customer_details->make_model_variant}}</p>
                <p><b>Current-status :</b>{{$customer_details->current_status}}</p>
                <p><b>Last-Update :</b>{{$customer_details->last_updated}}</p>
             
              </div>
              @php
              */@endphp
           
            @endif 
         </div>
          </div>

         <div class="tab-content">
      

            <div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
              <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                    <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
                    Parts
              </h3>
              <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ">
                <table class="table table-bordered " style="margin-left:25px;" > 
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Description</th>
                      <th scope="col">Quantity</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $i = 0;
                    @endphp

                  @if(isset($items) && $items['parts'])
                     @foreach ($items['parts'] as $parts)
                      
                  
                      <tr>
                          @php
                          $i++;
                         @endphp
                      <td scope="row">{{$i}}</td>
                      <td scope="row">{{$parts->item_name}}</td>
                      <td scope="row">{{$parts->quantity}}</td>
                      </tr>
                      @endforeach
                      @endif
                  </tbody>
                  </table>
                 
              </div>
            </div>
            <div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
              <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                    <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
                    Service
              </h3>
              <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ">
                <table class="table table-bordered " style="margin-left:25px;" > 
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Description</th>
                      <th scope="col">Status</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                    @php
                        $i = 0;
                    @endphp
                  @if(isset($items) && $items['service'])
                     @foreach ($items['service'] as $service)
                      
                  
                      <tr>
                        @php
                            $i++;
                        @endphp
                      <td scope="row">{{$i}}</td>
                      <td scope="row">{{$service->item_name}}</td>
                      <td scope="row">{{$service->item_status_name}}</td>
                      </tr>
                      @endforeach
                      @endif
                  </tbody>
                  </table>
                 
              </div>
            </div>
     
            <div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
              <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                    <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
                   Images
              </h3>
              <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ">
                  	<!--Start Before Image -->

				<div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
					<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Before
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-default-open">
				    	<div class="clearfix"></div>
							<div class="form-group">
							
								<!--Start Before Image -->

								<div class="col-lg-12 col-md-12 col-sm-12">
                  @if(isset($imges) && $imges['beforeImg'])
                    @foreach ($imges['beforeImg'] as $beforeImg)
                    <div class="img-wrap" style="padding:5px">
                      <img alt="Before Image"  src="{{$beforeImg->image_url}}" width="120" height="120" />
                    </div>
                    @endforeach
                  @endif
								</div>

						 		<!--End Before Image -->

							</div>


				    </div>
				</div>
				<!--End Before Image -->
				<!--Start Progress Image -->
				<div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
					<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Progress
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
				    	<div class="clearfix"></div>
							<div class="form-group">
							
								<!--Start Before Image -->
								{{-- TODO : EDIT --}}
                <div class="col-lg-12 col-md-12 col-sm-12">
                  @if(isset($imges) && $imges['progressImg'])
                    @foreach ($imges['progressImg'] as $progressImg)
                    <div class="img-wrap" style="padding:5px">
                      <img alt="Progress Image"  src="{{$progressImg->image_url}}" width="120" height="120" />
                    </div>
                    @endforeach
                  @endif
								</div>
						 		<!--End Before Image -->

							</div>


				    </div>
				</div>

				<!--End Progress Image -->

				<!--Start After Image -->
				<div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
					<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        After
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
				    	<div class="clearfix"></div>
							<div class="form-group">
								{{-- TODO : EDIT --}}
					
								<!--Start Before Image -->
								<div class="col-lg-12 col-md-12 col-sm-12" id="">
                  @if(isset($imges) && $imges['afterImg'])
                  @foreach ($imges['afterImg'] as $afterImg)
                  <div class="img-wrap" style="padding:5px">
                    <img alt="After Image"  src="{{$afterImg->image_url}}" width="120" height="120" />
                  </div>
                  @endforeach
                @endif

								</div>
						 		<!--End Before Image -->
							</div>
					</div>
				</div>

				<!--End After Image -->
              </div>
            </div>
            <div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
              <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                    <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
                    Checklist
              </h3>
              <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ">
                <table style="border-collapse: collapse;" class="table table-bordered" id="checklist-table">
                  <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="40%">Description</th>
                    <th width="5%">Checked?</th>
                    <th width="50%">Notes</th>
                  </tr>
                    @php
                        $i = 0;
                    @endphp

                      @if(isset($checklists) )
                        @foreach ($checklists as $checklist)
                          
                      
                          <tr>
                              @php
                              $i++;
                            @endphp
                          <td scope="row">{{$i}}</td>
                          <td scope="row">{{$checklist->name}}</td>
                          <td scope="row" style="text-align: center"><i class="fa fa-check" aria-hidden="true" style="color:#78b13f"></i></td>
                          <td scope="row">{{$checklist->checklist_notes}}</td>
                          </tr>
                          @endforeach
                      @endif
                  <tr>
                  </thead>
                  <tbody>
              
                  </tbody>
                </table>
              </div>
            </div>
            <div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
              <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                    <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
                    Previous Visit
              </h3>
              <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ">
                <div class="panel-body" style="height: 300px;overflow-y: scroll;background-color: white;">
                            <table class="table table-bordered ">
                              <thead>
                                <tr>
                                  <td align="center"><strong>Vehicle No</strong></td>
                                  <td align="center"><strong>Date</strong></td>
                                  <td align="center"><strong>Job Card No</strong></td>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($historical_jc_infos as $historical_jc_info)
                                <tr>
                                  <td align="center">{{ $historical_jc_info->registration_no }}</td>
                                  <td align="center">{{ $historical_jc_info->job_date }}</td>
                                  <td align="center"><a href="{{ generateEncryptedURL (url('job_card_acknowledgement/'),$historical_jc_info->id)}}" target="_blank"" style="color: blue;font-size: 15px;">{{ $historical_jc_info->order_no }}</a></td>
                                </tr>
                                @endforeach
                               
                              </tbody>
                            </table>
                             
                  </div>
              </div>
            </div>
            <div class="w-60 mx-auto px-4-footer py-1 rounded-bottom bg-primary text-white shadow-sm text-center"  >
              <span>Copyright ©  Propelsoft.in . All rights reserved</span>

              </div>
        
            
          </div>
      </div>
      
    </div>
  </div>
</div>
</div>

</body>
</html>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script>

  $(document).ready(function(){
    // Accordion related code
      var accordionHeaders = $('#jq_accordion .accordion-header');
      var accordionContentAreas = $('#jq_accordion .ui-accordion-content ').hide();
      var accordionContentAreasOpen = $('#jq_accordion .ui-accordion-default-open ').show();
               
      accordionHeaders.click(function() {
        //alert();
        console.log("panel open");
        var panel = $(this).next();
        var isOpen = panel.is(':visible');
        // open or close as necessary
        panel[isOpen ? 'slideUp' : 'slideDown']()
          // trigger the correct custom event
          .trigger(isOpen ? 'hide' : 'show');

        // stop the link from causing a pagescroll
        return false;
      });


  });
	

</script>
