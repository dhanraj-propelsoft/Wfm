<div class="modal-header">
    <h4 class="modal-title float-right">Add New Account</h4>
</div>

    {!! Form::open([
        'class' => 'form-horizontal validateform'
    ]) !!}                                        
    {{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('name', 'Account Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>	
		<div class="form-group">
			{!! Form::label('type', 'Type', array('class' => 'control-label col-md-3')) !!}

			<div class="col-md-12">
				{{Form::select('type', ["cash" => "Cash", "bank" => "Bank", "credit_card" => "Credit Card"], null, ['class'=>'form-control select_item'])}}
			</div>
		</div>	
		<div class="form-group">
			{!! Form::label('balance', 'Balance', array('class' => 'control-label col-md-3')) !!}

			<div class="col-md-12">
				{!! Form::text('balance', null,['class' => 'form-control price']) !!}
			</div>
		</div>
		
		<div class="form-group">						 
			{!! Form::label('account_number', 'Account Details(Bank Name, Type, Account Number)', ['class' => 'control-label col-md-12']) !!}
			
			<div class="col-md-12">
				{!! Form::text('account_number', null,['class' => 'form-control']) !!}
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
		    name: { required: true },                
		},

		messages: {
		    name: { required: "Account Name is required." },                
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
            url: '{{ route('account.store') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                name: $('input[name=name]').val(),
                balance: $('input[name=balance]').val(),
                account_number: $('input[name=account_number]').val(),                
                },
            success:function(data, textStatus, jqXHR) {

                call_back(`<tr role="row" class="odd">
                    <td>`+data.data.name+`</td>
                    <td>`+data.data.account_number+`</td>
                    <td>`+data.data.balance+`</td>
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

                $('.loader_wall_onspot').hide();

                },
            error:function(jqXHR, textStatus, errorThrown) {
                //alert("New Request Failed " +textStatus);
                }
            });
        }
    });

</script>