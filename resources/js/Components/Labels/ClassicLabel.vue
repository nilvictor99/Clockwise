<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        value: {
            type: String,
            default: '',
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'white', 'indigo', 'primary', 'black'].includes(value),
        },
        weight: {
            type: String,
            default: 'medium',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
        },
        transform: {
            type: String,
            default: 'normal',
            validator: value =>
                ['normal', 'uppercase', 'lowercase', 'capitalize'].includes(
                    value
                ),
        },
        required: {
            type: Boolean,
            default: false,
        },
    });

    const themeClasses = computed(() => {
        const themes = {
            gray: 'text-gray-700 dark:text-gray-600',
            white: 'text-white dark:text-gray-100',
            indigo: 'text-indigo-700 dark:text-indigo-500',
            primary: 'text-[#d35400] dark:text-orange-300',
            black: 'text-black',
        };
        return themes[props.theme] || themes.gray;
    });

    const weightClasses = computed(() => {
        const weights = {
            normal: 'font-normal',
            medium: 'font-medium',
            semibold: 'font-semibold',
            bold: 'font-bold',
        };
        return weights[props.weight] || weights.medium;
    });

    const sizeClasses = computed(() => {
        const sizes = {
            xs: 'text-xs',
            sm: 'text-sm',
            base: 'text-base',
            lg: 'text-lg',
        };
        return sizes[props.size] || sizes.sm;
    });

    const transformClasses = computed(() => {
        const transforms = {
            normal: '',
            uppercase: 'uppercase',
            lowercase: 'lowercase',
            capitalize: 'capitalize',
        };
        return transforms[props.transform] || '';
    });

    const labelClasses = computed(() => [
        'block',
        themeClasses.value,
        weightClasses.value,
        sizeClasses.value,
        transformClasses.value,
    ]);
</script>

<template>
    <label :class="labelClasses">
        <span v-if="value">
            {{ value }}
            <span v-if="required" class="text-red-500">*</span>
        </span>
        <span v-else>
            <slot />
            <span v-if="required" class="text-red-500">*</span>
        </span>
    </label>
</template>
