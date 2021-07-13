@extends('layouts.master')
@section('head_links') @parent
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/data-tables/datatables.min.css') }}">
@stop
@include('includes.hrm')
@section('content')

<div class="alert alert-success">
    {{ Session::get('flash_message') }}
</div>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="fill header">
  <h4 class="float-left page-title">Attendance Setting</h4>
  @permission('attendance-setting-create')
  	<a class="btn btn-danger float-right add" style="color: #fff">+ New</a>
  @endpermission
</div>

<div class="float-left" style="width: 100%; padding-top: 10px">
  <table id="datatable" class="table data_table" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th> Name </th> 
        <th> Standard Working Hours </th>               
        <th> Minimum Hours for Full Day </th>
        <th> Minimum Hours for Half Day </th>
        <th> Minimum Hours for Official Half Day </th>
        <th> Grace Time </th>
        <th> Deduction Days </th>
        <th> Cancel Deduction </th>
        <th> Deduct From </th>
        <th> Action </th>
      </tr>
    </thead>
    <tbody>
      @foreach($attendance_settings as $attendance_setting)
        <tr>
          <td>{{ $attendance_setting->name }}</td>              
          <td>{{ $attendance_setting->standard_working_hours }}</td>              
          <td>{{ $attendance_setting->min_hours_for_full_day }}</td>
          <td>{{ $attendance_setting->min_hours_for_half_day }}</td>
          <td>{{ $attendance_setting->min_hours_for_official_half_day }}</td>
          <td>{{ $attendance_setting->grace_time }}</td>
          <td>{{ $attendance_setting->deduction_days }}</td>
          <td>
            @if($attendance_setting->cancel_deduction == '1')
              <label class="grid_label badge badge-success">Yes</label>
            @else
              <label class="grid_label badge badge-warning">No</label>
            @endif
          </td>
          <td>
           @if(($attendance_setting->deduct_from) == '1')
              <label class="grid_label badge badge-warning">LOP</label>
            @else
              <label class="grid_label badge badge-success">CL</label>
            @endif
          </td>
          <td>           
            @permission('attendance-setting-edit')
              <a data-id="{{ $attendance_setting->id }}" class="grid_label action-btn edit-icon edit"><i class="fa li_pen"></i></a>
            @endpermission

            @permission('attendance-setting-delete')
              <a data-id="{{ $attendance_setting->id }}" class="grid_label action-btn delete-icon delete"><i class="fa fa-trash-o"></i></a> 
            @endpermission
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

@stop

@section('dom_links')
@parent
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/plugins/data-tables/datatables.min.js') }}"></script>
   <script type="text/javascript">
   var datatable = null;

   var datatable_options = {"stateSave": true};

  $(document).ready(function() {



  datatable = $('#datatable').DataTable(datatable_options);

  $('.add').on('click', function(e) {
        e.preventDefault();
        $.get("{{ route('attendance_setting.create') }}", function(data) {
          $('.crud_modal .modal-container').html("");
          $('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
  });

  $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        $.get("{{ url('hrm/attendance/settings') }}/"+$(this).data('id')+"/edit", function(data) {
          $('.crud_modal .modal-container').html("");
          $('.crud_modal .modal-container').html(data);
        });
        $('.crud_modal').find('.modal-dialog').addClass('modal-lg');
        $('.crud_modal').modal('show');
  });

  $('body').on('click', '.status', function(e) {
    $(this).hide();
    $(this).parent().find('select').css('display', 'block');
  });

  $('body').on('change', '.active_status', function(e) {
      var status = $(this).val();
      var id = $(this).attr('id');
      var obj = $(this);
      var url = "{{ route('shift_status_approval') }}";
      change_status(id, obj, status, url, "{{ csrf_token() }}");
    });


  $('body').on('click', '.delete', function(){
    var id = $(this).data('id');
    var parent = $(this).closest('tr');
    var delete_url = '{{ route('attendance_setting.destroy') }}';
    delete_row(id, parent, delete_url, "{{ csrf_token() }}");
  });

  });
  </script>
@stop