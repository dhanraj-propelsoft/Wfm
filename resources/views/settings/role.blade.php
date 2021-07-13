@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.settings')
@section('content')

<div class="alert alert-success">
	{{ Session::get('flash_message') }}
</div>
<div class="alert alert-danger">
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
		  <h4 class="float-left page-title">Roles</h4>
		  
			<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
		<a class="btn btn-danger float-right role_copy" style="display:none;color: #fff;margin-right: 10px;">Copy Role</a>
		  
		</div>




<div class="float-left" style="width: 100%; padding-top: 10px">
			<table id="datatable" class="table data_table table-hover" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th></th>
				<th> Role </th>	
				<th> Display Name </th>
				<th> Description </th>
				<th> Action </th>
			</tr>
		</thead>
		<tbody>
		@foreach($roles as $role)
			<tr>
				<td width="1">
					{{ Form::checkbox('role_id',$role->id, null, ['id' => $role->id, 'data-id' => $role->id,'class' => 'role_id']) }}<label for="{{$role->id}}"><span></span></label>
				</td>
				<td>{{ $role->name }}</td>
				<td>{{ $role->display_name }}</td>		
				<td>{{ $role->description }}</td>
				<td>
					
					<a data-id="{{ $role->id }}" class="grid_label action-btn view-icon"><i class="fa fa-eye"></i></a> 
								
					<a data-id="{{ $role->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a> 
					<a data-id="{{ $role->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 
					
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

   var datatable_options = {"stateSave": true};


	$(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

	$('.add').on('click', function(e) {
		e.preventDefault(); 
		$('.loader_wall_onspot').show();
		$('body').css('overflow', 'hidden');
		$('.full_modal_content').animate({ height: 'auto' }, 400, function() {
			$.get("{{ route('roles.create') }}", function(data) {
				$('.full_modal_content').show();
				$('.full_modal_content').html("");
				$('.full_modal_content').html(data);
				$('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
		        $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
				$('.loader_wall_onspot').hide();
			});
		});
	});

	$('body').on('click', '.edit', function(e) {
		var id = $(this).data('id');
		e.preventDefault(); 
		$('.loader_wall_onspot').show();
		$('body').css('overflow', 'hidden');
		$('.full_modal_content').animate({ height: 'auto' }, 400, function() {
			$.get("{{ url('roles') }}/"+id+"/edit", function(data) {
			  $('.full_modal_content').show();
			  $('.full_modal_content').html("");
			  $('.full_modal_content').html(data);
			  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
		        $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
				$('.loader_wall_onspot').hide();
			});
		});
	});


	$('body').on('click', '.delete', function(){
		var id = $(this).data('id');
		var parent = $(this).closest('tr');
		var delete_url = '{{ route('roles.destroy') }}';
		delete_row(id, parent, delete_url, "{{ csrf_token() }}");
   });

$('body').on('click','.role_copy',function(e){
		
		var id = $('input[name=role_id]:checked').data('id');
		$.ajax({
				 url: "{{ route('role_check') }}",
				 type: 'Post',
				 data: {
					_token :$('input[name=_token]').val(),
					role_id: id,
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
					if(data.status == true){
						
						alert_message("Role is already exists!!!", "error");
					}
					else{
						check_if(id)
					}
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		
	function check_if($id){
	  	var id=$id;
		 e.preventDefault(); 
		$('.loader_wall_onspot').show();
		 $('body').css('overflow', 'hidden');
		$('.full_modal_content').animate({ height: 'auto' }, 400, function() {
			$.get("{{ url('roles') }}/"+id+"/copy", function(data) {
			  $('.full_modal_content').show();
			  $('.full_modal_content').html("");
			  $('.full_modal_content').html(data);
			   $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
		        $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
				$('.loader_wall_onspot').hide();
			});
		});
		}
	});
	$('body').on('click','.role_id',function()
	{
		 if($(this).prop("checked") == true){
        	$('.role_copy').show();
        }
        else if($(this).prop("checked") == false){
        	$('.role_copy').hide();
        }
		
	 
	
 });


	/*$('body').on('click','#role_id',function()
	{
		//alert();
		//var id = $(this).data('id');
		//alert(id);
		$('.role_copy').removeProp("display");
		$('.role_copy').css('display','block');
	});


	$('body').on('click','.role_copy',function(e){
			
		var id = $('input[name=role_id]:checked').data('id');
		//alert(id);
		e.preventDefault(); 
		$('.loader_wall_onspot').show();
		$('body').css('overflow', 'hidden');
		$('.full_modal_content').animate({ height: 'auto' }, 400, function() {
			$.get("{{ url('roles') }}/"+id+"/copy", function(data) {
			  $('.full_modal_content').show();
			  $('.full_modal_content').html("");
			  $('.full_modal_content').html(data);
			  $('.full_modal_content form').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });
		        $('.full_modal_content').css({ top: $("#page-header").height()+'px', left: ($('#sidebar-menu').outerWidth())+'px', width: ($(window).outerWidth()-$('#sidebar-menu').outerWidth()) +'px' });
				$('.loader_wall_onspot').hide();
			});
		});

	});*/

	});
	</script>
@stop