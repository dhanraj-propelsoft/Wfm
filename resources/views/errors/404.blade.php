@extends('layouts.error')
@section('content')

<div class="center-vertical">
    <div class="center-content row">
        <div class="col-md-6 wow bounceInDown center-margin">
            <div class="server-message">
                <h2 style="margin-bottom: 10px">Error 404</h2>
                <h4>Page Not Found!</h4>
                <br>
                <br><br>
                <h5>The page you were looking for is removed or moved.</h5>
                <button onclick="goBack()" class="btn btn-lg btn-primary">Return to previous page</button>
            </div>
        </div>

    </div>
</div>
@stop