<div class="modal-header" style="background-color: #e9ecef;">

	<h5 class="modal-title float-right"><b>Add Customer Grouping Name</b></h5>
    <a  class="close" data-dismiss="modal">&times;</a>


</div>



	{!! Form::open([

		'class' => 'form-horizontal validateform'

	]) !!}                                        

	{{ csrf_field() }}



<div class="modal-body" style="overflow-y: scroll;">

	<div class="form-body">

		<div class="form-group">

			{!! Form::label('name', 'Customer Grouping Name', array('class' => 'control-label col-md-5 required')) !!}



			<div class="col-md-12">

				{!! Form::text('name', null,['class' => 'form-control']) !!}

			</div>

		</div>

		<div class=" form-group row col-md-12">

			<div class="col-md-6">

				{!! Form::label('discount', 'Discount', array('class' => 'control-label col-md-5')) !!}

				{!! Form::select('discount',$discounts, null, ['class' => 'select_item form-control' ,'id'=> 'discount' ]) !!}

			</div>

			<div class="col-md-6">

				{!! Form::label('discount_value', 'Value', array('class' => 'control-label col-md-5')) !!}

				{!! Form::text('discount_value', null,['class' => 'form-control','disabled']) !!}

			</div>

		</div>

		<div class="form-group">						 

			{!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}

			

			<div class="col-md-12">

				{!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}

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



	$('select[name=discount]').on('change',function(){

          var discount_id = $(this).val();



          $.ajax({

			url: '{{ route('discount_search') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				discount_id: discount_id          

				},

			success:function(data, textStatus, jqXHR) {

				$('input[name=discount_value]').val(data.discount_value.value);

				}

			});



	});

	$('.validateform').validate({

		errorElement: 'span', //default input error message container

		errorClass: 'help-block', // default input error message class

		focusInvalid: false, // do not focus the last invalid input

		rules: {

			wms_division_id: { required: true },

			name: { 

				required: true,

				remote: {

						url: '{{ route('customer_grouping_name') }}',

						type: "post",

						data: {

						 _token :$('input[name=_token]').val()

						}

					}

			},                

		},



		messages: {

			//wms_division_id: { required: "Wms Division Name is required." },

			name: { required: "Customer Group Name is required.", remote: "Customer Group Name is already exists!" },                

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

			url: '{{ route('customer_grouping.store') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				name: $('input[name=name]').val(),

				discount_value:$('input[name=discount_value]').val(),

				description: $('textarea[name=description]').val()                

				},

			success:function(data, textStatus, jqXHR) {



				call_back(`<tr role="row" class="odd">

					<td>

						<input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">

						<label for="`+data.data.id+`"><span></span></label>

					</td>

					<td>`+data.data.name+`</td>

					<td>`+data.data.discount_value+`</td>

					<td>`+data.data.description+`</td>

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