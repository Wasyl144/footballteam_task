<template>
  <section class="login-sec bg-gray-100">
    <MainContainer>
      <div class="flex flex-col items-center justify-center min-h-screen py-2">
        <div class="px-4 py-6 sm:p-8 max-w-md w-full bg-white rounded-xl shadow-md flex items-center space-x-4">
          <BaseForm @submit="submitForm">
            <BaseInput
              id="email"
              type="text"
              placeholder="E-mail"
              v-model="email"
              label="E-mail"
              required
            />
            <BaseInput
              id="password"
              type="password"
              placeholder="Password"
              v-model="password"
              label="Password"
              required
            />
            <BaseButton type="submit" class="w-full mt-1">Sign In</BaseButton>
          </BaseForm>
        </div>
      </div>
    </MainContainer>
  </section>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import Cookies from 'js-cookie';

import { useRouter } from 'vue-router';

import useFetch from '@/composables/useFetch';

import BaseForm from '@/components/BaseForm.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseButton from '@/components/BaseButton.vue';
import MainContainer from '@/components/MainContainer.vue';

const email = ref('');
const password = ref('');

const router = useRouter();

const { fetchData, status, data } = useFetch();
const submitForm = async () => {

  const userData = {
    email: email.value,
    password: password.value
  }

  await fetchData('/api/login', 'POST', userData);

  if (status.value === 200) {
    Cookies.set('token', data.value.token)
    router.push('/home');
  }
};

onMounted(async () => {
  await fetchData('/api/user-data');

  console.log(status)

  if (status.value === 200) {
    router.push('/home')
  }
})
</script>
