<template>
    <div class="login-container">
        <Head title="Log in" />

        <div class="background-pattern"></div>

        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <img src="/images/winfield-logo.png" alt="Winfield Logo" />
                    </div>
                </div>
                <h1 class="login-title">PayCenter Admin</h1>
                <p class="login-subtitle">Secure Access Portal</p>
            </div>

            <!-- Status Message -->
            <div v-if="status" class="status-message">
                <div class="status-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <p>{{ status }}</p>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit" class="login-form">
                <!-- Email Input -->
                <div class="input-group">
                    <div class="input-container">
                        <div class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2"/>
                                <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <TextInput
                            id="email"
                            type="email"
                            class="professional-input"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Email address"
                        />
                    </div>
                    <InputError :message="form.errors.email" class="error-message" />
                </div>

                <!-- Password Input -->
                <div class="input-group">
                    <div class="input-container">
                        <div class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="16" r="1" fill="currentColor"/>
                                <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <TextInput
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="professional-input"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                        />
                        <div class="input-icon-right" @click="togglePasswordVisibility">
                            <svg v-if="showPassword" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <svg v-else viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.99902 3L20.999 21M9.8433 9.91364C9.32066 10.4536 8.99902 11.1892 8.99902 12C8.99902 13.6569 10.3422 15 11.999 15C12.8215 15 13.5667 14.669 14.1086 14.133M6.49902 6.64715C4.59972 7.90034 3.15305 9.78394 2.45703 12C3.73128 16.0571 7.52159 19 11.9992 19C13.9881 19 15.8414 18.4194 17.3988 17.4184M10.999 5.04939C11.328 5.01673 11.6617 5 11.9992 5C16.4769 5 20.2672 7.94291 21.5414 12C21.2607 12.894 20.8577 13.7338 20.3522 14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <InputError :message="form.errors.password" class="error-message" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <label class="remember-me">
                        <Checkbox name="remember" v-model:checked="form.remember" class="custom-checkbox" />
                        <span>Remember me</span>
                    </label>

                    <!-- <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="forgot-password"
                    >
                        Forgot password?
                    </Link> -->
                </div>

                <!-- Submit Button -->
                <PrimaryButton
                    class="professional-button"
                    :class="{ 'loading': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing" class="button-content">
                        <svg class="button-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Sign In
                    </span>
                    <span v-else class="button-content">
                        <svg class="loading-spinner" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" opacity="0.3"/>
                            <path d="M21 12C21 7.02944 16.9706 3 12 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Signing in...
                    </span>
                </PrimaryButton>
            </form>

            <!-- Sign Up Link -->
            <!-- <div class="signup-link">
                <p>
                    Don't have an account?
                    <Link :href="route('register')" class="signup-action">
                        Create one now
                    </Link>
                </p>
            </div> -->
        </div>
    </div>
</template>

<script setup>
import Checkbox from '@/Admin/Components/Checkbox.vue';
import InputError from '@/Admin/Components/InputError.vue';
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import TextInput from '@/Admin/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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

const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>


* {
    box-sizing: border-box;
}

.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: #012749;
    position: relative;
    overflow: hidden;
}
/*
.background-pattern {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(1, 39, 73, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(220, 176, 29, 0.05) 0%, transparent 50%);
    background-size: 400px 400px;
} */

.login-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    width: 100%;
    max-width: 400px;
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(220, 176, 29, 0.1);
    position: relative;
}

.login-header {
    text-align: center;
    margin-bottom: 1.75rem;
}

.logo-container {
    margin-bottom: 1rem;
}

.logo-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100px;
    height: 130px;





    
    /* background: linear-gradient(135deg, #012749, #1e3a5f); */
    /* border-radius: 12px; */
    /* color: #dcb01d; */
    /* box-shadow: 0 4px 12px rgba(1, 39, 73, 0.15); */
}

.logo-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.login-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #012749;
    margin-bottom: 0.25rem;
    letter-spacing: -0.025em;
}

