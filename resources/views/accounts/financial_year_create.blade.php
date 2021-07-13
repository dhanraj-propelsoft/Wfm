<div class="alert alert-danger exist_year">
    {{ Session::get('flash_message') }}
</div>
<div class="modal-header">
	<h4 class="modal-title float-right">Financial Year Setup</h4>
</div>
	{!! Form::model($financial_years, ['class' => 'form-horizontal validateform'
		 ]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">			
	<div class="row" style="margin-left: 25px;margin-right: 25px">
		<div class="col-md-12">
			<div class="form-group">
				{!! Form::label('financial_start_year', 'Financial-Start', array('class' => 'control-label col-md-4 required')) !!}
				<div class="col-md-12">
				@isset($financial_years->id)	
					{{ Form::text('financial_start_year', null, ['class' => 'form-control date']) }}
				@else
					{{ Form::text('financial_start_year', $financial_year_start_date, ['class' => 'form-control date']) }}		
				@endisset 
				
			    </div>
	        </div>
		</div>
	</div>
	<div class="row" style="margin-left: 25px;margin-right: 25px">
		<div class="col-md-12">
			<div class="form-group">
				{!! Form::label('financial_end_year', 'Financial-End', array('class' => 'control-label col-md-4 required')) !!}
				<div class="col-md-12">
				@isset($financial_years->id)	
					{{ Form::text('financial_end_year', null, ['class' => 'form-control date']) }}
				@else
					{{ Form::text('financial_end_year', $financial_year_end_date, ['class' => 'form-control date']) }}		
				@endisset 
			    </div>
	        </div>
		</div>
	</div>
	<div class="form-group col-md-12" style="margin-left: 40px;margin-right: 25px">
		@isset($financial_years->id)
			@if($financial_years->status == '1')
			{!! Form::checkbox('current_year','1', true, array('id' => 'current_year')) !!}
				<label for="current_year"> <span></span>Current Year</label>
			@else
			{!! Form::checkbox('current_year','1', null, array('id' => 'current_year')) !!}
				<label for="current_year"> <span></span>Current Year</label>	
			@endif				
		@else
			{!! Form::checkbox('current_year','1', false, array('id' => 'current_year')) !!}
			<label for=""> <span></span>Current Year</label>		
		@endisset 
	</div> 
	<div class="row" style="margin-left: 25px;margin-right: 25px">
		<div class="col-md-12">
			<div class="form-group">
			{!! Form::label('voucher_format', 'Voucher-Format', array('class' => 'control-label col-md-4')) !!}
			<div class="col-md-12">
			{!! Form::text('voucher_year_format', null,['class' => 'form-control']) !!}
		    </div>
	        </div>
		</div>
	</div>
	<label for="show_restart_vouchers" style="margin-left: 50px;"><span></span><a href="#" id="show_restart_vouchers">Show..</a></label></input>
	<div class="restart_vouchers_info" style="display: none;">
		<div class="form-group col-md-12" style="margin-left: 40px;margin-right: 25px">
			{!! Form::checkbox('restart_all_vouchers','1', null, array('id' => 'restart_all_vouchers')) !!}
			<label for="restart_all_vouchers"> <span></span>Restart All Voucher</label>
		</div> 
	</div>
						
</div>

</div>
							
</div>
</div>
<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>{!! Form::close() !!}

<script>
	$('.date').datepicker({
        format: "yyyy-mm-dd",
        changeMonth:true,
        changeYear:true,
        autoclose:true
    });
	

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input 
		rules:{	
			financial_start_year:{ required: true},		
			financial_end_year:{ required: true},
		},
		messages:{
			financial_start_year: { required: " Financial-Start is required."},
			financial_end_year: { required: " Financial-End is required."},
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
			$.ajax({
					 url: '{{ route('fiscal_year_duplicate') }}',
					 type: 'post',
					 data: {
						_token :$('input[name=_token]').val(),
						@isset($financial_years->id)
						financial_id: {!!$financial_years->id!!},
						@endisset
						financial_start_year:$('input[name=financial_start_year]').val(),
						financial_end_year:$('input[name=financial_end_year]').val(),
						},
					 	dataType: "json",
						success:function(data, textStatus, jqXHR) {

							if(data == true){
								alert_message("This Financial Year Is Already Exists",'error');
								$('input[name=financial_start_year]').val('');
								$('input[name=financial_end_year]').val('');

							}
							else
							{
									$('.loader_wall_onspot').show();
									$.ajax({
											@isset($financial_years->id)
											url: "{{ route('financial_year.store',["id"=>$financial_years->id]) }}",
											@else
											url: "{{ route('financial_year.store') }}",
											@endisset
											type: 'post',
											data: {
											_token: '{{ csrf_token() }}',
											financial_start_year:$('input[name=financial_start_year]').val(),
											financial_end_year:$('input[name=financial_end_year]').val(),
											current_year:$('#current_year').is(":checked"),
											voucher_format:$('input[name=voucher_year_format]').val(),
											restart_vuchers:$('#restart_all_vouchers').is(":checked"),
													},
											success:function(data, textStatus, jqXHR) {
											
											var financial_year = data.data.financial_years;
											var active_selected = "";
											var in_active_selected = "";
											var selected_text = "";
											var selected_class = "";
											if(financial_year.status == 1) {
												active_selected = "selected";
												selected_text = "Current Year";
												selected_class = "badge-success";
											} else {
												in_active_selected = "selected";
												selected_text = "Old Year";
												selected_class = "badge-warning";
											}

											call_back(`<tr role="row" class="odd">			
											    <td>`+financial_year.name+`</td>
											    <td>`+financial_year.books_start_year+`</td>
											    <td>`+financial_year.books_end_year+`</td>
											    <td>`+financial_year.financial_start_year+`</td>
											    <td>`+financial_year.financial_end_year+`</td>
											    <td>`+financial_year.voucher_year_format+`</td>
											    <td>`+financial_year.updated_at+`</td>
												<td>
													<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
													<select style="display:none" id="`+financial_year.id+`"  class=" form-control active_status">
														<option `+active_selected+` value="1">Current Year</option>
														<option `+in_active_selected+`value="0">Old Year</option>
													</select>
												
												</td>		
												<td>
													<a data-id="`+financial_year.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>
												</td>
												</tr>`,`edit`,data.message,financial_year.id);
											$('.loader_wall_onspot').hide();
											window.location.href = "{{ route('financialyear_list.index') }}"; 
											},
											error:function(jqXHR, textStatus, errorThrown) {		
											}
										});
							}
							
						},
				 		error:function(jqXHR, textStatus, errorThrown) {
							//alert("New Request Failed " +textStatus);
						}
			});

			
		}
			
	});

	
    $('#restart_all_vouchers').on('change',function(){
   	//alert();
   		if($(this).prop("checked") == true)
		{
			
		 $('.voucher_restart_modal').modal('show');
		 $('.confirm_to_continue').on('click',function(){
		 	//alert();
		 	$('.voucher_restart_modal').find('#content').text('');
		 	$('.voucher_restart_modal').find('#content').text("This will restart for all the voucher..For Ex: The new invoice Gen number start from 1. IN / 20 / 1...Please accept for the seplatro or system admin");
		 	$('.confirm_to_continue').css('display','none');
		 	$('.cancel_btn').css('display','none');
		 	
		 	
		 	$(".close_btn").removeAttr("style");

		 	$(".confirm_to_restart").removeAttr("style");
		 		$('.confirm_to_restart').on('click',function(){
		 			//alert();
		 			$.ajax({
		 				url : "{{ route('restart_all_vouchers') }}",
		 				type : 'POST',
		 				data :
		 				{
		 					_token : "{{ csrf_token() }}",
		 				},
		 				success:function(data)
		 				{
		 					//alert();
					 	$('.voucher_restart_modal').find('#content').text('');
					 	$('.voucher_restart_modal').find('#content').text(data.message);
		 				$('.confirm_to_restart').css('display','none');


		 				},
		 				error:function()
		 				{

		 				}

		 			});

		 		});


		 });
				
		}

   });


	$('#show_restart_vouchers').on('click',function(){

			$('.restart_vouchers_info').toggle();
			
	});

</script>