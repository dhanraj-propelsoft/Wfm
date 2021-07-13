<div class="modal-header" style="background-color: #e9ecef;">

	<h5 class="modal-title float-right"><b>Add Specification</b></h5>
    <a  class="close" data-dismiss="modal">&times;</a>


</div>



	{!! Form::open([

		'class' => 'form-horizontal validateform'

	]) !!}                                        

	{{ csrf_field() }}



<div class="modal-body" style="overflow-y: scroll;">

	<div class="form-body">

	<div class="form-row">

    <div class="form-group col-md-6">

      <label for="inputEmail4">Type</label>

      {!! Form::select('type',$type , null, ['class' => 'form-control select_item']) !!}

    </div>

    <div class="form-group col-md-6">

      <label for="specification">Specification</label>

      <input type="text" class="form-control" name="specification">

    </div>

  </div>

  <div class="form-row">

    <div class="form-group col-md-6">

      <label for="list">List values?</label>

      <select class="form-control" id="" name="list">

        <option value="1">yes</option>

        <option value="0">no</option>

      </select>

    </div>

  </div>

  <div class="form-group">

    <label for="description">Description</label>

    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}

  </div>

	</div>

</div>



<div class="modal-footer" style="background-color: #e9ecef;">                                            

	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

	<button type="submit" class="btn btn-success">Save</button>

</div>

	

{!! Form::close() !!}

<script>

	$('.validateform').validate({

		errorElement: 'span', //default input error message container

		errorClass: 'help-block', // default input error message class

		focusInvalid: false, // do not focus the last invalid input

		rules: {

			specification: { required: true },

			type: { required: true },

			specification: { 

				required: true,

				remote: {

						url: '{{ route('specification_master.vehicle_spec_name') }}',

						type: "post",

						data: {

						 _token :$('input[name=_token]').val()

						}

					}

			},                

		},



		messages: {

			specification: { required: "Specification is required." },

			type: {type: "Type is required"},

			specification: { required: "Specification Name is required.", remote: "Specification Name is already exists!" },                

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

			url: '{{ route('specification_master.store') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				type: $('select[name=type]').val(),

				specification: $('input[name=specification]').val(),

				list: $('select[name=list]').val(),

				description: $('textarea[name=description]').val()             

				},

			success:function(data, textStatus, jqXHR) {

                     //console.log(data);

				call_back(`<tr role="row" class="odd">

					<td>

						<input id="`+data.data.id+`" class="item_check" name="unit" value="`+data.data.id+`" type="checkbox">

						<label for="`+data.data.id+`"><span></span></label>

					</td>

					<td>`+data.data.specification+`</td>

					<td>`+data.data.type+`</td>

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

