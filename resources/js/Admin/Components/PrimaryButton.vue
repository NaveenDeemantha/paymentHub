<template>
    <button class="primary-btn">
        <slot />
    </button>
</template>

<style scoped>
.primary-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #1f2937;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #ffffff;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.primary-btn:hover {
    background-color: #374151;
}

.primary-btn:focus {
    background-color: #374151;
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #6366f1;
}

.primary-btn:active {
    background-color: #111827;
}

@media (prefers-color-scheme: dark) {
    .primary-btn {
        background-color: #e5e7eb;
        color: #1f2937;
    }
    .primary-btn:hover {
        background-color: #ffffff;
    }
    .primary-btn:focus {
        background-color: #ffffff;
        box-shadow: 0 0 0 2px #1f2937, 0 0 0 4px #4b5563;
    }
    .primary-btn:active {
        background-color: #d1d5db;
    }
}
</style>
