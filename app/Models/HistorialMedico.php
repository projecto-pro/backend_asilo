<?php

namespace App\Models;

use App\Models\Medico;
use App\Models\FichaMedica;
use App\Models\ConsultaMedica;
use App\Models\EvolucionMedica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialMedico extends Model
{
    use HasFactory;

    protected $table = 'historial_medico';
    protected $fillable = [
        'correlativo', 'fecha_hora_inicio', 'fecha_hora_finaliza', 'encargado', 'diagnostico', 'concluida',
        'ficha_medica_id', 'consulta_medica_id', 'medico_id'
    ];
    protected $casts = ['concluida' => 'boolean', 'fecha_hora_inicio' => 'datetime:d-m-Y H:i:s', 'fecha_hora_finaliza' => 'datetime:d-m-Y H:i:s'];
    public $timestamps = false;

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

    public function evoluciones()
    {
        return $this->hasMany(EvolucionMedica::class, 'historial_medico_id', 'id');
    }
}
