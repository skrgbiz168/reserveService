<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    weeks: Array,
    reserves: Object,
    seach: Object,
});

const form = useForm({
    day: '',
    hour: '',
    stayTime: '',
});

const seach = useForm({
    week: props.seach.week,
    stayTime: props.seach.stayTime,
});

const stayTimeValue = Array.from({ length: 6 }, (_, i) => ({
  text: `${i + 1}時間`,
  value: i+1 ,
}));

const hours = Array.from({ length: 24 }, (_, i) => `${i}時`);

const reserve = (day, index) => {
    form.day = day
    form.hour = index
    form.stayTime = seach.stayTime

    form.post(route('reserve.checkAuth'))
};
const sendStayTime = () => {
  seach.get(route('reserve.index'))
}

const sendSubWeek = () => {
  if (seach.week !== null && seach.week !== 0) {
    seach.week -= 1
    seach.get(route('reserve.index'))
  }
}

const sendAddWeek = () => {
  if (seach.week == null || seach.week == 0) {
    seach.week = 1
  } else {
    seach.week += 1
  }
  seach.get(route('reserve.index'))
}
</script>

<template>
        <Head title="予約ページ" />
        <div
            class="relative mt-16 w-full md:w-3/4 md:mx-auto px-4 min-h-screen "
        >

        <div class="mt-4 flex justify-center">
            <table class="min-w-full">
              <tbody>
                <tr>
                  <td class="w-1/2 text-right px-4 py-2">
                    <label for="stayTime">使用時間</label>
                  </td>
                  <td class="w-1/2 px-4 py-2">
                    <select id="stayTime" class="w-3/4 rounded-lg border"
                        v-model="seach.stayTime" @change="sendStayTime">
                      <option v-for="hour in stayTimeValue" :key="hour.value" :value="hour.value">
                        {{ hour.text }}
                      </option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
        </div>

        <div class="w-full mt-4 flex justify-center">
          <button v-if="seach.week != null && seach.week != 0"
            class="w-1/3 text-center text-grey rounded-lg border border-gray-900 hover:border-gray-400"
            @click="sendSubWeek">
            前の一週間
          </button>
          <div v-if="seach.week == null || seach.week == 0" class="w-1/3"></div>
          <div class="w-1/3"></div>
          <button class="w-1/3 text-center text-grey rounded-lg border border-gray-900 hover:border-gray-400"
            @click="sendAddWeek">
            次の一週間
          </button>
        </div>
        <table class="min-w-full w-auto mt-4">
          <thead>
            <tr>
              <th class="w-1/12 border px-4 py-2"></th>
              <th v-for="day in weeks" :key="day" class="w-1/12 border px-4 py-2">{{ day }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(hour, index) in hours" :key="hour">
              <td class="w-1/12 border px-4 py-2">{{ hour }}</td>
              <td v-for="day in weeks" :key="day" class="w-1/12 border px-4 py-2">
                <button v-if="reserves[day + ' ' + index]" @click="reserve(day, index)" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold m-1 rounded">
                  ○
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
