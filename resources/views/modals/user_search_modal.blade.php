<div class="bs-modal-lg modal fade search_user_modal"
 tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title">Search User</h4>
	  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
	  {!! Form::open(['class' => 'form-horizontal', 'id' => 'search_user']) !!}
		{{ csrf_field() }}
	  <div class="modal-body">
	  <div class="alert alert-danger" style="margin-bottom: 5px; padding: 5px;" id="errorlist"></div>
		<div class="form-body">
						<div class="row">
							<div class="col-md-6">
							  	<div class="form-group"> 
							  		{!! Form::text('first_name', null , ['class' => 'form-control', 'placeholder' => 'User Name']) !!} 
							  	</div>
							</div>
							
							<div class="col-md-6">
							  <div class="form-group"> 
							  	{!! Form::text('crm_code', null, ['class' => 'form-control', 'placeholder' => 'Propel Id']) !!} 
							  </div>
							</div>			
				  		</div>

					  	<div class="row">
							<div class="col-md-6">
							  <div class="form-group"> 
							  	{!! Form::text('mobile_no', null , ['class' => 'form-control', 'placeholder' => 'Mobile Number']) !!} </div>
							</div>
							<!--/span-->
							<div class="col-md-6">
							  <div class="form-group"> 
							  	{!! Form::text('email_address', null, ['class' => 'form-control', 'placeholder' => 'Email Address']) !!} </div>
							</div>
							<!--/span-->
					  	</div>

					  	<div class="row">
							<div class="col-md-6">
							  <div class="form-group"> 
							  	{!! Form::text('aadhar_no', null , ['class' => 'form-control', 'placeholder' => 'Aadhar Number']) !!} </div>
							</div>
							<!--/span-->
							<div class="col-md-6">
							  <div class="form-group"> 
							  	{!! Form::text('pan_no', null, ['class' => 'form-control', 'placeholder' => 'PAN Number']) !!} </div>
							</div>
							<!--/span-->
					  	</div>

						<div class="row">
							<div class="col-md-6">
							  <div class="form-group"> 
							  	{!! Form::text('passport_no', null , ['class' => 'form-control', 'placeholder' => 'Passport Number']) !!} </div>
							</div>
							<!--/span-->
							<div class="col-md-6">
							  <div class="form-group"> 
							  	{!! Form::text('license_no', null, ['class' => 'form-control', 'placeholder' => 'License Number']) !!} </div>
							</div>
							<!--/span-->
						  	</div>
						</div>
	 		 </div>
	  

	  		<div style="display: block; justify-content: initial;" class="modal-footer col-md-12">
				<!-- <a style="color: #007bff; cursor: pointer; display: none;" class="float-left add_global_item">Add New Item</a> -->

				<!-- <button type="button" class=" btn btn-success add_item" id="add_user_container"> Add New</button> -->
				<button type="button" class="float-right btn default" id ="clear">Reset</button>

				<!-- <button type="button" class="float-right btn default" data-dismiss="modal">Close</button> -->
				 <button type="submit" class="float-right btn btn-danger">Search</button>
			</div>
	  {!! Form::close() !!}

		<table style="display: none;padding-top: 10px;" class="table result">
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
<script type="text/javascript">

$('.search_user_modal').on('hidden.bs.modal', function(e){
   $('#search_user').closest('.search_user_modal').find('.result tbody').html("");
   $('#search_user').closest('.search_user_modal').find('.modal-title').text("Search User");
   $('#search_user')[0].reset();
}) ;

$("input:text").on('focus', function() {
  $('#search_user').closest('.search_user_modal').find('#errorlist').hide();
  $('#search_user').closest('.search_user_modal').find('#errorlist').text("");
});

	$('body').on('click', '#add_user_container', function() {
		  	$('.search_user_modal').modal('hide');
		  	add_new_link();
		});

$('body').on('click', '#user_detailed_search', function() {
	  $('.search_user_modal').modal('show');
	  $('#search_user').closest('.search_user_modal').find('.result tbody').html("");
		  $('#search_user').closest('.search_user_modal').find('.modal-title').text("Search User");
		  $('#search_user')[0].reset();
		  $('#search_user').show();
	  current_select_item = $(this).closest('.search_container').find('select.person_id');
	});

