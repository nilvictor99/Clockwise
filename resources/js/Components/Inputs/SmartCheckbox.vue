<script setup>
    import { computed, ref } from 'vue';

    const props = defineProps({
        modelValue: {
            type: [String, Number, Boolean, Object, Array, null],
            default: null,
        },
        value: {
            type: [String, Number, Boolean, Object],
            required: true,
        },
        mode: {
            type: String,
            default: 'multi',
            validator: value =>
                ['single', 'multi', 'selectAll'].includes(value),
        },
        allValues: {
            type: Array,
            default: () => [],
        },
        label: {
            type: String,
            default: '',
        },
        color: {
            type: String,
            default: 'orange',
        },
        size: {
            type: String,
            default: 'md',
            validator: value => ['sm', 'md', 'lg'].includes(value),
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        icon: {
            type: String,
            default: '',
        },
        rules: {
            type: Array,
            default: () => [],
        },
        required: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['update:modelValue', 'change', 'select']);
    const error = ref('');

    const colorClasses = computed(() => {
        const colorMap = {
            orange: 'text-orange-600 ring-orange-400 checked:bg-orange-500 checked:border-orange-500 focus:ring-orange-400',
            gray: 'text-gray-600 ring-gray-400 checked:bg-gray-600 checked:border-gray-600 focus:ring-gray-500',
            primary:
                'text-indigo-600 ring-indigo-400 checked:bg-indigo-600 checked:border-indigo-600 focus:ring-indigo-500',
            danger: 'text-red-600 ring-red-400 checked:bg-red-600 checked:border-red-600 focus:ring-red-500',
            warning:
                'text-yellow-500 ring-yellow-400 checked:bg-yellow-500 checked:border-yellow-500 focus:ring-yellow-400',
            info: 'text-blue-600 ring-blue-400 checked:bg-blue-600 checked:border-blue-600 focus:ring-blue-500',
            success:
                'text-green-600 ring-green-400 checked:bg-green-600 checked:border-green-600 focus:ring-green-500',
        };
        return colorMap[props.color] || colorMap.orange;
    });

    const sizeClasses = computed(() => {
        const sizeMap = {
            sm: {
                checkbox: 'h-4 w-4',
                text: 'text-sm',
                ring: 'ring-2',
            },
            md: {
                checkbox: 'h-5 w-5',
                text: 'text-base',
                ring: 'ring-4',
            },
            lg: {
                checkbox: 'h-6 w-6',
                text: 'text-lg',
                ring: 'ring-6',
            },
        };
        return sizeMap[props.size] || sizeMap.md;
    });

    const isChecked = computed(() => {
        if (props.mode === 'selectAll') {
            return (
                Array.isArray(props.modelValue) &&
                props.allValues.length > 0 &&
                props.allValues.every(val => props.modelValue.includes(val))
            );
        } else if (props.mode === 'multi') {
            return (
                Array.isArray(props.modelValue) &&
                props.modelValue.includes(props.value)
            );
        } else if (props.mode === 'single') {
            return props.modelValue === props.value;
        }
        return false;
    });

    function validate() {
        if (props.required && !props.modelValue) {
            error.value = 'Este campo es requerido';
            return false;
        }

        for (const rule of props.rules) {
            const result = rule(props.modelValue);
            if (result !== true) {
                error.value = result;
                return false;
            }
        }

        error.value = '';
        return true;
    }

    function handleInput() {
        if (props.disabled) return;

        let newValue;

        if (props.mode === 'selectAll') {
            newValue = isChecked.value ? [] : [...props.allValues];
        } else if (props.mode === 'multi') {
            newValue = Array.isArray(props.modelValue)
                ? [...props.modelValue]
                : [];
            const index = newValue.findIndex(item => item === props.value);
            if (index > -1) {
                newValue.splice(index, 1);
            } else {
                newValue.push(props.value);
            }
        } else if (props.mode === 'single') {
            newValue = props.value;
        }

        emit('update:modelValue', newValue);
        emit('change', newValue);
        emit('select', newValue);
        validate();
    }

    function onKeyDown(event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            handleInput(event);
        }
    }
</script>

<template>
    <div>
        <label
            class="group relative inline-flex items-center cursor-pointer select-none"
            :class="{ 'opacity-80 pointer-events-none': disabled }"
        >
            <input
                type="checkbox"
                :checked="isChecked"
                :disabled="disabled"
                :aria-checked="isChecked"
                :aria-label="label"
                @input="handleInput"
                @keydown="onKeyDown"
                class="rounded form-checkbox focus:ring-2 transition-all duration-150 ease-in-out transform hover:scale-105"
                :class="[
                    sizeClasses.checkbox,
                    colorClasses,
                    { [sizeClasses.ring + ' ring-opacity-50']: isChecked },
                ]"
            />
            <span v-if="icon" class="mr-1">
                <i :class="icon"></i>
            </span>
            <span
                class="ml-1 font-medium"
                :class="[
                    sizeClasses.text,
                    colorClasses,
                    { 'text-red-500': error },
                ]"
            >
                <slot>{{ label }}</slot>
            </span>
            <span
                v-if="isChecked"
                class="absolute inset-0 transition-opacity duration-300 rounded"
                :class="{ 'opacity-10': isChecked }"
            ></span>
        </label>
        <p v-if="error" class="text-xs text-red-500 mt-1">{{ error }}</p>
    </div>
</template>
s
