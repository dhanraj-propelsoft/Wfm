<div class="modal-header">
	<h4 class="modal-title float-right">Edit  category items</h4>
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
				
					{!! Form::select('my_vehicle_number',$vehicles_registerno,$vechicles->vehicle_id,['class' => 'form-control select_registernumber','id' => 'vehicles_register']) !!}
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('complaint', 'My Complaint', array('class' => 'control-label  required','id'=>'TicketMessage')) !!}

				<div class="form-group">
					{!! Form::textarea('complaint',$vechicles->abservation_summary,['class' => 'form-control', 'rows'=>'3']) !!}
					{!! Form::hidden('complaintid', $vechicles->id ) !!}
				</div>
				</div>
			</div>	
		</div>
		<div class="row">
		 	<div class="col-md-12 form-group">
		 		
				{{ Form::label('set_on','Set ON',array('class' =>'control-label required')) }}
				{{ Form::text('set_on',$vechicles->observed_on, ['class' => 'form-control date-picker datetype extend','data-date-format' => 'dd-mm-yyyy']) }}
				</div>
			</div>
			
		<div class="row">
			<div class="form-group col-md-12"> 
				{{ Form::label('status', 'Status', array('class' => 'control-label required')) }}
		
			<select class ="form-control" id="type" name="status">
    			
   				 <option value="1" {{ $vechicles->closure_status == '1' ? 'selected':'' }} >Open</option>
   				 <option value="0" {{ $vechicles->closure_status == '0' ? 'selected':'' }}>Close</option>
  
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
			
			complaint: { 
				required: true },
			
				
				
       
			},                
		

		messages: {
			//name: { required: "Unit Name is required." },
			complaint: { required: " complaint is required."},
			                
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
			url: '{{ url('user/vms/complaint_update') }}',			
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id:$('input[name=complaintid]').val(),
				vehicle_number: $('select[name=my_vehicle_number]').val(),
				complaint:$('textarea[name=complaint]').val(),
				set_on: $('input[name=set_on]').val(),
				status: $('select[name=status]').val(),
				
				
				//description: $('textarea[name=description]').val()                
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
					
					
					
					</tr>`,`edit`,data.message, data.data.id);

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
  

</script>