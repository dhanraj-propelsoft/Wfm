@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@section('head_links') @parent
<style>


  	#suppliers, #notifications {
		width: 600px;
		height: 350px;
	}

</style>
@stop
@include('includes.inventory')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header"  style="height:45px;width: 102%;background-color: #e3e3e9;margin-left: -10px;margin-bottom: 20px;">
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
    @include('inventory.dashboard_search')

</div>
<!-- <div class="row">
  <div class="col-md-12">
	<div class="dashboard_container">
	  <div class="title_container">
		<h5>Cash Flow</h5>
		<div class="dashboard_option_container">
		  <div class="dashboard_option_action">This month <i class="fa fa-caret-down "></i> </div>
		  <ul class="dashboard_option_list">
			<li><a class="multidelete">This month</a></li>
			<li><a class="multitime">This financial quarter</a></li>
			<li><a class="multitime">This financial year</a></li>
			<li><a class="multidelete">Last month</a></li>
			<li><a class="multitime">Last financial quarter</a></li>
			<li><a class="multitime">Last financial year</a></li>
		  </ul>
		</div>
	  </div>
	  <div id="income_expense"></div>
	</div>
  </div>
</div> -->
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.pie.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.resize.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.categories.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.waypoints.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/counterup/jquery.counterup.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript">
	var datatable = null;
	$(document).ready(function() {
		 load_map();

         $('.search').on('click',function(){
            //alert();
            var from_date = $('input[name=from_date]').val();
            var to_date = $('input[name=to_date]').val();
            $.ajax({
                type: 'POST',
                url : '{{ route('inventory.dashboard_search') }}',
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