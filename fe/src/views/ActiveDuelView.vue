<template>
  <TheHeader/>
  <section class="home-sec">
    <MainContainer>
      <template v-if="duelStore.status === 'finished'">
        <BaseCard>
          <p class="text-center text-xl text-green-500">{{ finalMessage }}</p>
        </BaseCard>
      </template>
      <template v-else>
          <GameBoard
            :round="duelStore.round"
            :yourPoints="duelStore.yourPoints"
            :opponentPoints="duelStore.opponentPoints"
            :countdownTime="countdownTime"
          />
          <BaseCard>
            <BaseTitle><h1>Your Cards</h1></BaseTitle>
            <template v-if="!isLoading">
              <GameCardsList :cards="duelStore.cards" :clickable="true" />
            </template>
            <template v-else>
              Loading...
            </template>
          </BaseCard>
      </template>
    </MainContainer>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

import { useRouter } from 'vue-router';

import { useDuelStore } from '@/stores/duelStore';
import useFetch from '@/composables/useFetch';

import TheHeader from '@/components/common/TheHeader.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseTitle from '@/components/BaseTitle.vue';
import MainContainer from '@/components/MainContainer.vue';
import GameCardsList from '@/components/game-cards/GameCardsList.vue';
import GameBoard from '@/components/active-duel/GameBoard.vue';

const router = useRouter();

const { data, fetchData, isLoading } = useFetch();
const duelStore = useDuelStore();

const countdownTime = ref(15);
let intervalId = null;

const intervalTime = computed(() => countdownTime.value * 1000);

const finalMessage = computed(() => {
  if (duelStore.yourPoints > duelStore.opponentPoints) {
    return 'Koniec gry! Wygrałeś, gratulacje!';
  } else if (duelStore.yourPoints == duelStore.opponentPoints) {
    return 'Koniec gry! Remis!';
  } else {
    return 'Koniec gry! Niestety, przegrałeś!';
  }
});

const refreshDuel = async () => {
  if (!duelStore.isCardSelected) {
    console.log("You didn't choose any card, you get 0 points!");
  } else {
    await fetchData('/api/duels/action', 'POST', duelStore.selectedCard);
  }
  await fetchData('/api/duels/active');

  if (duelStore.selectedCard) {
    duelStore.disableCard(duelStore.selectedCard.id);

    duelStore.isCardSelected = false;
    duelStore.selectedCard = null;
  }

  if (data.value) {
    console.log(data.value, 'after refresh')
    setRound(data.value.round);
    setPoints(data.value.your_points, data.value.opponent_points);
    setCards(data.value.cards);
    setStatus(data.value.status);
  };
}

const setRound = (round) => {
  duelStore.round = round;
};

const setPoints = (yourPoints, opponentPoints) => {
  duelStore.yourPoints = yourPoints;
  duelStore.opponentPoints = opponentPoints;
};

const setCards = (cards) => {
  duelStore.cards = cards;
};

const setStatus = (status) => {
  duelStore.status = status;
};

onMounted(async () => {
  await fetchData('/api/duels/active');
  
  if (data.value) {
    setRound(data.value.round);
    setPoints(data.value.your_points, data.value.opponent_points);
    setCards(data.value.cards);
    setStatus(data.value.status);

    if (data.value.status === 'finished') {
      setTimeout(() => {
        router.push('/home');
      }, 3000);
    }
  };

  intervalId = setInterval(() => {
    refreshDuel();
  }, intervalTime.value);
});

onUnmounted(() => {
  clearInterval(intervalId);
});
</script>