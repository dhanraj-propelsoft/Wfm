@extends('layouts.master')
@include('includes.settings')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>

<div class="modal-header">
	<h4 class="modal-title float-right">Hourly Expense Calculator</h4>
</div>

	
	{!!Form::model($WmsOrganizationCost, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('expense1', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor1', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value1', null,['class' => 'form-control value1']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor2', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value2', null,['class' => 'form-control value2']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor3', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value3', null,['class' => 'form-control value3']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor4', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value4', null,['class' => 'form-control value4']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor5', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value5', null,['class' => 'form-control value5']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor6', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value6', null,['class' => 'form-control value6']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor7', null,['class' => 'form-control ']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value7', null,['class' => 'form-control value7']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor8', null,['class' => 'form-control ']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value8', null,['class' => 'form-control value8']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor9', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value9', null,['class' => 'form-control value9']) !!}
			</div>
		</div>
	</div>
	<div class="row col-md-8"> 
		<div class="col-md-4">
			<div class="form-group"> 
				{{ Form::label('summary', 'Expense', array('class' => 'control-label')) }}		
				{!! Form::text('factor10', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
			<div class="form-group"> 
				{{ Form::label('summary', 'Amount', array('class' => 'control-label')) }}		
				{!! Form::text('value10', null,['class' => 'form-control value10']) !!}
			</div>
		</div>
	</div>
	<div class="col-md-8 row">	
		<div class="col-md-4">
					{!! Form::label('grn_number', 'Month Expense Total', array('class' => 'control-label')) !!}
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
					{!! Form::text('month_expense_total', null,['class' => 'form-control','disabled' =>'disabled']) !!}
		</div>
	</div>
	<div class="col-md-8 row" style="margin-top: 10px;">	
		<div class="col-md-4">
					{!! Form::label('grn_number', 'Days Per Month', array('class' => 'control-label')) !!}
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
					{!! Form::text('days_per_month', null,['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-md-8 row" style="margin-top: 10px;">	
		<div class="col-md-4">
					{!! Form::label('grn_number', 'Hours Per Day', array('class' => 'control-label')) !!}
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
					{!! Form::text('hours_per_day', null,['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-md-8 row" style="margin-top: 10px;">	
		<div class="col-md-4">
					{!! Form::label('grn_number', 'Hours per Month', array('class' => 'control-label')) !!}
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
					{!! Form::text('hours_per_month', null,['class' => 'form-control','disabled' =>'disabled']) !!}
		</div>
	</div>
	<div class="col-md-8 row" style="margin-top: 10px;">	
		<div class="col-md-4">
					{!! Form::label('grn_number', 'Hourly Org Cost', array('class' => 'control-label')) !!}
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
					{!! Form::text('hourly_org_cost', null,['class' => 'form-control','disabled' =>'disabled']) !!}
		</div>
	</div>
	<div class="col-md-8 row" style="margin-top: 10px;">	
		<div class="col-md-4">
					{!! Form::label('grn_number', 'No Of Employees', array('class' => 'control-label')) !!}
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
					{!! Form::text('no_of_employees', null,['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-md-8 row" style="margin-top: 10px;">	
		<div class="col-md-4">
					{!! Form::label('grn_number', 'Hourly Employee Cost', array('class' => 'control-label')) !!}
		</div>
		<div class="col-md-4" style="margin-left: -30px;">
					{!! Form::text('hourly_employee_cost', null,['class' => 'form-control','disabled' =>'disabled']) !!}
		</div>
	</div>
	

</div>
<hr>                                 
<button type="submit" class="btn btn-success" style="margin-left: 350px;">Submit</button>
{!! Form::close() !!}
@stop

@section('dom_links')
@parent
<script>

$('.value1, .value2, .value3, .value4, .value5, .value6, .value7, .value8, .value9 ,.value10').blur(function()
{
	
	var value1 = 0;
	var value2 = 0;
	var value3 = 0;
	var value4 = 0;
	var value5 = 0;
	var value6 = 0;
	var value7 = 0;
	var value8 = 0;
	var value9 = 0;
	var value10 = 0;
	if($('input[name=value1]').val() != ''){
	var value1 = $('input[name=value1]').val();
	}
	if($('input[name=value2]').val() != ''){
	var value2 = $('input[name=value2]').val();
	}
	if($('input[name=value3]').val() != ''){
	var value3 = $('input[name=value3]').val();
	}
	if($('input[name=value4]').val() != ''){
	var value4 = $('input[name=value4]').val();
	}
	if($('input[name=value5]').val() != ''){
	var value5 = $('input[name=value5]').val();
	}
	if($('input[name=value6]').val() != ''){
	var value6 = $('input[name=value6]').val();
	}
	if($('input[name=value7]').val() != ''){
	var value7 = $('input[name=value7]').val();
	}
	if($('input[name=value8]').val() != ''){
	var value8 = $('input[name=value8]').val();
	}
	if($('input[name=value9]').val() != ''){
	var value9 = $('input[name=value9]').val();
	}
	if($('input[name=value10]').val() != ''){
	var value10 = $('input[name=value10]').val();
	}

    $('input[name=month_expense_total]').val(parseInt(value1)+parseInt(value2)+parseInt(value3)+parseInt(value4)+parseInt(value5)+parseInt(value6)+parseInt(value7)+parseInt(value8)+parseInt(value9)+parseInt(value10));

    hours_per_days();
    no_of_employees();

});	
$('input[name=hours_per_day]').blur(function(){
		hours_per_days();
		no_of_employees();
});
$('input[name=no_of_employees]').blur(function(){
		no_of_employees();

});
function hours_per_days()
{
	var days_per_month = 0;
	var hours_per_day = 0;
	if($('input[name=days_per_month]').val() != ''){
	var days_per_month = $('input[name=days_per_month]').val();
	}
	if($('input[name=hours_per_day]').val() != ''){
	var hours_per_day = $('input[name=hours_per_day]').val();
	}
	$('input[name=hours_per_month]').val(parseInt(days_per_month)*parseInt(hours_per_day));

	$('input[name=hourly_org_cost]').val($('input[name=month_expense_total]').val()/$('input[name=hours_per_month]').val());
	return false;
}
function no_of_employees()
{	
	$('input[name=hourly_employee_cost]').val($('input[name=hourly_org_cost]').val()/$('input[name=no_of_employees]').val());
	return false;
}

  $('.validateform').validate({
	errorElement: 'span', //default input error message container
	errorClass: 'help-block', // default input error message class
	focusInvalid: false, // do not focus the last invalid input
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
			@isset($WmsOrganizationCost->id)
			url: '{{ route('organizations_cost_update',["id"=>$WmsOrganizationCost->id]) }}',
			@else
			url: '{{ route('organizations_cost_update') }}',
			@endisset
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'Post',
			factor1: $('input[name=factor1]').val(),
			value1: $('input[name=value1]').val(),
			factor2: $('input[name=factor2]').val(),
			value2: $('input[name=value2]').val(),
			factor3: $('input[name=factor3]').val(),
			value3: $('input[name=value3]').val(),
			factor4: $('input[name=factor4]').val(),
			value4: $('input[name=value4]').val(),
			factor5: $('input[name=factor5]').val(),
			value5: $('input[name=value5]').val(),   
			factor6: $('input[name=factor6]').val(),
			factor6: $('input[name=value6]').val(),
			factor7: $('input[name=factor7]').val(),
			value7: $('input[name=value7]').val(),
			factor8: $('input[name=factor8]').val(),
			value8: $('input[name=value8]').val(),
			factor9: $('input[name=factor9]').val(),
			value9: $('input[name=value9]').val(),
			factor10: $('input[name=factor10]').val(),
			value10: $('input[name=value10]').val(), 
			month_expense_total: $('input[name=month_expense_total]').val(),
			days_per_month: $('input[name=days_per_month]').val(),
			hours_per_day: $('input[name=hours_per_day]').val(),
			hours_per_month: $('input[name=hours_per_month]').val(),
			hourly_org_cost: $('input[name=hourly_org_cost]').val(),
			no_of_employees: $('input[name=no_of_employees]').val(),
			hourly_employee_cost: $('input[name=hourly_employee_cost]').val(),       
				},
			success:function(data, textStatus, jqXHR) {

				$('.loader_wall_onspot').hide();
				alert_message(data.message, "success");
				location.reload(true);
			},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>
@stop