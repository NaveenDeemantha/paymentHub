<template>
    <button
        :type="type"
        class="secondary-btn"
    >
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<style scoped>
.secondary-btn {
    display: inline-flex;
    align-items: center;
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    background-color: #ffffff;
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #374151;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    transition: background-color 0.15s ease-in-out, opacity 0.15s ease-in-out;
}

.secondary-btn:hover {
    background-color: #f9fafb;
}

.secondary-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #6366f1;
}

.secondary-btn:disabled {
    opacity: 0.25;
}

@media (prefers-color-scheme: dark) {
    .secondary-btn {
        border-color: #6b7280;
        background-color: #1f2937;
        color: #d1d5db;
    }
    .secondary-btn:hover {
        background-color: #374151;
    }
    .secondary-btn:focus {
        box-shadow: 0 0 0 2px #1f2937, 0 0 0 4px #4b5563;
    }
}
</style>
