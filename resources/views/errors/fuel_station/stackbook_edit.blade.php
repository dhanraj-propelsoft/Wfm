<div class="modal-header">
	<h4 class="modal-title float-right">Daily Stock Book</h4>
</div>

	{!! Form::open([
		'class' => 'form-horizontal validateform'
	]) !!}
	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		<div class="row">
			<div class="form-group col-md-6">
				<div class="form-group">
					{!! Form::label('set_on', 'SetOn', array('class' => 'control-label  required','id'=>'set_on')) !!}

					
				
					{!! Form::date('set_on',$stockbook->date,['class' => 'form-control set_on','disabled']) !!}
				</div>
			</div>		
			<div class="form-group col-md-6"> 
				<div class="form-group">
					{!! Form::label('tank_name', 'Tank Name ', array('class' => 'control-label  required')) !!}

					{!! Form::select('tank_name',$tank,$stockbook->name,['class' => 'form-control tank_name', 'id' => 'tank_name','disabled']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6"> 
			
							<div class="form-group">
								{!! Form::label('opening', 'Opening ', array('class' => 'control-label  required')) !!}

								{!! Form::text('opening',$stockbook->opening,['class' => 'form-control opening', 'id' => 'opening']) !!}
							</div>
						
			</div>
		
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('purchase', 'Purchase', array('class' => 'control-label  required')) !!}

					{{ Form::text('purchase',$stockbook->purchase, ['class' => 'form-control purchase ', 'id' => 'purchase']) }} 

				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('sales', 'Sales', array('class' => 'control-label  required')) !!}

					{{ Form::text('sales',$stockbook->sales , ['class' => 'form-control sales ', 'id' => 'sales']) }} 

				</div>
			</div>
			<div class="form-group col-md-6"> 
				<div class="form-group">
					{!! Form::label('total_stock', 'TotalStock ', array('class' => 'control-label  required')) !!}

					{!! Form::text('total_stock',$stockbook->total_stock,['class' => 'form-control total_stock', 'id' => 'total_stock','disabled']) !!}
				</div>
			</div>
		</div>
		
			
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('testing', 'Testing', array('class' => 'control-label  required')) !!}
			
					{!! Form::text('testing', $stockbook->testing,['class' => 'form-control testing ','id' => 'testing']) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('closing', 'Closing', array('class' => 'control-label  required')) !!}
					{!! Form::text('closing' , $stockbook->closing ,['class' => 'form-control closing','id' => 'closing','disabled']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('unit_rate', 'UnitRate', array('class' => 'control-label  ')) !!}
			
					{!! Form::text('unit_rate', $stockbook->unit_rate,['class' => 'form-control unit_rate ','id' => 'unit_rate','disabled']) !!}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('sales_worth', 'SalesWorth', array('class' => 'control-label  ')) !!}
					{!! Form::text('sales_worth', $stockbook->sales_worth,['class' => 'form-control sales_worth','id' => 'sales_worth','disabled']) !!}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('stock_worth', 'StockWorth', array('class' => 'control-label  ')) !!}
					{!! Form::text('stock_worth', $stockbook->stock_worth,['class' => 'form-control stock_worth','id' => 'stock_worth','disabled']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					
					{!! Form::hidden('bookid',$stockbook->bookid,['class' => 'form-control bookid','id'=>'bookid']) !!}
				</div>
			</div>
		</div>
			
		<!-- <div class="row">
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('dip_reading', 'DipReading', array('class' => 'control-label  required')) !!}
					{!! Form::text('dip_reading', null,['class' => 'form-control dip_reading']) !!}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('dip_stock', 'Dip Stock', array('class' => 'control-label  required')) !!}
					{!! Form::text('dip_stock', null,['class' => 'form-control dip_stock']) !!}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::label('dip_variation', 'Dip Variation', array('class' => 'control-label  required')) !!}
					{!! Form::text('dip_variation', null,['class' => 'form-control dip_variation']) !!}
				</div>
			</div>
		</div>
		 -->

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
			tank_name:{ required: true },
			
				},
	
