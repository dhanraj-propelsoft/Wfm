@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.inventory')
@section('content')

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>



@if($errors->any())
	<div class="alert alert-danger">
		@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
		@endforeach
	</div>
@endif

<div class="fill header">
	<h4 class="float-left page-title">Transaction</h4>
	<!-- @permission('adjustment-create')
	<a class="btn btn-success float-right add" style="color: #fff"> Add to {{$type}}</a>
	
	@if($transaction_type == "sales") <a style="margin-right: 10px; color: #fff; " class="btn btn-info float-right add_expense" > Add as Expense</a> @endif
	@endpermission -->
</div>
<br><br><br>

		<div id="transaction_print" style="width: 100%; float: left;"></div>
					
@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
   <script type="text/javascript">
   var datatable = null;

	function call_back(data, modal, message, id = null) {
		datatable.destroy();
		if($('.edit[data-id="' + id + '"]')) {
			$('.edit[data-id="' + id + '"]').closest('tr').remove();
		}
		$('.data_table tbody').prepend(data);
		datatable = $('#datatable').DataTable();
		$('.crud_modal').modal('hide');

		alert_message(message, "success");
		
	}

	$(document).ready(function() {


	datatable = $('#datatable').DataTable();

				$.ajax({
					url: "{{ route('print_transaction') }}"+"/"+{{$id}},
					type: 'post',
					data: {
						_token : '{{ csrf_token() }}',
						id: {{$id}}
					},
					success:function(data, textStatus, jqXHR) {

						$('.print_content').show();
						$('.print_content').find('.modal-footer').show();

						var container = $('.print_content').find("#print");
						container.html("");

						if(container.html(data.transaction_data)) {

							$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

							container.find("[data-value='voucher_type']").text(data.transaction_type);
							container.find("[data-value='po']").text(data.po_no);
							container.find("[data-value='purchase']").text(data.purchase_no);
							container.find("[data-value='grn']").text(data.grn_no);
							container.find("[data-value='date']").text(data.date);
							container.find("[data-value='payment_mode']").text(data.payment_mode);
							container.find("[data-value='resource_person']").text(data.resource_person);
							container.find("[data-value='shipping_address']").text(data.shipping_address);
							container.find("[data-value='billing_address']").text(data.billing_address);

							var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

							var row = container.find('.item_table tbody tr').clone();

							var items = ``;

							for (var i = 0; i < (data.items).length; i++) {
								var j = i + 1;
								var new_row = row.clone();

								new_row.find('.col_id').text(j);
								new_row.find('.col_desc').text(data.items[i].name);
								new_row.find('.col_hsn').text(data.items[i].hsn);
								new_row.find('.col_gst').text(data.items[i].gst);
								new_row.find('.col_discount').text(data.items[i].discount);
								new_row.find('.col_quantity').text(data.items[i].quantity);
								new_row.find('.col_rate').text(data.items[i].rate);
								new_row.find('.col_amount').text(data.items[i].amount);

								items += `<tr>`+new_row.html()+`</tr>`;
							}

							container.find('.item_table tbody').empty();

							container.find('.item_table tbody').append(items);

							container.find('.total_table .sub_total').text(data.sub_total);
							container.find('.total_table .total').text(data.total);

							var discount_row = container.find('.total_table .discounts').clone();
							var tax_row = container.find('.total_table .taxes').clone();

							var total = ``;

							for (var i = 0; i < (data.discounts).length; i++) {

								var new_row = discount_row.clone();

								new_row.find('.discount_name').text(data.discounts[i].key);
								new_row.find('.discount_value').text(data.discounts[i].value);

								total += `<tr>`+new_row.html()+`</tr>`;
							}

							for (var i = 0; i < (data.discounts).length; i++) {

								var new_row = discount_row.clone();

								new_row.find('.discount_name').text(data.discounts[i].key);
								new_row.find('.discount_value').text(data.discounts[i].value);

								total += `<tr>`+new_row.html()+`</tr>`;
							}

							for (var i = 0; i < (data.taxes).length; i++) {

								var new_row = tax_row.clone();

								new_row.find('.tax_name').text(data.taxes[i].key);
								new_row.find('.tax_value').text(data.taxes[i].value);

								total += `<tr>`+new_row.html()+`</tr>`;
							}
							container.find('.total_table .discounts, .total_table .taxes').remove();
							container.find(".total_table tr").first().after(total);

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

	

	$('.add').on('click', function(e) {
		e.preventDefault();

		$.ajax({
			url: "{{ route('add_to_account') }}",
			type: 'post',
			data: {
				_token : '{{ csrf_token() }}',
				id: {{$id}}
			},
			success:function(data, textStatus, jqXHR) {
				window.location.replace(data.data.url);
			}
		});

	});

	$('.add_expense').on('click', function(e) {
		e.preventDefault();

		$.ajax({
			url: "{{ route('add_to_expense') }}",
			type: 'post',
			data: {
				_token : '{{ csrf_token() }}',
				id: {{$id}}
			},
			success:function(data, textStatus, jqXHR) {
				window.location.replace('{{ route('notifications') }}');
			}
		});

	});

	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('inventory/adjustment') }}/"+$(this).data('id')+"/edit", function(data) {
			$('.crud_modal .modal-container').html("");
			$('.crud_modal .modal-container').html(data);
		});

		$('.crud_modal').modal('show');
	});


	});
	</script>
@stop