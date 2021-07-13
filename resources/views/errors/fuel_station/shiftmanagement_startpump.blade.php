<div class="modal-header">
	<h4 class="modal-title float-right">Start Pump Shift</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">

			<div class="form-group col-md-3"> 
				{!! Form::label('Shift', ' Shift ', array('class' => 'control-label  required')) !!}
				{!! Form::select('Shift',$shift,null,['class' => 'form-control   shift','id'=>'shift']) !!}
				{!! Form::label('', '', array('class' => 'control-label  required shift_result')) !!}
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('employee', ' Employee Name ', array('class' => 'control-label  required')) !!}

					{!! Form::select('employee',$employee,null,['class' => 'form-control select_item']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<div class="col-xs-12">
           				 {!! Form::label('date','Date Here', array('class' => 'control-label required ')) !!}
          				  {!! Form::text('date',Carbon\Carbon::today()->format('d.m.Y'),array('class'=>'form-control datepicker','data-date-format'=>'dd.mm.yyyy','readonly'=>'true')) !!}
       				 </div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<div class="col-xs-12">
						{!! Form::label('start_time', 'Shift Start At', array('class' => 'control-label  required','id'=>'itemtype')) !!}

						{!! Form::time('start_time',$mytime, null,['class' => 'form-control','readonly'=>'true']) !!}
					</div>
				</div>
			</div>

		</div>
		<br>
		<div class="row" style="overflow-y:scroll;">
			<div class="col-md-6">
					<table border="3" class="table data_bordered"  >
						<thead>
							<tr>
							<th width="30%" >Pump Name</th>
							<th width="70%" style="text-align: center">Product</th>
							</tr>
						</thead>
						<tbody >
							
							@foreach($pumpname as $pump)
							<tr>
							<td style="text-align: left">
								{{$pump->pumpsname}}
								
							</td>
							<td>{{$pump->productname}}</td>
								
							</tr>
							@endforeach
							
						</tbody>
					</table>
				</div>
				</div>
				<div class="row">
			<div class="form-group col-md-12"> 
				{{ Form::label('notes', 'Notes', array('class' => 'control-label required')) }}		
				 {!! Form::textarea('notes',null,['class'=>'form-control', 'rows' => 1, 'cols' => 40]) !!}
			</div>
		</div>
	</div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>{!! Form::close() !!}

{{--

	@stop

@section('dom_links')
@parent 				
 

--}}

<script>
	$(document).ready(function() {		

		basic_functions();
	}); 

	$('body').on('click', '#shift', function(e) {

	 	var shift_id=$(this).val()

	$.ajax({
		type:"GET",
		url:"{{url('fuel_station/get_shift')}}/"+shift_id,
		 success:function(res)
		 { 
		     console.log(res);
		 	if(res == 1)
		 	{
		 			$('.shift_result').text("Already Create This Shift Today");
		 	}
		 	else
		 	{
		 			$('.shift_result').text("");
		 	}
		 

		 }

	});
	});
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {

			employee:{ required: true },
			
			
			},
	
messages: {
			//name: { required: "Unit Name is required." },
			tankname: { required: " TankName is required."},
			
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
			url: '{{ route('shiftstartpump_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',

				Shift: $('select[name=Shift]').val(),
				employee: $('select[name=employee]').val(),
				date:$('textarea[name=date]').val(),
				start_time:$('input[name=start_time]').val(),
				notes:$('textarea[name=notes]').val(),

				},
			success:function(data, textStatus, jqXHR) {
				
				 window.location.href = 'shiftmanagement';
					
						$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
	

</script>