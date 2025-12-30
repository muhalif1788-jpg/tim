<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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

    // Kolom yang akan dienkripsi otomatis
    protected $encryptable = [
        'phone',
        'address'
    ];

    // Enkripsi email secara manual karena digunakan untuk login
    protected $appends = ['email_masked'];

    public function setAttribute($key, $value)
    {
        
        if (in_array($key, $this->encryptable) && !is_null($value)) {
            $value = Crypt::encryptString($value);
        }
        
        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable) && !is_null($value)) {
            try {
                $value = Crypt::decryptString($value);
            } catch (DecryptException $e) {
                $value = null;
            }
        }

        return $value;
    }


    public function scopeSearchEncrypted($query, $field, $searchValue)
    {

        $encryptedValue = Crypt::encryptString($searchValue);
        return $query->where($field, $encryptedValue);
    }

    public function getEmailMaskedAttribute()
    {
        $email = $this->attributes['email'] ?? '';
        if (empty($email)) return '';
        
        list($local, $domain) = explode('@', $email);
        $localLength = strlen($local);
        $maskedLocal = substr($local, 0, 1) . str_repeat('*', max(1, $localLength - 2)) . substr($local, -1);
        
        return $maskedLocal . '@' . $domain;
    }

    public function getEmailForLogin()
    {
        return $this->attributes['email'] ?? null;
    }

  
    public function getEmailAttribute($value)
    {
        return $value;
    }

    public function setPasswordAttribute($value)
    {
        if (!$value) {
            return;
        }

        if (Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

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
        // Pencarian hanya di kolom non-encrypted
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    // Untuk phone/address encrypted, gunakan scope khusus
                    ->orWhere(function($q) use ($search) {
                        $users = User::all();
                        foreach ($users as $user) {
                            if (str_contains(strtolower($user->phone), strtolower($search)) || 
                                str_contains(strtolower($user->address), strtolower($search))) {
                                $q->orWhere('id', $user->id);
                            }
                        }
                    });
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