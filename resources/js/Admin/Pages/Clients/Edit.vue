<template>
    <Head title="Edit Client" />

    <AuthenticatedLayout>
        <template #header>
            <div class="header-container">
                <Link :href="route('clients.show', client.id)" class="back-link">
                    ← Back
                </Link>
                <h2 class="page-title">
                    Edit Client: {{ client.name }}
                </h2>
            </div>
        </template>

        <div class="content-container">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="submit" class="form-container">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    Client Name *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="form-input"
                                    required
                                >
                                <div v-if="form.errors.name" class="form-error">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="domain" class="form-label">
                                    Domain *
                                </label>
                                <input
                                    type="text"
                                    id="domain"
                                    v-model="form.domain"
                                    class="form-input"
                                    required
                                >
                                <div v-if="form.errors.domain" class="form-error">
                                    {{ form.errors.domain }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="return_url" class="form-label">
                                    Return URL *
                                </label>
                                <input
                                    type="url"
                                    id="return_url"
                                    v-model="form.return_url"
                                    class="form-input"
                                    required
                                >
                                <div v-if="form.errors.return_url" class="form-error">
                                    {{ form.errors.return_url }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="webhook_url" class="form-label">
                                    Webhook URL (Optional)
                                </label>
                                <input
                                    type="url"
                                    id="webhook_url"
                                    v-model="form.webhook_url"
                                    class="form-input"
                                >
                                <div v-if="form.errors.webhook_url" class="form-error">
                                    {{ form.errors.webhook_url }}
                                </div>
                            </div>

                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    v-model="form.is_active"
                                    class="form-checkbox"
                                >
                                <label for="is_active" class="checkbox-label">
                                    Active (Client can make payments)
                                </label>
                            </div>

                            <div class="form-actions">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="btn-primary"
                                    :class="{ 'btn-disabled': form.processing }"
                                >
                                    <span v-if="form.processing">Saving...</span>
                                    <span v-else>Save Changes</span>
                                </button>

                                <Link
                                    :href="route('clients.show', client.id)"
                                    class="btn-secondary"
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

<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
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

<style scoped>
/* Header */
.header-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.back-link {
    color: #4b5563;
    text-decoration: none;
    transition: color 0.15s;
}

.back-link:hover {
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .back-link {
        color: #9ca3af;
    }
    .back-link:hover {
        color: #e5e7eb;
    }
}

.page-title {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.25;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .page-title {
        color: #e5e7eb;
    }
}

/* Content Layout */
.content-container {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 48rem;
    margin: 0 auto;
    padding: 0 1.5rem;
}

@media (min-width: 640px) {
    .content-wrapper {
        padding: 0 2rem;
    }
}

/* Card */
.card {
    background-color: #ffffff;
    overflow: hidden;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
}

@media (prefers-color-scheme: dark) {
    .card {
        background-color: #1f2937;
    }
}

.card-body {
    padding: 1.5rem;
}

/* Form */
.form-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.25rem;
}

@media (prefers-color-scheme: dark) {
    .form-label {
        color: #d1d5db;
    }
}

.form-input {
    margin-top: 0.25rem;
    display: block;
    width: 100%;
    border-radius: 0.375rem;
    border-color: #d1d5db;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.form-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 1px #6366f1;
    outline: none;
}

@media (prefers-color-scheme: dark) {
    .form-input {
        background-color: #374151;
        border-color: #4b5563;
        color: #ffffff;
    }
}

.form-error {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Checkbox */
.checkbox-group {
    display: flex;
    align-items: center;
}

.form-checkbox {
    border-radius: 0.25rem;
    border-color: #d1d5db;
    color: #4f46e5;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.form-checkbox:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 1px #6366f1;
    outline: none;
}

@media (prefers-color-scheme: dark) {
    .form-checkbox {
        background-color: #374151;
        border-color: #4b5563;
    }
}

.checkbox-label {
    margin-left: 0.5rem;
    font-size: 0.875rem;
    color: #374151;
}

@media (prefers-color-scheme: dark) {
    .checkbox-label {
        color: #d1d5db;
    }
}

/* Form Actions */
.form-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-top: 1rem;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: #4f46e5;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.btn-primary:hover {
    background-color: #4338ca;
}

.btn-primary:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #4f46e5;
}

.btn-disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.btn-secondary {
    color: #4b5563;
    text-decoration: none;
    transition: color 0.15s;
}

.btn-secondary:hover {
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .btn-secondary {
        color: #9ca3af;
    }
    .btn-secondary:hover {
        color: #e5e7eb;
    }
}
</style>
