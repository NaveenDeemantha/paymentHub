<template>
    <Head title="Dialog Genie Business" />

    <div class="page-container">
        <div class="summary-panel">
            <div class="panel-texture"></div>

            <div class="panel-content">
                <Link href="/" class="back-link group">
                    <div class="icon-box">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </div>
                    <span class="link-text">Back to Home</span>
                </Link>

                <div class="hero-section">
                    <p class="overline">Proceed to Dialog Payment</p>
                    <h1 class="main-title">Genie Business <br> Checkout</h1>
                </div>

                <div class="security-footer">
                    <div class="ssl-badge preview-badge">
                        <div class="check-circle preview-circle">
                            <svg class="check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <p class="badge-title">Dedicated Integration Surface</p>
                            <p class="badge-subtitle">Genie now runs on its own backend flow.</p>
                        </div>
                    </div>

                    <p class="terms-text">
                        This screen uses the separate Genie Business backend. Payment initiation, callback handling, and status verification are wired independently from Sampath.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <div class="mobile-header">
                <Link href="/" class="mobile-back">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>Back</span>
                </Link>
                <span class="font-serif">Genie Business</span>
            </div>

            <div class="form-wrapper">
                <div class="form-card">
                    <div class="form-header">
                        <div class="merchant-info">
                            <div class="merchant-logo genie-logo-shell">
                                <img :src="'/images/' + 'genie-business.png'" alt="Dialog Genie Business" class="merchant-logo-img">
                            </div>
                            <div>
                                <h2 class="merchant-name">Dialog Genie Business</h2>
                                <p class="merchant-sub">Dedicated wallet and business payment integration</p>
                            </div>
                        </div>
                        <div class="server-status">
                            <p :class="['status-pill', integrationReady ? 'status-ready' : 'status-preview']">
                                {{ integrationReady ? 'Config Ready' : 'Config Missing' }}
                            </p>
                        </div>
                    </div>

                    <!-- <div class="integration-banner">
                        <div>
                            <p class="banner-title">Separate Genie payment flow is active</p>
                            <p class="banner-text">This form submits into the Genie Business controller and service. Add the live endpoint and credentials in the environment to complete the external gateway handoff.</p>
                        </div>
                    </div> -->

                    <form :action="route('genie-business.initiate')" method="POST" class="payment-form">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <div class="form-row grid-cols-2">
                            <div>
                                <label class="input-label">Student Name</label>
                                <input type="text" name="student_name" v-model="form.student_name" class="input-field" placeholder="Full Name" required>
                            </div>
                            <div>
                                <label class="input-label">Student ID</label>
                                <input type="text" name="student_id" v-model="form.student_id" class="input-field" placeholder="Student ID" required>
                            </div>
                        </div>

                        <div class="form-row grid-cols-12">
                            <div class="col-main">
                                <label class="input-label">Program</label>
                                <input type="text" name="program" v-model="form.program" class="input-field" placeholder="Program Name" required>
                            </div>
                            <div class="col-side">
                                <label class="input-label">NIC / Passport</label>
                                <input type="text" name="nic_passport" v-model="form.nic_passport" class="input-field" placeholder="NIC or Passport ID" required>
                            </div>
                        </div>

                        <div class="form-row grid-cols-2">
                            <div>
                                <label class="input-label">Email Address</label>
                                <input type="email" name="email" v-model="form.email" @blur="validateEmail"
                                    :class="['input-field', { 'input-error': errors.email }]" placeholder="name@example.com" required>
                                <p v-if="errors.email" class="error-message">{{ errors.email }}</p>
                            </div>
                            <div>
                                <label class="input-label">Mobile Number</label>
                                <input type="tel" name="phone" v-model="form.phone" @blur="validatePhone"
                                    :class="['input-field', { 'input-error': errors.phone }]" placeholder="+94 7X XXX XXXX" required>
                                <p v-if="errors.phone" class="error-message">{{ errors.phone }}</p>
                            </div>
                        </div>

                        <div class="form-row">
                            <div>
                                <label class="input-label">Reference <span class="optional">(Optional)</span></label>
                                <input type="text" name="reference" v-model="form.reference" class="input-field" placeholder="Any Reference / Remarks">
                            </div>
                        </div>

                        <div class="form-row grid-cols-12">
                            <div class="col-main">
                                <label class="input-label">Amount</label>
                                <div class="input-group">
                                    <span class="currency-prefix">Rs.</span>
                                    <input type="number" name="amount" v-model="form.amount" class="input-field-large" placeholder="0.00" step="0.01" min="1" required>
                                </div>
                            </div>
                            <div class="col-side">
                                <label class="input-label">Currency</label>
                                <select name="currency" v-model="form.currency" class="select-field">
                                    <option value="LKR">LKR</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="$page.props.errors && $page.props.errors.payment" class="error-box">
                            {{ $page.props.errors.payment }}
                        </div>

                        <div class="preview-note" :class="integrationReady ? 'ready-note' : ''">
                            <span v-if="integrationReady">
                                The Genie Business backend is enabled. Submitting this form will create a transaction record and hand off to the external Genie flow.
                            </span>
                            <span v-else>
                                This backend path is implemented, but the Genie Business environment values are still missing on the server.
                            </span>
                        </div>

                        <div class="submit-section">
                            <button type="submit" :class="['submit-btn', integrationReady ? 'live-submit' : 'preview-submit']"
                                :disabled="!integrationReady" @click="validateForm">
                                <span>{{ integrationReady ? 'Proceed to Genie Business' : 'Configuration Required' }}</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="pane-footer">
                DEDICATED DIALOG GENIE BUSINESS CHECKOUT
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps({
    integrationReady: Boolean,
});

