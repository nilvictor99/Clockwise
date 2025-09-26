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
        label: {
            type: String,
            default: '',
        },
        color: {
            type: String,
            default: 'orange',
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        name: {
            type: String,
            default: '',
        },
        checked: {
            type: Boolean,
            default: false,
        },
        multi: {
            type: Boolean,
            default: false,
        },
        allowDeselect: {
            type: Boolean,
            default: false,
        },
        tooltip: {
            type: String,
            default: '',
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
            danger: 'text-red-600 ring-red-400 checked:bg-red-600 checked:border-red-600 focus:ring-red-500',
            warning:
                'text-yellow-500 ring-yellow-400 checked:bg-yellow-500 checked:border-yellow-500 focus:ring-yellow-400',
            info: 'text-blue-600 ring-blue-400 checked:bg-blue-600 checked:border-blue-600 focus:ring-blue-500',
            success:
                'text-green-600 ring-green-400 checked:bg-green-600 checked:border-green-600 focus:ring-green-500',
            orange: 'text-orange-600 ring-orange-400 checked:bg-orange-500 checked:border-orange-500 focus:ring-orange-400',
        };
        return colorMap[props.color] || colorMap.orange;
    });

    const isChecked = computed(() => {
        if (props.multi) {
            if (Array.isArray(props.modelValue)) {
                return props.modelValue.some(item =>
                    typeof item === 'object'
                        ? JSON.stringify(item) === JSON.stringify(props.value)
                        : item === props.value
                );
            }
            return false;
        }
        if (props.modelValue === null) return props.checked;
        if (
            typeof props.modelValue === 'object' &&
            typeof props.value === 'object'
        ) {
            return (
                JSON.stringify(props.modelValue) === JSON.stringify(props.value)
            );
        }
        return props.modelValue === props.value;
    });

    const inputType = computed(() => {
        return props.multi ? 'checkbox' : 'radio';
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

        if (props.multi) {
            let newValue = Array.isArray(props.modelValue)
                ? [...props.modelValue]
                : [];
            const idx = newValue.findIndex(item =>
                typeof item === 'object'
                    ? JSON.stringify(item) === JSON.stringify(props.value)
                    : item === props.value
            );

            if (idx > -1) {
                if (props.allowDeselect) {
                    newValue.splice(idx, 1);
                }
            } else {
                newValue.push(props.value);
            }

            emit('update:modelValue', newValue);
            emit('change', newValue);
            emit('select', newValue);
        } else {
            if (
                typeof props.modelValue === 'object' &&
                typeof props.value === 'object'
            ) {
                if (
                    JSON.stringify(props.modelValue) ===
                        JSON.stringify(props.value) &&
                    props.allowDeselect
                ) {
                    emit('update:modelValue', null);
                    emit('change', null);
                    emit('select', null);
                } else {
                    emit('update:modelValue', props.value);
                    emit('change', props.value);
                    emit('select', props.value);
                }
            } else {
                if (props.modelValue === props.value && props.allowDeselect) {
                    emit('update:modelValue', null);
                    emit('change', null);
                    emit('select', null);
                } else {
                    emit('update:modelValue', props.value);
                    emit('change', props.value);
                    emit('select', props.value);
                }
            }
        }
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
                :type="inputType"
                :name="name"
                :value="value"
                :checked="isChecked"
                :disabled="disabled"
                :aria-checked="isChecked"
                :aria-label="label"
                @input="handleInput"
                @keydown="onKeyDown"
                class="rounded-full form-radio h-5 w-5 focus:ring-2 transition-all duration-150 ease-in-out transform hover:scale-105"
                :class="[
                    inputType === 'checkbox' ? 'rounded' : 'rounded-full',
                    colorClasses,
                    { 'ring-4 ring-opacity-50': isChecked },
                ]"
            />
            <span v-if="icon" class="mr-1">
                <i :class="icon"></i>
            </span>
            <span
                class="ml-1 text-base font-medium"
                :class="[colorClasses, { 'text-red-500': error }]"
            >
                <slot>{{ label }}</slot>
            </span>
            <span
                v-if="tooltip"
                class="absolute -top-8 left-0 bg-gray-600 text-white text-xs rounded py-1 px-1 opacity-0 group-hover:opacity-100 transition-opacity z-10"
            >
                {{ tooltip }}
            </span>
            <span
                v-if="isChecked"
                class="absolute inset-0 transition-opacity duration-300 rounded-full"
                :class="{ 'opacity-10': isChecked }"
            ></span>
        </label>
        <p v-if="error" class="text-xs text-red-500 mt-1">{{ error }}</p>
    </div>
</template>
