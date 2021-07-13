<div class="alert alert-danger exist_year">
    {{ Session::get('flash_message') }}
</div>
<div class="modal-header">
	<h4 class="modal-title float-right">SMS Template</h4>
</div>

	{!! Form::model($sms_template, ['class' => 'form-horizontal validateform'
		 ]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">			
	<div class="row" style="margin-left: 40px;margin-right: 40px">
			<div class="form-group col-md-12"> 
				{{ Form::label('summary', 'Summary', array('class' => 'control-label required')) }}		
				{!! Form::text('sms_type', null,['class' => 'form-control']) !!}
			</div>
	</div>
	<div class="row" style="margin-left: 25px;margin-right: 25px">
			<div class="col-md-12">
				<div class="form-group">
				{!! Form::label('Smstext', 'SMS Text', array('class' => 'control-label col-md-12 required')) !!}
				<div class="col-md-12">
				{{ Form::textarea('sms_content', null, ['class' => 'form-control','rows' =>04]) }}
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

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			sms_type:{ required: true },
			sms_content:{ required: true },
			},
			messages: {
			sms_type: { required: " Summary is required."},
			sms_content: { required: " SMS Text is required."},
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
			@isset($sms_template->id)
			url: '{{ route('sms_template_store',["id"=>$sms_template->id]) }}',
			@else
			url: '{{ route('sms_template_store') }}',
			@endisset
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				summary: $('input[name=sms_type]').val(),
				smstext:$('textarea[name=sms_content]').val(),
				 
				},
			success:function(data, textStatus, jqXHR) {
				var sms_template=data.data.sms_template;
				var example =sms_template.sms_content;
				var result=example.slice(0, 50)+'...';
				call_back(`<tr role="row" class="odd">			
				    <td>`+sms_template.sms_type+`</td>
				    <td>`+result+`</td>	
					<td>
						<a data-id="`+sms_template.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+sms_template.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td>
					</tr>`,`edit`,data.message,sms_template.id);
				$('.loader_wall_onspot').hide();
				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});



</script>