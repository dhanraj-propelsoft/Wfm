<div class="modal-header" style="background-color: #e9ecef;">

	<h5 class="modal-title float-right"><b>Edit Specification Values</b></h5>
    <a  class="close" data-dismiss="modal">&times;</a>


</div>



{!!Form::model($spec_value, [

    'class' => 'form-horizontal validateform'

  ]) !!}



  {{ csrf_field() }}



<div class="modal-body"  style="overflow-y: scroll;">

  <div class="form-body">

     {!! Form::hidden('id', null) !!}

  <div class="form-row">

    <div class="form-group col-md-6">

      <label for="inputEmail4">Type</label>

      {!! Form::select('type',$type,$spec_value->vehicle_type_id, ['class' => 'form-control select_item']) !!}

    </div>

    <div class="form-group col-md-6">

      <label for="specification">Specification</label>

      {!! Form::select('specification',$specification,$selected_spec_value->id, ['class' => 'form-control select_item']) !!}

    </div>

  </div>

    <div class="form-group">

    <label for="value">Value</label>

    {!! Form::text('value',$selected_spec_value->value, ['class' => 'form-control']) !!}

  </div>

  <div class="form-group">

    <label for="description">Description</label>

    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'cols'=>'40']) !!}

  </div>



  



  </div>

</div>





<div class="modal-footer" style="background-color: #e9ecef;">                                            

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

      type: { required: true },

      specification: { required: true },

      value: { required: true },                

    },



    messages: {

      type: { required: "Type is required." },

      specification: { required: "Specification is required." },

      value: { required: "Value is required." },

      

     // name: { required: "Specification Name is required.", remote: "Specification Name is already exists!" },                

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

          <td> <a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>

              <a data-id="`+data.data.id+`" class="action-btn grid_label delete-icon delete"><i class="fa fa-trash-o"></i></a></td>

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

