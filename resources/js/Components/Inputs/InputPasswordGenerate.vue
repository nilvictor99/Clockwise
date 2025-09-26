<script setup>
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';
    import ClassicLabel from '@/Components/Labels/ClassicLabel.vue';
    import Key from '../Icons/Key.vue';
    import Loader from '../Icons/Loader.vue';
    import Eye from '../Icons/Eye.vue';
    import EyeClosed from '../Icons/EyeClosed.vue';

    const props = defineProps({
        modelValue: {
            type: String,
            default: '',
        },
        generateRoute: {
            type: String,
            required: true,
        },
        placeholder: {
            type: String,
            default: 'Enter password...',
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['dark', 'gray', 'white', 'indigo', 'danger'].includes(value),
        },
        label: {
            type: String,
            default: '',
        },
        initialValue: {
            type: String,
            default: '',
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        icon: {
            type: String,
            default: 'fas fa-key',
        },
    });

    const emit = defineEmits(['update:modelValue', 'generated']);

    const input = ref(null);
    const isGenerating = ref(false);
    const showPassword = ref(false);

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

    const buttonThemeClasses = computed(() => {
        switch (props.theme) {
            case 'dark':
                return 'bg-gray-800 hover:bg-gray-900 text-white';
            case 'gray':
                return 'bg-gray-500 hover:bg-gray-600 text-white';
            case 'white':
                return 'bg-white hover:bg-gray-100 text-gray-800';
            case 'indigo':
                return 'bg-indigo-500 hover:bg-indigo-700 text-white';
            case 'danger':
                return 'bg-red-500 hover:bg-red-700 text-white';
            default:
                return 'bg-gray-300 hover:bg-gray-400 text-gray-800';
        }
    });

    const generatePassword = async () => {
        if (isGenerating.value) return;
        isGenerating.value = true;
        try {
            const response = await axios.get(props.generateRoute);
            const generatedPassword = response.data.password || response.data;
            emit('update:modelValue', generatedPassword);
            emit('generated', generatedPassword);
        } catch (error) {
            console.error('Error generating password:', error);
        } finally {
            isGenerating.value = false;
        }
    };

    const handleInput = event => {
        emit('update:modelValue', event.target.value);
    };

    onMounted(() => {
        if (props.initialValue) {
            emit('update:modelValue', props.initialValue);
        }
    });

    defineExpose({ focus: () => input.value.focus() });
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
    <div class="flex">
        <input
            ref="input"
            :class="`font-bold rounded-l-md shadow-sm w-full px-4 py-2 ${themeClasses}`"
            :value="modelValue"
            :disabled="disabled"
            :type="showPassword ? 'text' : 'password'"
            :placeholder="placeholder"
            @input="handleInput"
        />
        <button
            type="button"
            :class="`px-3 py-2 focus:outline-none border-l border-gray-500 ${buttonThemeClasses}`"
            @click="showPassword = !showPassword"
            :disabled="disabled"
            aria-label="Toggle password visibility"
        >
            <Eye v-if="showPassword" />
            <EyeClosed v-else />
        </button>
        <button
            type="button"
            :class="`rounded-r-md px-3 py-2 focus:outline-none border-l border-gray-500 ${buttonThemeClasses}`"
            @click="generatePassword"
            :disabled="isGenerating || disabled"
        >
            <Key v-if="!isGenerating" />
            <Loader v-else class="animate-spin" />
        </button>
    </div>
</template>
