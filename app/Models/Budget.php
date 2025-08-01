<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'budget_id';
    
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
        'service_type_id',
        'custom_service_type',
        'spreadsheet',
        'progress',
        'observation',
        'approval_date',
        'responsible_user_id',
        'responsible_manager_id',
        'deadline',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'approval_date' => 'date',
        'deadline' => 'date',
    ];

    /**
     * Get the client that owns the budget.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'user_id');
    }

    /**
     * Get the service type that owns the budget.
     */
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id', 'service_type_id');
    }

    /**
     * Get the responsible user that owns the budget.
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id', 'user_id');
    }

    /**
     * Get the responsible manager that owns the budget.
     */
    public function responsibleManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_manager_id', 'user_id');
    }

    /**
     * Get the budget providers for the budget.
     */
    public function budgetProviders(): HasMany
    {
        return $this->hasMany(BudgetProvider::class, 'budget_id', 'budget_id');
    }

    /**
     * Get the documents for the budget.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'budget_id', 'budget_id');
    }
}