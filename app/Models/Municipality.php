<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipality extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'municipality_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'federative_unit_id',
        'code',
    ];

    /**
     * Get the federative unit that owns the municipality.
     */
    public function federativeUnit(): BelongsTo
    {
        return $this->belongsTo(FederativeUnit::class, 'federative_unit_id', 'federative_unit_id');
    }

    /**
     * Get the users for the municipality.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'municipality_id', 'municipality_id');
    }
}