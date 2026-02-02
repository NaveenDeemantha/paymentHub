<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'name' => 'Demo Client Website',
            'domain' => 'democlient.com',
            'api_key' => Client::generateApiKey(),
            'return_url' => 'http://localhost:3000/payment/result',
            'webhook_url' => 'http://localhost:3000/webhooks/payment',
            'webhook_secret' => Client::generateWebhookSecret(),
            'is_active' => true,
        ]);
    }
}
