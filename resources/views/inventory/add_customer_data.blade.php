
<div class="modal-header" style="background-color: #e9ecef;">
	<h5 class="modal-title float-right"><b>Add User</b></h5>
    <a type="button" class="close" data-dismiss="modal">&times;</a>

</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

 <div class="modal-body" style="overflow-y: scroll;height:450px;">
		<div class="alert alert-danger" style="margin-bottom: 5px; padding: 5px;" id="errorlist"></div>
		<div class="form-body add_business_modal">
		  <div class="row">
		  	<div class="col-md-3 customer_type" > 
				{{ Form::label('customer', 'User type', array('class' => 'control-label required')) }}<br>
				<div class="custom-panel">
					<input id="business_type" type="radio" name="customer"  checked="checked" value="1" />
					<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
					<input id="people_type" type="radio" name="customer" value="0" />
					<label for="people_type" ><span></span>People</label>
					{{ Form::hidden('type',$type) }}
				</div>
			</div>
			
		  	<div class="col-md-2">
			  <div class="form-group"> {!! Form::label('mobile_no', 'Mobile Number', ['class' => 'control-label required']) !!}
				
				{!! Form::text('mobile_no', null, ['class' => 'form-control numbers','style' => 'background-color: yellow;','id' => 'mobile_no']) !!} </div>
			</div>
			<div class="col-md-2">
				<div class="form-group"> 
					{!! Form::label('title_id', 'Title', ['class' => 'control-label']) !!}
					
					{!! Form::select('title_id',$title, null, ['class' => 'select_item form-control' ,'id'=> 'title' ]) !!} 
				</div>
			</div>
			<div class="col-md-3">
			  	<div class="form-group"> 
				  	{!! Form::label('first_name', 'First/Business Name', ['class' => 'control-label required','id' =>'f_name']) !!}
					
					{!! Form::text('first_name', null, ['class' => 'form-control ','id' => 'first','style' => 'background-color: yellow;']) !!}
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="form-group"> 
					{!! Form::label('last_name', 'Last Name', ['class' => 'control-label ']) !!}
				
					{!! Form::text('last_name', null, ['class' => 'form-control ']) !!} </div>
			</div>
			<!-- <div class="col-md-6">
			  <div class="form-group"> 
				{!! Form::label('display_name', 'Display Name \ Business Name', ['class' => 'control-label required']) !!}
				
				{!! Form::text('display_name', null, ['class' => 'form-control']) !!} </div>
			</div> -->
		  </div>
		  <div class="row">
			<!-- <div class="col-md-6">
			  <div class="form-group"> {!! Form::label('mobile_no', 'Mobile Number', ['class' => 'control-label required']) !!}
				
				{!! Form::text('mobile_no', null, ['class' => 'form-control numbers']) !!} </div>
			</div> -->
			<div class="col-md-3">
				<div class="form-group"> 
					{!! Form::label('gst_no', 'GSTIN', ['class' => 'control-label']) !!}
					
					{!! Form::text('gst_no', null, ['class' => 'form-control','id' => 'gst']) !!} 
				</div>
			</div>
			<div class="col-md-4">
			  	<div class="form-group"> 
					{!! Form::label('contact', 'Contact', ['class' => 'control-label']) !!}
					
					{!! Form::text('contact', null, ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-5">
			  	<div class="form-group"> 
					{!! Form::label('display_name', 'Display Name', ['class' => 'control-label']) !!}
					
					{!! Form::text('display_name', null, ['class' => 'form-control']) !!}
				</div>
			</div>
			<!-- <div class="col-md-6">
			  <div class="form-group"> 
				{!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
			
				{!! Form::text('phone', null, ['class' => 'form-control numbers']) !!} 
			</div>
			</div> -->
		  </div>
		  <div class="row">
			<div class="col-md-6">
			  	<div class="form-group"> 
				  	{!! Form::label('email_address', 'Email', ['class' => 'control-label']) !!}
					
					{!! Form::text('email_address', null, ['class' => 'form-control']) !!} 
				</div>
			</div>

			<!-- This field is not there in DB - peoples table -->

			<!-- <div class="col-md-6">
			  <div class="form-group"> {!! Form::label('web_address', 'Web Address', ['class' => 'control-label']) !!}
				
				{!! Form::text('web_address', null, ['class' => 'form-control']) !!} </div>
			</div> -->

			<div class="col-md-6">
			 	<div class="form-group"> 
				  	{!! Form::label('max_credit_limit', 'Credit Limit', ['class' => 'control-label']) !!}
					
					{!! Form::text('max_credit_limit', null, ['class' => 'form-control numbers','placeholder'=>'Enter Amount']) !!} 
				</div>
			</div>

			<div class="col-md-6">
			  <div class="form-group"> {!! Form::label('group_name', 'Group Name', ['class' => 'control-label']) !!}
				
				{!! Form::select('group_name',$group_name, null, ['class' => 'select_item form-control' ,'id'=> 'group_name' ]) !!}  </div>
			</div>
			<div class="col-md-6">
			  <div class="form-group"> 
				{!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}

				{!! Form::text('phone', null, ['class' => 'form-control numbers']) !!} 
			</div>
			</div>
		  </div>
		  <ul class="nav nav-tabs">
			<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#address">Address</a> </li>
			<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#billing">Billing Preferences</a> </li>
			<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#tax">Other Informations</a> </li>
			<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#attachments">Attachments</a> </li>
		  </ul>
		  <div class="tab-content" style="border:1px solid #ccc; border-top: 0px; padding: 10px;">
			<div class="tab-pane active" id="address">
			  <div class="row">
				<div class="col-md-6">                	
					{{ Form::hidden('billing_id',null) }}
					{!! Form::label('billing_address', 'Billing Address', ['class' => 'control-label required']) !!}
				  <div class="row">
					<div class="col-md-12">
					  <div class="form-group"> {!! Form::textarea('billing_address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40','style' => 'background-color:yellow;']) !!} </div>
					</div>
				  </div>
				  <div class="row">
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::select('billing_state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} </div>
					</div>
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::select('billing_city_id', [], null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!} </div>
					</div>
				  </div>
				  <div class="row">
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::text('billing_pin',null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} </div>
					</div>
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::text('billing_google',null, ['class' => 'form-control', 'placeholder' => 'Google Location']) !!} </div>
					</div>
				  </div>
				</div>
				<div class="col-md-6 shipping_address"> 
					{{ Form::hidden('shipping_id',null) }}

					{!! Form::label('shipping_address', 'Shipping Address', ['class' => 'control-label']) !!}

					<div style="float: right;"> {!! Form::checkbox('same_billing_address', '1', false, ['class' => 'control-label ', 'id' => 'same_billing_address']) !!}
                     <label for="same_billing_address"><span></span>Same as Billing Address</label>
                    </div>
				  
				<div class="clearfix"></div>
				  <div class="row">
					<div class="col-md-12">
					  <div class="form-group"> {!! Form::textarea('shipping_address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!} </div>
					</div>
				  </div>
				  <div class="row">
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::select('shipping_state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state']) !!} </div>
					</div>
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::select('shipping_city_id', ['' => 'Select City'], null, ['class' => 'select_item form-control' ,'id'=> 'city'] ) !!} </div>
					</div>
				  </div>
				  <div class="row">
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::text('shipping_pin',null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!} </div>
					</div>
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::text('shipping_google',null, ['class' => 'form-control', 'placeholder' => 'Google Location']) !!} </div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="billing">
			  <div class="row">
				<div class="col-md-6">
				  <div class="form-group"> {!! Form::label('payment_method', 'Payment Method', ['class' => 'control-label']) !!}
					
					{!! Form::select('payment_mode_id', $payment, null, ['class' => 'form-control select_item']) !!} </div>
				</div>
				<div class="col-md-6">
				  <div class="form-group"> {!! Form::label('terms', 'Terms', ['class' => 'control-label']) !!}
					
					{!! Form::select('term_id', $terms, null, ['class' => 'form-control select_item']) !!} </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tax">
			  <div class="row">
				<div class="col-md-6">
				  <div class="form-group"> {!! Form::label('pan_no', 'PAN', ['class' => 'control-label']) !!}
					
					{!! Form::text('pan_no', null, ['class' => 'form-control']) !!} </div>
				</div>
				<!-- <div class="col-md-6">
				  <div class="form-group"> {!! Form::label('gst_no', 'GSTIN', ['class' => 'control-label']) !!}
					
					{!! Form::text('gst_no', null, ['class' => 'form-control']) !!} </div>
				</div> -->
			  </div>
			</div>
			<div class="tab-pane" id="attachments">
			  <div  class="dropzone" id="user_file-upload"> </div>
			</div>
		  </div>
		</div>
	  </div>

