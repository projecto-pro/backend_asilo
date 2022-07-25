<?php

namespace App\Models;

use App\Models\Rol;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolMenu extends Model
{
    use HasFactory;


    protected $table = 'rol_menu';
    protected $fillable = ['rol_id', 'menu_id'];



    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
