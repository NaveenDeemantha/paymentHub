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

<template>
    <Head title="Paycenter Payment" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <Link href="/" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-800">PayCenter</h1>
                </Link>
                <!-- <div class="flex items-center space-x-4">
                    <Link v-if="$page.props.auth?.user" :href="route('dashboard')" class="text-gray-600 hover:text-gray-900">
                        Dashboard
                    </Link>
                    <Link v-else href="/" class="text-gray-600 hover:text-gray-900">
                        Back to Home
                    </Link>
                </div> -->
            </div>
        </header>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold mb-2 text-gray-900">Sampath Bank Payment</h3>
                            <p class="text-sm text-gray-600">
                                Fill in the details below to start a payment. You'll be redirected to Paycenter's secure payment page.
                            </p>
                        </div>

                        <form :action="route('pay.initiate')" method="POST" class="space-y-4">
                            <input type="hidden" name="_token" :value="csrfToken">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                        Amount *
                                    </label>
                                    <input
                                        type="number"
                                        id="amount"
                                        name="amount"
                                        v-model="form.amount"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                        min="1"
                                        step="0.01"
                                    >
                                </div>

                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                                        Currency *
                                    </label>
                                    <select
                                        id="currency"
                                        name="currency"
                                        v-model="form.currency"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    >
                                        <option value="LKR">LKR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Order ID
                                </label>
                                <input
                                    type="text"
                                    id="order_id"
                                    name="order_id"
                                    v-model="form.order_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Leave empty to auto-generate"
                                >
                                <p class="text-xs text-gray-500 mt-1">
                                    Optional - A unique reference will be generated if left empty
                                </p>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    v-model="form.description"
                                    rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Payment for..."
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        v-model="form.email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="customer@example.com"
                                    >
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Phone
                                    </label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        v-model="form.phone"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="+94771234567"
                                    >
                                </div>
                            </div>

                            <div v-if="$page.props.errors && $page.props.errors.payment" class="p-3 bg-red-100 text-red-700 rounded-md">
                                {{ $page.props.errors.payment }}
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Proceed to Payment
                                </button>

                                <p class="text-xs text-gray-500">
                                    You'll be redirected to Paycenter's secure payment page
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">How it works:</h4>
                    <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800">
                        <li>Fill in the payment details above</li>
                        <li>Click "Proceed to Payment" to go to Paycenter's secure page</li>
                        <li>Complete your payment on Paycenter's platform</li>
                        <li>You'll be redirected back with the payment result</li>
                    </ol>
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
