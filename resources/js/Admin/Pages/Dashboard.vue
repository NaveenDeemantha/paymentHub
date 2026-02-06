<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="page-title">
                Paycenter Payment Gateway
            </h2>
        </template>

        <div class="content-container">
            <div class="content-wrapper">
                <!-- Welcome Message -->
                <div class="welcome-card">
                    <div class="welcome-content">
                        <h3 class="welcome-title">Paycenter Gateway</h3>
                        <p class="welcome-subtitle">Centralized payment processing for all your client websites</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-content">
                            <div>
                                <p class="stat-label">Total Clients</p>
                                <p class="stat-value">{{ stats?.total_clients || 0 }}</p>
                            </div>
                            <div class="stat-icon-wrapper blue">
                                <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-content">
                            <div>
                                <p class="stat-label">Total Transactions</p>
                                <p class="stat-value">{{ stats?.total_transactions || 0 }}</p>
                            </div>
                            <div class="stat-icon-wrapper purple">
                                <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-content">
                            <div>
                                <p class="stat-label">Completed</p>
                                <p class="stat-value green">{{ stats?.completed_transactions || 0 }}</p>
                            </div>
                            <div class="stat-icon-wrapper green">
                                <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-content">
                            <div>
                                <p class="stat-label">Total Amount</p>
                                <p class="stat-value">LKR {{ stats?.total_amount || 0 }}</p>
                            </div>
                            <div class="stat-icon-wrapper yellow">
                                <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Quick Actions</h3>
                        <div class="actions-grid">
                            <Link
                                :href="route('clients.create')"
                                class="action-card action-blue"
                            >
                                <div class="action-icon-wrapper blue">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div class="action-text">
                                    <h4 class="action-title">Add New Client</h4>
                                    <p class="action-description">Register a new website</p>
                                </div>
                            </Link>

                            <Link
                                :href="route('clients.index')"
                                class="action-card action-purple"
                            >
                                <div class="action-icon-wrapper purple">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="action-text">
                                    <h4 class="action-title">View All Clients</h4>
                                    <p class="action-description">Manage client websites</p>
                                </div>
                            </Link>

                            <Link
                                :href="route('pay.index')"
                                class="action-card action-green"
                            >
                                <div class="action-icon-wrapper green">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div class="action-text">
                                    <h4 class="action-title">Test Payment</h4>
                                    <p class="action-description">Make a test payment</p>
                                </div>
                            </Link>

                            <Link
                                :href="route('transactions.index')"
                                class="action-card action-yellow"
                            >
                                <div class="action-icon-wrapper yellow">
                                    <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <div class="action-text">
                                    <h4 class="action-title">View All Transactions</h4>
                                    <p class="action-description">Browse payment records</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div v-if="recentTransactions && recentTransactions.length > 0" class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h3 class="card-title">Recent Transactions</h3>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead class="table-head">
                                    <tr>
                                        <th class="table-header">Student</th>
                                        <th class="table-header">Reference</th>
                                        <th class="table-header">Amount</th>
                                        <th class="table-header">Status</th>
                                        <th class="table-header">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    <tr v-for="transaction in recentTransactions" :key="transaction.id" class="table-row">
                                        <td class="table-cell">
                                            <div class="cell-primary">
                                                {{ transaction.student_name || 'N/A' }}
                                            </div>
                                            <div class="cell-secondary">
                                                {{ transaction.student_id || 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="cell-secondary">
                                                {{ transaction.client_ref || transaction.reqid }}
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="cell-primary">
                                                {{ transaction.currency }} {{ transaction.amount }}
                                            </div>
                                        </td>
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
                                        <td class="table-cell cell-secondary">
                                            {{ new Date(transaction.created_at).toLocaleString() }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- API Documentation Link -->
                <div class="info-card">
                    <div class="info-content">
                        <div class="info-icon-wrapper">
                            <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="info-text">
                            <h3 class="info-title">Need to integrate with a client website?</h3>
                            <div class="info-body">
                                <p>Check the API documentation files in your project root:</p>
                                <ul class="info-list">
                                    <li><code class="code-block">CLIENT_API_DOCUMENTATION.md</code> - Integration guide</li>
                                    <li><code class="code-block">MULTI_CLIENT_SETUP.md</code> - Multi-client setup</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentTransactions: Array,
});
</script>

<style scoped>
/* Page Layout */
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

@media (min-width: 640px) {
    .content-wrapper {
        padding: 0 2rem;
    }
}

/* Welcome Card */
.welcome-card {
    overflow: hidden;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    background: linear-gradient(to right, #4f46e5, #9333ea);
}

.welcome-content {
    padding: 1.5rem;
    color: #ffffff;
}

.welcome-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    color: #e0e7ff;
}

/* Stats Grid */
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

/* Stat Card */
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

.stat-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
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
    font-size: 1.875rem;
    font-weight: 700;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .stat-value {
        color: #f3f4f6;
    }
}

.stat-value.green {
    color: #16a34a;
}

@media (prefers-color-scheme: dark) {
    .stat-value.green {
        color: #4ade80;
    }
}

