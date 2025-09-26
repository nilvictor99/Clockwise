<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { QrcodeStream } from 'vue-qrcode-reader';
    import XIcon from '../Icons/XIcon.vue';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import Modal from '../Modals/Modal.vue';
    import InputError from '../Inputs/InputError.vue';
    import LoadPoints from '@/Components/Icons/LoadPoints.vue';
    import InputSelectClasic from '../Inputs/InputSelectClasic.vue';
    import Bulb from '@/Components/Icons/Bulb.vue';
    import BulbOff from '@/Components/Icons/BulbOff.vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['close']);

    const scanning = ref(false);
    const error = ref('');
    const cameraError = ref('');
    const loading = ref(false);
    const selectedCamera = ref('work');
    const torchEnabled = ref(false);
    const toggleTorch = () => {
        torchEnabled.value = !torchEnabled.value;
    };

    watch(
        () => props.show,
        newShow => {
            if (!newShow) {
                stopScanning();
            }
        }
    );

    const onDetect = detectedCodes => {
        if (detectedCodes.length > 0) {
            const result = detectedCodes[0].rawValue;
            scanning.value = false;
            loading.value = false;
            router.post(
                route('scan'),
                { qr_data: result, type: selectedCamera.value },
                {
                    onSuccess: () => {
                        emit('close');
                        router.reload();
                    },
                }
            );
        }
    };

    const onError = err => {
        cameraError.value = err.message;
        loading.value = false;
        emit('error', err);
    };

    const startScanning = () => {
        if (!selectedCamera.value) {
            error.value = 'Selecciona un tipo antes de escanear.';
            return;
        }
        scanning.value = true;
        error.value = '';
        cameraError.value = '';
        loading.value = true;
    };

    const stopScanning = () => {
        scanning.value = false;
        loading.value = false;
    };

    const closeModal = () => {
        stopScanning();
        emit('close');
    };
</script>

<template>
    <Modal :show="props.show" @close="closeModal" maxWidth="md">
        <div class="p-6 sm:p-6 lg:p-8">
            <div class="flex justify-between items-center mb-4">
                <h2
                    class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-800"
                >
                    Escáner de Código QR
                </h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-700 transition-colors"
                >
                    <XIcon class="h-6 w-6 sm:h-8 sm:w-8" />
                </button>
            </div>

            <div class="qr-scanner">
                <div
                    class="flex flex-col sm:flex-row gap-2 p-2 justify-center mb-4"
                >
                    <div
                        class="flex flex-col sm:flex-col gap-2 w-full sm:w-[200px]"
                    >
                        <InputSelectClasic
                            v-if="!scanning"
                            v-model="selectedCamera"
                            :options="[
                                { id: 'work', text: 'Trabajo' },
                                { id: 'break', text: 'Descanso' },
                            ]"
                            placeholder="Tipo"
                            theme="gray"
                            :required="true"
                            :CleanButton="true"
                            class="w-full sm:w-auto"
                        />
                        <ClasicButton
                            @click="startScanning"
                            :disabled="scanning"
                            variant="blue"
                            class="w-full sm:w-auto flex justify-center"
                        >
                            <LoadPoints v-if="scanning" :variant="'white'" />
                            <span v-else>Iniciar Escaneo</span>
                        </ClasicButton>
                    </div>
                    <ClasicButton
                        v-if="scanning"
                        @click="stopScanning"
                        variant="danger"
                        class="w-full sm:w-auto flex justify-center"
                    >
                        Detener
                    </ClasicButton>
                    <ClasicButton
                        v-if="scanning"
                        @click="toggleTorch"
                        variant="gray"
                        class="w-full sm:w-auto flex justify-center items-center"
                    >
                        <Bulb v-if="!torchEnabled" class="w-5 h-5" />
                        <BulbOff v-else class="w-5 h-5" />
                    </ClasicButton>
                </div>
                <div v-if="scanning" class="mt-4 relative">
                    <div
                        v-if="loading"
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg z-10"
                    >
                        <LoadPoints />
                    </div>
                    <qrcode-stream
                        @detect="onDetect"
                        @error="onError"
                        :formats="['qr_code']"
                        :torch="torchEnabled"
                        class="rounded-lg border border-gray-300 shadow-md w-full max-w-xs sm:max-w-md lg:max-w-lg mx-auto"
                    />
                    <InputError class="mt-2 text-center text-sm sm:text-base">
                        {{ $t(cameraError) }}
                    </InputError>
                </div>
                <InputError class="mt-2 text-center text-sm sm:text-base">
                    {{ $t(error) }}
                </InputError>
            </div>
        </div>
    </Modal>
</template>
