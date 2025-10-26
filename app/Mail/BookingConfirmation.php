<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * @param array{
     *   type:string,
     *   start_at:string,
     *   end_at:string,
     *   name:string,
     *   email:string,
     *   phone:?string
     * } $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->to($this->data['email'], $this->data['name'] ?? null)
            ->replyTo(config('services.contact.to_email'), config('mail.from.name'))
            ->subject('Bevestiging van je reservering')
            ->markdown('mail.booking-confirmation', ['data' => $this->data]);
    }
}