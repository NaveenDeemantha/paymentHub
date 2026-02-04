<template>
    <button class="danger-btn">
        <slot />
    </button>
</template>

<style scoped>
.danger-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #dc2626;
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

.danger-btn:hover {
    background-color: #ef4444;
}

.danger-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #ef4444;
}

.danger-btn:active {
    background-color: #b91c1c;
}

@media (prefers-color-scheme: dark) {
    .danger-btn:focus {
        box-shadow: 0 0 0 2px #1f2937, 0 0 0 4px #ef4444;
    }
}
</style>
