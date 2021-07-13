
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<style>

div.gallery {
  margin: 5px;
  border: 1px solid #ccc;
  float: left;
  width: 180px;
}

div.gallery:hover {
  border: 1px solid #777;
}

div.gallery img {
  width: 100%;
  height: auto;
}

div.desc {
  padding: 15px;
  text-align: center;
}
.card {
  
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 1115px;
  margin: auto;
  text-align: center;
  font-family: arial;
  background-color: #001f4d ;
  color : white;
}

.title {
  color: white;
  font-size: 18px;

}

button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: black;
  background-color:  #c2c2d6;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;

}
a {
  text-decoration: none;
  font-size: 22px;
  color: black;
}

button:hover, a:hover {
  opacity: 0.7;
}
/*.panel-body{ background: linear-gradient(to right, #D83F87, #44318D) }*/
.panel-body{  background-color: #001f4d }
a:hover,a:focus{
    text-decoration: none;
    outline: none;
    color: #fff;
}
#accordion .panel{
    border: none;
    border-radius: 5px;
    box-shadow: none;
    margin-bottom: 10px;
    background: transparent;
}
#accordion .panel-heading{
    padding: 0;
    border: none;
    border-radius: 5px;
    background: transparent;
    position: relative;
    font-color: #0000;
}
#accordion .panel-title a{
    display: block;
    padding: 20px 30px;
    margin: 0;
    background:  #c2c2d6;
    font-size: 17px;
    font-weight: bold;
    font-color: #0000;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
    border-radius: 5px;
    position: relative;
    color:black;
}
#accordion2 .panel-title1 a{
    display: block;
    padding: 10px 20px;
    margin: 0;
    background: #c2c2d6;
    font-size: 17px;
    font-weight: bold;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
    border-radius: 2px;
    position: relative;
}
.panel-group .panel {
    margin-bottom: 0;
    border-radius: 4px;
   margin-right: 0;

}
#accordion .panel-title a.collapsed{ border: none; }
#accordion2 .panel-title1 a.collapsed{ border: none; }
#accordion .panel-title a:before,
#accordion2 .panel-title1 a:before,
#accordion .panel-title a.collapsed:before{
    content: "\f107";
    font-family: "Font Awesome 5 Free";
    width: 30px;
    height: 30px;
    line-height: 27px;
    text-align: center;
    font-size: 25px;
    font-weight: 900;
    color: #fff;
    position: absolute;
    top: 10px;
    right: 30px;
    transform: rotate(180deg);
    transition: all .4s cubic-bezier(0.080, 1.090, 0.320, 1.275);
}
#accordion2 .panel-title1 a.collapsed:before{
    content: "\f107";
    font-family: "Font Awesome 5 Free";
    width: 30px;
    height: 30px;
    line-height: 27px;
    text-align: center;
    font-size: 25px;
    font-weight: 900;
    color: #fff;
    position: absolute;
    top: 10px;
    right: 30px;
    transform: rotate(180deg);
    transition: all .4s cubic-bezier(0.080, 1.090, 0.320, 1.275);
}
#accordion .panel-title a.collapsed:before{
    color: rgba(255,255,255,0.5);
    transform: rotate(0deg);
}
#accordion2 .panel-title1 a.collapsed:before{
    color: rgba(255,255,255,0.5);
    transform: rotate(0deg);
}
#accordion .panel-body{
    padding: 20px 30px;
    
    font-size: 15px;
    color: #fff;
    line-height: 28px;
    letter-spacing: 1px;
    border-top: none;
    border-radius: 5px;
        padding-left: 0;
}
@media screen and (max-width: 800px) {
  table {
    border: 0;
    padding-left: 0;
  }
}
</style>
</head>
<body>
  <div class="text-light mt-5 mb-5 text-center"> <div class="alert alert-success alert-dismissible" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Your Estimation is successfully  approved.</strong>  Our representative will call and update you the status.  Thanks for choosing our service.
  </div></div>
  <div  class="estimation">
    <div class="alert alert-danger text-center" style="display:none;"> </div>

    @if(isset($company_info)) 
<div class="card" style="margin-top: 15px;" >
    <p><button>{{$company_info->org_name}}</button></p>
  <h1 class="title">Address: {{$company_info->org_address}}</h1>
  <p><b>Mobile:</b> {{$company_info->org_ph}}</p>
  <p><b>Gst:</b> {{$company_info->org_gst}}</p>
 <p><button></button></p>
</div>
 @endif

  @if(isset($customer_details)) 
