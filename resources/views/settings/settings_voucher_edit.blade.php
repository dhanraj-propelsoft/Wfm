<div class="modal-header">
	<h4 class="modal-title float-right">Edit Voucher</h4>
</div>

	{!!Form::model($vouchers, [
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		<div class="row">
			
			<div class="form-group col-md-6">
				{!! Form::label('display_name', 'Voucher Name', array('class' => 'control-label col-md-6 required')) !!}

				<div class="col-md-12">
					{!! Form::text('display_name', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				{!! Form::label('code', 'Voucher Code', array('class' => 'control-label col-md-4 required')) !!}

				<div class="col-md-12">
					{!! Form::text('code', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group col-md-6">
				{!! Form::label('voucher_type_id', 'Voucher Type', array('class' => 'control-label col-md-4 required')) !!}

				<div class="col-md-12">
					{{Form::select('voucher_type_id', $voucher_type, null, ['class'=>'form-control select_item'])}}
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="form-group col-md-6">
				{!! Form::label('date_setting', 'Date', ['class' => 'control-label col-md-4']) !!}

				<div class="col-md-12">
				<div class="row">
				{{ Form::radio('date_setting','0', null, ['class' => 'md-radiobtn', 'id'=> 'current_date']) }}
				<label class="control-label col-md-6" for="current_date"><span></span>Current Date</label>

				{{ Form::radio('date_setting','1', null, ['class' => 'md-radiobtn', 'id'=> 'custom_date']) }}
				<label class="control-label col-md-6" for="custom_date"><span></span>Custom Date</label>
				</div>
			</div>
			</div>
			<div class="form-group col-md-3">
				{!! Form::label('starting_value', 'Starting Value', array('class' => 'control-label col-md-4 required')) !!}

				<div class="col-md-12">
					{{Form::text('starting_value', $vouchers->starting_value, ['class'=>'form-control numbers'])}}
				</div>
			</div>
			<div class="form-group col-md-3">
				
				<input id="to_restart" type="checkbox" name="to_restart" value="0" @if($vouchers->restart == 1) checked @endif/>
										
				<label for="to_restart" ><span></span>To restart</label>
				<div>
				{!! Form::label('last_restarted_date', 'Last Restarted', array('class' => 'control-label ')) !!}
				{{Form::text('last_restarted_date', $vouchers->last_restarted, ['class'=>'form-control numbers','disabled'])}}
				</div>
				
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				{!! Form::label('format_id', 'Voucher Numbering Format', array('class' => 'control-label col-md-8 required')) !!}

				<div class="col-md-12">
					{{Form::select('format_id', $voucher_format, null, ['class'=>'form-control select_item'])}}
				</div>
			</div>
				<div class="form-group col-md-6">
				{!! Form::label('print_id', 'Voucher Printing Format', array('class' => 'control-label col-md-6 required')) !!}

				<div class="col-md-12">
					<div class="common_print">
					{{Form::select('print_id', $printing_format, null, ['class'=>'form-control select_item'])}}
                    </div>
					<div class="multi_print">
	                   	@if($vouchers->voucher_type_id == 22)
	                     {{ Form::select('job_invoice_printformate', $multi_print_formates,$print_templates, ['id' => 'print_id', 'multiple' => 'multiple','class'=>'form-control select_item job_invoice_printformate','style'=>'width: 100%;']) }}
	                    @elseif($vouchers->voucher_type_id == 21)
	                    {{ Form::select('job_invoice_printformate', $multi_print_formates,$sales_templates, ['id' => 'print_id', 'multiple' => 'multiple','class'=>'form-control select_item job_invoice_printformate','style'=>'width: 100%;']) }}
	                    @endif
	               </div>
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
		
		var  voucher_id = $('select[name=voucher_type_id]').val();
              if(voucher_id === "22" || voucher_id === "21"){
                  $('.multi_print').show();
                  $('.common_print').hide();
              }else{
              	$('.common_print').show();
              	$('.multi_print').hide();
              }

		$('select[name=voucher_type_id]').on('change',function(){
              var  voucher_id = $(this).val();
                   
              if(voucher_id === "22" || voucher_id === "21"){
                  $('.multi_print').show();
                  $('.common_print').hide();
              }else{
              	 $('.common_print').show();
              	 $('.multi_print').hide();
              }
		});
		$('input[name=to_restart]').on('change',function(){
			//alert();
			if($(this).prop("checked") == true)
			{
			 $('input[name=to_restart]').val(1);

			}
			else
			{
			 $('input[name=to_restart]').val(0);

			}

		});
	});

	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			
			display_name: { required: true },
			code: { required: true },                
			voucher_type_id: { required: true },                
			format_id: { required: true },                
			print_id: { required: true },
		},

		messages: {
		
			display_name: { required: "Display Name is required." },
			code: { required: "Voucher Code is required." },
			voucher_type_id: { required: "Voucher Type is required." },
			format_id: { required: "Voucher Format Number is required." },
			print_id: { required: "Voucher Print Format is required." },
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
			url: '{{ route('voucher_list.update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),				
				display_name: $('input[name=display_name]').val(),
				code: $('input[name=code]').val(),
				starting_value: $('input[name=starting_value]').val(),
				voucher_type_id: $('select[name=voucher_type_id]').val(),
				format_id: $('select[name=format_id]').val(),
				print_id: $('select[name=print_id]').val(),
				multi_print_id: $('select[name=job_invoice_printformate]').val(),
				date_setting: $("input[name=date_setting]:checked").val(),
				restart_value : $('input[name=to_restart]').val(),

				      
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td>`+data.data.display_name+`</td>
					<td>`+data.data.voucher_type_id+`</td>
					<td>`+data.data.code+`</td>					
					<td>
					<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
					<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					</td></tr>`, `edit`, data.message, data.data.id);

				$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>