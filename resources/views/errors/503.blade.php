@extends('layouts.error')
@section('content')

<div class="center-vertical">
    <div class="center-content row">
        <div class="col-md-6 wow bounceInDown center-margin">
            <div class="server-message">
                <h2 style="margin-bottom: 10px">Error 503</h2>
                <h4>Stay tuned.</h4>
                <br>
                <br><br>
                <h5>Propel is temporarily unavilable, but we're working hard to fix the problem. We'll be up and running soon. Please bear with us.</h5>
                <button onclick="goBack()" class="btn btn-lg btn-primary">Return to previous page</button>
            </div>
        </div>

    </div>
</div>
@stop