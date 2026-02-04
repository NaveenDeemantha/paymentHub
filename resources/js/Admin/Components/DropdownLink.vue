<template>
    <Link
        :href="href"
        class="dropdown-link"
    >
        <slot />
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<style scoped>
.dropdown-link {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    text-align: left;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #374151;
    text-decoration: none;
    transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
}

.dropdown-link:hover {
    background-color: #f3f4f6;
}

.dropdown-link:focus {
    background-color: #f3f4f6;
    outline: none;
}

@media (prefers-color-scheme: dark) {
    .dropdown-link {
        color: #d1d5db;
    }
    .dropdown-link:hover {
        background-color: #1f2937;
    }
    .dropdown-link:focus {
        background-color: #1f2937;
    }
}
</style>
