<?php

namespace App\Models;

use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Protegido;
use App\Models\TipoIngreso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IngresoAsilo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ingreso_asilo';
    protected $fillable = [
        'correlativo_anual', 'fecha_ingreso', 'fecha_egreso', 'descuento',
        'protegido_id', 'responsable_id', 'tipo_ingreso_id', 'usuario_id'
    ];

    public function scopeTodos($query)
    {
        return $query->with('protegido.persona.municipio', 'responsable.municipio', 'tipo_ingreso')->orderByDesc('ingreso_asilo.id');
    }

    public function protegido()
    {
        return $this->hasOne(Protegido::class, 'id', 'protegido_id');
    }

    public function responsable()
    {
        return $this->hasOne(Persona::class, 'id', 'responsable_id');
    }

    public function tipo_ingreso()
    {
        return $this->hasOne(TipoIngreso::class, 'id', 'tipo_ingreso_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }
}
