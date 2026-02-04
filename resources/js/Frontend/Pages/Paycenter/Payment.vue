<template>
    <Head title="Redirecting to Payment..." />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="page-title">
                Redirecting to Payment Gateway
            </h2>
        </template>

        <div class="main-content">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="spinner-wrapper">
                            <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="spinner-track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="spinner-fill" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="message-primary">Redirecting to Paycenter's secure payment page...</p>
                        <p class="message-secondary">Please wait...</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// NOTE: This component is no longer used with Hosted Redirect method
// Users are redirected directly to Paycenter's page, not shown in iframe
// Keeping this file for reference, but it won't be rendered

defineProps({
    paymentUrl: String,
});
</script>

<style scoped>
.page-title {
    font-weight: 600;
    font-size: 1.25rem;
    color: #1f2937;
    line-height: 1.75rem;
}

.main-content {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 80rem;
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

.card {
    background-color: #ffffff;
    overflow: hidden;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
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
    text-align: center;
}

.spinner-wrapper {
    margin-bottom: 1rem;
}

.spinner {
    animation: spin 1s linear infinite;
    height: 3rem;
    width: 3rem;
    margin: 0 auto;
    color: #4f46e5;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.spinner-track {
    opacity: 0.25;
}

.spinner-fill {
    opacity: 0.75;
}

.message-primary {
    font-size: 1.125rem;
    font-weight: 500;
}

.message-secondary {
    font-size: 0.875rem;
    color: #4b5563;
    margin-top: 0.5rem;
}

/* Dark mode support (if AuthenticatedLayout provides it) */
@media (prefers-color-scheme: dark) {
    .page-title {
        color: #e5e7eb;
    }
    
    .card {
        background-color: #1f2937;
    }
    
    .card-body {
        color: #f3f4f6;
    }
    
    .message-secondary {
        color: #9ca3af;
    }
}
</style>
