<div class="content">
  <div class="fill header">
	<h3 class="float-left voucher_id"></h3>
	<div class="float-right close_full_modal"><i style="font-size: 60px; margin-top: -15px;" class="fa icon-arrows-remove"></i></div>
  </div>
  <div class="clearfix"></div>
  {!! Form::open(['class' => 'form-horizontal voucherform']) !!}
  {{ csrf_field() }}
<div class="form-body" style="overflow-y: auto; padding: 15px 25px; height: 500px;">
	<div class="form-group">
	  <div class="row">
		<div class="col-md-2">
		  <label class="required" for="vouchers">Type:</label>
		  <select class="form-control select_item" name="voucher_type">
			<option data-name="" value="">Select Type</option>			
				
				@foreach($voucher_list as $voucher)

				<option data-name="{{ $voucher->name }}" value="{{ $voucher->id }}">{{ $voucher->display_name }}</option>
				
				@endforeach		
		  </select>
		</div>
		<div class="col-md-2">
		  <label class="required" for="date">Date:</label>
		  {!! Form::text('date', null,['class' => 'form-control accounts-date-picker', 'data-date-format' => 'dd-mm-yyyy']) !!}
		</div>
		<div style="display: none;" class="col-md-2">
		  <label for="reference_id">Reference Type:</label>
		  {!! Form::select('reference_type', [], null, ['class' => 'form-control', 'style' => 'width: 100%']); !!} 
		</div>

		<div style="display: none;"  class="col-md-2 reference_container">
		  <label for="reference_id">Reference #:</label>
		  {!! Form::text('reference_id', null,['class' => 'form-control']) !!} </div>
		<div class="col-md-2">
		  <div style="display: none; width: 100%" class="account_ledger">
			<label for="ledger">Account:</label>
			{!! Form::select('', [], null, ['class' => 'form-control select_item ledger', 'style' => 'width: 100%']); !!} </div>
		</div>

		<div class="col-md-4"> </div>
	  </div>
	</div>

	<div class="form-group">
	  <div class="row">
	  	<div class="col-md-2">
		  <div style="display: none; width: 100%" class="payment">
			<label for="payment">Payment:</label>
			{!! Form::select('payment', $payment, null, ['class' => 'form-control select_item payment', 'style' => 'width: 100%']); !!}</div>
		</div>
	  	<div class="col-md-2">
		  <div style="display: none; width: 100%" class="cheque_book">
			<label for="cheque_no">Cheque Book:</label>
			{!! Form::select('cheque_no',[''=>'Select Cheque No'], null, ['class' => 'form-control select_item cheque_book','style' => 'width: 100%']); !!} </div>
		</div>
	  </div>
	</div>

	<div style="display: none;" class="form-group account_data">
	  <div class="recurring" style="display: none;">
		<hr>
		<h5 class="float-left">Recurring</h5>
		<div class="clearfix"></div>
		<div class="form-group">
		  <div class="row">
			<div class="col-md-2"> {!! Form::label('interval', 'Interval', ['class' => 'control-label']) !!}
			  {!!	Form::select('interval', ['0'=>'Daily','1'=>'Weekly','2'=>'Monthly'], null, ['class' => 'form-control select_item']); !!} </div>
			<div class="col-md-2 month" style="display: none;">
			  <label style="position: absolute; left: -5px; top: 30px;">On</label>
			  <label class="control-label">&nbsp;</label>
			  {!! Form::label('', '', ['class' => 'control-label']) !!}			
			  {!!	Form::select('period', ['' => 'Day','1'=>'First','2'=>'Second','3'=>'Third','4'=>'Fourth','0'=>'Last'], null, ['class' => 'form-control select_item']); !!} </div>
			<div class="col-md-2 week" style="display: none;">
			  <label class="control-label">&nbsp;</label>
			  {!! Form::select('week_day_id', $weekdays, $weekday ,['class' => 'form-control select_item']) !!} </div>
			<div class="col-md-2 day" style="display: none;">
			  <label class="control-label">&nbsp;</label>
			  {{ Form::select('day',$days,null ,['class' => 'form-control select_item']) }} </div>
			<div class="col-md-3 every">
			  <label class="control-label" style="width: 100%">&nbsp;</label>
			  <label class="every_time" style="float: left; padding-right: 8px;"> every </label>
			  {{ Form::text('frequency', null, ['class'=>'form-control numbers', 'style' => 'float:left; width: 50px']) }}
			  <label class="period" style="float: left; padding-left: 8px;"> day(s) </label>
			</div>
		  </div>
		</div>
		<div class="form-group">
		  <div class="row">
			<div class="col-md-2"> {!! Form::label('start_date', 'Start Date', ['class' => 'control-label']) !!}	
			  {{ Form::text('start_date', null ,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) }} </div>
			<div class="col-md-2"> {!! Form::label('end', 'End', ['class' => 'control-label']) !!}	
			  {!!	Form::select('end', ['0' => 'None','1'=>'By','2'=>'After'], 0, ['class' => 'form-control select_item']); !!} </div>
			<div class="col-md-2 end_date" style="display: none;"> {!! Form::label('end_date', 'End Date', ['class' => 'control-label']) !!}	
			  {{ Form::text('end_date', null ,['class' => 'form-control date-picker datetype', 'data-date-format' => 'dd-mm-yyyy']) }} </div>
			<div class="col-md-1 occurence" style="display: none;">
			  <label class="control-label">&nbsp;</label>
			  {{ Form::text('end_occurence', null ,['class' => 'form-control number']) }}
			  <label class="period" style="position: absolute; right: -60px; top: 30px;"> Occurences </label>
			</div>
		  </div>
		</div>
		<hr>
	  </div>
	  <table class="table crud_table">
		<thead>
		  <tr>
			<th width="25%" class="debit_ledger">Debit Ledger</th>
			<th width="25%" class="credit_ledger">Credit Ledger</th>
			<th width="25%">Description</th>
			<th width="15%">Amount</th>
			<th width="10%"></th>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td class="debit_ledger">{!! Form::select('debit_ledger', ['' => 'Select Ledger'], null, ['class' => 'form-control select_item ledger']); !!}</td>
			<td class="credit_ledger">{!! Form::select('credit_ledger', ['' => 'Select Ledger'], null, ['class' => 'form-control select_item ledger']); !!}</td>
			<td>{{ Form::textarea('description', null, ['class' => 'form-control', 'size' => '20x1']) }}</td>
			<td>{!! Form::text('amount',null,['class'=>'form-control price','placeholder'=>'Amount', 'autocomplete' => 'off']) !!}</td>
			<td><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a></td>
		  </tr>
		</tbody>
	  </table>
	  <div class="form-group">
		<div class="row">
		  <div class="col-md-11 float-right">
			<h5 class="total" style="float:right; text-align:right; width: 150px;">Rs 0.00</h5>
			<h5 style="float:right; text-align:right; font-weight:bold;">Total</h5>
		  </div>
		</div>
	  </div>
	  <div class="form-group">
		<label style="vertical-align:top" for="notes">Notes:</label>
		{{ Form::textarea('notes', null, ['class' => 'form-control', 'size' => '20x2']) }} </div>
	</div>

