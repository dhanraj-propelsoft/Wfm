@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@if($category == "category")
	@if(Session::get('module_name') == "inventory")
		@include('includes.inventory')
	@elseif(Session::get('module_name') == "trade")
		@include('includes.trade')
	@else
		@include('includes.inventory')
	@endif
@elseif($category == "division")
@include('includes.workshop')
@endif
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
          <h4 class="float-left page-title">@if($category == "category") Category @elseif($category == "division") Division @endif</h4>
           @permission('item-category-create')
          	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
     	   @endpermission
        </div>




<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
		<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			<li><a class="multidelete">Delete</a></li>
			<li><a data-value="1" class="multiapprove">Make Active</a></li>
			<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
		</ul>
		</div>
  			<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
        <thead>
            <tr>
            	<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>
				<th> Category </th>								
				<th> Parent Category </th>	
				<th> Status </th>
				<th> Action </th>
            </tr>
        </thead>
        <tbody>
        @foreach($inventory_categories as $inventory_category)
            <tr>
            	<td width="1">{{ Form::checkbox('inventory_category',$inventory_category->id, null, ['id' => $inventory_category->id, 'class' => 'item_check']) }}<label for="{{$inventory_category->id}}"><span></span></label></td>
            	<td>{{ $inventory_category->name }}</td>							
				<td>{{ $inventory_category->parent_name }}</td>
                
                <td>
						@if($inventory_category->status == 1)
							<label class="grid_label badge badge-success status">Active</label>
						@elseif($inventory_category->status == 0)
							<label class="grid_label badge badge-warning status">In-Active</label>
						@endif
						@permission('item-category-edit')
						<select style="display:none" id="{{ $inventory_category->id }}" class="active_status form-control">
						<option @if($inventory_category->status == 1) selected="selected" @endif value="1">Active</option>
						<option @if($inventory_category->status == 0) selected="selected" @endif value="0">In-Active</option>
						</select>
						@endpermission
					</td>
					<td>
					@permission('item-category-edit')					
						<a data-id="{{$inventory_category->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					@endpermission
					@permission('item-category-delete')
						<a data-id="{{$inventory_category->id}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					@endpermission
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
		datatable = $('#datatable').DataTable({"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]]});
		$('.crud_modal').modal('hide');

		alert_message(message, "success");
		
	}

	$(document).ready(function() {


	datatable = $('#datatable').DataTable({"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]]});

	

	$('.add').on('click', function(e) {

        e.preventDefault();
        $.get("{{ route('category.create', [$category]) }}", function(data) {

        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').modal('show');


	});

	$('body').on('click', '.edit', function(e) {
        e.preventDefault();
        $.get("{{ url('inventory/category') }}/{{$category}}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });

        $('.crud_modal').modal('show');
	});


	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});


	$('body').on('click', '.multidelete', function() {
	var url = "{{ route('category.multidestroy') }}";
	multidelete($(this), url);
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('category.multiapprove') }}";
		active_status($(this), $(this).data('value'), url);
	});




		$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var current = $(this);
			$.ajax({
			 url: "{{ route('category.status') }}",
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



		$('body').on('click', '.delete', function(){
		
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = '{{ route('category.destroy') }}';
			delete_row(id, parent, delete_url);
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
								datatable = $('#datatable').DataTable({"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]]});
								$('.delete_modal_ajax').modal('hide');
								alert_message(data.message, "success");
							},
						 error:function(jqXHR, textStatus, errorThrown) {
							}
						});
			    });
		    }

function multidelete(obj, url) {
	var values = [];
	obj.closest(".table_container").find('tbody tr').each(function() {
		var value = $(this).find("td:first").find("input:checked").val();
		if(value != undefined) {
			values.push(value);
		}
	});
	$('.delete_modal_ajax').modal('show');
	$('.delete_modal_ajax_btn').off().on('click', function() {
		$.ajax({
			url: url,
			type: 'post',
			data: {
				_method: 'delete',
				_token: '{{ csrf_token() }}',
				id: values.join(",")
			},
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				datatable.destroy();
				var list = data.data.list;
				for(var i in list) {
					$("input.item_check[value="+list[i]+"]").closest('tr').remove();
				}
				$(obj).closest('.batch_container').hide();
				$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
				$("input.item_check, input[name=check_all]").prop('checked', false);
				datatable = $('#datatable').DataTable({"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]]});
				$('.delete_modal_ajax').modal('hide');
			},
			error: function(jqXHR, textStatus, errorThrown) {}
		});
	});
}






function active_status(obj, status, url) {
	var values = [];
	obj.closest(".table_container").find('tbody tr').each(function() {
		var value = $(this).find("td:first").find("input:checked").val();
		if(value != undefined) {
			values.push(value);
		}
	});
	$.ajax({
			url: url,
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				id: values.join(","),
				status: status
			},
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				datatable.destroy();
				var list = data.data.list;
				for(var i in list) {
					if(status == 1) {
						$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-warning');
						$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-success');
					}else if(status == 0) {
						$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').removeClass('badge-success');
						$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').addClass('badge-warning');
					}
					

					var active_text = $("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').closest('td').find('select').find('option[value="'+status+'"]').text();
					$("input.item_check[value="+list[i]+"]").closest('tr').find('label.status').text(active_text);
				}
				$(obj).closest('.batch_container').hide();
				$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
				$("input.item_check, input[name=check_all]").prop('checked', false);
				datatable = $('#datatable').DataTable({"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]]});
			},
			error: function(jqXHR, textStatus, errorThrown) {}
		});
}



	});
	</script>
@stop