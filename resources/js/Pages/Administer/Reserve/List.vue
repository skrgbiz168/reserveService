<script setup>
import AdministerAuthenticatedLayout from '@/Layouts/AdministerAuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { format } from 'date-fns'
import { ref } from 'vue';

const props = defineProps({
  reserves: Object,
  seach: Object
});

const seach = useForm({
  user_id:props.seach.user_id,
  user_name:props.seach.user_name,
  type:props.seach.type,
})

const seachType = ref([
      { value: 0, text: '予約予定' },
      { value: 1, text: '予約履歴' },
    ])

const sendSeach = () => {
  seach.get(route('administer.reserve.list'))
}

const formatDate = value => {
  const date = new Date(value);
  const minutes = date.getMinutes();
  // 分が0の場合とそれ以外でフォーマットを分ける
  return minutes === 0
    ? format(date, "M月d日 H時")
    : format(date, "M月d日 H時m分");
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

                  <div class="mt-4 flex justify-center">
                      <table class="min-w-full">
                        <tbody>
                          <tr>
                            <td class="w-1/2 text-right px-4 py-2">
                              <label for="user_id">ユーザーID</label>
                            </td>
                            <td class="w-1/2 px-4 py-2">
                              <input type="text" id="user_id" class="w-3/4 block border rounded-lg"
                                  v-model="seach.user_id" />
                            </td>
                          </tr>
                          <tr>
                            <td class="w-1/2 text-right px-4 py-2">
                              <label for="user_name">ユーザー名</label>
                            </td>
                            <td class="w-1/2 px-4 py-2">
                              <input type="text" id="user_name" class="w-3/4 block border rounded-lg"
                                  v-model="seach.user_name" />
                            </td>
                          </tr>
                          <tr>
                            <td class="w-1/2 text-right px-4 py-2">
                              <label for="stayTime">表示タイプ</label>
                            </td>
                            <td class="w-1/2 px-4 py-2">
                              <select id="stayTime" class="w-3/4 rounded-lg border"
                                  v-model="seach.type">
                                <option v-for="seach in seachType" :key="seach.value" :value="seach.value">
                                  {{ seach.text }}
                                </option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td class="w-1/2 px-4 py-4">
                            </td>
                            <td class="w-1/2 px-4 py-4">
                              <button class="mx-4 py-2 w-1/2 bg-indigo-400 rounded-lg"
                                  @click="sendSeach" :disabled="seach.processing">
                                検索
                          </button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                  </div>

                  <div v-if="reserves.data.length == 0" class="mt-4 flex justify-center">
                    <p class="w-full text-center">予約がありません</p>
                  </div>
                  <div v-if="reserves.data.length != 0" class="my-4 flex justify-center">
                    <table class="table-auto w-full m-4 text-left whitespace-no-wrap">
                      <thead>
                        <tr>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">登録者名</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始時間</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">終了時間</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="reserve in reserves.data" :key="reserve.id">
                          <td class="px-4 py-3">{{ reserve.user.name }}</td>
                          <td class="px-4 py-3">{{ formatDate(reserve.start_at) }}</td>
                          <td class="px-4 py-3">{{ formatDate(reserve.finish_at) }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <Pagination class="my-6 flex justify-center" :links="reserves.links" />
                </div>
            </div>
        </div>
    </AdministerAuthenticatedLayout>
</template>
