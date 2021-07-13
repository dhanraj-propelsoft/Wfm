@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('fuel_station', Session::get('organization_id')))

	  	
	  	<?php 
			$plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];
		?>

		@if(App\Organization::checkPlan($plan, Session::get('organization_id')))

			<?php
				$plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
			?>

			@include('includes.fuel_station_free')
			@include('includes.fuel_station_starter')
			@include('includes.fuel_station_lite')
			@include('includes.fuel_station_standard')
			@include('includes.fuel_station_professional')
			@include('includes.fuel_station_enterprise')
			@include('includes.fuel_station_corporate')


		@endif

	
@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'fuel_station');
?>