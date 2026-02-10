<template>
    <div class="page-container">


        <div class="brand-panel">
            <div class="brand-bg-gradient"></div>
            <div class="brand-glow"></div>
            <div class="brand-content">
                <!-- Brand Identity -->
                <div class="brand-header">
                     <div class="brand-logo-wrapper">
                        <div class="brand-logo-icon">
                            <svg class="icon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="brand-name">PayCenter</span>
                    </div>

                    <h1 class="hero-title">
                        Secure <br>
                        <span class="highlight-gold">Payments</span>
                    </h1>
                    <p class="hero-subtitle">
                        The official payment gateway for <br> <span class="text-white-soft">Winfield School of Business</span>.
                    </p>
                </div>

                <!-- Footer / Trust Badges -->
                <div class="brand-footer">
                    <div class="trust-badge">
                         <svg class="icon-small-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                         <span>AES-256 ENCRYPTED</span>
                    </div>
                     <div class="separator-dot"></div>
                     <span class="trust-text">PCI DSS COMPLIANT</span>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: Action -->
        <div class="action-panel">

            <!-- Top Bar -->
            <div class="top-nav">
                <div class="nav-links">
                    <span>Help & Support</span>
                    <div class="nav-separator"></div>
                    <span class="nav-active">English</span>
                </div>
            </div>

            <!-- Content Container -->
            <div class="content-wrapper">

                <h2 class="section-title">Select Payment Method</h2>

                <div class="cards-grid">

                    <!-- SAMPATH CARD -->
                    <Link :href="route('pay.index')" class="payment-card group active-card">
                        <div class="status-badge online">
                            <span class="status-dot animate-pulse"></span> ONLINE
                        </div>

                        <div class="card-logo-wrapper">
                             <img src="/images/sampath-bank-logo.png" alt="Sampath Bank" class="card-logo">
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">Sampath Bank</h3>
                            <p class="card-description">Instant Visa/Mastercard payments via secure gateway.</p>
                        </div>

                        <div class="card-action-btn">
                            Pay Now
                        </div>
                    </Link>

                    <!-- COMMERCIAL CARD -->
                    <div class="payment-card disabled-card">
                        <div class="status-badge soon">
                            SOON
                        </div>

                        <div class="card-logo-wrapper">
                             <img src="/images/commercial-bank-logo.png" alt="Commercial Bank" class="card-logo">
                        </div>

                        <div class="card-content">
                            <h3 class="card-title text-slate-400">Commercial Bank</h3>
                            <p class="card-description text-slate-400">Integration in progress. Available soon.</p>
                        </div>

                        <div class="card-action-btn disabled">
                            Coming Soon
                        </div>
                    </div>
                </div>

                <!-- Mobile Footer -->
                 <div class="mobile-footer">
                    <span>SECURED BY WINFIELD</span>
                </div>

            </div>
        </div>

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
@import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

/* --- Layout Structure --- */
.page-container {
    height: 100vh;
    width: 100%;
    background-color: #0b1120;
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

/* --- Left Panel: Brand --- */
.brand-panel {
    width: 100%;
    height: 30vh;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2rem;
    color: white;
    background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 30px 30px;
}

@media (min-width: 768px) {
    .brand-panel {
        width: 45%;
        height: 100%;
        padding: 3rem;
    }
}

@media (min-width: 1024px) {
    .brand-panel {
        width: 40%;
        padding: 4rem;
    }
}

.brand-bg-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom right, #0b1120, #0f172a, #0b1120);
    opacity: 0.95;
    z-index: 0;
}

