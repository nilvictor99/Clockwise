<script setup>
    import { ref, watch } from 'vue';
    import Search from '@/Components/Icons/Search.vue';
    import ButtonClean from '@/Components/Buttons/ButtonClean.vue';

    const props = defineProps({
        placeholder: { type: String, default: 'Buscar...' },
        modelValue: { type: String, default: '' },
        cleanButton: { type: Boolean, default: false },
    });

    const emit = defineEmits(['update:modelValue', 'search']);

    const searchTerm = ref(props.modelValue ?? '');

    watch(searchTerm, val => {
        emit('update:modelValue', val);
        debounceSearch(val);
    });

    let timeout;
    function debounceSearch(val) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            emit('search', val);
        }, 400);
    }

    function clearSearch() {
        searchTerm.value = '';
        emit('update:modelValue', '');
        emit('cleared');
    }
</script>

<template>
    <div class="relative w-full">
        <div class="flex w-full relative">
            <span
                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
            >
                <Search class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 font-bold" />
            </span>
            <input
                class="block w-full pl-10 pr-10 py-2 text-sm sm:text-base text-gray-900 bg-transparent bg-white border border-white outline-none dark:border-white focus:ring-2 focus:ring-white focus:ring-opacity-5 focus:border-white font-bold dark:bg-gray-100 transition-all duration-200 rounded-md"
                :placeholder="placeholder"
                v-model="searchTerm"
                type="text"
                autocomplete="off"
            />
            <ButtonClean
                v-if="cleanButton && searchTerm"
                @click="clearSearch"
                :isDeleting="!!searchTerm"
                size="small"
                :disabled="false"
                class="absolute right-2 top-1/2 transform -translate-y-1/2"
                buttonClass="bg-[#3c1415] w-8 h-8 flex items-center justify-center p-2 text-white rounded-md hover:bg-[#5e0f0b] focus:outline-none"
            />
        </div>
    </div>
</template>
