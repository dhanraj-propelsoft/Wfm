@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.accounts')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Ledger Groups</h4>
  @permission('ledger-group-create') <a class="btn btn-danger float-right add transaction_change master_add" style="color: #fff">+ New</a> @endpermission
  
  @permission('ledger-group-approval')
  @if(isset($settings))
  <div style=" height: 20px; margin:5px 10px 0 0;" class="pull-right" ><span class="tooltips cursor-help" style="font-size:13px; pointer:" data-container="body" data-placement="top" data-original-title="Automatically approve ledger.">Auto Approval</span>
    <input name="approval" data-checkbox="{{$settings->id}}" class="make-switch" <?php if($settings->status == "1") { echo 'checked="true"'; } else { echo 'checked="false"'; } ?> data-size="mini" type="checkbox">
  </div>
  @endif
  @endpermission </div>
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
        <th> Group </th>
        <th> Account Head </th>
        <th> Approve Status </th>
        <th> Status </th>
        <th> Action </th>
      </tr>
    </thead>
    <tbody>
    
    @foreach($ledger_groups as $ledger_group)
    <tr>
      <td width="1">  @if($ledger_group->delete_status != 0) {{ Form::checkbox('ledger_group',$ledger_group->id, null, ['id' => $ledger_group->id, 'class' => 'item_check']) }}<label for="{{$ledger_group->id}}"><span></span></label>
		@endif
      </td>
      <td>{{ $ledger_group->display_name }}</td>
      <td>{{ $ledger_group->parent_group }}</td>
      
      <td> @if($ledger_group->delete_status == 0)
        <label class="grid_label badge badge-info">Approved</label>
        @endif
        
        @if($ledger_group->delete_status == 1)
        @if($ledger_group->approval_status == 1)
        <label class="grid_label badge badge-info status">Approved</label>
        @elseif($ledger_group->approval_status == 0)
        <label class="grid_label badge badge-warning status">Not Approved</label>
        @endif

        @permission('ledger-group-approval')
        <select style="display:none" id="{{ $ledger_group->id }}" class="approval_status form-control">
          <option @if($ledger_group->approval_status == 1) selected="selected" @endif value="1">Approved</option>
          <option @if($ledger_group->approval_status == 0) selected="selected" @endif value="0">Not Approved</option>
        </select>
        @endif 
        @endpermission
        </td>
      <td> @if($ledger_group->delete_status == 0)
        <label class="grid_label badge badge-success">Active</label>
        @endif
        @if($ledger_group->delete_status == 1)
        @if($ledger_group->status == 1)
        <label class="grid_label badge badge-success status mainstatus">Active</label>
        @elseif($ledger_group->status == 0)
        <label class="grid_label badge badge-warning status mainstatus">In-Active</label>
        @endif
        <select style="display:none" id="{{ $ledger_group->id }}" class="active_status form-control">
          <option @if($ledger_group->status == 1) selected="selected" @endif value="1">Active</option>
          <option @if($ledger_group->status == 0) selected="selected" @endif value="0">In-Active</option>
        </select>
        @endif </td>
      <td> @if($ledger_group->delete_status == 0) <a class="grid_label action-btn disabled-icon" href=""> <i class="fa fa-ban"></i> </a> @endif
        @if($ledger_group->delete_status == 1) <a data-id="{{$ledger_group->id}}" class="grid_label action-btn edit-icon edit transaction_change"><i class="fa li_pen"></i></a> <a data-id="{{$ledger_group->id}}" class="grid_label action-btn delete-icon delete transaction_change"><i class="fa fa-trash-o"></i></a> @endif </td>
    </tr>
    @endforeach
      </tbody>
    
  </table>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 
<script type="text/javascript">
   var datatable = null;

   var datatable_options = {"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[2, "asc"], [1, "asc"]], "stateSave": true};


	$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

	$('.add').on('click', function(e) {
        e.preventDefault();
        $.get("{{ route('group.create') }}", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').modal('show');
	});

	$('body').on('click', '.edit', function(e) {
        e.preventDefault();
        $.get("{{ url('accounts/groups') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').modal('show');
	});

	@permission('ledger-group-approval')
	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});
	@endpermission

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('group.multidestroy') }}";
		multidelete($(this), url, "{{ csrf_token() }}");
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('group.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url, "{{ csrf_token() }}");
	});


	<?php if( isset($settings) && $settings->status == "1") { ?>
		$('input[name=approval]').bootstrapSwitch('state', true);
	<?php } else { ?>
		$('input[name=approval]').bootstrapSwitch('state', false);
	<?php } ?>

	$('input[name=approval]').on('switchChange.bootstrapSwitch', function (event, state) {
		var status;
		var id = $(this).data('checkbox');

		 if (state) {
		 	status = 1;
		 } else {
		 	status = 0;
		 }

	 	$.ajax({
			 url: "{{ route('ledgergroup_approval') }}",
			 type: 'post',
			 data: {
			 	id: id,
			 	_token :"{{ csrf_token() }}",
			 	status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					if(data.status == 1) {
						$('input[name=approval]').bootstrapSwitch('state', true);
					} else {
						$('input[name=approval]').bootstrapSwitch('state', false);
					}
				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});

	   event.preventDefault();
	});

		$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('group_change_status') }}";
			change_status(id, obj, status, url, "{{ csrf_token() }}");
		});

		$('body').on('change', '.approval_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			$.ajax({
			 url: "{{ route('change_approval_status') }}",
			 type: 'post',
			 data: {

			 	id: id,
			 	_token :"{{ csrf_token() }}",
			 	status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					if(status == 0) {
						obj.parent().find('label').removeClass('badge-info');
						obj.parent().find('label').addClass('badge-warning');
					}else if(status == 1) {
						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').addClass('badge-info');

						var notification_val = parseInt($('.notification_count_first').text());
						if(notification_val > 1) {
							$('.notification_count_first').text(notification_val - 1);
						} else {
							$('.notification_count_first').hide();
						}

						if($('#'+id+'.ledger_group_approve').length) {
			                $('#'+id+'.ledger_group_approve').closest('li').remove(); 
			            }
					}
					obj.hide();
					obj.parent().find('label').show();
					obj.parent().find('label').text(obj.find('option:selected').text());
				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		});


		$('body').on('click', '.delete', function(){
			var id = $(this).data('id');
			var parent = $(this).closest('tr');
			var delete_url = '{{ route('group.destroy') }}';
			delete_row(id, parent, delete_url, "{{ csrf_token() }}");
	   });


	});
	</script> 
@stop