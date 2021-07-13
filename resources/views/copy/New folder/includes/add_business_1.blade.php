<!-- Modal Starts -->

@include('modals.business_search_modal')
@include('modals.add_business_modal')

@section('dom_links')
@parent
<script type="text/javascript">
var current_business_selection = null;
function add_new_business_link() {
	setTimeout(function() {
		current_business_selection.select2("close");
	  	current_business_selection.closest('.search_container').find('.search_popover, .searchuser_container').show();
	  	current_business_selection.closest('.search_container').find('.user_add_container, .searchuser_result_container').hide();
	  	current_business_selection.val("");
	  	current_business_selection.trigger("change");
	}, 0);
}





	function select_business(select_item) {
		select_item.closest('.search_container').find('.content').html("");
		
		select_item.closest('.search_container').find('.content').append(`<div class="search_popover" style="display: none;">

					<div class="form-group">
						<div class="row searchuser_container">
							<div style="padding-top: 15px;" class="col-md-12">	
								{{ Form::label('', 'SEARCH BY', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}
								<br>
								{{ Form::radio('search_by', 0, true, ['id' => 'business_user']) }} <label for="business_user"><span></span>Business Name</label>
								{{ Form::radio('search_by', 1, false, ['id' => 'business_crm']) }} <label for="business_crm"><span></span>PROPEL-ID</label>
							</div>
							<div class="col-md-12">	
								{{ Form::text('crm_id', null, ['class'=>'form-control', 'placeholder' => 'PROPEL-ID', 'style' => 'display: none']) }}

								{{ Form::text('username', null, ['class'=>'form-control', 'placeholder' => 'Name']) }}
							</div>
							<div class="col-md-12">
								<button style="float: right; margin: 5px;" class="btn btn-success simple_business_search_btn">Search</button>
							</div>
							<div style="position: absolute; bottom: 0;" class="col-md-12"> <a id="business_detailed_search" href="javascript:;">Detailed Search</a>
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
							<div class="col-md-12">	
								{{ Form::label('name', 'Business Name', ['class'=>'control-label required']) }}

								{{ Form::text('name', null, ['class'=>'form-control', 'autocomplete' => 'off']) }}
							</div>


							<div class="col-md-12">	
								{{ Form::label('gst', 'GST NO', ['class'=>'control-label required']) }}

								{{ Form::text('gst', null, ['class'=>'form-control', 'autocomplete' => 'off']) }}
							</div>

							<div class="container">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6">	
											{{ Form::label('mobile', 'Mobile', ['class'=>'control-label required']) }}

											{{ Form::text('mobile', null, ['class'=>'form-control', 'autocomplete' => 'off']) }}
										</div>

										<div class="col-md-6">	
											{{ Form::label('phone', 'phone', ['class'=>'control-label']) }}

											{{ Form::text('phone', null, ['class'=>'form-control', 'autocomplete' => 'off']) }}
										</div>
									</div>
							</div>
							</div>


							<div class="row">
							<div class="col-md-12">	
							<div class="row">
							<div class="col-md-6">	
								{{ Form::label('state', 'State', ['class'=>'control-label required']) }}

								{{ Form::select('user_state', $state, null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }}
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
	})


        .on("select2:select", function(e) { 
        	if(e.params.data.id == "0" && e.params.data.name == "") {
				current_business_selection = select_item;
				add_new_business_link();
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
			  var regex = /^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/;
			  return regex.test(gst);
		}

		

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
        	} else if(business_gst.val() == "") {
        		business_gst.closest('div').append('<span class="error" style="color:red">Enter your GST No.</span>');
        	} else if(!isGst(business_gst.val())) {
        		business_gst.closest('div').append('<span class="error" style="color:red">Enter a valid GST No.</span>');
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
        	}  else {
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
					 	business_gst: business_gst.val(),
						person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val()
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