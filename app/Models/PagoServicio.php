<?php

namespace App\Models;

use App\Models\Mes;
use App\Models\Usuario;
use App\Models\Servicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PagoServicio extends Model
{
    use HasFactory;

    protected $table = 'pago_servicio';
    protected $fillable = ['monto', 'fecha', 'anio', 'servicio_id', 'mes_id', 'usuario_id'];

    public function servicio()
    {
        return $this->hasOne(Servicio::class, 'id', 'servicio_id');
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
