<?php

namespace App\Models;

use App\Models\MedicinaPresentacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicina extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medicina';
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'foto', 'usuario_id'];
    protected $hidden = ['foto'];
    protected $appends = ['picture'];

    public function getPictureAttribute()
    {
        return Storage::disk('medicina')->exists($this->foto) ? Storage::disk('medicina')->url($this->foto) : Storage::disk('default')->url("medicina.png");
    }

    public function precios()
    {
        return $this->hasMany(MedicinaPresentacion::class, 'medicina_id', 'id');
    }
}
