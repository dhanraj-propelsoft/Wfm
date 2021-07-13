console.log('Naveen Common js file');

//to create estimation
function create_estimation(data) {
	//console.log("id" + data);
	var url = jobcard_create_estimate_route;
	url = url.replace(':id', data);
	//console.log("url" + url);
	window.location.href = url;


}

//to view estimated
function view_estimation(data) {
	//console.log("id" + data);
	var url = jobcard_view_estimate_route;
	url = url.replace(':id', data);
	//console.log("url" + url);
	window.location.href = url;


}

//to create invoice
function create_invoice(data,key) 
{
	//console.log("id" + data);
	console.log("key"+key);

	var url = jobcard_create_invoice_route;
	console.log("url" + url);
	url = url.replace(':id', data);
	if(key == "create_inv_key1")
	{
		console.log("create_inv_key1");
		url = url.replace(':type', 'job_invoice_cash');

	}
	else if(key == "create_inv_key2")
	{
		console.log("create_inv_key2");
		url = url.replace(':type', "job_invoice");

	}
	console.log("url" + url);
	window.location.href = url;

}

//to view invoiced
function view_invoice(data,type) {
	
	var url = jobcard_view_invoice_route;
	url = url.replace(':id', data);
	url = url.replace(':type', type);

	console.log("url" + url);
	window.location.href = url;

}

//to delete jobcard
function jobcard_delete(id)
{
	var url = jobcard_delete_route;
	url = url.replace(':id', id);
	console.log("url" + url);
	$.ajax({
		url: url,
		type: 'post',
		data: 
		{
			_method : 'delete',
			_token: csrf_token,
		
		},
		success:function(data)
		{
			console.log(data);
			var url = jobcard_index_route;
			window.location.href = url;
			
			
		}
	});
}


//jobcard advance payment

function jobcard_advance_payment(id)
{
	
	console.log("jobcard_advance_payment");
	$.ajax({
		url: jobcard_advance_route,
		type: 'post',
		data: 
		{
			_token: csrf_token,
			id :id,
			
		},
		success:function(data)
		{
			console.log(data);
			var payment = data.payment;
			var ledgers = data.ledgers;
			//$('select[name=job_card]').html(`<option value=`+data.selected_job_card.id+`>`+data.selected_job_card.order_no+`</option>`);
			$('input[name=job_card]').val(data.selected_job_card.order_no);
			$('input[name=job_card]').attr('data-id',data.selected_job_card.id);
			$('.people').hide();
			if(data.name.user_type == 0)
			{
				$('.people').show();
				$('.business').hide();
				$('.people').find('select').prop('disabled', false);
				$('.business').find('select').prop('disabled', true);
				$('#people_type').prop('checked',true);
				// $('select[name=people_id]').html("<option value='"+data.name.person_id+"'>"+data.name.display_name+"</option>")
				// $('select[name=people_id]').val(data.name.person_id);
				$('input[name=people_id]').val(data.name.display_name);
				$('input[name=people_id]').attr('data-id',data.name.person_id);

			}
			else if(data.name.user_type == 1)
			{
				$('.business').show();
				$('.people').hide();
				$('.business').find('select').prop('disabled', false);
				$('.people').find('select').prop('disabled', true);
				$('#business_type').prop('checked',true);
				// $('select[name=people_id]').html("<option value='"+data.name.business_id+"'>"+data.name.display_name+"</option>")
				// $('select[name=people_id]').val(data.name.business_id);
				$('input[name=people_id]').val(data.name.display_name);
				$('input[name=people_id]').attr('data-id',data.name.business_id);

			}
				$('.invoice_modal').find('select[name=invoice_payment_method]').html('');
				$('.invoice_modal').find('select[name=invoice_payment_ledger]').html('');
				for (var i in payment) 
				{
					$('.invoice_modal').find('select[name=invoice_payment_method]').append("<option value='"+payment[i].id+"'>"+payment[i].display_name+"</option>");
				}
				for (var i in ledgers) 
				{
					$('.invoice_modal').find('select[name=invoice_payment_ledger]').append("<option value='"+ledgers[i].id+"'>"+ledgers[i].name+"</option>");
				}

				$('.invoice_modal').modal('show');
			
		
		}
	});
}

// jobcard acknowledgement
function jobcard_acknowledgement(id)
{
	$.ajax({
		url: jobcard_ack_sms_route + "/" + id,
		type: 'get',
		dataType: "json",
		beforeSend: function() {

		},
		success: function(data, textStatus, jqXHR) {
			console.log(data);
			if(data.message && data.message == 'SUCCESS'){
				showAlertMsg(data.data,"success");
			}
			/* Loading Indicator */
			new imageLoader(cImageSrc, 'stopAnimation()');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			/* Loading Indicator */
			new imageLoader(cImageSrc, 'stopAnimation()');
			showAlertMsg("SMS request failed to register. Please contact customer care.","error");
		}
	});
}
