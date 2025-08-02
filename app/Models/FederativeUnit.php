<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FederativeUnit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'acronym',
        'code',
    ];

    /**
     * Get the municipalities for the federative unit.
     */
    public function municipalities(): HasMany
    {
        return $this->hasMany(Municipality::class, 'federative_unit_id');
    }
}