@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.hrm')
@section('content')












<div class="modal-header">
	<h4 class="modal-title float-right">Add Department</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}                                        
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="form-group">
			{!! Form::label('name', 'Roaster Name', array('class' => 'control-label col-md-4 required')) !!}

			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>

		<div class="form-group">
		{!! Form::label('date', 'Duration', array('class' => 'control-label col-md-4 required')) !!}

							 
			<div class="col-md-12">
				<div class="input-group accounts-daterange input-daterange" data-date-format="dd-dd-yyyy">
				{!! Form::text('from_date',null,['class' => 'form-control datetype','placeholder'=>'From']) !!}
				<span class="input-group-addon"> to </span>
				{!! Form::text('to_date',null,['class' => 'form-control datetype','placeholder'=>'Till']) !!} </div>
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


@stop

@section('dom_links')
@parent

<script>
	$(document).ready(function() {

		$(".parentname").hide();

		$('input[type="checkbox"]').on('change', function() {
			$('select[name=parent_department]').val("");
			$('select[name=parent_department]').trigger("change");
			
			if($(this).is(":checked")) {
				$(".parentname").show();
			} 
			else {
				$(".parentname").hide();
				$('select[name=parent_id]').val('');
			}
		});

		basic_functions();
	});
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			name: { required: true },
			//parent_department: { required: true },                
		},

		messages: {
			name: { required: "Department Name is required." },
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
			url: '{{ route('roaster.store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				name: $('input[name=name]').val(),
				parent_department: $('select[name=parent_department]').val(),
				description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
					<td><input id="`+data.data.id+`" class="item_check" name="department" value="`+data.data.id+`" type="checkbox"><label for="`+data.data.id+`"><span></span></label>
					</td>
					<td>`+data.data.name+`</td>
					<td>`+data.data.parent_department+`</td>
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

				$('.loader_wall_onspot').hide();

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>
@stop