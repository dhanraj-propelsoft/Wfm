@extends('layouts.master')
@section('head_links') @parent

@stop
@include('includes.admin')
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
	
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.pie.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.resize.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/flot/jquery.flot.categories.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/charts/jquery-easypiechart/jquery.easypiechart.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script> 
<script type="text/javascript">
	$(document).ready(function() {


	});

</script> 
@stop