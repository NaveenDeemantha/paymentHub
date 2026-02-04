<template>
    <div class="layout-container">
        <div>
            <Link href="/">
                <ApplicationLogo class="logo" />
            </Link>
        </div>

        <div class="content-wrapper">
            <slot />
        </div>
    </div>
</template>

<script setup>
import ApplicationLogo from '@/Admin/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.layout-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 1.5rem;
    background-color: #f3f4f6;
}

@media (min-width: 640px) {
    .layout-container {
        justify-content: center;
        padding-top: 0;
    }
}

@media (prefers-color-scheme: dark) {
    .layout-container {
        background-color: #111827;
    }
}

.logo {
    height: 5rem;
    width: 5rem;
    fill: currentColor;
    color: #6b7280;
}

.content-wrapper {
    margin-top: 1.5rem;
    width: 100%;
    overflow: hidden;
    background-color: #ffffff;
    padding: 1rem 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border-radius: 0.5rem;
}

@media (min-width: 640px) {
    .content-wrapper {
        max-width: 28rem;
    }
}

@media (prefers-color-scheme: dark) {
    .content-wrapper {
        background-color: #1f2937;
    }
}
</style>
