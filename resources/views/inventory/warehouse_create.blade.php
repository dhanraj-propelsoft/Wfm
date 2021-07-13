<div class="modal-header" style="background-color: #e9ecef;">

	<h5 class="modal-title float-right"><b>Add Warehouse</b></h5>
	<a  class="close" data-dismiss="modal">&times;</a>


</div>





{!! Form::open(['class' => 'form-horizontal validateform']) !!}

{{ csrf_field() }}



<div class="modal-body" style="overflow-y: scroll;">

	<div class="form-body">



		<input type="hidden" value="{{$id}}" name="business_id" >



		<div class="row">

			<div class="col-md-6 search_container"> 

				{{ Form::label('employee_id', 'Employee', array('class' => 'control-label required')) }}

				{{ Form::select('employee_id', $employees, null, ['class' => 'form-control', 'id' => 'employee_id']) }}

			<div class="content"></div>

			</div>

			<div class="col-md-6">

				<div class="form-group">

				{!! Form::label('placename', 'Warehouse Name', ['class' => 'control-label required']) !!}

				{!! Form::label('', '*', ['class' => 'control-label text-danger']) !!}

				{!! Form::text('placename', null, ['class' => 'form-control']) !!}

				</div>

			</div>

		</div>



		<div class="row" style = "display:none;">

			<div class="col-md-6">

				<div class="form-group">

				{!! Form::label('address_type', 'Address Types', ['class' => 'control-label', 'style' => 'display:none']) !!}



				{!! Form::select('address_type', $businessaddresstype , null, ['class' => 'select_item form-control']) !!}

				</div>

			</div>  

		</div>



		<div class="row">

			<div class="col-md-6">

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

			</div>                                  

		</div>



		<div class="row">

			<div class="col-md-6">

				<div class="form-group">

					{!! Form::label('email_address', 'Email', ['class' => 'control-label']) !!}

					{!! Form::text('email_address', null, ['class' => 'form-control']) !!}

				</div>

			</div>

				

			<div class="col-md-6">

				<div class="form-group">

					{!! Form::label('web_address', 'Web Address', ['class' => 'control-label']) !!}

					{!! Form::text('web_address', null, ['class' => 'form-control']) !!}

				</div>

			</div>

		</div>



		<div class="row">

			<div class="col-md-6">

				<div class="form-group">				

				{!! Form::label('state', 'State', ['class' => 'control-label']) !!}



				{!! Form::select('state', $state, null, ['class' => 'select_item form-control']); !!}

				</div>

			</div>

			

			<div class="col-md-6">

				<div class="form-group">				

				{!! Form::label('city', 'District / Village / City', ['class' => 'control-label']) !!}



				{!! Form::select('city', [''=>'Select City'], null, ['class' => 'select_item form-control']); !!}

				</div>

			</div>			

		</div>



		<div class="row">



			<div class="col-md-6">

				<div class="form-group">

				{!! Form::label('address', 'Address', ['class' => 'control-label']) !!}

				{!! Form::text('address', null, ['class' => 'form-control']) !!}

				</div>

			</div>			

				

			<div class="col-md-6">

				<div class="form-group">

				{!! Form::label('pin', 'PIN', ['class' => 'control-label']) !!}

				{!! Form::text('pin', null, ['class' => 'form-control numbers']) !!}

				</div>

			</div>			

		</div>



		<div class="row">

			<div class="col-md-6">

				<div class="form-group">

				{!! Form::label('landmark', 'Landmark', ['class' => 'control-label']) !!}

				{!! Form::text('landmark', null, ['class' => 'form-control']) !!}

				</div>

			</div>

			<div class="col-md-6">

				<div class="form-group">

				{!! Form::label('google', 'Google Location', ['class' => 'control-label']) !!}

				{!! Form::text('google', null, ['class' => 'form-control']) !!}

				</div>

			</div>

		</div>



	</div>



	<div class="modal-footer" style="background-color: #e9ecef;">

		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

		<button type="submit" class="btn btn-success">Submit</button>

	</div>



{!! Form::close() !!}

								

<script>





var current_select_item = null;



$(document).ready(function() {



	 basic_functions();



	$( "select[name=state]" ).on('change', function () {

	var city = $( "select[name=city]" );

	var area = $( "select[name=area]" );

	var select_val = $(this).val();

	city.empty();

		city.append("<option value=''>Select City</option>");

	if(select_val != "") {

	$('.loader_wall_onspot').show();

		$.ajax({

			 url: "{{ route('get_city') }}",

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

					$('.loader_wall_onspot').hide();

				},

			 error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

				}

			});

	}

  });



	$('select[name=people]').each(function() {

			$(this).prepend('<option value="0"></option>');

			select_user($(this));

		});



});



$('.validateform').validate({

			errorElement: 'span', //default input error message container

			errorClass: 'help-block', // default input error message class

			focusInvalid: false, // do not focus the last invalid input

			rules: {

				placename: {

					required: true,

					remote: {

						url: '{{ route('check_warehouse_name') }}',

						type: "post",

						data: {

						 _token :$('input[name=_token]').val(),

						 business_id :$('input[name=business_id]').val()						 

						}

					}

				},

				mobile_no: {

					required: true

				}

			},



			messages: {

				placename: {

					required: "Warehouse Name is required.",

					remote: "Warehouse Name is already exists!"

				},

				mobile_no: {

					required: "Mobile No is required."

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



				$.ajax({

				 url: '{{ route('warehouse.store') }}',

				 type: 'post',

				 data: {

					_token: '{{ csrf_token() }}',

					placename: $('input[name=placename]').val(),

					employee_id: $('select[name=employee_id]').val(),

					city_id: $('select[name=city]').val(),

					pin: $('input[name=pin]').val(),

					landmark: $('input[name=landmark]').val(),

					google: $('input[name=google]').val(),

					mobile_no: $('input[name=mobile_no	]').val(),

					contact_person_id: $('select[name=people]').val(),

					phone: $('input[name=phone]').val(),

					email_address: $('input[name=email_address]').val(),

					web_address: $('input[name=web_address]').val(),

					address: $('input[name=address]').val(),

					business_id: $('input[name=business_id]').val(),                 

					},

				 success:function(data, textStatus, jqXHR) {



					call_back(`<tr role="row" class="odd">

							<td>`+data.data.placename+`</td>

							<td>`+data.data.person+`</td>

							<td>`+data.data.mobile_no+`</td>

							<td>`+data.data.email_address+`</td>

							<td>`+data.data.address+`</td>

							<td>

                                <label class="grid_label badge badge-success status">Active</label>

                                <select style="display:none" id="`+data.data.id+`" class="active_status form-control">

                                    <option value="1">Active</option>

                                    <option value="0">In-active</option>

                                </select>

                            </td>

							

							<td>							

							<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;

							<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>

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

