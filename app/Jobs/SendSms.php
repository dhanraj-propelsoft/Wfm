<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Sms;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $user;
    protected $pass;
    protected $sender;
    protected $phone;
    protected $message;

    public function __construct($user, $pass, $sender, $phone, $message)
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->sender = $sender;
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $text = rawurlencode($this->message);

         $url = 'http://trans.smsfresh.co/api/sendmsg.php?user='.$this->user.'&pass='.$this->pass.'&sender='.$this->sender.'&phone='.$this->phone.'&text='.$text.'&priority=ndnd&stype=normal';

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

        if($message_id != "") {

            $sms = new Sms;
            $sms->user = $this->user;
            $sms->pass = $this->pass;
            $sms->sender = $this->sender;
            $sms->phone = $this->phone;
            $sms->message = $text;
            $sms->priority = 'ndnd';
            $sms->stype = 'normal';
            $sms->message_id = $message_id;
            $sms->save();

        }
    }
}
