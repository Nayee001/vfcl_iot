<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    use HasFactory;
    protected $table = 'menus';
    // common user status
    const STATUS = [
        'ISPUBLISHED' => 1,
        'ISNOTPUBLISHED' => 2,
    ];
    protected $fillable = [
        'permission_id',
        'submenu',
        'title',
        'parent_menu_id',
        'link',
        'sort',
        'target',
        'status',
        'icon',
        'menu_type',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
