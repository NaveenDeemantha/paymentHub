<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    /**
     * Display a listing of clients
     */
    public function index()
    {
        $clients = Client::withCount('transactions')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Admin/Pages/Clients/Index', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new client
     */
    public function create()
    {
        return Inertia::render('Admin/Pages/Clients/Create');
    }

    /**
     * Store a newly created client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:clients,domain',
            'return_url' => 'required|url',
            'webhook_url' => 'nullable|url',
        ]);

        $client = Client::create([
            'name' => $validated['name'],
            'domain' => $validated['domain'],
            'api_key' => Client::generateApiKey(),
            'return_url' => $validated['return_url'],
            'webhook_url' => $validated['webhook_url'] ?? null,
            'webhook_secret' => $validated['webhook_url'] ? Client::generateWebhookSecret() : null,
            'is_active' => true,
        ]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client created successfully');
    }

    /**
     * Display the specified client
     */
    public function show(Client $client)
    {
        $client->load([
            'transactions' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }
        ]);

        // Show API key only on show page (for copying)
        $client->makeVisible(['api_key', 'webhook_secret']);

        return Inertia::render('Admin/Pages/Clients/Show', [
            'client' => $client,
            'stats' => [
                'total_transactions' => $client->transactions()->count(),
                'completed' => $client->transactions()->where('status', 'completed')->count(),
                'failed' => $client->transactions()->where('status', 'failed')->count(),
                'total_amount' => $client->transactions()->where('status', 'completed')->sum('amount'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified client
     */
    public function edit(Client $client)
    {
        return Inertia::render('Admin/Pages/Clients/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified client
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:clients,domain,' . $client->id,
            'return_url' => 'required|url',
            'webhook_url' => 'nullable|url',
            'is_active' => 'required|boolean',
        ]);

        // Generate webhook secret if webhook URL is added but secret doesn't exist
        if ($validated['webhook_url'] && !$client->webhook_secret) {
            $validated['webhook_secret'] = Client::generateWebhookSecret();
        }

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client updated successfully');
    }

    /**
     * Regenerate API key
     */
    public function regenerateApiKey(Client $client)
    {
        $client->update([
            'api_key' => Client::generateApiKey(),
        ]);

        return back()->with('success', 'API key regenerated successfully');
    }

    /**
     * Regenerate webhook secret
     */
    public function regenerateWebhookSecret(Client $client)
    {
        $client->update([
            'webhook_secret' => Client::generateWebhookSecret(),
        ]);

        return back()->with('success', 'Webhook secret regenerated successfully');
    }
}
