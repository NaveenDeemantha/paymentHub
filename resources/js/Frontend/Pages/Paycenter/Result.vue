<template>
    <Head title="Payment Status" />

    <div class="overlay-container">
        
        <!-- Background Pattern -->
        <div class="bg-pattern"></div>
        <div class="bg-glow"></div>

        <!-- Receipt Card -->
        <div class="receipt-card">
            
            <!-- Status Header -->
            <div class="status-section">
                <div class="status-icon-wrapper" :class="success ? 'bg-success' : 'bg-failure'">
                    <svg v-if="success" class="status-icon text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <svg v-else class="status-icon text-failure" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>

                <h1 class="status-title">
                    {{ success ? 'Payment Successful' : 'Payment Failed' }}
                </h1>
                <p class="status-message">
                    {{ success 
                        ? 'Thank you! Your transaction has been processed successfully.' 
                        : 'We could not process your transaction. Please try again.' 
                    }}
                </p>
            </div>

            <!-- Receipt Details -->
            <div class="details-section">
                <div class="detail-row">
                    <span class="detail-label">Reference ID</span>
                    <span class="detail-value mono">{{ order_id || 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ new Date().toLocaleDateString() }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount Paid</span>
                    <span class="detail-total">{{ currency }} {{ amount }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="action-section">
                <Link href="/" class="home-btn mt-3">
                    Return to Home
                </Link>
                <div v-if="success" class="download-container">
                    <button class="download-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Download Receipt
                    </button>
                </div>
            </div>

        </div>

    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    success: Boolean,
    order_id: String,
    amount: String,
    currency: String,
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

/* Container */
.overlay-container {
    position: fixed;
    inset: 0;
    background-color: #0b1120;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    font-family: 'Inter', sans-serif;
}

/* Backgrounds */
.bg-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
    opacity: 0.2;
    pointer-events: none;
}

.bg-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    max-width: 32rem;
    height: 32rem;
    background-color: rgba(59, 130, 246, 0.1);
    filter: blur(100px);
    border-radius: 9999px;
    pointer-events: none;
}


/* Card */
.receipt-card {
    position: relative;
    width: 100%;
    max-width: 28rem;
    background-color: #ffffff;
    border-radius: 1.5rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Status Section */
.status-section {
    background-color: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.status-icon-wrapper {
    width: 5rem;
    height: 5rem;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    ring: 4px solid #ffffff;
    animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.2s backwards;
}

.bg-success { background-color: #dcfce7; }
.bg-failure { background-color: #fee2e2; }

.status-icon {
    width: 2.5rem;
    height: 2.5rem;
}

.text-success { color: #16a34a; }
.text-failure { color: #dc2626; }

.status-title {
    font-family: 'Merriweather', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.5rem;
    animation: fadeInUp 0.5s ease-out 0.3s backwards;
}

.status-message {
    font-size: 0.875rem;
    color: #64748b;
    max-width: 20rem;
    line-height: 1.5;
    animation: fadeInUp 0.5s ease-out 0.4s backwards;
}

/* Details Section */
.details-section {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 0.75rem;
    border-bottom: 1px dashed #e2e8f0;
    animation: fadeInUp 0.5s ease-out backwards;
}

.detail-row:nth-child(1) { animation-delay: 0.5s; }
.detail-row:nth-child(2) { animation-delay: 0.6s; }
.detail-row:nth-child(3) { animation-delay: 0.7s; }

.detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.detail-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.05em;
}

.detail-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: #334155;
}

.mono {
    font-family: monospace;
}

.detail-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
}

/* Action Section */
.action-section {
    padding: 1.5rem;
    background-color: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    animation: fadeIn 0.5s ease-out 0.8s backwards;
}

.home-btn {
    width: 100%;
    display: block;
    padding: 0.875rem;
    background-color: #0b1120;
    color: #ffffff;
    font-weight: 700;
    border-radius: 0.75rem;
    text-align: center;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-decoration: none;
}

.home-btn:hover {
    background-color: #1a253a;
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.download-container {
    text-align: center;
}

.download-btn {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: color 0.2s;
}

.download-btn:hover {
    color: #475569;
}

@keyframes popIn {
    from { 
        opacity: 0; 
        transform: scale(0.9) translateY(10px); 
    }
    to { 
        opacity: 1; 
        transform: scale(1) translateY(0); 
    }
}

@keyframes fadeInUp {
    from { 
        opacity: 0; 
        transform: translateY(10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
