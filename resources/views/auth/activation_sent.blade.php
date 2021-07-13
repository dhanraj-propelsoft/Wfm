@extends('layouts.app')
@section('content')
<header class="full_background">
  <div class="container" id="maincontent" tabindex="-1">
    <div class="row">
      <div class="col-lg-12"> 
        <!--<img class="img-responsive" src="img/profile.png" alt="">-->
        <div class="intro-text"> 
          <!-- <h2 class="head_name">Activation link</h2> -->
          <h5 class="activation_msg">{{$activation['message']}}</h5>
          <hr class="star-light fancy-line">
        </div>
      </div>
    </div>
  </div>
</header>
@stop

@section('dom_links')
@parent 
<script>


</script> 
@stop