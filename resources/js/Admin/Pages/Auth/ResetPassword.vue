<template>
    <GuestLayout>
        <Head title="Reset Password" />

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
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Reset Password
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

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
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

.opacity-25 {
    opacity: 0.25;
}
</style>
