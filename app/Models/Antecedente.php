<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Antecedente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'antecedente';
    protected $fillable = ['nombre'];
}
