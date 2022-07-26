<?php

namespace App\Models;

use App\Models\Medico;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Protegido;
use App\Models\IngresoAsilo;
use App\Models\SolicitudMedica;
use App\Models\FichaMedicaAntecedente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FichaMedica extends Model
{
    use HasFactory;

    protected $table = 'ficha_medica';
    protected $fillable = [
        'expediente', 'fecha_ingreso', 'hora_ingreso', 'fecha_nacimiento', 'cui',
        'nombre_completo', 'genero', 'direccion', 'telefono', 'alergia',
        'protegido_id', 'ingreso_asilo_id', 'contacto_id', 'solicitud_medica_id',
        'medico_tratante_id', 'usuario_id'
    ];
    protected $casts = ['fecha_ingreso' => 'date:d-m-Y', 'hora_ingreso' => 'date:H:i:s', 'fecha_nacimiento' => 'date:d-m-Y'];

    public function protegido()
    {
        return $this->hasOne(Protegido::class, 'id', 'protegido_id');
    }

    public function ingreso_asilo()
    {
        return $this->hasOne(IngresoAsilo::class, 'id', 'ingreso_asilo_id');
    }

    public function contacto()
    {
        return $this->hasOne(Persona::class, 'id', 'contacto_id');
    }

    public function solicitud_medica()
    {
        return $this->hasOne(SolicitudMedica::class, 'id', 'solicitud_medica_id');
    }

    public function medico_tratante()
    {
        return $this->hasOne(Medico::class, 'id', 'medico_tratante_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }

    public function antecedentes()
    {
        return $this->hasMany(FichaMedicaAntecedente::class, 'ficha_medica_id', 'id');
    }
}
