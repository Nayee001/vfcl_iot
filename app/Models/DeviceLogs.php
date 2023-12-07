<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceLogs extends Model
{
    use HasFactory;
    protected $table = 'device_logs';

    protected $fillable = [
        'device_id',
        'json_response',
    ];
}
