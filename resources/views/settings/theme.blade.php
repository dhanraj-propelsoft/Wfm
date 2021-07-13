@extends('layouts.master')
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
          <h4 class="float-left clearfix page-title">Theme Settings</h4>

          <br><br><br>
		  <h5>Header</h5>

          <div class="theme-color clearfix">
                <h6>Solids</h6>
                <a class="set-header-theme bg-blue" data-class="bg-blue">Blue</a>
                <a class="set-header-theme bg-green" data-class="bg-green">Green</a>
                <a class="set-header-theme bg-red" data-class="bg-red">Red</a>
                <a class="set-header-theme bg-orange" data-class="bg-orange">Warning</a>
                <a class="set-header-theme bg-black" data-class="bg-black">Black</a>
                <a class="set-header-theme bg-yellow" data-class="bg-yellow">Yellow</a>
                <a style="border:1px solid #ddd !important" class="set-header-theme" data-class="bg-white">Transparent</a>

                <div class="clear"></div>
<br><br>
                <h6>Gradients</h6>
                <a class="set-header-theme bg-gradient-1" data-class="bg-gradient-1">Gradient 1</a>
                <a class="set-header-theme bg-gradient-2" data-class="bg-gradient-2">Gradient 2</a>
                <a class="set-header-theme bg-gradient-3" data-class="bg-gradient-3">Gradient 3</a>
                <a class="set-header-theme bg-gradient-4" data-class="bg-gradient-4">Gradient 4</a>
                <a class="set-header-theme bg-gradient-5" data-class="bg-gradient-5">Gradient 5</a>
                <a class="set-header-theme bg-gradient-9" data-class="bg-gradient-9">Gradient 6</a>
                <a class="set-header-theme bg-gradient-7" data-class="bg-gradient-7">Gradient 7</a>
                <a class="set-header-theme bg-gradient-8" data-class="bg-gradient-8">Gradient 8</a>
                <a class="set-header-theme bg-gradient-10" data-class="bg-gradient-10">Gradient 10</a>
            </div>



<br><br><br>
			<h5>Sidebar</h5>


            <div class="theme-color clearfix">
                <h6>Solids</h6>
                <a class="set-sidebar-theme bg-blue" data-class="bg-blue">Blue</a>
                <a class="set-sidebar-theme bg-green" data-class="bg-green">Green</a>
                <a class="set-sidebar-theme bg-red" data-class="bg-red">Red</a>
                <a class="set-sidebar-theme bg-orange" data-class="bg-orange">Warning</a>
                <a class="set-sidebar-theme bg-black" data-class="bg-black">Black</a>
                <a class="set-sidebar-theme bg-yellow" data-class="bg-yellow">Yellow</a>
                <a style="border:1px solid #ddd !important" class="set-sidebar-theme" data-class="bg-trans">Transparent</a>

                <div class="clear"></div>
<br><br>
                <h6>Gradients</h6>
                <a class="set-sidebar-theme bg-gradient-1" data-class="bg-gradient-1">Gradient 1</a>
                <a class="set-sidebar-theme bg-gradient-2" data-class="bg-gradient-2">Gradient 2</a>
                <a class="set-sidebar-theme bg-gradient-3" data-class="bg-gradient-3">Gradient 3</a>
                <a class="set-sidebar-theme bg-gradient-4" data-class="bg-gradient-4">Gradient 4</a>
                <a class="set-sidebar-theme bg-gradient-5" data-class="bg-gradient-5">Gradient 5</a>
                <a class="set-sidebar-theme bg-gradient-9" data-class="bg-gradient-9">Gradient 6</a>
                <a class="set-sidebar-theme bg-gradient-7" data-class="bg-gradient-7">Gradient 7</a>
                <a class="set-sidebar-theme bg-gradient-8" data-class="bg-gradient-8">Gradient 8</a>
                <a class="set-sidebar-theme bg-gradient-10" data-class="bg-gradient-10">Gradient 10</a>
            </div>
       
        </div>





					
@stop

@section('dom_links')
@parent

<script type="text/javascript">

$(document).ready(function() {

    $('.set-header-theme').on('click', function() {
        var theme = $(this).data('class');
        $('#page-header').removeClass().addClass(theme);
        $.ajax({
             url: "{{ route('change_theme') }}",
             type: 'post',
             data: {
                _token :"{{ csrf_token() }}",
                header: theme
                },
             dataType: "json",
                success:function(data, textStatus, jqXHR) {
                },
             error:function(jqXHR, textStatus, errorThrown) {
                //alert("New Request Failed " +textStatus);
                }
            });
    });

    $('.set-sidebar-theme').on('click', function() {
        var theme = $(this).data('class');
        if(theme == "bg-trans") {
            theme = "' '";
            $('#page-sidebar').removeClass();
        } else {
            theme = "gradient "+theme;
            $('#page-sidebar').removeClass().addClass('gradient').addClass(theme);
        }
        $.ajax({
             url: "{{ route('change_theme') }}",
             type: 'post',
             data: {
                _token :"{{ csrf_token() }}",
                sidebar: theme
                },
             dataType: "json",
                success:function(data, textStatus, jqXHR) {
                },
             error:function(jqXHR, textStatus, errorThrown) {
                //alert("New Request Failed " +textStatus);
                }
            });
    });

});

</script>
@stop