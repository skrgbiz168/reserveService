<script setup>
import AdministerAuthenticatedLayout from '@/Layouts/AdministerAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { nl2br } from '@/common.js'

const props = defineProps({
  course: Object
});

const displayTime = time => {
  var text = ''
  if (time >= 60) {
    text += time / 60 + '時間'
  }
  if (time % 60 != 0) {
    text += time % 60 + '分'
  }
  return text
}

</script>
<template>
    <Head title="コース作成" />

    <AdministerAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">コース作成</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg whitespace-nowrap">
                  <div class="flex-wrap -m-2 pb-4">
                    <div class="mt-4 p-4 flex justify-center">
                        <div class="w-1/4 flex items-center text-right">
                          <label for="name" class="w-full leading-7 text-sm text-gray-600">コース名 :</label>
                        </div>
                        <div class="w-3/4 flex items-center">
                          <p class="ml-4 w-3/4"> {{ course.name }}</p>
                        </div>
                    </div>
                    <div v-if="course.description" class="p-4 flex justify-center">
                        <div class="w-1/4 flex items-center text-right">
                          <label for="description" class="w-full leading-7 text-sm text-gray-600">説明</label>
                        </div>
                        <div class="w-3/4 flex items-center">
                          <div id="memo" v-html="nl2br(course.description)" class="ml-4 w-3/4 rounded text-base outline-none resize-none transition-colors"></div>
                        </div>
                    </div>
                    <div class="p-4 flex justify-center">
                        <div class="w-1/4 flex items-center text-right">
                          <label for="description" class="w-full leading-7 text-sm text-gray-600">実施時間</label>
                        </div>
                        <div class="w-3/4 flex items-center justify-start">
                          <p class="ml-4 w-3/4"> {{ displayTime(course.runtime) }}</p>
                        </div>
                    </div>
                    <div class="p-4 flex justify-center">
                      <Link as="button" :href="route('administer.courses.index')"
                        class="flex mx-auto text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">
                          一覧に戻る
                      </Link>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </AdministerAuthenticatedLayout>
</template>
