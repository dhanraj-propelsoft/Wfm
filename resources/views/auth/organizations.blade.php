@extends('layouts.app')
@section('content')
<style type="text/css">
	.link{ 
		font-size: 13px;
	}
	#example2 {
  border: 1px solid;
  padding: 10px;
  box-shadow: 5px 5px 5px 10px #c1c1c1;
}
</style>
<div class="user-login">
  <div class="row bs-reset">
    <div class="col-md-6 bs-reset">
      <div class="login-bg" style="background-image:url({{ URL::asset('assets/layout/images/login_bg/bg1.jpg') }})"> </div>
    </div>
    <div class="col-md-6 login-container bs-reset">
      <div class="logo_container"> <img src="{{ URL::to('/') }}/assets/layout/images/logo.png" /> </div>
      <div style="margin-top: 95px" class="login-content"> @if(Session::has('flash_message'))
        <div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger"> @foreach($errors->all() as $error)
          <p>{{ $error }}</p>
          @endforeach </div>
        @endif <a style="float: right" href="{{ url('/logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"> <i class="fa fa-user"></i> Sign in as different user! </a>
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
        </form>
        
        <!-- BEGIN LOGIN FORM -->
        
        <h3 class="form-title">Choose Account</h3>
        <br>
        <div style="clear: both;"></div>
        <table class="table">
          <thead>
            <tr>
              <th>Account</th>
              <th>Type</th>
               <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><a href="{{route('persons_store')}}">{{ Auth::user()->name }}</a></td>
              <td><a href="{{route('persons_store')}}">Personal</a></td>
              <td></td>
            </tr>
          @foreach($organizations as $organization)
          <tr>
            <td><a href="{{route('companies_store', [$organization->id])}}">{{ $organization->name }}</a></td>
            <td><a href="{{route('companies_store', [$organization->id])}}">Business</a></td>
            <td>
          
			</td>
          </tr>
          @endforeach
            </tbody>
          
        </table>
        <a class="business_erp active" href="{{ route('search_register_business') }}">Add Your Business</a> 
        
        <!-- END LOGIN FORM --> 
      </div>
    </div>
  </div>
</div>
@stop

@section('dom_links')
@parent 
<script>
	$(document).ready(function(e) {

		@if(!isset($single))

			$('input[name=account_type]').on('change', function() {
				if($(this).val() == 1) {
						$('.organization').css('display', 'block');
				} else {
						$('.organization').css('display', 'none');
				}
			});

		@endif

		$('.login-form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				account_type: {
						required: true
				},
				organizations: {
						required: true
				}
			},

			messages: {
				account_type: {
						required: "Account type is required."
				},
				organizations: {
						required: "Organization is required."
				}
			},

			invalidHandler: function(event, validator) { //display error alert on form submit   
				$('.alert-danger', $('.login-form')).show();
			},

			highlight: function(element) { // hightlight error inputs
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function(label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},

			errorPlacement: function(error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},

			submitHandler: function(form) {
				form.submit(); // form validation success, call ajax form submit
			}
		});

	})
$('body').on('click', '.link', function(){
        $.ajax({
				 url: "{{ route('quick_access') }}",
				 type: 'Post',
				 data: {
				 	_token: '{{ csrf_token() }}',
				 	id:$(this).data('id'),
				 	url:$(this).data('url')
					},
				 dataType: "json",
					success:function(data, textStatus, jqXHR) {
						
						if(data == 'jobcard'){
						window.location.href = "{{ route('transaction.index', ['job_card']) }}";	
						}else if(data == 'jobinvoice'){
						window.location.href = "{{ route('transaction.index', ['job_invoice']) }}";
						}else if(data == 'Jobboard'){
						window.location.href = "{{ route('trade_wms.job_board') }}";
						}else if(data == 'purchase'){
						window.location.href = "{{ route('transaction.index', ['purchases']) }}";
						}else if(data == 'invoice'){
						window.location.href = "{{ route('transaction.index', ['sales']) }}";
						}else{
						window.location.href = "{{ route('dashboard') }}";	
						}
					
					},
			 error:function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
			}
		});
 });

		</script> 
@stop