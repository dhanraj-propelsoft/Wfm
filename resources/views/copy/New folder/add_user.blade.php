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
	  	current_selection.closest('.search_container').find('.search_popover, .searchuser_container').show();
	  	current_selection.closest('.search_container').find('.user_add_container, .searchuser_result_container').hide();
	  	current_selection.val("");
	  	current_selection.trigger("change");
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
		set_user_data(id, 0)
	});


	$('body').on('change', '.business_id', function() {
		var id = $(this).val();
		set_user_data(id, 1)
	});

});


function set_user_data(id, account) {

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

		if(id != "" && id != 0) {
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
						$(".first_name").val(data.first_name);
					}

					if($(".last_name").length){
						$(".last_name").val(data.last_name);
					}

					if($(".display_name").length){
						$(".display_name").val(data.display_name);
					}

					if($(".email").length){
						$(".email").val(data.email_address);
					}

					if($(".mobile").length){
						$(".mobile").val(data.mobile_no);
					}

					if($(".address").length) {

						var address = "";

						if(data.billing_address != "") {
							address += data.billing_address+"\n";
						}

						if(data.billing_city != "") {
							address += data.billing_city+"\n";
						}

						if(data.billing_state != "") {
							address += data.billing_state;
						}

						if(data.billing_pin != "" && data.billing_state != "") {
							address += " - "+data.billing_pin;
						}

						$(".address").val(address);
					}

					if($(".shipping_address").length) {

						var address = "";

						if(data.shipping_address != "") {
							address += data.billing_address+"\n";
						}

						if(data.shipping_city != "") {
							address += data.shipping_city+"\n";
						}

						if(data.billing_state != "") {
							address += data.shipping_state;
						}

						if(data.shipping_pin != "" && data.shipping_state != "") {
							address += " - "+data.shipping_pin;
						}

						$(".shipping_address").val(address);
					}

					if($(".gender#"+data.gender_id).length){
						$(".gender").prop('checked', false);
						$(".gender#"+data.gender_id).prop('checked', true);
						$('.gender').trigger('change');
					}

					if($(".title").length){
						$(".title").val(data.title_id);
						$('.title').trigger('change');
					}

					if($(".blood_group").length){
						$(".blood_group").val(data.blood_group_id);
						$('.blood_group').trigger('change');
					}

					if($(".marital_status").length){
						$(".marital_status").val(data.marital_id);
						$('.marital_status').trigger('change');
					}
				},
			error:function(jqXHR, textStatus, errorThrown) {}
			});
		}
}


	function select_user(select_item) {
		select_item.closest('.search_container').find('.content').html("");
		select_item.closest('.search_container').find('.content').append(`<div class="search_popover" style="display: none;">
											<div class="form-group">
												<div class="row searchuser_container">
												<div style="padding-top: 15px;" class="col-md-12">	
														{{ Form::label('', 'SEARCH BY', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}
														<br>
														{{ Form::radio('search_by', 0, true, ['id' => 'user']) }} <label for="user"><span></span>Username</label>
														{{ Form::radio('search_by', 1, false, ['id' => 'crm']) }} <label for="crm"><span></span>PROPEL-ID</label>
													</div>
													<div class="col-md-12">	
														{{ Form::text('crm_id', null, ['class'=>'form-control', 'placeholder' => 'PROPEL-ID', 'style' => 'display: none']) }}

														{{ Form::text('username', null, ['class'=>'form-control', 'placeholder' => 'Email / Mobile']) }}
													</div>
													<div class="col-md-12">
														<button style="float: right; margin: 5px;" class="btn btn-success simple_usersearch_btn">Search</button>
													</div>
													<div style="position: absolute; bottom: 0;" class="col-md-12"> <a id="user_detailed_search" href="javascript:;">Detailed Search</a>
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

												<div class="row user_add_container" style="display: none" >
												{!! Form::open(['class' => 'form-horizontal uservalidateform col-md-12']) !!}
												<div style="padding-top: 15px;" class="col-md-12">	
														{{ Form::label('', 'Add User', array('class' => 'control-label', 'style' => 'font-weight: bold')) }}
														
													</div>
													<div class="col-md-12">	
														{{ Form::label('name', 'Name', ['class'=>'control-label required']) }}

														{{ Form::text('name', null, ['class'=>'form-control', 'autocomplete' => 'off']) }}
													</div>

													<div class="col-md-12">	
														{{ Form::label('mobile', 'Mobile', ['class'=>'control-label required']) }}

														{{ Form::text('mobile', null, ['class'=>'form-control', 'autocomplete' => 'off']) }}
													</div>

													<div class="col-md-12">	
														{{ Form::label('state', 'State', ['class'=>'control-label required']) }}

														{{ Form::select('user_state', $state, null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }}
													</div>

													<div class="col-md-12">	
														{{ Form::label('city', 'city', ['class'=>'control-label required']) }}

														{{ Form::select('user_city', ['' => 'Select city'], null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }}
													</div>

													<div class="col-md-12">	
														<button style="float: right; margin: 5px;" class="btn btn-success add_new_user">Add</button>
													</div>
													{!! Form::close() !!}
													<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" id="user_detailed_add" class="detailed_user">Add Detailed Record</a>
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
       	current_selection = select_item;
           return "No Results Found <i onclick='return add_new_link()' class='add_new_link'>+ Add New</i>";
       }
   },
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatPeople,
}).on("select2:select", function(e) { 
			if(e.params.data.id == "0" && e.params.data.name == "") {
				current_selection = select_item;
				add_new_link();
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

			var name = select_item.closest('.search_container').find('input[name=name]');
			var mobile = select_item.closest('.search_container').find('input[name=mobile]');
			var state = select_item.closest('.search_container').find('select[name=user_state]'); 
			var city =  select_item.closest('.search_container').find('select[name=user_city]');

			name.closest('div').find('span.error').remove();
			mobile.closest('div').find('span.error').remove();
			state.closest('div').find('span.error').remove();
			city.closest('div').find('span.error').remove();

			if(name.val() == "") {
				name.closest('div').append('<span class="error" style="color:red">Enter a valid name</span>');
			} else if(mobile.val() == "") {
				mobile.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');
			} else if(isNaN(mobile.val())) {
				mobile.closest('div').append('<span class="error" style="color:red">Enter a valid mobile number</span>');
			} else if((mobile.val()).length != 10) {
				mobile.closest('div').append('<span class="error" style="color:red">Mobile number should be 10 numbers</span>');
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
						first_name: name.val(),
						mobile_no: mobile.val(),
						city_id: city.val(),
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
		});

	}


</script>
@stop
<!-- Modal Ends -->