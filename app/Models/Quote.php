<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quote_number',
        'client_id',
        'created_by',
        'total_cost',
        'profit_margin_percent',
        'suggested_price',
        'final_price',
        'status',
        'valid_until',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_cost' => 'decimal:2',
            'profit_margin_percent' => 'decimal:2',
            'suggested_price' => 'decimal:2',
            'final_price' => 'decimal:2',
            'valid_until' => 'date',
        ];
    }

    /**
     * Get the client that owns the quote.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who created the quote.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the quote items.
     */
    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Calculate suggested price based on total cost and profit margin.
     */
    public function calculateSuggestedPrice(): float
    {
        return $this->total_cost + ($this->total_cost * ($this->profit_margin_percent / 100));
    }

    /**
     * Scope a query to only include quotes with a specific status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include expired quotes.
     */
    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now())
            ->where('status', '!=', 'accepted');
    }
}
