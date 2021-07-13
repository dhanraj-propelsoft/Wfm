<div class="modal-header">
	<h4 class="modal-title float-right">Sms Allocation </h4>
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
					{!! Form::label('organization', 'Organization', ['class' => ' control-label required']) !!}
				
					{!! Form::select('organization',$organization_data,null,['class' => 'form-control select_item','id' => 'organization']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('payment_reference_id', 'Payment Reference Id', array('class' => 'control-label  required','id'=>'payment_reference_id')) !!}

					<div class="form-group">
						{!! Form::text('payment_reference_id', null,['class' => 'form-control','maxlength'=>"15"]) !!}
					</div>
				</div>
			</div>
		</div>	
		<div class="row">
		 	<div class="col-md-12">
		 		<div class="form-group">
               		{!! Form::label('payment_date', 'Payment Date', array('class' => 'control-label  required')) !!}

               	<div class="form-group">
					{{ Form::text('payment_date', null, ['class'=>'form-control date-picker datetype extend', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off' ]) }}
				</div>
				</div>				 
            </div>
		</div>		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('payment', 'Paid Amount', array('class' => 'control-label  required','id'=>'payment')) !!}

					<div class="form-group">
						{!! Form::number('payment', null,['class' => 'form-control']) !!}
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('sms_limit', 'Sms Limit', array('class' => 'control-label  required','id'=>'sms_limit')) !!}

					<div class="form-group">
						{!! Form::number('sms_limit', null,['class' => 'form-control']) !!}
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

<script>
	$(document).ready(function() {

		

		basic_functions();
	});
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			organization:{ required : true },
			payment_reference_id: { required: true },
			payment_date: { required: true },
			payment: { required: true },
			sms_limit: { required: true }
			},

		messages: {
			organization: { required: "Organization Is Mandatetory" },
			payment_reference_id: { required: "Payment Reference No Is Mandatetory" },
			payment_date: { required: "Payment Date Is Mandatetory" },
			payment: { required: "Paid Amount Is Mandatetory" },
			sms_limit: { required: "Sms Limit Is Mandatetory" }
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
			url: '{{ route('smsAllocation.Store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				 organization: $('select[name=organization]').val(),
				 payment_reference_id: $('input[name=payment_reference_id]').val(),
				 payment_date: $('input[name=payment_date]').val(),
				 payment: $('input[name=payment]').val(),
				 sms_limit: $('input[name=sms_limit]').val(),
				 sms_ledger_type:"credit"
				     
				},
			success:function(data, textStatus, jqXHR) {
			    if(data.message == " SUCCESS")
			    {
			    call_back(`<tr role="row" class="odd">
                			<td>`+data.data.pDate+`</td>
                		    <td>`+data.data.pDescription+`</td>
                			<td>`+data.data.pCredit+`</td>
                			<td>`+data.data.pDebit+`</td>
                			<td>`+data.data.pBalance+`</td>
                			</tr>`,`add`,data.message, data.data.id);


			    }


				$('.loader_wall_onspot').hide();
				$('.crud_modal').modal('hide');

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
  if (selectedDate > now) {
    alert("Not Allow Future Date");
  } 
});
</script>