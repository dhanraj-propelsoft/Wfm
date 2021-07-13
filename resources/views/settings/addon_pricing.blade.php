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
  <h4 class="float-left page-title">@if($type == "addon_upgrade") Addon  @endif</h4>
</div>

				{!! Form::open([
				'route' => 'subscribe.addon_pricing_store',
				'class' => 'form-horizontal validateform'
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
						  <span class="desc"> Choose Addon </span> </a> </li>

						

						<li class="disabled"> <a href="#tab3" data-toggle="tab" class="step"> <span class="number"> 2 </span><br>
						  <span class="desc"> &nbsp; Confirm &nbsp;&nbsp;&nbsp; </span> </a> </li>
				  	</ul>

				<div class="bar progress progress-striped" role="progressbar">
					<div class="progress-bar progress-bar-success" style="width: 33%;"> </div>
				</div>

				<div class="tab-content">

					<div class="tab-pane" id="tab1">				

					</div>


					

					<div class="tab-pane" id="tab3">
					  	<div class="form-group"> 
					  		{!! Form::label('', '', ['class' => 'control-label col-md-3']) !!} 
					  	</div>
					  	<p><b>Note </b>The above given information are true to best of my Knowledge. I accept them by proceeding it.</p>
					</div>

				  </div>

				</div>

			  </div>

			<div style="position: fixed; right: 10px; " class="col-md-4 plan-container custom-panel">

			  	{!! Form::hidden('addon_id', $id) !!}

			  	{!! Form::hidden('type', $type) !!}

					<h6> You've Selected :</h6>

						<div class="form-group col-md-12 ">
							<div class="row">
								<div class="col-md-7 info ">
									{!! Form::select('package_id', $package_plan, $default_package->package_id, ['class' => ' form-control  required','style' => 'background-color: #fdd787;','disabled']); !!}
								</div>
								<div class="col-md-5 info ">				
							  		
								</div>
							</div>
						</div>				

						<!-- <div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-12 info package">
							  		
							  		
								</div>
							</div>
						</div> -->

						<div class="form-group col-md-12 ">
							<div class="row">
								<div class="col-md-7 info plan ">
									{!! Form::select('plan_id', $subscription_plan, $default_package->plan_id, ['class' => ' form-control required','style' => 'background-color: #fdd787;','disabled']); !!}
								</div>
								<div class="col-md-5 info price">

									<!-- Rs. {{$plan_details->price}} -->						  		
								</div>
							</div>
						</div>

						

						<div class="form-group col-md-12 ">
							<div class="row">
								<div class="col-md-7 info plan ">

								@if($id == 1)
									{!! Form::select('addon_pricing_id', $addon_ledger, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}
								@endif

								@if($id == 2)
									{!! Form::select('addon_pricing_id', $addon_sms, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 3)
									{!! Form::select('addon_pricing_id', $addon_employee, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 4)
									{!! Form::select('addon_pricing_id', $addon_customer, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 5)
									{!! Form::select('addon_pricing_id', $addon_supplier, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 6)
									{!! Form::select('addon_pricing_id', $addon_purchase, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 7)
									{!! Form::select('addon_pricing_id', $addon_invoice, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 8)
									{!! Form::select('addon_pricing_id', $addon_grn, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 9)
									{!! Form::select('addon_pricing_id', $addon_vehicles, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 10)
									{!! Form::select('addon_pricing_id', $addon_jobcard, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 11)
									{!! Form::select('addon_pricing_id', $addon_transaction, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 13)
									{!! Form::select('addon_pricing_id', $addon_storage, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								@if($id == 14)
									{!! Form::select('addon_pricing_id', $addon_call_hour, null, ['class' => ' form-control required','style' => 'background-color: #fdd787;']); !!}	
								@endif

								</div>
								<div class="col-md-5 info addon_pricing">

									
							  		
								</div>
							</div>
						</div>

						
						
						<!-- <div class="form-group col-md-12">
							<div class="row">
								<div class="col-md-7 info addon">
									Free Addons		
								</div>
								<div class="col-md-5 info addon_price">
									Rs. 0.00	
								</div>
							</div>
						</div> -->

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

									Total Payment
							  		
								</div>

								<div class="col-md-5 total_payment total_price get_price">
							
							  		
								</div>

								{!! Form::hidden('total_payment',null, ['class' => 'form-control']) !!}

								{!! Form::hidden('addon_value',null, ['class' => 'form-control']) !!}

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

			


	</form>		
		
			
{!! Form::close() !!}


			

   

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
		$('input[name=package_id1]').prop('checked', false);
		$('.package_select').find('.selected').hide();
		$('.package_select').css('background', 'none');
		$(this).parent().find('input[name=package_id1]').prop('checked', true);
		$(this).closest('.package_select').find('.selected').show();
		$(this).closest('.package_select').css('background', '#ccc');

	}, function() {
		$('input[name=package_id1]').prop('checked', false);
		$('.package_select').find('.selected').hide();
		$('.package_select').css('background', 'none');
		$(this).parent().find('input[name=package_id1]').prop('checked', true);
		$(this).closest('.package_select').find('.selected').show();
		$(this).closest('.package_select').css('background', '#ccc');
	});


	/*setTimeout(function() { 
		$(this).closest('.package_select').trigger('click'); 
	}, 100);*/

	
	

	$("select[name=addon_pricing_id]" ).on('change', function () {

		var addon_price_id = $('select[name=addon_pricing_id]').val();
		var addon_id = $('input[name=addon_id]').val();		

		if(addon_price_id !='')
		{
			$.ajax({
				 url: "{{ route('subscribe.get_addon_details') }}",
				 type: 'post',
				 data: {
					_token :$('input[name=_token]').val(),					
					addon_price_id : addon_price_id,
					addon_id : addon_id,
					
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						
						var result = data.result;

						for(var i in result) {									

							var pricing_id = result[i].id;
							var value = result[i].value;
							var price = result[i].price;
							var addon_id = result[i].addon_id;
						}

						//$(".plan").text(plan_name);
						$(".addon_pricing").text('Rs. '+price);

						$(".addon").text('Free Addons');
						$(".addon_price").text('Rs. 0.00');

						$(".total_title").text('Total Payment');
						
						$(".total_price").text('Rs. '+price);

						$("input[name=total_payment]").val(price);
						$("input[name=addon_value]").val(value);		


						$('.loader_wall_onspot').hide();


					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
			})

		}

	});


	

	$('.form-wizard').bootstrapWizard({onTabClick: function(tab, navigation, index) {
			if($(tab).hasClass('disabled')) {
				return false;
			}
		}
	});		



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

			$(".package-container").hide();
			$(".panel-group").hide();
			$(".plan-details").hide();

			var validator = $(".validateform").validate();
			
			if(validator.checkForm() == true) {
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

		$('select[name=package_id]').removeAttr('disabled');
		$('select[name=plan_id]').removeAttr('disabled');

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

	$('input[name="change_package"]').on('change', function(){
		if($(this).is(":checked")) {
			$(".package").show();
			$(".package_text").hide();
		} else {
			$('select[name="package_id"]').val($('input[name="existing_package"]').val());
			$(".package").hide();
			$(".package_text").show();
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
					addon_pricing_id: {
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
					addon_pricing_id: {
						required: "Ledger is required"
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