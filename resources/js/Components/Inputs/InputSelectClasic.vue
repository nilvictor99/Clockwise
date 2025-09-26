<script setup>
    import { computed, ref, watch, onBeforeUnmount } from 'vue';
    import XIcon from '@/Components/Icons/XIcon.vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';
    import { usePage } from '@inertiajs/vue3';

    const props = defineProps({
        modelValue: [String, Number, Array],
        options: { type: Array, required: true },
        placeholder: { type: String, default: 'Seleccione una opción' },
        disabled: { type: Boolean, default: false },
        focusColor: { type: String, default: '' },
        bold: { type: Boolean, default: false },
        theme: { type: String, default: 'gray' },
        multiple: { type: Boolean, default: false },
        customClass: { type: String, default: '' },
        label: { type: String, default: '' },
        CleanButton: { type: Boolean, default: false },
        initialValue: {
            type: [String, Number, Object],
            default: null,
        },
        roles: {
            type: Array,
            default: () => [],
        },
        permissions: {
            type: Array,
            default: () => [],
        },
    });

    const emit = defineEmits(['update:modelValue', 'change']);

    const showDropdown = ref(false);
    const selectRef = ref(null);
    const focusedOptionIndex = ref(-1);

    const selectedValue = computed({
        get() {
            if (props.modelValue) {
                return props.modelValue;
            }
            const initial = getInitialOption.value;
            return initial?.id ?? '';
        },
        set(value) {
            emit('update:modelValue', value);
            emit('change', value);
        },
    });

    const labelTheme = computed(() => {
        const themeMap = {
            orange: 'gray',
            red: 'primary',
            green: 'gray',
            yellow: 'gray',
            gray: 'gray',
            lightgray: 'gray',
            purple: 'gray',
            blue: 'gray',
        };
        return themeMap[props.theme.toLowerCase()] || 'gray';
    });

    const labelWeight = 'semibold';
    const labelSize = 'sm';
    const labelTransform = 'normal';
    const labelRequired = false;

    const themeClasses = computed(() => {
        const themes = {
            orange: {
                base: 'border-orange-400 text-orange-600',
                focus:
                    props.focusColor ||
                    'focus:border-orange-500 focus:ring-orange-200',
                hover: 'hover:border-orange-500 hover:ring-orange-100',
                badgeBg: 'bg-orange-100',
                badgeText: 'text-gray-800',
                optionSelected: 'bg-orange-50 text-orange-700',
                optionHover: 'hover:bg-orange-100',
                optionFocus: 'bg-orange-200',
                arrow: 'text-orange-500',
                dropdownBorder: 'border-orange-200',
            },
            red: {
                base: 'border-red-400 text-red-600',
                focus:
                    props.focusColor ||
                    'focus:border-red-500 focus:ring-red-200',
                hover: 'hover:border-red-500 hover:ring-red-100',
                badgeBg: 'bg-red-100',
                badgeText: 'text-red-700',
                optionSelected: 'bg-red-50 text-red-700',
                optionHover: 'hover:bg-red-100',
                optionFocus: 'bg-red-200',
                arrow: 'text-red-500',
                dropdownBorder: 'border-red-200',
            },
            green: {
                base: 'border-green-400 text-green-600',
                focus:
                    props.focusColor ||
                    'focus:border-green-500 focus:ring-green-200',
                hover: 'hover:border-green-500 hover:ring-green-100',
                badgeBg: 'bg-green-100',
                badgeText: 'text-green-700',
                optionSelected: 'bg-green-50 text-green-700',
                optionHover: 'hover:bg-green-100',
                optionFocus: 'bg-green-200',
                arrow: 'text-green-500',
                dropdownBorder: 'border-green-200',
            },
            yellow: {
                base: 'border-yellow-400 text-yellow-600',
                focus:
                    props.focusColor ||
                    'focus:border-yellow-500 focus:ring-yellow-200',
                hover: 'hover:border-yellow-500 hover:ring-yellow-100',
                badgeBg: 'bg-yellow-100',
                badgeText: 'text-yellow-700',
                optionSelected: 'bg-yellow-50 text-yellow-700',
                optionHover: 'hover:bg-yellow-100',
                optionFocus: 'bg-yellow-200',
                arrow: 'text-yellow-500',
                dropdownBorder: 'border-yellow-200',
            },
            gray: {
                base: 'border-gray-400 text-gray-600',
                focus:
                    props.focusColor ||
                    'focus:border-gray-500 focus:ring-gray-200',
                hover: 'hover:border-gray-500 hover:ring-gray-100',
                badgeBg: 'bg-gray-100',
                badgeText: 'text-gray-700',
                optionSelected: 'bg-gray-50 text-gray-700',
                optionHover: 'hover:bg-gray-100',
                optionFocus: 'bg-gray-200',
                arrow: 'text-gray-500',
                dropdownBorder: 'border-gray-200',
            },
            lightgray: {
                base: 'border-gray-300 text-gray-600',
                focus:
                    props.focusColor ||
                    'focus:border-gray-300 focus:ring-gray-200',
                hover: 'hover:border-gray-300 hover:ring-gray-100',
                badgeBg: 'bg-gray-100',
                badgeText: 'text-gray-700',
                optionSelected: 'bg-gray-50 text-gray-700',
                optionHover: 'hover:bg-gray-100',
                optionFocus: 'bg-gray-200',
                arrow: 'text-gray-500',
                dropdownBorder: 'border-gray-200',
            },
            purple: {
                base: 'border-purple-400 text-purple-600',
                focus:
                    props.focusColor ||
                    'focus:border-purple-500 focus:ring-purple-200',
                hover: 'hover:border-purple-500 hover:ring-purple-100',
                badgeBg: 'bg-purple-100',
                badgeText: 'text-purple-700',
                optionSelected: 'bg-purple-50 text-purple-700',
                optionHover: 'hover:bg-purple-100',
                optionFocus: 'bg-purple-200',
                arrow: 'text-purple-500',
                dropdownBorder: 'border-purple-200',
            },
            blue: {
                base: 'border-blue-400 text-blue-600',
                focus:
                    props.focusColor ||
                    'focus:border-blue-500 focus:ring-blue-200',
                hover: 'hover:border-blue-500 hover:ring-blue-100',
                badgeBg: 'bg-blue-100',
                badgeText: 'text-blue-700',
                optionSelected: 'bg-blue-50 text-blue-700',
                optionHover: 'hover:bg-blue-100',
                optionFocus: 'bg-blue-200',
                arrow: 'text-blue-500',
                dropdownBorder: 'border-blue-200',
            },
        };

        return themes[props.theme.toLowerCase()] || themes.blue;
    });

    const selectedOptions = computed(() => {
        if (!props.multiple || !Array.isArray(props.modelValue)) return [];
        return props.options.filter(opt => {
            const optionValue = opt.id ?? opt.value ?? opt;
            return props.modelValue.includes(optionValue);
        });
    });

    const getInitialOption = computed(() => {
        if (!props.initialValue) return null;
        if (
            typeof props.initialValue === 'object' &&
            props.initialValue !== null
        ) {
            return props.initialValue;
        }

        return props.options.find(opt => {
            const optionValue = opt.id ?? opt.value ?? opt;
            return optionValue === props.initialValue;
        });
    });

    const getOptionDisplay = option => {
        if (!option) return '';
        if (typeof option === 'string') return option;
        return option.text ?? option.label ?? option;
    };

    function toggleDropdown() {
        if (!props.disabled) {
            showDropdown.value = !showDropdown.value;
            if (showDropdown.value) focusedOptionIndex.value = -1;
        }
    }

    function selectOption(option) {
        const optionValue = option.id ?? option.value ?? option;
        if (!props.multiple) {
            selectedValue.value = optionValue;
            showDropdown.value = false;
        } else {
            let val = Array.isArray(props.modelValue)
                ? [...props.modelValue]
                : [];
            if (!val.includes(optionValue)) {
                val.push(optionValue);
                selectedValue.value = val;
            }
        }
    }

    function removeBadge(option) {
        if (!props.multiple || props.disabled) return;
        let val = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
        const optionValue = option.id ?? option.value ?? option;
        val = val.filter(v => v !== optionValue);
        selectedValue.value = val;
    }

    function isSelected(option) {
        const optionValue = option.id ?? option.value ?? option;
        if (!props.multiple) {
            return selectedValue.value === optionValue;
        }
        return (
            Array.isArray(props.modelValue) &&
            props.modelValue.includes(optionValue)
        );
    }

    function handleClickOutside(event) {
        if (selectRef.value && !selectRef.value.contains(event.target)) {
            showDropdown.value = false;
            focusedOptionIndex.value = -1;
        }
    }

    function handleKeyDown(event) {
        if (props.disabled) return;

        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            if (!showDropdown.value) {
                toggleDropdown();
            } else if (focusedOptionIndex.value >= 0) {
                selectOption(props.options[focusedOptionIndex.value]);
            }
        } else if (event.key === 'Escape') {
            showDropdown.value = false;
            focusedOptionIndex.value = -1;
        } else if (event.key === 'ArrowDown') {
            event.preventDefault();
            if (!showDropdown.value) {
                toggleDropdown();
            } else if (focusedOptionIndex.value < props.options.length - 1) {
                focusedOptionIndex.value++;
            }
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            if (focusedOptionIndex.value > 0) {
                focusedOptionIndex.value--;
            }
        }
    }

    watch(showDropdown, val => {
        if (val) {
            document.addEventListener('mousedown', handleClickOutside);
            document.addEventListener('keydown', handleKeyDown);
        } else {
            document.removeEventListener('mousedown', handleClickOutside);
            document.removeEventListener('keydown', handleKeyDown);
        }
    });

    onBeforeUnmount(() => {
        document.removeEventListener('mousedown', handleClickOutside);
        document.removeEventListener('keydown', handleKeyDown);
    });

    function clearSelection() {
        selectedValue.value = '';
        emit('update:modelValue', '');
        emit('change', '');
    }

    const hasAccess = computed(() => {
        const user = usePage().props.auth?.user;
        if (!user) return false;
        if (!props.roles.length && !props.permissions.length) return true;

        return (
            props.roles.some(role => user.roles.includes(role)) ||
            props.permissions.some(permission =>
                user.permissions.includes(permission)
            )
        );
    });
