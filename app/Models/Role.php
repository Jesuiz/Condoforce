<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = ['name', 'salary'];
    public static $areas = ['Residente', 'Vigilante', 'Mantenimiento', 'Supervisor', 'Delegado', 'Administrador', 'Gerente'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
