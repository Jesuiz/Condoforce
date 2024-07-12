<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    
    protected $table = 'reports';
    protected $fillable = ['name','description','area','user_id','condominium_id'];
    public static $areas = ['Residente', 'Vigilancia', 'Mantenimiento', 'Supervisión', 'Delegación', 'Administración', 'Gerencia'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (!$task->area || !in_array($task->area, self::$areas)) {
                throw new \InvalidArgumentException("Invalid area: {$task->area}");
            }
        });
    }
    
    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }
}
