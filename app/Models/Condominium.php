<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class Condominium extends Model
{
    use HasFactory, Notifiable;
    
    protected $table = 'condominiums';
    protected $fillable = ['name','address','employees','budget','is_active'];

    
    public function user()
    {
        return $this->hasMany(User::class);
    }
    
    public function task()
    {
        return $this->hasMany(Task::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}
