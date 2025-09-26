<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        title: {
            type: String,
            required: true,
        },
        value: {
            type: [String, Number],
            required: true,
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'green', 'red', 'yellow', 'blue', 'indigo'].includes(
                    value
                ),
        },
        size: {
            type: String,
            default: 'md',
            validator: value => ['sm', 'md', 'lg', 'xl'].includes(value),
        },
        titleWeight: {
            type: String,
            default: 'semibold',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        valueWeight: {
            type: String,
            default: 'bold',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        unit: {
            type: String,
            default: '',
        },
    });

    const themeClasses = computed(() => {
        const themes = {
            gray: 'from-gray-100 to-gray-200 text-gray-700',
            green: 'from-green-100 to-green-200 text-green-700',
            red: 'from-red-100 to-red-200 text-red-700',
            yellow: 'from-yellow-100 to-yellow-200 text-yellow-700',
            blue: 'from-blue-100 to-blue-200 text-blue-700',
            indigo: 'from-indigo-100 to-indigo-200 text-indigo-700',
        };
        return themes[props.theme] || themes.gray;
    });

    const sizeClasses = computed(() => {
        const sizes = {
            sm: {
                container: 'p-3',
                title: 'text-sm',
                value: 'text-xl',
            },
            md: {
                container: 'p-4',
                title: 'text-lg',
                value: 'text-3xl',
            },
            lg: {
                container: 'p-5',
                title: 'text-xl',
                value: 'text-4xl',
            },
            xl: {
                container: 'p-6',
                title: 'text-2xl',
                value: 'text-5xl',
            },
        };
        return sizes[props.size] || sizes.md;
    });

    const weightClasses = computed(() => {
        const weights = {
            normal: 'font-normal',
            medium: 'font-medium',
            semibold: 'font-semibold',
            bold: 'font-bold',
        };
        return {
            title: weights[props.titleWeight] || weights.semibold,
            value: weights[props.valueWeight] || weights.bold,
        };
    });
</script>

<template>
    <div
        :class="[
            'bg-gradient-to-r rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300',
            themeClasses,
            sizeClasses.container,
        ]"
    >
        <h3 :class="['mb-2', sizeClasses.title, weightClasses.title]">
            {{ title }}
        </h3>
        <p
            :class="[
                sizeClasses.value,
                weightClasses.value,
                `text-${props.theme}-900`,
            ]"
        >
            {{ value }}{{ unit }}
        </p>
    </div>
</template>
