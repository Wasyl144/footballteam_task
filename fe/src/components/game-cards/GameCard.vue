<template>
  <div class="game-card rounded-lg shadow-xl relative overflow-hidden w-full h-full" @click="handleClick" :class="{'disabled': isCardDisabled, 'is-clickable': clickable }">
    <div class="game-card__image h-full">
      <img :src="updatedImageSrc ? updatedImageSrc : imageSrc" :alt="name">
    </div>
    <span class="game-card__power absolute -top-[50px] -right-[50px] rounded-full bg-gradient-to-r from-yellow-300 to-50% to-red-500 w-[100px] h-[100px]">
      <span class="flex justify-center w-[25px] absolute left-4 bottom-4 text-black font-bold text-2xl">
        {{ power }}
      </span>
    </span>
    <span class="game-card__name flex items-center justify-center absolute bottom-0 left-0 w-full h-[50px] bg-gradient-to-t from-black via-50% via-black/75 to-transparent">
      <span class="font-bold text-transparent uppercase italic leading-none text-xl lg:text-2xl text-yellow-400">
        {{ name }}
      </span>
    </span>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

import { useDuelStore } from '@/stores/duelStore';


const duelStore = useDuelStore();

const props = defineProps({
  id: {
    type: Number,
    require: true
  },
  name: {
   type: String,
   require: true
  },
  power: {
    type: Number,
    require: true
  },
  image: {
    type: String,
    require: true
  },
  clickable: {
    type: Boolean,
    default: false
  }
});

const imagesLocation = import.meta.globEager('@/assets/images/cards/*.jpg');

const imageSrc = imagesLocation[`/src/assets/images/cards/${props.image}`]?.default;
const updatedImageSrc = ref(null);

watch(() => duelStore.selectedCard?.id, (newValue) => {
  if (newValue === props.id) {
    updatedImageSrc.value = imagesLocation[`/src/assets/images/cards/${duelStore.selectedCard?.image}`]?.default;
  }
});

const isCardDisabled = computed(() => duelStore.isCardDisabled(props.id));

const handleClick = () => {
  if (props.clickable) {
    duelStore.setIsCardSelected(true);
    duelStore.setSelectedCard(props);
  }
};
</script>

<style lang="scss" scoped>
.game-card {
  &__image {
    img {
      @apply w-full h-full;
      object-fit: cover;
      object-position: center;
    }
  }
  &__power {
    box-shadow: 0px 0 30px 5px #000;
  }
  &.disabled {
    @apply pointer-events-none;
  }
  &.is-clickable {
    @apply cursor-pointer transition ease-in-out duration-300;
    &:hover {
      transform: translateY(-15px);
    }
    &::before {
      content: ' ';
      @apply absolute left-0 top-0 w-full h-full bg-black/70 hidden;
      z-index: 1;
    }
    &.disabled {
      &::before {
        @apply block;
      }
    }
  }
}
</style>