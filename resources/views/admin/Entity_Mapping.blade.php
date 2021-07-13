
@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.admin')
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
  <h4 class="float-left page-title">Entity Mappings</h4>
  
	<a class="btn btn-danger float-right excel_export" style="color: #fff">Export to Excel</a>

</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			@permission('department-delete')
				<li><a class="multidelete">Delete</a></li>
			@endpermission
			@permission('department-edit')
				<li><a data-value="1" class="multiapprove">Make Active</a></li>
				<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
			@endpermission
		</ul>
	</div>
	<div class="float-right" style="width: 100%; padding-top: 10px">
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>

	  <tr>
	  <th> Propel ID</th>
		<th>Person Name </th> 
		<th> Person Phonenumber</th>
		<th> People Name</th>
		<th> People Phonenumber</th>
		<th>User Name </th>
		<th>User Phone Number</th>
		<th>Status</th>
		<th>User Last Login</th>

	  </tr>
	</thead>
	<tbody>
		 @foreach($mapping as $mappings)
		<tr>
			
		 <td >{{ $mappings->propel_id }}</td>
		  	<td>{{ $mappings->person_name }}</td>
		  	<td>{{ $mappings->person_mobile }}</td>
		  	<td>{{ $mappings->people_name }}</td>
		  	<td>{{ $mappings->people_mobile }}</td>
		  	<td>{{ $mappings->user_name }}</td>
		  	<td>{{ $mappings->user_mobile }}</td>
		  	<td>@if($mappings->status == 1)
			  		<label class="grid_label badge badge-success ">Active</label>
				@elseif($mappings->status == '2')
			  		
			  		<label class="grid_label badge badge-warning ">In-Active</label>
				@endif</td>
			<td></td>
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
		$.get("{{ route('category_create') }}", function(data) {
		  $('.crud_modal .modal-container').html("");
		  $('.crud_modal .modal-container').html(data);
		});
		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
		$('.crud_modal').modal('show');
  });
   $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        isFirstIteration = true;
         $.get("{{ url('admin/category') }}/"+$(this).data('id')+"/edit", function(data) {
        	$('.crud_modal .modal-container').html("");
        	$('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').modal('show');
	});
$('body').on('click', '.status', function(e) {
		$(this).hide();
		$(this).parent().find('select').css('display', 'block');
  	});


  $('body').on('change', '.active_status', function(e) {
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var url = "{{ route('category.status1') }}";
			var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
			console.log(data);
			// $("#expire_"+id).text(data.expire_on);
		});

// $('.edit').on('click', function(e) {
// 		e.preventDefault();
// 		isFirstIteration = true;
// 			var id = $(".edit").val();
// 			if(id){


// 		$.get("{{ url('admin/category') }}/"+id+"/edit", function(data) {
//   $('.crud_modal .modal-container').html("");
// 		  $('.crud_modal .modal-container').html(data);
// 		});
// 		//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
// 		$('.crud_modal').modal('show');
		
// 			}
//   });
  // 	$('body').on('click', '.edit', function(e) {
		// e.preventDefault();
		// $.get("{{ url('hrm/departments') }}/"+$(this).data('id')+"/edit", function(data) {
		//   $('.crud_modal .modal-container').html("");
		//   $('.crud_modal .modal-container').html(data);
		// });
		// $('.crud_modal').modal('show');
  // 	});

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


  });
  </script>
@stop