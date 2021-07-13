@section('sidebar')
@parent
	<li class="header"><span>Vehicle Management</span></li>
      <li><a data-link="dashboard" href="{{ route('user.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
     
      <li><a data-link="vehicle_register" href="{{ route('vehicle_register') }}"> <i class="fa fa-truck"></i><span>My Vehicle Registration</span></a></li>
      <li><a data-link="MyComplaints" href="{{ route('vehicle_complaint') }}"><i class="fa fa-file-text-o"></i><span>Complaints</span></a></li>
      <li><a data-link="VehicleReport" href="{{ route('vehicle_report.index') }}"><i class="fa fa-clipboard"></i><span>VehicleReport</span></a></li>
      
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'vms');
?>