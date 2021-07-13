@extends('layouts.master')
@section('head_links') @parent
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
  <link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}" />
  <link rel='stylesheet' href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.print.min.css') }}" media='print' />
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
  <style>

	#container ul.nav.nav-tabs {
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f9fafa+0,eef5f8+100 */
		background: #f9fafa; /* Old browsers */
		background: -moz-linear-gradient(top,  #f9fafa 0%, #eef5f8 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(top,  #f9fafa 0%,#eef5f8 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom,  #f9fafa 0%,#eef5f8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f9fafa', endColorstr='#eef5f8',GradientType=0 ); /* IE6-9 */
		box-shadow:0px 1px 2px #ccc;
		padding: 15px 10px;
		border-radius: 10px;
	}

	.nav-tabs .nav-link {
		padding: 0.5rem .8rem;
	}
	.nav-tabs .nav-link:hover {
		border: 1px solid transparent;
		border-radius: none;
	}

	.nav-tabs .nav-link.active {
		background: #D2DEE2;
	   box-shadow: inset 0 3px 1px #B9C2D1;
	   border-radius: 15px;
	   border: 1px solid transparent;
	}

	label {
		font-weight: bold;
	}

	/* input[type=text], .select2, textarea.form-control, .radio{
		display: none;
	} */

	.text {
		float: left;
		width: 100%;
	}

	.text-styled {
		color: #5682b7;
		font-size: 22px;
		font-family: 'ProximaNovaLight', 'ProximaNovaRegular', 'Source Sans Pro', Arial, sans-serif;
	}
  </style>
