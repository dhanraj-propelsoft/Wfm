<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notification\Service\SmsNotificationService;
use App\PersonCommunicationAddress;
use App\PersonAddressType;
use Carbon\Carbon;
use App\Setting;
use App\Country;
use App\Custom;
use App\Person;
use App\State;
use App\City;
use App\User;
use Mail;
use Hash;
use Auth;
use DB;

class SignupController extends Controller
{

    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
    }

    private $successStatus = 200;

    public function get_state()
    {
        $country = Country::select('id', 'name')->where('name', "India")->first();

        $state = State::select('id', 'name')->where('country_id', $country->id)
            ->where('status', 1)
            ->get();

        return response()->json([
            'result' => $state
        ], $this->successStatus);
    }

    public function get_city($id = null)
    {
        $city = City::select('id', 'name')->where('status', 1)
            ->where('state_id', request('id'))
            ->get();

        return response()->json([
            'result' => $city
        ], $this->successStatus);
    }

    public function getCity($id)
    {
        $city = City::select('id', 'name')->where('status', 1)
            ->where('state_id', $id)
            ->get();

        return response()->json([
            'result' => $city
        ], $this->successStatus);
    }

    public function search()
    {
        $query = Person::select('users.id', 'persons.id as person_id', 'persons.first_name', DB::raw('COALESCE(persons.last_name, "") AS last_name'), DB::raw('COALESCE(person_communication_addresses.email_address, "") AS email_address'), DB::raw('COALESCE(person_communication_addresses.mobile_no, "") AS mobile_no'), DB::raw('COALESCE(DATE_FORMAT(persons.dob, "%d-%m-%Y" ), "") AS dob'), DB::raw('COALESCE(persons.mother_name, "") AS mother_name'), DB::raw('COALESCE(persons.father_name, "") AS father_name'), 'persons.crm_code', DB::raw('group_concat(cities.name) AS city'));

        $query->leftJoin('users', 'persons.id', '=', 'users.person_id');
        $query->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');
        $query->leftJoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id');

        if (request('first_name') != null) {
            $query->where("persons.first_name", '=', request('first_name'));
        }

        if (request('last_name') != null) {
            $query->where("persons.last_name", '=', request('last_name'));
        }

        if (request('dob') != null) {
            $dob = Carbon::parse(request('dob'))->format('Y-m-d');
            $query->where("persons.dob", '=', "$dob");
        }

        if (request('city_id') != null) {
            $query->where("person_communication_addresses.city_id", '=', request('city_id'));
        }

        if (request('mobile_no') != null) {
            $query->where("person_communication_addresses.mobile_no", '=', request('mobile_no'));
        }

        if (request('email_address') != null) {
            $query->where("person_communication_addresses.email_address", '=', request('email_address'));
        }

        $query->groupBy('persons.id');

        $results = $query->get();

        return response()->json([
            'result' => $results
        ], $this->successStatus);
    }

    public function send_propel_id()
    {
        $person = Person::where('crm_code', request('crm_code'))->first();
        $person->otp = Custom::otp(4);
        $person->save();

        $person_id = $person->id;

        $user = User::where('person_id', $person->id)->first();
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

            // $this->dispatch(new SendSms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $user_mobile, "$person->otp is your OTP to create your account on PROPEL ERP"));

            /* Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $user_mobile, "$person->otp is your OTP to create your account on PROPEL ERP"); */

            $to_email = $user_email;
            $to_name = $person->first_name;
            $data = [
                'name' => $person->first_name,
                'otp' => $person->otp
            ];

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

        $email = (strlen($email_name) > 0) ? str_pad(substr($email_name, - 2, 2), strlen($email_name), "X", STR_PAD_LEFT) . "@" . substr(strrchr($user_email, "@"), 1) : "";

        return response()->json([
            'is_registered' => $is_registered,
            'mobile' => $mobile,
            'email' => $email,
            'person_id' => $person_id
        ], $this->successStatus);
    }

    public function store_propel(Request $request)
    {
        if (request('person_id') != null) {

            $registered_person = User::where('person_id', request('person_id'))->where('status', '1')->first();

            if ($registered_person == null || $registered_person == "") {

                $person_id = request('person_id');
            } else {
                $success['status'] = '0';
                $success['message'] = 'Selected user is already registered. Please login.';
                return response()->json($success, $this->successStatus);
            }

            $person = Person::find(request('person_id'));

            if (request('otp') != null) {
                if ($person->otp == request('otp')) {
                    $address_type = PersonAddressType::where('name', 'residential')->first();

                    $address = PersonCommunicationAddress::where('address_type', $address_type->id)->where('person_id', $person->id)->first();

                    $first_name = $person->first_name;
                    $mobile_no = $address->mobile_no;
                    $email_address = (request('email') != null) ? request('email') : $address->email_address;
                    $status = 1;
                } else {
                    $success['status'] = '0';
                    $success['message'] = 'Otp does not match!';
                    return response()->json($success, $this->successStatus);
                }
            } else {

                $first_name = request('first_name');
                $mobile_no = request('mobile_no');
                $email_address = request('email_address');
                $person_id = request('person_id');
                $status = 0;
            }

            $user = new User();
            $user->name = $first_name;
            $user->mobile = $mobile_no;
            $user->email = $email_address;
            $user->password = Hash::make(request('password'));
            $user->person_id = $person_id;
            $user->otp_time = Carbon::now()->format('Y-m-d H:i:s');
            $user->otp = Custom::otp(4);
            $user->otp_sent = 1;
            $user->status = $status;
            $user->save();

            if (Auth::loginUsingId([
                $user->id
            ])) {

                $setting = new Setting();
                $setting->name = 'theme';
                $setting->data = json_encode([
                    "header" => "bg-gradient-8",
                    "sidebar" => "gradient bg-gradient-8"
                ]);
                $setting->user_id = $user->id;
                $setting->save();

                Custom::createAccounts($user->id, $user, true);

                if (request('otp') != null) {
                    $success['status'] = '1';
                    $success['user'] = $user;
                    $success['propelId'] = Person::find($user->person_id)->crm_code;
                    $success['image'] = "";
                    $success['token'] = $user->createToken($user->name)->accessToken;
                } else {
                    $success['status'] = '2';
                    $success['message'] = "Kindly verify your email and OTP to login.";
                }

                return response()->json($success, $this->successStatus);
            }
        } else {
            $mobile_no = request('mobile_no');
            $subject = "Otp Send";
            $content_addresed_to = request('first_name') . " " . request('last_name');

            $person_data = array(
                'first_name' => request('first_name'),
                'last_name' => request('last_name'),
                'mother_name' => "",
                'father_name' => "",
                'dob' => request('dob'),
                'state' => request('state'),
                'city' => request('city'),
                'mobile' => request('mobile_no'),
                'email' => request('email_address'),
                'password' => request('password')
            );

            $personid = Custom::createPerson($person_data, false);

            $person_id = $personid['person_id'];

            if ($person_id != null || $person_id != "") {

                $otp = Custom::createUser($person_id, $person_data, false);
                $message = "$otp " . config('constants.messages.sms_activation');

                if (count(Mail::failures()) > 0) {
                    $activation['message'] = config('constants.messages.activation_error');
                } else {
                    $activation['message'] = config('constants.messages.activation');
                    Log::info('Signupcontroller->Store_propel:- Line No 263');
                    $sms_notify_model = $this->SmsNotificationService->save($mobile_no, $subject, $content_addresed_to, $message, " ", "OTP");
                    // OTP Message
                    // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), request('mobile_no'), "$otp ".config('constants.messages.sms_activation'));
                }

                $success['status'] = '2';
                $success['message'] = "Kindly verify your email and OTP to login.";
                return response()->json($success, $this->successStatus);
            }
        }
    }
}
