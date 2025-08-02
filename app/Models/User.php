<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // 'admin' ou 'customer'
        'status',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
    ];

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_type == 'admin';
    }

    /**
     * Check if user is customer
     *
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->user_type == 'customer';
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == 1;
    }

    /**
     * Get budgets for the customer.
     */
    public function customerBudgets()
    {
        return $this->hasMany(Budget::class, 'customer_id');
    }

    /**
     * Get budgets assigned to the admin.
     */
    public function responsibleBudgets()
    {
        return $this->hasMany(Budget::class, 'responsible_user_id');
    }

    /**
     * Get infractions for the customer.
     */
    public function infractions()
    {
        return $this->hasMany(Infraction::class, 'customer_id');
    }
}