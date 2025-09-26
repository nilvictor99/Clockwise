<script setup>
    import { ref, watchEffect } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    const page = usePage();
    const show = ref(true);
    const style = ref('success');
    const message = ref('');
    const duration = 5000;

    watchEffect(() => {
        style.value = page.props.jetstream.flash?.bannerStyle || 'success';
        message.value = page.props.jetstream.flash?.banner || '';
        show.value = !!message.value;
        if (message.value) {
            setTimeout(() => {
                show.value = false;
            }, duration);
        }
    });
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show && message"
            class="fixed top-4 right-4 z-50 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto"
            :class="{
                'bg-gray-500': style == 'success',
                'bg-red-700': style == 'danger',
            }"
        >
            <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center min-w-0">
                        <span
                            class="flex p-2 rounded-lg"
                            :class="{
                                'bg-gray-600': style == 'success',
                                'bg-red-600': style == 'danger',
                            }"
                        >
                            <svg
                                v-if="style == 'success'"
                                class="size-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <svg
                                v-if="style == 'danger'"
                                class="size-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                                />
                            </svg>
                        </span>
                        <p class="ms-3 font-medium text-sm text-white truncate">
                            {{ message }}
                        </p>
                    </div>
                    <div class="shrink-0 sm:ms-3">
                        <button
                            type="button"
                            class="-me-1 flex p-2 rounded-md focus:outline-none sm:-me-2 transition"
                            :class="{
                                'hover:bg-gray-600 focus:bg-gray-600':
                                    style == 'success',
                                'hover:bg-red-600 focus:bg-red-600':
                                    style == 'danger',
                            }"
                            aria-label="Dismiss"
                            @click.prevent="show = false"
                        >
                            <svg
                                class="size-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
