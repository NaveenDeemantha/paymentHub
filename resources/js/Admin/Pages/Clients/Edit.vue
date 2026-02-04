<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    client: Object,
});

const form = useForm({
    name: props.client.name,
    domain: props.client.domain,
    return_url: props.client.return_url,
    webhook_url: props.client.webhook_url || '',
    is_active: props.client.is_active,
});

const submit = () => {
    form.put(route('clients.update', props.client.id));
};
</script>

<template>
    <Head title="Edit Client" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('clients.show', client.id)" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                    ← Back
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Edit Client: {{ client.name }}
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
                                >
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
                                >
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
                                >
                                <div v-if="form.errors.webhook_url" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.webhook_url }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    v-model="form.is_active"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                >
                                <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    Active (Client can make payments)
                                </label>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    <span v-if="form.processing">Saving...</span>
                                    <span v-else>Save Changes</span>
                                </button>

                                <Link
                                    :href="route('clients.show', client.id)"
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
