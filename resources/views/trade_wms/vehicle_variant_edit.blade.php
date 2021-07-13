<div class="alert alert-success">

	{{ Session::get('flash_message') }}

</div>

<div class="modal-header" style="background-color: #e9ecef;">

	<h5 class="modal-title float-right"><b>Edit Vehicle Variant</b></h5>
    <a  class="close" data-dismiss="modal">&times;</a>


</div>



	{!!Form::model($vehicle_variant, [

		'class' => 'form-horizontal validateform'

	]) !!}



	{{ csrf_field() }}



<div class="modal-body" style="overflow-y: scroll;">

	<div class="form-body">

		{!! Form::hidden('id', null) !!}

	

		<div class="form-group">

				{!! Form::label('name', 'Vehicle Variant Name', array('class' => 'control-label col-md-5 required')) !!}



			<div class="col-md-12">

				{!! Form::text('name', null,['class' => 'form-control']) !!}

			</div>

		</div>

		

		<div class="form-group">

				{{ form::label('version','Version',['class' => 'control-label col-md-4 required']) }}

			<div class="col-md-12">

			<!-- 

				{!! Form::select('version', [], null, array('class' => 'select-tag form-control select2-hidden-accessible', 'data-date-format' => 'dd-mm-yyyy','id'=>'tags','data-select2-id'=>'10','tabindex'=>'-1', 'aria-hidden'=>'true','multiple')) !!} -->

				<select name="version" class="select-tag form-control  select2-hidden-accessible" id="tags"  multiple>

			            <option value="<?php echo $vehicle_variant->version ?>" selected="selected">{{ $vehicle_variant->version }}</option>

			            

			     </select> 

			   

			</div>

		</div>

		<!-- <div class="form-group" style="display:none;">

		 {!! Form::select('version', $version, $vehicle_variant->version , array('class' => 'select-tag form-control select2-hidden-accessible', 'data-date-format' => 'dd-mm-yyyy','id'=>'tags','data-select2-id'=>'10','tabindex'=>'-1', 'aria-hidden'=>'true','multiple')) !!}

		</div> -->

		<div class="form-group">

			{!! Form::label('vehicle_make_id', 'Vehicle Make', array('class' => 'control-label col-md-4 required')) !!}



			<div class="col-md-12">

				{{Form::select('vehicle_make_id', $vehicle_make_id, null, ['class'=>'form-control select_item', 'id' => 'make_id'])}}

			</div>

		</div>

		<div class="form-group">

			{!! Form::label('vehicle_model_id', 'Vehicle Model', array('class' => 'control-label col-md-4 required')) !!}



			<div class="col-md-12">

				{{Form::select('vehicle_model_id', $vehicle_model_id, $vehicle_variant->vehicle_model_id, ['class'=>'form-control select_item', 'id' => 'model_id'])}}



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

	<button type="submit" class="btn btn-success sub">Submit</button>

</div>

	



{!! Form::close() !!}



<script>



	/*('.sub').on('click',function(e){

		e.preventDefault();

		var ver=$('select[name=version]').val();

		alert(ver);



	});*/

	$('.validateform').validate({

		errorElement: 'span', //default input error message container

		errorClass: 'help-block', // default input error message class

		focusInvalid: false, // do not focus the last invalid input

		rules: {

			//name: { required: true },

			name: { 

				required: true,

				/*remote: {

						url: '{{ route('vehicle_variant_name') }}',

						type: "post",

						data: {

						 	_token :$('input[name=_token]').val(),

						 	id:$('input[name=id]').val()

						}

					}*/

			}, 

			version: { required:true },

			vehicle_make_id : { required:true },     

			vehicle_model_id : { required:true  },         



		},



		messages: {

			//name: { required: "Unit Name is required." },

			name: { required: "Vehicle Variant Name is required."/*, remote: "Vehicle Variant Name is already exists!"*/ }, 

			version : { required: " Version required." },

			vehicle_make_id : { required: "Vehicle Make Name is required."},  

			vehicle_model_id : { required: "Vehicle Modal Name is required."},                

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

			url: '{{ route('vehicle_variant.update') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				_method: 'PATCH',

				id: $('input[name=id]').val(),

				name: $('input[name=name]').val(),

				version: $('select[name=version]').val(),

				make_id: $('select[name=vehicle_make_id]').val(),

				model_id: $('select[name=vehicle_model_id]').val(),

				description: $('textarea[name=description]').val()                

			},

			success:function(data, textStatus, jqXHR) {



				if(data.status == 1)

				{

				var active_selected = "";

                var inactive_selected = "";

                var selected_text = "In-Active";

                var selected_class = "badge-warning";



                if(data.data.status == 1) {

                    active_selected = "selected";

                    selected_text = "Active";

                    selected_class = "badge-success";

                } else if(data.data.status == 0) {

                    inactive_selected = "selected";

                }



				call_back(`<tr role="row" class="odd">

					<td>

						<input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">

						<label for="`+data.data.id+`"><span></span></label>

					</td>

					<td>`+data.data.make_id+`</td>

					<td>`+data.data.model_id+`</td>

					<td>`+data.data.name+`</td>

					<td>`+data.data.version+`</td>

					<td>`+data.data.config+`</td>

					<td>

                        <label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>

                        <select style="display:none" id="`+data.data.id+`" class="active_status form-control">

                            <option `+active_selected+` value="1">Active</option>

                            <option `+inactive_selected+` value="0">In-Active</option>

                        </select>

                    </td>

					<td>

					  	<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;

					  	<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>

					</td></tr>`, `edit`, data.message, data.data.id);



				$('.loader_wall_onspot').hide();

				}

				else

				{

					alert_message(data.message,'success');



				}



				},

				error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

				}

			});

		}

	});



	$(document).ready(function(){



		$('select[name=version]').trigger("change");

		$('#make_id').change(function() {

			var make = $('#make_id option:selected').val();

			//alert(make);

			$('#model_id').html('');

			$('#model_id').append("<option value=''>Select Model</option>");

			$.ajax({

				url: '{{ route('get_vehicle_model_name') }}',

				type: "post",

				data: {

					_token: '{{ csrf_token() }}',

					id: make,

				},

				dataType: "json",

				success:function(data, textStatus, jqXHR) {

					var model = data.result;

					for (var i in model) {

						$('#model_id').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");

					}

				},

				error:function(jqXHR, textStatus, errorThrown){

				}

			});

		});



		 var colors = ["#919191"];



        $('.select-tag').select2({

		    tags: true,

		    multiple: true,

		    tokenSeparators: [',']

		});

	});



</script>