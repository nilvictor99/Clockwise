<script setup>
    import { onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import LoadingSection from '@/Components/Sections/LoadingSection.vue';

    onMounted(() => {
        router.visit(route('password-vault.list'));
    });
</script>

<template>
    <AppLayout>
        <LoadingSection />
    </AppLayout>
</template>
