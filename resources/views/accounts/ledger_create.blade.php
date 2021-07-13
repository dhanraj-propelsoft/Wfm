
<div class="content">
  <div class="fill header">
	<h3 class="float-left">Create Ledger</h3>
	<div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i></div>
  </div>
  <div class="clearfix"></div>
{!! Form::open(['class' => 'form-horizontal validateform']) !!}										
	{{ csrf_field() }}
<div class="modal-body">
  	<ul class="nav nav-tabs">
		<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#ledger_info">Ledger Info</a> </li>
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#credit_info">Credit Info</a> </li>
  	</ul>
  	<div class="tab-content" style="padding: 10px;">
		<div class="tab-pane active" id="ledger_info"> <br>
	  		<div class="form-body">
	  			{!! Form::hidden('id', null) !!}

	  			

				<div class="row">
		  			<div class="form-group col-md-12"> {!! Form::label('ledger_type', 'Ledger Type', array('class' => 'control-label col-md-6 required')) !!}
						<div class="col-md-12"> 
							@foreach($ledger_types as $ledger_type)
			  				<div style="float: left;" class="tooltips col-md-3" data-container="body" data-placement="top" data-original-title="<?php if($ledger_type->name == "personal") { echo 'Ledger such as Staff, Customer, Vendor and Trade agent etc'; } elseif($ledger_type->name == "impersonal") { echo 'Internal accounts such as Income, Expenses, Stock, etc'; } elseif($ledger_type->name == "bank") { echo 'Scheduled, Private and National Bank with IFSC & MICR only'; } elseif($ledger_type->name == "nbfc") { echo 'Finance and loan Labiality accounts other than Scheduled, Private and National Bank'; } ?>">
								<input class="md-radiobtn" id="{{$ledger_type->name}}" type="radio" name="ledger_type" <?php if($ledger_type->name == "personal") { echo 'checked="checked"'; } ?> value="{{$ledger_type->id}}">
								<label for="{{$ledger_type->name}}"> <span style="margin-right: 5px; "></span>{{$ledger_type->display_name}}</label>
			  				</div>
			  				@endforeach
			  			</div>
		  			</div>
				</div>
				<div class="row personal ">
				  	<div class="form-group col-md-12"> {!! Form::label('', 'User Type', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-10"> 
							@foreach($person_types as $person_type)
					  		<div style="float: left;" class="col-md-3"> {{ Form::checkbox('person_type',$person_type->id, null, ['id' => $person_type->id]) }}
								<label for="{{$person_type->id}}"><span></span>{{$person_type->display_name}}</label>
					  		</div>
					  		@endforeach
					  	</div>
				  	</div>
				</div>
				<div style="display: none;" class="row personal">
		  			<div class="form-group col-md-6"> {!! Form::label('type_of_account', 'Account Type', array('class' => 'control-label col-md-6 required')) !!}
						<div class="col-md-10">
						  	<input id="people_type" type="radio" name="customer" checked="checked" />
						  	<label for="people_type"><span></span>People</label> &nbsp;&nbsp;&nbsp;
						  	<input id="business_type" type="radio" name="customer" />
						  	<label for="business_type"><span></span>Business</label>
						</div>
				  	</div>
		  			<div class="form-group col-md-6 people"> {!! Form::label('person_id', 'Person', array('class' => 'control-label col-md-6 required')) !!}
						<div class="col-md-12 search_container"> 
						{{ Form::select('person_id', $people, null, ['class' => 'form-control person_id', 'id' => 'person_id']) }}
						{{ Form::checkbox('user_type', '0', true, ['id' => 'user_type']) }}
						{{ Form::checkbox('account_person_type_id', "", true, ['id' => 'account_person_type_id']) }}
			  				<div class="content"></div>
						</div>
		  			</div>
		  			<div style="display: none;" class="form-group col-md-6 business"> {!! Form::label('business_id', 'Business', array('class' => 'control-label col-md-6 required')) !!}
						<div class="col-md-12 search_container"> 
						{{ Form::select('business_id', $business, null, ['class' => 'form-control business_id']) }}
						{{ Form::checkbox('user_type', '1', true, ['id' => 'user_type']) }}
						{{ Form::checkbox('account_person_type_id', "", true, ['id' => 'account_person_type_id']) }}
			  				<div class="content"></div>
						</div>
		  			</div>
				</div>
				<div class="row">
		  			<div class="form-group col-md-4"> {!! Form::label('ledger_name', 'Ledger Name', array('class' => 'control-label col-md-6 required')) !!}
						<div class="col-md-12"> {{ Form::text('ledger_name', null, ['class' => 'form-control display_name']) }}
						</div>
		  			</div>
		  			<div class="form-group col-md-4"> {!! Form::label('ledger_group', 'Parent', array('class' => 'control-label col-md-12 required')) !!}
						<div class="col-md-12"> {!! Form::select('ledger_group', [], null, ['class'=>'form-control select_item']) !!} </div>
		  			</div>
		  			<div class="form-group col-md-4"> {!! Form::label('account_head', 'Account Head', array('class' => 'control-label col-md-12 required')) !!}
						<div class="col-md-12"> {!! Form::text('account_head', null, ['class' => 'form-control', 'disabled']) !!} </div>
		  			</div>
				</div>
				<div class="row">
		  			<div style="display:none" class="form-group col-md-4 opening_balance_date"> {!! Form::label('opening_balance_date', 'Opening Balance on', array('class' => 'control-label col-md-12 required')) !!}
						<div class="col-md-12"> {!! Form::text('opening_balance_date', date('d-m-Y'), ['class' => 'form-control accounts-date-picker', 'data-date-format' => 'dd-mm-yyyy']) !!} </div>
		  			</div>
		  			<div style="display:none" class="form-group col-md-4 opening_balance"> {!! Form::label('opening_balance','Opening Balance', ['class' => 'col-md-12 control-label']) !!}
						<div class="col-md-12"> {!! Form::text('opening_balance', null, ['class'=>'form-control price']) !!} </div>
		  			</div>
		  			<div class="form-group col-md-4"> {!! Form::label('opening_balance_type', 'Type', array('class' => 'control-label col-md-4 required')) !!}
						<div class="col-md-12"> {!! Form::select('opening_balance_type', ['' => 'Select', 'debit' => 'Dr', 'credit' => 'Cr'],null,['class' => 'form-control select_item']) !!} </div>
				  	</div>
				</div>
				<div style="display:none" class="row bank"">
				  	<div  class="form-group col-md-4"> {!! Form::label('bank_name', 'Bank Name', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-12"> {!! Form::select('bank_name', $bank, null, ['class' => 'select_item form-control','id'=> 'banklist' ]) !!} </div>
				 	</div>
				  	<div class="form-group col-md-4"> {!! Form::label('account_type','A/c Type', ['class' => 'col-md-6 control-label']) !!}
						<div class="col-md-12">{!! Form::select('account_type', $account_type, null, ['class' => 'select_item form-control' ]) !!} </div>
				  	</div>
				  	<div class="form-group col-md-4"> {!! Form::label('account_no', 'A/c NO', array('class' => 'control-label col-md-4')) !!}
						<div class="col-md-12">{!! Form::text('account_no',null,['class'=>'form-control']) !!} </div>
				  	</div>
				</div>
				<div style="display:none" class="row nbfc"">
				  	<div class="form-group col-md-4"> {!! Form::label('nbfc_name', 'NBFC Name', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-12"> {!! Form::text('nbfc_name',null,['class'=>'form-control']) !!} </div>
				  	</div>
				  	<div class="form-group col-md-4"> {!! Form::label('nbfc_branch', 'NBFC Branch', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-12">{!! Form::text('nbfc_branch',null,['class'=>'form-control']) !!} </div>
				  	</div>
				</div>
				<div style="display:none" class="row bank">
		  			<div class="form-group col-md-4"> {!! Form::label('state', 'State', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-12"> {!! Form::select('state', ['' => 'Select State'], null, ['class' => 'select_item form-control' ,'id'=> 'state' ]) !!} </div>
		  			</div>
		  			<div class="form-group col-md-4"> {!! Form::label('city','City', ['class' => 'col-md-6 control-label']) !!}
						<div class="col-md-12">{!! Form::select('city', ['' => 'Select City'], null, ['class' => 'select_item form-control','id'=> 'city' ]) !!} </div>
		  			</div>
		  			<div class="form-group col-md-4"> {!! Form::label('bank_branch', 'Bank Branch', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-12">{!! Form::select('bank_branch', ['' => 'Select Branch' ], null, ['class' => 'select_item form-control','id'=> 'branch' ]) !!} </div>
				  	</div>
				</div>
				<div style="display:none" class="row bank">
				  	<div class="form-group col-md-4"> {!! Form::label('micr', 'MICR Code', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-12"> {!! Form::text('micr',null,['class'=>'form-control', 'readonly']) !!} </div>
				  	</div>
				  	<div class="form-group col-md-4"> {!! Form::label('ifsc','IFSC Code', ['class' => 'col-md-6 control-label']) !!}
						<div class="col-md-12">{!! Form::text('ifsc',null,['class'=>'form-control', 'readonly']) !!} </div>
				  	</div>
				</div>
				<div style="display:none" class="row bank">
				  	<div class="form-group col-md-12"> {!! Form::checkbox('cheque_book','1', null, ['class' => 'col-md-6 control-label', 'id' => 'cheque_book']) !!}
				  		<label for="cheque_book" class="col-md-6"><span></span>Enable Cheque Book</label>
				  	</div>
				</div>

				<div class="cheque"> 
					<div class="row">
					  	<div class="form-group col-md-6"> {!! Form::label('book_no', 'Book No', array('class' => 'control-label col-md-6')) !!}
							<div class="col-md-12"> {!! Form::text('book_no',null,['class'=>'form-control']) !!} </div>
					  </div>
					</div>
					<div class="row">
					  	<div class="form-group col-md-4"> {!! Form::label('no_of_leaves','No. of Leaves', ['class' => 'col-md-12 control-label']) !!}
							<div class="col-md-12">{!! Form::text('no_of_leaves',null,['class'=>'form-control']) !!} </div>
					  	</div>
					  	<div class="form-group col-md-4"> {!! Form::label('cheque_no_from', 'Cheque No. From', array('class' => 'control-label col-md-12')) !!}
							<div class="col-md-12"> {!! Form::text('cheque_no_from',null,['class'=>'form-control']) !!} </div>
					  	</div>
					  	<div class="form-group col-md-4"> {!! Form::label('cheque_no_to','Cheque No. To', ['class' => 'col-md-12 control-label']) !!}
							<div class="col-md-12">{!! Form::text('cheque_no_to',null,['class'=>'form-control', 'readonly']) !!} </div>
					  	</div>
					</div>
					<div class="row">
					  	<div class="form-group col-md-12"> {!! Form::label('next_book_warning', 'Next Book Alert at (Chq No.)', array('class' => 'control-label col-md-6')) !!}
							<div class="col-md-6"> {!! Form::text('next_book_warning',null,['class'=>'form-control']) !!} </div>
					  	</div>
					</div>
				</div>
	  		</div>
		</div>
		<div class="tab-pane" id="credit_info">
	  		<div class="form-body">

	  			<div class="row">
				  	<div class="form-group col-md-4"> {!! Form::label('credit_period', 'Credit Period', array('class' => 'control-label col-md-6')) !!}
						<div class="col-md-12"> {{ Form::text('credit_period', null, ['class' => 'form-control']) }}</div>
				  	</div>
				</div>
				<div class="row">
				  	<div class="form-group col-md-4">
				   		{!! Form::checkbox('debit_limit','1', null, ['class' => 'col-md-6 control-label', 'id' => 'debit_limit']) !!}
				  		<label for="debit_limit" class="col-md-12"><span></span>Debit Limit</label>
				  	</div>
				  	<div class="form-group col-md-4"> {!! Form::label('min_debit_limit','Minimum', ['class' => 'col-md-6 control-label']) !!}
						<div class="col-md-12">{{ Form::text('min_debit_limit', null, ['class' => 'form-control numbers', 'readonly']) }} </div>
				  	</div>
				  	<div class="form-group col-md-4"> {!! Form::label('max_debit_limit','Maximum', ['class' => 'col-md-6 control-label']) !!}
						<div class="col-md-12">{{ Form::text('max_debit_limit', null, ['class' => 'form-control numbers', 'readonly']) }} </div>
				  </div>
				</div>

				<div class="row">
		  			<div class="form-group col-md-4">
					   	{!! Form::checkbox('credit_limit','1', null, ['class' => 'col-md-6 control-label', 'id' => 'credit_limit']) !!}
					  	<label for="credit_limit" class="col-md-12"><span></span>Credit Limit</label>
					</div>
					<div class="form-group col-md-4"> {!! Form::label('min_credit_limit','Minimum', ['class' => 'col-md-6 control-label']) !!}
						<div class="col-md-12">{{ Form::text('min_credit_limit', null, ['class' => 'form-control numbers', 'readonly']) }}</div>
					</div>
					<div class="form-group col-md-4"> {!! Form::label('max_credit_limit','Maximum', ['class' => 'col-md-6 control-label']) !!}
						<div class="col-md-12">{{ Form::text('max_credit_limit', null, ['class' => 'form-control numbers', 'readonly']) }} </div>
					</div>
				</div>
	  		</div>
		</div>
  	</div>
</div>

<div class="save_btn_container">
  	<button type="reset" class="btn btn-default clear cancel_transaction">Cancel</button>
  	<button style="float:right" type="submit" class="btn btn-success">Save </button>
  	<!-- <div style="margin:-25px auto 0px; width: 150px;"><a class="make_recurring"> Make Recurring</a></div> -->
  </div>


{!! Form::close() !!} 
</div>

<script type="text/javascript">

	var current_select_item = null;

	$(document).ready(function() {

		$('.cancel_transaction').on('click', function(e) {
			e.preventDefault();
			$('.close_full_modal').trigger('click');
		});

		$('input[name=customer]').on('change', function(){
			$('select[name=person_id], select[name=business_id]').val("");
			$('select[name=person_id], select[name=business_id]').trigger("change");
		});

		$('#people_type').on('click', function(){
			$('.people').show();
			$('.business').hide();
			$('.people').find('select').prop('disabled', false);
			$('.business').find('select').prop('disabled', true);
		});

		$('#business_type').on('click', function(){	
			$('.business').show();
			$('.people').hide();
			$('.business').find('select').prop('disabled', false);
			$('.people').find('select').prop('disabled', true);
		});

		$('select[name=person_id]').each(function() {
			//$(this).prepend('<option value="0"></option>');
			select_user($(this));
		});

		$('select[name=business_id]').each(function() {
			//$(this).prepend('<option value="0"></option>');
			select_business($(this));
		});

		$('select[name=parent_id]').on('change', function()
		{
			var value = $(this).val();
			if(value == "") 
			{
				$('input[name=account_head]').val("");
			} 
			else if(value != "") 
			{
				$('.loader_wall_onspot').show();
				$.ajax({
				url: "{{ route('parent_group') }}",
				type: 'get',
				data: {
					id: value
					},
				dataType: "json",
					success:function(data, textStatus, jqXHR) {
						console.log(data);
						$('input[name=account_head]').val(data[0].display_name);					
						$('.loader_wall_onspot').hide();
					},
				error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
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

		change_radio(1);

		basic_functions();

		$("input[name=ledger_type]").change(function () {
			change_radio($(this).val());
		});	

		$("input[name=cheque_book]").change(function () {
			if($('input[name=cheque_book]').is(':checked')) { 
				$('.cheque').show();
			} else {
				$('.cheque').hide();
			}
		});

		$('input[name=no_of_leaves], input[name=cheque_no_from]').keyup(function() {
		  	if($(this).val() != 0) {
				var leaves = parseInt($('input[name=no_of_leaves]').val());
				var from = parseInt($('input[name=cheque_no_from]').val());
				$('input[name=cheque_no_to]').val(isNaN((leaves+from)-1) ? 0 : (leaves+from)-1);
		  	} else {
				$('input[name=cheque_no_to]').val("");
		  	} 
	  	});

		$("input[name=debit_limit]").change(function () {
			if($(this).is(':checked')) { 
				$(this).closest('.row').find('input[name=min_debit_limit]').prop('readonly', false); 
				$(this).closest('.row').find('input[name=max_debit_limit]').prop('readonly', false); 
			} else if(!$(this).is(':checked')) { 
				$(this).closest('.row').find('input[name=min_debit_limit]').prop('readonly', true); 
				$(this).closest('.row').find('input[name=max_debit_limit]').prop('readonly', true); 
			}
		});

		$("input[name=credit_limit]").change(function () {
			if($(this).is(':checked')) { 
				$(this).closest('.row').find('input[name=min_credit_limit]').prop('readonly', false); 
				$(this).closest('.row').find('input[name=max_credit_limit]').prop('readonly', false); 
			} else if(!$(this).is(':checked')) { 
				$(this).closest('.row').find('input[name=min_credit_limit]').prop('readonly', true); 
				$(this).closest('.row').find('input[name=max_credit_limit]').prop('readonly', true); 
			}
		});

		$('select[name=bank_name]').on('change', function(e) {
			var bank = $(this).val();
			$('.loader_wall_onspot').show();

			$.get("{{route('get_bank_state')}}?bank=" + bank, function(data) {
				$('.loader_wall_onspot').hide();

				$('select[name=state]').empty();
				$('select[name=state]').append('<option value="">Select State</option>');
				$('select[name=city]').empty();
				$('select[name=city]').append('<option value="">Select City</option>');
				$('select[name=bank_branch]').empty();
				$('select[name=bank_branch]').append('<option value="">Select Branch</option>');
				$('input[name=ifsc], input[name=micr]').val();

				$.each(data['state'], function(index, data) {
					$('select[name=state]').append('<option value="'+data.state+'">'+data.state+'</option>');
				});
				$('select[name=state], select[name=city], select[name=bank_branch]').val("").trigger("change");

			});
		});

		$('select[name=state]').on('change', function(e) {
			var state = $(this).val();
			var bank = $('select[name=bank_name]').val();
			$('.loader_wall_onspot').show();

			$.get("{{route('get_bank_city')}}?state=" + state + '&bank=' + bank, function(data) {
				$('.loader_wall_onspot').hide();

				$('select[name=city]').empty();
				$('select[name=city]').append('<option value="">Select City</option>');
				$('select[name=bank_branch]').empty();
				$('select[name=bank_branch]').append('<option value="">Select Branch</option>');
				$('input[name=ifsc], input[name=micr]').val();

				$.each(data['city'], function(index, data) {
					$('select[name=city]').append('<option value="' + data.city + '">' + data.city + '</option>');
				});
			});
		});

		$('select[name=city]').on('change', function(e) {

			var city = $(this).val();
			var bank = $('select[name=bank_name]').val();
			var state = $('select[name=state]').val();
			$('.loader_wall_onspot').show();

			$.get("{{route('get_bank_branch')}}?city=" + city + '&bank=' + bank + '&state=' + state, function(data) {
				$('.loader_wall_onspot').hide();

				$('select[name=bank_branch]').empty();
				$('select[name=bank_branch]').append('<option value="">Select Branch</option>');
				$('input[name=ifsc], input[name=micr]').val();

				$.each(data['branch'], function(index, data) {
					$('select[name=bank_branch]').append('<option value="' + data.branch + '">' + data.branch + '</option>');
				});
			});
		});

		$('select[name=bank_branch]').on('change', function(e) {

			var branch = $(this).val();
			var bank = $('select[name=bank_name]').val();
			var state = $('select[name=state]').val();
			var city = $('select[name=city]').val();
		   
			$('.loader_wall_onspot').show();

			$.get("{{route('get_bank_code')}}?branch=" + branch+ '&bank=' + bank + '&state=' + state+ '&city=' + city, function(data) {
				$('.loader_wall_onspot').hide();

				$('input[name=ifsc]').val(data.ifsc);
				$('input[name=micr]').val(data.micr);
			});
		});

		$('#nbfc_name').on('change', function(e) {
		    //console.log(e);
		    var cat_id = e.target.value;
		    $('.loader_wall_onspot').show();
		    //ajax
		    $.get("{{route('get_nbfc_branch')}}?cat_id=" + cat_id, function(data) {
		    	$('.loader_wall_onspot').hide();
		        //success data
		        $('#nbfc_branch').empty(); 
		        $('.nbfc_branch span.select2-chosen').text("Select Branch");
		        $('#nbfc_branch').append('<option value="">Select Branch</option>');
		        $.each(data['branch'], function(index, subcatObj) {
		            $('#nbfc_branch').append('<option value="' + subcatObj.branch + '">' + subcatObj.branch + '</option>');
		        });
		    });
		});

		function change_radio(current) {
		if($('select[name=opening_balance_type]').val() == "") 
		{
			$('select[name=opening_balance_type]').val('Debit').trigger('change');
		}		
			var id = current;
			var ledger_group_id = $("select[name=ledger_group]");

			ledger_group_id.empty();
			ledger_group_id.append("<option value=''>Select Ledger Group</option>");
			ledger_group_id.val("").trigger("change");
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
							$('.loader_wall_onspot').hide();
						},
					 error:function(jqXHR, textStatus, errorThrown) {
						//alert("New Request Failed " +textStatus);
					}
				});

				ledger_type_check();
				
				$('input:not(input[type=button]):not(input[type=submit]):not(input[type=reset]):input:not(input[name=_token]):not(input[type=radio]):not(input[type=checkbox]):not(input[name=opening_balance_date]):not(input[name=opening_balance]):not(input[name=user_type]):not(input[name=account_person_type_id]), select:not(select[name=opening_balance_type])').val("");
		}

		function ledger_type_check() {
			$('.cheque').hide();
			$(':checkbox:not(input[name=user_type]):not(input[name=account_person_type_id])').prop('checked', false);
			$('.personal, .impersonal, .bank, .nbfc').hide();

			if($('#personal').is(':checked')) { 
				$('.personal').show();
			} else if($('#impersonal').is(':checked')) {
				$('.impersonal').show();
			} else if($('#bank').is(':checked')) {
				$('.bank').show();
			} else if($('#nbfc').is(':checked')) {
				$('.nbfc').show();
			}
		}
	});

	$('.validateform').validate({
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
				person_type : $('input[name="person_type"]:checked').map(function(){
			        return this.value;
			    }).get(),
				ledger_type: $('input[name=ledger_type]:checked').val(),
				person_id: $('select[name=person_id]').val(),
				business_id: $('select[name=business_id]').val(),
				account_head: $('input[name=account_head]').val(),
				opening_balance_date: $('input[name=opening_balance_date]').val(),
				opening_balance: $('input[name=opening_balance]').val(),
				opening_balance_type: $('select[name=opening_balance_type]').val(),
				account_type: $('select[name=account_type]').val(),
				account_no: $('input[name=account_no]').val(),
				bank_name: $('select[name=bank_name]').val(),
				bank_branch: $('select[name=bank_branch]').val(),
				ifsc: $('input[name=ifsc]').val(),
				micr: $('input[name=micr]').val(),
				micr: $('input[name=micr]').val(),
				cheque_book: $('input[name=cheque_book]:checked').val(),
				book_no: $('input[name=book_no]').val(),
				no_of_leaves: $('input[name=no_of_leaves]').val(),
				cheque_no_from: $('input[name=cheque_no_from]').val(),
				cheque_no_to: $('input[name=cheque_no_to]').val(),
				next_book_warning: $('input[name=next_book_warning]').val(),
				nbfc_name: $('input[name=nbfc_name]').val(),
				nbfc_branch: $('input[name=nbfc_branch]').val(),
				group_id: $('select[name=ledger_group]').val(),
				user_id: $('select[name=user_id]').val(),
				credit_period: $('input[name=credit_period]').val(),
				debit_limit: $('input[name=debit_limit]:checked').val(),
				credit_limit: $('input[name=credit_limit]:checked').val(),
				min_debit_limit: $('input[name=min_debit_limit]').val(),
				max_debit_limit: $('input[name=max_debit_limit]').val(),
				min_credit_limit: $('input[name=min_credit_limit]').val(),
				max_credit_limit: $('input[name=max_credit_limit]').val(),
			},
			dataType: "json",
				success:function(data, textStatus, jqXHR) {

					var active_approve_selected = "";
					var inactive_approve_selected = "";
					var selected_approve_text = "Not Approved";
					var selected_approve_class = "badge-warning";

					if(data.data.approval_status == 1) {
						active_approve_selected = "selected";
						selected_approve_text = "Approved";
						selected_approve_class = "badge-info";
					} else if(data.data.status == 0) {
						inactive_approve_selected = "selected";
					}

					var html = ``;

					if(data.status == 1) {
						html += `<tr role="row" class="odd">
						<td>
							<input id="`+data.data.id+`" class="item_check" name="ledgers" value="`+data.data.id+`" type="checkbox">
							<label for="`+data.data.id+`"><span></span></label>
						</td>
						<td><a href="{{ url('accounts/ledger') }}/`+data.data.id+`">`+data.data.name+`</a></td>
						<td>`+data.data.display_name+`</td>
						<td>`+data.data.opening_balance+ data.data.opening_balance_type+`</td>
						<td>
							<label class="grid_label badge `+selected_approve_class+` status">`+selected_approve_text+`</label>
							<select style="display:none" id="`+data.data.id+`" class="approval_status form-control">
								<option `+active_approve_selected+` value="1">Approved</option>
								<option `+inactive_approve_selected+` value="0">Not Approved</option>
							</select>
						</td>
						<td>
							<label class="grid_label badge badge-success status">Active</label>
							<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
								<option value="1">Active</option>
								<option value="0">In-active</option>
							</select>
						</td>
						<td>
						<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
						<a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
						</td></tr>`;
					}

					call_back(html, `add`, data.message);	
					$('.close_full_modal').trigger('click');				
					$('.loader_wall_onspot').hide();
				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
</script> 
