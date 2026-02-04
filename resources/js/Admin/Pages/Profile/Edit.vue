<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="page-title">
                Profile
            </h2>
        </template>

        <div class="content-container">
            <div class="content-wrapper">
                <div class="card">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="form-section"
                    />
                </div>

                <div class="card">
                    <UpdatePasswordForm class="form-section" />
                </div>

                <div class="card">
                    <DeleteUserForm class="form-section" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<style scoped>
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

.content-container {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

@media (min-width: 640px) {
    .content-wrapper {
        padding: 0 2rem;
    }
}

.card {
    padding: 1rem;
    background-color: #ffffff;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    border-radius: 0.5rem;
}

@media (min-width: 640px) {
    .card {
        padding: 2rem;
        border-radius: 0.5rem;
    }
}

@media (prefers-color-scheme: dark) {
    .card {
        background-color: #1f2937;
    }
}

.form-section {
    max-width: 36rem;
}
</style>
