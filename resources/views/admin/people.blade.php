@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')

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
  <h4 class="float-left page-title">People</h4>

	<a class="btn btn-danger float-right excel_export" style="color: #fff">Export to Excel</a>
  
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	
	<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  	<tr>
		  	<th> PropelD </th> 
			<th> Name </th>
			<th> Modules </th>
			<th> Type </th>
			<th> Started From </th>
			<th> Next Expiry</th>
			<th>Pending Amount </th>
			<th> Action </th>
			<th></th>
	  	</tr>
	</thead>
	<tbody>
		  @foreach($peoples as $people)
	<tr>
		
		<td>{{ $people->propel_id}}</td>
		<td>{{ $people->display_name }}</td>
		<td>@if(!empty($people->store()))

				@foreach($people->store() as $value => $key)

					<label class="grid_label badge badge-success">{{ $key['display_name'] }}</label>

				@endforeach

			@endif</td>
		<td>
				@if($people->is_active == 1)
			  		<label class="grid_label badge badge-primary status">Paid</label>
				@elseif($people->is_active == '0')
			  		<label class="grid_label badge badge-warning status">Free</label>
				@endif
			  </td>
		
		<td>{{ $people->started_date }}</td>
		  	<td id="expire_{{ $people->id }}">{{ $people->expire_on }}</td>
		  	<td></td>
		  			  <td>
					@if($people->status == 0)
						<label class="grid_label badge badge-warning status">InActive</label>
					@elseif($people->status ==1)
						<label class="grid_label badge badge-success status">Extend
						</label>
					@endif

					<select style="display:none" id="{{ $people->id }}" class="active_status form-control">
					<option @if($people->status == 1) selected="selected" @endif value="1">Extend</option>
					<option @if($people->status == 0) selected="selected" @endif value="0">In-Active</option>
					<!-- <option @if($people->status == 2) selected="selected" @endif value="2">Active</option>
					 -->
					</select>
				</td>
				 <td>
                	<a data-id="{{ $people->id}}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
					
				       
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


     var isFirstIteration = true;        

     var datatable_options = {"pageLength": 10, "columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [],

        dom: 'lBfrtip',
        buttons: [
            
           
            {
                extend: 'excel',
                exportOptions: {
                     columns: [0,1,2,3,4,5,6]
                },
                footer: false
            },
           
        ]


    };


  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);
	$('.buttons-excel').css('display','none');
	
	$('body').on('click', '.excel_export', function(){
        $(".buttons-excel")[0].click(); //trigger the click event
    });

 	$('.add').on('click', function(e) {
		e.preventDefault();
		$.get("{{ route('hrm_departments.create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('hrm/departments') }}/"+$(this).data('id')+"/edit", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		$('.crud_modal').modal('show');
  	});

  	$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});

  	$('body').on('click', '.delete', function(){
	var id = $(this).data('id');
	var parent = $(this).closest('tr');
	var delete_url = '{{ route('hrm_departments.destroy') }}';
	delete_row(id, parent, delete_url);
   	});

	$('body').on('click', '.multidelete', function() {
		var url = "{{ route('hrm_departments.multidestroy') }}";
		multidelete($(this), url);
	});

	$('body').on('click', '.multiapprove', function() {
		var url = "{{ route('hrm_departments.multiapprove') }}";
		multi_status($(this), $(this).data('value'), url);
	});

	$('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('people.status') }}";
			var data=change_status(id, obj, status, url, "{{ csrf_token() }}");
			
	});

  });

  </script>
@stop
