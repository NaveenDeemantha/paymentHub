<template>
    <Head title="Transaction Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="page-title">
                    Transaction Details
                </h2>
                <Link :href="route('transactions.index')" class="back-link">
                    ← Back to Transactions
                </Link>
            </div>
        </template>

        <div class="content-container">
            <div class="content-wrapper">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="status-header">
                            <div>
                                <h3 class="text-2xl font-bold">{{ transaction.client_ref }}</h3>
                                <p class="text-gray-500 mt-1">Transaction Reference</p>
                            </div>
                            <span
                                :class="[
                                    'status-badge-large',
                                    transaction.status === 'completed' ? 'status-green' :
                                    transaction.status === 'failed' ? 'status-red' :
                                    transaction.status === 'processing' ? 'status-blue' :
                                    'status-yellow'
                                ]"
                            >
                                {{ transaction.status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Main Info Grid -->
                <div class="info-grid">
                    <!-- Student Information -->
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Student Information</h3>
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label">Student Name</span>
                                    <span class="info-value">{{ transaction.student_name || 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Student ID</span>
                                    <span class="info-value">{{ transaction.student_id || 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">NIC / Passport</span>
                                    <span class="info-value">{{ transaction.nic_passport || 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Program</span>
                                    <span class="info-value">{{ transaction.program || 'N/A' }}</span>
                                </div>
                                <div class="info-item" v-if="transaction.reference">
                                    <span class="info-label">Reference</span>
                                    <span class="info-value">{{ transaction.reference }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Contact Information</h3>
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ transaction.customer_email || 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Phone</span>
                                    <span class="info-value">{{ transaction.customer_phone || 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Payment Details</h3>
                        <div class="info-grid-3">
                            <div class="info-item">
                                <span class="info-label">Amount</span>
                                <span class="info-value text-xl font-bold">
                                    {{ transaction.currency }} {{ transaction.amount }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Currency</span>
                                <span class="info-value">{{ transaction.currency }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status</span>
                                <span class="info-value">{{ transaction.status }}</span>
                            </div>
                            <div class="info-item" v-if="transaction.payment_state">
                                <span class="info-label">Payment State</span>
                                <span class="info-value">{{ transaction.payment_state }}</span>
                            </div>
                            <div class="info-item" v-if="transaction.reqid">
                                <span class="info-label">Request ID</span>
                                <span class="info-value font-mono text-sm">{{ transaction.reqid }}</span>
                            </div>
                            <div class="info-item" v-if="transaction.transaction_id">
                                <span class="info-label">Transaction ID</span>
                                <span class="info-value font-mono text-sm">{{ transaction.transaction_id }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Timeline</h3>
                        <div class="info-grid-3">
                            <div class="info-item">
                                <span class="info-label">Created At</span>
                                <span class="info-value">{{ formatDate(transaction.created_at) }}</span>
                            </div>
                            <div class="info-item" v-if="transaction.initiated_at">
                                <span class="info-label">Initiated At</span>
                                <span class="info-value">{{ formatDate(transaction.initiated_at) }}</span>
                            </div>
                            <div class="info-item" v-if="transaction.completed_at">
                                <span class="info-label">Completed At</span>
                                <span class="info-value">{{ formatDate(transaction.completed_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="card" v-if="transaction.description">
                    <div class="card-body">
                        <h3 class="card-title">Description</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ transaction.description }}</p>
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
    transaction: Object,
});

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
}
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

.back-link {
    color: #f97316;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
}

.back-link:hover {
    text-decoration: underline;
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

/* Card Styles */
.card {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    overflow: hidden;
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
    color: #1f2937;
    margin-bottom: 1rem;
}

@media (prefers-color-scheme: dark) {
    .card-title {
        color: #e5e7eb;
    }
}

/* Status Header */
.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

@media (max-width: 640px) {
    .status-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}

.status-badge-large {
    padding: 0.5rem 1.5rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

/* Info Grids */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .info-label {
        color: #9ca3af;
    }
}

.info-value {
    font-size: 0.875rem;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .info-value {
        color: #e5e7eb;
    }
}

/* Status Badges */
.status-green {
    background-color: #d1fae5;
    color: #065f46;
}

@media (prefers-color-scheme: dark) {
    .status-green {
        background-color: #064e3b;
        color: #6ee7b7;
    }
}

.status-yellow {
    background-color: #fef3c7;
    color: #92400e;
}

@media (prefers-color-scheme: dark) {
    .status-yellow {
        background-color: #78350f;
        color: #fde68a;
    }
}

.status-red {
    background-color: #fee2e2;
    color: #991b1b;
}

@media (prefers-color-scheme: dark) {
    .status-red {
        background-color: #7f1d1d;
        color: #fca5a5;
    }
}

.status-blue {
    background-color: #dbeafe;
    color: #1e40af;
}

@media (prefers-color-scheme: dark) {
    .status-blue {
        background-color: #1e3a8a;
        color: #93c5fd;
    }
}

/* Utility Classes */
.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-between {
    justify-content: space-between;
}

.text-2xl {
    font-size: 1.5rem;
}

.text-xl {
    font-size: 1.25rem;
}

.text-sm {
    font-size: 0.875rem;
}

.font-bold {
    font-weight: 700;
}

.font-mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}

.mt-1 {
    margin-top: 0.25rem;
}

.text-gray-500 {
    color: #6b7280;
}

.text-gray-700 {
    color: #374151;
}

@media (prefers-color-scheme: dark) {
    .text-gray-700 {
        color: #e5e7eb;
    }
}

@media (prefers-color-scheme: dark) {
    .dark\:text-gray-300 {
        color: #d1d5db;
    }
}
</style>
