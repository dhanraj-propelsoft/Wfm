<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessCommunicationAddress;
use App\BusinessProfessionalism;
use App\BusinessAddressType;
use App\AccountLedger;
use App\AccountPersonType;
use App\AccountLedgerType;
use App\AccountLedgerCreditInfo;
use App\PersonCommunicationAddress;
use App\AccountGroup;
use App\Jobs\SendOtpEmail;
use App\BusinessNature;
use App\PersonAddressType;
use App\PeopleAddress;
use App\Jobs\SendSms;
use App\Notification\Service\SmsNotificationService;
use App\Organization;
use App\Business;
use App\People;
use App\Person;
use App\User;
use App\State;
use App\City;
use App\Custom;
use Session;
use Mail;
use DB;

class SearchBusinessController extends Controller
{

    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('search_business');
    }

    public function propel_search()
    {
        return view('search_propel_business');
    }

    public function check_business(Request $request)
    {
        $business = Business::where('business_name', $request->business_name)->first();

        if (! empty($business->business_name)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function check_business_mobile_number(Request $request)
    {
        // dd($request->all());
        $mobile = BusinessCommunicationAddress::where('mobile_no', $request->number)->where('status', '1')->first();
        if (! empty($mobile->id)) {
            $status = "false";
            return response()->json($status);
        } else {
            $status = "true";
            return response()->json($status);
        }
    }

    public function check_business_email_address(Request $request)
    {
        // dd($request->all());
        $mobile = BusinessCommunicationAddress::where('email_address', $request->email_address)->where('status', '1')->first();
        if (! empty($mobile->id)) {
            $status = "false";
            return response()->json($status);
        } else {
            $status = "true";
            return response()->json($status);
        }
    }

    public function validate_business_mobile_number(Request $request)
    {
        $organization_id = session::get('organization_id');
        $mob = $request->input('number');

        $check = People::leftjoin('persons', 'persons.id', '=', 'people.person_id');
        $check->leftjoin('people_person_types', 'people_person_types.people_id', '=', 'people.id');
        $check->leftjoin('person_communication_addresses', 'person_communication_addresses.person_id', '=', 'persons.id');
        $check->leftjoin('businesses', 'businesses.id', '=', 'people.business_id');
        $check->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id');
        $check->where(function ($query) use ($mob) {
            $query->where('business_communication_addresses.mobile_no', $mob)
                ->orWhere('person_communication_addresses.mobile_no', $mob);
        });
        if ($request->type_name == 'Vendor') {
            $check->where('people_person_types.person_type_id', '=', 3);
        }
        if ($request->input('type_name') == "customer") {
            $check->where('people_person_types.person_type_id', '=', 2);
        }
        if ($request->input('type_name') == "employee") {
            $check->where('people_person_types.person_type_id', '=', 1);
        }

        $check->where('people.organization_id', $organization_id);
        $check_mob = $check->exists();
        // dd($check_mob);
        /*
         * $cln = clone $check;
         * $data = $cln->first();
         * dd($data);
         */
        $get_data = People::select('people.*', 'cities.name AS city_name', 'cities.id AS city_id', 'cities.state_id')->leftjoin('persons', 'persons.id', '=', 'people.person_id');
        $get_data->leftjoin('people_person_types', 'people_person_types.people_id', '=', 'people.id');
        $get_data->leftjoin('person_communication_addresses', 'person_communication_addresses.person_id', '=', 'persons.id');
        $get_data->leftjoin('businesses', 'businesses.id', '=', 'people.business_id');
        $get_data->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id');

        $get_data->leftJoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id');

        $get_data->where(function ($query) use ($mob) {
            $query->where('business_communication_addresses.mobile_no', $mob)
                ->orWhere('person_communication_addresses.mobile_no', $mob);
        });
        if ($request->type_name == 'Vendor') {
            $get_data->where('people_person_types.person_type_id', '=', 3);
        }
        if ($request->input('type_name') == "customer") {
            $get_data->where('people_person_types.person_type_id', '=', 2);
        }
        if ($request->input('type_name') == "employee") {
            $get_data->where('people_person_types.person_type_id', '=', 1);
        }

        $get_data->where('people.organization_id', $organization_id);

        $check_mobile = $get_data->first();
        // dd($check_mobile);
        $state_id = '';
        $city = '';
        $datas = '';
        $city_name = '';
        $state_name = '';

        if ($check_mob == false) {
            $data = People::select('*', 'person_communication_addresses.city_id as person_city_id', 'business_communication_addresses.city_id as business_city_id', 'cities.name as city_name')->leftjoin('persons', 'persons.id', '=', 'people.person_id');
            $data->leftjoin('person_communication_addresses', 'person_communication_addresses.person_id', '=', 'persons.id');
            $data->leftjoin('businesses', 'businesses.id', '=', 'people.business_id');
            $data->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id');
            $data->leftJoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id');

            /*
             * $data->where('business_communication_addresses.mobile_no',$mob);
             * $data->orWhere('person_communication_addresses.mobile_no',$mob);
             */
            $data->where(function ($query) use ($mob) {
                $query->where('business_communication_addresses.mobile_no', $mob)
                    ->orWhere('person_communication_addresses.mobile_no', $mob);
            });

            $datas = $data->first();
            // dd($datas);
            if ($datas) {
                if ($datas->person_city_id) {
                    $city = $datas->person_city_id;
                } else {
                    $city = $datas->business_city_id;
                }
            }

            if ($city) {
                $state_id = City::where('id', $city)->first()->state_id;
                $city_name = City::where('id', $city)->first()->name;
            }
            // dd($datas);
            if ($state_id) {
                $state_name = State::where('id', $state_id)->first()->name;
            }
        }
        // dd($datas);

        return response()->json([
            'check' => $check_mob,
            'data' => $datas,
            'check_mobile' => $check_mobile,
            'state_id' => $state_id,
            'city_id' => $city,
            'city_name' => $city_name,
            'state_name' => $state_name
        ]);
    }

    public function check_business_gst_number(Request $request)
    {
        $inputs = $request->all();
        $gst = Business::where('gst', $inputs['number'])->where('status', '1')->first();
        // dd($gst);
        if (! empty($gst->id)) {
            $status = "false";
            return response()->json($status);
        } else {
            $status = "true";
            return response()->json($status);
        }
    }

    public function search_existence(Request $request)
    {
        $input = $request->all();

        // dd($input);
        /*
         * $query = Business::select('businesses.id', 'organizations.id AS organization_id', 'organizations.id AS business_id', 'businesses.business_name', 'businesses.bcrm_code', 'business_communication_addresses.city_id', 'business_communication_addresses.phone', 'business_communication_addresses.web_address', 'business_communication_addresses.email_address', 'business_communication_addresses.mobile_no','businesses.gst');
         *
         * $query->leftJoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id');
         * $query->leftJoin('business_field_values', 'business_field_values.businesses_id', '=', 'businesses.id');
         * $query->leftJoin('organizations', 'organizations.business_id', '=', 'businesses.id');
         *
         * foreach($input as $key => $value)
         * {
         * if($value != "" && $key != "_token") {
         *
         * if($key == "business_name") {
         * $query->where("business_name", 'LIKE', "%".$value."%");
         * }
         *
         * else if($key == "mobile_no") {
         * $query->where("mobile_no", 'LIKE', "%".$value."%");
         * }
         * else if($key == "gst") {
         * $query->where("gst", 'LIKE', "%".$value."%");
         * }
         *
         * else {
         * $query->where("$key", 'like', "%".$value."%");
         * }
         * }
         * }
         *
         * $query->groupby('businesses.id');
         *
         * $results = $query->get();
         */

        $query = Business::select('businesses.id', 'organizations.id AS organization_id', 'organizations.id AS business_id', 'businesses.business_name', 'businesses.bcrm_code', 'business_communication_addresses.city_id', 'business_communication_addresses.phone', 'business_communication_addresses.web_address', 'business_communication_addresses.email_address', 'business_communication_addresses.mobile_no', 'businesses.gst');

        $query->leftJoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id');
        $query->leftJoin('business_field_values', 'business_field_values.businesses_id', '=', 'businesses.id');
        $query->leftJoin('organizations', 'organizations.business_id', '=', 'businesses.id');
        $query->groupby('businesses.id');

        $mob = clone $query;
        $gst = clone $query;

        if ($mob->where('mobile_no', $input['mobile_no'])->exists()) {
            $results = $mob->where('mobile_no', $input['mobile_no'])->get();
        } else {

            $results = $gst->where('gst', $input['gst'])->get();
        }
        return view('search_business', compact('results', 'request'));
    }

    public function simple_business_search(Request $request)
    {
        $query = Business::select('id', 'business_name', 'alias', 'bcrm_code');

        if ($request->input('crm_id') != null) {
            $query->where('bcrm_code', $request->input('bcrm_id'));
        }
        if ($request->input('businessname') != null) {
            $query->where('business_name', 'LIKE', "%" . $request->input('businessname') . "%");
        }

        $business = $query->first();

        return response()->json($business);
    }

    public function add_organization(Request $request)
    {
        $business = Business::findOrFail($request->input('id'));
        $business_address_type = BusinessAddressType::where('name', 'business')->first();
        $business_communication_addresses = BusinessCommunicationAddress::where('business_id', $business->id)->where('address_type', $business_address_type->id)->first();

        $check_person = People::where('business_id', $business->id)->where('organization_id', Session::get('organization_id'))->first();

        if ($check_person == null) {
            $people = new People();
            $people->business_id = $business->id;
            $people->company = $business->alias;
            $people->display_name = $business->alias;
            $people->mobile_no = $business_communication_addresses->mobile_no;
            $people->email_address = $business_communication_addresses->email_address;
            $people->organization_id = Session::get('organization_id');
            $people->user_type = 1;
            $people->save();

            if ($people->id) {
                $people_address = new PeopleAddress();
                $people_address->people_id = $people->id;
                $people_address->address = $business_communication_addresses->address;
                $people_address->city_id = $business_communication_addresses->city_id;
                $people_address->pin = $business_communication_addresses->pin;
                $people_address->landmark = $business_communication_addresses->landmark;
                $people_address->google = $business_communication_addresses->google;
                $people_address->save();
            }
            $people_id = $people->id;
            $business_id = $people->business_id;
        } else {
            $people_id = $check_person->id;
            $business_id = $check_person->business_id;
        }

        if ($request->input('person_type') != null) {

            $person_type_id = AccountPersonType::where('name', $request->input('person_type'))->first()->id;

            $person_type = DB::table('people_person_types')->where('people_id', $people_id)
                ->where('person_type_id', $person_type_id)
                ->first();

            if ($person_type == null) {
                DB::table('people_person_types')->insert([
                    'people_id' => $people_id,
                    'person_type_id' => $person_type_id
                ]);
            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'Contact' . config('constants.flash.added'),
            'data' => [
                'id' => $business_id
            ]
        ]);
    }

    public function advanced_business_search(Request $request)
    {
        $input = $request->all();

        $query = Business::select('businesses.id', 'businesses.bcrm_code', 'businesses.business_name', 'businesses.alias', 'businesses.gst', 'business_communication_addresses.email_address', 'business_communication_addresses.mobile_no', 'business_communication_addresses.phone', 'business_communication_addresses.web_address', 'businesses.pan', 'cities.name as city');
        $query->leftJoin('business_communication_addresses', 'businesses.id', '=', 'business_communication_addresses.business_id');
        $query->leftjoin('cities', 'cities.id', '=', 'business_communication_addresses.city_id');

        foreach ($input as $key => $value) {
            if ($value != "" && $key != "_token") {

                if ($key == "business_name") {
                    $query->where("business_name", 'LIKE', "%" . $value . "%");
                } else if ($key == "bcrm_code") {
                    $query->where("bcrm_code", 'LIKE', "%" . $value . "%");
                } else if ($key == "email") {
                    $query->where("email_address", 'LIKE', "%" . $value . "%");
                } 
                else if ($key == "mobile") {
                    $query->where("mobile_no", 'LIKE', "%" . $value . "%");
                } else if ($key == "phone_no") {
                    $query->where("phone", 'LIKE', "%" . $value . "%");
                } else if ($key == "gst") {
                    $query->where("gst", 'LIKE', "%" . $value . "%");
                } else if ($key == "pan_no") {
                    $query->where("pan", 'LIKE', "%" . $value . "%");
                } else if ($key == "web_address") {
                    $query->where("web_address", 'LIKE', "%" . $value . "%");
                } 
                else {
                    $query->where("$key", 'like', "%" . $value . "%");
                }
            }
        }

        $query->groupby('businesses.id');

        /*
         * if($request->input('business_name') != null) {
         * $query->where("businesses.business_name", '=', $request->input('business_name'));
         * }
         * if($request->input('bcrm_code') != null) {
         * $query->where("businesses.bcrm_code", '=', $request->input('bcrm_code'));
         * }
         *
         * if($request->input('email') != null) {
         * $query->where("business_communication_addresses.email_address", '=', $request->input('email'));
         * }
         *
         * if($request->input('mobile') != null) {
         * $query->where("business_communication_addresses.mobile_no", '=', $request->input('mobile'));
         * }
         *
         * if($request->input('pan_no') != null) {
         * $query->where("businesses.pan", '=', $request->input('pan_no'));
         * }
         *
         * if($request->input('gst') != null) {
         * $query->where("businesses.gst", '=', $request->input('gst'));
         * }
         *
         * if($request->input('phone_no') != null) {
         * $query->where("business_communication_addresses.phone", '=', $request->input('phone_no'));
         * }
         *
         * if($request->input('web_address') != null) {
         * $query->where("business_communication_addresses.web_address", '=', $request->input('web_address'));
         * }
         */

        $results = $query->get();

        return ($results) ? $results : json_encode([]);
    }

    public function register_propel_business_id(Request $request)
    {
        $business = Business::where('bcrm_code', $request->business_id)->first();
        $business->otp = Custom::otp(4);
        $business->save();

        $business_id = $business->id;

        $organization = Organization::where('business_id', $business->id)->first();
        $is_registered = false;

        $address_type = BusinessAddressType::where('name', 'business')->first();

        $address = BusinessCommunicationAddress::where('address_type', $address_type->id)->where('business_id', $business->id)->first();

        $business_mobile = $address->mobile_no;
        $business_email = $address->email_address;

        $mobile = str_pad(substr($business_mobile, - 3, 3), strlen($business_mobile), "X", STR_PAD_LEFT);

        $email_name = substr($business_email, 0, strrpos($business_email, '@'));

        $email = (strlen($email_name) > 0) ? str_pad(substr($email_name, - 2, 2), strlen($email_name), "X", STR_PAD_LEFT) . "@" . substr(strrchr($business_email, "@"), 1) : null;

        if ($organization != null) {
            $is_registered = true;
        } else {
            /*
             * $this->dispatch(new SendSms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $business_mobile, "$business->otp is your OTP to create your account on PROPEL ERP"));
             *
             * $this->dispatch(new SendOtpEmail(['name' => $business->name, 'otp' => $business->otp], $business_email));
             */

            $to_email = $business_email;
            $to_name = $business->name;
            $data = [
                'name' => $business->name,
                'otp' => $business->otp
            ];
            $subject = "Search Bussiness";
            $message = "$business->otp is your OTP to create your account on PROPEL ERP";
            $sms_notify_model = $this->SmsNotificationService->save($business_mobile, $subject, $to_name, $message, "", "OTP");

            // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$business_mobile , "$business->otp is your OTP to create your account on PROPEL ERP");

            /*
             * Mail::send('emails.otp_mail', $data, function ($message) use ($to_email, $to_name) {
             * $message->from('support@propelsoft.in', 'PropelERP');
             * $message->to($to_email, $to_name);
             * $message->subject("Propel OTP Code");
             * });
             */
        }

        return view('register_business', compact('is_registered', 'mobile', 'email', 'business_id'));
    }

    public function add_business(Request $request)
    {

        // dd($request->all());
        $business_mobile = $request->input('business_mobile');

        /*
         * $mobile_no = User::where('mobile',$business_mobile)->first();
         * if(!empty($mobile_no->id))
         * {
         * return response()->json(['status' => 0]);
         * }
         * else
         * {
         */
        $already_exits_data = Business::where('id', $request->id)->first();
        // dd($already_exits_data);
        if ($already_exits_data != null) {
            $check_person = People::where('business_id', $request->id)->where('organization_id', Session::get('organization_id'))->first();
            if ($check_person == null) {
                $people = new People();
                $people->business_id = $already_exits_data->id;
                $people->company = $request->input('business_name');
                $people->first_name = $request->input('business_name');
                $people->display_name = $request->input('business_name');
                $people->mobile_no = $request->input('business_mobile');
                $people->email_address = $request->input('business_email');
                $people->gst_no = $request->input('business_gst');
                $people->organization_id = Session::get('organization_id');
                $people->user_type = 1;
                $people->save();

                if ($people->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $people->id;
                    $people_address->address = $request->input('address');
                    $people_address->city_id = $request->input('business_city');
                    $people_address->pin = $request->input('pin');
                    $people_address->save();
                }
                $people_id = $people->id;
            } else {
                $people_id = $check_person->id;
                $people = $check_person;
            }
            if ($request->input('person_type') != null) {

                $person_type_id = AccountPersonType::where('name', $request->input('person_type'))->first()->id;

                $person_type = DB::table('people_person_types')->where('people_id', $people_id)
                    ->where('person_type_id', $person_type_id)
                    ->first();

                if ($person_type == null) {
                    DB::table('people_person_types')->insert([
                        'people_id' => $people_id,
                        'person_type_id' => $person_type_id
                    ]);
                }
            }
            return response()->json([
                'status' => 1,
                'message' => 'Contact' . config('constants.flash.added'),
                'data' => [
                    'id' => $already_exits_data->id,
                    'business_name' => $people->display_name,
                    'alias' => $people->display_name
                ]
            ]);
        } else {
            // dd($request->input('business_city'));
            $city = City::select('name')->where('id', $request->input('business_city'))
                ->first()->name;

            $bcrm_code = Custom::business_crm($city, $request->input('business_mobile'), $request->input('business_name'));

            $business = new Business();
            $business->bcrm_code = $bcrm_code;
            $business->business_name = $request->input('business_name');
            $business->alias = $request->input('business_name');
            $business->pan = $request->input('business_pan');
            $business->gst = $request->input('business_gst');
            $business->save();

            if ($business->id) {

                $address_type = BusinessAddressType::where('name', 'business')->first();

                $business_address = new BusinessCommunicationAddress();
                $business_address->business_id = $business->id;
                $business_address->address_type = $address_type->id;
                $business_address->address = $request->input('business_address');
                $business_address->city_id = $request->input('business_city');
                $business_address->mobile_no = $request->input('business_mobile');
                $business_address->mobile_no_prev = $request->input('business_mobile');
                $business_address->email_address = $request->input('business_email');
                $business_address->contact = $request->input('owner_name');

                $business_address->save();

                $people = new People();
                $people->business_id = $business->id;
                $business->company = $request->input('business_name');
                $people->first_name = $request->input('business_name');
                $people->display_name = $request->input('business_name');
                $people->mobile_no = $request->input('business_mobile');
                $people->email_address = $request->input('business_email');
                $people->gst_no = $request->input('business_gst');
                $people->organization_id = Session::get('organization_id');
                $people->user_type = 1;
                $people->save();

                if ($people->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $people->id;
                    $people_address->address = $request->input('business_address');
                    $people_address->city_id = $request->input('business_city');
                    $people_address->pin = $request->input('pin');
                    $people_address->save();
                }
            }

            if ($request->input('person_type') != null) {

                $person_type_id = AccountPersonType::where('name', $request->input('person_type'))->first()->id;

                $person_type = DB::table('people_person_types')->where('people_id', $people->id)
                    ->where('person_type_id', $person_type_id)
                    ->first();

                if ($person_type == null) {
                    DB::table('people_person_types')->insert([
                        'people_id' => $people->id,
                        'person_type_id' => $person_type_id
                    ]);
                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'Contact' . config('constants.flash.added'),
                'data' => [
                    'id' => $business->id,
                    'business_name' => $people->display_name,
                    'alias' => $people->display_name
                ]
            ]);
        }
        /* } */
    }

    public function add_businesses(Request $request)
    {

        // dd($request->all());
        $business_mobile = $request->input('business_mobile');

        $mobile_no = User::where('mobile', $business_mobile)->first();
        if (! empty($mobile_no->id)) {
            return response()->json([
                'status' => 0
            ]);
        } else {
            $city = City::select('name')->where('id', $request->input('business_city'))
                ->first()->name;

            $bcrm_code = Custom::business_crm($city, $request->input('business_mobile'), $request->input('business_name'));

            $business = new Business();
            $business->bcrm_code = $bcrm_code;
            $business->business_name = $request->input('business_name');
            $business->alias = $request->input('business_name');
            $business->pan = $request->input('business_pan');
            $business->gst = $request->input('business_gst');

            $business->save();

            if ($business->id) {

                $address_type = BusinessAddressType::where('name', 'business')->first();

                $business_address = new BusinessCommunicationAddress();
                $business_address->business_id = $business->id;
                $business_address->address_type = $address_type->id;
                $business_address->address = $request->input('business_address');
                $business_address->city_id = $request->input('business_city');
                $business_address->mobile_no = $request->input('business_mobile');
                $business_address->mobile_no_prev = $request->input('business_mobile');
                $business_address->email_address = $request->input('business_email');
                $business_address->save();

                $people = new People();
                $people->business_id = $business->id;
                $people->display_name = $request->input('business_name');
                $people->mobile_no = $request->input('business_mobile');
                $people->organization_id = Session::get('organization_id');
                $people->user_type = 1;
                $people->save();

                if ($people->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $people->id;
                    $people_address->address = $request->input('business_address');
                    $people_address->city_id = $request->input('business_city');
                    $people_address->pin = $request->input('pin');
                    $people_address->save();
                }
            }

            if ($request->input('person_type') != null) {

                $person_type_id = AccountPersonType::where('name', $request->input('person_type'))->first()->id;

                $person_type = DB::table('people_person_types')->where('people_id', $people->id)
                    ->where('person_type_id', $person_type_id)
                    ->first();

                if ($person_type == null) {
                    DB::table('people_person_types')->insert([
                        'people_id' => $people->id,
                        'person_type_id' => $person_type_id
                    ]);
                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'Contact' . config('constants.flash.added'),
                'data' => [
                    'id' => $business->id,
                    'business_name' => $people->display_name,
                    'alias' => $people->display_name
                ]
            ]);
        }
    }

    public function add_full_business(Request $request)
    {

        // dd($request->all());
        $organization_id = session::get('organization_id');
        $code = '';
        $id = '';
        $mobile_number = $request->input('mobile_no');
        $name = $request->input('first_name');
        $credit_limit_value = $request->input('max_credit_limit');

        $mobile = People::select('people.id', 'people.person_id', 'people.business_id')->leftjoin('persons', 'persons.id', '=', 'people.person_id')
            ->leftjoin('person_communication_addresses', 'person_communication_addresses.person_id', '=', 'persons.id')
            ->leftJoin('businesses', 'businesses.id', '=', 'people.business_id')
            ->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id')
            ->where('person_communication_addresses.mobile_no', $mobile_number)
            ->orWhere('business_communication_addresses.mobile_no', $mobile_number)
            ->first();
        // dd($mobile);
        if ($mobile != null) {
            $people = new People();
            $people->user_type = $request->input('type');
            if ($mobile->person_id != null) {
                $people->person_id = $mobile->person_id;
                $address_type = BusinessAddressType::where('name', 'business')->first();
            }
            if ($mobile->business_id != null) {
                $people->business_id = $mobile->business_id;
                $address_type = PersonAddressType::where('name', 'residential')->first();
            }
            $people->title_id = ($request->input('title_id') != null) ? $request->input('title_id') : null;
            $people->first_name = $request->input('first_name');
            $people->last_name = $request->input('last_name');
            $people->company = $request->input('display_name');
            $people->display_name = $request->input('display_name');
            $people->mobile_no = $request->input('mobile_number');
            $people->email_address = $request->input('email_address');
            $people->phone = $request->input('phone');
            $people->gst_no = $request->input('gst_no');
            $people->pan_no = $request->input('pan_no');
            $people->payment_mode_id = $request->input('payment_mode_id');
            $people->term_id = $request->input('term_id');
            $people->group_id = $request->input('group_name');
            $people->organization_id = Session::get('organization_id');
            $people->save();
        } else {
            if ($request->input('type') == 1) {
                $city = City::select('name')->where('id', $request->input('billing_city_id'))
                    ->first()->name;

                $bcrm_code = Custom::business_crm($city, $request->input('mobile_number'), $request->input('first_name'));
                $code = $bcrm_code;

                $business = new Business();
                $business->bcrm_code = $bcrm_code;
                $business->business_name = $request->input('first_name');
                $business->alias = $request->input('first_name');
                $business->pan = $request->input('pan_no');
                $business->gst = $request->input('gst_no');
                $business->save();

                $address_type = BusinessAddressType::where('name', 'business')->first();

                if ($business->id) {

                    $business_address = new BusinessCommunicationAddress();
                    $business_address->address_type = $address_type->id;
                    $business_address->placename = $request->input('first_name');
                    $business_address->address = $request->input('billing_address');
                    $business_address->address_prev = $request->input('billing_address');
                    $business_address->city_id = $request->input('billing_city_id');
                    $business_address->pin = $request->input('billing_pin');
                    $business_address->google = $request->input('billing_google');
                    $business_address->mobile_no = $request->input('mobile_no');
                    $business_address->mobile_no_prev = $request->input('mobile_no');
                    $business_address->phone = $request->input('phone');
                    $business_address->phone_prev = $request->input('phone');
                    $business_address->email_address = $request->input('email_address');
                    $business_address->email_address_prev = $request->input('email_address');
                    $business_address->business_id = $business->id;
                    $business_address->save();

                    $people = new People();
                    $people->user_type = $request->input('type');
                    $people->business_id = $business->id;
                    $people->title_id = ($request->input('title_id') != null) ? $request->input('title_id') : null;
                    $people->first_name = $request->input('first_name');
                    $people->last_name = $request->input('last_name');
                    $people->company = $request->input('display_name');
                    $people->display_name = $request->input('display_name');
                    $people->mobile_no = $request->input('mobile_no');
                    $people->email_address = $request->input('email_address');
                    $people->phone = $request->input('phone');
                    $people->gst_no = $request->input('gst_no');
                    $people->pan_no = $request->input('pan_no');
                    $people->payment_mode_id = $request->input('payment_mode_id');
                    $people->term_id = $request->input('term_id');
                    $people->group_id = $request->input('group_name');
                    $people->organization_id = Session::get('organization_id');
                    $people->save();

                    $id = $business->id;
                }
            }

            if ($request->input('type') == 0) {
                // dd($request->all());
                $city = City::select('name')->where('id', $request->input('billing_city_id'))
                    ->first()->name;

                $crm_code = Custom::personal_crm($city, $request->input('mobile_number'), $request->input('first_name'));
                $code = $crm_code;

                $person = new Person();
                $person->crm_code = $crm_code;
                $person->salutation = $request->input('title_id');
                $person->first_name = $request->input('first_name');
                $person->last_name = $request->input('last_name');
                $person->gst_no = $request->input('gst_no');
                $person->pan_no = $request->input('pan_no');
                $person->save();
                $address_type = PersonAddressType::where('name', 'residential')->first();
                if ($person->id) {
                    $person_address = new PersonCommunicationAddress();
                    $person_address->person_id = $person->id;
                    $person_address->address_type = $address_type->id;
                    $person_address->city_id = $request->input('billing_city_id');
                    $person_address->mobile_no = $request->input('mobile_no');
                    $person_address->email_address = $request->input('email_address');
                    $person_address->email_address_prev = $request->input('email_address');

                    $person_address->save();

                    $people_exist = People::where('person_id', $person->id)->where('organization_id', Session::get('organization_id'))->first();

                    if ($people_exist == null) {
                        $people = new People();
                        $people->person_id = $person->id;
                        $people->title_id = ($request->input('title_id') != null) ? $request->input('title_id') : null;
                        $people->first_name = $request->input('first_name');
                        $people->last_name = $request->input('last_name');
                        $people->display_name = $request->input('display_name');
                        $people->mobile_no = $request->input('mobile_no');
                        $people->email_address = $request->input('email_address');
                        $people->phone = $request->input('phone');
                        $people->gst_no = $request->input('gst_no');
                        $people->pan_no = $request->input('pan_no');
                        $people->payment_mode_id = $request->input('payment_mode_id');
                        $people->term_id = $request->input('term_id');
                        $people->organization_id = Session::get('organization_id');
                        $people->save();

                        Custom::add_addon('records');
                    } else {
                        $people = $people_exist;
                    }
                    $id = $people->person_id;
                }
            }
        }

        $person_type_name = $request->input('person_type');
        if ($person_type_name != null) {

            $person_type_id = AccountPersonType::where('name', $person_type_name)->first()->id;

            $person_type = DB::table('people_person_types')->where('people_id', $people->id)
                ->where('person_type_id', $person_type_id)
                ->first();

            if ($person_type == null) {
                DB::table('people_person_types')->insert([
                    'people_id' => $people->id,
                    'person_type_id' => $person_type_id
                ]);
            }
        }

        $account_ledgers = AccountLedger::select('account_ledgers.id');

        if ($people->person_id != null) {

            $account_ledgers->where('person_id', $people->person_id);
        } else {
            $account_ledgers->where('business_id', $people->business_id);
        }

        $account_ledgers->where('organization_id', Session::get('organization_id'));
        $account_ledger = $account_ledgers->first();

        if ($account_ledger == null) {

            $personal_ledger = AccountLedgerType::where('name', 'personal')->first();
            $organization = Organization::findOrFail(Session::get('organization_id'));

            $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', Session::get('organization_id'))->first();

            $ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $personal_ledger->id, $people->person_id, $people->business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', '1', '1', Session::get('organization_id'), false);
        } else {
            $ledger = $account_ledger->id;
        }

        $credit_limit = AccountLedgerCreditInfo::findOrFail($ledger);

        $credit_limit->max_credit_limit = $request->input('max_credit_limit');

        $credit_limit->save();

        if ($people->id) {
            $billing_address = new PeopleAddress();
            $billing_address->people_id = $people->id;
            $billing_address->address_type = 0;
            $billing_address->address = $request->input('billing_address');
            $billing_address->city_id = $request->input('billing_city_id');
            $billing_address->google = $request->input('billing_google');
            $billing_address->pin = $request->input('billing_pin');
            $billing_address->save();

            if ($request->input('same_billing_address') != null) {
                $shipping_address = new PeopleAddress();
                $shipping_address->people_id = $people->id;
                $shipping_address->address_type = $address_type->id;
                $shipping_address->address = $request->input('shipping_address');
                $shipping_address->city_id = $request->input('shipping_city_id');
                $shipping_address->google = $request->input('shipping_google');
                $shipping_address->pin = $request->input('shipping_pin');
                $shipping_address->save();
            } else {
                $shipping_address = new PeopleAddress();
                $shipping_address->people_id = $people->id;
                $shipping_address->address_type = $address_type->id;
                $shipping_address->address = $request->input('billing_address');
                $shipping_address->city_id = $request->input('billing_city_id');
                $shipping_address->google = $request->input('billing_google');
                $shipping_address->pin = $request->input('billing_pin');
                $shipping_address->save();
            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'Contact' . config('constants.flash.added'),
            'data' => [
                'id' => $id,
                'name' => $people->display_name,
                'alias' => $people->display_name,
                'type' => $request->input('type'),
                'mobile_no' => $people->mobile_no,
                'email' => $people->email_address,
                'person_type' => $request->input('person_type'),
                'address' => ($request->input('type') != null) ? $city : ""
            ]
        ]);
    }

    public function add_full_businesses(Request $request)
    {
        $city = City::select('name')->where('id', $request->input('billing_city_id'))
            ->first()->name;

        $bcrm_code = Custom::business_crm($city, $request->input('business_mobile'), $request->input('business_name'));

        $business = new Business();
        $business->bcrm_code = $bcrm_code;
        $business->business_name = $request->input('business_name');
        $business->alias = $request->input('business_name');
        $business->pan = $request->input('business_pan');
        $business->gst = $request->input('business_gst');
        $business->save();

        $address_type = BusinessAddressType::where('name', 'business')->first();

        if ($business->id) {
            $business_address = new BusinessCommunicationAddress();
            $business_address->business_id = $business->id;
            $business_address->address_type = $address_type->id;
            $business_address->city_id = $request->input('billing_city_id');
            $business_address->pin = $request->input('billing_pin');
            $business_address->google = $request->input('billing_google');
            $business_address->mobile_no = $request->input('mobile_no');
            $business_address->mobile_no_prev = $request->input('mobile_no');
            $business_address->phone = $request->input('phone');
            $business_address->phone_prev = $request->input('phone');
            $business_address->email_address = $request->input('email');
            $business_address->email_address_prev = $request->input('email');
            $business_address->web_address = $request->input('web_address');
            $business_address->web_address_prev = $request->input('web_address');
            $business_address->address = $request->input('billing_address');
            $business_address->address_prev = $request->input('billing_address');
            $business_address->save();

            $people = new People();
            $people->business_id = $business->id;
            $people->title_id = ($request->input('title_id') != null) ? $request->input('title_id') : null;
            $people->first_name = $request->input('first_name');
            $people->last_name = $request->input('last_name');
            $people->company = $request->input('business_name');
            $people->display_name = $request->input('display_name');
            $people->mobile_no = $request->input('mobile_no');
            $people->email_address = $request->input('email_address');
            $people->phone = $request->input('phone');
            $people->gst_no = $request->input('gst_no');
            $people->pan_no = $request->input('pan_no');
            $people->payment_mode_id = $request->input('payment_mode_id');
            $people->term_id = $request->input('term_id');
            $people->organization_id = Session::get('organization_id');
            $people->save();

            $address_type = BusinessAddressType::where('name', 'business')->first();

            $account_ledgers = AccountLedger::select('account_ledgers.id');

            if ($people->person_id != null) {

                $account_ledgers->where('person_id', $people->person_id);
            } else {
                $account_ledgers->where('business_id', $people->business_id);
            }

            $account_ledgers->where('organization_id', Session::get('organization_id'));
            $account_ledger = $account_ledgers->first();

            if ($account_ledger == null) {

                $personal_ledger = AccountLedgerType::where('name', 'personal')->first();
                $organization = Organization::findOrFail(Session::get('organization_id'));

                $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', Session::get('organization_id'))->first();

                $ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $personal_ledger->id, $people->person_id, $people->business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', '1', '1', Session::get('organization_id'), false);
            } else {
                $ledger = $account_ledger->id;
            }

            // People Ledger
            // $ledger

            $credit_limit = AccountLedgerCreditInfo::findOrFail($ledger);

            $credit_limit->max_credit_limit = $request->input('credit_limit');

            $credit_limit->save();

            if ($people->id) {
                $billing_address = new PeopleAddress();
                $billing_address->people_id = $people->id;
                $billing_address->address_type = 0;
                $billing_address->address = $request->input('billing_address');
                $billing_address->city_id = $request->input('billing_city_id');
                $billing_address->google = $request->input('billing_google');
                $billing_address->pin = $request->input('billing_pin');
                $billing_address->save();

                if ($request->input('same_billing_address') != null) {
                    $shipping_address = new PeopleAddress();
                    $shipping_address->people_id = $people->id;
                    $shipping_address->address_type = $address_type->id;
                    $shipping_address->address = $request->input('shipping_address');
                    $shipping_address->city_id = $request->input('shipping_city_id');
                    $shipping_address->google = $request->input('shipping_google');
                    $shipping_address->pin = $request->input('shipping_pin');
                    $shipping_address->save();
                } else {
                    $shipping_address = new PeopleAddress();
                    $shipping_address->people_id = $people->id;
                    $shipping_address->address_type = $address_type->id;
                    $shipping_address->address = $request->input('billing_address');
                    $shipping_address->city_id = $request->input('billing_city_id');
                    $shipping_address->google = $request->input('billing_google');
                    $shipping_address->pin = $request->input('billing_pin');
                    $shipping_address->save();
                }
            }

            if ($request->input('person_type') != null) {

                $person_type_id = AccountPersonType::where('name', $request->input('person_type'))->first()->id;

                $person_type = DB::table('people_person_types')->where('people_id', $people->id)
                    ->where('person_type_id', $person_type_id)
                    ->first();

                if ($person_type == null) {
                    DB::table('people_person_types')->insert([
                        'people_id' => $people->id,
                        'person_type_id' => $person_type_id
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'Contact' . config('constants.flash.added'),
            'data' => [
                'id' => $business->id,
                'business_name' => $people->display_name,
                'alias' => $people->display_name
            ]
        ]);
    }
}
