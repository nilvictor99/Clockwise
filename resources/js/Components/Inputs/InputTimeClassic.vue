<script setup>
    import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';
    import CalendarClock from '@/Components/Icons/CalendarClock.vue';

    const props = defineProps({
        modelValue: { type: String, default: '' },
        label: { type: String, default: '' },
        id: {
            type: String,
            default: () =>
                `input-time-${Math.random().toString(36).substr(2, 9)}`,
        },
        disabled: { type: Boolean, default: false },
        placeholder: { type: String, default: 'Seleccionar hora' },
        name: { type: String, default: '' },
        autofocus: { type: Boolean, default: false },
        theme: {
            type: String,
            default: 'gray',
            validator: v =>
                ['dark', 'gray', 'white', 'indigo', 'danger'].includes(v),
        },
        clearOnFocus: { type: Boolean, default: false },
        defaultValue: { type: [String, Number], default: null },
        allowInput: { type: Boolean, default: true },
    });

    const emit = defineEmits(['update:modelValue', 'change']);

    const inputRef = ref(null);
    const popoverRef = ref(null);
    const showPopover = ref(false);
    const hourInput = ref(null);
    const minuteInput = ref(null);
    const hourDropdownOpen = ref(false);
    const minuteDropdownOpen = ref(false);

    const hours = Array.from({ length: 24 }, (_, i) =>
        String(i).padStart(2, '0')
    );
    const minutes = Array.from({ length: 60 }, (_, i) =>
        String(i).padStart(2, '0')
    );

    const selectedHour = ref('');
    const selectedMinute = ref('');
    const hourSearch = ref('');
    const minuteSearch = ref('');

    const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;

    const filteredHours = computed(() => {
        const search = hourSearch.value.toLowerCase();
        return hours.filter(h => h.includes(search));
    });

    const filteredMinutes = computed(() => {
        const search = minuteSearch.value.toLowerCase();
        return minutes.filter(m => m.includes(search));
    });

    const inputValue = computed({
        get() {
            return selectedHour.value && selectedMinute.value
                ? `${selectedHour.value}:${selectedMinute.value}`
                : '';
        },
        set(val) {
            if (timeRegex.test(val)) {
                const [h, m] = val.split(':');
                updateTimeValues(h, m);
            } else {
                clearTime();
            }
        },
    });

    watch(
        () => props.modelValue,
        val => {
            if (val && /^\d{2}:\d{2}$/.test(val)) {
                const [h, m] = val.split(':');
                selectedHour.value = h;
                selectedMinute.value = m;
                hourSearch.value = h;
                minuteSearch.value = m;
            } else {
                selectedHour.value = '';
                selectedMinute.value = '';
                hourSearch.value = '';
                minuteSearch.value = '';
            }
        },
        { immediate: true }
    );

    const themeClasses = computed(() => {
        const themes = {
            dark: {
                input: 'border-gray-800 focus:border-gray-900 focus:ring-gray-900 bg-gray-900 text-white placeholder-gray-400',
                popover: 'bg-gray-900 border-gray-800',
                select: 'border-gray-700 bg-gray-800 text-white focus:ring-gray-900',
                button: 'text-gray-400 hover:text-gray-300',
                text: 'text-gray-300',
                separator: 'text-gray-500',
                divider: 'border-gray-700',
                icon: 'text-gray-400',
            },
            gray: {
                input: 'border-gray-300 focus:border-gray-400 focus:ring-gray-400 bg-white text-gray-800 placeholder-gray-400',
                popover: 'bg-white border-gray-300',
                select: 'border-gray-300 bg-white text-gray-800 focus:ring-gray-400',
                button: 'text-gray-500 hover:text-gray-700',
                text: 'text-gray-700',
                separator: 'text-gray-600',
                divider: 'border-gray-300',
                icon: 'text-gray-500',
            },
            white: {
                input: 'border-white focus:border-gray-200 focus:ring-gray-200 bg-white text-gray-800 placeholder-gray-400',
                popover: 'bg-white border-white',
                select: 'border-white bg-white text-gray-800 focus:ring-gray-200',
                button: 'text-gray-600 hover:text-gray-800',
                text: 'text-gray-800',
                separator: 'text-gray-600',
                divider: 'border-white',
                icon: 'text-gray-600',
            },
            indigo: {
                input: 'border-indigo-300 focus:border-indigo-500 focus:ring-indigo-200 bg-white text-indigo-900 placeholder-indigo-400',
                popover: 'bg-white border-indigo-300',
                select: 'border-indigo-300 bg-white text-indigo-900 focus:ring-indigo-200',
                button: 'text-indigo-600 hover:text-indigo-800',
                text: 'text-indigo-900',
                separator: 'text-indigo-600',
                divider: 'border-indigo-300',
                icon: 'text-indigo-600',
            },
            danger: {
                input: 'border-red-300 focus:border-red-500 focus:ring-red-200 bg-white text-red-900 placeholder-red-400',
                popover: 'bg-white border-red-300',
                select: 'border-red-300 bg-white text-red-900 focus:ring-red-200',
                button: 'text-red-600 hover:text-red-800',
                text: 'text-red-900',
                separator: 'text-red-600',
                divider: 'border-red-300',
                icon: 'text-red-600',
            },
        };
        return themes[props.theme] || themes.gray;
    });

    const updateTimeValues = (hour, minute) => {
        selectedHour.value = hour.padStart(2, '0');
        selectedMinute.value = minute.padStart(2, '0');
        hourSearch.value = selectedHour.value;
        minuteSearch.value = selectedMinute.value;
        emit(
            'update:modelValue',
            `${selectedHour.value}:${selectedMinute.value}`
        );
        emit('change', `${selectedHour.value}:${selectedMinute.value}`);
    };

    const selectHour = hour => {
        selectedHour.value = hour;
        hourSearch.value = hour;
        hourDropdownOpen.value = false;
        if (!selectedMinute.value) {
            selectedMinute.value = '00';
            minuteSearch.value = '00';
        }
        updateTime();
    };

    const selectMinute = minute => {
        selectedMinute.value = minute;
        minuteSearch.value = minute;
        minuteDropdownOpen.value = false;
        updateTime();
    };

    const updateTime = () => {
        if (selectedHour.value && selectedMinute.value) {
            emit(
                'update:modelValue',
                `${selectedHour.value}:${selectedMinute.value}`
            );
            emit('change', `${selectedHour.value}:${selectedMinute.value}`);
        }
    };

    const handleTimeInput = e => {
        const value = e.target.value;
        if (timeRegex.test(value)) {
            const [h, m] = value.split(':');
            updateTimeValues(h, m);
        }
    };

    const handleHourInput = e => {
        const value = e.target.value;
        hourSearch.value = value;
        hourDropdownOpen.value = true;

        if (/^\d{2}$/.test(value) && parseInt(value) < 24) {
            selectedHour.value = value;
            if (!selectedMinute.value) selectedMinute.value = '00';
            updateTimeValues(value, selectedMinute.value);
        }
    };

    const handleMinuteInput = e => {
        const value = e.target.value;
        minuteSearch.value = value;
        minuteDropdownOpen.value = true;

        if (/^\d{2}$/.test(value) && parseInt(value) < 60) {
            selectedMinute.value = value;
            updateTimeValues(selectedHour.value, value);
        }
    };

    const clearTime = () => {
        selectedHour.value = '';
        selectedMinute.value = '';
        hourSearch.value = '';
        minuteSearch.value = '';
        inputValue.value = '';
        showPopover.value = false;
    };

    const handleClickOutside = event => {
        if (
            popoverRef.value &&
            !popoverRef.value.contains(event.target) &&
            inputRef.value &&
            !inputRef.value.contains(event.target)
        ) {
            showPopover.value = false;
            hourDropdownOpen.value = false;
            minuteDropdownOpen.value = false;
        }
    };

    onMounted(() => {
        document.addEventListener('mousedown', handleClickOutside);
        // Initialize from modelValue or defaultValue
        if (props.modelValue && /^\d{2}:\d{2}$/.test(props.modelValue)) {
            const [h, m] = props.modelValue.split(':');
            selectedHour.value = h;
            selectedMinute.value = m;
            hourSearch.value = h;
            minuteSearch.value = m;
        } else if (
            props.defaultValue &&
            /^\d{2}:\d{2}$/.test(props.defaultValue)
        ) {
            const [h, m] = String(props.defaultValue).split(':');
            selectedHour.value = h;
            selectedMinute.value = m;
            hourSearch.value = h;
            minuteSearch.value = m;
            // Emit to set modelValue if not provided
            emit('update:modelValue', String(props.defaultValue));
        }
    });
    onBeforeUnmount(() => {
        document.removeEventListener('mousedown', handleClickOutside);
    });
