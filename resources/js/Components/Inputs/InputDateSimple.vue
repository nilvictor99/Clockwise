<script setup>
    import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';
    import CalendarClock from '@/Components/Icons/CalendarClock.vue';
    import ChevronLeft from '@/Components/Icons/ChevronLeft.vue';
    import ChevronRight from '@/Components/Icons/ChevronRight.vue';

    const props = defineProps({
        modelValue: { type: String, default: '' },
        minDate: { type: String, default: null },
        maxDate: { type: String, default: null },
        placeholder: { type: String, default: 'Seleccionar fecha' },
        disabled: { type: Boolean, default: false },
        borderColor: { type: String, default: 'border-gray-300' },
        focusColor: {
            type: String,
            default: 'focus:border-gray-500 focus:ring-gray-200',
        },
        bold: { type: Boolean, default: false },
        yearRange: { type: Number, default: 10 },
        allowInput: { type: Boolean, default: true },
        theme: {
            type: String,
            default: 'gray',
            validator: value => ['gray', 'indigo', 'orange'].includes(value),
        },
        label: { type: String, default: '' },
        clearOnFocus: { type: Boolean, default: false },
        defaultValue: { type: [String, Date], default: null },
        calculateOnInput: { type: Boolean, default: false },
        id: {
            type: String,
            default: () =>
                `input-date-${Math.random().toString(36).substr(2, 9)}`,
        },
    });

    const emit = defineEmits(['update:modelValue', 'change', 'calculate']);

    const themeClasses = computed(() => {
        const themes = {
            gray: {
                border: 'border-gray-300',
                focus: 'focus:border-gray-400 focus:ring-gray-200',
                hover: 'hover:bg-gray-100',
                selected: 'bg-gray-500 text-white',
                accent: 'text-gray-500 hover:text-gray-400',
                buttonHover: 'hover:bg-gray-100 text-gray-400',
                dropdown: {
                    button: 'hover:text-gray-600',
                    item: 'hover:bg-gray-100',
                    selected: 'bg-gray-50',
                },
            },
            indigo: {
                border: 'border-indigo-300',
                focus: 'focus:border-indigo-500 focus:ring-indigo-200',
                hover: 'hover:bg-indigo-100',
                selected: 'bg-indigo-600 text-white',
                accent: 'text-indigo-600 hover:text-indigo-800',
                buttonHover: 'hover:bg-indigo-100 text-indigo-600',
                dropdown: {
                    button: 'hover:text-indigo-600',
                    item: 'hover:bg-indigo-100',
                    selected: 'bg-indigo-50',
                },
            },
            orange: {
                border: 'border-orange-300',
                focus: 'focus:border-orange-500 focus:ring-orange-200',
                hover: 'hover:bg-orange-100',
                selected: 'bg-orange-600 text-white',
                accent: 'text-orange-600 hover:text-orange-800',
                buttonHover: 'hover:bg-orange-100 text-orange-600',
                dropdown: {
                    button: 'hover:text-orange-600',
                    item: 'hover:bg-orange-100',
                    selected: 'bg-orange-50',
                },
            },
        };
        return themes[props.theme];
    });

    const labelTheme = computed(() => {
        const themeMap = {
            gray: 'gray',
            indigo: 'indigo',
            orange: 'primary',
        };
        return themeMap[props.theme] || 'gray';
    });

    const labelWeight = 'semibold';
    const labelSize = 'sm';
    const labelTransform = 'normal';
    const labelRequired = false;

    const showCalendar = ref(false);
    const showYearSelector = ref(false);
    const showMonthSelector = ref(false);
    const calendarRef = ref(null);
    const inputRef = ref(null);
    const previousValue = ref(null);

    const getCurrentDate = () => {
        const date = new Date();
        return new Date(
            date.toLocaleString('en-US', { timeZone: 'America/Lima' })
        );
    };

    const currentYear = getCurrentDate().getFullYear();
    const currentMonth = getCurrentDate().getMonth();

    const formatDateForModel = date => {
        if (!date) return '';
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const formatDateForDisplay = dateString => {
        if (!dateString) return '';
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    };

    const parseDate = dateString => {
        if (!dateString) return null;
        if (dateString.includes('/')) {
            const [day, month, year] = dateString.split('/');
            return new Date(year, parseInt(month) - 1, day);
        }
        const [year, month, day] = dateString.split('-');
        return new Date(year, parseInt(month) - 1, day);
    };

    const inputValue = ref(
        props.modelValue ? formatDateForDisplay(props.modelValue) : ''
    );
    const currentDate = ref(new Date(currentYear, currentMonth, 1));
    const selectedDate = ref(
        props.modelValue ? parseDate(props.modelValue) : null
    );

    const availableYears = computed(() => {
        const years = [];
        const startYear = currentYear - props.yearRange;
        const endYear = currentYear + props.yearRange;
        for (let year = startYear; year <= endYear; year++) {
            years.push(year);
        }
        return years;
    });

    const months = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre',
    ];

    const weekdays = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'];

    const formattedDate = computed(() => {
        return selectedDate.value
            ? formatDateForDisplay(formatDateForModel(selectedDate.value))
            : '';
    });

    watch(
        () => props.modelValue,
        newVal => {
            if (newVal) {
                selectedDate.value = parseDate(newVal);
                inputValue.value = formatDateForDisplay(newVal);
                currentDate.value = new Date(
                    selectedDate.value.getFullYear(),
                    selectedDate.value.getMonth(),
                    1
                );
            } else {
                selectedDate.value = null;
                inputValue.value = '';
            }
        }
    );

    watch(showCalendar, val => {
        if (val && !selectedDate.value) {
            const today = getCurrentDate();
            selectedDate.value = today;
            currentDate.value = new Date(
                today.getFullYear(),
                today.getMonth(),
                1
            );
            inputValue.value = formatDateForDisplay(formatDateForModel(today));
        }
    });

    const daysInMonth = computed(() => {
        const year = currentDate.value.getFullYear();
        const month = currentDate.value.getMonth();
        return new Date(year, month + 1, 0).getDate();
    });

    const firstDayOfMonth = computed(() => {
        const year = currentDate.value.getFullYear();
        const month = currentDate.value.getMonth();
        return (new Date(year, month, 1).getDay() + 6) % 7;
    });

    const calendarDays = computed(() => {
        const days = [];
        const year = currentDate.value.getFullYear();
        const month = currentDate.value.getMonth();
        const prevMonthDays = new Date(year, month, 0).getDate();
        for (let i = 0; i < firstDayOfMonth.value; i++) {
            days.push({
                day: prevMonthDays - firstDayOfMonth.value + i + 1,
                month: month - 1,
                year: month === 0 ? year - 1 : year,
                isCurrentMonth: false,
            });
        }
        for (let i = 1; i <= daysInMonth.value; i++) {
            days.push({
                day: i,
                month: month,
                year: year,
                isCurrentMonth: true,
            });
        }
        const remainingDays = 42 - days.length;
        for (let i = 1; i <= remainingDays; i++) {
            days.push({
                day: i,
                month: month + 1,
                year: month === 11 ? year + 1 : year,
                isCurrentMonth: false,
            });
        }
        return days;
    });

    const isValidDateFormat = dateString => {
        const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
        if (!regex.test(dateString)) return false;
        const [day, month, year] = dateString.split('/');
        const date = new Date(year, month - 1, day);
        return (
            date &&
            date.getFullYear() === parseInt(year) &&
            date.getMonth() === parseInt(month) - 1 &&
            date.getDate() === parseInt(day)
        );
    };

    const handleInputChange = event => {
        const value = event.target.value;
        inputValue.value = value;
        if (isValidDateFormat(value)) {
            const date = parseDate(value);
            if (
                !selectedDate.value ||
                selectedDate.value.getTime() !== date.getTime()
            ) {
                selectedDate.value = date;
                currentDate.value = new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    1
                );
                emit('update:modelValue', formatDateForModel(date));
                emit('change', formatDateForModel(date));
                if (props.calculateOnInput) {
                    emit('calculate', formatDateForModel(date));
                }
            }
        } else if (value === '') {
            selectedDate.value = null;
            emit('update:modelValue', '');
            emit('change', '');
            if (props.calculateOnInput) {
                emit('calculate', '');
            }
        }
    };

    const handleInputBlur = () => {
        if (inputValue.value && !isValidDateFormat(inputValue.value)) {
            inputValue.value = formattedDate.value;
        }
    };

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

    const handleYearSelect = year => {
        currentDate.value = new Date(year, currentDate.value.getMonth(), 1);
        showYearSelector.value = false;
    };

    const handleMonthSelect = monthIndex => {
        currentDate.value = new Date(
            currentDate.value.getFullYear(),
            monthIndex,
            1
        );
        showMonthSelector.value = false;
    };

    const selectDay = day => {
        if (isDateDisabled(day)) return;
        const date = new Date(day.year, day.month, day.day);
        selectedDate.value = date;
        inputValue.value = formatDateForDisplay(formatDateForModel(date));
        emit('update:modelValue', formatDateForModel(date));
        emit('change', formatDateForModel(date));
        if (props.calculateOnInput) {
            emit('calculate', formatDateForModel(date));
        }
        showCalendar.value = false;
    };

    const clearDate = () => {
        selectedDate.value = null;
        inputValue.value = '';
        currentDate.value = new Date(currentYear, currentMonth, 1);
        emit('update:modelValue', '');
        emit('change', '');
        if (props.calculateOnInput) {
            emit('calculate', '');
        }
    };

    const goToToday = () => {
        const today = getCurrentDate();
        currentDate.value = new Date(today.getFullYear(), today.getMonth(), 1);
        if (!props.disabled) {
            selectedDate.value = today;
            inputValue.value = formatDateForDisplay(formatDateForModel(today));
            emit('update:modelValue', formatDateForModel(today));
            emit('change', formatDateForModel(today));
            if (props.calculateOnInput) {
                emit('calculate', formatDateForModel(today));
            }
        }
    };

    // isDateDisabled con validación de minDate y maxDate
    const isDateDisabled = day => {
        const date = new Date(day.year, day.month, day.day);

        if (props.minDate) {
            const min = parseDate(props.minDate);
            if (min && date < min) return true;
        }
        if (props.maxDate) {
            const max = parseDate(props.maxDate);
            if (max && date > max) return true;
        }
        return false;
    };

    const isDateSelected = day => {
        if (!selectedDate.value) return false;
        return (
            selectedDate.value.getDate() === day.day &&
            selectedDate.value.getMonth() === day.month &&
            selectedDate.value.getFullYear() === day.year
        );
    };

    onMounted(() => {
        document.addEventListener('mousedown', handleClickOutside);
        document.addEventListener('keydown', handleKeyDown);
        if (!props.modelValue && props.defaultValue !== null) {
            const defaultVal = String(props.defaultValue);
            emit('update:modelValue', defaultVal);
        }
    });

    onBeforeUnmount(() => {
        document.removeEventListener('mousedown', handleClickOutside);
        document.removeEventListener('keydown', handleKeyDown);
    });

    const prevMonth = () => {
        const year = currentDate.value.getFullYear();
        const month = currentDate.value.getMonth();
        if (month === 0) {
            currentDate.value = new Date(year - 1, 11, 1);
        } else {
            currentDate.value = new Date(year, month - 1, 1);
        }
    };
    const nextMonth = () => {
        const year = currentDate.value.getFullYear();
        const month = currentDate.value.getMonth();
        if (month === 11) {
            currentDate.value = new Date(year + 1, 0, 1);
        } else {
            currentDate.value = new Date(year, month + 1, 1);
        }
    };
    const handleClickOutside = event => {
        if (
            calendarRef.value &&
            !calendarRef.value.contains(event.target) &&
            inputRef.value &&
            !inputRef.value.contains(event.target)
        ) {
            showCalendar.value = false;
        }
    };
    const handleKeyDown = event => {
        if (event.key === 'Escape') {
            showCalendar.value = false;
        }
        if (event.key === 'Enter' && showCalendar.value) {
            if (currentDate.value) {
                const date = new Date(
                    currentDate.value.getFullYear(),
                    currentDate.value.getMonth(),
                    1
                );
                selectedDate.value = date;
                inputValue.value = formatDateForDisplay(
                    formatDateForModel(date)
                );
                emit('update:modelValue', formatDateForModel(date));
                emit('change', formatDateForModel(date));
                if (props.calculateOnInput) {
                    emit('calculate', formatDateForModel(date));
                }
                showCalendar.value = false;
            }
        }
    };

    defineExpose({ focus: () => inputRef.value.focus() });
