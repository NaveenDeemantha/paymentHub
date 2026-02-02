<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    result: Object,
});
</script>

<template>
    <Head title="Payment Result" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Payment Result</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="result" class="space-y-4">
                            <h3 class="text-lg font-bold">Transaction Details</h3>
                            
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-md overflow-x-auto">
                                <pre class="text-xs">{{ JSON.stringify(result, null, 2) }}</pre>
                            </div>

                            <div v-if="result.responseData" class="mt-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="font-semibold">Response Code:</span> {{ result.responseData.responseCode }}
                                    </div>
                                    <div>
                                        <span class="font-semibold">Status:</span> {{ result.responseData.responseText }}
                                    </div>
                                    <div>
                                        <span class="font-semibold">Order Ref:</span> {{ result.responseData.clientRef }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-red-500">
                            No result data received.
                        </div>

                        <div class="mt-6">
                            <Link :href="route('pay.index')" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                Make Another Payment
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
