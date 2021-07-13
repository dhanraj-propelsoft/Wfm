<?php Log::info('JobCard-ListView-Blade:-before master extends');?>
<!-- Master page to render the whole application. left and top bar with center screens -->
@extends('layouts.master')
<?php Log::info('JobCard-ListView-Blade:-After master extends');?>
<!-- Stylesheet link - using head links from master blade..@parent includes head linkd in this page-->
@section('head_links') @parent

<link rel="stylesheet" type="text/css" href="{{ URL::asset('resources/views/trade_wms/jobcard/JobCardListView/JobCard-ListView.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/jquery.contextMenu.min.css') }}"/>


@stop
<?php Log::info('JobCard-ListView-Blade:-Before include trade_wms page');?>
<!-- Left navigation for trade_wms module-->
@include('includes.trade_wms')
<?php Log::info('JobCard-ListView-Blade:-After include trade_wms page');?>

@section('content')

<?php Log::info('JobCard-ListView-Blade:-Before include JobCard-Advance-Popup ');?>
<!-- Jobcard advance payment popup-->
@include('trade_wms.jobcard.JobCard-Advance-Popup')
<?php Log::info('JobCard-ListView-Blade:-After include JobCard-Advance-Popup');?>

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
<div class="alert alert-danger"></div>
@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif	

<div class="fill header" style="height:40px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
	<?php Log::info('JobCard-ListView-Blade:-Header Inside');?>
	<div class="row" style="padding-top: 5px;">
		<div style="float: left;margin-right: auto; padding-left: 20px;">
			<h5 class="float-left page-title" style="letter-spacing: 2px;"><b>JOB CARDs</b></h5>
		</div>
<!-- 		<div class="float-center form-inline">		 -->
		<div class="btn-group btn-group-sm form-inline float-right" style="padding-left: 10px;padding-right: 5px;height:25px;">
                {{ Form::text('search_text', null, ['class'=>'form-control ', 'data-placement'=>'top', 'placeholder' => 'JC # / Registration #','style' => 'border-radius: 4px 4px 4px 4px;width:130px;height:25px;']) }}&nbsp;/&nbsp;
				{{ Form::select('jobcard_status',jobCardStatuses(),'ALL',['class' => 'form-control','placeholder' => '-- Status --','style' => 'border-radius: 4px 4px 4px 4px;width:140px;height:25px;','id'=>"jobcard_status"]) }}
				{{ Form::select('date_range',dateRangeFilter(),'LAST_24_HOURS',['class' => 'form-control','placeholder' => '-- Date Range --','style' => 'border-radius: 4px 4px 4px 4px;width:120px;height:25px;']) }}
				&nbsp;(&nbsp;
				{{ Form::text('from_date',null,['class' => 'form-control date-picker', 'placeholder' => 'From Date', 'data-date-format' => 'yyyy-mm-dd','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;',"disabled"=>true,"id"=>"from_date"]) }}
				&nbsp;-&nbsp;
				{{ Form::text('to_date',null,['class' => 'form-control date-picker','placeholder' => 'To Date','data-date-format' => 'yyyy-mm-dd','style' => 'border-radius: 4px 4px 4px 4px;width:100px;height:25px;',"disabled"=>true,"id"=>"to_date"]) }}
				&nbsp;)&nbsp;
				<button style="height:25px;color: #fff;border-radius: 3px;border:2px solid white;vertical-align:middle; text-align:center;line-height:10px;" type="submit" data-original-title="Search" data-toggle="tooltip" class="date btn btn-success search"><i class="fa fa-search" ></i></button>
				<button style="height:25px;color: #fff;border-radius: 3px;border:2px solid white;vertical-align:middle; text-align:center;line-height:10px;"" type="button" data-original-title="Reset Search" data-toggle="tooltip" class="date btn btn-success reset"><i class="fa fa-refresh" ></i></button>
		</div>		
		<div class="btn-group btn-group-sm float-right" style="padding-left: 10px;padding-right: 30px;height:25px;">
			<a class="btn btn-success float-left add transaction_limit " style="color: #fff;border-radius: 3px;border:2px solid white;vertical-align:middle; text-align:center;line-height:10px;"><i class="fa fa-plus"></i> New</a>
			<a class="btn btn-success float-left excel_export" style="color: #fff;border-radius: 3px;border:2px solid white;vertical-align:middle; text-align:center;line-height:10px;"><i class="fa fa-download"></i> Download</a>
		</div> 
	</div>
	<?php Log::info('JobCard-ListView-Blade:-Header Outside');?>
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
	
	<?php Log::info('JobCard-ListView-Blade:-Inside ');?>
	
		<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
			<thead>
				
				<th>Number</th>
				<th>Registration #</th>
				<th style="width:125px">Customer</th>
				<th>Assigned To</th>
				<th  style="width:100px">Advance Paid  &nbsp;(<i class="fa fa-inr" ></i>)</th>
				<th>Last Modified</th>
				<th>Created </th>
				<th>Status</th>
			
				{{-- <th>Id</th>
				<th>Vechicle Id</th>
				<th>Registration No</th>
				<th>Order No</th> --}}
			</thead>
			<tbody>
				
			</tbody>
		</table>
		
	<?php Log::info('JobCard-ListView-Blade:-End ');?>
</div>

@stop

@include('trade_wms.jobcard.JobCardListView.JobCard-ListView-JavaScript')