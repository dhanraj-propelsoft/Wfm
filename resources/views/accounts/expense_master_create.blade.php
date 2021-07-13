<div class="modal-header">
	<h4 class="modal-title float-right">Add Petty Cash Expenses</h4>
</div>
@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

	{!! Form::open([
		'class' => 'form-horizontal form_pettycash'
	]) !!}                                        
	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('name', 'Expense Name', array('class' => 'control-label col-md-5 required')) !!}
			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control expense_name']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('name', 'Expense Ledger', array('class' => 'control-label col-md-5 required')) !!}
		<div class="row">
		<div class="col-md-9" style="margin-left: 14px;">
			{!! Form::select('expense_ledger',$expensesLedgers,$dailyExpenseId,['class' =>'form-control select_item expense_ledger']) !!}
        </div>
        <div class="col-md-2">
        	 <a class="btn btn-success ledger_add" style="color: #fff;">Add</a>
        </div>
		</div>
		</div>
		<div class="form-group">						 
			{!! Form::label('description', 'Description', ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-12">
				{!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
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

	$('.form_pettycash').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { 
				required: true,
				remote: {
						url: '{{ route('expense_name') }}',
						type: "post",
						data: {
						 _token :$('input[name=_token]').val(),
						'ledger_id':function () { return $('select[name=expense_ledger]').val(); }
						}
					}
			}, 
		 	expense_ledger: { required: true},               
		},
		messages: {
			name: { required: "Expense Name is required..", 
					  remote: "Expense Name is already exists!" },
			expense_ledger:{ required: "Expense ledger is required."}                
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
			url: '{{ route('expense_masters.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				expense_ledger:$('select[name=expense_ledger]').val(),
				description: $('textarea[name=description]').val(),
				},
			success:function(data, textStatus, jqXHR) {
				$('.loader_wall_onspot').hide();
				if(data.status == 1){

				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.description+`</td>
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
					</td></tr>`, `add`, data.message);
			}else{
				$('.crud_modal').modal('hide');
				alert_message(data.message,'error');

			}
					
				},
				error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	$('body').on('change', '.expense_ledger', function(e) {
				$('.form_pettycash').valid();
	});

		$('body').on('click', '.ledger_add', function(e) {
	    	e.preventDefault(); 
	    	$ledger_type=1;
	      $.get("{{ url('accounts/ledgers/create') }}/"+$ledger_type, function(data) {
				$('.crud_modal_sm .modal-container').html("");
				$('.crud_modal_sm .modal-container').html(data);
			});
			$('.crud_modal_sm').modal('show');
	  });
</script>