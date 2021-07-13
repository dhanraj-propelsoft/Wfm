<?php Log::info('JobCard_Detail-Blade:-before master extends');?>
<!-- Master page to render the whole application. left and top bar with center screens -->
@extends('layouts.master')
<?php Log::info('JobCard_Detail-Blade:-After master extends');?>
<!-- Stylesheet link - using head links from master blade..@parent includes head linkd in this page-->
@section('head_links') @parent

<?php Log::info('JobCard_Detail-Blade:-Before Link JobCardDetail style pages');?>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('resources/views/trade_wms/jobcard/JobCardDetail/JobCard-Detail.css') }}">
<?php Log::info('JobCard_Detail-Blade:-After link JobCardDetail style pages');?>

<?php Log::info('JobCard_Detail-Blade:-Before Link transaction style pages');?>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/layout/css/transaction.css') }}">
<?php Log::info('JobCard_Detail-Blade:-After link transaction style pages');?>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/jquery.contextMenu.min.css') }}"/>


@stop
<?php Log::info('JobCard_Detail-Blade:-Before include trade_wms page');?>
<!-- Left navigation for trade_wms module-->
@include('includes.trade_wms')
<?php Log::info('JobCard_Detail-Blade:-After include trade_wms page');?>

@section('content')

<?php Log::info('JobCard-ListView-Blade:-Before include JobCard-Advance-Popup ');?>
<!-- Jobcard advance payment popup-->
@include('trade_wms.jobcard.JobCard-Advance-Popup')
<?php Log::info('JobCard-ListView-Blade:-After include JobCard-Advance-Popup');?>

