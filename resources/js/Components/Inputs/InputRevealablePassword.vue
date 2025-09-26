<script setup>
    import TextInput from '@/Components/Inputs/TextInput.vue';
    import { ref, computed } from 'vue';
    import { defineProps, defineEmits } from 'vue';
    import Eye from '../Icons/Eye.vue';
    import EyeClosed from '../Icons/EyeClosed.vue';

    const props = defineProps({
        modelValue: {
            type: String,
            default: '',
        },
        id: {
            type: String,
            default: 'password',
        },
        required: {
            type: Boolean,
            default: false,
        },
        autocomplete: {
            type: String,
            default: 'current-password',
        },
        theme: {
            type: String,
            default: 'gray',
            validator: value =>
                ['gray', 'white', 'indigo', 'primary', 'black'].includes(value),
        },
        weight: {
            type: String,
            default: 'bold',
            validator: value =>
                ['normal', 'medium', 'semibold', 'bold'].includes(value),
        },
        placeholder: {
            type: String,
            default: '',
        },
    });
    defineEmits(['update:modelValue']);
    const showPassword = ref(false);

    function togglePassword() {
        showPassword.value = !showPassword.value;
    }

    const themeClasses = computed(() => {
        switch (props.theme) {
            case 'dark':
                return 'border-gray-800 focus:border-gray-900 focus:ring-gray-900 bg-gray-900 text-white placeholder-gray-400';
            case 'gray':
                return 'border-gray-300 focus:border-gray-400 focus:ring-gray-400 bg-white text-gray-600 placeholder-gray-400';
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

    const weightClasses = computed(() => {
        switch (props.weight) {
            case 'normal':
                return 'font-normal';
            case 'medium':
                return 'font-medium';
            case 'semibold':
                return 'font-semibold';
            case 'bold':
                return 'font-bold';
            default:
                return 'font-bold';
        }
    });
</script>

<template>
    <div class="relative">
        <TextInput
            :id="id"
            :type="showPassword ? 'text' : 'password'"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :required="required"
            :autocomplete="autocomplete"
            :placeholder="placeholder"
            :class="`block w-full ${themeClasses} ${weightClasses}`"
        />
        <button
            type="button"
            class="absolute top-1/2 -translate-y-1/2 right-2 flex items-center justify-center w-8 h-8 text-gray-600 hover:text-gray-900 focus:outline-none"
            @click="togglePassword"
        >
            <EyeClosed v-if="!showPassword" />
            <Eye v-else />
        </button>
    </div>
</template>
