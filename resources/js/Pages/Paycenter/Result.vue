<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    success: Boolean,
    message: String,
    transaction: Object,
});
</script>

<template>
    <Head title="Payment Result" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-800">PayCenter</h1>
                </div>
            </div>
        </header>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-8 text-gray-900">
                        <!-- Success State -->
                        <div v-if="success" class="text-center">
                            <div class="mb-6">
                                <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold text-green-600 mb-2">
                                Payment Successful!
                            </h3>

                            <p class="text-gray-600 mb-6">
                                {{ message }}
                            </p>

                            <div v-if="transaction" class="bg-gray-50 rounded-lg p-6 text-left mb-6">
                                <h4 class="font-semibold mb-4 text-lg">Transaction Details</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Order Reference:</span>
                                        <span class="font-mono font-semibold">{{ transaction.client_ref }}</span>
                                    </div>
                                    <div v-if="transaction.transaction_id" class="flex justify-between">
                                        <span class="text-gray-600">Transaction ID:</span>
                                        <span class="font-mono font-semibold">{{ transaction.transaction_id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Amount:</span>
                                        <span class="font-semibold">{{ transaction.currency }} {{ transaction.amount }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
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

                            <h3 class="text-2xl font-bold text-red-600 mb-2">
                                Payment Failed
                            </h3>

                            <p class="text-gray-600 mb-6">
                                {{ message || 'The payment could not be processed.' }}
                            </p>

                            <div v-if="transaction" class="bg-gray-50 rounded-lg p-6 text-left mb-6">
                                <h4 class="font-semibold mb-4 text-lg">Transaction Details</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Order Reference:</span>
                                        <span class="font-mono font-semibold">{{ transaction.client_ref }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                            {{ transaction.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-center mt-8">
                            <Link
                                :href="route('pay.index')"
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <span v-if="success">Make Another Payment</span>
                                <span v-else>Try Again</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Support Info -->
                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Need help?</strong> If you have questions about this transaction, please contact support with your order reference number.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-20">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ new Date().getFullYear() }} PayCenter. All rights reserved.</p>
                <p class="text-gray-400 mt-2">Secure payment gateway for your business</p>
            </div>
        </footer>
    </div>
</template>
