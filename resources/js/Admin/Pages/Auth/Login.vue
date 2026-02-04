<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="status-message">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="form-container">
            <div class="form-group">
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

            <div class="form-group">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="form-input"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="form-error" :message="form.errors.password" />
            </div>

            <div class="remember-me">
                <label class="checkbox-label">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="remember-text">Remember me</span>
                </label>
            </div>

            <div class="form-actions">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="forgot-password"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="login-button"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Admin/Components/Checkbox.vue';
import GuestLayout from '@/Admin/Layouts/GuestLayout.vue';
import InputError from '@/Admin/Components/InputError.vue';
import InputLabel from '@/Admin/Components/InputLabel.vue';
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import TextInput from '@/Admin/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>
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

.form-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-input {
    margin-top: 0.25rem;
    display: block;
    width: 100%;
}

.form-error {
    margin-top: 0.5rem;
}

.remember-me {
    display: block;
    margin-top: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
}

.remember-text {
    margin-left: 0.5rem;
    font-size: 0.875rem;
    color: #4b5563;
}

@media (prefers-color-scheme: dark) {
    .remember-text {
        color: #9ca3af;
    }
}

.form-actions {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.forgot-password {
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #4b5563;
    text-decoration: underline;
    transition: color 0.15s;
}

.forgot-password:hover {
    color: #111827;
}

.forgot-password:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #4f46e5;
}

@media (prefers-color-scheme: dark) {
    .forgot-password {
        color: #9ca3af;
    }
    .forgot-password:hover {
        color: #f3f4f6;
    }
    .forgot-password:focus {
        box-shadow: 0 0 0 2px #1f2937, 0 0 0 4px #6366f1;
    }
}

.login-button {
    margin-left: 1rem;
}

.opacity-25 {
    opacity: 0.25;
}
</style>
