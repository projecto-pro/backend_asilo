<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;


    protected $table = 'menu';
    protected $fillable = ['nombre', 'nombre_ruta', 'padre', 'mostrar', 'icono'];
    protected $casts = ['mostrar' => 'boolean'];
    protected $appends = ['principal'];



    public function getPrincipalAttribute()
    {
        $menu = Menu::find($this->padre);
        return !is_null($menu) ? $menu->id : 0;
    }
}
