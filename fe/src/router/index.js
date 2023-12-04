import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '@/views/LoginView.vue';
import HomeView from '@/views/HomeView.vue';
import DuelsView from '@/views/DuelsView.vue';
import ActiveDuelView from '@/views/ActiveDuelView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/home',
      name: 'home',
      component: HomeView
    },
    {
      path: '/duels',
      name: 'duels',
      component: DuelsView
    },
    {
      path: '/active-duel',
      name: 'activeDuel',
      component: ActiveDuelView
    },
  ]
});

export default router;
