<script setup>
    import TrashIcon from '@/Components/Icons/Trash.vue';

    defineProps({
        disabled: {
            type: Boolean,
            default: false,
        },
    });

    defineEmits(['click']);
</script>

<template>
    <section class="tooltip tooltip-error" data-tip="Eliminar">
        <button
            :disabled="disabled"
            @click="$emit('click')"
            class="btn btn-link btn-sm text-error p-1 hover:bg-gray-100 rounded-md inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-500 hover:text-red-600 focus:outline-none"
        >
            <TrashIcon />
        </button>
    </section>
</template>
