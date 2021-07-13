
<div class="modal-header">
<h4 class="modal-title float-right">Edit Custom Values</h4>
</div>
{!!Form::model($custom_values, ['class' => 'form-horizontal validateform'])!!}
{{ csrf_field() }}
<div class="modal-body">
  <div class="form-body">
  {!! Form::hidden('id', null) !!}
    <div class="form-group"> {{ Form::label('module', 'Module', array('class' => 'control-label col-md-5 required')) }}
      <div class="col-md-12">{!! Form::text('module', null, ['class'=>'form-control','disabled']) !!} </div>
    </div>
    <div class="form-group"> {{ Form::label('screen',' Screen', ['class' => 'control-label col-md-5 required']) }}
      <div class="col-md-12">{!! Form::text('screen', null, ['class'=>'form-control','disabled']) !!}</div>
    </div>
    <div class="form-group"> {{ Form::label('factor','Factor', ['class' => 'control-label col-md-5 required']) }}
      <div class="col-md-12"> 
     {!! Form::text('factor', null, ['class'=>'form-control','disabled']) !!}
      </div>
    </div>
    <div class="form-group"> 
      {{ Form::label('multiple','Multiple', ['class' => 'control-label col-md-5 required']) }}
    @if($custom_values->multiple == 0)
      <div class="col-md-12"> 
     {!! Form::text('multiple','NO', ['class'=>'form-control','id'=>'0','disabled']) !!}
      </div>
      @elseif($custom_values->multiple == 1)
      <div class="col-md-12"> 
     {!! Form::text('multiple','YES', ['class'=>'form-control','id'=>'1','disabled']) !!}
      </div>
      @endif
    </div>
     <div class="form-group" style="margin-left:15px;margin-right:15px"> 
     	{{ Form::label('sample1','Sample', ['class' => 'control-label col-md-4 required']) }}
      <div > 
       {!! Form::textarea('sample', $sample_data, ['class'=>'form-control ','rows'=>'4','cols'=>'10','disabled','id'=>'sample']) !!}   
      </div>
    </div>
    <div class="form-group" style="margin-left:15px;margin-right:15px">  
     	{{ Form::label('Data1','Data', ['class' => 'control-label col-md-5 required']) }}  
     {!! Form::textarea('data', $data, ['class'=>'form-control ','rows'=>'4','cols'=>'10','id'=>'data']) !!}   
  
    </div>   
  </div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-success">Submit</button>
         </div>

       {!! Form::close() !!}  
      
<style type="text/css">

	.myInput
{
   border-style:none;
}
.myInput:hover
{
   border-style:none;
}
.myInput:focus
{
   border-style:none;
}
	.myInput1
{
   border-style:none;
}
.myInput1:hover
{
   border-style:none;
}
.myInput1:focus
{
   border-style:none;
}
</style> 
      

<script type="text/javascript">
	 $(".myInput").attr("disabled", true);

 $('.validateform').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    rules: {
        ac_name: {
          required: true
        },  
        ac_no: {
          required: true
        },
        bank:{
          required: true
        },
        neft:{
          required: true
        }
      },

      messages: {
        ac_name: {
          required: "Account Name is required."
        },        
        ac_no: {
          required: "Account Number is required."
        },
        bank: {
          required: "Bank is required."
        },
        neft: {
          required: "Type is required."
        }
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

       var text = $('textarea#sample').val();
      var data = $('textarea#data').val();


      $.ajax({
        url: '{{ route('custom_values.update') }}',
        type: 'post',
        data: {
          _token: '{{ csrf_token() }}',
          _method: 'PATCH',
          module:$('input[name=module]').val(),
          screen:$('input[name=screen]').val(),
          factor:$('input[name=factor]').val(),
          multiple:$('input[name=multiple]').attr('id'),
          id:$('input[name=id]').val(),         
         sample:text,
         data:data



        },
        success:function(data, textStatus, jqXHR) {

          call_back_custom(`<tr role="row" class="odd">
    <td><input id="`+data.data.id+`" class="item_checkbox" name="custom_value" value="`+data.data.id+`" type="checkbox">
              <label for="`+data.data.id+`"><span></span></label></td>
     <td>`+data.data.module+`</td>
     <td>`+data.data.screen+`</td>
     <td>`+data.data.factor+`</td>     
     <td>`+data.data.multiple+`</td>
     <td>`+data.data.value+`</td>
     </tr>`, `edit`, data.message, data.data.id);

    $('.loader_wall_onspot').hide();
      },
        error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
      });
    }
  });
  function call_back_custom(data, modal, message, id = null) {
    
    datatable.destroy();

     $('.item_checkbox[id="' + id + '"]').closest('tr').remove();

    $('.data_table tbody').prepend(data);

    datatable = $('#datatable').DataTable(datatable_options);
      
    $('.crud_modal').modal('hide');

    alert_message(message, "success");
    }

 
</script>