<div class="modal-footer" style="background-color: #e9ecef;">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script> 
<script type="text/javascript">
	$(document).ready(function() {
		basic_functions();
	});
var current_selection = null;
Dropzone.autoDiscover = false;

	var user_file_upload = new Dropzone("div#user_file-upload", {
		paramName: 'file',
		url: "{{route('user_file_upload')}}",
		params: {
			_token: '{{ csrf_token() }}'
		},
		dictDefaultMessage: "Drop or click to upload files",
		clickable: true,
		maxFilesize: 5, // MB
		acceptedFiles: "image/*,.xlsx,.xls,.pdf,.doc,.docx",
		maxFiles: 10,
		autoProcessQueue: false,
		addRemoveLinks: true,
		removedfile: function(file) {
			file.previewElement.remove();
		},
		queuecomplete: function() {
			user_file_upload.removeAllFiles();
		}
	});

	$("input[name=same_billing_address]").on('change', function(){
		
		var billing_address = $("textarea[name=billing_address]").val();
		var billing_state_id = $("select[name=billing_state_id]").val();
		var billing_city_id = $("select[name=billing_city_id]").val();
		var billing_pin = $("input[name=billing_pin]").val();
		var billing_google = $("input[name=billing_google]").val();

		//alert($("textarea[name=billing_address]").val(address));

		if($(this).is(':checked'))
		{
			
			$("textarea[name=shipping_address]").val(billing_address).prop('disabled',true);
			$("select[name=shipping_state_id]").val(billing_state_id).trigger('change');
			$("select[name=shipping_state_id]").prop('disabled',true);
			$("select[name=shipping_city_id]").append($("select[name=billing_city_id]").clone().contents());
			$("select[name=shipping_city_id]").val(billing_city_id).trigger('change');
			$("select[name=shipping_city_id]").prop('disabled',true);
			$("input[name=shipping_pin]").val(billing_pin).prop('disabled',true);
			$("input[name=shipping_google]").val(billing_google).prop('disabled',true);
		}
		else {
			$(".shipping_address").find("input,textarea,select").val("").prop('disabled',false);
			$("select[name=shipping_state_id]").val("").trigger("change");
			$("select[name=shipping_city_id]").empty();
			$("select[name=shipping_city_id]").append("<option value=''>Select City</option>");
			$("select[name=shipping_city_id]").val("").trigger("change");
		}
	});


		
			
		/*$('select[name=billing_state_id], select[name=shipping_state_id]').each(function (e) {
		  	
		  	$(this).on('change', function () {
		  	
			var obj = $(this);
			var select_val = obj.val();
			console.log(select_val);
			var city;
			if(obj.attr('name') == "billing_state_id") {
			  city = $('.add_business_modal').find( "select[name=billing_city_id]" );
			} else if(obj.attr('name') == "shipping_state_id") {
			  city = $('.add_business_modal').find( "select[name=shipping_city_id]" );
			}
			city.empty();
			city.append("<option value=''>Select City</option>");

			if(select_val != "") {
				$('.loader_wall_onspot').show();

				$.ajax({
				 url: "{{ route('get_city') }}",
				 type: 'post',
				 data: {
				  _token :'{{ csrf_token() }}',
				  state: select_val
				 
				  },
				 dataType: "json",
				  async: "false",
				  success:function(data, textStatus, jqXHR) {
				  	console.log("response");
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
		*/

	$('input[name=first_name]').keyup(function(){
		//alert();
		var value = $('input[name=first_name]').val();
		
		$('input[name=display_name]').val(value);
	
	});
	 $('input[name=last_name]').keyup(function(){
			//alert();
			var value = $(this).val();
			var first_name = $('#first').val();
		
			
			$('input[name=display_name]').val( first_name+" "+ value);
	
			});

	$('#mobile_no').blur(function(){
		
		var data = $(this).val();
		var user_type = $('input[name=customer]:checked').val();
		//alert(user_type);
		if(data)
		{
			
		$.ajax({
			url : '{{ route('get_data_from_mobile_number') }}',
		type : 'get',
		data: 
		{
			data : data	,
			user_type : user_type
		},
		success:function(data)
		{
			//alert();
			console.log("data"+data);
			if(data.status == '0')
			{
				$('.add_new_customer').modal('show');
				$('.add_new_customer').find('.show_message').text(data.message);
				
				$('.add_modal_ajax_btn').css('display','none');
				$('.close_modal_ajax_btn').val('');
				$('.close_modal_ajax_btn').text('Ok');
				$('.close_modal_ajax_btn').on('click',function(){
					$('input[name=mobile_no]').val('');
					$('.add_new_customer').modal('hide');


				});

			}
			if(data.status == '1' && data.data != null)
			{
				//alert();
			

				$('.add_new_customer').modal('show');
				$('.add_new_customer').find('.show_message').text(data.message);
				$('.add_modal_ajax_btn').css('display','block');


				$('.add_modal_ajax_btn').on('click',function(){
					
					$('input[name=title]').val(data.data.salutation);
					if(data.data.business_name)
					{
						$('input[name=first_name]').val(data.data.business_name);
						$('input[name=last_name]').val(data.data.business_name);
						$('input[name=display_name]').val(data.data.business_name);

					}
					if(data.data.first_name)
					{
						$('input[name=first_name]').val(data.data.first_name);
						$('input[name=last_name]').val(data.data.last_name);
						$('input[name=display_name]').val(data.data.first_name);

					}
					if(data.data.user_type == 0)
					{
						$('#people_type').prop('checked',true);
					}
					if(data.data.user_type == 1)
					{
						$('#business_type').prop('checked',true);
							
					}
					

					

					$('input[name=email_address]').val(data.data.email_address);
					$('input[name=gst_no]').val(data.data.gst);
					$('input[name=phone]').val(data.data.mobile_no);
					$('textarea[name=billing_address]').val(data.data.address);
					$('#state').val(data.state_id).change();
					console.log("trigger state");
					var city_ids = data.data.city_id;


					var obj = $('select[name=billing_state_id]');
					var select_val = obj.val();
					console.log("select_val2"+select_val);
					var city;
					if(obj.attr('name') == "billing_state_id") {
					  city = $('.add_business_modal').find( "select[name=billing_city_id]" );
					} else if(obj.attr('name') == "shipping_state_id") {
					  city = $('.add_business_modal').find( "select[name=shipping_city_id]" );
					}
					city.empty();
					city.append("<option value=''>Select City</option>");

					if(select_val != "") {
						$('.loader_wall_onspot').show();

						$.ajax({
						 url: "{{ route('get_city') }}",
						 type: 'post',
						 data: {
						  _token :'{{ csrf_token() }}',
						  state: select_val
						 
						  },
						 dataType: "json",
						  async: "false",
						  success:function(data, textStatus, jqXHR) {
						  	console.log("response2");
							var result = data.result;
							for(var i in result) {  
							  city.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
							}
							$('.loader_wall_onspot').hide();
								console.log("city"+city_ids);
							$('select[name=billing_city_id]').val(city_ids).change();
						  },
						 error:function(jqXHR, textStatus, errorThrown) {
						  //alert("New Request Failed " +textStatus);
						  }
						});
					}
					//$('select[name=billing_state_id]').trigger('change');

				
					
					$('input[name=billing_pin]').val(data.data.pin);
					$('.add_new_customer').modal('hide');

				});

				$('.close_modal_ajax_btn').on('click',function(){
					$('input[name=mobile_no]').val('');
					$('.add_new_customer').modal('hide');


				});
				
				

			}

		},
		error:function()
		{

		}


		});

		}
		
	});


	
		  	
	$('select[name=billing_state_id], select[name=shipping_state_id]').on('change', function () 
	{
		  	
			var obj = $(this);
			var select_val = obj.val();
			console.log("select_val"+select_val);
			var city;
			if(obj.attr('name') == "billing_state_id") {
			  city = $('.add_business_modal').find( "select[name=billing_city_id]" );
			} else if(obj.attr('name') == "shipping_state_id") {
			  city = $('.add_business_modal').find( "select[name=shipping_city_id]" );
			}
			city.empty();
			city.append("<option value=''>Select City</option>");

			if(select_val != "") {
				$('.loader_wall_onspot').show();

				$.ajax({
				 url: "{{ route('get_city') }}",
				 type: 'post',
				 data: {
				  _token :'{{ csrf_token() }}',
				  state: select_val
				 
				  },
				 dataType: "json",
				  async: "false",
				  success:function(data, textStatus, jqXHR) {
				  	console.log("response1");
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
		


	function call_city()
	{
		console.log("function call");
		var obj = $('select[name=billing_state_id]');
		var select_val = obj.val();
			console.log("select_val"+select_val);
			var city;
			if(obj.attr('name') == "billing_state_id") {
			  city = $('.add_business_modal').find( "select[name=billing_city_id]" );
			} else if(obj.attr('name') == "shipping_state_id") {
			  city = $('.add_business_modal').find( "select[name=shipping_city_id]" );
			}
			city.empty();
			city.append("<option value=''>Select City</option>");

			if(select_val != "") {
				$('.loader_wall_onspot').show();

				$.ajax({
				 url: "{{ route('get_city') }}",
				 type: 'post',
				 data: {
				  _token :'{{ csrf_token() }}',
				  state: select_val
				 
				  },
				 dataType: "json",
				  async: "false",
				  success:function(data, textStatus, jqXHR) {
				  	console.log("response1");
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
	}


	$('#gst').blur(function(){
		//alert();
		var data = $('input[name=gst_no]').val();
		var user_type = $('input[name=customer]:checked').val();
		//alert(user_type);
		console.log("user_type"+user_type);
		if(data)
		{
			$.ajax({
			url : '{{ route('get_data_from_gst_number') }}',
		type : 'get',
		data: 
		{
			data : data	,
			user_type : user_type
		},
		success:function(data)
		{
			//alert();
			console.log(data);
			if(data.status == '0')
			{
				$('.add_new_customer').modal('show');
				$('.add_new_customer').find('.show_message').text(data.message);
				
				$('.add_modal_ajax_btn').css('display','none');
				$('.close_modal_ajax_btn').val('');
				$('.close_modal_ajax_btn').text('Ok');
				$('.close_modal_ajax_btn').on('click',function(){
					$('input[name=gst_no]').val('');
					$('.add_new_customer').modal('hide');


				});

			}
			if(data.status == '1' && data.data != null)
			{
					//alert();
			

				$('.add_new_customer').modal('show');
				$('.add_new_customer').find('.show_message').text(data.message);

				$('.add_modal_ajax_btn').on('click',function(){
					
					$('input[name=title]').val();
					if(data.data.business_name)
					{
						$('input[name=first_name]').val(data.data.business_name);
						$('input[name=last_name]').val(data.data.business_name);
						$('input[name=display_name]').val(data.data.business_name);

					}
					if(data.data.first_name)
					{
						$('input[name=first_name]').val(data.data.first_name);
						$('input[name=last_name]').val(data.data.last_name);
						$('input[name=display_name]').val(data.data.first_name);

					}
					

					
					$('input[name=mobile_no]').val(data.data.mobile_no);
					$('input[name=email_address]').val(data.data.email_address);
					$('input[name=gst_no]').val(data.data.gst);
					$('input[name=phone]').val(data.data.mobile_no);
					$('textarea[name=billing_address]').val(data.data.address);
					$('select[name=billing_state_id]').val(data.state_id);
					$('select[name=billing_city_id]').val(data.data.city_id);
					$('input[name=billing_pin]').val(data.data.pin);
					$('.add_new_customer').modal('hide');

				});

				$('.close_modal_ajax_btn').on('click',function(){
					$('input[name=gst_no]').val('');
					$('.add_new_customer').modal('hide');


				});
				
				

			}
			else if(data.status == '2')
			{
					

				
					
					$('input[name=title]').val();
					$('input[name=first_name]').val("");
					$('input[name=last_name]').val("");
					$('input[name=display_name]').val("");					
					$('input[name=mobile_no]').val("");
					$('input[name=email_address]').val("");
					$('input[name=gst_no]').val(data.data);
					$('input[name=phone]').val("");
					$('textarea[name=billing_address]').val();
					$('select[name=billing_state_id]').val();
					$('select[name=billing_city_id]').val();
					$('input[name=billing_pin]').val();
					$('.add_new_customer').modal('hide');

				

				
				

			}

		},
		error:function()
		{

		}


		});
		}
		

	});
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			//name: { required: true },
			
			first_name: {
				  required: true,
				},
				display_name:{
					required : true,

				},
				
				mobile_no: {
				  required: true,
				  number: true,
				  minlength:10,
				  maxlength:10
				},   
				billing_state_id :
				{
					required : true,
				},  
				billing_city_id: {
					required: true,
				},          
		},

		messages: {
			//name: { required: "Unit Name is required." },
			
			first_name: {
				  required: "Name is required"
				},
				display_name :{
					required: "Display name is required"

				},
				
				mobile_no: {
				  required: "Mobile number is required"
				}, 
				billing_state_id: {
					required : " State is required"
				}, 
				billing_city_id: {
					required : "City is required"
				},             
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
			$('.loader_wall_onspot').show();

			$.ajax({
			url: '{{ route('new_customer_data.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}', 
				type : $('input[name=customer]:checked').val(),
				//person_type : $('input[name=customer]:checked').val(),
				mobile_number : $('input[name=mobile_no]').val(),
				title_id : $('select[name=title_id]').val(), 
				first_name : $('input[name=first_name]').val(),
				last_name : $('input[name=last_name]').val(),
				pan_no : $('input[name=pan_no]').val(),
				gst_no : $('input[name=gst_no]').val(),
				display_name : $('input[name=display_name]').val(),
				contact : $('input[name=contact]').val(),

				email_address : $('input[name=email_address]').val(),
				max_credit_limit : $('input[name=max_credit_limit]').val(),
				group_name : $('select[name=group_name]').val(),
				phone : $('input[name=phone]').val(),
				billing_address : $('textarea[name=billing_address]').val(),
				billing_state_id : $('select[name=billing_state_id]').val(),
				billing_city_id : $('select[name=billing_city_id]').val(),
				billing_pin : $('input[name=billing_pin]').val(),
				billing_google : $('input[name=billing_google]').val(),
				same_billing_address : $('input[name=same_billing_address]').val(),
				shipping_address : $('textarea[name=shipping_address]').val(),
				shipping_state_id : $('select[name=shipping_state_id]').val(),
				shipping_city_id : $('select[name=shipping_city_id]').val(),
				shipping_pin : $('input[name=shipping_pin]').val(),
				shipping_google : $('input[name=shipping_google]').val(),
				payment_mode_id : $('select[name=payment_mode_id]').val(),
				term_id : $('select[name=term_id]').val(),
				person_type : $('input[name=type]').val()
				
				},
			success:function(data, textStatus, jqXHR) {
				console.log(data);
					call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.id+`" class="item_check" name="category" value="`+data.id+`" type="checkbox">
						<label for="`+data.id+`"><span></span></label>
					</td>
					<td>`+data.code+`</td>
					<td>`+data.name+`</td>
					<td>`+data.name+`</td>
					<td>`+data.mobile_no+`</td>
					<td></td>
					<td>`+data.credit_limit_value+`</td>
					<td>
						<label class="grid_label badge badge-success status">Active</label>
						<select style="display:none" id="" class="active_status form-control">
							<option value="1">Active</option>
							<option value="0">In-active</option>
						</select>
					</td>
					<td>
					<a data-id="`+data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`, `add`, data.message);

				$('.loader_wall_onspot').hide();
				
				},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>