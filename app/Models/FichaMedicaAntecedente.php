<?php

namespace App\Models;

use App\Models\Antecedente;
use App\Models\FichaMedica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FichaMedicaAntecedente extends Model
{
    use HasFactory;

    protected $table = 'ficha_medica_antecedente';
    protected $fillable = [
        'ficha_medica_id', 'antecedente_id'
    ];
    public $timestamps = false;

    public function ficha_medica()
    {
        return $this->hasOne(FichaMedica::class, 'id', 'ficha_medica_id');
    }

    public function antecedente()
    {
        return $this->hasOne(Antecedente::class, 'id', 'antecedente_id');
    }
}
