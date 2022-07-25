<?php

namespace App\Models;

use App\Models\Mes;
use App\Models\Usuario;
use App\Models\IngresoAsilo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mensualidad extends Model
{
    use HasFactory;

    protected $table = 'mensualidad';
    protected $fillable = ['monto', 'pagado', 'fecha_pago', 'anio', 'ingreso_asilo_id', 'mes_id', 'usuario_id'];
    protected $casts = ['pagado' => 'boolean'];
    public $timestamps = false;

    public function ingreso_asilo()
    {
        return $this->hasOne(IngresoAsilo::class, 'id', 'ingreso_asilo_id');
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