.stat-icon-wrapper {
    padding: 0.75rem;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon-wrapper.blue {
    background-color: #dbeafe;
}

.stat-icon-wrapper.blue .stat-icon {
    color: #2563eb;
}

@media (prefers-color-scheme: dark) {
    .stat-icon-wrapper.blue {
        background-color: #1e3a8a;
    }
    .stat-icon-wrapper.blue .stat-icon {
        color: #93c5fd;
    }
}

.stat-icon-wrapper.purple {
    background-color: #f3e8ff;
}

.stat-icon-wrapper.purple .stat-icon {
    color: #9333ea;
}

@media (prefers-color-scheme: dark) {
    .stat-icon-wrapper.purple {
        background-color: #581c87;
    }
    .stat-icon-wrapper.purple .stat-icon {
        color: #d8b4fe;
    }
}

.stat-icon-wrapper.green {
    background-color: #dcfce7;
}

.stat-icon-wrapper.green .stat-icon {
    color: #16a34a;
}

@media (prefers-color-scheme: dark) {
    .stat-icon-wrapper.green {
        background-color: #14532d;
    }
    .stat-icon-wrapper.green .stat-icon {
        color: #86efac;
    }
}

.stat-icon-wrapper.yellow {
    background-color: #fef9c3;
}

.stat-icon-wrapper.yellow .stat-icon {
    color: #ca8a04;
}

@media (prefers-color-scheme: dark) {
    .stat-icon-wrapper.yellow {
        background-color: #713f12;
    }
    .stat-icon-wrapper.yellow .stat-icon {
        color: #fde047;
    }
}

.stat-icon {
    width: 2rem;
    height: 2rem;
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

/* Actions Grid */
.actions-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 768px) {
    .actions-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.action-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 2px dashed;
    border-radius: 0.5rem;
    transition: all 0.2s;
    text-decoration: none;
    border-color: #d1d5db;
}

@media (prefers-color-scheme: dark) {
    .action-card {
        border-color: #4b5563;
    }
}

.action-card:hover {
    border-style: solid;
}

.action-blue:hover { border-color: #6366f1; }
.action-purple:hover { border-color: #a855f7; }
.action-green:hover { border-color: #22c55e; }
.action-yellow:hover { border-color: #f59e0b; }

@media (prefers-color-scheme: dark) {
    .action-blue:hover { border-color: #818cf8; }
    .action-purple:hover { border-color: #c084fc; }
    .action-green:hover { border-color: #4ade80; }
    .action-yellow:hover { border-color: #fbbf24; }
}

.action-icon-wrapper {
    flex-shrink: 0;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.action-icon-wrapper.blue { background-color: #e0e7ff; color: #4f46e5; }
.action-icon-wrapper.purple { background-color: #f3e8ff; color: #9333ea; }
.action-icon-wrapper.green { background-color: #dcfce7; color: #16a34a; }
.action-icon-wrapper.yellow { background-color: #fef3c7; color: #d97706; }

@media (prefers-color-scheme: dark) {
    .action-icon-wrapper.blue { background-color: #312e81; color: #a5b4fc; }
    .action-icon-wrapper.purple { background-color: #581c87; color: #d8b4fe; }
    .action-icon-wrapper.green { background-color: #14532d; color: #86efac; }
    .action-icon-wrapper.yellow { background-color: #78350f; color: #fde68a; }
}

.action-icon {
    width: 1.5rem;
    height: 1.5rem;
}

.action-text {
    flex: 1;
}

.action-title {
    font-weight: 600;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .action-title {
        color: #f3f4f6;
    }
}

.action-description {
    font-size: 0.875rem;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .action-description {
        color: #9ca3af;
    }
}

/* Data Table */
.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

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
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

@media (prefers-color-scheme: dark) {
    .table-header {
        color: #9ca3af;
    }
}

.table-body {
    background-color: #ffffff;
}

@media (prefers-color-scheme: dark) {
    .table-body {
        background-color: #1f2937;
    }
}

.table-row {
    border-top: 1px solid #e5e7eb;
    transition: background-color 0.15s;
}

@media (prefers-color-scheme: dark) {
    .table-row {
        border-color: #374151;
    }
}

.table-row:hover {
    background-color: #f9fafb;
}

@media (prefers-color-scheme: dark) {
    .table-row:hover {
        background-color: #374151;
    }
}

.table-cell {
    padding: 0.75rem 1rem;
    white-space: nowrap;
}

.cell-primary {
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .cell-primary {
        color: #f3f4f6;
    }
}

.cell-secondary {
    font-size: 0.875rem;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .cell-secondary {
        color: #9ca3af;
    }
}

.status-badge {
    padding: 0.25rem 0.5rem;
    display: inline-flex;
    font-size: 0.75rem;
    line-height: 1;
    font-weight: 600;
    border-radius: 9999px;
}

.status-green {
    background-color: #dcfce7;
    color: #166534;
}

@media (prefers-color-scheme: dark) {
    .status-green {
        background-color: #14532d; /* green-900 */
        color: #dcfce7; /* green-100 */
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

/* Info Card */
.info-card {
    background-color: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 0.5rem;
    padding: 1.5rem;
}

@media (prefers-color-scheme: dark) {
    .info-card {
        background-color: rgba(30, 58, 138, 0.2);
        border-color: #1e3a8a;
    }
}

.info-content {
    display: flex;
    align-items: flex-start;
}

.info-icon-wrapper {
    flex-shrink: 0;
}

.info-icon {
    width: 1.5rem;
    height: 1.5rem;
    color: #2563eb;
}

@media (prefers-color-scheme: dark) {
    .info-icon {
        color: #60a5fa;
    }
}

.info-text {
    margin-left: 0.75rem;
    flex: 1;
}

.info-title {
    font-size: 0.875rem;
    font-weight: 500;
    color: #1e40af;
}

@media (prefers-color-scheme: dark) {
    .info-title {
        color: #bfdbfe;
    }
}

.info-body {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #1d4ed8;
}

@media (prefers-color-scheme: dark) {
    .info-body {
        color: #93c5fd;
    }
}

.info-list {
    list-style-type: disc;
    list-style-position: inside;
    margin-top: 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.code-block {
    background-color: #dbeafe;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: monospace;
}

@media (prefers-color-scheme: dark) {
    .code-block {
        background-color: #1e3a8a;
    }
}
</style>
