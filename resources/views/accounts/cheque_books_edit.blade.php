<div class="modal-header">
	<h4 class="modal-title float-right">Edit Cheque Book</h4>
</div>

	{!!Form::model($chequebooks, [
		'class' => 'form-horizontal validateform'
	]) !!}

	{{ csrf_field() }}

<div class="modal-body">
  <div class="form-body">
	  {!! Form::hidden('id', null) !!}
	<!-- <div class="form-group">
	  {!! Form::label('name', 'A/C Ledger Name', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('name', null,['class' => 'form-control']) !!}
	  </div>
	</div>
	<div class="form-group">
	  {!! Form::label('account_type', 'A/C Type', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('account_type', null,['class' => 'form-control']) !!}
	  </div>
	</div>
	<div class="form-group">
	  {!! Form::label('account_no', 'A/C No', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('account_no', null,['class' => 'form-control']) !!}
	  </div>
	</div>
	<div class="form-group">
	  {!! Form::label('bank_name', 'Bank Name', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('bank_name', null, ['class' => 'form-control']) !!}
	  </div>
	</div>
	<div class="form-group">
	  {!! Form::label('ifsc', 'IFSC CODE', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('ifsc', null,['class' => 'form-control']) !!}
	  </div>
	</div>
	<div class="form-group">
	  {!! Form::label('micr', 'MICR Code', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('micr', null,['class' => 'form-control']) !!}
	  </div>
	</div>
	<div class="form-group">
	  {!! Form::label('bank_branch', 'Branch Name', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('bank_branch', null,['class' => 'form-control']) !!}
	  </div>
	</div> -->
	<div class="form-group">
	  {!! Form::label('book_no', 'Book No', array('class' => 'control-label col-md-4 required')) !!}

	  @if(isset($id))
		<div class="col-md-12">
		  {!! Form::text('book_no', null,['class' => 'form-control', 'disabled']) !!}
		</div>
	   @else
		<div class="col-md-12">
		  {!! Form::text('book_no', null,['class' => 'form-control']) !!}
		</div>
	  @endif
	</div>
	<div class="form-group">
	  {!! Form::label('no_of_leaves', 'No. of Leaves', array('class' => 'control-label col-md-4 required')) !!}
	  
	  @if(isset($id))
		<div class="col-md-12">
		  {!! Form::text('no_of_leaves', "",['class' => 'form-control']) !!}
		</div>  
	  @else
		<div class="col-md-12">
		  {!! Form::text('no_of_leaves', null,['class' => 'form-control']) !!}
		</div>
	  @endif
	</div>
	<div class="form-group">
	  {!! Form::label('cheque_no_from', 'Cheque No. From', array('class' => 'control-label col-md-4 required')) !!}
	  
	  @if(isset($id))
		<div class="col-md-12">
		  {!! Form::text('cheque_no_from', $chequebooks->cheque_no_to+1,['class' => 'form-control', 'disabled']) !!}
		  {!! Form::hidden('continue','1',['class'=>'form-control']) !!}
		</div>
	  @else
		<div class="col-md-12">
		  {!! Form::text('cheque_no_from', null,['class' => 'form-control']) !!}
		</div>
	  @endif
	</div>
	<div class="form-group">
	  {!! Form::label('cheque_no_to', 'Cheque No. To', array('class' => 'control-label col-md-4 required')) !!}
	  
	  @if(isset($id))
		<div class="col-md-12">
		  {!! Form::text('cheque_no_to', "",['class' => 'form-control', 'readonly']) !!}
		</div>  
	  @else
		<div class="col-md-12">
		  {!! Form::text('cheque_no_to', null,['class' => 'form-control', 'readonly']) !!}
		</div>
	  @endif
	</div>
	<div class="form-group">
	  {!! Form::label('next_book_warning', 'Next Book Alert at (Chq No.)', array('class' => 'control-label col-md-4 required')) !!}

	  <div class="col-md-12">

		{!! Form::text('next_book_warning', null,['class' => 'form-control']) !!}
	  </div>
	</div>
  </div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Submit</button>
</div>
  
{!! Form::close() !!}
				
<script>
  $(document).ready(function() {
	 basic_functions();
	 //Continue to next Book
	  @if(isset($id))
	$('input[name=no_of_leaves]').keyup(function() {
	  if($(this).val() != 0) {
		cheque_to($(this));
	  } else {
		$('input[name=cheque_no_to]').val("");
	  } 
	  });

	  if($('input[name=no_of_leaves]').val() != "") {
		if($(this).val() != 0) {
		  cheque_to($('input[name=no_of_leaves]'));
		} else {
		$('input[name=cheque_no_to]').val("");
	  }
	  }
	  //Edit Cheque Book
	@else
	$('input[name=no_of_leaves]').keyup(function() {
	  if($(this).val() != 0) {
		$('input[name=cheque_no_from]').val("1");
		cheque_to($(this));
	  } else {
		$('input[name=cheque_no_to], input[name=cheque_no_from]').val("");
	  }
	  });
	  
	  $('input[name=cheque_no_from]').keyup(function() {
		cheque_to($('input[name=no_of_leaves]'));
	  });
	@endif

	  function cheque_to(obj) {

	  var leaves = parseInt(obj.val());
		var from = parseInt($('input[name=cheque_no_from]').val());
		$('input[name=cheque_no_to]').val((leaves+from)-1);
	}
  });

  $('.validateform').validate({
	errorElement: 'span', //default input error message container
	errorClass: 'help-block', // default input error message class
	focusInvalid: false, // do not focus the last invalid input
	rules: {
		book_no: { required: true },
		no_of_leaves: {	required: true },
		cheque_no_from: { required: true },
		cheque_no_to: {	required: true },
		next_book_warning: { required: true }                
	},

	messages: {
		book_no: { required: "Book number is required." },
        no_of_leaves: { required: "Leaves count is required." },
        cheque_no_from: { required: "Cheque starting number is required." },
        cheque_no_to: { required: "Cheque last number is required." },
        next_book_warning: { required: "Next book warning number is required." }                
	},

	invalidHandler: function(event, validator) 
	{ 
		//display error alert on form submit   
		$('.alert-danger', $('.login-form')).show();
	},

	highlight: function(element) 
	{ // hightlight error inputs
		$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
	},

	success: function(label) {
		label.closest('.form-group').removeClass('has-error');
		label.remove();
	},

		submitHandler: function(form) {
			$('.loader_wall_onspot').show();

			$.ajax({
			 url: '{{ route('cheque_book.update') }}',
			 type: 'post',
			 data: {
				_token: '{{ csrf_token() }}',
				_method: 'PATCH',
				id: $('input[name=id]').val(),
				continue: $('input[name=continue]').val(),
				name: $('input[name=name]').val(),               
				book_no: $('input[name=book_no]').val(),               
				no_of_leaves: $('input[name=no_of_leaves]').val(),               
				cheque_no_from: $('input[name=cheque_no_from]').val(),               
				cheque_no_to: $('input[name=cheque_no_to]').val(),               
				next_book_warning: $('input[name=next_book_warning]').val(),             
				account_type: $('input[name=account_type]').val(),             
				account_no: $('input[name=account_no]').val(),             
				bank_name: $('input[name=bank_name]').val(),           
				ifsc: $('input[name=ifsc]').val(),           
				micr: $('input[name=micr]').val(),           
				bank_branch: $('input[name=bank_branch]').val(),        
				},
			 success:function(data, textStatus, jqXHR) {

				call_back(`<tr role="row" class="odd">
						<td>`+data.data.name+`<br>`+data.data.bank_name+ `</td>
						<td>`+data.data.account_no+` <br> `+data.data.account_type+ `</td>
						<td>`+data.data.book_no+`</td>
						<td>`+data.data.cheque_no_from+`</td>
						<td>`+data.data.cheque_no_to+`</td>
						<td>`+data.data.next_book_warning+`</td>						
						<td>
						<a class="grid_label badge badge-info continue_edit_group"  data-id="`+data.data.id+`"> Continue Next Book </a>
						
						  <a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit_group"><i class="fa li_pen"></i></a>
						  <a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
						</td></tr>`, `edit`, data.message, data.data.id);

				$('.loader_wall_onspot').hide();

				},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				}
			});
		}
	});

</script>