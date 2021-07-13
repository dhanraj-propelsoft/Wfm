<div class="modal-header">
	<h4 class="modal-title float-right">Add Ticket</h4>
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
						{!! Form::label('ticketnumber', 'Ticket number', array('class' => 'control-label  required','id'=>'ticketnumber')) !!}

					<div class="form-group">
						{!! Form::text('ticketnumber', $ticket_number,['class' => 'form-control', 'readonly' => 'true']) !!}
					</div>
				    </div>
				</div>
		
		</div>	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('ticketname', 'Ticket Name', array('class' => 'control-label  required','id'=>'ticketname')) !!}

				<div class="form-group">
					{!! Form::text('ticketname', null,['class' => 'form-control']) !!}
				</div>
				</div>
			</div>
		
		</div>
		<div class="row">
		 	<div class="form-group col-md-6"> 
				{{ Form::label('issued_by', 'issued_by', array('class' => 'control-label col-md-12 required')) }}
      		<div class="form-group">
      			{!! Form::select('issued_by',$issued_by,$Loggined_employee_id,['class' => 'form-control select_item','id' => 'issued_by']) !!}
			</div>
			</div>
			<div class="form-group col-md-6"> 
				{{ Form::label('priority', 'priority', array('class' => 'control-label col-md-12 required')) }}
			<div class="form-group">
				<select class="form-control" name="priority" id="priority">
   		             <option>Select Priority</option>
   		             <option value=1>Low</option>
		             <option value=2>Medium</option>
		             <option value=3>High</option>
		   
  				</select> 
			</div>				  		
			</div>
		</div>	
		
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('ticketmessage', 'Ticket Message', array('class' => 'control-label  required','id'=>'TicketMessage')) !!}

					<div class="form-group">
					{!! Form::textarea('ticketmessage', null,['class' => 'form-control', 'rows'=>'3']) !!}
					</div>
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
			
			ticketname: { required: true },
			ticketmessage: { required: true },
			ticketnumber:{ required: true },
			issued_by:{ required: true },
			priority:{ required: true },
			
				},
				
	
messages: {
			ticketname: { required: "Ticket Name is required." },
			ticket_message: { required: "Ticket Message is required." },
			ticketnumber: { required: " Ticket Number is required."},
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
			url: '{{ route('supportticket_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				ticketnumber: $('input[name=ticketnumber]').val(),
				ticketname: $('input[name=ticketname]').val(),
					issued_by: $('select[name=issued_by]').val(),
					priority: $('select[name=priority]').val(),
					ticketmessage: $('textarea[name=ticketmessage]').val(),
				
				//description: $('textarea[name=description]').val()                
				},
						success:function(data, textStatus, jqXHR) {
                  // console.log(data.data.status);

                
				

                  var open_selected = "";
				var progress_selected = "";
				var close_selected = "";
				
				var selected_text = "open";
				var selected_class = "badge-success";

				if(data.data.status == 1) {
					open_selected = "selected";
					selected_text = "open";
					selected_class = "badge-primary";
				} else if(data.data.status == 2) {
					progress_selected = "selected";
					selected_text = "progress";
					selected_class = "badge-warning";
				} else if(data.data.status == 3) {
					close_selected  = "selected";
					selected_text = "close";
					selected_class = "badge-danger";
				}
				call_back(`<tr role="row" class="odd">
					<td>`+data.data.ticket_number+`</td>
				     <td>`+data.data.raised_by+`</td>
					<td>`+data.data.ticket_name+`</td>
					<td>`+data.data.ticket_message+`</td>
					<td>`+data.data.created_at+`</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control">
							<option `+open_selected+` value="1">open</option>
							<option `+progress_selected+`value="2">Progress</option>
							<option `+close_selected+` value="3">close</option>
						</select>
					</td>
					
					<td><a data-id="`+data.data.id+`" class="grid_label action-btn show-icon view edit"><i class="fa fa-eye"></i> </a></td>
					</tr>`,`add`,data.message, data.data.id);

				$('.loader_wall_onspot').hide();
				

				},

			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
 
</script>