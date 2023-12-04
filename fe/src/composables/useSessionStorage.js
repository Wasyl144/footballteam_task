import { ref, watch } from "vue";

export default function useSessionStorage(key, initialValue) {
  const storedValue = JSON.parse(sessionStorage.getItem(key));
  const state = ref(storedValue || initialValue);

  watch(state, (newValue) => {
    sessionStorage.setItem(key, JSON.stringify(newValue));
  });

  return state;
}