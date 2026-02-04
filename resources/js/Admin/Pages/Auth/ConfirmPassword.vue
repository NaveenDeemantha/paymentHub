<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="info-text">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div class="form-group">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="form-input"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="form-error" :message="form.errors.password" />
            </div>

            <div class="form-actions">
                <PrimaryButton
                    class="start-button"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
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

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
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

.form-group {
    margin-top: 1rem;
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
    justify-content: flex-end;
}

.start-button {
    margin-left: 1rem;
}

.opacity-25 {
    opacity: 0.25;
}
</style>
