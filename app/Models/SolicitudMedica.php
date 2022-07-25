<?php

namespace App\Models;

use App\Models\Usuario;
use App\Models\Protegido;
use App\Models\IngresoAsilo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudMedica extends Model
{
    use HasFactory;

    protected $table = 'solicitud_medica';
    protected $fillable = ['fecha', 'motivo', 'ingreso_asilo_id', 'protegido_id', 'usuario_id'];
    protected $casts = ['asignado' => 'boolean', 'fecha' => 'date:d-m-Y'];

    public function ingreso_asilo()
    {
        return $this->hasOne(IngresoAsilo::class, 'id', 'ingreso_asilo_id');
    }

    public function protegido()
    {
        return $this->hasOne(Protegido::class, 'id', 'protegido_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }
}
