<script setup>
    import { computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import Loader from '../Icons/Loader.vue';

    const props = defineProps({
        loading: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: 'button',
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        size: {
            type: String,
            default: 'normal',
        },
        variant: {
            type: String,
            default: 'gray',
        },
        roles: {
            type: Array,
            default: () => [],
        },
        permissions: {
            type: Array,
            default: () => [],
        },
    });

    const textSize = computed(() => {
        return props.size === 'square' ? 'text-sm' : 'text-sm';
    });

    const buttonClasses = computed(() => {
        const baseClasses = [
            'flex items-center font-medium rounded-lg border border-none',
            'focus:outline-none',
            'disabled:opacity-50 disabled:pointer-events-none',
            props.size === 'square'
                ? 'justify-center size-[46px]'
                : 'py-2 px-4 gap-x-2',
            textSize.value,
        ];

        const variantClasses = {
            primary:
                'bg-[#d35400] text-white hover:bg-[#c95a00] focus:bg-[#c95a00] border-transparent',
            secondary:
                'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:bg-gray-400 border-transparent',
            outline:
                'bg-transparent text-indigo-500 hover:bg-indigo-50 border-indigo-500',
            danger: 'bg-red-500 text-white hover:bg-red-600 focus:bg-red-700 border-transparent',
            success:
                'bg-green-500 text-white hover:bg-green-600 focus:bg-green-700 border-transparent',
            warning:
                'bg-yellow-500 text-white hover:bg-yellow-600 focus:bg-yellow-700 border-transparent',
            info: 'bg-blue-500 text-white hover:bg-blue-600 focus:bg-blue-700 border-transparent',
            light: 'bg-white text-gray-800 hover:bg-gray-100 focus:bg-gray-200 border-gray-300',
            dark: 'bg-gray-800 text-white hover:bg-gray-700 focus:bg-gray-600 border-gray-600',
            gray: 'bg-gray-500 text-white hover:bg-gray-600 focus:bg-gray-700 border-gray-600',
            blue: 'bg-[#04335c] text-white hover:bg-[#032a4a] focus:bg-[#032a4a] border-transparent',
        };

        return [
            ...baseClasses,
            variantClasses[props.variant] || variantClasses.primary,
        ];
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
</script>

<template>
    <button
        v-if="hasAccess"
        :type="type"
        :class="buttonClasses"
        :disabled="loading || disabled"
    >
        <span
            v-if="loading"
            class="animate-spin"
            role="status"
            aria-label="loading"
        >
            <Loader class="size-5" />
        </span>
        <span v-if="!loading || size !== 'square'">
            <slot></slot>
        </span>
    </button>
</template>