</script>

<template>
    <div v-if="hasAccess" class="gap-1 relative w-full" ref="selectRef">
        <ClassicLabel
            v-if="label"
            :value="label"
            :theme="labelTheme"
            :weight="labelWeight"
            :size="labelSize"
            :transform="labelTransform"
            :required="labelRequired"
            class="mb-2"
            :for="'select-' + _uid"
        />
        <div
            :class="[
                'mt-2 w-full rounded-md py-2 px-3 transition duration-200 outline-none flex items-center flex-wrap min-h-[42px] relative border',
                disabled
                    ? 'bg-gray-100 text-gray-500 cursor-not-allowed'
                    : 'cursor-pointer',
                themeClasses.base,
                themeClasses.focus,
                themeClasses.hover,
                bold ? 'font-semibold' : '',
                customClass,
            ]"
            @click="toggleDropdown"
            :tabindex="disabled ? -1 : 0"
            role="combobox"
            :aria-expanded="showDropdown"
            :aria-controls="'dropdown-' + _uid"
            :aria-disabled="disabled"
        >
            <!-- Badges para selección múltiple -->
            <template v-if="multiple">
                <template v-if="selectedOptions.length">
                    <span
                        v-for="option in selectedOptions"
                        :key="option.id ?? option.value ?? option"
                        :class="[
                            'flex items-center rounded-full px-2 py-1 mr-2 mb-1 text-xs font-semibold',
                            themeClasses.badgeBg,
                            themeClasses.badgeText,
                        ]"
                    >
                        {{ getOptionDisplay(option) }}
                        <button
                            type="button"
                            :class="[
                                'ml-1 focus:outline-none',
                                themeClasses.badgeText,
                                'hover:opacity-80',
                            ]"
                            @click.stop="removeBadge(option)"
                            :disabled="disabled"
                            aria-label="Eliminar opción"
                        >
                            ×
                        </button>
                    </span>
                </template>
                <span v-else class="text-gray-400">{{ placeholder }}</span>
            </template>
            <template v-else>
                <span v-if="selectedValue" :class="themeClasses.badgeText">
                    {{
                        getOptionDisplay(
                            options.find(
                                o =>
                                    o.id.toString() === selectedValue.toString()
                            ) ||
                                getInitialOption || { text: '' }
                        )
                    }}
                </span>
                <span v-else class="text-gray-400">{{ placeholder }}</span>
            </template>
            <!-- Icono flecha -->
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
            >
                <svg
                    :class="[
                        'w-5 h-5 transition-transform duration-200',
                        themeClasses.arrow,
                        { 'rotate-180': showDropdown },
                    ]"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </div>
            <button
                v-if="CleanButton && selectedValue"
                type="button"
                class="absolute right-8 top-1/2 -translate-y-1/2 focus:outline-none"
                :class="[themeClasses.arrow, 'hover:opacity-80']"
                @click.stop="clearSelection"
                aria-label="Limpiar selección"
            >
                <XIcon />
            </button>
        </div>
        <!-- Dropdown -->
        <div
            v-if="showDropdown"
            :id="'dropdown-' + _uid"
            :class="[
                'absolute z-[9999] mt-1 w-full bg-white rounded-md shadow-lg max-h-60 overflow-auto transform transition-all duration-200 ease-in-out',
                'animate-[fadeIn_0.2s_ease-in-out]',
                themeClasses.dropdownBorder,
            ]"
            role="listbox"
        >
            <ul>
                <li
                    v-for="(option, index) in options"
                    :key="option.id ?? option.value ?? option"
                    @click="selectOption(option)"
                    :class="[
                        'px-4 py-2 cursor-pointer transition-colors duration-150',
                        isSelected(option) ? themeClasses.optionSelected : '',
                        focusedOptionIndex === index
                            ? themeClasses.optionFocus
                            : '',
                        themeClasses.optionHover,
                    ]"
                    role="option"
                    :aria-selected="isSelected(option)"
                >
                    {{ getOptionDisplay(option) }}
                </li>
                <li
                    v-if="!options.length"
                    class="px-4 py-2 text-gray-500"
                    role="option"
                >
                    No hay opciones disponibles
                </li>
            </ul>
        </div>
    </div>
</template>
