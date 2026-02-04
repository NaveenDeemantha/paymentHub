<template>
    <Head title="Paycenter Payment" />

    <div class="page-container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <Link href="/" class="logo-link">
                    <svg class="logo-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                    <h1 class="logo-text">PayCenter</h1>
                </Link>
            </div>
        </header>

        <div class="main-content">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h3 class="card-title">Sampath Bank Payment</h3>
                            <p class="card-description">
                                Fill in the details below to start a payment. You'll be redirected to Paycenter's secure payment page.
                            </p>
                        </div>

                        <form :action="route('pay.initiate')" method="POST" class="payment-form">
                            <input type="hidden" name="_token" :value="csrfToken">

                            <div class="form-row">
                                <div class="form-group form-group-large">
                                    <label for="amount" class="form-label">
                                        Amount *
                                    </label>
                                    <input
                                        type="number"
                                        id="amount"
                                        name="amount"
                                        v-model="form.amount"
                                        class="form-input"
                                        required
                                        min="1"
                                        step="0.01"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="currency" class="form-label">
                                        Currency *
                                    </label>
                                    <select
                                        id="currency"
                                        name="currency"
                                        v-model="form.currency"
                                        class="form-select"
                                        required
                                    >
                                        <option value="LKR">LKR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="order_id" class="form-label">
                                    Order ID
                                </label>
                                <input
                                    type="text"
                                    id="order_id"
                                    name="order_id"
                                    v-model="form.order_id"
                                    class="form-input"
                                    placeholder="Leave empty to auto-generate"
                                >
                                <p class="form-hint">
                                    Optional - A unique reference will be generated if left empty
                                </p>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    v-model="form.description"
                                    rows="2"
                                    class="form-textarea"
                                    placeholder="Payment for..."
                                ></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        v-model="form.email"
                                        class="form-input"
                                        placeholder="customer@example.com"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        Phone
                                    </label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        v-model="form.phone"
                                        class="form-input"
                                        placeholder="+94771234567"
                                    >
                                </div>
                            </div>

                            <div v-if="$page.props.errors && $page.props.errors.payment" class="error-message">
                                {{ $page.props.errors.payment }}
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-submit">
                                    Proceed to Payment
                                </button>

                                <p class="form-note">
                                    You'll be redirected to Paycenter's secure payment page
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="info-box">
                    <h4 class="info-title">How it works:</h4>
                    <ol class="info-list">
                        <li>Fill in the payment details above</li>
                        <li>Click "Proceed to Payment" to go to Paycenter's secure page</li>
                        <li>Complete your payment on Paycenter's platform</li>
                        <li>You'll be redirected back with the payment result</li>
                    </ol>
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
import { ref, onMounted } from 'vue';

const form = ref({
    amount: 500,
    currency: 'LKR',
    order_id: '',
    description: '',
    email: '',
    phone: '',
});

const csrfToken = ref('');

onMounted(() => {
    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
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
    padding: 1.5rem;
    color: #111827;
}

.card-header {
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #111827;
}

.card-description {
    font-size: 0.875rem;
    color: #4b5563;
}

/* Form */
.payment-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 768px) {
    .form-row {
        grid-template-columns: 2fr 1fr;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group-large {
    grid-column: span 2;
}

@media (min-width: 768px) {
    .form-group-large {
        grid-column: span 2;
    }
    
    .form-row .form-group:first-child {
        grid-column: span 2;
    }
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.25rem;
}

.form-input,
.form-select,
.form-textarea {
    margin-top: 0.25rem;
    display: block;
    width: 100%;
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    padding: 0.5rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    resize: vertical;
}

.form-hint {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

/* Error Message */
.error-message {
    padding: 0.75rem;
    background-color: #fee2e2;
    color: #b91c1c;
    border-radius: 0.375rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-top: 1rem;
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: #16a34a;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.btn-submit:hover {
    background-color: #15803d;
}

.btn-submit:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.btn-submit:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #16a34a;
}

.form-note {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Info Box */
.info-box {
    margin-top: 1.5rem;
    background-color: #eff6ff;
    border-radius: 0.5rem;
    padding: 1rem;
}

.info-title {
    font-weight: 600;
    color: #1e3a8a;
    margin-bottom: 0.5rem;
}

.info-list {
    list-style-type: decimal;
    list-style-position: inside;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
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
