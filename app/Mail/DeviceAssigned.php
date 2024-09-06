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

class DeviceAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $device;
    public $user;

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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.device_assigned')
            ->subject('Device Assigned to You')
            ->with([
                'deviceName' => $this->device->name,
                'ApiKey' => $this->device->short_apikey,
                'userName' => $this->user->name,
            ]);
    }
}
