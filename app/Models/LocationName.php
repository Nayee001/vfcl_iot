<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationName extends Model
{
    use HasFactory;
    protected $table = "location_names";
    protected $fillable = ['user_id', 'device_id', 'location_id', 'location_name'];
}
