<?php
namespace App;

use DateTime;
use Auth;
use Softon\Indipay\Facades\Indipay;
use Carbon\Carbon;
use App\AccountHead;
use App\AccountGroup;
use App\AccountLedger;
use App\AccountVoucher;
use App\AccountVoucherType;
use App\AccountLedgerCreditInfo;
use App\PersonCommunicationAddress;
use App\BusinessCommunicationAddress;
use App\Jobs\SendVerificationEmail;
use App\AccountVoucherSeparator;
use App\InventoryCategoryType;
use App\ActivationRepository;
use App\AccountVoucherFormat;
use App\HrmAttendanceSetting;
use App\AccountFinancialYear;
use App\OrganizationPackage;
use App\HrmPayrollFrequency;
use App\AccountGroupParent;
use App\PrintTemplateType;
use App\InventoryCategory;
use App\HrmEmploymentType;
use App\AccountLedgerType;
use App\TradeSalesReturn;
use App\TransactionType;
use App\WmsTransaction;
use App\PersonalAccount;
use App\BillingAddress;
use App\InventoryStore;
use App\HrmHolidayType;
use App\HrmDesignation;
use App\HrmPersonType;
use App\PrintTemplate;
use App\InventoryItem;
use App\InventoryRack;
use App\HrmDepartment;
use App\Jobs\SendSms;
use App\ShipmentMode;
use App\HrmLeaveType;
use App\Subscription;
use App\Organization;
use App\ServiceType;
use App\TermPeriod;
use App\HrmWeekOff;
use App\HrmHoliday;
use App\TaxGroup;
use App\HrmShift;
use App\HrmBreak;
use App\Business;
use App\Discount;
use App\Package;
use App\Setting;
use App\TaxType;
use App\Record;
use App\Person;
use App\State;
use App\Addon;
use App\Unit;
use App\User;
use App\City;
use App\Tax;
use App\Sms;
use App\MultiTemplate;
use Session;
use Image;
use Mail;
use Hash;
use DB;
use URL;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardDetail;

class Custom
{

    public static function personal_crm($city, $mobile, $name)
    {
        $crm_code = strtoupper("I" . substr($city, 0, 2) . str_pad(rand(0, pow(10, 3) - 1), 3, '0', STR_PAD_LEFT) . substr($mobile, 7, 3) . substr($name, 0, 2));

        if (Person::where('crm_code', $crm_code)->exists()) {
            $crm_code = self::personal_crm($data['city'], $data['mobile'], $data['first_name']);
        }

        return $crm_code;
    }

    public static function business_crm($city, $mobile, $name)
    {
        $bcrm = strtoupper("B" . substr($city, 0, 2) . str_pad(rand(0, pow(10, 3) - 1), 3, '0', STR_PAD_LEFT) . substr($mobile, 7, 3) . substr($name, 0, 2));

        if (Business::where('bcrm_code', $bcrm)->exists()) {
            $bcrm = self::business_crm($city, $mobile, $name);
        }

        return $bcrm;
    }

    public static function otp($num)
    {
        $x = $num - 1;

        $min = pow(10, $x);
        $max = pow(10, $x + 1) - 1;
        $value = rand($min, $max);

        return $value;
    }

    public static function transaction_id($x)
    {
        $len = $x;
        $last = - 1;
        $code = null;
        for ($i = 0; $i < $len; $i ++) {
            do {
                $next_digit = mt_rand(0, 9);
            } while ($next_digit == $last);
            $last = $next_digit;
            $code .= $next_digit;
        }

        $transaction_id = $code;

        if (Subscription::where('transaction_id', $transaction_id)->exists()) {
            $transaction_id = self::transaction_id($x);
        }

        return $transaction_id;
    }

    public static function randomKey($length)
    {
        $pool = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        for ($i = 0; $i < $length; $i ++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }

        return $key;
    }

