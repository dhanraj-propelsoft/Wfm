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
          <h4 class="float-left page-title">WareHouse</h4>
          @permission('role-create')
          	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
          @endpermission
</div>


<div class="float-left" style="width: 100%; padding-top: 10px">
  			<table id="datatable" class="table data_table" width="100%" cellspacing="0">
        <thead>
            <tr>
				<th>Warehouse</th>
				<th>Contact Person</th>
				<th>Mobile Number</th>
				<th>Email Address</th>				
				<th>Address</th>
				<th>Status</th>
				<th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($warehouses as $warehose)
            <tr>
                <td>{{ $warehose->placename }}</td>
				<td>{{ $warehose->contact_person_name }}</td>
				<td>{{ $warehose->mobile_no }}</td>
				<td>{{ $warehose->email_address }}</td>
				
				<td>
					{{ $warehose->address }}
				</td>
				<td>
					@if($warehose->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($warehose->status == 0)
						<label class="grid_label badge badge-warning status">In-Active</label>
					@endif

					<select style="display:none" id="{{ $warehose->id }}" class="active_status form-control">
					<option @if($warehose->status == 1) selected="selected" @endif value="1">Active</option>
					<option @if($warehose->status == 0) selected="selected" @endif value="0">In-Active</option>
					</select>
				</td>
                <td>	
				    <a data-id="{{ $warehose->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					
					<a data-id="{{ $warehose->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
				</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


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
		datatable = $('#datatable').DataTable({"stateSave": true}
);
		$('.crud_modal').modal('hide');

		$('.alert-success').text(message);
		$('.alert-success').show();

		setTimeout(function() { $('.alert').fadeOut(); }, 3000);
	}

	$(document).ready(function() {

	datatable = $('#datatable').DataTable({"stateSave": true}
);

	$('.add').on('click', function(e) {
        e.preventDefault();
        $.get("{{ route('warehouse.create') }}", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
	});

	$('body').on('click', '.edit', function(e) {
        e.preventDefault();
        $.get("{{ url('inventory/warehouse') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
	});

	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('warehouse.destroy') }}';
		delete_row(id, parent, delete_url);
   });

	$('body').on('click', '.status', function(e) {
        $(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});

	$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var current = $(this);
			$.ajax({
			 url: "{{ route('warehouse.status') }}",
			 type: 'post',
			 data: {
			 	id: id,
			 	_token :"{{ csrf_token() }}",
			 	status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					if(status == 0) {
						current.parent().find('label').removeClass('badge-success');
						current.parent().find('label').addClass('badge-warning');
					}else if(status == 1) {
						current.parent().find('label').removeClass('badge-warning');
						current.parent().find('label').addClass('badge-success');
					}
					current.hide();
					current.parent().find('label').show();
					current.parent().find('label').text(current.find('option:selected').text());
				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		});

   		function delete_row(id, parent, delete_url) {
			$('.delete_modal_ajax').modal('show');
				$('.delete_modal_ajax_btn').off().on('click', function() {
			        $.ajax({
						 url: delete_url,
						 type: 'post',
						 data: {
						 	_method: 'delete',
						 	_token : '{{ csrf_token() }}',
						 	id: id,
							},
						 dataType: "json",
							success:function(data, textStatus, jqXHR) {
								datatable.destroy();
								parent.remove();
								datatable = $('#datatable').DataTable({"stateSave": true}
);
								$('.delete_modal_ajax').modal('hide');
								$('.alert-success').text(data.message);
								$('.alert-success').show();

								setTimeout(function() { $('.alert').fadeOut(); }, 3000);
							},
						 error:function(jqXHR, textStatus, errorThrown) {
							}
						});
			    });
		    }

	});
	</script>
@stop