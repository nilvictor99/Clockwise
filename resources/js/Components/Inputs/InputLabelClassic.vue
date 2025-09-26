<script setup>
    import { onMounted, ref, computed } from 'vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';

    const props = defineProps({
        modelValue: String,
        DisabledInput: {
            type: Boolean,
            required: false,
        },
        placeholder: {
            type: String,
            required: false,
        },
        type: {
            type: String,
            default: 'text',
        },
        name: {
            type: String,
            required: false,
        },
        autofocus: {
            type: Boolean,
            required: false,
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['dark', 'gray', 'white', 'indigo', 'danger'].includes(value),
        },
        label: { type: String, default: '' },
        clearOnFocus: {
            type: Boolean,
            default: false,
        },
        defaultValue: {
            type: [String, Number],
            default: null,
        },
        calculateOnInput: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['update:modelValue', 'calculate']);

    const input = ref(null);
    const previousValue = ref(null);

    const labelTheme = computed(() => {
        const themeMap = {
            dark: 'gray',
            gray: 'gray',
            white: 'white',
            indigo: 'indigo',
            danger: 'primary',
        };
        return themeMap[props.theme] || 'gray';
    });

    const labelWeight = 'semibold';
    const labelSize = 'sm';
    const labelTransform = 'normal';
    const labelRequired = false;

    const handleFocus = () => {
        if (props.clearOnFocus) {
            previousValue.value = props.modelValue;
            emit('update:modelValue', '');
        }
    };

    const handleBlur = () => {
        if (props.clearOnFocus && !props.modelValue) {
            emit(
                'update:modelValue',
                previousValue.value || props.defaultValue
            );
        }
    };

    const handleInput = event => {
        emit('update:modelValue', event.target.value);
        if (props.calculateOnInput) {
            emit('calculate', event.target.value);
        }
    };

    onMounted(() => {
        if (!props.modelValue && props.defaultValue !== null) {
            emit('update:modelValue', String(props.defaultValue));
        }

        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    });

    defineExpose({ focus: () => input.value.focus() });

    const themeClasses = computed(() => {
        switch (props.theme) {
            case 'dark':
                return 'border-gray-800 focus:border-gray-900 focus:ring-gray-900 bg-gray-900 text-white placeholder-gray-400';
            case 'gray':
                return 'border-gray-500 focus:border-gray-600 focus:ring-gray-600 bg-white text-gray-700 placeholder-gray-400';
            case 'white':
                return 'border-white focus:border-gray-200 focus:ring-gray-200 bg-white text-gray-800 placeholder-gray-400';
            case 'indigo':
                return 'border-indigo-500 focus:border-indigo-700 focus:ring-indigo-700 bg-indigo-50 text-indigo-900 placeholder-indigo-400';
            case 'danger':
                return 'border-red-500 focus:border-red-700 focus:ring-red-700 bg-red-50 text-red-900 placeholder-red-400';
            default:
                return 'border-gray-300 focus:border-gray-400 focus:ring-gray-400 bg-white text-gray-800 placeholder-gray-400';
        }
    });
</script>

<template>
    <ClassicLabel
        v-if="label"
        :value="label"
        :theme="labelTheme"
        :weight="labelWeight"
        :size="labelSize"
        :transform="labelTransform"
        :required="labelRequired"
        class="font-semibold mb-2"
        :for="'input-' + _uid"
    />
    <input
        ref="input"
        :class="`font-bold rounded-md shadow-sm w-full ${themeClasses}`"
        :value="modelValue"
        :disabled="DisabledInput"
        :type="type"
        :placeholder="placeholder"
        :name="name"
        @input="handleInput"
        :autofocus="autofocus"
        @focus="handleFocus"
        @blur="handleBlur"
    />
</template>
