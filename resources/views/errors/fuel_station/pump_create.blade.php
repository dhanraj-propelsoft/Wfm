<div class="modal-header">
	<h4 class="modal-title float-right">Add Pump Mechine</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			<div class="form-group col-md-12"> 
				{{ Form::label('tankname', 'TankName', array('class' => 'control-label  required')) }}
		
				{!! Form::select('tankname',$tankname, null,['class' => 'form-control select_tankname' ,'id' => 'tankname']) !!}
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('mechinename', 'Mechinename', ['class' => ' control-label  required']) !!}
				
					{!! Form::select('mechinename',[],null,['class' => 'form-control select_mechinename','id' => 'mechinename']) !!}
			
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('pump_name', ' Pump Name ', array('class' => 'control-label  required','id'=>'pump_name')) !!}

				
					{!! Form::text('pump_name', null,['class' => 'form-control','id'=>'pump_name']) !!}
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('description', ' Description ', array('class' => 'control-label  required')) !!}

				
					{!! Form::text('description', null,['class' => 'form-control','id'=>'description']) !!}
				</div>
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
			pump_name:{ required: true },
			mechinename:{ required: true },
			pump_name:{required:true,

				remote:function(element) 
			{
        return {
        	url: '{{ route('pumpname_check') }}',
		 			type: "post",
            data: {
			 			 _token :$('input[name=_token]').val(),
			 			tank_id :$('select[name=tankname]').val(),
						mechine_id :$('select[name=mechinename]').val(), 
						
				  },
				}
						
						  						 
			}
			   } 
				},
			
	
messages: {
			//name: { required: "Unit Name is required." },
			pump_name: { required: " Pump  Name is required.",
			
			 remote: "The Pumpname is already exists!." },
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
			url: '{{ route('pump_store') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				tankname :$('select[name=tankname]').val(),
				mechinename :$('select[name=mechinename]').val(), 
				pump_name: $('input[name=pump_name]').val(),
				description: $('input[name=description]').val(),
				},
			success:function(data, textStatus, jqXHR) {
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

				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="tank" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					
				     <td>`+data.data.name+`</td>
				      <td>`+data.data.pumpmechine+`</td>
				       <td>`+data.data.tank+`</td>
				       <td>
						<label class="grid_label badge `+selected_class+` status">`+selected_text+`</label>
						<select style="display:none" id="`+data.data.id+`"  class=" form-control active_status">
							<option `+active_selected+` value="1">Active</option>
							
							<option `+inactive_selected+` value="0">In-Active</option>
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

	
  $("body").on("keypress blur","#pump_name",function(){
  	 var tankID = $('.select_tankname').find('option:selected').text();  
  	 var mechineID = $('.select_mechinename').find('option:selected').text(); 
  	  var pumpname = $(this).val(); 
  	    $("#description").val(tankID+"/"+mechineID+"/"+pumpname);
    // console.log(tankID);
    // console.log(mechineID);
    // console.log(pumpname);
  });


$('#tankname').click(function(){

          var tankID = $(this).val();  
          	//console.log(tankID);  
    if(tankID){
        $.ajax({
           type:"GET",
           url:"{{url('fuel_station/get-mechine-list')}}/"+tankID,
           success:function(res){               
            if(res){
                $("#mechinename").empty();
               
                $.each(res,function(key,value){
                    $("#mechinename").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#mechinename").empty();
            }
           }
        });
    }else{
        $("#mechinename").empty();
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
			var url = "{{ route('pump.status') }}";
			var data=change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
		});

</script>