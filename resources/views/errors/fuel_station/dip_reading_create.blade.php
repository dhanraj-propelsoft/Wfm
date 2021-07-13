{{--
@extends('layouts.master')
@section('content')

--}}
<div class="modal-header">
	<h4 class="modal-title float-right">Add Daily Dip Reading</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			<div class="form-group col-md-12"> 
				{{ Form::label('tankname', 'TankName', array('class' => 'control-label required')) }}
		
				{!! Form::select('tankname',$tankname, null,['class' => 'form-control' ,'id'=>'tank_id']) !!}
				{!! Form::label('', '', array('class' => 'control-label  required tank_result')) !!}
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-12"> 
				{{ Form::label('product', 'Product', array('class' => 'control-label required')) }}
		
				{!! Form::select('product',[], null,['class' => 'form-control']) !!}
			</div>
		</div>

		<div class="row">
	    	<div class="form-group col-md-8">
				<label class="control-label required" for="order_id">Reading Type</label> <br>
					<div class="custom-panel" >
						
						<input id="open" type="checkbox" name="reading_type" value="1" >
						<label for="open" class="custom-panel-radio"><span></span>Open</label>

						<input id="close" type="checkbox" name="reading_type"  value="2">
						<label for="close" class="custom-panel-radio" ><span></span>Close</label>

						<input id="on_demand" type="checkbox" name="reading_type"  value="3">
						<label for="on_demand" class="custom-panel-radio" ><span></span>On-Demand</label> 
					
					</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('dip_reading', ' Dip Reading ', array('class' => 'control-label  required','id'=>'itemtype')) !!}

				
					{!! Form::text('dip_reading', null,['class' => 'form-control','id'=>'dipreading']) !!}
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('temparature', ' Temparature ', array('class' => 'control-label  required','id'=>'itemtype')) !!}

				
					{!! Form::text('temparature', null,['class' => 'form-control']) !!}
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('quantity', 'Quantity', array('class' => 'control-label  required','id'=>'itemtype')) !!}

				
					{!! Form::text('quantity', null,['class' => 'form-control','id'=>'quantity','disabled']) !!}
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="form-group col-md-12"> 
				{{ Form::label('reading_by', 'ReadingBy', array('class' => 'control-label required')) }}
		
				{!! Form::select('reading_by',$reading_by, null,['class' => 'form-control']) !!}
			</div>
		</div>
		
		<div class="row">
			<input type="file" name="file-upload" class="form-control" style="display: inline-block;opacity: 0;	position: absolute;
				margin-left: 40px;margin-right: 20px;padding-top: 30px;
				padding-bottom: 67px;width: 85%;z-index: 99;
				margin-top: 10px;cursor:pointer;">
			<label for="file-upload" class="custom-file-upload" style="position:relative;display: inline-block;cursor: pointer;
				padding-top:40px;padding-bottom:40px;width:91%;
				border:1px dashed #ff5b57 !important;margin-left:20px;
				margin-right:20px;margin-top:10px;text-align:center;">
					
			</label>
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

