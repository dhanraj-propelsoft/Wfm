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

			@include('includes.inventory_free')
			@include('includes.inventory_starter')
			@include('includes.inventory_lite')
			@include('includes.inventory_standard')
			@include('includes.inventory_professional')
			@include('includes.inventory_enterprise')
			@include('includes.inventory_corporate')


		@endif
		

	@endif
@endif
@stop

@section('dom_links')
@parent

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
</script>
@stop

<?php
	Session::put('module_name', 'inventory');
?>