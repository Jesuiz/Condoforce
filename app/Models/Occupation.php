<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class Occupation extends Model
{
    use HasFactory;

    protected $table = 'occupation';
    protected $fillable = ['name', 'salary'];
    public static $areas = ['Residente', 'Delegado', 'Vigilante', 'Supervisor', 'Mantenimiento', 'Administrador', 'Gerente'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}