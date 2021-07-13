<div>

	@if($status == 0)

		@if($name == "job_request")
			<label>ID : No Transactions found.
				<a href="#" class="job_make_transaction approval_status  jobcard-estimation" data-name="job_request" data-ref ="jobcard-estimation" id="invoice_credit" data-id="{{ $id }}" >Click here to Create</a></label>
		@elseif($name == "job_invoice")
			<label>ID : No Transactions found. 
				<a href="#" class="job_make_transaction approval_status  hover jobcard-invoice_credit" data-ref ="jobcard-invoice_credit" data-name="job_invoice" id="invoice_credit" data-id="{{ $id }}" >Click here to Create</a></label>
		@elseif($name == "delivery_note")
			<label>ID : No Transactions found.
				<a href="#" class="make_transaction approval_status" data-name="delivery_note" id="{{ $id }}" data-id="{{ $id }}" >Click here to Create</a></label>
		@elseif($name =="goods_receipt_note")

			<label>ID : No Transactions found.
				<a href="#" class="make_transaction approval_status po_to_grn" data-name="goods_receipt_note" data-ref="po_to_grn" id="{{ $id }}" data-id="{{ $id }}" >Click here to Create</a></label>
		@else
			<label>ID : No Transactions found.></label>
		@endif
	@else
		@foreach($view_estimation as $view_estimations)
			<a  style="color:#17a2b8;" class="views_show" data-id="{{ $view_estimations->id }}">ID : {{ $view_estimations->order_no }}</a><br>
		@endforeach
	@endif



</div>


<script type="text/javascript">
	$(document).ready(function(){
		
	
	// to show edit in view invoice,estimation,grn,dn
	$('.views_show').on('click',function () {
		
			$('#centralModalSm').modal('hide');

			isFirstIteration = true;
			var id = $(this).data('id');
	        var vehicle_id = $(this).data('vehicle_id');       

			if(id != "" && typeof(id) != "undefined") {

				$('.loader_wall_onspot').show();
					$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

						$.get("{{ url('transaction') }}/"+id+"/edit", function(data) {

						  $('.full_modal_content').show();
						  $('.full_modal_content').html("");
						  $('.full_modal_content').html(data);
						  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
						  $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
						  $('.loader_wall_onspot').hide();
						  
						});
			
					});
				}

			});
	});




</script>