<script setup>
    import { Link } from '@inertiajs/vue3';
    import { computed } from 'vue';
    import chevronLeft from '@/Components/Icons/chevron-left.vue';
    import chevronRight from '@/Components/Icons/chevron-right.vue';

    const props = defineProps({
        pagination: {
            type: Object,
            required: true,
        },
    });

    const meta = computed(() => props.pagination.meta ?? props.pagination);
    const links = computed(() => meta.value.links ?? props.pagination.links);
    const prevUrl = computed(
        () => props.pagination.prev_page_url ?? props.pagination.links?.prev
    );
    const nextUrl = computed(
        () => props.pagination.next_page_url ?? props.pagination.links?.next
    );

    const visibleLinks = computed(() => {
        if (!Array.isArray(links.value)) return [];

        const numericLinks = links.value.filter(link => {
            const label = String(link.label)
                .replace(/&laquo;|&raquo;|Anterior|Siguiente/g, '')
                .trim();
            return !isNaN(parseInt(label));
        });

        if (numericLinks.length <= 5) {
            return numericLinks;
        } else {
            const firstLinks = numericLinks.slice(0, 5);
            const lastLink = numericLinks[numericLinks.length - 1];
            return [...firstLinks, { label: '...', url: null }, lastLink];
        }
    });
</script>

<template>
    <div class="flex justify-end">
        <nav class="flex mr-0" aria-label="Pagination">
            <div class="flex items-center gap-x-1">
                <Link
                    v-if="prevUrl"
                    :href="prevUrl"
                    class="min-h-9.5 min-w-9.5 p-2 inline-flex justify-center items-center text-gray-500 hover:text-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-100"
                >
                    <chevronLeft class="size-5" />
                </Link>
                <template v-for="link in visibleLinks" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="min-h-9.5 min-w-9.5 flex justify-center items-center border py-2 px-3 text-sm rounded-lg"
                        :class="[
                            link.active
                                ? 'bg-gray-400 text-white border-gray-100'
                                : 'text-gray-500 border-transparent hover:bg-gray-100',
                        ]"
                    >
                        {{ link.label }}
                    </Link>

                    <div v-else class="flex items-center px-2">
                        <span class="text-gray-400">•••</span>
                    </div>
                </template>
                <Link
                    v-if="nextUrl"
                    :href="nextUrl"
                    class="min-h-9.5 min-w-9.5 p-2 inline-flex justify-center items-center text-gray-500 hover:text-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-100"
                >
                    <chevronRight class="size-5" />
                </Link>
            </div>
        </nav>
    </div>
</template>
