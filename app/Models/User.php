<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    // Relatie naar rollen
    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    // Check of gebruiker een rol heeft
    public function hasRole($roleName) {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class); // Assumes a user has many vehicles
    }
    
    
}