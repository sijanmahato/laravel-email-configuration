<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TemplatedEmail extends Mailable
{
    public function __construct(
        public string $resolvedSubject,
        public string $resolvedHtml,
        public ?string $resolvedText = null
    ) {
    }

    public function build(): static
    {
        $mail = $this->subject($this->resolvedSubject)
            ->view('emails.raw-html', ['html' => $this->resolvedHtml]);

        if ($this->resolvedText !== null && $this->resolvedText !== '') {
            $mail->text('emails.raw-text', ['text' => $this->resolvedText]);
        }

        return $mail;
    }
}