</script>

<template>
    <div class="flex flex-col gap-1 w-full relative">
        <ClassicLabel
            v-if="label"
            :value="label"
            :theme="theme"
            weight="semibold"
            size="sm"
            transform="normal"
            :required="false"
            class="font-semibold"
            :for="id"
        />
        <div class="relative w-full">
            <input
                ref="inputRef"
                :id="id"
                type="text"
                :class="`font-bold rounded-md shadow-sm w-full px-3 py-2 ${themeClasses.input}`"
                :value="inputValue"
                :disabled="disabled"
                :readonly="!allowInput"
                :placeholder="placeholder"
                :name="name"
                :autofocus="autofocus"
                @focus="showPopover = true"
                @input="handleTimeInput"
            />
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <CalendarClock
                    :class="`h-5 w-5 ${themeClasses.icon} cursor-pointer`"
                    @click="!disabled && (showPopover = !showPopover)"
                />
            </div>
            <transition name="calendar">
                <div
                    v-show="showPopover"
                    ref="popoverRef"
                    :class="`absolute z-50 mt-1 p-4 rounded-lg shadow-lg ${themeClasses.popover}`"
                    style="min-width: 280px"
                >
                    <div
                        :class="`mb-2 text-sm font-semibold ${themeClasses.text}`"
                    >
                        Selecciona hora y minuto
                    </div>

                    <div class="flex gap-4 items-center justify-between mb-4">
                        <!-- Hour Input with Dropdown -->
                        <div class="relative flex-1">
                            <input
                                ref="hourInput"
                                type="text"
                                v-model="hourSearch"
                                @input="handleHourInput"
                                @focus="hourDropdownOpen = true"
                                :placeholder="'HH'"
                                :class="`w-full px-2 py-1 rounded border text-sm ${themeClasses.input}`"
                                maxlength="2"
                            />
                            <!-- Hour Dropdown -->
                            <div
                                v-show="hourDropdownOpen"
                                class="absolute top-full left-0 w-full mt-1 max-h-40 overflow-y-auto rounded-md shadow-lg border"
                                :class="themeClasses.popover"
                            >
                                <button
                                    v-for="hour in filteredHours"
                                    :key="hour"
                                    @click="selectHour(hour)"
                                    type="button"
                                    :class="`w-full px-3 py-1 text-left text-sm hover:bg-gray-100 
                                        ${selectedHour === hour ? 'bg-gray-50' : ''} ${themeClasses.text}`"
                                >
                                    {{ hour }}
                                </button>
                            </div>
                        </div>

                        <span
                            :class="`mx-1 font-bold ${themeClasses.separator}`"
                            >:</span
                        >

                        <!-- Minute Input with Dropdown -->
                        <div class="relative flex-1">
                            <input
                                ref="minuteInput"
                                type="text"
                                v-model="minuteSearch"
                                @input="handleMinuteInput"
                                @focus="minuteDropdownOpen = true"
                                :placeholder="'MM'"
                                :class="`w-full px-2 py-1 rounded border text-sm ${themeClasses.input}`"
                                maxlength="2"
                            />
                            <!-- Minute Dropdown -->
                            <div
                                v-show="minuteDropdownOpen"
                                class="absolute top-full left-0 w-full mt-1 max-h-40 overflow-y-auto rounded-md shadow-lg border"
                                :class="themeClasses.popover"
                            >
                                <button
                                    v-for="minute in filteredMinutes"
                                    :key="minute"
                                    @click="selectMinute(minute)"
                                    type="button"
                                    :class="`w-full px-3 py-1 text-left text-sm hover:bg-gray-100 
                                        ${selectedMinute === minute ? 'bg-gray-50' : ''} ${themeClasses.text}`"
                                >
                                    {{ minute }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        :class="`flex justify-between pt-2 border-t ${themeClasses.divider}`"
                    >
                        <button
                            @click="clearTime"
                            :class="`text-sm ${themeClasses.button}`"
                            type="button"
                            :disabled="disabled"
                        >
                            Limpiar
                        </button>
                        <button
                            @click="showPopover = false"
                            :class="`text-sm font-medium ${themeClasses.button}`"
                            type="button"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
    .calendar-enter-active,
    .calendar-leave-active {
        transition: all 0.2s ease;
    }
    .calendar-enter-from,
    .calendar-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>
