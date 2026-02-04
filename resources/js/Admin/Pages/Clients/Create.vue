<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    domain: '',
    return_url: '',
    webhook_url: '',
});

const submit = () => {
    form.post(route('clients.store'));
};
</script>

<template>
    <Head title="Create Client" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('clients.index')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                    ← Back
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Create New Client
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Client Name *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                    placeholder="e.g., My E-commerce Store"
                                >
                                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div>
                                <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Domain *
                                </label>
                                <input
                                    type="text"
                                    id="domain"
                                    v-model="form.domain"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                    placeholder="e.g., mystore.com"
                                >
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Just the domain name, without http:// or https://
                                </p>
                                <div v-if="form.errors.domain" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.domain }}
                                </div>
                            </div>

                            <div>
                                <label for="return_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Return URL *
                                </label>
                                <input
                                    type="url"
                                    id="return_url"
                                    v-model="form.return_url"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required
                                    placeholder="https://mystore.com/payment/result"
                                >
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Where to redirect customers after payment completion
                                </p>
                                <div v-if="form.errors.return_url" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.return_url }}
                                </div>
                            </div>

                            <div>
                                <label for="webhook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Webhook URL (Optional)
                                </label>
                                <input
                                    type="url"
                                    id="webhook_url"
                                    v-model="form.webhook_url"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="https://mystore.com/webhooks/payment"
                                >
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Receive real-time payment notifications (recommended)
                                </p>
                                <div v-if="form.errors.webhook_url" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.webhook_url }}
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">What happens next?</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm text-blue-800 dark:text-blue-300">
                                    <li>Unique API key will be generated automatically</li>
                                    <li>Webhook secret will be created (if webhook URL provided)</li>
                                    <li>Client will be able to integrate using the API key</li>
                                </ul>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    <span v-if="form.processing">Creating...</span>
                                    <span v-else>Create Client</span>
                                </button>

                                <Link
                                    :href="route('clients.index')"
                                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
