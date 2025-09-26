<script setup>
    import { onMounted, ref, computed } from 'vue';

    const props = defineProps({
        modelValue: String,
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'white', 'indigo', 'dark', 'danger'].includes(value),
        },
        placeholder: {
            type: String,
            default: '',
        },
        weight: {
            type: String,
            default: 'bold',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        textColor: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'white', 'indigo', 'dark', 'danger'].includes(value),
        },
    });

    defineEmits(['update:modelValue']);

    const input = ref(null);

    onMounted(() => {
        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    });

    const inputClasses = computed(() => {
        const baseClasses = 'rounded-md shadow-sm';
        const weightClass =
            {
                normal: 'font-normal',
                medium: 'font-medium',
                semibold: 'font-semibold',
                bold: 'font-bold',
            }[props.weight] || 'font-bold';

        const textColorClass =
            {
                black: 'text-black',
                red: 'text-red-600',
                green: 'text-green-600',
                gray: 'text-gray-600',
            }[props.textColor] || 'text-black';
        switch (props.theme) {
            case 'dark':
                return `${baseClasses} ${weightClass} ${textColorClass} border-gray-800 focus:border-gray-900 focus:ring-gray-900 bg-gray-900 placeholder-gray-400`;
            case 'gray':
                return `${baseClasses} ${weightClass} ${textColorClass} border-gray-300 focus:border-gray-400 focus:ring-gray-400 bg-white placeholder-gray-400`;
            case 'white':
                return `${baseClasses} ${weightClass} ${textColorClass} border-white focus:border-gray-200 focus:ring-gray-200 bg-white placeholder-gray-400`;
            case 'indigo':
                return `${baseClasses} ${weightClass} ${textColorClass} border-indigo-500 focus:border-indigo-700 focus:ring-indigo-700 bg-indigo-50 placeholder-indigo-400`;
            case 'danger':
                return `${baseClasses} ${weightClass} ${textColorClass} border-red-500 focus:border-red-700 focus:ring-red-700 bg-red-50 placeholder-red-400`;
            default:
                return `${baseClasses} ${weightClass} ${textColorClass} border-gray-300 focus:border-gray-400 focus:ring-gray-400 bg-white placeholder-gray-400`;
        }
    });

    defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        :class="inputClasses"
        :value="modelValue"
        :placeholder="placeholder"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
