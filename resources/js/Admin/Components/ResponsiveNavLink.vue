<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'responsive-nav-link active'
        : 'responsive-nav-link inactive',
);
</script>

<style scoped>
.responsive-nav-link {
    display: block;
    width: 100%;
    padding-left: 0.75rem;
    padding-right: 1rem;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    border-left-width: 4px;
    text-align: left;
    font-size: 1rem;
    font-weight: 500;
    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, color 0.15s ease-in-out;
}

.responsive-nav-link:focus {
    outline: none;
}

.active {
    border-color: #818cf8;
    color: #4338ca;
    background-color: #eef2ff;
}

.active:focus {
    color: #3730a3;
    background-color: #e0e7ff;
    border-color: #4338ca;
}

.inactive {
    border-color: transparent;
    color: #4b5563;
}

.inactive:hover {
    color: #1f2937;
    background-color: #f9fafb;
    border-color: #d1d5db;
}

.inactive:focus {
    color: #1f2937;
    background-color: #f9fafb;
    border-color: #d1d5db;
}

@media (prefers-color-scheme: dark) {
    .responsive-nav-link {
        border-color: transparent;
    }

    .active {
        border-color: #4f46e5;
        color: #a5b4fc;
        background-color: rgba(49, 46, 129, 0.5);
    }
    .active:focus {
        color: #c7d2fe;
        background-color: #312e81;
        border-color: #a5b4fc;
    }

    .inactive {
        color: #9ca3af;
    }
    .inactive:hover {
        color: #e5e7eb;
        background-color: #374151;
        border-color: #4b5563;
    }
    .inactive:focus {
        color: #e5e7eb;
        background-color: #374151;
        border-color: #4b5563;
    }
}
</style>