<script type="text/javascript">

	
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			dip_reading:{ required: true },
		
			quantity:{ required: true },
			
				},
	
	messages: {
			//name: { required: "Unit Name is required." },
			pump_mechine: { required: " Pump Mechine Name is required."},
			
			quantity: { required: " Quantity is required."},
				
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
			url: '{{ route('dipreading_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				reading_type:$('input[name=reading_type]:checked').val(),
				tankname :$('select[name=tankname]').val(),
				product :$('select[name=product]').val(),  
				dip_reading: $('input[name=dip_reading]').val(),
				temparature: $('input[name=temparature]').val(),
				quantity: $('input[name=quantity]').val(),
				reading_by: $('select[name=reading_by]').val(),
				
				     
				},
			success:function(data, textStatus, jqXHR) {
				var approved_selected = "";
				var draft_selected = "";
				
				var selected_text = "Approved";
				var selected_class = "badge-success";

				if(data.data.status == 1) {
					open_selected = "selected";
					selected_text = "Approved";
					selected_class = "badge-success";
				} else if(data.data.status == 0) {
					progress_selected = "selected";
					selected_text = "Draft";
					selected_class = "badge-warning";
				} 

				var open_type="";
				var close_type="";
				var ondemand_type=''
				var type_text="Open";
				var type_class="badge-success";

				if(data.data.reading_type==1)
				{
					open_type="selected";
					type_text="Open";
					type_class="badge-success";
				}
				else if(data.data.reading_type==2)
				{
					close_type="selected";
					type_text="Close";
					type_class="badge-warning";
				}
				else if(data.data.reading_type==3)
				{
					ondemand_type="selected";
					type_text="On-Demand";
					type_class="badge-success";
				}


				var temp=data.data.temparature;
				if(temp == null){
					temp='';
				}
				else{
					temp=data.data.temparature;
				}
				

				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="tank" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					
				    <td>`+data.data.date+`</td>
				    <td>`+data.data.tankname+`</td>
				    <td>`+data.data.readingat+`</td>
				    <td>`+data.data.reading+`</td>
				    <td>`+data.data.quantity+`</td>
				    <td>`+temp+`</td>
				    <td>`+data.data.reading_by+`</td>
				   <td>
						<label class="grid_label badge `+type_class+` ">`+type_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control ">
							<option `+open_type+` value="1">Open</option>
							
							<option `+close_type+` value="2">Close</option>

							<option `+ondemand_type+` value="3">Close</option>
						</select>
							</td>
					<td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control active_status">
							<option `+approved_selected+` value="1">Approved</option>
							
							<option `+draft_selected+` value="0">Draft</option>
						</select>
							</td>
					
					
					</tr>`,`add`,data.message, data.data.id);

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
			//alert(status);
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('pumpmechine.status') }}";
			var data=change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
		});


$('#tank_id').click(function(){

          var tankID = $(this).val();  
          	//console.log(tankID);  
    if(tankID)
    {
        $.ajax({
           type:"GET",
           url:"{{url('fuel_station/get-product-list')}}/"+tankID,
        success:function(data, textStatus, jqXHR) {
        tank=data.data.tank; 
        res=data.data.mechine;
        type=data.data.type;
       
           
            if(res){
                $("#product").empty();
               
                $.each(res,function(key,value){
                    $("#product").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else
            {
               $("#product").empty();
            }

            if(type==1)
            {
            	$('#open').attr('disabled', true);
            	
            }
            else if(type==2)
            {
            	
            	$('#close').attr('disabled', true);
           
            }
             if(tank == 1)
		 	{
		 			$('.tank_result').text("This Tank All Ready Readed Today");
		 	}
		 	else
		 	{
		 			$('.tank_result').text("");
		 	}
           
        
           }
        });
    }
    else
    {
        $("#product").empty();
    }      
   });


	$('body').on('change keyup ', '#dipreading', function(e)
	{

		var reading = $(this).val(); 
		var tank_id=$('#tank_id').val(); 
		if(tank_id == null)
		{
			var value=0;
		  	 	$('#quantity').val(value);

		}else

		{	
			$.ajax({
		    type:"GET",
		    url:"{{url('fuel_station/get_dipreading')}}/"+reading+"/"+tank_id,
		    success:function(data, textStatus, jqXHR) {
		    $('#quantity').val(data.reading);
    		   }
		    });
		}

	});





	if ($('#dip_reading').val()=='') {
		$('#dip_reading').val('');

	}




	$('#open').click(function(){
		if($("#open").is(':checked'))
  			$("#close").prop("checked", false);
  			$("#on_demand").prop("checked", false);

	});

	$('#close').click(function(){
		if($("#close").is(':checked'))
  			$("#open").prop("checked", false);
  			$("#on_demand").prop("checked", false);

	});
	$('#on_demand').click(function(){
		if($("#on_demand").is(':checked'))
  			$("#close").prop("checked", false);
  			$("#open").prop("checked", false);

	});


</script>