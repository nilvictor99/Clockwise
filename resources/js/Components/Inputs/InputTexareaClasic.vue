<script setup>
    import { onMounted, ref, computed, watch } from 'vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';

    const props = defineProps({
        modelValue: {
            type: String,
            default: '',
        },
        placeholder: {
            type: String,
            default: '',
        },
        rows: {
            type: Number,
            default: 4,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        id: {
            type: String,
            default: 'textarea-clasic',
        },
        customClass: {
            type: String,
            default: '',
        },
        label: {
            type: String,
            default: '',
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['dark', 'gray', 'white', 'indigo', 'danger'].includes(value),
        },
        defaultValue: {
            type: [String, Number],
            default: null,
        },
        clearOnFocus: {
            type: Boolean,
            default: false,
        },
        calculateOnInput: {
            type: Boolean,
            default: false,
        },
        fontWeight: {
            type: String,
            default: 'bold',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
    });

    const emit = defineEmits(['update:modelValue', 'calculate']);
    const textarea = ref(null);
    const inputValue = ref(props.modelValue || '');
    const previousValue = ref(null);

    watch(
        () => props.modelValue,
        newValue => {
            inputValue.value = newValue;
        }
    );

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

    const fontWeightClass = computed(() => {
        const weights = {
            normal: 'font-normal',
            medium: 'font-medium',
            semibold: 'font-semibold',
            bold: 'font-bold',
        };
        return weights[props.fontWeight] || 'font-bold';
    });

    const defaultClass = computed(() => {
        switch (props.theme) {
            case 'dark':
                return 'w-full px-3 py-2 border rounded-md max-h-32 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 border-gray-800 bg-gray-900 text-white placeholder-gray-400';
            case 'gray':
                return 'w-full px-3 py-2 border rounded-md max-h-32 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 border-gray-300 bg-white text-gray-500 placeholder-gray-400';
            case 'white':
                return 'w-full px-3 py-2 border rounded-md max-h-32 focus:outline-none focus:ring-1 focus:ring-gray-200 focus:border-gray-200 border-white bg-white text-gray-800 placeholder-gray-400';
            case 'indigo':
                return 'w-full px-3 py-2 border rounded-md max-h-32 focus:outline-none focus:ring-1 focus:ring-indigo-700 focus:border-indigo-700 border-indigo-500 bg-indigo-50 text-indigo-900 placeholder-indigo-400';
            case 'danger':
                return 'w-full px-3 py-2 border rounded-md max-h-32 focus:outline-none focus:ring-1 focus:ring-red-700 focus:border-red-700 border-red-500 bg-red-50 text-red-900 placeholder-red-400';
            default:
                return 'w-full px-3 py-2 border rounded-md max-h-32 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-300 border-gray-300';
        }
    });

    const textareaClass = computed(() =>
        [defaultClass.value, props.customClass].filter(Boolean).join(' ')
    );

    const handleFocus = () => {
        if (props.clearOnFocus) {
            previousValue.value = inputValue.value;
            inputValue.value = '';
            emit('update:modelValue', '');
        }
    };

    const handleBlur = () => {
        if (props.clearOnFocus && !inputValue.value) {
            const restoreValue =
                previousValue.value || String(props.defaultValue || '');
            inputValue.value = restoreValue;
            emit('update:modelValue', restoreValue);
        }
    };

    const updateValue = event => {
        const value = event.target.value;
        inputValue.value = value;
        emit('update:modelValue', value);
        if (props.calculateOnInput) {
            emit('calculate', value);
        }
    };

    onMounted(() => {
        if (!props.modelValue && props.defaultValue !== null) {
            const defaultVal = String(props.defaultValue);
            inputValue.value = defaultVal;
            emit('update:modelValue', defaultVal);
        }
    });

    defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <div class="textarea-wrapper">
        <ClassicLabel
            v-if="label"
            :value="label"
            :theme="labelTheme"
            :weight="labelWeight"
            :size="labelSize"
            :transform="labelTransform"
            :required="labelRequired"
            class="font-semibold mb-2"
            :for="'textarea-' + _uid"
        />
        <textarea
            ref="textarea"
            :id="id"
            v-model="inputValue"
            :placeholder="placeholder"
            :rows="rows"
            :disabled="disabled"
            :class="`${fontWeightClass} ${textareaClass}`"
            @input="updateValue"
            @focus="handleFocus"
            @blur="handleBlur"
        ></textarea>
    </div>
</template>
