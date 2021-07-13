@extends('layouts.app')
@section('head_links')
@parent
	<style>
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
@section('content')


<div class="user-login">
  <div class="row bs-reset">
	<div class="col-md-12 login-container bs-reset">

	<div  class="login-content">

		
		<div><h5>1.Select Package</h5></div>

		
		@if($errors->any())
		<div class="alert alert-info"> {{$errors->first()}} </div>
		@endif

		
		
		@if(Session::has('flash_message'))
		<div class="alert alert-success"> 
			{{ Session::get('flash_message') }} 
		</div>
		@endif

		<?php $error_value = null; ?>

		{!! Form::open(['route' => 'register_business.store', 'class' => 'form-horizontal validateform ' ]) !!}

		{!! Form::hidden('business_id',$business_id) !!}

		<div class="form-body business">

		  	<div class="row">
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('business_name', 'Business User Name', ['class' => 'control-label']) !!}
					{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}
					
					{!! Form::text('business_name', $mybusiness->business_name, ['class' => 'form-control']) !!} </div>
				</div>
				
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('alias', 'Company Name', ['class' => 'control-label']) !!}
					{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}
					{!! Form::text('alias', $mybusiness->business_name, ['class' => 'form-control']) !!} </div>
				</div>
				 
		  	</div>

		  	<div class="row">
				<div class="col-md-6">
			  		<div class="form-group">
				  		{!! Form::label('business_nature', 'Nature Of Business', array('class' => 'control-label required')) !!}

				  		{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}
					
						<select class="select_item form-control" placeholder="Select Nature of Business" name="business_nature">
						  <option value="">Select Business Nature</option>
						  
						@foreach($businessnature as $nature)

						<option value="{{ $nature->id }}" 
						@if(isset($request)) 
							@if($nature->id == $request->business_nature) 
							selected="true" @endif
						@endif> {{ $nature->name}} </option>
										  
						@endforeach
														
						</select>
			  		</div>
				</div>
				
				<div class="col-md-6">
			  		<div class="form-group">
				   	{!! Form::label('business_professionalism_id', 'Business Profession', ['class' => 'control-label']) !!}
					{!! Form::label('', '*', ['class' => 'control-label']) !!}
					<select class="select_item form-control" placeholder="Select Nature of Business" name="business_professionalism">
					  <option value="">Select Business Professionalism</option>
				  
					@foreach($businessprofessionalism as $profession)

						<option value="{{ $profession->id }}" 
						@if(isset($request)) 
							@if($profession->id == $request->business_professionalism) 
									  selected="true" 
							@endif 
						@endif> {{ $profession->name}} </option>
									  
					@endforeach
																
						</select>
			  		</div>
				</div>
			
		  	</div>

		  <div class="row"> 
			
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('mobile', 'Mobile', ['class' => 'control-label']) !!}
					{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}
					{!! Form::text('mobile', $mybusiness->mobile_no, ['class' => 'form-control numbers','placeholder'=>'Mobile']) !!} </div>
				</div>
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
					
					{!! Form::text('phone', $mybusiness->phone, ['class' => 'form-control numbers','placeholder'=>'Phone']) !!} </div>
				</div>
			
			 
		  </div>

		  	<div class="row">
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
					{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}
					{!! Form::text('email', $mybusiness->email_address, ['class' => 'form-control','placeholder'=>'Email']) !!} </div>
				</div>
				
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('web', 'web', ['class' => 'control-label']) !!}
					
					{!! Form::text('web', $mybusiness->web_address, ['class' => 'form-control','placeholder'=>'Web']) !!} </div>
				</div>
		  	</div>
		 
		  
		  	<div class="row">
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('state', 'State', ['class' => 'control-label']) !!}
					{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}

					<?php 
				  		$states = null;
				  		if(isset($mybusiness->state_id)) { $states = $mybusiness->state_id; } ?>

						{!! Form::select('state',$state, $states, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!}
					
					 </div>
				</div>
				<div class="col-md-6">
				  <div class="form-group"> 
				  	{!! Form::label('city', 'City', ['class' => 'control-label']) !!}
					{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}
					<?php 
				  		$cities = null;
				  		if(isset($mybusiness->city_id)) { $cities = $mybusiness->city_id; } ?>

						{!! Form::select('city', $city, $cities, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!} 

					 </div>
				</div>
			
		  	</div>
		  	<div class="row">
				<div class="col-md-6">
				  <div class="form-group"> {!! Form::label('pan', 'PAN', ['class' => 'control-label']) !!}
					
					{!! Form::text('pan', $mybusiness->pan, ['class' => 'form-control','placeholder'=>'PAN']) !!} </div>
				</div>
				<div class="col-md-6">
				  <div class="form-group"> {!! Form::label('tin', 'TIN', ['class' => 'control-label']) !!}
					{!! Form::text('tin', $mybusiness->tin, ['class' => 'form-control','placeholder'=>'TIN']) !!} </div>
				</div>
			
		  	</div>
		  	<div class="row">
				<div class="col-md-6">
				  <div class="form-group"> {!! Form::label('gst', 'GST', ['class' => 'control-label']) !!}
					
					{!! Form::text('gst', $mybusiness->gst, ['class' => 'form-control','placeholder'=>'GST']) !!} </div>
				</div>
			
		  	</div>
		  	<div class="row"> 
			
				@foreach($businessinformation as $information)
				<div class="col-md-6">
				  <div class="form-group"> {!! Form::label($information->id, $information->name, ['class' => 'control-label']) !!}
					{!! Form::text($information->id, null, ['class' => 'form-control','placeholder'=>$information->name]) !!} </div>
				</div>
				@endforeach 
		
		  	</div>
		


		<div class="form-actions float-right">
		  <!-- <button type="button" class="btn btn-default">Cancel</button> -->
		  <a style="color: #fff" class="btn btn-success business business_btn">Next<i class="fa fa-chevron-right"></i></a>
		  
		</div>

		</div>


		<div class="form-body modules" style="display: none;">

			<div class="row">

				<div class="col-md-8">
					<div class="container-fluid">		  	

					<div class="row"> 
						@foreach($packages as $package)

							@if($package->display_name != 'Books' && $package->display_name != 'HRM' && $package->display_name != 'Inventory' && $package->display_name != 'WFM')

						  <div class="col-md-3">
							<div style="border-radius:5px; position: relative; margin:5px; padding: 5px;  margin-bottom: 15px; text-align: center;" class="package_select" > <i style="color: #fff; position: absolute; right: 0; padding: 15px; display: none;" class="fa fa-check selected" aria-hidden="true"></i>

							<img width="50" src="{{ URL::to('/') }}/public/package/{{ $package->id }}.png">

							  <h6 style="color:#000; font-weight: bold;">{{$package->display_name}}</h6>

							  <p style="color:#000;  font-size: 12px;">{{$package->modules}}</p>

							  <input type="radio" value="{{$package->id}}" name="package_id"></div>
						  </div>

						  @endif
					  @endforeach 
					</div>

					</div >
				</div>

				<div class="col-md-4 custom-panel">

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
								{!! Form::select('subscription_plan', $subscription_plan, null, ['class' => ' form-control','style' => 'background-color: #fdd787; ']); !!}
							</div>
							<div class="col-md-5 info price">
						  		
							</div>
						</div>
					</div>

					
					<div class="form-group col-md-12">
						<div class="row">
							<div class="col-md-7 info addon">
						  		
							</div>
							<div class="col-md-5 info addon_price">
						  		
							</div>
						</div>
					</div>

					<div class="form-group col-md-12">
						<div class="row">
							<div class="col-md-7 total_payment total_title">
						  		
							</div>
							<div class="col-md-5 total_payment total_price">
						  		
							</div>
						</div>
					</div>



					<div class="form-actions form-group row" style="">

					<button type="submit" class="btn btn-success modules_save" style="display: none; margin: auto; "><i class="fa fa-check"></i>Continue</button>

					</div>
					
				</div>

			</div>
		
			<br>

			<div><h5>2.Select the Plan of the Package</h5></div>

			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				
				<!-- Free14days Plan = 1 -->
				<div class="panel panel-default">

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
				<div class="panel panel-default">

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
				<div class="panel panel-default">

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
				<div class="panel panel-default">

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
				<div class="panel panel-default">

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
				<div class="panel panel-default">

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
				<div class="panel panel-default">

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

   		</div>
		  

		</div> 

		
		{!! Form::close() !!} 

	</div>

	</div>
  </div>
</div>
@stop

  @section('dom_links')
  @parent 
<script>

$(document).ready(function() {

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

	

	$(".package_select" ).on('click', function () {

		var package_val = $('input[name=package_id]:checked').val();

		$('.books-free, .hrm-free, .wfm-free, .inventory-free, .trade-free, .wms-free, .books-starter, .hrm-starter, .wfm-starter, .inventory-starter, .trade-starter, .wms-starter, .books-lite, .hrm-lite, .wfm-lite, .inventory-lite, .trade-lite, .wms-lite, .books-standard, .hrm-standard, .wfm-standard, .inventory-standard, .trade-standard, .wms-standard, .books-professional, .hrm-professional, .wfm-professional, .inventory-professional, .trade-professional, .wms-professional, .books-enterprise, .hrm-enterprise, .wfm-enterprise, .inventory-enterprise, .trade-enterprise, .wms-enterprise, .books-corporate, .hrm-corporate, .wfm-corporate, .inventory-corporate, .trade-corporate, .wms-corporate').hide();		

		if(package_val != '')
		{
			$.ajax({
				 url: "{{ route('business_register.get_package_details') }}",
				 type: 'post',
				 data: {
					_token :$('input[name=_token]').val(),					
					package : package_val
					
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						
						var result = data.module_result;

						modules = [];

						for(var i in result) {							

							modules.push(result[i].module);					
						}

						var modules = modules.toString();

						$(".package").text(modules);

						//console.log(modules.toString());

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

	$("select[name=subscription_plan]" ).on('change', function () {		
		
		var package_val = $('input[name=package_id]:checked').val();
		var plan_val = $("select[name=subscription_plan]").val();				


		if(package_val !='' && plan_val !='')
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

	$('.validateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				business_name: {
					required: true,
					remote: {
						url: '{{ route('check_business') }}',
						type: "post",
						data: {
						  _token :$('input[name=_token]').val()
						}
					}
				},
				alias: {
					required: true
				},
				business_nature: {
					required: true
				},
				business_professionalism: {
					required: true
				},
				state: {
					required: true
				},
				city: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				mobile: {
					required: true,
					mobileIND: true
				},
				package_id: {

					required: true
				},
				pan: {
					 pan: true
				},
				gst: {
					required: true,
					 gst: true
				},
				tin: {
					 tin: true
				},
				subscription_plan: {
					required: true
				}
			},

			messages: {
				business_name: {
					required: "Business Name is required.",
					remote: "Business name already in use!"
				},
				alias: {
					required: "Company Name is required.",
				},
				business_nature: {
					required: "Select Nature of Business."
				},
				business_professionalism: {
					required: "Select Business Profession."
				},
				state: {
					required: "Select Your State."
				},
				city: {
					required: "Select Your City."
				},
				email: {
					required: "Email is required."
				},
				mobile: {
					required: "Mobile Number is required."
				},
				package_id: {
					required: "Package is required."
				},
				gst: {
					required: "GST is required."
				},
				subscription_plan: {
					required: "Subscription Plan is required."
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

			/*errorPlacement: function(error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},*/

			submitHandler: function(form) {
				$('.loader_wall').show();
				form.submit(); // form validation success, call ajax form submit
			}
	});

	

	
  
	</script> 
@stop