<div class="card" >
    <p><button class="button1">DR.{{$customer_details->customer_name}}</button></p>
    <h1 class="title">{{$customer_details->registration_no}}</h1>
  <p><b>Mobile:</b>{{$customer_details->customer_mobile}}</p>
  <p><b>Make-Model-varient:</b>{{$customer_details->make_model_variant}}</p>
  <p><b>Current-status:</b>{{$customer_details->current_status}}</p>
  <p><b>Last-Update:</b>{{$customer_details->last_updated}}</p>
 <p><button class="button1"></button></p>
</div>
@endif
<div class ="card1" >
  <div class="demo">
    <div class="container">
            <div class= "col-sm-12 col-md-12 col-lg-12">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Complaints
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                              @if(isset($complaints))
                              <ul>
                               <?php //$complaints_list=explode(' ',$complaints[0]->complaint); ?>
                               @foreach($complaints as $complaint)
                                   <?php $complaints_list=preg_split('/\r\n|\r|\n/',$complaint->complaint); ?>
                                   <?php //var_dump($complaints_list); ?>
                                  @foreach($complaints_list as $complaintData)
                                   <li>{{$complaintData}}</li>
                                 @endforeach
                                @endforeach
                              </ul>
                              @endif
                            </div>
                        </div>
                    </div>
                   <!--  <div class="panel panel-default">
                       <div class="panel-heading" role="tab" id="headingTwo">
                           <h4 class="panel-title">
                               <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                   Checklist
                               </a>
                           </h4>
                       </div>
                       <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                           <div class="panel-body">
                               @if(isset($checklists))
                               <ul>
                                @foreach($checklists as $checklist)
                              <li> <b>{{$checklist->name}}</b> <input type="checkbox" checked="checked"> <i>{{$checklist->checklist_notes}}</i></li>
                               @endforeach
                             </ul>
                                @endif
                           </div>
                       </div>
                   </div> -->
                     <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                   Work & Spares 

                                </a>
                            </h4>
                        </div>

                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                              @if(isset($Works_Spares))
                                <div class="col-sm-6">
                                  <div calss="table-responsive">
                                  <table class="table ">
                                    <thead>
                                    <th>NO</th>
                                    <th>Item + Description</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                  </thead>
                                  <?php $i=1 ?>
                                    @foreach($Works_Spares as $Works_Spare)
                                    <?php $amount = $Works_Spare->amount + $Works_Spare->tax_amount;
                                      $item_amount = number_format((float)$amount, 2, '.', ''); ?>
                                    <tbody>
                                    <td>{{$i++}}</td>
                                    <td>{{$Works_Spare->item_name}}</td>
                                    <td>{{$Works_Spare->quantity}}</td>
                                    <td>{{$amount}}</td>
                                  </tbody>
                                    @endforeach
                                  </table>
                                  <div class="py-3 px-5 text-right">
                            <div class="mb-2">Total</div>
                            <div class="h4 font-weight-light">Rs.{{$transaction->total}}</div>
                        </div>
                        @if($transaction->approval_status == '0')
                        <div style="background:white;">
                             <canvas id="signature-pad" class="signature-pad"></canvas>      
                        </div>
                        <button id="clear">Clear</button>
                        @endif
                         <?php
                    $date=date_create($transaction->approved_date);
                    ?>
                      @if($transaction->approval_status == '1')
                      <img src="{{asset('public/signatures/organization_id-'.$transaction->organization_id).'/transaction_id-'.$transaction->id.'/'.$transaction->signature}}" width="200" height= "200">
                        <p style="color:white;">Approved on (<?php echo date_format($date,"l jS \of F Y h:i:s A"); ?>)</p>
                      @elseif($transaction->total !==  "0.00" || $transaction->approval_status == '0')
                      <div style="display:block"><input type="submit" class="btn btn-primary aprove_btn"  id="{{$transaction->id}}" data-id="{{$transaction->reference_id}}" value="Approve"></div>
                      @elseif($transaction->total ==  "0.00")
                      <div class="aprove_btn" style="display:none"><input type="submit"  name="aprove"  value="Approve" ></div>
                      @endif
                               </div>
                                </div>
                                 @endif
                            </div>
                        </div>
                    </div> 
             <!--  <div class="panel panel-default">
               <div class="panel-heading">
                   <h4 class="panel-title">
                       <a data-toggle="collapse" data-parent="#accordion1" href="#collapsefour">Photos and Attachments  
                       </a>
                   </h4>
               </div>
               <div id="collapsefour" class="panel-collapse collapse">
                   <div class="panel-body">
             
                       <div class="panel-group" id="accordion2">
                           <div class="panel panel-default">
                               <div class="panel-heading">
                                   <h4 class="panel-title1">
                                       <a data-toggle="collapse" data-parent="#accordion2" href="#collapsefourOne">Before
                                       </a>
                                   </h4>
                               </div>
                               <div id="collapsefourOne" class="panel-collapse collapse in">
                                   <div class="panel-body">
                                     @if(isset($photos)) 
                               @foreach($photos as $photo)
                               @if($photo->image_category == '1')
                                      <div class="gallery">
                                       <img src="{{asset('public/wms_attachments/org_'.$photo->organization_id).'/temp/'.$photo->origional_file}}"  width="10%" height="10%" id="myImg" class="pop">
                                      </div>
                                      @endif
                                      @endforeach
                                     @endif 
                                   </div>
                               </div>
                           </div>
                           <div class="panel panel-default">
                               <div class="panel-heading">
                                   <h4 class="panel-title1">
                                       <a data-toggle="collapse" data-parent="#accordion3" href="#collapsefourTwo">Progress
                                       </a>
                                   </h4>
                               </div>
                               <div id="collapsefourTwo" class="panel-collapse collapse">
                                   <div class="panel-body">
                                     @if(isset($photos)) 
                               @foreach($photos as $photo)
                               @if($photo->image_category == '2')
                                      <div class="gallery">
                                       <img src="{{asset('public/wms_attachments/org_'.$photo->organization_id).'/temp/'.$photo->origional_file}}"  width="10%" height="10%" id="myImg" class="pop">
                                      </div>
                                      @endif
                                      @endforeach
                                     @endif 
                                   </div>
                               </div>
                           </div>
                           <div class="panel panel-default">
                               <div class="panel-heading">
                                   <h4 class="panel-title1">
                                       <a data-toggle="collapse" data-parent="#accordion2" href="#collapsefourThree">After
                                       </a>
                                   </h4>
                               </div>
                               <div id="collapsefourThree" class="panel-collapse collapse">
                                   <div class="panel-body">
                                     @if(isset($photos)) 
                               @foreach($photos as $photo)
                               @if($photo->image_category == '3')
                                      <div class="gallery">
                                       <img src="{{asset('public/wms_attachments/org_'.$photo->organization_id).'/temp/'.$photo->origional_file}}"  width="10%" height="10%" id="myImg" class="pop">
                                      </div>
                                      @endif
                                      @endforeach
                                     @endif 
                                   </div>
                               </div>
                           </div>
                       </div>
             
                   </div>
               </div>
             </div> -->
               <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingFive">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                   Additional Information
                                </a>
                            </h4>
                  </div>
                  <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                          <div class="panel-body">
                              @if(isset($custom_values))
                              <ul>
                               <?php //$complaints_list=explode(' ',$complaints[0]->complaint); ?>
                               @foreach($custom_values as $custom_value)
                                   <?php $custom_list=preg_split('/\r\n|\r|\n/',$custom_value->data1); ?>
                                   <?php //var_dump($complaints_list); ?>
                                    @foreach($custom_list as $custom_listdata)

                                   <li>{{$custom_listdata}}</li>

                                   @endforeach
                                @endforeach
                              </ul>
                              @endif
                            </div>
                           
                  </div>
                </div>    

                </div>
            </div>

