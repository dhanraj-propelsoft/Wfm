<div class="modal-header">
	<h4 class="modal-title float-right">Today Rate</h4>
</div>
@section('content')

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			<div class="form-group col-md-4"> 
				{!! Form::label('on_date', 'Pricing Date', array('class' => 'control-label col-md-12 required')) !!}
			<div class="col-md-12"> 
				{!! Form::text('on_date',date('d-m-Y'),['class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy', 'id'=>'on_date']) !!} </div>
			</div>
		</div>
		<br>
		<br>
	
		<div class="row" style="margin-left: 10px;margin-right: 10px">
			<table id="datatable" class="table data_table" width="100%" cellspacing="0">
				<thead>
				  <tr>	
					<th>Product</th> 
					<th> Price </th>
					<th> Tax </th>
					<th>Total Amount </th>
				  </tr>
				</thead>
				<tbody>
					@foreach($item as $items)
					<tr>						
						<td>
							{{$items->item_name}}	

							{!! Form::hidden('item_id[]',$items->item_id,['class' => 'form-control item_id']) !!}
						
						</td>						
						<td>
							{!! Form::text('price',null,['class' => 'form-control price','id' => 'price','required']) !!}
						</td>
						<td>
							<select name='tax_id' class='form-control select_item taxes' id = 'tax_id' >

								<option value="">Select Tax</option>

								@foreach($taxes as $tax) 

								<option value="{{$tax->id}}" data-value="{{$tax->value}}" data-tax="{{$tax->tax_value}}" data-type="{{$tax->tax_type}}">{{$tax->display_name}}</option>

								@endforeach

							</select>
							<div class='tax_container'></div> 
						</td>	
						<td>
							{!! Form::text('total_amount', null,['class' => 'form-control total_amount','id'=>'total_amount']) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
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
	$(function loading(){

	$('.price,#tax_id,.total_amount').on("change input",calculate);

		  function calculate()
		   {
		   	var obj = $(this);
		   	var parent = obj.closest('tr');
			var price = parent.find('input[name=price]').val();
			//console.log(price)
		    var tax_id = parent.find('select[name=tax_id]').find('option:selected').data('value');
		    var quantity=1;
		   var tax_value = isNaN(tax_id) ? 0 : tax_id/100;
		   var amount = (price*quantity).toFixed(2);
		   var tax_amount = (amount*tax_value).toFixed(2);
		   console.log(tax_amount);
		  var total_amount=parseFloat(amount) +parseFloat( tax_amount);
		  parent.find('input[name=total_amount]').val(total=total_amount);
		   }
	});

		
	
	
	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
				
				
			
				},
			
	
messages: {
			//name: { required: "Unit Name is required." },
		
			
			
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

			var itemid= [];
				$('input:hidden[name="item_id[]"]').each(function()
				{
						itemid.push($(this).val());
				});

			var price= [];
				$('input:text[name="price"]').each(function()
				{
						price.push($(this).val());
				});
			var tax_id= [];
				$('select[name="tax_id"]').each(function()
				{
						tax_id.push($(this).find("option:selected").val());
				});
			var total_amount= [];
				$('input:text[name="total_amount"]').each(function()
				{
						total_amount.push($(this).val());
				});



			$.ajax({
			url: '{{ route('fsmitem_update') }}',
			type: 'post',
			data: {
					_token: '{{ csrf_token() }}',
					id: itemid,
					list_price:price,
					tax_id:tax_id,
					sale_price:total_amount,
					on_date:$('input[name=on_date]').val(),		
				  },
			success:function(data, textStatus, jqXHR) {
				

				$('.loader_wall_onspot').hide();
				$('.crud_modal .modal-container').hide();
			    window.location.reload()
				

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