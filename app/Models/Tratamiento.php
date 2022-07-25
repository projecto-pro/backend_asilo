<?php

namespace App\Models;

use App\Models\Usuario;
use App\Models\Protegido;
use App\Models\EvolucionMedica;
use App\Models\MedicinaPresentacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tratamiento extends Model
{
    use HasFactory;

    protected $table = 'tratamiento';
    protected $fillable = [
        'codigo_protegido', 'cantidad', 'medicamento', 'indicacion', 'precio', 'subtotal',
        'descuento_aplicado', 'descuento', 'total', 'cancelado', 'entregado',
        'protegido_id', 'evolucion_medica_id', 'medicina_presentacion_id', 'usuario_id'
    ];
    protected $casts = ['cancelado' => 'boolean', 'entregado' => 'boolean'];

    public function protegido()
    {
        return $this->hasOne(Protegido::class, 'id', 'protegido_id');
    }

    public function evolucion_medica()
    {
        return $this->hasOne(EvolucionMedica::class, 'id', 'evolucion_medica_id');
    }

    public function medicina_presentacion()
    {
        return $this->hasOne(MedicinaPresentacion::class, 'id', 'medicina_presentacion_id');
    }

    public function usuario()
    {
        return $this->hasMany(Usuario::class, 'id', 'usuario_id');
    }
}
