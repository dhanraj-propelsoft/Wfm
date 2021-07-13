<div id="example_processing" class="dataTables_processing" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="" style="position:absolute">Processing...</span> </div>
<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
			<th> Name </th>	
			<th> Category </th>
			<th> In Stock </th>
			<th> Purchase Price </th>
			<th> selling Unit Price </th>
			<th> selling Unit Price + Tax </th>
			<th> Status </th>
			<th> Action </th>
		</tr>
	</thead>
	<tbody>
	@foreach($inventory_items as $inventory_item)
		<tr>
			
			<td width="1" style="padding-left: 7px;">{{ Form::checkbox('inventory_item',$inventory_item->id, null, ['id' => $inventory_item->id, 'class' => 'item_check']) }}<label for="{{$inventory_item->id}}"><span></span></label></td>
			<td>
			<image style="border:2px solid #ccc; border-radius: 3px;" width="50" height="50" src="{{ $inventory_item->image }}" />

			<span>{{ $inventory_item->name }}</td></span>
			<td>{{ $inventory_item->category_name }}</td>
			<td>@if($inventory_item->in_stock != null)
					{{$inventory_item->in_stock}} {{$inventory_item->unit}}
				@endif
			</td>	
			 <td>{{ $inventory_item->purchase_price }}</td>
			<td>{{ $inventory_item->selling_price }}</td>

			

			<td>
				<?php 
					$sale_price = App\Custom::get_least_closest_date(json_decode($inventory_item->sale_price_data, true));
				?>
					{{ $inventory_item->base_price }}

				<?php			
					echo "<span style='color:#aaa'> From ".Carbon\Carbon::parse($sale_price['date'])->format('jS \\of M Y')."</span>" ;	
			 	?>
			 		
			 </td>
				<td>
					@if($inventory_item->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($inventory_item->status == 0)
						<label class="grid_label badge badge-warning status">In-Active</label>
					@endif
					@permission('item-edit')
					<select style="display:none" id="{{ $inventory_item->id }}" class="active_status form-control">
						<option @if($inventory_item->status == 1) selected="selected" @endif value="1">Active</option>
						<option @if($inventory_item->status == 0) selected="selected" @endif value="0">In-Active</option>
					</select>
					@endpermission
				</td>
				<td>
				@permission('item-edit')					
					<a data-id="{{$inventory_item->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
				@endpermission
				<!--@permission('item-delete')-->
				<!--	<a data-id="{{$inventory_item->id}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>-->
				<!--@endpermission-->
					<a href="{{route('item.show', $inventory_item->id)}}" data-id="{{$inventory_item->id}}" class="grid_label action-btn show-icon show"><i class="fa fa-eye"></i></a>
				@permission('adjustment-create')
					@if($inventory_item->in_stock != null)
					<a href="javascript:;" data-id="{{$inventory_item->id}}" data-stock="{{$inventory_item->in_stock}}" class="grid_label badge badge-info create">Adjust Quantity</a>
					@endif
				@endpermission
				<a data-id="{{$inventory_item->id}}" class="grid_label action-btn edit-icon batch"><i class="fa fa-bold"></i></a>
				</td>
		</tr>
	@endforeach
	</tbody>
</table>

<div class="dataTables_info" id="datatable_info" role="status" aria-live="polite">Showing {{$inventory_items->firstItem()}} to {{$inventory_items->lastItem()}} of {{ $inventory_items->total() }} entries</div> 


{{$inventory_items->links()}}