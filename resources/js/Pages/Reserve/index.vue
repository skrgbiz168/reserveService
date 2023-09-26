<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    startWeek:String,
    weeks: Array,
    reserves: Array,
});
const hours = Array.from({ length: 24 }, (_, i) => `${i}時`);

const reserve = (day, hour) => {
      alert(`Reserved for ${day} at ${hour}`);
    };

</script>

<template>
        <Head title="予約ページ" />
        <div
            class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
        >
        <table class="min-w-full w-auto">
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
                <button v-if="reserves[day + ' ' + index]" @click="reserve(day, hour)" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold m-1 rounded">
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
