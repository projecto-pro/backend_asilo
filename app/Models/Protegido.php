<?php

namespace App\Models;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Protegido extends Model
{
    use HasFactory;

    protected $table = 'protegido';
    protected $fillable = ['codigo', 'activo', 'persona_id', 'usuario_id'];
    protected $casts = ['activo' => 'boolean'];

    public function persona()
    {
        return $this->hasOne(Persona::class, 'id', 'persona_id');
    }
}
