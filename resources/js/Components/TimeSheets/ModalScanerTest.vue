<script setup>
    import { ref, watch } from 'vue';
    import {
        QrcodeStream,
        QrcodeCapture,
        QrcodeDropZone,
        setZXingModuleOverrides,
    } from 'vue-qrcode-reader';
    import XIcon from '../Icons/XIcon.vue';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import Modal from '../Modals/Modal.vue';
    import InputError from '../Inputs/InputError.vue';
    import LoadPoints from '@/Components/Icons/LoadPoints.vue';
    import Camera from '../Icons/Camera.vue';
    import Upload from '../Icons/Upload.vue';
    import DragDrop from '../Icons/DragDrop.vue';
    import RosetteCheck from '../Icons/RosetteCheck.vue';

    setZXingModuleOverrides({
        locateFile: file =>
            `https://cdn.jsdelivr.net/npm/@zxing/library@0.19.3/${file}`,
    });

    const props = defineProps({
        show: { type: Boolean, default: false },
    });

    const emit = defineEmits(['close', 'success', 'error']);

    const activeTab = ref('stream');
    const scanning = ref(false);
    const error = ref('');
    const cameraError = ref('');
    const loading = ref(false);
    const scannedResult = ref('');
    const detectedCodes = ref([]);
    const torchEnabled = ref(false);
    const formats = ref(['qr_code']);

    watch(
        () => props.show,
        newVal => {
            if (!newVal) {
                stopScanning();
                resetState();
            }
        }
    );

    const onDetect = codes => {
        if (codes.length > 0) {
            const result = codes[0].rawValue;
            scannedResult.value = result;
            detectedCodes.value = codes;
            emit('success', result);
        }
    };

    const onError = err => {
        if (activeTab.value === 'stream') {
            cameraError.value = err.message;
        } else {
            error.value = err.message;
        }
        loading.value = false;
        emit('error', err);
    };

    const startScanning = () => {
        scanning.value = true;
        loading.value = true;
        resetState();
    };

    const stopScanning = () => {
        scanning.value = false;
        loading.value = false;
    };

    const resetState = () => {
        scannedResult.value = '';
        detectedCodes.value = [];
        error.value = '';
        cameraError.value = '';
    };

    const toggleTorch = () => {
        torchEnabled.value = !torchEnabled.value;
    };

    const closeModal = () => {
        stopScanning();
        resetState();
        emit('close');
    };
</script>

