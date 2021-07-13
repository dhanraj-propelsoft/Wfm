<!-- Modal Starts -->

@include('modals.business_search_modal')
@include('modals.add_business_modal')
@include('modals.add_new_customer')


@section('dom_links')

@parent

<script type="text/javascript">

	var current_business_selection = null;

	function add_new_business_link() {

		setTimeout(function() {

			

			var business_name = $('.search_business_modal input[name=business_name]').val();
			var bcrm_code = $('.search_business_modal input[name=bcrm_code]').val();
			var mobile = $('.search_business_modal input[name=mobile_no]').val();
		  	var email = $('.search_business_modal input[name=email_address]').val();
		  	var pan_no = $('.search_business_modal input[name=pan_no]').val();
		  	var gst = $('.search_business_modal input[name=gst]').val();
		  	var phone_no = $('.search_business_modal input[name=phone_no]').val();
		  	var web_address = $('.search_business_modal input[name=web_address]').val();

			//console.log(business_name);

			current_business_selection.select2("close");
			//$('.search_business_modal').modal('show');
			//$('.add_business_container_modal').modal('show');
		  	//current_business_selection.closest('.search_container').find('.search_popover, .searchuser_container').show();

		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container ').show();

		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=name]').val(business_name);
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=mobile]').val(mobile);
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=email_address]').val(email);
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=pan]').val(pan_no);
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=gst]').val(gst);
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container input[name=phone]').val(phone_no);


		  	//current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').append($('input[name=name]').val(business_name));

		  	//current_business_selection.closest('.search_container').find('.user_add_container, .searchuser_result_container').hide();

		  	current_business_selection.val("");

		  	current_business_selection.trigger("change");

		}, 0);

	}

	function detailed_search_business_link() {

		setTimeout(function() {
			current_business_selection.select2("close");

			//$('.search_business_modal').modal('show');

		  	//current_business_selection.closest('.search_container').find('.search_popover, .searchuser_container').show();
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').show();

		  	//current_business_selection.closest('.search_container').find('.user_add_container, .searchuser_result_container').hide();
		  	
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=mobile]').val('');
		  	
		  	
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=name]').val('');
		  	
		  	
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=owner_name]').val('');
		  	
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=email_address]').val('');
		  	
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=pan]').val('');
		  
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=gst]').val('');
		  	
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=phone]').val('');
		  
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=address]').val('');
		  	
		  	var user_state = current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_state]').val();
		 
		  	if(user_state)
		  	{
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_state]').val('');
		  	}
		  	var user_city = current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_state]').val();
		  
		  	if(user_city)
		  	{
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('select[name=user_city]').val('');
		  	}
		  	var state = current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=state]').val();
		  
		  	if(state)
		  	{
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=state]').val('');
		  	}
		  	var city = current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=city]').val();
		  
		  	if(city)
		  	{
		  		current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('input[name=city]').val('');
		  	}
		  	if(current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#state').attr('data-id'))
			{
				current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#state').removeAttr('data-id');

			}
			if(current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#city').attr('data-id'))
			{
				current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('#city').removeAttr('data-id');

			}
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('.cont').hide();
		  	current_business_selection.closest('.search_container').find('.search_popover, .user_add_container').find('.con').show();

		  	current_business_selection.val("");

		  	current_business_selection.trigger("change");

		  	//$("#business_detailed_search").click();
		  	

		}, 0);

	}
	


	function select_business(select_item) {

		select_item.closest('.search_container').find('.content').html("");

		select_item.closest('.search_container').find('.content').append(`<div class="search_popover" style="display: none;">

					<div class="form-group">

						<div class="row searchuser_container">
							
							<div style=" display:none; position: absolute; bottom: 0;" class="col-md-12"> <a id="business_detailed_search" href="javascript:;">Detailed Search</a>
							</div>
						</div>

						<div class="row searchuser_result_container" style="display: none">
							<div style="padding-top: 15px;" class="col-md-12">	
								{{ Form::label('', 'Search Result', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}
								<br>
								<label class="result_text" style="font-weight: bold; "></label>
								<button data-id="" style="float: right; margin: 5px;" class="btn btn-success simple_result_btn">Add</button>
							</div>
							<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" class="add_simple_user">Add New</a>
							</div>
						</div>

						<div class="row user_add_container" style="display: none; width: 400px" >

							{!! Form::open(['class' => 'form-horizontal uservalidateform col-md-12']) !!}

							<div style="padding-top: 15px;" class="col-md-12">	
								{{ Form::label('', 'Add Business', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}							
							</div>

							

							<div class="container">
								<div class="row">
									<div class="col-md-4">
										{{ Form::label('mobile', 'Mobile', ['class'=>'control-label required']) }}
									</div>			
									<div class="col-md-8">
										{!! Form::text('mobile', null , ['class' => 'form-control', 'placeholder' => 'Mobile']) !!} 				
									</div>			
								</div>

								<div class="row">
									<div class="col-md-4">
										 {{ Form::label('name', 'Business Name', ['class'=>' control-label required']) }} 
									</div>			
									<div class="col-md-8">
										{!! Form::text('name', null , ['class' => 'form-control', 'placeholder' => 'Business Name']) !!}
										
									</div>			
								</div>
								<div class="row">
									<div class="col-md-4">
										 {{ Form::label('owner_name', 'Contact', ['class'=>' control-label required']) }} 
									</div>			
									<div class="col-md-8">
										{!! Form::text('owner_name', null , ['class' => 'form-control', 'placeholder' => 'Name , Designation , Phone']) !!}
										
									</div>			
								</div>								

								

								<div class="row">
									<div class="col-md-4">
										{{ Form::label('email', 'E-Mail ID', ['class'=>'control-label']) }}
									</div>			
									<div class="col-md-8">
										{!! Form::text('email_address', null , ['class' => 'form-control', 'placeholder' => 'E-Mail ID','id' => 'email']) !!}				
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
										{{ Form::label('gst', 'GST NO', ['class'=>'control-label ']) }} 
									</div>			
									<div class="col-md-8">
										{!! Form::text('gst', null, ['class' => 'form-control', 'placeholder' => 'GST NO']) !!} 
										
									</div>			
								</div>

								<div class="row">
									<div class="col-md-4">
										{{ Form::label('phone', 'Phone No', ['class'=>'control-label']) }}
									</div>			
									<div class="col-md-8">
										{!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone No']) !!}				
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
										{{ Form::select('user_state', $state, null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'user_state']) }}				
									</div>			
								</div>

								<div class="row">
									<div class="col-md-4">
										{{ Form::label('city', 'city', ['class'=>'control-label required']) }}
									</div>			
									<div class="col-md-8">
										{{ Form::select('user_city', ['' => 'Select City'], null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'user_city']) }} 			
									</div>			
								</div>
								</div>
								<div class="cont" style="display:none;">
									<div class="row">
										<div class="col-md-4">
											{{ Form::label('state', 'State', ['class'=>'control-label required']) }}
										</div>			
										<div class="col-md-8">
											{{ Form::text('state',null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'state','disabled']) }}				
										</div>			
									</div>

									<div class="row">
										<div class="col-md-4">
											{{ Form::label('city', 'city', ['class'=>'control-label required']) }}
										</div>			
										<div class="col-md-8">
											{{ Form::text('city', null, ['class'=>'form-control select_item', 'style' => 'width: 100%' ,'id' => 'city','disabled']) }} 			
										</div>			
									</div>
								</div>


							</div>

							<div class="col-md-12">

								<button style="float: right; margin: 5px;" data-id="" class="btn btn-success add_new_business ">Add</button>
								<button style="float:right;margin:5px;" class="btn btn-primary reset">Reset</button>
							</div>

							{!! Form::close() !!}

							<!--<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" id="business_detailed_add" class="detailed_business">Add Detailed Record</a>
							</div>-->
						</div>

					</div>

			</div>`);


 	$(document).ready(function(){

		/*$('input[name=mobile]').keyup(function(){
			var data = $('input[name=mobile]').val();
        	var business_mobile = $('input[name=mobile]');

			//alert(data);
			$.ajax({
				url: '{{ route('check_business_mobile_number') }}',
				type: "post",
				data:
				{
					 _token :$('input[name=_token]').val(),
					 number: data

				},
				success:function(data)
				{
					//alert();
					console.log(data.status);
					console.log(business_mobile.closest('div').find('span').length);

					if(data == "false")
					{

						if(business_mobile.closest('div').find('span').length==0)
						{
						business_mobile.closest('div').append('<span class="error" style="color:red">Mobile number already exists!</span>');
						}
					}
					else
					{
						if(business_mobile.closest('div').find('span').length>0)
						{
						business_mobile.closest('div').find('span').remove();
						}
					}

					
				},
				error:function()
				{

				}


			});

		});


		$('input[name=email_address]').keyup(function()
		{
			//alert();
			var email = $('#email').val();
        	var business_email = $('input[name=email_address]');

			
			$.ajax({
				url: '{{ route('check_business_email_address') }}',
				type: "post",
				data:
				{
					 _token :$('input[name=_token]').val(),
					 email_address: email

				},
				success:function(data)
				{
					//alert();

					console.log(data);
					
					console.log(business_email.closest('div').find('span').length);
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

		});
		$('input[name=gst]').keyup(function(){

			
			var data = $('input[name=gst]').val();
			
        	var gst = $('input[name=gst]');

			$.ajax({
				url: '{{ route('check_business_gst_number') }}',
				type: "post",
				data:
				{
					 _token :$('input[name=_token]').val(),
					 number: data

				},
				success:function(data)
				{
					
					

					if(data == "false")
					{
						if(gst.closest('div').find('span').length==0)
						{
						gst.closest('div').append('<span class="error" style="color:red">GST number already exists!</span>');
						}
					}
					else
					{
						if(gst.closest('div').find('span').length>0)
						{
						gst.closest('div').find('span').remove();
						}
					}

					
				},
				error:function()
				{

				}


			});


		});*/


	});


	$('input[name=mobile]').blur(function(){

			var people_data = $('input[name=mobile]').val();			

        	var business_mobile = $('input[name=mobile]');

        	var type_name = $('.search_container').find('input[name=account_person_type_id]:checked').val();

        	//var city = $('.search_container').find( "select[name=user_city]");
        	
			if(people_data)
			{
				$.ajax({
				url: '{{ route('validate_business_mobile_number') }}',
				type: "post",
				data:
				{
					_token :$('input[name=_token]').val(),
					number: people_data,
					type_name : type_name

				},
				success:function(data, textStatus, jqXHR) 
				{
					
					var ll= $('#search_popover').removeAttr('style');
									
					
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
						$('.add_new_business').hide();						

						
						business_mobile.closest('div').append('<span class="error" style="color:red">This mobile number already exists in your organization as ' +name+'!<br><button class="btn btn-primary erase_mobile_number">Ok</button></span>');

							$('.erase_mobile_number').on('click',function(e){
								e.preventDefault();							

								$('input[name=mobile]').val('');
								//business_mobile.closest('div').find('.error').hide();
								business_mobile.closest('div').find('.error').hide();


								//console.log(business_mobile.closest('div').find('.error').length);
								//console.log(business_mobile.closest('div').find('.error'));
							if($('input[name=name]').val())
							{
								$('input[name=name]').val('');

							}
							if($('input[name=owner_name]').val())
							{
								$('input[name=owner_name]').val('');
							}
							
							if($('input[name=email_address]').val())
							{
								$('input[name=email_address]').val('');
							}
							
							if($('input[name=pan]').val())
							{
								$('input[name=pan]').val('');
							}
							if($('input[name=gst]').val())
							{
							  $('input[name=gst]').val('');
							}
							
							if($('input[name=phone]').val())
							{
								$('input[name=phone]').val('');
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
							if($('#user_state').val())
							{
								$('#user_state').val('');
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
							if($('#user_city').val())
							{
								$('#user_city').val('');

							}
							$('.con').show();
							$('.cont').hide();

								$('.add_new_business').show();
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

						$('.add_new_business').hide();
						
						business_mobile.closest('div').append('<span class="error" style="color:red">This exists as '+name+' in system or other module. Want to copy here?<br><button class="btn btn-success copy_business">Yes</button><button class="btn btn-primary erase_phone_field">No</button></span>');

						$('.erase_phone_field').on('click',function(e){
							e.preventDefault();
							$('input[name=mobile]').val('');
							business_mobile.closest('div').find('.error').hide();
							if($('input[name=name]').val())
							{
								$('input[name=name]').val('');

							}
							if($('input[name=owner_name]').val())
							{
								$('input[name=owner_name]').val('');
							}
							
							if($('input[name=email_address]').val())
							{
								$('input[name=email_address]').val('');
							}
							
							if($('input[name=pan]').val())
							{
								$('input[name=pan]').val('');
							}
							if($('input[name=gst]').val())
							{
							  $('input[name=gst]').val('');
							}
							
							if($('input[name=phone]').val())
							{
								$('input[name=phone]').val('');
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
							if($('#user_state').val())
							{
								$('#user_state').val('');
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
							if($('#user_city').val())
							{
								$('#user_city').val('');

							}
							$('.con').show();
							$('.cont').hide();
							$('.add_new_business').show();

						});

						$('.copy_business').on('click',function(e){								
						
							e.preventDefault();
							
							$('.cont').show();
							$('input[name=name]').val(data.data.display_name);
							$('input[name=owner_name]').val(data.data.contact);
							$('input[name=email_address]').val(data.data.email_address);
							$('input[name=pan]').val(data.data.pan_no);
							if(data.data.gst_no)
							{
							  $('input[name=gst]').val(data.data.gst_no);
							}
							$('input[name=phone]').val(data.data.phone);
							$('input[name=address]').val(data.data.address);

							$('.cont').find('#state').val(data.state_name);
							$('.cont').find('#state').attr('data-id',data.state_id);
							$('#user_state').val(data.state_id);
							$('.cont').find('#city').val(data.city_name);
							$('.cont').find('#city').attr('data-id',data.city_id);
							$('#user_city').val(data.city_id);

							//console.log();

							$('.con').hide();

							/*select_item.closest('.search_container').find( "select[name=user_state]" ).val(data.state_id);

							select_item.closest('.search_container').find( "select[name=user_state]" ).trigger("change");*/

							

							
							/*select_item.closest('.search_container').find( "select[name=user_city]").val(data.city_id);*/

							//select_item.closest('.search_container').find( "select[name=user_city]" ).trigger("change");
						
							

							var bb=$('.add_new_business').attr('data-id',data.data.business_id);
							business_mobile.closest('div').find('.error').hide();
							//console.log(business_mobile.closest('div').find('.error').length);
							$('.add_new_business').show();
						});
							
					}

					if(data.check == false && data.data == null)
					{
						if($('input[name=name]').val())
							{
								$('input[name=name]').val('');

							}
							if($('input[name=owner_name]').val())
							{
								$('input[name=owner_name]').val('');
							}
							
							if($('input[name=email_address]').val())
							{
								$('input[name=email_address]').val('');
							}
							
							if($('input[name=pan]').val())
							{
								$('input[name=pan]').val('');
							}
							if($('input[name=gst]').val())
							{
							  $('input[name=gst]').val('');
							}
							
							if($('input[name=phone]').val())
							{
								$('input[name=phone]').val('');
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
							if($('#user_state').val())
							{
								$('#user_state').val('');
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
							if($('#user_city').val())
							{
								$('#user_city').val('');

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

	$('.reset').on('click',function(e){
		//alert();
		e.preventDefault();
		$('input[name=mobile]').val('');
		$('input[name=name]').val('');
		$('input[name=owner_name]').val('');		
		$('input[name=email_address]').val('');
		$('input[name=pan]').val('');
		$('input[name=gst]').val('');
		$('input[name=phone]').val('');
		$('input[name=address]').val('');
		$('#user_state').val('');
		$('#user_city').val('');

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

		       	current_business_selection = select_item;

		           return "No Results Found <i onclick='return add_new_business_link()' class='add_new_business_link'>+ Add New</i>";

		       }

		   	},

	  		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work

	  		minimumInputLength: 1,

	  		templateResult: formatPeople,

			}).on("select2:select", function(e) { 
				//console.log(select_item);


        	if(e.params.data.id == "0" && e.params.data.name == "") { 		

				current_business_selection = select_item;

				add_new_business_link();

        	} else if(e.params.data.id == "-1" && e.params.data.name == "") {
        		
        		current_business_selection = select_item;

				detailed_search_business_link();
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


        select_item.closest('.search_container').find('.simple_business_search_btn').on('click', function(e) {

        	e.preventDefault();

        	var businessname = select_item.closest('.search_container').find('input[name=username]').val();

        	var business_id = select_item.closest('.search_container').find('input[name=crm_id]').val();



        	if(businessname == "" && business_id == "" ) { } else {

	        	$.ajax({

				url: "{{ route('simple_business_search') }}",

				type: 'post',

				data: {

				 	_token: '{{ csrf_token() }}',

				 	businessname: businessname,

				 	bcrm_id: business_id

				},

				dataType: "json",

				success:function(data, textStatus, jqXHR) {


					select_item.closest('.search_container').find('.searchuser_container').hide();

					select_item.closest('.search_container').find('.searchuser_container input[name=username], .searchuser_container input[name=crm_id]').val("");

					select_item.closest('.search_container').find('.searchuser_result_container').show();

					if(data.business_name) {

						select_item.closest('.search_container').find('.searchuser_result_container').find('label.result_text').text(data.business_name + " (" +data.alias + ")");

						select_item.closest('.search_container').find('.searchuser_result_container').find('.simple_result_btn').show();

						select_item.closest('.search_container').find('.searchuser_result_container').find('.simple_result_btn').data('id', data.id);

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


        select_item.closest('.search_container').find('.simple_result_btn').on('click', function(e) {

        	e.preventDefault();

        	var value = $(this).data('id');

        	var text = select_item.closest('.search_container').find('.result_text').text();



        	$.ajax({

			 url: "{{ route('simple_organization_add') }}",

			 type: 'post',

			 data: {

			 	_token: '{{ csrf_token() }}',

			 	id: value,

				person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val()

				},

			success:function(data, textStatus, jqXHR) {

	        	select_item.append('<option value="'+data.data.id+'">'+text+'</option>');

	        	select_item.val(data.data.id);

				select_item.trigger("change");

				select_item.closest('.search_container').find('.search_popover').hide();

				select_item.closest('.search_container').find('.searchuser_result_container').hide();

				},

			error:function(jqXHR, textStatus, errorThrown) {}

			});

        });


        $('body').on('click', '.select_business', function() {

			var id = $(this).data('id');
			var name = $(this).data('name');
			var mobile = $(this).data('mobile');
			$.ajax({

				 url: "{{ route('simple_organization_add') }}",

				 type: 'post',

				 data: {

				 	_token: '{{ csrf_token() }}',

				 	id: id,

				 	mobile_no: mobile,

					person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val()

					},

				success:function(data, textStatus, jqXHR) {

					current_select_item.append('<option value="'+data.data.id+'">'+name+'</option>');

					current_select_item.val(data.data.id);

					current_select_item.trigger("change");

					current_select_item.closest('.search_container').find('.search_popover').hide();

					current_select_item.closest('.search_container').find('.searchuser_result_container').hide();

					$('.search_business_modal').modal('hide');



				},

				error:function(jqXHR, textStatus, errorThrown) {}

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




        function isGst(gst) {
			//var regex = /^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/;

			var regex = /^([0][1-9]|[1-2][0-9]|[3][0-7])([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})?$/;

			  return regex.test(gst);
		}	



        select_item.closest('.search_container').find('.add_new_business').on('click', function(e) {

        	e.preventDefault();
        	var id = $(this).data('id');


        	var business_name = select_item.closest('.search_container').find('input[name=name]');

        	var business_mobile = select_item.closest('.search_container').find('input[name=mobile]');

        	var business_state = select_item.closest('.search_container').find('select[name=user_state]'); 

        	var business_city =  select_item.closest('.search_container').find('select[name=user_city]');

        	var business_phone = select_item.closest('.search_container').find('input[name=phone]');

        	var business_pan = select_item.closest('.search_container').find('input[name=pan]');

        	var business_gst = select_item.closest('.search_container').find('input[name=gst]');

        	var business_address = select_item.closest('.search_container').find('input[name=address]');

        	var business_email = select_item.closest('.search_container').find('input[name=email_address]');

        	var city_id= ($('input[name=city]').attr('data-id')) ? $('input[name=city]').attr('data-id'): business_city.val();   
        	var owner_name =  select_item.closest('.search_container').find('input[name=owner_name]');



        	business_name.closest('div').find('span.error').remove();
        	business_mobile.closest('div').find('span.error').remove();
        	business_state.closest('div').find('span.error').remove();
        	business_city.closest('div').find('span.error').remove();
        	business_phone.closest('div').find('span.error').remove();
        	business_pan.closest('div').find('span.error').remove();
        	business_gst.closest('div').find('span.error').remove();
        	business_address.closest('div').find('span.error').remove();
        	business_email.closest('div').find('span.error').remove();


			$.ajax({

				url: "{{ route('ledger_limitation') }}",
				type: 'get',
				data:{
					/* _token : '{{ csrf_token() }}', */
					
				},
				success: function(data, textStatus, jqXHR)
				{
					var ledger_limit = data.ledger_limitation;

					if(ledger_limit == true){

			        	if(business_name.val() == "") {

			        		business_name.closest('div').append('<span class="error" style="color:red">Enter a valid Name</span>');

			        	}  else if(business_mobile.val() == "") {

			        		business_mobile.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');

			        	} else if(isNaN(business_mobile.val())) {

			        		business_mobile.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');

			        	} else if((business_mobile.val()).length != 10) {

			        		business_mobile.closest('div').append('<span class="error" style="color:red">Mobile number should be 10 numbers</span>');

			        	}/*else if(business_gst.val() == "") {

			        		business_gst.closest('div').append('<span class="error" style="color:red">Enter your GST No.</span>');

			        	} else if(!isGst(business_gst.val())) {

			        		business_gst.closest('div').append('<span class="error" style="color:red">Enter a valid GST No.</span>');

			        	}*/ else if(business_state.val() == "") {

			        		business_state.closest('div').append('<span class="error" style="color:red">Choose a state</span>');

			        	} else if(business_city.val() == "") {

			        		business_city.closest('div').append('<span class="error" style="color:red">Choose a city</span>');

			        	}  else {

			        		$('.loader_wall_onspot').show();

							$.ajax({

								 url: '{{ route('simple_business_add') }}',

								 type: 'post',

								 data: {

								 	_token : '{{ csrf_token() }}',
					 				id : id,
								 	business_name: business_name.val(),
								 	business_mobile: business_mobile.val(),
								 	business_city: city_id,
					 				owner_name : owner_name.val(),
								 	business_phone: business_phone.val(),
								 	business_pan: business_pan.val(),
								 	business_gst: business_gst.val(),
								 	business_email: business_email.val(),
								 	business_address: business_address.val(),

									person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val()

									},

								 dataType: "json",

									success:function(data, textStatus, jqXHR) {

										if(data.status == 1)
										{
										select_item.append('<option value="'+data.data.id+'">'+data.data.business_name+" ("+data.data.alias+')</option>');

							        	select_item.val(data.data.id);

										select_item.trigger("change");

										select_item.closest('.search_container').find('.search_popover').hide();

										select_item.closest('.search_container').find('.user_add_container').hide();

										$('.loader_wall_onspot').hide();

										select_item.closest('.search_container').find('form')[0].reset();
										}
										if(data.status == 0)
										{
										
										$('.loader_wall_onspot').hide();
										business_mobile.closest('div').find('span').remove();
										business_mobile.closest('div').append('<span class="error" style="color:red">Mobile Number already exists!</span>');
										}

									},

								 error:function(jqXHR, textStatus, errorThrown) {

									//alert("New Request Failed " +textStatus);

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