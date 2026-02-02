<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    paymentUrl: String,
});
</script>

<template>
    <Head title="Complete Payment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Complete Payment</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center">
                        <div v-if="paymentUrl" class="w-full h-screen max-h-[600px] border border-gray-300 rounded-lg overflow-hidden">
                            <iframe :src="paymentUrl" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                        <div v-else class="text-red-500">
                            Error: Payment URL is missing.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
