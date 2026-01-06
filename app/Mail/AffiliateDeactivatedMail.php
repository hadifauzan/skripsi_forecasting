<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AffiliateDeactivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $reason;

    public function __construct($name, $reason = null)
    {
        $this->name = $name;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Akun Affiliate Anda Telah Dinonaktifkan - Gentle Living')
                    ->view('emails.affiliate-deactivated');
    }
}
