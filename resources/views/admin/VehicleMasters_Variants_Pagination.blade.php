<style>

	</style>

<div id="example_processing" class="dataTables_processing" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="" style="position:absolute">Processing...</span> </div>
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>
	  	
		<th>DB Primary ID</th> 
		<th>Type</th>
		<th>Category</th>
		<th>Make</th>
		<th>Model</th>
		<th> Varient  </th>	
		<th>Version</th>
		<th>CreatedBy </th>
	
		<th>CreatedOn</th>
		<th>Edit</th>
		<th></th>
		 </tr>
	</thead>
	<tbody>
				@foreach($variants as $varient)
				<tr>
					 <td>{{$varient->id}}</td>
					 <td>{{$varient->type}}</td>
					 <td>{{$varient->category}}</td>
					  <td > {{$varient->make}} </td>
					   <td > {{$varient->model}} </td>
					 <td > {{$varient->name}} </td>
					   <td > {{$varient->version}} </td>
					<td> {{$varient->user_name}} </td>
					

					<td> {{$varient->start_date}} </td>
					<td>
                	<a data-id="{{ $varient->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					
				       
				</td>
				<td>

					@if($varient->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($varient->status == 0)
						<label class="grid_label badge badge-warning status">In-Active
						</label>
						
					@endif

					<select style="display:none" id="{{ $varient->id }}" class="active_status form-control">
					<option @if($varient->status == 1) selected="selected" @endif value="1">Active</option>
					<option @if($varient->status == 0) selected="selected" @endif value="0">In-Active</option>
					
					</select>
				</td>
					
				</tr>
				@endforeach
			</tbody>
  </table>
  {{$variants->links()}}

  