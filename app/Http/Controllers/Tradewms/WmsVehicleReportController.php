<?php
namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use App\VehicleRegisterDetail;
use App\VehicleTyreSize;
use App\VehicleTyreType;
use App\VehicleBodyType;
use App\VehicleCategory;
use App\VehicleFuelType;
use App\VehicleRimType;
use App\VehicleVariant;
use App\VehicleSpecification;
use App\InventoryItem;
use App\VehicleModel;
use App\VehicleWheel;
use App\CustomerGroping;
use App\VehicleMake;
use App\VehicleSegment;
use App\WmsPriceList;
use App\WmsTransaction;
use App\Transaction;
use App\AccountEntry;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\People;
use App\Custom;
use Validator;
use Response;
use Session;
use App\Person;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use Auth;
use File;
use DB;
use App\Notification\Service\SmsNotificationService;
use Illuminate\Support\Facades\Log;

class WmsVehicleReportController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $vehicle_make_id, $vehicle_model_id, $vehicle_tyre_size, $vehicle_tyre_type, $vehicle_variant, $vehicle_wheel, $fuel_type, $rim_type, $body_type, $vehicle_category, $vehicle_drivetrain, $service_type, $vehicle_usage;

    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
        $this->vehicle_make_id = VehicleMake::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_make_id->prepend('Select Vehicle Make', '');

        $this->vehicle_model_id = VehicleModel::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_model_id->prepend('Select Vehicle Model', '');

        $this->vehicle_tyre_size = VehicleTyreSize::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_tyre_size->prepend('Select Tyre Size', '');

        $this->vehicle_tyre_type = VehicleTyreType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_tyre_type->prepend('Select Tyre Type', '');

        $this->vehicle_variant = VehicleVariant::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_variant->prepend('Select Vehicle Variant', '');

        $this->vehicle_wheel = VehicleWheel::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_wheel->prepend('Select Vehicle Wheel', '');

        $this->fuel_type = VehicleFuelType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->fuel_type->prepend('Select Fuel Type', '');

        $this->rim_type = VehicleRimType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->rim_type->prepend('Select Rim Type', '');

        $this->body_type = VehicleBodyType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->body_type->prepend('Select Body Type', '');

        $this->vehicle_category = VehicleCategory::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_category->prepend('Select Vehicle Category', '');
    }

    public function vehicle_list_report(Request $request)
    {
        $organization_id = Session::get('organization_id');
        /*
         * $vehicle = new \stdClass;
         * $vehicle->id = '1';
         * $vehicle->name = 'Car';
         * $vehicle->model = 'SUV';
         * $vehicle->make_year = '2015';
         * $vehicle->status = '1';
         *
         * $vehicles[]=$vehicle;
         */
        $reports = Transaction::select(DB::raw('CONCAT(transactions.order_no, " - ",hrm_employees.first_name,"-",transactions.total) AS job_invoice'), 'transactions.id', 'inventory_items.name as item_name', 'account_vouchers.name', 'transactions.order_no', 'transactions.total', 'hrm_employees.first_name')->leftjoin('transaction_items', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
            ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
            ->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'transactions.employee_id')
            ->where('account_vouchers.name', '=', "job_invoice")
            ->where('transactions.organization_id', '=', $organization_id)
            ->get();

        $country = Country::where('name', 'India')->first();
        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name', 'id');
        $title->prepend('Title', '');

        $payment = PaymentMode::where('status', '1')->pluck('display_name', 'id');
        $payment->prepend('Select Payment Method', '');

        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term', '');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('display_name', 'id');
        $group_name->prepend('Select Group Name', '');

        $reg_no = VehicleRegisterDetail::leftjoin('wms_vehicle_organizations', 'wms_vehicle_organizations.vehicle_id', '=', 'vehicle_register_details.id')->where('wms_vehicle_organizations.organization_id', $organization_id)
            ->orderby('registration_no')
            ->pluck('vehicle_register_details.registration_no', 'vehicle_register_details.id');
        $reg_no->prepend('Select Vehicle No', '');

        $name = 'customer';
        $customer = People::leftJoin('people_person_types', 'people_person_types.people_id', '=', 'people.id')->leftjoin('account_person_types', 'account_person_types.id', '=', 'people_person_types.person_type_id')
            ->where('people.organization_id', $organization_id)
            ->where('account_person_types.name', $name)
            ->groupBy('people.id')
            ->orderBy('people.first_name')
            ->pluck('people.display_name', 'people.id');
        $customer->prepend('Select Customer', '');

        $report = WmsTransaction::select('transactions.order_no', 'wms_transactions.job_date', 'inventory_items.name', DB::raw('(CASE WHEN wms_transactions.advance_amount is NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total as total'), DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"));
        $report->leftjoin('transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');

        $report->leftjoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id');
        $report->leftjoin('inventory_items', 'transaction_items.item_id', '=', 'inventory_items.id');
        $report->leftjoin('account_vouchers', 'transactions.transaction_type_id', '=', 'account_vouchers.id');
        $report->leftJoin('people', function ($join) use ($organization_id) {
            $join->on('people.person_id', '=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0')
                ->orOn('people.business_id', '=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
        });

        $report->leftJoin('people AS business', function ($join) use ($organization_id) {
            $join->on('business.business_id', '=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
        });
        $report->whereIn('account_vouchers.name', [
            'job_invoice',
            'job_invoice_cash'
        ]);
        $report->where('transactions.organization_id', '=', $organization_id);
        $report->whereNull('transactions.deleted_at');
        $report->whereNotNull('transactions.order_no');

        if ($request->input('vehicle_no')) {
            $reg_no = $request->input('vehicle_no');
            $report = WmsTransaction::select('transactions.order_no', 'wms_transactions.job_date', DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), 'inventory_items.name', DB::raw('transactions.total + wms_transactions.advance_amount as total'));
            $report->leftjoin('transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');
            $report->leftjoin('hrm_employees', 'transactions.employee_id', '=', 'hrm_employees.id');
            $report->leftjoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id');
            $report->leftjoin('inventory_items', 'transaction_items.item_id', '=', 'inventory_items.id');
            $report->leftjoin('account_vouchers', 'transactions.transaction_type_id', '=', 'account_vouchers.id');
            $report->where('wms_transactions.registration_id', '=', $reg_no);
            $report->leftJoin('people', function ($join) use ($organization_id) {
                $join->on('people.person_id', '=', 'transactions.people_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('transactions.user_type', '0');
            });
            $report->leftJoin('people AS business', function ($join) use ($organization_id) {
                $join->on('business.business_id', '=', 'transactions.people_id')
                    ->where('business.organization_id', $organization_id)
                    ->where('transactions.user_type', '1');
            });
            $report->whereIn('account_vouchers.name', [
                'job_invoice',
                'job_invoice_cash'
            ]);
            $report->where('transactions.organization_id', '=', $organization_id);
            $report->whereNull('transactions.deleted_at');
            $report->whereNotNull('transactions.order_no');
        }
        if ($request->input('customer_id')) {

            $customer_id = $request->input('customer_id');
            $report->where('people.id', $customer_id);
        }
        if ($request->input('from_date')) {
            $from_date = $request->input('from_date');
            $start_date = date_string($from_date);
            // dd($start_date);
            $report->where('wms_transactions.job_date', '>=', $start_date);
            // dd($data);
        }
        if ($request->input('to_date')) {
            $to_date = $request->input('to_date');
            $end_date = date_string($to_date);
            $report->where('wms_transactions.job_date', '<=', $end_date);
        }
        $reports = $report->get();
        // dd($reports);
        if ($request->ajax()) {
            if (count($reports) > 0) {
                return response()->json([
                    'status' => 1,
                    'data' => $reports
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => "No data available."
                ]);
            }
        }
        // dd($reports);
        return view('trade_wms.vehicle_report', compact('reg_no', 'reports', 'state', 'city', 'title', 'payment', 'terms', 'group_name', 'customer', 'reports'));
    }

    public function get_search_result(Request $request)
    {

        // dd($request->all());
        $organization_id = Session::get('organization_id');

        if ($request->input('group_id') == null && $request->input('last_visit_fromdate') == null && $request->input('last_visit_todate') == null && $request->input('next_visit_fromdate') == null && $request->input('next_visit_todate') == null) {
            $group_details = people::select('people.display_name AS owner', 'people.mobile_no AS owner_mobile_no', DB::raw('IF(customer_gropings.display_name IS NULL,"",customer_gropings.display_name)AS group_name'))->leftjoin('people_person_types', 'people_person_types.people_id', '=', 'people.id')
                ->leftjoin('customer_gropings', 'customer_gropings.id', '=', 'people.group_id')
                ->where('people_person_types.person_type_id', '=', 2)
                ->where('people.organization_id', $organization_id)
                ->get();

            return response()->json([
                'status' => 1,
                'data' => $group_details
            ]);
        } else if ($request->input('group_id') != null) {
            $group_details = CustomerGroping::select('customer_gropings.id', 'customer_gropings.display_name AS group_name', 'people.display_name AS owner', 'people.mobile_no AS owner_mobile_no')->leftjoin('people', 'people.group_id', '=', 'customer_gropings.id')
                ->where('customer_gropings.id', $request->input('group_id'))
                ->where('customer_gropings.organization_id', $organization_id)
                ->get();

            return response()->json([
                'status' => 1,
                'data' => $group_details
            ]);
        } else if ($request->input('last_visit_fromdate') && $request->input('last_visit_todate')) {
            $from_date = "";
            $to_date = "";
            if ($request->input('last_visit_fromdate')) {
                $from_date = date_string($request->input('last_visit_fromdate'));
            }
            if ($request->input('last_visit_todate')) {
                $to_date = date_string($request->input('last_visit_todate'));
            }

            $last_visit_dates = Transaction::select('transactions.id', 'vehicle_register_details.registration_no', DB::raw('IF(people.display_name IS NULL,business.display_name,people.display_name) AS owner'), DB::raw('IF(people.mobile_no IS NULL,business.mobile_no,people.mobile_no) AS owner_mobile_no'), DB::raw('IF(people.group_id IS NULL,business.group_id,people.group_id)AS group_id'), DB::raw('IF(customer_gropings.display_name IS NULL,"",customer_gropings.display_name)AS group_name'), 'wms_transactions.job_date AS last_visit', 'wms_transactions.vehicle_next_visit AS next_visit', DB::raw('IF(wms_transactions.next_visit_mileage IS NULL," ",wms_transactions.next_visit_mileage) AS next_visit_mileage'), DB::raw('IF(wms_transactions.vehicle_next_visit_reason IS NULL,"",wms_transactions.vehicle_next_visit_reason) AS vehicle_next_visit_reason'), DB::raw('IF(vehicle_register_details.driver IS NULL,"",vehicle_register_details.driver)AS driver_name'), DB::raw('IF(vehicle_register_details.driver_mobile_no IS NULL,"",vehicle_register_details.driver_mobile_no)AS driver_mobile'));
            $last_visit_dates->leftjoin('wms_transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');
            $last_visit_dates->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
            $last_visit_dates->leftJoin('people', function ($join) use ($organization_id) {
                $join->on('people.person_id', '=', 'transactions.people_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('transactions.user_type', '0');
            });
            $last_visit_dates->leftJoin('people AS business', function ($join) use ($organization_id) {
                $join->on('business.business_id', '=', 'transactions.people_id')
                    ->where('business.organization_id', $organization_id)
                    ->where('transactions.user_type', '1');
            });
            $last_visit_dates->leftjoin("customer_gropings", function ($join) {
                $join->on("customer_gropings.id", "=", "people.group_id")
                    ->orOn("customer_gropings.id", "=", "business.group_id");
            });
            $last_visit_dates->where('transactions.organization_id', $organization_id);

            $last_visit_dates->whereNull('transactions.deleted_at');

            $last_visit_dates->where('wms_transactions.jobcard_status_id', '8');

            if (! empty($from_date) && ! empty($to_date)) {
                $last_visit_dates->wherebetween('wms_transactions.job_date', [
                    $from_date,
                    $to_date
                ]);
            }
            if ($request->input('from_date')) {
                $report->where('wms_transactions.job_date', '>=', $from_date);
            }
            if ($request->input('to_date')) {
                $last_visit_dates->where('wms_transactions.job_date', '<=', $to_date);
            }
            $last_visit_dates->groupby('vehicle_register_details.id');
            $last_visit_date = $last_visit_dates->get();

            return response()->json([
                'status' => 1,
                'data' => $last_visit_date
            ]);
        } else if ($request->input('next_visit_fromdate') && $request->input('next_visit_todate')) {
            $next_visit_from_date = "";
            $next_visit_to_date = "";
            if ($request->input('next_visit_fromdate')) {
                $next_visit_from_date = date_string($request->input('next_visit_fromdate'));
            }
            if ($request->input('next_visit_todate')) {
                $next_visit_to_date = date_string($request->input('next_visit_todate'));
            }

            $next_visit_dates = Transaction::select('transactions.id', 'vehicle_register_details.registration_no', DB::raw('IF(people.display_name IS NULL,business.display_name,people.display_name) AS owner'), DB::raw('IF(people.mobile_no IS NULL,business.mobile_no,people.mobile_no) AS owner_mobile_no'), DB::raw('IF(customer_gropings.display_name IS NULL,"",customer_gropings.display_name)AS group_name'), 'wms_transactions.job_date AS last_visit', 'wms_transactions.vehicle_next_visit AS next_visit', DB::raw('IF(wms_transactions.next_visit_mileage IS NULL," ",wms_transactions.next_visit_mileage) AS next_visit_mileage'), DB::raw('IF(wms_transactions.vehicle_next_visit_reason IS NULL,"",wms_transactions.vehicle_next_visit_reason) AS vehicle_next_visit_reason'), DB::raw('IF(vehicle_register_details.driver IS NULL,"",vehicle_register_details.driver)AS driver_name'), DB::raw('IF(vehicle_register_details.driver_mobile_no IS NULL,"",vehicle_register_details.driver_mobile_no)AS driver_mobile'));
            $next_visit_dates->leftjoin('wms_transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');
            $next_visit_dates->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
            $next_visit_dates->leftJoin('people', function ($join) use ($organization_id) {
                $join->on('people.person_id', '=', 'transactions.people_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('transactions.user_type', '0');
            });
            $next_visit_dates->leftJoin('people AS business', function ($join) use ($organization_id) {
                $join->on('business.business_id', '=', 'transactions.people_id')
                    ->where('business.organization_id', $organization_id)
                    ->orwhere('transactions.user_type', '1');
            });
            $next_visit_dates->leftjoin("customer_gropings", function ($join) {
                $join->on("customer_gropings.id", "=", "people.group_id")
                    ->orOn("customer_gropings.id", "=", "business.group_id");
            });
            $next_visit_dates->where('transactions.organization_id', $organization_id);

            $next_visit_dates->whereNull('transactions.deleted_at');

            $next_visit_dates->where('wms_transactions.jobcard_status_id', '8');

            if (! empty($next_visit_from_date) && ! empty($next_visit_to_date)) {
                $next_visit_dates->wherebetween('wms_transactions.vehicle_next_visit', [
                    $next_visit_from_date,
                    $next_visit_to_date
                ]);
            }
            if ($request->input('next_visit_fromdate')) {
                $next_visit_dates->where('wms_transactions.vehicle_next_visit', '>=', $next_visit_from_date);
            }
            if ($request->input('next_visit_todate')) {
                $next_visit_dates->where('wms_transactions.vehicle_next_visit', '<=', $next_visit_to_date);
            }
            $next_visit_dates->groupby('vehicle_register_details.id');
            $next_visit_date = $next_visit_dates->get();

            return response()->json([
                'status' => 1,
                'data' => $next_visit_date
            ]);
        }
    }

    public function wms_item_price_list()
    {
        $organization_id = Session::get('organization_id');
        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name', 'id');
        $title->prepend('Title', '');

        $payment = PaymentMode::where('status', '1')->pluck('display_name', 'id');
        $payment->prepend('Select Payment Method', '');

        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term', '');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('display_name', 'id');
        $group_name->prepend('Select Group Name', '');

        $vehicle_make_id = $this->vehicle_make_id;
        $vehicle_model_id = $this->vehicle_model_id;
        $vehicle_tyre_size = $this->vehicle_tyre_size;
        $vehicle_tyre_type = $this->vehicle_tyre_type;
        $vehicle_variant_id = $this->vehicle_variant;
        $vehicle_wheel = $this->vehicle_wheel;
        $fuel_type = $this->fuel_type;
        $rim_type = $this->rim_type;
        $body_type = $this->body_type;
        /* $vehicle_category = $this->vehicle_category; */
        $vehicle_segment = VehicleSegment::where('organization_id', $organization_id)->orderby('name')->pluck('name', 'id');
        $vehicle_segment->prepend('Choose a Pricing Segment', '');

        $specifications = VehicleSpecification::select('vehicle_specifications.vehicle_spec_id', 'vehicle_spec_masters.display_name', DB::raw('GROUP_CONCAT(vehicle_specification_details.display_name) AS value'), DB::raw('GROUP_CONCAT(vehicle_specification_details.id) AS value_id'))->leftjoin('vehicle_spec_masters', 'vehicle_spec_masters.id', '=', 'vehicle_specifications.vehicle_spec_id')
            ->leftjoin('vehicle_specification_details', 'vehicle_specification_details.vehicle_specifications_id', '=', 'vehicle_specifications.vehicle_spec_id')
            ->where('vehicle_specifications.organization_id', $organization_id)
            ->where('vehicle_specifications.pricing', 1)
            ->groupby('vehicle_specifications.vehicle_spec_id')
            ->get();

        return view('trade_wms.item_price_list', compact('vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant_id', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_segment', 'specifications', 'state', 'city', 'title', 'payment', 'terms', 'group_name'));
    }

    public function inventory_item_search(Request $request)
    {
        // dd($request->all());
        $keyword = $request->input('term');
        $organization_id = Session::get('organization_id');

        /*
         * $query = InventoryItem::select('inventory_items.*')
         * ->leftjoin('inventory_categories','inventory_items.category_id','=','inventory_categories.id');
         * $query->where('inventory_items.name','LIKE','%'. $keyword.'%');
         * $query->where('inventory_categories.category_type_id','=',2);
         * $query->where('inventory_items.organization_id',$organization_id);
         *
         * $inventory_item_search = $query->take(10)->get();
         */

        $query = InventoryItem::select('inventory_items.*')->leftjoin('global_item_models', 'inventory_items.global_item_model_id', '=', 'global_item_models.id')
            ->leftjoin('global_item_categories', 'global_item_models.category_id', '=', 'global_item_categories.id')
            ->leftjoin('global_item_main_categories', 'global_item_categories.main_category_id', '=', 'global_item_main_categories.id');
        $query->where('inventory_items.name', 'LIKE', '%' . $keyword . '%');
        $query->where('global_item_main_categories.category_type_id', '=', 2);
        $query->where('inventory_items.organization_id', $organization_id);

        $inventory_item_search = $query->take(10)->get();

        $inventory_item_array = [];

        foreach ($inventory_item_search as $value) {
            $price = Custom::get_least_closest_date(json_decode($value->sale_price_data, true));
            // dd($price);
            $list_price = $price['list_price'];
            // dd($list_price);
            $inventory_item_array[] = [
                'id' => $value->id,
                'label' => $value->name,
                'name' => $value->name,
                'price' => $list_price
            ];
            // dd($inventory_item_array);
        }

        return response()->json($inventory_item_array);
    }

    public function price_list_search(Request $request)
    {
        // dd($request->all());
        $organization_id = Session::get('organization_id');

        $item_name = $request->item_name;
        $vehicle_segment = $request->vehicle_segment;

        $data = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'inventory_items.sale_price_data', 'inventory_items.selling_price', 'inventory_items.tax_id', 'vehicle_segments.id as segment_id', 'vehicle_segments.display_name as segment', 'wms_price_lists.id AS vehicle_price_list_id', 'wms_price_lists.price AS price', 'tax_groups.display_name as tax', 'inventory_items.base_price')->leftJoin('vehicle_segments', 'vehicle_segments.organization_id', '=', 'inventory_items.organization_id')
            ->leftjoin('global_item_models', 'inventory_items.global_item_model_id', '=', 'global_item_models.id')
            ->leftjoin('global_item_categories', 'global_item_models.category_id', '=', 'global_item_categories.id')
            ->leftjoin('global_item_main_categories', 'global_item_categories.main_category_id', '=', 'global_item_main_categories.id')
            ->leftjoin('tax_groups', 'tax_groups.id', '=', 'inventory_items.tax_id')
            ->
        // ->leftjoin('group_tax','group_tax.group_id','=','inventory_items.tax_id')

        // ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')

        leftJoin('wms_price_lists', function ($join) {

            $join->on('wms_price_lists.vehicle_segments_id', '=', 'vehicle_segments.id');
            $join->on('wms_price_lists.inventory_item_id', '=', 'inventory_items.id');
        })
            ->where('inventory_items.organization_id', $organization_id)
            ->where('global_item_main_categories.category_type_id', '=', 2);

        if (! empty($item_name)) {
            $data->where('inventory_items.name', 'LIKE', $item_name);
        }
        if (! empty($vehicle_segment)) {
            $data->where('vehicle_segments.id', 'LIKE', $vehicle_segment);
        }

        $searched_datas = $data->get();

        foreach ($searched_datas as $value) {
            $name = json_decode($value->sale_price_data)[0];
            $value['list_price'] = $name->list_price;
        }

        // dd($searched_datas);
        return response()->json([
            'status' => 1,
            'data' => $searched_datas
        ]);
    }

    public function price_list_search_org(Request $request)
    {
        // dd($request->all());
        $organization_id = Session::get('organization_id');
        $item_name = $request->item_name;
        $vehicle_segment = $request->vehicle_segment;
        $spec1 = $request->spec1;
        $spec2 = $request->spec2;

        $data = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'inventory_items.sale_price_data', 'inventory_items.selling_price', 'inventory_items.tax_id', 'taxes.value as tax_value', 'vehicle_segments.id as segment_id', 'vehicle_segments.display_name as segment', 'wms_price_lists.id AS vehicle_price_list_id', 'wms_price_lists.price AS price', 'tax_groups.display_name as tax', 'spec1.id AS spec1_id', 'spec2.id AS spec2_id', 'spec1.name AS specname1', 'spec2.name AS specname2', 'inventory_items.base_price')->leftjoin('global_item_models', 'inventory_items.global_item_model_id', '=', 'global_item_models.id')
            ->leftjoin('global_item_categories', 'global_item_models.category_id', '=', 'global_item_categories.id')
            ->leftjoin('global_item_main_categories', 'global_item_categories.main_category_id', '=', 'global_item_main_categories.id')
            ->leftjoin('vehicle_segments', 'vehicle_segments.organization_id', '=', 'inventory_items.organization_id')
            ->leftjoin('wms_vehicle_specs', 'wms_vehicle_specs.organization_id', '=', 'inventory_items.organization_id')
            ->leftjoin('vehicle_specification_details AS spec1', 'spec1.vehicle_specifications_id', '=', 'wms_vehicle_specs.pricing_spec1')
            ->leftjoin('vehicle_specification_details AS spec2', 'spec2.vehicle_specifications_id', '=', 'wms_vehicle_specs.pricing_spec2')
            ->leftjoin('tax_groups', 'tax_groups.id', '=', 'inventory_items.tax_id')
            ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
            ->leftjoin('taxes', 'taxes.id', '=', 'group_tax.tax_id');

        $data->leftJoin('wms_price_lists', function ($join) use ($vehicle_segment) {

            if ($vehicle_segment == null) {
                $join->on('wms_price_lists.vehicle_segments_id', '=', 'vehicle_segments.id');
            } else {
                $join->on('wms_price_lists.vehicle_segments_id', '=', 'vehicle_segments.id');
            }

            $join->on('wms_price_lists.inventory_item_id', '=', 'inventory_items.id');

            /*
             * if($spec1 != null)
             * {
             * $join->on('wms_price_lists.spec_value1', '=', 'spec1.id');
             * }
             * if($spec2 != null)
             * {
             * $join->on('wms_price_lists.spec_value2', '=', 'spec2.id');
             * }
             */
        });

        $data->where('inventory_items.organization_id', $organization_id);
        $data->where('global_item_main_categories.category_type_id', '=', 2);
        // $data->groupby('inventory_items.id','vehicle_segments.id');

        if (! empty($item_name)) {
            $data->where('inventory_items.name', 'LIKE', $item_name);
        }
        if (! empty($vehicle_segment)) {
            $data->where('vehicle_segments.id', 'LIKE', $vehicle_segment);
        }

        /*
         * if(!empty($spec1)){
         *
         * $data->where('spec1.id','LIKE',$spec1);
         * }
         * if(!empty($spec2)){
         * $data->where('spec2.id','LIKE',$spec2);
         * }
         */

        $searched_datas = $data->get();

        foreach ($searched_datas as $value) {
            $name = json_decode($value->sale_price_data)[0];
            $value['list_price'] = $name->list_price;
        }

        // dd($searched_datas);

        return response()->json([
            'status' => 1,
            'data' => $searched_datas
        ]);
    }

    public function price_list_update(Request $request)
    {
        // dd($request->all());
        $organization_id = Session::get('organization_id');
        $price_list_id = $request->price_list_id;
        $item_id = $request->item_id;
        $segment_id = $request->segment_id;
        $base_price = $request->base_price;
        $item_price = $request->item_price;
        $spec1_id = $request->spec1_id;
        $spec2_id = $request->spec2_id;
        $tax = $request->tax;

        if (count($item_id) > 0) {
            for ($i = 0; $i < count($item_id); $i ++) {
                if ($tax[$i] == "NaN") {
                    $tax[$i] = 0;
                }
                $price = $item_price[$i] / (1 + $tax[$i] / 100);

                $item_prices = WmsPriceList::updateOrCreate([
                    'id' => $price_list_id[$i]
                ], [
                    'inventory_item_id' => $item_id[$i],
                    'vehicle_segments_id' => $segment_id[$i],
                    'base_price' => $base_price[$i],
                    'price' => Custom::two_decimal($price),
                    'spec_value1' => $spec1_id[$i],
                    'spec_value2' => $spec2_id[$i],
                    'organization_id' => $organization_id,
                    'created_by' => Auth::user()->id,
                    'last_modified_by' => Auth::user()->id
                ]);
            }
        }

        /*
         * if($item_id != null)
         * {
         * if(count($item_id) > 0)
         * {
         * for($i=0;$i<count($item_id);$i++) {
         * if($item_id[$i] != "") {
         * DB::table('wms_price_lists')->insert([
         * 'inventory_item_id' => $item_id[$i],
         * 'vehicle_segments_id' => $segment_id[$i],
         * 'base_price' => $base_price[$i],
         * 'price' => $item_price[$i],
         * 'organization_id' => $organization_id,
         *
         * ]);
         * }
         * }
         * }
         * }
         */

        return response()->json([
            'message' => 'Price List' . config('constants.flash.added')
        ]);
    }

    public function price_list_status_approval(Request $request)
    {
        WmsPriceList::where('id', $request->input('id'))->update([
            'status' => $request->input('status')
        ]);

        return response()->json([
            "status" => $request->input('status')
        ]);
    }

    public function maintanance_history_report()
    {
        return view('trade_wms.maintanance_history');
    }

    public function service_history_report()
    {
        // dd($request->all());
        $vehicles_register = VehicleRegisterDetail::where('status', '1')->pluck('registration_no', 'id');
        $vehicles_register->prepend('Select Vehicle', '');

        return view('trade_wms.service_history', compact('vehicles_register'));
    }

    public function search_service_history(Request $request)
    {
        $organization_id = Session::get('organization_id');

        $owner_name = $request->input('name');
        $registration_id = $request->input('registration_no');
        $from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
        $to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');

        $service_history = VehicleRegisterDetail::select('vehicle_register_details.id', 'persons.first_name AS owner_name', 'vehicle_register_details.registration_no', 'vehicle_makes.name AS make_name', 'vehicle_models.name AS model_name', 'vehicle_variants.name AS variant_name', 'wms_transactions.vehicle_next_visit', 'wms_transactions.vehicle_note', 'wms_transactions.vehicle_note', 'vehicle_usages.name AS usage_name', 'transactions.date', 'transactions.total', 'organizations.name AS organization_name', 'inventory_items.name AS item_name', 'transaction_items.amount AS item_amount');
        $service_history->leftJoin('vehicle_configurations', 'vehicle_configurations.id', '=', 'vehicle_register_details.vehicle_configuration_id');
        $service_history->leftJoin('persons', 'persons.id', '=', 'vehicle_register_details.owner_id');
        $service_history->leftJoin('wms_transactions', 'wms_transactions.registration_id', '=', 'vehicle_register_details.id');
        $service_history->leftJoin('transactions', 'transactions.id', '=', 'wms_transactions.id');
        $service_history->leftJoin('transaction_items', 'transaction_items.transaction_id', '=', 'transactions.id');
        $service_history->leftJoin('organizations', 'organizations.id', '=', 'transactions.organization_id');
        $service_history->leftJoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id');
        $service_history->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_register_details.vehicle_make_id');
        $service_history->leftJoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_register_details.vehicle_model_id');
        $service_history->leftJoin('vehicle_variants', 'vehicle_variants.id', '=', 'vehicle_register_details.vehicle_variant_id');
        $service_history->leftJoin('vehicle_usages', 'vehicle_usages.id', '=', 'vehicle_register_details.vehicle_usage_id');

        /*
         * $service_history = VehicleRegisterDetail::select('vehicle_register_details.id', 'persons.first_name AS owner_name', 'vehicle_register_details.registration_no', 'vehicle_makes.name AS make_name', 'vehicle_models.name AS model_name', 'vehicle_variants.name AS variant_name', 'wms_transactions.vehicle_next_visit', 'wms_transactions.vehicle_note', 'wms_transactions.vehicle_note', 'vehicle_usages.name AS usage_name', 'transactions.date', 'transactions.total', 'organizations.name AS organization_name', DB::raw('GROUP_CONCAT(inventory_items.name) AS item_name'), DB::raw('GROUP_CONCAT(transaction_items.amount) AS item_amount'));
         * $service_history->leftJoin('vehicle_configurations', 'vehicle_configurations.id','=','vehicle_register_details.vehicle_configuration_id');
         * $service_history->leftJoin('persons', 'persons.id','=','vehicle_register_details.owner_id');
         * $service_history->leftJoin('wms_transactions', 'wms_transactions.registration_id','=','vehicle_register_details.id');
         * $service_history->leftJoin('transactions', 'transactions.id','=','wms_transactions.id');
         * $service_history->leftJoin('transaction_items', 'transaction_items.transaction_id','=','transactions.id');
         * $service_history->leftJoin('organizations', 'organizations.id','=','transactions.organization_id');
         * $service_history->leftJoin('inventory_items', 'inventory_items.id','=','transaction_items.item_id');
         * $service_history->leftJoin('vehicle_makes', 'vehicle_makes.id','=','vehicle_register_details.vehicle_make_id');
         * $service_history->leftJoin('vehicle_models', 'vehicle_models.id','=','vehicle_register_details.vehicle_model_id');
         * $service_history->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');
         * $service_history->leftJoin('vehicle_usages', 'vehicle_usages.id','=','vehicle_register_details.vehicle_usage_id');
         */

        if ($owner_name != null) {
            $service_history->where('persons.first_name', 'LIKE', '%' . $owner_name . '%');
        }
        if ($registration_id != null) {
            $service_history->Orwhere('vehicle_register_details.id', $registration_id);
        }
        /*
         * if($from_date != null && $to_date != null) {
         * $service_history->whereBetween('transactions.date', [$from_date, $to_date]);
         * }
         */
        $service_history->orderby('vehicle_register_details.id');
        $reports = $service_history->get();
        // dd($reports);

        return response()->json([
            'status' => 1,
            'message' => 'Search Service History Result',
            'data' => $reports
        ]);
    }

    public function business_customer_report()
    {
        return view('trade_wms.business_customer');
    }

    public function customer_promotion()
    {
        $organization_id = Session::get('organization_id');

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name', 'id');
        $title->prepend('Title', '');

        $payment = PaymentMode::where('status', '1')->pluck('display_name', 'id');
        $payment->prepend('Select Payment Method', '');

        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term', '');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('display_name', 'id');
        $group_name->prepend('Select Group Name', '');

        return view('trade_wms.customer_promotion', compact('group_name', 'organization_id', 'state', 'city', 'title', 'payment', 'terms', 'group_name'));
    }

    public function upload_image_sms(Request $request)
    {

        // dd($request->all());
        $organization_id = Session::get('organization_id');
        $files = $request->file('file');
        if ($request->hasFile('file')) {
            $path_name = public_path() . '/sms_images/Organization_id-' . $organization_id;
            if (file_exists($path_name)) {
                array_map('unlink', glob($path_name . '/*.*'));
            }
            foreach ($files as $file) {
                $imageName = $file->getClientOriginalName();
                $path = '/sms_images/Organization_id-' . $organization_id;
                if (! file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }
                $file->move(public_path($path), $imageName);
            }
        }

        // return view('trade_wms.image_sms',compact('all_files','organization_id','file_names'));
    }

    public function view_image($org_id)
    {
        $organization_id = Session::get('organization_id');
        $path = '/sms_images/Organization_id-' . $org_id;

        $path_name = public_path($path);

        $all_files = glob($path_name . "/*.*");

        $file_names = File::allFiles($path_name . '/');

        return view('trade_wms.image_sms', compact('all_files', 'org_id', 'file_names'));
    }

    public function send_sms(Request $request)
    {
        $customer_name = $request->customer_name;
        $message = $request->message;
        $mobile_no = $request->mobile_number;
        $subject = "Customer Promotion";

        Log::info("WmsVehicleReportController->send_sms :- Inside " . json_encode($request->all()));

        if ($mobile_no) {
            $sms_notify_model = $this->SmsNotificationService->save($mobile_no, $subject, $customer_name, $message, Session::get('organization_id'), "PROMOTION");

            if ($sms_notify_model['message'] == pStatusSuccess()) {
                Log::info("WmsVehicleReportController->send_sms :- Stored Success And Return");
                Custom::add_addon('sms');
                Log::info("WmsVehicleReportController->send_sms :- Return ");
                return response()->json([
                    'status' => 1,
                    'message' => "SMS Sent to all the selected Customers",
                    'data' => []
                ]);
            } else {
                Log::info("WmsVehicleReportController->send_sms :- Stored failed And Return");

                return response()->json([
                    'status' => 0,
                    'message' => "failed to send sms"
                ]);
            }
        }
    }

    public function receivables_report(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $customer_id = $request->input('customer');

        $organization_id = Session::get('organization_id');
        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name', 'id');
        $title->prepend('Title', '');

        $payment = PaymentMode::where('status', '1')->pluck('display_name', 'id');
        $payment->prepend('Select Payment Method', '');

        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term', '');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('display_name', 'id');
        $group_name->prepend('Select Group Name', '');

        $name = 'customer';
        $customer = People::leftJoin('people_person_types', 'people_person_types.people_id', '=', 'people.id')->leftjoin('account_person_types', 'account_person_types.id', '=', 'people_person_types.person_type_id')
            ->where('people.organization_id', $organization_id)
            ->where('account_person_types.name', $name)
            ->groupBy('people.id')
            ->orderBy('people.first_name')
            ->pluck('people.display_name', 'people.id');
        $customer->prepend('Select Customer', '');
        // dd($customer);
        $report = AccountEntry::select('account_entries.id', 'transactions.id as transaction_id', 'account_entries.voucher_no', 'account_entries.reference_voucher_id', 'transactions.order_no', 'account_transactions.amount', 'transactions.name as customer_name', 'account_entries.date')->leftjoin('transactions', 'transactions.id', '=', 'account_entries.reference_voucher_id')
            ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
            ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
            ->where('account_entries.organization_id', $organization_id)
            ->where('account_vouchers.name', '=', 'wms_receipt')
            ->where('account_person_types.name', '=', 'customer');
        $report->leftJoin('people', function ($join) {
            $join->on('people.person_id', '=', 'transactions.people_id')
                ->where('transactions.user_type', '0')
                ->orOn('people.business_id', '=', 'transactions.people_id')
                ->where('transactions.user_type', '1');
        });
        $report->leftJoin('people AS business', function ($join) use ($organization_id) {
            $join->on('business.business_id', '=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
        });
        $report->leftjoin('people_person_types', 'people_person_types.people_id', '=', 'people.id')->leftjoin('account_person_types', 'account_person_types.id', '=', 'people_person_types.person_type_id');

        if ($request->input('from_date')) {
            $start_date = date_string($from_date);

            $report->where('account_entries.date', '>=', $start_date);
        }
        if ($request->input('to_date')) {
            $end_date = date_string($to_date);
            $report->where('account_entries.date', '<=', $end_date);
        }
        if ($request->input('customer')) {
            $customer_id = $request->input('customer');
            $report->where('people.id', $customer_id);
        }
        $reports = $report->groupby('account_entries.id')->get();
        // dd($reports);
        if ($request->ajax()) {
            if (count($reports) > 0) {
                return response()->json([
                    'status' => 1,
                    'data' => $reports
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => "No data available."
                ]);
            }
        }
        return view('trade_wms.receivables_report', compact('state', 'city', 'title', 'payment', 'terms', 'group_name', 'customer', 'reports'));
    }

    public function view_receipt_details($id)
    {
        $view_receipt_detail = AccountEntry::select('account_entries.id', 'account_entries.voucher_no', 'account_vouchers.display_name AS voucher_name', 'account_entries.cheque_no', 'account_entries.date', 'account_entries.reference_voucher_id', 'transactions.order_no', 'account_entries.payment_mode_id', 'payment_modes.name as payment', 'account_transactions.debit_ledger_id', 'account_transactions.credit_ledger_id', 'al1.name as credit_ledger', 'al2.name as debit_ledger', 'account_entries.description', 'account_transactions.amount')->leftjoin('transactions', 'transactions.id', '=', 'account_entries.reference_voucher_id')
            ->leftjoin('payment_modes', 'payment_modes.id', '=', 'account_entries.payment_mode_id')
            ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
            ->leftjoin('account_ledgers AS al1', 'al1.id', '=', 'account_transactions.credit_ledger_id')
            ->leftjoin('account_ledgers AS al2', 'al2.id', '=', 'account_transactions.debit_ledger_id')
            ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
            ->where('account_vouchers.name', '=', 'wms_receipt')
            ->where('account_entries.id', $id)
            ->first();

        // dd($view_receipt_detail);

        return view('trade_wms.receivables_report_view', compact('view_receipt_detail'));
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