.brand-glow {
    position: absolute;
    top: -5rem;
    left: -5rem;
    width: 24rem;
    height: 24rem;
    background-color: #dcb01d;
    border-radius: 9999px;
    mix-blend-mode: overlay;
    filter: blur(100px);
    opacity: 0.1;
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.brand-content {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    height: 100%;
    align-items: flex-start;
}

@media (min-width: 768px) {
    .brand-content {
        height: auto;
        justify-content: center;
    }
}

.brand-logo-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.brand-logo-icon {
    padding: 0.625rem;
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    border-radius: 0.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.icon-gold {
    width: 2rem;
    height: 2rem;
    color: #dcb01d;
}


.brand-name {
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -0.025em;
    background: linear-gradient(to right, #ffffff, #94a3b8);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    animation: fadeIn 1s ease-out;
}

.hero-title {
    font-family: 'Merriweather', serif;
    font-size: 1.875rem;
    font-weight: 500;
    line-height: 1.25;
    margin-bottom: 1rem;
    color: #ffffff;
    animation: slideInLeft 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
}

@media (min-width: 768px) {
    .hero-title {
        font-size: 3rem;
    }
}

.highlight-gold {
    color: #dcb01d;
}

.hero-subtitle {
    color: #94a3b8;
    font-size: 0.875rem;
    max-width: 24rem;
    border-left: 2px solid #dcb01d;
    padding-left: 1rem;
    animation: slideInLeft 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) 0.2s backwards;
}

@media (min-width: 768px) {
    .hero-subtitle {
        font-size: 1.125rem;
    }
}

.text-white-soft {
    color: #e2e8f0;
    font-weight: 600;
}

.brand-footer {
    margin-top: auto;
    display: none;
    align-items: center;
    gap: 1.5rem;
    opacity: 0.6;
    animation: fadeIn 1s ease-out 1s backwards;
}

@media (min-width: 768px) {
    .brand-footer {
        display: flex;
    }
}

/* ... existing trust-badge styles ... */
.trust-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.icon-small-gold {
    width: 1rem;
    height: 1rem;
    color: #dcb01d;
}

.trust-badge span, .trust-text {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
}

.separator-dot {
    height: 0.25rem;
    width: 0.25rem;
    border-radius: 9999px;
    background-color: #475569;
}

/* --- Right Panel: Action --- */
.action-panel {
    width: 100%;
    height: 70vh;
    background-color: #f8fafc;
    position: relative;
    display: flex;
    flex-direction: column;
    border-top-left-radius: 2rem;
    border-top-right-radius: 2rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    animation: slideUpPanel 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
}

@media (min-width: 768px) {
    .action-panel {
        width: 55%; /* Default */
        height: 100%;
        border-radius: 0;
        box-shadow: none;
        animation: none; /* No slide up on desktop, maybe fade */
    }
}
@media (min-width: 1024px) {
    .action-panel {
        width: 60%;
    }
}


.top-nav {
    flex: none;
    height: 4rem;
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    background-color: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}

@media (min-width: 768px) {
    .top-nav {
        height: 5rem;
        padding: 0 3rem;
    }
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
}

.nav-separator {
    height: 1rem;
    width: 1px;
    background-color: #e2e8f0;
}

.nav-active {
    color: #0b1120;
}

.content-wrapper {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

@media (min-width: 768px) {
    .content-wrapper {
        padding: 3rem;
    }
}

@media (min-width: 1024px) {
    .content-wrapper {
        padding: 4rem;
    }
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0b1120;
    margin-bottom: 2rem;
    font-family: 'Merriweather', serif;
    animation: fadeInUp 0.6s ease-out 0.3s backwards;
}

.cards-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    width: 100%;
    max-width: 56rem;
    margin: 0 auto;
}

@media (min-width: 1280px) {
    .cards-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* --- Card Styles --- */
.payment-card {
    position: relative;
    border-radius: 0.75rem;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
    text-decoration: none;
    animation: fadeInUp 0.6s ease-out 0.5s backwards;
}

/* Stagger for second card */
.payment-card:nth-child(2) {
    animation-delay: 0.7s;
}

@media (min-width: 768px) {
    .payment-card {
        padding: 2rem;
    }
}

.payment-card.active-card {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
}

.payment-card.active-card:hover {
    border-color: #16a34a;
    box-shadow: 0 20px 25px -5px rgba(22, 163, 74, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
    transform: translateY(-0.25rem);
}

.payment-card.disabled-card {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    cursor: not-allowed;
    opacity: 0.6;
}

.status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.625rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.status-badge.online {
    background-color: #f0fdf4;
    color: #15803d;
}

.status-badge.soon {
    background-color: #e2e8f0;
    color: #64748b;
}

.status-dot {
    width: 0.375rem;
    height: 0.375rem;
    border-radius: 9999px;
    background-color: #22c55e;
}

.card-logo-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
    padding: 0;
    margin: 0;
}

.active-card:hover .card-logo-wrapper {
    transform: scale(1.05);
}

.card-logo {
    width: 10rem;
    height: 8rem;
    object-fit: contain;
}

.card-content {
    text-align: center;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.card-description {
    font-size: 0.75rem;
    color: #64748b;
    max-width: 200px;
    margin: 0 auto;
    line-height: 1.5;
}

.card-action-btn {
    width: 100%;
    margin-top: 0.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    transition: background-color 0.2s;
}

.active-card .card-action-btn {
    background-color: #0b1120;
    color: #ffffff;
}

.active-card:hover .card-action-btn {
    background-color: #16a34a;
}

.disabled-card .card-action-btn {
    background-color: #e2e8f0;
    color: #94a3b8;
}

.mobile-footer {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
    opacity: 0.4;
}

@media (min-width: 768px) {
    .mobile-footer {
        display: none;
    }
}

.mobile-footer span {
    font-size: 0.625rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    color: #0b1120;
}

/* --- Animations --- */
@keyframes pulse {
    0%, 100% { opacity: 0.1; }
    50% { opacity: 0.2; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUpPanel {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

</style>
