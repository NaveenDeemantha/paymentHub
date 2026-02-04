<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="checkbox-input"
    />
</template>

<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<style scoped>
.checkbox-input {
    border-radius: 0.25rem;
    border-color: #d1d5db;
    color: #4f46e5;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.checkbox-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 1px #6366f1;
    outline: none;
}

@media (prefers-color-scheme: dark) {
    .checkbox-input {
        background-color: #111827;
        border-color: #374151;
        color: #6366f1;
    }
    .checkbox-input:focus {
        box-shadow: 0 0 0 1px #4f46e5;
        border-color: #4f46e5;
        background-color: #1f2937;
    }
}
</style>
