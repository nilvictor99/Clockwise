<script setup>
    import { computed } from 'vue';
    import { Link, usePage } from '@inertiajs/vue3';

    const props = defineProps({
        active: Boolean,
        href: String,
        as: String,
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
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-gray-400 text-start text-base font-medium text-gray-700 bg-gray-50 focus:outline-none focus:text-gray-800 focus:bg-gray-100 focus:border-gray-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
    });
</script>

<template>
    <div v-if="hasAccess">
        <button
            v-if="as == 'button'"
            :class="classes"
            class="w-full text-start"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :class="classes"
            class="w-full text-start"
            :href="href"
        >
            <slot />
        </a>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
