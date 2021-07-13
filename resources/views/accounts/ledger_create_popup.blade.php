<div class="modal-header">
	<h4 class="modal-title float-right">Create Ledger</h4>
</div>
	{!! Form::open(['class' => 'form-horizontal ledger_validateform']) !!}
	{{ csrf_field() }}
<div class="modal-body">
	<h5>Impersonal account</h5>
		<div class="form-body">
			<div class="form-group">
				{!! Form::label('ledger_name', 'Ledger Name', array('class' => 'control-label col-md-5 required')) !!}
				<div class="col-md-12">
				{{ Form::text('ledger_name', null, ['class' => 'form-control display_name']) }}
				</div>
			</div>
			<div class="form-group">						 
				{!! Form::label('ledger_group', 'Parent', array('class' => 'control-label col-md-3 ')) !!}
				<div class="col-md-12">
				{!! Form::select('ledger_group',[], null, ['class'=>'form-control select_item']) !!}
				</div>
			</div>
			<div class="form-group"> 
				{!! Form::label('account_head', 'Account Head', array('class' => 'control-label col-md-5  ')) !!}
				<div class="col-md-12"> 
				{!! Form::text('account_head', null, ['class' => 'form-control','disabled']) !!} 
				</div>
		  	</div>
		  	<div class="form-group"> 
		  				{!! Form::label('opening_balance_type', 'Type', array('class' => 'control-label col-md-4  ')) !!}
						<div class="col-md-12"> 
						{!! Form::select('opening_balance_type', ['' => 'Select', 'debit' => 'Dr', 'credit' => 'Cr'],null,['class' => 'form-control select_item type']) !!} </div>
			</div>
				<div class="row">
		  			<div style="display:none" class="form-group col-md-4 opening_balance_date"> {!! Form::label('opening_balance_date', 'Opening Balance on', array('class' => 'control-label col-md-12 required')) !!}
						<div class="col-md-12"> {!! Form::text('opening_balance_date', date('d-m-Y'), ['class' => 'form-control accounts-date-picker', 'data-date-format' => 'dd-mm-yyyy']) !!} </div>
		  			</div>
		  			<div style="display:none" class="form-group col-md-4 opening_balance"> {!! Form::label('opening_balance','Opening Balance', ['class' => 'col-md-12 control-label']) !!}
						<div class="col-md-12"> {!! Form::text('opening_balance', null, ['class'=>'form-control price']) !!} </div>
		  			</div>
				</div>
		  
	</div>
</div>
<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="submit" class="btn btn-success">Save</button>
</div>	
{!! Form::close() !!}
<script>
$(document).ready(function() {
		basic_functions();
		$('.type').prop("disabled", true);
		if($('select[name=opening_balance_type]').val() == "") 
		{
			$('select[name=opening_balance_type]').val('Debit').trigger('change');
		}		
			var id = {!! $ledger_types !!};
			var ledger_group_id = $("select[name=ledger_group]");
			$('.loader_wall_onspot').show();
				$.ajax({
					url: "{{ route('get_ledger_group') }}",
					type: 'get',
					data: {
						_token :$('input[name=_token]').val(),
						id: id
						},
						success:function(data_ledger, textStatus, jqXHR) {
							ledger_group_id.append(data_ledger);
							@isset($ledger_group_type_id)
							ledger_group_id.val({!! $ledger_group_type_id !!}).trigger("change");
							ledger_group_id.prop("disabled", true);
							@endisset		
							$('.loader_wall_onspot').hide();
						},
					 error:function(jqXHR, textStatus, errorThrown) {
						//alert("New Request Failed " +textStatus);
					}
				});


		$('select[name=ledger_group]').on('change', function()
			{
			var value = $(this).val();
			var type  = $(this).find("option:selected").data("type");

			if(value == "")
			{
				$('input[name=account_head]').val("");
			}
			else if(value != "") 
			{
				$('.loader_wall_onspot').show();
				$.ajax({
				 url: '{{ route('parent_group') }}',
				 type: 'get',
				 data: {
					id: value
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						//console.log(data);
						if(data[0].display_name == "Asset" || data[0].display_name == "Liabilities") {
							$('.opening_balance_date, .opening_balance').show();
						} else {
							$('.opening_balance_date, .opening_balance').hide();
						}

						$('input[name=account_head]').val(data[0].display_name);
						$('select[name=opening_balance_type]').val(type).trigger('change');
						$('.loader_wall_onspot').hide();
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
			}
		});
	});
$('.ledger_validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input

		rules: {
			ledger_name: { 
				required: true,
					remote: {
						url: '{{ route('check_ledgers') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val()
						}
					} 
				},
			ledger_type: { required: true },
			person_id: { required: true },
			business_id: { required: true },
			opening_balance_date: { required: true },
			account_type: { required: true },
			ledger_group: { required: true },
		},

		messages: {
			ledger_name: { 
				required: "Name is required.",
				remote: "Ledger name already exists!" 
			},
			ledger_type: { required: "Ledger Type Name is required." },
			person_id: { required: "Person Name is required." },
			business_id: { required: "Business Name is required." },
			opening_balance_date: { required: "Opening Date is required." },
			account_type: { required: "Account Type is required." },
			ledger_group: { required: "Parent Name is required." },
		},        

		invalidHandler: function(event, validator) { //display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
		},

		highlight: function(element) { // hightlight error inputs
			$(element)
				.closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},

		submitHandler: function(form) {
			$('.loader_wall_onspot').show();
			$.ajax({
			url: "{{ route('ledgers.store') }}",
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=ledger_name]').val(),
				display_name: $('input[name=ledger_name]').val(),
				ledger_type: {!! $ledger_types !!},
				account_head: $('input[name=account_head]').val(),
				opening_balance_date: $('input[name=opening_balance_date]').val(),
				opening_balance: $('input[name=opening_balance]').val(),
				opening_balance_type: $('select[name=opening_balance_type]').val(),
				group_id: $('select[name=ledger_group]').val(),
				
			},
			dataType: "json",
				success:function(data, textStatus, jqXHR) {
					$('.loader_wall_onspot').hide();
					$('.crud_modal_sm').modal('hide');
					 var newadd = new Option(data.data.name, data.data.id, true, true);
				 	$('.expense_ledger').append(newadd).trigger('change');
					
				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
</script>