<?php

namespace App\Models;

use App\Models\Municipio;
use App\Models\Departamento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persona extends Model
{
    use HasFactory, SoftDeletes;

    const GENERO = ["Masculino", "Femenino"];

    protected $table = "persona";
    protected $fillable = [
        "cui", "primer_nombre", "segundo_nombre",
        "primer_apellido", "segundo_apellido", "foto",
        "email", "ubicacion", "telefono", "genero",
        "departamento_id", "municipio_id"
    ];
    protected $hidden = ["foto"];
    protected $appends = ["full_name", "picture", "fotografia"];

    public function getFullNameAttribute()
    {
        return str_replace('  ', ' ', "{$this->primer_nombre} {$this->segundo_nombre} {$this->primer_apellido} {$this->segundo_apellido}");
    }

    public function getPictureAttribute()
    {
        $path = null;

        if (Storage::disk('persona')->exists($this->foto)) {
            $path = Storage::disk('persona')->url($this->foto);
        } else {
            $path = $this->genero == Persona::GENERO[0] ? Storage::disk('default')->url("hombre.png") : Storage::disk('default')->url("mujer.png");
        }

        return $path;
    }

    public function getFotografiaAttribute()
    {
        return Storage::disk('persona')->exists($this->foto) ? Storage::disk('persona')->url($this->foto) : Storage::disk('default')->url("usuario.png");
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function scopeTodos($query)
    {
        return $query->with('departamento', 'municipio')->withTrashed()->where('id', '<>', 1)->orderByDesc('id');
    }

    public function departamento()
    {
        return $this->hasOne(Departamento::class, 'id', 'departamento_id');
    }

    public function municipio()
    {
        return $this->hasOne(Municipio::class, 'id', 'municipio_id');
    }
}
