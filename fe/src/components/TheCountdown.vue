<template>
  <div class="countdown flex justify-center items-center relative">
    <svg class="progress-ring" width="60" height="60">
      <circle
        class="progress-ring__circle"
        stroke="#d1d5db"
        stroke-width="2"
        fill="transparent"
        r="28"
        cx="30"
        cy="30"
      />
      <circle
        class="progress-ring__circle"
        stroke="#f43f5e"
        stroke-width="2"
        fill="transparent"
        :style="{ strokeDashoffset: progress, strokeDasharray: fullProgress }"
        r="28"
        cx="30"
        cy="30"
      />
    </svg>
    <span class="time text-xl font-bold absolute">{{ countdownTime }}</span>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({
  seconds: {
    type: Number,
    default: 10,
  },
});

const countdownTime = ref(props.seconds);

let intervalId = null;

onMounted(() => {
  intervalId = setInterval(() => {
    countdownTime.value--;

    if (countdownTime.value === 0) {
      countdownTime.value = props.seconds;
    }
  }, 1000);
});

onUnmounted(() => {
  clearInterval(intervalId);
});

const fullProgress = Math.PI * 28 * 2;

const progress = computed(() => {
  const progressPercent = countdownTime.value / props.seconds;
  
  return fullProgress * (1 - progressPercent);
});
</script>

<style lang="scss" scoped>
.progress-ring {
  transform: rotate(270deg);
  &__circle {
    stroke-linecap: round;
  }
}

.time {
  @apply absolute left-1/2 top-1/2;
  transform: translate(-50%, -50%);
}
</style>