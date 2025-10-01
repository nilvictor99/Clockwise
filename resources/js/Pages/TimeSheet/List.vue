<script setup>
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import { ref } from 'vue';
    import { router, usePoll } from '@inertiajs/vue3';
    import Edit from '@/Components/Buttons/Edit.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import DateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import InputSelectClasic from '@/Components/Inputs/InputSelectClasic.vue';
    import ModalQrGenerate from '@/Components/TimeSheets/ModalQrGenerate.vue';
    import ModalQrScanner from '@/Components/TimeSheets/ModalQrScanner.vue';
    import PerPageSelector from '@/Components/Utils/PerPageSelector.vue';
    import Qrcode from '@/Components/Icons/Qrcode.vue';
    import Scan from '@/Components/Icons/Scan.vue';

    const props = defineProps({
        timesheets: {
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
        staff: {
            type: Array,
            default: () => [],
        },
        staffId: { type: [String, Number], default: '' },
        perPage: {
            type: Number,
            default: 5,
        },
    });

    const showQrModal = ref(false);
    const qrCode = ref('');
    const userName = ref('');
    const showScanner = ref(false);
    const perPage = ref(props.perPage ?? 5);

    const generateQr = async () => {
        const response = await fetch(route('generate'), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const { name, qrCode: qrCodeData } = await response.json();
        qrCode.value = qrCodeData;
        userName.value = name;
        showQrModal.value = true;
    };

    const search = ref(props.search);
    const dateRange = ref(props.dateRange || { start: '', end: '' });
    const staffId = ref(props.staffId ?? '');
    const selectedStaffText =
        props.staff.find(
            staff => staff.id.toString() === props.staffId?.toString()
        )?.name || '';
    const staffOptions = props.staff.map(staff => ({
        id: staff.id,
        text: staff.name || 'Sin nombre',
    }));

    usePoll(5000, { only: ['timesheets'] });
    function handleSearch(val) {
        if (typeof val === 'object' && val !== null) {
            dateRange.value = val;
        }
        if (typeof val === 'string') {
            search.value = val;
        }

        router.get(
            route('timesheets.list'),
            {
                search: search.value,
                start: dateRange.value.start,
                end: dateRange.value.end,
                staff_id: staffId.value,
                page: 1,
                per_page: perPage.value,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }
    const formatWorkDate = (dayIn, dayOut) => {
        if (!dayIn) return '';
        const dateIn = new Date(dayIn).toISOString().split('T')[0];
        if (!dayOut) {
            return dateIn;
        }
        const dateOut = new Date(dayOut).toISOString().split('T')[0];
        return dateIn === dateOut ? dateIn : `${dateIn} - ${dateOut}`;
    };
    const formatTime = datetime => {
        if (!datetime) return '';
        return new Date(datetime).toLocaleTimeString('es-PE', {
            hour: '2-digit',
            minute: '2-digit',
        });
    };
    const formatHours = hours => {
        if (!hours) return '0';
        return hours.substring(0, 5);
    };
</script>

<template>
    <AppLayout title="Timesheets">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4 mt-1">
                <!-- Filters Section -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end"
                    >
                        <DateRangeFilter
                            v-model="dateRange"
                            label="Filtrar por fechas"
                            borderColor="border-orange-400"
                            focusColor="focus:border-orange-500 focus:ring-orange-200"
                            :bold="true"
                            :disabled="false"
                            :cleanButton="true"
                            @filter="handleSearch"
                        />
                        <InputSelectClasic
                            v-model="staffId"
                            :options="staffOptions"
                            :initialValue="{
                                id: props.staffId,
                                text: selectedStaffText,
                            }"
                            :roles="['super usuario', 'super_admin']"
                            :permissions="[]"
                            label="Colaboradores"
                            placeholder="Colaborador"
                            :disabled="false"
                            theme="gray"
                            :bold="true"
                            :CleanButton="true"
                            @change="handleSearch"
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
                            placeholder="Buscar Nombre, Usuario o Url"
                            @search="handleSearch"
                            class="flex-1"
                        />
                        <ClasicButton
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="[]"
                            @click="generateQr"
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            <div class="flex flex-row items-center">
                                <Qrcode class="h-5 w-5 mr-2" />
                                {{ $t('Register Timesheet') }}
                            </div>
                        </ClasicButton>
                        <ClasicButton
                            :roles="['super usuario', 'super_admin']"
                            :permissions="['create_timesheet']"
                            @click="showScanner = true"
                            variant="success"
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            <div class="flex flex-row items-center">
                                <Scan class="h-5 w-5 mr-2" />
                                {{ $t('Scan QR') }}
                            </div>
                        </ClasicButton>
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
                                        Calendario
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Fecha
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Colaborador
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Tipo
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Ingreso
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Salida
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Horas
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
                                    v-for="timesheet in timesheets.data"
                                    :key="timesheet.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-gray-900 truncate max-w-xs"
                                    >
                                        {{
                                            timesheet.calendar ||
                                            'Sin calendario'
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{
                                            formatWorkDate(
                                                timesheet.day_in,
                                                timesheet.day_out
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{
                                            timesheet.staff?.name || 'Sin staff'
                                        }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            :class="
                                                timesheet.type === 'work'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-blue-100 text-blue-800'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                        >
                                            {{ $t(timesheet.type) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center whitespace-nowrap"
                                    >
                                        <span
                                            :class="
                                                timesheet.type === 'work'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-blue-100 text-blue-800'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold"
                                        >
                                            {{ formatTime(timesheet.day_in) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center whitespace-nowrap"
                                    >
                                        <span
                                            :class="
                                                timesheet.type === 'work'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-blue-100 text-blue-800'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold"
                                        >
                                            {{
                                                formatTime(timesheet.day_out) ||
                                                '-'
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{
                                            formatHours(timesheet.hours) || '0'
                                        }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <Edit
                                            :roles="[
                                                'super usuario',
                                                'super_admin',
                                            ]"
                                            :permissions="['update_timesheet']"
                                            @click="
                                                $inertia.visit(
                                                    route('timesheets.edit', {
                                                        id: timesheet.id,
                                                    })
                                                )
                                            "
                                            class="text-indigo-600 hover:text-indigo-900 cursor-pointer"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        v-if="timesheets.data?.length > 4"
                        class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4"
                    >
                        <PerPageSelector
                            v-model="perPage"
                            :options="[5, 10, 25, 50, 100]"
                            label=""
                            labelPosition="left"
                            theme="indigo"
                            :bold="true"
                            :clearButton="true"
                            :disabled="isLoading"
                            @change="val => handleSearch(val, 'per-page')"
                        />
                        <Pagination :pagination="timesheets" />
                    </div>
                </div>
            </div>
        </section>

        <ModalQrGenerate
            :show="showQrModal"
            :qr-code="qrCode"
            :userName="userName"
            @close="showQrModal = false"
        />
        <ModalQrScanner :show="showScanner" @close="showScanner = false" />
    </AppLayout>
</template>
