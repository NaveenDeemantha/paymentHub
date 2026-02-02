<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    success: Boolean,
    message: String,
    transaction: Object,
});
</script>

<template>
    <Head title="Payment Result" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Payment Result
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-gray-900 dark:text-gray-100">
                        <!-- Success State -->
                        <div v-if="success" class="text-center">
                            <div class="mb-6">
                                <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                                Payment Successful!
                            </h3>

                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                {{ message }}
                            </p>

                            <div v-if="transaction" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left mb-6">
                                <h4 class="font-semibold mb-4 text-lg">Transaction Details</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Order Reference:</span>
                                        <span class="font-mono font-semibold">{{ transaction.client_ref }}</span>
                                    </div>
                                    <div v-if="transaction.transaction_id" class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Transaction ID:</span>
                                        <span class="font-mono font-semibold">{{ transaction.transaction_id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Amount:</span>
                                        <span class="font-semibold">{{ transaction.currency }} {{ transaction.amount }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full text-sm font-medium">
                                            {{ transaction.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Failure State -->
                        <div v-else class="text-center">
                            <div class="mb-6">
                                <svg class="w-20 h-20 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-2">
                                Payment Failed
                            </h3>

                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                {{ message || 'The payment could not be processed.' }}
                            </p>

                            <div v-if="transaction" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-left mb-6">
                                <h4 class="font-semibold mb-4 text-lg">Transaction Details</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Order Reference:</span>
                                        <span class="font-mono font-semibold">{{ transaction.client_ref }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full text-sm font-medium">
                                            {{ transaction.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                            <Link
                                :href="route('pay.index')"
                                class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                <span v-if="success">Make Another Payment</span>
                                <span v-else>Try Again</span>
                            </Link>

                            <Link
                                :href="route('dashboard')"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-gray-800 dark:text-gray-200 uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                Back to Dashboard
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Support Info -->
                <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <strong>Need help?</strong> If you have questions about this transaction, please contact support with your order reference number.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
