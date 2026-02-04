<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="info-text">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div
            v-if="status"
            class="status-message"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="form-input"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="form-error" :message="form.errors.email" />
            </div>

            <div class="form-actions">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Admin/Layouts/GuestLayout.vue';
import InputError from '@/Admin/Components/InputError.vue';
import InputLabel from '@/Admin/Components/InputLabel.vue';
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import TextInput from '@/Admin/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<style scoped>
.info-text {
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: #4b5563;
}

@media (prefers-color-scheme: dark) {
    .info-text {
        color: #9ca3af;
    }
}

.status-message {
    margin-bottom: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #16a34a;
}

@media (prefers-color-scheme: dark) {
    .status-message {
        color: #4ade80;
    }
}

.form-input {
    margin-top: 0.25rem;
    display: block;
    width: 100%;
}

.form-error {
    margin-top: 0.5rem;
}

.form-actions {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.opacity-25 {
    opacity: 0.25;
}
</style>
