<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * @param array{name:string,email:string,phone:?string,message:string,ip:?string,ua:?string,submitted_at:string} $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->to(config('services.contact.to_email'))
            ->replyTo($this->data['email'] ?? config('services.contact.to_email'), $this->data['name'] ?? null)
            ->subject('Nieuwe contactaanvraag via de website')
            ->markdown('mail.contact-submitted', ['data' => $this->data]);
    }
}