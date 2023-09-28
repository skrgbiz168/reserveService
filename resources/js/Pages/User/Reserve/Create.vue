<script setup>
import UserAuthenticatedLayout from '@/Layouts/UserAuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    start_at: String,
    stay_time: String,
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

                    <table class="min-w-full p-4 mt-4">
                      <tbody>
                        <tr>
                          <td class="w-1/2 text-right px-4 py-2">開始日時</td>
                          <td class="w-1/2 px-4 py-2">
                            {{ start_at }}
                          </td>
                        </tr>
                        <tr>
                          <td class="w-1/2 text-right px-4 py-2">使用時間</td>
                          <td class="w-1/2 px-4 py-2">
                            {{ stay_time +1 }} 時間
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </UserAuthenticatedLayout>
</template>
