<div class="modal-header">
	<h4 class="modal-title float-right">JobCard Complaints</h4>
	  <div class="alert alert-danger alert-danger_msg"></div>
	  <div class="alert alert-success alert-success_msg"></div>
</div>

{!!Form::model($transaction_id, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{!! Form::hidden('id', $transaction_id) !!}

		@if($more_complaint_datas)
		<div class = "col-md-12">
			<table id="additional_complaints" style="border-collapse: collapse;" class="table additional_complaints table-bordered">
				<thead style="text-align: center;">
					<tr>
						<th style="background-color:#ccc;width: 100%;">User Compalints</th>
						<th style="background-color:#ccc;">Done?</th>
						<th style="background-color:#ccc;"><a class="grid_label action-btn edit-icon showtext_row"><i class="fa fa-plus"></i></a></th>
					</tr>
				</thead>
				@if(sizeof($more_complaint_datas) > 0)
				<tbody>
				@foreach($more_complaint_datas as $more_complaint_data)	
					<tr>
						<td style="width: 100%;">
							{{ Form::text('more_complaint',$more_complaint_data->additional_complaints, ['class' => 'form-control', 'data-value' => '','id'=>$more_complaint_data->status]) }}
						</td>
						<td style="text-align: center;"><input type="checkbox" value="{{$more_complaint_data->status}}"  id="more_status"name="more_status" style="display: inline-block;width:25px;height:25px"></td>
						<td></td>
					</tr>
				@endforeach	
				</tbody>
				@else
				<tbody class="more_trow" style="display: none">
				<tr class="more_row">
					<td class="item_td" style="width: 100%;">
						{{ Form::text('more_complaint', null, ['class' => 'form-control more_complaint', 'data-value' => '','id'=>'0','data-id'=>'1']) }}
						<div class="morecomplaint_container"></div>
					</td>
					<td align="center">
						<input type="checkbox" value="0"  id="more_status" name="more_status" style="display: inline-block;width:25px;height:25px">
						<div class="morestatus_container"></div>
			   		</td>		
					<td>
						<a style="display: none;" class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a> <a class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i>
						</a>
					</td>						
				</tr>
			</tbody>
				@endif
			</table>
		</div>
		@endif
		
		@if($service_datas)
		<div class = "col-md-12">
			<table id="grouped_item_table" style="border-collapse: collapse;" class="table grouped_item_table table-bordered">
				<thead>
					<tr>
					 	<th style="background-color:#ccc;text-align: center;width: 100%;">Group Services</th>
						<th style="background-color:#ccc;text-align: center;">Done?</th>
						<th style="background-color:#ccc;text-align: center;"><a class="grid_label action-btn edit-icon showservice_row"><i class="fa fa-plus"></i></a></th>
					</tr>	
				</thead>
				@if(sizeof($service_datas) > 0)
				<tbody class="service_trow">
					@foreach($service_datas as $service_data)
					<tr>
						<td style="width: 100%;">
							<select name='service_items' class='form-control service_items select_item' id = ''>
								<option value="{{$service_data->service_id}}">{{$service_data->service}}</option>
								@foreach($service_items as $service_item) 
								<option value="{{$service_item->id}}">{{$service_item->name}}</option>
								@endforeach
							</select>
						</td>
						<td style="text-align: center;"><input type="checkbox" value="{{$service_data->status}}"  id="service_status"name="service_status" style="display: inline-block;width:25px;height:25px"></td>
						<td></td>
					</tr>
					@endforeach	
				</tbody>
				@else
				<tbody class="service_trow" style="display: none">
					<tr class="service_row">
					<td class="item_td" style="width: 100%;">
						 <select name="service_items" class="form-control service_items select_item" id="">
									<option value="">Select Item</option>
										@foreach($service_items as $service_item)
										<option  value="{{$service_item->id}}">{{$service_item->name}}</option>
									@endforeach
									</select>
						<!-- {{ Form::select('service_items',$service_items, null, ['class'=>'form-control service_items','id'=>'']) }} -->
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
				@endif
			</table>
		</div>
		@endif

		@if($complaint_datas)
		<div class = "col-md-12">
			{{ Form::hidden('complaint_item_types',2, null, ['class'=>'form-control ']) }}
			<table id="Complaints_table" style="border-collapse: collapse;" class="table Complaints_table table-bordered">
				<thead style="text-align: center;">
					<tr>
						<th style="background-color:#ccc;width: 100%;">Defined Complaints</th>
						<th style="background-color:#ccc;">Done?</th>
						<th style="background-color:#ccc;"><a class="grid_label action-btn edit-icon showcomplaint_row"><i class="fa fa-plus"></i></a></th>
					</tr>
				</thead>
				@if(sizeof($complaint_datas) > 0)
				<tbody class="complaints_trow">
					@foreach($complaint_datas as $complaint_data)
					<tr>
						<td style="width: 100%;">
							<select name='complaint_items' class='form-control complaint_items select_item' id = ''>
								<option value="{{$complaint_data->complaint_id}}">{{$complaint_data->complaint}}</option>
								@foreach($complaint_items as $complaint_item) 
								<option value="{{$complaint_item->id}}">{{$complaint_item->name}}</option>
								@endforeach
								</select>
						</td>
						<td style="text-align: center;"><input type="checkbox" value="{{$complaint_data->status}}"  id="complaint_status" name="complaint_status" style="display: inline-block;width:25px;height:25px"></td>
						<td></td>
					</tr>
					@endforeach	
				</tbody>
				@else
				<tbody class="complaints_trow" style="display: none">
				<tr>
					<td class="complaint_item_td" style="width: 100%;">
						 <select name="complaint_items" class="form-control complaint_items select_item" id="0">
									<option value="">Select Complaint</option>
										@foreach($complaint_items as $complaint_item)
										<option  value="{{$complaint_item->id}}">{{$complaint_item->name}}</option>
									@endforeach
									</select>

						<!-- {{ Form::select('complaint_items',$complaint_items, null, ['class'=>'form-control complaint_items select_item','id'=>'0']) }} -->
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
			@endif
			</table>
		</div>
		@endif

		@if($amc_datas)
		<div class = "col-md-12">
			{{ Form::hidden('amc_item_types',3, null, ['class'=>'form-control ']) }}
			<table id="amc_item_table" style="border-collapse: collapse;" class="table amc_item_table table-bordered">
				<thead style="text-align: center;">
					<tr>
						<th style="background-color:#ccc;width: 100%;">Amc Items</th>
						<th style="background-color:#ccc;">Done?</th>
						<th style="background-color:#ccc;"><a class="grid_label action-btn edit-icon showamc_row"><i class="fa fa-plus"></i></a></th>
					</tr>
				</thead class="amc_trow">
				@if(sizeof($amc_datas) > 0)
				<tbody>
					@foreach($amc_datas as $amc_data)
					<tr>
						<td style="width: 100%;">
							<select name='amc_items' class='form-control amc_items select_item' id = '0'>
								<option value="{{$amc_data->amc_id}}">{{$amc_data->amc}}</option>
								@foreach($amc_items as $amc_item) 
								<option value="{{$amc_item->id}}">{{$amc_item->name}}</option>
								@endforeach
								</select>
						</td>
						<td style="text-align: center;"><input type="checkbox" value="{{$amc_data->status}}"  id="amc_status" name="amc_status" style="display: inline-block;width:25px;height:25px"></td>
						<td></td>
					</tr>
					@endforeach	
				</tbody>
				@else
				<tbody  class="amc_trow" style="display: none">
				<tr>
					<td class="amc_item_td" style="width: 100%;">
						<select name="amc_items" class="form-control amc_items select_item" id="0">
									<option value="">Select Items</option>
										@foreach($amc_items as $amc_item)
										<option  value="{{$amc_item->id}}">{{$amc_item->name}}</option>
									@endforeach
									</select>
						<!-- {{ Form::select('amc_items',$amc_items, null, ['class'=>'form-control amc_items select_item','id'=>'0']) }} -->
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
			@endif
			</table>
		</div>
		@endif


	</div>
</div>

<div class="modal-footer"> 
	<button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>	     
	<button type="submit" class="btn btn-success update_save" id ="{{$transaction_id}}" >Apply</button>	
</div>
{!! Form::close() !!}

<script>
	$(document).ready(function() {

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

		var find_service_status = $("input[name=service_status]");
		
		var find_complaint_status = $("input[name=complaint_status]");

		var find_amc_status = $("input[name=amc_status]");

		var find_more_status = $("input[name=more_status]");

		find_service_status.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});

		find_complaint_status.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});

		find_amc_status.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});

		find_more_status.each(function () { 
						if($(this).val() == 1){
							$(this).prop( "checked", true );
						}else{
							$(this).prop( "checked", false );
						}
					});

		var service_row_index = $('.grouped_item_table tbody > tr').length;
		if(service_row_index>1){
			$('.showservice_row').hide();
		}

		var complaint_row_index=$('complaintitem_table tbody>tr').length;
		if(complaint_row_index>1){
			$('.complaints_trow').hide();
		}

		var amc_row_index = $('.amc_item_table tbody > tr').length;
		if(amc_row_index>1){
			$('.showamc_row').hide();
		}


		$('.grouped_item_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowservice"><i class="fa fa-trash-o"></i></a><a  class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');

		$('.Complaints_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowcomplaint"><i class="fa fa-trash-o"></i></a><a  class="grid_label action-btn edit-icon add_rowcomplaint"><i class="fa fa-plus"></i></a>');

		$('.amc_item_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowamc"><i class="fa fa-trash-o"></i></a><a  class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i></a>');

		$('.additional_complaints').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i></a>');

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

							clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowservice"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a href="javascript:;" class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');
						
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

			var row_index = $('.grouped_item_table tbody > tr').length;
		
		
			for (var i in selected_item_array) {

				$('select[name=service_items]:last').find('span > option[value="' + selected_item_array[i] + '"]').unwrap();
			}

			$('select[name="service_items"]:last > span > option').unwrap();

			if(row_index > 1) {
				$('.grouped_item_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowservice"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');
			} else {
				$('.grouped_item_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_rowservice"><i class="fa fa-plus"></i></a>');
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
			

				clone.find('.complaintitem_container,.complaintstatus_container').empty();

				
				
			

				if(item.length >= 1){

				
					clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowcomplaint"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a href="javascript:;" class="grid_label action-btn edit-icon add_rowcomplaint"><i class="fa fa-plus"></i></a>');
				
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

		 	var row_index =$('.Complaints_table tbody > tr').length;
		 	
		
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

					clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowamc"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i></a>');
				
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
			obj.closest('tr').nextUntil( 'tr.parent' ).remove();    
			obj.closest('tr').remove();     
		 	var row_index =$('.amc_item_table').find('tr').length;

			for (var i in selected_item_array) {

				$('select[name=amc_items]:last').find('span > option[value="' + selected_item_array[i] + '"]').unwrap();
			}

			$('select[name="amc_items"]:last > span > option').unwrap();

			if(row_index >1) {

				$('.amc_item_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_rowamc"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i></a>');

			} else {

				$('.amc_item_table').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_rowamc"><i class="fa fa-plus"></i></a>');

			}

		});

		var count = $('input[name=more_complaint]').attr('data-id');

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

					clone.find('td').last().html('<a class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;<a href="javascript:;" class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i></a>');
						
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

			obj.closest('tr').nextUntil( 'tr.parent' ).remove();    
			obj.closest('tr').remove(); 
		
			var row_index =$('.additional_complaints tbody > tr').length;
		 
			if(row_index > 1) {
					$('.additional_complaints').find('tr').last().find('td').last().html('<a class="grid_label action-btn delete-icon remove_moreservice"><i class="fa fa-trash-o"></i></a><a class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i></a>');
				} else {
					$('.additional_complaints').find('tr').last().find('td').last().html('<a class="grid_label action-btn edit-icon add_moreservice"><i class="fa fa-plus"></i></a>');
				}

		});
		
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

		$('body').on('change', '.service_items', function() {

			var row_index =$('.service_trow').find('tr').length;

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
		
			if(row_index > 1){
				if ($('.amc_items >option[value="' + $(this).val() + '"]:selected').length > 1)
				{
						$('.alert-danger_msg').text('Already Exist');
						$('.alert-danger_msg').show();
						setTimeout(function() { $('.alert').fadeOut(); }, 3000);
			  			$(this).val('').change();
	        	}
    		}
		});	

		$('.update_save').on('click',function(e){

			e.preventDefault();

			var transaction_id = $(this).attr('id');

			$('textarea[name=compliant]').val("");

			var service_item =$("select[name=service_items] option:selected" ).map(function(){
								if($(this).text() != 'Select Items'){
			 						return {
            								value : $(this).val(),
            								id : $(this).attr('id') !== undefined ? $(this).attr('id') : 0,
            								type : 1,
            								text : ''

         								}
         						}		
								}).get(); 

			var complaint_item = $( "select[name=complaint_items] option:selected" ).map(function(){
								if($(this).text() != 'Select Items'){
			 						return {
            								value : $(this).val(),
            								id : $(this).attr('id') !== undefined ? $(this).attr('id') : 0,
            								type : 2,
            								text : ''
         								}
         						}		
								}).get(); 		

			var amc_item = $( "select[name=amc_items] option:selected" ).map(function(){
							if($(this).text() != 'Select Items'){
			 					return {
            								value : $(this).val(),
            								id : $(this).attr('id') !== undefined ? $(this).attr('id') : 0,
            								type : 3,
            								text : ''
         								}
							}			
							}).get();


			var more_complaint_item = $( "input[name=more_complaint]" ).map(function(){
							if($(this).val() != ""){
			 					return {
            								value : '',
            								id : $(this).attr('id') !== null ? $(this).attr('id') : 0,
            								type : 4,
            								text : $(this).val()
         								}
							}			
							}).get();

			

			$.ajax({
				 	url: '{{ route('jc_complaint.update') }}',
				 	type: 'post',
				 	data: {
							_token: '{{ csrf_token() }}',
							transaction_id: transaction_id, 
							service_items: service_item,
							complaint_items: complaint_item,
							amc_items: amc_item,
							more_complaint_item: more_complaint_item
						},
					beforeSend:function() {

							$('.loader_wall_onspot').show();

					},
				 	success:function(data, textStatus, jqXHR) {
				 		
				 		add_grouped_items()
				 		
					},
				 	error:function(jqXHR, textStatus, errorThrown) {
						//alert("New Request Failed " +textStatus);
					}
				});
		});

		function add_grouped_items(){

			$('.loader_wall_onspot').hide();

			$('.alert-success_msg').text('Complaints Updated Successfully..!');
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

			var complaints_value = $('input[name=more_complaint]').map(function(){
							if($(this).attr('id') == 1){
								return $(this).val()+' : '+"Completed"
							}else{
								return $(this).val()+' : '+"not Complete"
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


			var existing_item_id = $(".crud_table tbody").find('select[name=item_id]').map(function(){
			 					return $(this).val()
							}).get();


			var service_item_count = $( "select[name=service_items] option:selected" ).map(function(){
			 						return $(this).val()
								}).get(); 



	 		var complaint_item_count = $( "select[name=complaint_items] option:selected" ).map(function(){
			 						return $(this).val()
								}).get(); 

	 		var amc_item_count = $( "select[name=amc_items] option:selected" ).map(function(){
			 					return $(this).val()
							}).get();


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

						//console.log(data);
						$('.group_item_modal').modal('hide');

						$('.applied_completed_value').text(total_complaints_completed);	

						var string = complaints_value.toString();
						var string1 = service_text.toString();
						var string2 = complaints_text.toString();
						var string3 = amc_text.toString();

						var complaint_in_string = string+','+string1+','+string2+','+string3;
						var newList = complaint_in_string.replace(/,/g, "\n");

						$('textarea[name=compliant]').val(newList);

						$('.group_item_modal').find('modal-body').html(" ");

						$('.group_item_modal').find('modal-body').html(modal_body);

						var transaction_items = data.data.items;
						//console.log(transaction_items);

						var selected_employee = data.data.selected_employee;

						var items = [];

						for(var i in transaction_items){
							items.push(transaction_items[i].item_id);
						}

						var inventory_items = [];
						var i = 0;

						jQuery.grep(items, function(el) {

    					if (jQuery.inArray(el, existing_item_id) == -1) inventory_items.push(el);


   						 	i++;

						});


					

						$('.select_item').each(function() { 

							var select = $(this);  

							if(select.data('select2')) { 

								select.select2("destroy"); 

							} 

						});

						$(".crud_table tbody").find('tr:last').find('td:last').html('');

						var clone = $(".crud_table tbody").find('tr:first').clone(true, true);

						clone.find('.datetimepicker2').datetimepicker({
								rtl: false,
								orientation: "left",
								todayHighlight: true,
								autoclose: true
						});

						clone.find('select[name=item_id], select[name=tax_id], select[name=discount_id], input[name=quantity], input[name=rate], input[name=amount]').val("");

						clone.find('select > optgroup > span >  option').unwrap();

						var index_number = $(".crud_table tbody").find('tr').length;

						var index = index_number + 1;
						var item_array = [];

												
						for (var i = 0; i < transaction_items.length; i++) {
							for(var j = 0; j < inventory_items.length; j++){
								if (transaction_items[i].item_id == inventory_items[j]) {

										var transaction_item = clone.clone(true, true);

										var slno = index++;

										transaction_item.find('.index_number').text(slno);

										transaction_item.find('.index_number').closest('tr').attr("id","tr_"+slno);

			    						transaction_item.find('.index_number').closest('tr').attr("data-row",slno);

										transaction_item.find('select[name=item_id]').val(transaction_items[i].item_id);

										if(transaction_items[i].count == 1){

											transaction_item.find('select[name=item_id]').closest('tr').find('.item_batch').show();

											transaction_item.find('select[name=item_id]').closest('tr').find('.item_batch').attr("data-id",transaction_items[i].item_id);

											transaction_item.find('select[name=item_id]').closest('tr').find('input[name=quantity], input[name=rate], select[name=discount_id],input[name=in_stock], input[name=amount], input[name=base_price], input[name=new_base_price], input[name=tax_amount],input[name=tax_total], select[name=tax_id], select[name=discount_id], input[name=discount_value]').val("");

											transaction_item.find('select[name=item_id]').closest('tr').find('select, input, textarea').prop('disabled', true);
							
										}

										if(transaction_items[i].segment_price == null){
											transaction_item.find('input[name=rate]').val(data.data.base_price[i]);

											transaction_item.find('input[name=amount]').val(parseFloat(data.data.base_price[i]) * parseFloat(transaction_items[i].quantity));
										}
										else{
												transaction_item.find('input[name=rate]').val(transaction_items[i].segment_price);

												transaction_item.find('input[name=amount]').val(parseFloat(transaction_items[i].segment_price) * parseFloat(transaction_items[i].quantity));
											}

										transaction_item.find('input[name=in_stock]').val(transaction_items[i].in_stock);

										if(parseInt(transaction_items[i].quantity) > parseInt(transaction_items[i].in_stock))
										{
											transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#FF0000');


											transaction_item.find('select[name=job_item_status]').trigger('change');
							
										}
										else{

											transaction_item.find('input[name=quantity]').val(transaction_items[i].quantity).css('color', '#000000');

											//transaction_item.find('select[name=job_item_status]').val(1);

											transaction_item.find('select[name=job_item_status]').trigger('change');			

										}

										transaction_item.find('select[name=assigned_employee_id]').val(selected_employee);

										transaction_item.find('select[name=job_item_status]').val(1);

										$(".crud_table tbody").append(transaction_item);

										$('.select_item').select2();

										table();
								}		
							
							}	
						}

					
						
					},

					error:function(jqXHR, textStatus, errorThrown) {

						//alert("New Request Failed " +textStatus);

					}

			});

		}
	});	

</script>