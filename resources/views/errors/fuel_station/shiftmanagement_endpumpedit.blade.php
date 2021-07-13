		
			
			
		<div class="modal-header">
			<h4 class="modal-title float-right">End Pump Shift Edit</h4>
		</div>
<style type="text/css">
	.control-label{
		 margin-bottom: 0;}
	.start{
		 	width: 156px;
    	  }
    .cash {
    	 margin-bottom: 0;
    }
    .pump{
    	padding:0px;
    }
	.table td, .table th {
    padding: 0px;

	}
	.table-bordered thead th {
    border-bottom-width: 0px;
    border-top-width: 0px;
    border-left-width: 0px;
    border-bottom-width: 0px;
}
   		

</style>
			

		<div class="form-body" style="padding: 15px 25px 55px; ">
		  	<ul class="nav nav-tabs">
			  	<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link active" data-toggle="tab" href="#attachments">Shift Details</a> </li>
				 
				<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#fuelcashsales">Fuel Cash Sales</a> </li> 

		  		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#fuelccsales">Fuel Credit  Sales</a> </li>

		  		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#readings">Fuel Credit  Card Sales</a> </li>

		  		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#fuel_othersales">Other Sales</a> </li> 

		  		<li class="nav-item"> <a style="font-size: 14px; font-weight: bold;" class="nav-link" data-toggle="tab" href="#randominvoice">Random Invoices</a> </li> 		
		  		
		   		
		  
		  	</ul>
			<div class="tab-content">

				<div class="tab-pane active" id="attachments"  style="overflow-y: scroll;height: 500px;overflow-x:  scroll;" >
						{!! Form::open(['class' => 'form-horizontal endshift']) !!}
						{{ csrf_field() }}

						<div class="row" style="margin-top: 10px">
							<div class="form-group col-md-2" > 
								{!! Form::label('shift', ' Shift ', array('class' => 'control-label  required')) !!}
								
								{!! Form::text('shift',$end_pumpshift->shift_name,['class'=>'form-control shiftname','id' => 'shiftname','disabled']) !!}

								{!! Form::hidden('shift_id',$end_pumpshift->shift_id,['class'=>'form-control shiftid','id' => 'shiftid','disabled']) !!}
								
							</div>

							<div class="col-md-2">
							<div class="form-group">
							
				           		{!! Form::label('date','Date', array('class' => 'control-label required ')) !!}
				          		{!! Form::text('date',$end_pumpshift->date,array('class'=>'form-control datepicker','data-date-format'=>'dd.mm.yyyy')) !!}
				       	
							</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::label('employee', ' Employee Name ', array('class' => 'control-label  required')) !!}

									{!! Form::select('employee',$employee,$end_pumpshift->employee_id,['class' => 'form-control']) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									
										{!! Form::label('start_at', 'Shift Start At', array('class' => 'control-label  required','id'=>'itemtype')) !!}

										{!! Form::time('start_at',$end_pumpshift->start_time,['class' => 'form-control start','id'=>'start']) !!}
									
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									
										{!! Form::label('end_at', 'Shift End At', array('class' => 'control-label  required','id'=>'itemtype')) !!}

										{!! Form::time('end_at',$mytime, ['class' => 'form-control']) !!}
									
								</div>
							</div>
						</div>
						<div class=" cash" style=""><b><u>Cash book Details:</u></b>
							<div class="row cash">
								<div class="col-md-2" >
									<div class="form-group">
										{!! Form::label('fuelsales', ' Fuel Sales', array('class' => 'control-label  required')) !!}

										{!! Form::text('fuelsales', $fuelsales, array('class' => 'form-control', 'id' => 'fuelsales', )) !!}

									</div>
								</div>
								<div class="col-md-2" >
									<div class="form-group">
										{!! Form::label('othersales', ' Other Sales ', array('class' => 'control-label  required')) !!}

										{!! Form::text('othersales',$othersales,array('class' => 'form-control', 'id' => 'othersales')) !!}
									</div>
								</div>
								<div class="col-md-2" >
									<div class="form-group">
										{!! Form::label('otherreceipt', 'Other Receipt ', array('class' => 'control-label  required')) !!}

										{!! Form::text('otherreceipt',$other_receipts,array('class' => 'form-control', 'id' => 'otherreceipt' )) !!}
									</div>
								</div>
								<div class="col-md-2" >
									<div class="form-group">
										{!! Form::label('expenses', ' Expenses', array('class' => 'control-label  required')) !!}

										{!! Form::text('expenses',$expenses,array('class' => 'form-control', 'id' => 'expenses')) !!}
									</div>
								</div>
								<div class="col-md-2" >
									<div class="form-group">
										{!! Form::label('total', ' Total', array('class' => 'control-label  required')) !!}

										{!! Form::text('total',$total_sales,array('class' => 'form-control','id' => 'total')) !!}
									</div>
								</div>
							</div>
						</div>
						<h6 ><b><u>Fuel Sales By Pump:</u></b></h6>
						<div class=" col-md-12 row" style="padding-left: 20px">
							<div class="form-group" >
							<table class="datatable table-bordered shift_table" id="shifttable"  border="5">
								<thead>
									<tr>
										<th>Pump Name</th>
										<th>Product</th>
										<th style="align-content: center;">At the Rate </th>
										<th>Open meter</th>
										<th >Close meter</th>						
										<th >Testing</th>
										<th>Sales Quantity</th>
										<th>Sales By Pump </th>
										<th>Attendant</th>
										<th>Sales Quantity</th>
										<th>Sales By Cash </th>
										<th>More/Less</th>
										<th>Notes</th>
									</tr>
								</thead>
								<tbody>		
								<?php $total_salesquantity=0;$total_sales_pump=0;
								$total_atentantqty=0;$total_attendantcash=0; $total_descrepancy=0;?>							 
									@foreach($end_data as $pump)
									<tr class="parent_items">
										<td class="pump">{{$pump->pumpsname}}

											{!! Form::hidden('manage_id[]', $pump->manage_id, array('class' => 'form-control', 'id' => 'manage_id', )) !!}

											{!! Form::hidden('cash_detail_id[]',$pump->cash_detail_id,null,['class' => 'form-control']) !!}
												
										</td>	
										<td>{{$pump->productname}}
											{!! Form::hidden('productid[]',$pump->productid,null,['class' => 'form-control']) !!}
										</td>
										<td class="rate_td">{{$pump->selling_price}}
											{!! Form::hidden('at_rate[]',$pump->selling_price,null,['class' => 'form-control']) !!}
										</td>
										<td class="openmeter_td">
											{!! Form::text('open_meter[]',$pump->pump_openmeter,['class' => 'form-control open_meter','id' => 'open_meter']) !!}
										</td>
										<td class="closemeter_td">
											{!! Form::text('close_meter[]',$pump->pump_closemeter,['class' => 'form-control close_meter','id' => 'close_meter']) !!}
										</td>
										<td class="testing_td">
											{!! Form::text('testing[]',$pump->pump_testing,['class' => 'form-control testing','id' => 'testing']) !!}
										</td>
										<td class="quantity_td" id="quantity_td">
											{!! Form::text('quantity[]',$pump->pump_salesquantity,['class' => 'form-control quantity','id' => 'quantity','disabled']) !!}
										</td>
										<td class="salesbycash_td">
											{!! Form::text('salesby_cash[]',$pump->pump_sales,['class' => 'form-control salesbycash','id'=>'salesby_cash','disabled']) !!}
										</td>
										<td >
											{!! Form::text('attendant[]',$pump->rep_pumpattendant,['class' => 'form-control attendant','id'=>'attendant']) !!}
										</td>
										<td  class="salequantity_td">
											{!! Form::text('sale_quantity[]',$pump->rep_salesquantity,['class' => 'form-control sale_quantity','id'=>'sale_quantity']) !!}
										</td>
										<td class="sale_cash_td">
											{!! Form::text('sale_cash[]',$pump->rep_salesbycash,['class' => 'form-control sale_cash','id'=>'sale_cash']) !!}
										</td>
										<td class="result_td">
											

											{!! Form::text('more_less[]', $pump->repvspump_descrepancy,['class' => 'form-control more_less','disabled']) !!}
										</td>
										<td>
											{!! Form::text('notes[]',$pump->notes,['class' => 'form-control notes']) !!}
										</td>
											<?php 
											$total_salesquantity= $total_salesquantity+$pump->pump_salesquantity;
											$total_sales_pump=	$total_sales_pump+$pump->pump_sales;
											$total_atentantqty=$total_atentantqty+$pump->rep_salesquatity;
											$total_attendantcash=$total_attendantcash+$pump->rep_salesbycash;
											$total_descrepancy=$total_descrepancy+$pump->repvspump_descrepancy;?>
									</tr>								
										@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td>Total By System</td>
										<td class="totalquantity_td">{!! Form::text('totalquantity[]',$total_salesquantity,['class' => 'form-control totalquantity','id' => 'totalquantity','disabled']) !!}</td>
										<td>{!! Form::text('totalcash[]',$total_sales_pump,['class' => 'form-control totalcash','id' => 'totalcash','disabled']) !!}</td>
										<td>Total by Manual</td>
										<td>{!! Form::text('salequantity[]',	$total_atentantqty,['class' => 'form-control salequantity_total','id' => 'salequantity','disabled']) !!}</td>
										<td>{!! Form::text('salecash[]',$total_attendantcash,['class' => 'form-control salecash_total','id' => 'salecash','disabled']) !!}</td>
										<td>{!! Form::text('total_descrepancy[]',$total_descrepancy,['class' => 'form-control total_descrepancy','id' => 'total_descrepancy','disabled']) !!}</td>
										<td></td>
										
									</tr>
								</tbody>
							</table>
							</div>
						</div>			
						<h6><u><b>	Summary By Invoice</b></u> </h6>
						<div class="row" style="padding-left: 20px">
							<div class="form-group">
								<table class="col-md-12">
									<tbody>
										
										<tr>
											<td>
												Fuel Cash Sales:
											</td>
											<td><?php echo $cashsale_report; ?>
												{!! Form::hidden('fuel_cashsale',$cashsale_report,null,['class' => 'form-control']) !!}
											</td>
											
										</tr>
										<tr>
											<td>
												Fuel Credit Sale:
											</td>
											<td>
												<?php echo $creditsale_report; ?>
													{!! Form::hidden('fuelcredit_sale',$creditsale_report,null,['class' => 'form-control']) !!}
												</td>
											
										</tr>
									
										<tr>
											<td>
												Fuel Credit Card:
											</td>
											<td><?php echo $creditcardsale_report; ?>
												{!! Form::hidden('fuelcreditcard_sale',$creditcardsale_report,null,['class' => 'form-control']) !!}
											</td>
										</tr>					
										
										
										<tr>
											<td>
												Other Sale Cash :
											</td>
											<td><?php echo $otherscash_report; ?></td>
											
												{!! Form::hidden('otherscash_report',$otherscash_report,null,['class' => 'form-control']) !!}
											</td>
										
										</tr>
										<tr>
											<td>
												Other Sale Credit :
											</td>
											<td><?php echo $otherscredit_report; ?>
												{!! Form::hidden('otherscredit_report',$otherscredit_report,null,['class' => 'form-control']) !!}
											</td>
											
										</tr>
									</tbody>
								</table>	
							</div>
						</div> 
					
						<div class="modal-footer">                                            
							<button type="button" class="btn btn-default " data-dismiss="modal"  >Cancel</button>
							<button type="submit" class="btn btn-success">Submit</button>
						</div>
				</div>
		
				{!! Form::close() !!}

				<div class="tab-pane" id="fuelcashsales" >
					<h4>Fuel Cash Sales</h4>
					<div class="row" >
						<div class="col-md-12 ">
							<table border="10" class="table table_bordered" style="margin-top: 30px" >
								<thead>
									<tr>
										<th>Invoice Number</th>
										<th>Pump Name</th>
										<th>Product</th>
										<th>Vechicle</th>
										<th>Customer</th>
										<th>Sales Quantity</th>
										<th>Sales Amount</th>
										<th>At The Rate</th>
									</tr>
								</thead>
								<tbody >
										<?php 
										$cashsalequality=0;$cashsalesaamount=0;
										?>
										@foreach($cash_sale as $fuelcash)
									<tr>
										<td style="text-align: left">
											{{$fuelcash->invoice_number}}					
										</td>
										<td>{{$fuelcash->pump_name}}</td>
										<td>{{$fuelcash->item_name}}</td>
										<td>{{$fuelcash->registration_no}}</td>
										<td>{{$fuelcash->employee_name}}</td>
										<td>{{$fuelcash->quantity}}</td>
										<td>{{$fuelcash->amount}}</td>
										<td>{{$fuelcash->rate}}</td>	
										<?php 
										$cashsalequality=$cashsalequality+$fuelcash->quantity;
										$cashsalesaamount=$cashsalesaamount+$fuelcash->amount;
										 ?>
									</tr>
										@endforeach
									<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Total</td>
											<td><?php echo $cashsalequality;?></td>
											<td><?php echo $cashsalesaamount	;?></td>
											<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="fuelccsales" >
					<h4>Fuel Credit Sales</h4>
					<div class="row" >
						<div class="col-md-12">
							<table border="5" class="table table_bordered" style="margin-top: 30px" >
								<thead>
									<tr>
										<th >Invoice Number</th>
										<th>Pump Name</th>
										<th>Product</th>
										<th>Vechicle</th>
										<th>Customer</th>
										<th>Sales Quality</th>
										<th>Sales Amount</th>
										<th>At The Rate</th>
									</tr>
								</thead>
								<tbody >
										<?php 
										$quantity_total=0;$amount_total=0;
										?>
										@foreach($credit_sale as $fuel_creditsale)
									<tr>
										<td style="text-align: left">
											{{$fuel_creditsale->invoice_number}}					
										</td>
										<td>{{$fuel_creditsale->pump_name}}</td>
										<td>{{$fuel_creditsale->item_name}}</td>
										<td>{{$fuel_creditsale->registration_no}}</td>
										<td>{{$fuel_creditsale->employee_name}}</td>
										<td>{{$fuel_creditsale->quantity}}</td>
										<td>{{$fuel_creditsale->amount}}</td>
										<td>{{$fuel_creditsale->rate}}</td>	
										<?php 
										$quantity_total=$quantity_total+$fuel_creditsale->quantity;
										$amount_total=$amount_total+$fuel_creditsale->amount;
										 ?>
									</tr>
										@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td>Total</td>
										<td><?php echo $quantity_total;?></td>
										<td><?php echo $amount_total	;?></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="readings" >
					<h4>Fuel Credit Card Sales</h4>
					<div class="row" >
						<div class="col-md-12">
							<table border="5" class="table table_bordered" style="margin-top: 30px" >
								<thead>
									<tr>
									<th >Invoice Number</th>
									<th>Pump Name</th>
									<th>Product</th>
									<th>Vechicle</th>
									<th>Customer</th>
									<th>Sales Quality</th>
									<th>Sales Amount</th>
									<th>At The Rate</th>
									</tr>
								</thead>
								<tbody >
									<?php 
									$saquality_total=0;$saamount_total=0;
									?>
									@foreach($creditcard_sale as $creditcard_sale)
									<tr>
									<td style="text-align: left">
										{{$creditcard_sale->invoice_number}}					
									</td>
									<td>{{$creditcard_sale->pump_name}}</td>
									<td>{{$creditcard_sale->item_name}}</td>
									<td>{{$creditcard_sale->registration_no}}</td>
									<td>{{$creditcard_sale->employee_name}}</td>
									<td>{{$creditcard_sale->quantity}}</td>
									<td>{{$creditcard_sale->amount}}</td>
									<td>{{$creditcard_sale->rate}}</td>	
									<?php 
									$saquality_total=$saquality_total+$creditcard_sale->quantity;
									$saamount_total=$saamount_total+$creditcard_sale->amount;
									 ?>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td>Total</td>
										<td><?php echo $saquality_total;?></td>
										<td><?php echo $saamount_total	;?></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="fuel_othersales" >
					<h4>Fuel Other Sales</h4>
					<div class="row" >
						<div class="col-md-12">
							<table border="5" class="table table_bordered" style="margin-top: 30px" >
								<thead>
									<tr>
										<th >Invoice Number</th>
										<th>Pump Name</th>
										<th>Product Name</th>
										<th>Vechicle</th>
										<th>Customer</th>
										<th>Sales Quality</th>
										<th>Sales Amount</th>
										<th>At The Rate</th>
									</tr>
								</thead>
								<tbody >
										<?php 
										$otherquality_total=0;$otheramount_total=0;
										?>
										@foreach($others_sale as $othersale)
									<tr>
										<td style="text-align: left">
											{{$othersale->invoice_number}}					
										</td>
										<td>{{$othersale->pump_name}}</td>
										<td>{{$othersale->item_name}}</td>
										<td>{{$othersale->registration_no}}</td>
										<td>{{$othersale->employee_name}}</td>
										<td>{{$othersale->quantity}}</td>
										<td>{{$othersale->amount}}</td>
										<td>{{$othersale->rate}}</td>	
										<?php 
										$otherquality_total=$otherquality_total+$othersale->quantity;
										$otheramount_total=$otheramount_total+$othersale->amount;
										 ?>
									</tr>
										@endforeach
									<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Total</td>
											<td><?php echo $otherquality_total;?></td>
											<td><?php echo $otheramount_total	;?></td>
											<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="randominvoice" >
					<h4>Random Invoice</h4>
					<div class="row" >
						<div class="col-md-12">
							<div style="width:800px;height:300px;border:1px solid #000; margin-top: 30px"><br><br><br><br>
								<div class="row" style="padding-left: 10px">
								<div class="col-md-4" >
								<div class="form-group">
									{!! Form::label('fuel_sale', ' FuelSale', array('class' => 'control-label  required')) !!}

									{!! Form::text('fuel_sale',null,array('class' => 'form-control','id' => 'fuel_sale')) !!}
								</div>
								</div>
								</div>
								<div class="row" style="padding-left: 10px">
								<div class="col-md-2" >
								<div class="form-group">
									{!! Form::label('break_up', ' BreakUp', array('class' => 'control-label  required')) !!}

									{!! Form::text('break_up',null,array('class' => 'form-control','id' => 'break_up')) !!}
								</div>
								</div>
								<div class="col-md-2" >
								<div class="form-group">
									{!! Form::label('break_up1', ' BreakUp1', array('class' => 'control-label  required')) !!}

									{!! Form::text('break_up1',null,array('class' => 'form-control','id' => 'break_up1')) !!}
								</div>
								</div>
								<div class="col-md-2" >
								<div class="form-group">
									{!! Form::label('break_up2', ' BreakUp2', array('class' => 'control-label  required')) !!}

									{!! Form::text('break_up2',null,array('class' => 'form-control','id' => 'break_up2')) !!}
								</div>
								</div>
								<div class="col-md-2" >
								<div class="form-group">
									{!! Form::label('break_up3', ' BreakUp3', array('class' => 'control-label  required')) !!}

									{!! Form::text('break_up3',null,array('class' => 'form-control','id' => 'break_up3')) !!}
								</div>
								</div>
								<div class="col-md-2" >
								<div class="form-group">
									{!! Form::label('break_up4', ' BreakUp4', array('class' => 'control-label  required')) !!}

									{!! Form::text('break_up4',null,array('class' => 'form-control','id' => 'break_up4')) !!}
								</div>
								</div>
								<div class="col-md-2" >
								<div class="form-group">
									{!! Form::label('break_up5', ' BreakUp5', array('class' => 'control-label  required')) !!}

									{!! Form::text('break_up5',null,array('class' => 'form-control','id' => 'break_up5')) !!}
								</div>
								</div>&nbsp;&nbsp;&nbsp;
								<a class="btn btn-danger col-md-2 float-right add" style="color: #fff;height: 30px;margin-top: 12px;padding-left: 20px" onclick="submit1()">Generate</a>
			  						
							    
							</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    <div class="row"style="padding-left: 20px" >
							    	{!! Form::label('invoice', ' InvoiceNumber:', array('class' => 'control-label  required')) !!}
							    	<div class="screen" id="screen1"></div>

							    </div>
							</div>							
						</div>
					</div>
				</div>
			</div>




		{{--

			@stop

		@section('dom_links')
		@parent 				
		 

		--}}

		<script>
	 	$('.datepicker').datepicker({  

	       format: 'dd.mm.yyyy'

	     }); 

			var sum_value;


			function submit1(){
                 var a = document.getElementById("fuel_sale").value;
                
             	 var b = document.getElementById("break_up").value;


                var c = parseInt(a)/parseInt(b);
                var invoice_no=[];
                for(i=1;i<=c;i++){
				invoice_no.push("inc00"+i);

                }
                var invoice=invoice_no.join(",")
               
               	      document.getElementById("screen1").innerHTML=invoice;
             		
             	}

             	$(function() 
             	{
		   			 $(".open_meter, .close_meter,.testing,.sale_quantity,.sale_cash").on("keydown keyup",calculate);
		   			 function calculate()
		   			 {

		   			 	var tr=$(this).parent().parent();

		   			 	var systemqty_total=0;
		   			 	var systemcash_total=0;
		   			 	var manualqty_total=0;
		   			 	var manualcash_total=0;
		   			 	var open_meter=$(tr).find('.openmeter_td').children('.open_meter').val();
						var close_meter=$(tr).find('.closemeter_td').children('.close_meter').val();
						var testing=$(tr).find('.testing_td').children('.testing').val();
						var rate=$(tr).find('.rate_td').text();

						var total_qty= close_meter - open_meter -testing;	
						var total_cash=total_qty * rate;							
						if(total_qty<0||total_qty==0){
							total_qty="";
							total_cash="";
						}
			  				
						$(tr).find('.quantity_td').children('.quantity').val(total_qty);
						$(tr).find('.salesbycash_td').children('.salesbycash').val(total_cash);

						var system_quantity=$('.quantity').map(function()
		   			 	 {
							if ($(this).val())
							{
								
								 systemqty_total=parseInt(systemqty_total)+parseInt($(this).val());
							}
						});
						var system_cash=$('.salesbycash').map(function()
		   			 	 {
							if ($(this).val())
							{
								
								 systemcash_total=parseInt(systemcash_total)+parseInt($(this).val());
							}
						});

						var manual_quantity=$('.sale_quantity').map(function()
		   			 	 {
		   			 	 	
							if ($(this).val())
							{
								
								manualqty_total=parseInt(manualqty_total)+parseInt($(this).val());
							}
						});
						var manual_cash=$('.sale_cash').map(function()
		   			 	 {
		   			 	 	
							if ($(this).val())
							{
								
								manualcash_total=parseInt(manualcash_total)+parseInt($(this).val());
							}
						});


							

						
					$('.totalquantity').val(systemqty_total);
					$('.totalcash').val(systemcash_total);
		   			$('.salequantity_total').val(manualqty_total);	
		   			$('.salecash_total').val(manualcash_total);	
					}
			
				});
             	

             	$(function() {
   			 $(".sale_cash").on("keydown keyup",check_amount);
   			 function check_amount(){

   			 		var tr=$(this).parent().parent();
			
					var saleby_cash=$(tr).find('.salesbycash_td').children('.salesbycash').val();
				
					var sale_cash=$(tr).find('.sale_cash_td').children('.sale_cash').val();

				

					var result= sale_cash-saleby_cash ;

				

					if(result < 0){
						
						
						$(tr).find('.result_td').children('.more_less').val(result);

					}
					else if(result > 0){
					
					
						$(tr).find('.result_td').children('.more_less').val(result);

					}
					else{
					
					
						$(tr).find('.result_td').children('.more_less').val(result);

					}
					$(function() 
             	{
	   			 $(".sale_cash").on("keydown keyup",check_amount);
	   			 function check_amount()
	   			 {

   			 		var tr=$(this).parent().parent();
			
					var saleby_cash=$(tr).find('.salesbycash_td').children('.salesbycash').val();
				
					var sale_cash=$(tr).find('.sale_cash_td').children('.sale_cash').val();

					var descrepancy_total=0;

					var result= sale_cash-saleby_cash ;

				

					if(result < 0){
						
						
						$(tr).find('.result_td').children('.more_less').val(result);

					}
					else if(result > 0){
					
					
						$(tr).find('.result_td').children('.more_less').val(result);

					}
					else{
					
					
						$(tr).find('.result_td').children('.more_less').val(result);

					}

					var descrepancy=$('.more_less').map(function()
		   			 	 {
		   			 	 	console.log($(this).val());
		   			 	 	
							if ($(this).val())
							{
								
								descrepancy_total=parseInt(descrepancy_total)+parseInt($(this).val());
							}
						});
					$('.total_descrepancy').val(descrepancy_total);
				}
			
			});
			}
			
			});


		$(function() {
   			 $("#fuelsales, #othersales,#otherreceipt,#expenses").on("keydown keyup", sum);
				function sum() {
			$("#total").val(Number($("#fuelsales").val()) + Number($("#othersales").val())+Number($("#otherreceipt").val())-Number($("#expenses").val()));
	
			}
			});
    
	
		
$(document).ready(function() {

	basic_functions();	
			
			$('.endshift').validate({
				errorElement: 'span', //default input error message container
				errorClass: 'help-block', // default input error message class
				focusInvalid: false, // do not focus the last invalid input
				rules: {

					
					open_meter:{ required: true },
					
					
					},
			
		messages: {
					//name: { required: "Unit Name is required." },
					open_meter: { required: " open_meter is required."},
					
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

					var cash_detail_id= [];
					$('input:hidden[name="cash_detail_id[]"]').each(function()
					{
						cash_detail_id.push($(this).val());
					});

					var fsm_id= [];
					$('input:hidden[name="fsm_id[]"]').each(function()
					{
						fsm_id.push($(this).val());
					});

					var productid= [];
					$('input:hidden[name="productid[]"]').each(function()
					{
						productid.push($(this).val());
					});
					

					var at_rate= [];
					$('input:hidden[name="at_rate[]"]').each(function()
					{
						at_rate.push($(this).val());
					});


						var open_meter= [];

					$('input:text[name="open_meter[]"]').each(function()
					{
						open_meter.push($(this).val());
					});

					var close_meter= [];
					$('input:text[name="close_meter[]"]').each(function()
					{
						close_meter.push($(this).val());
					});

					var testing= [];
					$('input:text[name="testing[]"]').each(function()
					{
						testing.push($(this).val());
					});

					var quantity= [];
					$('input:text[name="quantity[]"]').each(function()
					{
						quantity.push($(this).val());
					});

					var salesby_cash= [];
					$('input:text[name="salesby_cash[]"]').each(function()
					{
						salesby_cash.push($(this).val());
					});
					var attendant=[];
					$('input:text[name="attendant[]"]').each(function(){
						attendant.push($(this).val());
					});
					var sale_quantity=[];
					$('input:text[name="sale_quantity[]"]').each(function(){
						sale_quantity.push($(this).val());
					});
					var sale_cash=[];
					$('input:text[name="sale_cash[]"]').each(function(){
						sale_cash.push($(this).val());
					});

					var more_less= [];
					$('input:text[name="more_less[]"]').each(function()
					{
						more_less.push($(this).val());
					});

					var notes= [];
					$('input:text[name="notes[]"]').each(function()
					{
						notes.push($(this).val());
					});

					var manage_id= [];
					$('input:hidden[name="manage_id[]"]').each(function()
					{
						manage_id.push($(this).val());
					});


					$.ajax({
					url: '{{ route('shiftendpump_update') }}',
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',

						fsm_id:fsm_id,
						shiftid: $('input[name=shift_id]').val(),
						cash_detail_id: cash_detail_id,
						productid: productid,
						employee: $('select[name=employee]').val(),
						At_rate: at_rate,
						openmeter:open_meter,
						close_meter: close_meter,
						testing: testing,
						quantity: quantity,
						salesby_cash: salesby_cash,
						attendant:attendant,
						sale_quantity:sale_quantity,
						sale_cash:sale_cash,
						more_less: more_less,
						notes: notes,
						end_at: $('input[name=end_at]').val(),
						fuelsales: $('input[name=fuelsales]').val(),
						othersales: $('input[name=othersales]').val(),	
						otherreceipt: $('input[name=otherreceipt]').val(),
						expenses: $('input[name=expenses]').val(),
						total: $('input[name=total]').val(),
						fuelcashsale: $('input[name=fuel_cashsale]').val(),	
						fuelcreditcard_sale:$('input[name=fuelcreditcard_sale]').val(),
						fuelcredit_sale:$('input[name=fuelcredit_sale]').val(),	
						otherscash_report :$('input[name=otherscash_report]').val(),
						otherscredit_report :$('input[name=otherscredit_report]').val(),
						manage_id:manage_id,


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
			

});


		</script>