</div>

  <div class="save_btn_container">
  	<button type="reset" class="btn btn-default clear cancel_transaction">Cancel</button>
  	<button type="submit" class="btn btn-success">Save </button>
  	<!-- <div style="margin:-25px auto 0px; width: 150px;"><a class="make_recurring"> Make Recurring</a></div> -->
  </div>
  {!! Form::close() !!} </div>
<script type="text/javascript">
	
	 $(document).ready(function() {

	basic_functions();

	$('select[name=payment], .account_ledger select').on('change', function() {
		var cheque_no = $( "select[name=cheque_no]" );
		var id = $('.account_ledger select').val();
		cheque_no.val("");
		cheque_no.select2('val', '');
		cheque_no.empty();

		if(id != "" && $('select[name=payment]').find('option:selected').text()=='Cheque') {
		$('.loader_wall_onspot').show();
			$.ajax({
				 url: '{{ route('get_cheque') }}',
				 type: 'get',
				 data: {
					_token :$('input[name=_token]').val(),
					account_ledger_id: id,
					cheque_no: "",
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						var result = data.result;
						cheque_no.append("<option value=''>Select cheque no</option>");
						for(var i in result) {	
							cheque_no.append("<option value='"+result[i]+"'>"+result[i]+"</option>");
						}
						$('.loader_wall_onspot').hide();
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	$('select[name=payment]').on('change', function() {
		
		 if($(this).find('option:selected').text()=='Cheque')
		 {
			$('.cheque_book').show();
		 }else {
			$('.cheque_book').hide();
		 }
	});

	var accounts_date_picker = $('.accounts-date-picker');

	accounts_date_picker.datepicker({
		 rtl: false,
		 orientation: "left",
		 todayHighlight: true,
		 autoclose: true,
		 startDate: financialyear_start,
		 endDate: financialyear_end,
		 format: 'dd-mm-yyyy'
	});

	 $('select[name=applicable_to]').on('change', function(){

	 });

	 $('.ledger').on('change', function() {
		 var obj = $(this);
		 var selected = obj.find('option:selected').val();

		 if (selected != "") {
			 $('.ledger').find('span option').unwrap();
			 obj.closest('tr').find('.ledger').find('option[value="' + selected + '"]').wrap('<span>');
			 $('.account_ledger').find('.ledger').find('option[value="' + selected + '"]').wrap('<span>');
			 obj.find('span option[value="' + selected + '"]').unwrap();
		 }
	 });

	$('body').on('click', '.add_row', function() {
		 $('.select_item').each(function() { 
				var select = $(this);  
				if(select.data('select2')) { 
					select.select2("destroy"); 
				} 
	});

		 var obj = $(this);
		 var clone = $(this).closest('tr').clone(true, true);
		 clone.find('select, textarea, :text').val("");

		 var row_index = $('.crud_table tbody > tr').length;

		 clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_row experience_delete"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');

		 obj.closest('tr').after(clone);
		 obj.closest('td').html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a>');
		 $('.select_item').select2();

		 

	 });

	 $('body').on('click', '.remove_row', function() {
		 (this).closest('tr').remove();

		 var row_index = $('.crud_table tbody > tr').length;

		 if (row_index > 1) {
			 $('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_row"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
		 } else {
			 $('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row"><i class="fa fa-plus"></i></a>');
		 }

		 $('.total').text(getTotal());

	 });

	 $('input[name=amount]').each(function() {
		 $(this).keyup(function() {
			 $('.total').text(getTotal());
		 });
	 });



	 $('select[name=voucher_type]').on('change', function() {

		 var voucher_type = $(this).find('option:selected').data('name');
		 var voucher_name = $(this).find('option:selected').text();
		 var voucher_id = $(this).val();
		 $('.voucher_id').text("");

		 $('.account_data').hide();
		 $('.account_ledger').hide();
		 $('.reference_container').hide();
		 $('.cheque_book').hide();
		 $('.account_data').find('select, textarea, :text').val("");
		 $('.account_data').find('input[name=amount]').val("0.00");

		 if (voucher_type != "") {

			$('.account_data').show();
			$('.reference_container').hide();
			$('.add_row').show();

			$('.account_ledger').hide();			
			$('.payment').hide();
			$('.account_ledger').find('select').attr('name');
			$('.debit_ledger').show();
			$('.credit_ledger').show();
			$('.account_ledger').find('select').empty();
			$('.debit_ledger').find('select').empty();
			$('.credit_ledger').find('select').empty();
			$('.account_ledger, .debit_ledger, .credit_ledger').find('select').removeAttr('name');

			$('select[name=reference_type]').closest('div').hide();
			$('select[name=reference_type]').empty();

			get_ledgers("journal", $('.debit_ledger').find('select'));
			get_ledgers("journal", $('.credit_ledger').find('select'));

			$.each($(".crud_table tr"), function() { 
				if($(this).children(":eq(0)").hasClass('credit_ledger')) {
            		$(this).children(":eq(1)").after($(this).children(":eq(0)"));
				}
        	});

			 switch (voucher_type) {
				 case "receipt":
				 	$('.reference_container').show();
					$('.account_ledger').show();
					$('.account_ledger').find('select').attr('name', 'debit_ledger');
					$('.debit_ledger').find('select').removeAttr('name');
					$('.debit_ledger').hide();
					$('.credit_ledger').show();
					$('.credit_ledger').find('select').attr('name', 'credit_ledger');
					get_ledgers(voucher_type, $('.account_ledger').find('select'));
					get_reference(voucher_type);
					break;
				 case "payment":
				 	$('.reference_container').show();
					$('.account_ledger').show();
					$('.account_ledger').find('select').attr('name', 'credit_ledger');
					$('.debit_ledger').show();
					$('.debit_ledger').find('select').attr('name', 'debit_ledger');
					$('.credit_ledger').find('select').removeAttr('name');
					$('.credit_ledger').hide();
					get_ledgers(voucher_type, $('.account_ledger').find('select'));
					get_reference(voucher_type);
					break;
				 case "deposit":
				 	$('.reference_container').show();
					$('.account_ledger').show();
					
					$('.payment').show();
					$('.account_ledger').find('select').attr('name', 'debit_ledger');
					$('.debit_ledger').find('select').removeAttr('name');
					$('.debit_ledger').hide();
					$('.credit_ledger').show();
				    $('.credit_ledger').find('select').attr('name', 'credit_ledger');
					get_ledgers(voucher_type, $('.account_ledger').find('select'));
					//get_ledgers("", $('.credit_ledger').find('select'));
					break;
				 case "withdrawal":
				 	$('.reference_container').show();
					$('.account_ledger').show();
					$('.account_ledger').find('select').attr('name', 'credit_ledger');
					$('.debit_ledger').show();
					$('.debit_ledger').find('select').attr('name', 'debit_ledger');
					$('.credit_ledger').hide();
					$('.credit_ledger').find('select').removeAttr('name');
					get_ledgers(voucher_type, $('.account_ledger').find('select'));
					//get_ledgers("", $('.debit_ledger').find('select'));
					break;
				case "credit_note":
				 	$('.reference_container').show();
				 	$('.account_ledger').hide();
					$('.account_ledger').find('select').attr('name');
					$('.debit_ledger').find('select').attr('name', 'debit_ledger');
					$('.credit_ledger').find('select').attr('name', 'credit_ledger');
					$('.debit_ledger').show();
					$('.credit_ledger').show();
					$('.account_ledger').find('select').empty();
					$('.add_row, .remove_row').hide();
					$('.crud_table').find('tr:gt(1)').remove();
					get_reference(voucher_type);

					$.each($(".crud_table tr"), function() { 
						if($(this).children(":eq(1)").hasClass('credit_ledger')) {
		            		$(this).children(":eq(1)").after($(this).children(":eq(0)"));
						}
		        	});
					//get_ledgers(voucher_type, $('.debit_ledger').find('select'));
			 		get_ledgers(voucher_type, $('.credit_ledger').find('select'));
					break;
				case "debit_note":
				 	$('.reference_container').show();
				 	$('.account_ledger').hide();
					$('.account_ledger').find('select').attr('name');
					$('.debit_ledger').find('select').attr('name', 'debit_ledger');
					$('.credit_ledger').find('select').attr('name', 'credit_ledger');
					$('.debit_ledger').show();
					$('.credit_ledger').show();
					$('.account_ledger').find('select').empty();
					$('.add_row, .remove_row').hide();
					$('.crud_table').find('tr:gt(1)').remove();
					get_reference(voucher_type);
					$.each($(".crud_table tr"), function() { 
						if($(this).children(":eq(0)").hasClass('credit_ledger')) {
		            		$(this).children(":eq(1)").after($(this).children(":eq(0)"));
						}
		        	});

					get_ledgers(voucher_type, $('.debit_ledger').find('select'));
			 		//get_ledgers(voucher_type, $('.credit_ledger').find('select'));
					break;
				 case "journal":
				 	$('.account_ledger').hide();
					$('.account_ledger').find('select').attr('name');
					$('.debit_ledger').find('select').attr('name', 'debit_ledger');
					$('.credit_ledger').find('select').attr('name', 'credit_ledger');
					$('.debit_ledger').show();
					$('.credit_ledger').show();
					$('.account_ledger').find('select').empty();
					get_ledgers(voucher_type, $('.debit_ledger').find('select'));
			 		get_ledgers(voucher_type, $('.credit_ledger').find('select'));
					break;
			 }


			 $.ajax({
				 url: "{{ route('get_voucher_no') }}",
				 type: 'post',
				 data: {
					 _token: $('input[name=_token]').val(),
					 voucher_type: voucher_id
				 },
				 success: function(data, textStatus, jqXHR) {
					 $('.voucher_id').text(voucher_name + '# ' + data.voucher_no);
					 if (data.date_setting == 0) {
						 accounts_date_picker.datepicker('setDate', new Date());
					 }
				 }
			 });
		 }
	 });

	

	$('.make_recurring').on('click', function() {
		if ($('.account_data').is(':visible')) {
			$('.recurring').show();
			$('.voucher_code').hide();
		}
	});

	 $('.cancel_transaction').on('click', function(e) {
		 e.preventDefault();
		 if ($('.recurring').is(':hidden')) {
			$('.close_full_modal').trigger('click');
		 } else {
			 $('.recurring').hide();
			 $('.voucher_code').show();
		 }

	 });

	 $("select[name=interval]").on('change', function() {
		 $('select[name=period]').val('');
		 $('select[name=week_day_id]').val('{{$weekday}}');
		 $('select[name=day]').val(1);
		 $('select[name=period], select[name=week_day_id], select[name=day]').trigger('change');
		 $('.every').show();
		 $('.month').hide();
		 $('.week').hide();
		 $('.day').hide();

		 if ($(this).val() == 0) {
			 $('.every .every_time').text(" every ");
			 $('.every .period').text(" day(s) ");
		 } else if ($(this).val() == 1) {
			 $('.week').show();
			 $('.every .every_time').text(" for every ");
			 $('.every .period').text(" week(s) ");
		 } else if ($(this).val() == 2) {
			 $('.month').show();
			 $('.day').show();
			 $('.every .every_time').text(" of every ");
			 $('.every .period').text(" month(s) ");
		 }
	 });

	 $('select[name=period]').on('change', function() {
		 if ($(this).val() != '') {
			 $('.week').show();
			 $('.day').hide();
		 } else {
			 $('.week').hide();
			 $('.day').show();
		 }
	 });

	 $('select[name=end]').on('change', function() {
		 $('.end_date').hide();
		 $('.occurence').hide();


		 $('input[name=end_date], input[name=end_occurence]').val("");

		 if ($(this).val() == '1') {
			 $('.end_date').show();
			 $('.occurence').hide();
		 } else if ($(this).val() == '2') {
			 $('.end_date').hide();
			 $('.occurence').show();
		 }
	 });

	 function get_reference(voucher_type) {

	 	var reference_type = $('select[name=reference_type]');

	 	reference_type.empty();

	 	$.ajax({
			 url: "{{ route('get_reference_type') }}",
			 type: 'post',
			 data: {
				 _token: $('input[name=_token]').val(),
				 voucher_type: voucher_type
			 },
			 success: function(data, textStatus, jqXHR) {
			 	 var result = data.result;
			 	 if(result.length > 1) {
			 	 	reference_type.closest('div').show();
			 	 } else {
			 	 	reference_type.closest('div').hide();
			 	 }
				 for (var i in result) {
					 reference_type.append("<option value='" + result[i].id + "'>" + result[i].name + "</option>");
				 }
			 },
			 error: function(jqXHR, textStatus, errorThrown) {}
		});
	 }


	 function get_ledgers(voucher_type, ledger) {

		$('.loader_wall').show();
		 $.ajax({
			 url: "{{ route('get_ledgers') }}",
			 type: 'post',
			 data: {
				 _token: $('input[name=_token]').val(),
				 voucher_type: voucher_type
			 },
			 success: function(data, textStatus, jqXHR) {
				 ledger.empty();
				 ledger.append("<option value=''>Select Ledger</option>");
				 var result = data.result;
				 for (var i in result) {
					 ledger.append("<option value='" + result[i].id + "'>" + result[i].name + "</option>");
				 }
				 $('.loader_wall').hide();
			 },
			 error: function(jqXHR, textStatus, errorThrown) {}
		});
	 }

 });

	function getTotal() {
		 var total = 0;
		 $("input[name=amount]").each(function() {
			 if (!isNaN(this.value) && this.value.length != 0) {
				total += parseFloat(this.value);
			}
		 });
		 return total.toFixed(2);
	}


 $('.voucherform').validate({
	 errorElement: 'div', //default input error message container
	 errorClass: 'help-block', // default input error message class
	 focusInvalid: false, // do not focus the last invalid input
	 rules: {
		 voucher_type: {
			 required: true
		 },
		 date: {
			 required: true
		 },
		 debit_ledger: {
			 required: true
		 },
		 credit_ledger: {
			 required: true
		 },
		 reference_id: {
			 remote: {
				 url: "{{ route('get_account_transaction_order') }}",
				 type: "post",
				 data: {
					_token : '{{ csrf_token() }}',
					type: 'purchases'
				 }
			 }
		 }
	 },

	 messages: {
		 voucher_type: {
			 required: "Voucher Type is required"
		 },
		 date: {
			 required: "Invoice date is required"
		 },
		 debit_ledger: {
			 required: "Debit ledger is required"
		 },
		 credit_ledger: {
			 required: "Credit ledger is required"
		 },
		 reference_id: {
			 remote: "Voucher number does not exist"
		 }
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
		$.ajax({
			 url: "{{ route('vouchers.store') }}",
			 type: 'post',
			 data: {
			 _token: '{{ csrf_token() }}',
			 voucher_type: $('select[name=voucher_type]').val(),
			 date: $('input[name=date]').val(),
			 payment: $('select[name=payment]').val(),
			 cheque_no: $('select[name=cheque_no]').val(),
			 cheque_book_id: $("select[name=debit_ledger]").val(),
			 debit_ledger: $("select[name=debit_ledger]").map(function() {
				 return this.value;
			 }).get(),
			 credit_ledger: $("select[name=credit_ledger]").map(function() {
				 return this.value;
			 }).get(),
			 reference_type: $('select[name=reference_type]').val(),
			 reference_id: $('input[name=reference_id]').val(),
			 description: $("textarea[name=description]").map(function() {
				 return this.value;
			 }).get(),
			 amount: $("input[name=amount]").map(function() {
				 return this.value;
			 }).get(),
			 notes: $('textarea[name=notes]').val()
			 },

			 dataType: "json",
			success: function(data, textStatus, jqXHR) {

				 call_back(`<tr>
							  <td>`+data.data.order_no+`</td>
							  <td>`+data.data.type+`</td>
							  <td>`+data.data.date+`</td>
							  <td>`+data.data.amount+`</td>
							  <td>`+data.data.reference+`</td>
							  <td>
								<a data-id="`+data.data.id+`" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
								<a data-id="`+data.data.id+`" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
							  </td>
							</tr>`, `add`, data.message);
				 $('.close_full_modal').trigger('click');
				 $('.loader_wall_onspot').hide();
			 },
			 error: function(jqXHR, textStatus, errorThrown) {
				 //alert("New Request Failed " +textStatus);
			 }
		 });
	 }
 });

</script> 