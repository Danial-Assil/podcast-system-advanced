<?php
namespace App\Services\Shared;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class MailService
{
    public function sendRaw(string $to, string $subject, string $message): void
    {
        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to)->subject($subject);
        });
    }

    public function sendMailable(string $to, Mailable $mailable): void
    {
        Mail::to($to)->send($mailable);
    }
}
