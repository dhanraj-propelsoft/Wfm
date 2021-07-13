<div class="modal-header">
	<h4 class="modal-title float-right">Add Employee</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		<div class="form-group">
			<div class="row">
				<div class="col-md-4">
					{{ Form::label('staff_type_id', 'Employee Type', array('class' => 'control-label required')) }}
					{!! Form::select('staff_type_id',$staff_type, null, ['class' => 'select_item form-control' ]) !!}
				</div>
				<div class="col-md-4 search_container"> 
					{{ Form::label('person_id', 'Choose Person', array('class' => 'control-label required')) }}
					{{ Form::select('person_id', $people, null, ['class' => 'form-control person_id select_item', 'id' => 'person_id']) }}
					{{ Form::checkbox('account_person_type_id', 'employee', true, ['id' => 'account_person_type_id']) }}
					{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
					<div class="content"></div>
				</div>				
				<div class="col-md-4">
					{{ Form::label('employee_code', 'Employee Code', array('class' => 'control-label required')) }}
					{{ Form::text('employee_code', null, ['class'=>'form-control']) }} 
				</div>
				
			</div>
		</div>	

		<div class="form-group">
			<div class="row">
			  	<div class="col-md-4">
					<label for="title">Title</label>
					{!! Form::select('title',$title, null, ['class' => 'select_item form-control title' ]) !!}
				</div>
				<div class="col-md-4">				
					{{ Form::label('employee_first_name', 'First Name', array('class' => 'control-label required')) }}
					{{ Form::text('employee_first_name', null, ['class'=>'form-control first_name']) }} 
				</div>
			  	<div class="col-md-4">
					<label for="employee_last_name">Last Name</label>
					{{ Form::text('employee_last_name', null, ['class'=>'form-control last_name']) }}
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
			  <div class="col-md-4">
				{{ Form::label('email', 'Email', array('class' => 'control-label required')) }}
				{{ Form::text('email', null, ['class'=>'form-control email']) }} </div>
			  <div class="col-md-4">
				{{ Form::label('phone_no', 'Mobile', array('class' => 'control-label required')) }}
				{{ Form::text('phone_no', null, ['class'=>'form-control mobile']) }} 
			</div>
			<div class="col-md-4">			
				{{ Form::label('gender_id', 'Gender', array('class' => 'control-label required')) }}<br>
					@foreach($genders as $gender)
						<input type="radio" name="gender_id" class="gender" id="{{ $gender->id }}"  value="{{ $gender->id }}">
						<label for="{{ $gender->id }}"><span></span>{{$gender->name}}</label>
					@endforeach
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-4">
				<label for="blood_group_id">Blood Group</label>
				{{ Form::select('blood_group_id',$blood_groups, null, ['class' => 'form-control select_item blood_group', 'id' => 'blood_group_id']) }}</div>
			  <div class="col-md-4">
				<label for="marital_status">Marital Status</label>
				{{ Form::select('marital_status', $marital_status, null, ['class' => 'form-control select_item marital_status', 'id' => 'marital_status']) }} </div>
				<div class="col-md-4">
					{{ Form::label('joined_date', 'Joining Date', array('class' => 'control-label required')) }}
					{!! Form::text('joined_date', null,['class' => 'form-control date-picker','data-date-format' => 'dd-mm-yyyy']) !!}
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
			  <div class="col-md-4">
				{{ Form::label('branch_id', 'Branch', array('class' => 'control-label required')) }}
				{!! Form::select('branch_id',$branch, null, ['class' => 'select_item form-control' ]) !!} </div>
			  <div class="col-md-4">
				{{ Form::label('department_id', 'Department', array('class' => 'control-label required')) }}
				{!! Form::select('department_id',$department, null, ['class' => 'select_item form-control' ]) !!} </div>
			  <div class="col-md-4">
				{{ Form::label('designation_id', 'Designation', array('class' => 'control-label required')) }}
				{!! Form::select('designation_id',$designation, null, ['class' => 'select_item form-control' ]) !!} </div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
					{{ Form::label('address', 'Address', array('class' => 'control-label required')) }}
				{!! Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'rows'=>'1 ','cols'=>'40']) !!}
			</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
				{{ Form::label('state_id', 'State', array('class' => 'control-label required')) }}		  
				  	{!! Form::select('state_id',$state, null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} 
				  </div>				
				<div class="col-md-6">
					{{ Form::label('city_id', 'City', array('class' => 'control-label required')) }}
				   {!! Form::select('city_id', ['' => 'Select City'], null, ['class' => 'select_item form-control' ,'id'=> 'city' ]) !!} 
				</div>
				
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
				{{ Form::label('pin', 'PIN', array('class' => 'control-label ')) }}		  
				  	{!! Form::text('pin',null, ['class' => 'form-control', 'placeholder' => 'Pincode']) !!}
				  </div>				
				<div class="col-md-6">
					{{ Form::label('google', 'Google', array('class' => 'control-label ')) }}
				   	{!! Form::text('google',null, ['class' => 'form-control', 'placeholder' => 'Google Location']) !!} 
				</div>
				
			</div>
		</div>

	</div>	
		
	</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

<script>
	$(document).ready(function() {

		$(".parentname").hide();

		$('input[type="checkbox"]').on('change', function() {
			$('select[name=parent_department]').val("");
			$('select[name=parent_department]').trigger("change");
			
			if($(this).is(":checked")) {
				$(".parentname").show();
			} 
			else {
				$(".parentname").hide();
				$('select[name=parent_id]').val('');
			}
		});

	basic_functions();

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


	$('.side_panel').on('click', function() {
	  $('.slide_panel_bg').fadeIn();
	  $('.settings_panel').animate({ right: 0 });
	});

	$('.close_side_panel').on('click', function() {
	  $('.slide_panel_bg').fadeOut();
	  $('.settings_panel').animate({ right: "-25%" });
		});
	});
	
	$('.validateform').validate({
		//ignore: [],
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			person_id: { 
				required: true,
				remote: {
					url: '{{ route('check_employee') }}',
					type: "post",
					data: {
					 _token :$('input[name=_token]').val()
					}
				} 
			},
			
			staff_type_id: { required: true },
			employee_first_name: { required: true },
			employee_code: { required: true },
			payment_method_id: { required: true },
			gender_id: { required: true },
			phone_no: { required: true },
			email: { required: true },
			joined_date: { required: true },
			employment_type_id: { required: true },
			branch_id: { required: true },
			department_id: { required: true },
			designation_id: { required: true },
		},

		messages: {
			person_id: { 
				required: "Person is required.",
				remote: "Person already exists!" 
			},
			
			staff_type_id: { required: "Employee Type is required" },
			employee_first_name: { required: "First Name is required." },
			employee_code: { required: "Code is required." },
			payment_method_id: { required: "Payment method is required." },
			gender_id : { required: "Gender is required." },
			phone_no : { required: "Mobile No. is required." },
			email : { required: "Email Id is required." },
			joined_date : { required: "Joined Date is required." },
			employment_type_id : { required: "Job Type is required." },
			branch_id : { required: "Branch is required." },
			department_id : { required: "Department is required." },
			designation_id : { required: "Designation is required." },
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
			url: '{{ route('staff.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				staff_type_id: $('select[name=staff_type_id]').val(),
				person_id: $('select[name=person_id]').val(),
				employee_code: $('input[name=employee_code]').val(),
				title_id: $('select[name=title]').val(),
				first_name: $('input[name=employee_first_name]').val(),
				last_name: $('input[name=employee_last_name]').val(),
				email: $('input[name=email]').val(),
				phone_no: $('input[name=phone_no]').val(),
				gender_id: $('input[name=gender_id]').val(),
				blood_group_id: $('select[name=blood_group_id]').val(),
				marital_status: $('select[name=marital_status]').val(),
				

				joined_date: $('input[name=joined_date]').val(),				
				branch_id: $('select[name=branch_id]').val(),
				department_id: $('select[name=department_id]').val(),
				designation_id: $('select[name=designation_id]').val(),

				contact_person: $('input[name=contact_person]').val(),
				address: $('textarea[name=address]').val(),
				city_id: $('select[name=city_id]').val(),
				pin: $('input[name=pin]').val(),
				google: $('input[name=google]').val(),
			
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
                	<td><input id="`+data.data.id+`" class="item_check" name="employee" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label></td>
                    <td>`+data.data.name+`</td>
					<td>`+data.data.code+`</td>
					<td>`+data.data.phone_no+`</td>
					<td>`+data.data.email+`</td>
					<td>`+data.data.blood_group+`</td>
					<td>`+data.data.gender+`</td>
                   
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