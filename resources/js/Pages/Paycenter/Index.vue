<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    amount: 500,
    order_id: '',
    description: '',
    email: '',
    phone: '',
});

const submit = () => {
    // This will POST to the backend and trigger redirect to Paycenter
    form.post(route('pay.initiate'));
};
</script>

<template>
    <Head title="Paycenter Payment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Paycenter Payment Gateway
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Initiate Payment</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Fill in the details below to start a payment. You'll be redirected to Paycenter's secure payment page.
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Amount (LKR) *
                                </label>
                                <input
                                    type="number"
                                    id="amount"
                                    v-model="form.amount"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                    min="1"
                                    step="0.01"
                                >
                                <div v-if="form.errors.amount" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.amount }}
                                </div>
                            </div>

                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Order ID
                                </label>
                                <input
                                    type="text"
                                    id="order_id"
                                    v-model="form.order_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Leave empty to auto-generate"
                                >
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Optional - A unique reference will be generated if left empty
                                </p>
                                <div v-if="form.errors.order_id" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.order_id }}
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Payment for..."
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="form.email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="customer@example.com"
                                    >
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Phone
                                    </label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        v-model="form.phone"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="+94771234567"
                                    >
                                </div>
                            </div>

                            <div v-if="form.errors.payment" class="p-3 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-md">
                                {{ form.errors.payment }}
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >
                                    <span v-if="form.processing">Processing...</span>
                                    <span v-else>Proceed to Payment</span>
                                </button>

                                <p v-if="!form.processing" class="text-xs text-gray-500 dark:text-gray-400">
                                    You'll be redirected to Paycenter's secure payment page
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">How it works:</h4>
                    <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800 dark:text-blue-300">
                        <li>Fill in the payment details above</li>
                        <li>Click "Proceed to Payment" to go to Paycenter's secure page</li>
                        <li>Complete your payment on Paycenter's platform</li>
                        <li>You'll be redirected back with the payment result</li>
                    </ol>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