@stop
@include('includes.settings')
@section('content')

	<div class="alert alert-success">
	</div>


	<div class="content">
	<div class="row">
	<div  style="margin:auto auto auto auto; ">
		
					<img width="100%" src="{{$path}}" style="background: #eee; border-radius: 5px; padding: 10px; " class="img-responsive img" alt="Employee Image"/>

					<div style=" height: 160px; width: 160px; display: none" class="dropzone" id="image-upload" >
						<img width="100%" src="{{$path}}" style="background: #eee; border-radius: 5px; padding: 10px; " class="img-responsive img_old" alt="Employee Image" /> 
					</div>
	</div>

	<div class="col-md-9">
		<h3 class="float-left">{{ $person->first_name }}</h3><div class="clearfix"></div>
			<h5 class="float-left"></h5>
			<div class="clearfix"></div>

			<ul class="nav nav-tabs">
			  <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#personal_details">Personal Details</a> </li>
			  <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#communication">Communication</a> </li>	  
			</ul>

			{!! Form::open(['class' => 'form-horizontal validateform']) !!}

				<div class="tab-content" style= border-top: 0px; padding: 10px;">
				
					<div class="tab-pane active" id="personal_details">
						<div class="clearfix"></div><br>
							<div class="personal_details_edit_tab btn btn-info">Edit</div>
							<div style="display: none;" class="personal_details_update_tab btn btn-success">Update</div>
								<br><br>
							
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												{!! Form::label('person_name','Name', array('class' => 'control-label required')) !!}
												{!! Form::text('person_name', $person->first_name,['class' => 'form-control ']) !!}
												<span class="text text-styled">{{$person->first_name}}</span>
											</div>
											<div class="col-md-4">
												<label for="gender_id">Gender</label><br>
												@foreach($genders as $gender)
												<input type="radio" @if($gender->id == $person->gender_id) checked="checked" @endif name="gender_id" id="{{ $gender->id }}"  value="{{ $gender->id }}" class="form-control">
												<label class="radio" for="{{ $gender->id }}" required='required'>
													<span></span>{{ $gender->gender }}</label>
												@endforeach				
												<span class="text text-styled">{{ $person->gender_name }}</span>
											</div>

											<div class="col-md-4">
												{!! Form::label('alias', 'Alice', array('class' => 'control-label')) !!}
										
												{!! Form::text('alias', $person->alias, ['class' => 'form-control' ]) !!}
												<span class="text text-styled">{{$person->alias}}</span>
											</div>					
										</div>
								  	</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												<label for="marital_status_id">Marital Status</label>
												{{ Form::select('marital_status_id',$marital_status, $person->marital_status_id, ['class' => 'form-control select_item', 'id' => 'marital_status']) }}
												<span class="text text-styled">{{ $person->marital_status_name }}</span>
											</div>

											<div class="col-md-4">
												{!! Form::label('dob','Date of Birth', array('class' => 'control-label required')) !!}
												{!! Form::text('dob', $person->dob,['class' => 'form-control date-picker rearrangedate','data-date-format' => 'dd-mm-yyyy']) !!}
												<span class="text text-styled rearrangedatetext">{{ $person->dob }}</span>
											</div>
										
										  	<div class="col-md-4">
												<label for="blood_group_id">Blood Group</label>
												{{ Form::select('blood_group_id',$blood_groups, $person->blood_group_id, ['class' => 'form-control select_item', 'id' => 'blood_group_id']) }}
												<span class="text text-styled">{{ $person->blood_group }}</span> 
											</div>								
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-4">
												{!! Form::label('pan_no','PAN', array('class' => 'control-label ')) !!}
											
												{!! Form::text('pan_no', $person->pan_no,['class' => 'form-control ']) !!}
												<span class="text text-styled">{{$person->pan_no}}</span>
											</div>	

											<div class="col-md-4">
												{!! Form::label('aadhar_no','Aathar No', array('class' => 'control-label')) !!}
											
												{!! Form::text('aadhar_no', $person->aadhar_no,['class' => 'form-control ']) !!}
												<span class="text text-styled">{{$person->aadhar_no}}</span>
											</div>

											<div class="col-md-4">
												{!! Form::label('passport_no','Passport No', array('class' => 'control-label')) !!}
											
												{!! Form::text('passport_no', $person->passport_no,['class' => 'form-control ']) !!}
												<span class="text text-styled">{{$person->passport_no}}</span>
											</div>			
										</div>
									</div>			
								</div>
					</div>

					<div class="tab-pane" id="communication">
						<div class="clearfix"></div>
						<br>	
						<?php
						if($communication){
						$address_id = $communication->address_id;
						$communication_address = $communication->address;
						$communication_mobile = $communication->mobile_no;
						$communication_email = $communication->email_address;
						$communication_state = $communication->state_name;
						$communication_city = $communication->city_name;
						$communication_pin = $communication->pin;
						$communication_google = $communication->google;	
						$communication_city_id= $communication->city_id;
                        $communication_state_id = $communication->sate_id;
					}else{
					    $address_id = '';
					    $communication_address = '';
						$communication_mobile = '';
						$communication_email = '';
						$communication_state = '';
						$communication_city = '';
						$communication_pin = '';
						$communication_google = '';	
				}


				if($official_communication){
                     $official_communication_address = $official_communication->address;
                     $official_communication_pin = $official_communication->pin;
                     $official_communication_google = $official_communication->google;
                     $official_communication_city_id= $official_communication->city_id;
                     $official_communication_state_id = $official_communication->sate_id;
                     $official_communication_state_name = $official_communication->sate_name;
                     $official_communication_city_name = $official_communication->city_name;


			}else{
                     $official_communication_address = '';
                     $official_communication_state = '';
                     $official_communication_city = '';
                     $official_communication_pin = '';
                     $official_communication_google = '';
                     $official_communication_state_name = '';
                     $official_communication_city_name = '';
		}
