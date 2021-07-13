<div class="modal-header">
	<h4 class="modal-title float-right">Edit Specification Values</h4>
</div>

{!!Form::model($specification, [
    'class' => 'form-horizontal validateform'
  ]) !!}

  {{ csrf_field() }}

<div class="modal-body">
  <div class="form-body">
     {!! Form::hidden('id', null) !!}
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Type</label>
      {!! Form::select('type',$type, null, ['class' => 'form-control select_item']) !!}
    </div>
    <div class="form-group col-md-6">
      <label for="specification">Specification</label>
      {!! Form::select('specification',$specification,$selected_spec->display_name, ['class' => 'form-control select_item']) !!}
    </div>
  </div>
    <div class="form-group">
    <label for="value">Value</label>
    {!! Form::text('value', null, ['class' => 'form-control']) !!}
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}
  </div>

  

  </div>
</div>

<div class="modal-footer">                                            
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success">Save</button>
</div>
	
{!! Form::close() !!}
<script>
$('.validateform').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    rules: {
      //name: { required: true },
    /*  display_name: { 
        required: true,
        remote: {
            url: '{{ route('check_unit_name') }}',
            type: "post",
            data: {
              _token :$('input[name=_token]').val(),
              id:$('input[name=id]').val()
            }
          }
      }, */               
    },

    messages: {
      //name: { required: "Unit Name is required." },
      display_name: { required: " Spec is required.", remote: " Spec is already exists!" },                
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
        url: '{{ route('specification_values.update') }}',
        type: 'post',
        data: {
          _token: '{{ csrf_token() }}',
          _method: 'PATCH',
          id:$('input[name=id]').val(),
          type: $('select[name=type]').val(),
        specification: $('select[name=specification]').val(),
        value: $('input[name=value]').val(),
        description: $('textarea[name=description]').val()              
        },
        success:function(data, textStatus, jqXHR) {
          call_back(`<tr role="row" class="odd">
          <td>
            <input id="`+data.data.id+`" class="item_check" name="category" value="`+data.data.id+`" type="checkbox">
            <label for="`+data.data.id+`"><span></span></label>
          </td>
          <td>`+data.data.type+`</td>
          <td>`+data.data.specification+`</td>
          <td>`+data.data.value+`</td>
          <td>`+data.data.description+`</td>
          <td></td>
          </tr>`,`edit`, data.message, data.data.id);

        $('.loader_wall_onspot').hide();
        },
        error:function(jqXHR, textStatus, errorThrown) {
        //alert("New Request Failed " +textStatus);
        }
      });
    }
  });

</script>
