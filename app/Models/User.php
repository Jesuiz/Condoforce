<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'users';
    protected $fillable = ['name','email','password','country','doc_type','document','cellphone','address','condominium_id','profile_img'];
    protected $hidden = ['password','remember_token',];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }
    
    public function role()
    {
        return $this->hasOne(Role::class);
    }
}