</script>

<template>
    <div class="flex flex-col gap-1 w-full relative">
        <ClassicLabel
            v-if="label"
            :value="label"
            :theme="labelTheme"
            :weight="labelWeight"
            :size="labelSize"
            :transform="labelTransform"
            :required="labelRequired"
            class="font-semibold"
            :for="id"
        />
        <div class="relative" ref="inputRef">
            <input
                :id="id"
                type="text"
                v-model="inputValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :readonly="!allowInput"
                @focus="
                    showCalendar = true;
                    handleFocus();
                "
                @input="handleInputChange"
                @blur="
                    handleInputBlur;
                    handleBlur();
                "
                :class="[
                    'w-full rounded-md py-2 px-3 bg-white text-gray-800',
                    themeClasses.border,
                    themeClasses.focus,
                    bold ? 'font-semibold' : '',
                    disabled
                        ? 'bg-gray-100 text-gray-500 cursor-not-allowed'
                        : 'cursor-text',
                ]"
            />
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <CalendarClock
                    class="h-5 w-5 text-gray-500 cursor-pointer"
                    @click="!disabled && (showCalendar = !showCalendar)"
                />
            </div>
            <div
                v-show="showCalendar"
                ref="calendarRef"
                class="absolute z-50 mt-1 p-4 bg-white border border-gray-200 rounded-lg shadow-lg"
                style="width: 320px"
            >
                <!-- Encabezado con selectores de año y mes -->
                <div class="flex justify-between items-center mb-4">
                    <button
                        @click="prevMonth"
                        :class="['p-1 rounded-full', themeClasses.buttonHover]"
                        type="button"
                    >
                        <ChevronLeft />
                    </button>

                    <div class="flex gap-2">
                        <!-- Selector de mes -->
                        <div class="relative">
                            <button
                                @click="showMonthSelector = !showMonthSelector"
                                :class="[
                                    'text-lg font-semibold text-gray-800',
                                    themeClasses.dropdown.button,
                                ]"
                            >
                                {{ months[currentDate.getMonth()] }}
                            </button>
                            <div
                                v-if="showMonthSelector"
                                class="absolute top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto"
                            >
                                <button
                                    v-for="(month, index) in months"
                                    :key="index"
                                    @click="handleMonthSelect(index)"
                                    :class="[
                                        'block w-full px-4 py-2 text-left text-gray-700',
                                        themeClasses.dropdown.item,
                                        {
                                            [themeClasses.dropdown.selected]:
                                                index ===
                                                currentDate.getMonth(),
                                        },
                                    ]"
                                >
                                    {{ month }}
                                </button>
                            </div>
                        </div>
                        <!-- Selector de año -->
                        <div class="relative">
                            <button
                                @click="showYearSelector = !showYearSelector"
                                :class="[
                                    'text-lg font-semibold text-gray-800',
                                    themeClasses.dropdown.button,
                                ]"
                            >
                                {{ currentDate.getFullYear() }}
                            </button>
                            <div
                                v-if="showYearSelector"
                                class="absolute top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto"
                            >
                                <button
                                    v-for="year in availableYears"
                                    :key="year"
                                    @click="handleYearSelect(year)"
                                    :class="[
                                        'block w-full px-4 py-2 text-left text-gray-700',
                                        themeClasses.dropdown.item,
                                        {
                                            [themeClasses.dropdown.selected]:
                                                year ===
                                                currentDate.getFullYear(),
                                        },
                                    ]"
                                >
                                    {{ year }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <button
                        @click="nextMonth"
                        :class="['p-1 rounded-full', themeClasses.buttonHover]"
                        type="button"
                    >
                        <ChevronRight />
                    </button>
                </div>

                <!-- Días de la semana -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <div
                        v-for="(day, index) in weekdays"
                        :key="index"
                        class="text-center text-sm font-medium text-gray-600"
                    >
                        {{ day }}
                    </div>
                </div>

                <!-- Días del mes -->
                <div class="grid grid-cols-7 gap-1">
                    <button
                        v-for="(day, index) in calendarDays"
                        :key="index"
                        @click="selectDay(day)"
                        :disabled="isDateDisabled(day)"
                        type="button"
                        :class="[
                            'w-10 h-10 flex items-center justify-center rounded-full text-sm',
                            isDateSelected(day)
                                ? themeClasses.selected
                                : day.isCurrentMonth
                                  ? `text-gray-800 ${themeClasses.hover}`
                                  : 'text-gray-400',
                            isDateDisabled(day)
                                ? 'cursor-not-allowed opacity-50'
                                : 'cursor-pointer',
                        ]"
                    >
                        {{ day.day }}
                    </button>
                </div>

                <div
                    class="flex justify-between mt-4 pt-2 border-t border-gray-200"
                >
                    <button
                        @click="clearDate"
                        :class="['text-sm', themeClasses.accent]"
                        type="button"
                        :disabled="disabled"
                    >
                        Limpiar
                    </button>
                    <button
                        @click="goToToday"
                        :class="['text-sm font-medium', themeClasses.accent]"
                        type="button"
                        :disabled="disabled"
                    >
                        Hoy
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .calendar-enter-active,
    .calendar-leave-active {
        transition: all 0.3s ease;
    }
    .calendar-enter-from,
    .calendar-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>
