<div class="modal-header">
	<h4 class="modal-title float-right">View Ticket</h4>
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
						{!! Form::text('ticketnumber', $show->ticket_number,['class' => 'form-control', 'readonly' => 'true']) !!}
					</div>
				    </div>
				</div>
		
		</div>	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('ticketname', 'Ticket Name', array('class' => 'control-label  required','id'=>'ticketname')) !!}

				<div class="form-group">
					{!! Form::text('ticketname',$show->ticket_name,['class' => 'form-control','readonly']) !!}
				</div>
				</div>
			</div>
		
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('org_id', 'Organization_id', array('class' => 'control-label  required','id'=>'org_id')) !!}

				<div class="form-group">
					{!! Form::text('org_id',$show->organization_id,['class' => 'form-control','readonly']) !!}
				</div>
				</div>
			</div>
		
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('org_name','Organization_Name', array('class' => 'control-label  required','id'=>'org_id')) !!}

				<div class="form-group">
					{!! Form::text('org_name',$show->name,['class' => 'form-control','readonly']) !!}
				</div>
				</div>
			</div>
		
		</div>
		<div class="row">
		 	<div class="form-group col-md-12"> 
				{{ Form::label('raisedby_name','Raisedby_Name', array('class' => 'control-label required')) }}
      		<div class="form-group">
      			{!! Form::text('raisedby_name',$show->raised_by,['class' => 'form-control ','id' => 'raisedby_name','readonly']) !!}
			</div>
			</div>
		</div>
		<div class="row">
		 	<div class="form-group col-md-12"> 
				{{ Form::label('raisedby_number','Raisedby_Number', array('class' => 'control-label required')) }}
      		<div class="form-group">
      			{!! Form::text('raisedby_number',$show->phone_no,['class' => 'form-control ','id' => 'raisedby_number','readonly']) !!}
			</div>
			</div>
		</div>
		<div class="row">
			
			
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('priority', 'Priority', ['class' => ' control-label required']) !!}
				
					{!! Form::select('priority',$priority,$show->priorityname,['class' => 'form-control select_item','id' => 'priority']) !!}
					</div>
				</div>
			</div>
		<div class="row">
			
			
				<div class="col-md-12">
					<div class="form-group">
					{!! Form::label('status', 'Status', ['class' => ' control-label required']) !!}
				
					{!! Form::select('status',$status,$show->statusname,['class' => 'form-control select_item','id' => 'status']) !!}
					</div>
				</div>
			</div>
		
		
		<div class="row">
		 	<div class="form-group col-md-12"> 
				{{ Form::label('created_on', 'Created On', array('class' => 'control-label required')) }}
      		<div class="form-group">
      			{!! Form::text('created_on',$show->start_date,['class' => 'form-control ','id' => 'created_on','readonly']) !!}
			</div>
			</div>
		</div>
		<div class="row">
		 	<div class="form-group col-md-12"> 
				{{ Form::label('closed_on', 'Closed On', array('class' => 'control-label required')) }}
      		<div class="form-group">
      			{!! Form::text('closed_on',$show->closed_on,['class'=>'form-control date-picker datetype extend', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off','id'=>$show->id ]) !!}
      			<div class="InputDate_Error"></div>
			</div>
			</div>
		</div>
			
		 {!! Form::hidden('supportid', $show->id  ) !!}
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('ticketmessage', 'Ticket Message', array('class' => 'control-label  required','id'=>'TicketMessage')) !!}

				<div class="form-group">
					{!! Form::textarea('ticketmessage', $show->ticket_message,['class' => 'form-control', 'rows'=>'3','readonly']) !!}
				</div>
				</div>
			</div>
		</div>	
	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('propel_reply', 'propel Reply ', array('class' => 'control-label  required','id'=>'propel_reply')) !!}

				<div class="form-group">
					{!! Form::textarea('propel_reply', null,['class' => 'form-control', 'rows'=>'3']) !!}
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
			
			propel_reply:{ required: true },
			
			
				},
				
	
messages: {
			propel_reply: { required: " propel_reply message  is required."},
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
			url: '{{ route('supportticket_ubdate') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				priority: $('select[name=priority]').val(),
				status: $('select[name=status]').val(),
				propel_reply: $('textarea[name=propel_reply]').val(),
				closed_on: $('input[name=closed_on]').val(),
				supportid:$('input[name=supportid]').val(),
				
				
				
				//description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) {
                  // console.log(data.data.status);

                  var low_selected = "";
				var medium_selected = "";
				var high_selected = "";
				
				var selected_text1 = "Low";
				var selected_class1 = "badge-primary";

				if(data.data.priority == 1) {
					low_selected = "selected";
					selected_text1 = "Low";
					selected_class1 = "badge-primary";
				} else if(data.data.priority == 2) {
					medium_selected = "selected";
					selected_text1 = "Medium";
					selected_class1 = "badge-warning";
				} else if(data.data.priority == 3) {
					high_selected  = "selected";
					selected_text1= "High";
					selected_class1 = "badge-danger";
				}

                  var open_selected = "";
				var progress_selected = "";
				var close_selected = "";
				
				var selected_text = "Open";
				var selected_class = "badge-primary";

				if(data.data.status == 1) {
					open_selected = "selected";
					selected_text = "Open";
					selected_class = "badge-primary";
				} else if(data.data.status == 2) {
					progress_selected = "selected";
					selected_text = "Progress";
					selected_class = "badge-warning";
				} else if(data.data.status == 3) {
					close_selected  = "selected";
					selected_text = "Close";
					selected_class = "badge-danger";
				}
				call_back(`<tr role="row" class="odd">
					<td>`+data.data.ticket_number+`</td>
				     <td>`+data.data.org_id+`</td>
					<td>`+data.data.org_name+`</td>
					<td>`+data.data.ticket_name+`</td>
					<td>
						<label class="grid_label badge `+selected_class1+` priority">`+selected_text1+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control">
							<option `+low_selected+` value="1">Low</option>
							<option `+medium_selected+`value="2">Medium</option>
							<option `+high_selected+` value="3">High</option>
						</select>
					</td>
					
					<td></td>
					<td>`+data.data.created_at+`</td>
						<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class="active_status form-control">
							<option `+open_selected+` value="1">Open</option>
							<option `+progress_selected+`value="2">Progress</option>
							<option `+close_selected+` value="3">Close</option>
						</select>
					</td>
					
					<td><a data-id="`+data.data.id+`" class="grid_label action-btn show-icon view edit"><i class="fa fa-eye"></i> </a></td>
					</tr>`,`edit`,data.message, data.data.id);

				$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
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