@extends('layouts.app')
@section('content')
<div class="user-login">
  <div class="row bs-reset">
    <div class="col-md-6 bs-reset">
      <div class="login-bg" style="background-image:url({{ URL::asset('assets/layouts/images/login_bg/bg1.jpg') }})"> </div>
    </div>
    <div class="col-md-6 login-container bs-reset">

      <div style="margin-top: 95px" class="login-content">
        <h1>Reset Password</h1>
        @if(Session::has('flash_message'))
        <div class="alert alert-success"> {{ Session::get('flash_message') }} </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger"> @foreach($errors->all() as $error)
          <p>{{ $error }}</p>
          @endforeach </div>
        @endif 
        
        <!-- BEGIN LOGIN FORM -->
        <form class="login-form" method="POST" action="{{ route('reset_store_password') }}">
          {{ csrf_field() }}

          <div class="form-group">
    <input placeholder="Email or Mobile" name="mobile" type="text" class="form-control"  />
    </div>

    <button type="submit" class="btn btn-success">Submit</button>

        
        </form>
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
        $('#login').keyup(function() {
            if($.isNumeric($('#login').val())) {
                $('#login').attr('name', 'mobile');
            } else {
                $('#login').attr('name', 'email');
            }
        });

        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                login: {
                    required: true
                },
                password: {
                    required: true
                },
                remember: {
                    required: false
                }
            },

            messages: {
                login: {
                    required: "Email is required."
                },
                password: {
                    required: "Password is required."
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
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

    </script> 
@stop