<template>
    <Modal :show="show" @close="closeModal" maxWidth="2xl">
        <div
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-h-screen flex flex-col"
        >
            <div
                class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200"
            >
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                    {{ $t('scan_qr_code') }}
                </h2>
                <button
                    @click="closeModal"
                    class="p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    :aria-label="$t('close_modal')"
                >
                    <XIcon class="w-5 h-5 text-gray-600" />
                </button>
            </div>

            <div class="flex border-b border-gray-200 bg-gray-50">
                <button
                    v-for="tab in ['stream', 'capture', 'dropzone']"
                    :key="tab"
                    @click="activeTab = tab"
                    :class="[
                        'flex-1 flex flex-col items-center py-3 text-xs sm:text-sm font-medium transition',
                        activeTab === tab
                            ? 'text-blue-600 bg-white'
                            : 'text-gray-500 hover:text-gray-700',
                    ]"
                >
                    <Camera v-if="tab === 'stream'" class="w-5 h-5 mb-1" />
                    <Upload
                        v-else-if="tab === 'capture'"
                        class="w-5 h-5 mb-1"
                    />
                    <DragDrop v-else class="w-5 h-5 mb-1" />
                    {{ $t(`tabs.${tab}`) }}
                </button>
            </div>
            <div class="flex-1 p-4 sm:p-6 overflow-y-auto">
                <transition name="slide">
                    <div
                        v-if="scannedResult"
                        class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-center"
                    >
                        <RosetteCheck
                            class="w-10 h-10 text-green-500 mx-auto mb-2"
                        />
                        <h3 class="text-sm font-semibold text-green-800">
                            {{ $t('code_detected') }}
                        </h3>
                        <p class="text-xs text-green-700 mt-1 break-all">
                            {{ scannedResult }}
                        </p>
                        <ClasicButton
                            @click="resetState"
                            variant="green"
                            size="sm"
                            class="mt-3 w-full sm:w-auto"
                        >
                            {{ $t('scan_another') }}
                        </ClasicButton>
                    </div>
                </transition>

                <!-- Modo: Cámara -->
                <div v-if="activeTab === 'stream'" class="space-y-4">
                    <div v-if="!scanning" class="text-center">
                        <ClasicButton
                            @click="startScanning"
                            variant="blue"
                            size="lg"
                            class="w-full flex justify-center"
                        >
                            {{ $t('start_camera') }}
                        </ClasicButton>
                    </div>

                    <div v-if="scanning" class="space-y-4">
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-100 p-4 rounded-lg shadow-md transition-all"
                        >
                            <ClasicButton
                                @click="stopScanning"
                                variant="red"
                                size="xs"
                                class="flex justify-center w-full py-1 px-2 hover:scale-105 focus:ring-1 focus:ring-red-500 transition-all"
                            >
                                {{ $t('stop_camera') }}
                            </ClasicButton>
                            <ClasicButton
                                @click="toggleTorch"
                                variant="gray"
                                size="xs"
                                class="flex justify-center w-full py-1 px-2 hover:scale-105 focus:ring-1 focus:ring-gray-500 transition-all"
                            >
                                {{
                                    $t(
                                        torchEnabled
                                            ? 'turn_off_flash'
                                            : 'turn_on_flash'
                                    )
                                }}
                            </ClasicButton>
                        </div>

                        <div
                            class="relative aspect-video bg-black rounded-xl overflow-hidden"
                        >
                            <div
                                v-if="loading"
                                class="absolute inset-0 flex items-center justify-center z-10"
                            >
                                <LoadPoints />
                            </div>

                            <qrcode-stream
                                @detect="onDetect"
                                @error="onError"
                                :formats="formats"
                                :torch="torchEnabled"
                                class="rounded-lg shadow-md w-full max-w-xs sm:max-w-md lg:max-w-lg mx-auto"
                            />

                            <svg
                                v-if="detectedCodes.length"
                                class="absolute inset-0 w-full h-full pointer-events-none transition-opacity duration-300"
                                viewBox="0 0 640 480"
                            >
                                <polygon
                                    v-for="(code, i) in detectedCodes"
                                    :key="i"
                                    :points="
                                        code.cornerPoints
                                            .map(p => `${p.x},${p.y}`)
                                            .join(' ')
                                    "
                                    fill="none"
                                    stroke="lime"
                                    stroke-width="4"
                                    opacity="0.8"
                                    class="animate-pulse"
                                />
                            </svg>
                        </div>
                    </div>
                    <InputError
                        v-if="cameraError"
                        class="text-center text-sm text-red-600 mb-4"
                    >
                        {{ cameraError }}
                    </InputError>
                    <InputError
                        v-if="error"
                        class="text-center text-sm text-red-600 mb-4"
                    >
                        {{ error }}
                    </InputError>
                </div>
                <div
                    v-else-if="activeTab === 'capture'"
                    class="text-center space-y-4"
                >
                    <qrcode-capture
                        @detect="onDetect"
                        @error="onError"
                        :formats="formats"
                        class="w-full flex justify-center border-2 border-dashed border-gray-300 rounded-xl p-8 bg-white hover:border-blue-400"
                    />
                </div>

                <div
                    v-else-if="activeTab === 'dropzone'"
                    class="text-center space-y-4"
                >
                    <qrcode-drop-zone
                        @detect="onDetect"
                        @error="onError"
                        :formats="formats"
                        class="w-full border-2 border-dashed border-blue-300 rounded-xl p-10 bg-white hover:border-blue-500 transition"
                    >
                        <p class="text-gray-600 text-sm">
                            {{ $t('drag_image_here') }}
                        </p>
                    </qrcode-drop-zone>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
    .slide-enter-active,
    .slide-leave-active {
        transition: all 0.3s ease;
    }
    .slide-enter-from,
    .slide-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>
