<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;

class SendEmployeeAddedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $email;
    public $name;
    public $business_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $email, $business_name)
    {
        $this->data = $data;
        $this->email = $email;
        $this->name = $data['name'];
        $this->business_name = $business_name;
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
        $business = $this->business_name;

        Mail::send('emails.employee_added_mail', $this->data, function ($message) use ($to_email, $to_name, $business) {
            $message->from('support@propelsoft.in', 'PropelERP');
            $message->to($to_email, $to_name);
            $message->subject($business." added you as an Employee!");
        });
    }
}