.login-subtitle {
    color: #64748b;
    font-size: 0.95rem;
    font-weight: 500;
}

.status-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(34, 197, 94, 0.05);
    border: 1px solid rgba(34, 197, 94, 0.2);
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.status-icon {
    flex-shrink: 0;
    width: 16px;
    height: 16px;
    color: #22c55e;
}

.status-message p {
    color: #16a34a;
    font-weight: 500;
    font-size: 0.875rem;
    margin: 0;
}

.login-form {
    space-y: 1.25rem;
}

.input-group {
    margin-bottom: 1.25rem;
}

.input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #64748b;
    z-index: 10;
    transition: color 0.2s ease;
}

.input-icon-right {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #64748b;
    z-index: 10;
    transition: color 0.2s ease;
    cursor: pointer;
}

.input-icon-right:hover {
    color: #dcb01d;
}

.professional-input {
    width: 100% !important;
    padding: 0.875rem 2.75rem 0.875rem 2.75rem !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    font-size: 0.95rem !important;
    font-weight: 500 !important;
    color: #1e293b !important;
    transition: all 0.2s ease !important;
}

.professional-input:focus {
    outline: none !important;
    border-color: #dcb01d !important;
    background: white !important;
    box-shadow: 0 0 0 3px rgba(220, 176, 29, 0.1) !important;
}

.professional-input:focus + .input-icon {
    color: #dcb01d;
}

.professional-input::placeholder {
    color: #94a3b8 !important;
    font-weight: 400 !important;
}

.input-container:focus-within .input-icon {
    color: #dcb01d;
}

.error-message {
    margin-top: 0.375rem;
    color: #dc2626 !important;
    font-size: 0.8rem;
    font-weight: 500;
}

.form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 1.25rem 0;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: color 0.2s ease;
}

.remember-me:hover {
    color: #1e293b;
}

.custom-checkbox {
    width: 16px !important;
    height: 16px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 4px !important;
    background: white !important;
    transition: all 0.2s ease !important;
}

.custom-checkbox:checked {
    background: #dcb01d !important;
    border-color: #dcb01d !important;
    color: #012749 !important;
}

.custom-checkbox:focus {
    box-shadow: 0 0 0 3px rgba(220, 176, 29, 0.1) !important;
}

.forgot-password {
    color: #dcb01d !important;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}

.forgot-password:hover {
    color: #012749 !important;
    text-decoration: underline;
}

.professional-button {
    width: 100% !important;
    padding: 0.875rem 1.5rem !important;
    background: linear-gradient(135deg, #012749, #1e3a5f) !important;
    color: #dcb01d !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    border: none !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 4px rgba(1, 39, 73, 0.2) !important;
    position: relative;
    overflow: hidden;
}

.professional-button:hover {
    box-shadow: 0 4px 8px rgba(1, 39, 73, 0.3) !important;
    transform: translateY(-1px);
}

.professional-button:active {
    transform: translateY(0) !important;
}

.professional-button.loading {
    pointer-events: none;
    opacity: 0.8;
}

.button-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.button-icon {
    width: 16px;
    height: 16px;
}

.loading-spinner {
    width: 16px;
    height: 16px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.signup-link {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #f1f5f9;
}

.signup-link p {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 500;
    margin: 0;
}

.signup-action {
    color: #dcb01d !important;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}

.signup-action:hover {
    color: #012749 !important;
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 480px) {
    .login-container {
        padding: 0.75rem;
    }

    .login-card {
        padding: 1.5rem;
        max-width: 100%;
    }

    .login-title {
        font-size: 1.375rem;
    }

    .form-options {
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
    }
}

@media (max-height: 700px) {
    .login-card {
        padding: 1.5rem;
    }

    .login-header {
        margin-bottom: 1.25rem;
    }

    .logo-container {
        margin-bottom: 0.75rem;
    }

    .input-group {
        margin-bottom: 1rem;
    }

    .form-options {
        margin: 1rem 0;
    }

    .signup-link {
        margin-top: 1rem;
        padding-top: 1rem;
    }
}
</style>
