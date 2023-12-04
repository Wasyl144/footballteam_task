<template>
  <TheHeader/>
  <section class="duels-sec">
    <MainContainer>
      <BaseCard>
        <BaseTitle><h1>Duels History</h1></BaseTitle>
        <template v-if="duelsError">
          <p class="text-red-500 text-center md:text-lg">{{ duelsError }}</p>
        </template>
        <template v-else>
          <DuelsList :duels="duels" />
        </template>
      </BaseCard>
    </MainContainer>
  </section>
</template>

<script setup>
import { onMounted } from 'vue';

import useFetch from '@/composables/useFetch';

import TheHeader from '@/components/common/TheHeader.vue';
import BaseCard from '@/components/BaseCard.vue';
import BaseTitle from '@/components/BaseTitle.vue';
import MainContainer from '@/components/MainContainer.vue';
import DuelsList from '@/components/duels/DuelsList.vue';

const { data: duels, error: duelsError, fetchData: fetchDuels } = useFetch();

onMounted(async () => {
  await fetchDuels('/api/duels');
});
</script>