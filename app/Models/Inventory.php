<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    
    protected $table = 'inventory';

    protected $fillable = [
        'name',
        'description',
        'units',
        'amount',
        'expiration',
        'user_id',
        'condominium_id',
        'category_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
