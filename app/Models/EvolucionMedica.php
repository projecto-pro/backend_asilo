<?php

namespace App\Models;

use App\Models\Medico;
use App\Models\FichaMedica;
use App\Models\Laboratorio;
use App\Models\Tratamiento;
use App\Models\ConsultaMedica;
use App\Models\HistorialMedico;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvolucionMedica extends Model
{
    use HasFactory;

    protected $table = 'evolucion_medica';
    protected $fillable = [
        'correlativo', 'fecha_hora', 'encargado', 'evolucion',
        'historial_medico_id', 'ficha_medica_id', 'consulta_medica_id', 'medico_id'
    ];
    protected $casts = ['fecha_hora' => 'datetime:d-m-Y H:i:s'];
    public $timestamps = false;

    public function historial_medico()
    {
        return $this->hasOne(HistorialMedico::class, 'id', 'historial_medico_id');
    }

    public function ficha_medica()
    {
        return $this->hasOne(FichaMedica::class, 'id', 'ficha_medica_id');
    }

    public function consulta_medica()
    {
        return $this->hasOne(ConsultaMedica::class, 'id', 'consulta_medica_id');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'id', 'medico_id');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'evolucion_medica_id', 'id');
    }

    public function laboratorios()
    {
        return $this->hasMany(Laboratorio::class, 'evolucion_medica_id', 'id');
    }
}
