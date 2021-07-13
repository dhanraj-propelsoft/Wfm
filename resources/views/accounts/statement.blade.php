@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

	@stop

@include('includes.accounts')
@section('content')
@include('includes.add_user')
@include('includes.add_business')
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
	<h4 class="float-left page-title">Statement of Accounts </h4>
	<button class="btn btn-primary float-right pdf_generation button">Generate PDF</button>

	@permission('ledger-create')
		<a class="btn btn-danger float-right add transaction_change master_add" style="color: #fff">+ New</a>
	@endpermission

	@permission('ledger-approval')
		@if(isset($settings))
			<div style=" height: 20px; margin:5px 10px 0 0;" class="pull-right">
				<span class="tooltips cursor-help" style="font-size:13px; pointer:" data-container="body" data-placement="top" data-original-title="Automatically approve ledger.">Auto Approval </span>

				<input name="approval" data-checkbox="{{$settings->id}}" class="make-switch" <?php if($settings->status == "1") { echo 'checked="true"'; } else { echo 'checked="false"'; } ?> data-size="mini" type="checkbox">
			</div>
		@endif
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
	<table id="datatable" class="table group_table table-hover generate_pdf" width="100%" cellspacing="0">
		<thead>
			<tr>
      			<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th>					
				<th> Ledger </th>
				<th> Type </th>
				<th> Closing Balance </th>
			</tr>
		</thead>
		<tbody>
		@foreach($account_ledgers as $ledger)
			<tr>
				<td width="1"> @if($ledger->delete_status != 0) 
					{{ Form::checkbox('ledger',$ledger->id, null, ['id' => $ledger->id, 'class' => 'item_check']) }}<label for="{{$ledger->id}}"><span></span></label>
				@endif
				</td>
				<td>
					<a href="{{ url('accounts/ledger') }}/{{$ledger->id}}/{{$ledger->parent}}">{{ $ledger->ledger}}</a>
				</td>
				<td>{{ $ledger->ledger_group_name }}</td>					
				
				<td><span class="removeSign">{{ $ledger->closing_balance }}</span> {{ $ledger->balance_type }}</td> 

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

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('assets/plugins/jspdf.autotable.min.js') }}"></script> 

   <script type="text/javascript">
   var datatable = null;

	function call_back(data, modal, message, id = null) {
		datatable.destroy();
		if($('.edit[data-id="' + id + '"]')) {
			$('.edit[data-id="' + id + '"]').closest('tr').remove();
		}
		$('.group_table tbody').prepend(data);
		datatable = $('#datatable').DataTable({"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[2, "asc"], [1, "asc"]], "stateSave": true});
		$('.crud_modal').modal('hide');

		alert_message(message, "success");
	}

	$(document).ready(function() {

		$('.pdf_generation').click(function () {

			/*Table format - pdf , Automatically splitted in multiple pages*/
			$('.loader_wall_onspot').show();

			var pdf = new jsPDF('p', 'pt', 'a4');
			pdf.autoTable({html: '.generate_pdf'});
			pdf.save('Statement.pdf');

			$('.loader_wall_onspot').hide();
			/*End*/		
			
		});

	datatable = $('#datatable').DataTable({"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[2, "asc"], [1, "asc"]], "stateSave": true});

	$('.add').on('click', function(e) {
	    e.preventDefault(); 
	    $('.loader_wall_onspot').show();
	    $('body').css('overflow', 'hidden');
	    $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
	     $.get("{{ route('ledgers.create') }}", function(data) {
	        $('.full_modal_content').show();
	        $('.full_modal_content').html("");
	        $('.full_modal_content').html(data);$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
	        $('.loader_wall_onspot').hide();
	      });
	    });
	  });

	$('body').on('click', '.edit', function(e) {
	    e.preventDefault(); 
	    var id = $(this).data('id');
	    $('.loader_wall_onspot').show();
	    $('body').css('overflow', 'hidden');
	    $('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {
	     $.get("{{ url('accounts/ledgers') }}/"+id+"/edit", function(data) {
	        $('.full_modal_content').show();
	        $('.full_modal_content').html("");
	        $('.full_modal_content').html(data);$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
	        $('.loader_wall_onspot').hide();
	      });
	    });
	  });

@permission('ledger-approval')
	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
	});
@endpermission	

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('ledgers.multidestroy') }}";
		multidelete($(this), url);
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('ledgers.multiapprove') }}";
		active_status($(this), $(this).data('value'), url);
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
			var current = $(this);
			$.ajax({
			 url: "{{ route('change_ledger_status') }}",
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

		$('body').on('change', '.approval_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var current = $(this);
			$.ajax({
			 url: "{{ route('change_ledger_approval_status') }}",
			 type: 'post',
			 data: {
				id: id,
				_token :"{{ csrf_token() }}",
				status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					if(status == 0) {
						current.parent().find('label').removeClass('badge-info');
						current.parent().find('label').addClass('badge-warning');
					}else if(status == 1) {
						current.parent().find('label').removeClass('badge-warning');
						current.parent().find('label').addClass('badge-info');

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
			var delete_url = '{{ route('ledgers.destroy') }}';
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
								datatable = datatable = $('#datatable').DataTable({"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[2, "asc"], [1, "asc"]], "stateSave": true});
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
						datatable = datatable = $('#datatable').DataTable({"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[2, "asc"], [1, "asc"]], "stateSave": true});
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
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.mainstatus').removeClass('badge-warning');
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.mainstatus').addClass('badge-success');
						}else if(status == 0) {
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.mainstatus').removeClass('badge-success');
							$("input.item_check[value="+list[i]+"]").closest('tr').find('label.mainstatus').addClass('badge-warning');
						}					

						var active_text = $("input.item_check[value="+list[i]+"]").closest('tr').find('label.mainstatus').closest('td').find('select').find('option[value="'+status+'"]').text();
						$("input.item_check[value="+list[i]+"]").closest('tr').find('label.mainstatus').text(active_text);
					}
					$(obj).closest('.batch_container').hide();
					$('#datatable').find('thead tr th:first :checkbox').prop('indeterminate', false);
					$("input.item_check, input[name=check_all]").prop('checked', false);
					datatable = datatable = $('#datatable').DataTable({"pageLength": 100, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[2, "asc"], [1, "asc"]], "stateSave": true});
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});
		}
	});
	</script>
@stop