@extends('layouts.master')

@include('includes.settings')
@section('content')
@section('head_links')
@parent
<style>
	.md-radio-inline .help-block {
		width: 200%;
		float: left;
		position: absolute;
		top: 20px;
	}
	#active_plan{
		border-radius: 5px;
		position: relative;
		margin: 5px 5px 15px;
		padding: 5px;
		text-align: center;
		background: rgb(204, 204, 204) none repeat scroll 0% 0%;
	}
	#active_icon{
		color: #fff; 
		position: absolute;
		right: 0; 
		padding: 15px;
	}
	#selective_icon{		
		color: #fff; 
		position: absolute; 
		right: 0; 
		padding: 15px; 
		display: none;"
	}

	.active_select{
		display: block;
		background-color:#ccc;
	}
	
		input[name*=package_id] {
			display: none;
		} 
		.custom-panel{
			/*border: 1px solid #d7dbe0;*/
			border: 1px solid #d7cece;
			border-radius: 2px;
		}

		.custom-panel-module{

			margin-bottom: 4px !important;
			margin-left: 3px !important;
			margin-right: 3px !important;
		}
		.panel-body{  background-color: #FFFFFF }
		a:hover,a:focus{
			text-decoration: none;
			outline: none;
		}
		#accordion .panel{
			border: none;
			border-radius: 5px;
			box-shadow: none;
			margin-bottom: 10px;
			background: transparent;
		}
		#accordion .panel-heading{
			padding: 0;
			border: none;
			border-radius: 5px;
			background: transparent;
			position: relative;
		}
		#accordion .panel-title a{
			display: block;
			padding: 8px 15px;
			margin: 0;
			background: #FF8C00;
			font-size: 14px;
			font-weight: bold;
			color: #fff;
			text-transform: uppercase;
			letter-spacing: 1px;
			border: none;
			border-radius: 3px;
			position: relative;
		}
		.panel-group .panel {
		   margin-bottom: 0;
		   border-radius: 4px;
		   margin-right: 0;

		}

		#accordion .panel-title a.collapsed{ border: none; }
		#accordion .panel-title a:before,
		#accordion .panel-title a.collapsed:before{
			content: "\f107";
			font-family: "Font Awesome 5 Free";
			width: 30px;
			height: 30px;
			line-height: 27px;
			text-align: center;
			font-size: 25px;
			font-weight: 900;
			color: #fff;
			position: absolute;
			top: 15px;
			right: 30px;
			transform: rotate(180deg);
			transition: all .4s cubic-bezier(0.080, 1.090, 0.320, 1.275);
		}
		#accordion .panel-title a.collapsed:before{
			color: rgba(255,255,255,0.5);
			transform: rotate(0deg);
		}
		#accordion .panel-body{
			padding: 10px 20px;
			font-size: 14px;
			color: #000000;
			line-height: 12px;
			letter-spacing: 0px;
			border-top: none;
			border-radius: 5px;
			padding-left: 0;
		}

		.info {
		  background-color: #fdd787;
		  padding: 7px; 

		}

		.total_payment {
		  background-color: #90EE90;
		  padding: 7px; 

		}

		.choose-plan{
			position: fixed;
			right: 0;
			z-index: 10;
		}
		.table_alt td{
		border: 0;
		padding: 0.50rem
		}

	

</style>
@stop
@section('module')
@parent
Premium Plan
@stop
@section('breadcrumbs')
@parent
<li> <a href="#">Premium Plan</a> </li>
@stop


@if(Session::has('flash_message'))
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@endif

@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">@if($type == "upgrade") Subscribe to Propel ERP @elseif($type == "renew") Renew @elseif($type == "plan-change") Change Plan @endif</h4>
</div>

				{!! Form::open([
				'route' => 'subscribe.store',
				'class' => 'form-horizontal validateform subscription_form'
				]) !!}
				{{ csrf_field() }}



