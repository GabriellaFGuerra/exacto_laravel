<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailbagItem extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'mailbag_item_id';
    
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
        'mailbag_id',
        'provider',
        'document_type',
        'check_number',
        'value',
        'expiration_date',
        'closed',
        'close_date',
        'observation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'expiration_date' => 'date',
        'closed' => 'integer',
        'close_date' => 'datetime',
    ];

    /**
     * Get the mailbag that owns the mailbag item.
     */
    public function mailbag(): BelongsTo
    {
        return $this->belongsTo(Mailbag::class, 'mailbag_id', 'mailbag_id');
    }
}