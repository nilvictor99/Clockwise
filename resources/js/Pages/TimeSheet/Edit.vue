<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import FormEdit from '@/Components/TimeSheets/FormEdit.vue';

    defineProps({
        data: {
            type: Object,
            required: true,
        },
    });

    const handleCancelled = () => {
        window.history.back();
    };
</script>

<template>
    <AppLayout title="Editar Asistencia">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-4 w-full max-w-7xl">
            <div class="flex flex-col md:flex-row gap-4 mt-4">
                <FormEdit :data="data" @cancelled="handleCancelled" />
            </div>
        </section>
    </AppLayout>
</template>
