<?php Log::info('Trade_wms-Blade:-Inside');?>
@section('sidebar')
<?php Log::info('Trade_wms-Blade:-Start Sidebar');?>
@parent
    @if(Session::get('organization_id'))
        <?php Log::info('Trade_wms-Blade:-Inside parent');?>
        <?php Log::info('Trade_wms-Blade:-Before checkModuleExists');?>
        @if (App\Organization::checkModuleExists('trade_wms', Session::get('organization_id')))
            <?php Log::info('Trade_wms-Blade:-After checkModuleExists');?>
            <?php

            $plan = [
                'Free14Days',
                'Starter',
                'Lite',
                'Standard',
                'Professional',
                'Enterprise',
                'Corporate'
            ];

            Log::info('Trade_wms-Blade:-Before checkPlan');
            $plan_name = App\Organization::checkPlan($plan, Session::get('organization_id'), $return_plan = true);
            Log::info('Trade_wms-Blade:- checkPlan Name - ' . $plan_name);
            ?>
        	@if($plan_name)
        		<?php Log::info('Trade_wms-Blade:-if Plan exist add the side menu...');?>


            		<li class="header"><span> WORKSHOP </span></li>

            		<?php
        		        Log::info('Trade_wms-Blade:-if Plan exist check the side menu...CAN start');
                        $can_WMS_jobboard = Auth::user()->can('WMS-jobboard') ? 'T' : 'F';
                        $can_wms_homepage = Auth::user()->can('wms_homepage') ? 'T' : 'F';

                        $can_today_summary = Auth::user()->can('today_summary') ? 'T' : 'F';
                        $can_WMS_Main_Dashboard = Auth::user()->can('WMS-Main-Dashboard') ? 'T' : 'F';
                        $can_wms_job_status_list = Auth::user()->can('wms-job-status-list') ? 'T' : 'F';
                        $can_WMS_JC_Stock_Report = Auth::user()->can('WMS-JC-Stock-Report') ? 'T' : 'F';
                        $can_WMS_JC_CustomerPromotion_Report = Auth::user()->can('WMS-JC-CustomerPromotion-Report') ? 'T' : 'F';
                        $can_ALL_Reports_Section = Auth::user()->can('ALL-Reports-Section') ? 'T' : 'F';
                        $can_WMS_Scheduleboard = Auth::user()->can('WMS-Scheduleboard') ? 'T' : 'F';

                        $can_customer_grouping = Auth::user()->can('customer-grouping') ? 'T' : 'F';
                        $can_service_type_list = Auth::user()->can('service-type-list') ? 'T' : 'F';
                        $can_vehicle_category_list = Auth::user()->can('vehicle-category-list') ? 'T' : 'F';
                        $can_vehicle_make_list = Auth::user()->can('vehicle-make-list') ? 'T' : 'F';
                        $can_vehicle_model_list = Auth::user()->can('vehicle-model-list') ? 'T' : 'F';
                        $can_variant_list = Auth::user()->can('variant-list') ? 'T' : 'F';
                        $can_readingfactor_list = Auth::user()->can('readingfactor-list') ? 'T' : 'F';
                        $can_checklist_list = Auth::user()->can('checklist-list') ? 'T' : 'F';
                        $can_permit_type_list = Auth::user()->can('permit-type-list') ? 'T' : 'F';
                        $can_specifiaction_master = Auth::user()->can('specifiaction-master') ? 'T' : 'F';
                        $can_vehicle_specifications = Auth::user()->can('vehicle-specifications') ? 'T' : 'F';
                        $can_specification_values = Auth::user()->can('specification-values') ? 'T' : 'F';
                        $can_segment_list = Auth::user()->can('segment-list') ? 'T' : 'F';
                        $can_segment_details = Auth::user()->can('segment-details') ? 'T' : 'F';
                        $can_price_list = Auth::user()->can('price-list') ? 'T' : 'F';

                        $can_wms_customer_info_list = Auth::user()->can('wms-customer-info-list') ? 'T' : 'F';
                        $can_vehicle_register = Auth::user()->can('vehicle-register') ? 'T' : 'F';


                        $can_wms_jobcard_list = Auth::user()->can('wms-jobcard-list') ? 'T' : 'F';
                        $can_wms_estimation_list = Auth::user()->can('wms-estimation-list') ? 'T' : 'F';
                        $can_wms_job_invoice_list = Auth::user()->can('wms-job-invoice-list') ? 'T' : 'F';
                        $can_WMS_Receivables = Auth::user()->can('WMS-Receivables') ? 'T' : 'F';


                        $can_gst_report = Auth::user()->can('gst-report') ? 'T' : 'F';
                        $can_vehicle_report = Auth::user()->can('vehicle-report') ? 'T' : 'F';
        		        Log::info('Trade_wms-Blade:-if Plan exist check the side menu...CAN end');
            		?>

            		@if($can_WMS_jobboard && $can_WMS_jobboard == 'T')
            		<li><a data-link="job_board" data-toggle="tooltip" data-placement="top" title="Job Board" href="{{ route('trade_wms.job_board') }}"><i class="fa icon-basic-accelerator"></i><span>Job Board</span></a></li>
            		@endif


            		@if($can_wms_homepage && $can_wms_homepage == 'T')
            	  	<li><a data-link="home_page" data-toggle="tooltip" data-placement="top" title="Home Page" href="{{ route('home_page.index') }}"><i class="fa fa-home"></i><span> Home Page </span></a></li>
            		@endif

            	  	<li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title=" Propel Management"><i class="fa icon-basic-folder-multiple"></i><span> Propel Management </span></a>
            			<div class="sidebar-submenu">
            			  	<ul>
                        		@if($can_today_summary && $can_today_summary == 'T')
                                <li><a data-link="jobstatus_dashboard" data-toggle="tooltip" data-placement="top" title="Today Summary" href="{{ route('trade_wms.today_summary') }}"><span>Today Summary</span></a></li>
                        		@endif

                        		@if($can_WMS_Main_Dashboard && $can_WMS_Main_Dashboard == 'T')
                                <li><a data-link="dashboard" data-toggle="tooltip" data-placement="top" title="Dashboard" href="{{ route('trade_wms.dashboard') }}"><span> Dashboard </span></a></li>
                        		@endif

                        		@if($can_wms_job_status_list && $can_wms_job_status_list == 'T')
                                	<li><a data-link="job_status" data-toggle="tooltip" data-placement="top" title="Job Status" href="{{ route('Jobstatus.index') }}"><span>Job Status</span></a></li>
                        		@endif

                        		@if($can_WMS_JC_Stock_Report && $can_WMS_JC_Stock_Report == 'T')
                                <li><a data-link="low-stock-report" data-toggle="tooltip" data-placement="top" title="JC Stock Report" href="{{ route('jc_stock_report.index') }}"><span>JC Stock Report</span></a></li>
                        		@endif

                        		@if($can_WMS_JC_CustomerPromotion_Report && $can_WMS_JC_CustomerPromotion_Report == 'T')
                                 <li><a data-link="customer_promotion" data-toggle="tooltip" data-placement="top" title="Customer Promotion" href="{{ route('customer_promotion') }}"><span>Customer Promotion</span></a></li>
                        		@endif

                        		@if($can_ALL_Reports_Section && $can_ALL_Reports_Section == 'T')
                                <li><a data-link="all_reports" data-toggle="tooltip" data-placement="top" title="Reports" href="{{ route('all_reports.index') }}"><span>Reports</span></a></li>
                        		@endif

                        		@if($can_WMS_Scheduleboard && $can_WMS_Scheduleboard == 'T')
                                <li><a data-link="schedule_board" href="{{ route('trade_wms.schedule_board') }}">
                                <span>Schedule Board</span></a></li>
                        		@endif

                                <li ><a data-link="visiting_jobcard" data-toggle="tooltip" data-placement="top"  title="Next Visit View Vechile" href="{{ route('visiting_jobcard') }}"><span>Next Visit View Vechile</span></a></li>
            				</ul>
            			</div>
            		</li>

            	  	<li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Masters"><i class="fa icon-basic-folder-multiple"></i><span> Masters </span></a>
            			<div class="sidebar-submenu">
            			  	<ul>
            					<li><a data-link="discount" data-toggle="tooltip" data-placement="top" title="Discount" href="{{ route('discount.index') }}"><span> Discount </span></a></li>
            				    <li><a data-link="unit" data-toggle="tooltip" data-placement="top" title="Units" href="{{ route('unit.index') }}"><span> Units </span></a></li>
            					<li><a data-link="shipment/mode" data-toggle="tooltip" data-placement="top" title=" Shipment Mode" href="{{ route('shipment_mode.index') }}"><span> Shipment Mode</span></a>
            					</li>
            				    <li><a data-link="items" data-toggle="tooltip" data-placement="top" title="Items" href="{{ route('item.index', ['items']) }}"><span> Items </span></a></li>
            				    <li><a data-link="tax" data-toggle="tooltip" data-placement="top" title="Tax" href="{{ route('tax.index') }}"><span>Tax</span></a></li>

                        		@if($can_customer_grouping && $can_customer_grouping == 'T')
            					<li><a data-link="customer_grouping" data-toggle="tooltip" data-placement="top" title="Customer Grouping" href="{{ route('customer_grouping.index') }}"><span>Customer Grouping </span></a></li>
                        		@endif

            		  			<li ><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Vehicle Masters"><span> Vehicle Masters</span></a>
            						<div class="sidebar-submenu">
            							<ul  style="margin-left: 20px;">
                                    		@if($can_service_type_list && $can_service_type_list == 'T')
            						  		<li><a data-link="service-type" data-toggle="tooltip" data-placement="top" title="Service Type " href="{{ route('service_type.index') }}"><span> Service Type </span></a></li>
                                    		@endif

                                    		@if($can_vehicle_category_list && $can_vehicle_category_list == 'T')
            					  			<li><a data-link="vehicle/category" data-toggle="tooltip" data-placement="top" title=" Vehicle Category" href="{{ route('vehicle_category.index') }}"><span> Vehicle Category </span></a></li>
                                    		@endif

                                    		@if($can_vehicle_make_list && $can_vehicle_make_list == 'T')
            					  			<li><a data-link="vehicle/make" data-toggle="tooltip" data-placement="top" title="Vehicle Make" href="{{ route('vehicle_make.index') }}"><span> Vehicle Make </span></a></li>
                                    		@endif

                                    		@if($can_vehicle_model_list && $can_vehicle_model_list == 'T')
            					  			<li><a data-link="vehicle/model" data-toggle="tooltip" data-placement="top" title="Vehicle Model" href="{{ route('vehicle_model.index') }}"><span> Vehicle Model </span></a></li>
                                    		@endif

                                    		@if($can_variant_list && $can_variant_list == 'T')
            					  			<li><a data-link="variant" data-toggle="tooltip" data-placement="top" title="Vehicle Variant" href="{{ route('vehicle_variant.index') }}"><span> Vehicle Variant </span></a></li>
                                    		@endif

                                    		@if($can_readingfactor_list && $can_readingfactor_list == 'T')
            					  			<li><a data-link="reading-factor" data-toggle="tooltip" data-placement="top" title="Reading Factor" href="{{ route('reading_factor.index') }}"><span> Reading Factor </span></a></li>
                                    		@endif

                                    		@if($can_checklist_list && $can_checklist_list == 'T')
            					  			<li><a data-link="vehicle/checklist" data-toggle="tooltip" data-placement="top" title=" Checklist" href="{{ route('VehicleChecklist.index') }}"><span> Checklist</span></a></li>
                                    		@endif

                                    		@if($can_permit_type_list && $can_permit_type_list == 'T')
            					  			<li><a data-link="vehicle/permit-type" data-toggle="tooltip" data-placement="top" title=" Vehicle Permit Type" href="{{ route('permit_type.index') }}"><span> Vehicle Permit Type </span></a></li>
                                    		@endif

                                    		@if($can_specifiaction_master && $can_specifiaction_master == 'T')
            					  			<li><a data-link="master_specification" data-toggle="tooltip" data-placement="top" title="Specification Master" href="{{ route('specification_master.index') }}"><span> Specification Master</span></a></li>
                                    		@endif

                                    		@if($can_vehicle_specifications && $can_vehicle_specifications == 'T')
            					  			<li><a data-link="vehicle/specification" data-toggle="tooltip" data-placement="top" title="Vehicle Specifications" href="{{ route('specification.index') }}"><span> Vehicle Specifications </span></a></li>
                                    		@endif

                                    		@if($can_specification_values && $can_specification_values == 'T')
            					  			<li><a data-link="specification_values" data-toggle="tooltip" data-placement="top" title="Specification Values " href="{{ route('specification_values.index') }}"><span> Specification Values </span></a></li>
                                    		@endif
            							</ul>
            						</div>
            					</li>
            					<li ><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Pricing"><span> Pricing</span></a>
            						<div class="sidebar-submenu">
            							<ul  style="margin-left: 20px;">
                                    		@if($can_segment_list && $can_segment_list == 'T')
            								<li><a data-link="pricingsegment" data-toggle="tooltip" data-placement="top" title=" Pricing Segment" href="{{ route('segment.index') }}"><span> Pricing Segment </span></a></li>
                                    		@endif

                                    		@if($can_segment_details && $can_segment_details == 'T')
            								<li><a data-link="segmentdetails" data-toggle="tooltip" data-placement="top" title="Pricing Segment Details" href="{{ route('VehicleSegmentDetail.index') }}"><span>Pricing Segment Details</span></a></li>
                                    		@endif

                                    		@if($can_price_list && $can_price_list == 'T')
            								<li><a data-link="item-price-list" data-toggle="tooltip" data-placement="top" title="Price Lists" href="{{ route('wms_item_price_list') }}"><span> Price Lists </span></a></li>
                                    		@endif
            							</ul>
            						</div>
            					</li>

            				</ul>
            			</div>

            		</li>

            		@if($can_wms_customer_info_list && $can_wms_customer_info_list == 'T')
            		  <li><a data-link="contact" data-toggle="tooltip" data-placement="top" title="Customer" href="{{ route('contact.index', ['wms-customer']) }}"><i class="fa fa-user"></i><span>Customer</span></a></li>
            		@endif

            		@if($can_vehicle_register && $can_vehicle_register == 'T')
            		<li style = "width:80%"><a data-link="registered-vehicles/list" data-toggle="tooltip" data-placement="top" title="Registered Vehicles" href="{{ route('vehicle_registered.index') }}"><i class="fa fa-car"></i><span>Registered Vehicles</span></a><span class="pull-right add_vehicle" style="position: absolute;right: -40px;top: 0px;color: #868e96;font-size:20px;"><i class="fa fa-plus-circle" data-toggle='tooltip' title="Add Vehicle"></i></span></li>
            		@endif


            		<li class="header"><span>Transactions</span></li>
            		@if($can_wms_jobcard_list && $can_wms_jobcard_list == 'T')
            	 	<li style = "width:70%"><a data-link="job_card" data-toggle="tooltip" data-placement="top" title="Job Card" href="{{ route('jobcard.index') }}"><i class="fa icon-ecommerce-cart"></i><span>Job Card</span></a></li>
            		@endif

            		@if($can_wms_estimation_list && $can_wms_estimation_list == 'T')
            		<li><a data-link="job_request" data-toggle="tooltip" data-placement="top" title="Estimation" href="{{ route('transaction.index', ['job_request']) }}"><i class="fa icon-ecommerce-bag-cloud"></i><span>Estimation</span></a></li>
            		@endif

            		@if($can_wms_job_invoice_list && $can_wms_job_invoice_list == 'T')
            		<li><a data-link="job_invoice" data-toggle="tooltip" data-placement="top" title="Job Invoice" href="{{ route('transaction.index', ['job_invoice']) }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span>Job Invoice</span></a></li>
            		@endif

            		@if($can_WMS_Receivables && $can_WMS_Receivables == 'T')
            		<li><a data-link="receipt" data-toggle="tooltip" data-placement="top" title="Receivables" href="{{ route('cash_transaction.index', ['wms_receipt']) }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables</span></a></li>
            		@endif

            		<li class="header"><span>Reports</span></li>

            		@if($can_gst_report && $can_gst_report == 'T')
            		<li><a data-link="gst-trade" data-toggle="tooltip" data-placement="top" title="GST Report" href="{{ route('gst_report.index','wms_sales') }}"><i class="fa icon-elaboration-todolist-check"></i><span>GST Report</span></a></li>
            		@endif

            		@if($can_vehicle_report && $can_vehicle_report == 'T')
            		<li><a data-link="vehicle/list" data-toggle="tooltip" data-placement="top" title="Vehicle Invoice Report" href="{{ route('vehicle_list_report') }}"><i class="fa fa-truck"></i><span>Vehicle Invoice Report</span></a></li>
            		@endif

            	    <li><a data-link="receivables_report" data-toggle="tooltip" data-placement="top" title="Receivables Report" href="{{ route('receivables_report') }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables Report</span></a></li>

        		<?php Log::info('Trade_wms-Blade:-After side menu renderend');?>
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

$('.add_vehicle').on('click',function(){
   $.get("{{ route('vehicle_registered.create') }}", function(data) {
				//$('.crud_modal .modal-container').html("");
				$('.crud_modal .modal-container').attr("data-id",0);
				$('.crud_modal .modal-container').html(data);
			});
			$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
});
$('.add_jobcard').on('click', function(e) {

			e.preventDefault();

			//var that = $(this);

			//$('.loader_wall_onspot').show();
            new imageLoader(cImageSrc, 'startAnimation()');

			$('body').css('overflow', 'hidden');

			$('.full_modal_content').attr("data-id",0)

			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

					$.get("{{ route('transaction.create', ['job_card']) }}", function(data) {

					  $('.full_modal_content').show();

					  $('.full_modal_content').html("");

					  $('.full_modal_content').html(data);

					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

					  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });

					  //$('.loader_wall_onspot').hide();
					  new imageLoader(cImageSrc, 'stopAnimation()');

					});





			});



		});

</script>
<?php Log::info('Trade_wms-Blade:-End');?>

@stop



<?php

Session::put('module_name', 'trade_wms');

?>