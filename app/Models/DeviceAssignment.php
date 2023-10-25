<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAssignment extends Model
{
    use HasFactory;
    protected $table = "device_assignments";
    protected $fillable = ['device_id', 'assign_to', 'assign_by', 'location', 'created_at', 'updated_at'];
}