</div>
</div>
</div>
</div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">              
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>
</body>
</html>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ URL::asset('assets/plugins/signature_pad.min.js') }}"></script>
  <script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
<script>

  var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
    backgroundColor: 'rgba(255, 255, 255, 0)',
    penColor: 'rgb(0, 0, 0)'
  });

  document.getElementById('clear').addEventListener('click', function () {
                signaturePad.clear();
            });




 $(".aprove_btn").click(function(e){
      var id = $(this).attr('id');
      var reference_id = $(this).attr("data-id");
      var d = new Date();

      var month = d.getMonth()+1;

      var day = d.getDate();

      var output = d.getFullYear() + '/' + ((''+month).length<2 ? '0' : '') + month + '/' +((''+day).length<2 ? '0' : '') + day +' '+d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();


      html2canvas([document.getElementById('signature-pad')], {
          onrendered: function (canvas) {
            var canvas_img_data = canvas.toDataURL('image/png');
            var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
            //ajax call to save image inside folder
            $.ajax({
              url: '{{ url("status") }}/' + id,
              data: { 
                 _token: '{{csrf_token()}}',
                  _method:'PATCH',
                img_data:img_data,
                approval_status:1,
                job_card_status:4,
                approved_on:output,
                reference_id:reference_id 
              },
              type: 'post',
              dataType: 'json',
              success: function (data, textStatus, jqXHR) {
                if(data.status == 1){
                  $('.estimation').css({display: "none"}); 
               
                  $('.alert').css({display: "block"});
                 }
              }
            });
          }
      });
  });

  $(function() {
    $('.pop').on('click', function() {
      console.log($(this).attr('src'));
      $('.imagepreview').attr('src', $(this).attr('src'));
      $('#imagemodal').modal('show');   
  });   
});
</script>
