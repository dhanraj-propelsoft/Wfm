@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('trade', Session::get('organization_id')))

	<?php 
		$plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];	
		 
	?>

		@if(App\Organization::checkPlan($plan, Session::get('organization_id')))

			<?php
				$plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
			?>

			@include('includes.trade_free')
			@include('includes.trade_starter')
			@include('includes.trade_lite')
			@include('includes.trade_standard')
			@include('includes.trade_professional')
			@include('includes.trade_enterprise')
			@include('includes.trade_corporate')


		@endif		  	


@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'trade');
?>