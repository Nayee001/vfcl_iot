<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Device;
use App\Models\User;

class DeviceVerificationNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected  $device;
    protected  $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Device $device, User $user)
    {
        $this->device = $device;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Device Verification Notification',
        );
    }

    public function build()
    {
        $verifyUrl = url('/devices');
        return $this->subject('Device Verification Notification!')
            ->view('emails.deviceVerification')
            ->with([
                'name' => $this->user->fname,
                'verifyUrl' => $verifyUrl
            ]);
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
