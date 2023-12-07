<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceData extends Model
{
    use HasFactory;
    protected $table = 'device_data';

    protected $fillable = [
        'id',
        'device_id',
        'topic',
        'device_status',
        'health_status',
        'timestamp',
        'created_at',
        'updated_at'
    ];
}
