<template>
    <Head title="Paycenter Payment" />

    <div class="page-container">
        
        <!-- LEFT PANEL: Summary -->
        <div class="summary-panel">
             <!-- Background Texture -->
             <div class="panel-texture"></div>

             <div class="panel-content">
                <!-- Header -->
                <Link href="/" class="back-link group">
                    <div class="icon-box">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </div>
                    <span class="link-text">Back to Home</span>
                </Link>

                <div class="hero-section">
                    <p class="overline">COMPLETE YOUR PAYMENT</p>
                    <h1 class="main-title">Secure <br> Checkout</h1>
                </div>

                <div class="security-footer">
                     <!-- Security Badge -->
                    <div class="ssl-badge">
                        <div class="check-circle">
                            <svg class="check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <div>
                            <p class="badge-title">SSL Encrypted</p>
                            <p class="badge-subtitle">Your data is physically secured.</p>
                        </div>
                    </div>
                    
                    <p class="terms-text">
                        By proceeding, you agree to our <a href="#" class="terms-link">Terms of Service</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: Form -->
        <div class="form-panel">
            
            <!-- Mobile Header (Visible only on small screens) -->
            <div class="mobile-header">
                 <Link href="/" class="mobile-back">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>Back</span>
                 </Link>
                 <span class="font-serif">PayCenter</span>
            </div>

            <!-- Form Container -->
            <div class="form-wrapper">
                <div class="form-card">
                    
                    <div class="form-header">
                        <div class="merchant-info">
                            <div class="merchant-logo">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                            <div>
                                <h2 class="merchant-name">Sampath Bank</h2>
                                <p class="merchant-sub">Corporate Payment Gateway</p>
                            </div>
                        </div>
                        <div class="server-status">
                            <p class="status-pill">Server Online</p>
                        </div>
                    </div>

                    <form :action="route('pay.initiate')" method="POST" class="payment-form">
                        <input type="hidden" name="_token" :value="csrfToken">

                        <!-- Row 1: Amount & Currency -->
                        <div class="form-row grid-cols-12">
                            <div class="col-main">
                                <label class="input-label">Amount</label>
                                <div class="input-group">
                                    <span class="currency-prefix">Rs.</span>
                                    <input type="number" name="amount" v-model="form.amount" required step="0.01"
                                        class="input-field-large" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-side">
                                <label class="input-label">Currency</label>
                                <select name="currency" v-model="form.currency" class="select-field">
                                    <option value="LKR">LKR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 2: Reference & Description -->
                        <div class="form-row grid-cols-2">
                             <div>
                                <label class="input-label">Reference ID <span class="optional">(Optional)</span></label>
                                <input type="text" name="order_id" v-model="form.order_id"
                                    class="input-field" placeholder="e.g. ST-2024-001">
                            </div>
                            <div>
                                <label class="input-label">Description</label>
                                <input type="text" name="description" v-model="form.description"
                                    class="input-field" placeholder="e.g. Semester Fees">
                            </div>
                        </div>

                         <!-- Row 3: Contact Info -->
                        <div class="form-row grid-cols-2">
                             <div>
                                <label class="input-label">Email Receipt To</label>
                                <input type="email" name="email" v-model="form.email"
                                    class="input-field" placeholder="name@example.com">
                            </div>
                            <div>
                                <label class="input-label">Phone Number</label>
                                <input type="tel" name="phone" v-model="form.phone"
                                    class="input-field" placeholder="+94 7X XXX XXXX">
                            </div>
                        </div>

                        <!-- Errors -->
                        <div v-if="$page.props.errors && $page.props.errors.payment" class="error-box">
                            {{ $page.props.errors.payment }}
                        </div>

                        <!-- Submit -->
                        <div class="submit-section">
                            <button type="submit" class="submit-btn" :disabled="form.processing">
                                <span>Proceed to Secure Payment</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Footer Small -->
            <div class="pane-footer">
                POWERED BY WINFIELD PAYCENTER
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'; // Assuming useForm is available or standard Ref
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
@import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.font-serif { font-family: 'Merriweather', serif; }

/* Layout */
.page-container {
    height: 100vh;
    width: 100%;
    background-color: #f8fafc; /* slate-50 */
    display: flex;
    flex-direction: column;
    overflow: hidden;
    font-family: 'Inter', sans-serif;
}

@media (min-width: 768px) {
    .page-container {
        flex-direction: row;
    }
}

/* Left Panel */
.summary-panel {
    display: none;
}

@media (min-width: 768px) {
    .summary-panel {
        display: flex;
        width: 35%;
        background-color: #0b1120;
        color: white;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }
}

@media (min-width: 1024px) {
    .summary-panel {
        width: 30%;
    }
}

.panel-texture {
    position: absolute;
    inset: 0;
    opacity: 0.2;
    background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}


.panel-content {
    position: relative;
    z-index: 10;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    animation: fadeIn 1s ease-out;
}

@media (min-width: 1024px) {
    .panel-content {
        padding: 3rem;
    }
}

