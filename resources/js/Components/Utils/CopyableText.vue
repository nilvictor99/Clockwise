<script setup>
    import { ref, computed } from 'vue';

    const props = defineProps({
        text: {
            type: String,
            required: true,
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'indigo', 'primary', 'black'].includes(value),
        },
        size: {
            type: String,
            default: 'sm',
            validator: value => ['xs', 'sm', 'base', 'lg'].includes(value),
        },
        truncate: {
            type: Boolean,
            default: false,
        },
        maxLength: {
            type: Number,
            default: 20,
        },
    });

    const showTooltip = ref(false);

    const copyToClipboard = async () => {
        try {
            await navigator.clipboard.writeText(props.text);
            showTooltip.value = true;
            setTimeout(() => {
                showTooltip.value = false;
            }, 1000);
        } catch (err) {
            console.error('Error al copiar:', err);
            alert('Error al copiar el texto.');
        }
    };

    const textClasses = computed(() => {
        const baseClasses = 'cursor-pointer transition-colors duration-200';
        const themeClasses = {
            gray: 'text-gray-800 hover:text-gray-600',
            indigo: 'text-indigo-600 hover:text-indigo-800',
            primary: 'text-blue-600 hover:text-blue-800',
            black: 'text-black hover:text-gray-600',
        };
        const sizeClasses = {
            xs: 'text-xs',
            sm: 'text-sm',
            base: 'text-base',
            lg: 'text-lg',
        };
        return `${baseClasses} ${themeClasses[props.theme]} ${sizeClasses[props.size]}`;
    });

    const tooltipClasses = computed(() => {
        const baseClasses =
            'absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs rounded shadow-lg z-10';
        const themeClasses = {
            gray: 'bg-gray-600 text-white',
            indigo: 'bg-indigo-600 text-white',
            primary: 'bg-blue-600 text-white',
            black: 'bg-black text-white',
        };
        return `${baseClasses} ${themeClasses[props.theme]}`;
    });

    const displayText = computed(() => {
        if (props.truncate && props.text.length > props.maxLength) {
            return props.text.slice(0, props.maxLength) + '...';
        }
        return props.text;
    });
</script>

<template>
    <div class="relative inline-block">
        <span
            :class="textClasses"
            @click="copyToClipboard"
            :title="`Haz click para copiar: ${text}`"
        >
            {{ displayText }}
        </span>
        <div v-if="showTooltip" :class="tooltipClasses">Copiado</div>
    </div>
</template>