$('body').on('click', '#clear', function() {
			$('#search_user')[0].reset();
		  	$('#search_user').closest('.search_user_modal').find('.result tbody').html("");
		});


  $('#search_user').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				mobile_no: {
				  number: true,
					
					maxlength:10
				},
				email_address: {
				  email:true,
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
				var first_name = $('.search_user_modal input[name=first_name]').val();
				var crm_code = $('.search_user_modal input[name=crm_code]').val();
			  	var mobile_no = $('.search_user_modal input[name=mobile_no]').val();
			  	var email = $('.search_user_modal input[name=email_address]').val();
			  	var pan_no = $('.search_user_modal input[name=pan_no]').val();
			  	var aadhar_no = $('.search_user_modal input[name=aadhar_no]').val();
			  	var license_no = $('.search_user_modal input[name=license_no]').val();
			  	var passport_no = $('.search_user_modal input[name=passport_no]').val();

			   if(mobile_no == "" && email == "" &&  pan_no == ""  && aadhar_no == ""  && license_no == ""  && passport_no == "" &&  first_name == "" && crm_code == "") {
				$('#search_user').closest('.search_user_modal').find('#errorlist').text("Fill any field to search!");
				$('#search_user').closest('.search_user_modal').find('#errorlist').show();
			   } else {

			   $('.loader_wall_onspot').show();
				$.ajax({
				 url: "{{ route('advanced_user_search') }}",
				 type: 'post',
				 data: {
				  	_token: '{{ csrf_token() }}',
				  	first_name : first_name,
				  	crm_code : crm_code,
				 	email: email,
				  	mobile_no: mobile_no,
				  	pan_no: pan_no,
				  	aadhar_no: aadhar_no,
				  	license_no: license_no,
				  	passport_no: passport_no
				  },
				  success:function(data, textStatus, jqXHR) {
					//$('#search_user').hide();
                          
					var html = "";
					var button = `<button type="button" class=" btn btn-success add_item" id="add_user_container" style="    float: right;"> Add New</button>`;
                       $('#search_user').closest('.search_user_modal').find('.result tbody').empty();
				if(data.length > 0){	

					for(var i in data) {


					  html += `<tr>
					  
					 <td><b>`+data[i].first_name+ ` , `+data[i].mobile_no+ `, `+data[i].city+ `</b>`;

					  if($('.search_user_modal input[name=crm_code]').val() != "") {
						html += `<br>`+crm_code;
					  }

					  if($('.search_user_modal input[name=mobile_no]').val() != "") {
						html += `<br>`+mobile_no;
					  }

					  if($('.search_user_modal input[name=email_address]').val() != "") {
						html += `<br>`+email;
					  }
					  if($('.search_user_modal input[name=aadhar_no]').val() != "") {
						html += `<br>`+aadhar_no;
					  }
					  if($('.search_user_modal input[name=passport_no]').val() != "") {
						html += `<br>`+passport_no;
					  }
					  if($('.search_user_modal input[name=license_no]').val() != "") {
						html += `<br>`+license_no;
					  }
					  if($('.search_user_modal input[name=pan_no]').val() != "") {
						html += `<br>`+pan_no;
					  }

					   html += `</td><td><button data-id="`+data[i].id+`" data-name="`+data[i].first_name+ ` `+data[i].last_name+`" data-mobile="`+mobile_no+`" style="padding: 3px;" class="btn btn-success float-right select_user">Select</button></td></tr>`;

					}

					html += `<tr><td><button type="button" class=" btn btn-success add_item" id="add_user_container"> Add New</button></td><td><button type="button" class="float-right btn default" data-dismiss="modal">Close</button></td></tr>`;

				}
				else{

					
					$('#search_user').closest('.search_user_modal').find('.result tbody').text("No results found..");
					$('#search_user').closest('.search_user_modal').find('.result tbody').append(button);
					
				}

					$('#search_user').closest('.search_user_modal').find('.result tbody').append(html);
					$('#search_user').closest('.search_user_modal').find('.result').show();
					//$('#search_user').closest('.search_user_modal').find('.modal-title').text("Search result..");
					$('.loader_wall_onspot').hide();
				  },
				 error:function(jqXHR, textStatus, errorThrown) {
				  //alert("New Request Failed " +textStatus);
				  }
				});

			   }
			}
		});

</script>
@stop
