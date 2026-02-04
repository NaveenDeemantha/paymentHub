<template>
    <dialog
        class="modal-root"
        ref="dialog"
    >
        <div
            class="modal-wrapper"
            scroll-region
        >
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-show="show"
                    class="modal-backdrop"
                    @click="close"
                >
                    <div class="backdrop-layer" />
                </div>
            </Transition>

            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 translate-y-4 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-4 scale-95"
            >
                <div
                    v-show="show"
                    class="modal-panel"
                    :class="maxWidthClass"
                >
                    <slot v-if="showSlot" />
                </div>
            </Transition>
        </div>
    </dialog>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);
const dialog = ref();
const showSlot = ref(props.show);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
            showSlot.value = true;

            dialog.value?.showModal();
        } else {
            document.body.style.overflow = '';

            setTimeout(() => {
                dialog.value?.close();
                showSlot.value = false;
            }, 200);
        }
    },
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape') {
        e.preventDefault();

        if (props.show) {
            close();
        }
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);

    document.body.style.overflow = '';
});

const maxWidthClass = computed(() => {
    return {
        sm: 'modal-sm',
        md: 'modal-md',
        lg: 'modal-lg',
        xl: 'modal-xl',
        '2xl': 'modal-2xl',
    }[props.maxWidth];
});
</script>

<style scoped>
.modal-root {
    z-index: 50;
    margin: 0;
    min-height: 100%;
    min-width: 100%;
    overflow-y: auto;
    background-color: transparent;
}

.modal-root::backdrop {
    background-color: transparent;
}

.modal-wrapper {
    position: fixed;
    inset: 0;
    z-index: 50;
    overflow-y: auto;
    padding: 1.5rem 1rem;
}

@media (min-width: 640px) {
    .modal-wrapper {
        padding: 1.5rem 0;
    }
}

.modal-backdrop {
    position: fixed;
    inset: 0;
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.backdrop-layer {
    position: absolute;
    inset: 0;
    background-color: #6b7280;
    opacity: 0.75;
}

@media (prefers-color-scheme: dark) {
    .backdrop-layer {
        background-color: #111827;
    }
}

.modal-panel {
    margin-bottom: 1.5rem;
    transform: none;
    overflow: hidden;
    border-radius: 0.5rem;
    background-color: #ffffff;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

@media (min-width: 640px) {
    .modal-panel {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }
}

@media (prefers-color-scheme: dark) {
    .modal-panel {
        background-color: #1f2937;
    }
}

/* Max Width Classes */
.modal-sm {
    max-width: 24rem;
}

@media (min-width: 640px) {
    .modal-sm {
        max-width: 24rem;
    }
}

.modal-md {
    max-width: 28rem;
}

@media (min-width: 640px) {
    .modal-md {
        max-width: 28rem;
    }
}

.modal-lg {
    max-width: 32rem;
}

@media (min-width: 640px) {
    .modal-lg {
        max-width: 32rem;
    }
}

.modal-xl {
    max-width: 36rem;
}

@media (min-width: 640px) {
    .modal-xl {
        max-width: 36rem;
    }
}

.modal-2xl {
    max-width: 42rem;
}

@media (min-width: 640px) {
    .modal-2xl {
        max-width: 42rem;
    }
}

/* Transitions */
.transition {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

.duration-300 {
    transition-duration: 300ms;
}

.duration-200 {
    transition-duration: 200ms;
}

.ease-out {
    transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
}

.ease-in {
    transition-timing-function: cubic-bezier(0.4, 0, 1, 1);
}

.opacity-0 {
    opacity: 0;
}

.opacity-100 {
    opacity: 1;
}

.translate-y-4 {
    transform: translateY(1rem);
}

.translate-y-0 {
    transform: translateY(0);
}

.scale-95 {
    transform: scale(0.95);
}

.scale-100 {
    transform: scale(1);
}

@media (min-width: 640px) {
    .sm\:translate-y-0 {
        transform: translateY(0);
    }
    .sm\:scale-100 {
        transform: scale(1);
    }
    .sm\:scale-95 {
        transform: scale(0.95);
    }
}
</style>
