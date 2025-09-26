<script setup>
    import { watch, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputError from '../Inputs/InputError.vue';
    import SelectClassic from '../Selects/SelectClassic.vue';

    const props = defineProps({
        mode: {
            type: String,
            default: 'create',
        },
        data: {
            type: Object,
            default: () => ({}),
        },
        passwords: {
            type: Array,
            default: () => [],
        },
        users: {
            type: Array,
            default: () => [],
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    // Computed properties para manejar los datos de usuario y contraseña
    const selectedPassword = computed(() => ({
        value: props.data?.password?.id,
        label: props.data?.password?.name,
    }));

    const selectedUser = computed(() => ({
        value: props.data?.shared_with?.id,
        label: props.data?.shared_with?.name,
        image: props.data?.shared_with?.profile_photo_url,
    }));

    const form = useForm({
        password_id: props.data?.password_id || '',
        shared_with: props.data?.shared_with?.id || '',
        permissions: props.data?.permissions || 'view',
    });

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('password-share.update', props.data.id), {
                onSuccess: () => emit('submitted'),
                preserveScroll: true,
            });
        } else {
            form.post(route('password-share.store'), {
                onSuccess: () => emit('submitted'),
                preserveScroll: true,
            });
        }
    };

    const cancel = () => {
        emit('cancelled');
    };

    // Opciones para el select de permisos
    const permissionOptions = [
        { value: 'view', label: 'Solo lectura' },
        { value: 'edit', label: 'Lectura y escritura' },
    ];

    watch(
        () => props.data,
        newData => {
            if (newData) {
                form.password_id = newData.password_id;
                form.shared_with = newData.shared_with?.id;
                form.permissions = newData.permissions;
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
            {{
                mode === 'edit'
                    ? 'Editar Compartir Contraseña'
                    : 'Compartir Nueva Contraseña'
            }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <SelectClassic
                    v-model="form.password_id"
                    :options="[selectedPassword]"
                    label="Contraseña"
                    theme="gray"
                    :disabled="false"
                    placeholder="Seleccione una contraseña"
                    :multiple="false"
                    :CleanButton="false"
                />
                <InputError class="mt-1" :message="form.errors.password_id" />
            </div>

            <div>
                <SelectClassic
                    v-model="form.shared_with"
                    :options="[selectedUser]"
                    label="Compartido con"
                    theme="gray"
                    :disabled="false"
                    placeholder="Seleccione un usuario"
                    :multiple="false"
                    :CleanButton="false"
                    :showImages="true"
                    imageKey="image"
                    imageSize="sm"
                    fallbackImage="https://ui-avatars.com/api/?name=u&color=7F9CF5&background=EBF4FF"
                />
                <InputError class="mt-1" :message="form.errors.shared_with" />
            </div>
        </div>

        <div class="mb-4">
            <SelectClassic
                v-model="form.permissions"
                :options="permissionOptions"
                label="Permisos"
                theme="gray"
                :disabled="false"
                placeholder="Seleccione los permisos"
                :multiple="false"
                :CleanButton="false"
            />
            <InputError class="mt-1" :message="form.errors.permissions" />
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
                {{ form.processing ? 'Guardando...' : 'Actualizar' }}
            </ClasicButton>
        </div>
    </form>
</template>
