@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('inventory', Session::get('organization_id')))

	<?php 
		$plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];	
		 
	?>

		@if(App\Organization::checkPlan($plan, Session::get('organization_id')))

			<?php
				$plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
			?>

			@include('includes.settings_free')
			@include('includes.settings_starter')
			@include('includes.settings_lite')
			@include('includes.settings_standard')
			@include('includes.settings_professional')
			@include('includes.settings_enterprise')
			@include('includes.settings_corporate')


		@endif
		

	@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'settings');
?>