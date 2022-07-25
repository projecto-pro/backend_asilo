<?php

namespace App\Models;

use App\Models\RolMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rol';
    protected $fillable = ['nombre'];

    public function roles_menus()
    {
        return $this->hasMany(RolMenu::class, 'rol_id', 'id')->orderBy('menu_id');
    }
}
