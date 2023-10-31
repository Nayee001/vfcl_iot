<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = "locations";
    protected $fillable = ['user_id', 'latitude', 'longitude', 'location_type', 'address', 'city', 'state','country','postal_code'];

    const LOCATIONTYPE = [
        'home' => 'Home',
        'work' => 'Work',
    ];
}
