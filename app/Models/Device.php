<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "devices";
    protected $fillable = ['name', 'device_type', 'description', 'owner', 'health', 'status', 'created_by'];

    const STATUS = ['Active' => 'Active', 'Inactive' => 'Inactive', 'Maintenance' => 'Maintenance', 'Repair' => 'Repair', 'Lost' => 'Lost'];
    const HEALTH = ['Good' => 'Good', 'Fair' => 'Fair', 'Poor' => 'Poor', 'New' => 'New', 'Critical' => 'Critical'];
}
