<div class="modal-header">
	<h4 class="modal-title float-right">Edit Petty Cash Expenses</h4>
</div>

	{!!Form::model($expenses, [
		'class' => 'form-horizontal form_pettycash'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		<div class="form-group">
			{!! Form::label('name', 'Expenses', array('class' => 'control-label col-md-5 required')) !!}
			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('name', 'Expense Ledger', array('class' => 'control-label col-md-5 required')) !!}
		<div class="row">
		<div class="col-md-9" style="margin-left: 14px;">
			{!! Form::select('expense_ledger',$expensesLedgers,$expenses->ledger_id,['class' =>'form-control select_item expense_ledger']) !!}
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
						 		id:$('input[name=id]').val(),
						'ledger_id':function () { return $('select[name=expense_ledger]').val(); }
						}
					}
			}, 
		 	expense_ledger: { required: true},                  
		},

		messages: {
			name: { required: "Expense Name is required.", remote: "Expense Name is already exists!" },
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
			var id = $('input[name=id]').val();

			$.ajax({
			url: "{{ route('expense_masters.store') }}/"+id,
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id: $('input[name=id]').val(),
				name: $('input[name=name]').val(),
				expense_ledger:$('select[name=expense_ledger]').val(),
				description: $('textarea[name=description]').val()                
			},
			success:function(data, textStatus, jqXHR) {
				var active_selected = "";
                var inactive_selected = "";
                var selected_text = "In-Active";
                var selected_class = "badge-warning";
                if(data.data.status == 1) {
                    active_selected = "selected";
                    selected_text = "Active";
                    selected_class = "badge-success";
                } else if(data.data.status == 0) {
                    inactive_selected = "selected";
                }
				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.description+`</td>
					<td>
                        <label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
                        <select style="display:none" id="`+data.data.id+`" class="active_status form-control">
                            <option `+active_selected+` value="1">Active</option>
                            <option `+inactive_selected+` value="0">In-Active</option>
                        </select>
                    </td>
					<td>
					  	<a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>
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
	$('body').on('click', '.ledger_add', function(e) {
	    	e.preventDefault(); 
	    	$ledger_type=1;
	      $.get("{{ url('accounts/ledgers/create') }}/"+$ledger_type, function(data) {
				$('.crud_modal_sm .modal-container').html("");
				$('.crud_modal_sm .modal-container').html(data);
			});
			$('.crud_modal_sm').modal('show');
	  });
	$('body').on('change', '.expense_ledger', function(e) {
				$('.form_pettycash').valid();
	});
</script>