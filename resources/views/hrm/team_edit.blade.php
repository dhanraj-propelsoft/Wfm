<div class="modal-header">
    <h4 class="modal-title float-right">Edit Team</h4>
</div>

    {!!Form::model($team, ['class' => 'form-horizontal validateform'])!!}
    {{ csrf_field() }}

<div class="modal-body">
	<div class="form-body">
		{{Form::hidden('id',null)}}
		<div class="form-group">
			{!! Form::label('id', 'Name', array('class' => 'control-label col-md-4 required')) !!}
			<div class="col-md-12">
				{!! Form::text('name', null,['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('description', 'Description', array('class' => 'control-label col-md-4')) !!}
			<div class="col-md-12">
				{!! Form::textarea('description', null,['class' => 'form-control', 'rows'=>'1', 'cols'=>'40']) !!}
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('employees', 'Selected Employees', ['class' => 'control-label col-md-3']) !!}
			<div class="col-md-12">
				<ul style="float: left; width: 100%; margin: 0 0 15px 0; padding: 0;" class="employees" style="list-style: none"></ul>
			</div>
		</div>
		<div class="clear"></div><br>
		<table class="table table-striped table-hover table-bordered" id="employeetable" >
			<thead>
				<tr>
					<th>
					</th>
					<th>
						Employee
					</th>
					<th>
						Designation
					</th>
					<th>
						Department
					</th>
				</tr>
			</thead>
			<tbody>
			@foreach($employees as $employee)
				<?php $checked = in_array($employee->id, $selected); ?>
				<tr>
					<td width="1">
					{{ Form::checkbox('employees',$employee->id, $checked, ['id' => 'id_'.$employee->id]) }}
					<label for="id_{{$employee->id}}"><span></span></label>
					</td>
					<td>
						{{$employee->first_name}} {{$employee->last_name}}
					</td>
					<td>
						{{$employee->designation}}
					</td>
					<td>
						{{$employee->department}}
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>

<div class="modal-footer">                                            
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-success">Submit</button>
</div>
	
{!! Form::close() !!}

<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
<script>
$(document).ready(function()
{

	 basic_functions();

	 $('body').on('click', '.close_btn', function() {
		$(this).parent().remove();
		$("input[name=employees][value="+$(this).parent().attr('id')+"]").prop('checked', false);
	});

	$("input[name=employees]").each(function() {
		employees_selected($(this));
	});

	$("input[name=employees]").on('change', function() {
		employees_selected($(this));
	});

	function employees_selected(obj) {
		var id = obj.val();
		var name = obj.parent().next().text();
		if(obj.is(':checked')) {
			$('.employees').append('<li id="'+id+'" style="background: #eaeaea; padding: 5px; border:1px solid #ccc; border-radius:3px; float: left; margin:2px;"><span class="close_btn" style="font-size: 9px">X</span> '+name+' </li>');
		} else {
			$('.employees').find('li[id="'+id+'"]').remove();
		}
	}

	 $('#employeetable').dataTable({
                language: {
                    aria: {
                        sortAscending: ": activate to sort column ascending",
                        sortDescending: ": activate to sort column descending"
                    },
                    emptyTable: "No data available in table",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries found",
                    infoFiltered: "(filtered1 from _MAX_ total entries)",
                    lengthMenu: "_MENU_ entries",
                    search: "Search:",
                    zeroRecords: "No matching records found"
                },
                buttons: [],
                responsive: !0,
                order: [
                    [0, "asc"]
                ],
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, 100]
                ],
                "paging": false,
                dom: "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
            });

    $(".dataTables_length").find('select').select2();

	$('.validateform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {
		    bcrm_id: { required: true },
		    //parent_department: { required: true },                
		},

		messages: {
		    bcrm_id: { required: "Bussiness is required." },
		    //parent_department: { required: "Parent Department Name is required." },                
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
            url: '{{ route('team.update') }}',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                id: $('input[name=id]').val(),
                name: $('input[name=name]').val(),
                description: $('textarea[name=description]').val(),
                employees: $("input[name=employees]:checked").map(function() { 
                        return this.value; 
                    }).get()
                },
            success:function(data, textStatus, jqXHR) {

            	var team_html = ``;
            	if((data.data.team_members).length > 0) {
            		var team = data.data.team_members;
            		for(i in team) {
            			team_html += `<a href="`+team[i].id+`" style="width:100%; padding:2px; float:left;">`+team[i].first_name+` `+team[i].last_name+` `+team[i].employee_code+` </a>`;
            		}
            	}

                call_back(`<tr role="row" class="odd">
                	<td><input id="`+data.data.id+`" class="item_check" name="team" value="`+data.data.id+`" type="checkbox">
                    	<label for="`+data.data.id+`"><span></span></label>
                    </td>
                    <td>`+data.data.name+`</td>
                    <td>`+team_html+`</td>
                    <td>`+data.data.description+`</td>
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
                    </td></tr>`, `edit`, data.message, data.data.id);

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
