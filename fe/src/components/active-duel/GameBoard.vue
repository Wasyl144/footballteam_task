<template>
  <p class="text-center text-red-500 font-bold mb-2">Don't reload page while playing!</p>
  <BaseCard>
    <div class="game-board flex flex-wrap flex-col md:flex-row items-center justify-between">
      <div class="game-board__card-box">
        <div class="flex flex-wrap flex-col xs:flex-row items-center">
          <div class="p-3 flex items-center justify-center border-2 border-gray-600 border-dashed w-[256px] h-[360px] md:w-[180px] md:h-[290px] lg:w-[220px] lg:h-[340px] rounded-lg">
            <template v-if="duelStore.isCardSelected && duelStore.selectedCard">
              <GameCard
                :id="duelStore.selectedCard.id"
                :name="duelStore.selectedCard.name"
                :power="duelStore.selectedCard.power"
                :image="duelStore.selectedCard.image"
              />
            </template>
            <template v-else>
              <span>Selected card</span>
            </template>
          </div>
          <div class="pl-0 pt-4 xs:pt-0 xs:pl-4 lg:pl-8">
            <div class="flex flex-col min-w-[80px] lg:min-w-[100px] text-center xs:text-left">
              <div class="player-nickname lg:text-xl truncate">You</div>
              <div class="score text-3xl lg:text-5xl font-semibold">{{ yourPoints }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="game-board__game-info my-4 md:my-0">
        <span class="block text-center mb-4 text-xl">Round: <span class="text-rose-500 font-bold">{{ round }}</span></span>
        <TheCountdown :seconds="countdownTime" />
      </div>
      <div class="game-board__card-box">
        <div class="flex flex-wrap flex-col xs:flex-row-reverse md:flex-row items-center">
          <div class="pl-0 pb-4 xs:pb-0 xs:pl-4 md:pr-4 md:pl-0 lg:pr-8">
            <div class="flex flex-col-reverse xs:flex-col min-w-[80px] lg:min-w-[100px] text-center xs:text-left md:text-right">
              <div class="player-nickname lg:text-xl truncate">Opponent</div>
              <div class="score text-3xl lg:text-5xl font-semibold">{{ opponentPoints }}</div>
            </div>
          </div>
          <div class="p-3 flex items-center justify-center border-2 border-gray-600 border-dashed w-[256px] h-[360px] md:w-[180px] md:h-[290px] lg:w-[220px] lg:h-[340px] rounded-lg">
            <span>Selected card</span>
          </div>
        </div>
      </div>
    </div>
  </BaseCard>
</template>

<script setup>
import { useDuelStore } from '@/stores/duelStore';

import BaseCard from '@/components/BaseCard.vue';
import TheCountdown from '@/components/TheCountdown.vue';
import GameCard from '@/components/game-cards/GameCard.vue';

defineProps ({
  round: {
    type: Number,
    required: true,
  },
  yourPoints: {
    type: Number,
    required: true,
  },
  opponentPoints: {
    type: Number,
    required: true,
  },
  countdownTime: {
    type: Number,
    required: true,
  }
});

const duelStore = useDuelStore();

</script>