<template>
    <Head :title="`Client: ${client.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="header-container">
                <div class="header-left">
                    <Link :href="route('clients.index')" class="back-link">
                        ← Back
                    </Link>
                    <h2 class="page-title">
                        {{ client.name }}
                    </h2>
                    <span 
                        :class="client.is_active ? 'status-active' : 'status-inactive'"
                    >
                        {{ client.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <Link 
                    :href="route('clients.edit', client.id)" 
                    class="btn-warning"
                >
                    Edit Client
                </Link>
            </div>
        </template>

        <div class="content-container">
            <div class="content-wrapper">
                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Total Transactions</div>
                        <div class="stat-value">{{ stats.total_transactions }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Completed</div>
                        <div class="stat-value text-green">{{ stats.completed }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Failed</div>
                        <div class="stat-value text-red">{{ stats.failed }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Amount</div>
                        <div class="stat-value">LKR {{ stats.total_amount }}</div>
                    </div>
                </div>

                <!-- Client Details -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Client Details</h3>
                        <dl class="details-grid">
                            <div class="detail-item">
                                <dt class="detail-label">Domain</dt>
                                <dd class="detail-value">{{ client.domain }}</dd>
                            </div>
                            <div class="detail-item">
                                <dt class="detail-label">Return URL</dt>
                                <dd class="detail-value break-all">{{ client.return_url }}</dd>
                            </div>
                            <div class="detail-item">
                                <dt class="detail-label">Webhook URL</dt>
                                <dd class="detail-value break-all">{{ client.webhook_url || 'Not configured' }}</dd>
                            </div>
                            <div class="detail-item">
                                <dt class="detail-label">Created</dt>
                                <dd class="detail-value">{{ new Date(client.created_at).toLocaleString() }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- API Credentials -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">API Credentials</h3>
                        
                        <!-- API Key -->
                        <div class="credential-group">
                            <div class="credential-header">
                                <label class="credential-label">API Key</label>
                                <button 
                                    @click="showApiKey = !showApiKey"
                                    class="btn-link"
                                >
                                    {{ showApiKey ? 'Hide' : 'Show' }}
                                </button>
                            </div>
                            <div class="input-group">
                                <input 
                                    :type="showApiKey ? 'text' : 'password'"
                                    :value="client.api_key" 
                                    readonly
                                    class="credential-input"
                                >
                                <button 
                                    @click="copyToClipboard(client.api_key)"
                                    class="btn-secondary"
                                >
                                    Copy
                                </button>
                                <button 
                                    @click="regenerateApiKey"
                                    :disabled="regenerateApiKeyForm.processing"
                                    class="btn-danger"
                                >
                                    Regenerate
                                </button>
                            </div>
                            <p class="credential-hint">
                                ⚠️ Keep this secret! Use this in API requests via X-API-Key header
                            </p>
                        </div>

                        <!-- Webhook Secret -->
                        <div v-if="client.webhook_secret" class="credential-group">
                            <div class="credential-header">
                                <label class="credential-label">Webhook Secret</label>
                                <button 
                                    @click="showWebhookSecret = !showWebhookSecret"
                                    class="btn-link"
                                >
                                    {{ showWebhookSecret ? 'Hide' : 'Show' }}
                                </button>
                            </div>
                            <div class="input-group">
                                <input 
                                    :type="showWebhookSecret ? 'text' : 'password'"
                                    :value="client.webhook_secret" 
                                    readonly
                                    class="credential-input"
                                >
                                <button 
                                    @click="copyToClipboard(client.webhook_secret)"
                                    class="btn-secondary"
                                >
                                    Copy
                                </button>
                                <button 
                                    @click="regenerateWebhook"
                                    :disabled="regenerateWebhookForm.processing"
                                    class="btn-danger"
                                >
                                    Regenerate
                                </button>
                            </div>
                            <p class="credential-hint">
                                Use this to verify webhook signatures
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div v-if="client.transactions && client.transactions.length > 0" class="card">
                    <div class="card-body">
                        <h3 class="card-title">Recent Transactions</h3>
                        <div class="table-container">
                            <table class="data-table">
                                <thead class="table-head">
                                    <tr>
                                        <th class="table-header">Client Ref</th>
                                        <th class="table-header">Amount</th>
                                        <th class="table-header">Status</th>
                                        <th class="table-header">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    <tr v-for="transaction in client.transactions" :key="transaction.id" class="table-row">
                                        <td class="table-cell transaction-ref">{{ transaction.client_ref }}</td>
                                        <td class="table-cell transaction-amount">{{ transaction.currency }} {{ transaction.amount }}</td>
                                        <td class="table-cell">
                                            <span 
                                                :class="[
                                                    'status-badge',
                                                    transaction.status === 'completed' ? 'status-green' :
                                                    transaction.status === 'failed' ? 'status-red' : 'status-yellow'
                                                ]"
                                            >
                                                {{ transaction.status }}
                                            </span>
                                        </td>
                                        <td class="table-cell transaction-date">{{ new Date(transaction.created_at).toLocaleString() }}</td>
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

<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
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

<style scoped>
/* Header */
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.back-link {
    color: #4b5563;
    text-decoration: none;
    transition: color 0.15s;
}

.back-link:hover {
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .back-link {
        color: #9ca3af;
    }
    .back-link:hover {
        color: #e5e7eb;
    }
}

.page-title {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.25;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .page-title {
        color: #e5e7eb;
    }
}

.status-active,
.status-inactive {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
}

.status-active {
    background-color: #dcfce7;
    color: #166534;
}

@media (prefers-color-scheme: dark) {
    .status-active {
        background-color: #14532d;
        color: #dcfce7;
    }
}

.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

@media (prefers-color-scheme: dark) {
    .status-inactive {
        background-color: #7f1d1d;
        color: #fee2e2;
    }
}

.btn-warning {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #ca8a04;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-decoration: none;
    transition: background-color 0.15s ease-in-out;
}

.btn-warning:hover {
    background-color: #a16207;
}

.btn-warning:focus {
    outline: none;
}

/* Content Layout */
.content-container {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.stat-card {
    background-color: #ffffff;
    overflow: hidden;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
    padding: 1.5rem;
}

@media (prefers-color-scheme: dark) {
    .stat-card {
        background-color: #1f2937;
    }
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .stat-label {
        color: #9ca3af;
    }
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .stat-value {
        color: #f3f4f6;
    }
}

.text-green { color: #16a34a; }
.text-red { color: #dc2626; }

@media (prefers-color-scheme: dark) {
    .text-green { color: #4ade80; }
    .text-red { color: #f87171; }
}

/* Details Card */
.details-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 768px) {
    .details-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .detail-label {
        color: #9ca3af;
    }
}

.detail-value {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .detail-value {
        color: #f3f4f6;
    }
}

.break-all {
    word-break: break-all;
}

/* Credentials */
.credential-group {
    margin-bottom: 1.5rem;
}

.credential-group:last-child {
    margin-bottom: 0;
}

.credential-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.credential-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

@media (prefers-color-scheme: dark) {
    .credential-label {
        color: #d1d5db;
    }
}

.btn-link {
    background: none;
    border: none;
    padding: 0;
    font-size: 0.875rem;
    color: #4f46e5;
    cursor: pointer;
}

.btn-link:hover {
    color: #312e81;
}

@media (prefers-color-scheme: dark) {
    .btn-link {
        color: #818cf8;
    }
    .btn-link:hover {
        color: #a5b4fc;
    }
}

.input-group {
    display: flex;
    gap: 0.5rem;
}

.credential-input {
    flex: 1;
    border-radius: 0.375rem;
    border-color: #d1d5db;
    background-color: #f9fafb;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .credential-input {
        background-color: #111827;
        border-color: #4b5563;
        color: #ffffff;
    }
}

.credential-hint {
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .credential-hint {
        color: #9ca3af;
    }
}

/* Buttons */
.btn-secondary {
    padding: 0.5rem 1rem;
    background-color: #4b5563;
    color: #ffffff;
    border-radius: 0.375rem;
    border: none;
    font-size: 0.875rem;
    cursor: pointer;
}

.btn-secondary:hover {
    background-color: #374151;
}

.btn-danger {
    padding: 0.5rem 1rem;
    background-color: #dc2626;
    color: #ffffff;
    border-radius: 0.375rem;
    border: none;
    font-size: 0.875rem;
    cursor: pointer;
}

.btn-danger:hover {
    background-color: #b91c1c;
}

.btn-danger:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

/* Base Card */
.card {
    background-color: #ffffff;
    overflow: hidden;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
}

@media (prefers-color-scheme: dark) {
    .card {
        background-color: #1f2937;
    }
}

.card-body {
    padding: 1.5rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1rem;
}

@media (prefers-color-scheme: dark) {
    .card-title {
        color: #f3f4f6;
    }
}

/* Table */
.table-container {
    overflow-x: auto;
}

.data-table {
    min-width: 100%;
    border-collapse: collapse;
}

.table-head {
    background-color: #f9fafb;
}

@media (prefers-color-scheme: dark) {
    .table-head {
        background-color: #111827;
    }
}

.table-header {
    padding: 0.5rem 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
}

@media (prefers-color-scheme: dark) {
    .table-header {
        color: #9ca3af;
    }
}

.table-body {
    background-color: #ffffff;
    border-top: 1px solid #e5e7eb;
}

@media (prefers-color-scheme: dark) {
    .table-body {
        background-color: #1f2937;
        border-color: #374151;
    }
}

.table-row {
    border-top: 1px solid #e5e7eb;
}

@media (prefers-color-scheme: dark) {
    .table-row {
        border-color: #374151;
    }
}

.table-cell {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.transaction-ref, .transaction-amount {
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .transaction-ref, .transaction-amount {
        color: #f3f4f6;
    }
}

.transaction-date {
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .transaction-date {
        color: #9ca3af;
    }
}

.status-badge {
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-green {
    background-color: #dcfce7;
    color: #166534;
}

@media (prefers-color-scheme: dark) {
    .status-green {
        background-color: #14532d;
        color: #dcfce7;
    }
}

.status-red {
    background-color: #fee2e2;
    color: #991b1b;
}

@media (prefers-color-scheme: dark) {
    .status-red {
        background-color: #7f1d1d;
        color: #fee2e2;
    }
}

.status-yellow {
    background-color: #fef9c3;
    color: #854d0e;
}

@media (prefers-color-scheme: dark) {
    .status-yellow {
        background-color: #78350f;
        color: #fef9c3;
    }
}
</style>
