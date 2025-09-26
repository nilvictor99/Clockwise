<script setup>
    import { watch, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputError from '../Inputs/InputError.vue';
    import InputTimeClassic from '../Inputs/InputTimeClassic.vue';
    import InputDateSimple from '../Inputs/InputDateSimple.vue';

    const props = defineProps({
        data: {
            type: Object,
            required: true,
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    const extractDate = data => {
        if (!data) return '';
        const date = new Date(data);
        return date.toISOString().split('T')[0];
    };

    const extractTime = datetime => {
        if (!datetime) return '';
        const date = new Date(datetime);
        return date.toLocaleTimeString('es-PE', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        });
    };

    const form = useForm({
        date: extractDate(props.data.day_in),
        day_in: extractTime(props.data.day_in),
        day_out: extractTime(props.data.day_out),
    });

    const staffName = computed(
        () => props.data?.staff?.full_name || props.data?.staff?.name || ''
    );
    const staffType = computed(() => props.data?.type || '');
    const calendarYear = computed(() => props.data?.calendar || '');

    const submitForm = () => {
        form.put(route('timesheets.update', props.data.id), {
            onSuccess: () => {
                emit('submitted');
            },
        });
    };

    const cancel = () => {
        emit('cancelled');
    };

    watch(
        () => props.data,
        newData => {
            if (newData) {
                form.date = extractDate(newData.day_in);
                form.day_in = extractTime(newData.day_in);
                form.day_out = extractTime(newData.day_out);
            }
        },
        { deep: true }
    );
</script>

<template>
    <form
        @submit.prevent="submitForm"
        class="bg-white p-4 sm:p-6 rounded-lg shadow-md w-full mx-auto max-w-4xl"
    >
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6"
        >
            <h2
                class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-0"
            >
                Editar Asistencia
            </h2>
            <div class="flex flex-wrap gap-2 items-center">
                <span
                    v-if="staffName"
                    class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-300"
                >
                    <strong class="mr-1">Colaborador:</strong> {{ staffName }}
                </span>
                <span
                    v-if="staffType"
                    class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold"
                    :class="{
                        'bg-green-100 text-green-800 border border-green-300':
                            staffType === 'work',
                        'bg-blue-100 text-blue-800 border border-blue-300':
                            staffType === 'break',
                    }"
                >
                    <strong class="mr-1">Tipo:</strong> {{ $t(staffType) }}
                </span>
                <span
                    v-if="calendarYear"
                    class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-300"
                >
                    <strong class="mr-1">Calendario:</strong> {{ calendarYear }}
                </span>
            </div>
        </div>

        <div
            v-if="form.errors.general"
            class="mb-4 text-red-600 text-sm sm:text-base"
        >
            {{ form.errors.general }}
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <InputDateSimple
                    v-model="form.date"
                    label="Fecha de Asistencia"
                    theme="gray"
                    :required="true"
                    name="date"
                    placeholder="Selecciona la fecha"
                    :disabled="false"
                    :minDate="null"
                    :maxDate="null"
                    :allowInput="true"
                    :yearRange="10"
                />
                <InputError class="mt-1" :message="form.errors.date" />
            </div>
            <div>
                <InputTimeClassic
                    v-model="form.day_in"
                    label="Hora de Inicio"
                    name="day_in"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la hora de inicio"
                    theme="gray"
                />
                <InputError class="mt-1" :message="form.errors.day_in" />
            </div>
            <div>
                <InputTimeClassic
                    v-model="form.day_out"
                    label="Hora de Fin"
                    name="day_out"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la hora de fin"
                    theme="gray"
                />
                <InputError class="mt-1" :message="form.errors.day_out" />
            </div>
        </div>

        <div
            class="mt-6 flex flex-col sm:flex-row sm:justify-end sm:space-x-4 space-y-2 sm:space-y-0"
        >
            <ClasicButton
                type="button"
                variant="gray"
                @click="cancel"
                class="w-full flex justify-center sm:w-auto"
            >
                Cancelar
            </ClasicButton>
            <ClasicButton
                type="submit"
                variant="gray"
                :loading="form.processing"
                :disabled="form.processing"
                class="w-full sm:w-auto flex justify-center"
            >
                {{ form.processing ? 'Guardando...' : 'Actualizar' }}
            </ClasicButton>
        </div>
    </form>
</template>