?>
						
							<div class = "communi">
								<div class="communication_edit_tab btn btn-info">Edit</div>
								<div style="display: none;" class="communication_update_tab btn btn-success">Update</div>
								<div class="col-md-6" style="    top: 24px;">
                                      <input type="checkbox" class="checkbox" name="communication" style="display:none;"><p class="content" style="display:none;font-size: 14px;color: #FFC107;">Updating Mobile number and Email address impact login too..This May Initiate Mobile number and Email Validation by any other means or OTP.</p>
                                     
                                      </div>
								    <div class="form-group">
                                          {{ Form::hidden('adderss_id',$address_id) }}
										<div class="row">
											<div class="col-md-6" style="top: 42px;">
											    {!! Form::label('phone','Mobile Number', array('class' => 'control-label required')) !!}
												{!! Form::text('phone',$communication_mobile , ['class' => 'form-control', 'placeholder' => 'Mobile Number','disabled']) !!} 
											  <span class="text">{{$communication_mobile }}</span>
											</div>

											<div class="col-md-6" style="top: 42px;">
											   {!! Form::label('email','Email Address', array('class' => 'control-label required')) !!}
												{!! Form::text('email',$communication_email, ['class' => 'form-control', 'placeholder' => 'Email Address','disabled']) !!} 
											  <span class="text">{{$communication_email}}</span>
											</div>
										</div>
										<div class="row residential" style="padding-top: 62px;padding-left: 13px;">
										{{ Form::hidden('residential','1') }}
											{!! Form::label('residential_address','Residential', array('class' => 'control-label required')) !!}
										</div>
                                    <div class="form-group">
										<div class="row">
											<div class="col-md-6">					  
												{!! Form::textarea('residential_address',$communication_address, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} 
											  <span class="text">{{$communication_address}}</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">			 

											  	<?php 
					  							$states = null;
					  							if(isset($communication_state_id)) { $states = $communication_state_id; } ?>
												{!! Form::select('state_id',$state,$states , ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

											  <span class="text">{{ $communication_state}}</span>
											</div>
											<div class="col-md-6">
											
											  	<?php 
					  							$cities = null;
					  							if(isset($communication_city_id)) { $cities = $communication_city_id; } ?>
												{!! Form::select('city_id', $city, $cities, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}
											  <span class="text">{{ $communication_city }}</span>
											</div>
										</div> 
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												{!! Form::text('r_pin',$communication_pin, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} 
											  <span class="text">{{ $communication_pin }}</span>
											</div>

											<div class="col-md-6">
												{!! Form::text('r_google',$communication_google, ['class' => 'form-control', 'placeholder' => 'Google Location']) !!} 
											  <span class="text">{{$communication_google}}</span>
											</div>
										</div>
									</div>
										<div class="row office" style="padding-left: 15px;">
											{{ Form::hidden('office','2') }}
											{!! Form::label('office','Office', array('class' => 'control-label required')) !!}
										</div>
										<div class="form-group">
										<div class="row">
											<div class="col-md-6">				  
												{!! Form::textarea('address',$official_communication_address, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} 
											  <span class="text">{{$official_communication_address}}</span>
											</div>
										</div>
									</div>
                                      <div class="form-group">
										<div class="row">
											<div class="col-md-6">			 

											  	<?php 
					  							$states = null;
					  							if(isset($official_communication_state_id)) { $states = $official_communication_state_id; } ?>
												{!! Form::select('state_id',$state,$states , ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 

											  <span class="text">{{ $official_communication_state_name }}</span>
											</div>
											<div class="col-md-6">
												{!! Form::select('city_id', $city, $cities, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!}
											  <span class="text">{{ $official_communication_city_name }}</span>
											</div>
										</div> 
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												{!! Form::text('pin',$official_communication_pin, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} 
											  <span class="text">{{ $official_communication_pin }}</span>
											</div>

											<div class="col-md-6">
												{!! Form::text('google',$official_communication_google, ['class' => 'form-control', 'placeholder' => 'Google Location']) !!} 
											  <span class="text">{{ $official_communication_google }}</span>
											</div>
										</div>
									</div>
										</div>
									</div>
					
									
							
								
					</div>
				</div>	

			{!! Form::close() !!}
			</div>
		</div>
	</div>	
@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script><script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<script type="text/javascript">

	Dropzone.autoDiscover = false;
	var image_upload = new Dropzone("div#image-upload", {
      paramName: 'file',
      url: "{{route('profile_image_upload')}}",
      params: {
          _token: '{{ csrf_token() }}'
      },
      dictDefaultMessage: "Drop or click to upload image",
      clickable: true,
      maxFilesize:5, // MB
      acceptedFiles: "image/*",
      maxFiles: 1,
      autoProcessQueue: false,
      addRemoveLinks: true,
      init: function() {
      this.on("addedfile", function(file) {
      $(".img_old").hide(); });
       },
      removedfile: function(file) {
          file.previewElement.remove();
      },
      queuecomplete: function() {
          image_upload.removeAllFiles();
      },
      success: function(file, response){
      
      	 $(".img").attr('src', response.data.path);
      	
       // image_call_back(response.data.path, response.data.id);
      }
    });
  
$(document).ready(function() {
  
  $('input[name=communication]').on('click',function(){
         $('input[name=phone]').removeAttr("disabled");
         $('input[name=email]').removeAttr("disabled");
  });

	var bankname = $('select[name=bank_name]').val();

	$('input[type=text], .select2, textarea.form-control, .radio').hide();

	$('.validateform').validate({

		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			
			person_name: { required: true },
			dob:{ required: true },
			residential_address:{required: true},
			r_state_id:{required: true},
			r_city_id:{required: true},
			r_pin:{required: true},
			phone: { 
				required: true,
				remote: {
						url: '{{ route('get_mobile_no') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val(),
						 id : '{{$id}}',
						}
					}
			},
		},

		messages: {
			person_name: { required: "Name is required." },
			dob: { required: "Date of Birth is required." },
			residential_address: {required: "Address is required." },
            r_state_id: {required: "state is required."},
            r_city_id: {required: "city is required."},
            r_pin: {required: "Pincode is required."},
            phone: { required: "Mobile Number is required.", remote: "Mobile Number is already exists!" },
		},

		invalidHandler: function(event, validator) 
		{ 
			//display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) 
		{ // hightlight error inputs
			$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},

		submitHandler: function(form) {
			
		}
	});


	$( "select[name=department_id]" ).change(function () {
		var designation = $( "select[name=designation_id]" );
		var id = $(this).val();
		designation.val("");
		designation.select2('val', '');
		designation.empty();
		if(id != "") {
		$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_designation') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					department_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						designation.append("<option value=''>Select Designation</option>");
						for(var i in result) {	
							designation.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
						designation.append("<option value=''></option>");
						$('.loader_wall_onspot').hide();
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	$( "select[name=designation_id]" ).change(function () {
		var employee= $( "select[name=employee_id]" );
		var id = $(this).val();
		employee.val("");
		employee.select2('val', '');
		employee.empty();
		if(id != "") {
			$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_employee') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					designation_id: id
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						employee.append("<option value=''>Select Employee</option>");
						for(var i in result) {
							employee.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	

	$('#mycalendar').fullCalendar({
	height: 400
	});

	$('.add_table_row').on('click', function(){
		$(this).closest('tr').find('input, .select_item').show();
	});

	$('body').on('click', '.experience_delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('employees.experience_delete') }}';
		delete_row(id, parent, delete_url);
	});

	$('body').on('click', '.education_delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('employees.education_delete') }}';
		delete_row(id, parent, delete_url);
   	});

	function delete_row(id, parent, delete_url) {
	  $('.delete_modal_ajax').modal('show');
		$('.delete_modal_ajax_btn').off().on('click', function() {
			  $.ajax({
			 url: delete_url,
			 type: 'post',
			 data: {
			  _method: 'delete',
			  _token : '{{ csrf_token() }}',
			  id: id,
			  },
			 dataType: "json",
			  success:function(data, textStatus, jqXHR) {
				parent.remove();
				$('.delete_modal_ajax').modal('hide');
				$('.alert-success').text(data.message);
				$('.alert-success').show();

				setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			  },
			 error:function(jqXHR, textStatus, errorThrown) {
			  }
			});
		});
	}

	$('.personal_details_edit_tab').on('click', function() {
		$(this).closest('.tab-pane').find('.personal_details_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();
		$(this).closest('.tab-pane').find('.personal_details_update_tab').show();
		$(this).closest('.tab-pane').find('.text').hide();
		$('.dropzone').show();
		$('.img').hide();
		$('.dp').hide();
		$(this).hide();
	});

	$('.personal_details_update_tab').on('click', function() {
		var obj = $(this);
		$('.dropzone').hide();
		$('.img').show();
		if($(".validateform").valid()) {
			$.ajax({
				url: '{{ route('person_profile.personal_details_update') }}',
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					_method: 'PATCH',
					id: '{{$id}}',
					first_name: $('input[name=person_name]').val(),
					alias: $('input[name=alias]').val(),
					dob: $('input[name=dob]').val(),
					gender_id: $('input[name=gender_id]:checked').val(),
					blood_group_id: $('select[name=blood_group_id]').val(),
					marital_status_id: $('select[name=marital_status_id]').val(),
					pan_no: $('input[name=pan_no]').val(),
					aadhar_no: $('input[name=aadhar_no]').val(),
					passport_no: $('input[name=passport_no]').val(),
					
					},
				success:function(data, textStatus, jqXHR) {
					image_upload.on("sending", function(file, xhr, response) {
									response.append("id", '{{$id}}');
								});

							image_upload.processQueue();
							
					obj.closest('.tab-pane').find('input[type=text]').each(function() {
						$(this).closest('div').find('.text').text($(this).val());
					});

					obj.closest('.tab-pane').find('select').each(function() {
						$(this).closest('div').find('.text').text($(this).find('option:selected').text());
					});

					obj.closest('.tab-pane').find('.personal_details_edit_tab').show();
					obj.closest('.tab-pane').find('.text').show();
					obj.closest('.tab-pane').find('.personal_details_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();
					obj.hide();
				},
				error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
				}
			});	
		}	
	});

	$('.communication_edit_tab').on('click', function() {
		$(this).closest('.communi').find('.communication_update_tab, input[type=text], .select2, textarea.form-control, .radio').show();
		$('input[name=communication]').css("display","block");
		$('.content').css("display","block");
		$(this).closest('.communi').find('.communication_update_tab').show();
		$(this).closest('.communi').find('.text').hide();
		$(this).hide();
	});
     var address_type2 = '';
     if($('textarea[name=address]').val().length > 0){
     	address_type2 = $('input[name=office]').val();
     }else{
     	address_type2 = '';
     }
        var mobile_no = '';
     $('input[name=phone]').on('change',function(){
        mobile_no = $('input[name=phone]').val();
	});
      var email_id = '';
      $('input[name=email]').on('change',function(){
        email_id = $('input[name=email]').val();
	});
	$('.communication_update_tab').on('click', function() {
		var obj = $(this);
		var parent = obj.closest('.communi');
		if($(".validateform").valid()) {
		$.ajax({
			url: '{{ route('person_profile.communication_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: parent.find("input[name=adderss_id]").val(),
				person_id: '{{$id}}',			
				address_type:$('input[name=residential]').val() ,
				address_type2: address_type2,
				residential_address: $('textarea[name=residential_address]').val(),
				residential_city_id: $('select[name=r_city_id]').val(),
				residential_pin: $('input[name=r_pin]').val(),
				residential_google: $('input[name=r_google]').val(),
				address: $('textarea[name=address]').val(),
				city_id: $('select[name=city_id]').val(),
				pin: $('input[name=pin]').val(),
				google: $('input[name=google]').val(),
				mobile_no: mobile_no,
				email_id: email_id,
				},
			success:function(data, textStatus, jqXHR) {

				obj.closest('.tab-pane').find('input[type=text], textarea').each(function() {
					//console.log($(this).closest('div'));
					$(this).closest('div').find('.text').text($(this).val());
				});

				parent.find('select').each(function() {
						$(this).closest('div').find('.text').text($(this).find('option:selected').text());
					});



				obj.closest('.tab-pane').find('.communication_edit_tab').show();
					obj.closest('.tab-pane').find('.text').show();
					obj.closest('.tab-pane').find('.communication_update_tab, input[type=text], .select2, textarea.form-control, .radio').hide();
					obj.hide();		


			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});	
		}	
	});



	$('.date-picker').datepicker('destroy');

	$('body').off('click', '.add_grid_row').on('click', '.add_grid_row', function() {

		$('.select_item').each(function() {
			var select = $(this);  
			if(select.data('select2')) { 
				select.select2("destroy"); 
			}
		});



		//$('.date-picker').datepicker('destroy');

		var clone = $(this).closest('tr').clone(true, true);
		clone.find('select[name=item_id], input[name=quantity]').val("");
		clone.find('input, .select2, .update_btn, .remove_row').show();
		$(this).closest('tr').after(clone);
		$('.select_item').select2();

		//$('.date-picker').datepicker('update');

		$(this).hide();
		$(this).closest('tr').find('input, .select2, .update_btn, .remove_row').show();
	  	
	  	var person_id = $(this).closest('tr').find('select[name=person_id]');
	  //select_user($(this));

	  });

	$('body').on('click', '.remove_row', function() {
	
		$(this).closest('tr').remove();

	});

	$( "select[name=state_id]" ).change(function () {

		var city;

		if($(this).attr('name') == "state_id") {
			city = $(this).closest('.row').find( "select[name=city_id]" );
		} 

		var select_val = $(this).val();
		city.empty();
		city.append("<option value=''>Select City</option>");
			$.ajax({
				 url: '{{ route('get_city') }}',
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
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
	});

	$('select[name=person_id]').each(function() {
	  $(this).prepend('<option value="0"></option>');
	  select_user($(this));
	});
  
  });

</script> 
@stop