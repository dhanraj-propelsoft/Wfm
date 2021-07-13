<div class="modal-header" style="background-color: #e9ecef;">

    <h5 class="modal-title float-right"><b>Add Rack</b></h5>
	<a  class="close" data-dismiss="modal">&times;</a>


</div>



    {!! Form::open([

        'class' => 'form-horizontal validateform'

    ]) !!}                                        

    {{ csrf_field() }}



<div class="modal-body" style="overflow-y: scroll;">

	<div class="form-body">

		<div class="form-group">

			{!! Form::label('name', 'Rack Name', array('class' => 'control-label col-md-3 required')) !!}



			<div class="col-md-12">

				{!! Form::text('name', null,['class' => 'form-control']) !!}

			</div>

		</div>

		<div class="form-group">		

			<div class="col-md-12">

				{{ Form::checkbox('check_store','1',null, ['id' => 'check_store']) }}

				<label class="control-label" for="check_store"><span></span>I have a Store</label>

			</div>		

		</div>		



		<div class="form-group store" style="display: none;">

			{!! Form::label('store_id', 'Store Name', array('class' => 'control-label col-md-3')) !!}



			<div class="col-md-12">

				{{Form::select('store_id', $store_name, null, ['class'=>'form-control select_item'])}}

			</div>

		</div>



		<div class="form-group warehouse">

			{!! Form::label('warehouse_id', 'Warehouse Name', array('class' => 'control-label col-md-4')) !!}



			<div class="col-md-12">

				{{Form::select('warehouse_id', $warehouse_name, null, ['class'=>'form-control select_item'])}}

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

	$(document).ready(function() {

	   	basic_functions();

	 	$("input[name=check_store]").on('change',function(){

			

			$('select[name=store_id]').val('');

			$('select[name=warehouse_id]').val('');

		});



	   	$("input[name=check_store]").on('change', function(){

			if($(this).is(':checked'))	{			

				$('.store').show();

				$('.warehouse').hide();

			}

			else{

				$('.store').hide();

				$('.warehouse').show();

			}

		});

	});

	

	$('.validateform').validate({

		errorElement: 'span', //default input error message container

		errorClass: 'help-block', // default input error message class

		focusInvalid: false, // do not focus the last invalid input

		rules: {

		    name: {

		    	required: true,

		    	remote: {

					url: '{{ route('check_rack_name') }}',

					type: "post",

					data: {

						_token :$('input[name=_token]').val()						 

					}

				}

		    },		                  

		},



		messages: {

		    name: { 

		    	required: "Rack Name is required.",

		    	remote: "Rack Name is already exists!"

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

            url: '{{ route('rack.store') }}',

            type: 'post',

            data: {

                _token: '{{ csrf_token() }}',

                name: $('input[name=name]').val(),

                store_id: $('select[name=store_id]').val(),

                warehouse_id: $('select[name=warehouse_id]').val(),

                description: $('textarea[name=description]').val()                

                },

            success:function(data, textStatus, jqXHR) {



                call_back(`<tr role="row" class="odd">

                	<td><input id="`+data.data.id+`" class="item_check" name="rack" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>

					</td>

                    <td>`+data.data.name+`</td>

                    <td>`+data.data.warehouse+`</td>

                    <td>`+data.data.store+`</td>

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