<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;

class SendOtpEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $email;
    public $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $data, $email)
    {
        $this->data = $data;
        $this->email = $email;
        $this->name = $data['name'];
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

        Mail::send('emails.otp_mail', $this->data, function ($message) use ($to_email, $to_name) {
            $message->from('support@propelsoft.in', 'PropelERP');
            $message->to($to_email, $to_name);
            $message->subject("Propel OTP Code");
        });

    }
}
