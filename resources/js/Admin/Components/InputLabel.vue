<template>
    <label class="form-label">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<style scoped>
.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

@media (prefers-color-scheme: dark) {
    .form-label {
        color: #d1d5db;
    }
}
</style>
