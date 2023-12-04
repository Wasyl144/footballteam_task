<template>
  <div class="card rounded-lg shadow-xl bg-white p-4" @submit.prevent="emitEvent">
    <slot />
  </div>
</template>

<script setup>
const emit = defineEmits(['submit']);

const emitEvent = () => {
  emit('submit');
};
</script>

<style lang="scss" scoped>
.card {
  & + .card {
    @apply mt-4;
  }
}
</style>
