<script setup>
    import { ref, onMounted, onBeforeUnmount, computed } from 'vue';

    defineProps({
        staffs: {
            type: Array,
            default: () => [],
        },
        title: {
            type: String,
            default: 'Staff Presente Hoy',
        },
        emptyMessage: {
            type: String,
            default: 'No hay staff presente hoy',
        },
        theme: {
            type: String,
            default: 'blue',
            validator: value =>
                [
                    'dark',
                    'orange',
                    'blue',
                    'red',
                    'indigo',
                    'success',
                    'lightgreen',
                ].includes(value),
        },
        size: {
            type: String,
            default: 'md',
            validator: value => ['sm', 'md', 'lg', 'xl'].includes(value),
        },
        maxHeight: {
            type: String,
            default: 'max-h-48',
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

    const sizeClasses = computed(() => {
        const sizes = {
            sm: {
                tooltip: 'p-2',
                title: 'text-xs',
                avatar: 'w-6 h-6',
                text: 'text-xs',
                item: 'p-1.5 space-x-2',
                empty: 'text-xs',
            },
            md: {
                tooltip: 'p-4',
                title: 'text-sm',
                avatar: 'w-8 h-8',
                text: 'text-sm',
                item: 'p-2 space-x-3',
                empty: 'text-sm',
            },
            lg: {
                tooltip: 'p-5',
                title: 'text-base',
                avatar: 'w-10 h-10',
                text: 'text-base',
                item: 'p-3 space-x-4',
                empty: 'text-base',
            },
            xl: {
                tooltip: 'p-6',
                title: 'text-lg',
                avatar: 'w-12 h-12',
                text: 'text-lg',
                item: 'p-4 space-x-5',
                empty: 'text-lg',
            },
        };
        return sizes.md;
    });

    const themeClasses = {
        dark: {
            tooltip: 'bg-gray-800 text-white',
            arrow: 'bg-gray-800',
            text: 'text-white',
            title: 'text-white',
            empty: 'text-gray-300',
            itemBg: 'bg-white/10',
            itemHover: 'hover:bg-white/20',
        },
        gray: {
            tooltip: 'bg-gray-600 text-white',
            arrow: 'bg-gray-600',
            text: 'text-white',
            title: 'text-white',
            empty: 'text-gray-200',
            itemBg: 'bg-white/10',
            itemHover: 'hover:bg-white/20',
        },
        orange: {
            tooltip: 'bg-orange-600 text-white',
            arrow: 'bg-orange-600',
            text: 'text-white',
            title: 'text-white',
            empty: 'text-orange-200',
            itemBg: 'bg-white/10',
            itemHover: 'hover:bg-white/20',
        },
        blue: {
            tooltip: 'bg-blue-600 text-white',
            arrow: 'bg-blue-600',
            text: 'text-white',
            title: 'text-white',
            empty: 'text-blue-200',
            itemBg: 'bg-white/10',
            itemHover: 'hover:bg-white/20',
        },
        red: {
            tooltip: 'bg-red-600 text-white',
            arrow: 'bg-red-600',
            text: 'text-white',
            title: 'text-white',
            empty: 'text-red-200',
            itemBg: 'bg-white/10',
            itemHover: 'hover:bg-white/20',
        },
        indigo: {
            tooltip: 'bg-indigo-600 text-white',
            arrow: 'bg-indigo-600',
            text: 'text-white',
            title: 'text-white',
            empty: 'text-indigo-200',
            itemBg: 'bg-white/10',
            itemHover: 'hover:bg-white/20',
        },
        success: {
            tooltip: 'bg-green-600 text-white',
            arrow: 'bg-green-600',
            text: 'text-white',
            title: 'text-white',
            empty: 'text-green-200',
            itemBg: 'bg-white/10',
            itemHover: 'hover:bg-white/20',
        },
        lightgreen: {
            tooltip: 'bg-green-200 text-green-700',
            arrow: 'bg-green-200 text-green-700',
            text: 'text-green-700',
            title: 'text-green-800',
            empty: 'text-green-600',
            itemBg: 'bg-gray-100/50',
            itemHover: 'hover:bg-gray-100/10',
        },
    };
</script>

<template>
    <div class="relative group">
        <div class="truncate">
            <slot></slot>
        </div>
        <div
            v-if="staffs.length > 0 || $slots.content"
            :class="[
                'invisible group-hover:visible fixed z-[99999] rounded-lg shadow-lg text-sm break-words whitespace-normal',
                themeClasses[theme].tooltip,
                sizeClasses.tooltip,
            ]"
            :style="{
                top: tooltipPosition.y + 'px',
                left: tooltipPosition.x + 'px',
                transform: 'translate(-50%, -100%)',
                maxWidth: '320px',
            }"
        >
            <div class="text-left">
                <slot name="content">
                    <div class="max-w-xs">
                        <h4
                            :class="[
                                'font-semibold mb-3 text-center',
                                themeClasses[theme].title,
                                sizeClasses.title,
                            ]"
                        >
                            {{ title }}
                        </h4>
                        <div :class="['space-y-2 overflow-y-auto', maxHeight]">
                            <div
                                v-for="staff in staffs"
                                :key="staff.id"
                                :class="[
                                    'flex items-center rounded-lg transition-colors duration-200',
                                    themeClasses[theme].itemBg,
                                    themeClasses[theme].itemHover,
                                    sizeClasses.item,
                                ]"
                            >
                                <img
                                    :src="staff.profile_photo_url"
                                    :alt="staff.name"
                                    :class="[
                                        'rounded-full border-2 border-white flex-shrink-0',
                                        sizeClasses.avatar,
                                    ]"
                                />
                                <div class="flex-1 min-w-0">
                                    <span
                                        :class="[
                                            'font-medium block truncate',
                                            themeClasses[theme].text,
                                            sizeClasses.text,
                                        ]"
                                    >
                                        {{ staff.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div
                            v-if="staffs.length === 0"
                            :class="[
                                'text-center mt-3',
                                themeClasses[theme].empty,
                                sizeClasses.empty,
                            ]"
                        >
                            {{ emptyMessage }}
                        </div>
                    </div>
                </slot>
            </div>
            <div
                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2"
                :class="themeClasses[theme].arrow"
            ></div>
        </div>
    </div>
</template>
