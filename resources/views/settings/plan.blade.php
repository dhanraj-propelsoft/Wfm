@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
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
		  <h4 class="float-left page-title">My Plan</h4>
		</div>
<br /><br /><br /><br />

<div class="col-md-8 offset-md-2">
	@if(App\Custom::plan_is_activated())
		<div class="plan-box">
			<div class="plan-box-header clearfix">
				
					<div class="subscription-box">
						<font style="font-size: 22px">{{$package->plan or $free_plan}}</font>
						<span>Validity:  {{$package->expire_on or $expires_on}}</span>
						@if(empty($package->plan_name))
							<a href="{{ route('subscribe', ['type' => 'upgrade']) }}" class="btn subscription-btn" title="">Upgrade Plan</a>

							<!-- <a href="{{ route('register_business') }}" class="btn subscription-btn" title="">Upgrade Plan</a> -->

						@elseif($package->plan_name && ($renew == true))
							<a href="{{ route('subscribe', ['type' => 'renew']) }}" class="btn subscription-btn" title="">Renew Plan</a>
						@elseif($package->plan_name && ($renew != true))
							<a href="{{ route('subscribe', ['type' => 'plan-change']) }}" class="btn subscription-btn" title="">Change Plan</a>
						@endif
							<!-- <a href="#" class="btn subscription-btn" title="">Change Plan</a> -->
					</div>
				
			</div>
			<table class="table plan_table">
			<thead>
			<tr>
			<th style="text-align: center;"> </th>
			
			<th style="text-align: center;"> Total </th>
			<th style="text-align: center;"> Used </th>
			<th style="text-align: center;"> Available </th>
			</tr>
			</thead>
			<tbody>
				@foreach($organization_addons as $addon)
				<tr>
					<td valign="middle" style="font-size: 16px; " align="center"> <span class=" bold">{{$addon->display_name}}</span> <br>

					 <a href="{{ route('addon_subscribe', ['type' => 'addon_upgrade',$addon->id]) }}" style="font-size: 11px;" title="">Add More</a>
					 </td>
					
					<td valign="middle" align="center"> {{$addon->value}} </td>
					<td valign="middle" align="center"> {{$addon->used}} </td>
					<td valign="middle" align="center"> 
						<div  style="font-size: 14px; " class="badge badge-default bold">{{$addon->value - $addon->used}}</div> 
					</td>
				</tr>
				@endforeach
			</tbody>
			</table>
		</div>
		@else
		<h3 style="text-align: center; ">Subscription is not activated. Contact PropelSoft for more details.</h3>
		@endif
	</div>

					
@stop

@section('dom_links')
@parent
   <script type="text/javascript">

	$(document).ready(function() {

	});

	</script>
@stop