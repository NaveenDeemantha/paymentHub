<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" class="form-container">
            <div class="form-group">
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="form-input"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="form-error" :message="form.errors.name" />
            </div>

            <div class="form-group">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="form-input"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                />

                <InputError class="form-error" :message="form.errors.password" />
            </div>

            <div class="form-group">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="form-input"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="form-error"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="form-actions">
                <Link
                    :href="route('login')"
                    class="already-registered"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="register-button"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
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
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<style scoped>
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

.form-actions {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.already-registered {
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #4b5563;
    text-decoration: underline;
    transition: color 0.15s;
}

.already-registered:hover {
    color: #111827;
}

.already-registered:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #4f46e5;
}

@media (prefers-color-scheme: dark) {
    .already-registered {
        color: #9ca3af;
    }
    .already-registered:hover {
        color: #f3f4f6;
    }
    .already-registered:focus {
        box-shadow: 0 0 0 2px #1f2937, 0 0 0 4px #6366f1;
    }
}

.register-button {
    margin-left: 1rem;
}

.opacity-25 {
    opacity: 0.25;
}
</style>