<div class="clearfix"></div>
	<div class="row">
	  	<div class="col-md-12">
			<div class="steps">
			  <div class="row step-line"> </div>
			</div>
			<div class="row">
			  <div class="col-md-7"> 
			  	
				<div class="form-wizard">
				  <ul class="nav steps">
					<li class="active"> <a href="#tab1" data-toggle="tab" class="step active"> <span class="number"> 1 </span><br>
					  <span class="desc"> Choose Plan </span> </a> </li>
					<li class="disabled"> <a href="#tab2" data-toggle="tab" class="step"> <span class="number"> 2 </span><br>
					  <span class="desc"> Billing Details </span> </a> </li>
					<li class="disabled"> <a href="#tab3" data-toggle="tab" class="step"> <span class="number"> 3 </span><br>
					  <span class="desc"> &nbsp; Confirm &nbsp;&nbsp;&nbsp; </span> </a> </li>
				  </ul>

				<div class="bar progress progress-striped" role="progressbar">
					<div class="progress-bar progress-bar-success" style="width: 33%;"> </div>
				</div>

				<div class="tab-content">					

					<div class="tab-pane" id="tab2">
					  <div class="form-group"> 
					  	{!! Form::label('', '', ['class' => 'control-label col-md-3']) !!} </div>
					  <div class="form-group">
						<div class="row"> 
							{!! Form::label('name', 'Name', ['class' => 'control-label col-md-3 required']) !!}
						  <div class="col-md-8"> 
						  	{!! Form::text('name', $organization_name, ['class' => 'form-control']) !!} </div>
						</div>
					  </div>
					  <div class="form-group">
						<div class="row"> 
							{!! Form::label('plan_id', 'Address Type', ['class' => 'control-label col-md-3 required']) !!}
						  &nbsp;&nbsp;
						  <!-- {{ Form::radio('address_type', 'personal', false, ['id' => 'personal']) }}
						  <label for="personal"><span></span>Personal Address</label> -->
						  &nbsp;&nbsp;&nbsp;
						  {{ Form::radio('address_type', 'business', true, ['id' => 'business']) }}
						  	<label for="business"><span></span>Business Address</label>
						</div>
					  </div>

					  <!-- <div class="form-group address_type">
						<div class="row"> {!! Form::label('', '', ['class' => 'control-label col-md-3 required']) !!}
						  <div class="col-md-8">
							<select name="address" class="form-control select_item">
							  <option value="">Select Address</option>
							</select>
						  </div>
						</div>
					  </div> -->

					<div style="" class="address_container">

						<div class="form-group">
						  <div class="row"> {!! Form::label('door', 'Door No / Block', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> 
								{!! Form::text('door', null, ['class' => 'form-control']) !!} </div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('street', 'Street', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> 
								{!! Form::text('street', null, ['class' => 'form-control']) !!} </div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('state', 'State', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> 
								<!-- {!! Form::select('state', $state, null, ['class' => 'select2_category form-control']) !!} -->

								<?php 
		  							$states = null;
		  							if(isset($address_details->state_id)) { $states = $address_details->state_id; } ?>
									{!! Form::select('state',$state, $states, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

							</div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('city', 'City', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> 
								<!-- {!! Form::select('city', ['' => 'Select City'], ($address_details->city_id != null) ? $address_details->city_id : null, ['class' => 'select2_category form-control']) !!} -->

								<?php 
				  					$cities = null;
				  					if(isset($address_details->city_id)) { $cities = $address_details->city_id; } ?>

								{!! Form::select('city', $city, $cities, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!} 

							</div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('area', 'Area', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> 
								{!! Form::text('area', null, ['class' => 'form-control' ]) !!} </div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('pin','Pin', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('pin', ($address_details->pin != null) ? $address_details->pin : null, ['class' => 'form-control numbers']) !!} </div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('landmark', 'Landmark', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('landmark', ($address_details->landmark != null) ? $address_details->landmark : null, ['class' => 'form-control']) !!} </div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('mobile_no', 'Mobile Number', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> 
								{!! Form::text('mobile_no', ($address_details->mobile_no != null) ? $address_details->mobile_no : null, ['class' => 'form-control numbers']) !!} </div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('phone', 'Phone', ['class' => 'control-label col-md-3']) !!}
							<div class="col-md-8 "> 
								{!! Form::text('phone',  ($address_details->phone != null) ? $address_details->phone : null, ['class' => 'form-control numbers']) !!} </div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="row"> {!! Form::label('email_address', 'Email', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('email_address', ($address_details->email_address != null) ? $address_details->email_address : null, ['class' => 'form-control']) !!}							  
							  
							{!! Form::hidden('payment_mode_id', $payment_type->id, ['class' => 'form-control']) !!} </div>
						  </div>
						</div>

					</div>

					</div>

					<div class="tab-pane" id="tab3">
					  <div class="form-group"> {!! Form::label('', '', ['class' => 'control-label col-md-3']) !!} </div>
					  <p><b>Note </b>The above given information are true to best of my Knowledge. I accept them by proceeding it.</p>
					</div>

				  </div>

				</div>

				<!-- <div class="form-actions">
				  <div class="row">
					<div class="col-md-11"> <a href="javascript:;" class="btn btn-success next float-right"> Continue <i class="fa fa-angle-right"></i> </a> <a style=" display: none;"  href="javascript:;" class="btn btn-success payment float-right"> Make Payment <i class="fa fa-angle-right"></i> </a> <a style="margin-right: 10px; display: none;" href="javascript:;" class="btn btn-default previous prev float-right"> <i class="fa fa-angle-left"></i> Back </a> </div>
				  </div>
				</div> -->

				

			  </div>

			<div style="position: fixed; right: 10px; " class="col-md-4 plan-container custom-panel">

					<h6> You've Selected :</h6>

									

					<div class="form-group col-md-12">
						<div class="row">
							<div class="col-md-12 info package">
						  		
						  		
							</div>
						</div>
					</div>					

					<div class="form-group col-md-12 ">
						<div class="row">
							<div class="col-md-7 info plan ">
								{!! Form::select('plan_id', $subscription_plan, $default_package->plan_id, ['class' => ' form-control required','style' => 'background-color: #fdd787; ']); !!}
							</div>
							<div class="col-md-5 info price">

								Rs. {{$plan_details->price}}
						  		
							</div>
						</div>
					</div>
					
					<div class="form-group col-md-12">
						<div class="row">
							<div class="col-md-7 info addon">

								Free Addons
						  		
							</div>
							<div class="col-md-5 info addon_price">


								Rs. 0.00
						  		
							</div>
						</div>
					</div>

					<div class="form-group col-md-12 ">
						<div class="row">
							<div class="col-md-4 info ">						
								<input type="radio" name="term_period_id" class="quarterly" id="quarterly"  value="3" @if($plan_details->term_period_id == 3) checked="checked" else checked="" @endif>
								<label for="quarterly"><span></span>Quarterly</label>
							</div>

							<div class="col-md-4 info ">						
								<input type="radio" name="term_period_id" class="halfearly" id="halfearly"  value="4" @if($plan_details->term_period_id == 4) checked="checked" else checked="" @endif>
								<label for="halfearly"><span></span>Half-Yearly</label>
							</div>

							<div class="col-md-4 info ">						
								<input type="radio" name="term_period_id" class="annualy" id="annualy"  value="5" @if($plan_details->term_period_id == 5) checked="checked" else checked="" @endif>
								<label for="annualy"><span></span>Annualy</label>
							</div>
							
						</div>
					</div>

					<div class="form-group col-md-12">
						<div class="row">
							<div class="col-md-7 total_payment total_title">

								<label for="total_payment"><span></span>Total Payment ( for selected period )</label>
								
						  		
							</div>
							<div class="col-md-5 total_payment total_price get_price">

								@if($plan_details->term_period_id != '')
								
									Rs. {{$plan_details->total_price}}
								@else

									Rs. {{$plan_details->price}}
								
								@endif								
						  		
							</div>

							{!! Form::hidden('total_payment',$plan_details->price, ['class' => 'form-control']) !!}

							{!! Form::hidden('discount_payment',null, ['class' => 'form-control']) !!}


						</div>
					</div>

					<div class="form-actions form-group row" style="">

						<!-- <button type="submit" class="btn btn-success modules_save" style="margin: auto;"><i class="fa fa-check"></i> Continue</button> -->


						<a href="javascript:;" class="btn btn-success next " style="margin: auto;"> Next <i class="fa fa-angle-right"></i> </a>

						<a style="margin: auto; display: none;"  href="javascript:;" class="btn btn-success payment float-right"> Make Payment <i class="fa fa-angle-right"></i> 
						</a>

						<a style="margin-right: 10px; display: none;" href="javascript:;" class="btn btn-default previous prev float-right"> <i class="fa fa-angle-left"></i> Back </a>

					</div>
				

			</div>
			</div>
	  	</div>
	</div>


	

			<div class="row package-container">

				<div class="col-md-8">
					<div class="container-fluid">	

					<input type="hidden" value="{{$current_package}}" name="current_package" >	  	

					<div class="row"> 
						@foreach($packages1 as $package)
						
						@if($package->display_name != 'Books' && $package->display_name != 'HRM' && $package->display_name != 'Inventory' && $package->display_name != 'WFM' )

						  <div class="col-md-3">

						  	<!-- check id & style -->

							<div style="border-radius:5px; position: relative; margin:5px; padding: 5px;  margin-bottom: 15px; text-align: center;" class="package_select"   <?php if( $package->id == $current_package){ echo $current_package;
							 ?> id="active_plan" <?php } ?> >

							 <i class="fa fa-check selected" aria-hidden="true" <?php if($current_package==$package->id){ ?> 
							 	id="active_icon" <?php } else { ?> id="selective_icon" <?php  } ?> ></i>

							<!-- End -->

							<img width="50" src="{{ URL::to('/') }}/public/package/{{ $package->id }}.png">

							  <h6 style="color:#000; font-weight: bold;">{{$package->display_name}}</h6>

							  <p style="color:#000;  font-size: 12px;">{{$package->modules}}</p>

							  <input type="radio" value="{{$package->id}}" name="package_id" >

							   <input type="hidden" value="{{$package->id}}" name="package-org-id">

							</div>

						  </div>

						  @endif


						@endforeach 
					</div>

					</div >
				</div>

				

			</div>

	</form>		
		
			<br>


			<div class="plan-details"><h5>2.Select the Plan of the Package</h5></div>


			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				
				<!-- Free14days Plan = 1 -->
				<div class="col-md-7 panel panel-default">

					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
							  Free14Days

							</a>
						</h4>
					</div>



					<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingone" >

						
						<div class="panel-body">

							<div class="row" style="padding: 20px">

								<div class="col-md-2 custom-panel books-free" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>
											<th>Books</th>
										</tr>
										  
										</thead>
										<tbody>

										<?php 
											$plans = App\Custom::plan_package($plan_id = 1);
										?>	

										 <!-- str_replace need this braces -->

											@foreach($plans as $plan)

											@if($plan->module_id == 2)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif		

											@endforeach
										 
										</tbody>
									</table>

									</div>

									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										<tr>
											<th>Addon</th>
										</tr>									
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>	

										 <!-- str_replace need this braces -->

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 2)

											@if($addon->addon_id == 1
											)									

											<tr>
												<td >{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>					
											

											@endif

											@if($addon->addon_id == 2
											)
											<tr>
												<td >{{$addon->value}} {{$addon->addon_name}} </td>
											</tr>				
											

											@endif	

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                             
													   
								</div>&nbsp;

								<div class="col-md-2 custom-panel hrm-free" style="display: none;">
									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>HRM</th>
										  </tr>									  
										</thead>
										<tbody>

										<?php 
										$plans = App\Custom::plan_package($plan_id = 1);
										?>	

											@foreach($plans as $plan)

											@if($plan->module_id == 3)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	

											@endforeach
										 
										</tbody>
									</table>

									</div>

									<br>

									<div class="custom-panel">				

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 3)

											@if($addon->addon_id == 3
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>

									</div> <br>                           
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel wfm-free" style="display: none;">
									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WFM</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 1);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 4)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>

									<div class="custom-panel"> 

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 4)

											@if($addon->addon_id == 3
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>

									</div>                          
													   
								</div> &nbsp;
							

								<div class="col-md-2 custom-panel inventory-free" style="display: none;">
									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Inventory</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 1);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 5)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 5)

											@if($addon->addon_id == 5
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}
											</td>
											</tr>

											@endif

											@if($addon->addon_id == 6
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel trade-free" style="display: none;">
									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Trade</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 1);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 6)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 6)

											@if($addon->addon_id == 4
											)
											<tr>

											<td>{{$addon->value}} {{$addon->addon_name}}
											</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td>{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td>{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table> 
									</div>                           
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel wms-free" style="display: none;">

									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WMS</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 1);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 7)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 7)

											@if($addon->addon_id == 4
											)										

											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											
											</tr>

											@endif

											@if($addon->addon_id == 9
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											<td ></td>
											</tr>

											@endif

											@if($addon->addon_id == 10
											)
											<tr>

											<td >{{$addon->value}} {{$addon->addon_name}}</td>
											
											</tr>

											@endif						

											@endif

										@endforeach									 
										</tbody>
									</table>                              
									</div><br>	   
								</div>

							</div>						   	                   
						   
						</div>

					</div>

				</div>
				<!-- End -->

				<!-- Starter Plan = 2 -->
				<div class="col-md-7 panel panel-default">

					<div class="panel-heading" role="tab" id="headingTwo">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							  Starter

							</a>
						</h4>
					</div>

					<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">

						<div class="panel-body">
							  
							<div class="row" style="padding: 20px">

								<div class="col-md-2 custom-panel books-starter" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>
											<th>Books</th>
										</tr>
										  
										</thead>
										<tbody>

										<?php 
											$plans = App\Custom::plan_package($plan_id = 2);
										?>	

										 <!-- str_replace need this braces -->

											@foreach($plans as $plan)

											@if($plan->module_id == 2)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif		

											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>	

										 <!-- str_replace need this braces -->

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 2)

											@if($addon->addon_id == 1
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 2
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif	

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>

													   
								</div>&nbsp; 

								<div class="col-md-2 custom-panel hrm-starter" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>HRM</th>
										  </tr>									  
										</thead>
										<tbody>

										<?php 
										$plans = App\Custom::plan_package($plan_id = 2);
										?>	

											@foreach($plans as $plan)

											@if($plan->module_id == 3)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 3)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                             
													   
								</div>&nbsp; 

								<div class="col-md-2 custom-panel wfm-starter" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WFM</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 2);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 4)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 1);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 4)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div>&nbsp; 
							

								<div class="col-md-2 custom-panel inventory-starter" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Inventory</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 2);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 5)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 2);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 5)

											@if($addon->addon_id == 5
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 6
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                             
													   
								</div>&nbsp; 

								<div class="col-md-2 custom-panel trade-starter" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Trade</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 2);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 6)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>

									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 2);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 6)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                          
													   
								</div>&nbsp;

								<div class="col-md-2 custom-panel wms-starter" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WMS</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 2);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 7)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 2);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 7)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 9
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 10
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif						

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div><br>


													   
								</div>

							</div>	

						</div>
								
					</div>               

				</div>
				<!-- End -->

				<!-- Lite Plan = 3 -->
				<div class="col-md-7 panel panel-default">

					<div class="panel-heading" role="tab" id="headingThree">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
							  Lite

							</a>
						</h4>
					</div>

					<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">

						<div class="panel-body">
							  
							<div class="row" style="padding: 20px">

								<div class="col-md-2 custom-panel books-lite" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>
											<th>Books</th>
										</tr>
										  
										</thead>
										<tbody>

										<?php 
											$plans = App\Custom::plan_package($plan_id = 3);
										?>	

										 <!-- str_replace need this braces -->

											@foreach($plans as $plan)

											@if($plan->module_id == 2)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif		

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 3);
										?>	

										 <!-- str_replace need this braces -->

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 2)

											@if($addon->addon_id == 1
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 2
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif	

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div>&nbsp;

								<div class="col-md-2 custom-panel hrm-lite" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>HRM</th>
										  </tr>									  
										</thead>
										<tbody>

										<?php 
										$plans = App\Custom::plan_package($plan_id = 3);
										?>	

											@foreach($plans as $plan)

											@if($plan->module_id == 3)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 3);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 3)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                           
													   
								</div>&nbsp;

								<div class="col-md-2 custom-panel wfm-lite" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WFM</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 3);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 4)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">
		
									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 3);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 4)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                               
													   
								</div> &nbsp;						

								<div class="col-md-2 custom-panel inventory-lite" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Inventory</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 3);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 5)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id =3);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 5)

											@if($addon->addon_id == 5
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 6
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel trade-lite" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Trade</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 3);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 6)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 3);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 6)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                             
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel wms-lite" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WMS</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 3);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 7)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 2);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 7)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 9
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 10
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif						

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div> <br>                        
													   
								</div>&nbsp;

							</div>

						</div>
								
					</div>               

				</div>
				<!-- End -->

				<!-- Standard Plan = 4 -->
				<div class="col-md-7 panel panel-default">

					<div class="panel-heading" role="tab" id="headingForu">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
							 Standard

							</a>
						</h4>
					</div>

					<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">

						<div class="panel-body">
							  
							<div class="row" style="padding: 20px">

								<div class="col-md-2 custom-panel books-standard" style="display: none;">

									<br>
									<div class="custom-panel">
									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>
											<th>Books</th>
										</tr>
										  
										</thead>
										<tbody>

										<?php 
											$plans = App\Custom::plan_package($plan_id = 4);
										?>	

										 <!-- str_replace need this braces -->

											@foreach($plans as $plan)

											@if($plan->module_id == 2)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif		

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">
									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 4);
										?>	

										 <!-- str_replace need this braces -->

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 2)

											@if($addon->addon_id == 1
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 2
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif	

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div>&nbsp; 

								<div class="col-md-2 custom-panel hrm-standard" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>HRM</th>
										  </tr>									  
										</thead>
										<tbody>

										<?php 
										$plans = App\Custom::plan_package($plan_id = 4);
										?>	

											@foreach($plans as $plan)

											@if($plan->module_id == 3)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel"> 

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 4);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 3)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                          
													   
								</div>&nbsp; 

								<div class="col-md-2 custom-panel wfm-standard" style="display: none;">

									<br>
									<div class="custom-panel">
									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WFM</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 4);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 4)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 4);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 4)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                          
													   
								</div>&nbsp;					

								<div class="col-md-2 custom-panel inventory-standard" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Inventory</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 4);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 5)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 4);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 5)

											@if($addon->addon_id == 5
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 6
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                           
													   
								</div>&nbsp;

								<div class="col-md-2 custom-panel trade-standard" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Trade</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 4);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 6)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id =4);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 6)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>

									</div>
													   
								</div>&nbsp;

								<div class="col-md-2 custom-panel wms-standard" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WMS</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 4);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 7)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 4);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 7)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 9
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 10
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif						

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div><br>
													   
								</div>&nbsp;

							</div>

						</div>
								
					</div>               

				</div>
				<!-- End -->

				<!-- Professional Plan = 5 -->
				<div class="col-md-7 panel panel-default">

					<div class="panel-heading" role="tab" id="headingFive">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
							  Professional

							</a>
						</h4>
					</div>

					<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">

						<div class="panel-body">
							  
							<div class="row" style="padding: 20px">

								<div class="col-md-2 custom-panel books-professional" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>
											<th>Books</th>
										</tr>
										  
										</thead>
										<tbody>

										<?php 
											$plans = App\Custom::plan_package($plan_id = 5);
										?>	

										 <!-- str_replace need this braces -->

											@foreach($plans as $plan)

											@if($plan->module_id == 2)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif		

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 5);
										?>	

										 <!-- str_replace need this braces -->

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 2)

											@if($addon->addon_id == 1
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 2
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif	

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                          
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel hrm-professional" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>HRM</th>
										  </tr>									  
										</thead>
										<tbody>

										<?php 
										$plans = App\Custom::plan_package($plan_id = 5);
										?>	

											@foreach($plans as $plan)

											@if($plan->module_id == 3)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	

											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 5);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 3)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                             
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel wfm-professional" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WFM</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 5);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 4)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 5);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 4)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                         
													   
								</div> &nbsp;					

								<div class="col-md-2 custom-panel inventory-professional" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Inventory</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 5);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 5)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 5);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 5)

											@if($addon->addon_id == 5
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 6
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                           
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel trade-professional" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Trade</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 5);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 6)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>

									</div>

									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 5);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 6)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                          
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel wms-professional" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WMS</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 5);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 7)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 4);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 7)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 9
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 10
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif						

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>
									<br>                             
													   
								</div> &nbsp;

							</div>

						</div>
								
					</div>               

				</div>
				<!-- End -->

				<!-- Enterprise Plan = 6 -->
				<div class="col-md-7 panel panel-default">

					<div class="panel-heading" role="tab" id="headingSix">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
							  Enterprise

							</a>
						</h4>
					</div>

					<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">

						<div class="panel-body">
							  
							<div class="row" style="padding: 20px">

								<div class="col-md-2 custom-panel books-enterprise" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>
											<th>Books</th>
										</tr>
										  
										</thead>
										<tbody>

										<?php 
											$plans = App\Custom::plan_package($plan_id = 6);
										?>	

										 <!-- str_replace need this braces -->

											@foreach($plans as $plan)

											@if($plan->module_id == 2)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif		

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 6);
										?>	

										 <!-- str_replace need this braces -->

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 2)

											@if($addon->addon_id == 1
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 2
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif	

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>
													   
								</div> &nbsp; 

								<div class="col-md-2 custom-panel hrm-enterprise" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>HRM</th>
										  </tr>									  
										</thead>
										<tbody>

										<?php 
										$plans = App\Custom::plan_package($plan_id =6);
										?>	

											@foreach($plans as $plan)

											@if($plan->module_id == 3)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 6);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 3)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                           
													   
								</div> &nbsp; 

								<div class="col-md-2 custom-panel wfm-enterprise" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WFM</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 6);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 4)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 6);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 4)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div> &nbsp;						

								<div class="col-md-2 custom-panel inventory-enterprise" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Inventory</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 6);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 5)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 6);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 5)

											@if($addon->addon_id == 5
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 6
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                             
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel trade-enterprise" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Trade</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 6);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 6)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id =6);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 6)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                        
													   
								</div> &nbsp; 

								<div class="col-md-2 custom-panel wms-enterprise" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WMS</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 6);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 7)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>

									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 6);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 7)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 9
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 10
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif						

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>
									<br>		   
								</div>

							</div>

						</div>
								
					</div>               

				</div>
				<!-- End -->

				<!-- Corporate Plan = 7 -->
				<div class="col-md-7 panel panel-default">

					<div class="panel-heading" role="tab" id="headingSeven">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
							  Corporate

							</a>
						</h4>
					</div>

					<div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">

						<div class="panel-body">
							  
							<div class="row" style="padding: 20px">

								<div class="col-md-2 custom-panel books-corporate" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>
											<th>Books</th>
										</tr>
										  
										</thead>
										<tbody>

										<?php 
											$plans = App\Custom::plan_package($plan_id = 7);
										?>	

										 <!-- str_replace need this braces -->

											@foreach($plans as $plan)

											@if($plan->module_id == 2)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif		

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 7);
										?>	

										 <!-- str_replace need this braces -->

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 2)

											@if($addon->addon_id == 1
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 2
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif	

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel hrm-corporate" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>HRM</th>
										  </tr>									  
										</thead>
										<tbody>

										<?php 
										$plans = App\Custom::plan_package($plan_id = 7);
										?>	

											@foreach($plans as $plan)

											@if($plan->module_id == 3)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	

											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 6);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 3)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                           
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel wfm-corporate" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WFM</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 7);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 4)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 6);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 4)

											@if($addon->addon_id == 3
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif								

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div> &nbsp;					

								<div class="col-md-2 custom-panel inventory-corporate" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Inventory</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 7);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 5)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 7);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 5)

											@if($addon->addon_id == 5
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 6
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                             
													   
								</div> &nbsp; 

								<div class="col-md-2 custom-panel trade-corporate" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>Trade</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 7);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 6)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 7);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 6)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 8
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif							

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>                            
													   
								</div> &nbsp;

								<div class="col-md-2 custom-panel wms-corporate" style="display: none;">
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										  <tr>										
											<th>WMS</th>
										  </tr>									  
										</thead>
										<tbody>

											<?php 
											$plans = App\Custom::plan_package($plan_id = 7);
											?>	

											@foreach($plans as $plan)
											@if($plan->module_id == 7)

											<td>{!! str_replace(",", "<br/>",($plan->features))  !!}</td>

											@endif	
											@endforeach
										 
										</tbody>
									</table>
									</div>
									<br>
									<div class="custom-panel">

									<table id="datatable" class="table table_alt" width="100%" cellspacing="0">
										<thead>
										 <tr>
											<th>Addon</th>
										</tr>
										
										  
										</thead>
										<tbody>

										<?php
											$addons = App\Custom::package_addon($plan_id = 7);
										?>

										 @foreach($addons as $addon)

										 	@if($addon->module_id == 7)

											@if($addon->addon_id == 4
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 7
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 9
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif

											@if($addon->addon_id == 10
											)
											<tr>

											<td colspan="2">{{$addon->value}} {{$addon->addon_name}}</td>
											</tr>

											@endif						

											@endif

										@endforeach									 
										</tbody>
									</table>
									</div>
									<br>                           
													   
								</div>

							</div>

						</div>
								
					</div>               

				</div>
				<!-- End -->


			</div>

   

@stop
@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.bootstrap.wizard.js') }}"></script>
<script>
$(document).ready(function() { 

	sidebar_minimized();

	basic_functions();

	$.fn.clicktoggle = function(a, b) {
        return this.each(function() {
            var clicked = false;
            $(this).click(function() {
                if (clicked) {
                    clicked = false;
                    return b.apply(this, arguments);
                }
                clicked = true;
                return a.apply(this, arguments);
            });
        });
    };


   

	$('.package_select').clicktoggle(function() {
		
		$('input[name=package_id]').prop('checked', false);
		$('.package_select').find('.selected').hide();
		$('.package_select').css('background', 'none');
		$(this).parent().find('input[name=package_id]').prop('checked', true);
		$(this).closest('.package_select').find('.selected').show();
		$(this).closest('.package_select').css('background', '#ccc');

	}, function() {
		
		$('input[name=package_id]').prop('checked', false);
		$('.package_select').find('.selected').hide();
		$('.package_select').css('background', 'none');
		$(this).parent().find('input[name=package_id]').prop('checked', true);
		$(this).closest('.package_select').find('.selected').show();
		$(this).closest('.package_select').css('background', '#ccc');
	});


	/*setTimeout(function() { 
		$(this).closest('.package_select').trigger('click'); 
	}, 100);*/

	/*$("input[name=term_period_id], .package_select" ).on('click change', function () {

		var term = $('input[name=term_period_id]:checked').val();

		if(term == 3){
			var price = $("input[name=total_payment]").val();
			$(".total_price").text('Rs. '+price);
			$(".price").text('Rs. '+price);

		}

		if(term == 4){
			var price = $("input[name=total_payment]").val();

			var discount_price = $("input[name=discount_payment]").val();

			var discount =  parseFloat((10/100)*(price));

			var final_price = (parseFloat(price - discount).toFixed(2));	

			$(".total_price").text('Rs. '+final_price);			

			$("input[name=discount_payment]").val(final_price);

			$(".price").text('Rs. '+final_price);
			
		}

		if(term == 5){
			var price = $("input[name=total_payment]").val();

			var discount =  parseFloat((20/100)*(price));

			var final_price = (parseFloat(price - discount).toFixed(2));

			$(".total_price").text('Rs. '+final_price);

			$("input[name=discount_payment]").val(final_price);

			$(".price").text('Rs. '+final_price);
		}		

	});*/

	

	$("input[name=package_id], .package_select" ).on('click', function () {
		
		//var package_val = $('select[name=package_id]').val();

		//var package_org = $('input[name=package-org-id]').val();		

		$('.books-free, .hrm-free, .wfm-free, .inventory-free, .trade-free, .wms-free, .books-starter, .hrm-starter, .wfm-starter, .inventory-starter, .trade-starter, .wms-starter, .books-lite, .hrm-lite, .wfm-lite, .inventory-lite, .trade-lite, .wms-lite, .books-standard, .hrm-standard, .wfm-standard, .inventory-standard, .trade-standard, .wms-standard, .books-professional, .hrm-professional, .wfm-professional, .inventory-professional, .trade-professional, .wms-professional, .books-enterprise, .hrm-enterprise, .wfm-enterprise, .inventory-enterprise, .trade-enterprise, .wms-enterprise, .books-corporate, .hrm-corporate, .wfm-corporate, .inventory-corporate, .trade-corporate, .wms-corporate').hide();	

		var package_val = $('input[name=package_id]:checked').val();
		
		var plan_val = $("select[name=plan_id]").val();

		var term = $('input[name=term_period_id]:checked').val();

		if(package_val !='' && plan_val !='' && term != '')
		{

			$.ajax({
				 url: "{{ route('business_register.get_package_details') }}",
				 type: 'post',
				 data: {
					_token :$('input[name=_token]').val(),					
					package : package_val,
					plan: plan_val,
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						
						var result = data.result;

						var result_module = data.module_result;

						modules = [];

						for(var i in result_module) {						

							modules.push(result_module[i].module);		
						}

						var modules = modules.toString();

						$(".package").text(modules);


						for(var i in result) {								

							var plan_name = result[i].plan_name;
							var price = result[i].price;
						}

						//$(".plan").text(plan_name);
						
						$(".price").text('Rs. '+price);
						$(".addon").text('Free Addons');
						$(".addon_price").text('Rs. 0.00');

						$(".total_title").text('Total Payment');
						
						$(".total_price").text('Rs. '+price);

						$("input[name=total_payment]").val(price);


						if(term == 3){

							//$(".total_price").text('Rs. '+price);

							//$(".price").text('Rs. '+price);

							var price = $("input[name=total_payment]").val();

							var final_price = (parseFloat(3*(price)).toFixed(2));

							$(".total_price").text('Rs. '+final_price);

							$("input[name=discount_payment]").val(final_price);
						}

						if(term == 4){

							var price = $("input[name=total_payment]").val();

							var half_yearly_price = (parseFloat(6*(price)).toFixed(2));

							var discount = parseFloat((10/100)*(half_yearly_price));

							var final_price = (parseFloat(half_yearly_price - discount).toFixed(2));

							$(".total_price").text('Rs. '+final_price);

							$("input[name=discount_payment]").val(final_price);
						}

						if(term == 5){

							var price = $("input[name=total_payment]").val();

							var annual_price = (parseFloat(12*(price)).toFixed(2));

							var discount =  parseFloat((20/100)*(annual_price));

							var final_price = (parseFloat(annual_price - discount).toFixed(2));

							$(".total_price").text('Rs. '+final_price);

							$("input[name=discount_payment]").val(final_price);
						}

						$('.loader_wall_onspot').hide();

					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
			})

		}	

		

		if(package_val == 1)
		{
			$('.books-free').show();
			$('.books-starter').show();
			$('.books-lite').show();
			$('.books-standard').show();
			$('.books-professional').show();
			$('.books-enterprise').show();
			$('.books-corporate').show();			
		}
		
		if(package_val == 2)
		{
			$('.books-free').show();
			$('.books-starter').show();
			$('.books-lite').show();
			$('.books-standard').show();
			$('.books-professional').show();
			$('.books-enterprise').show();
			$('.books-corporate').show();

			$('.hrm-free').show();
			$('.hrm-starter').show();
			$('.hrm-lite').show();
			$('.hrm-standard').show();
			$('.hrm-professional').show();
			$('.hrm-enterprise').show();
			$('.hrm-corporate').show();
		}

		if(package_val == 3)
		{

			$('.hrm-free').show();
			$('.hrm-starter').show();
			$('.hrm-lite').show();
			$('.hrm-standard').show();
			$('.hrm-professional').show();
			$('.hrm-enterprise').show();
			$('.hrm-corporate').show();

			$('.wfm-free').show();
			$('.wfm-starter').show();
			$('.wfm-lite').show();
			$('.wfm-standard').show();
			$('.wfm-professional').show();
			$('.wfm-enterprise').show();
			$('.wfm-corporate').show();
		}

		if(package_val == 4)
		{
			$('.books-free').show();
			$('.books-starter').show();
			$('.books-lite').show();
			$('.books-standard').show();
			$('.books-professional').show();
			$('.books-enterprise').show();
			$('.books-corporate').show();

			$('.hrm-free').show();
			$('.hrm-starter').show();
			$('.hrm-lite').show();
			$('.hrm-standard').show();
			$('.hrm-professional').show();
			$('.hrm-enterprise').show();
			$('.hrm-corporate').show();			

			$('.inventory-free').show();
			$('.inventory-starter').show();
			$('.inventory-lite').show();
			$('.inventory-standard').show();
			$('.inventory-professional').show();
			$('.inventory-enterprise').show();
			$('.inventory-corporate').show();
		}

		if(package_val == 5)
		{
			$('.books-free').show();
			$('.books-starter').show();
			$('.books-lite').show();
			$('.books-standard').show();
			$('.books-professional').show();
			$('.books-enterprise').show();
			$('.books-corporate').show();

			$('.hrm-free').show();
			$('.hrm-starter').show();
			$('.hrm-lite').show();
			$('.hrm-standard').show();
			$('.hrm-professional').show();
			$('.hrm-enterprise').show();
			$('.hrm-corporate').show();			

			$('.trade-free').show();
			$('.trade-starter').show();
			$('.trade-lite').show();
			$('.trade-standard').show();
			$('.trade-professional').show();
			$('.trade-enterprise').show();
			$('.trade-corporate').show();
		}

		if(package_val == 6)
		{
			$('.books-free').show();
			$('.books-starter').show();
			$('.books-lite').show();
			$('.books-standard').show();
			$('.books-professional').show();
			$('.books-enterprise').show();
			$('.books-corporate').show();

			$('.hrm-free').show();
			$('.hrm-starter').show();
			$('.hrm-lite').show();
			$('.hrm-standard').show();
			$('.hrm-professional').show();
			$('.hrm-enterprise').show();
			$('.hrm-corporate').show();			

			$('.wms-free').show();
			$('.wms-starter').show();
			$('.wms-lite').show();
			$('.wms-standard').show();
			$('.wms-professional').show();
			$('.wms-enterprise').show();
			$('.wms-corporate').show();
		}

		if(package_val == 7)
		{
			$('.books-free').show();
			$('.books-starter').show();
			$('.books-lite').show();
			$('.books-standard').show();
			$('.books-professional').show();
			$('.books-enterprise').show();
			$('.books-corporate').show();

			$('.hrm-free').show();
			$('.hrm-starter').show();
			$('.hrm-lite').show();
			$('.hrm-standard').show();
			$('.hrm-professional').show();
			$('.hrm-enterprise').show();
			$('.hrm-corporate').show();

			$('.inventory-free').show();
			$('.inventory-starter').show();
			$('.inventory-lite').show();
			$('.inventory-standard').show();
			$('.inventory-professional').show();
			$('.inventory-enterprise').show();
			$('.inventory-corporate').show();

			$('.trade-free').show();
			$('.trade-starter').show();
			$('.trade-lite').show();
			$('.trade-standard').show();
			$('.trade-professional').show();
			$('.trade-enterprise').show();
			$('.trade-corporate').show();			
		}
		
		if(package_val == 8)
		{
			$('.books-free').show();
			$('.books-starter').show();
			$('.books-lite').show();
			$('.books-standard').show();
			$('.books-professional').show();
			$('.books-enterprise').show();
			$('.books-corporate').show();

			$('.hrm-free').show();
			$('.hrm-starter').show();
			$('.hrm-lite').show();
			$('.hrm-standard').show();
			$('.hrm-professional').show();
			$('.hrm-enterprise').show();
			$('.hrm-corporate').show();

			$('.inventory-free').show();
			$('.inventory-starter').show();
			$('.inventory-lite').show();
			$('.inventory-standard').show();
			$('.inventory-professional').show();
			$('.inventory-enterprise').show();
			$('.inventory-corporate').show();
					
			
			$('.wms-free').show();
			$('.wms-starter').show();
			$('.wms-lite').show();
			$('.wms-standard').show();
			$('.wms-professional').show();
			$('.wms-enterprise').show();
			$('.wms-corporate').show();
		}

	});



	$("#active_plan").trigger('click');



	$("select[name=plan_id], input[name=term_period_id]" ).on('change', function () { 
		
		//var package_val = $('input[name=package_id]:checked').val();		

		var package_val = $('input[name=package_id]:checked').val();
		
		var plan_val = $("select[name=plan_id]").val();

		var term = $('input[name=term_period_id]:checked').val();

		if(package_val !='' && plan_val !='' && term != '')
		{			

			$.ajax({
				 url: "{{ route('business_register.get_package_details') }}",
				 type: 'post',
				 data: {
					_token :$('input[name=_token]').val(),					
					package : package_val,
					plan: plan_val,
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						
						var result = data.result;

						for(var i in result) {								

							var plan_name = result[i].plan_name;
							var price = result[i].price;
						}

						//$(".plan").text(plan_name);
						
						$(".price").text('Rs. '+price);
						$(".addon").text('Free Addons');
						$(".addon_price").text('Rs. 0.00');

						$(".total_title").text('Total Payment');
						
						$(".total_price").text('Rs. '+price);

						$("input[name=total_payment]").val(price);



						if(term == 3){

							//$(".total_price").text('Rs. '+price);

							//$(".price").text('Rs. '+price);

							var price = $("input[name=total_payment]").val();

							var final_price = (parseFloat(3*(price)).toFixed(2));

							$(".total_price").text('Rs. '+final_price);

							$("input[name=discount_payment]").val(final_price);
						}

						if(term == 4){

							var price = $("input[name=total_payment]").val();

							var half_yearly_price = (parseFloat(6*(price)).toFixed(2));

							var discount = parseFloat((10/100)*(half_yearly_price));

							var final_price = (parseFloat(half_yearly_price - discount).toFixed(2));

							$(".total_price").text('Rs. '+final_price);

							$("input[name=discount_payment]").val(final_price);
						}

						if(term == 5){

							var price = $("input[name=total_payment]").val();

							var annual_price = (parseFloat(12*(price)).toFixed(2));

							var discount =  parseFloat((20/100)*(annual_price));

							var final_price = (parseFloat(annual_price - discount).toFixed(2));

							$(".total_price").text('Rs. '+final_price);

							$("input[name=discount_payment]").val(final_price);
						}

						$('.loader_wall_onspot').hide();

					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
			})

		}

	});



	$(".business_btn").on('click', function() {

		$(".validateform").valid();

		if ($(".validateform").validate().checkForm()) {
			$(".business").hide();
			$(".modules").show();
			$(".modules_save").show();
		}


	});

	$('.form-wizard').bootstrapWizard({onTabClick: function(tab, navigation, index) {
			if($(tab).hasClass('disabled')) {
				return false;
			}
		}
	});

		/*$('select[name="plan_id"], select[name="term_period_id"]').on('change', function(){
			if($('select[name="plan_id"]').val() != "" && $('select[name="term_period_id"]').val() != "") {
				$(".plan-container").show();
			} else {
				$(".plan-container").hide();
			}
		});

		$('select[name="package_id"], select[name="ledger_id"], select[name="term_period_id"], select[name="plan_id"]').on('change', function(){

		
			var package_id = $('select[name="package_id"]').val();

			@if($type == "plan-change")
				var term = {{$current_term}};
			@else
				var term = $('select[name="term_period_id"]').val();
			@endif

			@if($type == "renew")
				var plan = {{$current_plan}};
				var ledger = $('select[name="ledger_id"]').val();
			@else
				var plan = $('select[name="plan_id"]').val();
				var ledger = $('select[name="ledger_id"]').val();
			@endif		


			if(term != "" && plan != "" && package_id != "" @if($type == "upgrade") && ledger != "" @endif) 
			{
				get_estimate(plan, term, ledger, package_id);
				$(".plan-container").show();
			} 
			else 
			{
				$(".plan-container").hide();
			}

		});*/




	$(".prev").on('click', function(e) {
			e.preventDefault();
			var prev = $('.nav li.active').prev('li:visible');
			var prev_tab = $('.nav li.active').prev('li:visible').find('a').attr('href');
			var prev_other_tab = $('.nav li.active').prev('li:visible').prev('li:visible').find('a').attr('href');

			
				$('.form-group').removeClass('has-error');
				$('.help-block').remove();
				if(prev_tab) {
					$('.nav li.active').removeClass('active');
					prev.addClass('active');
					$('.form-wizard').find("a[href*='"+prev_tab+"']").trigger('click');
					$('.payment').hide();
					$('.next').show();
					if(prev_other_tab == undefined) {
						$('.next').show();
						$('.prev').hide();
					}
					return false;
				}

				
			
	});

	$(".next").on('click', function(e) {
			e.preventDefault();
			var next = $('.nav li.active').next('li:visible');
			var next_tab = $('.nav li.active').next('li:visible').find('a').attr('href');
			var next_other_tab = $('.nav li.active').next('li:visible').next('li:visible').find('a').attr('href');
			var tab = $('.form-wizard').find("a[href*='"+next_tab+"']").parent().prev('li:visible');
			var navigation = $('.nav');
			var index = tab.index();

			

			var validator = $(".validateform").validate();
			
			if(validator.checkForm() == true) {

				$(".package-container").hide();
				$(".panel-group").hide();
				$(".plan-details").hide();

				$('.form-group').removeClass('has-error');
				$('.help-block').remove();


				if(next_tab) {
					$(next).removeClass('disabled');
					if(tab) {
						$(tab).addClass('selected');
					}
					$('.nav li.active').removeClass('active');
					next.addClass('active');
					$('.form-wizard').find("a[href*='"+next_tab+"']").trigger('click');
					tab_click(tab, navigation, index);
					$('.payment').hide();
					if(next_other_tab == undefined) {
						$('.next').hide();
						$('.prev').show();
						$('.payment').show();
					}
					return false;
				}

				
			} else {
				$('.form-group').addClass('has-error');
					validator.showErrors();
				}
	});

	$('.payment').on('click', function() {
		if($(".validateform").valid()) {
			$(".validateform").submit();
		}
	});


	$('.edit_panel').on('click', function() {
		$('.validateform').find('.portlet-body').slideUp();
		$(this).closest('.portlet').find('.portlet-body').slideToggle();
	});

	//get_address_type($('input[name="address_type"]:checked').val());

	/*$('input[name="address_type"]').on('change', function(){
		get_address_type($(this).val());
	});*/


	$('select[name="address"]').on('change', function(){
		var id = $(this).val();

		if(id != "") {

			$('.address_container').show();
			
			var address_type = $('input[name="address_type"]:checked').val();
			var city = $('select[name="city"]');
			$.ajax({
					url: '{{route('get_address')}}',
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						id: id,
						address_type: address_type 
					},
					success: function(data, textStatus, jqXHR) {
						var result = data.address;
						var cities = data.cities;
						city.empty();
						city.append('<option value="">Select City</option>');
						if(city.length > 0) {
							for(j in cities) {
								city.append('<option value="'+cities[j].id+'">'+cities[j].name+'</option>');
							}
						}
							$('input[name="door"]').val(result.door);
							$('input[name="street"]').val(result.street);
							$('input[name="area"]').val(result.area);
							$('select[name="city"]').select2('val', result.city);
							$('select[name="state"]').select2('val', result.state);
							$('input[name="pin"]').val(result.pin);
							$('input[name="landmark"]').val(result.landmark);
							$('input[name="phone"]').val(result.phone);
							$('input[name="mobile_no"]').val(result.mobile_no);
							$('input[name="email_address"]').val(result.email_address);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						//alert("New Request Failed " +textStatus);
					}
				});
			
		} else {
			$('.address_container').hide();
		}
	});

	/*$('input[name="change_package"]').on('change', function(){
		if($(this).is(":checked")) {
			$(".package").show();
			$(".package_text").hide();
		} else {
			$('select[name="package_id"]').val($('input[name="existing_package"]').val());
			$(".package").hide();
			$(".package_text").show();
		}
	});*/


	


	$( "select[name=state]" ).on('change', function () {
		var city = $( "select[name=city]" );
		var area = $( "select[name=area]" );
		var select_val = $(this).val();
		city.empty();
			city.append("<option value=''>Select City</option>");
		if(select_val != "") {
		$('.loader_wall_onspot').show();
			$.ajax({
				 url: "{{ route('get_city') }}",
				 type: 'post',
				 data: {
					_token :$('input[name=_token]').val(),
					state: select_val
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						for(var i in result) {  
							city.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
		}
  	});

 });





	function get_estimate(plan, term, ledger, package) {
		$.ajax({
			url: '{{route('get_estimate_price')}}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				package: package,
				plan: plan,
				term: term,
				ledger: ledger
			},
			success: function(data, textStatus, jqXHR) {
				$('.package_name').text(data.package);
				$('.module_name').text(data.modules);
				$('.module_price').html(' ' + data.package_price);
				$('.ledger_price').html(' ' + data.ledger_price);
				$('.ledger_name').text(data.ledger);
				if(data.ledger != 0) {
					$('.ledger_row').show();
				} else {
					$('.ledger_row').hide();
				}
				if(data.discount != 0) {
					$('.discount').show();
					$('.discount_amount').html('-' + data.discount_amount);
					$('.discount_percent').text(data.discount + "%");
				} else {
					$('.discount').hide();
					$('.discount_amount').text("");
					$('.discount_percent').text("");
				}
				$('.sub_total').html(' ' + data.subtotal);

				$('.plan_table').find('tr.tax_row').remove();

				if((data.tax).length > 0) {

					var tax_html = "";

					for(var i in data.tax) {
						tax_html += `<tr class="tax_row"><td style="text-align: right; font-size: 12px;" colspan="2">`;
						tax_html += data.tax[i].name+"&nbsp;&nbsp;&nbsp;&nbsp;"+data.tax[i].amount+`</td></tr>`;
					}

					$('.plan_table tbody').find('tr').last().prev().after(tax_html);
				}

				$('.total').html('<h3><i class="fa fa-inr"></i> '+data.total+'</h3>');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
	}	

	/*function get_address_type(value) {


		$('.address_type').show();

		var address = $('select[name="address"]');
		//$('.address_container').hide();
		address.select2('val', '');

		$.ajax({
				url: '{{route('get_address_type')}}',
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					address_type: value 
				},
				success: function(data, textStatus, jqXHR) {
					address.empty();
					address.append('<option value="">Select Address</option>');
					for(var i in data) {
						address.append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
				}
			});
	}*/

	function tab_click(tab, navigation, index) {
			var total = navigation.find('li').length;
			var current = index+2;
			var percent = (current/total) * 100;
			$('.form-wizard').find('.progress-bar').animate({width:percent+'%'});

			if(total == current) {
				$('.next').hide();
				$('.payment').show();
			} else {
				$('.next').show();
				$('.payment').hide();
			}

			if(current == 1) {
				$('.prev').hide();
			} else {
				$('.prev').show();
			}
	}

	$('.validateform').validate({
				errorElement: 'span', //default input error message container
				errorClass: 'help-block', // default input error message class
				focusInvalid: false, // do not focus the last invalid input
				rules: {
					plan_id: {
						required: true
					},
					term_period_id: {
						required: true
					},
					ledger_id: {
						required: true
					},
					address_type: {
						required: true
					},
					address: {
						required: true
					},
					email_address: {
						required: true,
						email: true
					},
					mobile_no: {
						required: true,
						number: true,
						minlength: 10,
						maxlength: 10
					},
					door: {
						required: true
					},
					street: {
						required: true
					},
					area: {
						required: true
					},
					city: {
						required: true
					},
					state: {
						required: true
					},
					pin: {
						required: true
					}
				},

				messages: {
					plan_id: {
						required: "Plan is required"
					},
					term_period_id: {
						required: "Term period is required"
					},
					ledger_id: {
						required: "Ledger is required"
					},
					address_type: {
						required: "Address Type is required."
					},
					address: {
						required: "Address is required."
					},
					email: {
						required: "Email is required"
					},
					mobile_no: {
						required: "Mobile Number is required"
					},
					door: {
						required: "Door No is required."
					},
					street: {
						required: "Street name is required."
					},
					area: {
						required: "Area name is required."
					},
					city: {
						required: "City is required."
					},
					state: {
						required: "State  is required."
					},
					pin: {
						required: "Pincode is required."
					}
				},

				invalidHandler: function(event, validator) { //display error alert on form submit   
					$('.alert-danger', $('.login-form')).show();
				},

				highlight: function(element) { // hightlight error inputs
					$(element)
						.closest('.form-group').addClass('has-error'); // set error class to the control group
				},

				success: function(label) {
					label.closest('.form-group').removeClass('has-error');
					label.remove();
				},

				submitHandler: function(form) {
					$('.loader_wall').show();
					form.submit(); // form validation success, call ajax form submit
				}
	});


</script>
@stop