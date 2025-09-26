<script setup>
    import Edit from '@/Components/Icons/Edit.vue';
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    const props = defineProps({
        roles: {
            type: Array,
            default: () => [],
        },
        permissions: {
            type: Array,
            default: () => [],
        },
    });

    const hasAccess = computed(() => {
        const user = usePage().props.auth?.user;
        if (!user) return false;
        if (!props.roles.length && !props.permissions.length) return true;

        return (
            props.roles.some(role => user.roles.includes(role)) ||
            props.permissions.some(permission =>
                user.permissions.includes(permission)
            )
        );
    });

    defineEmits(['click']);
</script>

<template>
    <section v-if="hasAccess" class="tooltip tooltip-warning" data-tip="Editar">
        <button
            @click="$emit('click')"
            class="btn btn-link btn-lg p-1 hover:bg-gray-100 rounded-md inline-flex items-center gap-x-4 font-bold rounded-lg border border-transparent text-gray-500 hover:text-gray-600 focus:outline-none text-center"
            type="button"
            aria-label="Editar"
            title="Editar"
        >
            <Edit />
        </button>
    </section>
</template>
