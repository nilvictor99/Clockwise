<script setup>
    import DialogModal from '@/Components/Modals/DialogModal.vue';

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['close']);

    const closeModal = () => {
        emit('close');
    };
</script>

<template>
    <DialogModal
        :show="show"
        @close="closeModal"
        maxWidth="sm"
        class="bg-white rounded-lg shadow-lg animate-fade-in"
    >
        <template #title>
            <h3 class="text-lg font-semibold text-gray-800">
                Demasiados Intentos
            </h3>
        </template>
        <template #content>
            <p class="text-sm text-gray-600">
                Has realizado demasiados intentos de inicio de sesión. Por
                favor, espera unos minutos antes de intentarlo de nuevo.
            </p>
        </template>
        <template #footer>
            <button
                @click="closeModal"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200"
            >
                Entendido
            </button>
        </template>
    </DialogModal>
</template>

<style scoped>
    /* Animación para el modal */
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
