<div class="alert alert-success">

	{{ Session::get('flash_message') }}

</div>

<div class="modal-header" style="background-color: #e9ecef;">

	<h5 class="modal-title float-right"><b>Add  Variant Version</b></h5>
    <a  class="close" data-dismiss="modal">&times;</a>


</div>



	{!! Form::open([

		'class' => 'form-horizontal validateform'

	]) !!}                                        

	{{ csrf_field() }}



<div class="modal-body" style="overflow-y: scroll;">

	<div class="form-body">

		<div class="form-group">

			{!! Form::label('name', 'Vehicle Variant Name', array('class' => 'control-label col-md-5 required')) !!}

			

			<div class="col-md-12">

				{{Form::select('name', $vehicle_version_id, null, ['class'=>'form-control select_item variant', 'id' => 'make_id'])}}

			</div>

		</div>

		<div class="form-group">

			{!! Form::label('version', 'Existing  Versions', array('class' => 'control-label col-md-12 required version')) !!}

			

			{!! Form::label('version', 'New Version', array('class' => 'control-label col-md-4 required ')) !!}

			

			<div class="col-md-12">

				

				 {!! Form::select('version', [], null,array('class' => 'select-tag form-control select2-hidden-accessible', 'data-date-format' => 'dd-mm-yyyy','id'=>'tags','data-select2-id'=>'10','tabindex'=>'-1', 'aria-hidden'=>'true','multiple')) !!}

			</div>

		</div>

		<div class="form-group">

			{!! Form::label('make_id', 'Vehicle Make', array('class' => 'control-label col-md-4 required')) !!}



			<div class="col-md-12">

				{{Form::text('make_id', null, ['class'=>'form-control select_item', 'id' => 'make_id','disabled' =>'true'])}}

			</div>

		</div>

		<div class="form-group">

			{!! Form::label('model_id', 'Vehicle Model', array('class' => 'control-label col-md-4 required')) !!}



			<div class="col-md-12">

				{{Form::text('model_id', null, ['class'=>'form-control select_item', 'id' => 'model_id','disabled' => 'true'])}}



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

						 _token :$('input[name=_token]').val()

						 },

					},*/

			},  

			make_id : { required:true },     

			model_id : { required:true ,

						/*remote: {

							url: '{{ route('variant_name') }}',

							type: "post",

							data: {



								_token: '{{ csrf_token() }}',

								variant : $('input[name=name]').val(),

								model:$('select[name=make_id]').val(),

								make: $('select[name=model_id]').val(),

							},

					},*/

			 }, 



			version: { required: true},        

		},



		messages: {

			//name: { required: "Unit Name is required." },

			name: { required: "Vehicle Variant Name is required."/*, remote: "Vehicle Variant Name is already exists!"*/ }, 

			make_id : { required: "Vehicle Make Name is required."},  

			model_id : { required: "Vehicle Modal Name is required."/*, remote: "This  Name is already exists!"*/ },   

			version : { required: " Version is required."},            



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

			url: '{{ route('vehicle_variant_version.store') }}',

			type: 'post',

			data: {

				_token: '{{ csrf_token() }}',

				id: $('select[name=name]').val(),

				make_id: $('input[name=make_id]').attr("data-id"),

				model_id: $('input[name=model_id]').attr("data-id"),

				version:$('select[name=version]').val(),

				description: $('textarea[name=description]').val()                

				},

			success:function(data, textStatus, jqXHR) {

				console.log(data.data);

				var version = data.data;

				var make_id =data.make_id;

				var model_id = data.model_id;

				/*var version = data.data.version;

				var ex = version.split(",");

				console.log(ex);

				for(var i in ex)

				{

					var ver = ex[i];

				}

				console.log(ver);*/



				if(data.status == 1)

				{

					for(var i in version)

					{

				call_back(`<tr role="row" class="odd">

					<td>

						<input id="`+version[i].id+`" class="item_check" name="category" value="`+version[i].id+`" type="checkbox">

						<label for="`+version[i].id+`"><span></span></label>

					</td>					

					<td>`+make_id+`</td>

					<td>`+model_id+`</td>

					<td>`+version[i].name+`</td>

					<td>`+version[i].version+`</td>

					<td>`+version[i].vehicle_configuration+`</td>

					<td>

						<label class="grid_label badge badge-success status">Active</label>

						<select style="display:none" id="`+version[i].id+`" class="active_status form-control">

							<option value="1">Active</option>

							<option value="0">In-active</option>

						</select>

					</td>

					<td>

					<a data-id="`+version[i].id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;

					<a data-id="`+version[i].id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>

					</td></tr>`, `add`, data.message);

					}	



				}

				else

				{



					alert_message(data.message,'success');

				}

				$('.loader_wall_onspot').hide();

				//$('.crud_modal').modal('hide');





				},

				error:function(jqXHR, textStatus, errorThrown) {

				//alert("New Request Failed " +textStatus);

				}

			});

		}

	});



	$(document).ready(function(){

		/*$('#make_id').change(function() {

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

		});*/



  var colors = ["#919191"];



        $(".select-tag").select2({

            tags: true,

            tokenSeparators: [',', ' '],

            templateSelection: function (data, container) {

                var selection = $('.select-tag').select2('data');

                var idx = selection.indexOf(data);



                //console.log(">>Selection",data.text, data.idx, idx);

                //data.idx = idx;

                //$(container).css("background-color", colors[data.idx]);

                return data.text;

            },

        })







		$('.variant').change(function(){

			//alert();

			//var make = $('#make_id option:selected').val();

			var variant=$('select[name=name]').val();

			//alert(variant);

			

			$.ajax({

				url: '{{ route('get_make_name') }}',

				type: "post",

				data:

				{

					_token: '{{ csrf_token() }}',

					variant : variant,

					

				},

				success:function(data){

					//console.log(data.version);

					var version = data.data;

					var num_version =data.version;

					//alert();

					//console.log(num_version);

					var v=JSON.stringify(num_version);

					//console.log(v);

					for (var i in version) 

					{

					$('input[name=make_id]').val(version[i].make_name);

					$('input[name=make_id]').attr("data-id",version[i].vehicle_make_id);

					$('input[name=model_id]').val(version[i].model_name);

					$('input[name=model_id]').attr("data-id",version[i].vehicle_model_id);



					}

					/*var keys=[]

					var values=[]

					i=0

					for (var key in v[0]) 

					{

					    keys[i]=key;

					    values[i]=v[0][key]    

					    i=i+1

					}

					console.log(key);

					console.log(values);*/

					var data = JSON.parse(v);

					//console.log(data.version);

					for(var j in data )

					{

					$('.version').text("Existing Versions  :  "+data[j].version);

					console.log(data[j].version);



					}

				

				},

				error:function(){



				}

			});

		});

	});



</script>