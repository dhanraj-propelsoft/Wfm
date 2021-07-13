@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))

	<?php 
		$plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];	
		 
	?>

		@if(App\Organization::checkPlan($plan, Session::get('organization_id')))

			<?php
				$plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
			?>

			@include('includes.wfm_free')
			@include('includes.wfm_starter')
			@include('includes.wfm_lite')
			@include('includes.wfm_standard')
			@include('includes.wfm_professional')
			@include('includes.wfm_enterprise')
			@include('includes.wfm_corporate')


		@endif
		

	@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'wfm');
?>