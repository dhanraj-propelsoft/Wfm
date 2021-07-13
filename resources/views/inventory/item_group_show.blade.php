@extends('layouts.master')
@section('head_links') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.inventory')
@section('content')
<div class="row">
  <div class="col-md-3">
	<ul class="list-unstyled profile-nav">
	  <li style="position:relative"> <img width="100%" src="{{ URL::to('/') }}/public/image_not_available.png" class="img-responsive" alt="Employee Image"/> <a id="change_photo" class="change_image">Change Photo</a> 
	    {!! Form::open(['method' => 'POST','class' => 'profile-edit','files' => true]) !!}
		<div id="photo_container" class="row" style="display:none">
		  <label class="upload">
			<input type="file" name="employee_image" />
			<input type="hidden" name="employee_id" value="" />
			<span>Upload</span> </label>
		</div>
		{!! Form::close() !!}
	  </li>
	</ul>
  </div>
  <div class="col-md-9">
	<div class="col-md-9">
	  <div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group">
			<h3> {{ $item->name }} </h3>
		  </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group">
			<h5> <strong>Category:</strong> {{ $item->category_name }}</h5>
		  </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group"> <strong>In Stock:</strong> {{ $item->in_stock }} </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group">
			<table style="border-collapse: collapse;" class="table table-bordered">
			  <thead>
				<tr>
				  <th>Sale Price</th>
				  <th>Active From</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
			  
			  @foreach($sale_price_data as $data)
			  <tr>
				<td class= "sale">{{App\Custom::two_decimal($data['sale_price'])}}</td>
				<td class="date">{{$data['on_date']}}</td>
				<td><a data-price="{{$data['sale_price']}}" data-date= "{{$data['on_date']}}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a></td>
			  </tr>
			  @endforeach
				</tbody>
			  
			</table>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script type="text/javascript">
  $(document).ready(function() {

	  basic_functions();

	  $('body').on('click', '.delete', function() {

		  var price = $(this).data('price');
		  var date = $(this).data('date');

		  var obj = $(this);
		  $.ajax({
			  url: "{{ route('item.data_remove') }}",
			  type: 'post',
			  data: {
				  _token: '{{csrf_token()}}',
				  id: "{{$item->id}}",
				  sale_price: price,
				  on_date: date,
			  },
			  dataType: "json",
			  success: function(data, textStatus, jqXHR) {
				  if (data.status == 1) {
					  obj.closest('tr').remove();
				  }
			  }
		  });
	  });
  });
</script> 
@stop 