<?php

namespace App\Models;

use App\Models\Mes;
use App\Models\Medico;
use App\Models\Usuario;
use App\Models\SolicitudMedica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsultaMedica extends Model
{
    use HasFactory;

    protected $table = 'consulta_medica';
    protected $fillable = [
        'fecha_hora', 'hora', 'subtotal',
        'descuento', 'total', 'pagado', 'solicitud_medica_id', 'medico_id',
        'usuario_id', 'mes_id', 'atendido', 'anulado'
    ];
    protected $casts = [
        'pagado' => 'boolean', 'atendido' => 'boolean', 'anulado' => 'boolean',
        'fecha_hora' => 'date:d-m-Y', 'hora' => 'date:H:i:s'
    ];

    public function solicitud_medica()
    {
        return $this->hasOne(SolicitudMedica::class, 'id', 'solicitud_medica_id');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'id', 'medico_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }

    public function mes()
    {
        return $this->hasOne(Mes::class, 'id', 'mes_id');
    }
}
