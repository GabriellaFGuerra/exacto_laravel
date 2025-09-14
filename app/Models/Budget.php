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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'service_type_id',
        'custom_service_type',
        'spreadsheets',
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
        'approval_date' => 'date',
        'deadline' => 'date',
        'spreadsheets' => 'array',
    ];

    /**
     * Get the customer that owns the budget.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the service type that owns the budget.
     */
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the responsible user for the budget.
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    /**
     * Get the responsible manager for the budget.
     */
    public function responsibleManager(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'responsible_manager_id');
    }

    /**
     * Get the documents for the budget.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the budget providers for the budget.
     */
    public function budgetProviders(): HasMany
    {
        return $this->hasMany(BudgetProvider::class);
    }

    /**
     * Get all providers associated with this budget.
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'budget_providers', 'budget_id', 'provider_id')
            ->withPivot('value', 'observation', 'attachment')
            ->withTimestamps();
    }
}