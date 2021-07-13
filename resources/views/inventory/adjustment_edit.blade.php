<div class="modal-header" style="background-color: #e9ecef;">

											<h5 class="modal-title float-right"><b>Edit Inventory Adjustment</b></h5>
	<a  class="close" data-dismiss="modal">&times;</a>


										</div>





							{!!Form::model($inventory_adjustments, ['class' => 'form-horizontal validateform'])!!}

										

							{{ csrf_field() }}

<div class="modal-body" style="overflow-y: scroll;">

							<div class="form-body" >

									{!! Form::hidden('id', null) !!}

											<div class="form-group">

										{{ Form::label('item_id', 'Item', array('class' => 'control-label col-md-5 required')) }}



										<div class="col-md-12">

										{!! Form::select('item_id', $inventory_item, null, ['class' => 'select_item form-control', 'id' => 'item_id']) !!}

										</div>

									</div>





									<div class="form-group">

										<div class="row">

										<div class="col-md-6">	

										{{ Form::label('adjustment_no', 'Adjustment No', array('class' => 'control-label col-md-12 required')) }}

										<div class="col-md-12">{!! Form::text('adjustment_no', null, ['class'=>'form-control', 'placeholder'=>'Adjustment No','id'=>'adjustment_no', 'disabled']) !!}

										</div>

										</div>

									

								

										<div class="col-md-6">

											{!! Form::label('date', 'Date', array('class' => 'control-label col-md-6 required')) !!}

										<div class="col-md-12">

											{!! Form::text('date', null,['class' => 'form-control date-picker rearrangedate', 'data-date-format' => 'dd-mm-yyyy']) !!}

										</div>

										</div>

										</div>

									

									</div>





									<div class="form-group">

										<div class="row">



										<div class="col-md-4">	

										{{ Form::label('in_stock', 'Available Qty', array('class' => 'control-label col-md-12')) }}

										

										<div class="col-md-12">{!! Form::text('in_stock', $inventory_item_stocks, ['class'=>'form-control', 'id'=>'in_stock', 'disabled' => 'true']) !!}

										</div>

										</div>

										

									

										<div class="col-md-4">

										

										{{ Form::label('new_stock', 'New Qty', array('class' => 'control-label col-md-12')) }}

										

										<div class="col-md-12">{!! Form::text('new_stock', null, ['class'=>'form-control decimal required', 'placeholder'=>'e.g.10, -10', 'id'=>'new_stock']) !!}

										</div>

										

										</div>



										<div class="col-md-4">



										{{ Form::label('quantity', 'Changing Qty', array('class' => 'control-label col-md-12')) }}

										

										<div class="col-md-12">{!! Form::text('quantity', '', ['class'=>'form-control', 'id'=>'quantity', 'disabled' => 'true']) !!}

										</div>

										</div>

										</div>

										

								 </div>





									<div class="form-group">	

										{{ Form::label('description', 'Reason', array('class' => 'control-label col-md-5')) }}

										<div class="col-md-12">{!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Reason','id'=>'description', 'size' => '3x4']) !!}

										</div>

									</div>

	

															

							</div>

</div>

									<div class="modal-footer" style="background-color: #e9ecef;">

									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

									<button type="submit" class="btn btn-success">Submit</button>

									</div>



							{!! Form::close() !!}		

						

					





<script type="text/javascript">

$(document).ready(function() {



		basic_functions();



		$('body').on('change', 'select[name=item_id]', function() {

			var id = $(this).val();



			if(id != "") {



				$.ajax({



					 url: '{{ route('adjustment.get_available_quantity') }}',

					 type: 'post',

					 data: {

					 	_token : '{{csrf_token()}}',

					 	id: id,

					 },

					 dataType: "json",

					 success:function(data, textStatus, jqXHR) {

					 	var result = data.result;

					 	var new_stock = isNaN(parseFloat($('input[name=new_stock]').val())) ? 0 : parseFloat($('input[name=new_stock]').val());

					 	$('input[name=in_stock]').val(result.in_stock);	

						if(!isNaN(parseFloat( result.in_stock ))) {

							$('input[name=quantity]').val(parseFloat(result.in_stock)+parseFloat(new_stock));

						}

					 	$('input[name=in_stock]').val(result.in_stock);	

					 }



				});

			}

		});



		$('body').on('input change', 'input[name=new_stock], select[name=item_id]', function() {

			var new_stock = parseFloat($('input[name=new_stock]').val());

			var in_stock = parseFloat($('input[name=in_stock]').val());



			if( new_stock < 0 ) {

				if( Math.abs(new_stock) > in_stock ) {

					$('input[name=new_stock]').val('-'+in_stock);

					new_stock = parseFloat('-'+in_stock);

				}

			}



			$('input[name=quantity]').val(parseFloat(in_stock)+parseFloat(new_stock));

				

		});

	

			

	});



		$('.validateform').validate({

            errorElement: 'span', //default input error message container

            errorClass: 'help-block', // default input error message class

            focusInvalid: false, // do not focus the last invalid input



            rules: {

                item_id: {

                    required: true

                },

                date: {

                    required: true

                },

                new_stock: {

                    required: true

                }

            },



            messages: {

                name: {

                    required: "Inventory Item is required."

                },

                date: {

                    required: "Date is required."

                },

                new_stock: {

                    required: "New quantity is required."

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

				 url: '{{ route('adjustment.update') }}',

				 type: 'post',

				 data: {

				 	_token: '{{ csrf_token() }}',

				 	_method: 'PATCH',

				 	id: $('input[name=id]').val(),

				 	adjustment_no: $('input[name=adjustment_no]').val(),

				 	item_id: $('select[name=item_id]').val(),

				 	date:$('input[name=date]').val(),

				 	description:$('textarea[name=description]').val(),

				 	quantity:$('input[name=quantity]').val(),

				 	

					},

				 dataType: "json",

					success:function(data, textStatus, jqXHR) {



						var active_selected = "";

						var inactive_selected = "";

						var selected_text = "In-Active";

						var selected_class = "badge-warning";



						var item_name = ($('select[name=item_id] option:selected').val() == "") ? '' : $('select[name=item_id] option:selected').text();



						if(data.data.status == 1) {

							active_selected = "selected";

							selected_text = "Active";

							selected_class = "badge-success";

						} else if(data.data.status == 0) {

							inactive_selected = "selected";

						}



					



						call_back(`<tr role="row" class="odd">

								<td>`+data.data.adjustment_no+`</td>

				     			<td>`+item_name+`</td>

				     			<td>`+data.data.quantity+`</td>				     							            

				            	<td>`+data.data.date+`</td>	

				            	<td>`+data.data.description+`</td>	

							

							

							<td>

							<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;

							<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>

							</td></tr>`, `edit`, data.message, data.data.id);		

						$('.loader_wall_onspot').hide();

					},

				 error:function(jqXHR, textStatus, errorThrown) {

					//alert("New Request Failed " +textStatus);

					}

				});

            }

        });

	</script>

