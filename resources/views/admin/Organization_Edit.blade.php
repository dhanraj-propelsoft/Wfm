<div class="modal-header">
	<h4 class="modal-title float-right">Extend The Expire Date</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">
		 <div class="row">
		 <div class="col-md-6">
                  	                  	<label for="expire_date" class="control-label required">Extend Expire Date</label>
					{{ Form::text('expire_date', null, ['class'=>'form-control date-picker datetype extend', 'data-date-format' => 'dd-mm-yyyy', 'autocomplete' => 'off','id'=>$organization->id ]) }}
					 {!! Form::hidden('id', $organization->id  ) !!}
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
			id:{ required : true },
			expire_date: { required: true },
			//parent_department: { required: true },
			},

		messages: {
			expire_date: { required: "organization date in past" },
			//parent_department: { required: "Parent Department Name is required." },
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
			url: '{{ route('organization.extend') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id: $('input[name=id]').val(),
				expire_date: $('input[name=expire_date]').val(),
				     
				},
			success:function(data, textStatus, jqXHR) {
				console.log($("#expire_"+$('input[name=id]').val()).text(data.expire_on));

			

				$('.loader_wall_onspot').hide();
				$('.crud_modal').modal('hide');

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});


$('.extend').datepicker().change(evt => {
	//console.log("workit");
  var selectedDate = $('.extend').datepicker('getDate');
  var now = new Date();
  now.setHours(0,0,0,0);
  if (selectedDate < now) {
    alert("Selected date is in the past");
  } else {
  	// var status = $(this).val();
			// var id = $(this).attr('id');
			// console.log(id);
			// var obj = $(this);
			// var url = "{{ route('organization.extend') }}";
			// var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
			// console.log(data);
   console.log("ok");
  }
});</script>