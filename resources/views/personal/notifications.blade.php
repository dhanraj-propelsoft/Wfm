@extends('layouts.master')
@section('head_links') @parent
@stop
@include('includes.personal_accounts')
@section('content')
<div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
@if($errors->any())
<div class="alert alert-danger"> @foreach($errors->all() as $error)
  <p>{{ $error }}</p>
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Notifications</h4>
</div>
<div class="clearfix"></div>

<div class="row">

  <div class="col-md-12">
	  <ul class="notifications">
	  @foreach($notifications as $notification)
		<li class="notification_list" style="@if($notification['status'] ==1) background: #fcef8d; @endif">
		<div class="label label-sm label-success">
			<i class="fa fa-bell-o"></i>
		</div>
		<div style="width: 100%; text-align: right; font-style: italic;  " class="date"> @if($notification['status'] == 1) {{$notification['time']}} @endif </div>
		<h5 style="float: left; padding-left: 10px;"> {{$notification['message']}} </h5><br>
		@if($notification['status'] != 1)
		<div style="float: right; margin-top: -15px;">
		<a class="grid_label badge badge-success" style="color: #fff;">Viewed</a>
		</div>

		@else
			
			<div style="float: right; margin-top: -15px;" class="action_dropdown">
			<a>Action</a>
			  <div>
				<ul>
				<li><a class="add_transaction @if($notification['type'] == 'income') add_income @elseif($notification['type'] == 'liability') add_liability @else add_expense @endif" data-id="{{$notification['id']}}" data-date="{{$notification['date']}}" data-total="{{$notification['amount']}}">Add&nbsp;to&nbsp;Transaction</a></li>
				</ul>
			  </div>
			</div>

		@endif 
		
		</li>
		@endforeach
	  </ul>
  </div>
</div>
@stop

@section('dom_links')
@parent 

<script type="text/javascript">
var current_id;
	function call_back(data, modal, message, id = null) {}

    function add_bill(id) {
		/*if($('.add_transaction[data-id="' + current_id + '"]')) {
			$('.add_transaction[data-id="' + current_id + '"]').closest('.notification_list').remove();
		}*/

		if($('.add_transaction[data-id="' + current_id + '"]')) {

			var action_dropdown = $('.add_transaction[data-id="' + current_id + '"]').closest('.action_dropdown');

			action_dropdown.closest('.notification_list').find('.date').remove();
			action_dropdown.html('<a class="grid_label badge badge-success" style="color: #fff;">Viewed</a>');
			action_dropdown.removeClass('action_dropdown');
			action_dropdown.closest('li').removeAttr('style');
		}

		$('.loader_wall_onspot').hide();
		$('.crud_modal').modal('hide');

		alert_message("Successfully added to transaction!", "success");
   }

	$(document).ready(function() {

		$('.discard').on('click', function() {
			var obj = $(this);
			var id = obj.attr('id');

			$.ajax({
				url: "{{route('discard_notifications')}}",
				type: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					id: id,
					status: '1',
				},
				success: function(data, textStatus, jqXHR) {
					obj.closest('li').slideUp();
					obj.closest('li').remove();
				},
				error: function(jqXHR, textStatus, errorThrown) {}
			});
		});


		 $('body').on('click', '.add_transaction', function(e) {
			e.preventDefault();
			current_id = $(this).data('id');

			var that = $(this);
			var transaction_type = "";

			 if(that.hasClass('add_income')) {
					transaction_type = "salary";
			  } else if(that.hasClass('add_expense') || that.hasClass('add_liability')) {
					transaction_type = "transaction";
			  }


			$.get("{{ url('user/personal/transaction/create/') }}/"+$(this).data('id')+"/"+transaction_type+"", function(data) {
			  $('.crud_modal .modal-container').html("");
			  $('.crud_modal .modal-container').html(data);
			  var transaction_category = $('.crud_modal .modal-container').find('select[name=transaction_category]');
			  

			  $('.crud_modal .modal-container').find('input[name=amount]').val(that.data('total'));
			  $('.crud_modal .modal-container').find('input[name=date]').val(that.data('date'));

			  if(that.hasClass('add_income')) {
				$('.crud_modal .modal-container').find('.modal-title').text("Add Income Transaction");
				$('.crud_modal .modal-container').find('.source').text("Source of Income");
				$('.crud_modal .modal-container').find('.account').text("Income Account");
					type = "income";
			  } else if(that.hasClass('add_expense')) {
				$('.crud_modal .modal-container').find('.modal-title').text("Add Expense Transaction");
				$('.crud_modal .modal-container').find('.source').html("Source&nbsp;of&nbsp;Expense");
				$('.crud_modal .modal-container').find('.account').text("Expense Account");
					type = "expense";
			  } else if(that.hasClass('add_liability')) {
				$('.crud_modal .modal-container').find('.modal-title').text("Add Liability Transaction");
				$('.crud_modal .modal-container').find('.source').html("Source&nbsp;of&nbsp;Expense");
				$('.crud_modal .modal-container').find('.account').text("Liability Account");
					type = "liability";
			  }
					
				$('.crud_modal .modal-container').find('input[name=transaction_type]').val(type);

			  $.ajax({
					url: '{{ route('get_personal_transaction_category') }}',
					type: 'post',
					data: {
						_token: '{{ csrf_token() }}',
						type: type,  
					},
					success:function(data, textStatus, jqXHR) {
						transaction_category.empty();
						transaction_category.append('<option value="">Select Category</option>');

						for(var i in data) {
							transaction_category.append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
						}
					}
				});

			});
			//$('.crud_modal').find('.modal-dialog').addClass('modal-lg');
			$('.crud_modal').modal('show');
	  });



	});

</script> 
@stop