<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAssignment extends Model
{
    use HasFactory;
    protected $table = "device_assignments";
    protected $fillable = ['device_id', 'assign_to', 'assign_by', 'location_id', 'created_at', 'updated_at'];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assign_to')->select(['id', 'fname', 'lname']);
    }

    public function deviceLocation()
    {
        return $this->belongsTo(LocationName::class, 'location_id');
    }
}
