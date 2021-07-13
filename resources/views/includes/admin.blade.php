@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('super_admin', Session::get('organization_id')))

	<?php 
		$plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];	
		 
	?>

		@if(App\Organization::checkPlan($plan, Session::get('organization_id')))

			<?php
				$plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
			?>

			@include('includes.admin_free')
			@include('includes.admin_starter')
			@include('includes.admin_lite')
			@include('includes.admin_standard')
			@include('includes.admin_professional')
			@include('includes.admin_enterprise')
			@include('includes.admin_corporate')


		@endif



	
@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'super_admin');
?>