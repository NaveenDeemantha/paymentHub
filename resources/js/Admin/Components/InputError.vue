<template>
    <div v-show="message">
        <p class="error-message">
            {{ message }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<style scoped>
.error-message {
    font-size: 0.875rem;
    color: #dc2626;
}

@media (prefers-color-scheme: dark) {
    .error-message {
        color: #f87171;
    }
}
</style>
