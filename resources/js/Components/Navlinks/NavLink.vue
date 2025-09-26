<script setup>
    import { computed } from 'vue';
    import { Link, usePage } from '@inertiajs/vue3';

    const props = defineProps({
        href: String,
        active: Boolean,
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

    const classes = computed(() => {
        return props.active
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-gray-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-gray-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
    });
</script>

<template>
    <Link v-if="hasAccess" :href="href" :class="classes">
        <slot />
    </Link>
</template>
