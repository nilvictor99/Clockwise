<template>
    <svg
        :class="[
            'transition-transform duration-200',
            sizeClass,
            { 'rotate-180': rotated },
        ]"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 9l-7 7-7-7"
        ></path>
    </svg>
</template>

<script>
    export default {
        name: 'ArrowIcon',
        props: {
            size: {
                type: String,
                default: 'w-4 h-4',
            },
            rotated: {
                type: Boolean,
                default: false,
            },
        },
        computed: {
            sizeClass() {
                return this.size;
            },
        },
    };
</script>
