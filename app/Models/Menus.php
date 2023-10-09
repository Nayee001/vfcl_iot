<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    use HasFactory;
    protected $table = 'menus';


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
