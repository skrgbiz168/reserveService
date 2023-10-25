<script setup>
import AdministerAuthenticatedLayout from '@/Layouts/AdministerAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="管理者トップページ" />

    <AdministerAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">管理者トップページ</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-center">
                        <Link as="button" :href="route('administer.reserve.list')"
                          class="w-2/3 p-2 mt-4 text-center text-xl font-medium rounded-lg border bg-blue-400 border-gray-900 hover:border-gray-400">
                          予約一覧
                        </Link>
                    </div>
                    <div class="flex justify-center mb-4">
                        <Link as="button" :href="route('administer.courses.index')"
                          class="w-2/3 p-2 mt-4 text-center text-xl font-medium rounded-lg border bg-blue-400 border-gray-900 hover:border-gray-400">
                          コース一覧
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdministerAuthenticatedLayout>
</template>
