<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    client: Object,
    stats: Object,
});

const showApiKey = ref(false);
const showWebhookSecret = ref(false);

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    alert('Copied to clipboard!');
};

const regenerateApiKeyForm = useForm({});
const regenerateApiKey = () => {
    if (confirm('Are you sure? This will invalidate the current API key and all existing integrations will stop working.')) {
        regenerateApiKeyForm.post(route('clients.regenerate-api-key', props.client.id));
    }
};

const regenerateWebhookForm = useForm({});
const regenerateWebhook = () => {
    if (confirm('Are you sure? This will invalidate the current webhook secret.')) {
        regenerateWebhookForm.post(route('clients.regenerate-webhook-secret', props.client.id));
    }
};
</script>

<template>
    <Head :title="`Client: ${client.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('clients.index')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                        ← Back
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ client.name }}
                    </h2>
                    <span 
                        :class="client.is_active 
                            ? 'px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                            : 'px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                    >
                        {{ client.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <Link 
                    :href="route('clients.edit', client.id)" 
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none transition ease-in-out duration-150"
                >
                    Edit Client
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Transactions</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ stats.total_transactions }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Completed</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats.completed }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Failed</div>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats.failed }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Amount</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">LKR {{ stats.total_amount }}</div>
                    </div>
                </div>

                <!-- Client Details -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Client Details</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Domain</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ client.domain }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Return URL</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 break-all">{{ client.return_url }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Webhook URL</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 break-all">{{ client.webhook_url || 'Not configured' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ new Date(client.created_at).toLocaleString() }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- API Credentials -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">API Credentials</h3>
                        
                        <!-- API Key -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">API Key</label>
                                <button 
                                    @click="showApiKey = !showApiKey"
                                    class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                                >
                                    {{ showApiKey ? 'Hide' : 'Show' }}
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <input 
                                    :type="showApiKey ? 'text' : 'password'"
                                    :value="client.api_key" 
                                    readonly
                                    class="flex-1 rounded-md border-gray-300 bg-gray-50 dark:bg-gray-900 dark:border-gray-600 dark:text-white"
                                >
                                <button 
                                    @click="copyToClipboard(client.api_key)"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm"
                                >
                                    Copy
                                </button>
                                <button 
                                    @click="regenerateApiKey"
                                    :disabled="regenerateApiKeyForm.processing"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white rounded-md text-sm"
                                >
                                    Regenerate
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                ⚠️ Keep this secret! Use this in API requests via X-API-Key header
                            </p>
                        </div>

                        <!-- Webhook Secret -->
                        <div v-if="client.webhook_secret">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Webhook Secret</label>
                                <button 
                                    @click="showWebhookSecret = !showWebhookSecret"
                                    class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                                >
                                    {{ showWebhookSecret ? 'Hide' : 'Show' }}
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <input 
                                    :type="showWebhookSecret ? 'text' : 'password'"
                                    :value="client.webhook_secret" 
                                    readonly
                                    class="flex-1 rounded-md border-gray-300 bg-gray-50 dark:bg-gray-900 dark:border-gray-600 dark:text-white"
                                >
                                <button 
                                    @click="copyToClipboard(client.webhook_secret)"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm"
                                >
                                    Copy
                                </button>
                                <button 
                                    @click="regenerateWebhook"
                                    :disabled="regenerateWebhookForm.processing"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white rounded-md text-sm"
                                >
                                    Regenerate
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Use this to verify webhook signatures
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div v-if="client.transactions && client.transactions.length > 0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Transactions</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Client Ref</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="transaction in client.transactions" :key="transaction.id">
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ transaction.client_ref }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ transaction.currency }} {{ transaction.amount }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span 
                                                :class="transaction.status === 'completed' 
                                                    ? 'px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800' 
                                                    : transaction.status === 'failed' 
                                                    ? 'px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800'
                                                    : 'px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800'"
                                            >
                                                {{ transaction.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ new Date(transaction.created_at).toLocaleString() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
