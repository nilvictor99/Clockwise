<script setup>
    import { defineEmits, ref, computed } from 'vue';
    import Backspace from '../Icons/Backspace.vue';

    const props = defineProps({
        buttonClass: {
            type: String,
            default:
                'bg-[#3c1415] w-16 h-[42px] absolute flex items-center justify-center top-0 right-0 p-2 text-white rounded-[0px_5px_5px_0px] hover:bg-[#5e0f0b] focus:outline-none focus:ring-none focus:ring-[#3c1415]-400',
        },
        isDeleting: {
            type: Boolean,
            default: false,
        },
        size: {
            type: String,
            default: 'medium',
            validator: value => ['small', 'medium', 'large'].includes(value),
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['click']);
    const isHovered = ref(false);

    function onClick() {
        if (!props.disabled) {
            emit('click');
        }
    }

    const computedClass = computed(() => {
        return [
            props.buttonClass,
            {
                'opacity-50 cursor-not-allowed': props.disabled,
                'w-12 h-[32px]': props.size === 'small',
                'w-20 h-[52px]': props.size === 'large',
                'scale-105': isHovered.value && !props.disabled,
            },
        ];
    });
</script>

<template>
    <button
        type="button"
        @click="onClick"
        :class="computedClass"
        :disabled="disabled"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <div class="relative">
            <Backspace :class="{ 'animate-pulse': isDeleting }" />
        </div>
    </button>
</template>
