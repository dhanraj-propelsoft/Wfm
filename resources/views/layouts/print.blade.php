<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="{{ URL::asset('') }}favicon.ico" type="image/x-icon">
<link rel="icon" href="{{ URL::asset('') }}favicon.ico" type="image/x-icon">
<meta content="" name="description" />
<meta content="" name="author" />
<noscript>
<meta http-equiv="refresh" content="0; URL={{url('script')}}">
</noscript>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('head_links')
<title>{{ config('app.name', 'Laravel') }}</title>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/theme.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/linecon.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/background.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/stylesheet.css') }}">
@show
</head>

<body>
@yield('content')
@section('dom_links') 
<!-- Scripts --> 
<!--[if lt IE 9]>
		<script src="{{ URL::asset('assets/plugins/respond.min.js') }}"></script>
		<script src="{{ URL::asset('assets/plugins/excanvas.min.js') }}"></script> 
		<![endif]--> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/modernizr-custom.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-3.2.1.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/popper.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}" ></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/moment.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/select2/js/select2.full.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/screenfull.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/row-sorter.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/layout/js/custom.js') }}"></script> 
@show
@section('foot_links')
@show
</body>
</html>
