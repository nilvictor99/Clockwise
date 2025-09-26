<script setup>
    import { Link, router } from '@inertiajs/vue3';
    import ApplicationMark from '@/Components/Utils/ApplicationMark.vue';
    import Dropdown from '@/Components/Drops/Dropdown.vue';
    import DropdownLink from '@/Components/Navlinks/DropdownLink.vue';
    import Menu from '@/Components/Icons/Menu.vue';
    import Arrow from '@/Components/Icons/Arrow.vue';

    defineProps({
        title: String,
        toggleSidebar: Function,
    });

    const logout = () => {
        router.post(route('logout'));
    };
</script>

<template>
    <header class="bg-white border-b border-gray-100">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center justify-between w-56 sm:w-56">
                    <Link :href="route('dashboard')">
                        <ApplicationMark class="block h-9 w-auto" />
                    </Link>
                    <button
                        @click="toggleSidebar"
                        class="mr-8 text-gray-600 hover:bg-gray-100 focus:outline-none sm:mr-0"
                    >
                        <Menu class="h-7 w-7" />
                    </button>
                </div>

                <!-- User Dropdown -->
                <div class="relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                v-if="
                                    $page.props.jetstream.managesProfilePhotos
                                "
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                            >
                                <img
                                    class="size-8 rounded-full object-cover"
                                    :src="
                                        $page.props.auth.user.profile_photo_url
                                    "
                                    :alt="$page.props.auth.user.name"
                                />
                            </button>

                            <span v-else class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-500 hover:bg-gray-600 focus:outline-none focus:bg-gray-400 active:bg-gray-400 transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.name }}

                                    <Arrow class="ml-2 -mr-0.5 h-4 w-4" />
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Account
                            </div>
                            <DropdownLink :href="route('profile.show')">
                                Profile
                            </DropdownLink>
                            <DropdownLink href="/admin" as="a">
                                Panel Administrativo
                            </DropdownLink>
                            <DropdownLink
                                v-if="$page.props.jetstream.hasApiFeatures"
                                :href="route('api-tokens.index')"
                            >
                                API Tokens
                            </DropdownLink>

                            <div class="border-t border-gray-200" />
                            <form @submit.prevent="logout">
                                <DropdownLink as="button">
                                    Log Out
                                </DropdownLink>
                            </form>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </div>
    </header>
</template>
