<template>
    <section>
        <header>
            <h2 class="section-title">
                Update Password
            </h2>

            <p class="section-description">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="form-container">
            <div>
                <InputLabel for="current_password" value="Current Password" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="form-input"
                    autocomplete="current-password"
                />

                <InputError
                    :message="form.errors.current_password"
                    class="form-error"
                />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="form-input"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="form-error" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="form-input"
                    autocomplete="new-password"
                />

                <InputError
                    :message="form.errors.password_confirmation"
                    class="form-error"
                />
            </div>

            <div class="form-actions">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="success-message"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script setup>
import InputError from '@/Admin/Components/InputError.vue';
import InputLabel from '@/Admin/Components/InputLabel.vue';
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import TextInput from '@/Admin/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<style scoped>
.section-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .section-title {
        color: #f3f4f6;
    }
}

.section-description {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #4b5563;
}

@media (prefers-color-scheme: dark) {
    .section-description {
        color: #9ca3af;
    }
}

.form-container {
    margin-top: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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
    display: flex;
    align-items: center;
    gap: 1rem;
}

.transition {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.ease-in-out {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

.opacity-0 {
    opacity: 0;
}

.success-message {
    font-size: 0.875rem;
    color: #4b5563;
}

@media (prefers-color-scheme: dark) {
    .success-message {
        color: #9ca3af;
    }
}
</style>
