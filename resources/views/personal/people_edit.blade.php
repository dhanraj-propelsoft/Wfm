<div class="modal-header">
    <h4 class="modal-title float-right">Edit People</h4>
</div>

    {!! Form::model($people, [
        'class' => 'form-horizontal validateform'
    ]) !!}

<div class="modal-body">
	<div class="form-body">
	{!! Form::hidden('id', null) !!}
	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				{!! Form::label('name', 'Name', array('class' => 'control-label col-md-12 required')) !!}

				<div class="col-md-12">
					{!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
				</div>
			</div>	
			<div class="col-md-6">
				{!! Form::label('relationship_id', 'Relationship', array('class' => 'control-label col-md-12 required')) !!}

				<div class="col-md-12 form-group" style="margin-bottom: 0px;">
					{{Form::select('relationship_id', $relationship, null, ['class'=>'form-control select_item'])}}
				</div>
			</div>	
		</div>
	</div>


	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				{!! Form::label('mobile', 'Mobile Number', array('class' => 'control-label col-md-12 required')) !!}

				<div class="col-md-12 form-group" style="margin-bottom: 0px;">
					{!! Form::text('mobile', null,['class' => 'form-control numbers']) !!}
				</div>
			</div>	
			<div class="col-md-6">
				{!! Form::label('email', 'Email Address', array('class' => 'control-label col-md-12')) !!}

				<div class="col-md-12 form-group" style="margin-bottom: 0px;">
					{!! Form::text('email', null,['class' => 'form-control']) !!}
				</div>
			</div>	
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				{!! Form::label('dob', 'DOB', array('class' => 'control-label col-md-12')) !!}

				<div class="col-md-12">
					{!! Form::text('dob', null, ['class' => 'form-control  date-picker datetype', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off']) !!}
				</div>
			</div>		
			<div class="col-md-6">
				{!! Form::label('pan', 'PAN Number', array('class' => 'control-label col-md-12')) !!}

				<div class="col-md-12 form-group" style="margin-bottom: 0px;">
					{!! Form::text('pan', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				{!! Form::label('aadhar', 'Aadhar Number', array('class' => 'control-label col-md-12')) !!}

				<div class="col-md-12">
					{!! Form::text('aadhar', null,['class' => 'form-control']) !!}
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
		    name: { required: true },
		    relationship_id: { required: true },
		    category_id: { required: true },
		    mobile: { required: true }  ,
		    email: { email: true },
		    pan: { pan: true },
			aadhar: { aadhar: true }     
		},

		messages: {
		    name: { required: "Name is required." },
		    relationship_id: { required: "Relationship is required." },
		    category_id: { required: "Category is required." },
		    mobile: { required: "Mobile is required." }             
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
            url: '{{ route('personal_people.update') }}',
            type: 'post',
            data: {
            	_method: 'PATCH',
                _token: '{{ csrf_token() }}',
                id: $('input[name=id]').val(),
                name: $('input[name=name]').val(),
                relationship_id: $('select[name=relationship_id]').val(), 
                mobile: $('input[name=mobile]').val(),
                email: $('input[name=email]').val(),
                dob: $('input[name=dob]').val(),
                aadhar: $('input[name=aadhar]').val(),
                pan: $('input[name=pan]').val() 
                },
            success:function(data, textStatus, jqXHR) {

            	var row = $('tbody tr').length;

                call_back(`<tr role="row" class="odd">
					<td width="1"><input id="`+data.data.id+`" class="item_check" name="person" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label></td>
					<td>`+data.data.name+`</td>
					<td>`+$('select[name=relationship_id] option:selected').text()+`</td>
					<td>`+data.data.mobile+`</td>
					<td>`+data.data.email+`</td>
					<td>`+data.data.aadhar+`</td>
					<td>`+data.data.pan+`</td>
					<td>
													<label class="grid_label badge badge-success status">Active</label>
												
						<select style="display:none" id="`+data.data.id+`" class="active_status form-control">
							<option selected="selected" value="1">Active</option>
							<option value="0">In-Active</option>
						</select>            
					</td>
					<td>
						<a data-id="`+data.data.id+`" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
							
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