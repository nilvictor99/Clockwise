<script setup>
    import { ref, computed } from 'vue';
    import Photo from '../Icons/Photo.vue';
    import { useForm } from '@inertiajs/vue3';
    import Loader from '../Icons/Loader.vue';

    const props = defineProps({
        user: {
            type: Object,
            required: true,
        },
        size: {
            type: String,
            default: 'md',
            validator: value => ['sm', 'md', 'lg', 'xl'].includes(value),
        },
        theme: {
            type: String,
            default: 'default',
            validator: value =>
                ['default', 'blue', 'dark', 'light'].includes(value),
        },
        showBackgroundChanger: {
            type: Boolean,
            default: true,
        },
        editable: {
            type: Boolean,
            default: false,
        },
    });

    const sizeClasses = computed(() => {
        const sizes = {
            sm: {
                container: 'h-24 sm:h-32',
                avatar: 'w-16 h-16 sm:w-20 sm:h-20',
                name: 'text-lg sm:text-xl',
                email: 'text-xs sm:text-sm',
            },
            md: {
                container: 'h-32 sm:h-40',
                avatar: 'w-24 h-24 sm:w-32 sm:h-32',
                name: 'text-xl sm:text-2xl',
                email: 'text-sm sm:text-base',
            },
            lg: {
                container: 'h-40 sm:h-48',
                avatar: 'w-32 h-32 sm:w-40 sm:h-40',
                name: 'text-2xl sm:text-3xl',
                email: 'text-base sm:text-lg',
            },
            xl: {
                container: 'h-48 sm:h-56',
                avatar: 'w-36 h-36 sm:w-48 sm:h-48',
                name: 'text-3xl sm:text-4xl',
                email: 'text-lg sm:text-xl',
            },
        };
        return sizes[props.size] || sizes.md;
    });

    const themeClasses = computed(() => {
        const themes = {
            default: {
                overlay: 'bg-black bg-opacity-20',
                card: 'bg-white',
                name: 'text-gray-900',
                email: 'text-gray-600',
            },
            blue: {
                overlay: 'bg-blue-900 bg-opacity-30',
                card: 'bg-blue-50',
                name: 'text-blue-900',
                email: 'text-blue-700',
            },
            dark: {
                overlay: 'bg-gray-900 bg-opacity-50',
                card: 'bg-gray-800',
                name: 'text-white',
                email: 'text-gray-300',
            },
            light: {
                overlay: 'bg-white bg-opacity-30',
                card: 'bg-gray-50',
                name: 'text-gray-900',
                email: 'text-gray-700',
            },
        };
        return themes[props.theme] || themes.default;
    });

    const backgroundInput = ref(null);
    const isChangingBackground = ref(false);

    const form = useForm({
        bg_image: null,
    });

    const backgroundImageUrl = computed(() => {
        if (props.user.bg_image) {
            return `/storage/${props.user.bg_image}`;
        }
        return '/System/Samples/fondo-sample.webp';
    });

    const updateBackgroundImage = event => {
        const file = event.target.files[0];
        if (file) {
            isChangingBackground.value = true;
            form.bg_image = file;

            form.post(route('dashboard.uploadbg'), {
                preserveScroll: true,
                onSuccess: () => {
                    isChangingBackground.value = false;
                    if (backgroundInput.value) {
                        backgroundInput.value.value = '';
                    }
                },
                onError: errors => {
                    console.error('Error al actualizar la imagen:', errors);
                    isChangingBackground.value = false;
                    if (backgroundInput.value) {
                        backgroundInput.value.value = '';
                    }
                },
            });
        }
    };

    const triggerFileInput = () => {
        if (props.editable && backgroundInput.value) {
            backgroundInput.value.click();
        }
    };
</script>

<template>
    <form @submit.prevent="submitForm">
        <div class="relative px-4 sm:px-6 lg:px-12">
            <div class="relative">
                <img
                    :src="backgroundImageUrl"
                    alt="Background"
                    :class="[
                        'object-cover w-full rounded-t-xl shadow-lg',
                        sizeClasses.container,
                    ]"
                    style="filter: brightness(0.95) saturate(1.1)"
                />
                <div
                    :class="[
                        'absolute inset-0 rounded-t-xl',
                        themeClasses.overlay,
                    ]"
                ></div>

                <!-- Background Changer Button -->
                <div
                    v-if="showBackgroundChanger && editable"
                    class="absolute top-4 right-4"
                >
                    <button
                        type="button"
                        @click="triggerFileInput"
                        :disabled="isChangingBackground || form.processing"
                        class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition-colors duration-200 disabled:opacity-50"
                        title="Cambiar fondo"
                    >
                        <Photo
                            v-if="!isChangingBackground && !form.processing"
                            class="w-5 h-5 text-white"
                        />
                        <Loader
                            v-else
                            class="w-5 h-5 text-white animate-spin"
                        />
                    </button>
                </div>
            </div>

            <input
                v-if="editable"
                ref="backgroundInput"
                type="file"
                accept="image/*"
                class="hidden"
                @change="updateBackgroundImage"
            />

            <!-- Profile Avatar -->
            <div
                class="absolute -translate-x-1/2 left-1/2 -bottom-8 cursor-pointer"
            >
                <img
                    :src="user.profile_photo_url"
                    :alt="user.name"
                    :class="[
                        'border-4 border-white rounded-full shadow-xl hover:shadow-2xl transition-shadow duration-300',
                        sizeClasses.avatar,
                    ]"
                />
            </div>
        </div>
    </form>

    <!-- Profile Info Card -->
    <div class="px-4 sm:px-6 lg:px-12 mt-12">
        <div
            :class="[
                'p-6 rounded-lg shadow-lg border hover:shadow-xl transition-shadow duration-300',
                themeClasses.card,
                'border-gray-200',
            ]"
        >
            <div class="text-center">
                <span
                    :class="['font-bold', sizeClasses.name, themeClasses.name]"
                >
                    {{ user.name }}
                </span>
            </div>
            <div class="mt-2 text-center">
                <span :class="[sizeClasses.email, themeClasses.email]">
                    {{ user.email }}
                </span>
            </div>
        </div>
    </div>
</template>
