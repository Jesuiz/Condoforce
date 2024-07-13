<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $table = 'tasks';
    protected $fillable = ['name','description','area','status','time_limit','reason','user_id','condominium_id'];
    public static $areas = ['Residente', 'Vigilancia', 'Mantenimiento', 'Supervisión', 'Delegación', 'Administración', 'Gerencia'];
    public static $statuses = ['Asignado', 'En Desarrollo', 'Finalizado', 'Fallido'];

    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (!in_array($task->area, self::$areas)) {
                throw new \InvalidArgumentException("Invalid area: {$task->area}");
            }

            if (!in_array($task->status, self::$statuses)) {
                throw new \InvalidArgumentException("Invalid status: {$task->status}");
            }
        });
    }
    
    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
