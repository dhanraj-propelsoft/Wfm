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
  <h4 class="float-left page-title">Buy Addons</h4>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12">
	<div class="steps">
	  <div class="row step-line"> </div>
	</div>
	<div class="row">
	  <div class="col-md-8"> {!! Form::open([
		'route' => 'store_addon',
		'class' => 'form-horizontal validateform subscription_form'
		]) !!}
		{{ csrf_field() }}
		<div class="form-wizard">
		  <ul class="nav steps">
			<li class="active"> <a href="#tab1" data-toggle="tab" class="step active"> <span class="number"> 1 </span><br>
			  <span class="desc"> Choose Addons </span> </a> </li>
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
			  <div class="form-group"> {!! Form::label('', '', ['class' => 'control-label col-md-3']) !!} </div>

			
			  <div class="form-group">
			  	<div class="row">{!! Form::label('addons', 'Addons', ['class' => 'control-label col-md-3 required']) !!}
				<div class="col-md-9">
				@foreach($addons as $addon)
				  {{ Form::checkbox('addons', $addon->id, false, ['id' => $addon->display_name, 'data-name' => $addon->name]) }}
				  <label for="{{$addon->display_name}}"><span></span>{{$addon->display_name}}</label>
				  &nbsp;&nbsp;&nbsp;
				@endforeach
				  </div>
			  </div>
			  </div>

			  <div style="display: none;" class="form-group addons records">
				<div class="row"> {!! Form::label('ledger_id', 'Ledgers', ['class' => 'control-label col-md-3 required']) !!}
				  <div class="col-md-4"> 
				  <select name="ledger_id" class="form-control select2_category">
					<option value="">Select Ledger Pack</option>
					@foreach($ledgers as $ledger)
						<option data-price="{{$ledger->price}}" data-value="{{$ledger->size}}" value="{{$ledger->id}}">{{$ledger->display_name}}</option>
					@endforeach
				</select> 
				</div>
				</div>
			  </div>



			  <div style="display: none;" class="form-group addons sms">
				<div class="row"> {!! Form::label('sms_id', 'SMS', ['class' => 'control-label col-md-3 required']) !!}
				  <div class="col-md-4"> 
				<select name="sms_id" class="form-control select2_category">
					<option value="">Select SMS Pack</option>
					@foreach($sms as $sm)
						<option data-price="{{$sm->price}}" value="{{$sm->id}}">{{$sm->display_name}}</option>
					@endforeach
				</select> </div>
				</div>
			  </div>


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
	  <div style="position: fixed; right: 10px; display: none; " class="col-md-4 plan-container">
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
			  <tr class="addons sms">
				<td valign="middle" align="left"><span>SMS</span></td>
				<td valign="middle" align="right"><span class="sms_price"></span></td>
			  </tr>
			  <tr class="addons records">
				<td valign="middle" align="left"><span>Ledgers</span><br>
				<span style="font-size:10px; color: #b73c3c;">{{$remaining_day_text}}</span></td>
				<td valign="middle" align="right"><span class="ledger_price"></span></td>
			  </tr>
			  <tr>
				<td valign="middle" align="left"><span>Sub-Total</span></td>
				<td valign="middle" align="right"><span class="sub_total"></span></td>
			  </tr>
			  @foreach($tax_array as $tax)
			  <tr>
				<td colspan="2" align="right" style="text-align: right; font-size: 12px;">{{ $tax['name'] }} @ {{$tax['amount']}}% on <span  class="tax_amount"></span></td>
			  </tr>
			  @endforeach
			  
			  <tr>
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

	

	var ledger_price = 0;
	var sms_price = 0;

	sidebar_minimized();

	$('.form-wizard').bootstrapWizard({onTabClick: function(tab, navigation, index) {
		if($(tab).hasClass('disabled')) {
			return false;
		}
	}});


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

			if($('input[name="addons"]:checked').length <= 0) {
				return false;
			}
			
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

	$('input[name="addons"]').on('change', function(){

		$(".addons").hide();

		if($('input[name="addons"]:checked').length > 0) {
			$(".plan-container").show();
		} else {
			$(".plan-container").hide();
		}

		$('input[name="addons"]').each(function() {
			var obj = $(this);

			if(obj.is(":checked")) {
				$("."+obj.data('name')).show();
			} else {
				$("."+obj.data('name')).find('input, select').val("");
			}

			$("."+obj.data('name')).find('select').trigger("change");

		});

	});

	$('select[name="ledger_id"], select[name="sms_id"]').on('change', function() {

		var ledger = $('select[name="ledger_id"]');
		var sms = $('select[name="sms_id"]');

		if(ledger.val() != "") {
			var single_ledger = ledger.find("option:selected").data('price') / 30;

			ledger_price = parseFloat((single_ledger * {{$remaining_days}}) - {{$remaining_ledger_amount}}).toFixed(2);
		} else {
			ledger_price = 0.00;
		}

		if(sms.val() != "") {
			sms_price = sms.find("option:selected").data('price');
		} else {
			sms_price = 0.00;
		}

		var sub_total = (parseFloat(ledger_price) + parseFloat(sms_price)).toFixed(2);

		$(".ledger_price").text(ledger_price);
		$(".sms_price").text(sms_price);

		$(".sub_total").text(sub_total);

		$('.tax_amount').html(sub_total+"&nbsp;&nbsp;&nbsp;&nbsp;"+ parseFloat(sub_total * ({{ $tax['amount'] }} / 100)).toFixed(2) );

		var total = parseFloat(sub_total) + parseFloat(sub_total * ({{ $tax['tax'] }} / 100));

		$(".total").html('<h3><i class="fa fa-inr"></i> '+ parseFloat(total).toFixed(2) );
		
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
				addons: {
					required: true
				},
				sms_id: {
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
				addons: {
					required: "Addon type is required"
				},
				sms_id: {
					required: "SMS pack is required"
				},
				ledger_id: {
					required: "Ledger pack is required"
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