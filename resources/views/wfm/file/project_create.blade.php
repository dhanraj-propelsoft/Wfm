<div class="modal-header">
    <h4 class="modal-title float-right">Add Project </h4>
     <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
</div>

{!! Form::open(['class' => 'form-horizontal validateform']) !!}
{{ csrf_field() }}

    <div class="modal-body">
    <div class="container-fluid">
        <div class="form-body">
                <div class="row">
            <div class="col-md-12" >
                <div class="form-group" >
                     <input type="text" class="inputText" style=" border: 1px solid #ced4da;color: #999; " required/>
                    <span class="fa fa-plus-circle floating-label" style="color: #999;"">&nbsp;Add Project</span>
                </div>
                
            </div>
        </div>
          
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="textarea"  class="inputTextArea" style=" border: 1px solid #ced4da;color: #999; " required/>
                    <span class="fa fa-plus-circle floating-label" style="color: #999;"">&nbsp;Project details</span>
                </div>
            </div>
        </div> 

            <div class="row">

 <div class="col-xs-6 col-md-6"   >
           <div class="input-group" style="    border: 1px solid #ddd;">
      <span class="input-group-addon" for="create_date" style="color: #919191;"><i class="fa fa-user"></i></span>
    <select id="create_date" type="select" class="form-control tooltips" title="Created by" name="create_date"  style="color: #919191;">
        <option selected disabled hidden>Created by</option>
        <option>Myself</option>

    <option>Employee 1</option>
    <option>Employee 2</option>
    <option>Employee 3</option>
      </select>
    </div>
           </div>


           <div class="col-xs-6 col-md-6">
            <div class="input-group" style="    border: 1px solid #ddd;">
      <span class="input-group-addon" for="category" style="color: #919191;"><i class="fa fa-user"></i></span>
     <select id="category" type="select" class="form-control tooltips"  title="Category" name="size"  style="color: #919191;">
        <option selected disabled hidden>Category</option>
        <option>Category 1</option>
    <option>Category 2</option>
    <option>Category 3</option>
      </select>
     
    </div>
           </div>

         

           

 </div>


            <div class="row">
          

            <div class="col-xs-6 col-md-6">
            <div class="input-group" style="    border: 1px solid #ddd;">
      <span class="input-group-addon" for="deadline_date" style="color: #919191;"><i class="fa fa-calendar"></i></span>
     <input id="date"  class="form-control date-picker tooltips" name="date"  placeholder="Deadline" title="Deadline" style="color: #919191;" data-date-format = 'dd-mm-yyyy'>
     
    </div>
           </div>


           <div class="col-xs-6 col-md-6"   >
           <div class="input-group" style="    border: 0px;bottom:-1px; ">
     <label for="upload-photo" class="" style="color:#999">Attachment...<i class="fa fa-paperclip "></i></label><input type="file" name="photo" id="upload-photo" multiple/>
    </div>
           </div> 

 </div>


            
            
     
            
        </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" name="cancel_btn" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success button" >Add Project</button>
    </div>
{!! Form::close() !!}
                                
<script>
$(document).ready(function()
{

       //alert();
        var date = new Date();
date.setDate(date.getDate());
 
 $("input[name=date]").datepicker({ 
    todayHighlight: true,
    startDate: date
});



    basic_functions();
      

           
  $("button[name=cancel_btn]").on("click",function(){

    alert("Are You sure..!Do You Wand To Cancel");
 });
    

    $('.validateform').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            project_category_name: { required: true  },
           
        },

        messages: {
            project_category_name: { required: "Project Category Name is required."},
          
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
                url: '{{ route('projectcategory.store') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    project_category_name: $('input[name=project_category_name]').val(),
                 
                },
                success:function(data, textStatus, jqXHR) {
                    if(data.status==0)
                    {
                        
                          $('.loader_wall_onspot').hide();
                         input='input[name=project_category_name]';
                         field_name="Project Category Name";
                         error_msg(input,field_name);
                         return false;
                    }
                    call_back(`<tr role="row" class="odd">
                      
                        <td>`+data.data.name+`</td>
                        <td>
                            <label class="grid_label badge badge-success status">Active</label>
                            <select style="display:none" id="`+data.data.id+`" class="active_status form-control">
                            <option value="1">Active</option>
                            <option value="0">In-active</option>
                            </select>
                        </td>                           
                        <td>
                        <a data-id="`+data.data.id+`" class="action-btn grid_label edit-icon edit"><i class="fa li_pen"></i></a>&nbsp;
                        <a data-id="`+data.data.id+`" class="action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a>
                        </td></tr>`, `add`, data.message);

                    $('.loader_wall_onspot').hide();

                },
                error:function(jqXHR, textStatus, errorThrown) {
                    //alert("New Request Failed " +textStatus);
                }
            });
        }
    });
});
</script>
