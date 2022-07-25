<?php

namespace App\Models;

use App\Models\Medico;
use App\Models\Especialidad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicoEspecialidad extends Model
{
    use HasFactory;

    protected $table = 'medico_especialidad';
    protected $fillable = ['medico_id', 'especialidad_id'];

    public function medico()
    {
        return $this->hasOne(Medico::class, 'id', 'medico_id');
    }

    public function especialidad()
    {
        return $this->hasOne(Especialidad::class, 'id', 'especialidad_id');
    }
}
