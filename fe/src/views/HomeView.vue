<template>
  <TheHeader/>
  <section class="home-sec">
    <MainContainer>
      <BaseCard>
        <div class="flex flex-col-reverse xs:flex-row justify-center items-center xs:justify-between mb-4">
          <BaseTitle class="!mb-0"><h1>Your Cards</h1></BaseTitle>
          <div class="flex items-center">
            <template v-if="data && data.new_card_allowed">
              <BaseButton @click="getNewCard" className="bg-sky-500 hover:bg-sky-700 mb-4 mr-2 xs:mb-0">
                Get new card
              </BaseButton>
            </template>
            <BaseButton @click="startDuel" className="bg-emerald-500 hover:bg-emerald-700 mb-4 xs:mb-0">
              Start the duel
            </BaseButton>
          </div>
        </div>
        <template v-if="error">
          <p class="text-red-500 text-center md:text-lg">{{ error }}</p>
        </template>
        <template v-else>
          <template v-if="!isLoading && data">
            <GameCardsList :cards="data.cards" />
          </template>
          <template v-else>
            Loading...
          </template>
        </template>
      </BaseCard>
    </MainContainer>
  </section>
</template>

<script setup>
  import { onMounted } from 'vue';

  import { useRouter } from 'vue-router';

  import useFetch from '@/composables/useFetch';

  import { useHeaderStore } from "@/stores/headerStore";

  import TheHeader from '@/components/common/TheHeader.vue';
  import BaseCard from '@/components/BaseCard.vue';
  import BaseTitle from '@/components/BaseTitle.vue';
  import MainContainer from '@/components/MainContainer.vue';
  import GameCardsList from '@/components/game-cards/GameCardsList.vue';
  import BaseButton from '@/components/BaseButton.vue';

  const router = useRouter();
  const headerStore = useHeaderStore();
  const { data, error, fetchData, isLoading } = useFetch();

  onMounted(() => {
    getUserData();
  });

  const getUserData = async () => {
    await fetchData('/api/user-data');

    headerStore.updateUser(data.value);
  };
  const startDuel = async () => {
    const response = await fetchData('/api/duels','POST');

    if (response && response.status === 200) {
      router.push('/active-duel');
    } else {
      console.error('Failed to fetch data:', response);
    }
  }

  const getNewCard = async () => {
    try {
      const response = await fetchData('/api/cards', 'POST');

      if (response && response.status === 200) {
        await fetchData('/api/user-data');
      } else {
        console.error('Failed to fetch data:', response);
      }
    } catch (error) {
      console.error('Failed to fetch data:', error.message);
    }
  }
</script>