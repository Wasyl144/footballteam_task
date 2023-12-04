<template>
  <header class="header fixed left-0 right-0 z-header bg-gray-800">
    <MainContainer>
      <div class="flex items-center h-[70px]">
        <div class="flex w-full items-center justify-between">
          <div class="header__user">
            <div class="header__user-name font-bold text-lg text-rose-500">{{ headerStore.username }}</div>
            <div class="header__user-level text-white">
              Level: <span class="font-bold pr-2 mr-2 border-r border-rose-500">{{ headerStore.level }}</span>
              <span>{{ headerStore.levelPoints }}</span>
            </div>
          </div>
          <div class="header__nav flex items-center">
            <div :class="isOpen ? 'absolute flex flex-col justify-center top-[66px] pt-2 pb-4 h-max bottom-0 right-0 transform translate-y-1 transition-transform duration-300 bg-gray-800 w-40' : 'hidden'" class="md:flex md:w-auto md:flex-row md:items-center md:h-auto md:relative md:transform-none md:top-0 md:p-0">
              <div class="header__nav-item px-2" v-for="link in navLinks" :key="link.name">
                <router-link :to="link.url" active-class="text-rose-500" class="header__nav-link w-full flex items-center justify-center h-10 hover:text-rose-500"
                :class="{ 'text-white': route.path !== link.url }">{{ link.name }}</router-link>
              </div>
              <div class="flex justify-center pl-2">
                <BaseButton @click="logOut">Log Out</BaseButton>
              </div>
            </div>
            <button @click="isOpen = !isOpen" type="button" class="block hover:text-rose-500 focus:text-rose-500 focus:outline-none md:hidden" :class="isOpen ? 'text-rose-500' : 'text-white'">
              <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                <path v-if="isOpen" fill-rule="evenodd" d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 1 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 1 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z"/>
                <path v-if="!isOpen" fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </MainContainer>
  </header>
</template>

<script setup>
import { ref } from "vue";

import { useRoute } from "vue-router";

import { useHeaderStore } from "@/stores/headerStore";
import useHeader from "@/composables/useHeader";

import BaseButton from '@/components/BaseButton.vue';
import MainContainer from '@/components/MainContainer.vue';

const route = useRoute();
const headerStore = useHeaderStore();

const isOpen = ref(false);

const { logOut } = useHeader();

let navLinks = [
  { name: 'Home', url: '/home' },
  { name: 'Duels', url: '/duels' },
]
</script>