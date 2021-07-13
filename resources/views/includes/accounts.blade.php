@section('sidebar')
@parent
@if(Session::get('organization_id'))

@if (App\Organization::checkModuleExists('books', Session::get('organization_id')))

	<?php 
		$plan =['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];		 
	?>

		@if(App\Organization::checkPlan($plan, Session::get('organization_id')))

			<?php
				$plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'),$return_plan=true);
			?>

			@include('includes.accounts_free')
			@include('includes.accounts_starter')
			@include('includes.accounts_lite')
			@include('includes.accounts_standard')
			@include('includes.accounts_professional')
			@include('includes.accounts_enterprise')
			@include('includes.accounts_corporate')


		@endif



@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'books');
?>