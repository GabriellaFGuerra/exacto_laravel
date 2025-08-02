<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appeal extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'infraction_id',
        'subject',
        'description',
        'appeal',
        'status',
    ];

    /**
     * Get the infraction that owns the appeal.
     */
    public function infraction(): BelongsTo
    {
        return $this->belongsTo(Infraction::class);
    }
}