<script setup>
import AdministerAuthenticatedLayout from '@/Layouts/AdministerAuthenticatedLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue'
import Pagination from '@/Components/Pagination.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { format } from 'date-fns'
import { ref } from 'vue';

const props = defineProps({
  courses: Object,
  search: Object
});

const seach = useForm({
  name:props.search.name,
})

const sendSeach = () => {
  seach.get(route('administer.courses.index'))
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
                  <FlashMessage />
                  <div class="mt-4 flex justify-end">
                    <Link as="button" :href="route('administer.courses.create')"
                      class="mx-4 py-2 w-1/4 bg-blue-400 rounded-lg">
                      作成する
                    </Link>
                    </div>
                  <div class="mt-4 flex justify-center">
                      <table class="min-w-full">
                        <tbody>
                          <tr>
                            <td class="w-1/3 text-right px-4 py-2">
                              <label for="name">コース名</label>
                            </td>
                            <td class="w-2/3 px-4 py-2">
                              <input type="text" id="name" class="w-3/4 block border rounded-lg"
                                  v-model="seach.name" />
                            </td>
                          </tr>
                          <tr>
                            <td class="w-1/3 px-4 py-4">
                            </td>
                            <td class="w-2/3 px-4 py-4">
                              <button class="mx-4 py-2 w-1/2 bg-blue-400 rounded-lg"
                                  @click="sendSeach" :disabled="seach.processing">
                                検索
                              </button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                  </div>

                  <div v-if="courses.data.length == 0" class="mt-4 flex justify-center mb-4">
                    <p class="w-full text-center">コースがありません</p>
                  </div>
                  <div v-if="courses.data.length != 0" class="my-4 flex justify-center">
                    <table class="table-auto w-full m-4 text-left whitespace-no-wrap">
                      <thead>
                        <tr>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">コース名</th>
                          <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">実施時間</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="course in courses.data" :key="course.id">
                          <td class="px-4 py-3"> <a :href="route('administer.courses.show', {course:course.id})">
                            {{ course.name }}</a></td>
                          <td class="px-4 py-3"><a :href="route('administer.courses.show', {course:course.id})">
                            {{ course.runtime }}</a></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <Pagination class="my-6 flex justify-center" :links="courses.links" />
                </div>
            </div>
        </div>
    </AdministerAuthenticatedLayout>
</template>
