<script type="text/javascript">

	//csrf token
	var csrf_token	=	"{{csrf_token()}}";

    //Route
    var jobcard_index_route = "{{route('jobcard.index')}}";
    var jobcard_edit_route = '{{ route("jobcard.edit", ":id") }}';
    var jobcard_print_route = "{{route('jobcard_print_transaction')}}";
    var jobcard_advance_route = "{{route('jobcard_advance')}}";
    var jobcard_create_route = '{{ route("jobcard.edit","create") }}';
    var jobcard_change_Status_route = '{{ route("jobcard.changeStatus") }}';
    var jobcard_create_estimate_route = '{{ route("jobcard.estimation", ":id") }}';
    var jobcard_view_estimate_route = '{{ route("job_estimation.index", ":id") }}';
    var jobcard_create_invoice_route = '{{ route("jobcard.invoice", [":id",":type"]) }}';
    var jobcard_view_invoice_route = '{{ route("job_invoice.index", [":id",":type"]) }}';
    var jobcard_delete_route = '{{ route("jobcard.destroy", ":id") }}';

    var jobcard_item_route =  '{{ route("inventoryItem.findAllForProductChooser") }}';
    var find_vehicle_url = "{{ url('findVehicle') }}/";
    var find_customer_route = "{{ route('findCustomerByMobile') }}";
    var find_city_route ="{{ route('get_city') }}";
    var find_jobcard_detail_url = "{{ url('findJobDetails') }}/";
    var jobcard_image_url = "{{ url('findJobCardImageByTID') }}/";
    var vehicle_category_url = '{{ route("jobcard.vehicleCategory", ":id") }}';
    var jobcard_ack_sms_route = "{{ url('jobCardsendSMS/')}}";
    //var jobcard_index_route = '{{ route("jobcard.index") }}';

</script>

<script type="text/javascript" src="{{ URL::asset('resources/views/trade_wms/jobcard/JobCard-Common.js') }}"></script>