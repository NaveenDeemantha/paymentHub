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
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.25rem;
    transition: all 0.2s ease-in-out;
    border-radius: 10px;
    text-decoration: none;
}

.nav-link:focus {
    outline: none;
}

.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.4);
}

.active:hover {
    box-shadow: 0 6px 8px -1px rgba(102, 126, 234, 0.5);
}

.inactive {
    color: #6b7280;
    background: transparent;
}

.inactive:hover {
    color: #1f2937;
    background: rgba(99, 102, 241, 0.1);
}

.inactive:focus {
    color: #1f2937;
    background: rgba(99, 102, 241, 0.1);
}
</style>
