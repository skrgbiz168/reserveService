<script setup>
import UserAuthenticatedLayout from '@/Layouts/UserAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue'
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

    <UserAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">予約一覧</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <FlashMessage />
                  <div v-if="reserves.length == 0" class="mt-4 flex justify-center">
                    <p class="w-full text-center">予約がありません</p>
                  </div>
                  <div v-if="reserves.length != 0" class="my-4 flex justify-center">
                    <div>
                      <div v-for="(reserve, index) in reserves" :key="reserve.id"
                          class="mt-4 p-2 border-2 border-gray-900">
                        <p>{{ index + 1 }}つ目</p>
                        <p>開始時間：{{ formatDate(reserve.start_at) }}</p>
                        <p>終了時間：{{ formatDate(reserve.finish_at) }}</p>
                        <hr v-if="index !== reserves.length - 1" />
                      </div>
                    </div>

                  </div>
                </div>
            </div>
        </div>
    </UserAuthenticatedLayout>
</template>
