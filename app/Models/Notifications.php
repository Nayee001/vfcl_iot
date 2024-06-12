<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "notifications";

    protected $fillable = ['device_id', 'user_id', 'notification', 'read','created_at','updated_at'];
    const READ = ['Yes' => 'Yes', 'No' => 'No'];

    const DefaultNotiMessages = [
        'deviceVerification' => "Please confirm the device to proceed further",
    ];
}
