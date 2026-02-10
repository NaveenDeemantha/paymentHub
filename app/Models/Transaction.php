<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_ref',
        'reqid',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'payment_state',
        'customer_email',
        'customer_phone',
        'student_name',
        'student_id',
        'program',
        'nic_passport',
        'reference',
        'description',
        'request_data',
        'response_data',
        'initiated_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_data' => 'array',
        'response_data' => 'array',
        'initiated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the client that owns the transaction
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'completed' &&
               in_array($this->payment_state, ['COMPLETED', 'SUCCESS', 'AUTHORIZED']);
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Check if transaction failed
     */
    public function isFailed(): bool
    {
        return in_array($this->status, ['failed', 'cancelled']);
    }

    /**
     * Scope for successful transactions
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    /**
     * Scope for user transactions
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
