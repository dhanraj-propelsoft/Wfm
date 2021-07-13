<!-- Modal Starts -->

@include('modals.user_search_modal')
@include('modals.add_user_modal')
@section('dom_links')

@parent

<script type="text/javascript">

var current_selection = null;

	function add_new_link() {

		setTimeout(function() {

			current_selection.select2("close");

			var first_name = $('.search_user_modal input[name=first_name]').val();
			var crm_code = $('.search_user_modal input[name=crm_code]').val();
		  	var mobile_no = $('.search_user_modal input[name=mobile_no]').val();
		  	var email = $('.search_user_modal input[name=email_address]').val();
		  	var pan_no = $('.search_user_modal input[name=pan_no]').val();
		  	var aadhar_no = $('.search_user_modal input[name=aadhar_no]').val();
		  	var license_no = $('.search_user_modal input[name=license_no]').val();
		  	var passport_no = $('.search_user_modal input[name=passport_no]').val();

		  	//current_selection.closest('.search_container').find('.search_popover, .searchuser_container').show();

		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container').show();

		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=name]').val(first_name);
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=mobile_no]').val(mobile_no);
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=email_address]').val(email);
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=pan]').val(pan_no);
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=aadhar_no]').val(aadhar_no);
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=license_no]').val(license_no);
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=passport_no]').val(passport_no);

		  	//current_selection.closest('.search_container').find('.user_add_container, .searchuser_result_container').hide();

		  	current_selection.val("");

		  	current_selection.trigger("change");

		}, 0);
	}


	function detailed_search_link() {

		setTimeout(function() {

			current_selection.select2("close");

		  	//current_selection.closest('.search_container').find('.search_popover, .searchuser_container').show();
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container').show();


		  	//current_selection.closest('.search_container').find('.user_add_container, .searchuser_result_container').hide();

		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=mobile_no]').val('');
		  
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=name]').val('');
		  
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=email]').val('');
		  
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=pan]').val('');
		  
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=aadhar_no]').val('');
		  
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=passport_no]').val('');
		  	
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=license_no]').val('');
		  	
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=address]').val('');
		  	
		  	var user_state = current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_state]').val();
		  
		  	if(user_state)
		  	{
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_state]').val('');
		  	}
		  	var user_city = current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_city]').val();
		  
		  	if(user_city)
		  	{
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_city]').val('');
		  	}
		  	var state = current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=state]').val();
		  
		  	if(state)
		  	{
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=state]').val('');
		  	}
		  	var city = current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=city]').val();
		 
		  	if(city)
		  	{
		  		current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=city]').val('');
		  	}
		  	if(current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#state').attr('data-id'))
			{
				current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#state').removeAttr('data-id');

			}
			if(current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#city').attr('data-id'))
			{
				current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#city').removeAttr('data-id');

			}
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('.cont').hide();
		  	current_selection.closest('.search_container').find('.search_popover, .user_add_container').find('.con').show();

		  	current_selection.val("");

		  	current_selection.trigger("change");

		  	//$("#user_detailed_search").click();
		  	

		}, 0);

	}


	$(document).ready(function() {

		$('body').on('input', 'input[name=username]', function() {

			var value = $(this).val();

			if(!isNaN(value)) {

				$('body').find('input[name=mobile]').val($(this).val());
				$('body').find('input[name=mobile_no]').val($(this).val());
			}

		});


		$('input[name=username]').closest('.search_container').find('#user').prop('checked', true);


		$('body').on('input', 'business .select2-search__field', function() {

			if(!current_selection.hasClass('individual')) { 
				$('body').find('input[name=username]').val($(this).val());
			}

		});
		

		$('body').on('change', '.person_id', function() {
			var id = $(this).val();
			console.log(id);

			set_user_data(id, 0)

		});


		$('body').on('change', '.business_id', function() {		

			var id = $(this).val();
			set_user_data(id, 1)
		});

	});


	function set_user_data(id, account) {

		console.log(id);

			if($(".first_name").length){

				$(".first_name").val("");
			}
			if($(".last_name").length){

				$(".last_name").val("");
			}
			if($(".display_name").length){

				$(".display_name").val("");
			}
			if($(".email").length){

				$(".email").val("");
			}
			if($(".mobile").length){

				$(".mobile").val("");
			}
			if($(".gender").length){

				$(".gender").prop('checked', false);

				$('.gender').trigger('change');
			}

			if($(".title").length){			

				$(".title").val("");

				$('.title').trigger('change');
			}
			if($(".blood_group").length){

				$(".blood_group").val("");

				$('.blood_group').trigger('change');
			}
			if($(".marital_status").length){

				$(".marital_status").val("");

				$('.marital_status').trigger('change');
			}

			if(id) {

				//console.log(id);

				$.ajax({

				 url: "{{ route('get_people_detail') }}",

				 type: 'post',

				 data: {

					_token: '{{ csrf_token() }}',
					id: id,
					account: account

					},

				success:function(data, textStatus, jqXHR) {
					
				

						if($(".first_name").length){

							$(".first_name").val(data.data.first_name);
						}
						if($(".last_name").length){

							$(".last_name").val(data.data.last_name);

						}
						if($(".display_name").length){

							$(".display_name").val(data.data.display_name);
						}
						if($(".email").length){

							$(".email").val(data.data.email_address);

						}
						if($(".mobile").length){

							$(".mobile").val(data.data.mobile_no);

						}
						if($(".address").length) {

							var address = "";

							if(data.data.billing_address != "") {

								address += data.data.billing_address+"\n";
							}
							if(data.data.billing_city != "") {

								address += data.data.billing_city+"\n";

							}
							if(data.data.billing_state != "") {

								address += data.data.billing_state;
							}

							if(data.data.billing_pin != "" && data.data.billing_state != "") {

								address += " - "+data.data.billing_pin;

							}
							$(".address").val(address);

						}



						if($(".shipping_address").length) {

							var address = "";
							if(data.data.shipping_address != "") {
								address += data.data.billing_address+"\n";

							}
							if(data.data.shipping_city != "") {

								address += data.data.shipping_city+"\n";
							}

							if(data.data.billing_state != "") {

								address += data.data.shipping_state;

							}

							if(data.data.shipping_pin != "" && data.data.shipping_state != "") {

								address += " - "+data.data.shipping_pin;

							}

							$(".shipping_address").val(address);
						}



						if($(".gender#"+data.data.gender_id).length){

							$(".gender").prop('checked', false);

							$(".gender#"+data.data.gender_id).prop('checked', true);

							$('.gender').trigger('change');

						}

						if($(".title").length){

							$(".title").val(data.data.title_id);

							$('.title').trigger('change');

						}
						if($(".blood_group").length){

							$(".blood_group").val(data.data.blood_group_id);

							$('.blood_group').trigger('change');
						}
						if($(".marital_status").length){

							$(".marital_status").val(data.data.marital_id);

							$('.marital_status').trigger('change');
						}

					},

				error:function(jqXHR, textStatus, errorThrown) {}

				});

			}

	}





		function select_user(select_item) {

			//console.log(select_item);

			select_item.closest('.search_container').find('.content').html("");

			//console.log(select_item.closest('.search_container').find('.content').length);

			select_item.closest('.search_container').find('.content').append(`<div class="search_popover" style="display: none;">

							<div class="form-group">

								<div class="row searchuser_container">							

										<div style=" display:none; position: absolute; bottom: 0;" class="col-md-12"> <a id="user_detailed_search" href="javascript:;">Detailed Search</a>
										</div>
								</div>

								<div class="row searchuser_result_container" style="display: none">

									<div style="padding-top: 15px;" class="col-md-12">	

										{{ Form::label('', 'Search Result', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}

										<br>

										<label class="result_text" style="font-weight: bold; "></label>

										<button data-id="" data-mobile="" style="float: right; margin: 5px;" class="btn btn-success simple_result_btn">Add</button>

									</div>

									<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" class="add_simple_user">Add New</a>

									</div>
								</div>

								<div class="row user_add_container" style="display: none; width: 400px" >

									{!! Form::open(['class' => 'form-horizontal uservalidateform col-md-12']) !!}



										<div style="padding-top: 15px;" class="col-md-12">	

											{{ Form::label('', 'Add User', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}

										</div>


										<div class="container">	
											<div class="row">
												<div class="col-md-4">
													{{ Form::label('mobile_no', 'Mobile', ['class'=>'control-label required']) }}
												</div>			
												<div class="col-md-8">
													{!! Form::text('mobile_no', null , ['class' => 'form-control', 'placeholder' => 'Mobile']) !!} 				
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													 {{ Form::label('name', 'Name', ['class'=>' control-label required']) }} 
												</div>			
												<div class="col-md-8">
													{!! Form::text('name', null , ['class' => 'form-control', 'placeholder' => 'Name']) !!}
													
												</div>			
											</div>

											

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('email', 'E-Mail ID', ['class'=>'control-label']) }}
												</div>			
												<div class="col-md-8">
													{!! Form::text('email', null , ['class' => 'form-control', 'placeholder' => 'E-Mail ID']) !!}				
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('pan', 'PAN', ['class'=>'control-label']) }}
												</div>			
												<div class="col-md-8">
													{!! Form::text('pan', null , ['class' => 'form-control', 'placeholder' => 'PAN']) !!}				
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('aadhar_no', 'Aadhar No', ['class'=>'control-label']) }}
												</div>			
												<div class="col-md-8">
													{!! Form::text('aadhar_no', null , ['class' => 'form-control', 'placeholder' => 'Aadhar No']) !!}				
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('passport_no', 'Passport No', ['class'=>'control-label']) }}
												</div>			
												<div class="col-md-8">
													{!! Form::text('passport_no', null , ['class' => 'form-control', 'placeholder' => 'Passport No']) !!}				
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('license_no', 'License No', ['class'=>'control-label']) }}
												</div>			
												<div class="col-md-8">
													{!! Form::text('license_no', null , ['class' => 'form-control', 'placeholder' => 'License No']) !!}		
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('address', 'Address', ['class'=>'control-label']) }}
												</div>			
												<div class="col-md-8">
													{!! Form::text('address', null , ['class' => 'form-control', 'placeholder' => 'Address']) !!}				
												</div>			
											</div>
											<div class="con">

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('state', 'State', ['class'=>'control-label required']) }}
												</div>			
												<div class="col-md-8">
													{{ Form::select('user_state', $state, null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'person_state']) }}				
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('city', 'city', ['class'=>'control-label required']) }}
												</div>			
												<div class="col-md-8">
													{{ Form::select('user_city',['' => 'Select city'], null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'person_city']) }} 			
												</div>			
											</div>
											</div>
											<div class="cont" style="display:none;">

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('state', 'State', ['class'=>'control-label required']) }}
												</div>			
												<div class="col-md-8">
													{{ Form::text('state',null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'state' ,'disabled']) }}				
												</div>			
											</div>

											<div class="row">
												<div class="col-md-4">
													{{ Form::label('city', 'city', ['class'=>'control-label required']) }}
												</div>			
												<div class="col-md-8">
													{{ Form::text('city', null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'city' ,'disabled']) }} 			
												</div>			
											</div>
											</div>

										</div>	
										
										<div class="col-md-12">	
											<button style="float: right; margin: 5px;" data-id="" class="btn btn-success add_new_user ">Add</button>
											<button style="float:right;margin:5px;" class="btn btn-primary reset">Reset</button>

										</div>

										{!! Form::close() !!}

										<!--<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" id="user_detailed_add" class="detailed_user">Add Detailed Record</a>

										</div>-->

								</div>

							</div>

						</div>`);

		$('.uservalidateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			/*rules: {
								
				mobile_no: {
					required: true,
					remote: {
						url: '{{ route('check_person_mobile_number') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					}
				},
				
			
			},

			messages: {
				
				mobile_no: {
					required: "Mobile Number is required.",
					remote: "Mobile Number already exists!"
				},
				
			},*/

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
				form.submit(); // form validation success, call ajax form submit
			}
		});

		//to validate mobile number
		$('input[name=mobile_no]').blur(function(){
				//alert();
				var data = $('input[name=mobile_no]').val();
	        	var person_mobile = $('input[name=mobile_no]');
	        	var type_name = $('.search_container').find('input[name=account_person_type_id]:checked').val();

				//alert(data);
				if(data)
				{
					$.ajax({
					url: '{{ route('validate_business_mobile_number') }}',
					type: "post",
					data:
					{
						 _token :$('input[name=_token]').val(),
						 number: data,
						 type_name : type_name

					},
					success:function(data)
					{
						
						if(data.check == true)
						{
							if(data.check_mobile.person_id)
							{
								var name = "People";
								
							}
							if(data.check_mobile.business_id)
							{
								var name = "Business";
								
							}
							$('.add_new_user').hide();
							person_mobile.closest('div').append('<span class="error" style="color:red">This mobile number already exists in your organization as '+name+' !<br><button class="btn btn-primary erase_mobile_number">Ok</button></span>');
							$('.erase_mobile_number').on('click',function(e){
								e.preventDefault();
								$('input[name=mobile_no]').val('');
								person_mobile.closest('div').find('.error').hide();
							if($('input[name=name]').val())
							{
								$('input[name=name]').val('');

							}
							
							if($('input[name=email]').val())
							{
								$('input[name=email]').val('');
							}
							
							if($('input[name=pan]').val())
							{
								$('input[name=pan]').val('');
							}
							if($('input[name=aadhar_no]').val())
							{
							  $('input[name=aadhar_no]').val('');
							}
							
							if($('input[name=passport_no]').val())
							{
								$('input[name=passport_no]').val('');
							}
							if($('input[name=license_no]').val())
							{
								$('input[name=license_no]').val('');
							}
							
							if($('input[name=address]').val())
							{
								$('input[name=address]').val('');
							}
							if($('input[name=state]').val())
							{
								//console.log("state");
								$('.cont').find('#state').val('');
							}
							if($('.cont').find('#state').attr('data-id'))
							{
								$('.cont').find('#state').removeAttr('data-id');

							}
							if($('#person_state').val())
							{
								$('#person_state').val('');
							}
							if($('input[name=city]').val())
							{
								//console.log("city");
								$('.cont').find('#city').val('');

							}
							if($('.cont').find('#city').attr('data-id'))
							{
								$('.cont').find('#city').removeAttr('data-id');

							}
							if($('#personcity').val())
							{
								$('#person_city').val('');

							}
							$('.con').show();
							$('.cont').hide();
							$('.add_new_user').show();


							});
							
							
						}
						if(data.check == false && data.data != null)
						{

							if(data.data.person_id)
							{
								var name = "Person";
								
							}
							if(data.data.business_id)
							{
								var name ="Business";
								
							}
							$('.add_new_user').hide();
							
							person_mobile.closest('div').append('<span class="error" style="color:red">This exists as '+name+'in system or other module. Want to copy here?<br><button class="btn btn-success copy_business">Yes</button><button class="btn btn-primary remove_number">No</button></span>');
							$('.remove_number').on('click',function(e){
								e.preventDefault();
								$('input[name=mobile_no]').val('');
								if($('input[name=name]').val())
							{
								$('input[name=name]').val('');

							}
							
							if($('input[name=email]').val())
							{
								$('input[name=email]').val('');
							}
							
							if($('input[name=pan]').val())
							{
								$('input[name=pan]').val('');
							}
							if($('input[name=aadhar_no]').val())
							{
							  $('input[name=aadhar_no]').val('');
							}
							
							if($('input[name=passport_no]').val())
							{
								$('input[name=passport_no]').val('');
							}
							if($('input[name=license_no]').val())
							{
								$('input[name=license_no]').val('');
							}
							
							if($('input[name=address]').val())
							{
								$('input[name=address]').val('');
							}
							if($('input[name=state]').val())
							{
								//console.log("state");
								$('.cont').find('#state').val('');
							}
							if($('.cont').find('#state').attr('data-id'))
							{
								$('.cont').find('#state').removeAttr('data-id');

							}
							if($('#person_state').val())
							{
								$('#person_state').val('');
							}
							if($('input[name=city]').val())
							{
								//console.log("city");
								$('.cont').find('#city').val('');

							}
							if($('.cont').find('#city').attr('data-id'))
							{
								$('.cont').find('#city').removeAttr('data-id');

							}
							if($('#person_city').val())
							{
								$('#person_city').val('');

							}
							$('.con').show();
							$('.cont').hide();
							$('.add_new_user').show();
							person_mobile.closest('div').find('.error').hide();

							});

							$('.copy_business').on('click',function(e){
								//alert();
								e.preventDefault();
								$('.cont').show();

								$('input[name=name]').val(data.data.display_name);
								$('input[name=email]').val(data.data.email_address);
								$('input[name=pan]').val(data.data.pan_no);
								if(data.data.gst_no)
								{
								  $('input[name=gst]').val(data.data.gst_no);
								}
								$('input[name=aadhar_no]').val(data.data.aadhar_no);
								$('input[name=passport_no]').val(data.data.passport_no);
								$('input[name=license_no]').val(data.data.license_no);
								$('input[name=address]').val(data.data.address);
								/*$('#person_state').val(data.state_id);
								$('#person_city').val(data.city_id);*/
								
								$('.cont').find('#state').val(data.state_name);
								$('.cont').find('#state').attr('data-id',data.state_id);
								$('#person_state').val(data.state_id);
								$('.cont').find('#city').val(data.city_name);
								$('.cont').find('#city').attr('data-id',data.city_id);
								$('#person_city').val(data.city_id);
								$('.con').hide();


								
								var bb=$('.add_new_user').attr('data-id',data.data.person_id);
								person_mobile.closest('div').find('.error').hide();
								//console.log(person_mobile.closest('div').find('.error').length);
								$('.add_new_user').show();


							});
											
							
						}
						if(data.check == false && data.data == null)
						{
							if($('input[name=name]').val())
							{
								$('input[name=name]').val('');

							}
							
							if($('input[name=email]').val())
							{
								$('input[name=email]').val('');
							}
							
							if($('input[name=pan]').val())
							{
								$('input[name=pan]').val('');
							}
							if($('input[name=aadhar_no]').val())
							{
							  $('input[name=aadhar_no]').val('');
							}
							
							if($('input[name=passport_no]').val())
							{
								$('input[name=passport_no]').val('');
							}
							if($('input[name=license_no]').val())
							{
								$('input[name=license_no]').val('');
							}
							
							if($('input[name=address]').val())
							{
								$('input[name=address]').val('');
							}
							if($('input[name=state]').val())
							{
								//console.log("state");
								$('.cont').find('#state').val('');
							}
							if($('.cont').find('#state').attr('data-id'))
							{
								$('.cont').find('#state').removeAttr('data-id');

							}
							if($('#person_state').val())
							{
								$('#person_state').val('');
							}
							if($('input[name=city]').val())
							{
								//console.log("city");
								$('.cont').find('#city').val('');

							}
							if($('.cont').find('#city').attr('data-id'))
							{
								$('.cont').find('#city').removeAttr('data-id');

							}
							if($('#person_city').val())
							{
								$('#person_city').val('');

							}
							$('.con').show();
							$('.cont').hide();
						}

						
					},
					error:function()
					{

					}


					});
				}
				

		});	



		//to validate person email

		$('input[name=email]').keyup(function()
		{
				//alert();
				var email = $('input[name=email]').val();
	        	var business_email = $('input[name=email]');

	        	if(email)
	        	{
	        			$.ajax({
					url: '{{ route('check_person_email_address') }}',
					type: "post",
					data:
					{
						 _token :$('input[name=_token]').val(),
						 email_address: email

					},
					success:function(data)
					{				
						//console.log(business_email.closest('div').find('span').length);
						if(data == "false")
						{
							if(business_email.closest('div').find('span').length==0)
							{
							business_email.closest('div').append('<span class="error" style="color:red">Email already exists!</span>');
							}
						
						}
						else
						{
							if(business_email.closest('div').find('span').length>0)
							{
							business_email.closest('div').find('span').remove();
							}
						}
					},
					error:function()
					{

					}


				});
	        	}

				
			

		});

		$('.reset').on('click',function(e){
			e.preventDefault();
		//alert();
		$('input[name=mobile_no]').val('');
		$('input[name=name]').val('');
		$('input[name=email]').val('');
		$('input[name=pan]').val('');
		$('input[name=aadhar_no]').val('');
		$('input[name=passport_no]').val('');
		$('input[name=license_no]').val('');
		$('input[name=address]').val('');
		$('#person_state').val('');
		$('#person_city').val('');


		


	});

		

		select_item.select2({

			dropdownParent: select_item.parent(),

	  		ajax: {

	    	url: "{{ route('search_people') }}",

	    	type: 'post',

	    	dataType: 'json',

		    	data: function (params) {
		    	
		      	return {

					_token: '{{ csrf_token() }}',	

					user_type: select_item.closest('.search_container').find("input[name=user_type]:checked").val(),

					person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val(),

			        search: params.term, // search term

			        page: params.page

		      	};

		    },

		    processResults: function (data, params) {

		      params.page = params.page || 1;

		      return {

		        results: data,

		        pagination: {

		          more: (params.page * 30) < data.total_count

		        }

		      };

		    },

		    	cache: true

		  	},

  		placeholder: 'Search',

			"language": {

			       "noResults": function(){

			       	current_selection = select_item;

			           return "No Results Found <i onclick='return add_new_link()' class='add_new_link'>+ Add New</i>";
			       }

			},

			escapeMarkup: function (markup) { return markup; }, // let our 	custom formatter work

			  	minimumInputLength: 1,

			  	templateResult: formatPeople,

				}).on("select2:select", function(e) { 

						if(e.params.data.id == "0" && e.params.data.name == "") {

							current_selection = select_item;

							add_new_link();

						} else if(e.params.data.id == "-1" && e.params.data.name == "") {

							current_selection = select_item;

							detailed_search_link();

						}

		});



		select_item.closest('.search_container').find('input[name=search_by]').on('change', function() {

			var obj = $(this);

			obj.closest('.search_container').find('input[name=username], input[name=crm_id]').val("");

			if(obj.val() == 0) {

				obj.closest('.search_container').find('input[name=username]').show()

				obj.closest('.search_container').find('input[name=crm_id]').hide();

			} else if(obj.val() == 1) {

				obj.closest('.search_container').find('input[name=username]').hide()

				obj.closest('.search_container').find('input[name=crm_id]').show();

			}

		});



		select_item.closest('.search_container').find('.add_simple_user').on('click', function(e) {

			select_item.closest('.search_container').find('.searchuser_result_container').hide();

			select_item.closest('.search_container').find('.user_add_container').show();

		});


		select_item.closest('.search_container').find('.add_simple_user').on('click', function(e) {

			select_item.closest('.search_container').find('.searchuser_result_container').hide();

			select_item.closest('.search_container').find('.user_add_container').show();

		});


		select_item.closest('.search_container').find('.simple_usersearch_btn').on('click', function(e) {

			e.preventDefault();

			var username = select_item.closest('.search_container').find('input[name=username]').val();

			var propel_id = select_item.closest('.search_container').find('input[name=crm_id]').val();



			if(username == "" && propel_id == "" ) { } else {

				$.ajax({

				url: '{{ route('simple_user_search') }}',

				type: 'post',

				data: {

					_token: '{{ csrf_token() }}',

					username: username,

					crm_id: propel_id

				},

				dataType: "json",

				success:function(data, textStatus, jqXHR) {



					select_item.closest('.search_container').find('.searchuser_container').hide();

					select_item.closest('.search_container').find('.searchuser_container input[name=username], .searchuser_container input[name=crm_id]').val("");

					select_item.closest('.search_container').find('.searchuser_result_container').show();

					if(data.first_name) {

						select_item.closest('.search_container').find('.searchuser_result_container').find('label.result_text').text(data.first_name + " " +data.last_name);

						select_item.closest('.search_container').find('.searchuser_result_container').find('.simple_result_btn').show();

						select_item.closest('.search_container').find('.searchuser_result_container').find('.simple_result_btn').data('id', data.id);

						select_item.closest('.search_container').find('.searchuser_result_container').find('.simple_result_btn').data('mobile', data.mobile);

					} else {

						select_item.closest('.search_container').find('.searchuser_result_container').find('label.result_text').text("No result found!");

						select_item.closest('.search_container').find('.searchuser_result_container').find('.simple_result_btn').hide();

					}

					},

				 error:function(jqXHR, textStatus, errorThrown) {

					}

				});

			}

		});



		//HERE IT SETS THE SELECTED USER

		select_item.closest('.search_container').find('.simple_result_btn').on('click', function(e) {

			alert('tedsfa');

			return;

			e.preventDefault();

			var value = $(this).data('id');

			var mobile = $(this).data('mobile');

			var text = select_item.closest('.search_container').find('.result_text').text();



			$.ajax({

			 url: "{{ route('simple_people_add') }}",

			 type: 'post',

			 data: {

				_token: '{{ csrf_token() }}',

				id: value,

				mobile_no: mobile,

				person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val()

				},

			success:function(data, textStatus, jqXHR) {

				select_item.append('<option value="'+data.data.id+'">'+text+'</option>');

				select_item.val(data.data.id);

				select_item.trigger("change");

				select_item.closest('.search_container').find('.search_popover').hide();

				select_item.closest('.search_container').find('.searchuser_result_container').hide();

				},

			error:function(jqXHR, textStatus, errorThrown) {

			}

			});

		});



		$('body').on('click', '.select_user', function() {

			var id = $(this).data('id');
			var name = $(this).data('name');
			var mobile = $(this).data('mobile');

			$.ajax({

				 url: "{{ route('simple_people_add') }}",

				 type: 'post',

				 data: {

					_token: '{{ csrf_token() }}',
					id: id,
					mobile_no: mobile
					},

				success:function(data, textStatus, jqXHR) {

					current_select_item.append('<option value="'+data.data.id+'">'+name+'</option>');

					current_select_item.val(data.data.id);

					current_select_item.trigger("change");

					current_select_item.closest('.search_container').find('.search_popover').hide();

					current_select_item.closest('.search_container').find('.searchuser_result_container').hide();

					$('.search_user_modal').modal('hide');
				},

				error:function(jqXHR, textStatus, errorThrown) {

				}

				});

		});




		select_item.closest('.search_container').find('select[name=user_state]').on('change', function () {

			var obj = $(this);

			var city = select_item.closest('.search_container').find( "select[name=user_city]" );

			var select_val = $(this).val();

			city.empty();

			city.append("<option value=''>Select City</option>");

				if(select_val != "") {

			$('.loader_wall_onspot').show();

				$.ajax({

					 url: '{{ route('get_city') }}',

					 type: 'post',

					 data: {

						_token : '{{ csrf_token() }}',

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


		 //HERE IT SETS THE SELECTED USER

		 select_item.closest('.search_container').find('.add_new_user').on('click', function(e) {

			e.preventDefault();
			var id= $(this).data('id');


			var name = select_item.closest('.search_container').find('input[name=name]');
			var email_address = select_item.closest('.search_container').find('input[name=email]');
			var mobile_no = select_item.closest('.search_container').find('input[name=mobile_no]');
			var pan = select_item.closest('.search_container').find('input[name=pan]');
			var aadhar_no = select_item.closest('.search_container').find('input[name=aadhar_no]');
			var passport_no = select_item.closest('.search_container').find('input[name=passport_no]');
			var license_no = select_item.closest('.search_container').find('input[name=license_no]');
			var address = select_item.closest('.search_container').find('input[name=address]');
			var state = select_item.closest('.search_container').find('select[name=user_state]'); 
			var city =  select_item.closest('.search_container').find('select[name=user_city]');

			var city_id= ($('input[name=city]').attr('data-id')) ? $('input[name=city]').attr('data-id'): city.val();      		

			name.closest('div').find('span.error').remove();

			mobile_no.closest('div').find('span.error').remove();

			state.closest('div').find('span.error').remove();

			city.closest('div').find('span.error').remove();

			$.ajax({

				url: "{{ route('ledger_limitation') }}",
				type: 'get',
				data:{
					/*_token : '{{ csrf_token() }}', */
				},
				success: function(data, textStatus, jqXHR)
				{
					var ledger_limit = data.ledger_limitation;

					if(ledger_limit == true){

						if(name.val() == "") {

							name.closest('div').append('<span class="error" style="color:red">Enter a valid name</span>');

						} else if(mobile_no.val() == "") {

							mobile_no.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');

						} else if(isNaN(mobile_no.val())) {

							mobile_no.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');

						} else if((mobile_no.val()).length != 10) {

							mobile_no.closest('div').append('<span class="error" style="color:red">Mobile number should be 10 numbers</span>');

						} else if(state.val() == "") {

							state.closest('div').append('<span class="error" style="color:red">Choose a state</span>');

						} else if(city.val() == "") {

							city.closest('div').append('<span class="error" style="color:red">Choose a city</span>');

						} else {

							$('.loader_wall_onspot').show();

							$.ajax({

								 url: '{{ route('simple_user_add') }}',

								 type: 'post',

								 data: {

									_token : '{{ csrf_token() }}',
									id : id,
									first_name: name.val(),
									mobile_no: mobile_no.val(),
									email_address: email_address.val(),
									pan: pan.val(),
									aadhar_no: aadhar_no.val(),
									passport_no: passport_no.val(),
									license_no: license_no.val(),
									address: address.val(),
									city_id: city_id,

									person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val()

									},

								dataType: "json",

									success:function(data, textStatus, jqXHR) {

										select_item.append('<option value="'+data.data.id+'">'+data.data.first_name+" "+data.data.last_name+'</option>');

										select_item.val(data.data.id);

										select_item.trigger("change");

										select_item.closest('.search_container').find('.search_popover').hide();

										select_item.closest('.search_container').find('.user_add_container').hide();

										$('.loader_wall_onspot').hide();

										select_item.closest('.search_container').find('form')[0].reset();

									},

								 error:function(jqXHR, textStatus, errorThrown) {

									}

								});

						}
					}
					else{

						$('#error_dialog #title').text('Limit Exceeded!');
							$('#error_dialog #message').html('{{ config('constants.error.ledger_limit') }}' + "<br>Please go to <b>Settings -> Subscription -> My Plan </b> and buy more... Or contact us.");
							$('#error_dialog').modal('show');

							return false;

					}
				}

			});

		});



	}





</script>

@stop

<!-- Modal Ends -->