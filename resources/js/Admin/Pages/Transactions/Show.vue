<template>
    <Head title="Transaction Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Transaction Details</h1>
                    <p class="page-subtitle">View complete transaction information</p>
                </div>
                <Link :href="route('transactions.index')" class="btn-back">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Transactions
                </Link>
            </div>
        </template>

        <div class="content-wrapper">
            <!-- Status Card -->
            <div class="card status-card">
                <div class="card-body-compact">
                    <div class="status-header">
                        <div class="transaction-ref">
                            <span class="ref-label">Transaction Reference</span>
                            <h2 class="ref-number">{{ transaction.transaction_number || transaction.client_ref || transaction.id }}</h2>
                        </div>
                        <span :class="['status-badge-large', getStatusClass(transaction.status)]">
                            {{ formatStatus(transaction.status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Info Grid -->
            <div class="info-grid-compact">
                <!-- Student Information -->
                <div class="card">
                    <div class="card-body-compact">
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
                            <div class="info-item" v-if="transaction.nic_passport">
                                <span class="info-label">NIC / Passport</span>
                                <span class="info-value">{{ transaction.nic_passport }}</span>
                            </div>
                            <div class="info-item" v-if="transaction.program">
                                <span class="info-label">Program</span>
                                <span class="info-value">{{ transaction.program }}</span>
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
                    <div class="card-body-compact">
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

                <!-- Client Information -->
                <div class="card" v-if="transaction.client">
                    <div class="card-body-compact">
                        <h3 class="card-title">Client Information</h3>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="info-label">Client Name</span>
                                <span class="info-value">{{ transaction.client.name }}</span>
                            </div>
                            <div class="info-item" v-if="transaction.client_ref">
                                <span class="info-label">Client Reference</span>
                                <span class="info-value">{{ transaction.client_ref }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card payment-card">
                <div class="card-body-compact">
                    <h3 class="card-title">Payment Details</h3>
                    <div class="payment-amount">
                        <span class="amount-label">Total Amount</span>
                        <span class="amount-value">{{ transaction.currency }} {{ formatAmount(transaction.amount) }}</span>
                    </div>
                    <div class="info-grid-3">
                        <div class="info-item">
                            <span class="info-label">Currency</span>
                            <span class="info-value">{{ transaction.currency }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Payment Status</span>
                            <span :class="['status-badge-inline', getStatusClass(transaction.status)]">
                                {{ formatStatus(transaction.status) }}
                            </span>
                        </div>
                        <div class="info-item" v-if="transaction.payment_state">
                            <span class="info-label">Payment State</span>
                            <span class="info-value">{{ transaction.payment_state }}</span>
                        </div>
                        <div class="info-item" v-if="transaction.reqid">
                            <span class="info-label">Request ID</span>
                            <span class="info-value info-mono">{{ transaction.reqid }}</span>
                        </div>
                        <div class="info-item" v-if="transaction.transaction_id">
                            <span class="info-label">Transaction ID</span>
                            <span class="info-value info-mono">{{ transaction.transaction_id }}</span>
                        </div>
                        <div class="info-item" v-if="transaction.transaction_type">
                            <span class="info-label">Transaction Type</span>
                            <span class="info-value">{{ transaction.transaction_type }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-body-compact">
                    <h3 class="card-title">Timeline</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <span class="timeline-label">Created</span>
                                <span class="timeline-value">{{ formatDateTime(transaction.created_at) }}</span>
                            </div>
                        </div>
                        <div class="timeline-item" v-if="transaction.initiated_at">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <span class="timeline-label">Initiated</span>
                                <span class="timeline-value">{{ formatDateTime(transaction.initiated_at) }}</span>
                            </div>
                        </div>
                        <div class="timeline-item" v-if="transaction.completed_at">
                            <div class="timeline-marker completed"></div>
                            <div class="timeline-content">
                                <span class="timeline-label">Completed</span>
                                <span class="timeline-value">{{ formatDateTime(transaction.completed_at) }}</span>
                            </div>
                        </div>
                        <div class="timeline-item" v-if="transaction.updated_at && transaction.updated_at !== transaction.created_at">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <span class="timeline-label">Last Updated</span>
                                <span class="timeline-value">{{ formatDateTime(transaction.updated_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card" v-if="transaction.description">
                <div class="card-body-compact">
                    <h3 class="card-title">Description</h3>
                    <p class="description-text">{{ transaction.description }}</p>
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

const formatAmount = (amount) => {
    return Number(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatDateTime = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatStatus = (status) => {
    return status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';
};

const getStatusClass = (status) => {
    const statusMap = {
        'completed': 'status-completed',
        'pending': 'status-pending',
        'failed': 'status-failed',
        'processing': 'status-processing'
    };
    return statusMap[status] || 'status-pending';
};
</script>

<style scoped>
/* Page Header */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #0b1120;
    margin: 0;
}

.page-subtitle {
    color: #64748b;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}

.btn-back {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: white;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    text-decoration: none;
    cursor: pointer;
}

.btn-back:hover {
    border-color: #dcb01d;
    color: #dcb01d;
}

.btn-back .icon {
    width: 1.125rem;
    height: 1.125rem;
}

/* Content Layout */
.content-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    max-width: 100%;
}

.content-wrapper > .status-card {
    grid-column: span 2;
}

.content-wrapper > .payment-card,
.content-wrapper > .card:has(.timeline),
.content-wrapper > .card:has(.description-text) {
    grid-column: span 2;
}

/* Card Styles */
.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.card-body {
    padding: 1.5rem;
}

.card-body-compact {
    padding: 0.875rem;
}

.card-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    margin: 0 0 0.625rem 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Status Card */
.status-card {
    background: #f8fafc;
    border: 2px solid #dcb01d;
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
}

.transaction-ref {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.ref-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.ref-number {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0b1120;
    margin: 0;
}

.status-badge-large {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.875rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
    white-space: nowrap;
}

.status-badge-inline {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-completed {
    background: white;
    color: #065f46;
    border: 1.5px solid #10b981;
}

.status-pending {
    background: white;
    color: #92400e;
    border: 1.5px solid #fbbf24;
}

.status-processing {
    background: white;
    color: #1e3a8a;
    border: 1.5px solid #3b82f6;
}

.status-failed {
    background: white;
    color: #991b1b;
    border: 1.5px solid #ef4444;
}

/* Info Grids */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-grid-compact {
    display: contents;
}

.info-grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.875rem;
    margin-top: 0.625rem;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
}

.info-value {
    font-size: 0.8125rem;
    color: #0b1120;
    font-weight: 500;
}

.info-mono {
    font-family: 'Courier New', monospace;
    font-size: 0.8125rem;
    color: #1e293b;
    background: #f9fafb;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #e5e7eb;
    display: inline-block;
}

/* Payment Card */
.payment-card {
    border: 2px solid #dcb01d;
}

.payment-amount {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 0.75rem;
    background: #fafafa;
    border-radius: 6px;
    margin-bottom: 0.625rem;
    border-left: 3px solid #dcb01d;
}

.amount-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.amount-value {
    font-size: 1.375rem;
    font-weight: 700;
    color: #0b1120;
}

/* Timeline */
.timeline {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
    position: relative;
    padding-left: 1.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0.5rem;
    bottom: 0.5rem;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    display: flex;
    gap: 1rem;
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 0.625rem;
    height: 0.625rem;
    border-radius: 50%;
    background: #dcb01d;
    z-index: 1;
}

.timeline-marker.completed {
    background: #10b981;
}

.timeline-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.timeline-label {
    font-size: 0.6875rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.timeline-value {
    font-size: 0.8125rem;
    color: #0b1120;
    font-weight: 500;
}

/* Description */
.description-text {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.8125rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .content-wrapper {
        grid-template-columns: 1fr;
    }

    .content-wrapper > .status-card,
    .content-wrapper > .payment-card,
    .content-wrapper > .card:has(.timeline),
    .content-wrapper > .card:has(.description-text) {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .status-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .info-grid-3 {
        grid-template-columns: 1fr;
    }

    .ref-number {
        font-size: 1rem;
    }

    .amount-value {
        font-size: 1.125rem;
    }
}
</style>
