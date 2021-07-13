<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Custom;
use App\Notification\Service\SmsNotificationService;
use Mail;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public $email;

    public $name;

    public $mobile;

    public $otp;

    public $add_user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($add_user, $data, $email, $name, $mobile, $otp, SmsNotificationService $SmsNotificationService)
    {
        $this->add_user = $add_user;
        $this->data = $data;
        $this->email = $email;
        $this->name = $name;
        $this->mobile = $mobile;
        $this->otp = $otp;
        $this->SmsNotificationService = $SmsNotificationService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $to_email = $this->email;
        $to_name = $this->name;

        Mail::send('emails.mail_verification', $this->data, function ($message) use ($to_email, $to_name) {
            $message->from('support@propelsoft.in', 'PropelERP');
            $message->to($to_email, $to_name);
            $message->subject("Verify your email address");
        });

        if ($this->add_user == true) {
            if (count(Mail::failures()) > 0) {
                $activation['message'] = config('constants.messages.activation_error');
            } else {
                $activation['message'] = config('constants.messages.activation');
                $mobile = $this->mobile;
                $subject = "Otp Send";
                $message = $this->otp . " " . config('constants.messages.sms_activation');
                $msg = $this->SmsNotificationService->save($mobile, $subject, $to_name, $message, " ", "OTP");

               // Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $this->mobile, $this->otp . " " . config('constants.messages.sms_activation'));
            }
        }
    }
}
