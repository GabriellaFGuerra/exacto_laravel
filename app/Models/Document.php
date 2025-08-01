<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'document_id';
    
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
        'client_id',
        'budget_id',
        'document_type_id',
        'attachment',
        'issue_date',
        'periodicity',
        'expiration_date',
        'observation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'periodicity' => 'integer',
        'expiration_date' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Get the client that owns the document.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'user_id');
    }

    /**
     * Get the budget that owns the document.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_id', 'budget_id');
    }

    /**
     * Get the document type that owns the document.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'document_type_id');
    }
}