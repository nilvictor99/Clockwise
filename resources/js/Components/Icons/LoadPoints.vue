<template>
    <span class="flex items-center gap-2" :class="variantClass">
        Cargando
        <svg
            width="32"
            height="10"
            viewBox="0 0 32 10"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <circle cx="4" cy="5" r="4">
                <animate
                    attributeName="fill"
                    :values="colorPointsAnim"
                    dur="1s"
                    repeatCount="indefinite"
                    begin="0s"
                />
            </circle>
            <circle cx="16" cy="5" r="4">
                <animate
                    attributeName="fill"
                    :values="colorPointsAnim"
                    dur="1s"
                    repeatCount="indefinite"
                    begin="0.2s"
                />
            </circle>
            <circle cx="28" cy="5" r="4">
                <animate
                    attributeName="fill"
                    :values="colorPointsAnim"
                    dur="1s"
                    repeatCount="indefinite"
                    begin="0.4s"
                />
            </circle>
        </svg>
    </span>
</template>

<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        variant: {
            type: String,
            default: 'gray',
        },
    });

    const variantStyles = {
        indigo: {
            colorPoints: '#6366f1',
            colorPointsAlt: '#a5b4fc',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        primary: {
            colorPoints: '#d35400',
            colorPointsAlt: '#f39c12',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        secondary: {
            colorPoints: '#374151',
            colorPointsAlt: '#9ca3af',
            colorText: 'text-gray-800',
            bg: 'bg-transparent',
        },
        outline: {
            colorPoints: '#6366f1',
            colorPointsAlt: '#a5b4fc',
            colorText: 'text-indigo-500',
            bg: 'bg-transparent',
        },
        danger: {
            colorPoints: '#ef4444',
            colorPointsAlt: '#f87171',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        success: {
            colorPoints: '#22c55e',
            colorPointsAlt: '#4ade80',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        warning: {
            colorPoints: '#eab308',
            colorPointsAlt: '#fde68a',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        info: {
            colorPoints: '#3b82f6',
            colorPointsAlt: '#60a5fa',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        light: {
            colorPoints: '#f3f4f6',
            colorPointsAlt: '#d1d5db',
            colorText: 'text-gray-800',
            bg: 'bg-transparent',
        },
        dark: {
            colorPoints: '#1f2937',
            colorPointsAlt: '#4b5563',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        gray: {
            colorPoints: '#6b7280',
            colorPointsAlt: '#d1d5db',
            colorText: 'text-gray-500',
            bg: 'bg-transparent',
        },
        black: {
            colorPoints: '#000000',
            colorPointsAlt: '#434343',
            colorText: 'text-white',
            bg: 'bg-transparent',
        },
        white: {
            colorPoints: '#ffffff',
            colorPointsAlt: '#ffffff00',
            colorText: 'text-gray-100',
            bg: 'bg-transparent',
        },
    };

    const variantClass = computed(() => {
        const style = variantStyles[props.variant] || variantStyles.primary;
        return `${style.bg} ${style.colorText}`;
    });

    const colorPointsAnim = computed(() => {
        const style = variantStyles[props.variant] || variantStyles.primary;
        return `${style.colorPoints};${style.colorPointsAlt};${style.colorPoints}`;
    });
</script>
