@extends('layouts.master')
@include('includes.settings')
@section('content')

@if(Session::has('flash_message'))
<div class="alert alert-success" style="display: block;"> {{ Session::get('flash_message') }} </div>
@endif

@if($errors->any())
<div class="alert alert-danger" style="display: block;"> @foreach($errors->all() as $error)
  {{ $error }}
  @endforeach </div>
@endif
<div class="fill header">
  <h4 class="float-left page-title">Change Password</h4>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12"> {!! Form::open([
    'class' => 'form-horizontal validateform'
    ]) !!}
    
    {{ csrf_field() }}
    <div class="form-body">
      <div class="form-group"> {!! Form::label('old_password', 'Current Password', ['class' => 'col-md-3 control-label required']) !!}
        <div class="col-md-12">{!! Form::text('old_password', null, ['class' => 'form-control', 'placeholder' => 'Current Password']) !!} </div>
      </div>
      <div class="form-group"> {!! Form::label('password', 'New Password', ['class' => 'col-md-3 control-label required']) !!}
        <div class="col-md-12"> {!! Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'New Password']) !!} </div>
      </div>
      <div class="form-group"> {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-md-3 control-label required']) !!}
        <div class="col-md-12"> {!! Form::text('password_confirmation', null, ['class' => 'form-control', 'placeholder' => 'Confirm Password']) !!} </div>
      </div>
    </div>
    <div class="footer_bar">
      <button type="reset" class="btn btn-default clear_btn"></button>
      <button type="submit" class="btn btn-success submit_btn">Save </button>
    </div>
    {!! Form::close() !!} </div>
</div>
@stop

@section('dom_links')
@parent 
<script>


$('.validateform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                old_password: {
                    required: true
                },
                password: {
                    minlength: 6
                },
                password_confirmation: {
                    minlength: 6,
                    equalTo: '[name="password"]'
                }
            },

            messages: {
                old_password: {
                    required: "Current Password is required."
                },
                password: {
                    required: "New Password is required."
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

            submitHandler: function(form) {
                form.submit(); // form validation success, call ajax form submit
            }
        });
</script> 
@stop