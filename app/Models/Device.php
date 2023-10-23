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

    /**
     * This function establishes an inverse one-to-many (belongsTo) relationship
     * with the DeviceType model, using the 'device_type' foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *         Returns an instance of the belongsTo relation.
     */
    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class, 'device_type');
    }

    /**
     * This function establishes an inverse one-to-many (belongsTo) relationship
     * with the User model, using the 'owner' foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *         Returns an instance of the belongsTo relation.
     */
    public function deviceOwner()
    {
        return $this->belongsTo(User::class, 'owner')->select(['id', 'fname', 'lname']);
    }

    /**
     * This function establishes an inverse one-to-many (belongsTo) relationship
     * with the User model, using the 'created_by' foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *         Returns an instance of the belongsTo relation.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'fname', 'lname']);
    }
}
