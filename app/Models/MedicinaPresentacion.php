<?php

namespace App\Models;

use App\Models\Medicina;
use App\Models\Presentacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicinaPresentacion extends Model
{
    use HasFactory;

    protected $table = 'medicina_presentacion';
    protected $fillable = ['nombre_medicina', 'nombre_presentacion', 'precio', 'medicina_id', 'presentacion_id'];
    protected $appends = ["full_name"];

    public function getFullNameAttribute()
    {
        return "{$this->nombre_presentacion} - {$this->nombre_medicina}";
    }

    public function medicina()
    {
        return $this->hasOne(Medicina::class, 'id', 'medicina_id');
    }

    public function presentacion()
    {
        return $this->hasOne(Presentacion::class, 'id', 'presentacion_id');
    }
}
