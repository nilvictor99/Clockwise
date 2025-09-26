<script setup>
    import { defineProps, defineEmits } from 'vue';
    import Modal from '@/Components/Modals/Modal.vue';
    import XIcon from '../Icons/XIcon.vue';
    import ClasicButton from '../Buttons/ClasicButton.vue';

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        title: {
            type: String,
            default: '¿Está seguro?',
        },
        description: {
            type: String,
            default:
                '¿Está seguro que desea eliminar este elemento? Esta acción no se puede deshacer.',
        },
        itemType: {
            type: String,
            default: 'elemento',
        },
        itemId: {
            type: [String, Number],
            default: '',
        },
        itemDetails: {
            type: Object,
            default: () => ({}),
        },
        cancelButtonText: {
            type: String,
            default: 'Cancelar',
        },
        confirmButtonText: {
            type: String,
            default: 'Eliminar',
        },
        loading: {
            type: Boolean,
            default: false,
        },
        showItemId: {
            type: Boolean,
            default: true,
        },
    });

    const emit = defineEmits(['close', 'confirm']);

    const closeModal = () => {
        emit('close');
    };

    const confirmDelete = () => {
        emit('confirm');
    };
</script>

<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ title }}
                </h3>
                <button
                    @click="closeModal"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                >
                    <XIcon class="h-8 w-8" />
                </button>
            </div>

            <div class="mt-2 text-gray-600">
                <p>{{ description }}</p>
            </div>

            <div
                v-if="Object.keys(itemDetails).length > 0 || showItemId"
                class="bg-gray-50 rounded-lg p-4 mt-4"
            >
                <h4 class="text-sm font-medium text-gray-700 mb-2">
                    {{ itemType }}:
                </h4>
                <dl class="grid grid-cols-2 gap-2">
                    <template v-if="showItemId && itemId">
                        <dt class="text-sm text-gray-500">ID:</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ itemId }}
                        </dd>
                    </template>

                    <slot name="item-details"></slot>
                </dl>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <ClasicButton
                    variant="secondary"
                    :disabled="loading"
                    @click="closeModal"
                >
                    {{ cancelButtonText }}
                </ClasicButton>
                <ClasicButton
                    variant="danger"
                    :loading="loading"
                    :disabled="loading"
                    @click="confirmDelete"
                >
                    {{ confirmButtonText }}
                </ClasicButton>
            </div>
        </div>
    </Modal>
</template>
