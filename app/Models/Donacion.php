<?php

namespace App\Models;

use App\Models\Mes;
use App\Models\Entidad;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donacion extends Model
{
    use HasFactory;

    protected $table = 'donacion';
    protected $fillable = ['monto', 'fecha', 'anio', 'entidad_id', 'mes_id', 'usuario_id'];
    public $timestamps = false;

    public function entidad()
    {
        return $this->hasOne(Entidad::class, 'id', 'entidad_id');
    }

    public function mes()
    {
        return $this->hasOne(Mes::class, 'id', 'mes_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }
}
