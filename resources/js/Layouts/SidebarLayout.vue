<script setup>
    import { ref, onMounted, onUnmounted } from 'vue';
    import { Head } from '@inertiajs/vue3';
    import Banner from '@/Components/Utils/Banner.vue';
    import Header from '@/Layouts/Components/Header.vue';
    import Sidebar from '@/Layouts/Components/Sidebar.vue';
    import Footer from '@/Layouts/Components/Footer.vue';

    defineProps({
        title: String,
    });

    const showingSidebar = ref(false);
    const windowWidth = ref(window.innerWidth);

    const toggleSidebar = () => {
        showingSidebar.value = !showingSidebar.value;
    };

    const updateWindowWidth = () => {
        windowWidth.value = window.innerWidth;
    };

    onMounted(() => {
        window.addEventListener('resize', updateWindowWidth);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', updateWindowWidth);
    });
</script>

<template>
    <div>
        <Head :title="title">
            <link
                rel="icon"
                type="image/x-icon"
                href="/System/favicons/favicon.ico"
            />
        </Head>
        <Banner />
        <div class="min-h-screen bg-white flex flex-col overflow-hidden">
            <Header :toggleSidebar="toggleSidebar" />
            <div class="flex flex-1 relative overflow-hidden">
                <transition name="slide">
                    <Sidebar
                        v-show="showingSidebar"
                        :isOpen="showingSidebar"
                        :toggleSidebar="toggleSidebar"
                        class="absolute z-10 md:relative md:z-auto"
                    />
                </transition>
                <div
                    v-if="showingSidebar"
                    class="fixed inset-0 bg-black bg-opacity-0 z-5 md:hidden"
                    @click="toggleSidebar"
                ></div>
                <main class="flex-1 p-4 md:p-6 overflow-auto">
                    <slot />
                </main>
            </div>
            <Footer />
        </div>
    </div>
</template>

<style>
    .slide-enter-active,
    .slide-leave-active {
        transition: transform 0.3s ease;
    }
    .slide-enter-from,
    .slide-leave-to {
        transform: translateX(-100%);
    }
</style>
