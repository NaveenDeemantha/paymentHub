<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'api_key',
        'return_url',
        'webhook_url',
        'webhook_secret',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    protected $hidden = [
        'api_key',
        'webhook_secret',
    ];

    /**
     * Get transactions for this client
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Generate a unique API key
     */
    public static function generateApiKey(): string
    {
        return 'pk_' . Str::random(32);
    }

    /**
     * Generate webhook secret
     */
    public static function generateWebhookSecret(): string
    {
        return 'whsec_' . Str::random(32);
    }

    /**
     * Check if client is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
