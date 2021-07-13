<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notification\Service\SmsNotificationService;
use App\PersonCommunicationAddress;
use App\ActivationRepository;
use App\PersonAddressType;
use Carbon\Carbon;
use App\Setting;
use App\Country;
use App\Custom;
use App\Person;
use App\State;
use App\City;
use App\User;
use Validator;
use Session;
use Hash;
use Mail;
use DB;
use Auth;
use URL;

class RegisterController extends Controller
{

    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
    }

    public function new_user(Request $request)
    {
        return view('activation_sent');
    }

    public function add_user(Request $request)
    {
        // dd($request->all());
        $otp = $request->input('otp');
        $token = $request->input('token');

        $user = DB::table('user_activations')->select('user_id')
            ->where('token', $token)
            ->first();
        // dd($user);
        $user_id = $user->user_id;
        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::select('name', 'id')->where('country_id', $country_id)
            ->orderBy('name')
            ->get();
        $newuser = User::findOrFail($user->user_id);
        // dd($newuser->otp);
        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $otp_time = Carbon::parse($newuser->otp_time)->format('Y-m-d H:i:s');
        if ($otp == $newuser->otp) {

            return view('auth.user_register_create', compact('state', 'request', 'token', 'otp'));
        } else {
            return view('auth.otp_activation', compact('token', 'user_id'))->withErrors([
                'Otp does not match!'
            ]);
        }
        /* return view('auth.otp_activation', compact('state', 'request','token','otp')); */
    }

    public function otp_activation(Request $request)
    {
        // dd($request->all());
        $u_id = User::select('id')->where('person_id', $request->input('person_id'))
            ->first();
        // dd($u_id->id);
        $user_id = $u_id->id;
        $u_token = DB::table('user_activations')->select('user_id', 'token')
            ->where('user_id', $user_id)
            ->first();
        $token = $u_token->token;
        return view('auth.otp_activation', compact('user_id', 'token'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        Log::info('RegisterController->Store:- Line No 82');

        $mobile = User::where('mobile', $request->mobile_no)->where('status', '!=', '1')->first();
        if (! empty($mobile->id)) {
            $mobile->delete();
        }

        $email = User::where('email', $request->email_address)->where('status', '!=', '1')->first();
        if (! empty($email->id)) {
            $email->delete();
        }

        $person_id = null;

        $input = array(
            "_token" => $request->input('_token'),
            "first_name" => $request->input('first_name'),
            "mobile" => $request->input('mobile_no'),
            "email" => $request->input('email_address'),
            "dob" => $request->input('dob'),
            "mother_name" => $request->input('mother_name'),
            "father_name" => $request->input('father_name'),
            "state" => $request->input('state'),
            "city" => $request->input('city')
            /*
         * "password" => $request->input('password'),
         * "password_confirmation" => $request->input('password_confirmation')
         */
        );

        $v = Validator::make($input, [
            'first_name.*' => 'required|max:255',
            'mobile.*' => 'required|digits:10|unique:users',
            'email.*' => 'required|email|max:255|unique:users',
            'dob.*' => 'required',
            'mother_name.*' => 'required',
            'father_name.*' => 'required',
            'city.*' => 'required'
            /* 'password.*' => 'required|min:6|confirmed', */
        ]);

        if ($v->fails()) {
            $errors = $v->messages()->all();
            $request->merge($errors);
            return (new RegisterUserController())->add_user($request);
        }

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $mobile = $request->input('mobile_no');
        $email = $request->input('email_address');
        $dob = $request->input('dob');
        $mother_name = $request->input('mother_name');
        $father_name = $request->input('father_name');
        $state = $request->input('state');
        $city = $request->input('city');
        $password = $request->input('password');

        $person_data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'mother_name' => $mother_name,
            'father_name' => $father_name,
            'dob' => $dob,
            'state' => $state,
            'city' => $city,
            'mobile' => $mobile,
            'email' => $email,
            'password' => $password
        );

        if ($request->input('person_id')) {

            $registered_person = User::where('person_id', $request->input('person_id'))->where('status', '1')->first();

            if ($registered_person == null || $registered_person == "") {

                $person_id = $request->input('person_id');
            } else {
                return redirect()->back()->withErrors([
                    'Selected user is already registered. Please login.'
                ]);
            }
        } else {

            $personid = Custom::createPerson($person_data, false);

            $person_id = $personid['person_id'];
        }

        if ($person_id != null || $person_id != "") {

            $otp = Custom::createUser($person_id, $person_data, false);
            $sms_content = "$otp " . config('constants.messages.sms_activation');
            $subject = "Register";
            $name = $request->input('first_name') . " " . $request->input('last_name');

            if (count(Mail::failures()) > 0) {
                $activation['message'] = config('constants.messages.activation_error');
            } else {
                $activation['message'] = config('constants.messages.activation');

                $msg = $this->SmsNotificationService->save($mobile, $subject, $name, $sms_content, " ", "OTP");

                // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $mobile, "$otp ".config('constants.messages.sms_activation'));
            }
        }

        $u_id = User::select('id')->where('person_id', $person_id)->first();
        // dd($u_id->id);
        $user_id = $u_id->id;
        $u_token = DB::table('user_activations')->select('user_id', 'token')
            ->where('user_id', $user_id)
            ->first();
        $token = $u_token->token;
        // dd($token);
        // dd($u_token);
        // start comment for email not working
        /*
         * $repo = new ActivationRepository;
         * $token = $repo->createActivation($newuser);
         */
        /*
         * $user = DB::table('user_activations')->select('user_id', 'created_at')->where('token', $token)->first();
         * $user_id = $user->user_id;
         */
        // $repo = new ActivationRepository;
        // $token = $repo->createActivation($person_id);
        // return view('auth.activation_sent', compact('activation'));
        // return redirect('user/activation', ['token']);
        // dd($person_id);
        /*
         * dd($newuser->person_id);
         * $repo = new ActivationRepository;
         * $token = $repo->createActivation($newuser);
         * //dd($token);
         * $url = route('user.activate', [$token]);
         */
        // end
        return view('auth.otp_activation', compact('token', 'user_id'));
    }

    public function store_propel(Request $request)
    {

        // dd($request->all());
        $person = Person::find($request->person_id);
        // dd($person);
        $query = PersonCommunicationAddress::select('id', 'mobile_no', 'email_address')->where('person_id', $request->person_id)->first();
        $person_id = $request->person_id;
        $is_registered = "";
        $mobile = $query->mobile_no;
        $email = $query->email_address;
        // dd($is_registered);
        if ($person->otp == $request->otp) {
            $address_type = PersonAddressType::where('name', 'residential')->first();

            $address = PersonCommunicationAddress::where('address_type', $address_type->id)->where('person_id', $person->id)->first();

            $user = new User();
            $user->name = $person->first_name;
            $user->mobile = $address->mobile_no;
            $user->email = ($request->email != null) ? $request->email : $address->email_address;
            $user->password = Hash::make($request->password);
            $user->person_id = $request->person_id;
            $user->otp_time = Carbon::now()->format('Y-m-d H:i:s');
            $user->otp = Custom::otp(4);
            $user->otp_sent = 1;
            $user->status = "1";
            $user->save();
            // dd($user->id);
            // end
            /*
             * $per_id = $request->input('person_id');
             * //dd($per_id);
             * $user = User::select('id','person_id','name','mobile','email','password')->where('person_id',$per_id)->first();
             * dd($user->id);
             */

            if (Auth::loginUsingId([
                $user->id
            ])) {

                $setting = new Setting();
                $setting->name = 'theme';
                $setting->data = json_encode([
                    "header" => "bg-gradient-8",
                    "sidebar" => "gradient bg-gradient-8"
                ]);
                $setting->user_id = Auth::id();
                $setting->save();

                Session::put('theme_header', "bg-gradient-8");
                Session::put('theme_sidebar', "gradient bg-gradient-8");

                Custom::createAccounts($user->id, $user, true);
                return redirect()->intended('companies');
            }
        } else {
            return view('auth.register_propel', compact('is_registered', 'mobile', 'email', 'person_id'))->withErrors([
                'Otp does not match!'
            ]);
        }
    }

    public function resend_otp($token)
    {
        Log::info('RegisterController->Resend_otp :- Line No 267');
        $user = DB::table('user_activations')->select('user_id', 'created_at')
            ->where('token', $token)
            ->first();
        $user_id = $user->user_id;

        $newuser = User::findOrFail($user_id);

        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $otp_time = Carbon::parse($newuser->otp_time)->format('Y-m-d H:i:s');
        ;

        $resend = array();

        if ($newuser->otp_sent > 2) {
            if (Custom::time_difference($current_time, $otp_time, 'm') > 60) {
                $newuser->otp_time = Carbon::now()->format('Y-m-d H:i:s');
                $newuser->otp = Custom::otp(4);
                $newuser->otp_sent = 0;
                $newuser->save();
            }
        }

        if ($newuser->otp_sent > 2) {
            $resend['resend'] = "Resend SMS limit exceeded. Try after an hour!";
        } else {

            $newuser->otp_time = Carbon::now()->format('Y-m-d H:i:s');
            $newuser->otp = Custom::otp(4);
            $newuser->otp_sent += 1;
            $newuser->save();

            $name = $newuser->name;
            $mobile = $newuser->mobile;
            $subject = "OTP SEND";
            $sms_content = "$newuser->otp " . config('constants.messages.sms_activation');

            $msg = $this->SmsNotificationService->save($mobile, $subject, $name, $sms_content, " ", "OTP");

            // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $newuser->mobile, "$newuser->otp ".config('constants.messages.sms_activation'));
        }

        return view('auth.otp_activation', compact('user_id', 'token', 'resend'));
    }

    public function activateUser($token)
    {
        $user = DB::table('user_activations')->select('user_id', 'created_at')
            ->where('token', $token)
            ->first();
        $user_id = $user->user_id;

        $newuser = User::findOrFail($user_id);

        $resend = array();

        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $token_time = Carbon::parse($user->created_at)->format('Y-m-d H:i:s');

        if ($newuser->status == 1) {
            $time_exceeded['time_exceeded'] = "User already registered. Please <a href='" . route('login') . "'> login </a>!";
            return view('auth.otp_activation', compact('user_id', 'token', 'time_exceeded', 'resend'));
        } else {
            if (Custom::time_difference($current_time, $token_time, 'h') < 24) {
                $newuser->status = '2'; // Email Verified
                $newuser->save();

                return view('auth.otp_activation', compact('user_id', 'token', 'resend'));
            } else {
                $time_exceeded['time_exceeded'] = "Time limit is exceeded to use the verification link. Please create account <a href='" . route('search_register_user') . "'> again </a>!";
                return view('auth.otp_activation', compact('user_id', 'token', 'time_exceeded', 'resend'));
            }
        }
    }

    public function activateUserLogin(Request $request)
    {
        // dd($request->all());
        // $person_id = $request->person_id;
        $password = $request->input('password');
        $otp = $request->input('otp');
        $token = $request->input('user_token');
        $user = DB::table('user_activations')->select('user_id')
            ->where('token', $token)
            ->first();
        // dd($user->user_id);

        // To get token and otp
        /* $user = User::select('users.id','user_activations.user_id','user_activations.token')->leftjoin('user_activations','user_activations.user_id','=','users.id')->where('users.person_id',$person_id)->first(); */

        // start to update password
        $pass = Hash::make($password);
        DB::table('users')->where('id', $user->user_id)->update([
            'password' => $pass
        ]);
        // $pass = User::findOrFail($user->user_id)->update(['password'=>$password]);
        // end to update password

        $newuser = User::findOrFail($user->user_id);
        // dd($newuser->id);
        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $otp_time = Carbon::parse($newuser->otp_time)->format('Y-m-d H:i:s');

        if ($otp == $newuser->otp) {

            $newuser->status = "1";
            $newuser->save();
            if (Auth::loginUsingId([
                $newuser->id
            ])) {

                $setting = new Setting();
                $setting->name = 'theme';
                $setting->data = json_encode([
                    "header" => "bg-gradient-8",
                    "sidebar" => "gradient bg-gradient-8"
                ]);
                $setting->user_id = Auth::id();
                $setting->save();

                Session::put('theme_header', "bg-gradient-8");
                Session::put('theme_sidebar', "gradient bg-gradient-8");

                Custom::createAccounts($newuser->id, $newuser, true);
                return redirect()->intended('companies');
            }
        }
        /*
         * if($otp == $newuser->otp) {
         * if(Custom::time_difference($current_time, $otp_time, 'm') > 60) {
         * return redirect()->back()->withErrors(['error', 'Otp time expired!']);
         * } else {
         * $newuser->status = "1";
         * $newuser->save();
         * if(Auth::loginUsingId([$newuser->id])) {
         *
         * $setting = new Setting();
         * $setting->name = 'theme';
         * $setting->data = json_encode(["header" => "bg-gradient-8", "sidebar" => "gradient bg-gradient-8"]);
         * $setting->user_id = Auth::id();
         * $setting->save();
         *
         * Session::put('theme_header', "bg-gradient-8");
         * Session::put('theme_sidebar', "gradient bg-gradient-8");
         *
         *
         * Custom::createAccounts($newuser->id, $newuser, true);
         * return redirect()->intended('companies');
         * }
         * }
         * }
         */
    }

    public function save_personal_user(Request $request)
    {
        $person_data = array(
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'mother_name' => $request->input('mother_name'),
            'father_name' => $request->input('father_name'),
            'dob' => $request->input('dob'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'mobile' => $request->input('mobile_no'),
            'email' => $request->input('email_address'),
            'password' => null
        );

        $personid = Custom::createPerson($person_data, false);

        return $personid;
    }
}
