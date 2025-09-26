<script setup>
    import Modal from '@/Components/Modals/Modal.vue';
    import XIcon from '../Icons/XIcon.vue';

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        qrCode: {
            type: String,
            default: '',
        },
    });

    const emit = defineEmits(['close']);

    const closeModal = () => {
        emit('close');
    };
</script>

<template>
    <Modal :show="show" @close="closeModal" maxWidth="md">
        <div
            class="p-4 sm:p-6 flex justify-between items-center border-b border-gray-200"
        >
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                Código QR
            </h2>
            <button
                class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200"
                @click="closeModal"
            >
                <XIcon class="h-6 w-6 sm:h-8 sm:w-8" />
            </button>
        </div>
        <div class="p-4 sm:p-6 flex justify-center">
            <img
                :src="qrCode"
                alt="QR Code"
                class="max-w-full h-auto rounded-lg shadow-md"
            />
        </div>
    </Modal>
</template>
