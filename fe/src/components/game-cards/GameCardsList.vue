<template>
  <div class="game-cards-list grid gap-2 md:gap-4 grid-cols-2 sm:grid-cols-3 lg:grid-cols-5">
    <div class="game-cards-list__item z-1" 
         v-for="card in cards" 
         :key="card.id"      
    >
      <GameCard 
        :id="card.id"
        :name="card.name" 
        :power="card.power"
        :image="card.image" 
        :clickable="clickable"
      />
    </div>
  </div>
</template>

<script setup>
import GameCard from '@/components/game-cards/GameCard.vue';

defineProps({
  cards: Array,
  clickable: {
    type: Boolean,
    default: false
  }
});
</script>