    public static function image_resize($image, $size, $name, $path)
    {
        try {
            $extension = $image->getClientOriginalExtension();
            $imageRealPath = $image->getRealPath();

            $dt = new DateTime();

            $img = Image::make($imageRealPath); // use this if you want facade style code
            $img->resize(intval($size), null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $path_array = explode('/', $path);

            $public_path = '';

            foreach ($path_array as $p) {
                $public_path .= $p . "/";
                if (! file_exists(public_path($public_path))) {
                    mkdir(public_path($public_path), 0777, true);
                }
            }
            return $img->save(public_path($path) . '/' . $name);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function file_upload($file, $name, $path)
    {
        try {
            $path_array = explode('/', $path);

            $public_path = '';

            foreach ($path_array as $p) {
                $public_path .= $p . "\\";
                if (! file_exists(public_path($public_path))) {
                    mkdir(public_path($public_path), 0777, true);
                }
            }
            return $file->move(public_path($public_path), $name);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function check_plan()
    {
        $package = OrganizationPackage::where('organization_id', Session::get('organization_id'))->whereNotNull('subscription_id')->first();

        if ($package != "") {
            return true;
        } else {
            return false;
        }
    }

    public static function check_activeplan()
    {
        $package = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)
            ->whereNotNull('subscription_id')
            ->first();

        if ($package != "") {
            return true;
        } else {
            return false;
        }
    }

    public static function plan_is_activated()
    {
        if (self::check_plan()) {

            $subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)
                ->whereNotNull('subscription_id')
                ->first();

            if ($subscription == "") {
                return false;
            }
        }

        return true;
    }

    public static function plan_renewal()
    {

        // $plan = DB::table('organization_packages')->select('package_id')->where('organization_id', Session::get('organization_id'))->first();
        $organization = Organization::findOrFail(Session::get('organization_id'));

        $message = "";

        if (self::check_plan()) {

            $subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)
                ->whereNotNull('subscription_id')
                ->first();

            if ($subscription != "") {
                $total_days = self::time_difference(Carbon::parse($subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

                if ($total_days < 0) {
                    $message = "Your subscription is expired. Please .";
                } else if ($total_days == 0) {
                    $message = "Your subscription expires today.";
                } else if ($total_days < 15) {
                    $message = "Your subscription will be expired in " . $total_days . " days";
                }
            } else {
                $message = "Your subscription is not activated.";
            }
        } else {

            $subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->whereNull('subscription_id')->first();

            $total_days = self::time_difference(Carbon::parse($subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

            if ($total_days < 0) {
                $message = "Your free plan is expired. Please.";
            } else if ($total_days == 0) {
                $message = "Your subscription expires today.";
            } else if ($total_days <= 15) {
                $message = "Your free plan will be expired in " . $total_days . " days";
            }
        }

        return $message;
    }

    public static function check_module_list()
    {
        $modules = DB::table('module_organization')->where('organization_id', Session::get('organization_id'))->count();

        if ($modules > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function plan_expire($plan, $organization_id)
    {
        $free_user = OrganizationPackage::where('organization_id', Session::get('organization_id'))->whereNull('subscription_id')->first();

        $subscription_plan = OrganizationPackage::select('organization_packages.plan_id', 'organization_packages.organization_id', 'subscription_plans.name')->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
            ->where('organization_packages.organization_id', $organization_id)
            ->whereIn('subscription_plans.name', $plan)
            ->first();

        // dd($subscription_plan->name);

        if (self::check_plan()) {
            // paid plan

            $subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)
                ->whereNotNull('subscription_id')
                ->first();

            if ($subscription != "") {

                $total_days = self::time_difference(Carbon::parse($subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

                if ($total_days < 0) {
                    return true;
                }
            } else {
                return true;
            }
        } else {
            // free plan
            // dd($subscription_plan->name);

            $total_days = self::time_difference(Carbon::parse($free_user->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

            if ($total_days < 0 || $subscription_plan->name == "lite_business") {

                return true;
            }
        }

        return false;
    }

    public static function plan_limitation()
    {
        $subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)
            ->whereNotNull('subscription_id')
            ->first();

        if ($subscription != "") {

            $total_days = self::time_difference(Carbon::parse($subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

            if ($total_days < 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $free_user = OrganizationPackage::where('organization_id', Session::get('organization_id'))->whereNull('subscription_id')->first();

            $total_days = self::time_difference(Carbon::parse($free_user->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

            if ($total_days < 0) {

                return false;
            } else {
                return true;
            }
        }
    }

    public static function plan_package($plan_id)
    {
        $plans = DB::table('package_plan')->select('package_plan.features', 'package_plan.plan_id', 'package_plan.module_id')
            ->where('package_plan.plan_id', $plan_id)
            ->get();

        return $plans;
    }

    public static function package_addon($plan_id)
    {
        $addons = DB::table('subscription_addons')->select('subscription_addons.addon_id', 'subscription_addons.value', 'addons.display_name AS addon_name', 'package_plan.module_id', 'package_plan.plan_id')
            ->
        leftjoin('addons', 'addons.id', '=', 'subscription_addons.addon_id')
            ->
        leftjoin('package_plan', 'package_plan.plan_id', '=', 'subscription_addons.subscription_plan_id')
            ->
        where('package_plan.plan_id', $plan_id)
            ->
        get();

        return $addons;
    }

    public static function remaining_voucher($addon_type)
    {

        /*
         * $addon_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')
         * ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
         * ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
         * ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
         * ->where('account_vouchers.organization_id', Session::get('organization_id'))
         * ->where('modules.name', Session::get('module_name'))
         * ->whereIn('account_vouchers.name', $type)
         * ->first();
         */
        if ($addon_type) {
            if ($addon_type == 'purchases') {

                $addon_type = 'purchase';
            } elseif ($addon_type == 'sales' || $addon_type == 'sales_cash' || $addon_type == 'job_invoice' || $addon_type == 'job_invoice_cash') {
                $addon_type = 'invoice';
            } elseif ($addon_type == 'job_card') {
                $addon_type = 'job_card';
            } elseif ($addon_type == 'goods_receipt_note') {
                $addon_type = 'grn';
            } elseif ($addon_type == 'vehicles') {
                $addon_type = 'vehicles';
            } elseif ($addon_type == 'employee') {
                $addon_type = 'employee';
            } 
            else { // addon name not there

                return false;
            }

            $vouchers = Addon::where('name', $addon_type)->first()->id;

            $remaining_vouchers = DB::table('addon_organization')->where('addon_id', $vouchers)
                ->where('organization_id', Session::get('organization_id'))
                ->where('status', 1)
                ->first();

            if ($remaining_vouchers != '') {

                return $remaining_vouchers->value;
            } else {

                return false;
            }

            /*
             * if($remaining_vouchers != null) {
             *
             * if($remaining_vouchers->used >= $remaining_vouchers->value)
             * {
             * return false;
             * }
             *
             * return true;
             * }
             */
        }
    }

    public static function remaining_transaction()
    {
        $transactions = Addon::where('name', 'transaction')->first()->id;

        $remaining_transactions = DB::table('addon_organization')->where('addon_id', $transactions)
            ->where('organization_id', Session::get('organization_id'))
            ->where('status', 1)
            ->first();

        if ($remaining_transactions->used >= $remaining_transactions->value) {
            return false;
        }

        return true;
    }

    public static function remaining_sms()
    {
        $sms = Addon::where('name', 'sms')->first()->id;

        $remaining_sms = DB::table('addon_organization')->where('addon_id', $sms)
            ->where('organization_id', Session::get('organization_id'))
            ->where('status', 1)
            ->first();

        if ($remaining_sms->used >= $remaining_sms->value) {
            return false;
        }

        return true;
    }

    public static function remaining_promotion_sms()
    {
        $sms = Addon::where('name', 'promotion_sms')->first()->id;

        $remaining_promotion_sms = DB::table('addon_organization')->where('addon_id', $sms)
            ->where('organization_id', Session::get('organization_id'))
            ->where('status', 1)
            ->first();

        if ($remaining_promotion_sms->used >= $remaining_promotion_sms->value) {
            return false;
        }

        return true;
    }

    public static function remaining_ledger()
    {
        $ledgers = Addon::where('name', 'records')->first()->id;

        $remaining_ledgers = DB::table('addon_organization')->where('addon_id', $ledgers)
            ->where('organization_id', Session::get('organization_id'))
            ->where('status', 1)
            ->first();

        if ($remaining_ledgers->used >= $remaining_ledgers->value) {
            return false;
        }

        return true;
    }

    public static function remaining_revenue()
    {
        $revenue = Addon::where('name', 'total_revenue')->first()->id;

        $remaining_revenue = DB::table('addon_organization')->where('addon_id', $revenue)
            ->where('organization_id', Session::get('organization_id'))
            ->where('status', 1)
            ->first();

        if ($remaining_revenue->used >= $remaining_revenue->value) {
            return false;
        }

        return true;
    }

    public static function remaining_employee()
    {
        $employees = Addon::where('name', 'employee')->first()->id;

        $remaining_employees = DB::table('addon_organization')->where('addon_id', $employees)
            ->where('organization_id', Session::get('organization_id'))
            ->where('status', 1)
            ->first();

        if ($remaining_employees != null) {

            if ($remaining_employees->used >= $remaining_employees->value) {
                return false;
            }
        }

        return true;
    }

    public static function add_addon($addon, $organization_id = false)
    {
        if ($organization_id == false) {
            $organization_id = Session::get('organization_id');
        }

        $id = Addon::where("name", $addon)->first()->id;

        $used = DB::table('addon_organization')->where([
            [
                'organization_id',
                $organization_id
            ],
            [
                'addon_id',
                $id
            ]
        ])->first();

        if ($used == null) {
            DB::table('addon_organization')->insert([
                'organization_id' => $organization_id,
                'addon_id' => $id
            ]);
        } else {
            DB::table('addon_organization')->where('organization_id', $organization_id)
                ->where('addon_id', $id)
                ->update([
                'used' => Custom::two_decimal($used->used + 1)
            ]);
        }

        return true;
    }

    public static function add_revenue($addon, $revenue_total, $organization_id = false)
    {

        // dd($addon);
        if ($organization_id == false) {
            $organization_id = Session::get('organization_id');
        }

        $id = Addon::where("name", $addon)->first()->id;

        $used = DB::table('addon_organization')->where([
            [
                'organization_id',
                $organization_id
            ],
            [
                'addon_id',
                $id
            ]
        ])->first();

        // $transaction_revenue = Custom::two_decimal($revenue_total);

        if ($used == null) {
            DB::table('addon_organization')->insert([
                'organization_id' => $organization_id,
                'addon_id' => $id
            ]);
        } else {
            DB::table('addon_organization')->where('organization_id', $organization_id)
                ->where('addon_id', $id)
                ->update([
                'used' => Custom::two_decimal($used->used + $revenue_total)
            ]);
        }

        return true;
    }

    public static function delete_revenue($addon, $revenue_total)
    {
        $organization_id = Session::get('organization_id');

        $id = Addon::where("name", $addon)->first()->id;

        $used = DB::table('addon_organization')->where([
            [
                'organization_id',
                $organization_id
            ],
            [
                'addon_id',
                $id
            ]
        ])->first();

        $transaction_revenue = Custom::two_decimal($revenue_total);

        $minus_value = ($used->used != 0) ? ($used->used - $transaction_revenue) : 0;

        DB::table('addon_organization')->where('organization_id', $organization_id)
            ->where('addon_id', $id)
            ->update([
            'used' => $minus_value
        ]);

        return true;
    }

    public static function delete_addon($addon)
    {
        $organization_id = Session::get('organization_id');

        $id = Addon::where("name", $addon)->first()->id;

        $used = DB::table('addon_organization')->where([
            [
                'organization_id',
                $organization_id
            ],
            [
                'addon_id',
                $id
            ]
        ])->first();

        $minus_value = ($used->used != 0) ? ($used->used - 1) : 0;

        DB::table('addon_organization')->where('organization_id', $organization_id)
            ->where('addon_id', $id)
            ->update([
            'used' => $minus_value
        ]);

        return true;
    }

    public static function time_difference($currentime, $specifictime, $type)
    {
        $time1 = Carbon::parse($currentime);
        $time2 = Carbon::parse($specifictime);

        if ($type == "d") {
            return $time2->diffInDays($time1, false);
        } else if ($type == "h") {
            return $time2->diffInHours($time1, false);
        } else if ($type == "m") {
            return $time2->diffInMinutes($time1, false);
        } else if ($type == "s") {
            return $time2->diffInSeconds($time1, false);
        }
    }

    public static function time_twenty_four($time)
    {
        $new_time = DateTime::createFromFormat('h:i A', $time);
        return $new_time->format('H:i:s');
    }

    public static function send_transms($user, $pass, $sender, $phone, $message)
    {
        /* app('Illuminate\Contracts\Bus\Dispatcher')->dispatch(new SendSms($user, $pass, $sender, $phone, $message)); */
        $text = rawurlencode($message);

        $organization_id = Session::get('organization_id');

        $user_id = Auth::user();

        if ($user_id == null) {
            $auth_user_id = null;
        } else {
            $auth_user_id = Auth::user()->id;
        }

        $url = 'http://trans.smsfresh.co/api/sendmsg.php?user=' . $user . '&pass=' . $pass . '&sender=' . $sender . '&phone=' . $phone . '&text=' . $text . '&priority=ndnd&stype=normal';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ]);

        $message_id = curl_exec($ch);

        if ($message_id != "") {
            $sms = new Sms();
            $sms->user = config('constants.sms.user');
            $sms->pass = config('constants.sms.sender');
            $sms->sender = config('constants.sms.sender');
            $sms->phone = $phone;
            $sms->message = $text;
            $sms->priority = 'ndnd';
            $sms->stype = 'normal';
            $sms->message_id = $message_id;
            $sms->user_id = $auth_user_id;
            $sms->organization_id = $organization_id;
            $sms->save();
        }

        return true;
    }

    public static function userby($model, $is_create, $user = null)
    {
        $user = Auth::user()->id;

        if (!$model->created_by) {
            $model->created_by = $user;
        }

        $model->last_modified_by = $user;
        $model->save();
    }

    public static function tree($elements, $parentId = null)
    {

        // dd($parentId);
        $datetime = new DateTime();

        $last_day = new DateTime();
        $last_day->modify('last day of this month');

        $html = "";

        if (! empty(array_filter($elements))) {

            $html .= "<ol class='tree_list'>";
            foreach ($elements as $element) {
                // dd($element);
                /* inventory asset url is changed - it shows stock Report */

                if ($element['parent'] == $parentId) {

                    if ($element['amount'] != "0.00") {

                        $html .= "<li><i class='fa'></i><div>" . $element['name'] . "</div><div class='negativeSign' style='text-align:right'>" . $element['amount'] . "</div>";

                        if ($element['ledgers']) {
                            $html .= "<ol class='tree_list'>";

                            foreach ($element['ledgers'] as $ledger) {

                                $inventory_url = ($element['name'] == 'Other Current Asset') ? url('accounts/stock-report') : url('accounts/ledger') . "/" . $ledger['id'] . "/" . $ledger['parent'];

                                if ($ledger['amount'] != "0.00") {

                                    $html .= "<li><i class='fa'></i><a href='" . $inventory_url . "'><div>" . $ledger['name'] . "</div><div class='negativeSign' style='text-align:right'>" . $ledger['amount'] . "</div></a></li>";
                                }
                            }

                            $html .= "</ol>";
                        }
                    }

                    $children = self::tree($elements, $element['id']);
                    if ($children) {
                        $html .= $children;
                    }

                    $html .= "</li>";
                }
            }
            $html .= "</ol>";
        }

        return $html;
    }

    public static function account_type($id, $model, $is_person)
    {
        if ($is_person == true) {
            $model->user_id = $id;
        } else {
            $model->organization_id = $id;
        }

        $model->save();
    }

    public static function create_group($name, $id, $connections, $account_type, $display_name, $head, $group_id, $opening_type, $approval, $delete, $is_person)
    {
        if (! $account_type->hasAccountGroup($name, $id)) {
            $group = new AccountGroup();
            $group->name = $name;
            $group->display_name = $display_name;
            $group->parent_id = $group_id;
            $group->account_head = $head;
            $group->opening_type = $opening_type;
            $group->approval_status = $approval;
            $group->delete_status = $delete;
            $group->save();

            if (! $is_person) {
                Custom::add_addon('records');
            }

            if ($is_person == true) {
                self::account_type($id, $group, true);
            } else {
                self::account_type($id, $group, false);
            }

            self::userby($group, true);

            if (count($connections) > 0) {
                foreach ($connections as $value) {
                    $group->find($group->id)
                        ->ledger_group()
                        ->attach($value);
                }
            }

            return array(
                'group' => $group->id,
                'head' => $head
            );
        }
    }

    public static function create_ledger($name, $account_type, $display_name, $ledger_type, $person_id, $business_id, $group, $opening_date, $opening_type, $opening_balance, $approval = null, $delete, $id, $is_person, $bank_account_type = null, $account_no = null, $bank_name = null, $bank_branch = null, $ifsc = null, $micr = null, $nbfc_name = null, $nbfc_branch = null)
    {
        if (! $account_type->hasAccountLedger($name, $person_id, $business_id, $id)) {
            $ledger = new AccountLedger();
            $ledger->name = $name;
            $ledger->display_name = $display_name;
            $ledger->ledger_type = $ledger_type;
            $ledger->person_id = $person_id;
            $ledger->business_id = $business_id;
            $ledger->group_id = $group;
            $ledger->opening_balance_date = $opening_date;
            $ledger->opening_balance = $opening_balance;
            $ledger->opening_balance_type = $opening_type;
            $ledger->account_type = $bank_account_type;
            $ledger->account_no = $account_no;
            $ledger->bank_name = $bank_name;
            $ledger->bank_branch = $bank_branch;
            $ledger->ifsc = $ifsc;
            $ledger->micr = $micr;
            $ledger->nbfc_name = $nbfc_name;
            $ledger->nbfc_branch = $nbfc_branch;
            $ledger->approval_status = ($approval != null) ? $approval : 0;
            $ledger->delete_status = $delete;
            $ledger->save();

            self::userby($ledger, true);

            self::account_type($id, $ledger, $is_person);

            $account_ledger_creditinfo = new AccountLedgerCreditInfo();
            $account_ledger_creditinfo->id = $ledger->id;
            $account_ledger_creditinfo->save();
            self::userby($account_ledger_creditinfo, true);

            if (! $is_person) {
                Custom::add_addon('records');
            }
        } else {
            if ($is_person == true) {
                $ledger = AccountLedger::where('name', $name)->where('person_id', $person_id)
                    ->where('business_id', $business_id)
                    ->where('user_id', $id)
                    ->first();
            } else {
                $ledger = AccountLedger::where('name', $name)->where('person_id', $person_id)
                    ->where('business_id', $business_id)
                    ->where('organization_id', $id)
                    ->first();
            }
        }

        return $ledger->id;
    }

    public static function create_voucher($name, $display_name, $account_type, $code, $voucher_type, $debit_ledger, $format, $print, $delete, $id, $type = null, $is_person, $account_status = 0)
    {
        if (! $account_type->hasVoucher($name, $id)) {

            $voucher_type_id = AccountVoucherType::where('name', $voucher_type)->first();

            // dd($voucher_type_id);

            $account_voucher = new AccountVoucher();
            $account_voucher->name = $name;
            $account_voucher->display_name = $display_name;
            $account_voucher->code = $code;
            $account_voucher->voucher_type_id = $voucher_type_id->id;
            if ($debit_ledger != null) {
                $account_voucher->debit_ledger_id = $debit_ledger;
            }

            $account_voucher->type = $type;
            $account_voucher->format_id = $format;
            $account_voucher->print_id = $print;
            $account_voucher->delete_status = $delete;
            $account_voucher->account_status = $account_status;
            $account_voucher->save();
            self::userby($account_voucher, true, $id);
            if ($is_person == true) {
                self::account_type($id, $account_voucher, true);
            } else {
                self::account_type($id, $account_voucher, false);
            }

            if (! $is_person) {
                Custom::add_addon('records');
            }
        }
    }

    public static function createAccounts($id, $account_type, $is_person)
    {
        $asset = AccountHead::where('name', 'asset')->first();
        $expense = AccountHead::where('name', 'expense')->first();
        $income = AccountHead::where('name', 'income')->first();
        $liability = AccountHead::where('name', 'liability')->first();

        $personal_ledger = AccountLedgerType::where('name', 'personal')->first();

        $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

        $bank_ledger = AccountLedgerType::where('name', 'bank')->first();
        $nbfc_ledger = AccountLedgerType::where('name', 'nbfc')->first();

        $auto_number = AccountVoucherSeparator::where('name', 'auto_number')->first();

        $manual = AccountVoucherSeparator::where('name', 'manual')->first();

        $financial_year = AccountVoucherSeparator::where('name', 'financial_year')->first();

        $voucher_code = AccountVoucherSeparator::where('name', 'voucher_code')->first();

        if (! $account_type->hasFormat('Default Format', $id)) {
            $voucher_format = new AccountVoucherFormat();
            $voucher_format->name = 'Default Format';
            $voucher_format->icon = '/';
            $voucher_format->save();

            self::userby($voucher_format, true, $id);

            if ($is_person == true) {
                self::account_type($id, $voucher_format, true);
            } else {
                self::account_type($id, $voucher_format, false);
            }

            /*
             * $voucher_format->find($voucher_format->id)->separator()->attach($auto_number->id);
             *
             * DB::table('account_format_separator')->where('format_id', $voucher_format->id)->where('separator_id', $auto_number->id)->update(['order' => 1]);
             */

            /* voucher default format - Ex. JC/2019/1 */

            $voucher_format->find($voucher_format->id)
                ->separator()
                ->attach($voucher_code->id);

            $voucher_format->find($voucher_format->id)
                ->separator()
                ->attach($financial_year->id);

            $voucher_format->find($voucher_format->id)
                ->separator()
                ->attach($auto_number->id);

            DB::table('account_format_separator')->where('format_id', $voucher_format->id)
                ->where('separator_id', $voucher_code->id)
                ->update([
                'order' => 1,
                'value' => 0
            ]);

            DB::table('account_format_separator')->where('format_id', $voucher_format->id)
                ->where('separator_id', $financial_year->id)
                ->update([
                'order' => 2,
                'value' => 0
            ]);

            DB::table('account_format_separator')->where('format_id', $voucher_format->id)
                ->where('separator_id', $auto_number->id)
                ->update([
                'order' => 3,
                'value' => 0
            ]);

            /* End */

            if (! $is_person) {
                Custom::add_addon('records');
            }
        }

        $default = PrintTemplateType::where('name', 'general')->first()->id;
        // $sale = PrintTemplateType::where('name', 'sale')->first()->id;
        // $payslip= PrintTemplateType::where('name', 'payslip')->first()->id;

        $B2B_HSNbased_Invoice = PrintTemplateType::where('name', 'B2B_HSNbased_Invoice')->first()->id;
        $B2B_HSNbased_JobEstimation = PrintTemplateType::where('name', 'B2B_HSNbased_JobEstimation')->first()->id;
        $B2B_TaxPercentage_Invoice = PrintTemplateType::where('name', 'B2B_TaxPercentage_Invoice')->first()->id;
        $B2B_TaxPercentage_JobEstimation = PrintTemplateType::where('name', 'B2B_TaxPercentage_JobEstimation')->first()->id;
        $B2C_NoTax_JobEstimation = PrintTemplateType::where('name', 'B2C_NoTax_JobEstimation')->first()->id;
        $B2C_NoTax_JobInvoice = PrintTemplateType::where('name', 'B2C_NoTax_JobInvoice')->first()->id;
        $B2C_OneLine_Tax_Invoice = PrintTemplateType::where('name', 'B2C_OneLine_Tax_Invoice')->first()->id;
        $B2C_OneLineTax_Job_Estimation = PrintTemplateType::where('name', 'B2C_OneLineTax_Job_Estimation')->first()->id;

        $Payslip_Print = PrintTemplateType::where('name', 'Payslip_Print')->first()->id;
        $PO_Purchase_GRN = PrintTemplateType::where('name', 'PO_Purchase_GRN')->first()->id;

        $business_address_type = BusinessAddressType::where('name', 'business')->first()->id;

        if (! $is_person) {
            $business = Business::select('businesses.alias AS business_name', 'businesses.pan', 'businesses.tin', 'businesses.gst', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin', 'business_communication_addresses.phone', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.web_address')->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id')
                ->leftjoin('cities', 'cities.id', '=', 'business_communication_addresses.city_id')
                ->leftjoin('states', 'states.id', '=', 'cities.state_id')
                ->where('business_communication_addresses.address_type', $business_address_type)
                ->where('businesses.id', $account_type->business_id)
                ->first();

            $first_line_address = $business->address;
            $second_line_address = "";

            if ($business->city != null) {
                $second_line_address .= $business->city;
            }
            if ($business->city != null && $business->pin != null) {
                $second_line_address .= " - ";
            }
            if ($business->pin != null) {
                $second_line_address .= $business->pin;
            }
            if ($business->city != null && $business->state != null || $business->pin != null && $business->state != null) {
                $second_line_address .= ". ";
            }
            if ($business->state != null) {
                $second_line_address .= $business->state . ".";
            }

            $phone_address = "";

            if ($business->mobile_no != null) {
                $phone_address .= "Mobile: " . $business->mobile_no;
            }
            if ($business->mobile_no != null && $business->phone != null) {
                $phone_address .= ", ";
            }
            if ($business->phone != null) {
                $phone_address .= "Phone: " . $business->phone;
            }
            if ($business->mobile_no != null && $business->email_address != null || $business->phone != null && $business->email_address != null) {
                $phone_address .= ", ";
            }
            if ($business->email_address != null) {
                $phone_address .= "Email: " . $business->email_address;
            }
            if ($business->mobile_no != null && $business->email_address != null || $business->phone != null && $business->email_address != null || $business->email_address != null && $business->web_address != null) {
                $phone_address .= ", ";
            }
            if ($business->web_address != null) {
                $phone_address .= "Web: " . $business->web_address;
            }

            $print_templates = [
                [
                    'name' => 'default_print',
                    'display_name' => 'Default Print',
                    'data' => '',
                    'print_template_type_id' => $default
                ],

                [
                    'name' => 'B2B_HSNbased_Invoice',
                    'display_name' => 'B2B HSNbased Invoice',
                    'data' => '<style>
				.workspace
				{
				   	display: block;
				  	page-break-inside: avoid;
				  	page-break-before: avoid;
				  	page-break-after: avoid;
				    -webkit-region-break-inside: avoid; 
				}
				</style>
				<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
				<div class="invoice_print" style="border:1px solid black;">
				  	<div style="position: relative; min-height: 300px; height: 299px; font-family: Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0);" class="header_container content_container">

				    	<div style="float: left; position: absolute; top: 143px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
				    	<hr style="border:1px solid black;width:700px">
				  		<div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div>
				  		<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 114.467px; left: 253.467px;" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 20px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;">Tax Invoice</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 166px; left: 8.00003px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 191px; left: 10px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal: </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 212px; left: 10px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 233px; left: 11px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 254px; left: 11px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST No:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 165px; left: 456px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher#: </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 184px; left: 474px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated:</div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 0.233321px; left: 193.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 40.4667px; left: 185.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 70.4667px; left: 172.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone: </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 70.35px; left: 338.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
					<div style="position:relative;" class="body_container">
					<table style="width:100%;border: 1px solid black;border-collapse: collapse;" class="invoice_item_table">
							<thead>
							    <tr>
							      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_id">Sl.No</th>
							      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_desc">PARTICULARS</th>
							      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_hsn">HSN/SAC</th>
							      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_quantity">QTY</th>
							      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_rate">RATE</th>
							      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_discount">DISCOUNT</th>
							      <th style="padding: 5px;border: 1px solid black;border-collapse: collapse;text-align: center;font-family: Times New Roman, Times, serif;" class="col_t_amount">AMOUNT</th>
							    </tr>
							</thead>
					<tbody>
						    <tr>
							    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
							    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
							    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
							    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
							    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
							    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
							    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
						   	</tr>
				    <tr>
				    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr>
				    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr>
				    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr>
				    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_id">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr>
				    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr>
				    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr>
				    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
				   </tr>
					<tr>
					    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr>
					    <td style="padding: 5px;text-align: center;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_t_amount">&nbsp;</td>
					   </tr>
					   </tbody><tfoot>
					   <tr>
					   <td class="col_id" colspan="3" style="border-top: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">E &amp; OE</td>
					      <td style="border-top: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
					      <td style="border-top: 1px solid black;font-family: Times New Roman, Times, serif;">&nbsp;</td>
					      <td style="border-top: 1px solid black;border-right: 1px solid black;border-left: 1px solid black;font-family: Times New Roman, Times, serif;">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px;font-family: Times New Roman, Times, serif;">000.00</span></td>
					      <td style="border-top: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px;font-family: Times New Roman, Times, serif;">000.00</span></td>
					   </tr>
					   </tfoot>
					  
					</table>

					<table style="width:100%;">
					<tbody><tr><td>
					<table style="width:80%;border-collapse: collapse;border: 1px solid black;float:left;" class="hsnbasedTable">
					<thead>
					<tr>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">HSN/SAC</td>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_tax_value">Taxable</td>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_igst">IGST%</td>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_igst_amount">IGST Amt</td>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_cgst">CGST%</td>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">CGST Amt</td>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sgst">SGST%</td>
						<td style="padding: 5px;border-collapse: collapse;border: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">SGST Amt</td>
					</tr>
					</thead>
					<tbody>
						<tr>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
						</tr>
						<tr>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
						</tr>
						<tr>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
						</tr>
						<tr>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
						</tr>
						<tr>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
						</tr>
						<tr>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;font-family: Times New Roman, Times, serif;" class="col_sac">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_tax_value">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_igst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_cgst_amount">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst">&nbsp;</td>
						<td style="padding: 5px;border-left: 1px solid black;border-right: 1px solid black;text-align: right;font-family: Times New Roman, Times, serif;" class="col_sgst_amount">&nbsp;</td>
						</tr>
					</tbody>
					<tfoot>
					<tr>
					<td colspan="8" style="border-top: 1px solid black;padding-bottom: 25px;font-family: Times New Roman, Times, serif;">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px;font-family: Times New Roman, Times, serif;">Four Thousand Only</i></td>
					</tr>
					</tfoot>
					</table>
					<table style="width:20%;float:left;border-bottom:1px solid black;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;" class="ft">
					<tbody><tr>
					<td style="padding-top:5px;padding-bottom:5px;">CGST</td>
					<td style="text-align: right;font-family: Times New Roman, Times, serif;"><span class="value_result" data-value="total_cgst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
					</tr>
					<tr>
					<td style="padding-top:5px;padding-bottom:5px;">SGST</td>
					<td style="text-align: right;"><span class="value_result" data-value="total_sgst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">00.00</span></td>
					</tr>
					<tr>
					<td style="padding-top:5px;padding-bottom:5px;">IGST</td>
					<td style="text-align: right;"><span class="value_result" data-value="total_igst" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
					</tr>
					<tr>
					<td style="padding-top:5px;padding-bottom:5px;">Round off</td>
					<td style="text-align: right;"><span class="value_result" data-value="round_off" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
					</tr>
					<tr>
					<td style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
					<td style="text-align: right;">&nbsp;</td>
					</tr>
					<tr>
					<td style="padding-top:5px;padding-bottom:5px;">&nbsp;</td>
					<td style="text-align: right;">&nbsp;</td>
					</tr>
					<tr>
					<td style="padding-top:20px;padding-bottom:38px;font-family: Times New Roman, Times, serif;">TOTAL:</td>
					<td style="text-align: right;padding-top:20px;padding-bottom:38px;font-family: Times New Roman, Times, serif;"><span class="value_result" data-value="total_amountwithtax" style="font-family: Times New Roman, Times, serif; font-size: 14px; color: rgb(0, 0, 0);float:right;">000.00</span></td>
					</tr>
					</tbody></table>
				</td>
				</tr></tbody></table>
					</div>
					  <div style="position: relative;font-family: Times New Roman, Times, serif; color: rgb(0, 0, 0);  width: 100%;" class="footer_container content_container">
					  Discription:Goods once sold can not be taken back!
					  </div>
					  </div>
				</div>
			',
                    'print_template_type_id' => $B2B_HSNbased_Invoice
                ],

                [
                    'name' => 'B2B_HSNbased_JobEstimation',
                    'display_name' => 'B2B HSNbased JobEstimation',
                    'data' => '<style>
				.workspace
				{
				    display: block;
				    page-break-inside: avoid;
				  page-break-before: avoid;
				  page-break-after: avoid;
				    -webkit-region-break-inside: avoid; 
				}
				</style>
				<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
				<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div style="position: relative; min-height: 300px; height: 299px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="float: left; position: absolute; top: 143px; left: 0.0000292188px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
				 <hr style="border: 1px solid black; width: 700px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 166px; left: 8.00003px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 191px; left: 10px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 212px; left: 10px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 233px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 254px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST No:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 165px; left: 456px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher#: </div><div style="right: -5px; top: 15px; width: 15px; display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 184px; left: 474px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated:</div><div style="right: -5px; top: 15px; width: 15px; display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 0.116652px; left: 183.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.1167px; left: 204.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 67.1167px; left: 178.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 67.4667px; left: 336.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 108.233px; left: 237.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 22px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
				<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="body_container">
				<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="invoice_item_table">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">DISCOUNT</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
				    </tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">E &amp; OE</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				   </tr>
				   </tfoot>
				  
				</table>
				<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="hsnbasedTable">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">HSN/SAC</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
				</tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sac">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				</tbody>
				<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
				</tr>
				</tfoot>
				</table>
				<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="ft">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">CGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">SGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">IGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Round off</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">TOTAL:</td>
				<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				</tbody></table>
				</td>
				</tr></tbody></table>
				</div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 12px;" class="footer_container content_container">
				  Discription:Goods once sold can not be taken back!
				  </div>
				  </div>
				</div>
			',
                    'print_template_type_id' => $B2B_HSNbased_JobEstimation
                ],

                [
                    'name' => 'B2B_TaxPercentage_Invoice',
                    'display_name' => 'B2B TaxPercentage Invoice',
                    'data' => '<style>
					.workspace
					{
					    display: block;
					    page-break-inside: avoid;
					  page-break-before: avoid;
					  page-break-after: avoid;
					    -webkit-region-break-inside: avoid; 
					}
					</style>
					<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
					<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					  <div style="position: relative; min-height: 300px; height: 302px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

					  <div style="float: left; position: absolute; top: 143px; left: -0.0000318164px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					 <hr style="border: 1px solid black; width: 700px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
					  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 214.233px; left: 4.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 237.233px; left: 11.2333px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 257.35px; left: 10.3499px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST no : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.117px; left: 9.11667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 189.35px; left: 9.34994px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.583px; left: 478.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher # :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 186.7px; left: 502.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -0.533342px; left: 232.467px;" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 40.4667px; left: 214.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.35px; left: 201.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.35px; left: 368.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 112.467px; left: 254.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 20px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -2.53334px; left: 209.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
					<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container">
					<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="invoice_item_table">
					<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">DISCOUNT</th>
					      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
					    </tr>
					</thead>
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
					    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
					   </tr>
					   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">E &amp; OE</td>
					      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
					      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
					   </tr>
					   </tfoot>
					  
					</table>
					<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="floatedTable">
					<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">GST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
					<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
					</tr>
					</thead>
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
					<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
					</tr>
					</tbody>
					<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
					</tr>
					</tfoot>
					</table>
					<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="ft">
					<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">CGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">SGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">IGST</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Round off</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
					</tr>
					<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
					<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">TOTAL:</td>
					<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
					</tr>
					</tbody></table>
					</td>
					</tr></tbody></table>
					</div>
					  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 14px;" class="footer_container content_container">
					  Discription:Goods once sold can not be taken back!
					  </div>
					  </div>
					</div>
			',
                    'print_template_type_id' => $B2B_TaxPercentage_Invoice
                ],

                [
                    'name' => 'B2B_TaxPercentage_JobEstimation',
                    'display_name' => 'B2B TaxPercentage JobEstimation',
                    'data' => '<style>
				.workspace
					{
					    display: block;
					    page-break-inside: avoid;
					  page-break-before: avoid;
					  page-break-after: avoid;
					    -webkit-region-break-inside: avoid; 
					}
				</style>
				<div data-type="portrait" style="background: rgb(255, 255, 255);" class="workspace">
				<div class="invoice_print" style="border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				  <div style="position: relative; min-height: 300px; height: 302px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="float: left; position: absolute; top: 143px; left: -0.0000318164px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
				 <hr style="border: 1px solid black; width: 700px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				  <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div>
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 214.233px; left: 4.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 237.233px; left: 11.2333px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 257.35px; left: 10.3499px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST no : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.117px; left: 9.11667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 189.35px; left: 9.34994px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Modal : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.583px; left: 478.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Voucher # :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 186.7px; left: 502.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 2.11665px; left: 207.117px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 48.35px; left: 196.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 77.2333px; left: 185.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 22.1167px; left: 29.1167px;" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 77.2333px; left: 342.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GST : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 106.467px; left: 250.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 22px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
				<div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container">
				<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="invoice_item_table">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">Sl.No</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">PARTICULARS</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">HSN/SAC</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">QTY</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">RATE</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">DISCOUNT</th>
				      <th style="padding: 5px; border: 1px solid black; border-collapse: collapse; text-align: center; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">AMOUNT</th>
				    </tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				     <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				    <td style="padding: 5px; text-align: center; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_hsn">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				    <td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_t_amount">&nbsp;</td>
				   </tr>
				   </tbody><tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				   <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				   <td class="col_id" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">E &amp; OE</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_discount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				      <td style="border-top: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total:<span class="value_result" data-value="total_amount" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">000.00</span></td>
				   </tr>
				   </tfoot>
				  
				</table>
				<table style="width: 100%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<table style="width: 80%; border-collapse: collapse; border: 1px solid black; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="floatedTable">
				<thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">GST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">Taxable</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">IGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">IGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">CGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">CGST Amt</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">SGST%</td>
				<td style="padding: 5px; border-collapse: collapse; border: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">SGST Amt</td>
				</tr>
				</thead>
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_gst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_tax_value">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_igst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_cgst_amount">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst">&nbsp;</td>
				<td style="padding: 5px; border-left: 1px solid black; border-right: 1px solid black; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_sgst_amount">&nbsp;</td>
				</tr>
				</tbody>
				<tfoot style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td colspan="8" style="border-top: 1px solid black; padding-bottom: 25px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Rupees: <i class="value_result" data-value="rupees" style="font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0);">Four Thousand Only</i></td>
				</tr>
				</tfoot>
				</table>
				<table style="width: 20%; float: left; border-color: black; border-style: solid; border-width: 1px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="ft">
				<tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">CGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_cgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">SGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_sgst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">00.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">IGST</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_igst" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Round off</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="round_off" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 5px; padding-bottom: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				<td style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">&nbsp;</td>
				</tr>
				<tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				<td style="padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">TOTAL:</td>
				<td style="text-align: right; padding-top: 20px; padding-bottom: 38px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><span class="value_result" data-value="total_amountwithtax" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); float: right;">000.00</span></td>
				</tr>
				</tbody></table>
				</td>
				</tr></tbody></table>
				</div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; color: rgb(0, 0, 0); width: 100%; font-size: 14px;" class="footer_container content_container">
				  Discription:Goods once sold can not be taken back!
				  </div>
				  </div>
				</div>
			',
                    'print_template_type_id' => $B2B_TaxPercentage_JobEstimation
                ],

                [
                    'name' => 'B2C_NoTax_JobEstimation',
                    'display_name' => 'B2C NoTax JobEstimation',
                    'data' => '<style>
	              .item_table {
	                border-collapse: collapse;
	              }
	               @media print {
	              body {
	                -webkit-print-color-adjust: exact;
	              }
	              }
	              </style>
	              <div data-type="portrait" style="width: 210mm; height: 300mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;border:1px solid" class="workspace">
	                <div style="position: relative; min-height: 200px; height: 266px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -1.53334px; left: 274.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 40.1167px; left: 328.117px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 92.5833px; left: 334.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 182.467px; left: 2.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 202.7px; left: 13.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 144.467px; left: 16.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.583px; left: 14.5833px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 221.467px; left: 22.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 143.467px; left: 514.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="estimate_no">Estimation No</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.467px; left: 544.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Estimation Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.8167px; left: 249.817px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.35px; left: 223.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.2333px; left: 445.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 118.117px; left: 0.116667px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 790px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
	                <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container content_container">
	                  <table class="no_tax_sales_table" style="border-top: 1px solid; border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%">
	                    <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
	                        <th style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">#</th>
	                        <th style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
	                      </tr>
	                    </thead>
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                  <table class="total_table" style="border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" align="right">
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total</td>
	                        <td class="sales_total_amount" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <div style="position: relative; float: left; width: 100%; height: 49px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="footer_container content_container">
	                  
	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: 4.23331px; left: 5.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;">Disclaimer:<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Above work information and amount mentioned here may change<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">during actual Job. The amount for each work or goods<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"> mentioned here has tax included as per the Government norms. <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Please contact us within 7 days of this estimation for any note.<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; position: absolute; top: 3.35001px; left: 585.35px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1.11667px; width: 200px; height: 100px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="rectangle_result"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div>
	              </div>
			',
                    'print_template_type_id' => $B2C_NoTax_JobEstimation
                ],

                [
                    'name' => 'B2C_NoTax_JobInvoice',
                    'display_name' => 'B2C NoTax JobInvoice',
                    'data' => '<style>
	              .item_table {
	                border-collapse: collapse;
	              }
	               @media print {
	              body {
	                -webkit-print-color-adjust: exact;
	              }
	              }
	              </style>
	              <div data-type="portrait" style="width: 210mm; height: 300mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;border:1px solid" class="workspace">
	                <div style="position: relative; min-height: 200px; height: 266px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -1.53334px; left: 274.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 40.1px; left: 328.1px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 92.5667px; left: 334.567px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 182.467px; left: 2.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Customer : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 202.683px; left: 13.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Address:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 144.467px; left: 16.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 164.567px; left: 14.5667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Vehicle :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 221.45px; left: 22.4667px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 142.45px; left: 534.417px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Invoice :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="estimate_no">Estimation No</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 162.467px; left: 544.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Dated : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Estimation Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.8167px; left: 249.817px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.35px; left: 223.333px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 65.2333px; left: 445.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 118.1px; left: 0.100005px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 790px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
	                <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container content_container">
	                  <table class="no_tax_sales_table" style="border-top: 1px solid; border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%">
	                    <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
	                        <th style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">#</th>
	                        <th style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
	                        <th style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
	                      </tr>
	                    </thead>
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
	                        <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
	                     <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
	                        <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                  <table class="total_table" style="border-bottom: 1px solid; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" align="right">
	                    <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                      <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
	                        <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total</td>
	                        <td class="sales_total_amount" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
	                      </tr>
	                    </tbody>
	                  </table>
	                </div>
	                <div style="position: relative; float: left; width: 100%; height: 49px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="footer_container content_container">
	                  
	                <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: 4.21664px; left: 5.23331px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;">Disclaimer:<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Above work information and amount mentioned here may change<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">during actual Job. The amount for each work or goods<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"> mentioned here has tax included as per the Government norms. <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Please contact us within 7 days of this estimation for any note.<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; position: absolute; top: 3.33334px; left: 585.35px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1.11667px; width: 200px; height: 100px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="rectangle_result"></div><div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div>
	              </div>
			',
                    'print_template_type_id' => $B2C_NoTax_JobInvoice
                ],

                [
                    'name' => 'B2C_OneLine_Tax_Invoice',
                    'display_name' => 'B2C OneLine Tax Invoice',
                    'data' => '<style>
				.item_table {
				  border-collapse: collapse;
				}
				 @media print {
				body {
				  -webkit-print-color-adjust: exact;
				}
				}
				</style>
				<div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255);" class="workspace">
				  <div style="position: relative; min-height: 200px; height: 258px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 170.7px; left: 11.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 191.7px; left: 12.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 125.467px; left: 474.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Invoice No :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 147.7px; left: 477.7px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Invoice Dt :</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -2.53334px; left: 209.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 26px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 35px; left: 15px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 54.5833px; left: 173.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 57.5833px; left: 351.583px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 74.4667px; left: 246.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 22px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 130.233px; left: 12.2334px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 149.467px; left: 13.4667px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="make_model_variant">Make-Model-variant</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 213.35px; left: 14.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 32.2333px; left: 235.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 102.117px; left: 0.116728px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 680px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -0.0000165576px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle"></div></div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="body_container content_container">
				    <table class="no_tax_item_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" border="0">
				      <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">#</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
				      <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
				        </tr>
				      </thead>
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				  <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); height: 280px;">
				    <table class="total_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="100%" align="right">
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Sub-Total</td>
				          <td class="invoice_sub_total" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td class="tax_name" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Tax Amount</td>
				          <td class="tax_value" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Total</td>
				          <td class="invoice_total_amount" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				      </tbody>
				    </table>
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 235.05px; left: 402.467px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div></div>
				  <div style="position: relative; float: left; width: 100%; height: 102px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);" class="footer_container content_container">
				    
				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 57px; left: 25px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: -172.883px; left: 7.11673px; font-size: 14px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;">Disclaimer:<br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0);">Products sold can not be returned or Exchanged unless it is mentioned in Company warranty. These prices include Government taxes as per norms</div><div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 14px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: -196.883px; left: 1.1167px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 680px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
				</div>
			',
                    'print_template_type_id' => $B2C_OneLine_Tax_Invoice
                ],

                [
                    'name' => 'B2C_OneLineTax_Job_Estimation',
                    'display_name' => 'B2C OneLineTax Job Estimation',
                    'data' => '<style>
				.item_table {
				  border-collapse: collapse;
				}
				 @media print {
				body {
				  -webkit-print-color-adjust: exact;
				}
				}
				</style>
				<div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
				  <div style="position: relative; min-height: 200px; height: 250px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: -0.0000165576px; left: 0.0000292188px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 111.117px; left: -0.883272px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; position: absolute; top: 118.117px; left: 2.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;">Customer Information:</div><div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 134.117px; left: 4.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 156.117px; left: 2.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 180.117px; left: 2.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 203.467px; left: 2.4667px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 205.117px; left: 5.11673px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="vehicle_number">Vehicle Number</div> <div class="remove" style="display: none; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 114.233px; left: 492.233px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="label_result">Voucher:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="voucher_type">Voucher Type</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 133.35px; left: 472.35px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation No:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="purchase">Purchase</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 154.117px; left: 475.117px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation Dt:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;Times New Roman&quot;, Times, serif; z-index: 1; min-width: 150px; position: absolute; top: 174.117px; left: 474.117px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Estimation By:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="assigned_to">Mechannic Name</div> <div class="remove" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 93.1166px; left: -0.883272px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 665px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -4.65001px; left: 197.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 35.7px; left: 191.7px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 69.35px; left: 145.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 70.35px; left: 330.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div></div>
				  <div style="position: relative; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="body_container content_container">
				    <table class="no_tax_item_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" border="0">
				      <thead style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">#</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
				      <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
				        </tr>
				      </thead>
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <br style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div class="total_container content_container" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <table class="total_table" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" align="right">
				      <tbody style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Sub-Total</td>
				          <td class="invoice_sub_total" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td class="tax_name" style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Tax Amount</td>
				          <td class="tax_value" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">Total</td>
				          <td class="invoice_total_amount" style="text-align: right; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <div style="position: relative; float: left; width: 100%; height: 200px; font-family: &quot;Times New Roman&quot;, Times, serif; font-size: 12px; color: rgb(0, 0, 0);" class="footer_container content_container">
				    
				  <div style="width: auto; height: 10px; float: left; position: absolute; top: -0.0000318164px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 670px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
				</div>
			',
                    'print_template_type_id' => $B2C_OneLineTax_Job_Estimation
                ],

                [
                    'name' => 'Payslip_Print',
                    'display_name' => 'Payslip Print',
                    'data' => '<style>
				.item_table {
					border-collapse: collapse;
					border-width: 0px;
					border: 1px solid #000;
				}
				 @media print {
				body {
					-webkit-print-color-adjust: exact;
				}
				}
				</style>

				<div data-type="portrait" style="width: 273mm; height: 200mm; padding: 57mm 16mm 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
				  <div style="position: relative; min-height: 140px; font-family: Arial, sans-serif; color: rgb(0, 0, 0); height: 232px;" class="header_container content_container">
					<div style="width: auto; float: left; position: absolute; top: 0.999999px; left: 0.0000292188px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 97px; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="rectangle_result"></div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 10px; left: -4.99997px; width: 100%; text-align: center; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-size: 26px; font-family: Tahoma, sans-serif; width:100%;">Demo company</div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 47px; left: -1.99997px; width: 100%; text-align: center; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Tahoma, sans-serif; font-size: 12px; width:100%;"> Abiramam. Tamil Nadu.</div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 67px; left: 0.0000292188px; width: 100%; color: rgb(0, 0, 0); text-align: center;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Tahoma, sans-serif; font-size: 12px; width:100%;">Mobile: 8056259119, Email: rajeshkennedy@yahoo.com, </div>
					  <div class="remove" style="font-family: Arial, sans-serif; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; position: absolute; top: 100px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 40px;" class="rectangle_result"></div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 110px; width: 100%; text-align: center; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  <div style=" display:inline-block;">
						<div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold; display:inline-block;" class="label_result">PAY SLIP FOR THE MONTH</div>
						<div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
					  </div>
					  <div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;display:inline-block;" class="value_result" data-value="salary_month_year">Salary Month - Year</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: -0.00000129883px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle"></div>
					<div style="width: auto; float: left; position: absolute; top: 141px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 100px;" class="rectangle_result"></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 150px; left: 10px;" class="draggable ui-draggable ui-draggable-handle">
					  <div>
						<div style="float:left;">
						  <div style="float: left; padding-right: 15px; width:120px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="label_result">Employee</div>
						  <div style="right: 10px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
						</div>
						<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="employee">Employee Name</div>
						<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  </div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 175px; left: 10px;" class="draggable ui-draggable ui-draggable-handle">
					  <div>
						<div style="float:left;">
						  <div style="float: left; padding-right: 15px; width:120px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="label_result">Employee ID</div>
						  <div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
						</div>
						<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="employee_id">Employee ID</div>
						<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  </div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 200px; left: 10px;" class="draggable ui-draggable ui-draggable-handle">
					  <div>
						<div style="float:left;">
						  <div style="float: left; padding-right: 15px; width:120px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="label_result">Designation</div>
						  <div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
						</div>
						<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="designation">Designation</div>
						<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  </div>
					</div>
				  </div>
				  <div style="position: relative; font-family: Arial, sans-serif; color: rgb(0, 0, 0);float: left; width:914px;" class="body_container content_container">
					<div class="col_earnings" style="float: left; width: 50%;">
					  <table class="item_table earnings" width="100%" border="0">
						<thead>
						  <tr>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:left;" width="50%">Earnings</th>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:right;" width="50%">Amount</th>
						  </tr>
						</thead>
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						  <tr style="background: #f2f2f2">
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						</tbody>
					  </table>
					  <table style="border-top: none;" class="item_table" width="100%" border="0">
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left; font-weight:bold;" width="50%">Total Earnings</td>
							<td style="padding:5px; text-align:right; font-weight:bold;" width="50%"><span data-value="total_earnings"></span></td>
						  </tr>
						</tbody>
					  </table>
					</div>
					<div class="col_deductions" style="float: left; width: 50%;">
					  <table class="item_table deductions" width="100%" border="0">
						<thead>
						  <tr>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:left;" width="50%">Deductions</th>
							<th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 5px 10px; text-align:right;" width="50%">Amount</th>
						  </tr>
						</thead>
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						  <tr style="background: #f2f2f2">
							<td style="padding:5px; text-align:left;"></td>
							<td style="padding:5px; text-align:right;"></td>
						  </tr>
						</tbody>
					  </table>
					  <table style="border-top: none;" class="item_table" width="100%" border="0">
						<tbody>
						  <tr>
							<td style="padding:5px; text-align:left; font-weight:bold;" width="50%">Total Deductions</td>
							<td style="padding:5px; text-align:right; font-weight:bold;" width="50%"><span data-value="total_deductions"></span></td>
						  </tr>
						</tbody>
					  </table>
					</div>
				  </div>
				  <div style="position: relative; float: left; width:100%; font-family: Arial, sans-serif; color: rgb(0, 0, 0);" class="total_container content_container"> </div>
				  <div style="position: relative; height: 123px; font-family: Arial, sans-serif; color: rgb(0, 0, 0); float: left; width: 100%;" class="footer_container content_container">
					<div style="width: auto; float: left; position: absolute; top: -0.0000318164px; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px; width: 912px; height: 35px;" class="rectangle_result"></div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 9.99997px; width: 100%; text-align: right; left: 0.0000292188px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					  <div style=" display:inline-block;">
						<div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold; display:inline-block;" class="label_result selected_item">Net Pay  </div>
						<div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div>
					  </div>
					  <div style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;display:inline-block;" class="value_result" data-value="net_total">Total</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 55px; left: 3.00003px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;">Net Pay (In Words): <span class="net_pay_in_words"></span></div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 55px; left: 170px;" class="draggable ui-draggable ui-draggable-handle">
					<div>
					<div style="float: left; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-weight: bold;" class="value_result" data-value="net_pay_words">Net Pay in Words</div> 
					<div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					</div>
					</div>
					<div style="width: auto; height: 10px; float: left; position: absolute; top: 65px; left: 1px;" class="draggable ui-draggable ui-draggable-handle">
					  <div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1px; width: 912px;" class="line_result">Static Text</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
					<div style="float: left; font-family: Arial, sans-serif; z-index: 1; position: absolute; top: 95px; width: 100%; text-align: center; left: 0px;" class="draggable ui-draggable ui-draggable-handle">
					  <div class="text_result" style="color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: 12px;">*This is computer generated Payslip. Signature not required!</div>
					  <div class="remove" style="display: none;"><i class="fa fa-times"></i></div>
					</div>
				  </div>
			',
                    'print_template_type_id' => $Payslip_Print
                ],

                [
                    'name' => 'PO_Purchase_GRN',
                    'display_name' => 'PO Purchase GRN',
                    'data' => '<style>
				.item_table {
				  border-collapse: collapse;
				}
				 @media print {
				body {
				  -webkit-print-color-adjust: exact;
				}
				}
				</style>
				<div data-type="portrait" style="width: 210mm; height: 200mm; padding: 27mm 16mm; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" class="workspace">
				  <div style="position: relative; min-height: 200px; height: 199px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="header_container content_container">

				  <div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 111px; left: 9.00003px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Supplier:</div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_vendor">Customer / Vendor</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 132px; left: 9.00003px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">GSTN: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_gst">Customer GST</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 154px; left: 11px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Address: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="customer_address">Customer Address</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 113.117px; left: 468.117px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Voucher : </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="grn">Goods Receipt Note</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; min-width: 150px; position: absolute; top: 129.35px; left: 488.35px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="label_result">Date: </div><div style="right: -5px; top: 15px; width: 15px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;" class="value_result" data-value="date">Date</div> <div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: -4.65001px; left: 224.35px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 24px; font-family: &quot;Times New Roman&quot;, Times, serif; font-weight: bold;" class="value_result" data-value="company_name">My company Name</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 39.4667px; left: 236.467px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_address">My company Address</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 66.2333px; left: 185.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">Phone : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_phone">My company Phone</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; float: left; font-family: Arial, sans-serif; z-index: 1; min-width: 150px; position: absolute; top: 67.2333px; left: 371.233px;" class="draggable ui-draggable ui-draggable-handle"><div><div style="float:left;"><div style="float: left; padding-right: 15px; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="label_result">GSTN : </div><div style="right: -5px; top: 15px; width: 15px; display: none;" class="remove"><i class="fa fa-times"></i></div></div><div style="float: left; color: rgb(0, 0, 0); font-size: 14px; font-family: &quot;Times New Roman&quot;, Times, serif;" class="value_result" data-value="company_gst">Company GST</div> <div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div><div style="width: auto; height: 10px; float: left; position: absolute; top: 84.1167px; left: 1.1167px;" class="draggable ui-draggable ui-draggable-handle"><div style="color: transparent; border-style: solid; border-color: rgb(0, 0, 0); border-width: 0px 0px 1.11667px; width: 670px;" class="line_result">Static Text</div><div class="remove" style="display: none;"><i class="fa fa-times"></i></div></div></div>
				  <div style="position: relative; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="body_container content_container">
				    <table class="no_tax_item_table" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" border="0">
				      <thead style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); background-color: rgb(242, 242, 242);">
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">#</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">Item Description</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">Quantity</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">UnitRate RS</th>
				      <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">Discount RS</th>
				          <th style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">Total Rs</th>
				        </tr>
				      </thead>
				      <tbody style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_id">&nbsp;</td>
				          <td style="padding: 5px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_desc">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_quantity">&nbsp;</td>
				       <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_rate">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_discount">&nbsp;</td>
				          <td style="padding: 5px; text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="col_amount">&nbsp;</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <br style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				  <div class="total_container content_container" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				    <table class="total_table" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="100%" align="right">
				      <tbody style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Sub-Total</td>
				          <td class="invoice_sub_total" style="text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td class="tax_name" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Tax Amount</td>
				          <td class="tax_value" style="text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				        <tr style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" width="25%"></td>
				          <td style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Total</td>
				          <td class="invoice_total_amount" style="text-align: right; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">0.00</td>
				        </tr>
				      </tbody>
				    </table>
				  </div>
				  <div style="position: relative; float: left; width: 100%; height: 200px; font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);" class="footer_container content_container">
				    
				  <div style="width: auto; float: left; font-family: &quot;MS Serif&quot;, serif; z-index: 1; position: absolute; top: -0.0000318164px; left: 0.0000292188px; font-size: 12px; color: rgb(0, 0, 0);" class="draggable ui-draggable ui-draggable-handle"><div class="text_result" style="color: rgb(0, 0, 0); font-size: 12px; font-family: &quot;MS Serif&quot;, serif;">Desclaimer:<br style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0);">Above list of items and figures are printed as received. For any discrepancies please contact us within 14 days of receiving this.  </div><div class="remove" style="font-family: &quot;MS Serif&quot;, serif; font-size: 12px; color: rgb(0, 0, 0); display: none;"><i class="fa fa-times"></i></div></div></div>
				</div>
			',
                    'print_template_type_id' => $PO_Purchase_GRN
                ]
            ];
        } else {
            $print_templates = [
                [
                    'name' => 'default_print',
                    'display_name' => 'Default Print',
                    'data' => '',
                    'print_template_type_id' => $default
                ]
            ];
        }

        foreach ($print_templates as $print_template) {
            if (! $account_type->hasPrint('Default Invoice', $id)) {
                $voucher_print = new PrintTemplate();
                $voucher_print->name = $print_template['name'];
                $voucher_print->display_name = $print_template['display_name'];
                $voucher_print->print_template_type_id = $print_template['print_template_type_id'];
                $voucher_print->data = $print_template['data'];
                $voucher_print->original_data = $print_template['data'];
                $voucher_print->delete_status = 1;
                $voucher_print->save();
                self::userby($voucher_print, true, $id);
                if ($is_person == true) {
                    self::account_type($id, $voucher_print, true);
                } else {
                    self::account_type($id, $voucher_print, false);
                }

                if (! $is_person) {
                    Custom::add_addon('records');
                }
            }
        }

        if ($is_person == true) {
            $default_print = PrintTemplate::where('name', 'default_print')->where('user_id', $id)->first();

            $account_voucher_format_id = AccountVoucherFormat::where('name', 'Default Format')->where('user_id', $id)->first();
        } else {
            $default_print = PrintTemplate::where('name', 'default_print')->where('organization_id', $id)->first();

            $B2B_HSNbased_Invoice_print = PrintTemplate::where('name', 'B2B_HSNbased_Invoice')->where('organization_id', $id)->first();
            $B2B_HSNbased_JobEstimation_print = PrintTemplate::where('name', 'B2B_HSNbased_JobEstimation')->where('organization_id', $id)->first();
            $B2B_TaxPercentage_Invoice_print = PrintTemplate::where('name', 'B2B_TaxPercentage_Invoice')->where('organization_id', $id)->first();
            $B2B_TaxPercentage_JobEstimation_print = PrintTemplate::where('name', 'B2B_TaxPercentage_JobEstimation')->where('organization_id', $id)->first();

            $B2C_NoTax_JobEstimation_print = PrintTemplate::where('name', 'B2C_NoTax_JobEstimation')->where('organization_id', $id)->first();
            $B2C_NoTax_JobInvoice_print = PrintTemplate::where('name', 'B2C_NoTax_JobInvoice')->where('organization_id', $id)->first();
            $B2C_OneLine_Tax_Invoice_print = PrintTemplate::where('name', 'B2C_OneLine_Tax_Invoice')->where('organization_id', $id)->first();
            $B2C_OneLineTax_Job_Estimation_print = PrintTemplate::where('name', 'B2C_OneLineTax_Job_Estimation')->where('organization_id', $id)->first();

            $Payslip_Print_temp = PrintTemplate::where('name', 'Payslip_Print')->where('organization_id', $id)->first();

            $PO_Purchase_GRN_print = PrintTemplate::where('name', 'PO_Purchase_GRN')->where('organization_id', $id)->first();

            $account_voucher_format_id = AccountVoucherFormat::where('name', 'Default Format')->where('organization_id', $id)->first();
        }

        $ledger_groups = [
            [
                'name' => 'fixed_asset',
                'display_name' => 'Fixed Asset',
                'head' => $asset->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'investments',
                'display_name' => 'Investments',
                'head' => $asset->id,
                'opening_type' => 'debit',
                'connections' => [
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'misc_expenses',
                'display_name' => 'Misc. Expenses (ASSET)',
                'head' => $asset->id,
                'opening_type' => 'debit',
                'connections' => [
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'fixed_asset',
                'display_name' => 'Fixed Asset',
                'head' => $asset->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'current_asset',
                'display_name' => 'Current Asset',
                'head' => $asset->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id,
                    $bank_ledger->id,
                    $nbfc_ledger->id
                ],
                'child' => [
                    [
                        'name' => 'sundry_debtor',
                        'display_name' => 'Sundry Debtors',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ],
                    [
                        'name' => 'bank_account',
                        'display_name' => 'Bank Account',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id,
                            $bank_ledger->id,
                            $nbfc_ledger->id
                        ]
                    ],
                    [
                        'name' => 'deposit',
                        'display_name' => 'Deposits (Asset)',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id,
                            $bank_ledger->id,
                            $nbfc_ledger->id
                        ]
                    ],
                    [
                        'name' => 'cash',
                        'display_name' => 'Cash-in-hand',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ],
                    [
                        'name' => 'stock_in_hand',
                        'display_name' => 'Stock in Hand',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ],
                    [
                        'name' => 'other_current_asset',
                        'display_name' => 'Other Current Asset',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ],
                    [
                        'name' => 'loans_advances',
                        'display_name' => 'Loans & Advances (Asset)',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id,
                            $bank_ledger->id,
                            $nbfc_ledger->id
                        ]
                    ],
                    [
                        'name' => 'employees',
                        'display_name' => 'Employees',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id
                        ]
                    ]
                ]
            ],

            [
                'name' => 'current_account',
                'display_name' => 'Current Account',
                'head' => $asset->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'purchase_account',
                'display_name' => 'Purchase Accounts',
                'head' => $expense->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'direct_expense',
                'display_name' => 'Direct Expenses',
                'head' => $expense->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'indirect_expense',
                'display_name' => 'Indirect Expenses',
                'head' => $expense->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ],
                'child' => [
                    [
                        'name' => 'establishment_expenses',
                        'display_name' => 'Establishment Expenses',
                        'opening_type' => 'debit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id,
                            $bank_ledger->id,
                            $nbfc_ledger->id
                        ],
                        'child' => [
                            [
                                'name' => 'salary',
                                'display_name' => 'Salary',
                                'opening_type' => 'debit',
                                'connections' => [
                                    $personal_ledger->id,
                                    $impersonal_ledger->id,
                                    $bank_ledger->id,
                                    $nbfc_ledger->id
                                ]
                            ]
                        ]
                    ]
                ]
            ],

            [
                'name' => 'write_off',
                'display_name' => 'Write-Off',
                'head' => $expense->id,
                'opening_type' => 'debit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'sale_account',
                'display_name' => 'Sale Account',
                'head' => $income->id,
                'opening_type' => 'credit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'direct_income',
                'display_name' => 'Direct Income',
                'head' => $income->id,
                'opening_type' => 'credit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'indirect_income',
                'display_name' => 'Indirect Income',
                'head' => $income->id,
                'opening_type' => 'credit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'capital',
                'display_name' => 'Capital A/C',
                'head' => $liability->id,
                'opening_type' => 'credit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id
                ],
                'child' => [
                    [
                        'name' => 'reserves_surplus',
                        'display_name' => 'Reserves & Surplus',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ]
                ]
            ],

            [
                'name' => 'suspense',
                'display_name' => 'Suspense A/c',
                'head' => $liability->id,
                'opening_type' => 'credit',
                'connections' => [
                    $impersonal_ledger->id
                ]
            ],

            [
                'name' => 'loan_liability',
                'display_name' => 'Loans (Liability)',
                'head' => $liability->id,
                'opening_type' => 'credit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id,
                    $bank_ledger->id,
                    $nbfc_ledger->id
                ],
                'child' => [
                    [
                        'name' => 'bank_od',
                        'display_name' => 'Bank OD A/C',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id,
                            $bank_ledger->id,
                            $nbfc_ledger->id
                        ]
                    ],
                    [
                        'name' => 'secured_loan',
                        'display_name' => 'Secured Loan',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id,
                            $bank_ledger->id,
                            $nbfc_ledger->id
                        ]
                    ],
                    [
                        'name' => 'unsecured_loan',
                        'display_name' => 'Unsecured Loan',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id,
                            $bank_ledger->id,
                            $nbfc_ledger->id
                        ]
                    ]
                ]
            ],

