<script setup>
    import { computed } from 'vue';
    const props = defineProps({
        variant: {
            type: String,
            default: 'indigo',
            validator: value =>
                [
                    'indigo',
                    'primary',
                    'success',
                    'danger',
                    'warning',
                    'info',
                ].includes(value),
        },
        animation: {
            type: String,
            default: 'bounce',
            validator: value => ['bounce', 'pulse'].includes(value),
        },
    });

    const variantStyles = {
        indigo: {
            base: '#6366f1',
            light: '#a5b4fc',
        },
        primary: {
            base: '#3b82f6',
            light: '#93c5fd',
        },
        success: {
            base: '#22c55e',
            light: '#86efac',
        },
        danger: {
            base: '#ef4444',
            light: '#fca5a5',
        },
        warning: {
            base: '#f59e0b',
            light: '#fcd34d',
        },
        info: {
            base: '#3b82f6',
            light: '#93c5fd',
        },
    };

    const animationClass = computed(() => {
        return {
            bounce: 'animate-bounce',
            pulse: 'animate-pulse',
        }[props.animation];
    });

    const colorPoints = computed(
        () => variantStyles[props.variant]?.base || variantStyles.indigo.base
    );
    const colorPointsLight = computed(
        () => variantStyles[props.variant]?.light || variantStyles.indigo.light
    );
</script>

<template>
    <span class="inline-flex items-center gap-1">
        <svg
            v-for="(_, index) in 3"
            :key="index"
            xmlns="http://www.w3.org/2000/svg"
            width="12"
            height="12"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="rounded-full transition-colors duration-500"
            :class="[animationClass, `[animation-delay:${index * 0.2}s]`]"
        >
            <path
                stroke="none"
                d="M0 0h24v24H0z"
                :fill="
                    props.animation === 'bounce'
                        ? colorPoints
                        : colorPointsLight
                "
            />
            <path
                d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z"
                :fill="
                    props.animation === 'pulse' ? colorPoints : colorPointsLight
                "
            />
        </svg>
    </span>
</template>
