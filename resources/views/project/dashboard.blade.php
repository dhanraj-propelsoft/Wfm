@extends('layouts.master')
@include('includes.project')
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
        
        </div>





					
@stop

@section('dom_links')
@parent

   <script type="text/javascript">
   
	</script>
@stop