const form = useForm({
    amount: 500,
    currency: 'LKR',
    student_name: '',
    student_id: '',
    program: '',
    nic_passport: '',
    reference: '',
    email: '',
    phone: '',
});

const errors = ref({
    email: '',
    phone: '',
});

const csrfToken = ref('');

const validateEmail = () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!form.email) {
        errors.value.email = 'Email address is required';
        return false;
    }

    if (!emailRegex.test(form.email)) {
        errors.value.email = 'Please enter a valid email address';
        return false;
    }

    errors.value.email = '';
    return true;
};

const validatePhone = () => {
    const digitsOnly = form.phone.replace(/\D/g, '');

    if (!form.phone) {
        errors.value.phone = 'Mobile number is required';
        return false;
    }

    const sriLankaRegex = /^(?:\+94|0)?[1-9]\d{8}$/;

    if (!sriLankaRegex.test(digitsOnly)) {
        errors.value.phone = 'Please enter a valid Sri Lankan mobile number';
        return false;
    }

    const normalizedNumber = digitsOnly.replace(/^(?:94|0)/, '');
    if (!normalizedNumber.startsWith('7')) {
        errors.value.phone = 'Mobile number should start with 7';
        return false;
    }

    errors.value.phone = '';
    return true;
};

const validateForm = (event) => {
    const emailValid = validateEmail();
    const phoneValid = validatePhone();

    if (!emailValid || !phoneValid) {
        event.preventDefault();
        return false;
    }

    return true;
};

