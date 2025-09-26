<script setup>
    import { computed, ref, onMounted, onUnmounted } from 'vue';
    import XIcon from '@/Components/Icons/XIcon.vue';
    import LabelClasic from '@/Components/Labels/ClassicLabel.vue';

    const props = defineProps({
        modelValue: {
            type: [Number, String],
            required: true,
        },
        options: {
            type: Array,
            default: () => [5, 10, 25, 50, 100],
        },
        theme: {
            type: String,
            default: 'indigo',
            validator: value =>
                [
                    'indigo',
                    'blue',
                    'orange',
                    'red',
                    'green',
                    'yellow',
                    'gray',
                    'purple',
                ].includes(value),
        },
        label: {
            type: String,
            default: 'Registros por página',
        },
        labelPosition: {
            type: String,
            default: 'left',
            validator: value =>
                ['left', 'right', 'top', 'bottom'].includes(value),
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        bold: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            default: 'Seleccione cantidad',
        },
        clearButton: {
            type: Boolean,
            default: false,
        },
    });

    const containerClasses = computed(() => {
        const positions = {
            left: 'flex flex-row items-center gap-2',
            right: 'flex flex-row-reverse items-center gap-2',
            top: 'flex flex-col items-start gap-1',
            bottom: 'flex flex-col-reverse items-start gap-1',
        };
        return positions[props.labelPosition];
    });

    const wrapperClasses = computed(() => [
        'relative min-w-[120px] max-w-[180px]',
        props.disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
    ]);

    const selectClasses = computed(() => [
        'w-full rounded-md py-2 px-3',
        'border transition-all duration-200',
        'appearance-none cursor-pointer',
        props.bold ? 'font-semibold' : 'font-normal',
        themeClasses.value.base,
        themeClasses.value.focus,
        themeClasses.value.hover,
    ]);

    const emit = defineEmits(['update:modelValue', 'change']);
    const showDropdown = ref(false);
    const selectorRef = ref(null);
    const dropdownPosition = ref('bottom');

    const themeClasses = computed(() => {
        const themes = {
            indigo: {
                base: 'border-indigo-400 text-indigo-600',
                focus: 'focus:border-indigo-500 focus:ring-indigo-200',
                hover: 'hover:border-indigo-500',
                selected: 'bg-indigo-50 text-indigo-700',
                button: 'text-indigo-500',
            },
            blue: {
                base: 'border-blue-400 text-blue-600',
                focus: 'focus:border-blue-500 focus:ring-blue-200',
                hover: 'hover:border-blue-500',
                selected: 'bg-blue-50 text-blue-700',
                button: 'text-blue-500',
            },

            danger: {
                base: 'border-red-400 text-red-600',
                focus: 'focus:border-red-500 focus:ring-red-200',
                hover: 'hover:border-red-500',
                selected: 'bg-red-50 text-red-700',
                button: 'text-red-500',
            },
            success: {
                base: 'border-green-400 text-green-600',
                focus: 'focus:border-green-500 focus:ring-green-200',
                hover: 'hover:border-green-500',
                selected: 'bg-green-50 text-green-700',
                button: 'text-green-500',
            },
            gray: {
                base: 'border-gray-400 text-gray-600',
                focus: 'focus:border-gray-500 focus:ring-gray-200',
                hover: 'hover:border-gray-500',
                selected: 'bg-gray-50 text-gray-700',
                button: 'text-gray-500',
            },
        };
        return themes[props.theme] || themes.indigo;
    });

    function updateDropdownPosition() {
        if (!selectorRef.value) return;

        const rect = selectorRef.value.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;
        const dropdownHeight = 200;

        dropdownPosition.value =
            spaceBelow < dropdownHeight && spaceAbove > spaceBelow
                ? 'top'
                : 'bottom';
    }

    function handleChange(value) {
        if (!props.disabled) {
            emit('update:modelValue', value);
            emit('change', value);
            showDropdown.value = false;
        }
    }

    function clearSelection() {
        if (!props.disabled) {
            handleChange(props.options[0]);
        }
    }

    function toggleDropdown() {
        if (!props.disabled) {
            showDropdown.value = !showDropdown.value;
        }
    }

    function handleClickOutside(event) {
        if (selectorRef.value && !selectorRef.value.contains(event.target)) {
            showDropdown.value = false;
        }
    }

    function handleKeydown(event) {
        if (event.key === 'Escape') {
            showDropdown.value = false;
        }
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside);
        document.addEventListener('keydown', handleKeydown);
        window.addEventListener('scroll', updateDropdownPosition);
        window.addEventListener('resize', updateDropdownPosition);
    });

    onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside);
        document.removeEventListener('keydown', handleKeydown);
        window.removeEventListener('scroll', updateDropdownPosition);
        window.removeEventListener('resize', updateDropdownPosition);
    });
</script>

<template>
    <div :class="containerClasses">
        <LabelClasic
            v-if="label"
            :value="label"
            :theme="theme"
            :weight="bold ? 'semibold' : 'normal'"
            class="whitespace-nowrap select-none"
        />

        <div :class="wrapperClasses" ref="selectorRef">
            <div
                @click="toggleDropdown"
                :class="[
                    selectClasses,
                    'flex items-center justify-between group',
                    showDropdown ? themeClasses.focus : '',
                ]"
            >
                <span
                    :class="[
                        modelValue ? 'text-gray-900' : 'text-gray-400',
                        'truncate pr-2',
                    ]"
                >
                    {{ modelValue ? `${modelValue} registros` : placeholder }}
                </span>

                <div class="flex items-center gap-1 shrink-0">
                    <Transition name="fade">
                        <button
                            v-if="
                                clearButton &&
                                !disabled &&
                                modelValue !== options[0]
                            "
                            type="button"
                            class="p-1 rounded-full hover:bg-gray-100 transform active:scale-95 transition-all"
                            :class="themeClasses.button"
                            @click.stop="clearSelection"
                        >
                            <XIcon class="h-3.5 w-3.5" />
                        </button>
                    </Transition>

                    <svg
                        class="h-4 w-4 transition-transform duration-300 ease-in-out"
                        :class="[
                            themeClasses.button,
                            showDropdown ? 'rotate-180' : '',
                            'group-hover:scale-110',
                        ]"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
            </div>

            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <div
                    v-show="showDropdown"
                    class="absolute z-50 w-full bg-white border rounded-md shadow-lg overflow-hidden"
                    :class="[
                        themeClasses.base,
                        dropdownPosition === 'top'
                            ? 'bottom-full mb-1'
                            : 'top-full mt-1',
                    ]"
                >
                    <div class="max-h-48 overflow-y-auto scrollbar-thin">
                        <div
                            v-for="option in options"
                            :key="option"
                            @click="handleChange(option)"
                            class="px-3 py-1.5 cursor-pointer transition-all duration-150 text-sm"
                            :class="[
                                option === modelValue
                                    ? [themeClasses.selected, 'font-medium']
                                    : '',
                                'hover:bg-gray-50',
                            ]"
                        >
                            {{ option }} registros
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.2s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }

    .scrollbar-thin {
        scrollbar-width: thin;
    }

    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 2px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #666;
    }
</style>
