<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    use HasFactory;
    protected $table = 'device_types';

    protected $fillable = [
        'device_type',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];
}
