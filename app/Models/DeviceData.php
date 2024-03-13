<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Device;

class DeviceData extends Model
{
    use HasFactory;
    protected $table = 'device_data';

    protected $fillable = [
        'device_id',
        'fault_status',
        'topic',
        'device_status',
        'health_status',
        'device_timestamps',
        'valts',
        'timestamp',
    ];


    /**
     * This function establishes an inverse one-to-many (belongsTo) relationship
     * with the DeviceType model, using the 'Device' foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *         Returns an instance of the belongsTo relation.
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
