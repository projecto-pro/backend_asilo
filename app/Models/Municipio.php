<?php

namespace App\Models;

use App\Models\Departamento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Municipio extends Model
{
    use HasFactory;


    protected $table = 'municipio';
    protected $fillable = ['codigo_original', 'codigo', 'nombre', 'departamento_id'];
    protected $appends = ['full_name'];



    public function getFullNameAttribute()
    {
        $departamento = Departamento::find($this->departamento_id)->nombre;
        return str_replace('  ', ' ', "{$departamento}, {$this->nombre}");
    }
}
