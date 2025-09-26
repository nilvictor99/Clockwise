<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { computed } from 'vue';
    import StatWidget from '@/Components/Items/StatWidget.vue';
    import StaffListTooltip from '@/Components/Utils/StaffListTooltip.vue';
    import ProfileCard from '@/Components/Cards/ProfileCard.vue';

    const props = defineProps({
        auth: Object,
        staffs: Number,
        staffsPresentToday: Array,
        staffsAbsentToday: Array,
    });

    const totalEmployees = computed(() => props.staffs);
    const presentToday = computed(() => props.staffsPresentToday.length);
    const absentToday = computed(() => props.staffsAbsentToday.length);

    const attendanceRate = computed(() => {
        if (totalEmployees.value > 0) {
            return Math.round(
                (presentToday.value / totalEmployees.value) * 100
            );
        }
        return 0;
    });

    const { roles } = props.auth.user;

    const optionsAccess = [
        {
            name: 'Asistencias',
            route: route('timesheet'),
            background:
                'https://i.pinimg.com/736x/ce/c4/b3/cec4b3cc815353d62435bb666868594a.jpg',
            roles: ['super usuario', 'super_admin', 'Staff'],
        },
        {
            name: 'Contraseñas',
            route: route('password-vault'),
            background:
                'https://i.pinimg.com/736x/ae/48/4a/ae484a15a84631934a735e96ad73147d.jpg',
            roles: ['super usuario', 'super_admin', 'Staff'],
        },
    ];

    const filteredOptions = computed(() => {
        return optionsAccess.filter(option => isAccess(option.roles));
    });

    const isAccess = rolesUnit => {
        return roles.some(role => rolesUnit.includes(role));
    };
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <ProfileCard
                    :user="auth.user"
                    size="md"
                    theme="default"
                    :editable="true"
                />

                <div
                    v-if="
                        roles.includes(
                            'super usuario' || roles.includes('super_admin')
                        )
                    "
                    class="px-4 sm:px-6 rounded-lg lg:px-12"
                >
                    <div
                        class="bg-white rounded-md overflow-hidden shadow-xl sm:rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300"
                    >
                        <h3
                            class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800"
                        >
                            {{ $t('Statistics') }}
                        </h3>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6"
                        >
                            <StatWidget
                                :title="$t('Total Employees')"
                                :value="totalEmployees"
                                theme="gray"
                                size="md"
                            />
                            <StaffListTooltip
                                :staffs="staffsPresentToday"
                                :title="$t('Present Today')"
                                :empty-message="$t('No staff present today')"
                                theme="success"
                            >
                                <StatWidget
                                    :title="$t('Present Today')"
                                    :value="presentToday"
                                    theme="green"
                                    size="md"
                                />
                            </StaffListTooltip>
                            <StaffListTooltip
                                :staffs="staffsAbsentToday"
                                :title="$t('Absent Today')"
                                :empty-message="$t('No staff absent today')"
                                theme="red"
                            >
                                <StatWidget
                                    :title="$t('Absent Today')"
                                    :value="absentToday"
                                    theme="red"
                                    size="md"
                                />
                            </StaffListTooltip>
                            <StatWidget
                                :title="$t('Attendance Rate')"
                                :value="attendanceRate"
                                unit="%"
                                theme="yellow"
                                size="md"
                            />
                        </div>
                    </div>
                </div>

                <!-- Options Access -->
                <div
                    class="grid grid-cols-1 gap-6 px-4 sm:px-6 lg:px-10 mt-5 sm:grid-cols-2 lg:grid-cols-2"
                >
                    <div
                        v-for="option in filteredOptions"
                        :key="option.roles"
                        class="overflow-hidden transition-transform duration-300 transform rounded-xl hover:scale-105 shadow-lg hover:shadow-2xl"
                    >
                        <a :href="option.route" class="block">
                            <div class="relative group">
                                <img
                                    :src="option.background"
                                    alt=""
                                    class="object-cover w-full h-40 sm:h-48 rounded-t-xl"
                                    style="
                                        filter: brightness(0.97) saturate(1.1);
                                    "
                                />
                                <div
                                    class="absolute inset-0 transition-opacity duration-300 opacity-75 bg-gradient-to-t from-gray-600 via-transparent to-transparent group-hover:opacity-50 rounded-t-xl"
                                ></div>
                                <div
                                    class="absolute bottom-0 w-full p-4 bg-gradient-to-t from-gray-400 via-transparent to-transparent rounded-b-xl"
                                >
                                    <span
                                        class="text-lg sm:text-xl font-semibold text-white"
                                    >
                                        {{ option.name }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
