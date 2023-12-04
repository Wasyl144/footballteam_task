<template>
  <button
    class="text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
    :class="[className ? className : defaultClass]"
    :type="type"
    @click="clickEvent"
  >
    <slot></slot>
  </button>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'button'
  },
  className: String
});

const emit = defineEmits(['click']);
const clickEvent = () => emit('click');

const defaultClass = 'bg-rose-500 hover:bg-rose-700';
</script>