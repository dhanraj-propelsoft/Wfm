@extends('layouts.master')

@section('head_links') @parent

@if(app()->environment() == "production")

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/css/jquery.dataTables.min.css">

@elseif(app()->environment() == "local")

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">

@endif

@stop

@include('includes.hrm')

@section('content')

@include('includes.add_user')



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

  <h4 class="float-left page-title">Employee</h4>

  @permission('employee-create')

	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>

  @endpermission

</div>



<div class="float-left" style="width: 100%; padding-top: 10px">

  <table id="datatable" class="table data_table" width="100%" cellspacing="0">

	<thead>

	  <tr>     

		<th>Employee Name</th>

		<th>Code</th>

		<th>Mobile No</th>

		<th>Email</th> 

		<th>Blood Group</th>

		<th>Gender</th>

		<th>Action</th>

	  </tr>

	</thead>



	<tbody>

	  @foreach($employees as $employee)

		<tr> 

		  <td>{{$employee->first_name}}</td>

		  <td>{{$employee->employee_code}}</td>

		  <td>{{$employee->phone_no}}</td>

		  <td>{{$employee->email}}</td>

		  <td>{{$employee->blood_group}}</td>

		  <td>{{$employee->gender}}</td>

		  <td>

		  	@permission('employee-show')

		  		<a href="{{route('employees.show', $employee->id)}}" data-id="{{$employee->id}}" class="grid_label action-btn show-icon show"><i class="fa li_eye"></i></a>

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

@if(app()->environment() == "production")

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js"></script> 

@elseif(app()->environment() == "local")

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script> 

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> 

@endif

   <script type="text/javascript">

   var datatable = null;



   var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};





  $(document).ready(function() {



 datatable = $('#datatable').DataTable(datatable_options);



  		$('.add').on('click', function(e) {



  			/* Check plan data limit*/



			/* addon_organization table - value(coloumn) data length */

			var addon_length=$(this).attr('data-id');



			/* list view row length is greater than or equal to value - false */

			var row_length=$('#datatable').find('tbody tr').length;



			

			if(addon_length)

			{

				if(row_length >= addon_length)

				{

					$('#error_dialog #title').text('Limit Exceeded!');

					$('#error_dialog #message').text('{{ config('constants.error.limit_exceed') }}');

					$('#error_dialog').modal('show');



					return false;

				}

			}



			/*End*/

			

			e.preventDefault(); 		

			$('.loader_wall_onspot').show();

			$('body').css('overflow', 'hidden');

			$('.full_modal_content').animate({ height: $(window).height() + 'px' }, 400, function() {

				$.get("{{ route('employees.create') }}", function(data) {

				  $('.full_modal_content').show();

				  $('.full_modal_content').html("");

				  $('.full_modal_content').html(data);

				  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

		          $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });

				  $('.loader_wall_onspot').hide();

				});

			});

		});







  $('body').on('click', '.status', function(e) {

	$(this).hide();

	$(this).parent().find('select').css('display', 'block');

  });



  $('body').on('change', '.active_status', function(e) {

		var status = $(this).val();

		var id = $(this).attr('id');

		var obj = $(this);

		var url = "{{ route('department_status_approval') }}";

		change_status(id, obj, status, url, "{{ csrf_token() }}");

	});



  $('body').on('click', '.delete', function(){

	var id = $(this).data('id');

	var parent = $(this).closest('tr');

	var delete_url = '{{ route('branches.destroy') }}';

	delete_row(id, parent, delete_url, "{{ csrf_token() }}");

   });



  });

  </script>

@stop