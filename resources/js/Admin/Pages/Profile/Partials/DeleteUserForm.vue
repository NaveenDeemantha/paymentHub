<template>
    <section class="section-container">
        <header>
            <h2 class="section-title">
                Delete Account
            </h2>

            <p class="section-description">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="modal-content">
                <h2 class="modal-title">
                    Are you sure you want to delete your account?
                </h2>

                <p class="modal-description">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="form-group">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="form-input"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="form-error" />
                </div>

                <div class="modal-actions">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="delete-btn"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

<script setup>
import DangerButton from '@/Admin/Components/DangerButton.vue';
import InputError from '@/Admin/Components/InputError.vue';
import InputLabel from '@/Admin/Components/InputLabel.vue';
import Modal from '@/Admin/Components/Modal.vue';
import SecondaryButton from '@/Admin/Components/SecondaryButton.vue';
import TextInput from '@/Admin/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<style scoped>
.section-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

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

.modal-content {
    padding: 1.5rem;
}

.modal-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .modal-title {
        color: #f3f4f6;
    }
}

.modal-description {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #4b5563;
}

@media (prefers-color-scheme: dark) {
    .modal-description {
        color: #9ca3af;
    }
}

.form-group {
    margin-top: 1.5rem;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

.form-input {
    margin-top: 0.25rem;
    display: block;
    width: 75%;
}

.form-error {
    margin-top: 0.5rem;
}

.modal-actions {
    margin-top: 1.5rem;
    display: flex;
    justify-content: flex-end;
}

.delete-btn {
    margin-left: 0.75rem;
}

.opacity-25 {
    opacity: 0.25;
}
</style>
