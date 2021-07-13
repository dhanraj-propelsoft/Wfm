<div class="modal-header">
	<h4 class="modal-title float-right">Edit  GST Code</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}
<div class="modal-body">
	<div class="form-body">
		
			
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
				{!! Form::label('code', 'Edit code', array('class' => 'control-label  required')) !!}

				<div class="form-group">
				{!! Form::text('code',$gst->code,['class' => 'form-control']) !!}
				{!! Form::hidden('id',$gst->id,['class' => 'form-control']) !!}
			</div></div>
		</div>
		
		</div>	
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
			{!! Form::label('chapter', 'Edit Chapter', array('class' => 'control-label  required','id'=>'make')) !!}

				<div class="form-group">
				{!! Form::text('chapter',$gst->chapter,['class' => 'form-control']) !!}
			</div></div>
		</div>
		
		</div>	
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
				{!! Form::label('chaptername', 'Edit chapter', array('class' => 'control-label  required','id'=>'make')) !!}

				<div class="form-group">
				{!! Form::text('chaptername',$gst->chapter_name,['class' => 'form-control']) !!}
			</div></div>
		</div>
		
		</div>	
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
			{!! Form::label('description', 'Edit description', array('class' => 'control-label  required','id'=>'make')) !!}

				<div class="form-group">
				{!! Form::text('description',$gst->description,['class' => 'form-control']) !!}
			</div></div>
		</div>
		
		</div>	
		<div class="row">
				<div class="col-md-12">
					<div class="form-group">
				{!! Form::label('rate','Tax',array('class' =>'control-label  required'))  !!}
               <div class="form-group">
				<select class="form-control" name="rate">
					<option value=''>Select tax</option>
				
					@foreach ($rates as $rate) {
							<?php
							 $ids=0;
							 $rates=0;
						if($rate['id']==null)
						{
							$ids=null;
						}
						else{
							$ids=$rate['id'];
					    }
					    if($rate['rate']==null)
						{
							$rates="null";
						}
						else{
							$rates=$rate['id']."%";
					    }
					   ?>
					<option value="<?php echo $ids?>"<?php echo ($ids ==  $tax) ? 
					'selected="selected"' : '';?>><?php echo $rates; ?>
    				</option>
    				@endforeach
					}
				</select>
			</div></div>
		</div>
		
		</div>	
		
			</div>
		</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

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
			
			
			description: { 
				required: true },      
			},                
		

		messages: {
			//name: { required: "Unit Name is required." },
			description: { required: " Description is required."},                
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
				var tax=$('select[name=rate]').val();
				console.log(tax);
				var rate;
				if(tax==0)
				{
					rate="";
				}
				else
				{
					rate=tax;
				}
				
			$.ajax({
		url: '{{ url('admin/gst_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				 id: $('input[name=id]').val(),
				 code: $('input[name=code]').val(),
				 chapter: $('input[name=chapter]').val(),
				 chaptername: $('input[name=chaptername]').val(),
				 description: $('input[name=description]').val(),
				 rate:rate,

				
				//description: $('textarea[name=description]').val()                
				},
			success:function(data, textStatus, jqXHR) 
			{
                var gst_data=data.data;
                var rate1;
                if(gst_data.rate==null){
                	rate1="null";
                }
                else{
                	rate1=gst_data.rate+"%";
                }
                call_back(`<tr role="row" class="odd">
               
                                
                    <td>`+gst_data.code+`</td>
                    <td>`+gst_data.chapter+`</td>
                     <td>`+gst_data.chapter_name+`</td>
                    <td>`+gst_data.description+`</td>
                    <td>`+rate1+`</td>
                    <td><a data-id="`+gst_data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
                    
                    </tr>`,`edit`,data.message, gst_data.id);
			$('.loader_wall_onspot').hide();
		  	
			},

			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});



</script>