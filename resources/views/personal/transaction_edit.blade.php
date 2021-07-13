<div class="modal-header">
    <h4 class="modal-title float-right">Edit <span style="text-transform: capitalize;">{{$transaction->transaction_type}}</span> Transaction</h4>
</div>

    {!! Form::model($transaction, [
        'class' => 'form-horizontal validateform'
    ]) !!} 

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', null) !!}
		<div class="form-group">
		<div class="row">
		<div class="col-md-7">
			{!! Form::label('transaction_category', 'Category', array('class' => 'control-label col-md-12 required')) !!}
			<div class="col-md-12 form-group" style="margin-bottom: 0px;">
				{!! Form::hidden('transaction_type', $transaction->transaction_type, ['class' => 'form-control']) !!}
				{{Form::select('transaction_category', $category, $transaction->category_id, ['class'=>'form-control select_item'])}}
			</div>
		</div>
		<div class="col-md-5">
			{!! Form::label('amount', 'Amount', array('class' => 'control-label col-md-12 amount required')) !!}

			<div class="col-md-12">
				{!! Form::text('amount', $transaction->amount, ['class' => 'form-control price']) !!}
			</div>
		</div>
		</div>
		</div>

		<div class="form-group">
		<div class="row">
		<div class="col-md-4">
			{!! Form::label('date', 'Date', array('class' => 'control-label col-md-12 required')) !!}

			<div class="col-md-12">
				{!! Form::text('date', null, ['class' => 'form-control  date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) !!}
			</div>
		</div>	
		<div class="col-md-4">
			{!! htmlspecialchars_decode(Form::label('account_id', '<span style="text-transform: capitalize;">'.$transaction->transaction_type.' </span> Account', array('class' => 'control-label col-md-12 account required')))  !!}

			<div class="col-md-12 form-group" style="margin-bottom: 0px;">
				{{Form::select('account_id', $accounts, null, ['class'=>'form-control select_item'])}}
			</div>
		</div>	
		<div class="col-md-4">
			{!! Form::label('source', 'Source', array('class' => 'control-label col-md-12 required source')) !!}

			<div class="col-md-12 form-group" style="margin-bottom: 0px;">
				{{Form::text('source', null, ['class'=>'form-control'])}}
			</div>
		</div>	
		</div>
		</div>


		<div class="form-group">
		<div class="row">
		<div class="col-md-12">
			{!! Form::label('description', 'Transaction Details(Bank Name, Mode of Payment, Account Number)', array('class' => 'control-label col-md-12')) !!}

			<div class="col-md-12">
				{!! Form::text('description', null,['class' => 'form-control']) !!}
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
		    transaction_category: { required: true },  
		    amount: { required: true }, 
		    date: { required: true },  
		    account_id: { required: true }              
		},

		messages: {
		    transaction_category: { required: "Category is required." },  
		    amount: { required: "Amount is required." }, 
		    date: { required: "Date is required." },  
		    account_id: { required: "Account is required." }                
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
            url: '{{ route('personal_transaction.update') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                id: $('input[name=id]').val(),
                transaction_type: $('input[name=transaction_type]').val(),
                transaction_category: $('select[name=transaction_category]').val(),
                amount: $('input[name=amount]').val(), 
                date: $('input[name=date]').val(), 
                account_id: $('select[name=account_id]').val(),
                source: $('input[name=source]').val(),
                description: $('input[name=description]').val()
                },
            success:function(data, textStatus, jqXHR) {

            	var color =  ($('input[name=transaction_type]').val() == "expense") ? "#ff0000" : "#00af00";

                call_back(`<tr role="row" class="odd">
		<td width="1"><input id="`+data.data.id+`" class="item_check" name="transaction" value="`+data.data.id+`" type="checkbox"><label for="1"><span></span></label></td>
		<td>07 Aug, 2018</td>
		<td>`+$('select[name=transaction_category] option:selected').text()+`</td>
		<td>
		<span style="color:`+ color +`">
		`+data.data.amount+`</span>
		</td>		
		<td>  
			<a data-id="`+data.data.id+`" data-order="" data-type="" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			<a data-id="`+data.data.id+`" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
		</td>
		</tr>`, `edit`, data.message, data.data.id);

                $('.loader_wall_onspot').hide();

                },
            error:function(jqXHR, textStatus, errorThrown) {
                //alert("New Request Failed " +textStatus);
                }
            });
        }
    });

</script>