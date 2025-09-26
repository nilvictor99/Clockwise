<template>
    <div class="relative group">
        <div class="truncate">
            <slot></slot>
        </div>
        <div
            v-if="content"
            :class="[
                'invisible group-hover:visible fixed z-[99999] p-4 rounded-lg shadow-lg text-sm break-words whitespace-normal',
                themeClasses[theme].tooltip,
            ]"
            :style="{
                top: tooltipPosition.y + 'px',
                left: tooltipPosition.x + 'px',
                transform: 'translate(-50%, -100%)',
                maxWidth: '320px',
            }"
        >
            <div class="text-left">
                {{ content }}
            </div>
            <div
                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2"
                :class="themeClasses[theme].arrow"
            ></div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onBeforeUnmount } from 'vue';

    defineProps({
        content: {
            type: String,
            default: '',
        },
        theme: {
            type: String,
            default: 'dark',
            validator: value =>
                ['dark', 'orange', 'blue', 'red', 'indigo'].includes(value),
        },
    });

    const tooltipPosition = ref({ x: 0, y: 0 });

    const updateTooltipPosition = event => {
        if (event.target.closest('.group')) {
            const rect = event.target.closest('.group').getBoundingClientRect();
            tooltipPosition.value = {
                x: rect.left + rect.width / 2,
                y: rect.top - 10,
            };
        }
    };

    onMounted(() => {
        document.addEventListener('mousemove', updateTooltipPosition);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('mousemove', updateTooltipPosition);
    });

    const themeClasses = {
        dark: {
            tooltip: 'bg-gray-800 text-white',
            arrow: 'bg-gray-800',
        },
        gray: {
            tooltip: 'bg-gray-600 text-white',
            arrow: 'bg-gray-600',
        },
        orange: {
            tooltip: 'bg-orange-600 text-white',
            arrow: 'bg-orange-600',
        },
        blue: {
            tooltip: 'bg-blue-600 text-white',
            arrow: 'bg-blue-600',
        },
        red: {
            tooltip: 'bg-red-600 text-white',
            arrow: 'bg-red-600',
        },
        indigo: {
            tooltip: 'bg-indigo-600 text-white',
            arrow: 'bg-indigo-600',
        },
    };
</script>
