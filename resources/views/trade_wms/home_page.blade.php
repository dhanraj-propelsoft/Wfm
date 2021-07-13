@extends('layouts.master')
@section('head_links') @parent
<style>
.jc_view
{
	padding-top: 15px;
}
.card{
	border:1px solid rgb(255, 140, 0) !important;
	max-height: 254px;
    max-width: 184px;
    float: left;
    margin-right:10px;
    margin-top:10px;
    height: 254px;
    width: 184px;
    border-radius: 1.25rem;
}
.card-body{
	padding: 0rem;
}
.card-header {
    border-bottom: 1px solid rgb(255, 140, 0); 
   	border-top-left-radius: 18px !important;
    border-top-right-radius: 18px !important;
    background-color: #FF8C00;
}
.card-footer {
    padding: .25rem 1.25rem;
    border-top: 1px solid rgb(255, 140, 0);
    border-bottom-left-radius: 18px !important;
    border-bottom-right-radius: 18px !important;
    background-color: #FF8C00;
}
.pointer {cursor: pointer;}
.card:hover{
	box-shadow: 0 0 10px 5px gray;
}
.text-center {
    font-weight: bold;
}
.jc_options_popover{
    min-width: 172px;
    width:auto;
    background:#fff;
    position:absolute;
    z-index:99;
    top:-7px;
    box-shadow:0 0 10px #212529;
    border:1px solid #ccc;
    border-radius:3px;
    padding:5px
}
.job_status_popover{
    min-width: 204px;
    width:auto;
    background:#fff;
    position:absolute;
    z-index:99;
    top:-7px;
    box-shadow:0 0 5px #ddd;
    border:1px solid #ccc;
    border-radius:3px;
    padding:5px,

}
.jc_options_popover::before{
    position:absolute;
    z-index:-1;
    content:' ';
    padding:5px;
    left: -6px;
    top: 13px;
    background:	#FAEBD7;
    transform: rotate(310deg);
    box-shadow:0 0 5px #ddd;
    border-color:#ccc;
    border-style:solid;
    border-width:5px 0 0 1px
}
.job_status_popover::before{
    position:absolute;
    z-index:-1;
    content:' ';
    padding:5px;
    left: -6px;
    top: 13px;
    background: #FAF0E6;
    transform: rotate(310deg);
    box-shadow:0 0 5px #ddd;
    border-color:#ccc;
    border-style:solid;
    border-width:5px 0 0 1px
}

  





</style>
@stop
@include('includes.trade_wms')
@section('content')
@include('includes.add_user')
@include('includes.add_business')
<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
<div class="alert alert-danger"></div>
@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

