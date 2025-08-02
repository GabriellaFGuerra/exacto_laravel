<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'phone_2',
        'number',
        'cnpj',
        'municipality_id',
        'complement',
        'neighborhood',
        'zip_code',
    ];

    /**
     * Get the municipality that owns the provider.
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Get the provider services for this provider.
     */
    public function services(): HasMany
    {
        return $this->hasMany(ProviderService::class);
    }
}