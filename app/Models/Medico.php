<?php

namespace App\Models;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medico';
    protected $fillable = ['colegiado', 'foto', 'email', 'telefono', 'activo', 'persona_id'];
    protected $casts = ['activo' => 'boolean'];
    protected $hidden = ['foto'];
    protected $appends = ['picture'];

    public function getPictureAttribute()
    {
        return Storage::disk('medico')->exists($this->foto) ? Storage::disk('medico')->url($this->foto) : Storage::disk('default')->url("medico.png");
    }

    public function persona()
    {
        return $this->hasOne(Persona::class, 'id', 'persona_id');
    }

    public function especialidades()
    {
        return $this->hasMany(MedicoEspecialidad::class, 'medico_id', 'id');
    }
}