<div class="fill header" style="height:40px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
</div>	
<div>
	<ul class="jc_view">
		<li class="new_jc">
		<div class="card text-center">
  			<div class="card-header">
   				Add New Job Card
  			</div>
  			<div class="card-body">
    			<img src={{url('public/car.png')}} height=150px alt=""/>
  			</div>
  			<div class="card-footer text-muted">
   				<span class="addnew_jobcard pointer" style="color: #3366ff;">Click here to Add New Job Card</span>
  			</div>
		</div>
	</li>
	@foreach($transactions as $transaction)
	<li class="existing_jc">
     	<div class="card">
  			<div class="card-header text-center" >
 				{{ $transaction->order_no }}
  			</div>
  			<div class="card-body all_jcs" style="background-color: #C0C0C0;color:#fff;">
  				<div class="lists">
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Vehicle<span style="margin-left: 27px;margin-right: 1px;">:</span></b><span class="col-vehicle" data-id="{{ $transaction->id }}" data-status = "{{$transaction->jobcard_status_id}}">{{ $transaction->registration_no }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Customer<span style="margin-left: 10px;margin-right: 1px;">:</span></b><span class="col-customer">{{ $transaction->customer }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Mechanic<span style="margin-left: 11px;margin-right: 3px;">:</span></b><span class="col-mechanic"></span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Total Amt<span style="margin-left: 11px;margin-right: 1px;">:</span></b><span class="col-amount">{{ $transaction->jobcard_total }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Advance<span style="margin-left: 15px;margin-right: 1px;">:</span></b><span class="col-advance">{{ $transaction->advance_amount }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Due<span style="margin-left: 45px;margin-right: 1px;">:</span></b><span class="col-due">{{ $transaction->balance }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">From Date<span style="margin-left: 5px;margin-right: 1px;">:</span></b><span class="col-from_date">{{ $transaction->job_date }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">To Date<span style="margin-left: 22px;margin-right: 1px;">:</span></b><span class="col-to
    					_date">{{ $transaction->job_due_date }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Estimation<span style="margin-left: 3px;margin-right: 1px;">:</span></b><span class="col-estimation" data-id="{{ $transaction->estimation_id }}">{{ $transaction->estimation }}</span></p>
    				<p style="margin:0;padding:0;"><b style="margin-left: 3px;">Invoice<span style="margin-left: 25px;margin-right: 1px;">:</span></b><span class="col-invoice"></span></p>
    			</div>
    			<div class="options">
    			</div>
  			</div>
  			<div class="card-footer text-center" >
   				<b>{{ $transaction->jobcard_status }}</b>
  			</div>
		</div>
	</li>
	@endforeach	
	</ul>
</div>
@include('modals.invoice_modal')
@stop

@section('dom_links')
@parent

<script type="text/javascript">

	var isFirstIteration = true;

$(document).ready(function() {

	$('.addnew_jobcard').on('click', function(e) {
			e.preventDefault(); 
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.full_modal_content').attr("data-id",0)
			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
					$.get("{{ route('transaction.create', ['job_card']) }}", function(data) {
					  $('.full_modal_content').show();
					  $('.full_modal_content').html("");
					  $('.full_modal_content').html(data);
					  $('.full_modal_content').find('.tab_save_close_btn').addClass('jc_store');
					  $('.full_modal_content').find('.tab_save_btn').addClass('jc_store_btn');
					  $('.full_modal_content').find('.tab_print_btn').hide();
					  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					  $('.loader_wall_onspot').hide();
					});
			});			
	});

	
	$( ".lists" ).mousedown(function() {

		var obj = $(this);
		var container = $(this).next('.options');
		
		if((container.find('#jc_options_popover')).length > 0) {
			$(this).next('.options').find('#jc_options_popover').remove();
		}else{

			var transaction_id = obj.find('.col-vehicle').attr('data-id');
			var estimation_id = obj.find('.col-estimation').attr('data-id');
			var status_id = obj.find('.col-vehicle').attr('data-status');

			$(this).next('.options').append(`<div style="" class="row" id="job_card_options_modal">
							<div style="position: absolute; background:	#FAEBD7;left: 142px;top: 44px;" class="row  jc_options_popover" id="jc_options_popover">
							<div style= " padding-left: 13px;">
							<i class="fa fa-times-circle close" style="font-size: 20px;  
							  padding-left: 143px;"></i>
							   <b class="text-center"style="color:black; border-bottom: 1px dashed  red;">Click To Proceed</b>
							   </div>
								<div class="col-md-12">
									<ul class="list-group" style="padding-top:5px;">
  										<li><a style="color: #3366ff;" class="open_job_card" data-id=`+transaction_id+`>Open Job Card</a></li>
  										<li><a style="color: #3366ff;" class="open_estimation" data-id=`+estimation_id+`>Open Estimation</a></li>
  										<li style="color:	#808080;">Open Invoice</li>
  										<li><a style="color: #3366ff;" class="create_cash_invoice" data-id=`+transaction_id+` data-ref ="jobcard-invoice_cash" id="invoice_cash" data-name="job_invoice_cash" data-status=`+status_id+`>Create Cash Invoice</a></li>
  										<li><a style="color: #3366ff;" class="create_credit_invoice" data-ref ="jobcard-invoice_credit" id="invoice_credit" data-id=`+transaction_id+` data-name="job_invoice" data-status=`+status_id+`>Create Credit Invoice</a></li>
  										<li><a style="color: #3366ff;" class="create_estimation" data-ref ="jobcard-estimation" id="" data-id=`+transaction_id+` data-name="job_request">Create Estimation</a></li>
  										<li><a style="color: #3366ff;" class="change_job_status" data-id=`+transaction_id+`>Change Job Status</a>
  										<div class="jc_status"></div></li>
  										<li><a style="color: #3366ff;" data-id=`+transaction_id+` class="pay_advance" data-name="jc_payment">Pay Advance</a></li>
  										<li style="color:	#808080;">Move To Archive</li>
  										<li><a style="color: #3366ff;" data-id=`+transaction_id+` class="delete">Delete</a></li>
  										<li style="color:	#808080;">Pay For Invoice</li>
									</ul>
								</div>
							</div>
						</div>`);


			$(this).next('.options').find('.close').on('click',function(){
				$(this).closest('#jc_options_popover').remove();
				 //$('#jc_options_popover').remove();
			});

			$(this).next('.options').find('.open_job_card, .open_estimation').on('click',function(){

			  $('#jc_options_popover').remove();
				isFirstIteration = true;
				var id = $(this).attr('data-id');    

				if(id != "" && typeof(id) != "undefined") {

					$('.loader_wall_onspot').show();
					$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

						$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {
					  	$('.full_modal_content').show();
					  	$('.full_modal_content').html("");
					  	$('.full_modal_content').html(data);
					  	$('.full_modal_content').find('.tab_save_close_btn').addClass('jc_store');
					  	$('.full_modal_content').find('.tab_save_btn').addClass('jc_store_btn');
					  	$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
					  	$('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
					  	$('.loader_wall_onspot').hide();
					  
						});
		
					});
				}

			});

			$(this).next('.options').find('.create_cash_invoice , .create_credit_invoice, .create_estimation').on('click',function(){
				var id = $(this).attr('data-id');
				var reference = $(this).attr('data-name');
				var name = $(this).attr('data-ref');
				$.ajax({
				url: '{{ route('find_reference_id') }}',
				type: 'get',
				data: {
			    id: id,
			    reference:reference,
			    },
				success:function(data, textStatus, jqXHR) {

					if(data.data == null){
                        make_transaction(name);                  
					}else{
						$('.crud_modal .modal-container').html('<div class="modal-header"><h4 class="modal-title">Confirmation:</h4></div><div class="modal-body"><h7>'+data.data.display_name+'('+data.data.order_no+') For this Transaction is already Exist..!<br>Click Continue to Create<br> <input type="checkbox" name="vehicle" class="pull-left" style = "display:block;width: 22px;height: 19px;"checked disabled><span class="pull-left">Delete the existing Estimation</span></h7></div><div class="modal-footer"><button type="button" class="btn default" data-dismiss="modal">No</button><button type="button" id='+data.data.id+' data-name='+data.data.order_no+' class="btn btn-success ok_btn" data-dismiss="modal">Continue</button></div>');

   			               $('.crud_modal').modal('show');
   			               $('.ok_btn').on('click',function(e){
                               e.preventDefault();
                               var id = $(this).attr('id');
                               var order_no = $(this).attr('data-name');
                               if($('input[type="checkbox"]'). prop("checked") == true){
                                  $.ajax({
									url: '{{ route('transaction.destroy') }}',
									type: 'post',
									data: 
									{
								    _method: 'delete',
									_token: '{{ csrf_token() }}',
									id :id,
									},
									success:function(data)
									{
									  if(data.status == 1){
										 make_transaction(name);
									 }
									}
							});

                               }else{
                               	//Hided by vishnu
                                    /*$.ajax({
									url:'{{ route('transaction.destroy') }}',
									type: 'post',
									data: {
									_method: 'delete',
									_token : '{{ csrf_token() }}',
									id: id,
									},
									success:function(data)
									{
									  if(data.status == 1){
									  	var gen_no = data.data.gen_no;
									  existing_transaction(name,gen_no);
									 }
									}
							});*/
                               }
                              
   			               });
					}
					
				},
			    error:function(jqXHR, textStatus, errorThrown) {
				}
				});

			});

			$(this).next('.options').find('.pay_advance, .pay_for_invoice').on('click',function(){
				$('#jc_options_popover').remove();
				var id = $(this).attr('data-id');
				var name= $(this).attr('data-name');

				$.ajax({
						url: '{{ route('home_page.pay_advance') }}',
						type: 'post',
						data: 
						{
							_token: '{{ csrf_token() }}',
							id :id,
							type: 'wms_receipt',
							name: name,
						},
						success:function(data)
						{
							//console.log(data);
							var people  = data.people;
							var business = data.business;
							var date = new Date();
							var payment = data.payment;
							var ledgers = data.ledgers;


							$('.person_row').find('select[name=job_card]').html(`<option value=`+data.selected_job_card.id+`>`+data.selected_job_card.order_no+`</option>`);

							$('.people').hide();

							if(data.name.user_type == 0)
							{

								$('.people').show();
								$('.business').hide();
								$('.people').find('select').prop('disabled', false);
								$('.business').find('select').prop('disabled', true);
								$('#people_type').prop('checked',true);
								$('select[name=people_id]').html("<option value='"+data.name.person_id+"'>"+data.name.display_name+"</option>")
								$('input[name=payment_amount]').val(data.name.total);
								$('input[name=invoice_payment_amount]').val(data.name.total);
								$('select[name=people_id]').val(data.name.person_id);
							}
							else if(data.name.user_type == 1)
							{
								$('.business').show();
								$('.people').hide();
								$('.business').find('select').prop('disabled', false);
								$('.people').find('select').prop('disabled', true);
								$('#business_type').prop('checked',true);
								$('select[name=people_id]').html("<option value='"+data.name.business_id+"'>"+data.name.display_name+"</option>")
								$('input[name=payment_amount]').val(data.name.total);
								$('input[name=invoice_payment_amount]').val(data.name.total);
								$('select[name=people_id]').val(data.name.business_id);

							}
							$('.person_row').find('input[name=customer]').on('change', function(){
								if($(this).val() == "people") {
									$('.people').find('select[name=people_id]').html('');
									$('.people').find('select[name=people_id]').append("<option value=''>Select People</option>");
									for (var i in people) {
										$('.people').find('select[name=people_id]').append("<option value='"+people[i].id+"'>"+people[i].name+"</option>");
									}
									$('.people').show();
									$('.business').hide();
									$('.people').find('select').prop('disabled', false);
									$('.business').find('select').prop('disabled', true);
									$('.business').find('select').val('');
								} else if($(this).val() == "business")  {

									$('.business').find('select[name=people_id]').html('');
									$('.business').find('select[name=people_id]').append("<option value=''>Select Business</option>");
									for (var i in business) {
										$('.business').find('select[name=people_id]').append("<option value='"+business[i].id+"'>"+business[i].name+"</option>");
									}
									$('.business').show();
									$('.people').hide();
									$('.business').find('select').prop('disabled', false);
									$('.people').find('select').prop('disabled', true);
									$('.people').find('select').val('');
								}
							});

							$('input[name=invoice_payment_amount]').keyup(function() {
								var payment = parseFloat($(this).val());
								var due_amount = parseFloat($('input[name=invoice_due_amount]').val());
								if( payment > due_amount ) {
									$(this).val(due_amount);
								}
							});
							if(data.type == "jc_payment")
							{
								$('.person_row').show();
								$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').hide();
								$('.invoice_modal').find('select[name=job_card]').closest('.form-group').show();

								$('.invoice_modal').find('input[name=payment_date]').val($.datepicker.formatDate('dd-mm-yy', new Date()));
								$('.invoice_modal').find('select[name=invoice_payment_method]').html('');
								$('.invoice_modal').find('select[name=invoice_payment_ledger]').html('');
								for (var i in payment) {
									$('.invoice_modal').find('select[name=invoice_payment_method]').append("<option value='"+payment[i].id+"'>"+payment[i].display_name+"</option>");
								}
								for (var i in ledgers) {
									$('.invoice_modal').find('select[name=invoice_payment_ledger]').append("<option value='"+ledgers[i].id+"'>"+ledgers[i].name+"</option>");
								}

								$('.invoice_modal').find('.modal-title').text('WMS Receipt: (This is for Advance)');
           						$('.invoice_modal').find('.payment_amount').show();
            					$('.invoice_modal').find('.reduction').css('display','none');
								$('.invoice_modal').find('.tab_print_btn').hide();
								$('.invoice_modal').find('.btn-success').text('Save');
								$('.invoice_modal').find('.btn-default').text('Close');
								$('.invoice_modal').find('.btn-success').on('click',function(){
								$('.invoice_modal').find('.tab_print_btn').show();
								});
								$('.invoice_modal').find('.tab_print_btn').on('click',function(){
                   					var id=$(this).val();
                           
 
                    					print_receipt_transaction(id);

                    
							});
								$('.invoice_modal').modal('show');
		     				}
		     				else if(data.type == "invoice_payment")
		     				{
		     					$('.person_row').hide();
		     					$('.invoice_modal').find('input[name=payment_date]').val($.datepicker.formatDate('dd-mm-yy', new Date()));
		     					$('.invoice_modal').find('select[name=invoice_payment_method]').html('');
								$('.invoice_modal').find('select[name=invoice_payment_ledger]').html('');
		     					for (var i in payment) {
									$('.invoice_modal').find('select[name=invoice_payment_method]').append("<option value='"+payment[i].id+"'>"+payment[i].display_name+"</option>");
								}
								for (var i in ledgers) {
									$('.invoice_modal').find('select[name=invoice_payment_ledger]').append("<option value='"+ledgers[i].id+"'>"+ledgers[i].name+"</option>");
								}
								$('.invoice_modal').find('input[name=invoice_due_amount]').closest('.form-group').show();
								$('.invoice_modal').find('.payment_amount').hide();
								$('.invoice_modal').find('.reduction').css('display','block');
								$('.invoice_modal').find('select[name=job_card]').closest('.form-group').hide();
								//validator.resetForm();
								$('.invoice_modal').find('.modal-title').text("Wms Receipt:");
								$('.invoice_modal').find('input[name=invoice_due_amount]').val();
								$('.invoice_modal').find('input[name=invoice_payment_amount]').val();
            
								//user_type = $(this).data('user_type');
								//people_id = $(this).data('people_id');
								//type = $(this).data('type');
								//reference_id.push($(this).data('id'));
								//order_id.push($(this).data('reference_no'));
								//balance = $(this).data('balance');

			   					$('.invoice_modal').find('.btn-default').text('Close');
			  					$('invoice_modal').find('.tab_print_btn').hide();
			  					$('.invoice_modal').modal('show');
								
		     				}




						
						}
				});
			});

			$(this).next('.options').find('.delete').on('click',function(){
				$('#jc_options_popover').remove();
                var id = $(this).attr('data-id');
                var type = 'job_card';
                $.ajax({
						url: '{{ route('transaction.delete_confirmation') }}',
						type: 'get',
						data: {
			    				id: id,
			    				type: type,
			    		},
						success: function(data, textStatus, jqXHR) {
							//console.log(data);
							if(data.type == 'job_card')
							{
								var type = data.data.display_name;
								if(data.data == 'null'){
									$('.delete_modal_ajax').find('.modal-title').text("Confirmation:");
									$('.delete_modal_ajax').find('.modal-body').text("Deleted can not be retained, Are you sure to delete?");
									$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').show();
									$('.delete_modal_ajax').modal('show');
                    				$('.delete_modal_ajax_btn').off().on('click', function() {
										$.ajax({
											url: "{{ route('transaction.destroy') }}",
											type: 'post',
											data: {
													_method: 'delete',
													_token : '{{ csrf_token() }}',
														 id: id,
											},
											dataType: "json",
											beforeSend: function() {
												$('.loader_wall_onspot').show();
											},
											success:function(data, textStatus, jqXHR) {
												
												if(data.status == 1){
												$('.loader_wall_onspot').hide();
												location.reload();
												$('.delete_modal_ajax').modal('hide');
												$('.alert-success').text("Transaction Deleted Successfully!");
												$('.alert-success').show();

												
												}
											},
											error:function(jqXHR, textStatus, errorThrown) {
											}
										});
									});
								}
								else
								{
									$('.delete_modal_ajax').find('.modal-title').text("Alert");
									$('.delete_modal_ajax').find('.modal-body').html("This can not be deleted, because It is referred in "+type+" <b>"+data.data.order_no+"</b>");
									$('.delete_modal_ajax').find('.modal-footer').find('.btn-danger').hide();
									$('.delete_modal_ajax').modal('show');

								}	
							}
							 		
						},
					error: function(jqXHR, textStatus, errorThrown) {}
				});
			});

			$(this).next('.options').find('.change_job_status').on('click',function(){
				var transaction_id = $(this).attr('data-id');
				$(this).next('.jc_status').append(`<div style="" class="row" id="job_status">
					<div style="position: absolute; background: 	#FAF0E6;left: 142px;top: 44px;" class="row  job_status_popover" id="job_status_popover">
					<div style="    padding-top: 3px;height: 228px;" class="col-md-12">
					<i class="fa fa-times-circle close" style="float: right;font-size: 20px;"></i>
					<div style="    padding-top: 19px;">
					<label for="date" class="required">Change Job Card Status</label>
					<select name='jobcard_status_id' class='form-control' id =`+transaction_id+`>
						<option value="">Select Job Card Status</option>						
						@foreach($job_card_status as $status)					
						<option value="{{ $status->id }}">{{ $status->name }}</option>
						@endforeach					
					</select>
					</div>
					</div>
					</div>
					</div>`);

				//To save job_card_status when changing status 
				$('select[name=jobcard_status_id]').on('change',function(){
					var jobcard_status_id=$(this).val();
					var transaction_id =  $(this).attr('id');
					$.ajax({
							url: '{{ route('save_job_card_status') }}',
							type: 'post',
							data: 
								{
									_token: '{{ csrf_token() }}',
									jobcard_status_id : jobcard_status_id,
									id : transaction_id,
								},
							success:function(data)
							{
								if(data.data == "updated"){
									$('.job_status_popover').remove();
									$('.alert-success').text("Job card Status Changed Successfully..!");
									$('.alert-success').show();
									location.reload();
								}
							},
							error:function()
							{

							}

					});

				});
				//end

				$(this).next('.jc_status').find('.close').on('click',function(){
				 	$(this).closest('#job_status_popover').remove();
				});
			});

		}
	});



	/*copy when no existing jc/ji/jic present*/
	function make_transaction(name) {

		var obj = '';
		if(name == 'jobcard-estimation'){
			obj = $('.create_estimation');
		}else if(name == 'jobcard-invoice_cash'){
         	obj = $('.create_cash_invoice');
		}else if(name == 'jobcard-invoice_credit'){
			obj = $('.create_credit_invoice');
		}

				
		var id = obj.data('id');
		var transaction_name = obj.data('name');
		var transaction_type =  'job_card';
		var transaction_module =  'trade_wms';
                
		if(transaction_type == "job_card"){
			
			var id = obj.data('id');
			var transaction_name = obj.data('name');
			var job_status_id = obj.attr('data-status');
			
			
			if(transaction_name == "job_invoice" || transaction_name == "job_invoice_cash"){

			if(job_status_id == 1 || job_status_id == 2 || job_status_id == 3 || job_status_id == 4 || job_status_id == 5 || job_status_id == 8)
			{						
				alert_message("Copy Invoice is allowed only for jobcard status is Final Inspected or Vehicle Ready","error");
						return false;
			}
			else
			{

				$('<form>', {
				"id": "dynamic_form",
				"method": "POST",
				"html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy"> <input type="text" name="from" value="home-page">',
					    "action": '{{ route("add_to_account") }}'
				}).appendTo(document.body).submit();

				$('#dynamic_form').remove();

			}

			}

			else
			{

				$('<form>', {
				"id": "dynamic_form",
				"method": "POST",
				"html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy"><input type="text" name="from" value="home-page">',
				    "action": '{{ route("add_to_account") }}'
				}).appendTo(document.body).submit();

				$('#dynamic_form').remove();

			}

		}

		if(transaction_type != "job_card"){
			$('<form>', {
			"id": "dynamic_form",
			"method": "POST",
			"html": '<input type="text" name="_token" value="{{ csrf_token() }}"> <input type="text" name="id" value="'+id+'"> <input type="text" name="type" value="'+transaction_name+'"> <input type="text" name="notification_type" value="copy">',
				    "action": '{{ route("add_to_account") }}'
			}).appendTo(document.body).submit();

			$('#dynamic_form').remove();
		}			
    }
	/*end*/

	function print_receipt_transaction(id) {
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('print_receipt_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id
					},
					success:function(data, textStatus, jqXHR) {
                                 // console.log(data.jc_no);
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='receipt_no']").text(data.receipt_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='wording_amount']").text(data.wording_amount);
							container.find("[data-value='received_from']").text(data.received_from);
							container.find("[data-value='mode']").text(data.mode);
							container.find("[data-value='on_date']").text(data.on_date);
							container.find("[data-value='amount']").text(data.amount);
                            container.find("[data-value='jc_no']").text(data.jc_no);

							container.find("[data-value='company_name']").text(data.company_name);
							container.find("[data-value='company_address']").text(data.company_address);
							container.find("[data-value='city']").text(data.city);
							container.find("[data-value='pin']").text(data.pin);
							container.find("[data-value='mobile_no']").text(data.company_phone);
							container.find("[data-value='company_email_id']").text(data.company_email_id);
							container.find("[data-value='customer_name']").text(data.customer_name);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='customer_mobile_no']").text(data.customer_mobile_no);
							container.find("[data-value='customer_email']").text(data.customer_email);
                             


							var divToPrint=document.getElementById('print');
	  						var newWin=window.open('','Propel');


	  						newWin.document.open();
	  						newWin.document.write(`<html>
	  							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	  							<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
	  							<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
								<script> 

								window.onload=function() { window.print(); }

								$(document).ready(function() {
			


									$('body').on('click', '.print', function() {
									//printDiv();
									});



							}); </scr`+`ipt>


							 </body></html>`);

	  						
	  						newWin.document.close();

	  						$('.print_content #print').removeAttr('style');
							$('.print_content #print').html("");
							$('.print_content').removeAttr('style');
							$('.print_content .modal-footer').hide();
							$('.print_content').animate({top: '0px'}); 
							$('body').css('overflow', '');

						}

						$('.loader_wall_onspot').hide();

					}
				});
		
			});
					
	}

	function receipt_transaction(id,entry_id) {
			$('.loader_wall_onspot').show();
			$('body').css('overflow', 'hidden');
			$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

				$.ajax({
					url: "{{ route('receipt_transaction') }}",
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: id,
						entry_id: entry_id,	
					},
					success:function(data, textStatus, jqXHR) {
                                  //console.log(data.items);
						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='receipt_no']").text(data.receipt_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='wording_amount']").text(data.wording_amount);
							container.find("[data-value='received_from']").text(data.received_from);
							container.find("[data-value='mode']").text(data.mode);
							container.find("[data-value='on_date']").text(data.on_date);
							container.find("[data-value='amount']").text(data.amount);
                            container.find("[data-value='jc_no']").text(data.jc_no);

							container.find("[data-value='company_name']").text(data.company_name);
							container.find("[data-value='company_address']").text(data.company_address);
							container.find("[data-value='city']").text(data.city);
							container.find("[data-value='pin']").text(data.pin);
							container.find("[data-value='mobile_no']").text(data.company_phone);
							container.find("[data-value='company_email_id']").text(data.company_email_id);
							container.find("[data-value='customer_name']").text(data.customer_name);
							container.find("[data-value='customer_address']").text(data.customer_address);
							container.find("[data-value='customer_mobile_no']").text(data.customer_mobile_no);
							container.find("[data-value='customer_email']").text(data.customer_email);
                             


							var divToPrint=document.getElementById('print');
	  						var newWin=window.open('','Propel');


	  						newWin.document.open();
	  						newWin.document.write(`<html>
	  							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	  							<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
	  							<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
								<script> 

								window.onload=function() { window.print(); }

								$(document).ready(function() {
			


									$('body').on('click', '.print', function() {
									//printDiv();
									});



							}); </scr`+`ipt>


							 </body></html>`);

	  						
	  						newWin.document.close();

	  						$('.print_content #print').removeAttr('style');
							$('.print_content #print').html("");
							$('.print_content').removeAttr('style');
							$('.print_content .modal-footer').hide();
							$('.print_content').animate({top: '0px'}); 
							$('body').css('overflow', '');

						}

						$('.loader_wall_onspot').hide();

					}
				});
		
			});
				
	}


});	
</script>
@stop