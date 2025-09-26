<template>
    <button :type="type" :class="buttonClass" :disabled="loading || disabled">
        <span
            v-if="loading"
            class="loading loading-dots loading-lg ml-2 text-gray-700 dark:text-white"
        ></span>
        <span v-else>
            <slot></slot>
        </span>
    </button>
</template>

<script>
    export default {
        props: {
            loading: {
                type: Boolean,
                default: false,
            },
            type: {
                type: String,
                default: 'submit',
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        computed: {
            buttonClass() {
                return [
                    'inline-flex',
                    'items-center',
                    'justify-center',
                    'px-4',
                    'py-2',
                    'bg-blue-700',
                    'hover:bg-blue-800',
                    'border',
                    'border-transparent',
                    'rounded-md',
                    'font-semibold',
                    'text-xs',
                    'text-white',
                    'uppercase',
                    'tracking-widest',
                    'focus:outline-none',
                    'focus:ring-2',
                    'focus:ring-indigo-500',
                    'focus:ring-offset-2',
                    'transition',
                    'ease-in-out',
                    'duration-150',
                    {
                        'cursor-not-allowed': this.loading,
                        'bg-gray-500': this.disabled,
                        'hover:bg-blue-800': !this.disabled && !this.loading,
                        'opacity-50': this.disabled,
                    },
                ];
            },
        },
    };
</script>