<div class="content">
	@if($errors->any())
		<div class="alert alert-danger">
			@foreach($errors->all() as $error)
				<p>{{ $error }}</p>
			@endforeach
		</div>
	@endif

	<div class="fill header" style="height:40px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
		<div class="row" style="padding-top: 5px;">
			<div style="float: left;margin-right: auto; padding-left: 20px;">
			  <h5 class="float-left page-title"  style="letter-spacing: 2px;"><b>Job Card</b></h5>
		  </div>
			<div style="float: center;margin-right: auto; padding-left: 5px;">
			  <span class="fa fa-hashtag"></span>
			  {{-- TODO : EDIT --}}
			   <span class="title-order-no" >XXXXX/XX/XX</span>
			   &nbsp; &nbsp;<span class="fa fa-car"></span>
			  <span class="title-vehicle-no">Vehicle No</span>&nbsp;<span  class="title-vehicle-config">  Make/Model/Variant/Version</span> 
			   &nbsp; &nbsp;<span class="fa fa-user"></span> 
			   <span class="title-customer-name">Customer Name</span>
		  </div>
		  <div class="btn-group btn-group-sm float-right" style="padding-right: 30px;height:25px;">
			  <a class="btn btn-success float-left clear cancel_transaction" style="color: #fff;border-radius: 3px;border:2px solid white;vertical-align:middle; text-align:center;line-height:10px;"><i class="fa fa-times-circle"></i> Close</a>
			  <a class="btn btn-success float-left tab_save_btn" style="color: #fff;border-radius: 3px;border:2px solid white;vertical-align:middle; text-align:center;line-height:10px;"><i class="fa fa-save"></i> Save</a>
			<button class="btn btn-success float-left dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #fff;border-radius: 3px;border:2px solid white;vertical-align:middle; text-align:center;line-height:10px;"><i class="fa fa-plus"></i> Actions</button>
			<div class="dropdown-menu"  aria-labelledby="dropdownMenuButton">
				<a href="#" class="dropdown-item hover disableLink advance_payment" id="advance_payment" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-money"></i> Advance Payment</a>
				<hr style="width:100%;text-align:left;margin-left:0;  margin-top: 0em; margin-bottom: 0em; border-style: inset; border-width: 1px;">
				<a href="#" class="dropdown-item hover disableLink estimate_create_update" id="estimate_create_update" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-calculator"></i> Create Estimate</a>
				<a href="#" class="dropdown-item hover disableLink estimate_view" id="estimate_view" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-eye"></i> View Estimate</a>
				<hr style="width:100%;text-align:left;margin-left:0;  margin-top: 0em; margin-bottom: 0em; border-style: inset; border-width: 1px;">
				<a href="#" class="dropdown-item hover disableLink invoice_create_update" id="invoiceCash_create_update" data="create_inv_key1" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-money"></i> Create Invoice (Cash)</a>
				<a href="#" class="dropdown-item hover disableLink invoice_create_update" id="invoiceCredit_create_update" data="create_inv_key2" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-credit-card"></i> Create Invoice (Credit)</a>
				<a href="#" class="dropdown-item hover disableLink invoice_view" id="invoice_view" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-eye"></i> View Invoice</a>
				<hr style="width:100%;text-align:left;margin-left:0;  margin-top: 0em; margin-bottom: 0em; border-style: inset; border-width: 1px;">
				<a href="#" class="dropdown-item hover disableLink ack_customer" id="ack_customer" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';" data-original-title="Send Acknowledgement" data-toggle="tooltip"><i class="fa fa-comment"></i> Ack to Customer</a>
				<hr style="width:100%;text-align:left;margin-left:0;  margin-top: 0em; margin-bottom: 0em; border-style: inset; border-width: 1px;">
				<a href="#" class="dropdown-item hover disableLink print_ack" id="print_ack" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-print"></i> Print</a>
				<hr style="width:100%;text-align:left;margin-left:0;  margin-top: 0em; margin-bottom: 0em; border-style: inset; border-width: 1px;">
				<a href="#" class="dropdown-item hover disableLink delete" id="delete" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';"><i class="fa fa-trash"></i> Delete</a>
			</div>
		  </div>

  </div>


  	<div class="clearfix"></div>
	{!! Form::open(['class' => 'form-horizontal transactionform']) !!}
	{{ csrf_field() }}
	{!! Form::hidden('id', (!empty($id))? $id : null) !!}
	@if(!empty($id))
	{!! Form::hidden('_method', 'PATCH') !!}
	@endif


	<div class="form-body" style="padding: 5px 20px 50px; margin-top: 2px; ">
		
		<div class="alert alert-success">
			{{ Session::get('flash_message') }}
		</div>
	
        <!--<div class="errorsDiv" style="display: show;">
            <div class="row"><div class="form-group col-md-4"><a class="alert_close" id="alert_close" style="display: show;"><i class="fa fa-chevron-up"></i></a><a class="alert_open" id="alert_open" style="display: none;""><i class="fa fa-chevron-down"></i></a><span style="font-size: 14px; font-weight: bold;letter-spacing: 2px;">&nbsp;&nbsp;Alerts:</span></div></div>
            <ul id="errors_ul">
                <li><div class="row"> <div class="form-group col-md-1">DETAIL</div><div class="form-group col-md-2">Vehicle</div> </div> </li>
                <li><div class="row"> <div class="form-group col-md-1">DETAIL</div><div class="form-group col-md-1">Vehicle</div><div class="form-group col-md-1">Other info</div><div class="form-group col-md-2">Insurance Due Date</div><div class="form-group col-md-7">Error message will be displayed here with all the details for user to resolve it</div> </div> </li>
                <li><div class="row"> <div class="form-group col-md-1">ITEM</div><div class="form-group col-md-2">Parts</div> </div> </li>
                <li><div class="row"> <div class="form-group col-md-1">IMAGE</div><div class="form-group col-md-2">Before</div> </div> </li>
            </ul>
        </div>-->

        <!-- Alerts -->
        <!-- TODO: Show all validation error messages in the below div and show the div if there are errors. Use li tag to list the errors -->
        <div id="jq_accordion_alerts" class="ui-accordion ui-widget ui-helper-reset">
            <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" style="color: #a94442;">
                <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
                Alerts
            </h3>
            <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-default-open">
                <ul id="errors_ul">
                    <!--TODO Sample li , these will be created in jquery based on the errors received from API -->
                   {{--  <li><div class="row"> <div class="form-group col-md-1">DETAIL</div><div class="form-group col-md-2">Vehicle</div> </div> </li>
                    <li><div class="row"> <div class="form-group col-md-1">DETAIL</div><div class="form-group col-md-1">Vehicle</div><div class="form-group col-md-1">Other info</div><div class="form-group col-md-2">Insurance Due Date</div><div class="form-group col-md-7">Error message will be displayed here with all the details for user to resolve it</div> </div> </li>
                    <li><div class="row"> <div class="form-group col-md-1">ITEM</div><div class="form-group col-md-2">Parts</div> </div> </li>
                    <li><div class="row"> <div class="form-group col-md-1">IMAGE</div><div class="form-group col-md-2">Before</div> </div> </li>
						--}}
				</ul>

                <nav aria-label="breadcrumb" id="alerts_bc">
                  {{--<ol class="breadcrumb">
                    <li class="breadcrumb-item">DETAIL</li>
                    <li class="breadcrumb-item">Vehicle</li>
                    <li class="breadcrumb-item">Field</li>
                    <li class="breadcrumb-item">Error Message</li>
                  </ol>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">ITEM</li>
                    <li class="breadcrumb-item">Parts</li>
                    <li class="breadcrumb-item">Field</li>
                    <li class="breadcrumb-item">Error Message</li>
                  </ol> --}}
                </nav>

              </div>
        </div>

  		<ul class="nav nav-pills nav-justified navbar-dark bg-light">
		    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;letter-spacing: 2px;" class="nav-link active" data-toggle="tab" href="#order_details">DETAIL</a> </li>
		    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;letter-spacing: 2px;" class="nav-link" data-toggle="tab" href="#item_details">ITEM</a> </li>
		    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;letter-spacing: 2px;" class="nav-link" data-toggle="tab" href="#attachments">IMAGE</a> </li>
		    <li class="nav-item"> <a style="font-size: 14px; font-weight: bold;letter-spacing: 2px;" class="nav-link" data-toggle="tab" href="#checklist">CHECKLIST</a> </li>
		</ul>
	  	<div class="tab-content">
			  {{-- TODO : EDIT --}}
			

			<?php Log::info('JobCard_Detail-Blade:-Before order_details tab');?>
		    <!-- first tab starting from here..-->
	    	<div class="tab-pane active" id="order_details">
				<fieldset>
	    		<div class="row">
			    	<div class="col-md-2">
			    	</div>
			    	<div class="col-md-9">
			    		<a href="#" data-value="1" class="jobcard_status_update job_card_status_change job_status_1 chevron_active" data-id="1" data-toggle="tooltip" data-placement="top" title="New">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">New</span>
						 </div>
						 </a>
						<a href="#" data-value="2" class="jobcard_status_update job_card_status_change job_status_2 chevron" data-id="2" data-toggle="tooltip" data-placement="top" title="First Inspected">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">First Inspected</span>
						 </div>
						 </a>
						<a href="#" data-value="3" class="jobcard_status_update job_card_status_change job_status_3 chevron" data-id="3" data-toggle="tooltip" data-placement="top" title="Estimation Pending">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">Estimation Pending</span>
						 </div>
						</a>
						<a href="#" data-value="4" class="jobcard_status_update job_card_status_change job_status_4 chevron" data-id="4" data-toggle="tooltip" data-placement="top" title="Estimation Approved">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">Estimation Approved</span>
						 </div>
						 </a>
						 <a href="#" data-value="5" class="jobcard_status_update job_card_status_change job_status_5 chevron" data-id="5" data-toggle="tooltip" data-placement="top" title="Work in Progress">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">Work in Progress</span>
						 </div>
						 </a>
						<a href="#"  data-value="6" class="jobcard_status_update job_card_status_change job_status_6 chevron" data-id="6" data-toggle="tooltip" data-placement="top" title="Final Inspected">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">Final Inspected</span>
						 </div>
						</a>
						<a href="#" data-value="7"  class="jobcard_status_update job_card_status_change job_status_7 chevron" data-id="7" data-toggle="tooltip" data-placement="top" title="Vehicle Ready">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">Vehicle Ready</span>
						 </div>
						</a>
						<a href="#" data-value="8"  class="jobcard_status_update job_card_status_change job_status_8 chevron" data-id="8" data-toggle="tooltip" data-placement="top" title="Closed">
						 <div class="" id="che">

						 	<span class="arrow-span font-size-10">Closed</span>
						 </div>
						</a>

						<input name="jobcard_status_id" class="jobcard_status_id" type="hidden" value="1"/>
					</div>

				</div>


				<!-- Second row -->
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							{{-- TODO : EDIT --}}
							<label for="jc_name" class="required">Job Card #</label>
	                        {{ Form::text('job_card_no', null, ['class' => 'form-control', 'id' => 'job_card_no','disabled']) }}
						</div>
					</div>

                   <div class="col-md-3">
					   {{-- TODO : EDIT --}}
						<label class="required" for="date">Date</label>
						{{ Form::text('job_date', date('d-m-Y'), ['class'=>'form-control date-picker datetype ', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
					</div>
				</div>
				<div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">

				    <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Vehicle
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-default-open " id="vehicle-tab">
					   	<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label for="registration_number" class="control-label required">Registration Number</label>
									{{ Form::text('vehicle_registration_number',null, ['class' => 'form-control upperCase', 'id' => 'vehicle_registration_number']) }}
									{{ Form::hidden('vehicle_id',null, ['class' => 'form-control', 'id' => 'vehicle_id']) }}
									{{ Form::hidden('vehicle_existing','false', ['class' => 'form-control', 'id' => 'vehicle_existing']) }}
									{{ Form::hidden('IsVehicleRequiredFieldEmpty','false', ['class' => 'form-control', 'id' => 'vehicle_required_field']) }}
								</div>
								<div class="col-md-3 ">
									<div class="alert alert-danger span_mge" ></div>
								</div>
							</div>
				    	</div>
				    	<div class="row">
				    		<div class="form-group   col-md-3">
								<label for="vehicle_config" class="required">Make/ Modal / variant / Version</label>
								{!! Form::select('vehicle_config',defalutSelectDropDownArray("Configuration"),null, ['class' => 'form-control select_item required-field','id' =>'vehicle_config',"disabled"=>"disabled"]) !!}	
							
							</div>
				    		<div class="form-group col-md-3">
				    			<label for="vehicle_category" class="control-label required">Category</label>
									{{ Form::text('vehicle_category', null, ['class' => 'form-control properCase required-field','id' => 'vehicle_category', "readonly"=>"readonly"]) }}
									{{ Form::hidden('vehicle_category_id', null) }}

							</div>
				    	</div>
				    	<div class="row">
				    		<div class="form-group   col-md-3">
								<label for="engine_number" class="control-label">Engine Number</label>
								{{ Form::text('engine_number', null, ['class' => 'form-control', 'id' => 'engine_no','disabled']) }}
				    		</div>
				    		<div class="form-group col-md-3">
								<label for="chassis_number" class="control-label">Chasis Number</label>
								{{ Form::text('chassis_number', null, ['class' => 'form-control','id' => 'chassis_no','disabled']) }}
				    		</div>
				    	</div>
				    	<div class="row">
				    		<div class="form-group col-md-3">
				    		 	<label for="manufacturing_year" class="control-label">Manufacturing Year</label>
		                    	{{ Form::text('manufacturing_year', null, ['class'=>'form-control make_year', 'autocomplete' => 'off', 'id' => 'manufacturing_year','disabled']) }}
		                    </div>
				    		<div class="form-group col-md-3">
				    			<label for="vehicle_mileage" class="control-label required">Odometer Mileage</label>
				    			{{ Form::text('vehicle_mileage',  null, ['class' => 'form-control numbers']) }}
				    		</div>
				    	</div>
				        <!-- Nested second level Accordian -->
				        <div id="jq_accordion_l2" class="ui-accordion ui-widget ui-helper-reset">
				            <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				            <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				                Other Info
				            </h3>
				            <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
				               	<div class="row">
				            	    <div class="form-group col-md-3">
				        				<label for="vehicle_insurance" class="control-label">Insurance</label>
				        				{{ Form::text('vehicle_insurance', null, ['class'=>'form-control properCase','disabled','id' => 'vehicle_insurance']) }}
				            		</div>
				            	    <div class="form-group  col-md-3">
				        				<label for="insurance_due" class="control-label">Insurance Due Date</label>
				        				{{ Form::text('insurance_due', null, ['class'=>'form-control date-picker','disabled','id' => 'insurance_due','data-date-format' => 'dd-mm-yyyy']) }}
				            		</div>
				            	</div>
				                <div class="row">
				            	    <div class="form-group col-md-3">
				        				<label for="permit_type" class="control-label">Permit type</label>
										{!! Form::select('permit_type',defalutSelectDropDownArray("Permit Type"),null, ['class'=>'form-control select_item','id' => 'permit_type','disabled']) !!}
									
									</div>
				            	    <div class="form-group  col-md-3">
				        				<label for="permit_due" class="control-label">Permit Due Date</label>
				        				{{ Form::text('permit_due', null, ['class'=>'form-control date-picker','disabled','id' => 'permit_due','data-date-format' => 'dd-mm-yyyy']) }}
				            		</div>
				                </div>
				                <div class="row">
				            	    <div class="form-group col-md-3">
				        				<label for="fc_due" class="control-label">FC Due Date</label>
				        				{{ Form::text('fc_due', null, ['class'=>'form-control  date-picker datetype ','disabled','id' => 'fc_due', 'data-date-format' => 'dd-mm-yyyy']) }}
				            		</div>
				            	    <div class="form-group  col-md-3">
				        				<label for="tax_due" class="control-label">Tax Due Date</label>
				        				{{ Form::text('tax_due', null, ['class'=>'form-control  date-picker datetype ','disabled','id' => 'tax_due','data-date-format' => 'dd-mm-yyyy']) }}
				            		</div>
				                </div>
				            	<div class="row">
				            	    <div class="form-group col-md-3">
				        				<label for="warranty_km" class="control-label">Warranty KM</label>
				        				{{ Form::text('warranty_km', null, ['class'=>'form-control','disabled','id' => 'warranty_km']) }}
				            		</div>
				            	    <div class="form-group  col-md-3">
				        				<label for="warrenty_yrs" class="control-label">Warranty Years</label>
				        				{{ Form::text('warrenty_yrs', null, ['class'=>'form-control','disabled','id' => 'warrenty_yrs']) }}
				            		</div>
				            	</div>
				            	<div class="row">
				            	    <div class="form-group col-md-3">
				        				<label for="bank_loan" class="control-label">Bank Loan</label>
				        				{{ Form::select('bank_loan',defalutSelectDropDownArray("Bank Loan"),null ,['class' =>'form-control select_item','id' => 'bank_loan','disabled']) }}
				            		</div>
				            	    <div class="form-group  col-md-3">
				        				<label for="month_due_date" class="control-label">Month Due Date</label>
				        				{{ Form::text('month_due_date', null, ['class'=>'form-control  date-picker datetype ','disabled','id' => 'month_due_date','data-date-format' => 'dd-mm-yyyy']) }}
				            		</div>
				            	</div>
				            	<div class="row">
				            		<div class="form-group  col-md-6">
										<label for="vehicle_note" class="control-label">Vehicle Note</label>
										{{-- TODO : EDIT --}}
										{{ Form::textarea('vehicle_note', null, ['class'=>'form-control properCase', 'size' => '30x2']) }}
									</div>
				            	</div>
				            	<br>
				                <br>
				            </div>
				        </div>
				    </div>
				    <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Customer
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-default-open" id="customer-tab">

				        <div class="row" id="hid">
							<div class="form-group col-md-4">
    						    <a href="#/" id="OrganizationCust" class="chevron1 chevron1_disable customer_selection"  data-value="1" >
                                        Organization
						        </a>&nbsp;
                                <a href="#/" id="IndividualCust" class="chevron1 chevron1_disable customer_selection" data-value="0" >
                                        Individual (Person)
                                </a>
								{{ Form::hidden('customer_type',null, ['class' => 'form-control', 'id' => 'customer_type']) }}
								{{ Form::hidden('customer_id',null, ['class' => 'form-control', 'id' => 'customer_id']) }}
                                {{ Form::hidden('customer_existing','false', ['class' => 'form-control', 'id' => 'customer_existing']) }}
                                {{ Form::hidden('people_id','false', ['class' => 'form-control', 'id' => 'people_id']) }}
								{{ Form::hidden('IsCustomerRequiredFieldEmpty','false', ['class' => 'form-control', 'id' => 'customer_required_field']) }}
							</div>
							<div class="col-md-3">
								<div class="alert alert-danger custAlertMsg" ></div>
							</div>
                        </div>
				        <div class="row">
				    	    <div class="form-group col-md-3">
				    			<label for="customer" class="required">Customer Mobile Number</label>
								{{ Form::text('customer_mobile_number',null, ['class' => 'form-control numbers required-field', 'id' => 'customer_mobile_number']) }}
							</div>
							<div class="col-md-3">
								<div class="alert alert-danger customer-find-table" ></div>
							</div>
				    	</div>
				         <!-- <div class="row col-md-6 show_customer_details" style="display: none;"> -->
				        <div class="row show_customer_details" style="display: none;">
				    	    <div class="form-group col-md-6">
            					<table id="customer_table" class="display" style="cursor:pointer;" >
            						<thead>
                						<tr>
                							<th width="30%">Mobile Number</th>
                							<th width="50%">Name</th>
                							<th width="10%">Already Associated</th>
                							<th width="10%">Associated As</th>
                   						</tr>
            						</thead>
            					</table>
							</div>		
        				</div>
			
						{{-- Business Field --}}
						<div class="row ">
							<div class="form-group col-md-3 disable-business">
								<label for="customer" class="required">Business Name</label>
								{{ Form::text('business_name',null, ['class' => 'form-control customerName properCase required-field ', 'id' => 'business_name','disabled']) }}
								
							</div>
													
						</div>
						{{-- /Business Field --}}

						{{-- Person Field --}}
						<div class="row">
								<div class="form-group col-md-3 disable-individual">
											<label for="customer" class="required">First Name</label>
											{{ Form::text('first_name',null, ['class' => 'form-control customerName properCase required-field', 'id' => 'first_name','disabled']) }}

								</div>
								<div class="form-group col-md-3 disable-individual">
									<label for="customer" class="required">Last Name</label>
									{{ Form::text('last_name',null, ['class' => 'form-control customerName properCase required-field', 'id' => 'last_name','disabled']) }}
								
								</div>
						</div>
						
						{{-- /Person Field --}}

						<div class="row" >
							<div class="form-group col-md-3">
								<label for="customer" class="control-label">Email</label>
								{{ Form::email('customer_email', null, ['class'=>'form-control ', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Email' ,'disabled']) }}
							</div>
				         	
				    	    
						</div>
				        <div class="row" >
				    	    <div class="form-group col-md-6">
				    			<label for="customer" class="required">Address</label>
								{{ Form::textarea('customer_address', null, ['class'=>'form-control properCase required-field', 'size' => '30x1','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Address','disabled']) }}
							</div>
				    	</div>
				         <div class="row">
				    	    <div class="form-group col-md-3">
				    			<label for="customer" class="required">State</label>
								{{ Form::select('state',defalutSelectDropDownArray("State"),null, ['class'=>'form-control select_item required-field','id' => 'state','disabled']) }}
							</div>
				    		 <div class="form-group col-md-3">
				    			<label for="customer" class="required">City</label>
								{{ Form::select('city',defalutSelectDropDownArray("City"), null, ['class'=>'form-control select_item required-field','id' => 'city','disabled']) }}
				    		</div>
				        </div>
				        <div class="row">
				    	    <div class="form-group col-md-3">
				    			<label for="customer" class="required">Pincode</label>
								{{ Form::text('pincode', null, ['class'=>'form-control numbers required-field', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Pincode' ,'disabled']) }}
							</div>
				    		 <div class="form-group col-md-3 show_gst" style="display: none;">
				    			<label for="customer" class="required">GST</label>
								{{ Form::text('customer_gst', null, ['class'=>'form-control required-field', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'GST' ,'disabled']) }}
				    		</div>
				        </div>

				        <div class="row">
				    	    <div class="form-group  col-md-3">
				    			<label for="driver" class="control-label">Contact / Driver Name</label>
								{{ Form::text('driver',  null, ['class'=>'form-control properCase','id' => 'driver']) }}
				    		</div>
				    		<div class="form-group  col-md-3">
				    			<label for="driver" class="control-label">Contact / Driver Number</label>
								{{ Form::text('driver_contact',  null, ['class'=>'form-control numbers','id' => 'driver_contact']) }}
				    		</div>
				        </div>

				        <!-- Nested second level Accordian -->
				        <div id="jq_accordion_l2" class="ui-accordion ui-widget ui-helper-reset">
				            <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				            <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				                Other Info
							</h3>
							
								<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
									<fieldset  class="address_fields">
									<div >
										<table class="display cell-border" width="60%">
											<tr>
												<th></th>
												<th>Billing</th>
												<th>Delivery</th>
											</tr>
											<tr>
												<td width="10%">Name</td>
												<td width="20%">
													<input type="text"  data-toggle="tooltip" data-placement="top" title="Billing Name" class="form-control properCase" name="billing_name" value="{{ (!empty($company_name))?$company_name :null}}" autocomplete="off" />
												</td>
												<td width="20%">
													<input type="text" data-toggle="tooltip" data-placement="top" title="Shipping Name" class="form-control properCase" name="shipping_name" value="{{ (!empty($company_name))?$company_name : null}}" autocomplete="off"  />
												</td>
											</tr>
											<tr>
												<td width="10%">Mobile Number</td>
												<td width="20%">
													<input type="text"  data-toggle="tooltip" data-placement="top" title="Billing Mobile" class="form-control numbers" name="billing_mobile" value="{{ (!empty($company_mobile))?$company_mobile :null}}" autocomplete="off" />
												</td>
												<td width="20%">
													<input type="text" data-toggle="tooltip" data-placement="top" title="Shipping Mobile" class="form-control numbers" name="shipping_mobile" value="{{ (!empty($company_mobile))?$company_mobile : null}}" autocomplete="off"  />
												</td>
											</tr>
											<tr>
												<td width="10%">Email</td>
												<td width="20%">
													<input type="text"  data-toggle="tooltip" data-placement="top" title="Billing Email" class="form-control" name="billing_email" value="{{ (!empty($company_email))?$company_email:null}}" autocomplete="off"  />
												</td>
												<td width="20%">
													<input type="text" data-toggle="tooltip" data-placement="top" title="Shipping Email" class="form-control" name="shipping_email" value="{{ (!empty($company_mobile))?$company_mobile : null}}" autocomplete="off"  />
												</td>
											</tr>
											<tr class="show_gst" style="display: none;">
												<td width="10%">GST</td>
												<td width="20%">
													<input type="text" data-toggle="tooltip" data-placement="top" title="Billing GST" class="form-control" name="billing_gst" value="{{ (!empty($transactions))?$transactions->billing_gst : null}}" autocomplete="off"  />
												</td>
												<td></td>
											</tr>
											<tr>
												<td width="10%">Address</td>
												<td width="20%">
													<textarea name="billing_address" style="height: 50px;" data-toggle="tooltip" data-placement="top" title="Billing Address" class="form-control properCase" cols="30" rows="2"> {{ (!empty($company_address))?$company_address:null}}</textarea>
												</td>
												<td width="20%">
													<textarea name="shipping_address" data-toggle="tooltip" data-placement="top" title="Shipping Address" style="height:50px;" class="form-control properCase" cols="30" rows="2" > {{ (!empty($company_address))?$company_address : null}}</textarea>
												</td>
											</tr>
											<tr>
												<td width="10%">State</td>
												<td width="20%">
													{{ Form::select('billing_state',defalutSelectDropDownArray("State"),null, ['class'=>'form-control select_item','id' => 'billing_state']) }}
												</td>
												<td width="20%">
													{{ Form::select('shipping_state',defalutSelectDropDownArray("State"),null, ['class'=>'form-control select_item','id' => 'shipping_state']) }}
												</td>
											</tr>
											<tr>
												<td width="10%">City</td>
												<td width="20%">
													{{ Form::select('billing_city',defalutSelectDropDownArray("City"), null, ['class'=>'form-control select_item','id' => 'billing_city']) }}
												</td>
												<td width="20%">
													{{ Form::select('shipping_city',defalutSelectDropDownArray("City"), null, ['class'=>'form-control select_item','id' => 'shipping_city']) }}
												</td>
											</tr>
											<tr>
												<td width="10%">Pincode</td>
												<td width="20%">
													{{ Form::text('billing_pincode', null, ['class'=>'form-control numbers', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Shipping Pincode' ]) }}
												</td>
												<td width="20%">
													{{ Form::text('shipping_pincode', null, ['class'=>'form-control numbers', 'autocomplete' => 'off','data-toggle'=>'tooltip' ,'data-placement'=>'top', 'title' => 'Shipping Pincode' ]) }}
												</td>
											</tr>
										</table>
									</div>
									</fieldset>
								</div>
							
							
				        </div>
				    </div>
				    <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Job Details
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label class="required" for="service_type">Service Type</label>
								
									{{ Form::select('service_type',defalutSelectDropDownArray("Service Type"), null, ['class' => 'form-control select_item', 'id' => 'service_type']) }}
								
									{{-- TODO: Remove --}}
									<?php /*{{ Form::select('service_type', [$vehicle_sevice_type], (!empty($wms_transaction))?$wms_transaction->service_type : $sevice_type, ['class' => 'form-control select_item', 'id' => 'service_type']) }} */ ?>
								</div>

								<div class="col-md-3">
									<label for="job_due_date">Job Due Date</label>
									{{ Form::text('job_due_date',   date('d-m-Y', strtotime('+1 day')), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
								</div>

							</div>
						</div>

				    	<div class="row">
				    		<div class="form-group   col-md-6">
				                <label for="complaint">Complaints</label>
				                <textarea name="complaint" class="form-control complaint properCase"  value="" style="height: 75px;"> </textarea>

				    		</div>
				    	</div>
				    	<div class="row">
				    		<div class="form-group   col-md-3">
								<label for="shipment_mode_id">Delivery Method</label>
								{{ Form::select('shipment_mode_id',  defalutSelectDropDownArray("Delivery Method"), '', ['class' => 'form-control select_item', 'id' => 'shipment_mode_id']) }}
								 
							</div>
				    		<div class="form-group col-md-3">
								<label for="employee_id">Assigned to</label>
							
				                {{ Form::select('employee_id', defalutSelectDropDownArray("Employee"), null, ['class' => 'form-control select_item', 'id' => 'employee_id']) }}

				    		</div>
				    	</div>
				    	<div class="row">
				    		<div class="form-group col-md-3">
								<label for="job_completed_date">Delivery Date</label>
								{{-- TODO : EDIT --}}
								{{ Form::text('job_completed_date',   date('d-m-Y', strtotime('+1 day')), ['class'=>'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
				    		</div>
				    	</div>
				    </div>
				    <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Follow Up Visit
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" id="follow-up-visit-tab">
				        <div class="row">
				    	    <div class="form-group col-md-3">
								<label for="next_visit_reason" class="control-label required ">Next Visit Reason</label>
								{{ Form::text('next_visit_reason', null, ['class'=>'form-control properCase']) }}
				    		</div>
				    	    <div class="form-group  col-md-3">
								<label for="next_visit_mileage" class="control-label required">Next Visit - Odometer Mileage</label>
								{{ Form::text('next_visit_mileage',null, ['class'=>'form-control numbers']) }}
				    		</div>
				        </div>
				    	<div class="row">
				    	    <div class="form-group  col-md-3">
								<label for="next_visit_date" class="control-label required">Next Visit - Date</label>
								{{ Form::text('next_visit_date', date('d-m-Y', strtotime('+90 day')), ['class'=>'form-control date-picker datetype ', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) }}
				    		</div>
				    	</div>
				    	<br>
				        <br>
				    </div>
				    <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Previous Visit
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom previous-visit-tab">
				        <div class="row">
				    	    <div class="form-group col-md-3">
								<label for="last_visit" class="control-label">Last Visit</label>
								{{ Form::text('last_visit', null, ['class'=>'form-control','disabled','id' => 'last_update_date']) }}
				    		</div>
				    	    <div class="form-group  col-md-3">
								<label for="vehicle_last_job" class="control-label">Last Job Card</label>
								{{-- TODO : EDIT --}}
								{{ Form::text('vehicle_last_job',null, ['class'=>'form-control','disabled','id' => 'last_update_jc']) }}
				    		</div>
						</div>
				        <div class="row">
				    	    <div class="form-group col-md-6 previous-visit-link">
				    		</div>
						</div>
				       	<br>
				        <br>
				    </div>
				</div>


				</fieldset>
	    	</div>

			<?php Log::info('JobCard_Detail-Blade:-Before item_details tab');?>
	    	<!-- Second tab starting from here..-->

			<div class="tab-pane" id="item_details">
				<?php 	Log::info('Transaction Edit Blade - Inside second tab - B Naveen change'); ?>

				<!-- Naveen Changes for items selection-->
		        <div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">

		            <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
		                <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
		                Item Chooser
		            </h3>
		            <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
		    		    <div class="row">
		        	        <div class="form-group col-md-2" style="margin-top: 5px; margin-left: 10px; padding: 10px;">
		            	       <select name="item_category_id" class="form-control itemFilterByItemCategory" id="itemFilterByItemCategory">
		    						<option value="-1">Filter by Item Category</option>
		    						<option value="0" >All</option>
		        				</select>
		                    </div>
		        	        <div class="form-group col-md-2" style="margin-top: 5px; margin-left: 10px; padding: 10px;">
		            	       <select name="item_category_type_id" class="form-control itemFilterByItemCategoryType" id="itemFilterByItemCategoryType">
		    						<option value="-1">Filter by Item Category Type</option>
		    						<option value="0" >All</option>
		    						
		        				</select>
		                    </div>
                            <div class="form-group col-md-2" style="margin-top: 5px; margin-left: 10px; padding: 10px;">
            		       	   <select name="item_make_id" class="form-control itemFilterByItemMake" id="itemFilterByItemMake">
    		  						<option value="-1">Filter by Item Make</option>
    		  						<option value="0" >All</option>
    		    				</select>
        					</div>		                    
		                </div>
		    		    <div class="row">
		        	            <table id="productSelector" class="display cell-border" width="100%">
		                			<thead>
		                				<th>Item List</th>
		                				<th>Selected Items</th>
		                			</thead>
		                			<tbody>
		                				<tr>
		                					<td width="50%">
		                					    <ul id="sortable1" class="connectedSortable">
		                                            <input id="itemSearch" type="text" placeholder="Search...">
		                                        </ul>
		                                    </td>
		                					<td width="50%">
		                    					<ul id="sortable2" class="connectedSortable">
		                                        </ul>
		                					</td>
		                    			</tr>
		                    		</tbody>
		                		</table>
		                </div>
		                <br>
		    		    <div class="row">
		        	        <div class="form-group col-md-12" style="margin-top: 5px; margin-left: 10px; padding: 10px;">
		                        <button type="button" class="btn btn-success add_items" id="add_items" style="position:absolute;  bottom:2px;  right:30px;">Add Selected Items</button>
		                    </div>
		                </div>
		            </div>
					<?php Log::info('Transaction Edit Blade - Inside second tab - I Naveen change b parts table');?>
                	<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
               		<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>              Parts
               		</h3>
             		<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
        	            <div class="form-group" style=" margin-top: 5px; overflow-y: scroll; height:760px;" >
        					<table id="selected_item_parts_table" class="table data_table table-hover"  style="width:100%">
        						<thead>
            						<tr>
            							<th>Item Description</th>
            							<th>Category</th>
            							<th>Make</th>
            							<th>Stock</th>
            							<th>Quantity</th>
            						</tr>
        						</thead>
                    			<tbody>
                    				
                    			</tbody>        						
        					</table>
        				</div>
                    </div>
	                <h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
	                <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
	                Services
	            	</h3>
	             	<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
	    		        <div class="form-group" style=" margin-top: 5px; overflow-y: scroll; height:760px;" >
	        				<table id="selected_item_service_table" class="display" style="width:100%">
	        					<thead>
	            					<tr>
	            						<th>Item Description</th>
	            						<th>Job Description</th>
	            						<th>Assigned To</th>
	            						<th>Work Start Time</th>
	               						<th>Time Spent</th>
	               						<th>Status</th>

	            					</tr>
	        					</thead>
	        				</table>
	        			</div>
	            	</div>
        		</div>
				<?php Log::info('Transaction Edit Blade - Inside second tab - I Naveen change a parts table');?>
			</div>

			<?php Log::info('JobCard_Detail-Blade:-Before attachments tab');?>
	    	<!-- Thired tab starting from here..-->

			<div class="tab-pane" id="attachments">
				<!--Start Before Image -->

				<div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
					<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Before
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-default-open">
				    	<div class="clearfix"></div>
							<div class="form-group">
								{{-- TODO : EDIT --}}
							
								<!--Start Before Image -->

								<div class="col-lg-12 col-md-12 col-sm-12">

										<div class="dropzone" id="before_image" >
									
											<div class="fallback"></div>
										</div>

										<br>

										<div class="myProgress">
											<div id="BeforePBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="display:none;">Processing uploded file...</div>
										</div>
								</div>

								{{-- <div class="col-lg-12 col-md-12 col-sm-12">
									<div class="col-md-12 pull-right" style="padding:5px 20px">
										<button type="button" class="btn btn-success Insert_files pull-right" id="SaveBeforeImg" style="float: right;">Upload Files
												 </button>
									</div>
						 		</div> --}}

						 		<!--End Before Image -->

							</div>


				    </div>
				</div>
				<!--End Before Image -->
				<!--Start Progress Image -->
				<div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
					<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        Progress
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
				    	<div class="clearfix"></div>
							<div class="form-group">
							
								<!--Start Before Image -->
								{{-- TODO : EDIT --}}

								<div class="col-lg-12 col-md-12 col-sm-12">

										<div class="dropzone" id="progress_image" >
							
										</div><br>

										<div class="myProgress">
											<div id="ProgressPBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="display:none;">Processing uploded file...</div>
										</div>
								</div>

				

						 		<!--End Before Image -->

							</div>


				    </div>
				</div>

				<!--End Progress Image -->

				<!--Start After Image -->
				<div id="jq_accordion" class="ui-accordion ui-widget ui-helper-reset">
					<h3 class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
				        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>
				        After
				    </h3>
				    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">
				    	<div class="clearfix"></div>
							<div class="form-group">
								{{-- TODO : EDIT --}}
					
								<!--Start Before Image -->
								<div class="col-lg-12 col-md-12 col-sm-12" id="">

									<div class="dropzone" id="after_image" >
							
									</div><br>

									<div class="myProgress">
										<div id="ProgressPBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="display:none;">Processing uploded file...</div>
									</div>
								</div>

						
						 		<!--End Before Image -->

							</div>
					</div>
				</div>

				<!--End After Image -->
			</div>


	    	<!-- Fourth tab starting from here..-->

    	 	<div class="tab-pane" id="checklist">
	     		<div class="clearfix"></div>
				<div class="form-group"><br/>
					<table style="border-collapse: collapse;" class="table table-bordered" id="checklist-table">
						<thead>
						<tr>
							<th width="5%">#</th>
							<th width="40%">Description</th>
							<th width="5%">Checked?</th>
							<th width="50%">Notes</th>
						</tr>
						<tr>
						</thead>
						<tbody>
				
						</tbody>
					</table>
				</div>
	    	</div>
			<?php Log::info('JobCard_Detail-Blade:-Before checklist tab');?>

		
	  	</div>
	</div>
	{!! Form::close() !!}
	<?php Log::info('JobCard_Detail-Blade:-End');?>

</div>





@stop

@include('trade_wms.jobcard.JobCardDetail.JobCard-Detail-JavaScript')