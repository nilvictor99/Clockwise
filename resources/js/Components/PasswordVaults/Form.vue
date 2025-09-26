<script setup>
    import { watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputLabelClassic from '../Inputs/InputLabelClassic.vue';
    import InputError from '../Inputs/InputError.vue';
    import TextareaClassic from '../Inputs/InputTexareaClasic.vue';
    import SelectClassic from '../Selects/SelectClassic.vue';
    import InputPasswordGenerate from '../Inputs/InputPasswordGenerate.vue';

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
        name: props.data.name || '',
        username: props.data.username || '',
        password: props.data.password || '',
        url: props.data.url || '',
        notes: props.data.notes || '',
        type: props.data.type || 'private',
        active: props.data.active !== undefined ? props.data.active : true, // Agregué active si es necesario
    });

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('password-vault.update', props.data.id), {
                onSuccess: () => {
                    emit('submitted');
                },
            });
        } else {
            form.post(route('password-vault.store'), {
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
                form.name = newData.name || '';
                form.username = newData.username || '';
                form.password = newData.password || '';
                form.url = newData.url || '';
                form.notes = newData.notes || '';
                form.type = newData.type || 'private';
                form.active =
                    newData.active !== undefined ? newData.active : true;
            }
        },
        { deep: true }
    );
</script>

<template>
    <form
        @submit.prevent="submitForm"
        class="bg-white p-4 sm:p-6 rounded-lg shadow-md w-full max-w-7xl mx-auto"
    >
        <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-gray-900">
            {{ mode === 'edit' ? 'Editar Contraseña' : 'Crear Contraseña' }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <InputLabelClassic
                    v-model="form.name"
                    label="Nombre"
                    type="text"
                    theme="gray"
                    :required="true"
                    name="name"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa el nombre"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.name || ''"
                />
                <InputError class="mt-1" :message="form.errors.name" />
            </div>
            <div>
                <InputLabelClassic
                    v-model="form.username"
                    label="Usuario"
                    type="text"
                    theme="gray"
                    name="username"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa el usuario"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.username || ''"
                />
                <InputError class="mt-1" :message="form.errors.username" />
            </div>
            <div class="sm:col-span-2 lg:col-span-1">
                <InputPasswordGenerate
                    v-model="form.password"
                    :generateRoute="route('generate-password')"
                    label="Contraseña"
                    placeholder="Ingresa la contraseña"
                    theme="gray"
                    :initialValue="props.data.password || ''"
                    :disabled="false"
                />
                <InputError class="mt-1" :message="form.errors.password" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <InputLabelClassic
                    v-model="form.url"
                    label="URL"
                    type="url"
                    theme="gray"
                    name="url"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa la URL"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.url || ''"
                />
                <InputError class="mt-1" :message="form.errors.url" />
            </div>
            <div>
                <SelectClassic
                    v-model="form.type"
                    :options="[
                        { value: 'private', label: 'Privado' },
                        { value: 'public', label: 'Público' },
                    ]"
                    label="Tipo"
                    theme="gray"
                    :disabled="false"
                    placeholder="Seleccione un tipo"
                    :multiple="false"
                    :CleanButton="true"
                />
                <InputError class="mt-1" :message="form.errors.type" />
            </div>
        </div>
        <div class="mt-4">
            <TextareaClassic
                v-model="form.notes"
                label="Notas"
                theme="gray"
                :font-weight="'semibold'"
                :rows="4"
                placeholder="Ingresa las notas"
                :defaultValue="props.data.notes || ''"
            />
        </div>
        <div
            class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4"
        >
            <ClasicButton
                type="button"
                variant="gray"
                @click="cancel"
                class="w-full sm:w-auto flex items-center justify-center"
            >
                Cancelar
            </ClasicButton>
            <ClasicButton
                type="submit"
                variant="gray"
                :loading="form.processing"
                :disabled="form.processing"
                class="w-full sm:w-auto flex items-center justify-center"
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
