<div class="modal-header">
	<h4 class="modal-title float-right">JobCard Complaints</h4>
	  <div class="alert alert-danger alert-danger_msg"></div>
	  <div class="alert alert-success alert-success_msg"></div>
</div>

{!! Form::open(['class' => 'form-horizontal groupform']) !!}
{{ csrf_field() }}

{{dd('working-1')}}

<div class="modal-body">
	<div class="col-md-12 more_complaints">                    	
		<table id="more_complaints_table" style="border-collapse: collapse;margin-top: 10px;" class="table table-bordered more_complaints_table">
			<thead>
				<tr>
					<th style="background-color:#ccc;text-align: center;width: 100%;">User Complaint</span></th>
					<th style="background-color:#ccc;text-align: center;">Done?</th>
					<th style="background-color:#ccc;text-align: center;"> <a class="grid_label action-btn edit-icon showtext_row"><i class="fa fa-plus"></i></a>
			    	</th>
				</tr>
			</thead>
			<tbody class="more_trow" style="display: none">
				<tr class="more_row">
					<td class="item_td" style="width: 100%;">
						{{ Form::text('more_complaint', null, ['class' => 'form-control more_complaint', 'data-value' => '','id'=>'0','data-id'=>'1']) }}
						<div class="morecomplaint_container"></div>
					</td>
					<td align="center" >
						<input type="checkbox" value="0"  id="more_status" name="more_status" style="display: inline-block;width:25px;height:25px">
						<div class="morestatus_container"></div>
			   		</td>		
					<td>
						<a style="display: none;" class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i>
						</a>
					</td>						
				</tr>
			</tbody>
		</table>
	</div> 
	<div class=" col-md-12">	
		<table id="serviceitem_table" style="border-collapse: collapse;" class="table table-bordered serviceitem_table">
			<thead>
				<tr>
					<th style="background-color:#ccc;text-align: center;width: 100%;">Group Services</span></th>
					<th style="background-color:#ccc;text-align: center;">Done?</th>
					<th style="background-color:#ccc;text-align: center;"> <a class="grid_label action-btn edit-icon showservice_row"><i class="fa fa-plus"></i></a>
			    	</th>
				</tr>
			</thead>
			<tbody class="service_trow" style="display: none">
				<tr class="service_row">
					<td class="item_td" style="width: 100%;">
						 <select name="service_items" class="form-control service_items select_item" id="">
									<option value="">Select Item</option>
										@foreach($service_items as $service_item)
										<option  value="{{$service_item->id}}">{{$service_item->name}}</option>
									@endforeach
									</select>
						<span class="error-name" style=""></span>
						<div class="serviceitem_container"></div>
					</td>
					<td align="center">
						<input type="checkbox" value="0"  id="service_status"name="service_status" style="display: inline-block;width:25px;height:25px">
						<div class="servicestatus_container"></div>
			   		</td>		
					<td>
						<a style="display: none;" class="grid_label action-btn delete-icon remove_rowservice"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i>
						</a>
					</td>						
				</tr>
			</tbody>
		</table>
	</div>

	<div class="col-md-12">
		<table id="Complaints_table" style="border-collapse: collapse;" class="table table-bordered complaintitem_table">
			<thead style="text-align: center;">
				<th style="background-color:#ccc;width: 100%;">Defined Complaints</th>
				<th style="background-color:#ccc;">Done?</th>
				<th style="background-color:#ccc;"> 
					<a class="grid_label action-btn edit-icon showcomplaint_row"><i class="fa fa-plus"></i></a>
			   </th>
			</thead>
			<tbody class="complaints_trow" style="display: none">
				<tr>
					<td class="complaint_item_td" style="width: 100%;">
						{{ Form::select('complaint_items',$complaint_items, null, ['class'=>'form-control complaint_items select_item','id'=>'0']) }}
						<div class="complaintitem_container"></div>
					</td>
					<td align="center">					
						<input type="checkbox" id="complaint_status" name="complaint_status" value="0" style="display: inline-block;width:25px;height:25px">
						<div class="complaintstatus_container"></div>
			   		</td>		
					<td>
						<a style="display: none;" class="grid_label action-btn delete-icon remove_rowcomplaint"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_rowcomplaint"><i class="fa fa-plus"></i>
						</a>
					</td>	
				</tr>
			</tbody>
		</table>
	</div>

	<div class="col-md-12">
		<table id="amc_item_table" style="border-collapse: collapse;" class="table table-bordered amcitem_table">
			<thead style="text-align: center;">
				<th style="background-color:#ccc;width: 100%;">Amc Item</th>
				<th style="background-color:#ccc;">Done?</th>
				<th style="background-color:#ccc;"> 
					<a class="grid_label action-btn edit-icon showamc_row"><i class="fa fa-plus"></i></a>
				</th>
			</thead>
			<tbody  class="amc_trow" style="display: none">
				<tr>
					<td class="amc_item_td" style="width: 100%;">
						{{ Form::select('amc_items',$amc_items, null, ['class'=>'form-control amc_items select_item','id'=>'0']) }}
						<div class="amc_items_container"></div>
				    </td>
				    <td align="center">					
						<input type="checkbox" id="amc_status" value="0" name="amc_status"  style="display: inline-block;width:25px;height:25px">
						<div class="amcstatus_container"></div>
			   		</td>		
					<td>
						<a style="display: none;" class="grid_label action-btn delete-icon remove_rowamc"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i>
						</a>
					</td>	
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="modal-footer" >
	<button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>		
	<!--  <button type="button" class="btn btn-primary add_item">Apply</button>	 --> 
	<button type="submit" class="btn btn-success apply_save" id ="">Apply</button>	