/* Back Link */
.back-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2.5rem;
    opacity: 0.8;
    transition: opacity 0.2s;
    text-decoration: none;
    color: inherit;
    animation: slideInLeft 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.back-link:hover {
    opacity: 1;
}

.icon-box {
    padding: 0.5rem;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
    backdrop-filter: blur(4px);
    transition: background-color 0.2s;
}

.back-link:hover .icon-box {
    background-color: rgba(255, 255, 255, 0.2);
}

.icon {
    width: 1.25rem;
    height: 1.25rem;
    color: #dcb01d; /* Gold */
}

.link-text {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Hero Section inside Panel */
.hero-section {
    margin-top: 1rem;
    animation: slideInLeft 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) 0.1s backwards;
}

.overline {
    color: #dcb01d;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.main-title {
    font-family: 'Merriweather', serif;
    font-size: 1.875rem;
    font-weight: 500;
    line-height: 1.25;
}

/* Footer in Panel */
.security-footer {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    animation: fadeIn 1s ease-out 0.5s backwards;
}

.ssl-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(4px);
}

.check-circle {
    padding: 0.5rem;
    background-color: rgba(34, 197, 94, 0.2);
    border-radius: 9999px;
    /* Animated Check Pulse */
    animation: pulseSubtle 3s infinite;
}

.check-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: #4ade80;
}

.badge-title {
    font-size: 0.875rem;
    font-weight: 700;
    color: white;
}

.badge-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
}

.terms-text {
    font-size: 0.75rem;
    color: #64748b;
    max-width: 20rem;
}

.terms-link {
    color: #94a3b8;
    text-decoration: underline;
}

.terms-link:hover {
    color: white;
}

/* Right Panel: Form */
.form-panel {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    background-color: #ffffff;
    animation: slideUpPanel 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
}

@media (min-width: 768px) {
    .form-panel {
        width: 65%;
        animation: none; /* Desktop fade logic elsewhere if needed */
    }
}
@media (min-width: 1024px) {
    .form-panel {
        width: 70%;
    }
}

.mobile-header {
    height: 4rem;
    background-color: #0b1120;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    flex: none;
}

@media (min-width: 768px) {
    .mobile-header {
        display: none;
    }
}

.mobile-back {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
}

.form-wrapper {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

@media (min-width: 768px) {
    .form-wrapper {
        padding: 3rem;
    }
}

.form-card {
    width: 100%;
    max-width: 42rem;
    margin: 0 auto;
    animation: fadeIn 0.6s ease-out 0.2s backwards;
}

.form-header {
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 1rem;
}

.merchant-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.merchant-logo {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background-color: #fff7ed;
    border: 1px solid #ffedd5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.merchant-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.merchant-sub {
    font-size: 0.875rem;
    color: #64748b;
}

.server-status {
    text-align: right;
    display: none;
}

@media (min-width: 640px) {
    .server-status {
        display: block;
    }
}

.status-pill {
    font-size: 0.75rem;
    font-weight: 700;
    color: #16a34a;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background-color: #f0fdf4;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}

.payment-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}

@media (min-width: 640px) {
    .form-row.grid-cols-12 {
        grid-template-columns: repeat(12, 1fr);
    }
    .form-row.grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    .col-main {
        grid-column: span 8 / span 8;
    }
    .col-side {
        grid-column: span 4 / span 4;
    }
}

.input-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.05em;
    margin-bottom: 0.375rem;
}

.optional {
    color: #cbd5e1;
    font-weight: 400;
    text-transform: none;
}

.input-group {
    position: relative;
}

.currency-prefix {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-family: 'Merriweather', serif;
    font-size: 1.125rem;
}

.input-field, .input-field-large, .select-field {
    width: 100%;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    color: #1e293b;
}

.input-field-large {
    padding-left: 3rem;
    padding-right: 1rem;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    font-size: 1.125rem;
    font-weight: 700;
}

.input-field {
    padding: 0.625rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.select-field {
    padding: 0.75rem 1rem;
    font-weight: 700;
}

.input-field:focus, .input-field-large:focus, .select-field:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    background-color: #ffffff;
    transform: translateY(-1px);
}

.error-box {
    background-color: #fef2f2;
    color: #dc2626;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-left: 4px solid #ef4444;
    animation: fadeIn 0.3s ease-out;
}

.submit-section {
    padding-top: 1rem;
}

.submit-btn {
    width: 100%;
    padding: 1rem;
    background-color: #0b1120;
    color: white;
    font-weight: 700;
    border-radius: 0.75rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    position: relative;
    overflow: hidden;
}

.submit-btn:hover {
    background-color: #1a253a;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.submit-btn:active {
    transform: translateY(0);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.pane-footer {
    height: 3rem;
    background-color: #ffffff;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
    font-size: 0.625rem;
    color: #94a3b8;
    font-weight: 600;
    letter-spacing: 0.05em;
}

/* --- Keyframe Animations --- */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInLeft {
    from { 
        opacity: 0; 
        transform: translateX(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

@keyframes slideUpPanel {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

@keyframes pulseSubtle {
    0%, 100% { opacity: 0.2; }
    50% { opacity: 0.4; }
}
</style>
