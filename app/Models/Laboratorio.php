<?php

namespace App\Models;

use App\Models\Examen;
use App\Models\Usuario;
use App\Models\Protegido;
use App\Models\EvolucionMedica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laboratorio extends Model
{
    use HasFactory;

    protected $table = 'laboratorio';
    protected $fillable = [
        'codigo_protegido', 'examen', 'precio', 'subtotal', 'descuento_aplicado', 'descuento',
        'total', 'cancelado', 'realizado',
        'protegido_id', 'evolucion_medica_id', 'examen_id', 'usuario_id'
    ];
    protected $casts = ['cancelado' => 'boolean', 'realizado' => 'boolean'];

    public function protegido()
    {
        return $this->hasOne(Protegido::class, 'id', 'protegido_id');
    }

    public function evolucion_medica()
    {
        return $this->hasOne(EvolucionMedica::class, 'id', 'evolucion_medica_id');
    }

    public function examen()
    {
        return $this->hasOne(Examen::class, 'id', 'examen_id');
    }

    public function usuario()
    {
        return $this->hasMany(Usuario::class, 'id', 'usuario_id');
    }

    public function examen_realizado()
    {
        return $this->hasOne(ExamenRealizado::class, 'laboratorio_id', 'id');
    }
}
