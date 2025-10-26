<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * @param array{
     *   type:string,
     *   start_at:string,
     *   end_at:string,
     *   name:string,
     *   phone:string|null,
     *   email:string
     * } $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $subject = 'Nieuwe reservering: ' . ucfirst($this->data['type']) .
                   ' (' . $this->data['start_at'] . ' → ' . $this->data['end_at'] . ')';

        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->to(config('services.contact.to_email'))
            ->replyTo($this->data['email'], $this->data['name'] ?? null)
            ->subject($subject)
            ->markdown('mail.booking-submitted', ['data' => $this->data]);
    }
}