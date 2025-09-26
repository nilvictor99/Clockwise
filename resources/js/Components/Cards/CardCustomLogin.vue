<template>
    <div
        class="bg-white/20 backdrop-blur-[20px] rounded-[12px] z-5 flex flex-col shadow-[0_0_5px_0_rgba(22,22,22,0.1)] w-full max-w-[350px] sm:max-w-[400px] md:max-w-[450px] p-3 sm:p-5 md:p-6 animate-fade-in-up"
    >
        <div class="flex justify-center animate-bounce-in mb-4 sm:mb-6">
            <slot name="logo" />
        </div>
        <div class="animate-slide-up px-2 sm:px-0">
            <slot />
        </div>
    </div>
</template>

<style scoped>
    @keyframes fade-in-up {
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

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out;
    }

    .animate-bounce-in {
        animation: bounce-in 0.8s ease-out;
    }

    .animate-slide-up {
        animation: slide-up 0.7s ease-out 0.3s both;
    }
</style>
