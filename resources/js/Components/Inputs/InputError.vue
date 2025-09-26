<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        message: String,
        theme: {
            type: String,
            default: 'light',
        },
        color: {
            type: String,
            default: null,
        },
    });

    const getTextColor = computed(() => {
        if (props.color) {
            switch (props.color) {
                case 'white':
                    return 'text-white';
                case 'gray':
                    return 'text-gray-500';
                case 'indigo':
                    return 'text-indigo-500';
                case 'orange':
                    return 'text-orange-500';
                default:
                    return 'text-red-500';
            }
        } else {
            return props.theme === 'dark' ? 'text-orange-500' : 'text-red-500';
        }
    });
</script>

<template>
    <div v-show="message || $slots.default">
        <p :class="`text-sm ${getTextColor}`">
            <slot>{{ message }}</slot>
        </p>
    </div>
</template>
