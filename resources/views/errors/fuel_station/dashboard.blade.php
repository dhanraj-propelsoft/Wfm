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
@include('includes.fuel_station')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Dashboard</h4>
</div>
<div class="clearfix"></div>

    	<div class="row">
    		<a href="">
    			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    				<div class="dashboard-stat" style="background: #5cb85c;">
    					<div class="visual">
    						<i class="fa fa-users"></i>
    					</div>
    					<div class="details">						
    							<div class="number">								
    								<span class="counter">100</span>								
    							</div>						
    						<div class="desc"> Customers </div>
    					</div>
    					<a class="more" href="javascript:;"> &nbsp;&nbsp;</a>
    				</div>
    			</div>
            </a>
             
    	</div>

		<div class="row">
		      <div class="col-md-6">
		        <div class="dashboard_container">
		              <div class="title_container">
		                <a href="">
		                <h5 style="color:black;">Top 10 Consumers</h5>
		                </a>
		              </div>
		              <div id="consumers"></div>
		        </div>
		      </div>
		      <div class="col-md-6">
		        <div class="dashboard_container">
		              <div class="title_container">
		                <a href="">
		                <h5 style="color:black;">Sales</h5>
		                </a>
		              </div>
		              <div id="sales"></div>
		        </div>
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


</script> 
@stop