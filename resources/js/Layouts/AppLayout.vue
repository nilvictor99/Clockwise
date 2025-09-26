<script setup>
    import { ref } from 'vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import ApplicationMark from '@/Components/Utils/ApplicationMark.vue';
    import Banner from '@/Components/Utils/Banner.vue';
    import Dropdown from '@/Components/Drops/Dropdown.vue';
    import DropdownLink from '@/Components/Navlinks/DropdownLink.vue';
    import NavLink from '@/Components/Navlinks/NavLink.vue';
    import ResponsiveNavLink from '@/Components/Navlinks/ResponsiveNavLink.vue';

    defineProps({
        title: String,
    });

    const showingNavigationDropdown = ref(false);

    const switchToTeam = team => {
        router.put(
            route('current-team.update'),
            {
                team_id: team.id,
            },
            {
                preserveState: false,
            }
        );
    };

    const logout = () => {
        router.post(route('logout'));
    };
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

        <div class="min-h-screen bg-gray-100 flex flex-col">
            <nav class="bg-white border-b border-gray-100 flex-none">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left side: Logo and Navigation -->
                        <div class="flex items-center flex-1">
                            <!-- Logo -->
                            <div class="flex-shrink-0">
                                <Link
                                    :href="route('dashboard')"
                                    class="flex items-center"
                                >
                                    <ApplicationMark class="h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Main Navigation Links -->
                            <div
                                class="hidden sm:flex sm:items-center sm:ml-10 space-x-8"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    {{ $t('Dashboard') }}
                                </NavLink>
                                <NavLink
                                    :href="route('timesheet')"
                                    :active="route().current('timesheet')"
                                    :roles="[
                                        'super usuario',
                                        'super_admin',
                                        'Staff',
                                    ]"
                                    :permissions="['view_timesheet']"
                                >
                                    {{ $t('Timesheet') }}
                                </NavLink>
                                <NavLink
                                    :href="route('password-vault')"
                                    :active="route().current('password-vault')"
                                    :roles="[
                                        'super usuario',
                                        'super_admin',
                                        'Staff',
                                    ]"
                                    :permissions="['view_password::vault']"
                                >
                                    {{ $t('Password Vault') }}
                                </NavLink>
                            </div>
                        </div>

                        <!-- Right side: User and Team Controls -->
                        <div class="flex items-center">
                            <!-- Teams Dropdown -->
                            <Dropdown
                                v-if="$page.props.jetstream.hasTeamFeatures"
                                align="right"
                                width="60"
                            >
                                <template #trigger>
                                    <button
                                        class="flex items-center text-sm px-4 py-2 text-gray-700 hover:text-gray-900 focus:outline-none"
                                    >
                                        {{
                                            $page.props.auth.user.current_team
                                                .name
                                        }}
                                        <svg
                                            class="ml-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <!-- Team Management Options -->
                                    <div class="py-1">
                                        <DropdownLink
                                            :href="
                                                route(
                                                    'teams.show',
                                                    $page.props.auth.user
                                                        .current_team
                                                )
                                            "
                                        >
                                            Team Settings
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.jetstream
                                                    .canCreateTeams
                                            "
                                            :href="route('teams.create')"
                                        >
                                            Create New Team
                                        </DropdownLink>
                                    </div>

                                    <!-- Team Switcher -->
                                    <template
                                        v-if="
                                            $page.props.auth.user.all_teams
                                                .length > 1
                                        "
                                    >
                                        <div
                                            class="border-t border-gray-100"
                                        ></div>
                                        <div class="py-1">
                                            <template
                                                v-for="team in $page.props.auth
                                                    .user.all_teams"
                                                :key="team.id"
                                            >
                                                <button
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    @click="switchToTeam(team)"
                                                >
                                                    {{ team.name }}
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                            </Dropdown>

                            <!-- User Dropdown -->
                            <Dropdown align="right" width="48" class="ml-3">
                                <template #trigger>
                                    <button
                                        class="flex items-center text-sm focus:outline-none"
                                    >
                                        <img
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            :src="
                                                $page.props.auth.user
                                                    .profile_photo_url
                                            "
                                            :alt="$page.props.auth.user.name"
                                            class="h-8 w-8 rounded-full object-cover"
                                        />
                                        <span v-else class="text-gray-700">{{
                                            $page.props.auth.user.name
                                        }}</span>
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink
                                        :href="route('profile.show')"
                                        >{{ $t('Profile') }}</DropdownLink
                                    >
                                    <DropdownLink
                                        :roles="[
                                            'super usuario',
                                            'super_admin',
                                        ]"
                                        href="/admin"
                                        as="a"
                                        >{{
                                            $t('Panel Administration')
                                        }}</DropdownLink
                                    >
                                    <div class="border-t border-gray-100"></div>
                                    <form @submit.prevent="logout">
                                        <button
                                            type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            {{ $t('Log Out') }}
                                        </button>
                                    </form>
                                </template>
                            </Dropdown>

                            <!-- Mobile Menu Button -->
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="ml-2 sm:hidden p-2 text-gray-400 hover:text-gray-500"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div
                    v-show="showingNavigationDropdown"
                    class="sm:hidden bg-white border-t border-gray-200"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            {{ $t('Dashboard') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="['view_timesheet']"
                            :href="route('timesheet')"
                            :active="route().current('timesheet')"
                        >
                            {{ $t('Timesheet') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="['view_password::vault']"
                            :href="route('password-vault')"
                            :active="route().current('password-vault')"
                        >
                            {{ $t('Password Vault') }}
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
