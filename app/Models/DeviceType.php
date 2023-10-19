<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    const STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' => 2,
    ];
}
