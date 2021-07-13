<div class="modal-header">
	<h4 class="modal-title float-right"> Add Vehicle Configuration</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			<div class="row form-group">
				<div class="col-12">
					<div class="form-inline">
		                <div class="col-md-9 form-group">
		                    {!! Form::label('name', 'Vehicle Name', array('class' => 'control-label')) !!}
		                    {{ Form::text('name', null, ['class'=>'form-control col-md-11', 'id' => 'vehicle_name', 'readonly']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                	{!! Form::label('config_id', 'Config ID', array('class' => 'control-label')) !!}
		                	{{ Form::text('config_id', $config_id, ['class'=>'form-control', 'id' => 'config_id', 'readonly']) }}
		                	{{ Form::hidden('last_id', $last_id)}}
		                </div>
		            </div>
				</div>			
			</div>
			<div class="row form-group">
				<div class="col-12">
					<div class="form-inline">
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_category', 'Vehicle Category', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_category', $vehicle_category, null, ['class'=>'form-control select_item', 'id' => 'vehicle_category']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                	{!! Form::label('vehicle_make', 'Make', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_make', $vehicle_make_id, null, ['class'=>'form-control select_item', 'id' => 'vehicle_make']) }}
		                </div>     
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_model', 'Model', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_model', ['' => 'Select Model'], null, ['class'=>'form-control select_item', 'id' => 'vehicle_model']) }}
		                </div>	            
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_variant', 'Variant', array('class' => 'control-label required')) !!}
		                    {{ Form::select('vehicle_variant', ['' => 'Select Variant'], null, ['class'=>'form-control select_item', 'id' => 'vehicle_variant']) }}
		                </div>
		            </div>
				</div>			
			</div>
			<div class="row form-group">
				<div class="col-12">
					<div class="form-inline">
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_body_type', 'Body Type', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_body_type', $body_type, null, ['class'=>'form-control select_item', 'id' => 'vehicle_body_type']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_tyre_type', 'Tyre Type', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_tyre_type', $vehicle_tyre_type, null, ['class'=>'form-control select_item', 'id' => 'vehicle_tyre_type']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                	{!! Form::label('vehicle_rim_type', 'Rim / Wheel', array('class' => 'control-label')) !!}
		                    
		                    {{ Form::select('vehicle_rim_type', $rim_type, null, ['class'=>'form-control select_item', 'id' => 'vehicle_rim_type']) }}
		                </div>		                	            
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_tyre_size', 'Tyre Size', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_tyre_size', $vehicle_tyre_size, null, ['class'=>'form-control select_item', 'id' => 'vehicle_tyre_size']) }}
		                </div>
		            </div>
				</div>			
			</div>
			<div class="row form-group">
				<div class="col-12">
					<div class="form-inline">
		                <div class="col-md-3 form-group">
		                    {!! Form::label('fuel_type', 'Fuel Type', array('class' => 'control-label')) !!}
		                    {{ Form::select('fuel_type', $fuel_type, null, ['class'=>'form-control select_item', 'id' => 'fuel_type']) }}
		                </div>
		                <div class="col-md-3 form-group">
		                	{!! Form::label('vehicle_wheel_type', 'No of Wheels', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_wheel_type', $vehicle_wheel, null, ['class'=>'form-control select_item', 'id' => 'vehicle_wheel_type']) }}
		                </div>     
		                <div class="col-md-3 form-group">
		                    {!! Form::label('vehicle_drivetrain', 'Drivetrain', array('class' => 'control-label')) !!}
		                    {{ Form::select('vehicle_drivetrain', $vehicle_drivetrain, null, ['class'=>'form-control select_item', 'id' => 'vehicle_drivetrain']) }}
		                </div>
		            </div>
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
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

<script>
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			vehicle_make: { required: true },
			vehicle_model: { required: true },
			vehicle_variant: { required: true },
			                
		},

		messages: {
			vehicle_make: { required: "Vehicle Make is required." },
			vehicle_model: { required: "Vehicle Model is required." },
			vehicle_variant: { required: "Vehicle Variant is required." },
			                
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
			url: '{{ route('vehicle_configuration.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				vehicle_category: $('select[name=vehicle_category]').val(),
				vehicle_make: $('select[name=vehicle_make]').val(),
				vehicle_model: $('select[name=vehicle_model]').val(),
				vehicle_variant: $('select[name=vehicle_variant]').val(),
				vehicle_body_type: $('select[name=vehicle_body_type]').val(),
				vehicle_rim_type: $('select[name=vehicle_rim_type]').val(),
				vehicle_tyre_type: $('select[name=vehicle_tyre_type]').val(),
				vehicle_tyre_size: $('select[name=vehicle_tyre_size]').val(),
				fuel_type: $('select[name=fuel_type]').val(),
				vehicle_wheel_type: $('select[name=vehicle_wheel_type]').val(),
				vehicle_drivetrain: $('select[name=vehicle_drivetrain]').val(),
				description: $('textarea[name=description]').val(),

				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.vehicle_category+`</td>
					<td>`+data.data.vehicle_make+`</td>
					<td>`+data.data.vehicle_model+`</td>
					<td>`+data.data.vehicle_variant+`</td>
					<td>`+data.data.vehicle_body_type+`</td>
					<td>`+data.data.vehicle_rim_type+`</td>
					<td>`+data.data.vehicle_tyre_type+`</td>
					<td>`+data.data.vehicle_tyre_size+`</td>
					<td>`+data.data.vehicle_wheel_type+`</td>
					<td>`+data.data.vehicle_drivetrain+`</td>
					<td>`+data.data.fuel_type+`</td>
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

	$(document).ready(function(){
		$('#vehicle_make').change(function() {
			var make = $('#vehicle_make option:selected').val();
			var name = $('#vehicle_make option:selected').text();
			$('#vehicle_name').val(name);
			//alert(make);
			$('#vehicle_model').html('');
			$('#vehicle_model').append("<option value=''>Select Model</option>");
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
						$('#vehicle_model').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");
					}
				},
				error:function(jqXHR, textStatus, errorThrown){
				}
			});
		});

		$('#vehicle_model').change(function() {
			var model_id = $('#vehicle_model option:selected').val();
			var name = $('#vehicle_make option:selected').text();
			var model = $('#vehicle_model option:selected').text();
			$('#vehicle_name').val(name+' - '+model);
			//alert(make);
			$('#vehicle_variant').html('');
			$('#vehicle_variant').append("<option value=''>Select Model</option>");
			$.ajax({
				url: '{{ route('get_vehicle_variant_name') }}',
				type: "post",
				data: {
					_token: '{{ csrf_token() }}',
					id: model_id,
				},
				dataType: "json",
				success:function(data, textStatus, jqXHR) {
					var model = data.result;
					for (var i in model) {
						$('#vehicle_variant').append("<option value='"+model[i].id+"'>"+model[i].name+"</option>");
					}
				},
				error:function(jqXHR, textStatus, errorThrown){
				}
			});
		});

		$('#vehicle_variant').change(function() {
			var name = $('#vehicle_make option:selected').text();
			var model = $('#vehicle_model option:selected').text();
			var variant = $('#vehicle_variant option:selected').text();
			var last_id = $('input[name=last_id]').val();
			//alert(variant);
			$('#vehicle_name').val(name+' - '+model+' - '+variant+' Custom Code '+last_id);
		});	

		$("select[name=vehicle_body_type]").val($("select[name=vehicle_body_type]").find("option:contains(All)").val());
		$("select[name=vehicle_rim_type]").val($("select[name=vehicle_rim_type]").find("option:contains(All)").val());
		$("select[name=vehicle_tyre_type]").val($("select[name=vehicle_tyre_type]").find("option:contains(All)").val());
		$("select[name=vehicle_tyre_size]").val($("select[name=vehicle_tyre_size]").find("option:contains(All)").val());
		$("select[name=fuel_type]").val($("select[name=fuel_type]").find("option:contains(All)").val());
		$("select[name=vehicle_wheel_type]").val($("select[name=vehicle_wheel_type]").find("option:contains(All)").val());
		$("select[name=vehicle_drivetrain]").val($("select[name=vehicle_drivetrain]").find("option:contains(All)").val());

		$("select[name=vehicle_body_type], select[name=vehicle_rim_type], select[name=vehicle_tyre_type], select[name=vehicle_tyre_size], select[name=fuel_type], select[name=vehicle_wheel_type],select[name=vehicle_drivetrain]").trigger('change');		
	});

	

</script>