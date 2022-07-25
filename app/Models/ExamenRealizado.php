<?php

namespace App\Models;

use App\Models\Usuario;
use App\Models\Laboratorio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamenRealizado extends Model
{
    use HasFactory;

    protected $table = 'examen_realizado';
    protected $fillable = [
        'examen', 'documento',
        'laboratorio_id', 'usuario_id'
    ];
    public $timestamps = false;

    public function laboratorio()
    {
        return $this->hasOne(Laboratorio::class, 'id', 'laboratorio_id');
    }

    public function usuario()
    {
        return $this->hasMany(Usuario::class, 'id', 'usuario_id');
    }
}
