<?php

namespace App\Models;

use App\Models\Usuario;
use App\Models\Municipio;
use App\Models\Departamento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClienteProveedor extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'cliente_proveedor';
    protected $fillable = ['nit', 'nombre', 'telefonos', 'emails', 'direcciones', 'departamento_id', 'municipio_id', 'usuario_id'];

    public function setNitAttribute($value)
    {
        $this->attributes['nit'] = str_replace([" ", "-"], ["", ""], $value);
    }

    public function scopeTodos($query)
    {
        return $query->with('departamento', 'municipio')->withTrashed()->orderByDesc('id');
    }

    public function departamento()
    {
        return $this->hasOne(Departamento::class, 'id', 'departamento_id');
    }

    public function municipio()
    {
        return $this->hasOne(Municipio::class, 'id', 'municipio_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }
}
