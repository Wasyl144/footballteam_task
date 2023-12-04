import { defineStore } from 'pinia';
import useSessionStorage from '@/composables/useSessionStorage'; 

export const useHeaderStore = defineStore('header', {
  state: ()=>({
    username: useSessionStorage('username', ''),
    level: useSessionStorage('level', ''),
    levelPoints: useSessionStorage('levelPoints', '')
  }),
  actions: {
    updateUser(data) {
      this.username = data.username;
      this.level = data.level;
      this.levelPoints = data.level_points;
    },
  }
});