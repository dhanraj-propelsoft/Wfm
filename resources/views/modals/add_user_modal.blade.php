@section('head_links')
@parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/dropzone/dropzone.css') }}">
<style>
#select2-state-container
{
	background-color: yellow;

}
#select2-city-container
{
	background-color: yellow;
	
}
</style>
@stop
<div class="bs-modal-lg modal fade add_user_modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header" style="background-color: #e9ecef;">
		<h4 class="modal-title float-right">Add/Edit Person</h4>
		<a type="button" class="close" data-dismiss="modal">&times;</a>
	  </div>
	  {!! Form::open(['class' => 'form-horizontal', 'id' => 'add_user']) !!}
	  {{ csrf_field() }}
	  <div class="modal-body"  style="overflow-y: scroll;height:450px;">
		<div class="alert alert-danger" style="margin-bottom: 5px; padding: 5px;" id="errorlist"></div>
		<div class="form-body">
		  <div class="row">
		  	<div class="col-md-3 customer_type" > 
				{{ Form::label('customer', 'User type', array('class' => 'control-label required')) }}<br>
				<div class="custom-panel">
					<input id="business_type" type="radio" name="customer_type_name" checked="checked" value="1" />
					<label for="business_type" class="custom-panel-radio"><span></span>Business</label>
					<input id="people_type" type="radio" name="customer_type_name"  value="0" />
					<label for="people_type" ><span></span>People</label>
						
				</div>
			</div>
			<div class="col-md-2">
			  	<div class="form-group"> 
				  	{!! Form::label('mobile_no', 'Mobile Number', ['class' => 'control-label required']) !!}
					
					{!! Form::text('mobile_no', null, ['class' => 'form-control numbers','style' => 'background-color: yellow;']) !!}
				</div>
			</div>
			<div class="col-md-1">
				<div class="form-group"> 
					{!! Form::label('title', 'Title', ['class' => 'control-label']) !!}
					
					{!! Form::select('title',$title, null, ['class' => 'select_item form-control' ,'id'=> 'title' ]) !!} 
				</div>
			</div>
			<div class="col-md-4">
			  	<div class="form-group"> 
				  	{!! Form::label('first_name', 'First/Business Name', ['class' => 'control-label required']) !!}
					
					{!! Form::text('first_name', null, ['class' => 'form-control ','id' => 'first','style' => 'background-color: yellow;']) !!}
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="form-group"> 
					{!! Form::label('last_name', 'Last Name', ['class' => 'control-label ']) !!}
				
					{!! Form::text('last_name', null, ['class' => 'form-control ','id' => 'last_name']) !!} 
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group"> 
					{!! Form::label('gst_no', 'GSTIN', ['class' => 'control-label']) !!}
						
					{!! Form::text('gst_no', null, ['class' => 'form-control','id' =>'gst']) !!}
				</div>
			</div>
			<div class="col-md-6">
			  <div class="form-group"> 
				{!! Form::label('display_name', 'Display Name', ['class' => 'control-label']) !!}
				
				{!! Form::text('display_name', null, ['class' => 'form-control','id' => 'display_name']) !!} </div>
			</div>
		  </div>
		  <div class="row">
			<!-- <div class="col-md-6">
			  	<div class="form-group"> 
				  	{!! Form::label('mobile_no', 'Mobile Number', ['class' => 'control-label required']) !!}
					
					{!! Form::text('mobile_no', null, ['class' => 'form-control numbers']) !!}
				</div>
			</div> 
			<div class="col-md-6">
			  <div class="form-group"> 
				{!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}

				{!! Form::text('phone', null, ['class' => 'form-control numbers']) !!} 
			</div>
			</div>-->
		  </div>
		  <div class="row">
			<div class="col-md-6">
			  <div class="form-group"> {!! Form::label('email_address', 'Email', ['class' => 'control-label']) !!}
				
				{!! Form::text('email_address', null, ['class' => 'form-control']) !!} </div>
			</div>

			<!-- This field is not there in DB - peoples table -->

			<!-- <div class="col-md-6">
			  <div class="form-group"> {!! Form::label('web_address', 'Web Address', ['class' => 'control-label']) !!}
				
				{!! Form::text('web_address', null, ['class' => 'form-control']) !!} </div>
			</div> -->

			<div class="col-md-6">
			  <div class="form-group"> {!! Form::label('max_credit_limit', 'Credit Limit', ['class' => 'control-label']) !!}
				
				{!! Form::text('max_credit_limit', null, ['class' => 'form-control numbers','placeholder'=>'Enter Amount']) !!} </div>
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
					  <div class="form-group"> {!! Form::textarea('billing_address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40','style' => 'background-color: yellow;']) !!} </div>
					</div>
				  </div>
				  <div class="row">
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::select('billing_state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} </div>
					</div>
					<div class="col-md-6">
					  <div class="form-group"> {!! Form::select('billing_city_id', ['' => 'Select City'], null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!} </div>
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
		<button type="button" class="btn default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-danger form-update">Submit</button>
	  </div>
	  {!! Form::close() !!}
	  <table style="display: none;" class="table result">
		<tbody>
		</tbody>
	  </table>
	</div>
	<!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- Modal Ends --> 
@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<script type="text/javascript">

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

$('.add_user_modal').on('hidden.bs.modal', function(e){ 
   $('#add_user')[0].reset();
}) ;

 		$('#first').keyup(function(){
			//alert();
			var value = $(this).val();
		
			
			$('input[name=display_name]').val(value);
	
		});

	
		$('#last_name').keyup(function(){
			
			var value = $(this).val();
			var first_name = $('#first').val();
		
			
			$('#display_name').val( first_name+" "+ value);
	
		});
		$('input[name=mobile_no]').blur(function(){
			//alert();
			var data = $(this).val();
			var user_type = $('input[name=customer_type_name]:checked').val();
			
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

					$('.add_new_customer').modal('show');
					$('.add_new_customer').find('.show_message').text(data.message);

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
						$('select[name=billing_state_id]').val(data.state_id);
						$('select[name=billing_city_id]').val(data.data.city_id);
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

	$('input[name=gst_no]').blur(function(){
		
		var data = $('input[name=gst_no]').val();
		var user_type = $('input[name=customer_type_name]:checked').val();
		
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

		},
		error:function()
		{

		}


		});
		}
		

	});


