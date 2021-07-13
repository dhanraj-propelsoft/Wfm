@extends('layouts.master')
@section('head_links') @parent
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
<style>
  .pump1:hover {
  color: hotpink;
}
table{
    margin-top: -40px;
    margin-left: -10px;
    margin-right: 30px;

}
.table td, .table th {
    padding: 8.5px;
}
.title_box{
    width:360px;
    height:30px;
    border:1px ;
    margin-left: 25px;
}

 #select2-registration_number-container {
        background-color:yellow;
    }

    .dt-buttons {
      display: none;
    }
    .dataTables_length {
      margin-bottom: -35px;
    }
    .dropdown-menu > a:hover {
        background-color: #e74c3c;
        color:white;
    }
    .dropdown-menu {
        background-color: #e74c3c !important;
       min-width: 3rem;
    }
    .dropdown-menu > a {
     color: white;
    }
    {{--
@include('includes.fuel_station')
@section('content')
@include('includes.add_user')
@include('includes.add_business')
    --}}
  </style>



@stop
@include('includes.fuel_station')
@section('content')
@include('includes.add_user')
@include('includes.add_business')

<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Easy To Invoice</h4>
</div>

<div class="clearfix"></div>

    <div class="row" style="margin-top: 20px">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 registration_number ">
          
        

                {{ Form::select('registration_number', $vehicles_register, null, ['class' => 'form-control select_item registration_number', 'id' => 'registration_number','style' => 'background-color:yellow']) }}

      </div>
      
      <div class="col-md-2" style="margin-left: -30px;">
        <button class="refresh" style="height: 28px">
          <i class="fa fa-refresh"></i>
        </button>
      </div>
      

         <div class="dropdown" style="margin-left: 40px">
           <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
     Sale For New Vehicle
          
        </button>
      <div class="dropdown-menu "  aria-labelledby="dropdownMenuButton" style="width:180px">
         <a href="#" class="dropdown-item hover invoice_add_cash_sale"  data-name="cash">Cash Sale</a>
      
        <a href="#" class="dropdown-item hover  invoice_add "  data-name="credit" >Credit Sale</a>
          
    </div>
  </div>
      </div>

      <br><br>
    	
        <div class="row">


    		 @foreach($pump as $pumpdetail)
    			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
    				<div class="dashboard-stat pump1" style=" cursor:pointer;background: #5cb85c;" data-id=<?php echo"$pumpdetail->id"; ?>>
    					<div class="visual">
    						<i class="fas fa-gas-pump" style='padding-left:50px;'></i>
    					</div>
    					<div class="details">						
    							<div class="number">								
    								<span class="pumpname1"><?php echo"$pumpdetail->name"; ?></span>								
    							</div>						
    						
    					</div>
    					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
    				</div>
    			</div>
                  @endforeach

    	</div>
    	
       
      
<br><br>
<div class="row">
    <div class="title_box" ><h5> Amount Due Invoice</h5></div><br>

</div>

<div class="row">
    
    <div class="col-md-4"  style="overflow-y: scroll; height:380px;">       
            <table class="table" width="18%" style="margin-left: 10px;margin-top: 0px" >
                <thead>
                    <tr>
                        <th > Number</th>
                        <th>Vehicle </th>
                        <th> Amount</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transactions)
           
                        <tr style="  @if($transactions->status != 0) display:none @endif">
                            <td>                    
                              <a class="po_edit"  style=" cursor:pointer;" data-id="{{$transactions->id}}" data-vehicle_id="{{$transactions->vehicle_id}}">{{ $transactions->order_no }}</a>
                            </td>
                           
                            <td style="">{{$transactions->registration_no}}</td>
                            <td>{{$transactions->total}}</td>
                            <td>@if($transactions->status == 0)
                            <label class="grid_label badge badge-warning">Pending</label> 
                        @elseif($transactions->status == 1)
                            <label class="grid_label badge badge-success">Paid</label> 
                        @elseif($transactions->status == 2)
                            <label class="grid_label badge badge-info">Partially Paid</label> 
                        @elseif($transactions->status == 3)
                            <label class="grid_label badge badge-danger">Over due {{App\Custom::time_difference(Carbon\Carbon::now()->format('Y-m-d H:i:s'), Carbon\Carbon::parse($transactions->original_due_date)->format('Y-m-d'), 'd')}} days</label> 
                        @endif</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
       
        </div>
  

   
   
	
		

</div> 

@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.pie.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.resize.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.categories.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.waypoints.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.counterup.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/morris/morris.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/morris/raphael-min.js') }}"></script> 

<script type="text/javascript">

$('.invoice_add, .invoice_add_cash_sale').on('click', function(e) {
      e.preventDefault(); 
      var that = $(this);
      $('.loader_wall_onspot').show();
      $('body').css('overflow', 'hidden');
      $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

        if(that.hasClass('invoice_add_cash_sale')) {

          $.get("{{ route('transaction.create', ['job_invoice_cash']) }}", function(data) {
            $('.full_modal_content').show();
            $('.full_modal_content').html("");
            $('.full_modal_content').html(data);
            $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
            $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
            $('.loader_wall_onspot').hide();
          });
        } else {
          $.get("{{ route('transaction.create', ['job_invoice']) }}", function(data) {
            $('.full_modal_content').show();
            $('.full_modal_content').html("");
            $('.full_modal_content').html(data);
            $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
            $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
            $('.loader_wall_onspot').hide();
          });
        }
    
      });
        
    });


  $('.pump1').on('click', function(e) {
		e.preventDefault();
		var a=$(this).data('id');
    var reg_no =$('#registration_number').val();

   if(Boolean(reg_no)){
    
      $('.loader_wall_onspot').show();
      $('body').css('overflow', 'hidden');
      $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

    
        $.get("{{ url('fuel_station/shortcut_invoice_registernumber') }}/"+ reg_no+"/"+a , function(data) {
            $('.full_modal_content').show();
            $('.full_modal_content').html("");
            $('.full_modal_content').html(data);
            $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
            $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
            $('.loader_wall_onspot').hide();
          });
    
      });

    }else
    {

      $('.loader_wall_onspot').show();
        $('body').css('overflow', 'hidden');
        $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

      
          $.get("{{ url('fuel_station/shortcut_invoice_create') }}/"+ a , function(data) {
              $('.full_modal_content').show();
              $('.full_modal_content').html("");
              $('.full_modal_content').html(data);
              $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
              $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
              $('.loader_wall_onspot').hide();
            });
      
          });

       }
    
     });

  $('.po_edit').off().on('click', function(e) {
        
        isFirstIteration = true;
        var id = $(this).data('id');
        var vehicle_id = $(this).data('vehicle_id');
        if(id != "" && typeof(id) != "undefined") {
            $('.loader_wall_onspot').show();
                $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

                    $.get("{{ url('transaction') }}/"+id+"/edit/"+vehicle_id, function(data) {
                      $('.full_modal_content').show();
                      $('.full_modal_content').html("");
                      $('.full_modal_content').html(data);
                      $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
                      $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
                      $('.loader_wall_onspot').hide();
                      
                    });
        
                });
            }

        });

  $('#registration_number2').on('change', function(e) {
		e.preventDefault();
		var registration_number =$('select[name=registration_number]').val();
     
	
  });


 
  $(".pump1").hover(function(){
    $(this).css("background-color", "#058407");
    $(this).css("height", "150px");
    
    }, function(){
    $(this).css("background-color"," #5cb85c");
     $(this).css("height", "120px");
  });

  $(".pump1").on('click',function(){
    $(this).css("background-color", "#42f4e2");
   
    
    });



</script> 
@stop