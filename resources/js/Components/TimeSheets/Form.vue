<script setup>
    import { watch, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputLabelClassic from '../Inputs/InputLabelClassic.vue';
    import InputError from '../Inputs/InputError.vue';
    import InputTimeClassic from '../Inputs/InputTimeClassic.vue';
    import InputDateClasic from '../Inputs/InputDateClasic.vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';

    const props = defineProps({
        mode: {
            type: String,
            default: 'create',
        },
        data: {
            type: Object,
            default: () => ({}),
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    const form = useForm({
        calendar: props.data.calendar || '',
        staff_id: props.data.staff_id || '',
        type: props.data.type || '',
        day_in: props.data.day_in || '',
        day_out: props.data.day_out || '',
    });

    const staffName = computed(() => props.data?.staff?.name || '');
    const staffType = computed(() => props.data?.type || '');

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('timesheets.update', props.data.id), {
                onSuccess: () => {
                    emit('submitted');
                },
            });
        } else {
            form.post(route('timesheets.store'), {
                onSuccess: () => {
                    emit('submitted');
                },
            });
        }
    };

    const cancel = () => {
        emit('cancelled');
    };

    watch(
        () => props.data,
        newData => {
            if (newData) {
                form.calendar = newData.calendar || '';
                form.staff_id = newData.staff_id || '';
                form.type = newData.type || '';
                form.day_in = newData.day_in || '';
                form.day_out = newData.day_out || '';
            }
        },
        { deep: true }
    );
</script>

<template>
    <form
        @submit.prevent="submitForm"
        class="bg-white p-6 rounded-lg shadow-md w-full mx-auto"
    >
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ mode === 'edit' ? 'Editar Asistencia' : 'Crear Asistencia' }}
            </h2>
            <div v-if="mode === 'edit'" class="flex space-x-2 items-center">
                <span
                    v-if="staffName"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-300"
                >
                    <strong class="mr-1">Staff:</strong> {{ staffName }}
                </span>
                <span
                    v-if="staffType"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-300"
                >
                    <strong class="mr-1">Tipo:</strong> {{ staffType }}
                </span>
            </div>
        </div>

        <div v-if="form.errors.general" class="mb-4 text-red-600">
            {{ form.errors.general }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div v-if="mode === 'create'">
                <InputLabelClassic
                    v-model="form.staff_id"
                    label="Staff"
                    type="text"
                    theme="gray"
                    :required="true"
                    name="staff_id"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona el staff"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.staff_id || ''"
                />
                <InputError class="mt-1" :message="form.errors.staff_id" />
            </div>
            <div v-if="mode === 'create'">
                <InputLabelClassic
                    v-model="form.type"
                    label="Tipo"
                    type="text"
                    theme="gray"
                    name="type"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa el tipo"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.type || ''"
                />
                <InputError class="mt-1" :message="form.errors.type" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <InputLabelClassic
                    v-model="form.calendar"
                    label="Calendario"
                    type="text"
                    theme="gray"
                    :required="true"
                    name="calendar"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la fecha"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.calendar || ''"
                />
                <InputError class="mt-1" :message="form.errors.calendar" />
            </div>

            <div>
                <ClassicLabel
                    for="calendar"
                    class="mb-1"
                    value="Fecha de Asistencia"
                />
                <InputDateClasic
                    v-model="form.date"
                    label="Fecha de Asistencia"
                    theme="gray"
                    :required="true"
                    name="calendar"
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
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="
                        props.data.day_in ? props.data.day_in.split(' ')[1] : ''
                    "
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
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="
                        props.data.day_out
                            ? props.data.day_out.split(' ')[1]
                            : ''
                    "
                />
                <InputError class="mt-1" :message="form.errors.day_out" />
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
            <ClasicButton type="button" variant="gray" @click="cancel">
                Cancelar
            </ClasicButton>
            <ClasicButton
                type="submit"
                variant="gray"
                :loading="form.processing"
                :disabled="form.processing"
            >
                {{
                    form.processing
                        ? 'Guardando...'
                        : mode === 'edit'
                          ? 'Actualizar'
                          : 'Crear'
                }}
            </ClasicButton>
        </div>
    </form>
</template>
