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
  <h4 class="float-left page-title">GST HSN CODE</h4>
 
	
</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<div class="batch_container">
		<div class="batch_action"><i class="fa icon-arrows-slide-down1 arrow"></i>Batch Actions <i class="fa fa-caret-down "></i>
		</div>
		<ul class="batch_list">
			
			@permission('department-edit')
				<li><a data-value="1" class="multiapprove">Make Active</a></li>
				<li><a data-value="0" class="multiapprove">Make In-Active</a></li>
			@endpermission
		</ul>
	</div>
	<div class="col-md-2" style="margin-left:auto;margin-right:auto">
		<div class="row">
		<input type="text" name="code" class="code">
		<button style=" height:30px; border-radius: 5.5px" type="submit" class="date btn btn-success filter_data"><i class="fa fa-search" style="margin: 2px"></i></button>
		</div>
	</div>
<table id="datatable" class="table data_table" width="100%" cellspacing="0">
	<thead>
	  <tr>

	  	<th> Code </th> 
		<th >Chapter </th> 
		<th col width="400"> Chapter Name </th>
		<th col width="400"> Description </th>
		<th col width="400"> tax </th>
		<th>Edit</th>
				
	  </tr>
	</thead>
	<tbody>
	 
		<tr>
			
			<td></td>
			<td></td>
			<td width="30%"></td>
			<td width="30%"></td>
			<td width="30%"></td>
			<td>
              <a data-id="" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
			</td>		
		</tr>
	
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

   var datatable_options = {"order": [[1, "asc"]], "stateSave": true};

  $(document).ready(function() {

	datatable = $('#datatable').DataTable(datatable_options);

  

  	$('body').on('click', '.edit', function(e) {
		e.preventDefault();
		$.get("{{ url('admin/gst_edit') }}/"+$(this).data('id')+"/edit", function(data) {
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
			var url = "{{ route('organization.status') }}";
			var data = change_status(id, obj, status, url, "{{ csrf_token() }}");
		
			// $("#expire_"+id).text(data.expire_on);
		});
	$('body').on('click','.filter_data', function(e) {
		e.preventDefault();
		var code = $('.code').val();
			$.ajax({
                 url:"{{url('admin/code_search')}}",                       
                 type: "POST",                
                 data:{ "_token": "{{ csrf_token() }}",code:code},
                 success:function(data, textStatus, jqXHR)
                  {
                  		
                  $('#datatable tbody').empty();
                     var gst_data=data.gst;
                     var message="filtered";
                    
                    html=``;
                  for(var i in gst_data)
                    {                 	
                    	var rate=gst_data[i].rate;
						var tax;
						if(rate==null){
							tax="null";
						}else{
							tax=gst_data[i].rate+"%";
						}
                  	html+=`<tr>
                                
                    <td>`+gst_data[i].code+`</td>
                    <td>`+gst_data[i].chapter+`</td>
                     <td>`+gst_data[i].chapter_name+`</td>
                    <td>`+gst_data[i].description+`</td>
                    <td>`+tax+`</td>
                    <td><a data-id="`+gst_data[i].id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a></td>
                    
                    </tr>`;
				 }
				call_back_optional(html,`add`,``);

				}
             });

  });
	 });
  </script>
@stop