/*$('.add_user_modal').find( "select[name=title], input[name=first_name], input[name=last_name]").on('change', function() {
  var obj = $(this);
  
  var title = ($('.add_user_modal').find( "select[name=title]").val() != "") ? $('.add_user_modal').find( "select[name=title]").find("option:selected").text() : "";

  var first_name = ($('.add_user_modal').find( "input[name=first_name]").val() != "") ? " " + $('.add_user_modal').find( "input[name=first_name]").val() : "";

  var last_name = ($('.add_user_modal').find( "input[name=last_name]").val() != "") ? " " + $('.add_user_modal').find( "input[name=last_name]").val(): "";
  $( "input[name=display_name]").val($.trim(title + first_name + last_name));
  
});*/

/*$('.add_user_modal').find( "input[name=same_billing_address]").on('change', function() {
  if($(this).is(":checked")) {
	$(this).closest('.shipping_address').find('input:not([type=checkbox]), select, textarea').prop('disabled', true);
  } else {
	$(this).closest('.shipping_address').find('input, select, textarea').prop('disabled', false);
  }
});*/

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

$('.add_user_modal').find( "select[name=billing_state_id], select[name=shipping_state_id]" ).each(function () {
  $(this).on('change', function () {
	var obj = $(this);
	var select_val = obj.val();
	var city;
	if(obj.attr('name') == "billing_state_id") {
	  city = $( "select[name=billing_city_id]" );
	} else if(obj.attr('name') == "shipping_state_id") {
	  city = $( "select[name=shipping_city_id]" );
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

	$('body').on('click', '#user_detailed_add', function() {
	  
	  $('.add_user_modal').modal('show');
	  $('#search_user').closest('.search_user_modal').find('.result tbody').html("");
		  $('#add_user').closest('.search_user_modal').find('.modal-title').text("Search User");
		  $('#add_user')[0].reset();
		  $('#add_user').show();
	  current_select_item = $(this).closest('.search_container').find('select.person_id');
	});


  });

	/*$( "select[name=group_name]" ).on('change',function(){
		var id =$(this).find("option:selected").val();
		  $.ajax({
 			url: '{{ route("people.update") }}',
 			type: 'post',
 		data: {
			_token:'{{csrf_token()}}',
		 	_method:'PATCH',
			group_id:id,
		  },
	success:function(data, textStatus, jqXHR) { 
 
}

});
	})*/
 $('#add_user').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				first_name: {
				  required: true,
				},
				display_name: {
				  required: true,
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
				first_name: {
				  required: "Name is required"
				},
				display_name: {
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
				$.ajax({
				 url: "{{ route('advanced_user_add') }}",
				 type: 'post',
				 data: {
				  _token: '{{ csrf_token() }}',
				  type: $('input[name=customer_type_name]:checked').val(),
				  title_id: $('.add_user_modal select[name=title]').val(),
				  first_name: $('.add_user_modal input[name=first_name]').val(),
				  last_name: $('.add_user_modal input[name=last_name]').val(),
				  display_name: $('.add_user_modal input[name=display_name]').val(),
				  business_name: $('.add_business_modal input[name=first_name]').val(),
				  mobile_no: $('.add_user_modal input[name=mobile_no]').val(),
				  phone: $('.add_user_modal input[name=phone]').val(),
				  email: $('.add_user_modal input[name=email_address]').val(),
				  max_credit_limit: $('.add_user_modal input[name=max_credit_limit]').val(),
				  web_address: $('.add_user_modal input[name=web_address]').val(),
				  same_billing_address: $('.add_user_modal textarea[name=same_billing_address]').val(),
				  billing_address: $('.add_user_modal textarea[name=billing_address]').val(),
				  billing_state_id: $('.add_user_modal select[name=billing_state_id]').val(),
				  billing_city_id: $('.add_user_modal select[name=billing_city_id]').val(),
				  billing_pin: $('.add_user_modal input[name=billing_pin]').val(),
				  billing_google: $('.add_user_modal input[name=billing_google]').val(),
				  shipping_address: $('.add_user_modal textarea[name=shipping_address]').val(),
				  shipping_state_id: $('.add_user_modal select[name=shipping_state_id]').val(),
				  shipping_city_id: $('.add_user_modal select[name=shipping_city_id]').val(),
				  shipping_pin: $('.add_user_modal input[name=shipping_pin]').val(),
				  shipping_google: $('.add_user_modal input[name=shipping_google]').val(),
				  pan_no: $('.add_user_modal input[name=pan_no]').val(),
				  gst_no: $('.add_user_modal input[name=gst_no]').val(),
				  payment_mode_id: $('.add_user_modal select[name=payment_mode_id]').val(),
				  person_type:$('.search_container').find('input[name=account_person_type_id]:checked').val(),
				  term_id: $('.add_user_modal select[name=term_id]').val()
				  },
				success:function(data, textStatus, jqXHR) {

					user_file_upload.on("sending", function(file, xhr, response) {
						response.append("id", data.data.id);
					});

					user_file_upload.processQueue();
					$('#add_user')[0].reset();
					$('.loader_wall_onspot').hide();
					current_select_item.append('<option value="'+data.data.id+'">'+data.data.name+'</option>');
					current_select_item.val(data.data.id);
					current_select_item.trigger("change");
					$('.add_user_modal').modal('hide');
				  },
				 error:function(jqXHR, textStatus, errorThrown) { }
				});
			}
		});

</script> 
@stop