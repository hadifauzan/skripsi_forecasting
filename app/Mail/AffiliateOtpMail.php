<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AffiliateOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $otp;
    public Carbon $expiresAt;

    /**
     * Create a new message instance.
     *
     * @param string $name User's name
     * @param string $otp OTP code (plaintext)
     * @param Carbon $expiresAt OTP expiration time
     */
    public function __construct(string $name, string $otp, Carbon $expiresAt)
    {
        $this->name = $name;
        $this->otp = $otp;
        $this->expiresAt = $expiresAt;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi OTP - Gentle Living')
                    ->view('emails.affiliate-otp')
                    ->with([
                        'name' => $this->name,
                        'otp' => $this->otp,
                        'expiresAt' => $this->expiresAt,
                        'expiresInMinutes' => $this->expiresAt->diffInMinutes(now()),
                    ]);
    }
}
