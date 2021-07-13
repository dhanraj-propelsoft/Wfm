@extends('layouts.master')
@include('includes.utility')
@section('content')
@section('module')
@parent
CRM <small>System</small>
@stop
@section('breadcrumbs')
@parent
					<li>
						<a href="#">Role</a>
					</li>
					@stop

<div class="row profile">
				<div class="col-md-12">
					<!--BEGIN TABS-->
					<div class="tabbable-line tabbable-full-width">
						
								<div class="row">

									<div class="col-md-9">

<div class="row">

		<div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Name:</strong>

                {{ $role->display_name }}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Description:</strong>

                {{ $role->description }}

            </div>

        </div>


 <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Permissions:</strong>

                @if(!empty($rolePermissions))

					@foreach($rolePermissions as $v)

						<label class="label label-success">{{ $v->display_name }}</label>

					@endforeach

				@endif

            </div>

        </div>


										
										</div>
									</div>
								</div>

					</div>
					<!--END TABS-->
				</div>
			
			</div>

<div class="row">
				<div class="col-md-12">
				
				<h1></h1>
<p class="lead"></p>

<div class="row">
    <div class="col-md-6">
        <a href="{{ route('roles.index') }}" class="btn btn-info">Back to Roles</a>
    </div>
</div>
				
				</div>
			</div>
			

			
			@stop






