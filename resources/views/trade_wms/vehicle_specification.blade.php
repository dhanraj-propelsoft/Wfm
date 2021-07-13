@extends('layouts.master')
@section('head_links') @parent
 <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<style>
 
        .table td
        {
            padding: 2px;
        }
        body
        {
            font-size: 12px !important;
        }
        .btn
        {
            line-height: 1;
        }
    
</style>    
@stop

@if(Session::get('module_name') == "trade_wms")
	@include('includes.trade_wms')
@else
	@include('includes.inventory')
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
<div class="alert alert-danger danger">
	<p>No more then 2 pricing allowed</p>
</div>

<div class="fill header" style="height:43px;width: 102%;background-color: #e3e3e9;margin-left: -10px;">
	<h5 class="float-left page-title" style="padding-top: 8px;padding-left: 10px;"><b>Vehicle Specification List</b></h5>
		<!-- <div class="btn-group float-right">
	<a class="btn btn-danger float-left refresh" style="color: #fff">Update</a>
</div>  -->

</div>

<div class="float-left table_container" style="width: 100%; padding-top: 10px;">
	<table id="example" class="table data_table" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th> {{ Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all'] ) }} <label for="check_all"><span></span></label></th> 
				<th> Specification</th>          
				<th> Type </th>
				<th> Description </th>
				<th> Used</th>
				<th> Pricing </th>       
			</tr>
		</thead>
		<tbody>
			@foreach($specifications as $specification) 
			<tr>
				<td width="1" style="padding-left: 7px;">{{ Form::checkbox('specification',$specification->id, null, ['id' => $specification->id, 'class' => 'item_checkbox']) }}<label for="{{$specification->id}}"><span></span></label></td>   
				<td>{{$specification->name}}</td>   
				<td>{{$specification->type}}</td>
				<td>{{$specification->description}}</td>
				<td>@if($specification->used == '1')
						<label class="grid_label badge badge-success used">YES</label>
					@elseif($specification->used == '0')
					  <label class="grid_label badge badge-warning used">NO</label>
					  @elseif($specification->used == '')
					  <label class="grid_label badge badge-warning used">NO</label>
					  @endif
					<select style="display:none" id="{{ $specification->id }}" class="active_status form-control spec_used" name="used" data-id="{{ $specification->spec_id }}">
						<option @if($specification->used == 1) selected="selected" @endif value="1">YES</option>
						<option @if($specification->used == 0) selected="selected" @endif value="0">NO</option>
					</select>
				</td>
				<td>@if($specification->pricing == '1')
						<label class="grid_label badge badge-success pricing">YES</label>
					@elseif($specification->pricing == '0')
					  <label class="grid_label badge badge-warning pricing">NO</label>
					  @elseif($specification->pricing == '')
					  <label class="grid_label badge badge-warning pricing">NO</label>
					  @endif
					
					<select style="display:none" id="{{ $specification->id }}" class="pricing_status form-control" name="pricing" data-id="{{ $specification->spec_id }}" data-name="{{$specification->type_id}}">
						<option @if($specification->pricing == 1) selected="selected" @endif value="1">YES</option>
						<option @if($specification->pricing == 0) selected="selected" @endif value="0">NO</option>
					</select></td>


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
<!-- <script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script> -->

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/jquery.dataTables.js') }}"></script>



<script type="text/javascript">
	var datatable = null;

	var datatable_options = {"columnDefs": [{"orderable": false,"targets": [0, -1]}],"order": [[1, "asc"]], "stateSave": true};

	$(document).ready(function() {
		var table = $('#example').DataTable();
         $("#example thead th").each( function ( i ) {
         if (i == 1 ) {
        var select = $('<select><option value="">Specification</option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                    .search( $(this).val() )
                    .draw();
            } );
 
        table.column( i ).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
    }
      if (i == 2 ) {
        var select = $('<select><option value="">Type</option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                    .search( $(this).val() )
                    .draw();
            } );
 
        table.column( i ).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
    }
    } );

      /* $('.refresh').click(function() {
           location.reload();
            });*/


       $('body').on('click', '.used', function(e) {
			$(this).hide();
			$(this).parent().find('select').css('display', 'block');
		});

    /*$('body').on('click', '.pricing', function(e) {
			$(this).hide();
			$(this).parent().find('select').css('display', 'block');
		});*/



       $('body').on('change', '.active_status', function(e) {
			e.preventDefault();
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var spec_details_id =  $(this).attr('data-id');
			var type_id = $(this).attr('data-name');
			var url = "{{ route('specification.store') }}";
			change_activestatus(id, obj, status, url,spec_details_id,type_id,"{{ csrf_token() }}");

		});

       $('body').on('change', '.pricing_status', function(e) {
			e.preventDefault();
			var status = $(this).val();
			var id = $(this).attr('id');
			var obj = $(this);
			var spec_details_id =  $(this).attr('data-id');
			var type_id = $(this).attr('data-name');
			var url = "{{ route('specification.pricing_update') }}";
			change_activestatus(id, obj, status, url,spec_details_id,type_id,"{{ csrf_token() }}");

		});
        
        /*$('body').on('click', '.pricing', function(e) {
        	  e.preventDefault();
        	  var id = $(this).closest('tr').find('select[name=used]').val();
        	  var count = $("body").find(".pricing:contains('YES')").length;
        	  if(id == 0){
              $(this).hide();
              $('.alert-danger').text('The Specification is not Used');
              $('.alert-danger').show();
        	  }
        	   if(count >= 2){
        	  	   $(this).hide();
                   $('.alert').show();
        	  }else{
        	  	$(this).hide();
			    $(this).parent().find('select').css('display', 'block');
        	  }
        });*/


$('body').on('click','.pricing',function(e){
 e.preventDefault();
 var id = $(this).closest('tr').find('select[name=used]').val();
 if(id == 0){
              $('.pricing').attr('disabled','disabled');
              $('.alert-danger').text('The Specification is not Used');
              $('.alert-danger').show();
        	  }else{
        	  	$(this).hide();
			    $(this).parent().find('select').css('display', 'block');
        	  }
});
var count = $("body").find(".pricing:contains('YES')").length;
if(count >= 2){
	          $("body").find(".pricing:contains('NO')").attr('disabled','disabled');
              $('.alert-danger').text('No More Then 2 Pricing Used');
              $('.alert-danger').show();
}else{
	$(this).hide();
			    $(this).parent().find('select').css('display', 'block');
}
function change_activestatus(id, obj, status, url,spec_details_id,type_id,token) {

		$.ajax({
			 url: url,
			 type: 'post',
			 data: {
				id: id,
				type_id:type_id,
				spec_details_id:spec_details_id,
				_token :token,
				status: status
				},
			 dataType: "json",
				success:function(data, textStatus, jqXHR) {
					if(status == 0) {
						obj.parent().find('label').removeClass('badge-success');
						obj.parent().find('label').addClass('badge-warning');
					}else if(status == 1) {
						obj.parent().find('label').removeClass('badge-warning');
						obj.parent().find('label').addClass('badge-success');
					}
					obj.hide();
					obj.parent().find('label').show();
					obj.parent().find('label').text(obj.find('option:selected').text());
				},
			 error:function(jqXHR, textStatus, errorThrown) {
				}
			});
	}
});
</script>
@stop