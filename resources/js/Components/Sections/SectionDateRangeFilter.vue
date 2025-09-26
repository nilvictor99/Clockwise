<script setup>
    import { ref, watch, computed } from 'vue';
    import InputDateClasic from '@/Components/Inputs/InputDateClasic.vue';
    import Eraser from '@/Components/Icons/Eraser.vue';

    const props = defineProps({
        modelValue: {
            type: Object,
            default: () => ({ start: '', end: '' }),
        },
        label: String,
        borderColor: {
            type: String,
            default: 'border-gray-300',
        },
        focusColor: {
            type: String,
            default: 'focus:border-gray-500 focus:ring-gray-200',
        },
        bold: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        cleanButton: {
            type: Boolean,
            default: true,
        },
        placeholderStart: {
            type: String,
            default: 'Fecha inicio',
        },
        placeholderEnd: {
            type: String,
            default: 'Fecha fin',
        },
    });

    const emit = defineEmits(['update:modelValue', 'filter', 'cleared']);

    const localValue = ref({ ...props.modelValue });

    // Calcular si el campo de fecha final debe estar deshabilitado
    const isEndDateDisabled = computed(() => {
        return props.disabled || !localValue.value.start;
    });

    watch(
        () => props.modelValue,
        val => {
            localValue.value = { ...val };
        }
    );

    function updateValue() {
        emit('update:modelValue', { ...localValue.value });
        emit('filter', { ...localValue.value });
    }

    function handleStartDateChange(date) {
        localValue.value.start = date;

        // Si la fecha final existe y es anterior a la nueva fecha de inicio, resetearla
        if (
            localValue.value.end &&
            localValue.value.end < localValue.value.start
        ) {
            localValue.value.end = '';
        }

        updateValue();
    }

    function handleEndDateChange(date) {
        localValue.value.end = date;
        updateValue();
    }

    function clearDates() {
        localValue.value = { start: '', end: '' };
        updateValue();
        emit('cleared');
    }
</script>

<template>
    <div class="flex flex-col gap-1 w-full">
        <label v-if="label" class="font-semibold mb-1">{{ label }}</label>
        <div class="flex flex-col md:flex-row gap-2 items-center">
            <div class="flex-1 w-full md:w-auto">
                <InputDateClasic
                    v-model="localValue.start"
                    :placeholder="placeholderStart"
                    :disabled="disabled"
                    :borderColor="borderColor"
                    :focusColor="focusColor"
                    :bold="bold"
                    @change="handleStartDateChange"
                />
            </div>
            <span class="mx-1 text-gray-500 hidden md:inline">-</span>
            <div class="flex-1 w-full md:w-auto">
                <InputDateClasic
                    v-model="localValue.end"
                    :placeholder="placeholderEnd"
                    :disabled="isEndDateDisabled"
                    :borderColor="borderColor"
                    :allowInput="false"
                    :focusColor="
                        isEndDateDisabled
                            ? 'focus:border-gray-300 focus:ring-gray-100'
                            : focusColor
                    "
                    :bold="bold"
                    :minDate="localValue.start"
                    @change="handleEndDateChange"
                />
            </div>
            <button
                v-if="cleanButton && (localValue.start || localValue.end)"
                type="button"
                @click="clearDates"
                class="ml-2 px-2 py-2 rounded bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 mt-2 md:mt-0"
                :disabled="disabled"
            >
                <Eraser class="inline-block w-6 h-6" />
            </button>
        </div>
    </div>
</template>
