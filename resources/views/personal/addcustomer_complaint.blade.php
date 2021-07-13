<div class="modal-header">
	<h4 class="modal-title float-right">Add My Complaint</h4>
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
					{!! Form::label('my_vehicle_number', 'My Vehicle Number', ['class' => ' control-label required']) !!}
				
					{!! Form::select('my_vehicle_number',$vehicles_register,null,['class' => 'form-control select_registernumber','id' => 'vehicles_register']) !!}
				</div>
			</div>
			
		</div>
						
<!-- 		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('categoryname', 'Add  Category', array('class' => 'control-label  required','id'=>'maincategoryname')) !!}

				<div class="form-group">
					{!! Form::text('complaint_number',$complaint_number,['class' => 'form-control', 'readonly' => 'true']) !!}
				</div>
				</div>
			</div>
		</div> -->
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('complaint', 'My Complaint', array('class' => 'control-label  required','id'=>'TicketMessage')) !!}

				<div class="form-group">
					{!! Form::textarea('complaint', null,['class' => 'form-control', 'rows'=>'3']) !!}
				</div>
				</div>
			</div>	
		</div>
		
		<div class="row">
		 	<div class="col-md-12 form-group">
		 		
				{{ Form::label('set_on','Set ON',array('class' =>'control-label required')) }}
				{{ Form::text('set_on',null, ['class' => 'form-control date-picker datetype extend','data-date-format' => 'dd-mm-yyyy']) }}
				</div>
			</div>
		
	
		<div class="row">
			<div class="form-group col-md-12"> 
				{{ Form::label('status', 'Status', array('class' => 'control-label required')) }}
		
			<select name="status" class="form-control">
			    <option value="1">open</option>
			    <option value="0">close</option>
			  
			</select>
			</div>
		
		</div>
	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

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
			my_vehicle_number:{ required: true },
			complaint: { required: true },
			set_on:{ required:true },
		
			
					
				},
	
	messages: {
			//name: { required: "Unit Name is required." },
			my_vehicle_number: { required: " vechicle regitration_no is required."},
			complaint: {required :"complaint is required."},
			set_on:{required:" Set Your Complaint Date must"},

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
			url: '{{ route('complaint_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				vehicle_id: $('select[name=my_vehicle_number]').val(),
				complaint: $('textarea[name=complaint]').val(),
				set_on: $('input[name=set_on]').val(),
				status:$('select[name=status]').val(),
				             
				},
			success:function(data, textStatus, jqXHR) {
				var open_selected = "";
				var close_selected = "";
				
				var selected_text = "Open";
				var selected_class = "badge-success";

				if(data.data.status == 1) {
					open_selected = "selected";
					selected_text = "Open";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					progress_selected = "selected";
					selected_text = "Close";
					selected_class = "badge-warning";
				} 
				call_back(`<tr role="row" class="odd">
					
				     <td>`+data.data.registration_no+`</td>
					<td>`+data.data.complaint+`</td>
					<td>`+data.data.observed_on+`</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control active_status">
							<option `+open_selected+` value="1">Open</option>
							
							<option `+close_selected+` value="0">Close</option>
						</select>
					</td>
					<td><a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>
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
			var url = "{{ route('vms_complaint_activestatus') }}";
			change_status(id, obj, status, url, "{{ csrf_token() }}");
		});

$('.extend').datepicker().change(evt => {
	//console.log("workit");
  var selectedDate = $('.extend').datepicker('getDate');
  var now = new Date();
  now.setHours(0,0,0,0);
  if (selectedDate < now) {
  	$(".InputDate_Error")
   alert(" that date is past");
  } else {
  	// var status = $(this).val();
			// var id = $(this).attr('id');
			// console.log(id);
			// var obj = $(this);
			// var url = "{{ route('organization.extend') }}";
			// var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
			// console.log(data);
   console.log("ok");
  }
});

</script>