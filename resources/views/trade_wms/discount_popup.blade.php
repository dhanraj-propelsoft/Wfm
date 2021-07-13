<div class="modal-header" style="background-color: #e9ecef;">
	<h5 class="modal-title float-right"><b>Discound Adjustments</b></h5>
    <a  class="close" data-dismiss="modal">&times;</a>

</div>


{!! Form::open(['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}

	<div class="modal-body" style="overflow-y: scroll;">
		<div class="form-body">
			
					<!-- <table border="2">
						<tr>
							<td></td>
							<td>Goods</td>
							<td>Service</td>
							<td>Total</td>
						</tr>
						<tr>
							<td>Amount (without any discount)</td>
							<td><input type="text" name="amount_goods" class="form-control"></td>
							<td><input type="text" name="amount_service" class="form-control"></td>
							<td><input type="text" name="amount_total" class="form-control amount_total" value="{{ $total_with_tax }}" disabled="disabled"></td>
						</tr>
						<tr>
							<td>Adjustment Percent</td>
							<td><input type="text" name="amount_percent_goods" class="form-control"></td>
							<td><input type="text" name="amount_percent_services" class="form-control"></td>
							<td><input type="text" name="amount_percent_total" class="form-control"></td>
						</tr>
					</table> -->
				

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('amount', 'Amount (without any discount)', ['class' => ' control-label required']) !!}
				
					<input type="text" name="amount_total" class="form-control amount_total" value="{{ $total_with_tax }}" disabled="disabled">
				</div>
				
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('paying_amount', 'Paying Amount', ['class' => ' control-label required']) !!}
				
					{!! Form::text('paying_amount', null, array('class' => 'form-control paying_amount')) !!}
				</div>
				
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('adjustment_amount', 'Adjustment Amount', ['class' => ' control-label required']) !!}
				
					{!! Form::text('adjustment_amount', null, array('class' => 'form-control adjustment_amount','readonly')) !!}
				</div>
				
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('adjustment_percentage', 'Adjustment Percentage', ['class' => ' control-label required']) !!}
				
					{!! Form::text('adjustment_percentage', null, array('class' => 'form-control adjustment_percentage','readonly')) !!}
				</div>
				
			</div>
		</div>

		</div>
	</div>

	<div class="modal-footer" style="background-color: #e9ecef;">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<button type="button" class="btn btn-success apply">Apply</button>
	</div>
{!! Form::close() !!}

								
<script>
$(document).ready(function()
{
	 basic_functions();

	 $('.paying_amount').blur(function(){

	 	//alert();
	 	var paying_amount =$(this).val();
	 	//console.log(paying_amount);
	 	var amount_total = $('.amount_total').val();
	 	//console.log(amount_total);
	 	var adjustment_amount= parseFloat(amount_total - paying_amount).toFixed(2);
	 	console.log(parseFloat(amount_total - paying_amount).toFixed(2));
	 	console.log(parseInt(amount_total - paying_amount).toFixed(2));

	 	$('.adjustment_amount').val(adjustment_amount);
	 	var adjustment_percentage = (adjustment_amount/ amount_total)*100;

	 	var adjustment_percentages = parseFloat(adjustment_percentage).toFixed(2);

	 	console.log("adjustment_percentages"+adjustment_percentages);
	 	console.log("adjustment_percentage"+ parseInt(adjustment_amount/ amount_total).toFixed(2));

	 	$('.adjustment_percentage').val(adjustment_percentages+"%");




	 });

	 $('.apply').on('click',function(){
	 	//alert();
	 	var paying_amount =$('.paying_amount').val();
	 	//console.log(paying_amount);
	 	var amount_total = $('.amount_total').val();
	 	//console.log(amount_total);
	 	var adjustment_amount= parseFloat(amount_total - paying_amount).toFixed(2);
	 	//$('.adjustment_amount').val(adjustment_amount);
	 	var adjustment_percentage = (adjustment_amount/ amount_total)*100;

	 	var adjustment_percentages = parseFloat(adjustment_percentage).toFixed(2);

	 	if(adjustment_percentages)
	 	{
	 		$('input[name=new_discount_value]').val(adjustment_percentages).trigger('blur');

	 	}
		$('.discount_crud_modal').modal('hide');


	 });


	/* if($('.adjustment_percentage').val())
	 {
	 	$('input[name=new_discount_value]').trigger();
	 }*/

	/*$("input[name=is_sales]").on('change', function(){
	 	if(!$(this).is(':checked'))
		{			
			$("select[name=sales_ledger_id]").val('');
		}
	});

	$("input[name=is_purchase]").on('change', function(){
	 	if(!$(this).is(':checked'))
		{			
			$("select[name=purchase_ledger_id]").val('');
		}
	});

	$("input[name = is_sales]").on('change', function(){
	 	if($(this).is(':checked'))
		{
			$('.sales_ledgers').show();
		}
		else {
			$('.sales_ledgers').hide();
		}

	});

	$("input[name = is_purchase]").on('change', function(){
		if($(this).is(':checked'))
		{			
			$('.purchase_ledgers').show();
		}
		else {
			$('.purchase_ledgers').hide();
		}

	 });*/

$('.validateform').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				name: {	required: true	},
				value: { required: true	},
			},

			messages: {
				name: {	required: "Name is required."},
				value: {required: "Tax Value is required."},
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
				 url: '{{ route('tax.store') }}',
				 type: 'post',
				 data: {
					_token: '{{ csrf_token() }}',
					name: $('input[name=name]').val(),
					tax_type_id: $('select[name=tax_type_id]').val(),
					value: $('input[name=value]').val(),
					is_percent: $('input[name=is_percent]:checked').val(),
					is_sales: $('input[name=is_sales]:checked').val(),
					is_purchase: $('input[name=is_purchase]:checked').val(),
					/*sales_ledger_id: $('select[name=sales_ledger_id]').val(),
					purchase_ledger_id: $('select[name=purchase_ledger_id]').val(),*/
					description: $('textarea[name=description]').val(),  
					},
				 success:function(data, textStatus, jqXHR) {

					call_back(`<tr role="row" class="odd">
							<td>`+data.data.name+`</td>
							<td>`+data.data.value+`</td>
							<td>`+data.data.tax_type+`</td>
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
});
</script>
