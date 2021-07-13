<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\BusinessCommunicationAddress;
use App\HrmEmployeeWorkingPeriod;
use App\BusinessProfessionalism;
use App\OrganizationPackage;
use App\BusinessAddressType;
use App\BusinessFieldValue;
use App\AccountLedgerType;
use App\SubscriptionPlan;
use App\AddonPricing;
use App\PlanAccountType;
use App\BusinessNature;
use App\HrmDesignation;
use App\PaymentMethod;
use App\BusinessField;
use App\AccountGroup;
use App\BusinessInfo;
use App\Organization;
use App\HrmEmployee;
use App\Permission;
use Carbon\Carbon;
use App\HrmBranch;
use App\Business;
use App\Setting;
use App\WfmLabel;
use App\Country;
use App\Package;
use App\Module;
use App\Person;
use App\Custom;
use App\Addon;
use App\State;
use App\Term;
use App\User;
use App\Role;
use App\City;
use App\Notification\Service\SmsNotificationService;
use Validator;
use Response;
use Session;
use Auth;
use DB;

class RegisterBusinessController extends Controller
{

    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
    }

    public function create(Request $request)
    {
        $account_type_id = PlanAccountType::where('name', 'business')->first();

        $businessnature = BusinessNature::select('display_name AS name', 'id')->get();

        $subscription_plan = SubscriptionPlan::where('status', '1')->where('account_type_id', '2')->pluck('display_name', 'id');
        $subscription_plan->prepend('Choose Plan', '');

        $businessprofessionalism = BusinessProfessionalism::select('display_name AS name', 'id')->get();
        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::where('country_id', $country_id)->orderBy('name')
            ->orderby('name')
            ->pluck('name', 'id');
        $state->prepend('Select State', '');

        $businessinformation = BusinessField::where('status', '1')->get();

        $packages = Package::select('packages.id', 'packages.display_name', 'packages.image', DB::raw('GROUP_CONCAT(modules.display_name SEPARATOR " + ") AS modules'))->where('packages.status', '1')
            ->leftjoin('package_modules', 'packages.id', '=', 'package_modules.package_id')
            ->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id')
            ->where('packages.account_type_id', $account_type_id->id)
            ->groupby('id')
            ->get();

        return view('business_create', compact('businessnature', 'businessprofessionalism', 'state', 'businessinformation', 'packages', 'request', 'subscription_plan'));
    }

    public function get_package_details(Request $request)
    {
        $package = $request->input('package');
        $plan = $request->input('plan');

        $module_details = DB::table('package_modules')->select('modules.display_name AS module')
            ->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id')
            ->where('package_modules.package_id', $package)
            ->get();

        /*
         * foreach ($module_details as $key => $value) {
         *
         * dd($module_details[$key]->module);
         * }
         */

        $plan_details = DB::table('package_plan')->select('subscription_plans.name AS plan_name', 'package_plan.price')
            ->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'package_plan.plan_id')
            ->where('package_plan.package_id', $package)
            ->where('package_plan.plan_id', $plan)
            ->get();

        /*
         * foreach ($plan_details as $key => $value) {
         *
         * dd($plan_details[$key]->module);
         * }
         */

        return response()->json([
            'result' => $plan_details,
            'module_result' => $module_details
        ]);
    }

    public function store(Request $request)
    {
        $business_id = "";

        if ($request->input('business_id')) {
            $business_id = $request->input('business_id');
        } else {

            $v = Validator::make($request->all(), [
                'business_name.*' => 'required',
                'business_nature.*' => 'required',
                'business_professionalism.*' => 'required',
                'mobile.*' => 'required|digits:10',
                'city.*' => 'required',
                'package_id' => 'required', // |array|between:1,2
                'subscription_plan' => 'required'
            ]);

            if ($v->fails()) {
                $validator = $v->messages()->all();
                // return view('business_create')->withErrors($validator);
                return redirect()->back()->withErrors($validator);
            }

            $business = new Business();

            $business->business_name = $request->input('business_name');
            $business->alias = $request->input('alias');

            if ($request->input('business_nature') != null) {
                $business->business_nature_id = $request->input('business_nature');
            }

            if ($request->input('business_professionalism') != null) {
                $business->business_professionalism_id = $request->input('business_professionalism');
            }

            $anniversary_date = $request->input('anniversary_date');

            if ($anniversary_date != "") {
                $anniversary_date = explode('-', $request->input('anniversary_date'));
                $business->anniversary_date = $anniversary_date[2] . '-' . $anniversary_date[1] . '-' . $anniversary_date[0];
            } else {
                $business->anniversary_date = null;
            }

            $city = City::select('name')->where('id', $request->input('city'))
                ->first()->name;

            $bcrm_code = Custom::business_crm($city, $request->input('mobile'), $request->input('business_name'));

            $business->gst = $request->input('gst');

            $business->bcrm_code = $bcrm_code;
            $business->save();
            Custom::userby($business, true);

            $address_type = BusinessAddressType::where('name', 'business')->first();

            if ($business->id) {
                $businesscommunicationaddress = new BusinessCommunicationAddress();
                $businesscommunicationaddress->placename = $request->input('business_name');
                $businesscommunicationaddress->address_type = $address_type->id;
                $businesscommunicationaddress->city_id = $request->input('city');
                $businesscommunicationaddress->mobile_no = $request->input('mobile');
                $businesscommunicationaddress->mobile_no_prev = $request->input('mobile');
                $businesscommunicationaddress->phone = $request->input('phone');
                $businesscommunicationaddress->phone_prev = $request->input('phone');
                $businesscommunicationaddress->email_address = $request->input('email');
                $businesscommunicationaddress->email_address_prev = $request->input('email');
                $businesscommunicationaddress->web_address = $request->input('web');
                $businesscommunicationaddress->web_address_prev = $request->input('web');
                $businesscommunicationaddress->business_id = $business->id;
                $businesscommunicationaddress->save();
                Custom::userby($businesscommunicationaddress, true);

                $business_informations = $request->except('_token', 'business_name', 'alias', 'business_nature', 'business_professionalism', 'mobile', 'state', 'city', 'email', 'phone', 'web', 'anniversary_date', 'business_informations_key', 'business_informations_value', 'modules', 'package_id', 'pan', 'tin', 'gst');

                foreach ($business_informations as $business_information => $value) {
                    if ($value != "") {
                        $information = new BusinessFieldValue();
                        $information->businesses_id = $business->id;
                        // $information->business_informations_id = $business_information;
                        // $information->business_information = $value;
                        $information->business_field_id = $value;
                        $information->business_information = $business_information;
                        $information->save();
                    }
                }

                $business_id = $business->id;
            }
        }

        $mybusiness = Business::findOrFail($business_id);

        $organization = new Organization();
        $organization->name = $mybusiness->business_name;
        $organization->business_id = $business_id;
        $organization->status = '1';
        $organization->save();

        Session::put('account_type', 'business');
        Session::put('organization_id', $organization->id);

        $hrmbranches = new HrmBranch();
        $hrmbranches->id = $business_id;
        $hrmbranches->organization_id = $organization->id;
        $hrmbranches->branch_name = $mybusiness->business_name;
        $hrmbranches->created_by = $request->user()->id;
        $hrmbranches->last_modified_by = $request->user()->id;
        $hrmbranches->save();

        /*
         * $hrm_salary_scale = new HrmSalaryScale;
         * $hrm_salary_scale->name = "zero_scale";
         * $hrm_salary_scale->code = "ZS";
         */

        $hrmbranches->organization_id = $organization->id;
        $hrmbranches->branch_name = $mybusiness->business_name;
        $hrmbranches->created_by = $request->user()->id;
        $hrmbranches->last_modified_by = $request->user()->id;
        $hrmbranches->save();

        $cash_payment = new PaymentMethod();
        $cash_payment->name = "Cash";
        $cash_payment->display_name = "Cash";
        $cash_payment->organization_id = $organization->id;
        $cash_payment->created_by = $request->user()->id;
        $cash_payment->last_modified_by = $request->user()->id;
        $cash_payment->save();

        $cheque_payment = new PaymentMethod();
        $cheque_payment->name = "Cheque";
        $cheque_payment->display_name = "Cheque";
        $cheque_payment->organization_id = $organization->id;
        $cheque_payment->created_by = $request->user()->id;
        $cheque_payment->last_modified_by = $request->user()->id;
        $cheque_payment->save();

        $online_payment = new PaymentMethod();
        $online_payment->name = "Online Payment";
        $online_payment->display_name = "Online Payment";
        $online_payment->organization_id = $organization->id;
        $online_payment->created_by = $request->user()->id;
        $online_payment->last_modified_by = $request->user()->id;
        $online_payment->save();

        $receipt_term = new Term();
        $receipt_term->name = "on_receipt";
        $receipt_term->display_name = "Due on receipt";
        $receipt_term->days = 0;
        $receipt_term->organization_id = $organization->id;
        $receipt_term->created_by = $request->user()->id;
        $receipt_term->last_modified_by = $request->user()->id;
        $receipt_term->save();

        $fifteen_term = new Term();
        $fifteen_term->name = "net_15";
        $fifteen_term->display_name = "Net 15";
        $fifteen_term->days = 15;
        $fifteen_term->organization_id = $organization->id;
        $fifteen_term->created_by = $request->user()->id;
        $fifteen_term->last_modified_by = $request->user()->id;
        $fifteen_term->save();

        $thirty_term = new Term();
        $thirty_term->name = "net_30";
        $thirty_term->display_name = "Net 30";
        $thirty_term->days = 30;
        $thirty_term->organization_id = $organization->id;
        $thirty_term->created_by = $request->user()->id;
        $thirty_term->last_modified_by = $request->user()->id;
        $thirty_term->save();

        $person = Person::findOrFail($request->user()->person_id);

        $person->organizations()->attach($organization->id);

        // WFM LABEL CREATE When Create an Organization on 17-12-2018

        $NewLabel = new WfmLabel();
        $NewLabel->phrase = "Size";
        $NewLabel->organization_id = $organization->id;
        $NewLabel->save();

        $NewLabel2 = new WfmLabel();
        $NewLabel2->phrase = "Worth";
        $NewLabel2->organization_id = $organization->id;
        $NewLabel2->save();

        // WFM LABEL CREATE When Create an Organization

        $user = User::find($request->user()->id);
        $my_organization = $organization::find($organization->id);

        $selected_modules = DB::table('package_modules')->select('module_id')
            ->where('package_id', $request->input('package_id'))
            ->get();

        foreach ($selected_modules as $value) {
            $my_organization->modules()->attach($value);
        }

        /*
         * $module_array = array("utility");
         * $modules = Module::select('id')->whereIn('name', $module_array)->get();
         *
         * foreach ($modules as $module) {
         * $my_organization->modules()->attach($module->id);
         * }
         */

        $roles = [
            [
                "name" => "admin",
                "display_name" => "Admin",
                "description" => "Admin has every previleges"
            ],
            [
                "name" => "accountant",
                "display_name" => "Accountant",
                "description" => "Accountant has previlege on Account Modules"
            ],
            [
                "name" => "hr_manager",
                "display_name" => "HR Manager",
                "description" => "HR Manager has every previleges on HRM Modules"
            ],
            [
                "name" => "employee",
                "display_name" => "Employee",
                "description" => "Employee has basic previleges"
            ]
        ];

        foreach ($roles as $value) {
            $role = new Role();
            $role->name = $value['name'];
            $role->organization_id = $organization->id;
            $role->display_name = $value['display_name'];
            $role->description = $value['description'];
            $role->save();
            Custom::userby($role, true);

            if ($value['name'] == "admin") {
                $permissions = Permission::select('id')->get();
            } else if ($value['name'] == "accountant") {
                $permissions = Permission::select('id')->whereIn('module', [
                    'books',
                    'inventory',
                    'trade',
                    'trade_wms'
                ])
                    ->orWhere('name', 'books')
                    ->orWhere('name', 'trade')
                    ->get();
            } else if ($value['name'] == "hr_manager") {
                $permissions = Permission::select('id')->whereIn('module', [
                    'hrm',
                    'customer'
                ])
                    ->orWhere('name', 'hrm')
                    ->orWhere('name', 'trade')
                    ->orWhere('name', 'trade_wms')
                    ->get();
            } else if ($value['name'] == "employee") {
                $permissions = Permission::select('id')->whereIn('name', [
                    'hrm',
                    'permissions-list',
                    'permissions-create',
                    'permissions-edit',
                    'permissions-delete',
                    'leaves-list',
                    'leaves-create',
                    'leaves-edit',
                    'leaves-delete'
                ])
                    ->orWhere('name', 'hrm')
                    ->get();
            }

            foreach ($permissions as $permission) {
                $role->attachPermission($permission->id);
            }
        }

        $adminrole = Role::where('name', 'admin')->where('organization_id', $organization->id)->first();

        $user->roles()->attach($adminrole->id, [
            'organization_id' => $organization->id
        ]);

        Custom::createAccounts($organization->id, $organization, false);

        // $free_plan = SubscriptionPlan::where('name', 'free_business')->first()->id;

        $organization_pack = new OrganizationPackage();
        $organization_pack->package_id = $request->input('package_id');
        $organization_pack->organization_id = $organization->id;
        // $organization_pack->plan_id = $free_plan;
        $organization_pack->plan_id = $request->input('subscription_plan');
        $organization_pack->added_on = Carbon::now()->format('Y-m-d');
        $organization_pack->expire_on = Carbon::now()->addDays(15)->format('Y-m-d');
        $organization_pack->status = 1;
        $organization_pack->save();

        $addons = OrganizationPackage::select('organization_packages.plan_id', 'subscription_addons.addon_id', 'subscription_addons.value')->leftjoin('subscription_addons', 'subscription_addons.subscription_plan_id', '=', 'organization_packages.plan_id')
            ->where('organization_packages.organization_id', $organization->id)
            ->where('organization_packages.id', $organization_pack->id)
            ->get();

        foreach ($addons as $addon) {

            DB::table('addon_organization')->insert([
                'addon_id' => $addon->addon_id,
                'organization_id' => $organization->id,
                'used' => 0,
                'value' => $addon->value
            ]);
        }

        $settings_array = [
            'ledgergroup_approval',
            'ledger_approval'
        ];

        foreach ($settings_array as $settings) {
            $setting = new Setting();
            $setting->name = $settings;
            if (Organization::checkModuleExists('books', Session::get('organization_id'))) {
                $setting->status = 0;
            } else {
                $setting->status = 1;
            }

            $setting->organization_id = Session::get('organization_id');
            $setting->save();
        }

        $setting = new Setting();
        $setting->name = 'theme';
        $setting->status = 1;
        $setting->data = json_encode([
            "header" => "bg-gradient-8",
            "sidebar" => "gradient bg-gradient-8"
        ]);
        $setting->user_id = Auth::id();
        $setting->organization_id = Session::get('organization_id');
        $setting->save();

        Session::put('theme_header', "bg-gradient-8");
        Session::put('theme_sidebar', "gradient bg-gradient-8");

        Custom::createHrm($organization->id, $organization);
        Custom::createTrade($organization->id, $organization);

        Session::flash('flash_message', 'Organization successfully added!');

        $person = Person::find(Auth::user()->person_id);

        $hrm_employee = new HrmEmployee();
        $hrm_employee->person_id = Auth::user()->person_id;
        $hrm_employee->employee_code = '001';
        $hrm_employee->first_name = $person->first_name;
        $hrm_employee->last_name = $person->last_name;
        $hrm_employee->email = Auth::user()->email;
        $hrm_employee->phone_no = Auth::user()->mobile;
        $hrm_employee->organization_id = Session::get('organization_id');
        $hrm_employee->save();

        $ledgergroup = AccountGroup::where('name', 'employees')->where('organization_id', Session::get('organization_id'))->first();
        $personal_ledger = AccountLedgerType::where('name', 'personal')->first();
        $organization = Organization::findOrFail(Session::get('organization_id'));

        $hrm_employee->ledger_id = Custom::create_ledger($hrm_employee->first_name . "_" . $hrm_employee->last_name . "_" . $hrm_employee->employee_code, $organization, $hrm_employee->first_name . " " . $hrm_employee->last_name . " " . $hrm_employee->employee_code, $personal_ledger->id, $hrm_employee->person_id, null, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', '1', '1', Session::get('organization_id'), false);
        $hrm_employee->save();

        $employee_id = $hrm_employee->id;

        $organization_person = DB::table('organization_person')->where('person_id', $hrm_employee->person_id)
            ->where('organization_id', Session::get('organization_id'))
            ->first();

        if ($organization_person == null) {
            DB::table('organization_person')->insert([
                'person_id' => $hrm_employee->person_id,
                'organization_id' => Session::get('organization_id')
            ]);
        }

        $work_periods = new HrmEmployeeWorkingPeriod();
        $work_periods->employee_id = $employee_id;
        $work_periods->joined_date = date('Y-m-d');
        $work_periods->branch_id = $business_id;
        $work_periods->save();

        $designation_id = HrmDesignation::where('organization_id', Session::get('organization_id'))->first();

        if ($designation_id->id != null) {
            $designation = DB::table('hrm_employee_designation')->insert([
                'employee_id' => $employee_id,
                'designation_id' => $designation_id->id
            ]);
        }

        /* null ledger addon is created. so delete that addon only */

        $addon_deletes = DB::table('addon_organization')->where('organization_id', $organization->id)->get();

        foreach ($addon_deletes as $addon_delete) {

            DB::table('addon_organization')->where('organization_id', $addon_delete->organization_id)
                ->where('addon_id', 1)
                ->where('value', NULL)
                ->delete();
        }

        /* End */

        Session::put('business', $mybusiness->alias);
        Session::put('bcrm_code', $mybusiness->bcrm_code);
        return redirect()->route('dashboard');
    }

    public function send_otp(Request $request)
    {
        Log::info('RegisterBussinessController->send_otp:- Line No 499');
        $business = Business::find($request->business_id);
        $business->otp = Custom::otp(4);
        $business->save();

        $business_data = Business::select('businesses.id', 'businesses.otp', 'business_communication_addresses.email_address', 'business_communication_addresses.mobile_no')->leftJoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id')
            ->leftJoin('business_field_values', 'business_field_values.businesses_id', '=', 'businesses.id')
            ->leftJoin('organizations', 'organizations.business_id', '=', 'businesses.id')
            ->where('businesses.id', $business->id)
            ->first();

        /* SMS Send code */
        $content_addresed_to = $business->name;
        $mobile_no = $business_data->mobile_no;
        $subject = "OTP SEND";
        $message = "$business_data->otp is your OTP to verify your business on PROPEL ERP";
        $sms_notify_model = $this->SmsNotificationService->save($mobile_no, $subject, $content_addresed_to, $message, "", "OTP");

        // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $business_data->mobile_no, "$business_data->otp is your OTP to verify your business on PROPEL ERP");

        return redirect()->route('register_business.business_otp', [
            base64_encode($business->id)
        ]);
    }

    public function business_otp($id)
    {
        $id = base64_decode($id);
        return view('business_otp', compact('id'));
    }

    public function store_business(Request $request)
    {
        // dd($request->all());
        $business_id = $request->input('business_id');

        $mybusiness = Business::select('businesses.*', 'business_natures.name AS nature')->leftjoin('business_natures', 'business_natures.id', '=', 'businesses.business_nature_id')
            ->where('businesses.id', $business_id)
            ->first();

        $organization = new Organization();
        $organization->name = $mybusiness->business_name;
        $organization->business_id = $business_id;
        $organization->status = '1';
        $organization->save();

        Session::put('account_type', 'business');
        Session::put('organization_id', $organization->id);

        $hrmbranches = new HrmBranch();
        $hrmbranches->id = $business_id;
        $hrmbranches->organization_id = $organization->id;
        $hrmbranches->branch_name = $mybusiness->business_name;
        $hrmbranches->created_by = $request->user()->id;
        $hrmbranches->last_modified_by = $request->user()->id;
        $hrmbranches->save();

        $cash_payment = new PaymentMethod();
        $cash_payment->name = "Cash";
        $cash_payment->display_name = "Cash";
        $cash_payment->organization_id = $organization->id;
        $cash_payment->created_by = $request->user()->id;
        $cash_payment->last_modified_by = $request->user()->id;
        $cash_payment->save();

        $cheque_payment = new PaymentMethod();
        $cheque_payment->name = "Cheque";
        $cheque_payment->display_name = "Cheque";
        $cheque_payment->organization_id = $organization->id;
        $cheque_payment->created_by = $request->user()->id;
        $cheque_payment->last_modified_by = $request->user()->id;
        $cheque_payment->save();

        $online_payment = new PaymentMethod();
        $online_payment->name = "Online Payment";
        $online_payment->display_name = "Online Payment";
        $online_payment->organization_id = $organization->id;
        $online_payment->created_by = $request->user()->id;
        $online_payment->last_modified_by = $request->user()->id;
        $online_payment->save();

        $receipt_term = new Term();
        $receipt_term->name = "on_receipt";
        $receipt_term->display_name = "Due on receipt";
        $receipt_term->days = 0;
        $receipt_term->organization_id = $organization->id;
        $receipt_term->created_by = $request->user()->id;
        $receipt_term->last_modified_by = $request->user()->id;
        $receipt_term->save();

        $fifteen_term = new Term();
        $fifteen_term->name = "net_15";
        $fifteen_term->display_name = "Net 15";
        $fifteen_term->days = 15;
        $fifteen_term->organization_id = $organization->id;
        $fifteen_term->created_by = $request->user()->id;
        $fifteen_term->last_modified_by = $request->user()->id;
        $fifteen_term->save();

        $thirty_term = new Term();
        $thirty_term->name = "net_30";
        $thirty_term->display_name = "Net 30";
        $thirty_term->days = 30;
        $thirty_term->organization_id = $organization->id;
        $thirty_term->created_by = $request->user()->id;
        $thirty_term->last_modified_by = $request->user()->id;
        $thirty_term->save();

        $person = Person::findOrFail($request->user()->person_id);

        $organization_person = DB::table('organization_person')->where('organization_id', $organization->id)
            ->where('person_id', $person->id)
            ->first();

        if ($organization_person == null) {
            $person->organizations()->attach($organization->id);
        }

        $user = User::find($request->user()->id);
        $my_organization = $organization::find($organization->id);
        $package = Package::where('name', 'trade')->first();
        $selected_modules = DB::table('package_modules')->select('module_id')
            ->where('package_id', $package->id)
            ->get();

        foreach ($selected_modules as $value) {
            $my_organization->modules()->attach($value);
        }

        $roles = [
            [
                "name" => "admin",
                "display_name" => "Admin",
                "description" => "Admin has every previleges"
            ],
            [
                "name" => "accountant",
                "display_name" => "Accountant",
                "description" => "Accountant has previlege on Account Modules"
            ],
            [
                "name" => "hr_manager",
                "display_name" => "HR Manager",
                "description" => "HR Manager has every previleges on HRM Modules"
            ],
            [
                "name" => "employee",
                "display_name" => "Employee",
                "description" => "Employee has basic previleges"
            ]
        ];

        foreach ($roles as $value) {
            $role = new Role();
            $role->name = $value['name'];
            $role->organization_id = $organization->id;
            $role->display_name = $value['display_name'];
            $role->description = $value['description'];
            $role->save();
            Custom::userby($role, true);

            if ($value['name'] == "admin") {
                $permissions = Permission::select('id')->get();
            } else if ($value['name'] == "accountant") {
                $permissions = Permission::select('id')->whereIn('module', [
                    'accounts',
                    'inventory',
                    'trade'
                ])->get();
            } else if ($value['name'] == "hr_manager") {
                $permissions = Permission::select('id')->whereIn('module', [
                    'hrm',
                    'customer'
                ])->get();
            } else if ($value['name'] == "employee") {
                $permissions = Permission::select('id')->whereIn('name', [
                    'hrm',
                    'permissions-list',
                    'permissions-create',
                    'permissions-edit',
                    'permissions-delete',
                    'leaves-list',
                    'leaves-create',
                    'leaves-edit',
                    'leaves-delete'
                ])->get();
            }

            foreach ($permissions as $permission) {
                $role->attachPermission($permission->id);
            }
        }

        $adminrole = Role::where('name', 'admin')->where('organization_id', $organization->id)->first();

        $user->roles()->attach($adminrole->id, [
            'organization_id' => $organization->id
        ]);

        // $free_plan = SubscriptionPlan::where('name', 'free_business')->first()->id;

        $organization_pack = new OrganizationPackage();
        $organization_pack->package_id = $package->id;
        $organization_pack->organization_id = $organization->id;
        // $organization_pack->plan_id = $free_plan;
        $organization_pack->plan_id = $request->input('subscription_plan');
        $organization_pack->added_on = Carbon::now()->format('Y-m-d');
        $organization_pack->expire_on = Carbon::now()->addDays(15)->format('Y-m-d');
        $organization_pack->status = 1;
        $organization_pack->save();

        /* ADDON Create plan wise */

        $addons = OrganizationPackage::select('organization_packages.plan_id', 'subscription_addons.addon_id', 'subscription_addons.value')->leftjoin('subscription_addons', 'subscription_addons.subscription_plan_id', '=', 'organization_packages.plan_id')
            ->where('organization_packages.organization_id', $organization->id)
            ->where('organization_packages.id', $organization_pack->id)
            ->get();

        foreach ($addons as $addon) {

            DB::table('addon_organization')->insert([
                'addon_id' => $addon->addon_id,
                'organization_id' => $organization->id,
                'used' => 0,
                'value' => $addon->value
            ]);
        }

        /* End */

        Custom::createAccounts($organization->id, $organization, false);

        $settings_array = [
            'ledgergroup_approval',
            'ledger_approval'
        ];

        foreach ($settings_array as $settings) {
            $setting = new Setting();
            $setting->name = $settings;
            if (Organization::checkModuleExists('books', Session::get('organization_id'))) {
                $setting->status = 0;
            } else {
                $setting->status = 1;
            }

            $setting->organization_id = Session::get('organization_id');
            $setting->save();
        }

        $setting = new Setting();
        $setting->name = 'theme';
        $setting->status = 1;
        $setting->data = json_encode([
            "header" => "bg-gradient-8",
            "sidebar" => "gradient bg-gradient-8"
        ]);
        $setting->user_id = Auth::id();
        $setting->organization_id = Session::get('organization_id');
        $setting->save();

        Session::put('theme_header', "bg-gradient-8");
        Session::put('theme_sidebar', "gradient bg-gradient-8");

        Custom::createHrm($organization->id, $organization);
        Custom::createTrade($organization->id, $organization);

        $group_approval = Setting::select('status')->where('name', 'ledgergroup_approval')
            ->where('organization_id', $organization->id)
            ->first()->status;

        $ledger_approval = Setting::select('status')->where('name', 'ledger_approval')
            ->where('organization_id', $organization->id)
            ->first()->status;

        /* null ledger addon is created. so delete that addon only */

        $addon_deletes = DB::table('addon_organization')->where('organization_id', $organization->id)->get();

        foreach ($addon_deletes as $addon_delete) {

            DB::table('addon_organization')->where('organization_id', $addon_delete->organization_id)
                ->where('addon_id', 1)
                ->where('value', NULL)
                ->delete();
        }

        /* End */

        Session::put('group_approval', $group_approval);
        Session::put('ledger_approval', $ledger_approval);

        Session::put('bcrm_code', $mybusiness->bcrm_code);
        Session::put('business', $mybusiness->business_name);
        Session::put('business_nature', $mybusiness->nature);

        $person = Person::select('crm_code')->where('id', Auth::user()->person_id)->first();

        Session::put('crm_code', $person->crm_code);

        Session::flash('flash_message', 'Organization successfully added!');

        return redirect()->route('dashboard');
    }

    public function add_modules(Request $request)
    {
        $business_id = $request->input('business_id');

        if ($request->input('otp') != null) {
            $business = Business::find($business_id);

            if ($business->otp != $request->input('otp')) {
                return redirect()->back()->withErrors([
                    'Otp does not match!'
                ]);

                // return redirect()->back()->with('message', 'IT WORKS!');
            }
        }

        $account_type_id = PlanAccountType::where('name', 'business')->first();

        $businessnature = BusinessNature::select('display_name AS name', 'id')->get();

        $subscription_plan = SubscriptionPlan::where('status', '1')->where('account_type_id', '2')->pluck('display_name', 'id');
        $subscription_plan->prepend('Choose Plan', '');

        $businessprofessionalism = BusinessProfessionalism::select('display_name AS name', 'id')->get();

        $businessinformation = BusinessField::where('status', '1')->get();

        $packages = Package::select('packages.id', 'packages.display_name', 'packages.image', DB::raw('GROUP_CONCAT(modules.display_name SEPARATOR " + ") AS modules'))->where('packages.status', '1')
            ->leftjoin('package_modules', 'packages.id', '=', 'package_modules.package_id')
            ->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id')
            ->where('packages.account_type_id', $account_type_id->id)
            ->groupby('id')
            ->get();

        $mybusiness = Business::select('businesses.business_name', 'people.mobile_no', 'people.email_address', 'businesses.gst', 'business_natures.name AS nature', 'businesses.tin', 'businesses.pan', 'people.phone', 'business_communication_addresses.city_id AS city_id', 'states.id AS state_id', 'business_communication_addresses.web_address')->leftjoin('business_natures', 'business_natures.id', '=', 'businesses.business_nature_id', 'business_communication_addresses.city_id', 'cities.state_id')
            ->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id')
            ->leftjoin('people', 'people.business_id', '=', 'businesses.id')
            ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
            ->leftjoin('states', 'cities.state_id', '=', 'states.id')
            ->where('businesses.id', $business_id)
            ->first();

        $country_id = Country::where('name', 'India')->first()->id;

        $state = State::where('country_id', $country_id)->orderBy('name')
            ->orderby('name')
            ->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = [];

        if (! empty($mybusiness->city_id)) {
            $selected_city = City::where('id', $mybusiness->city_id)->first();
            $selected_state = State::select('id')->where('id', $selected_city->state_id)->first()->id;

            $city = City::select('id', 'name')->where('state_id', $selected_state)->pluck('name', 'id');
            $city->prepend('Select City', '');
        }

        return view('add_modules', compact('businessnature', 'businessprofessionalism', 'state', 'businessinformation', 'packages', 'request', 'subscription_plan', 'business_id', 'mybusiness', 'city'));
    }
}
