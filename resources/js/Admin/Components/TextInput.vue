<template>
    <input
        class="form-input"
        v-model="model"
        ref="input"
    />
</template>

<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<style scoped>
.form-input {
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    padding: 0.5rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5rem;
}

.form-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 1px #6366f1;
    outline: none;
}

@media (prefers-color-scheme: dark) {
    .form-input {
        background-color: #111827;
        border-color: #374151;
        color: #d1d5db;
    }
    .form-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 1px #4f46e5;
    }
}
</style>
