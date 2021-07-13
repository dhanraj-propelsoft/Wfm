<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="{{ URL::asset('assets/layout/images/fav_icon.png') }}" type="image/x-icon">

<link rel="icon" href="{{ URL::asset('assets/layout/images/fav_icon.png') }}" type="image/x-icon">
<meta content="" name="description" />
<meta content="" name="author" />
<noscript>
<meta http-equiv="refresh" content="0; URL={{url('script')}}">
</noscript>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Laravel') }}</title>

<!-- Styles -->
@section('head_links')
@if(app()->environment() == "production")
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css"/>
@elseif(app()->environment() == "local")
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap/css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"/>
@endif
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/login.css') }}">
@show
</head>
<body>
@include('includes.loader')
    @yield('content')
    @section('dom_links') 
<!-- Scripts --> 
<!--[if lt IE 9]>
    <script src="{{ URL::asset('assets/plugins/respond.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/excanvas.min.js') }}"></script> 
    <![endif]--> 
@if(app()->environment() == "production")
 <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script> 
 <script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}" ></script>

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/popper.min.js"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js"></script>
@elseif(app()->environment() == "local")
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/modernizr-custom.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}" ></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/popper.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/select2/js/select2.full.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script> 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/backstretch/jquery.backstretch.min.js') }}"></script> 
@endif
<script type="text/javascript">

        $(document).ready(function() {

            $('.select_item').select2();

            $('.reset').on('click', function (e) {
                e.preventDefault();
                $('input, select, textarea').not(':input[type=button],:input[type=reset], :input[type=submit], :input[type=hidden]').attr('value', '');
                $('.select_item').select2("val", "");

                $("input[type=radio]").prop( "checked", false );
            });

            $('.datetype').keypress(function (e) {
                if (e.which != 8 && e.which != 0 && e.which != 45 && (e.which < 48 || e.which > 57)) {
                        return false;
                  } 
            });

           $('.numbers').keypress(function (e) {
                  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                               return false;
                  }  
                   $(".mobile").attr('maxlength','10'); 
            });

            $.validator.addMethod("lettersonly", function(value, element) {
               return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
            }, "Enter Only Letters"); 

              

            $('.date-picker').datepicker({
                rtl: false,
                orientation: "left",
                autoclose: true
            });

            $(".login-bg").backstretch(["{{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }}",  "{{ URL::asset('assets/layout/images/login_bg/bg3.jpg') }}"], { fade: 1e3,
                duration: 8e3 });

            $.fn.clicktoggle = function(a, b) {
                return this.each(function() {
                    var clicked = false;
                    $(this).click(function() {
                        if (clicked) {
                            clicked = false;
                            return b.apply(this, arguments);
                        }
                        clicked = true;
                        return a.apply(this, arguments);
                    });
                });
            };

        });

    </script> 
@show
</body>
</html>
