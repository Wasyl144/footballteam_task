<template>
  <div class="mb-4">
    <label class="block">
      <span class="block text-gray-700 text-sm font-bold mb-2">{{ label }}</span>
      <input
        :id="id"
        :type="type"
        :placeholder="placeholder"
        :value="modelValue"
        @input="updateValue"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
        :required="required"
      />
    </label>
  </div>
</template>

<script setup>
defineProps({
  modelValue: [String, Number],
  type: String,
  id: String,
  label: String,
  placeholder: String,
  required: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (event) => {
  emit('update:modelValue', event.target.value);
};
</script>
