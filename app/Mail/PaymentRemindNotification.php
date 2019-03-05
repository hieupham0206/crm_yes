<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentRemindNotification extends Mailable
{
    use Queueable, SerializesModels;
    private $params;

    /**
     * Create a new message instance.
     *
     * @param $params
     */
    public function __construct($params)
    {
        //
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Thông báo nhắc nhở thanh toán')
            ->view('emails.payment_remind_notification')
            ->with(['params' => $this->params]);
    }
}
