<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'login',
        'password',
        'user_type',
        'status',
        'notification',
        'address',
        'number',
        'complement',
        'neighborhood',
        'municipality_id',
        'zip_code',
        'phone',
        'cnpj',
        'cpf',
        'photo',
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
        'password' => 'hashed',
        'status' => 'integer',
        'notification' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the municipality that owns the user.
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Get the budgets for the user as a customer.
     */
    public function customerBudgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'customer_id');
    }

    /**
     * Get the budgets where user is responsible.
     */
    public function responsibleBudgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'responsible_user_id');
    }

    /**
     * Get the documents for the user.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'customer_id');
    }

    /**
     * Get the infractions for the user.
     */
    public function infractions(): HasMany
    {
        return $this->hasMany(Infraction::class, 'customer_id');
    }

    /**
     * Get the mailbags for the user.
     */
    public function mailbags(): HasMany
    {
        return $this->hasMany(Mailbag::class, 'customer_id');
    }

    /**
     * Get the statements for the user.
     */
    public function statements(): HasMany
    {
        return $this->hasMany(Statement::class, 'customer_id');
    }
}