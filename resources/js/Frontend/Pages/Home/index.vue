<template>
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

        <!-- Hero Section -->
        <main class="main-content">
            <div class="hero-section">
                <h2 class="hero-title">
                    Secure Payment Gateway
                </h2>
                <p class="hero-description">
                    Choose your preferred bank and make secure payments instantly with our trusted payment platform.
                </p>
            </div>

            <!-- Payment Options -->
            <div class="payment-options">
                <h3 class="section-title">
                    Select Your Bank
                </h3>
                
                <div class="bank-grid">
                    <!-- Sampath Bank Card -->
                    <Link :href="route('pay.index')" class="bank-card active">
                        <div class="card-content">
                            <div class="bank-icon green">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            
                            <h4 class="bank-name">
                                Sampath Bank
                            </h4>
                            
                            <p class="bank-description">
                                Fast and secure payments with Sampath Bank's trusted payment gateway.
                            </p>
                            
                            <div class="cta-link">
                                <span>Pay Now</span>
                                <svg class="arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>
                            
                            <!-- Features -->
                            <ul class="features-list">
                                <li class="feature-item">
                                    <svg class="check-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Instant Processing
                                </li>
                                <li class="feature-item">
                                    <svg class="check-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Secure Transactions
                                </li>
                                <li class="feature-item">
                                    <svg class="check-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    24/7 Support
                                </li>
                            </ul>
                        </div>
                    </Link>

                    <!-- Commercial Bank Card -->
                    <div class="bank-card disabled">
                        <div class="coming-soon-badge">
                            COMING SOON
                        </div>
                        
                        <div class="card-content">
                            <div class="bank-icon orange">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            
                            <h4 class="bank-name">
                                Commercial Bank
                            </h4>
                            
                            <p class="bank-description">
                                Reliable payment solutions with Commercial Bank. Available soon!
                            </p>
                            
                            <div class="cta-link inactive">
                                <span>Available Soon</span>
                                <svg class="lock-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            
                            <!-- Features -->
                            <ul class="features-list inactive">
                                <li class="feature-item">
                                    <svg class="check-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Multiple Payment Methods
                                </li>
                                <li class="feature-item">
                                    <svg class="check-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Enhanced Security
                                </li>
                                <li class="feature-item">
                                    <svg class="check-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Wide Acceptance
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <h3 class="section-title">
                    Why Choose PayCenter?
                </h3>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h4 class="feature-title">Secure Payments</h4>
                        <p class="feature-description">Bank-grade encryption for all transactions</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h4 class="feature-title">Lightning Fast</h4>
                        <p class="feature-description">Process payments in seconds</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon purple">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h4 class="feature-title">24/7 Support</h4>
                        <p class="feature-description">Always here to help you</p>
                    </div>
                </div>
            </div>
        </main>

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
import { Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
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
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1rem 4rem;
}

/* Hero Section */
.hero-section {
    text-align: center;
    margin-bottom: 4rem;
    padding-top: 4rem;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1rem;
}

.hero-description {
    font-size: 1.25rem;
    color: #4b5563;
    max-width: 42rem;
    margin: 0 auto;
}

/* Payment Options */
.payment-options {
    max-width: 80rem;
    margin: 0 auto;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
    color: #1f2937;
    margin-bottom: 2rem;
}

.bank-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 768px) {
    .bank-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Bank Card */
.bank-card {
    background-color: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    position: relative;
    text-decoration: none;
    display: block;
}

.bank-card.active:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    transform: translateY(-0.5rem);
    border-color: #16a34a;
}

.bank-card.disabled {
    opacity: 0.75;
    cursor: not-allowed;
    border-color: #e5e7eb;
}

.coming-soon-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background-color: #eab308;
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
}

.card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* Bank Icon */
.bank-icon {
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    transition: transform 0.3s ease;
}

.bank-card.active:hover .bank-icon {
    transform: scale(1.1);
}

.bank-icon.green {
    background: linear-gradient(to bottom right, #4ade80, #16a34a);
}

.bank-icon.orange {
    background: linear-gradient(to bottom right, #fb923c, #dc2626);
}

.bank-icon .icon {
    width: 3rem;
    height: 3rem;
    color: #ffffff;
}

.bank-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.75rem;
}

.bank-description {
    color: #4b5563;
    margin-bottom: 1.5rem;
}

/* CTA Link */
.cta-link {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #059669;
    transition: color 0.15s ease;
}

.bank-card.active:hover .cta-link {
    color: #047857;
}

.cta-link.inactive {
    color: #9ca3af;
}

.arrow-icon,
.lock-icon {
    width: 1.25rem;
    height: 1.25rem;
    margin-left: 0.5rem;
    transition: transform 0.15s ease;
}

.bank-card.active:hover .arrow-icon {
    transform: translateX(0.5rem);
}

/* Features List */
.features-list {
    margin-top: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #4b5563;
    list-style: none;
    padding: 0;
}

.features-list.inactive {
    color: #6b7280;
}

.feature-item {
    display: flex;
    align-items: center;
}

.check-icon {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
    color: #10b981;
}

.features-list.inactive .check-icon {
    color: #9ca3af;
}

/* Features Section */
.features-section {
    margin-top: 5rem;
    max-width: 96rem;
    margin-left: auto;
    margin-right: auto;
}

.features-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 768px) {
    .features-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.feature-card {
    text-align: center;
}

.feature-icon {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.feature-icon.blue {
    background-color: #dbeafe;
}

.feature-icon.green {
    background-color: #d1fae5;
}

.feature-icon.purple {
    background-color: #e9d5ff;
}

.feature-icon .icon {
    width: 2rem;
    height: 2rem;
}

.feature-icon.blue .icon {
    color: #2563eb;
}

.feature-icon.green .icon {
    color: #059669;
}

.feature-icon.purple .icon {
    color: #9333ea;
}

.feature-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.feature-description {
    color: #4b5563;
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
