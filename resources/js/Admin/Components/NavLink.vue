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
        ? 'nav-link active'
        : 'nav-link inactive',
);
</script>

<style scoped>
.nav-link {
    display: inline-flex;
    align-items: center;
    padding: 0 0.25rem;
    padding-top: 0.25rem;
    border-bottom-width: 2px;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.25rem;
    transition: all 0.15s ease-in-out;
}

.nav-link:focus {
    outline: none;
}

.active {
    border-color: #818cf8;
    color: #111827;
}

.active:focus {
    border-color: #4338ca;
}

.inactive {
    border-color: transparent;
    color: #6b7280;
}

.inactive:hover {
    color: #374151;
    border-color: #d1d5db;
}

.inactive:focus {
    color: #374151;
    border-color: #d1d5db;
}

@media (prefers-color-scheme: dark) {
    .active {
        border-color: #4f46e5;
        color: #f3f4f6;
    }
    .active:focus {
        border-color: #6366f1;
    }

    .inactive {
        color: #9ca3af;
    }
    .inactive:hover {
        color: #d1d5db;
        border-color: #374151;
    }
    .inactive:focus {
        color: #d1d5db;
        border-color: #374151;
    }
}
</style>
