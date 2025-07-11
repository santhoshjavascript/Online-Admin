<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
    ];

    protected $casts = [
        'role' => \App\Enums\Role::class,
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'uploaded_by');
    }

    public function isAdmin()
    {
        return $this->is_admin || $this->role === \App\Enums\Role::ADMIN;
    }
}