<template>
    <div class="dropdown-container">
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div
            v-show="open"
            class="dropdown-overlay"
            @click="open = false"
        ></div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="dropdown-menu"
                :class="[widthClass, alignmentClasses]"
                @click="open = false"
            >
                <div
                    class="dropdown-content"
                    :class="contentClasses"
                >
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'content-default',
    },
});

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'origin-top-left start-0';
    } else if (props.align === 'right') {
        return 'origin-top-right end-0';
    } else {
        return 'origin-top';
    }
});

const open = ref(false);
</script>

<style scoped>
.dropdown-container {
    position: relative;
}

.dropdown-overlay {
    position: fixed;
    inset: 0;
    z-index: 40;
}

.dropdown-menu {
    position: absolute;
    z-index: 50;
    margin-top: 0.5rem;
    border-radius: 0.375rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.dropdown-content {
    border-radius: 0.375rem;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05);
}

.content-default {
    padding: 0.25rem 0;
    background-color: #ffffff;
}

@media (prefers-color-scheme: dark) {
    .content-default {
        background-color: #374151;
    }
}

.w-48 {
    width: 12rem;
}

.origin-top-left {
    transform-origin: top left;
}

.origin-top-right {
    transform-origin: top right;
}

.origin-top {
    transform-origin: top;
}

.start-0 {
    left: 0;
}

.end-0 {
    right: 0;
}

/* Transitions */
.transition {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

.ease-out {
    transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
}

.ease-in {
    transition-timing-function: cubic-bezier(0.4, 0, 1, 1);
}

.duration-200 {
    transition-duration: 200ms;
}

.duration-75 {
    transition-duration: 75ms;
}

.opacity-0 {
    opacity: 0;
}

.opacity-100 {
    opacity: 1;
}

.scale-95 {
    transform: scale(0.95);
}

.scale-100 {
    transform: scale(1);
}
</style>
