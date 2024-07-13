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
    protected $fillable = ['name', 'salary', 'user_id', 'condominium_id'];
    public static $areas = ['Residente', 'Vigilante', 'Mantenimiento', 'Supervisor', 'Delegado', 'Administrador', 'Gerente'];

    
    /* public static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (!$task->area || !in_array($task->area, self::$areas)) {
                throw new \InvalidArgumentException("Invalid area: {$task->area}");
            }
        });
    } */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
