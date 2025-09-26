<script setup>
    import { watch, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import SelectClassic from '@/Components/Selects/SelectClassic.vue';
    import InputSelectClasic from '../Inputs/InputSelectClasic.vue';
    import InputError from '@/Components/Inputs/InputError.vue';
    import XIcon from '../Icons/XIcon.vue';
    import CustomModal from '../Modals/CustomModal.vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        vaults: {
            type: Array,
            default: () => [],
        },
        users: {
            type: Array,
            default: () => [],
        },
    });

    const emit = defineEmits(['close']);

    const form = useForm({
        vault_ids: [],
        user_id: '',
        permission: 'view',
    });

    const vaultOptions = computed(() => {
        return props.vaults.map(vault => ({
            id: vault.id,
            text: vault.name,
        }));
    });

    const userOptions = computed(() => {
        return props.users.map(user => ({
            value: user.id,
            label: user.name,
            image: user.profile_photo_url,
        }));
    });

    const permissionOptions = [
        { value: 'view', label: 'Ver Contraseña' },
        { value: 'edit', label: 'Ver y Editar' },
    ];

    const closeModal = () => {
        form.reset();
        emit('close');
    };

    const shareVaults = () => {
        form.post(route('password-share.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
            onError: errors => {
                console.error('Error al compartir:', errors);
            },
        });
    };

    watch(
        () => props.show,
        newShow => {
            if (newShow && props.vaults.length > 0) {
                form.vault_ids = props.vaults.map(vault => vault.id);
            }
        }
    );
</script>

<template>
    <CustomModal :show="show" @close="closeModal">
        <div class="p-4">
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h2 class="text-xl font-semibold text-gray-900">
                    Compartir Contraseñas
                </h2>
                <button
                    @click="closeModal"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                >
                    <XIcon class="h-6 w-6" />
                </button>
            </div>

            <form @submit.prevent="shareVaults" class="space-y-6">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mt-4"
                >
                    <div>
                        <SelectClassic
                            v-model="form.user_id"
                            :options="userOptions"
                            label="Seleccionar Usuario"
                            theme="gray"
                            placeholder="Seleccione un usuario"
                            :multiple="false"
                            :CleanButton="false"
                            :showImages="true"
                            imageKey="image"
                            imageSize="sm"
                            fallbackImage="https://ui-avatars.com/api/?name=u&color=7F9CF5&background=EBF4FF /System/Samples/userimage.webp"
                        />
                        <InputError
                            :message="form.errors.user_id"
                            class="mt-1"
                        />
                    </div>

                    <div>
                        <SelectClassic
                            v-model="form.permission"
                            :options="permissionOptions"
                            label="Permisos"
                            theme="gray"
                            placeholder="Seleccione los permisos"
                            :multiple="false"
                            :CleanButton="false"
                        />
                        <InputError
                            :message="form.errors.permission"
                            class="mt-1"
                        />
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 mt-4"
                >
                    <div>
                        <InputSelectClasic
                            v-model="form.vault_ids"
                            :options="vaultOptions"
                            placeholder="Selecciona las contraseñas"
                            :multiple="true"
                            theme="gray"
                            :required="true"
                            label="Contraseñas"
                            :CleanButton="true"
                        />
                        <InputError
                            :message="form.errors.vault_ids"
                            class="mt-1"
                        />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <ClasicButton variant="secondary" @click="closeModal">
                        Cancelar
                    </ClasicButton>
                    <ClasicButton
                        type="submit"
                        variant="blue"
                        :disabled="!form.user_id || form.processing"
                        :loading="form.processing"
                    >
                        Compartir
                    </ClasicButton>
                </div>
            </form>
        </div>
    </CustomModal>
</template>
