<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="info-text">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </div>

        <div
            class="status-message"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="form-actions">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="logout-link"
                    >Log Out</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Admin/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
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

.form-actions {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logout-link {
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

.logout-link:hover {
    color: #111827;
}

.logout-link:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #4f46e5;
}

@media (prefers-color-scheme: dark) {
    .logout-link {
        color: #9ca3af;
    }
    .logout-link:hover {
        color: #f3f4f6;
    }
    .logout-link:focus {
        box-shadow: 0 0 0 2px #1f2937, 0 0 0 4px #6366f1;
    }
}

.opacity-25 {
    opacity: 0.25;
}
</style>
