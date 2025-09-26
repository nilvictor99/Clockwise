<script setup>
    import { Head, Link } from '@inertiajs/vue3';

    defineProps({
        canLogin: {
            type: Boolean,
        },
        laravelVersion: {
            type: String,
            required: true,
        },
        phpVersion: {
            type: String,
            required: true,
        },
    });
</script>

<template>
    <Head title="Bienvenido">
        <link
            rel="icon"
            type="image/x-icon"
            href="/System/favicons/favicon.ico"
        />
    </Head>
    <div
        class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center relative overflow-hidden"
        style="background-image: url('/System/Samples/background.webp')"
    >
        <div class="absolute inset-0 bg-black/30 backdrop-blur-[4px]"></div>

        <div
            class="relative z-10 text-center text-white p-6 sm:p-8 max-w-sm sm:max-w-md lg:max-w-lg w-full mx-4 animate-fade-in"
        >
            <div class="mb-6 sm:mb-8 animate-bounce-in">
                <img
                    src="/System/logos/logo.webp"
                    alt="logo"
                    class="w-24 h-24 sm:w-36 sm:h-36 mx-auto"
                />
            </div>
            <h1
                class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 drop-shadow-2xl animate-slide-up"
            >
                Bienvenido
            </h1>

            <p
                class="text-base sm:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90 animate-slide-up-delay"
            >
                Accede de manera segura y moderna.
            </p>

            <div
                class="flex justify-center space-x-4 sm:space-x-6 animate-slide-up-delay"
            >
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="w-32 h-14 text-center flex justify-center items-center inline-block bg-gradient-to-r from-zinc-300 via-zinc-500 to-zinc-700 text-white font-semibold py-3 px-6 sm:px-8 rounded-lg shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 animate-pulse-on-hover border-1 border-white/30 hover:border-white/50"
                >
                    Ingresar
                </Link>
            </div>

            <footer class="mt-8 sm:mt-12 text-xs sm:text-sm opacity-75">
                ¡Descubre tu potencial, un día a la vez!
            </footer>
        </div>
    </div>
</template>

<style scoped>
    /* Animaciones mantenidas en CSS */
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce-in {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slide-up-delay {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }
        50% {
            opacity: 0;
            transform: translateY(30px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }

    .animate-bounce-in {
        animation: bounce-in 1.2s ease-out;
    }

    .animate-slide-up {
        animation: slide-up 1s ease-out 0.5s both;
    }

    .animate-slide-up-delay {
        animation: slide-up-delay 1s ease-out 0.7s both;
    }

    .animate-pulse-on-hover:hover {
        animation: pulse 1s infinite;
    }
</style>
