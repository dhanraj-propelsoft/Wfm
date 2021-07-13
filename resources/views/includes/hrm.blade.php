@section('sidebar')
@parent
@if(Session::get('organization_id'))

@if (App\Organization::checkModuleExists('hrm', Session::get('organization_id')))

	<?php 
		$plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];	
		 
	?>

		@if(App\Organization::checkPlan($plan, Session::get('organization_id')))

			<?php
				$plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
			?>

			@include('includes.hrm_free')
			@include('includes.hrm_starter')
			@include('includes.hrm_lite')
			@include('includes.hrm_standard')
			@include('includes.hrm_professional')
			@include('includes.hrm_enterprise')
			@include('includes.hrm_corporate')


		@endif

	 
		@endif
	
	@endif
	


@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'hrm');
?>