            [
                'name' => 'current_liability',
                'display_name' => 'Current Liability',
                'head' => $liability->id,
                'opening_type' => 'credit',
                'connections' => [
                    $personal_ledger->id,
                    $impersonal_ledger->id,
                    $bank_ledger->id,
                    $nbfc_ledger->id
                ],
                'child' => [
                    [
                        'name' => 'sundry_creditor',
                        'display_name' => 'Sundry creditors',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ],
                    [
                        'name' => 'duties_taxes',
                        'display_name' => 'Duties & Taxes',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ],
                    [
                        'name' => 'provision',
                        'display_name' => 'Provision',
                        'opening_type' => 'credit',
                        'connections' => [
                            $personal_ledger->id,
                            $impersonal_ledger->id
                        ]
                    ]
                ]
            ]
        ];

        foreach ($ledger_groups as $ledger_group) {
            $parent = self::create_group($ledger_group['name'], $id, $ledger_group['connections'], $account_type, $ledger_group['display_name'], $ledger_group['head'], null, $ledger_group['opening_type'], '1', '0', $is_person);

            if (isset($ledger_group['child'])) {
                foreach ($ledger_group['child'] as $child) {
                    $sub = self::create_group($child['name'], $id, $child['connections'], $account_type, $child['display_name'], $parent['head'], $parent['group'], $child['opening_type'], '1', '0', $is_person);

                    if (isset($child['child'])) {
                        foreach ($child['child'] as $c) {
                            self::create_group($c['name'], $id, $c['connections'], $account_type, $c['display_name'], $sub['head'], $sub['group'], $c['opening_type'], '1', '0', $is_person);
                        }
                    }
                }
            }
        }

        $account_financial_year = new AccountFinancialYear();

        if (date('n') > 3) {
            $start_year = Carbon::createFromDate(null, 04, 01)->format('Y-m-d');
            $end_year = Carbon::createFromDate(null, 04, 01)->addYear()->format('Y-m-d');
        } else {
            $start_year = Carbon::createFromDate(null, 04, 01)->subYear()->format('Y-m-d');
            $end_year = Carbon::createFromDate(null, 03, 31)->format('Y-m-d');
        }

        $account_financial_year->name = $start_year . "-" . $end_year;
        $account_financial_year->books_start_year = $start_year;
        $account_financial_year->books_end_year = $end_year;
        $account_financial_year->financial_start_year = $start_year;
        $account_financial_year->financial_end_year = $end_year;

        if ($is_person == true) {
            $account_financial_year->user_id = $id;
            $account_financial_year->save();
        } else {
            $account_financial_year->organization_id = $id;
            $account_financial_year->save();
        }

        if (! $is_person) {
            Custom::add_addon('records');
        }

        if ($is_person == true) {
            $year = AccountFinancialYear::where('user_id', $id)->first();
            $ledgergroup = AccountGroup::where('name', 'cash')->where('user_id', $id)->first();
        } else {
            $year = AccountFinancialYear::where('organization_id', $id)->first();
            $ledgergroup = AccountGroup::where('name', 'cash')->where('organization_id', $id)->first();
        }

        $cash_ledger = self::create_ledger('Cash', $account_type, 'Cash', $impersonal_ledger->id, null, null, $ledgergroup->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

        if ($is_person != true) {
            $capital = AccountGroup::where('name', 'capital')->where('organization_id', $id)->first();
            $duties_taxes = AccountGroup::where('name', 'duties_taxes')->where('organization_id', $id)->first();
            $sale_account = AccountGroup::where('name', 'sale_account')->where('organization_id', $id)->first();
            $purchase_account = AccountGroup::where('name', 'purchase_account')->where('organization_id', $id)->first();
            $fixed_asset = AccountGroup::where('name', 'fixed_asset')->where('organization_id', $id)->first();
            $current_asset = AccountGroup::where('name', 'current_asset')->where('organization_id', $id)->first();
            $indirect_expense = AccountGroup::where('name', 'indirect_expense')->where('organization_id', $id)->first();
            $direct_expense = AccountGroup::where('name', 'direct_expense')->where('organization_id', $id)->first();
            $indirect_income = AccountGroup::where('name', 'indirect_income')->where('organization_id', $id)->first();
            $sundry_debtor = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $id)->first();
            $sundry_creditor = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $id)->first();
            $current_liability = AccountGroup::where('name', 'duties_taxes')->where('organization_id', $id)->first();
            $other_current_asset = AccountGroup::where('name', 'other_current_asset')->where('organization_id', $id)->first();

            self::create_ledger('opening_equity', $account_type, 'Equity', $impersonal_ledger->id, null, null, $capital->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            /*
             * self::create_ledger('input_gst', $account_type, 'Input GST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('input_cgst', $account_type, 'Input CGST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('input_sgst', $account_type, 'Input SGST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('input_igst', $account_type, 'Input IGST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('output_gst', $account_type, 'Output GST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('output_cgst', $account_type, 'Output CGST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('output_sgst', $account_type, 'Output SGST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('output_igst', $account_type, 'Output IGST', $impersonal_ledger->id, null, null, $duties_taxes->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             */

            self::create_ledger('inventory_asset', $account_type, 'Inventory Asset', $impersonal_ledger->id, null, null, $other_current_asset->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('accounts_receivable', $account_type, 'Accounts Receivable (Debtors)', $impersonal_ledger->id, null, null, $sundry_debtor->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('inventory_asset', $account_type, 'Inventory Asset', $impersonal_ledger->id, null, null, $current_asset->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('uncategorised_asset', $account_type, 'Uncategorised Asset', $impersonal_ledger->id, null, null, $current_asset->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('undeposited_funds', $account_type, 'Undeposited Funds', $impersonal_ledger->id, null, null, $ledgergroup->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('building_assets', $account_type, 'Building Assets', $impersonal_ledger->id, null, null, $fixed_asset->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('furniture_equipments', $account_type, 'Furniture and Equipments', $impersonal_ledger->id, null, null, $fixed_asset->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('accounts_payable', $account_type, 'Accounts Payable (Creditors)', $impersonal_ledger->id, null, null, $sundry_creditor->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('purchase_discounts', $account_type, 'Purchase Discounts', $impersonal_ledger->id, null, null, $indirect_income->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('sales', $account_type, 'Sales', $impersonal_ledger->id, null, null, $sale_account->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('Uncategorised Income', $account_type, 'Uncategorised Income', $impersonal_ledger->id, null, null, $indirect_income->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('sales_discounts', $account_type, 'Sales Discounts', $impersonal_ledger->id, null, null, $indirect_expense->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('sale_return', $account_type, 'Sale Return', $impersonal_ledger->id, null, null, $sale_account->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('purchases', $account_type, 'Purchases', $impersonal_ledger->id, null, null, $purchase_account->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('purchase_return', $account_type, 'Purchase Return', $impersonal_ledger->id, null, null, $purchase_account->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('computer_internet_expense', $account_type, 'Computer and Internet Expense', $impersonal_ledger->id, null, null, $indirect_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('computer_internet_expense', $account_type, 'Computer and Internet Expense', $impersonal_ledger->id, null, null, $indirect_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('PF Employer Contribution', $account_type, 'PF Employer Contribution', $impersonal_ledger->id, null, null, $indirect_expense->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('PF Employer Contribution Payable', $account_type, 'PF Employer Contribution Payable', $impersonal_ledger->id, null, null, $indirect_expense->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('ESI Employer Contribution', $account_type, 'ESI Employer Contribution', $impersonal_ledger->id, null, null, $indirect_expense->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('ESI Employer Contribution Payable', $account_type, 'ESI Employer Contribution Payable', $impersonal_ledger->id, null, null, $indirect_expense->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            self::create_ledger('Salary Payable', $account_type, 'Salary Payable', $impersonal_ledger->id, null, null, $current_liability->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);

            /*
             * self::create_ledger('conveyance_allowance', $account_type, 'Conveyance Allowance', $impersonal_ledger->id, null, null, $direct_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('esi_employer', $account_type, 'ESI Employer', $impersonal_ledger->id, null, null, $direct_expense->id, $year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('house_rent_allowance', $account_type, 'House Rent Allowance', $impersonal_ledger->id, null, null, $direct_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('medical_allowance', $account_type, 'Medical Allowance', $impersonal_ledger->id, null, null, $direct_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('PF Employer', $account_type, 'PF Employer', $impersonal_ledger->id, null, null, $direct_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('special_allowance', $account_type, 'Special Allowance', $impersonal_ledger->id, null, null, $direct_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);
             *
             * self::create_ledger('travel_expense', $account_type, 'Travel Expense', $impersonal_ledger->id, null, null, $direct_expense->id, $year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);
             */
        }

        $personal_voucher_array = [
            [
                'name' => 'receipt',
                'display_name' => 'Cash Receipt',
                'code' => 'CR',
                'debit_ledger' => $cash_ledger,
                'voucher_type' => 'receipt',
                'format' => $account_voucher_format_id->id,
                'print' => $default_print->id,
                'type' => '1',
                'delete' => '0',
                'account_status' => '1'
            ],
            [
                'name' => 'payment',
                'display_name' => 'Cash Payment',
                'code' => 'CP',
                'debit_ledger' => $cash_ledger,
                'voucher_type' => 'payment',
                'format' => $account_voucher_format_id->id,
                'print' => $default_print->id,
                'type' => '0',
                'delete' => '0',
                'account_status' => '1'
            ],
            [
                'name' => 'deposit',
                'display_name' => 'Bank Deposit',
                'code' => 'BD',
                'debit_ledger' => null,
                'voucher_type' => 'deposit',
                'format' => $account_voucher_format_id->id,
                'print' => $default_print->id,
                'type' => '1',
                'delete' => '0',
                'account_status' => '1'
            ],
            [
                'name' => 'withdrawal',
                'display_name' => 'Bank Withdrawal',
                'code' => 'BW',
                'debit_ledger' => null,
                'voucher_type' => 'withdrawal',
                'format' => $account_voucher_format_id->id,
                'print' => $default_print->id,
                'type' => '0',
                'delete' => '0',
                'account_status' => '1'
            ],
            [
                'name' => 'credit_note',
                'display_name' => 'Credit Note',
                'code' => 'CN',
                'debit_ledger' => null,
                'voucher_type' => 'credit_note',
                'format' => $account_voucher_format_id->id,
                'print' => $default_print->id,
                'type' => '1',
                'delete' => '0',
                'account_status' => '1'
            ],
            [
                'name' => 'debit_note',
                'display_name' => 'Debit Note',
                'code' => 'DN',
                'debit_ledger' => null,
                'voucher_type' => 'debit_note',
                'format' => $account_voucher_format_id->id,
                'print' => $default_print->id,
                'type' => '0',
                'delete' => '0',
                'account_status' => '1'
            ],
            [
                'name' => 'journal',
                'display_name' => 'Journal Entry',
                'code' => 'JE',
                'debit_ledger' => null,
                'voucher_type' => 'journal',
                'format' => $account_voucher_format_id->id,
                'print' => $default_print->id,
                'type' => null,
                'delete' => '0',
                'account_status' => '1'
            ]
        ];

        foreach ($personal_voucher_array as $personal_voucher) {
            self::create_voucher($personal_voucher['name'], $personal_voucher['display_name'], $account_type, $personal_voucher['code'], $personal_voucher['voucher_type'], $personal_voucher['debit_ledger'], $personal_voucher['format'], $personal_voucher['print'], $personal_voucher['delete'], $id, $personal_voucher['type'], $is_person, $personal_voucher['account_status']);
        }

        if (! $is_person) {

            $voucher_array = [
                [
                    'name' => 'receipt',
                    'display_name' => 'Cash Receipt',
                    'code' => 'CR',
                    'debit_ledger' => $cash_ledger,
                    'voucher_type' => 'receipt',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '1',
                    'delete' => '0',
                    'account_status' => '1'
                ],
                [
                    'name' => 'payment',
                    'display_name' => 'Cash Payment',
                    'code' => 'CP',
                    'debit_ledger' => $cash_ledger,
                    'voucher_type' => 'payment',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '0',
                    'delete' => '0',
                    'account_status' => '1'
                ],
                [
                    'name' => 'deposit',
                    'display_name' => 'Bank Deposit',
                    'code' => 'BD',
                    'debit_ledger' => null,
                    'voucher_type' => 'deposit',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '1'
                ],
                [
                    'name' => 'withdrawal',
                    'display_name' => 'Bank Withdrawal',
                    'code' => 'BW',
                    'debit_ledger' => null,
                    'voucher_type' => 'withdrawal',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '1'
                ],
                [
                    'name' => 'credit_note',
                    'display_name' => 'Credit Note',
                    'code' => 'CN',
                    'debit_ledger' => null,
                    'voucher_type' => 'credit_note',
                    'type' => '1',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'delete' => '0',
                    'account_status' => '1'
                ],
                [
                    'name' => 'debit_note',
                    'display_name' => 'Debit Note',
                    'code' => 'DN',
                    'debit_ledger' => null,
                    'voucher_type' => 'debit_note',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '0',
                    'delete' => '0',
                    'account_status' => '1'
                ],
                [
                    'name' => 'journal',
                    'display_name' => 'Journal Entry',
                    'code' => 'JE',
                    'debit_ledger' => null,
                    'voucher_type' => 'journal',
                    'type' => null,
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'delete' => '0',
                    'account_status' => '1'
                ],
                [
                    'name' => 'payroll',
                    'display_name' => 'Payroll',
                    'code' => 'PR',
                    'debit_ledger' => $cash_ledger,
                    'voucher_type' => 'payroll',
                    'format' => $account_voucher_format_id->id,
                    'print' => $Payslip_Print_temp->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'purchase_order',
                    'display_name' => 'Purchase Order',
                    'code' => 'PO',
                    'debit_ledger' => null,
                    'voucher_type' => 'purchase_order',
                    'format' => $account_voucher_format_id->id,
                    'print' => $PO_Purchase_GRN_print->id,
                    'type' => '0',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'purchases',
                    'display_name' => 'Purchase',
                    'code' => 'PU',
                    'debit_ledger' => null,
                    'voucher_type' => 'purchase',
                    'format' => $account_voucher_format_id->id,
                    'print' => $PO_Purchase_GRN_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'goods_receipt_note',
                    'display_name' => 'Goods Receipt Note',
                    'code' => 'GRN',
                    'debit_ledger' => null,
                    'voucher_type' => 'goods_receipt_note',
                    'format' => $account_voucher_format_id->id,
                    'print' => $PO_Purchase_GRN_print->id,
                    'type' => '0',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'sale_order',
                    'display_name' => 'Sale Order',
                    'code' => 'SO',
                    'debit_ledger' => null,
                    'voucher_type' => 'sale_order',
                    'format' => $account_voucher_format_id->id,
                    'print' => $B2C_OneLine_Tax_Invoice_print->id,
                    'type' => '1',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'sales',
                    'display_name' => 'Invoice',
                    'code' => 'IN',
                    'debit_ledger' => null,
                    'voucher_type' => 'sales',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '1',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'sales_cash',
                    'display_name' => 'Invoice Cash',
                    'code' => 'INC',
                    'debit_ledger' => null,
                    'voucher_type' => 'sales',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '1',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'delivery_note',
                    'display_name' => 'Delivery Note',
                    'code' => 'DN',
                    'debit_ledger' => null,
                    'voucher_type' => 'delivery_note',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '1',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'estimation',
                    'display_name' => 'Estimation',
                    'code' => 'ES',
                    'debit_ledger' => null,
                    'voucher_type' => 'sale_order',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '1',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'internal_consumption',
                    'display_name' => 'Internal Consumption',
                    'code' => 'IC',
                    'debit_ledger' => null,
                    'voucher_type' => 'goods_receipt_note',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '0',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'material_receipt',
                    'display_name' => 'Material Receipt',
                    'code' => 'MR',
                    'debit_ledger' => null,
                    'voucher_type' => 'goods_receipt_note',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => '0',
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'stock_journal',
                    'display_name' => 'Stock Journal',
                    'code' => 'SJ',
                    'debit_ledger' => null,
                    'voucher_type' => 'stock_journal',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'adjustment',
                    'display_name' => 'Adjustment',
                    'code' => 'AD',
                    'debit_ledger' => null,
                    'voucher_type' => 'stock_journal',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'job_card',
                    'display_name' => 'Job Card',
                    'code' => 'JC',
                    'debit_ledger' => null,
                    'voucher_type' => 'job_card',
                    'format' => $account_voucher_format_id->id,
                    'print' => $B2B_TaxPercentage_Invoice_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'job_request',
                    'display_name' => 'Job Estimation',
                    'code' => 'JE',
                    'debit_ledger' => null,
                    'voucher_type' => 'job_request',
                    'format' => $account_voucher_format_id->id,
                    'print' => $B2B_TaxPercentage_Invoice_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'job_invoice',
                    'display_name' => 'Job Invoice',
                    'code' => 'JI',
                    'debit_ledger' => null,
                    'voucher_type' => 'job_invoice',
                    'format' => $account_voucher_format_id->id,
                    'print' => $B2B_TaxPercentage_Invoice_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'job_invoice_cash',
                    'display_name' => 'Job Invoice Cash',
                    'code' => 'JIC',
                    'debit_ledger' => null,
                    'voucher_type' => 'job_invoice',
                    'format' => $account_voucher_format_id->id,
                    'print' => $B2B_TaxPercentage_Invoice_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ],
                [
                    'name' => 'wms_receipt',
                    'display_name' => 'WMS Receipt',
                    'code' => 'WR',
                    'debit_ledger' => null,
                    'voucher_type' => 'wms_receipt',
                    'format' => $account_voucher_format_id->id,
                    'print' => $default_print->id,
                    'type' => null,
                    'delete' => '0',
                    'account_status' => '0'
                ]
            ];

            foreach ($voucher_array as $voucher) {
                self::create_voucher($voucher['name'], $voucher['display_name'], $account_type, $voucher['code'], $voucher['voucher_type'], $voucher['debit_ledger'], $voucher['format'], $voucher['print'], $voucher['delete'], $id, $voucher['type'], $is_person, $voucher['account_status']);
            }
        }

        // JobInvoice = B2B_HSNbased_Invoice, B2B_TaxPercentage_Invoice , B2C_NoTax_JobInvoice, B2C_OneLine_Tax_Invoice

        // JobEstimation = B2B_HSNbased_JobEstimation , B2B_TaxPercentage_JobEstimation , B2C_NoTax_JobEstimation, B2C_OneLineTax_Job_Estimation

        /* Multi Print for job estimation , job invoice */

        $job_estimation = AccountVoucherType::where('name', 'job_request')->first();
        $job_invoice = AccountVoucherType::where('name', 'job_invoice')->first();

        if (! $is_person) {
            if ($job_estimation->id != null) {

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_estimation->id;
                $multi_template->print_temp_id = $B2B_HSNbased_JobEstimation_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_estimation->id;
                $multi_template->print_temp_id = $B2B_TaxPercentage_JobEstimation_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_estimation->id;
                $multi_template->print_temp_id = $B2C_NoTax_JobEstimation_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_estimation->id;
                $multi_template->print_temp_id = $B2C_OneLineTax_Job_Estimation_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();
            }

            if ($job_invoice->id != null) {

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_invoice->id;
                $multi_template->print_temp_id = $B2B_HSNbased_Invoice_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_invoice->id;
                $multi_template->print_temp_id = $B2B_TaxPercentage_Invoice_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_invoice->id;
                $multi_template->print_temp_id = $B2C_NoTax_JobInvoice_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();

                $multi_template = new MultiTemplate();
                $multi_template->voucher_id = $job_invoice->id;
                $multi_template->print_temp_id = $B2C_OneLine_Tax_Invoice_print->id;
                $multi_template->organization_id = Session::get('organization_id');
                $multi_template->created_by = Auth::user()->id;
                $multi_template->save();
            }
        }

        /* End */

        if ($is_person == true) {

            $cash = AccountLedger::where('name', 'Cash')->where('user_id', $id)->first();

            $personal_account = new PersonalAccount();
            $personal_account->name = $cash->name;
            $personal_account->user_id = $id;
            $personal_account->ledger_id = $cash->id;
            $personal_account->delete_status = 0;
            $personal_account->save();

            $direct_expense = AccountGroup::where('name', 'direct_expense')->where('user_id', $id)->first();
            $direct_income = AccountGroup::where('name', 'direct_income')->where('user_id', $id)->first();
            $current_liability = AccountGroup::where('name', 'current_liability')->where('user_id', $id)->first();

            $personal_expenses = [
                [
                    'name' => 'Electricity',
                    'image' => 'electricity'
                ],
                [
                    'name' => 'Entertainment',
                    'image' => 'entertainment'
                ],
                [
                    'name' => 'Food',
                    'image' => 'food'
                ],
                [
                    'name' => 'Gas',
                    'image' => 'gas'
                ],
                [
                    'name' => 'Grocery',
                    'image' => 'grocery'
                ],
                [
                    'name' => 'Internet',
                    'image' => 'internet'
                ],
                [
                    'name' => 'Medical',
                    'image' => 'medical'
                ],
                [
                    'name' => 'Fuel',
                    'image' => 'petrol'
                ],
                [
                    'name' => 'Travel',
                    'image' => 'travel'
                ],
                [
                    'name' => 'Water',
                    'image' => 'water'
                ],
                [
                    'name' => 'Bus Fare',
                    'image' => 'bus'
                ],
                [
                    'name' => 'Education',
                    'image' => 'education'
                ],
                [
                    'name' => 'EMI',
                    'image' => 'emi'
                ],
                [
                    'name' => 'Insurance',
                    'image' => 'insurance'
                ],
                [
                    'name' => 'Rent',
                    'image' => 'rent'
                ],
                [
                    'name' => 'Mobile',
                    'image' => 'mobile'
                ]
            ];

            $personal_incomes = [
                [
                    'name' => 'Salary',
                    'image' => 'salary'
                ],
                [
                    'name' => 'Commissions',
                    'image' => 'commission'
                ],
                [
                    'name' => 'Gifts',
                    'image' => 'gift'
                ],
                [
                    'name' => 'Interest',
                    'image' => 'interest'
                ],
                [
                    'name' => 'Investment',
                    'image' => 'investment'
                ],
                [
                    'name' => 'Mutual Fund',
                    'image' => 'mutual_fund'
                ],
                [
                    'name' => 'Provident Fund',
                    'image' => 'provident_fund'
                ],
                [
                    'name' => 'Savings',
                    'image' => 'saving'
                ],
                [
                    'name' => 'Wallet recharge',
                    'image' => 'wallet_recharge'
                ]
            ];

            $personal_liabilities = [
                [
                    'name' => 'Credit Card',
                    'image' => 'loan'
                ],
                [
                    'name' => 'Loan',
                    'image' => 'loan'
                ]
            ];

            $expense = PersonalTransactionType::where('name', 'expense')->first()->id;
            $income = PersonalTransactionType::where('name', 'income')->first()->id;
            $liability = PersonalTransactionType::where('name', 'liability')->first()->id;

            foreach ($personal_expenses as $personal_expense) {

                $category = new PersonalCategory();
                $category->name = $personal_expense['name'];
                $category->transaction_type = $expense;
                $category->ledger_id = self::create_ledger($personal_expense['name'], $account_type, $personal_expense['name'], $impersonal_ledger->id, null, null, $current_liability->id, $account_financial_year->books_start_year, 'debit', '0.00', '1', '0', $id, $is_person);
                $category->user_id = $id;
                $category->image = $personal_expense['image'] . '.png';
                $category->save();
                self::userby($category, true);
            }

            foreach ($personal_incomes as $personal_income) {

                $category = new PersonalCategory();
                $category->name = $personal_income['name'];
                $category->transaction_type = $income;
                $category->ledger_id = self::create_ledger($personal_income['name'], $account_type, $personal_income['name'], $impersonal_ledger->id, null, null, $direct_income->id, $account_financial_year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
                $category->user_id = $id;
                $category->image = $personal_income['image'] . '.png';
                $category->save();
                self::userby($category, true);
            }

            foreach ($personal_liabilities as $personal_liability) {

                $category = new PersonalCategory();
                $category->name = $personal_liability['name'];
                $category->transaction_type = $liability;
                $category->ledger_id = self::create_ledger($personal_liability['name'], $account_type, $personal_liability['name'], $impersonal_ledger->id, null, null, $current_liability->id, $account_financial_year->books_start_year, 'credit', '0.00', '1', '0', $id, $is_person);
                $category->user_id = $id;
                $category->image = $personal_liability['image'] . '.png';
                $category->save();
                self::userby($category, true);
            }
        }
    }

    public static function createPerson($data, $add_user)
    {

        // $dob = explode('-', $data['dob']);
        $dob = $data['dob'];
        $city = City::select('name')->where('id', $data['city'])->first()->name;

        $crm_code = self::personal_crm($city, $data['mobile'], $data['first_name']);

        $person = new Person();
        $person->crm_code = $crm_code;
        $person->first_name = $data['first_name'];
        $person->last_name = $data['last_name'];
        $person->mother_name = ($data['mother_name'] != "") ? $data['mother_name'] : null;
        $person->father_name = ($data['father_name'] != "") ? $data['father_name'] : null;
        /* $person->dob = $dob[2].'-'.$dob[1].'-'.$dob[0]; */
        $person->dob = ($dob != null) ? Carbon::parse($dob)->format('Y-m-d') : null;
        $person->save();

        $person_address = new PersonCommunicationAddress();
        $person_address->person_id = $person->id;
        $person_address->address_type = 1;
        $person_address->city_id = ($data['city'] != "") ? $data['city'] : null;
        $person_address->mobile_no = $data['mobile'];
        $person_address->mobile_no_prev = $data['mobile'];
        $person_address->email_address = $data['email'];
        $person_address->email_address_prev = $data['email'];
        $person_address->save();

        if ($add_user == true && ($person->id != "" || $person->id != null)) {
            self::createUser($person->id, $data, $add_user);
        } else {
            return array(
                'person_id' => $person->id,
                'first_name' => $data['first_name'] . " " . $data['last_name']
            );
        }
    }

    public static function createHrm($id, $my_organization)
    {
        Custom::createAttendanceType($id, $my_organization, 'Present', '#198917', '1', '0', '1');

        Custom::createPersonType($id, $my_organization, 'Employee', '1', '0');
        Custom::createPersonType($id, $my_organization, 'Guest', '0', '0');

        Custom::createEmploymentType($id, $my_organization, 'Permanent');

        Custom::createAttendanceSetting($id, $my_organization, 'General');

        Custom::createBreak($id, $my_organization, 'Lunch', Carbon::parse('13:00:00')->format('H:i:s'), Carbon::parse('14:00:00')->format('H:i:s'));

        Custom::createDepartment($id, $my_organization, 'General');
        Custom::createDepartment($id, $my_organization, 'Sales');

        $department_id = HrmDepartment::where('name', 'General')->where('organization_id', $id)->first()->id;

        Custom::createDesignation($id, $my_organization, 'General', $department_id);

        $attendance_setting = HrmAttendanceSetting::where('name', 'General')->where('organization_id', $id)->first()->id;

        Custom::createShift($id, $my_organization, 'General', Carbon::parse('10:00:00')->format('H:i:s'), Carbon::parse('19:00:00')->format('H:i:s'), $attendance_setting);

        Custom::createLeaveTypes($id, $my_organization, 'Casual Leave', 'CL', '#00b6ff', '1', '1');
        Custom::createLeaveTypes($id, $my_organization, 'Sick Leave', 'SL', '#ff6e00', '1', '1');
        Custom::createLeaveTypes($id, $my_organization, 'Leave', "L", '#d60202', '0', '0');

        Custom::createHolidayTypes($id, $my_organization, 'National Holiday', "NH", '#ff2100', '1', '0');
        Custom::createHolidayTypes($id, $my_organization, 'Local Holiday', "LH", '#7c7c7c', '1', '0');

        $holiday_type = HrmHolidayType::where('name', 'National Holiday')->first()->id;

        $state = BusinessCommunicationAddress::select('states.name')->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
            ->leftjoin('states', 'cities.state_id', '=', 'states.id')
            ->where('business_id', $my_organization->business_id)
            ->first()->name;

        $country = State::select('countries.name')->leftjoin('countries', 'states.country_id', '=', 'countries.id')
            ->where('states.name', $state)
            ->first()->name;

        Custom::createHolidays($id, $my_organization, 'New Year', date('Y-01-01'), $holiday_type, 1);

        if ($country == "India") {
            if ($state == "Tamil Nadu") {
                Custom::createHolidays($id, $my_organization, 'Pongal', date('Y-01-14'), $holiday_type, 1);
            }

            if ($state == "Gujarat") {
                Custom::createHolidays($id, $my_organization, 'Uttarayan', date('Y-01-14'), $holiday_type, 1);
            }

            Custom::createHolidays($id, $my_organization, 'Republic Day', date('Y-01-26'), $holiday_type, 1);

            if ($state == "Tamil Nadu") {
                Custom::createHolidays($id, $my_organization, 'Tamil Varuda Pirappu', date('Y-04-14'), $holiday_type, 1);
            }

            if ($state == "Kerala") {
                Custom::createHolidays($id, $my_organization, 'Vishu', date('Y-04-14'), $holiday_type, 1);
            }

            if ($state == "Tripura" || $state == "West Bengal") {
                Custom::createHolidays($id, $my_organization, 'Pohela Boishakh', date('Y-04-14'), $holiday_type, 1);
            }

            if ($state == "Assam") {
                Custom::createHolidays($id, $my_organization, 'Bihu', date('Y-04-15'), $holiday_type, 1);
            }

            if ($state == "Odisha") {
                Custom::createHolidays($id, $my_organization, 'Maha Vishuva Sankranti', date('Y-04-15'), $holiday_type, 1);
            }

            Custom::createHolidays($id, $my_organization, 'Independence Day', date('Y-08-15'), $holiday_type, 1);

            Custom::createHolidays($id, $my_organization, 'Gandhi Jayanti', date('Y-10-02'), $holiday_type, 1);
        }

        Custom::createHolidays($id, $my_organization, 'Christmas', date('Y-12-25'), $holiday_type, 1);

        $first_week_off = Weekday::where('name', 'sunday')->first()->id;

        Custom::createWeekoff($id, $my_organization, 'Weekoff', date('Y-m-d'), '#e0e0e0', $first_week_off, '1', '1', '0');

        Custom::createPayrollFrequency($id, $my_organization, 'Daily', 'D', '0');
        Custom::createPayrollFrequency($id, $my_organization, 'Weekly', 'W', '1', Weekday::where('name', 'saturday')->first()->id);
        Custom::createPayrollFrequency($id, $my_organization, 'Monthly', 'M', '2', null, 5);

        $frequency_id = HrmPayrollFrequency::where('name', 'Monthly')->where('organization_id', $id)->first()->id;

        Custom::createSalaryScale($id, $my_organization, 'ZeroScale', $frequency_id);

        $earnings = HrmPayHeadType::where('name', 'earnings')->first();
        $deductions = HrmPayHeadType::where('name', 'deductions')->first();

        $earning_ledger = AccountGroup::where('name', 'salary')->where('organization_id', $id)->first()->id;

        $deduction_ledger = AccountGroup::where('name', 'current_liability')->where('organization_id', $id)->first()->id;

        $indirect_income = AccountGroup::where('name', 'indirect_income')->where('organization_id', $id)->first()->id;

        $current_asset = AccountGroup::where('name', 'current_asset')->where('organization_id', $id)->first()->id;

        Custom::createPayHead($id, $my_organization, 'Basic', 'Basic', 'Basic', $earnings->id, $earning_ledger, '0', null, '1', '2');

        Custom::createPayHead($id, $my_organization, 'PF Employee Contribution', 'PF Employee Contribution', 'PF Employee Contribution', $deductions->id, $deduction_ledger, '0', null, '1', '2');

        // Custom::createPayHead($id, $my_organization, 'PF Employer Contribution', 'PF Employer Contribution', 'PF Employer Contribution', $deductions->id, $deduction_ledger, '0', '1', '2');

        Custom::createPayHead($id, $my_organization, 'ESI Employee Contribution', 'ESI Employee Contribution', 'ESI Employee Contribution', $deductions->id, $deduction_ledger, '0', null, '1', '2');

        // Custom::createPayHead($id, $my_organization, 'ESI Employer Contribution', 'ESI Employer Contribution', 'ESI Employer Contribution', $deductions->id, $deduction_ledger, '0', '1', '2');

        Custom::createPayHead($id, $my_organization, 'Recover from Employee', 'Recover from Employee', 'Recover from Employee', $deductions->id, $indirect_income, '0');

        Custom::createPayHead($id, $my_organization, 'Penalty/Fine', 'Penalty/Fine', 'Penalty/Fine', $deductions->id, $indirect_income, '0');

        Custom::createPayHead($id, $my_organization, 'Advance', 'Advance', 'Advance', $deductions->id, $current_asset, '0');

        $payhead_id = HrmPayHead::where('name', 'Basic')->where('organization_id', $id)->first()->id;

        $salary_scale_id = HrmSalaryScale::where('name', 'ZeroScale')->where('organization_id', $id)->first()->id;

        Custom::createSalaryPayHead($id, $my_organization, $payhead_id, $salary_scale_id);
    }

    public static function createPersonType($id, $my_organization, $name, $type, $delete_status)
    {
        if (! $my_organization->hasPersonType($name, $id)) {
            $person = new HrmPersonType();
            $person->name = $name;
            $person->type = $type;
            $person->organization_id = $id;
            $person->delete_status = $delete_status;
            $person->save();
            Custom::userby($person, false);
            Custom::add_addon('records');
        }
    }

    public static function createEmploymentType($id, $my_organization, $name)
    {
        if (! $my_organization->hasEmploymentType($name, $id)) {
            $employment_type = new HrmEmploymentType();
            $employment_type->name = $name;
            $employment_type->organization_id = $id;
            $employment_type->save();
            Custom::userby($employment_type, false);
            Custom::add_addon('records');
        }
    }

    public static function createAttendanceSetting($id, $my_organization, $name)
    {
        if (! $my_organization->hasAttendanceSetting($name, $id)) {
            $attendance_setting = new HrmAttendanceSetting();
            $attendance_setting->name = $name;
            $attendance_setting->organization_id = $id;
            $attendance_setting->save();
            Custom::userby($attendance_setting, false);
            Custom::add_addon('records');
        }
    }

    public static function createAttendanceType($id, $my_organization, $name, $color, $paid_status, $delete_status, $attendance_status = null)
    {
        if (! $my_organization->hasAttendanceType($name, $id)) {
            $attendance = new HrmAttendanceType();
            $attendance->name = $name;
            $attendance->display_name = $name;
            $attendance->color = $color;
            $attendance->organization_id = $id;
            $attendance->attendance_status = ($attendance_status != null) ? $attendance_status : 0;
            $attendance->paid_status = $paid_status;
            $attendance->delete_status = $delete_status;
            $attendance->save();
            Custom::userby($attendance, true);
            Custom::add_addon('records');
        }
    }

    /**
     * get_sting_diff function return the difference of the first string
     * 5-7-19
     */
    public static function get_string_diff($old, $new)
    {
        $from_start = strspn($old ^ $new, "\0");
        $from_end = strspn(strrev($old) ^ strrev($new), "\0");

        $old_end = strlen($old) - $from_end;
        $new_end = strlen($new) - $from_end;

        $start = substr($new, 0, $from_start);
        $end = substr($new, $new_end);
        $new_diff = substr($new, $from_start, $new_end - $from_start);
        $old_diff = substr($old, $from_start, $old_end - $from_start);

        // $new = "$start<ins style='background-color:#ccffcc'>$new_diff</ins>$end";
        // $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
        // return array("old"=>$old, "new"=>$new);
        return (int) $old_diff;
    }

    public static function createBreak($id, $my_organization, $name, $start_time, $end_time)
    {
        if (! $my_organization->hasBreak($name, $id)) {
            $breaks = new HrmBreak();
            $breaks->name = $name;
            $breaks->start_time = $start_time;
            $breaks->end_time = $end_time;
            $breaks->organization_id = $id;
            $breaks->save();
            Custom::userby($breaks, true);

            Custom::add_addon('records');
        }
    }

    public static function createDepartment($id, $my_organization, $name)
    {
        if (! $my_organization->hasDepartment($name, $id)) {
            $department = new HrmDepartment();
            $department->name = $name;
            $department->organization_id = $id;
            $department->save();

            Custom::userby($department, true);
            Custom::add_addon('records');
        }
    }

    public static function createDesignation($id, $my_organization, $name, $department_id)
    {
        if (! $my_organization->hasDesignation($name, $id)) {
            $designation = new HrmDesignation();
            $designation->name = $name;
            $designation->department_id = $department_id;
            $designation->organization_id = $id;
            $designation->save();

            Custom::userby($designation, true);
            Custom::add_addon('records');
        }
    }

    public static function createShift($id, $my_organization, $name, $from, $to, $attendance_setting)
    {
        if (! $my_organization->hasShift($name, $id)) {

            $from_time = Carbon::parse($from);
            $to_time = Carbon::parse($to);

            $workshift = new HrmShift();
            $workshift->name = $name;
            $workshift->from_time = $from_time;
            $workshift->to_time = $to_time;
            $workshift->total_hours = $to_time->diffInMinutes($from_time) / 60;
            $workshift->attendance_settings_id = $attendance_setting;
            $workshift->organization_id = $id;
            $workshift->save();

            Custom::userby($workshift, true);
            Custom::add_addon('records');
        }
    }

    public static function createLeaveTypes($id, $my_organization, $name, $code, $color, $paid_status, $delete_status)
    {
        if (! $my_organization->hasLeaveTypes($name, $id)) {
            $leave_types = new HrmLeaveType();

            $leave_types->name = $name;
            $leave_types->display_name = $name;
            $leave_types->code = $code;
            $leave_types->organization_id = $id;
            $leave_types->save();

            if ($leave_types) {
                Custom::createAttendanceType($id, $my_organization, $name, $color, $paid_status, $delete_status);
            }

            Custom::userby($leave_types, true);
            Custom::add_addon('records');
        }
    }

    public static function createHolidayTypes($id, $my_organization, $name, $code, $color, $paid_status, $delete_status)
    {
        if (! $my_organization->hasHolidayTypes($name, $id)) {
            $holiday_types = new HrmHolidayType();
            $holiday_types->name = $name;
            $holiday_types->code = $code;
            $holiday_types->pay_status = $paid_status;
            $holiday_types->organization_id = $id;
            $holiday_types->save();

            if ($holiday_types) {
                Custom::createAttendanceType($id, $my_organization, $name, $color, $paid_status, $delete_status);
            }

            Custom::userby($holiday_types, true);
            Custom::add_addon('records');
        }
    }

    public static function createHolidays($id, $my_organization, $name, $date, $holiday_type, $continue_status = null)
    {
        if (! $my_organization->hasHolidays($name, $id)) {
            $holiday = new HrmHoliday();
            $holiday->name = $name;

            $holiday->holiday_date = $date;
            $holiday->display_name = $name;
            if ($continue_status != null) {
                $holiday->continue_status = $continue_status;
            }
            $holiday->holiday_type_id = $holiday_type;
            $holiday->organization_id = $id;
            $holiday->save();

            Custom::userby($holiday, true);
            Custom::add_addon('records');
        }
    }

    public static function createPayHead($id, $my_organization, $name, $display_name, $code, $payhead_type, $payhead_ledger, $calculation_type = null, $formula = null, $fixed_month = null, $wage_type = null, $fixed_days = null, $minimum_attendance = null, $ledger_id = null, $description = null)
    {
        if (! $my_organization->hasHolidays($name, $id)) {

            $pay_head = new HrmPayHead();
            $pay_head->payhead_type_id = $payhead_type;
            $pay_head->code = $code;
            $pay_head->name = $name;
            $pay_head->display_name = $display_name;
            $pay_head->calculation_type = $calculation_type;
            $pay_head->formula = $formula;
            $pay_head->wage_type = $wage_type;
            $pay_head->fixed_month = $fixed_month;
            $pay_head->fixed_days = $fixed_days;

            if ($minimum_attendance != null) {
                $pay_head->minimum_attendance = $minimum_attendance;
            }

            if ($ledger_id != null) {
                $pay_head->ledger_id = $ledger_id;
            } else {
                $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();
                $pay_head->ledger_id = self::create_ledger($pay_head->name, $my_organization, $pay_head->display_name, $impersonal_ledger->id, null, null, $payhead_ledger, date('Y-m-d'), 'debit', '0.00', '1', '1', $id, false);
            }

            $pay_head->description = $description;
            $pay_head->organization_id = $id;
            $pay_head->save();

            Custom::userby($pay_head, true);
            Custom::add_addon('records');
        }
    }

    public static function createWeekoff($id, $my_organization, $name, $effective_date, $color, $first_week_off, $paid_status, $delete_status, $first_week_off_period = null, $second_week_off = null, $second_week_off_period = null)
    {
        if (! $my_organization->hasWeekoff($name, $id)) {
            $effective_date = $effective_date;

            $weekoff = new HrmWeekOff();
            $weekoff->name = $name;
            $weekoff->effective_date = $effective_date;
            $weekoff->first_week_off = $first_week_off;
            if ($first_week_off_period != null) {
                $weekoff->first_week_off_period = $first_week_off_period;
            }

            if ($second_week_off != null) {
                $weekoff->second_week_off = $second_week_off;
            }

            if ($second_week_off_period != null) {
                $weekoff->second_week_off_period = $second_week_off_period;
            }

            $weekoff->pay_status = $paid_status;
            $weekoff->organization_id = $id;
            $weekoff->save();

            if ($weekoff) {
                Custom::createAttendanceType($id, $my_organization, $name, $color, $paid_status, $delete_status);
            }

            Custom::userby($weekoff, true);
            Custom::add_addon('records');
        }
    }

    public static function createPayrollFrequency($id, $my_organization, $name, $code, $type = null, $week_day_id = null, $salary_day = null, $salary_period = null)
    {
        if (! $my_organization->hasPayrollFrequency($name, $id)) {
            $frequency = new HrmPayrollFrequency();
            $frequency->name = $name;
            $frequency->code = $code;
            $frequency->frequency_type = $type;

            if ($week_day_id != null) {
                $frequency->week_day_id = $week_day_id;
            }

            if ($salary_day != null && $type == 2 && $week_day_id == null) {
                $frequency->salary_day = $salary_day;
            }

            if ($salary_period != null) {
                $frequency->salary_period = $salary_period;
            }
            $frequency->organization_id = $id;
            $frequency->save();

            Custom::userby($frequency, true);
            Custom::add_addon('records');
        }
    }

    public static function createSalaryScale($id, $my_organization, $name, $frequency_id)
    {
        if (! $my_organization->hasSalaryScale($name, $id)) {

            $salaryscale = new HrmSalaryScale();
            $salaryscale->name = $name;
            $salaryscale->code = 'ZS';
            $salaryscale->frequency_id = $frequency_id;
            $salaryscale->status = 1;
            $salaryscale->organization_id = $id;
            $salaryscale->save();

            Custom::userby($salaryscale, true);
            Custom::add_addon('records');
        }
    }

    public static function createSalaryPayHead($id, $my_organization, $payhead_id, $salary_scale_id)
    {
        if ($salary_scale_id != null) {

            DB::table('hrm_salary_scale_pay_head')->insert([
                'pay_head_id' => $payhead_id,
                'salary_scale_id' => $salary_scale_id,
                'value' => 0.00
            ]);
        }
    }

    public static function createWarehouse($id, $my_organization, $name, $contact_person_id, $business_id)
    {
        $warehouse = BusinessCommunicationAddress::where('placename', $name)->where('business_id', $my_organization->business_id)->first();

        if ($warehouse == null) {

            $address_type_name = BusinessAddressType::where('name', 'warehouse')->first();

            $businesscommunicationaddress = new BusinessCommunicationAddress();
            $businesscommunicationaddress->address_type = $address_type_name->id;
            $businesscommunicationaddress->placename = $name;
            $businesscommunicationaddress->contact_person_id = $contact_person_id;
            $businesscommunicationaddress->business_id = $business_id;
            $businesscommunicationaddress->save();
            Custom::userby($businesscommunicationaddress, true);
            Custom::add_addon('records');

            return true;
        } else {
            return false;
        }
    }

    public static function createStore($id, $my_organization, $warehouse, $name)
    {
        if (! $my_organization->hasInventoryStore($name, $id)) {

            $warehouse_id = BusinessCommunicationAddress::find($warehouse)->id;

            $store = new InventoryStore();
            $store->name = $name;
            $store->warehouse_id = $warehouse_id;
            $store->organization_id = $id;
            $store->save();

            Custom::userby($store, true);
            Custom::add_addon('records');
        }
    }

    public static function createRack($id, $my_organization, $store, $name)
    {
        $existing_inventory = InventoryRack::where('name', $name)->where('store_id', $store)->first();

        if ($existing_inventory == null) {

            $store_id = InventoryStore::find($store)->id;

            $rack = new InventoryRack();
            $rack->name = $name;
            $rack->store_id = $store_id;
            $rack->save();

            Custom::userby($rack, true);
            Custom::add_addon('records');
        }
    }

    public static function createShipmentMode($id, $my_organization, $name)
    {
        if (! $my_organization->hasBreak($name, $id)) {
            $shipment = new ShipmentMode();
            $shipment->name = $name;
            $shipment->organization_id = $id;
            $shipment->save();

            Custom::userby($shipment, true);
            Custom::add_addon('records');
        }
    }

    public static function createService($id, $my_organization, $name)
    {
        if (! $my_organization->hasServiceType($name, $id)) {
            $service_type = new ServiceType();
            $service_type->name = $name;
            $service_type->display_name = $name;
            $service_type->is_chargeable = 1;
            $service_type->organization_id = $id;
            $service_type->status = 1;
            $service_type->save();
            Custom::userby($service_type, true);
            Custom::add_addon('records');
        }
    }

    public static function createTaxGroup($name, $is_sales, $is_purchase, $tax_type_id, $id, $my_organization, $is_person)
    {
        if (! $my_organization->hasTaxGroup($name, $id)) {
            $tax_group = new TaxGroup();
            $tax_group->name = $name;
            $tax_group->display_name = $name;
            $tax_group->is_sales = $is_sales;
            $tax_group->is_purchase = $is_purchase;
            $tax_group->tax_type_id = $tax_type_id;
            $tax_group->organization_id = $id;
            $tax_group->save();
            Custom::userby($tax_group, true);
            Custom::add_addon('records');
        } else {
            $tax_group = TaxGroup::where('name', $name)->where('organization_id', $id)->first();
        }

        return $tax_group;
    }

    public static function createTax($name, $value, $is_percent, $is_sales, $is_purchase, $purchase_ledger_id, $sales_ledger_id, $tax_group, $id, $my_organization, $is_person)
    {
        if (! $my_organization->hasTax($name, $id)) {

            $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();
            $duties_taxes = AccountGroup::where('name', 'duties_taxes')->where('organization_id', $id)->first();

            $ledger = Custom::create_ledger($name, $organization, $name, $impersonal_ledger->id, null, null, $duties_taxes->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $id, false);

            $tax = new Tax();
            $tax->name = $name;
            $tax->display_name = $name;
            $tax->value = $value;
            $tax->is_percent = $is_percent;
            $tax->is_sales = $is_sales;
            $tax->is_purchase = $is_purchase;
            $tax->purchase_ledger_id = $ledger;
            $tax->sales_ledger_id = $ledger;
            $tax->organization_id = $id;
            $tax->save();
            Custom::userby($tax, true);
            Custom::add_addon('records');

            $group_tax = DB::table('group_tax')->where('group_id', $tax_group->id)
                ->where('tax_id', $tax->id)
                ->first();

            if ($group_tax == null) {
                $tax_group->taxes()->attach($tax);
            }
        } else {
            $tax = TaxGroup::where('name', $name)->where('organization_id', $id)->first();
        }

        return $tax;
    }

    public static function createInventoryItem($name, $category, $tax_group, $rate, $sale_account, $purchase_account, $inventory_account, $unit, $id, $my_organization, $hsn = null, $include_sale_tax = null, $include_purchase_tax = null, $is_person)
    {
        if (! $my_organization->hasInventoryItem($name, $id)) {
            $item = new InventoryItem();
            $item->name = $name;
            $item->hsn = $hsn;
            $item->category_id = $category;
            $item->include_tax = $include_tax;
            $item->tax_id = $tax_group;
            if ($other_item->include_tax != null) {
                $item->sale_price_data = json_encode([
                    [
                        "sale_price" => $rate,
                        "on_date" => date('Y-m-d')
                    ]
                ]);
            } else {
                $item->sale_price_data = json_encode([
                    [
                        "sale_price" => $rate,
                        "on_date" => date('Y-m-d')
                    ]
                ]);
            }

            $item->include_purchase_tax = $include_purchase_tax;

            if ($item->include_purchase_tax != null) {
                $item->purchase_price = $rate;
            } else {
                $item->purchase_price = $rate;
            }

            $item->income_account = $sale_account;
            $item->expense_account = $purchase_account;
            $item->inventory_account = $inventory_account;
            $item->unit_id = $unit;
            $item->purchase_tax_id = $tax_group;
            $item->organization_id = $id;
            $item->save();

            Custom::userby($item, true);
            Custom::add_addon('records');
        } else {
            $item = InventoryItem::where('name', $name)->where('organization_id', $id)->first();
        }

        return $item;
    }

    public static function createDiscount($name, $value, $id, $is_percent, $is_sales, $is_purchase, $purchase_ledger_id, $sales_ledger_id, $my_organization, $is_person)
    {
        if (! $my_organization->hasDiscount($name, $id)) {
            $discount = new Discount();
            $discount->name = $discount->name;
            $discount->display_name = $name;
            $discount->value = $value;
            $discount->is_percent = $is_percent;
            $discount->is_sales = $is_sales;
            $discount->is_purchase = $is_purchase;
            $discount->sales_ledger_id = $sales_ledger_id;
            $discount->purchase_ledger_id = $purchase_ledger_id;
            $discount->organization_id = $id;

            $discount->save();

            Custom::userby($discount, true);
            Custom::add_addon('records');
        } else {
            $discount = Unit::where('name', $name)->where('organization_id', $id)->first();
        }

        return $discount;
    }

    public static function createUnit($name, $id, $my_organization, $is_person)
    {
        if (! $my_organization->hasUnit($name, $id)) {
            $unit = new Unit();
            $unit->name = $name;
            $unit->display_name = $name;
            $unit->organization_id = $id;
            $unit->save();
            Custom::userby($unit, true);
            Custom::add_addon('records');
        } else {
            $unit = Unit::where('name', $name)->where('organization_id', $id)->first();
        }

        return $unit;
    }

    public static function createTrade($id, $my_organization)
    {
        $gst_type_id = TaxType::where('name', 'gst')->first()->id;
        $igst_type_id = TaxType::where('name', 'igst')->first()->id;

        $tax_groups = [
            [
                'name' => '3.0% GST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $gst_type_id,
                'tax' => [
                    [
                        'name' => '1.5% CGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '1.5'
                    ],
                    [
                        'name' => '1.5% SGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '1.5'
                    ]
                ]
            ],
            [
                'name' => '3.0% IGST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $igst_type_id,
                'tax' => [
                    [
                        'name' => '3.0% IGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '3.0'
                    ]
                ]
            ],
            [
                'name' => '5.0% GST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $gst_type_id,
                'tax' => [
                    [
                        'name' => '2.5% CGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '2.5'
                    ],
                    [
                        'name' => '2.5% SGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '2.5'
                    ]
                ]
            ],
            [
                'name' => '5.0% IGST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $igst_type_id,
                'tax' => [
                    [
                        'name' => '5.0% IGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '5.0'
                    ]
                ]
            ],
            [
                'name' => '12.0% GST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $gst_type_id,
                'tax' => [
                    [
                        'name' => '6.0% CGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '6.0'
                    ],
                    [
                        'name' => '6.0% SGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '6.0'
                    ]
                ]
            ],
            [
                'name' => '12.0% IGST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $igst_type_id,
                'tax' => [
                    [
                        'name' => '12.0% IGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '12.0'
                    ]
                ]
            ],
            [
                'name' => '18.0% GST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $gst_type_id,
                'tax' => [
                    [
                        'name' => '9.0% CGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '9.0'
                    ],
                    [
                        'name' => '9.0% SGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '9.0'
                    ]
                ]
            ],
            [
                'name' => '18.0% IGST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $igst_type_id,
                'tax' => [
                    [
                        'name' => '18.0% IGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '18.0'
                    ]
                ]
            ],
            [
                'name' => '28.0% GST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $gst_type_id,
                'tax' => [
                    [
                        'name' => '14.0% CGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '14.0'
                    ],
                    [
                        'name' => '14.0% SGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '14.0'
                    ]
                ]
            ],
            [
                'name' => '28.0% IGST',
                'is_percent' => '1',
                'is_sales' => '1',
                'is_purchase' => '1',
                'tax_type_id' => $igst_type_id,
                'tax' => [
                    [
                        'name' => '28.0% IGST',
                        'is_percent' => '1',
                        'is_sales' => '1',
                        'is_purchase' => '1',
                        'value' => '28.0'
                    ]
                ]
            ]
        ];

        $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();
        $duties_taxes = AccountGroup::where('name', 'duties_taxes')->where('organization_id', $id)->first();

        foreach ($tax_groups as $group) {

            if (! $my_organization->hasTaxGroup($group['name'], $id)) {
                $tax_group = new TaxGroup();
                $tax_group->name = $group['name'];
                $tax_group->display_name = $group['name'];
                $tax_group->is_sales = $group['is_sales'];
                $tax_group->is_purchase = $group['is_purchase'];
                $tax_group->tax_type_id = $group['tax_type_id'];
                $tax_group->organization_id = $id;
                $tax_group->save();
                Custom::userby($tax_group, true);
                Custom::add_addon('records');
            } else {
                $tax_group = TaxGroup::where('name', $group['name'])->where('organization_id', $id)->first();
            }

            foreach ($group['tax'] as $value) {

                if (! $my_organization->hasTax($value['name'], $id)) {
                    $tax = new Tax();
                    $tax->name = $value['name'];
                    $tax->display_name = $value['name'];
                    $tax->value = $value['value'];
                    $tax->is_percent = $value['is_percent'];
                    $tax->is_sales = $value['is_sales'];
                    $tax->is_purchase = $value['is_purchase'];
                    $tax->organization_id = $id;
                    $tax->save();

                    $ledger = Custom::create_ledger($tax->name, $my_organization, $tax->display_name, $impersonal_ledger->id, null, null, $duties_taxes->id, date('Y-m-d'), 'debit', '0.00', '1', '1', $id, false);

                    $tax->purchase_ledger_id = $ledger;
                    $tax->sales_ledger_id = $ledger;
                    $tax->save();

                    Custom::userby($tax, true);
                    Custom::add_addon('records');

                    $group_tax = DB::table('group_tax')->where('tax_id', $tax->id)
                        ->where('group_id', $tax_group->id)
                        ->first();
                    if ($group_tax == null) {
                        $tax_group->taxes()->attach($tax);
                    }
                }
            }
        }

        if (! $my_organization->hasUnit('pcs', $id)) {
            $unit = new Unit();
            $unit->name = 'pcs';
            $unit->display_name = 'pcs';
            $unit->organization_id = $id;
            $unit->save();
            Custom::userby($unit, true);
            Custom::add_addon('records');
        }

        if (Custom::createWarehouse($id, $my_organization, 'Primary Warehouse', Auth::user()->person_id, $my_organization->business_id)) {

            $warehouse = BusinessCommunicationAddress::where('placename', 'Primary Warehouse')->where('business_id', $my_organization->business_id)->first()->id;

            Custom::createStore($id, $my_organization, $warehouse, 'Primary Store');

            $store = InventoryStore::where('name', 'Primary Store')->where('organization_id', $id)->first()->id;

            Custom::createRack($id, $my_organization, $store, 'Primary Rack');
        }

        Custom::createShipmentMode($id, $my_organization, 'General Shipment');
    }

    public static function createUser($id, $person_data, $add_user)
    {
        $pass = null;
        /*
         * if($person_data['password'] == "") {
         * $pass = self::randomKey(6);
         * } else {
         * $pass = $person_data['password'];
         * }
         */

        $newuser = new User();
        $newuser->name = $person_data['first_name'];
        $newuser->mobile = $person_data['mobile'];
        $newuser->email = $person_data['email'];
        /* $newuser->password = Hash::make($pass); */
        $newuser->person_id = $id;
        $newuser->otp_time = Carbon::now()->format('Y-m-d H:i:s');
        $newuser->otp = self::otp(4);
        $newuser->otp_sent = 1;
        $newuser->save();

        Session::forget('organization_id');

        $email = $newuser->email;
        $name = $newuser->name;
        // dd($newuser->id);
        $repo = new ActivationRepository();
        $token = $repo->createActivation($newuser);

        $url = route('user.activate', [
            $token
        ]);

        if ($person_data['password'] == "") {
            $data = [
                'name' => $name,
                'url' => $url,
                'pass' => $pass,
                'logo' => URL::to('/') . '/assets/layout/images/logo.png'
            ];
        } else {
            $data = [
                'name' => $name,
                'url' => $url,
                'pass' => '',
                'logo' => URL::to('/') . '/assets/layout/images/logo.png'
            ];
        }

        /* app('Illuminate\Contracts\Bus\Dispatcher')->dispatch(new SendVerificationEmail($add_user, $data, $email, $name, $newuser->mobile, $newuser->otp)); */
        /*
         * Mail::send('emails.mail_verification', $data, function ($message) use ($email, $name) {
         * $message->from('info@support.in', 'PropelERP');
         * $message->to($email, $name);
         * $message->subject("Verify your email address");
         * });
         */

        if ($add_user == true) {
            if (count(Mail::failures()) > 0) {
                $activation['message'] = config('constants.messages.activation_error');
            } else {
                $activation['message'] = config('constants.messages.activation');
                self::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $newuser->mobile, "$newuser->otp " . config('constants.messages.sms_activation'));
            }
        }

        return $newuser->otp;
    }

    public static function add_entry($date, $entries, $entry_id, $voucher_type, $id, $status, $is_person, $voucher_no = null, $gn_no = null, $ref_voucher = null, $ref_transaction = null, $grn = null, $payment_mode = null, $description = null, $checked_value = null, $inv_entry = null, $cash_payment = null)
    {

        // Log::info("custom add_entry :-first".json_encode($entries));
        // echo "<pre>";
        // print_r($voucher_type);
        // echo "</pre>";
        // exit;

        // Delete Previous Entries with given Entry
        if (isset($entry_id) && $entry_id != null) {

            AccountRecurring::where('id', $entry_id)->delete();
            AccountAllocation::where('id', $entry_id)->delete();

            if ($cash_payment == null) {
                AccountTransaction::where('entry_id', $entry_id)->delete();
            }
        }

        $years = AccountFinancialYear::select('voucher_year_format')->where('organization_id', $id)
            ->where('status', 1)
            ->first();
        // dd($years->voucher_year_format);
        $year = '';
        // $crnt_year = $now->year;
        Log::info("custom->add_entry :-years" . json_encode($years));
        if ($years->voucher_year_format == null) {
            // $now = Carbon::now()->format('Y');
            // dd("sdf");
            $year = Carbon::now()->format('Y');
        } else {

            $year = $years->voucher_year_format;
        }
        Log::info("custom->add_entry :-year" . json_encode($year));

        if (empty($entry_id) && $entry_id == null) {

            Log::info("custom->add_entry : Line No 4109");
            if ($is_person == true) {
                Log::info("custom->add_entry : Line No 5078 get id".json_encode($id));
                Log::info("custom->add_entry : Line No 5079 get voucher type".json_encode($voucher_type));
                $voucher_master = AccountVoucher::where('user_id', $id)->where('name', $voucher_type)->first();
                Log::info("custom->add_entry : Line No 5080 get voucher_master".json_encode($voucher_master));
                $previous_entry = AccountEntry::where('voucher_id', $voucher_master->id)->where('user_id', $id)
                    ->orderby('id', 'desc')
                    ->first();
                    Log::info("custom->add_entry : Line No 5084 get previous_entry" . json_encode($previous_entry));
            } else {
                Log::info("custom->add_entry : Line No 4116");
                $voucher_master = AccountVoucher::where('organization_id', $id)->where('name', $voucher_type)->first();
                Log::info("custom->add_entry : Line No 4118 voucher_master data " . json_encode($voucher_master->id));

                $previous_entry = AccountEntry::where('voucher_id', $voucher_master->id)->where('organization_id', $id)
                    ->orderby('id', 'desc')
                    ->first();
                Log::info("custom->add_entry : Line No 4120" . json_encode($previous_entry));
            }

            if ($gn_no != null) {
                $gen_no = $gn_no;
                Log::info("custom->add_entry : Line No 4127");
            } else {

                Log::info("custom->add_entry : Line No 4130 " . json_encode($voucher_type));
                // $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;
                $vou_restart_value = AccountVoucher::select('restart')->where('organization_id', $id)
                    ->where('name', $voucher_type)
                    ->first();

                Log::info("custom->add_entry : Line No 4134" . json_encode($vou_restart_value));
                    
                $gen_no = ($vou_restart_value->restart == 1) ? $voucher_master->starting_value : ($previous_entry->gen_no + 1);

                Log::info("custom->add_entry : Line No 4136");
            }

            $accountentries = new AccountEntry();

            $accountentries->status = $status;
            if ($payment_mode != null) {
                $accountentries->reference_voucher_id = $ref_voucher;
            }

            $accountentries->description = $description;

            $accountentries->voucher_no = self::generate_accounts_number($voucher_type, $gen_no, $is_person, ($is_person) ? $id : null);

            $accountentries->gen_no = $gen_no;

            if ($payment_mode != null) {
                $accountentries->payment_mode_id = $payment_mode;
            }

            if ($is_person == true) {
                $accountentries->user_id = $id;
            } else {
                $accountentries->organization_id = $id;
            }

            $accountentries->voucher_id = $voucher_master->id;
            Custom::userby($accountentries, true, ($is_person) ? $id : null);
            $accountentryid = $accountentries->id;

            Log::info("custom->add_entry : Line No 4168");
        } else {

            $accountentries = AccountEntry::findOrfail($entry_id);
            $accountentryid = $accountentries->id;

            Log::info("custom :-Else" . json_encode($accountentries));

            if ($voucher_type == "job_invoice_cash") {
                // dd($accountentries);
            }
        }

        Log::info("custom->add_entry : Line No 4182");
        if ($grn != null) {
            $accountentries->reference_voucher_id = $grn;
        } else {
            $accountentries->reference_voucher_id = $ref_transaction;
        }

        Log::info("custom->add_entry : Line No 4189");
        $accountentries->grn_no = $grn;
        $accountentries->reference_transaction_id = $ref_transaction;
        // $accountentries->reference_voucher = $reference_voucher;
        $accountentries->date = $date;
        $accountentries->save();

        Log::info("custom->add_entry : Line No 4196");
        $accountentryid = $accountentries->id;

        if ($accountentryid) {

            Log::info("custom->add_entry : Line No 4203");

            foreach ($entries as $entry) {

                if ($entry['amount'] == null) {
                    $amount = '0.00';
                } else {
                    $amount = $entry['amount'];
                }

                if ($cash_payment == null) {
                    $voucheraccount = new AccountTransaction();
                    $voucheraccount->debit_ledger_id = $entry['debit_ledger_id'];
                    $voucheraccount->credit_ledger_id = $entry['credit_ledger_id'];
                    $voucheraccount->description = "";
                    $voucheraccount->amount = $amount;
                    $voucheraccount->entry_id = $accountentryid;
                    $voucheraccount->save();

                    Custom::userby($voucheraccount, true, ($is_person) ? $id : null);
                }

                if ($cash_payment != null && $entry_id == null) {
                    $voucheraccount = new AccountTransaction();
                    $voucheraccount->debit_ledger_id = $entry['debit_ledger_id'];
                    $voucheraccount->credit_ledger_id = $entry['credit_ledger_id'];
                    $voucheraccount->description = "";
                    $voucheraccount->amount = $amount;
                    $voucheraccount->entry_id = $accountentryid;
                    $voucheraccount->save();

                    Custom::userby($voucheraccount, true, ($is_person) ? $id : null);
                }
            }

            if ($checked_value == "yes") {

                $debit_ledger_id = AccountLedger::select('id')->where('name', '=', 'sales_discounts')
                    ->where('organization_id', $id)
                    ->first();

                foreach ($entries as $entry) {
                    $voucheraccount = new AccountTransaction();
                    $voucheraccount->debit_ledger_id = $debit_ledger_id->id;
                    $voucheraccount->credit_ledger_id = $entry['credit_ledger_id'];
                    $voucheraccount->description = "This Discount is appiled After the Invoice";
                    $voucheraccount->amount = $entry['discount_amount'];
                    $voucheraccount->entry_id = $inv_entry;
                    $voucheraccount->save();
                    Custom::userby($voucheraccount, true, ($is_person) ? $id : null);
                }
            }
        }

        if ($cash_payment != null) {

            $voucher_type = $cash_payment['voucher_type'];

            if ($is_person == true) {

                $voucher_master = AccountVoucher::where('user_id', $id)->where('name', $voucher_type)->first();

                $previous_entry = AccountEntry::where('voucher_id', $voucher_master->id)->where('user_id', $id)
                    ->orderby('id', 'desc')
                    ->first();
            } else {
                $voucher_master = AccountVoucher::where('organization_id', $id)->where('name', $voucher_type)->first();

                $previous_entry = AccountEntry::where('voucher_id', $voucher_master->id)->where('organization_id', $id)
                    ->orderby('id', 'desc')
                    ->first();
            }

            // $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;

            $vou_restart_value = AccountVoucher::select('restart')->where('organization_id', $id)
                ->where('name', $voucher_type)
                ->first();

            $gen_no = ($vou_restart_value->restart == 1) ? $voucher_master->starting_value : ($previous_entry->gen_no + 1);

            $cash_entry = new AccountEntry();

            $cash_entry->status = $status;
            $cash_entry->voucher_no = self::generate_accounts_number($voucher_type, $gen_no, $is_person, ($is_person) ? $id : null);
            $cash_entry->gen_no = $gen_no;

            if ($cash_payment['payment_mode'] != null) {
                $cash_entry->payment_mode_id = $cash_payment['payment_mode'];
            }
            if ($is_person == true) {
                $cash_entry->user_id = $id;
            } else {
                $cash_entry->organization_id = $id;
            }
            $cash_entry->voucher_id = $voucher_master->id;

            Custom::userby($cash_entry, true, ($is_person) ? $id : null);

            $cash_entry->reference_voucher_id = $cash_payment['reference_voucher_id'];
            $cash_entry->reference_transaction_id = $cash_payment['reference_voucher_id'];
            $cash_entry->date = $date;
            $cash_entry->save();

            if ($cash_entry) {
                $payment = new AccountTransaction();
                $payment->debit_ledger_id = $cash_payment['debit_ledger_id'];
                $payment->credit_ledger_id = $cash_payment['credit_ledger_id'];
                $payment->description = "";
                $payment->amount = $cash_payment['amount'];
                $payment->entry_id = $cash_entry->id;
                $payment->save();

                Custom::userby($payment, true, ($is_person) ? $id : null);
            }
        }

        if ($accountentryid || $cash_entry) {

            DB::table('account_vouchers')->where('organization_id', $id)
                ->where('name', $voucher_type)
                ->update([
                'restart' => 0,
                'last_restarted' => Carbon::now()
            ]);
        }

        // ONLY for jobcard advance payment
        if ($voucher_type == 'wms_receipt' && $accountentries->grn_no != null) {
            
            Log::info("custom->add_entry : job card advance payment IN" );
            
            $account_transaction = AccountTransaction::where('entry_id', $accountentries->id)->first();

            $transactionModel = Transaction::where('id', $accountentries->grn_no)->first();
            Log::info("custom->add_entry : job card advance payment transaction model" . json_encode($transactionModel));
            
            $jobCardModel = $transactionModel->originatedFrom;
            //->where('transaction_type_id', $jobcard_transaction->transaction_type_id)->first();

            Log::info("custom->add_entry : job card advance payment jobCardModel" . json_encode($jobCardModel));
            
            $jobCardDetailModel = JobCardDetail::where('job_card_id', $jobCardModel->id)->first();

            $receipt_amount = $account_transaction->amount;
            $jobcard_amount = $transactionModel->total;
            $advance = $jobCardDetailModel->advance_amount;

            $advance_amount = ($advance + $receipt_amount);
            $jobCardDetailModel->advance_amount = $advance_amount;
            $jobCardDetailModel->save();

            $balance = ($jobcard_amount - $receipt_amount);
            $transactionModel->total = $balance;
            $transactionModel->save();
            Log::info("custom->add_entry : job card advance payment OUT" );
        }

        Log::info("custom->add_entry : Line No 4348" . json_encode($accountentryid));

        return $accountentryid;
    }

    public static function subscriptions($type, $term_period, $total_days, $payment_mode, $expire_date, $address, $package_id = null, $plan_id = null, $records, $subscription_type = null, $term = null)
    {
        $organization_id = Session::get('organization_id');
        $organization = Organization::findOrfail($organization_id);

        $price_report = [];

        $addon_array = [];

        $full_price = 0.00;

        $current_subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)
            ->whereNotNull('subscription_id')
            ->first();

        $addon_id = Addon::where('name', 'records')->first()->id;

        $addon = DB::table('addon_organization')->where('addon_id', $addon_id)
            ->where('organization_id', $organization_id)
            ->first()->value;

        $record_id = (Record::where('size', $addon)->first() != null) ? Record::where('size', $addon)->first()->id : 0;

        if ($current_subscription != "") {
            $current_balance = self::current_balance($current_subscription->subscription_id, $current_subscription->plan_id, $current_subscription->expire_on, $current_subscription->package_id, $record_id);
        } else {
            $current_balance = (object) array(
                'ledger_balance' => '0.00',
                'package_balance' => '0.00',
                'balance' => '0.00'
            );
        }

        $subscription_type_id = SubscriptionType::where('name', $subscription_type)->first()->id;

        $discount = 0.00;

        if ($subscription_type == "package") {

            $packages = OrganizationPackage::select('packages.id AS id', 'packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name', 'package_plan.price', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'), 'organization_packages.id AS package_id', DB::raw('GROUP_CONCAT(DISTINCT(modules.display_name)) AS modules'));
            $packages->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id');
            $packages->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id');
            $packages->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id');
            $packages->leftjoin('package_modules', 'package_modules.package_id', '=', 'packages.id');
            $packages->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id');
            $packages->leftjoin('package_plan', 'package_plan.package_id', '=', 'packages.id');
            $packages->where('organization_packages.organization_id', $organization_id);
            $packages->where('package_plan.plan_id', $plan_id);

            if ($current_subscription != "") {
                $packages->where('organization_packages.status', 1);
            }

            if ($package_id != null) {
                $packages->where('packages.id', $package_id);
            }

            $package = $packages->first();

            $discount = TermPeriod::findOrFail($term)->discount;

            $subtotal = ($package->price * $term_period);

            $total = $subtotal - ($subtotal * ($discount / 100));

            $full_price += ($total - $current_balance->package_balance);

            $price_report[] = array(
                'id' => $package->id,
                'name' => $package->package . " (" . $package->modules . ")",
                'price' => $package->price,
                'discount' => $discount,
                'subtotal' => $subtotal,
                'total' => $total,
                'previous_balance' => $current_balance->package_balance,
                'final_price' => ($total - $current_balance->package_balance)
            );
        }

        if (count($records) > 0) {
            foreach ($records as $key => $value) {

                $record = Record::findOrFail($value);

                if ($key == "record") {

                    if ($term_period == 3 || $term_period == 6 || $term_period == 12) {
                        $subtotal = Custom::two_decimal($record->price * $term_period);
                    } else {
                        $subtotal = Custom::two_decimal(Custom::two_decimal($record->price / 30) * $total_days);
                    }

                    $total = $subtotal;

                    $full_price += ($total - $current_balance->ledger_balance);

                    $addon_id = Addon::where('name', 'records')->first()->id;

                    $price_report[] = array(
                        'id' => $record->id,
                        'name' => $record->display_name,
                        'price' => $record->price,
                        'discount' => '0.00',
                        'subtotal' => $subtotal,
                        'total' => $total,
                        'previous_balance' => $current_balance->ledger_balance,
                        'final_price' => ($total - $current_balance->ledger_balance)
                    );
                }

                if ($key == "sms") {

                    $subtotal = $record->price;

                    $total = $subtotal;

                    $full_price += $total;

                    $addon_id = Addon::where('name', 'sms')->first()->id;

                    $price_report[] = array(
                        'id' => $record->id,
                        'name' => $record->display_name,
                        'price' => $record->price,
                        'discount' => '0.00',
                        'subtotal' => $subtotal,
                        'total' => $total,
                        'previous_balance' => "0.00",
                        'final_price' => $total
                    );
                }

                $addon_array[] = [
                    'organization_id' => $organization_id,
                    'addon_id' => $addon_id,
                    'value' => $record->size
                ];
            }
        }

        $tax_group = TaxGroup::where('name', "18.0% GST")->where('organization_id', $organization_id)->first();

        $tax_amount = 0;
        $tax_array = [];
        if ($tax_group != null) {

            $taxgroups = DB::table('group_tax')->where('group_id', $tax_group->id)->get();

            foreach ($taxgroups as $taxgroup) {

                $tax_value = Tax::where('id', $taxgroup->tax_id)->first();

                if ($tax_value->is_percent == 1) {
                    $tax = ($tax_value->value / 100) * $full_price;
                    $tax_amount += Custom::two_decimal($tax);
                } else if ($tax_value->is_percent == 0) {
                    $tax = $tax_value->value;
                    $tax_amount += Custom::two_decimal($tax);
                }

                $tax_array[] = [
                    "id" => $tax_value->id,
                    "name" => $tax_value->display_name,
                    "value" => $tax_value->value,
                    "is_percent" => $tax_value->is_percent,
                    "amount" => Custom::two_decimal($tax)
                ];

                // ACCOUNTS CAN BE INTEGRATED HERE
            }
        }

        $full_price += Custom::two_decimal($tax_amount);

        // ACCOUNTS CAN BE INTEGRATED HERE

        if ($type == "plan-change") {
            if ($full_price < 0) {
                abort(404); // Here create a page to say "You cannot downgrade your account. If you want to downgrade contact support team."
            }
        }

        $sub_id = Subscription::select('id')->orderby('id', 'desc')->first();
        $subid = 1;

        if ($sub_id != null) {
            $subid = $sub_id->id + 1;
        }

        $subscription = new Subscription();
        $subscription->organization_id = $organization_id;
        $subscription->subscription_type_id = $subscription_type_id;
        $subscription->tax_amount = Custom::two_decimal($tax_amount);
        $subscription->total_price = Custom::two_decimal($full_price);
        $subscription->price_report = json_encode($price_report);
        $subscription->tax_report = json_encode($tax_array);
        $subscription->transaction_id = self::transaction_id(16);
        $subscription->order_id = Carbon::now()->format('ym') . str_pad($subid, '6', '0', STR_PAD_LEFT);
        $subscription->added_on = Carbon::now()->format('Y-m-d H:i:s');
        $subscription->term_period_id = $term;
        $subscription->expire_on = $expire_date;
        $subscription->payment_mode_id = $payment_mode;
        $subscription->save();

        if ($subscription->id) {

            $billing_address = new BillingAddress();
            $billing_address->name = $address['name'];
            $billing_address->door = $address['door'];
            $billing_address->street = $address['street'];
            $billing_address->area = $address['area'];
            $billing_address->city = $address['city'];
            $billing_address->state = $address['state'];
            $billing_address->pin = $address['pin'];
            $billing_address->landmark = $address['landmark'];
            $billing_address->mobile_no = $address['mobile_no'];
            $billing_address->phone = $address['phone'];
            $billing_address->email_address = $address['email_address'];
            $billing_address->subscription_id = $subscription->id;
            $billing_address->save();

            Session::put('last_subscription_id', $subscription->id);

            if (! empty($package)) {
                Session::put('package_id', $package->id);
            }

            $pack_exists = OrganizationPackage::where('organization_id', $organization_id)->whereNotNull('subscription_id')->first();

            if ($subscription_type == "package") {
                if ($pack_exists == null) {

                    $organization_package = new OrganizationPackage();
                    $organization_package->package_id = $package_id;
                    $organization_package->plan_id = $plan_id;
                    $organization_package->organization_id = $organization_id;
                    $organization_package->added_on = $subscription->added_on;
                    $organization_package->expire_on = $subscription->expire_on;
                    $organization_package->subscription_id = $subscription->id;
                    $organization_package->save();

                    $organization->is_active = 1;
                    $organization->save();
                } else {
                    $organization_package = OrganizationPackage::where('organization_id', $organization_id)->whereNotNull('subscription_id')->first();

                    $expire_on = $subscription->expire_on;

                    $organization_package->package_id = $package_id;
                    $organization_package->plan_id = $plan_id;
                    $organization_package->added_on = $subscription->added_on;
                    $organization_package->expire_on = $expire_on;
                    $organization_package->status = 0;
                    $organization_package->subscription_id = $subscription->id;
                    $organization_package->save();
                }
            }
        }

        Session::put('addons', $addon_array);

        return true;
    }

    public static function current_balance($id, $plan, $expire_on, $package_id, $record_id)
    {
        $subscription = Subscription::findOrFail($id);
        $package = Package::findOrFail($package_id);
        $record = Record::findOrFail($record_id);
        $plan_id = SubscriptionPlan::findOrFail($plan);

        $time_difference = self::time_difference(Carbon::parse($expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

        $remaining_days = ($time_difference > 0) ? $time_difference : 0;

        if ($remaining_days == 90) {
            $oneday_ledger = Custom::two_decimal($record->price * 3);
            $oneday_package = DB::table('package_plan')->select('price')
                ->where('package_id', $package->id)
                ->where('plan_id', $plan_id->id)
                ->first()->price * 3;

            $ledger_balance = $oneday_ledger;
            $package_balance = $oneday_package;
        } else if ($remaining_days == 180) {
            $oneday_ledger = Custom::two_decimal($record->price * 6);
            $oneday_package = DB::table('package_plan')->select('price')
                ->where('package_id', $package->id)
                ->where('plan_id', $plan_id->id)
                ->first()->price * 6;

            $ledger_balance = $oneday_ledger;
            $package_balance = $oneday_package;
        } else if ($remaining_days == 365) {
            $oneday_ledger = Custom::two_decimal($record->price * 12);
            $oneday_package = DB::table('package_plan')->select('price')
                ->where('package_id', $package->id)
                ->where('plan_id', $plan_id->id)
                ->first()->price * 12;

            $ledger_balance = $oneday_ledger;
            $package_balance = $oneday_package;
        } else {
            $oneday_ledger = Custom::two_decimal($record->price / 30);
            $oneday_package = DB::table('package_plan')->select('price')
                ->where('package_id', $package->id)
                ->where('plan_id', $plan_id->id)
                ->first()->price / 30;

            $ledger_balance = number_format((float) ($oneday_ledger * $remaining_days), 2, '.', '');

            $package_balance = number_format((float) ($oneday_package * $remaining_days), 2, '.', '');
        }

        $balance = $package_balance + $ledger_balance;

        $result = (object) array(
            'ledger_balance' => $ledger_balance,
            'package_balance' => $package_balance,
            'balance' => $balance
        );

        return $result;
    }

    public static function get_accounts_number($voucher_name, $is_person)
    {
        if ($is_person == true) {
            $id = Auth::user()->id;
        } else {
            $id = Session::get('organization_id');
        }

        $voucher_masters = AccountVoucher::select('account_vouchers.id', 'account_vouchers.format_id', 'account_vouchers.code', 'account_voucher_types.name As voucher_name');
        $voucher_masters->leftJoin('account_voucher_types', 'account_voucher_types.id', '=', 'account_vouchers.voucher_type_id');

        if ($is_person == true) {
            $voucher_masters->where('user_id', $id);
        } else {
            $voucher_masters->where('organization_id', $id);
        }
        $voucher_masters->where('account_vouchers.name', $voucher_name);
        $voucher_master = $voucher_masters->first();

        $voucher_formats = AccountVoucherFormat::select('account_voucher_formats.id', 'account_voucher_separators.name', 'account_voucher_formats.icon');
        $voucher_formats->leftJoin('account_format_separator', 'account_format_separator.format_id', '=', 'account_voucher_formats.id');
        $voucher_formats->leftJoin('account_voucher_separators', 'account_format_separator.separator_id', '=', 'account_voucher_separators.id');
        $voucher_formats->leftJoin('account_vouchers', 'account_voucher_formats.id', '=', 'account_vouchers.format_id');
        if ($is_person == true) {
            $voucher_formats->where('account_voucher_formats.user_id', $id);
        } else {
            $voucher_formats->where('account_voucher_formats.organization_id', $id);
        }

        $voucher_formats->where('account_vouchers.format_id', $voucher_master->format_id);
        $voucher_formats->groupby('account_voucher_separators.id');
        $voucher_format = $voucher_formats->get();

        switch ($voucher_name) {
            case 'purchase_order':
                $entries = InventoryPurchaseOrder::select('gen_no');
                break;
            case 'purchase':
                $entries = InventoryPurchase::select('gen_no');
                break;
            case 'purchase_return':
                $entries = InventoryPurchaseReturn::select('gen_no');
                break;
            case 'goods_receipt_note':
                $entries = InventoryGoodsReceivedNote::select('gen_no');
                break;
            case 'internal_consumption':
                $entries = InventoryInternalConsumption::select('gen_no');
                break;
            case 'material_receipt':
                $entries = InventoryMaterialReceivedNote::select('gen_no');
                break;
            case 'adjustment':
                $entries = InventoryAdjustment::select('gen_no');
                break;
            case 'estimation':
                $entries = TradeEstimation::select('gen_no');
                break;
            case 'sale_order':
                $entries = TradeSalesOrder::select('gen_no');
                break;
            case 'sales':
                $entries = TradeSale::select('gen_no');
                break;
            case 'sale_return':
                $entries = TradeSalesReturn::select('gen_no');
                break;
            case 'delivery_note':
                $entries = TradeDeliveryNote::select('gen_no');
                break;
            default:
                $entries = AccountEntry::select('gen_no')->where('voucher_id', $voucher_master->id);
                break;
        }

        if ($is_person == true) {
            $entries->where('user_id', $id);
        } else {
            $entries->where('organization_id', $id);
        }
        $lastentry = $entries->orderby('id', 'desc')->first();

        return array(
            'voucher_master' => $voucher_master,
            'voucher_format' => $voucher_format,
            'lastentry' => ($lastentry != "") ? $lastentry->gen_no + 1 : 1
        );
    }

    public static function generate_accounts_number($voucher_name, $number, $is_person, $user = null, $api_org_id = false)
    {

        // dd($number);
        // For API 29/3/19
        if ($api_org_id) {
            $id = $api_org_id;
        } else {
            if ($is_person == true) {

                if ($user) {
                    $id = $user;
                } else {
                    $id = Auth::user()->id;
                }
            } else {
                $id = Session::get('organization_id');
            }
        }

        $years = AccountFinancialYear::select('voucher_year_format')->where('organization_id', $id)
            ->where('status', 1)
            ->first();
        // dd($years->voucher_year_format);
        $year = '';
        // $crnt_year = $now->year;
        if ($years->voucher_year_format == null) {
            // $now = Carbon::now()->format('Y');
            // dd("sdf");
            $year = Carbon::now()->format('Y');
        } else {

            $year = $years->voucher_year_format;
        }

        $voucher_masters = AccountVoucher::select('account_vouchers.*', 'account_voucher_types.name As voucher_name');
        $voucher_masters->leftJoin('account_voucher_types', 'account_voucher_types.id', '=', 'account_vouchers.voucher_type_id');

        if ($is_person == true) {
            $voucher_masters->where('user_id', $id);
        } else {
            $voucher_masters->where('organization_id', $id);
        }

        $voucher_masters->where('account_vouchers.name', $voucher_name);

        $voucher_master = $voucher_masters->first();
        // dd($voucher_master);
        $voucher_formats = AccountVoucherFormat::select('account_voucher_formats.id', 'account_voucher_separators.name', 'account_voucher_formats.icon', 'account_format_separator.value');
        $voucher_formats->leftJoin('account_format_separator', 'account_format_separator.format_id', '=', 'account_voucher_formats.id');
        $voucher_formats->leftJoin('account_voucher_separators', 'account_format_separator.separator_id', '=', 'account_voucher_separators.id');
        $voucher_formats->leftJoin('account_vouchers', 'account_voucher_formats.id', '=', 'account_vouchers.format_id');

        if ($is_person == true) {
            $voucher_formats->where('account_voucher_formats.user_id', $id);
        } else {
            $voucher_formats->where('account_voucher_formats.organization_id', $id);
        }

        $voucher_formats->where('account_vouchers.format_id', $voucher_master->format_id);
        $voucher_formats->groupby('account_voucher_separators.id');
        $voucher_formats->orderby('account_format_separator.order');
        $voucher_format = $voucher_formats->get();

        $voucher_no = array();
        $icon = '';

        foreach ($voucher_format as $format) {
            if ($format['name'] == "auto_number") {
                $voucher_no[] = str_pad($number, $format->value + 1, '0', STR_PAD_LEFT);
            } elseif ($format['name'] == "financial_year") {
                $voucher_no[] = $year;
            } elseif ($format['name'] == "voucher_code") {
                $voucher_no[] = $voucher_master->code;
            }

            $icon = $format->icon;
        }

        return implode($icon, $voucher_no);
    }

    public static function get_least_closest_date($dates, $current_date = null)
    {
        $list_price = null;
        $discount = null;
        $price = null;

        usort($dates, function ($item1, $item2) {

            return $item2['on_date'] <=> $item1['on_date'];
        });

        $mostRecent = 0;
        $price = 0.00;
        $now = ($current_date != null) ? strtotime($current_date) : time();

        foreach ($dates as $date) {
            $curDate = strtotime($date['on_date']);
            if ($curDate > $mostRecent && $curDate < $now) {
                $mostRecent = $curDate;
                $list_price = $date['list_price'];
                $discount = $date['discount'];
                $price = $date['sale_price'];
            }
        }
        if ($list_price == null && count($dates) > 0) {
            $list_price = $dates[0]['list_price'];
            $discount = $dates[0]['discount'];
            $price = $dates[0]['sale_price'];
        }
        return [
            "date" => date('Y-m-d', $mostRecent),
            "list_price" => self::two_decimal($list_price),
            "discount" => $discount,
            "price" => self::two_decimal($price)
        ];
    }

    public static function get_least_closest_stock_date($dates, $current_date = null)
    {

        // dd($dates);
        $purchase_price = null;
        $sale_price = null;
        $in_stock = null;
        $quantity = null;

        // $dates=[$dates];

        usort($dates, function ($item1, $item2) {

            if ($item1['voucher_type'] == 'Goods Receipt Note') {
                return $item2['date'] <=> $item1['date'];
            }
        });

        // dd($dates);
        $mostRecent = 0;
        $sale_price = 0.00;
        $now = ($current_date != null) ? strtotime($current_date) : time();

        foreach ($dates as $date) {

            if ($date['voucher_type'] == 'Goods Receipt Note') {

                $curDate = strtotime($date['date']);

                if ($curDate > $mostRecent && $curDate < $now) {
                    $mostRecent = $curDate;
                    $purchase_price = $date['purchase_price'];
                    $in_stock = $date['in_stock'];
                    $quantity = $date['quantity'];
                    $sale_price = $date['sale_price'];
                }
            }
        }
        /*
         * if($purchase_price == null && count($dates) > 0) {
         *
         * $purchase_price = $dates[0]['purchase_price'];
         * $in_stock = $dates[0]['in_stock'];
         * $quantity = $dates[0]['quantity'];
         * $sale_price = $dates[0]['sale_price'];
         * }
         */
        return [
            "date" => date('Y-m-d', $mostRecent),
            "purchase_price" => self::two_decimal($purchase_price),
            "sale_price" => self::two_decimal($sale_price),
            'in_stock' => $in_stock,
            'quantity' => $quantity
        ];
    }

    public static function two_decimal($number)
    {
        return number_format((float) $number, 2, '.', '');
    }

    public static function amountInIndianWords(float $num)
    {
        if (strlen(floor($num)) < 10) {
            $number = $num;
            $decimal = round($number - ($no = floor($number)), 2) * 100;
        } else {
            $number = substr((string) $num, - 12);
            $decimal = round($number - ($no = floor($number)), 2) * 100;

            echo str_replace("rupees", "and", self::amountInIndianWords(substr($num, 0, strlen(floor($num)) - 9) * 100));
        }

        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        );
        $digits = array(
            '',
            'hundred',
            'thousand',
            'lakh',
            'crore'
        );
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;

                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else
                $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? " and " . (isset($words[$decimal]) ? $words[$decimal] . ' paise' : ($words[(floor($decimal / 10) * 10)] . " " . $words[$decimal % 10]) . ' paise') : '';

        return ($Rupees ? $Rupees . 'rupees ' : '') . $paise;
    }

    public static function generate_image_thumbnail($source_image_path, $thumbnail_image_path, $max_size_height = "200", $max_size_width = "200")
    {
        list ($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }
        if ($source_gd_image === false) {
            return false;
        }
        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = $max_size_width / $max_size_height;
        if ($source_image_width <= $max_size_width && $source_image_height <= $max_size_height) {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) ($max_size_height * $source_aspect_ratio);
            $thumbnail_image_height = $max_size_height;
        } else {
            $thumbnail_image_width = $max_size_width;
            $thumbnail_image_height = (int) ($max_size_width / $source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

        $img_disp = imagecreatetruecolor($max_size_width, $max_size_width);
        $backcolor = imagecolorallocate($img_disp, 0, 0, 0);
        imagefill($img_disp, 0, 0, $backcolor);

        imagecopy($img_disp, $thumbnail_gd_image, (imagesx($img_disp) / 2) - (imagesx($thumbnail_gd_image) / 2), (imagesy($img_disp) / 2) - (imagesy($thumbnail_gd_image) / 2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));

        imagejpeg($img_disp, $thumbnail_image_path, 90);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        imagedestroy($img_disp);
        return true;
    }

    public static function GUID()
    {
        return strtoupper(bin2hex(openssl_random_pseudo_bytes(6)));
    }

    public static function GetOrganizationByModules($Auth_person_id, $module_name)
    {
        $organization = Organization::select('organizations.name', 'organizations.id');
        $organization->leftJoin('organization_person', 'organizations.id', '=', 'organization_person.organization_id');
        $organization->leftJoin('persons', 'persons.id', '=', 'organization_person.person_id');
        $organization->leftJoin('module_organization', 'module_organization.organization_id', '=', 'organizations.id');
        $organization->leftJoin('modules', 'module_organization.module_id', '=', 'modules.id');
        $organization->where('persons.id', Auth::user()->person_id);
        $organization->where('modules.name', "wfm");
        return $organization;
    }

    public static function attachments($inputs, $file, $id, $attachments_type, $attachment_prefix)
    {
        $files_array = $file;

        // dd($files_array);
        // Task Inputs array
        // dd($inputs);
        $task_id = $id;
        $org_id = $inputs['organization_id'];
        $project_id = $inputs['project_id'];
        $public_path = comment_attachment_path($org_id, $project_id);

        if (! file_exists($public_path)) {
            mkdir(($public_path), 0777, true);
        }
        $dt = new DateTime();
        $return_data = [];
        $attachment_path = [];
        // dd($files_array);

        if (is_array($files_array)) {
            foreach ($files_array as $file) {
                $name = $attachment_prefix . "_" . $task_id . "_" . $dt->format('Y-m-d-H-i-s') . "_" . $file->getClientOriginalName();

                $file->move($public_path, $name);

                $data['attach_type'] = $attachments_type;
                $data['attach_id'] = $task_id;

                $data['upload_file'] = $name;
                $data['file_original_name'] = $file->getClientOriginalName();
                $data['project_id'] = $project_id;
                $data['organization_id'] = $org_id;

                $data['file_suffix'] = "";

                $data['created_by'] = Auth::user()->person_id;
                $data["created_at"] = \Carbon\Carbon::now(); // \Datetime()
                $data["updated_at"] = \Carbon\Carbon::now();

                $attachment_id = custom::Save($tb_name = "wfm_attachments", $data);
                $return_data[$attachment_id] = $data['file_original_name'];
                $attachment_path[$attachment_id] = json_encode("org_" . $data['organization_id'] . '/pro_' . $data['project_id'] . '/' . $name);

                // $request->file('file')->move($public_path, $name);
                // return
            }
        }

        return array(
            'uploaded_files' => $return_data,
            'attachment_path' => $attachment_path
        );
        //
    }

    public static function Save($tb_name, $data)
    {
        $query_insert = DB::table($tb_name)->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public static function getLastGenNumber($transaction_typeid, $organization_id, $transaction_id = false)
    {
        Log::info("Custom->getLastGenNumber :- Inside " . $transaction_typeid . ' -- ' . $organization_id);
        $query = Transaction::where('transaction_type_id', $transaction_typeid)->where('organization_id', $organization_id)->orderby('id', 'desc');

        if ($transaction_id) {
            $query = $query->where('id', '!=', $transaction_id);
        }

        $previous_entry_exist = clone $query; // clone the query.
        $last2_entry_exist = clone $query; // clone the query.
        Log::info("Custom->getLastGenNumber :- LINE NO 5075");
        $previous_entry = $query->first();
        Log::info("Custom->getLastGenNumber :- previous_entry " . json_encode($previous_entry));

        if ($previous_entry_exist->exists()) {
            if ($previous_entry->gen_no != 0 && $previous_entry->gen_no != null) {
                $NewGenNum = $previous_entry->gen_no + 1;
                Log::info("Custom->getLastGenNumber :- previous_entry " . json_encode($NewGenNum));

                // (duplicate)
            } else {

                $Last_Second_rec = $last2_entry_exist->whereNotNull('gen_no')
                    ->where('gen_no', '!=', 0)
                    ->where('transaction_type_id', $transaction_typeid)
                    ->first()->gen_no;
                Log::info("Custom->getLastGenNumber :- previous_entry " . json_encode($Last_Second_rec->toSql()));
                $NewGenNum = $Last_Second_rec + 1;
            }
            Log::info("Custom->getLastGenNumber :- LINE NO 5075" . json_encode($NewGenNum));
            Log::info("Custom->getLastGenNumber :- return - " . $NewGenNum);
            return $NewGenNum;
        }
        Log::info("Custom->getLastGenNumber :- return - false ");
        return false;
    }

    public static function getLastJobCardGenNumber($transaction_typeid, $organization_id, $transaction_id = false)
    {
        $query = JobCard::select(DB::raw('max(gen_no) AS max_gen_no'))->where('organization_id', $organization_id)
            ->whereNull('job_cards.deleted_at')
            ->first();

        $max_gen_no = $query->max_gen_no;

        if ($max_gen_no == null) {
            return 1;
        } else {
            return $max_gen_no + 1;
        }
    }
    //hence defalut the get the India states
    public static function getStateByCountryId($id = false ,$isPluck = false){

        if(!$id){
            $id = 101; //india
        }
        $data = State::where('country_id',$id)->get();
        //pluck('name','id');
        if($isPluck){
            $data = $data->pluck('name', 'id');
        }
        return $data;
    }

    public static function getStateById($id, $isPluck = false ){

        $query = State::where('id',$id);
        if($isPluck){
            return $query->pluck('name','id');
        }else{
            return $query->first();
        }
        //
    }
    public static function getCityByStateId($id  ){

        return City::where('state_id',$id)->get();
        //pluck('name','id');
    }

    public static function getCityById($id,$isPluck = false){

        $query = City::where('id',$id);
        if($isPluck){
            return $query->pluck('name','id');
        }else{
            return $query->first();
        }
        
        //pluck('name','id');
    }

    public static function addressTypePerson()
    {
        return PersonAddressType::where('name', 'residential')->first();
    }

    public  static function addressTypeBusiness()
    {
        return BusinessAddressType::where('name', 'business')->first();
    }

    public  static function organization_id($id=false)
    {
        $organization_id;
        if($id){
            $organization_id = $id;
        }
        return $organization_id;
    }
}