<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceVerification extends Model
{
    use HasFactory;
    protected $table = "device_verifications";
    protected $fillable = ['device_id', 'status', 'encryption_key', 'created_at', 'updated_at'];
}
