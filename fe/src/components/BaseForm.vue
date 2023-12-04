<template>
  <form class="form block w-full" @submit.prevent="emitEvent">
    <slot />
  </form>
</template>

<script setup>
const emit = defineEmits(['submit']);

const emitEvent = () => {
  emit('submit');
};
</script>
