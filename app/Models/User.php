<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    
    protected $table = 'users';
    protected $fillable = ['name','email','password','country','doc_type','document','cellphone','address','profile_img','condominium_id','occupation_id','is_active'];
    protected $hidden = ['password','remember_token',];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->occupation_id) {
                $user->occupation_id = Occupation::where('name', 'Residente')->first()->id;
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
    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }
}