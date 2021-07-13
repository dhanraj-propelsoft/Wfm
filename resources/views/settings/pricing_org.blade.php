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
<div class="clearfix"></div>
	<div class="row">
	  	<div class="col-md-12">
			<div class="steps">
			  <div class="row step-line"> </div>
			</div>
			<div class="row">
			  <div class="col-md-8"> 
			  	{!! Form::open([
				'route' => 'subscribe.store',
				'class' => 'form-horizontal validateform subscription_form'
				]) !!}
				{{ csrf_field() }}
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

					<div class="tab-pane active" id="tab1">
						<div class="form-group"> 
						  	{!! Form::label('', '', ['class' => 'control-label col-md-3']) !!} </div>
						  {!! Form::hidden('type', $type) !!}

						@if($type == "upgrade" || $type == "plan-change")
						  <div class="form-group">
							<div class="row offset-md-3">
							</div>
						  </div>
							@endif
							
						  <div class="form-group">
							<div class="row"> {!! Form::label('package_id', 'Package', ['class' => 'control-label col-md-3 required']) !!}
							{!! Form::hidden('existing_package', $package->package_id) !!}
							  <div class="col-md-4  package" style="display: none;"> {!! Form::select('package_id', $package_list, $package->package_id, ['class' => 'form-control select2_category']) !!} </div>
							  <div class="col-md-4  package_text" > {!! Form::text('', $package->package, ['class' => 'form-control select2_category', 'disabled']) !!} </div>
							<div class="col-md-4">
							  {{ Form::checkbox('change_package', 'change_package', false, ['id' => 'change_package']) }}
							  <label for="change_package"><span></span>Change package.</label>
							  </div>
							</div>
						  </div>
						  
						  @if($type == "upgrade" || $type == "plan-change")
						  <div class="form-group">
							<div class="row"> {!! Form::label('plan_id', 'Plan', ['class' => 'control-label col-md-3 required']) !!}
							  <div class="col-md-4"> {!! Form::select('plan_id', $plans, null, ['class' => 'form-control select2_category']) !!} </div>
							</div>
						  </div>
						  @endif
						  
						  @if($type == "upgrade" || $type == "renew")
						  <div class="form-group">
							<div class="row"> {!! Form::label('ledger_id', 'Ledgers', ['class' => 'control-label col-md-3 required']) !!}
							@if($type == "renew")
							  <div class="col-md-4">{!! Form::text('', $current_size->value, ['class' => 'form-control select2_category', 'disabled']) !!} </div> @endif
							@if($type == "upgrade")
							  <div class="col-md-4"> {!! Form::select('ledger_id', $ledgers, null, ['class' => 'form-control select2_category']) !!} </div> @endif
							</div>
						  </div>
						   @endif
						
						@if($type == "upgrade" || $type == "renew")
						  <div class="form-group">
							<div class="row"> {!! Form::label('term_period_id', 'Term Period', ['class' => 'control-label col-md-3 required']) !!}
							  <div class="col-md-4"> {!! Form::select('term_period_id', $term_periods, null, ['class' => 'form-control select2_category']) !!} </div>
							</div>
						  </div>
						  @endif

					</div>

					<div class="tab-pane" id="tab2">
					  <div class="form-group"> {!! Form::label('', '', ['class' => 'control-label col-md-3']) !!} </div>
					  <div class="form-group">
						<div class="row"> {!! Form::label('name', 'Name', ['class' => 'control-label col-md-3 required']) !!}
						  <div class="col-md-8"> {!! Form::text('name', $organization_name, ['class' => 'form-control']) !!} </div>
						</div>
					  </div>
					  <div class="form-group">
						<div class="row"> {!! Form::label('plan_id', 'Address Type', ['class' => 'control-label col-md-3 required']) !!}
						  &nbsp;&nbsp;
						  {{ Form::radio('address_type', 'personal', false, ['id' => 'personal']) }}
						  <label for="personal"><span></span>Personal Address</label>
						  &nbsp;&nbsp;&nbsp;
						  {{ Form::radio('address_type', 'business', true, ['id' => 'business']) }}
						  <label for="business"><span></span>Business Address</label>
						</div>
					  </div>
					  <div class="form-group address_type">
						<div class="row"> {!! Form::label('', '', ['class' => 'control-label col-md-3 required']) !!}
						  <div class="col-md-8">
							<select name="address" class="form-control select_item">
							  <option value="">Select Address</option>
							</select>
						  </div>
						</div>
					  </div>
					  <div style="display: none;" class="address_container">
						<div class="form-group">
						  <div class="row"> {!! Form::label('door', 'Door No / Block', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('door', null, ['class' => 'form-control']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('street', 'Street', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('street', null, ['class' => 'form-control']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('state', 'State', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::select('state', $state, null, ['class' => 'select2_category form-control']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('city', 'City', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::select('city', ['' => 'Select City'], null, ['class' => 'select2_category form-control']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('area', 'Area', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('area', null, ['class' => 'form-control' ]) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('pin', 'Pin', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('pin', null, ['class' => 'form-control numbers']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('landmark', 'Landmark', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('landmark', null, ['class' => 'form-control']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('mobile_no', 'Mobile Number', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('mobile_no', null, ['class' => 'form-control numbers']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('phone', 'Phone', ['class' => 'control-label col-md-3']) !!}
							<div class="col-md-8 "> {!! Form::text('phone', null, ['class' => 'form-control numbers']) !!} </div>
						  </div>
						</div>
						<div class="form-group">
						  <div class="row"> {!! Form::label('email_address', 'Email', ['class' => 'control-label col-md-3 required']) !!}
							<div class="col-md-8"> {!! Form::text('email_address', null, ['class' => 'form-control']) !!}
							  
							  
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

				<div class="form-actions">
				  <div class="row">
					<div class="col-md-11"> <a href="javascript:;" class="btn btn-success next float-right"> Continue <i class="fa fa-angle-right"></i> </a> <a style=" display: none;"  href="javascript:;" class="btn btn-success payment float-right"> Make Payment <i class="fa fa-angle-right"></i> </a> <a style="margin-right: 10px; display: none;" href="javascript:;" class="btn btn-default previous prev float-right"> <i class="fa fa-angle-left"></i> Back </a> </div>
				  </div>
				</div>

				</form>

			  </div>
			  <div style="position: fixed; right: 10px; display: none;" class="col-md-4 plan-container">

				<div class="plan-box">
				  <div class="plan-box-header clearfix">
					<div class="subscription-box"> <font class="package_name" style="font-size: 22px">{{$package->package}}</font> <span class="module_name">{{$package->modules}}</span> </div>
				  </div>
				  <table class="table plan_table">
					<thead>
					  <tr>
						<th style="width: 150px"> </th>
						<th style="text-align: center;"> </th>
					  </tr>
					</thead>
					<tbody>
					  <tr class="package_row">
						<td valign="middle" align="left"><span>Package</span></td>
						<td valign="middle" align="right"><span class="module_price"></span></td>
					  </tr>
					  <tr style="display: none;" class="ledger_row">
						<td valign="middle" align="left"><span>Ledgers</span></td>
						<td valign="middle" align="right"><span class="ledger_price"></span></td>
					  </tr>
					  <tr class="sub_total_row">
						<td valign="middle" align="left"><span>Sub-Total</span></td>
						<td valign="middle" align="right"><span class="sub_total"></span></td>
					  </tr>
					  <tr class="discount">
						<td valign="middle" align="left"><span>Discount (<span class="discount_percent"></span>) <br>
						  <span style="font-size:10px; color: #b73c3c;">*For Package only</span></span></td>
						<td valign="middle" align="right"><span class="discount_amount"></span></td>
					  </tr>
					  <tr class="total_row">
						<td valign="middle" align="left"><span style="font-size: 24px;">Total</span></td>
						<td valign="middle" align="right"><span class="total"></span></td>
					  </tr>
					</tbody>
				  </table>
				</div>

			  </div>
			</div>
	  	</div>
	</div>


	

@stop
@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/jquery.bootstrap.wizard.js') }}"></script>
<script>
$(document).ready(function() { 

	sidebar_minimized();

	
	$('.form-wizard').bootstrapWizard({onTabClick: function(tab, navigation, index) {
		if($(tab).hasClass('disabled')) {
			return false;
		}
	}
	});

	$('select[name="plan_id"], select[name="term_period_id"]').on('change', function(){
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

		


		if(term != "" && plan != "" && package_id != "" @if($type == "upgrade") && ledger != "" @endif) {
			get_estimate(plan, term, ledger, package_id);
			$(".plan-container").show();
		} else {
			$(".plan-container").hide();
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
		if($(".validateform").valid()) {
			$(".validateform").submit();
		}
	});


	$('.edit_panel').on('click', function() {
		$('.validateform').find('.portlet-body').slideUp();
		$(this).closest('.portlet').find('.portlet-body').slideToggle();
	});

	get_address_type($('input[name="address_type"]:checked').val());

	$('input[name="address_type"]').on('change', function(){
		get_address_type($(this).val());
	});


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

	function get_address_type(value) {


		$('.address_type').show();

		var address = $('select[name="address"]');
		$('.address_container').hide();
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