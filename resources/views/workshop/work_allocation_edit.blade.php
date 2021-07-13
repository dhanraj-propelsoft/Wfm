<div class="modal-header">
	<h4 class="modal-title float-right">Edit Work Allocation</h4>
</div>

	{!! Form::model($work_allocation, ['class' => 'form-horizontal validateform' ]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">

		{!! Form::hidden('id', null) !!}

			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
					<label class="required" for="make_id">Make</label>
					{{ Form::select('make_id', $make, $make_id, ['class' => 'form-control select_item', 'id' => 'make_id']) }}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					<label class="required" for="vehicle_model_id">Model</label>	
					{!! Form::select('vehicle_model_id', $model, $work_allocation->model_id, ['class' => 'select_item form-control','id'=> 'vehicle_model_id' ]) !!}
				</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
					<label class="required" for="work">Work</label>		
					{{ Form::select('work',$work , $work_allocation->item_id, ['class' => 'form-control select_item', 'id' => 'vehicle_model_id']) }}
				</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
					<label class="required" for="make_id">Sale Price</label>
					{!! Form::text('sale_price', $price, ['class'=>'form-control', 'placeholder'=>'Sale Price','id'=>'sale_price']) !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					<label class="required" for="make_id">On Date </label>
					{!! Form::text('on_date',$date,['class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'on_date']) !!}
				</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					<label class="required" for="make_id">Estimation Time</label>
					{!! Form::text('estimated_time',null,['class' => 'form-control timepicker timepicker-24', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'estimated_time']) !!}
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
$(document).ready(function()
{
	basic_functions();

	$( "select[name=make_id]" ).change(function () {

		var model =  $( "select[name=vehicle_model_id]" );

		var select_val = $(this).val();
		model.empty();
		model.append("<option value=''>Select Model</option>");
			$.ajax({
				 url: '{{ route('get_model') }}',
				 type: 'post',
				 data: {
					_token : '{{ csrf_token() }}',
					make_id: select_val
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						for(var i in result) {	
							model.append("<option value='"+result[i].id+"'>"+result[i].name+"</option>");
						}
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});

	});

	$('select[name=business]').each(function() {
			$(this).prepend('<option value="0"></option>');
			select_business($(this));
		});

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			business: { required: true },
			//parent_department: { required: true },                
		},

		messages: {
			business: { required: "Bussiness is required." },
			//parent_department: { required: "Parent Department Name is required." },
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
			url: '{{ route('work_allocation.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',	
				id: {{$id}},	
				model: {{$model_id}},		
				model_id: $('select[name=vehicle_model_id]').val(),
				item_id: $('select[name=work]').val(),
				sale_price: $('input[name=sale_price]').val(),
				on_date: $('input[name=on_date]').val(),
				estimated_time: $('input[name=estimated_time]').val()
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.item_id+`_`+data.data.model_id+`" data-item_id="`+data.data.item_id+`" data-model_id="`+data.data.model_id+`" class="item_check" name="work_allocation" type="checkbox">
						<label for="`+data.data.item_id+`_`+data.data.model_id+`"><span></span></label>
					</td>	
					<td>`+data.data.model+`</td>
					<td>`+data.data.work+`</td>
					<td>`+data.data.estimated_time+`</td>
					<td>`+data.data.rate+`</td>
					<td>
						<a data-item_id="`+data.data.item_id+`" data-model_id="`+data.data.model_id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
						<a data-item_id="`+data.data.item_id+`" data-model_id="`+data.data.model_id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
						</td></tr>`, `edit`, data.message, {{$id}},{{$model_id}});

					$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

});
</script>