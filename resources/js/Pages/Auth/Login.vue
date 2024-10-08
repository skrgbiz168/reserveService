<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    start_at: String,
    stay_time: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    start_at: props.start_at,
    stay_time: props.stay_time,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="メールアドレス" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="パスワード" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">ログイン情報を保持する</span>
                </label>
            </div>

            <div class="flex justify-center mt-4">
                <button class="mx-4 py-2 w-full bg-red-400 rounded-lg"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    ログイン
                </button>
            </div>

            <div class="flex items-center justify-center mt-8">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    パスワードを忘れた場合
                </Link>
            </div>

            <div class="flex items-center justify-center mt-4">
                <Link
                    :href="route('register', {'start_at': start_at,'stay_time': stay_time})"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    未登録の場合はこちら
                </Link>
            </div>
        </form>

        <div class="flex items-center justify-center mt-4 p-4">
            <a :href="route('loginProvider', {'provider': 'line'})" class="flex justify-start border-2 border-gray-400 rounded-lg">
                <img src="/LineLogin.png" class="w-1/4 p-2">
                <p class="w-3/4 ml-1 flex items-center">Lineでログインする</p>
            </a>
        </div>

    </GuestLayout>
</template>
