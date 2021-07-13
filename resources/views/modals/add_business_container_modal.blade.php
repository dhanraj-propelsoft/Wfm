@section('head_links')
@parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
@stop
<!-- transaction detailed search  popup -->

<div class="bs-modal-lg modal fade add_business_container_modal" tabindex="-1" role="basic" aria-hidden="true">
  	<div class="modal-dialog">
		<div class="modal-content">
		  	<div class="modal-header">
				<h4 class="modal-title">Add Business</h4>
		 	 	<button type="button" class="close" data-dismiss="modal">&times;</button>
		  	</div>
	  		{!! Form::open(['class' => 'form-horizontal', 'id' => 'business_container']) !!}
			{{ csrf_field() }}

			<div class="modal-body"> 
			  	<div class="alert alert-danger" style="margin-bottom: 5px; padding: 5px;" id="errorlist"></div>
					<div class="form-body">

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
										{{ Form::label('mobile', 'Mobile', ['class'=>'control-label required']) }}
									</div>			
									<div class="col-md-8">
										{!! Form::text('mobile', null , ['class' => 'form-control', 'placeholder' => 'Mobile']) !!} 				
									</div>			
								</div>

								<div class="row">
									<div class="col-md-4">
										{{ Form::label('email', 'E-Mail ID', ['class'=>'control-label']) }}
									</div>			
									<div class="col-md-8">
										{!! Form::text('email_address', null , ['class' => 'form-control', 'placeholder' => 'E-Mail ID']) !!}				
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
										{{ Form::label('gst', 'GST NO', ['class'=>'control-label required']) }} 
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

								<div class="row">
									<div class="col-md-4">
										{{ Form::label('state', 'State', ['class'=>'control-label required']) }}
									</div>			
									<div class="col-md-8">
										{{ Form::select('user_state', $state, null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }}				
									</div>			
								</div>

								<div class="row">
									<div class="col-md-4">
										{{ Form::label('city', 'city', ['class'=>'control-label required']) }}
									</div>			
									<div class="col-md-8">
										{{ Form::select('user_city', ['' => 'Select city'], null, ['class'=>'form-control select_item', 'style' => 'width: 100%']) }} 			
									</div>			
								</div>
						
						
				  		

					</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
				
				<button type="submit" class="btn btn-success ">Save</button>
			</div>

	  	{!! Form::close() !!}

	  	<div style="position: absolute; bottom: 0;" class="col-md-12"> <a href="javascript:;" id="business_detailed_add" class="detailed_business">Add Detailed Record</a>
						</div>
		
				

		</div>
	<!-- modal-content --> 
  	</div>
  <!-- modal-dialog -->
</div>

<!-- Modal Ends -->

@section('dom_links')
@parent 
<script type="text/javascript">

		$('.add_business_container_modal').on('hidden.bs.modal', function(e){ 
		   $('#business_container').closest('.add_business_container_modal').find('.result tbody').html("");
		   $('#business_container').closest('.add_business_container_modal').find('.modal-title').text("Search User");
		   $('#business_container')[0].reset();
		});

		$("input:text").on('focus', function() {
		  $('#business_container').closest('.add_business_container_modal').find('#errorlist').hide();
		  $('#business_container').closest('.add_business_container_modal').find('#errorlist').text("");
		});

	
		

		$('select[name=user_state]').on('change', function () {
	        var obj = $(this);
			var city = $( "select[name=user_city]" );
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

		

	    /*$('body').on('click', '#add_business_container', function() {
		  
		  $('.search_business_modal').modal('hide');
		  $('.add_business_container_modal').modal('show');

		  $('#search_business').closest('.search_business_modal').find('.result tbody').html("");
		  $('#business_container').closest('.add_user_modal').find('.modal-title').text("Search Business");
		  $('#business_container')[0].reset();
		  $('#business_container').show();
		  current_select_item = $(this).closest('.search_container').find('select.business_id');
		});*/


        $('#business_container').validate({
			errorElement: 'span', //default input error message container
				errorClass: 'help-block', // default input error message class
				focusInvalid: false, // do not focus the last invalid input
				rules: {
              
                name: {
                    required: true
                },
                gst: {
                    required: true
                },
                mobile: {
                    required: true
                }
            },

            messages: {
               
                name: {
                    required: "Business Name is required."
                },
                gst: {
                    required: "GST is required."
                },
                mobile: {
                    required: "Mobile is required."
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
					 $('.loader_wall_onspot').show();
					var business_name =  $('.add_business_container_modal input[name=name]').val();
		        	var business_mobile = $('.add_business_container_modal input[name=mobile]').val();
		        	var business_state = $('.add_business_container_modal select[name=user_state]').val(); 
		        	var business_city =  $('.add_business_container_modal select[name=user_city]').val();
		        	var business_phone = $('.add_business_container_modal input[name=phone]').val();
		        	var business_pan = $('.add_business_container_modal input[name=pan]').val();
		        	var business_gst = $('.add_business_container_modal input[name=gst]').val();
		        	var business_address = $('.add_business_container_modal input[name=address]').val();

        			var business_email = $('.search_container input[name=email_address]').val();
	        				  
				   

					$.ajax({
						url: '{{ route('simple_business_add') }}',
						type: 'post',
						data: {
						 	_token : '{{ csrf_token() }}',
						 	business_name: business_name,
						 	business_mobile: business_mobile,
						 	business_city: business_city,
						 	business_phone: business_phone,
						 	business_pan: business_pan,
						 	business_gst: business_gst,
						 	business_email: business_email,
					 		business_address: business_address,
							person_type: 'customer',
							//person_type: select_item.closest('.search_container').find('input[name=account_person_type_id]:checked').val()
							},
						dataType: "json",
							success:function(data, textStatus, jqXHR) {

								console.log(data);								

								/*select_item.append('<option value="'+data.data.id+'">'+data.data.business_name+" ("+data.data.alias+')</option>');
					        	select_item.val(data.data.id);
								select_item.trigger("change");
								select_item.closest('.search_container').find('.search_popover').hide();0
								select_item.closest('.search_container').find('.user_add_container').hide();
								$('.loader_wall_onspot').hide();
								select_item.closest('.search_container').find('form')[0].reset();*/

								$('#business_container')[0].reset();
								$('.loader_wall_onspot').hide();
								current_select_item.append('<option value="'+data.data.id+'">'+data.data.business_name+'</option>');
								current_select_item.val(data.data.id);
								current_select_item.trigger("change");
								$('.add_business_container_modal').modal('hide');


							},
							 error:function(jqXHR, textStatus, errorThrown) {
								//alert("New Request Failed " +textStatus);
							}
					});				   
				}
		});


		

</script> 
@stop