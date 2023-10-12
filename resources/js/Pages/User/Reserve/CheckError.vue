<script setup>
import UserAuthenticatedLayout from '@/Layouts/UserAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    message: String,
});
</script>

<template>
    <Head title="予約確認" />

    <UserAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">予約確認</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="mt-4 flex justify-center">
                    <p class="w-full text-center"> {{ message }}</p>
                  </div>
                  <div class="my-4 flex justify-center">
                    <Link as="button" :href="route('reserve.index')"
                      class="w-2/3 text-center text-grey rounded-lg border border-gray-900 hover:border-gray-400">
                      予約一覧へ戻る
                    </Link>
                  </div>
                </div>
            </div>
        </div>
    </UserAuthenticatedLayout>
</template>
