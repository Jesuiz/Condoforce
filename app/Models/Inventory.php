<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    
    protected $table = 'inventory';

    protected $fillable = ['name','description','category','units','amount','expiration','user_id','condominium_id'];
    public static $categories = ['Mantenimiento', 'Jardinería', 'Iluminación', 'Limpieza', 'Seguridad', 'Suministros', 'Mobiliario', 'Tecnología', 'Materiales'];

    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            if (!$inventory->category || !in_array($inventory->category, self::$categories)) {
                throw new \InvalidArgumentException("Invalid category: {$inventory->category}");
            }
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
