<template>
  <div class="duels-list overflow-auto border rounded-lg border-gray-200">
    <table class="min-w-full ">
      <thead class="bg-gray-200">
        <tr class="text-left text-sm md:text-base font-bold uppercase whitespace-nowrap">
          <th class="p-4">
            Player Name
          </th>
          <th class="p-4">
            Opponent Name
          </th>
          <th class="p-4">
            Result
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="duel in duels" :key="duel.id" class="text-sm whitespace-nowrap">
          <td class="px-4 py-2">
            {{ duel.player_name }}
          </td>
          <td class="px-4 py-2">
            {{ duel.opponent_name }}
          </td>
          <td class="px-4 py-2">
            <span class="inline-flex px-3 items-center justify-center rounded-lg h-[35px]" :class="duel.won === 1 ? 'bg-green-300' : 'bg-red-300'">
              {{ duel.won === 1 ? 'Won' : 'Defeat' }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  duels: Array
});
</script>