messages: {
			//name: { required: "Unit Name is required." },
			tank_name: { required: " tank_name is required."},
			
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
			url: '{{ route('stockbook_update') }}',
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				set_on:$('input[name=set_on]').val(),
				tank_id: $('select[name=tank_name]').val(),
				opening:$('input[name=opening]').val(),
				purchase:$('input[name=purchase]').val(),
				sales:$('input[name=sales]').val(),
				total_stock:$('input[name=total_stock]').val(),
				testing:$('input[name=testing]').val(),
				closing:$('input[name=closing]').val(),
				
				unit_rate:$('input[name=unit_rate]').val(),
				sales_worth:$('input[name=sales_worth]').val(),
				stock_worth:$('input[name=stock_worth]').val(),
				bookid:$('input[name=bookid]').val(),
				
				
				             
				},
			success:function(data, textStatus, jqXHR) {
				


				call_back(`<tr role="row" class="odd">
					<td>
						<input id="`+data.data.id+`" class="item_check" name="tank" value="`+data.data.id+`" type="checkbox">
						<label for="`+data.data.id+`"><span></span></label>
					</td>
					
				    <td>`+data.data.date+`</td>
					<td>`+data.data.tankname+`</td>
					<td>`+data.data.product+`</td>
					<td>`+data.data.opening+`</td>
					<td>`+data.data.purchase+`</td>
					 <td>`+data.data.sales+`</td>
					<td>`+data.data.total_stock+`</td>
					<td>`+data.data.testing+`</td>
					<td>`+data.data.closing+`</td>
					<td>`+data.data.unit_rate+`</td>
					<td>`+data.data.sales_worth+`</td>
					<td>`+data.data.stock_worth+`</td>
					
					
					</tr>`,`add`,data.message);

				$('.loader_wall_onspot').hide();
				

				},
			error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

	

	$('body').on('change keyup ', '.opening', function(e) {

		var opening = $(this).val();
		var purchase=$('.purchase').val();
		var sales =$('.sales').val();
		var testing =$('.testing').val();
	    var unit_rate =$('.unit_rate').val();
		var add=  parseInt(opening) +  parseInt(purchase);
		var total_stock= parseInt(add)-parseInt(sales);
		var closing=parseInt(total_stock)-parseInt(testing);
		var stock_worth=parseInt(total_stock)*parseInt(unit_rate);

		 $("#total_stock").val(total_stock);
		 $("#closing").val(closing);
		  $("#stock_worth").val(stock_worth);
		});

	$('body').on('change keyup ', '.purchase', function(e) {

		var purchase = $(this).val();
		var opening=$('.opening').val();
		var sales =$('.sales').val();
		 var unit_rate =$('.unit_rate').val();
		var testing =$('.testing').val();

		var add=  parseInt(opening) +  parseInt(purchase);

		var total_stock= parseInt(add)-parseInt(sales);

		var closing=parseInt(total_stock)-parseInt(testing);
		var stock_worth=parseInt(total_stock)*parseInt(unit_rate);
		
	
		 $("#total_stock").val(total_stock);
		 $("#closing").val(closing);
		  $("#stock_worth").val(stock_worth);
	
		});

	$('body').on('change keyup ', '.sales', function(e) {

		var sales = $(this).val();
		var opening=$('.opening').val();
		var purchase =$('.purchase').val();
		var testing =$('.testing').val();
		var unit_rate =$('.unit_rate').val();
		
		var add=  parseInt(opening) +  parseInt(purchase);
		var total_stock= parseInt(add)-parseInt(sales);
		var closing=parseInt(total_stock)-parseInt(testing);
		var rate=parseInt(unit_rate) *  parseInt(sales);
		var stock_worth=parseInt(total_stock)*parseInt(unit_rate);
		

		 $("#total_stock").val(total_stock);
		 $("#closing").val(closing);
		  $("#sales_worth").val(rate);
		   $("#stock_worth").val(stock_worth);
		});
	$('body').on('change keyup ', '.testing', function(e) {

		var testing = $(this).val();
		var total_stock=$('.total_stock').val();
		var closing=parseInt(total_stock)- parseInt(testing);	
	
		 $("#closing").val(closing);

	
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
			
		});

  	$('.tank_name').change(function(){

          var pumpID = $(this).val();
         
    if(pumpID){
        $.ajax({
           type:"GET",
           url:"{{url('fuel_station/get_product_stock')}}/"+pumpID,
           success:function(res){               
            if(res){
            	
                $("#opening").val(res.opening);
                $("#purchase").val(res.purchases);
                $("#sales").val(res.sales);                
                $("#total_stock").val(res.total_stock);
                $("#testing").val(res.testing);                
                $("#closing").val(res.closing);
                   $("#product").val(res.product);
               
           
            }else{
               $("#opening").empty();
            }
           }
        });
    }else{
        $("#opening").empty();
      
    }      
   });
</script>