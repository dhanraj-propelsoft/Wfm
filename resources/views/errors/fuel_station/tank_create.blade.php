<div class="modal-header">
	<h4 class="modal-title float-right">Add  Tank</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('tankname', ' Tank Name ', array('class' => 'control-label  required','id'=>'itemtype')) !!}

					{!! Form::text('tankname', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group col-md-6"> 
				{{ Form::label('product', 'Product', array('class' => 'control-label required')) }}
		
				{!! Form::select('product',$product, null,['class' => 'form-control select_item']) !!}
			</div>
		</div>
		
		<div class="row">
			<div class="form-group col-md-6"> 
				{{ Form::label('tankstructure', 'Tank Structure', array('class' => 'control-label required')) }}		
				 {!! Form::textarea('tankstructure',null,['class'=>'form-control', 'rows' => 1, 'cols' => 40]) !!}
			</div>
		
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('reading_time', 'Reading Time', array('class' => 'control-label  required','id'=>'itemtype')) !!}

					{!! Form::time('reading_time', null,['class' => 'form-control']) !!}

				</div>
			</div>
		</div>
		
			
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('reading_time1', 'Reading Time', array('class' => 'control-label  required','id'=>'itemtype')) !!}
			
					{!! Form::time('reading_time1', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('reading_time2', 'Reading Time', array('class' => 'control-label  required','id'=>'itemtype')) !!}
					{!! Form::time('reading_time2', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
			
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('smstomanager', 'SmsToManager', array('class' => 'control-label  required','id'=>'itemtype')) !!}
					{!! Form::text('smstomanager', null,['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('smstoowner', 'SmsToOwner', array('class' => 'control-label  required','id'=>'itemtype')) !!}
					{!! Form::text('smstoowner', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
		<div class="row" style="overflow-y: scroll; height:400px;">
			<div class="col-md-12">
					<table border="3" class="table data_bordered"  >
						<thead>
							<tr>
							<th width="30%" >Stick Length In CM</th>
							<th width="70%" style="text-align: center">Respective Valume In Liter</th>
							</tr>
						</thead>
						<tbody >
							<?php for($i=1;$i<501;$i++){ ?>
							<tr>
							<td style="text-align: left">
								<?php echo $i; ?>
								
							</td>
							<td>
								{!! Form::hidden('length[]', $i,['class' => 'form-control length']) !!}

								{!! Form::text('volume[]', null,['class' => 'form-control']) !!}</td>
							</tr>
						<?php }?>
						</tbody>
					</table>
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
	
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			tankname:{ required: true,

				remote:function(element) {
        return {
        	url: '{{ route('tankname_check') }}',
		 			type: "post",
            data: {
			 			 _token :$('input[name=_token]').val(),
						
						  },
						}
						
						  						 
						}
				}
			},
			messages: {
			//name: { required: "Unit Name is required." },
			tankname: { required: " Tankname name is required.",
			 remote: "The Tankname  is already exists!." },
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
			url: '{{ route('tank_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				tankname: $('input[name=tankname]').val(),
				product: $('select[name=product]').val(),
				tank_structure:$('textarea[name=tankstructure]').val(),
				reading_time:$('input[name=reading_time]').val(),
				reading_time1:$('input[name=reading_time1]').val(),
				reading_time2:$('input[name=reading_time2]').val(),
				smstomanager:$('input[name=smstomanager]').val(),
				smstoowner:$('input[name=smstoowner]').val(),
				length:$('input[name="volume[]"]').map(function() {
					if ($(this).val()) {
						return $(this).parent().find('.length').val();
					}
      					 }).get().join(),
				volume:$('input[name="volume[]"]').map(function() {
					if ($(this).val()) {
						return $(this).val();
					}
      					 }).get().join(),

				
				             
				},
			success:function(data, textStatus, jqXHR) {

				var time1=data.reading_time;
				if(time1 )
				var active_selected = "";
				var inactive_selected = "";
				
				var selected_text = "Active";
				var selected_class = "badge-success";

				if(data.data.status == 1) {
					open_selected = "selected";
					selected_text = "Active";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					progress_selected = "selected";
					selected_text = "In_Active";
					selected_class = "badge-warning";
				} 
				
				var time1=data.reading_time;
				if(time1== null){
					time1='';
				}
				else{
					time1=data.reading_time;
				}

				var time2=data.reading_time1;
				if(time2== null){
					time2='';
				}
				else{
					time2=data.reading_time1;
				}

				var time3=data.reading_time2;
				if(time3== null){
					time3='';
				}
				else{
					time3=data.reading_time2;
				}


				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="tank" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					
				     <td>`+data.data.name+`</td>
					<td>`+data.data.product+`</td>
					<td>`+time1+`</td>
					<td>`+time2+`</td>
					<td>`+time3+`</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control active_status">
							<option `+active_selected+` value="1">Active</option>
							
							<option `+inactive_selected+` value="0">In-Active</option>
						</select>
					</td>
					</tr>`,`edit`,data.message, data.data.id);

				$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});
	
$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  	$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('tank.status') }}";
			var data=change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
		});

</script>