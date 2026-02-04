<template>
    <section>
        <header>
            <h2 class="section-title">
                Profile Information
            </h2>

            <p class="section-description">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="form-container"
        >
            <div>
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

            <div>
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

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="unverified-message">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="verification-link"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="verification-sent"
                >
                    A new verification link has been sent to your email address.
                </div>
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
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
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

.unverified-message {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .unverified-message {
        color: #e5e7eb;
    }
}

.verification-link {
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #4b5563;
    text-decoration: underline;
    transition: color 0.15s;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.verification-link:hover {
    color: #111827;
}

.verification-link:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #4f46e5;
}

@media (prefers-color-scheme: dark) {
    .verification-link {
        color: #9ca3af;
    }
    .verification-link:hover {
        color: #f3f4f6;
    }
    .verification-link:focus {
        box-shadow: 0 0 0 2px #1f2937, 0 0 0 4px #6366f1;
    }
}

.verification-sent {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #16a34a;
}

@media (prefers-color-scheme: dark) {
    .verification-sent {
        color: #4ade80;
    }
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
