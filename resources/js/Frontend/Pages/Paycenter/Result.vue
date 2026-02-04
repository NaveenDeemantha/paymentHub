<template>
    <Head title="Payment Result" />

    <div class="page-container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <svg class="logo-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                    <h1 class="logo-text">PayCenter</h1>
                </div>
            </div>
        </header>

        <div class="main-content">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <!-- Success State -->
                        <div v-if="success" class="result-container">
                            <div class="icon-wrapper">
                                <svg class="icon-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="result-title success">
                                Payment Successful!
                            </h3>

                            <p class="result-message">
                                {{ message }}
                            </p>

                            <div v-if="transaction" class="transaction-details">
                                <h4 class="details-title">Transaction Details</h4>
                                <div class="details-list">
                                    <div class="detail-row">
                                        <span class="detail-label">Order Reference:</span>
                                        <span class="detail-value mono">{{ transaction.client_ref }}</span>
                                    </div>
                                    <div v-if="transaction.transaction_id" class="detail-row">
                                        <span class="detail-label">Transaction ID:</span>
                                        <span class="detail-value mono">{{ transaction.transaction_id }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Amount:</span>
                                        <span class="detail-value">{{ transaction.currency }} {{ transaction.amount }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Status:</span>
                                        <span class="status-badge success">
                                            {{ transaction.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Failure State -->
                        <div v-else class="result-container">
                            <div class="icon-wrapper">
                                <svg class="icon-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="result-title error">
                                Payment Failed
                            </h3>

                            <p class="result-message">
                                {{ message || 'The payment could not be processed.' }}
                            </p>

                            <div v-if="transaction" class="transaction-details">
                                <h4 class="details-title">Transaction Details</h4>
                                <div class="details-list">
                                    <div class="detail-row">
                                        <span class="detail-label">Order Reference:</span>
                                        <span class="detail-value mono">{{ transaction.client_ref }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Status:</span>
                                        <span class="status-badge error">
                                            {{ transaction.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="actions">
                            <Link :href="route('pay.index')" class="btn-action">
                                <span v-if="success">Make Another Payment</span>
                                <span v-else>Try Again</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Support Info -->
                <div class="support-info">
                    <p class="support-text">
                        <strong>Need help?</strong> If you have questions about this transaction, please contact support with your order reference number.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <p>&copy; {{ new Date().getFullYear() }} PayCenter. All rights reserved.</p>
                <p class="footer-tagline">Secure payment gateway for your business</p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    success: Boolean,
    message: String,
    transaction: Object,
});
</script>

<style scoped>
/* Page Container */
.page-container {
    min-height: 100vh;
    background: linear-gradient(to bottom right, #eff6ff, #ffffff, #faf5ff);
}

/* Header */
.header {
    background-color: #ffffff;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.header-content {
    max-width: 1280px;
    margin: 0 auto;
    padding: 1rem;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.logo-icon {
    width: 2rem;
    height: 2rem;
    color: #2563eb;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

/* Main Content */
.main-content {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 48rem;
    margin: 0 auto;
    padding: 0 1.5rem;
}

@media (min-width: 640px) {
    .content-wrapper {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .content-wrapper {
        padding: 0 2rem;
    }
}

/* Card */
.card {
    background-color: #ffffff;
    overflow: hidden;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
}

@media (min-width: 640px) {
    .card {
        border-radius: 0.5rem;
    }
}

.card-body {
    padding: 2rem;
    color: #111827;
}

/* Result Container */
.result-container {
    text-align: center;
}

.icon-wrapper {
    margin-bottom: 1.5rem;
}

.icon-success,
.icon-error {
    width: 5rem;
    height: 5rem;
    margin: 0 auto;
}

.icon-success {
    color: #10b981;
}

.icon-error {
    color: #ef4444;
}

.result-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.result-title.success {
    color: #059669;
}

.result-title.error {
    color: #dc2626;
}

.result-message {
    color: #4b5563;
    margin-bottom: 1.5rem;
}

/* Transaction Details */
.transaction-details {
    background-color: #f9fafb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    text-align: left;
    margin-bottom: 1.5rem;
}

.details-title {
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.details-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-label {
    color: #4b5563;
}

.detail-value {
    font-weight: 600;
}

.detail-value.mono {
    font-family: monospace;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.success {
    background-color: #d1fae5;
    color: #065f46;
}

.status-badge.error {
    background-color: #fee2e2;
    color: #991b1b;
}

/* Actions */
.actions {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    background-color: #2563eb;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.btn-action:hover {
    background-color: #1d4ed8;
}

.btn-action:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #2563eb;
}

/* Support Info */
.support-info {
    margin-top: 1.5rem;
    background-color: #eff6ff;
    border-radius: 0.5rem;
    padding: 1rem;
}

.support-text {
    font-size: 0.875rem;
    color: #1e40af;
}

/* Footer */
.footer {
    background-color: #1f2937;
    color: #ffffff;
    padding: 2rem 0;
    margin-top: 5rem;
}

.footer-content {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1rem;
    text-align: center;
}

.footer-tagline {
    color: #9ca3af;
    margin-top: 0.5rem;
}
</style>
