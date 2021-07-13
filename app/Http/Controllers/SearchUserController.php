<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\BusinessCommunicationAddress;
use App\PersonCommunicationAddress;
use App\BusinessProfessionalism;
use App\PersonAddressType;
use App\AccountLedger;
use App\AccountPersonType;
use App\AccountLedgerType;
use App\AccountLedgerCreditInfo;
use App\AccountGroup;
use App\Jobs\SendOtpEmail;
use App\BusinessNature;
use App\PeopleAddress;
use App\Organization;
use App\Jobs\SendSms;
use App\Notification\Service\SmsNotificationService;
use App\Business;
use App\Country;
use App\Custom;
use App\Person;
use App\People;
use App\State;
use App\User;
use App\City;
use Session;
use DateTime;
use Mail;
use Hash;
use DB;
use Carbon\Carbon;

class SearchUserController extends Controller
{
    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
    }

    public function index()
    {
        $country_id = Country::where('name', 'India')->first()->id;

        $state = State::select('name', 'id')->where('country_id', $country_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        // dd($state);
        $city = City::select('name', 'id')->where('status', 1)
            ->orderBy('name')
            ->get();
        $results = array();
        // dd($results);

        return view('auth.search_user', compact('state', 'city', 'results'));
    }

    public function propel_search()
    {
        return view('auth.search_propel');
    }

    public function check_user_mobile_number(Request $request)
    {
        $mobile = User::where('mobile', $request->mobile_no)->where('status', '1')->first();

        if (! empty($mobile->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function check_person_mobile_number(Request $request)
    {
        $mobile = PersonCommunicationAddress::where('mobile_no', $request->mobile_no)->where('status', '1')->first();
        if (! empty($mobile->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function check_person_email_address(Request $request)
    {
        // dd($request->all());
        $email = PersonCommunicationAddress::where('email_address', $request->email_address)->where('status', '1')->first();
        // dd($email);
        if (! empty($email->id)) {
            $status = "false";
            return response()->json($status);
        } else {
            $status = "true";
            return response()->json($status);
        }
    }

    public function check_user_email_address(Request $request)
    {
        $email = User::where('email', $request->email_address)->where('status', '1')->first();
        if (! empty($email->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function check_propel_id(Request $request)
    {
        $propel_id = Person::where('crm_code', $request->propel_id)->first();
        if ($propel_id == null) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function check_business_id(Request $request)
    {
        $propel_id = Business::where('bcrm_code', $request->business_id)->first();
        if ($propel_id == null) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function search_existence(Request $request)
    {
        // dd($request->all());
        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::select('name', 'id')->where('country_id', $country_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        $city = City::select('name', 'id')->where('status', 1)
            ->orderBy('name')
            ->get();
        $input = $request->all();
        // dd($input);

        // start to check alreay exists user
        $user = Person::select('users.id', 'persons.id as person_id', 'users.status', 'persons.first_name', 'persons.last_name', DB::raw('group_concat(person_communication_addresses.email_address) AS email_address'), DB::raw('group_concat(person_communication_addresses.mobile_no) AS mobile_no'), DB::raw('DATE_FORMAT(persons.dob, "%d-%m-%Y" ) AS dob'), 'persons.mother_name', 'persons.father_name', 'persons.crm_code', DB::raw('group_concat(cities.name) AS city'));

        $user->leftJoin('users', 'persons.id', '=', 'users.person_id');
        $user->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');
        $user->leftJoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id');

        foreach ($input as $key => $value) {
            if ($value != "" && $key != "_token" && $key != "state" && $key != "email_address" && $key != "mobile_no" && $key != "city" && $key != "mother_name" && $key != "father_name") {
                if ($key == "crm_code") {
                    $user->where("crm_code", '=', "$value");
                } else if ($key == "city") {
                    $user->where("cities.id", '=', "$value");
                } else {
                    $user->where("$key", 'LIKE', $value);
                }
            }
        }

        $user->where(function ($q) use ($request) {
            $q->where('email_address', '=', $request->input('email_address'))
                ->Where('mobile_no', '=', $request->input('mobile_no'));
        });

        $user->where(function ($q) use ($request) {
            $q->where('cities.id', '=', $request->input('city'));
        });
        $user->where('users.status', '=', 1);
        $user->groupBy('persons.id');

        $users = $user->exists();
        // dd($users);
        if ($users) {

            $status = 1;

            return view('auth.search_user', compact('status', 'country_id', 'state', 'city', 'input'));
        }
        // dd($users);
        // end to check alreay exists user

        // start
        // not fully register process
        $check_user = Person::select('users.id', 'persons.id as person_id', 'users.status', 'user_activations.token', 'persons.first_name', 'persons.last_name', DB::raw('group_concat(person_communication_addresses.email_address) AS email_address'), DB::raw('group_concat(person_communication_addresses.mobile_no) AS mobile_no'), DB::raw('DATE_FORMAT(persons.dob, "%d-%m-%Y" ) AS dob'), 'persons.mother_name', 'persons.father_name', 'persons.crm_code', DB::raw('group_concat(cities.name) AS city'));

        $check_user->leftJoin('users', 'persons.id', '=', 'users.person_id');
        $check_user->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');
        $check_user->leftJoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id');
        $check_user->leftjoin('user_activations', 'user_activations.user_id', '=', 'users.id');

        foreach ($input as $key => $value) {
            if ($value != "" && $key != "_token" && $key != "state" && $key != "email_address" && $key != "mobile_no" && $key != "city" && $key != "mother_name" && $key != "father_name") {
                if ($key == "dob") {
                    $dob = explode('-', $value);
                    $dob_value = $dob[2] . '-' . $dob[1] . '-' . $dob[0];
                    $check_user->where("dob", '=', "$dob_value");
                } else if ($key == "crm_code") {
                    $check_user->where("crm_code", '=', "$value");
                } else if ($key == "city") {
                    $check_user->where("cities.id", '=', "$value");
                } else {
                    $check_user->where("$key", 'LIKE', $value);
                }
            }
        }

        $check_user->where(function ($q) use ($request) {
            $q->where('email_address', '=', $request->input('email_address'))
                ->Where('mobile_no', '=', $request->input('mobile_no'));
        });

        $check_user->where(function ($q) use ($request) {
            $q->where('cities.id', '=', $request->input('city'));
        });
        $check_user->where('users.status', '=', 0);
        $check_user->groupBy('persons.id');

        $results = $check_user->exists();
        // dd($results);
        if ($results) {
            $status = 2;
            $results = $check_user->first();
            // dd($results);
            // dd($input);
            return view('auth.search_user', compact('status', 'results', 'country_id', 'state', 'city', 'input'));
        }
        // dd($results);
        // end

        $check_mob = Person::select('users.id', 'users.name', 'users.status', 'user_activations.token', 'persons.id as person_id', 'persons.first_name', 'persons.last_name', DB::raw('group_concat(person_communication_addresses.email_address) AS email_address'), DB::raw('group_concat(person_communication_addresses.mobile_no) AS mobile_no'), DB::raw('DATE_FORMAT(persons.dob, "%d-%m-%Y" ) AS dob'), 'persons.mother_name', 'persons.father_name', 'persons.crm_code', DB::raw('group_concat(cities.name) AS city'));

        $check_mob->leftJoin('users', 'persons.id', '=', 'users.person_id');
        $check_mob->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');
        $check_mob->leftJoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id');
        $check_mob->leftJoin('user_activations', 'user_activations.user_id', '=', 'users.id');

        $check_mob->where(function ($q) use ($request) {
            $q->where('mobile_no', '=', $request->input('mobile_no'))
                ->orWhere('email_address', '=', $request->input('email_address'));
        });
        // $check_mob->WhereIn('users.status',['1','0']);

        // $check_mob->whereNotNull('users.status');
        // $ex = $query->exists();

        $checks = $check_mob->first();

        $sta = $checks->status;

        if ($checks && $sta != null) {
            // dd($checks);

            $status = 3;
            $check = $check_mob->first();
            // dd($check);

            $name = str_pad(substr($check->name, 3, 6), strlen($check->name), "*", STR_PAD_LEFT);
            $mobile = str_pad(substr($check->mobile_no, 3, 6), strlen($check->mobile_no), "*", STR_PAD_LEFT);

            $email_name = substr($check->email_address, 0, strrpos($check->email_address, '@'));

            $email = (strlen($email_name) > 0) ? str_pad(substr($email_name, 3, 2), strlen($email_name), "*", STR_PAD_LEFT) . "@" . substr(strrchr($check->email_address, "@"), 1) : null;

            return view('auth.search_user', compact('status', 'check', 'country_id', 'state', 'city', 'input', 'name', 'mobile', 'email'));
        }
        // var_dump($check);
        // dd($check);

        $query = Person::select('persons.id', 'persons.crm_code', 'persons.first_name', 'person_communication_addresses.mobile_no', 'person_communication_addresses.email_address', 'person_communication_addresses.city_id');
        $query->leftjoin('person_communication_addresses', 'person_communication_addresses.person_id', '=', 'persons.id');
        $query->where(function ($q) use ($request) {
            $q->where('person_communication_addresses.mobile_no', $request->input('mobile_no'))
                ->where('person_communication_addresses.email_address', $request->input('email_address'))
                ->where('persons.first_name', $request->input('first_name'))
                ->where('person_communication_addresses.city_id', $request->input('city'));
        });
        $query->orwhere(function ($q) use ($request) {
            $q->where('person_communication_addresses.mobile_no', '=', $request->input('mobile_no'))
                ->orwhere('person_communication_addresses.email_address', '=', $request->input('email_address'));
        });

        $person = $query->exists();
        if ($person) {
            $status = 4;
            $persons = $query->first();
            return view('auth.search_user', compact('status', 'persons', 'country_id', 'state', 'city', 'input'));
        }

        $status = 0;

        return view('auth.search_user', compact('status', 'country_id', 'state', 'city', 'input'));
    }

    public function simple_user_search(Request $request)
    {
        $query = User::select('persons.id', 'persons.first_name', 'persons.last_name', 'users.mobile');
        $query->leftJoin('persons', 'persons.id', '=', 'users.person_id');

        if ($request->input('username') != null) {
            if (preg_match("/[a-z]/i", $request->input('username'))) {
                $query->where("email", '=', $request->input('username'));
            } else {
                $query->where("mobile", '=', $request->input('username'));
            }
        } else if ($request->input('crm_id') != null) {
            $query->where("persons.crm_code", $request->input('crm_id'));
        }

        $results = $query->first();

        return ($results) ? $results : json_encode([]);
    }

    public function advanced_user_search(Request $request)
    {
        $input = $request->all();

        $query = Person::select('persons.id', 'persons.first_name', 'persons.last_name', 'persons.crm_code', 'person_communication_addresses.email_address', 'person_communication_addresses.mobile_no', 'persons.pan_no', 'persons.aadhar_no', 'persons.passport_no', 'persons.license_no');
        $query->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');

        foreach ($input as $key => $value) {
            if ($value != "" && $key != "_token") {

                if ($key == "first_name") {
                    $query->where("first_name", 'LIKE', "%" . $value . "%");
                } else if ($key == "crm_code") {
                    $query->where("crm_code", 'LIKE', "%" . $value . "%");
                } else if ($key == "email") {
                    $query->where("email_address", 'LIKE', "%" . $value . "%");
                } else if ($key == "mobile_no") {
                    $query->where("mobile_no", 'LIKE', "%" . $value . "%");
                } else if ($key == "pan_no") {
                    $query->where("pan_no", 'LIKE', "%" . $value . "%");
                } else if ($key == "aadhar_no") {
                    $query->where("aadhar_no", 'LIKE', "%" . $value . "%");
                } 
                else if ($key == "license_no") {
                    $query->where("license_no", 'LIKE', "%" . $value . "%");
                } else if ($key == "passport_no") {
                    $query->where("passport_no", 'LIKE', "%" . $value . "%");
                } 
                else {
                    $query->where("$key", 'like', "%" . $value . "%");
                }
            }
        }

        $query->groupby('persons.id');

        /*
         * if($request->input('first_name') != null) {
         * $query->where("persons.first_name", '=', $request->input('first_name'));
         * }
         *
         * if($request->input('crm_code') != null) {
         * $query->where("persons.crm_code", '=', $request->input('crm_code'));
         * }
         *
         * if($request->input('email') != null) {
         * $query->where("person_communication_addresses.email_address", '=', $request->input('email'));
         * }
         *
         * if($request->input('mobile_no') != null) {
         * $query->where("person_communication_addresses.mobile_no", '=', $request->input('mobile_no'));
         * }
         *
         * if($request->input('pan_no') != null) {
         * $query->where("persons.pan_no", '=', $request->input('pan_no'));
         * }
         *
         * if($request->input('aadhar_no') != null) {
         * $query->where("persons.aadhar_no", '=', $request->input('aadhar_no'));
         * }
         *
         * if($request->input('license_no') != null) {
         * $query->where("persons.license_no", '=', $request->input('license_no'));
         * }
         *
         * if($request->input('passport_no') != null) {
         * $query->where("persons.passport_no", '=', $request->input('passport_no'));
         * }
         */

        $results = $query->get();

        return ($results) ? $results : json_encode([]);
    }

    public function register_propel_id(Request $request)
    {
        // dd($request->propel_id);
        $person = Person::where('crm_code', $request->propel_id)->first();
        // dd($person);
        $person->otp = Custom::otp(4);
        $person->save();

        $person_id = $person->id;
        // dd($person_id);
        $user = User::where('person_id', $person->id)->first();
        // dd($user);
        $is_registered = false;

        if ($user != null) {
            $is_registered = true;
            $user_mobile = $user->mobile;
            $user_email = $user->email;
        } else {
            $address_type = PersonAddressType::where('name', 'residential')->first();

            $address = PersonCommunicationAddress::where('address_type', $address_type->id)->where('person_id', $person->id)->first();

            $user_mobile = $address->mobile_no;
            $user_email = $address->email_address;
            $to_email = $user_email;
            $to_name = $person->first_name;
            $data = [
                'name' => $person->first_name,
                'otp' => $person->otp
            ];
            $subject = "Search User";
            $message = "$person->otp is your OTP to create your account on PROPEL ERP";

            $sms_notify_model = $this->SmsNotificationService->save($user_mobile, $subject, $to_name, $message, "", "OTP");
            

            // $this->dispatch(new SendSms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $user_mobile, "$person->otp is your OTP to create your account on PROPEL ERP"));

            // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $user_mobile, "$person->otp is your OTP to create your account on PROPEL ERP");

            /*
             * Mail::send('emails.otp_mail', $data, function ($message) use ($to_email, $to_name) {
             * $message->from('support@propelsoft.in', 'PropelERP');
             * $message->to($to_email, $to_name);
             * $message->subject("Propel OTP Code");
             * });
             */

            // $this->dispatch(new SendOtpEmail(['name' => $person->first_name, 'otp' => $person->otp], $user_email));
        }

        $mobile = str_pad(substr($user_mobile, - 3, 3), strlen($user_mobile), "X", STR_PAD_LEFT);

        $email_name = substr($user_email, 0, strrpos($user_email, '@'));

        $email = (strlen($email_name) > 0) ? str_pad(substr($email_name, - 2, 2), strlen($email_name), "X", STR_PAD_LEFT) . "@" . substr(strrchr($user_email, "@"), 1) : null;

        return view('auth.register_propel', compact('is_registered', 'mobile', 'email', 'person_id'));
    }

    public function add_user(Request $request)
    {

        // dd($request->all());
        $mobile_number = $request->input('mobile_no');

        /*
         * $mobile_no = User::where('mobile',$mobile_number)->first();
         * if(!empty($mobile_no->id))
         * {
         * return response()->json(['status' =>0]);
         * }
         * else
         * {
         */
        $already_exits_data = Person::where('id', $request->id)->first();
        // dd($already_exits_data);

        if ($already_exits_data != null) {
            $people_exist = People::where('person_id', $already_exits_data->id)->where('organization_id', Session::get('organization_id'))->first();

            if ($people_exist == null) {
                $people = new People();
                $people->person_id = $already_exits_data->id;
                $people->first_name = $request->input('first_name');
                $people->display_name = $request->input('first_name');
                $people->mobile_no = $request->input('mobile_no');
                $people->email_address = $request->input('email_address');
                $people->organization_id = Session::get('organization_id');
                $people->save();

                if ($people->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $people->id;
                    $people_address->address_type = 0;
                    $people_address->address = $request->input('address');
                    $people_address->city_id = $request->input('city_id');
                    $people_address->pin = $request->input('pin');
                    $people_address->save();
                }

                Custom::add_addon('records');
            } else {
                $people = $people_exist;
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

            $state = State::select('states.id')->leftjoin('cities', 'cities.state_id', '=', 'states.id')
                ->where('cities.id', $request->input('city_id'))
                ->first()->id;

            $city = City::select('name')->where('id', $request->input('city_id'))
                ->first()->name;

            return response()->json([
                'status' => 1,
                'message' => 'Contact' . config('constants.flash.added'),
                'data' => [
                    'id' => $already_exits_data->id,
                    'first_name' => $people->first_name,
                    'last_name' => ($people->last_name) ? $people->last_name : "",
                    'people_id' => $people->id,
                    'city_id' => $request->input('city_id'),
                    'mobile_no' => $mobile_number,
                    'state' => $state,
                    'city' => $city
                ]
            ]);
        } else {
            $city = City::select('name')->where('id', $request->input('city_id'))
                ->first()->name;

            $crm_code = Custom::personal_crm($city, $request->input('mobile_no'), $request->input('first_name'));

            $person = new Person();
            $person->crm_code = $crm_code;
            $person->first_name = $request->input('first_name');
            $person->pan_no = $request->input('pan');
            $person->aadhar_no = $request->input('aadhar_no');
            $person->passport_no = $request->input('passport_no');
            $person->license_no = $request->input('license_no');
            $person->save();

            if ($person->id) {

                $address_type = PersonAddressType::where('name', 'residential')->first();

                $person_address = new PersonCommunicationAddress();
                $person_address->person_id = $person->id;
                $person_address->address_type = $address_type->id;
                $person_address->address = $request->input('address');
                $person_address->city_id = $request->input('city_id');
                $person_address->mobile_no = $request->input('mobile_no');
                $person_address->mobile_no_prev = $request->input('mobile_no');
                $person_address->email_address = $request->input('email_address');
                $person_address->save();

                $people_exist = People::where('person_id', $person->id)->where('organization_id', Session::get('organization_id'))->first();

                if ($people_exist == null) {
                    $people = new People();
                    $people->person_id = $person->id;
                    $people->first_name = $request->input('first_name');
                    $people->display_name = $request->input('first_name');
                    $people->mobile_no = $request->input('mobile_no');
                    $people->email_address = $request->input('email_address');
                    $people->organization_id = Session::get('organization_id');
                    $people->save();

                    Custom::add_addon('records');
                } else {
                    $people = $people_exist;
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

                if ($people->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $people->id;
                    $people_address->address_type = 0;
                    $people_address->address = $request->input('address');
                    $people_address->city_id = $request->input('city_id');
                    $people_address->pin = $request->input('pin');
                    $people_address->save();
                }
            }
            $state = State::select('states.id')->leftjoin('cities', 'cities.state_id', '=', 'states.id')
                ->where('cities.id', $request->input('city_id'))
                ->first()->id;

            return response()->json([
                'status' => 1,
                'message' => 'Contact' . config('constants.flash.added'),
                'data' => [
                    'id' => $person->id,
                    'first_name' => $people->first_name,
                    'last_name' => ($people->last_name) ? $people->last_name : "",
                    'people_id' => $people->id,
                    'city_id' => $request->input('city_id'),
                    'mobile_no' => $mobile_number,
                    'state' => $state,
                    'city' => $city
                ]
            ]);
        }
        /* } */
    }

    public function add_users(Request $request)
    {
        $mobile_number = $request->input('mobile_no');

        $mobile_no = User::where('mobile', $mobile_number)->first();
        if (! empty($mobile_no->id)) {
            return response()->json([
                'status' => 0
            ]);
        } else {
            $city = City::select('name')->where('id', $request->input('city_id'))
                ->first()->name;

            $crm_code = Custom::personal_crm($city, $request->input('mobile_no'), $request->input('first_name'));

            $person = new Person();
            $person->crm_code = $crm_code;
            $person->first_name = $request->input('first_name');
            $person->pan_no = $request->input('pan');
            $person->aadhar_no = $request->input('aadhar_no');
            $person->passport_no = $request->input('passport_no');
            $person->license_no = $request->input('license_no');
            $person->save();

            if ($person->id) {

                $address_type = PersonAddressType::where('name', 'residential')->first();

                $person_address = new PersonCommunicationAddress();
                $person_address->person_id = $person->id;
                $person_address->address_type = $address_type->id;
                $person_address->address = $request->input('address');
                $person_address->city_id = $request->input('city_id');
                $person_address->mobile_no = $request->input('mobile_no');
                $person_address->mobile_no_prev = $request->input('mobile_no');
                $person_address->email_address = $request->input('email_address');
                $person_address->save();

                $people_exist = People::where('person_id', $person->id)->where('organization_id', Session::get('organization_id'))->first();

                if ($people_exist == null) {
                    $people = new People();
                    $people->person_id = $person->id;
                    $people->first_name = $request->input('first_name');
                    $people->display_name = $request->input('first_name');
                    $people->mobile_no = $request->input('mobile_no');
                    $people->email_address = $request->input('email_address');
                    $people->organization_id = Session::get('organization_id');
                    $people->save();

                    Custom::add_addon('records');
                } else {
                    $people = $people_exist;
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

                if ($people->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $people->id;
                    $people_address->address_type = 0;
                    $people_address->address = $request->input('address');
                    $people_address->city_id = $request->input('city_id');
                    $people_address->pin = $request->input('pin');
                    $people_address->save();
                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'Contact' . config('constants.flash.added'),
                'data' => [
                    'id' => $person->id,
                    'first_name' => $people->first_name,
                    'last_name' => ($people->last_name) ? $people->last_name : ""
                ]
            ]);
        }
    }

    public function add_full_user(Request $request)
    {

        // dd($request->all());
        $city = City::select('name')->where('id', $request->input('billing_city_id'))
            ->first()->name;

        $crm_code = Custom::personal_crm($city, $request->input('mobile_no'), $request->input('first_name'));

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
            $person_address->pin = $request->input('mobile_no');
            $person_address->google = $request->input('billing_google');
            $person_address->mobile_no = $request->input('mobile_no');
            $person_address->mobile_no_prev = $request->input('mobile_no');
            $person_address->phone = $request->input('phone');
            $person_address->phone_prev = $request->input('phone');
            $person_address->email_address = $request->input('email');
            $person_address->email_address_prev = $request->input('email');
            $person_address->web_address = $request->input('web_address');
            $person_address->web_address_prev = $request->input('web_address');
            $person_address->address = $request->input('billing_address');
            $person_address->address_prev = $request->input('billing_address');
            $person_address->save();

            $people_exist = People::where('person_id', $person->id)->where('organization_id', Session::get('organization_id'))->first();

            if ($people_exist == null) {
                $people = new People();
                $people->person_id = $person->id;
                $person->title_id = ($request->input('title_id') != null) ? $request->input('title_id') : null;
                $people->first_name = $request->input('first_name');
                $people->last_name = $request->input('last_name');
                $people->display_name = $request->input('display_name');
                $people->mobile_no = $request->input('mobile_no');
                $people->email_address = $request->input('email');
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
                    $shipping_address->address_type = 1;
                    $shipping_address->address = $request->input('shipping_address');
                    $shipping_address->city_id = $request->input('shipping_city_id');
                    $shipping_address->google = $request->input('shipping_google');
                    $shipping_address->pin = $request->input('shipping_pin');
                    $shipping_address->save();
                } else {
                    $shipping_address = new PeopleAddress();
                    $shipping_address->people_id = $people->id;
                    $shipping_address->address_type = 1;
                    $shipping_address->address = $request->input('billing_address');
                    $shipping_address->city_id = $request->input('billing_city_id');
                    $shipping_address->google = $request->input('billing_google');
                    $shipping_address->pin = $request->input('billing_pin');
                    $shipping_address->save();
                }
            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'Contact' . config('constants.flash.added'),
            'data' => [
                'id' => $people->person_id,
                'name' => $people->display_name
            ]
        ]);
    }

    public function user_file_upload(Request $request)
    {
        $file = $request->file('file');
        $id = $request->input('id');

        $business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
        $business_name = Business::findOrFail($business_id)->business_name;

        $path_array = explode('/', 'organizations/' . $business_name . '/people');

        $public_path = '';

        foreach ($path_array as $p) {
            $public_path .= $p . "/";
            if (! file_exists(public_path($public_path))) {
                mkdir(public_path($public_path), 0777, true);
            }
        }

        $dt = new DateTime();

        $name = "_" . $id . "_" . $dt->format('Y-m-d-H-i-s') . "." . $file->getClientOriginalName();

        return $request->file('file')->move(public_path($public_path), $name);
    }

    public function user_file_remove(Request $request)
    {
        $file = $request->file('file');
        $id = $request->input('id');

        $business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
        $business_name = Business::findOrFail($business_id)->business_name;

        $path_array = explode('/', 'organizations/' . $business_name . '/people');

        $public_path = '';

        foreach ($path_array as $p) {
            $public_path .= $p . "/";
            if (! file_exists(public_path($public_path))) {
                mkdir(public_path($public_path), 0777, true);
            }
        }

        $dt = new DateTime();

        $name = "_" . $id . "_" . $dt->format('Y-m-d-H-i-s') . "." . $file->getClientOriginalName();

        return $request->file('file')->move(public_path($public_path), $name);
    }

    /* Search globally and select peopel - working this function */
    public function add_people(Request $request)
    {
        $person = Person::findOrFail($request->input('id'));
        $person_info = PersonCommunicationAddress::select('id', 'mobile_no', 'email_address', 'address_type', 'address', 'city_id')->where('person_id', $request->input('id'))
            ->first();

        $people_exist = People::where('person_id', $person->id)->where('organization_id', Session::get('organization_id'))->first();
        // dd($people_exist);
        if ($people_exist == null) {
            $people = new People();
            $people->person_id = $person->id;
            $people->first_name = $person->first_name;
            $people->last_name = $person->last_name;
            $people->display_name = $person->first_name;
            $people->mobile_no = $person_info->mobile_no;
            $people->email_address = $person_info->email_address;
            $people->organization_id = Session::get('organization_id');
            $people->save();

            Custom::add_addon('records');

            if ($people->id) {
                $people_address = new PeopleAddress();
                $people_address->people_id = $people->id;
                $people_address->address = $person_info->address;
                $people_address->address_type = $person_info->address_type;
                $people_address->city_id = $person_info->city_id;

                $people_address->save();
            }

            $people_id = $people->id;
            $person_id = $people->person_id;
        } else {
            $people_id = $people_exist->id;
            $person_id = $people_exist->person_id;
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
                'id' => $person_id
            ]
        ]);
    }

    public function get_people($take, $order = null)
    {
        if ($order == null)
            $order = "asc";
        return User::select('id', 'name', 'mobile', 'email')->take($take)
            ->orderby('id', $order)
            ->get();
    }

    public function people($account, $value)
    {
        if (is_numeric($account)) {
            $newuser = User::where('mobile', $account)->first();
        } else {
            $newuser = User::where('email', $account)->first();
        }

        $newuser->password = Hash::make($value);
        $newuser->save();

        return [
            "message" => "Success"
        ];
    }

    public function get_people_detail(Request $request)
    {
        $people = People::select('people.id as people_id', 'people.first_name', 'people.last_name', 'people.display_name', 'people.mobile_no', 'people.email_address', 'people_titles.id AS title_id', 'genders.id AS gender_id', 'billing_city.id as city_id', 'billing_state.id as state_id', DB::raw('DATE_FORMAT(people.created_at, "%Y-%m-%d") AS created_at'), DB::raw('COALESCE(billing_city.name, "") AS billing_city'), DB::raw('COALESCE(billing_state.name, "") as billing_state'), DB::raw('COALESCE(billing_address.address, "") as billing_address'), DB::raw('COALESCE(billing_address.pin, "") as billing_pin'), DB::raw('COALESCE(billing_address.google, "") as billing_google'), 'billing_address.id AS billing_id', DB::raw('COALESCE(shipping_city.name, "") AS shipping_city'), DB::raw('COALESCE(shipping_state.name, "") as shipping_state'), DB::raw('COALESCE(shipping_address.address, "") as shipping_address'), DB::raw('COALESCE(shipping_address.pin, "") as shipping_pin'), DB::raw('COALESCE(shipping_address.google, "") as shipping_google'), 'shipping_address.id AS shipping_id');
        $people->leftJoin('genders', 'genders.id', '=', 'people.gender_id');
        $people->leftJoin('people_titles', 'people_titles.id', '=', 'people.title_id');
        $people->leftJoin('people_addresses AS billing_address', function ($join) {
            $join->on('billing_address.people_id', '=', 'people.id')
                ->where('billing_address.address_type', '0');
        });
        $people->leftJoin('people_addresses AS shipping_address', function ($join) {
            $join->on('shipping_address.people_id', '=', 'people.id')
                ->where('shipping_address.address_type', '1');
        });

        if ($request->input('account') == 0) {
            $people->where('people.person_id', $request->input('id'));
        } else if ($request->input('account') == 1) {
            $people->where('people.business_id', $request->input('id'));
        }
        $people->leftjoin('cities AS billing_city', 'billing_address.city_id', '=', 'billing_city.id');
        $people->leftjoin('states AS billing_state', 'billing_city.state_id', '=', 'billing_state.id');
        $people->leftjoin('cities AS shipping_city', 'shipping_address.city_id', '=', 'shipping_city.id');
        $people->leftjoin('states AS shipping_state', 'shipping_city.state_id', '=', 'shipping_state.id');
        $people->where('people.organization_id', Session::get('organization_id'));

        $person = $people->first();

        return response()->json([
            'data' => $person,
            'type' => $request->input('account')
        ]);
    }
}
