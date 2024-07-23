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
    protected $fillable = ['name','email','password','country','doc_type','document','cellphone','address','profile_img','condominium_id','role_id','is_active'];
    protected $hidden = ['password','remember_token',];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->role_id) {
                $user->role_id = Role::where('name', 'Residente')->first()->id;
            }
        });
    }

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
        return $this->belongsTo(Role::class);
    }
}