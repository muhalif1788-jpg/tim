<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Scope untuk filter role
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeCustomer($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Accessor untuk badge role
    public function getRoleBadgeAttribute()
    {
        $badges = [
            'admin' => 'bg-red-100 text-red-800',
            'customer' => 'bg-green-100 text-green-800'
        ];
        
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . ($badges[$this->role] ?? 'bg-gray-100 text-gray-800') . '">' 
               . ucfirst($this->role) . '</span>';
    }
}