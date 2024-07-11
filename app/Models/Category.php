<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $fillable = [
        'name',
        'description',
        'area'
    ];
    
    public static $areas = ['Residente', 'Vigilancia', 'Mantenimiento', 'Supervisión', 'Delegación', 'Administración', 'Gerencia'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (!in_array($task->area, self::$areas)) {
                throw new \InvalidArgumentException("Invalid area: {$task->area}");
            }
        });
    }
}
