@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop


	@include('includes.wfm_settings')


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
  	<h4 class="float-left page-title">Size</h4>
	@permission('discount-create')
		<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
	@endpermission
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
		<div class="batch_action">
			<i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down"></i>
		</div>
		<ul class="batch_list">
		<?php /* ?>	@permission('discount-delete')
		  		<li><a class="multidelete">Delete</a></li>
		  	@endpermission
		  	@permission('discount-edit')
			  	<li><a data-value="1" class="multiapprove">Make Active</a></li>
			  	<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
		  	@endpermission
		  	<?php */ ?>
		</ul>
	</div>
  	<table id="datatable" class="table data_table" width="100%" cellspacing="0">
        <thead>
            <tr>
        
						
				<th style="width:80%" >Name</th>			
				<th>Status</th>
				<th>Action</th>
            </tr>
        </thead>
        <tbody>
        	@if(isset($Sizes))
        @foreach($Sizes as $Size)
            <tr>
            	
              
						
				  <td>{{ $Size->size_name }}</td>
				<td>
					@if($Size->status == 1)
						<label class="grid_label badge badge-success status">Active</label>
					@elseif($Size->status == 0)
						<label class="grid_label badge badge-warning status">In-Active</label>
					@endif

					@permission('discount-edit')
						<select style="display:none" id="{{ $Size->id }}" class="active_status form-control">
							<option @if($Size->status == 1) selected="selected" @endif value="1">Active</option>
							<option @if($Size->status == 0) selected="selected" @endif value="0">In-Active</option>
						</select>
					@endpermission						
				</td>
                <td>
                	@permission('discount-edit')	
				        <a data-id="{{ $Size->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					@endpermission

					@permission('discount-delete')
						<a data-id="{{ $Size->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
					@endpermission
				</td>
            </tr>
         
        @endforeach
        @endif
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

   var datatable_options = {"columnDefs": [{"orderable": true,"targets": [0]}],"order": [[1, "asc"]], "stateSave": true};

	$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

	$('.add').on('click', function(e) {
        e.preventDefault();
        $.get("{{ route('size.create') }}", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        //$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
	});

	$('body').on('click', '.edit', function(e) {
        e.preventDefault();
        $.get("{{ url('wfm/size') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').modal('show');
	});

	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('size.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
   });

	$('body').on('click', '.status', function(e) {
        $(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('discount.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}");
  	});

  	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('discount.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
  	});

  	$('body').on('change', '.active_status', function(e) {
		var status = $(this).val();
		var id = $(this).attr('id');
		var obj = $(this);
		var url = "{{ route('size.status') }}";
		change_status(id, obj, status, url, "{{ csrf_token() }}");
	});

	});

	</script>
@stop