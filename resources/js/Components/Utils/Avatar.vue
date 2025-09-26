<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        src: {
            type: String,
            default: '',
        },
        alt: {
            type: String,
            default: '',
        },
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'md', 'lg'].includes(value),
        },
        fallback: {
            type: String,
            default: '',
        },
    });

    const sizeClasses = computed(
        () =>
            ({
                xs: 'w-6 h-6',
                sm: 'w-8 h-8',
                md: 'w-10 h-10',
                lg: 'w-12 h-12',
            })[props.size]
    );

    const handleError = e => {
        if (props.fallback) {
            e.target.src = props.fallback;
        }
    };
</script>

<template>
    <img
        :src="src"
        :alt="alt"
        @error="handleError"
        :class="[sizeClasses, 'rounded-full object-cover flex-shrink-0']"
    />
</template>
