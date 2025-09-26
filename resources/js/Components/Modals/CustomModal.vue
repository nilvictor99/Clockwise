<script setup>
    import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        maxWidth: {
            type: String,
            default: '2xl',
        },
        closeable: {
            type: Boolean,
            default: true,
        },
    });

    const emit = defineEmits(['close']);
    const dialog = ref();
    const modalContent = ref(null);
    const showSlot = ref(props.show);

    watch(
        () => props.show,
        () => {
            if (props.show) {
                document.body.style.overflow = 'hidden';
                showSlot.value = true;
                dialog.value?.showModal();
            } else {
                document.body.style.overflow = null;
                dialog.value?.close();
                showSlot.value = false;
            }
        }
    );

    const close = () => {
        if (props.closeable) {
            emit('close');
        }
    };

    const handleClickOutside = event => {
        if (
            props.closeable &&
            modalContent.value &&
            !modalContent.value.contains(event.target)
        ) {
            close();
        }
    };

    const closeOnEscape = e => {
        if (e.key === 'Escape') {
            e.preventDefault();
            if (props.show) {
                close();
            }
        }
    };

    onMounted(() => {
        document.addEventListener('keydown', closeOnEscape);
    });

    onUnmounted(() => {
        document.removeEventListener('keydown', closeOnEscape);
        document.body.style.overflow = null;
    });

    const maxWidthClass = computed(() => {
        return {
            sm: 'sm:max-w-sm',
            md: 'sm:max-w-md',
            lg: 'sm:max-w-lg',
            xl: 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl',
        }[props.maxWidth];
    });
</script>

<template>
    <dialog
        ref="dialog"
        class="z-[9998] m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
        @click="handleClickOutside"
    >
        <div v-if="show" class="fixed inset-0 bg-gray-500/75 z-[9997]" />

        <div
            v-if="show"
            class="fixed inset-0 flex items-start justify-center overflow-visible px-4 z-[9998] pt-[12vh]"
        >
            <div
                ref="modalContent"
                :class="[
                    'relative w-full overflow-visible rounded-lg bg-white shadow-xl z-[9999]',
                    maxWidthClass,
                ]"
            >
                <slot v-if="showSlot" />
            </div>
        </div>
    </dialog>
</template>
