<script setup>
import AdministerAuthenticatedLayout from '@/Layouts/AdministerAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { format } from 'date-fns'

defineProps({
  reserves: Object,
});

const formatDate = value => {
  const date = new Date(value);
  const minutes = date.getMinutes();
  // 分が0の場合とそれ以外でフォーマットを分ける
  return minutes === 0
    ? format(date, "yyyy年M月d日 H時")
    : format(date, "yyyy年M月d日 H時m分");
}
</script>

<template>
    <Head title="予約一覧" />

    <AdministerAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">予約一覧</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div v-if="reserves.length == 0" class="mt-4 flex justify-center">
                    <p class="w-full text-center">予約がありません</p>
                  </div>
                  <div v-if="reserves.length != 0" class="my-4 flex justify-center">
                    <table class="table-auto w-full m-4 text-left whitespace-no-wrap">
                      <thead>
                        <tr>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">登録者名</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始時間</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">終了時間</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="reserve in reserves" :key="reserve.id">
                          <td class="px-4 py-3">{{ reserve.user.name }}</td>
                          <td class="px-4 py-3">{{ formatDate(reserve.start_at) }}</td>
                          <td class="px-4 py-3">{{ formatDate(reserve.finish_at) }}</td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
            </div>
        </div>
    </AdministerAuthenticatedLayout>
</template>
