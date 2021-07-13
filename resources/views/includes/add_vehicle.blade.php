<!-- Modal Starts -->

@include('modals.vehicle_search_modal')
@include('modals.add_vehicle_modal')

@section('dom_links')
@parent
<script type="text/javascript">


	function select_vehicle(select_item) {
		select_item.closest('.search_container').find('.content').html("");
		select_item.closest('.search_container').find('.content').append(`<div class="search_popover" style="display: none;">
											<div class="form-group">
												<div class="row searchuser_container">
												<div style="padding-top: 15px;" class="col-md-12">	
														{{ Form::label('', 'Enter Registration No.', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}
													</div>
													<div class="col-md-12">
													<div class="row container">	
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => 'TN']) }}
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => '00']) }}
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => 'AA']) }}
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => '1234']) }}
													</div>
													</div>
													<div class="col-md-12">
														<button style="float: right; margin: 5px;" class="btn btn-success simple_vehicle_search_btn">Search</button>
													</div>
													<div style="position: absolute; bottom: 0;" class="col-md-12"> <a id="bvehicle_detailed_search" href="javascript:;">Detailed Search</a>
													</div>
												</div>
												<div class="row searchvehicle_result_container" style="display: none">
												<div style="padding-top: 15px;" class="col-md-12">	
														{{ Form::label('', 'Search Result', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}
														<br>
														<label class="result_text" style="font-weight: bold; "></label>
														<button data-id="" style="float: right; margin: 5px;" class="btn btn-success simple_result_btn">Add</button>
													</div>
													<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" class="add_simple_vehicle">Add New</a>
													</div>
												</div>

												<div class="row user_add_container" style="display: none; width: 400px" >
												{!! Form::open(['class' => 'form-horizontal vehiclevalidateform col-md-12']) !!}
												<div style="padding-top: 15px;" class="col-md-12">	
														{{ Form::label('', 'Add Vehicle', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}
														
													</div>
													<div class="col-md-12">	
														{{ Form::label('name', 'Registration No.', ['class'=>'control-label required']) }}

														<div class="col-md-12">
													<div class="row container">	
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => 'TN']) }}
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => '00']) }}
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => 'AA']) }}
														{{ Form::text('reg_no', null, ['class'=>'form-control col-md-3', 'placeholder' => '1234']) }}
													</div>
													</div>
													</div>

													<div class="container">
													<div class="row">
													<div class="col-md-12">	
													<div class="row">
													<div class="col-md-6">	
														{{ Form::label('mobile', 'Mobile', ['class'=>'control-label required']) }}

														{{ Form::text('mobile', null, ['class'=>'form-control']) }}
													</div>

													<div class="col-md-6">	
														{{ Form::label('phone', 'phone', ['class'=>'control-label']) }}

														{{ Form::text('phone', null, ['class'=>'form-control']) }}
													</div>
													</div>
													</div>
													</div>


													<div class="row">
													<div class="col-md-12">	
													<div class="row">
													<div class="col-md-6">	
														{{ Form::label('state', 'State', ['class'=>'control-label required']) }}

														{{ Form::select('user_state', [], null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }}
													</div>

													<div class="col-md-6">	
														{{ Form::label('city', 'city', ['class'=>'control-label required']) }}

														{{ Form::select('user_city', ['' => 'Select city'], null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }}
													</div>
													</div>
													</div>
													</div>


													<div class="row">
													<div class="col-md-12">	
													<div class="row">
													<div class="col-md-6">	
														{{ Form::label('pan', 'pan', ['class'=>'control-label']) }}

														{{ Form::text('pan', null, ['class'=>'form-control']) }}
													</div>

													<div class="col-md-6">	
														{{ Form::label('gst', 'gst', ['class'=>'control-label']) }}

														{{ Form::text('gst', null, ['class'=>'form-control']) }}
													</div>
													</div>
													</div>
													</div>


													</div>

													<div class="col-md-12">	
														<button style="float: right; margin: 5px;" class="btn btn-success add_new_business">Add</button>
													</div>
													{!! Form::close() !!}
													<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" id="business_detailed_add" class="detailed_business">Add Detailed Record</a>
													</div>
												</div>
											</div>
										</div>`);

		select_item.select2({
            templateResult: format
        }).on("select2:select", function(e) { 
        	if(e.params.data.id == "0" && e.params.data.text == "") {
        		select_item.closest('.search_container').find('.search_popover, .searchvehicle_container').show();
        		select_item.closest('.search_container').find('.vehicle_add_container, .searchvehicle_result_container').hide();
        		select_item.val("");
				select_item.trigger("change");
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

        select_item.closest('.search_container').find('.add_simple_vehicle').on('click', function(e) {
        	select_item.closest('.search_container').find('.searchvehicle_result_container').hide();
        	select_item.closest('.search_container').find('.vehicle_add_container').show();
        });

        select_item.closest('.search_container').find('.add_simple_vehicle').on('click', function(e) {
        	select_item.closest('.search_container').find('.searchvehicle_result_container').hide();
        	select_item.closest('.search_container').find('.vehicle_add_container').show();
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
			 	id: value
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
				 	mobile_no: mobile
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
	  		});


         select_item.closest('.search_container').find('.add_new_business').on('click', function(e) {
        	e.preventDefault();

        	var business_name = select_item.closest('.search_container').find('input[name=name]');
        	var business_mobile = select_item.closest('.search_container').find('input[name=mobile]');
        	var business_state = select_item.closest('.search_container').find('select[name=user_state]'); 
        	var business_city =  select_item.closest('.search_container').find('select[name=user_city]');
        	var business_phone = select_item.closest('.search_container').find('input[name=phone]');
        	var business_pan = select_item.closest('.search_container').find('input[name=pan]');
        	var business_gst = select_item.closest('.search_container').find('input[name=gst]');

        	business_name.closest('div').find('span.error').remove();
        	business_mobile.closest('div').find('span.error').remove();
        	business_state.closest('div').find('span.error').remove();
        	business_city.closest('div').find('span.error').remove();
        	business_phone.closest('div').find('span.error').remove();
        	business_pan.closest('div').find('span.error').remove();
        	business_gst.closest('div').find('span.error').remove();

        	if(business_name.val() == "") {
        		business_name.closest('div').append('<span class="error" style="color:red">Enter a valid name</span>');
        	} else if(business_mobile.val() == "") {
        		business_mobile.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');
        	} else if(isNaN(business_mobile.val())) {
        		business_mobile.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');
        	} else if((business_mobile.val()).length != 10) {
        		business_mobile.closest('div').append('<span class="error" style="color:red">Mobile number should be 10 numbers</span>');
        	} else if(business_state.val() == "") {
        		business_state.closest('div').append('<span class="error" style="color:red">Choose a state</span>');
        	} else if(business_city.val() == "") {
        		business_city.closest('div').append('<span class="error" style="color:red">Choose a city</span>');
        	} else {
        		$('.loader_wall_onspot').show();
				$.ajax({
					 url: '{{ route('simple_business_add') }}',
					 type: 'post',
					 data: {
					 	_token : '{{ csrf_token() }}',
					 	business_name: business_name.val(),
					 	business_mobile: business_mobile.val(),
					 	business_city: business_city.val(),
					 	business_phone: business_phone.val(),
					 	business_pan: business_pan.val(),
					 	business_gst: business_gst.val()
						},
					 dataType: "json",
						success:function(data, textStatus, jqXHR) {
							select_item.append('<option value="'+data.data.id+'">'+data.data.business_name+" ("+data.data.alias+')</option>');
				        	select_item.val(data.data.id);
							select_item.trigger("change");
							select_item.closest('.search_container').find('.search_popover').hide();
							select_item.closest('.search_container').find('.user_add_container').hide();
							$('.loader_wall_onspot').hide();
							select_item.closest('.search_container').find('form')[0].reset();
						},
					 error:function(jqXHR, textStatus, errorThrown) {
						//alert("New Request Failed " +textStatus);
						}
					});
        	}
        });

	}


</script>
@stop
<!-- Modal Ends -->