onMounted(() => {
    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.font-serif { font-family: 'Merriweather', serif; }

.page-container {
    height: 100vh;
    width: 100%;
    background-color: #f8fafc;
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

.summary-panel {
    display: none;
}

@media (min-width: 768px) {
    .summary-panel {
        display: flex;
        width: 35%;
        background: linear-gradient(180deg, #07111f 0%, #0f172a 100%);
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
    opacity: 0.18;
    background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
    background-size: 20px 20px;
}

.panel-content {
    position: relative;
    z-index: 10;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    animation: fadeIn 1s ease-out;
}

@media (min-width: 1024px) {
    .panel-content {
        padding: 2rem;
    }
}

.back-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
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
    background-color: rgba(255, 255, 255, 0.18);
}

.icon {
    width: 1.25rem;
    height: 1.25rem;
    color: #60a5fa;
}

.link-text {
    font-weight: 500;
    letter-spacing: 0.025em;
}

.hero-section {
    margin-top: 0.5rem;
    animation: slideInLeft 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) 0.1s backwards;
}

.overline {
    color: #60a5fa;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 0.375rem;
}

.main-title {
    font-family: 'Merriweather', serif;
    font-size: 1.5rem;
    font-weight: 500;
    line-height: 1.2;
}

.security-footer {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    animation: fadeIn 1s ease-out 0.5s backwards;
}

.ssl-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(4px);
}

.preview-badge {
    background-color: rgba(37, 99, 235, 0.12);
}

.check-circle {
    padding: 0.5rem;
    border-radius: 9999px;
    animation: pulseSubtle 3s infinite;
}

.preview-circle {
    background-color: rgba(37, 99, 235, 0.18);
}

.check-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: #93c5fd;
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
    color: #94a3b8;
    max-width: 20rem;
}

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
        animation: none;
    }
}

@media (min-width: 1024px) {
    .form-panel {
        width: 70%;
    }
}

.mobile-header {
    height: 3rem;
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
    padding: 1rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

@media (min-width: 768px) {
    .form-wrapper {
        padding: 1.5rem;
    }
}

.form-card {
    width: 100%;
    max-width: 42rem;
    margin: 0 auto;
    animation: fadeIn 0.6s ease-out 0.2s backwards;
}

.form-header {
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 0.75rem;
}

.merchant-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.merchant-logo {
    width: 5rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* .genie-logo-shell {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
} */

.merchant-logo-img {
    width: 5rem;
    height: 3rem;
    object-fit: contain;
}

.merchant-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
}

.merchant-sub {
    font-size: 0.8125rem;
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
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}

.status-ready {
    color: #15803d;
    background-color: #f0fdf4;
}

.status-preview {
    color: #1d4ed8;
    background-color: #eff6ff;
}

.integration-banner {
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: 0.75rem;
    border: 1px solid #dbeafe;
    background: linear-gradient(180deg, #f8fbff 0%, #eff6ff 100%);
}

.banner-title {
    font-size: 0.9375rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.25rem;
}

.banner-text {
    font-size: 0.8125rem;
    line-height: 1.5;
    color: #475569;
}

.payment-form {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
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
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
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
    padding-top: 0.625rem;
    padding-bottom: 0.625rem;
    font-size: 1rem;
    font-weight: 700;
}

.input-field {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.select-field {
    padding: 0.625rem 0.75rem;
    font-weight: 700;
}

.input-field:focus, .input-field-large:focus, .select-field:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    background-color: #ffffff;
    transform: translateY(-1px);
}

.preview-note {
    background-color: #f8fafc;
    color: #475569;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    border-left: 4px solid #60a5fa;
}

.ready-note {
    border-left-color: #22c55e;
}

.error-box {
    background-color: #fef2f2;
    color: #b91c1c;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    border: 1px solid #fecaca;
}

.input-error {
    border-color: #ef4444;
    background-color: #fef2f2;
}

.error-message {
    margin-top: 0.375rem;
    font-size: 0.75rem;
    color: #dc2626;
}

.submit-section {
    padding-top: 0.5rem;
}

.submit-btn {
    width: 100%;
    padding: 0.75rem;
    font-weight: 700;
    border-radius: 0.75rem;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.preview-submit {
    background-color: #cbd5e1;
    color: #475569;
}

.live-submit {
    background-color: #0f172a;
    color: #ffffff;
}

.live-submit:hover {
    background-color: #1d4ed8;
}

.submit-btn:disabled {
    cursor: not-allowed;
}

.pane-footer {
    height: 2.5rem;
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
    50% { opacity: 0.45; }
}
</style>
