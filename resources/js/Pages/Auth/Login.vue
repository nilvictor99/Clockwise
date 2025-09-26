<script setup>
    import { Head, useForm } from '@inertiajs/vue3';
    import CardCustomLogin from '@/Components/Cards/CardCustomLogin.vue';
    import AuthenticationCardLogo from '@/Components/Cards/AuthenticationCardLogo.vue';
    import InputError from '@/Components/Inputs/InputError.vue';
    import Checkbox from '@/Components/Inputs/Checkbox.vue';
    import TextInput from '@/Components/Inputs/TextInput.vue';
    import InputRevealablePassword from '@/Components/Inputs/InputRevealablePassword.vue';
    import NativeButton from '@/Components/Buttons/NativeButton.vue';
    import ClassicLabel from '@/Components/Labels/ClassicLabel.vue';
    import LoadPoints from '@/Components/Icons/LoadPoints.vue';

    defineProps({
        canResetPassword: Boolean,
        status: String,
    });

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        })).post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <Head title="Iniciar Sesión">
        <link
            rel="icon"
            type="image/x-icon"
            href="/System/favicons/favicon.ico"
        />
    </Head>

    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap"
        rel="stylesheet"
    />

    <div
        class="h-screen bg-cover bg-center bg-no-repeat overflow-hidden flex items-center justify-center relative"
        style="background-image: url('/System/Samples/background.webp')"
    >
        <CardCustomLogin>
            <template #logo>
                <AuthenticationCardLogo />
            </template>
            <div
                class="mt-2 mb-2 text-white text-center p-2 border-t-[5px] border-b-[5px] border-white/20 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-pulse font-bold text-lg md:text-xl tracking-wider"
            >
                <span class="animate-fade-in">Iniciar Sesión</span>
            </div>
            <form @submit.prevent="submit">
                <div>
                    <ClassicLabel
                        :weight="'bold'"
                        :theme="'white'"
                        :size="'lg'"
                        value="Email"
                    />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="correo electrónico"
                        class="mt-1 block w-full"
                        autofocus
                        required
                        autocomplete="username"
                    />
                    <InputError
                        theme="dark"
                        color="white"
                        :message="form.errors.email"
                    />
                </div>

                <div class="mt-4">
                    <ClassicLabel
                        :weight="'bold'"
                        :size="'lg'"
                        :theme="'white'"
                        value="Password"
                    />
                    <InputRevealablePassword
                        id="password"
                        required
                        v-model="form.password"
                        class="mt-1 block w-full"
                        placeholder="contraseña"
                        autocomplete="current-password"
                    />
                    <InputError
                        class="mt-1"
                        :message="form.errors.password"
                        theme="dark"
                        color="white"
                    />
                </div>

                <div class="block mb-5 mt-4">
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="form.remember"
                            name="remember"
                        />
                        <span class="ms-2 text-sm text-gray-100"
                            >Mantener la Sesion</span
                        >
                    </label>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <NativeButton
                        :theme="'gray'"
                        :disabled="form.processing"
                        class="h-11 w-full md:w-full"
                    >
                        <LoadPoints v-if="form.processing" :variant="'white'" />
                        <span v-else>Iniciar Sesion</span>
                    </NativeButton>
                </div>
            </form>
        </CardCustomLogin>
        <span
            class="square square-tl absolute h-screen w-1/2 bg-[rgba(34,34,34,0.1)] hidden md:block"
            style="top: -80%; left: -10%; z-index: 50; transform: rotate(45deg)"
        ></span>
        <span
            class="square square-tr absolute h-screen w-1/2 bg-[rgba(255,255,255,0.1)] hidden md:block"
            style="top: 0%; right: -30%; transform: rotate(45deg)"
        ></span>
        <span
            class="square square-bl absolute h-screen w-1/2 bg-[rgba(255,255,255,0.1)] hidden md:block"
            style="bottom: -70%; left: -15%; transform: rotate(45deg)"
        ></span>
        <span
            class="square square-br absolute h-screen w-1/2 bg-[rgba(34,34,34,0.1)] hidden md:block"
            style="bottom: 0%; right: -40%; transform: rotate(45deg)"
        ></span>
        <span
            class="star star1 absolute w-[50px] h-[50px] box-shadow transition duration-500 hidden md:block"
            style="
                bottom: -10%;
                left: -30%;
                transform: rotate(-30deg);
                background: rgba(34, 34, 34, 0.5);
            "
        ></span>
        <span
            class="star star2 absolute w-[50px] h-[50px] box-shadow transition duration-500 hidden md:block"
            style="
                bottom: -30%;
                left: -10%;
                transform: rotate(-30deg);
                background: rgb(255, 255, 255, 0.5);
            "
        ></span>
    </div>
</template>

<style scoped>
    .square.square-tl {
        animation: bounce 50s infinite ease-in-out;
    }
    .square.square-tr {
        animation: bounce 50s infinite ease-in-out;
    }
    .square.square-bl {
        animation: bounce 50s infinite ease-in-out;
    }
    .square.square-br {
        animation: bounce 50s infinite ease-in-out;
    }
    .star1 {
        animation: sweep 4s infinite;
    }
    .star2 {
        animation: sweep 3s infinite;
    }

    @keyframes bounce {
        0% {
            transform: translateY(0px) rotate(45deg);
        }
        50% {
            transform: translateY(20px) rotate(45deg);
            border-radius: 50px;
        }
        100% {
            transform: translateY(0px) rotate(45deg);
        }
    }

    @keyframes sweep {
        100% {
            bottom: 120%;
            left: 120%;
            transform: rotate(360deg);
        }
    }
</style>