</div>
{!! Form::close() !!}

<script>
$(document).ready(function() {
	 
	basic_functions();


	$('.showservice_row').on('click',function(){
	 	$('.service_trow').show();
	 	$('.showservice_row').hide();

	});

	$('.showtext_row').on('click',function(){
	 	$('.more_trow').show();
	 	$('.showtext_row').hide();

	});

	$('.showcomplaint_row').on('click',function(){
	 	$('.complaints_trow').show();
	 	$('.showcomplaint_row').hide();

	});

	$('.showamc_row').on('click',function(){
	 	$('.amc_trow').show();
	 	$('.showamc_row').hide();

	});

	var complaint_row_index=$('complaintitem_table tbody>tr').length;
	if(complaint_row_index>1){
		$('.complaints_trow').hide();
	}

	var service_row_index = $('.serviceitem_table tbody > tr').length;
	if(service_row_index>1){
		$('.showservice_row').hide();
	}
	
	 var amc_row_index = $('.amcitem_table tbody > tr').length;
	if(amc_row_index>1){
		$('.showamc_row').hide();

	}



	$('body').on('click', '#service_status', function() {
		 
		var obj = $(this);

	    if($(this).prop("checked") == true)
	    {
	      	obj.closest("tr").find("select[name=service_items] option:selected" ).attr('id','1');
	      	obj.closest("tr").find("input[name=service_status]").val(1);
		} 
		else 
		{
			obj.closest("tr").find("select[name=service_items] option:selected" ).attr('id','0');
			obj.closest("tr").find("input[name=service_status]").val(0);
		}
	});

	$('body').on('click', '#more_status', function() {
		 
		var obj = $(this);

	    if($(this).prop("checked") == true)
	    {
	      	obj.closest("tr").find("input[name=more_complaint]" ).attr('id','1');
	      	obj.closest("tr").find("input[name=more_status]").val(1);
		} 
		else 
		{
			obj.closest("tr").find("input[name=more_complaint]" ).attr('id','0');
			obj.closest("tr").find("input[name=more_status]").val(0);
		}
	});

	$('body').on('click', '#complaint_status', function() {
		 
		var obj = $(this);
	    if($(this).prop("checked") == true)
	    {
			obj.closest("tr").find('select[name="complaint_items"] option:selected').attr('id','1');
			obj.closest("tr").find("input[name=complaint_status]").val(1);
		} 
		else 
		{
			obj.closest("tr").find('select[name="complaint_items"] option:selected').attr('id','0');
			obj.closest("tr").find("input[name=complaint_status]").val(0);
		}
	});

	$('body').on('click', '#amc_status', function() {
		var obj = $(this);
	    if($(this).prop("checked") == true)
	    {
	       	obj.closest("tr").find('select[name="amc_items"] option:selected').attr('id','1');
	       	obj.closest("tr").find("input[name=amc_status]").val(1);
		} 
		else 
		{
				obj.closest("tr").find('select[name="amc_items"] option:selected').attr('id','0');
				obj.closest("tr").find("input[name=amc_status]").val(0);
		}
	});
	
	$('body').on('change', '.service_items', function() {

		var row_index =$('.service_trow').find('tr').length;

		var vehicle_id = $('select[name=registration_number]').val();	

			if(vehicle_id == ""){	
				$('select[name=service_items]').val('').select2();	
				$('.alert-danger_msg').text('Please Select Vehicle');
				$('.alert-danger_msg').show();
				setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			}

		if(row_index > 1){

			if ($('.service_items >option[value="' + $(this).val() + '"]:selected').length > 1) {
					$('.alert-danger_msg').text('Already Exist');
					$('.alert-danger_msg').show();
					setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			  		$(this).val('').change();
	        }
	   
	    }
	});

	$('body').on('change', '.complaint_items', function() {
		var row_index =$('.complaints_trow').find('tr').length;

		var vehicle_id = $('select[name=registration_number]').val();	

			if(vehicle_id == ""){	
				$('select[name=service_items]').val('').select2();	
				$('.alert-danger_msg').text('Please Select Vehicle');
				$('.alert-danger_msg').show();
				setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			}
					
		if(row_index > 1){
			
	        
			if ($('.complaint_items >option[value="' + $(this).val() + '"]:selected').length > 1) {
					$('.alert-danger_msg').text('Already Exist');
					$('.alert-danger_msg').show();
					setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			  		$(this).val('').change();
	        	}
	   
	    }
	});

	$('body').on('change', '.amc_items', function() {

		var row_index =$('.amc_trow').find('tr').length;

		var vehicle_id = $('select[name=registration_number]').val();	

			if(vehicle_id == ""){	
				$('select[name=service_items]').val('').select2();	
				$('.alert-danger_msg').text('Please Select Vehicle');
				$('.alert-danger_msg').show();
				setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			}
		
		if(row_index > 1){
			
			if ($('.amc_items >option[value="' + $(this).val() + '"]:selected').length > 1) {
				alert_message("Already Exist", "error");

			
			  		$('.alert-danger_msg').text('Already Exist');
					$('.alert-danger_msg').show();
					setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			  		$(this).val('').change();
	       
	          
	        }
	   
    	}
	});		

	$('body').on('change', '.more_complaint', function() {

		var row_index =$('.more_complaint').find('tr').length;

		var vehicle_id = $('select[name=registration_number]').val();	

			if(vehicle_id == ""){	
				$('input[name=more_complaint]').val(' ');	
				$('.alert-danger_msg').text('Please Select Vehicle');
				$('.alert-danger_msg').show();
				setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			}
		
	});	
	
	$('body').off('click', '.add_rowservice').on('click', '.add_rowservice', function() {

		var obj = $(this);
		
		var item = obj.closest("tr").find('select[name="service_items"]');

		var selected_item = item.find(':selected').val();

		if(item.val() != "")
		{
			$('.select_item').each(function() { 
				var select = $(this);  
					if(select.data('select2')) { 
						select.select2("destroy"); 
					} 
			});

			var clone = $(this).closest('tr').clone();

			
			clone.find('.serviceitem_container, .servicestatus_container').empty();
	
			if(item.length >= 1){

				clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowservice"><i class="fa fa-trash-o"></i></a><a href="javascript:;" class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');
						
				obj.closest('tbody').append(clone);
			}

			obj.parent().html('<a class="grid_label action-btn delete-icon remove_rowservice"><i class="fa fa-trash-o"></i></a>');
			
	

			$('.select_item').select2();

		}

		
	});

	$('body').on('click', '.remove_rowservice', function() {

		var obj = $(this);

		var item = obj.closest("tr").find('select[name="service_items"]');
		

		var remaining_item = obj.closest("tr").find('select[name="service_items"]');
		var last_row_item = obj.closest("table").find('tr').last().find('select[name="service_items"]');

		var selected_item = item.find(':selected').val();

		var selected_item_array = [];

		last_row_item.each(function() {

			selected_item_array.push($(this).val());

		});

		selected_item_array.push(selected_item); 

		obj.closest('tr').nextUntil( 'tr.parent' ).remove();    
		obj.closest('tr').remove();   

		var row_index = $('.serviceitem_table tbody > tr').length;
		 
		/*if(row_index > 1) {
			obj.closest('tr').remove();   

         }*/
		
		for (var i in selected_item_array) {

			$('select[name=service_items]:last').find('span > option[value="' + selected_item_array[i] + '"]').unwrap();
		}

		$('select[name="service_items"]:last > span > option').unwrap();

		if(row_index > 1) {
					$('.serviceitem_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowservice"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');
				} else {
					$('.serviceitem_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');
				}

	});

	var count = 2;

	$('body').off('click', '.add_moreservice').on('click', '.add_moreservice', function() {

		var obj = $(this);
		
		var item = obj.closest("tr").find('input[name="more_complaint"]');

		if(item.val() != "")
		{
			$('.select_item').each(function() { 
				var select = $(this);  
					if(select.data('select2')) { 
						select.select2("destroy"); 
					} 
			});

			var clone = $(this).closest('tr').clone();
			
			clone.find('.morecomplaint_container, .morestatus_container').empty();
					
			clone.find('input[name=more_complaint]').val("");

			clone.find('input[name=more_complaint]').attr('data-id',count++);
	
			if(item.length >= 1){

				clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a><a href="javascript:;" class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i></a>');
						
				obj.closest('tbody').append(clone);
			}

			obj.parent().html('<a class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a>');



		}

	});

	$('body').on('click', '.remove_moreservice', function() {

		var obj = $(this);

		var item = obj.closest("tr").find('input[name="more_complaint"]');
		
		var remaining_item = obj.closest("tr").find('input[name="more_complaint"]');
		var last_row_item = obj.closest("table").find('tr').last().find('input[name="more_complaint"]');

		obj.closest('tr').remove();   
		
		var row_index =$('.more_trow').find('tr').length;
		 
		if(row_index > 1) {

			$('.more_complaints_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');

         }
        else {

			$('.more_complaints_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i></a>');

		}

	});

	$('body').off('click', '.add_rowcomplaint').on('click', '.add_rowcomplaint', function() {

		
		var obj = $(this);
		
		var item = obj.closest("tr").find('select[name="complaint_items"]');

		if(item.val() != "" ){          

			$('.select_item').each(function() { 
				var select = $(this);  
				if(select.data('select2')) { 
					select.select2("destroy"); 
				} 

			});
			 




			var clone = $(this).closest('tr').clone();

			var selected_item = item.find(':selected').val();
			
			clone.find('.datetimepicker2').datetimepicker({

			rtl: false,

			orientation: "left",

			todayHighlight: true,

			autoclose: true

			});

			

			clone.find('.complaintitem_container,.complaintstatus_container').empty();
			

			if(item.length >= 1){

				
				clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowcomplaint"><i class="fa fa-trash-o"></i></a><a href="javascript:;" class="grid_label action-btn edit-icon add_rowcomplaint"><i class="fa fa-plus"></i></a>');
				
				obj.closest('tbody').append(clone);

			}
			obj.parent().html('<a class="grid_label action-btn delete-icon remove_rowcomplaint"><i class="fa fa-trash-o"></i></a>');

			item.find('optgroup > option[value!="' + selected_item + '"]').wrap('<span>');

			$('.select_item').select2();
		}
	});

	$('body').on('click', '.remove_rowcomplaint', function() {

		var obj = $(this);

		var item = obj.closest("tr").find('select[name="complaint_items"]');
		

		var remaining_item = obj.closest("tr").find('select[name="complaint_items"]');
		var last_row_item = obj.closest("table").find('tr').last().find('select[name="complaint_items"]');

		var selected_item = item.find(':selected').val();

		

		var selected_item_array = [];


		last_row_item.each(function() {

			selected_item_array.push($(this).val());

		});



		selected_item_array.push(selected_item);    

		obj.closest('tr').nextUntil( 'tr.parent' ).remove();    
		obj.closest('tr').remove();      


		
		 var row_index =$('.complaints_trow').find('tr').length;
		 
		

		/*if(row_index > 1) {
			obj.closest('tr').remove();   

         }*/
		


		for (var i in selected_item_array) {

			$('select[name=complaint_items]:last').find('span > option[value="' + selected_item_array[i] + '"]').unwrap();
		}

		$('select[name="complaint_items"]:last > span > option').unwrap();

			if(row_index > 1) {
				$('.Complaints_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowcomplaint"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_rowcomplaint"><i class="fa fa-plus"></i></a>');
			} else {
				
				$('.Complaints_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_rowcomplaint"><i class="fa fa-plus"></i></a>');
			}

	});
	$('body').off('click', '.add_rowamc').on('click', '.add_rowamc', function() {

		
		var obj = $(this);
		
		var item = obj.closest("tr").find('select[name="amc_items"]');

		

		if(item.val() != "" ){          

			$('.select_item').each(function() { 
				var select = $(this);  
				if(select.data('select2')) { 
					select.select2("destroy"); 
				} 

			});
			
			
			var clone = $(this).closest('tr').clone();

			var selected_item = item.find(':selected').val();
			
			

			clone.find('.amcitem_container,.amcstatus_container').empty();

			

			if(item.length >= 1){

				clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowamc"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i></a>');
				
				obj.closest('tbody').append(clone);

			}
			obj.parent().html('<a class="grid_label action-btn delete-icon remove_rowamc"><i class="fa fa-trash-o"></i></a>');

			item.find('optgroup > option[value!="' + selected_item + '"]').wrap('<span>');

			$('.select_item').select2();
		}
	});

	$('body').on('click', '.remove_rowamc', function() {

		var obj = $(this);

		var item = obj.closest("tr").find('select[name="amc_items"]');
		

		var remaining_item = obj.closest("tr").find('select[name="amc_items"]');
		var last_row_item = obj.closest("table").find('tr').last().find('select[name="amc_items"]');

		var selected_item = item.find(':selected').val();

		

		var selected_item_array = [];


		last_row_item.each(function() {

			selected_item_array.push($(this).val());

		});



		selected_item_array.push(selected_item);        


		
		 var row_index =$('.amc_trow').find('tr').length;
		 
		

		if(row_index > 1) {
			obj.closest('tr').remove();   

         }
		


		for (var i in selected_item_array) {

			$('select[name=amc_items]:last').find('span > option[value="' + selected_item_array[i] + '"]').unwrap();
		}

		$('select[name="amc_items"]:last > span > option').unwrap();

		



		if(row_index >1) {

			$('.amcitem_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowamc"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i></a>');

		} else {

			$('.amcitem_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i></a>');

		}

	});



	/*$('.add_item').on('click',function(){

	 	add_grouped_items()
	 	
	});*/

	$('.apply_save').on('click',function(){

		$('.groupform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
			

		},

		messages: {
			
				
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



			var service_item =$("select[name=service_items] option:selected" ).map(function(){
								if($(this).text() != 'Select Items'){
			 						return {
            								value : $(this).val(),
            								id : $(this).attr('id') !== undefined ? $(this).attr('id') : 0,
            								type : 1,
            								text : '',
         								}
         						}		
								}).get(); 

			var complaint_item = $( "select[name=complaint_items] option:selected" ).map(function(){
								if($(this).text() != 'Select Items'){
			 						return {
            								value : $(this).val(),
            								id : $(this).attr('id') !== undefined ? $(this).attr('id') : 0,
            								type : 2,
            								text : '',
         								}
         						}		
								}).get(); 		

			var amc_item = $( "select[name=amc_items] option:selected" ).map(function(){
							if($(this).text() != 'Select Items'){
			 					return {
            								value : $(this).val(),
            								id : $(this).attr('id') !== undefined ? $(this).attr('id') : 0,
            								type : 3,
            								text : '',
         								}
							}			
							}).get();

			var more_complaint_item = $( "input[name=more_complaint]" ).map(function(){
							if($(this).val() != ""){
			 					return {
            								value : '',
            								id : $(this).attr('id') !== null ? $(this).attr('id') : 0,
            								type : 4,
            								text : $(this).val(),
         								}
							}			
							}).get();

			
				$.ajax({
				 		url: '{{ route('jc_complaint.store') }}',
				 		type: 'post',
				 		data: {
								_token: '{{ csrf_token() }}',
								service_items:service_item,
								/*service_item_status:service_item_status,*/
								complaint_items:complaint_item,
								/*complaint_item_status:complaint_item_status,*/
								amc_items:amc_item,
								/*amc_item_status:amc_item_status,*/
								/*jc_complaint:$('textarea[name=jc_complaint]').val(),*/
								uuid:$('.apply_save').attr('id'),
								more_complaint_item: more_complaint_item
								/*service_group_id: service_group_id,
								complaint_group_id: complaint_group_id,
								amc_group_id: amc_group_id*/
						},
						beforeSend:function() {

							$('.loader_wall_onspot').show();

						},
				 		success:function(data, textStatus, jqXHR) {

							add_grouped_items()

							$('.group_item_modal').find('.modal-footer').find('.apply_save').hide();

						},
				 		error:function(jqXHR, textStatus, errorThrown) {
							//alert("New Request Failed " +textStatus);
						}
				});

			}
		});
	});


	function add_grouped_items(){

		$('.loader_wall_onspot').hide();
		$('.alert-success_msg').text('Complaints Created Successfully..!');
		$('.alert-success_msg').show();
		$('.jobcard_complaint').css('display','none');
	 	$('.applied_complaint').css('display','block');
	 	$('.completed_value').css('display','none');
	 	$('.applied_completed_value').css('display','block');



	 	var service_item_count = $( "select[name=service_items] option:selected" ).map(function(){
			 						return $(this).val()
								}).get(); 

	 	var complaint_item_count = $( "select[name=complaint_items] option:selected" ).map(function(){
			 						return $(this).val()
								}).get(); 

	 	var amc_item_count = $( "select[name=amc_items] option:selected" ).map(function(){
			 					return $(this).val()
							}).get(); 

	 	var more_item_count = $( "input[name=more_complaint]" ).map(function(){
	 							if($(this).val() != ""){
			 						return $(this).val()
			 					}
							}).get(); 

	 	var service_status_count = $('input[name=service_status]:checked').map(function(){
			 							return $(this).val()
									}).get(); 

	 	var complaint_status_count = $('input[name=complaint_status]:checked').map(function(){
			 							return $(this).val()
									}).get(); 

	 	var amc_status_count = $('input[name=amc_status]:checked').map(function(){
			 							return $(this).val()
									}).get(); 

	 	var morecomplints_status_count = $('input[name=more_status]:checked').map(function(){
			 							return $(this).val()
									}).get();


	 	var sub_total_count = service_item_count.concat(complaint_item_count); 

	 	var sub_count = sub_total_count.concat(amc_item_count);

	 	var total_count = sub_count.concat(more_item_count);

	 	var checked_box_count = service_status_count.concat(complaint_status_count);

	 	var total_checked_with_count = checked_box_count.concat(amc_status_count);

	 	var total_checked_box_count = total_checked_with_count.concat(morecomplints_status_count);

	 	var array_count = total_count.filter(function(value) {
    				return value !== "" && value !== null;
				});

	 	var checkbox_total = total_checked_box_count.filter(function(value) {
    				return value !== "" && value !== null;
				});

	 	var total_complaints = array_count.length;

	 	var total_completed = checkbox_total.length;

	 	var total_complaints_completed = total_completed+'/'+total_complaints;

	 	$('input[name=more_complaint]').each(function(){
			var complaint_text = $(this).val();
			$(this).attr('data-value',complaint_text);
		});

		var modal_body = $('.group_item_modal').find('.modal-body').html();
		//console.log(modal_body);

		var complaints_value = $('input[name=more_complaint]').map(function(){
							if($(this).attr('id') == 1){
								if($(this).val() != ""){
									return $(this).val()+' : '+"Completed"
								}else{
									return ""
								}
								
							}else{

								if($(this).val() != ""){
									return $(this).val()+' : '+"Not Complete"
								}else{
									return ""
								}
							}
            								
		}).get();

		var service_text = $( "select[name=service_items] option:selected" ).map(function(){
			 						if($(this).text() != 'Select Item'){
			 							if($(this).attr('id') == 1){
											return $(this).text()+' : '+"Completed"
										}else{
											return $(this).text()+' : '+"Not Complete"
										}
			 						}
								}).get();


		var complaints_text = $( "select[name=complaint_items] option:selected" ).map(function(){
			 						if($(this).text() != 'Select Items'){
			 							if($(this).attr('id') == 1){
											return $(this).text()+' : '+"Completed"
										}else{
											return $(this).text()+' : '+"Not Complete"
										}
			 						}
								}).get();

		var amc_text = $( "select[name=amc_items] option:selected" ).map(function(){
			 						if($(this).text() != 'Select Items'){
			 							if($(this).attr('id') == 1){
											return $(this).text()+' : '+"Completed"
										}else{
											return $(this).text()+' : '+"Not Complete"
										}
			 						}
								}).get();
		

		var service_group = $('select[name=service_items]').find(":selected").val();


		var complaints_group = $('select[name=complaint_items]').find(":selected").val();

		var amc_group = $('select[name=amc_items]').find(":selected").val();


		$.ajax({

				url: '{{ route('get_group_values') }}',

				type: 'post',

				data: {
					_token : '{{ csrf_token() }}',
					service_group: service_item_count,
					complaints_group: complaint_item_count,
					amc_group: amc_item_count
					},

				dataType: "json",

				success:function(data, textStatus, jqXHR) {


					$('.group_item_modal').modal('hide');

					$('.group_item_modal').find('modal-body').html(" ");

					$('.group_item_modal').find('modal-body').html(modal_body);

					
					var transaction_items = data.data.items;

					console.log(transaction_items);
					
					
					$('.select_item').each(function() { 
							var select = $(this); 
							if(select.data('select2')) { 
								select.select2("destroy"); 
							} 
					});

					var clone = $(".crud_table tbody > tr ");

					clone.find('td:last').html('<a class="grid_label action-btn delete-icon remove_row_append"><i class="fa fa-trash-o"></i></a>');

					clone.find('.datetimepicker2').datetimepicker({
						rtl: false,
						orientation: "left",
						todayHighlight: true,
						autoclose: true
					});

					clone.find('select[name=item_id], select[name=tax_id], select[name=discount_id], input[name=quantity], input[name=rate], input[name=amount]').val("");
					
					var index_number = 1;
					var item_array = [];

					

					for(var i in transaction_items)
					{
						var transaction_item = clone.clone();

						transaction_item.find('.datetimepicker2').datetimepicker({
						rtl: false,
						orientation: "left",
						todayHighlight: true,
						autoclose: true
						});				

						/* get tr length using for batch item */
		
						var row_index = $('.append_table tbody > tr').length;	
						
						var New_data_row = row_index+1;

						transaction_item.find( "td:eq(0) > span" ).text(New_data_row);

						transaction_item.find( "td:eq(0) > span" ).removeClass('index_number');

						transaction_item.find( "td:eq(0) > span" ).addClass('index_number_append'); 


						transaction_item.find( "td:eq(1)" ).html('<input type="hidden" name="item_id" value="'+ transaction_items[i].item_id +'"  class="form-control"><input type="hidden" name="batch_id" value="'+transaction_items[i].batch_id+'" ><input type="text" name="append_item" class ="form-control" disabled="ture" style="width:180px;float: left;" value="'+transaction_items[i].item_name+'">');
						

						item_array.push(transaction_items[i].item_id);						

						transaction_item.find('input[name=in_stock]').val(transaction_items[i].in_stock);

						transaction_item.find('select[name=item_id]').val(transaction_items[i].item_id);

						if(transaction_items[i].count == 2 || transaction_items[i].count >= 2 ){

							transaction_item.find('select[name=item_id]').closest('tr').find('.item_batch').show();

							transaction_item.find('select[name=item_id]').closest('tr').find('.item_batch').attr("data-id",transaction_items[i].item_id);

							transaction_item.find('select[name=item_id]').closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock], input[name=amount], input[name=base_price], input[name=new_base_price], input[name=tax_amount],input[name=tax_total], select[name=tax_id], select[name=discount_id], input[name=discount_value]').val("");

							transaction_item.find('select[name=item_id]').closest('tr').find('select, input, textarea').prop('disabled', true);
							
						}

						transaction_item.find('input[name=quantity]').val(1);
				

						if(transaction_items[i].segment_price == null){
							transaction_item.find('input[name=rate]').val(data.data.base_price[i]);

							transaction_item.find('input[name=amount]').val(parseFloat(data.data.base_price[i]) * transaction_item.find('input[name=quantity]').val());
						}
						else{
							transaction_item.find('input[name=rate]').val(transaction_items[i].segment_price);

							transaction_item.find('input[name=amount]').val(parseFloat(transaction_items[i].segment_price) * transaction_item.find('input[name=quantity]').val());
						}				


						transaction_item.find('select[name=tax_id]').val(transaction_items[i].tax_id);

						$(".append_table tbody").append(transaction_item);
						
					}


					var row_index = $('.crud_table tbody > tr').length;

					$('.append_table').find('tr').find('td:last').html('<a class="grid_label action-btn delete-icon remove_row_append"><i class="fa fa-trash-o"></i></a>');

					$('.crud_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_row_append"><i class="fa fa-plus"></i></a>');				
	

					$('.applied_completed_value').text(total_complaints_completed);

					$('.group_item_modal').find('.modal-body').find('select[name=service_items]').val(1);	


					var service_value = $('.group_item_modal').find('.modal-body').find('.serviceitem_table ').find('input[name=service_status]');

					var additional_value = $('.group_item_modal').find('.modal-body').find('.more_complaints_table ').find('input[name=more_status]');

					var complaint_value = $('.group_item_modal').find('.modal-body').find('.complaintitem_table  ').find('input[name=complaint_status]');

					var amc_value = $('.group_item_modal').find('.modal-body').find('.amcitem_table').find('input[name=amc_status]');

					var more_complaint_checkbox_value = $('input[name=complaints_check]').val();

					var text_complaints = $('.group_item_modal').find('.modal-body').find('.more_complaints_table ').find('input[name=more_complaint]');

					if(more_complaint_checkbox_value == 1){
						$('input[name=complaints_check]').prop( "checked", true );
					}else{
						$('input[name=complaints_check]').prop( "checked", false );
					}

					$('input[name=complaints_check]').on('click',function(){
						if($(this).prop('checked') == true){
							$('.more_complaints').css('display','block');
							$(this).val('1');
						}else{
							$('.more_complaints').css('display','none');
							$(this).val('0');
						}
					});

					$('.showservice_row').on('click',function(){
	 					$('.service_trow').show();
	 					$('.showservice_row').hide();

					});

					$('.showtext_row').on('click',function(){
	 					$('.more_trow').show();
	 					$('.showtext_row').hide();

					});

					text_complaints.each(function () { 
						var value = $(this).attr('data-value');
						$(this).val(value);
					});


					additional_value.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});	

					service_value.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});

					complaint_value.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});	


					amc_value.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});	
					
					var string = complaints_value.toString();
					var string1 = service_text.toString();
					var string2 = complaints_text.toString();
					var string3 = amc_text.toString();

					var complaint_in_string = string+','+string1+','+string2+','+string3;
					var newList = complaint_in_string.replace(/,/g, "\n");

					$('textarea.complaint').val(newList);


					$('.select_item').select2();

					table();



				},

				error:function(jqXHR, textStatus, errorThrown) {

					//alert("New Request Failed " +textStatus);

				}

		});
	}

	
	

});