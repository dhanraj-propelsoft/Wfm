
<div id="example_processing" class="dataTables_processing" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="" style="position:absolute">Processing...</span> </div>
<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
			<th> Make </th>             
			<th> Model </th>             
			<th> Variant Name</th>             
			<th> Version </th>
			<th> Configuration </th>
			<th> Status </th>
			<th> Action </th>
		</tr>
	</thead>
	<tbody>
	@foreach($vehicle_variants as $vehicle_variant)
		
		<tr>
			<td width="1" style="padding-left: 7px;">{{ Form::checkbox('vehicle_variant',$vehicle_variant->id, null, ['id' => $vehicle_variant->id, 'class' => 'item_check']) }}<label for="{{$vehicle_variant->id}}"><span></span></label></td>             
			<td>{{ $vehicle_variant->make_name }}</td>              
			<td>{{ $vehicle_variant->model_name }}</td>              
			<td>{{ $vehicle_variant->name }}</td>              
			<td>{{ $vehicle_variant->version }}</td>
			<td>{{ $vehicle_variant->vehicle_configuration }}</td>
			<td>
				@if($vehicle_variant->status == '1')
					<label class="grid_label badge badge-success status">Active</label>
				@elseif($vehicle_variant->status == '0')
				  <label class="grid_label badge badge-warning status">In-Active</label>
				@endif
				
				<select style="display:none" id="{{ $vehicle_variant->id }}" class="active_status form-control">
					<option @if($vehicle_variant->status == 1) selected="selected" @endif value="1">Active</option>
					<option @if($vehicle_variant->status == 0) selected="selected" @endif value="0">In-Active</option>
				</select>
			
			</td>
			<td> 
			@permission('variant-edit')         
				<a data-id="{{ $vehicle_variant->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			@endpermission
			@permission('variant-delete')         
				<a data-id="{{ $vehicle_variant->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 
			@endpermission
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

<div class="dataTables_info" id="datatable_info" role="status" aria-live="polite">Showing {{$vehicle_variants->firstItem()}} to {{$vehicle_variants->lastItem()}} of {{ $vehicle_variants->total() }} entries</div> 


{{$vehicle_variants->links()}}


  