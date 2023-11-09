<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LocationName;

class Location extends Model
{
    use HasFactory;
    protected $table = "locations";
    protected $fillable = ['user_id', 'device_id', 'latitude', 'longitude', 'address', 'city', 'state', 'country', 'postal_code'];

    public function locationsNames()
    {
        return $this->hasMany(LocationName::class, 'location_id', 'id')->select('id','location_id','location_name');
    }
}
