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
        userName: {
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
    <Modal :show="show" @close="closeModal" maxWidth="sm">
        <div
            class="p-4 sm:p-4 h-42 sm:h-42 flex justify-between items-center border-b border-gray-200"
        >
            <h2
                class="text-lg sm:text-xl font-semibold text-gray-800 flex items-center gap-2"
            >
                Código QR -
                <span
                    class="bg-gray-500 text-gray-100 text-xs font-bold rounded-md uppercase px-2 py-1"
                    >{{ userName }}</span
                >
            </h2>

            <button
                class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200"
                @click="closeModal"
            >
                <XIcon class="h-8 w-8 sm:h-8 sm:w-8" />
            </button>
        </div>
        <div class="p-4 sm:p-6 flex flex-col justify-center">
            <img
                :src="qrCode"
                alt="QR Code"
                class="max-w-full h-auto rounded-lg shadow-md"
            />
        </div>
    </Modal>
</template>
