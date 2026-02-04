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

<template>
    <Head title="Redirecting to Payment..." />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Redirecting to Payment Gateway
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <div class="mb-4">
                            <svg class="animate-spin h-12 w-12 mx-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-lg font-medium">Redirecting to Paycenter's secure payment page...</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Please wait...</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
