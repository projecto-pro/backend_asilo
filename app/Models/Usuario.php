<?php

namespace App\Models;

use App\Models\Persona;
use App\Models\UsuarioRol;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    const SISTEMA = ['asilo', 'farmacia', 'laboratorio'];
    const USUARIO_ADMINISTRADOR = 'ADMINISTRADOR';
    const USUARIO_REGULAR = 'REGULAR';

    protected $table = 'usuario';
    protected $fillable = [
        'cui', 'password', 'admin',
        'sistema', 'persona_id'
    ];
    protected $hidden = ['password'];

    public function findForPassport($username)
    {
        return $this->where('cui', $username)->first();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function scopeTodos($query)
    {
        return $query->with('roles.rol', 'empleado.municipio')->withTrashed()->where('admin', '<>', $this->USUARIO_ADMINISTRADOR)->orderByDesc('id');
    }

    public function empleado()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(UsuarioRol::class, 'usuario_id', 'id');
    }
}
