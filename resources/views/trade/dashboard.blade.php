@extends('layouts.master')
@section('head_links') @parent
<style>


  #consumers, #sales {
		width: 600px;
		height: 320px;
	}
}


</style>
@stop
@include('includes.trade')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header" style="height:45px;width: 102%;background-color: #e3e3e9;margin-left: -10px;margin-bottom: 20px;">
    <div class="row" style="padding-top: 5px;">
        <div style="float: left;margin-left: 40px;">
            <h5 class="float-left page-title"><b>Dashboard</b></h5>
        </div>
        <div class="float-right form-inline" style="margin-left: 66%;">
          <!-- {{ Form::label('from_date','From date') }} -->
           {{ Form::text('from_date',$six_month_view,['class' => 'form-control date-picker', 'placeholder' => 'From Date', 'data-date-format' => 'dd-mm-yyyy','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
            {{ Form::text('to_date',$today_view,['class' => 'form-control date-picker','placeholder' => 'To Date','data-date-format' => 'dd-mm-yyyy','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;']) }}
            <button style=" height:25px;margin-left: 15px; border-radius: 3px 3px 3px 3px" type="submit" class="date btn btn-success search"><i class="fa fa-search" ></i></button>
        </div>
    </div>
</div>
<div  id="dashboard_search">
    @include('trade.dashboard_search')
    
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
	$(document).ready(function() {
        
         load_map();

         $('.search').on('click',function(){
            //alert();
            var from_date = $('input[name=from_date]').val();
            var to_date = $('input[name=to_date]').val();
            $.ajax({
                type: 'POST',
                url : '{{ route('trade.dashboard_search') }}',
                data : {
                    _token : '{{ csrf_token() }}',
                    from_date : from_date,
                    to_date : to_date
                },
                success:function(data){
                    //console.log(data.sales_data);
                    
                        $("#dashboard_search").html(data);
                        load_map();


                },
                error:function()
                {

                }

            });


        });
     });

</script> 
@stop