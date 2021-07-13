<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendForgotPasswordEmail;
use App\PersonCommunicationAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendSms;
use App\Notification\Service\SmsNotificationService;
use Carbon\Carbon;
use App\Custom;
use App\Person;
use App\User;
use Session;
use Hash;
use Auth;
use Mail;
use DB;

class ForgotPasswordController extends Controller
{

    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
    }

    /*
     * |--------------------------------------------------------------------------
     * | Password Reset Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller is responsible for handling password reset emails and
     * | includes a trait which assists in sending these notifications from
     * | your application to your users. Feel free to explore this trait.
     * |
     */
    public function reset_password()
    {
        Log::info('fORGETPASSWORDcONTROLLER->reset_password:-Inside');
        return view('auth.passwords.email');
    }

    public function store_password(Request $request)
    {
        Log::info('fORGETPASSWORDcONTROLLER->sTOREpassword:-Inside');
        $user = "";

        $reset = array();

        /*
         * if($request->input('email') != "") {
         * $user = User::where('email', $request->input('email'))->first();
         * } else if($request->input('mobile') != "") {
         * $user = User::where('mobile', $request->input('mobile'))->first();
         * }
         */

        $mobile_email = $request->input('mobile');

        if ($request->input('mobile') != "") {
            $users = User::select('users.*');
            $users->where(function ($query) use ($mobile_email) {
                $query->where('mobile', $mobile_email)
                    ->orWhere('email', $mobile_email);
            });
            $user = $users->first();
        }

        if ($user != "") {
            $user->otp = Custom::otp(4);
            $user->otp_sent += 1;
            $user->save();

            $user_id = $user->id;

            $get_token = DB::table('user_activations')->select('token')
                ->where('user_id', $user_id)
                ->first();

            $token = $get_token->token;

            $person = Person::findOrFail($user->person_id);
            $person_address = PersonCommunicationAddress::where('person_id', $user->person_id)->first();

            $email = $person_address->email_address;
            $mobile = $person_address->mobile_no;
            $name = $person->first_name;
            $subject = " Forget Password";
            $sms_content = "$user->otp is your OTP to verify your account on PROPEL ERP";

            $msg = $this->SmsNotificationService->save($mobile, $subject, $name, $sms_content, " ", "OTP");

            /*
             * $this->dispatch(new SendForgotPasswordEmail(['name' => $name, 'otp' => $user->otp], $email));
             *
             * $this->dispatch(new SendSms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $mobile, "$user->otp is your OTP to verify your account on PROPEL ERP"));
             */

            // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $mobile, "$user->otp is your OTP to verify your account on PROPEL ERP");

            $to_email = $email;
            $to_name = $name;
            $data = [
                'name' => $name,
                'otp' => $user->otp
            ];

            /*
             * Mail::send('emails.mail_reset', $data, function ($message) use ($to_email, $to_name) {
             * $message->from('support@propelsoft.in', 'PropelERP');
             * $message->to($to_email, $to_name);
             * $message->subject("Propel reset password!");
             * });
             */

            Session::flash('flash_message', 'Otp has been sent to your mobile and email ID!');
            return view('auth.otp_activation', compact('user_id', 'reset', 'token'));
        } else {
            foreach ($request->all() as $key => $value) {
                if ($value != "" && $key != "_token") {
                    return redirect()->back()->withErrors([
                        ucfirst($key) . " does not exist"
                    ]);
                }
            }
        }
    }

    public function reset_login(Request $request)
    {
        $reset = array();
        $token = $request->input('token');

        $user = User::findOrFail($request->input('user_id'));
        $user_id = $request->input('user_id');
        if ($user->otp == $request->input('otp')) {
            if (Auth::loginUsingId([
                $user->id
            ])) {
                return redirect()->intended('companies');
            }
        } else {
            $otperror = "OTP is incorrect!";
            return view('auth.otp_activation', compact('user_id', 'otperror', 'reset', 'token'));
        }
    }
}
