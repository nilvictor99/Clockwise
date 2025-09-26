<template>
    <div
        class="min-h-screen flex flex-col py-10 sm:justify-center items-center sm:py-2 bg-indigo-100"
    >
        <div
            class="w-80 sm:w-96 bg-indigo-500 sm:max-w-md px-6 py-6 shadow-md overflow-hidden sm:rounded-lg"
        >
            <div class="flex justify-center my-auto">
                <slot name="logo" />
            </div>
            <slot />
        </div>
    </div>
</template>
