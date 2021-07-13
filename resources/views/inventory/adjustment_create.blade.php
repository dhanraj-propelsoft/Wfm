<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
<div class="alert alert-danger">
	{{ Session::get('flash_message') }}
</div>
<div class="modal-header" style="background-color: #e9ecef;">
		<h5 class="modal-title float-right"><b>Add Inventory Adjustment</b></h5>
		<a  class="close" data-dismiss="modal">&times;</a>

</div>

		{!! Form::open(['class' => 'form-horizontal validateform']) !!}
										
		{{ csrf_field() }}

					@if($module_name=='fuel_station')
						<div class="modal-body" style="overflow-y: scroll;">
							<div class="form-body">

									<div class="form-group">
										{{ Form::label('tank', 'Tanks', array('class' => 'control-label col-md-5 required')) }}

										<div class="col-md-12">
										{!! Form::select('tank', $fsm_tanks, null, ['class' => 'select_items form-control', 'id' => 'tank']) !!}
										</div>
									</div>

									<div class="form-group">
										{{ Form::label('item_id', 'Items', array('class' => 'control-label col-md-5 required')) }}

										<div class="col-md-12">
										{!! Form::select('item_id',[],null,  ['class' => 'select_item form-control', 'id' => 'item_id']) !!}
										</div>
									</div>


									<div class="form-group">
										<div class="row">
										<div class="col-md-6">	
										{{ Form::label('adjustment_no', 'Adjustment No', array('class' => 'control-label col-md-12 required')) }}
										<div class="col-md-12">
										{!! Form::text('adjustment_no', $adjustment_no, ['class'=>'form-control', 'placeholder'=>'Adjustment No','id'=>'adjustment_no', 'disabled']) !!}
										</div>
										</div>
									
								
										<div class="col-md-6">
											{!! Form::label('date', 'Date', array('class' => 'control-label col-md-6 required')) !!}
										<div class="col-md-12">
											{!! Form::text('date', ($date_setting == 0) ? date('d-m-Y') : null,['class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy']) !!}

										</div>
										</div>
										</div>
									
									</div>


									<div class="form-group">
										<div class="row">

										<div class="col-md-4">	
										{{ Form::label('in_stock', 'Available Qty', array('class' => 'control-label col-md-12')) }}
										
										<div class="col-md-12">{!! Form::text('in_stock', $inventory_item_stocks, ['class'=>'form-control', 'id'=>'in_stock', 'disabled' => 'true']) !!}
										</div>
										</div>
										
									
										<div class="col-md-4">
										
										{{ Form::label('new_stock', 'New Qty', array('class' => 'control-label col-md-12 required')) }}
										
										<div class="col-md-12">{!! Form::text('new_stock', null, ['class'=>'form-control decimal', 'placeholder'=>'e.g.10, -10', 'id'=>'new_stock']) !!}
										</div>
										
										</div>

										<div class="col-md-4">

										{{ Form::label('quantity', 'Changing Qty', array('class' => 'control-label col-md-12')) }}
										
										<div class="col-md-12">{!! Form::text('quantity', null, ['class'=>'form-control', 'id'=>'quantity', 'disabled' => 'true']) !!}
										</div>
										</div>
										</div>
										
								 </div>


									<div class="form-group">	
										{{ Form::label('description', 'Reason', array('class' => 'control-label col-md-5')) }}
										<div class="col-md-12">{!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Reason','id'=>'description', 'size' => '3x4']) !!}
										</div>
									</div>
	

									
					
								 </div>
								 
														
									</div>
						</div>
						@else
						<div class="modal-body">
							<div class="form-body">

									<div class="form-group">
										{{ Form::label('item_id', 'Item', array('class' => 'control-label col-md-5 required')) }}

										<div class="col-md-12">
										{!! Form::select('item_id', $inventory_item, null, ['class' => 'select_item form-control', 'id' => 'item_id']) !!}
										</div>
									</div>
									 <div style="height:140px; overflow-y: auto;overflow-x: hidden;margin-left: 10px;">
									<table class="table batch_tables" style="display: none;">
										<thead>
											<tr>
												<th>Batch No</th>
												<th>Quantity</th>
												<th>Date</th>
												<th>Select</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
									</div>
									<br>


									<div class="form-group">
										<div class="row">
										<div class="col-md-6">	
										{{ Form::label('adjustment_no', 'Adjustment No', array('class' => 'control-label col-md-12 required')) }}
										<div class="col-md-12">
										{!! Form::text('adjustment_no', $adjustment_no, ['class'=>'form-control', 'placeholder'=>'Adjustment No','id'=>'adjustment_no', 'disabled']) !!}
										</div>
										</div>
									
								
										<div class="col-md-6">
											{!! Form::label('date', 'Date', array('class' => 'control-label col-md-6 required')) !!}
										<div class="col-md-12">
											{!! Form::text('date', ($date_setting == 0) ? date('d-m-Y') : null,['class' => 'form-control date-picker', 'data-date-format' => 'dd-mm-yyyy']) !!}

										</div>
										</div>
										</div>
									
									</div>


									<div class="form-group">
										<div class="row">

										<div class="col-md-4">	
										{{ Form::label('in_stock', 'Available Qty', array('class' => 'control-label col-md-12')) }}
										
										<div class="col-md-12">{!! Form::text('in_stock', $inventory_item_stocks, ['class'=>'form-control', 'id'=>'in_stock', 'disabled' => 'true']) !!}
										</div>
										</div>
										
									
										<div class="col-md-4">
										
										{{ Form::label('new_stock', 'New Qty', array('class' => 'control-label col-md-12 required')) }}
										
										<div class="col-md-12">{!! Form::text('new_stock', null, ['class'=>'form-control decimal', 'placeholder'=>'e.g.10, -10', 'id'=>'new_stock']) !!}
										</div>
										
										</div>

										<div class="col-md-4">

										{{ Form::label('quantity', 'Changing Qty', array('class' => 'control-label col-md-12')) }}
										
										<div class="col-md-12">{!! Form::text('quantity', null, ['class'=>'form-control', 'id'=>'quantity', 'disabled' => 'true']) !!}
										</div>
										</div>
										</div>
										
								 </div>


									<div class="form-group">	
										{{ Form::label('description', 'Reason', array('class' => 'control-label col-md-5')) }}
										<div class="col-md-12">{!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Reason','id'=>'description', 'size' => '3x4']) !!}
										</div>
									</div>
	

									
					
								 </div>
								 
														
									</div>
									</div>
									@endif
									<div class="modal-footer" style="background-color: #e9ecef;">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="submit" class="btn btn-success adjustment_save">Submit</button>
									</div>

							{!! Form::close() !!}		
						
					


<script type="text/javascript">
$(document).ready(function() {

		basic_functions();

		$(".rearrangedatetext").each(function() {
			var data = $(this).text();
			var mycustomdate = data.split('-');
			$(this).text($.trim(mycustomdate[2]) + '-' + $.trim(mycustomdate[1]) + '-' + $.trim(mycustomdate[0]));
		});

		$('body').on('change', 'select[name=item_id]', function(){
			var id = $(this).val();

			if(id != "") {

				$.ajax({

					 url: '{{ route('adjustment.get_available_quantity') }}',
					 type: 'post',
					 data: {
					 	_token : '{{csrf_token()}}',
					 	id: id,
					 },
					 dataType: "json",
					 success:function(data, textStatus, jqXHR) {
					    
					 	var result = data.result;
					 	var batche = data.batches;
					 	var batch_table = '';
					 	for(var i in batche)
		                {
		             batch_table += `<tr><td>`+batche[i].batch_number+`</td><td>`+batche[i].quantity+`</td><td>`+batche[i].created_at+`</td><td><input id=`+batche[i].id+` class="item_check" name="type" type="radio" data-quantity=`+batche[i].quantity+` value=`+batche[i].id+`><label for=`+batche[i].id+`><span></span></label></td>`;
		                }
		                $('.batch_tables').show();
		                $('.batch_tables tbody').empty().append(batch_table);
					 	var new_stock = isNaN(parseFloat($('input[name=new_stock]').val())) ? 0 : parseFloat($('input[name=new_stock]').val());
					 	$('input[name=in_stock]').val(result.in_stock);	
						if(!isNaN(parseFloat( result.in_stock ))) {
							$('input[name=quantity]').val(parseFloat(result.in_stock)+parseFloat(new_stock));
						}
					 	$('input[name=in_stock]').val(result.in_stock);	
					 }
				});
			}
		});

		$('body').on('input change', 'input[name=new_stock], select[name=item_id]', function() {
			var new_stock = parseFloat($('input[name=new_stock]').val());
// 			var in_stock = parseFloat($('input[name=in_stock]').val());
            var in_stock =parseFloat($('input[name=type]:checked').data('quantity'));
			if( new_stock < 0 ) {
				if( Math.abs(new_stock) > in_stock ) {
					$('input[name=new_stock]').val('-'+in_stock);
					new_stock = parseFloat('-'+in_stock);
				}
			}

			$('input[name=quantity]').val(parseFloat(in_stock)+parseFloat(new_stock));
				
		});
		
		$('body').on('input change', 'input[name=new_stock]', function() {
        	var radiochecked = $('input[name=type]:checked').val();
        	  if(radiochecked){ 
        	  }
        	  else{ 
        	    	alert_message("Select Any One Item Batch",'error');
        	    	$('input[name=new_stock]').val('');
        	  }
});
        $('body').on('change', 'input[name=type]', function(){
        	$('input[name=new_stock]').val('');
        	$('input[name=quantity]').val('');
        });

	});

	@if($module_name=='fuel_station')

		$('#tank').change(function(){

	          var tankID = $(this).val();
	         
		    if(tankID){
		        $.ajax({
		           type:"GET",
		           url:"{{url('fuel_station/get_product_details')}}/"+tankID,
		           success:function(data, textStatus, jqXHR) { 
		                      var item_val=data.item;
		                      var stock_val=data.stock;
		                      
		                $.each(item_val,function(key,value)
		                {
		                    $("#item_id").append('<option value="'+key+'">'+value+'</option>');
		                });
		                  $("#in_stock").val(stock_val);
		                  $("#quantity").val(stock_val);

					
		           
		           }
		        });
		    }else{
		        $("#item_id").empty();
		      
		    }      
	   	});
	@endif
	


		$('.validateform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input

            rules: {
              
                item_id: {
                    required: true
                },
                date: {
                    required: true
                },
                new_stock: {
                    required: true
                }
            },

            messages: {
               
                item_id: {
                    required: "Item is required."
                },
                date: {
                    required: "Date is required."
                },
                new_stock: {
                    required: "New quantity is required."
                }
            },
            

            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            submitHandler: function(form) {

            	$('.loader_wall_onspot').show();
            	$('.crud_modal').modal('show');

            	var item_id = $('select[name=item_id]').val();

            	var adjust_page = $('.crud_modal .modal-container').attr('data-page');

            	$('.adjustment_save').attr("disabled", true);

				$.ajax({
				 url: '{{ route('adjustment.store') }}',
				 type: 'post',
				 data: {
				 	_token: '{{ csrf_token() }}',
				 	item_id: $('select[name=item_id]').val(),
				 	batch_id: $('input[name=type]:checked').val(),
				 	adjustment_no: $('input[name=adjustment_no]').val(),
				 	date:$('input[name=date]').val(),
				 	description:$('textarea[name=description]').val(),
				 	quantity:$('input[name=new_stock]').val(),
				 	batch_quantity:$('input[name=quantity]').val(),
 					 	
                	},
					
				dataType: "json",
					success:function(data, textStatus, jqXHR) {

						if(item_id == null)
						{
							$('.adjustment_save').attr("disabled", true);
						}

						if(item_id == null)
						{
							$('.adjustment_save').attr("disabled", false);
						}

						$('.loader_wall_onspot').show();

						if(adjust_page == 1){

							location.assign("{{ route('item.index', ['items']) }}");
						}
						else{

							location.assign("{{ route('adjustment.index' )}}");
						}

						$('.alert-success').text('Quantity Updated..!');

						$('.alert-success').show();

						//$('.loader_wall_onspot').hide();

						//adjust_item(data.data.item_id, data.data.in_stock+ ' '+data.data.unit);
						
					},
				 error:function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					}
				});
            }
        });
        /*function adjust_item(id = null, closing_stock = null) {
        	
        }*/
	</script>
