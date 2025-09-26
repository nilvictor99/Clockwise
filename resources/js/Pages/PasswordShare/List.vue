<script setup>
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { ref, computed } from 'vue';
    import { router } from '@inertiajs/vue3';
    import Edit from '@/Components/Buttons/Edit.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import SectionDateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import Delete from '@/Components/Buttons/Delete.vue';
    import ModalDelete from '@/Components/Modals/ModalDelete.vue';

    const props = defineProps({
        passwordShares: {
            type: Object,
            required: true,
        },
        search: {
            type: String,
            default: '',
        },
        dateRange: {
            type: Object,
            default: () => ({ start: '', end: '' }),
        },
        auth: {
            type: Object,
            required: true,
        },
    });

    const showDeleteModal = ref(false);
    const shareToDelete = ref(null);
    const isDeleting = ref(false);
    const search = ref(props.search);
    const dateRange = ref(props.dateRange);

    function handleSearch(val) {
        if (typeof val === 'string') {
            search.value = val;
        }

        router.get(
            route('password-share.list'),
            {
                search: search.value,
                start: dateRange.value.start,
                end: dateRange.value.end,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }

    const handleDelete = share => {
        shareToDelete.value = share;
        showDeleteModal.value = true;
    };

    const confirmDelete = () => {
        if (!shareToDelete.value) return;

        isDeleting.value = true;
        router.delete(route('password-share.destroy', shareToDelete.value.id), {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                isDeleting.value = false;
                showDeleteModal.value = false;
                shareToDelete.value = null;
            },
        });
    };

    const canManageShare = computed(() => share => {
        const currentUserId = props.auth.user.id;
        return share.shared_by.id === currentUserId;
    });
</script>

<template>
    <AppLayout title="Password Shares">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4 mt-1">
                <div class="bg-white shadow-lg rounded-lg p-4 md:p-6">
                    <div
                        class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-end"
                    >
                        <SectionDateRangeFilter
                            v-model="dateRange"
                            label="Filtrar por fechas"
                            borderColor="border-gray-300"
                            focusColor="focus:border-indigo-500 focus:ring-indigo-200"
                            :bold="true"
                            :disabled="false"
                            :cleanButton="true"
                            @filter="handleSearch"
                        />
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div
                        class="flex flex-col sm:flex-row gap-4 mb-6 items-center"
                    >
                        <InputListSearch
                            v-model="search"
                            :cleanButton="true"
                            placeholder="Buscar por contraseña o usuario"
                            @search="handleSearch"
                            class="flex-1"
                        />
                    </div>

                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-sm"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Nombre
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Compartido Por
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Compartido Con
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Permisos
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="share in passwordShares.data"
                                    :key="share.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-gray-900"
                                    >
                                        {{ share.password?.name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{ share.shared_by?.name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{ share.shared_with?.name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800"
                                        >
                                            {{ share.permissions }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center flex space-x-2 justify-center"
                                    >
                                        <Edit
                                            v-if="canManageShare(share)"
                                            :roles="[
                                                'super usuario',
                                                'super_admin',
                                                'Staff',
                                            ]"
                                            :permissions="[]"
                                            @click="
                                                $inertia.visit(
                                                    route(
                                                        'password-share.edit',
                                                        { id: share.id }
                                                    )
                                                )
                                            "
                                            class="text-indigo-600 hover:text-indigo-900 cursor-pointer"
                                        />
                                        <Delete
                                            v-if="canManageShare(share)"
                                            :roles="[
                                                'super usuario',
                                                'super_admin',
                                            ]"
                                            :permissions="[
                                                'delete_password::vault',
                                            ]"
                                            @click="handleDelete(share)"
                                            class="text-red-600 hover:text-red-900 cursor-pointer"
                                            :disabled="isDeleting"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <Pagination :pagination="passwordShares" />
                    </div>
                </div>
            </div>
        </section>

        <ModalDelete
            :show="showDeleteModal"
            title="Eliminar Contraseña Compartida"
            description="¿Está seguro que desea eliminar esta contraseña compartida? Esta acción no se puede deshacer."
            itemType="Contraseña Compartida"
            :itemId="shareToDelete?.id"
            :loading="isDeleting"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
        />
    </AppLayout>
</template>
