		
	@if($plan_name =='Lite')

		<li class="header"><span>Admin</span></li>
	<li><a data-link=""><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
	<li>
		<a class="sub-menu"><i class="fa fa-object-group"></i>
			<span>Entities</span>
		</a>
		<div class="sidebar-submenu">
			<ul>
			  	<li>
			  	  <a data-link="organization" href="{{ route('organization.index') }}"><span>Organization</span></a>
			  	</li>
			 	<li>
			 		<a data-link="people" href="{{ route('people.index') }}">
			 			<span>People</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="person" href="{{ route('person.index') }}"><span>Persons</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="entity_mapping"href="{{ route('entity_mapping.index') }}" ><span>Entity Mappings</span>
			 		</a>
			 	</li>
			</ul>
		</div>
	</li>
	<li>
		<a class="sub-menu">
			<i class="fa fa-building-o"></i>
			<span>Business Type</span>
		</a>
		<div class="sidebar-submenu">
			<ul>
			  	<li>
			  		<a data-link="business-nature" href="{{ route('business_nature.index') }}">
			  		<span> Bussiness Nature of Business</span>
			  		</a>
			  	</li>
			 	<li>
			 		<a data-link="business-professionalism" href="{{ route('business_professionalism.index') }}"><span>Business Profession</span>
			 		</a>
			 	</li>
			</ul>
		</div>
	</li>
	<li>
		<a class="sub-menu"><i class="fa fa-book"></i>
			<span>Books</span>
		</a>
			<div class="sidebar-submenu">
				<ul>
			  		<li>
			  			<a data-link="bank-account-type" href="{{ route('bank_account_type.index') }}">
			  			 <span>Bank Account Type</span>
			  			</a>
			  		</li>
			 		<li>
			 			<a data-link="">
			 				<span>Customer Type</span>
			 			</a>
			 		</li>
				 	<li>
				 		<a data-link=""><span>Banks</span>
				 		</a>
				 	</li>
				</ul>
			</div>
	</li>

	<li>
		<a class="sub-menu"><i class="fa icon-basic-notebook"></i><span>Organisation Credits</span>
		</a>
		<div class="sidebar-submenu">
			<ul>
					<li>
			  		<a data-link="Exceeded" href="{{ route('organization_exceeded') }}"><span>Exceeded</span>
			  		</a>
			  	</li>
			  	<li>
			  		<a data-link="ledger" href="{{ route('organization_ledgers') }}"><span>Ledgers</span>
			  		</a>
			  	</li>
			 	<li>
			 		<a data-link="credits_sms" href="{{ route('organization_sms') }}"><span>SMS</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="credits_memorysize" href="{{ route('organization_memorysize') }}">
			 		<span>Memory Size</span>

			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Employee" href="{{ route('organization_employee') }}"><span>Employee</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Customer" href="{{ route('organization_customer') }}"><span>Customer</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Supplier" href="{{ route('organization_supplier') }}"><span>Supplier</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Purchase" href="{{ route('organization_purchase') }}"><span>Purchase</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Invoice" href="{{ route('organization_invoice') }}"><span>Invoice</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_GRN" href="{{ route('organization_grn') }}"><span>GRN</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Vehicles" href="{{ route('organization_vehicle') }}"><span>Vehicles</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Jobcard" href="{{ route('organization_jobcard') }}"><span>Job Card</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Transaction" href="{{ route('organization_transaction') }}"><span>Transaction</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="Credits_Print" href="{{ route('organization_print') }}"><span>Print Template</span>
			 		</a>
			 	</li>
			 	
			 	<li>
			 		<a data-link="Credits_Call" href="{{ route('organization_call') }}"><span>Call In Hour</span>
			 		</a>
			 	</li>

			</ul>
		</div>
	</li>

	<li>
		<a class="sub-menu"><i class="fa  fa-envelope" style="font-size:24px"></i>
			<span>SMS</span>
		</a>
		<div class="sidebar-submenu">
			<ul>

			  	<li>
			  	  <a data-link="sender-id" href="{{ route('sender_id') }}">
			  	 	<span>Sender-ID</span>
			  	  </a>
			  	</li>
			 	<li>
			 		<a data-link="sms" href="{{ route('sent_sms') }}" >
			 			<span>Sent SMS</span>
			 		</a>
			 	</li>
			 	<li>
			 		<a data-link="" ><span>SMS Messages</span>
			 		</a>
			 	</li>
			</ul>
	 	</div>
	</li>
	<li>
		<a class="sub-menu"><i class="fa icon-basic-accelerator"></i><span>Products/Items</span>
		</a>
		<div class="sidebar-submenu">
		<ul>
		  	<li>
		  		<a data-link="Main_Categories" href="{{ route('main_category.index') }}"><span>Main Category</span></a>
		  	</li>
		 	<li><a data-link="Categories" href="{{ route('Admin_category.index') }}" ><span>Category</span></a></li>
		 	<li><a data-link="Types" href="{{ route('type.index') }}"><span>Type</span></a></li>
		    <li><a data-link="make" href="{{ route('make.index') }}"><span>Make</span></a></li>
		 	<li><a data-link="Items" href="{{ route('model.index') }}"><span>Item</span></a></li>
		 	

		 			</ul>
	</div>
	</li>
	
	
		 	<li><a class="sub-menu"><i class="fa fa-car"></i><span>Vehicle Masters</span></a>
	<div class="sidebar-submenu">
		<ul>
			<li><a data-link="vehicle_type" href="{{ route('vehicle_type.index') }}"><span>Type</span></a></li>
		 	<li><a data-link="VehicleMasters_Category" href="{{ route('VehicleMasters_Category') }}"><span>Category</span></a></li>
		 	<li><a data-link="VehicleMasters_Make" href="{{ route('VehicleMasters_Make') }}"><span>Make</span></a></li>
			<li><a data-link="VehicleMasters_Model" href="{{ route('VehicleMasters_Model') }}"><span>Model</span></a></li>
			<li><a data-link="VehicleMasters_Varient" href="{{ route('VehicleMasters_Varient') }}"><span>Varient</span></a></li>
			<li ><a data-link="registered-vehicles" data-toggle="tooltip" data-placement="top" title="Registered Vehicles" href="{{ route('admin_vehicle_registered.index') }}"><span>Registered Vehicles</span></a></li>
		</ul>
	</div>
	</li>
	<li><a class="sub-menu"><i class="fa fa-area-chart"></i><span>Statistics</span></a><div class="sidebar-submenu">
		<ul>
		 	<li><a data-link="Statistics_Organization" href="{{ route('Statistics_Organization') }}"><span>Organization</span></a></li>
		 	<li><a data-link="Overall" href="{{ route('statistics_overall') }}" ><span>Overall</span></a></li>
			<li><a data-link="" ><span>Trend</span></a></li>
			<li><a data-link=""><span>Subscribed Amount</span></a></li>
			<li><a data-link=""><span>Users</span></a></li>
		</ul>
	</div></li>
<li><a class="sub-menu"><i class="fa  fa-support"></i><span>Supports</span></a><div class="sidebar-submenu">
		<ul>
			<li><a data-link="Support_Ticket" href="{{ route('Support_Ticket.index') }}" ><span>Support_Ticket</span></a></li>
		
		 	<li><a data-link=""><span>Send Messages</span></a></li>
					</ul>
	</div></li>
	<li><a data-link="gst page" href="{{ route('gst') }}"><i class="fa fa-percent"></i><span>GST Code</span></a></li>
	<!-- <li><a data_link="Support_Ticket"  href="{{ route('Support_Ticket.index') }}"><i class="fa fa-ticket"></i><span>Support_Ticket</span></a></li> -->
	
<!-- ss="fa icon-basic-accelerator"></i><span>Ledgers</span></a></li> -->
<li><a href="{{ route('broadcast') }}"><i class="fa fa-envelope"></i><span>Broadcast</span></a></li>
<li><a href="{{ route('import_csv_data_to_table') }}"><i class="fa fa-dashboard"></i><span>Global Item Import </span></a></li>
	@endif	