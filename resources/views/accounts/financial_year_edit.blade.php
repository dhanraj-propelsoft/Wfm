@extends('layouts.master')
@include('includes.accounts')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>

<div class="modal-header">
	<h4 class="modal-title float-right">Edit Financial Year</h4>
</div>

	{!!Form::model($financial_year, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
  <div class="form-body col-md-6">
	  {!! Form::hidden('id', null) !!}
	<div class="form-group">
	  {!! Form::label('financial_start_year', 'Opening Date', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-6">

		{!! Form::text('financial_start_year', null, ['class' => 'form-control datetype date-picker rearrangedate', 'data-date-format' => 'dd-mm-yyyy']) !!}
	  </div>
	</div>    
	<div class="form-group">
	  {!! Form::label('financial_end_year', 'Closing Date', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-6">

		{!! Form::text('financial_end_year', null, ['class' => 'form-control datetype date-picker rearrangedate', 'data-date-format' => 'dd-mm-yyyy']) !!}
	  </div>
	</div>
	<div class="form-group">
	  {!! Form::label('voucher_year_format', 'Voucher Financial Year', array('class' => 'control-label col-md-4')) !!}

	  <div class="col-md-6">

		 {{ Form::text('voucher_year_format', null, ['class'=>'form-control ', 'autocomplete' => 'off']) }}
	  </div>
	</div>
  </div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
  
{!! Form::close() !!}
@stop

@section('dom_links')
@parent

<script>
  $(document).ready(function() {
	 
  });

  $('.validateform').validate({
	errorElement: 'span', //default input error message container
	errorClass: 'help-block', // default input error message class
	focusInvalid: false, // do not focus the last invalid input
	rules: {
		financial_start_year: { required: true },
		financial_end_year: { required: true },
	},

	messages: {
		financial_start_year: { required: "Opening Date is required." },
		financial_end_year: { required: "Closing Date is required." },
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
			 url: '{{ route('financial_year.update') }}',
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				financial_start_year: $('input[name=financial_start_year]').val(),
				financial_end_year: $('input[name=financial_end_year]').val(), 
				voucher_financial_year : $('input[name=voucher_year_format]').val(),            

				},
			success:function(data, textStatus, jqXHR) {

				$('.loader_wall_onspot').hide();
				alert_message(data.message, "success");
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>
@stop