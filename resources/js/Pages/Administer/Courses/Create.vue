<script setup>
import AdministerAuthenticatedLayout from '@/Layouts/AdministerAuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
});

const form = useForm({
  name: '',
  description:'',
  runTimeHour:0,
  runTimeMinute:0,
});

const stayTimeHour = Array.from({ length: 7 }, (_, i) => ({
  text: i + '時間',
  value: i ,
}))

const stayTimeMinute = Array.from({ length: 4 }, (_, i) => ({
  text: i * 15 + '分',
  value: i * 15,
}))

const sendForm = () => {
  form.post(route('administer.courses.store'))
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
                          <label for="name" class="w-full leading-7 text-sm text-gray-600">コース名</label>
                        </div>
                        <div class="w-3/4 flex items-center">
                          <input type="text" id="name" name="name" v-model="form.name"
                            class="ml-4 w-3/4 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>
                    <InputError class="flex justify-center" :message="form.errors.name" />
                    <div class="p-4 flex justify-center">
                        <div class="w-1/4 flex items-center text-right">
                          <label for="description" class="w-full leading-7 text-sm text-gray-600">説明</label>
                        </div>
                        <div class="w-3/4 flex items-center">
                          <textarea id="description" name="description" v-model="form.description"
                            class="ml-4 w-3/4 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                        </div>
                    </div>
                    <InputError class="flex justify-center" :message="form.errors.description" />
                    <div class="p-4 flex justify-center">
                        <div class="w-1/4 flex items-center text-right">
                          <label for="description" class="w-full leading-7 text-sm text-gray-600">実施時間</label>
                        </div>
                        <div class="w-3/4 flex items-center justify-start">
                          <select id="stayTime" class="w-2/5 ml-4 rounded-lg border"
                              v-model="form.runTimeHour">
                            <option v-for="hour in stayTimeHour" :key="hour.value" :value="hour.value">
                              {{ hour.text }}
                            </option>
                          </select>
                          <select id="stayTime" class="w-1/3 ml-2 rounded-lg border"
                              v-model="form.runTimeMinute">
                            <option v-for="minute in stayTimeMinute" :key="minute.value" :value="minute.value">
                              {{ minute.text }}
                            </option>
                          </select>
                        </div>
                    </div>
                    <InputError class="flex justify-center" :message="form.errors.runTime" />
                    <div class="p-4 flex justify-center">
                      <button class="flex mx-auto text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg"
                          @click="sendForm" :disabled="form.processing">
                          登録する
                      </button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </AdministerAuthenticatedLayout>
</template>
