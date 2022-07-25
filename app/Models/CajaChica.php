<?php

namespace App\Models;

use App\Models\Mes;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CajaChica extends Model
{
    use HasFactory;

    protected $table = 'caja_chica';
    protected $fillable = ['monto', 'descripcion', 'fecha_hora', 'anio', 'mes_id', 'usuario_id'];

    public function mes()
    {
        return $this->hasOne(Mes::class, 'id', 'mes_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }
}
