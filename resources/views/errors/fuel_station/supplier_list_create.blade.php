<div class="modal-header">
	<h4 class="modal-title float-right">Add Supplier Details</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('propel_id', ' Propel ID ', array('class' => 'control-label  required')) !!}

					{!! Form::text('propel_id', null,['class' => 'form-control','id'=>'propel_id']) !!}
				</div>
			</div>
		
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('supplier_name', ' Supplier Name ', array('class' => 'control-label  required')) !!}

					{!! Form::text('supplier_name', null,['class' => 'form-control','id'=>'supplier_name']) !!}
				</div>
			</div>
		
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('contactname', ' Contact Name ', array('class' => 'control-label  required')) !!}

					{!! Form::text('contactname', null,['class' => 'form-control','id'=>'contactname']) !!}
				</div>
			</div>
		
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('tankname', ' Tank Name ', array('class' => 'control-label  required','id'=>'itemtype')) !!}

					{!! Form::text('tankname', null,['class' => 'form-control','id'=>'supplier_name']) !!}
				</div>
			</div>
		
		</div>
		
		
				</div>
			</div>
		

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>{!! Form::close() !!}

{{--

	@stop

@section('dom_links')
@parent 				
 

--}}

<script>
	$(document).ready(function() {

		

		basic_functions();
	});
	
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			tankname:{ required: true },
			
				},
	
messages: {
			//name: { required: "Unit Name is required." },
			tankname: { required: " TankName is required."},
			
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
			url: '{{ route('tank_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				tankname: $('input[name=tankname]').val(),
				product: $('select[name=product]').val(),
				tank_structure:$('textarea[name=tankstructure]').val(),
				reading_time:$('input[name=reading_time]').val(),
				reading_time1:$('input[name=reading_time1]').val(),
				reading_time2:$('input[name=reading_time2]').val(),
				smstomanager:$('input[name=smstomanager]').val(),
				smstoowner:$('input[name=smstoowner]').val(),
				volume:$('input[name="volume[]"]').map(function() {
					if ($(this).val()) {
						return $(this).val();
					}
      					 }).get().join(),

				
				             
				},
			success:function(data, textStatus, jqXHR) {
				var active_selected = "";
				var inactive_selected = "";
				
				var selected_text = "Active";
				var selected_class = "badge-success";

				if(data.data.status == 1) {
					open_selected = "selected";
					selected_text = "Active";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					progress_selected = "selected";
					selected_text = "In_Active";
					selected_class = "badge-warning";
				} 


				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="tank" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					
				     <td>`+data.data.name+`</td>
					<td>`+data.data.product+`</td>
					<td>`+data.data.reading_time+`</td>
					<td>`+data.data.reading_time1+`</td>
					<td>`+data.data.reading_time2+`</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control active_status">
							<option `+active_selected+` value="1">Active</option>
							
							<option `+inactive_selected+` value="0">In-Active</option>
						</select>
					</td>
					
					
					</tr>`,`add`,data.message, data.data.id);

				$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
	
$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  	$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('tank.status') }}";
			var data=change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
		});

</script>